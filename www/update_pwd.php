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
      if(isset($_GET['pkg'])){
          $pkg_id = $_GET['pkg'];
      }else if(isset($_POST['pkg'])){
          $pkg_id = $_POST['pkg'];
      }
      if ($stmt = $mysqli->prepare("SELECT cid,email,truename, company, address, country, phone, phone2, cctype, ccname,ccnumber,ccexpiry,update_time FROM members WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone, $cus_phone2, $cus_cctype, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime); // get variables from result.
           $stmt->fetch();

        }}


   echo page_head(true,false,$cus_name);


	if ($qstmt2 = $mysqli->prepare("SELECT password,acid FROM members_pkg WHERE id = ? and mid = ? limit 1")) {
                  $qstmt2->bind_param('ii', $pkg_id,$user_id);
                  $qstmt2->execute(); // Execute the prepared query.
                  $qstmt2->store_result();

                   if($qstmt2->num_rows > 0) { // If queue found
                     $qstmt2->bind_result($epwd,$acid);
                     $qstmt2->fetch();

                    }else{//if found
				log_bad("Tried direct access update_pwd",$mysqli);
                    }

        }else{//if fail db
		log_bad("Tried direct access update_pwd",$mysqli);	  
        }

   echo '<script type="text/javascript">
                var break_link=true;
         </script>';

echo '<script type="text/javascript" src="sha512.js"></script>
	<script type="text/javascript" src="forms.js"></script>';

if(isset($_POST['bpwd']) && $_POST['bpwd']!="") {

	// Phone and Email
	$data_str="cid:".$ccid."+pwd:".$_POST['bpwd']."+pkgid:".$pkg_id."+acid:".$acid;

//	print $data_str;
	$newencrypt2 = new rc4crypt;

	$rc4pass="3vcud2m9l48cdfbhzb6s2xaym2m5uruv8q4";
	$data_str= $newencrypt2->endecrypt ("$rc4pass", "$data_str", $case);

	$queuestr="updatepwd";
	      // add name/pwd combination to the queue
      if ($insert_stmt = $mysqli->prepare("INSERT INTO members_login (name, data, processed,aid,type,time) VALUES (?, ?, ?, ?, 7, NOW())")) {
           $insert_stmt->bind_param('sssi', $cus_name,$data_str,$queuestr, $user_id);
           // Execute the prepared query.
           $insert_stmt->execute();
           //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
      }else{
        echo "Database fail";
      }

	   $insert_stmt->close();


	header('Location: ./main_cli_list.php');
// 	print success	

} else {

   echo '  <legend>Change Password</legend>
	<div class="span6" style="margin-left:25%">
	<form class="form-horizontal" action="update_pwd.php" method="post" name="cc_form">
	<fieldset>
	   <div class="control-group">
	   <label class="control-label" for="bpwd">New Password:</label>
	     <div class="controls">
			<input type="password" name="bpwd" id="bpwd" value=""/>';
	echo		'
	     </div>
	   </div>
	   <div class="control-group">
           <label class="control-label" for="cbpwd">Confirm New Password:</label>
             <div class="controls">
                        <input type="password" name="cbpwd" id="cbpwd" value=""/>
	     </div>
           </div>
	   </br>
	   <input type="hidden" name="pkg" id="pkg" value="'.$pkg_id.'"/>
	   <div class="control-group">
		<button value="submit" class="btn btn-primary pull-right" onclick="bpwdhash(this.form, this.form.bpwd, this.form.cbpwd);">Submit</button>
	   </div>
	</fieldset>
	</form>
	</div>';
}
   echo page_foot($ajax);
} else {
	log_bad("Tried direct access update_pwd",$mysqli);
}


?>
