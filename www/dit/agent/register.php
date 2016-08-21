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
        $stmt->query("SELECT id, type, username, agentID, mainAgent, subtoggle from agents where id = '$user_id'");
        $stmt->next_record();
		$agent_id = $stmt->f("agentID");
                $uname = $stmt->f("username");
                $utype = $stmt->f("type");
                $subtoggle = $stmt->f("subtoggle");
		$magent = $stmt->f("mainAgent");
		$u_id = $stmt->f("id");

   if($utype == "subuser"){
	echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
	exit;
   }else{

if(isset($_POST['username'],$_POST['email'], $_POST['p'],$_POST['type'])) {
   $username = $_POST['username'];
   $email = $_POST['email'];
   $phone = $_POST['phone'];
   $password = $_POST['p']; // The hashed password.
   $type= $_POST['type'];


   // TODO add filters for those values
   $onetimeRF = $_POST['onetimeRF']; 
   $monthlyfee = $_POST['monthlyfee'];
   $addonfee = $_POST['addonfee'];


                // check two day boundaries
                $monthlyday = filter_var($_POST['monthlyday'], FILTER_VALIDATE_INT);
                $onetimeday = filter_var($_POST['onetimeday'], FILTER_VALIDATE_INT);
                $cancelday = filter_var($_POST['cancelday'], FILTER_VALIDATE_INT);
                if(!$monthlyday || !$onetimeday || !$cancelday){
                   echo 'Invalid Cashable Days! <a href="user.php">Back</a> <br/>';
                   exit;
                }



   $sub_id=-1;
   if($utype=="subagent" && $type=="subuser"){
	$agent_id=$magent;
	$sub_id=$u_id;
	   $onetimeRF = ""; 
	   $monthlyfee = "";
	   $addonfee = "";
   }

   if($utype=="main" && $type=="subuser"){
        $sub_id=0;
	   $onetimeRF = ""; 
	   $monthlyfee = "";
	   $addonfee = "";
   }

   if($utype!="main" && $type=="subagent"){
	echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
	exit;	
   }
        // Create a random salt
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        // Create salted password (Careful not to over season)
        $password = hash('sha512', $password.$random_salt);


	$check = new DB_Sql;
	$check->query("select * from agents where email='".$email."'");
	if($check->num_rows()>0){
		//echo "Sorry, this email has already been used...";
		//sleep(1);
		header('Location: ./register.php?error=1');
		//exit;
	}else{


		// Insert to database. 
		$insert_stmt = new DB_Sql;
		if ($insert_stmt->query("INSERT INTO agents (username, email, agentID, password, salt, type, mainAgent, subAgent, onetimeRFee, monthlyfee, addonfee, update_time, phone, monthlyday, onetimeday, cancelday) VALUES ('".$username."', '".$email."', '-1', '".$password."', '".$random_salt."', '".$type."', '".$agent_id."', '".$sub_id."', '".$onetimeRF."', '".$monthlyfee."', '".$addonfee."', NOW(), '".$phone."', '".$monthlyday."', '".$onetimeday."', '".$cancelday."' )")) {
		   $insert_stmt->free();
		}
		header('Location: ./main.php');
	}

} else {
   echo page_head(true,true,$uname);

   echo '<script type="text/javascript" src="sha512.js"></script>
         <script type="text/javascript" src="forms.js"></script>';

   echo '<script type="text/javascript">
	$(document).ready(function(){
        		boothUpdate();
		$( "#type" ).change(function() {
        		boothUpdate();
    		});
	});

		function boothUpdate() {
		    if( $( "#type" ).val()==="subagent"){
			$("#onetimeRFdiv").show();
			$("#monthlyfeediv").show();
			$("#addonfeediv").show();
                        $("#onetimedaydiv").show();
                        $("#monthlydaydiv").show();
                        $("#canceldaydiv").show();
		    }
		    else {
			$("#onetimeRFdiv").hide();
			$("#monthlyfeediv").hide();
                        $("#onetimedaydiv").hide();
                        $("#monthlydaydiv").hide();
			$("#addonfeediv").hide();
                        $("#canceldaydiv").hide();
		    }
		}

	</script>';


if(isset($_GET['error']) && $_GET['error']){
		print '<div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Failed!</strong> Email is already used.
                </div>';
}



   echo '  <legend>Admin Area</legend>
        <div class="span6" style="margin-left:25%">
        <form class="form-horizontal" action="register.php" method="post" name="reg_form">
        <fieldset>
           <div class="control-group">
           <label class="control-label" for="type">Usertype:</label>
             <div class="controls">
                <select id="type" name="type">';
if($utype == "main" && $subtoggle=="Y")
    echo                '<option value="subagent">sub-agent</option>';
    echo               '<option value="subuser">sub-user</option>
    		</select>
             </div>
           </div>';
/*
	   <div class="control-group" id="subagentdiv" style="display:none">
	   <label class="control-label" for="subagent">Adding for Sub-agent:</label>
             <div class="controls">
                <select id="subagent" name="subagent">';
    echo                '<option value="-1">-select-one-</option>';
	$sub_stmt = new DB_Sql;
        $sub_stmt->query("SELECT id, type, username from agents where mainAgent = '$agent_id' and type = 'subagent'");
        while($sub_stmt->next_record()){
		$subag_id = $sub_stmt->f("id");
                $subag_uname = $sub_stmt->f("username");		
    echo                '<option value="'.$subag_id.'">'.$subag_uname.'</option>';
	}
	$sub_stmt->free();

    echo         '</select>
             </div>
           </div>
*/
    echo	'<div class="control-group">
             <label class="control-label" for="username">Username:</label>
             <div class="controls">
                <input type="text" id="username" name="username" />
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
           <div class="control-group">
           <label class="control-label" for="phone">Phone:</label>
             <div class="controls">
                <input type="text" id="phone" name="phone" />
             </div>
           </div>
	   <div class="control-group" id="onetimeRFdiv">
           <label class="control-label" for="onetimeRF">One-time Referral Fee:</label>
             <div class="controls">
                <input type="text" id="onetimeRF" name="onetimeRF" value="0.00"/>
             </div>
           </div>
           <div class="control-group" id="onetimedaydiv">
           <label class="control-label" for="onetimeday">One-time Cashable Days:</label>
             <div class="controls">
                <input type="text" id="onetimeday" name="onetimeday" value="60"/>
             </div>
           </div>
           <div class="control-group" id="monthlyfeediv">
           <label class="control-label" for="monthlyfee">Referral Monthly Fee:</label>
             <div class="controls">
                <input type="text" id="monthlyfee" name="monthlyfee" value="0.00"/>
             </div>
           </div>
           <div class="control-group" id="monthlydaydiv">
           <label class="control-label" for="monthlyday">Monthly Cashable Days:</label>
             <div class="controls">
                <input type="text" id="monthlyday" name="monthlyday" value="60"/>
             </div>
           </div>
           <div class="control-group" id="canceldaydiv">
           <label class="control-label" for="cancelday">Cancellation Charge-back Days:</label>
             <div class="controls">
                <input type="text" id="cancelday" name="cancelday" value="90"/>
             </div>
           </div>

	   <div class="control-group" id="addonfeediv">
           <label class="control-label" for="addonfee">Add-on fee:</label>
             <div class="controls">
                <input type="text" id="addonfee" name="addonfee" value="0"/> %
             </div>
           </div>

           <br>
           <div class="control-group">
                <button value="submit" class="btn btn-primary pull-right" onclick="userhash(this.form, this.form.password, this.form.repassword, this.form.email,this.form.username, this.form.type, this.form.subagent);">Create Sub Account</button>
           </div>
        </fieldset>
        </form>
        </div>';
   echo page_foot($ajax);
}

  }// if utype
} else {
	echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br>';
}



?>
