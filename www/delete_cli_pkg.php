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
      if ($stmt = $mysqli->prepare("SELECT cid,email,username, company, address, country, phone, ccname,ccnumber,ccexpiry,update_time FROM members WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime); // get variables from result.
           $stmt->fetch();

	   list($cus_ccmonth,$cus_ccyear)=explode("/",$cus_ccexpiry);
        }}

if(isset($_POST['cid'],$_POST['pid'],$_POST['aid'],$_POST['uid']) && $_POST['cid']!="" && $_POST['pid']!="" && $_POST['aid']!="" && $_POST['uid']!="") {
             require_once "encryption.php";
	 $dccid = $converter->decode($_POST['cid']);
         $dacid = $converter->decode($_POST['aid']);
         $dpid = $converter->decode($_POST['pid']);
         $duid = $converter->decode($_POST['uid']);

	$data_str="cid:".$dccid."+pid:".$dpid."+aid:".$dacid."+uid:".$duid;
 
	$newencrypt2 = new rc4crypt;

	$rc4pass="3vcud2m9l48cdfbhzb6s2xaym2m5uruv8q4";
	$data_str= $newencrypt2->endecrypt ("$rc4pass", "$data_str", $case);

	$queuestr="updateadd";
	      // add name/pwd combination to the queue
      if ($insert_stmt = $mysqli->prepare("INSERT INTO members_login (name, data, processed,aid,type,time,pid) VALUES (?, ?, ?, ?, 4, NOW(),?)")) {
           $insert_stmt->bind_param('sssis', $cus_name,$data_str,$queuestr, $user_id, $_POST['pid']);
           // Execute the prepared query.
           $insert_stmt->execute();
           //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
      }else{
        echo "Database fail";
      }

	   $insert_stmt->close();
	
	header('Location: https://client.zazeen.com/main_cli_pkg.php?id='.$duid);	

} else {
   log_bad("Invalid params passed to pkg_updater.",$mysqli,$cus_name);
}
} else {
   echo 'You are not authorized to access this page, please login. <a href="cli_login.php">Back</a> <br/>';

}


?>
