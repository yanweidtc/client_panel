<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';

sec_session_start();
if(login_check() == true) {
	$ip=getenv ("REMOTE_ADDR");
//	print "====================IP:".$ip;



	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.zazeen.com/cgi-bin/orders.cgi/Order_iptv.html");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_POST, true);

	$data = $_POST;
	$data['func']='Submit';

//	print "<pre>".print_r($data,true)."</pre>";

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$output = curl_exec($ch);
	$info = curl_getinfo($ch);

	print $output;
//	print "<pre>".print_r($output,true)."</pre>";
//	print "<pre>".print_r($info,true)."</pre>";

	curl_close($ch);


} else {
	$ip=getenv ("REMOTE_ADDR");
	$tlog = new DB_Sql;
	$tlog->query("INSERT INTO bad_attempts (ip, msg, time) VALUES ('$ip', 'Try to directly access post.php', UTC_TIMESTAMP())");

        echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}



?>
