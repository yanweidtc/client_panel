<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';

sec_session_start();
if(login_check() == true) {
   global $n;
        $user_id = $_SESSION['user_id'];
        $stmt = new DB_Sql;
        $stmt->query("SELECT id, type, username, agentID from agents where id = '$user_id'");
        $stmt->next_record();
		$agent_id = $stmt->f("agentID");
                $uname = $stmt->f("username");
                $utype = $stmt->f("type");

   if($utype != "main"){
	echo 'You are not authorized to access this page. <br/>'; 
   }else{

if(isset($_POST['username'],$_POST['email'], $_POST['p'])) {
   $username = $_POST['username'];
   $email = $_POST['email'];
   $password = $_POST['p']; // The hashed password.

        // Create a random salt
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        // Create salted password (Careful not to over season)
        $password = hash('sha512', $password.$random_salt);

        // Insert to database. 
	$insert_stmt = new DB_Sql;
        if ($insert_stmt->query("INSERT INTO agents (username, email, agentID, password, salt, type, mainAgent) VALUES ('".$username."', '".$email."', '-1', '".$password."', '".$random_salt."', 'sub', '".$agent_id."')")) {
           $insert_stmt->free();
        }
        header('Location: ./main.php');

} else {
   echo page_head(true,true,$uname);

   $rstar = '&nbsp;<font color="red">*</font>';


   echo '<script type="text/javascript" src="sha512.js"></script>
         <script type="text/javascript" src="forms.js"></script>';

   echo '<script type="text/javascript" src="./js/jsDatePick.jquery.min.1.3.js"></script>';

   echo '<script type="text/javascript">
		window.onload = function(){
			new JsDatePick({
				useMode:2,
				target:"ACTIVATIONDATE",
				dateFormat:"%Y-%m-%d"
				/*selectedDate:{                                This is an example of what the full configuration offers.
					day:5,                                          For full documentation about these settings please see the full version of the code.
					month:9,
					year:2006
				},
				yearsRange:[1978,2020],
				limitToToday:false,
				cellColorScheme:"beige",
				dateFormat:"%m-%d-%Y",
				imgPath:"img/",
				weekStartDay:1*/
			});
		};
	</script>';



   echo '  <legend>Order Form</legend>
        <div class="span12" style="margin-left:25%">
        <form class="form-horizontal" action="register.php" method="post" name="reg_form">
        <fieldset>
	   <div class="control-group">
             <label class="control-label" for="province">Province:'.$rstar.'</label>
             <div class="controls">
		<select id="province" name="province">
                    <option value="ON">Ontario</option>
                    <option value="QC">Quebec</option>
                </select>
             </div>
           </div>
	   <div class="control-group">
             <label class="control-label" for="packageterm">Package Term:'.$rstar.'</label>
             <div class="controls">
		<select id="packageterm" name="packageterm">
                    <option value="1 Month Term">1 Month Term</option>
                </select>
             </div>
           </div>
	   <div class="control-group">
             <label class="control-label" for="packagetype">IPTV Package Type:'.$rstar.'</label>
             <div class="controls">
		<select id="packagetype" name="packagetype">
                    <option value="basic">Basic $(49.95)</option>
                </select>
             </div>
           </div>
	   <div class="control-group">
             <label class="control-label" for="">Addon channels:</label>
             <div class="controls">
			<div name=onaddons id=onaddons style="display:block">
			<input name=pkg1001 id=pkg1001 type=checkbox value="1"  >Bell Addon: $9.95 p/month<br>
			<input name=pkg1002 id=pkg1002 type=checkbox value="1"  >Blue Ant Addon: $0.99 p/month<br>
			<input name=pkg1003 id=pkg1003 type=checkbox value="1"  >ZoomerMedia Addon: $0.75 p/month<br>
			<input name=pkg1004 id=pkg1004 type=checkbox value="1"  >Astral English Addon: $4.99 p/month<br>
			<input name=pkg1005 id=pkg1005 type=checkbox value="1"  >Astral French Addon: $2.99 p/month<br>
			<input name=pkg1011 id=pkg1011 type=checkbox value="1"  >TVA Addon: $2.99 p/month<br>
			<input name=pkg1007 id=pkg1007 type=checkbox value="1"  >Super Ecran Addon: $12.99 p/month<br>
			<input name=pkg1008 id=pkg1008 type=checkbox value="1"  >Standalone Addon: ichannel (Issue Channel) $0.99<br>
			<input name=pkg1009 id=pkg1009 type=checkbox value="1"  >The Movie Network Package $12.99<br>
			<input name=pkg1010 id=pkg1010 type=checkbox value="1"  >Standalone Addon: Sportsnet World $29.95<br>
			<br>
			</div>
			<div name=qcaddons id=qcaddons style="display:none">
			<input name=pkg1014 id=pkg1014 type=checkbox value="1"  >Bell Addon: $9.95 p/month<br>
			<input name=pkg1015 id=pkg1015 type=checkbox value="1"  >Blue Ant Addon: $0.99 p/month<br>
			<input name=pkg1016 id=pkg1016 type=checkbox value="1"  >ZoomerMedia Addon: $0.75 p/month<br>
			<input name=pkg1017 id=pkg1017 type=checkbox value="1"  >Astral English Addon: $4.99 p/month<br>
			<input name=pkg1019 id=pkg1019 type=checkbox value="1"  >Super Ecran Addon: $12.99 p/month<br>
			<input name=pkg1020 id=pkg1020 type=checkbox value="1"  >Standalone Addon: ichannel (Issue Channel) $0.99<br>
			<input name=pkg1021 id=pkg1021 type=checkbox value="1"  >The Movie Network Package $12.99<br>
			<input name=pkg1022 id=pkg1022 type=checkbox value="1"  >Standalone Addon: Sportsnet World $29.95<br>
			<br>
			</div>
             </div>
           </div>
	   <div class="control-group">
             <label class="control-label" for="provider">Current Internet Service Provider:'.$rstar.'</label>
             <div class="controls">
		<select id="provider" name="provider">
			<option value="--select one--">--select one--</option>
			<option value="acanac">acanac</option>
			<option value="distibutel">distributel</option>
			<option value="xinflix">xinflix</option>
                </select>
             </div>
           </div>
	   <div class="control-group">
             <label class="control-label" for="numbox">Number of Required Set Top Box Units:'.$rstar.'</label>
             <div class="controls">
		<select id="numbox" name="numbox">
			<option value="--select one--">--select one--</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
                </select>
             </div>
           </div>
	   <div class="control-group">
             <label class="control-label" for="stbunit">Preferred STB Unit (IPTV Streaming Device):'.$rstar.'</label>
             <div class="controls">
		<select id="stbunit" name="stbunit">
			<option value="WNC $75.00 +  9.95 Shipping fee">WNC $75.00 + 9.95 Shipping fee</option>
			<option value="Entone $100.00 +  9.95 Shipping fee(Not Available)">Entone $75(Regular:$100.00) + 9.95 Shipping fee</option>
			<option value="Amino $150.00 +  9.95 Shipping fee(Not Available)" disabled>Amino $150.00 + 9.95 Shipping fee(Not available)</option>
                </select>
             </div>
           </div>
	   <div class="control-group">
             <label class="control-label" for="ACTIVATIONDATE">Preferred Activation Date(YYYY/mm/dd):</label>
             <div class="controls">
			<input type = "text" id="ACTIVATIONDATE" name = "ACTIVATIONDATE" size = "10" maxlength = "66" value = "2014-01-17" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="email">Email:</label>
             <div class="controls">
                <input type="text" id="email" name="email" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="password">Password:</label>
             <div class="controls">
                <input type="password" id="password" name="password" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="repassword">Confirm Password:</label>
             <div class="controls">
                <input type="password" id="repassword" name="repassword" />
             </div>
           </div>
           </br>
           <div class="control-group">
                <button value="submit" class="btn btn-primary pull-right" onclick="reghash(this.form, this.form.password, this.form.repassword, this.form.email,this.form.username);">Create Sub Account</button>
           </div>
        </fieldset>
        </form>
        </div>';
   echo page_foot($ajax);
}

  }// if utype
} else {
	echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}



?>
