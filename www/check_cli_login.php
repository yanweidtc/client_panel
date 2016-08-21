<?php
include 'db_connect.php';
include 'functions.php';

//sec_session_start($mysqli);


function insertion_callback($name,$password,$qqid,$counter,$mysqli){
      // first check if the provided name email combo is valid
	if ($qqstmt = $mysqli->prepare("SELECT processed,valid FROM members_login WHERE id = ?")) {
          $qqstmt->bind_param('i', $qqid);
          $qqstmt->execute(); // Execute the prepared query.
          $qqstmt->store_result();

           if($qqstmt->num_rows > 0) { // If queue found
             $qqstmt->bind_result($q_proc,$q_valid);
             $qqstmt->fetch();
	     if($q_proc == "done" && $q_valid == "N"){
		log_attempt($qqid,$mysqli);
		die("Invalid");
	     }
	     if($q_proc == "skip"){
                die("Skip");
             }	     
           }else{       // if queue not found
		die("QueueError");
           }
        }else{
                die("DBError");
        }
	



      // retrieve id of the queue
        if ($stmt = $mysqli->prepare("SELECT id FROM members WHERE username = ? AND qid = ?")) {
          $stmt->bind_param('si', $name, $qqid);
          $stmt->execute(); // Execute the prepared query.
          $stmt->store_result();

           if($stmt->num_rows > 0) { // If member exists
             $stmt->bind_result($mid); // get variables from result.
             $stmt->fetch();
		if(login($name, $password,$qqid, $mysqli, false) == true) {	// try login sessions
			die("success");
		}else{
			log_attempt($qqid,$mysqli);
			die("LoginError");
		}
           }else{	// if member reg not processed yet
		sleep(1);	// delay for 1 sec
		if($counter < 50){
			insertion_callback($name,$password,$qqid,($counter+1),$mysqli);
		}else{
			die("Timeout");
		}
	   }
        }else{
	        die("DBError");
	}
}

if(isset($_POST['name'],$_POST['p'],$_POST["qqid"])){
// filter name/email
$name=preg_replace("/[^a-zA-Z0-9_\-\. ]/","",$_POST['name']);
$password=preg_replace("/[^a-zA-Z0-9_\-\.]/","",$_POST['p']);
$qqid=preg_replace("/[^0-9]/","",$_POST["qqid"]);


if (strlen($name)<3 || strlen($name)>42)
        die("NameError");
if (strlen($password)<2 )
        die("PasswordError");


insertion_callback($name,$password,$qqid,0,$mysqli);


//insertion_callback("katie griffin","katieg198@hotmail.com","f59b88a95f93bec70d09cb6185c46bdfc49d5a92c947a5966d8e2e882570d9deb7e6494d676342670c1abd2536c5c03cf23fb84de506e6eec1e1629cd320d029",39,0,$mysqli);
}else{
	log_bad("Direct access to check script",$mysqli,$_POST['name']?$_POST['name']:"");
}
?>
