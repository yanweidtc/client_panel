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
      if ($stmt = $mysqli->prepare("SELECT cid,email,truename, company, address, country, phone, phone2, ccname,ccnumber,ccexpiry,update_time FROM members WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone, $cus_phone2, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime); // get variables from result.
           $stmt->fetch();

	   list($cus_ccmonth,$cus_ccyear)=explode("/",$cus_ccexpiry);
        }}


   echo page_head(true,false,$cus_name);
   echo '<script type="text/javascript">
                var break_link=true;
         </script>';

echo '<script type="text/javascript" src="sha512.js"></script>
	<script type="text/javascript" src="forms.js"></script>';

if(isset($_POST['email']) && $_POST['email']!="") {


	$data_str="cid:".$ccid."+email:".$_POST['email']."+oldemail:".$cus_email;
 
	$newencrypt2 = new rc4crypt;

	$rc4pass="3vcud2m9l48cdfbhzb6s2xaym2m5uruv8q4";
	$data_str= $newencrypt2->endecrypt ("$rc4pass", "$data_str", $case);

	$queuestr="updateem";
	      // add name/pwd combination to the queue
      if ($insert_stmt = $mysqli->prepare("INSERT INTO members_login (name, data, processed,aid,type,time) VALUES (?, ?, ?, ?, 3, NOW())")) {
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

   echo '  <legend>Update Email</legend>
	<div class="span6" style="margin-left:25%">
	<form class="form-horizontal" action="update_email.php" method="post" name="email_form">
	<fieldset>
	   <div class="control-group">
	   <label class="control-label" for="cusname">Name:</label>
	     <div class="controls">
		<p class="form-control-static" id="cusname">'.$cus_name.'</p>
	     </div>
	   </div>
	   <div class="control-group">
	   <label class="control-label" for="email">Email:</label>
	     <div class="controls">
		<input type="text" id="email" name="email" value="'.$cus_email.'"/>
	     </div>
	   </div>';
   echo	'
	   </br>
	   <div class="control-group">
		<button value="submit" class="btn btn-primary pull-right" onclick="emailhash(this.form, this.form.email);">Update Email</button>
	   </div>
	</fieldset>
	</form>
	</div>';
}
   echo page_foot($ajax);
} else {
   log_bad("Tried direct access update_email",$mysqli);
}


?>
