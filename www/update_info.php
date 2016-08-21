<?php
  include_once('./js/header.php');
  $ajax = false;

include './db_connect.php';
include './functions.php';
require "class.rc4crypt.php";



sec_session_start($mysqli);
if(login_check($mysqli,false) == true) {


   global $n; 

      $user_id = $_SESSION['user_id'];
      if ($stmt = $mysqli->prepare("SELECT cid,email,truename, company, address, country, phone, phone2, cctype, ccname,ccnumber,ccexpiry,update_time,agent FROM members WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone, $cus_phone2, $cus_cctype, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime, $cus_agent); // get variables from result.
           $stmt->fetch();

	   list($cus_ccmonth,$cus_ccyear)=explode("/",$cus_ccexpiry);
        }}


   echo page_head(true,false,$cus_name);
   echo '<script type="text/javascript">
                var break_link=true;
         </script>';

echo '<script type="text/javascript" src="sha512.js"></script>
	<script type="text/javascript" src="forms.js"></script>';

if(isset($_POST['cctype'],$_POST['ccname'],$_POST['ccnumber'],$_POST['ccmonth'], $_POST['ccyear'], $_POST['email'], $_POST['phone'], $_POST['phone2']) && $_POST['ccnumber']!="" && $_POST['email']!="" && $_POST['phone']!="") {

	// Phone and Email
	$data_str="cid:".$ccid."+phone:".$_POST['phone']."+phone2:".$_POST['phone2']."+email:".$_POST['email'];

	$ccflag="N";
	if( $_POST['cctype']!= $cus_cctype || $_POST['ccname'] != $cus_ccname || $_POST['ccmonth'] != $cus_ccmonth || $_POST['ccyear'] != $cus_ccyear ){
		$ccflag="Y";
	}

	$data_str.="+cctype:".$_POST['cctype']."+ccname:".$_POST['ccname']."+ccnumber:".$_POST['ccnumber']."+ccmonth:".$_POST['ccmonth']."+ccyear:".$_POST['ccyear']."+oldccnumber:".$cus_ccname."+ccflag:".$ccflag."";
 
	$newencrypt2 = new rc4crypt;

	$rc4pass="3vcud2m9l48cdfbhzb6s2xaym2m5uruv8q4";
	$data_str= $newencrypt2->endecrypt ("$rc4pass", "$data_str", $case);

	$queuestr="updateinfo";
	      // add name/pwd combination to the queue
      if ($insert_stmt = $mysqli->prepare("INSERT INTO members_login (name, data, processed,aid,type,time) VALUES (?, ?, ?, ?, 1, NOW())")) {
           $insert_stmt->bind_param('sssi', $cus_name,$data_str,$queuestr, $user_id);
           // Execute the prepared query.
           $insert_stmt->execute();
           //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
      }else{
        echo "Database fail";
      }

	   $insert_stmt->close();

	header('Location: ./main_cli_list.php');	

} else {

   echo '  <legend>Update Profile</legend>
	<div class="span6" style="margin-left:25%">
	<form class="form-horizontal" action="update_info.php" method="post" name="cc_form">
	<fieldset>
	   <div class="control-group">
           <label class="control-label" for="cusname">Name:</label>
             <div class="controls">
                <p class="form-control-static" id="cusname" name="cusname">'.$cus_name.'</p>
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="phone">Phone:</label>
             <div class="controls">
                <input type="text" id="phone" name="phone" value="'.$cus_phone.'"/>
             </div>
           </div>
   	   <div class="control-group">
           <label class="control-label" for="phone2">Phone2:</label>
             <div class="controls">
                <input type="text" id="phone2" name="phone2" value="'.$cus_phone2.'"/>
                <span class="help-block">Formats: 000-000-0000, 0000000000.</span>
             </div>
           </div>
	   <div class="control-group">
           <label class="control-label" for="email">Email:</label>
             <div class="controls">
                <input type="text" id="email" name="email" value="'.$cus_email.'"/>
             </div>
           </div>';
	if($cus_agent=="billagent" || $cus_agent=="shipagent"){
echo'	   <div class="dishide" style="display:none">';
	}else{
echo'	   <div class="dishide">';
	}
echo'	   <div class="control-group">
	   <label class="control-label" for="cctype">Credit Card Type:</label>
	     <div class="controls">
		<select name="cctype">
			<option value="Visa" ';
			if($cus_cctype == "Visa"){ echo 'selected="selected"';}
			echo '>Visa</option>
			<option value="Mastercard" ';
                        if($cus_cctype == "Mastercard"){ echo 'selected="selected"';}
                        echo '>Mastercard</option>
			<option value="Amex" ';
                        if($cus_cctype == "Amex"){ echo 'selected="selected"';}
                        echo '>Amex</option>
			<option value="Discover" ';
                        if($cus_cctype == "Discover"){ echo 'selected="selected"';}
                        echo '>Discover</option>
		</select>
	     </div>
	   </div>
	   <div class="control-group">
	   <label class="control-label" for="ccname">Name on Credit Card:</label>
	     <div class="controls">
		<input type="text" id="ccname" name="ccname" value="'.$cus_ccname.'"/>
	     </div>
	   </div>
	   <div class="control-group">
	   <label class="control-label" for="ccnumber">Credit Card Number:</label>
	     <div class="controls">
		<input type="text" id="ccnumber" name="ccnumber" value="'.$cus_ccnumber.'"/>
	     </div>
	   </div>
	   <div class="control-group">
	   <label class="control-label" for="ccexpiry">Credit Card Expiry Date:</label>
	     <div class="controls">
			<select name="ccmonth" class="span1">
			<option>'.$cus_ccmonth.'</option>
			<option>--------</option>
			  <option>1</option>
			  <option>2</option>
			  <option>3</option>
			  <option>4</option>
			  <option>5</option>
			  <option>6</option>
			  <option>7</option>
			  <option>8</option>
			  <option>9</option>
			  <option>10</option>
			  <option>11</option>
			  <option>12</option>
			</select>
			/
			<select name="ccyear" class="span1">
				<option>'.$cus_ccyear.'</option>
				<option>--------</option>';
			
	$dy=date("y");
	for($i=0;$i<30;$i++){
		echo '<option>'.($dy+$i).'</option>'."\n";
	}

	echo		'	</select>
	     </div>
	   </div>
	   </div>
		<input type="hidden" id="oldccnumber" name="oldccnumber" value="'.$cus_ccnumber.'"/>
	   </br>
	   <div class="control-group">
		<button value="submit" class="btn btn-primary pull-right" onclick="infohash(this.form, this.form.phone, this.form.phone2, this.form.email, this.form.cctype, this.form.ccnumber, this.form.ccyear,this.form.ccmonth,this.form.oldccnumber);">Update Info</button>
	   </div>
	</fieldset>
	</form>
	</div>';
}
   echo page_foot($ajax);
} else {
	log_bad("Tried direct access update_cc",$mysqli);
}


?>
