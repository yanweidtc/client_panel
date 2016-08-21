<?php
include 'db_connect.php';
include 'functions.php';

sec_session_start();

function insertion_callback($name,$email,$password,$qqid,$counter,$mysqli){
      // first check if the provided name email combo is valid
	if ($qqstmt = $mysqli->prepare("SELECT processed,valid FROM members_login WHERE id = ?")) {
          $qqstmt->bind_param('i', $qqid);
          $qqstmt->execute(); // Execute the prepared query.
          $qqstmt->store_result();

           if($qqstmt->num_rows > 0) { // If queue found
             $qqstmt->bind_result($q_proc,$q_valid);
             $qqstmt->fetch();
	     if($q_proc == "done" && $q_valid == "N"){
		die("Invalid");
	     }	     
           }else{       // if queue not found
		die("QueueError");
           }
        }else{
                die("DBError");
        }
	



      // retrieve id of the queue
        if ($stmt = $mysqli->prepare("SELECT id FROM members WHERE username = ? AND email = ?")) {
          $stmt->bind_param('ss', $name, $email);
          $stmt->execute(); // Execute the prepared query.
          $stmt->store_result();

           if($stmt->num_rows > 0) { // If member exists
             $stmt->bind_result($mid); // get variables from result.
             $stmt->fetch();
		if(login($name, $password, $mysqli, false) == true) {	// try login sessions
			die("success");
		}else{
			die("LoginError");
		}
           }else{	// if member reg not processed yet
		sleep(1);	// delay for 1 sec
		if($counter < 15){
			insertion_callback($name,$email,$password,$qqid,($counter+1),$mysqli);
		}else{
			die("Timeout");
		}
	   }
        }else{
	        die("DBError");
	}
}

// filter name/email
$name=preg_replace("/[^a-zA-Z0-9_\-\. ]/","",$_POST['name']);
$email=preg_replace("/[^a-zA-Z0-9_\-\.@]/","",$_POST['email']);
$qqid=preg_replace("/[^0-9]/","",$_POST["qqid"]);
$password=$_POST['p'];


if (strlen($name)<3 || strlen($name)>42)
        die("NameError");
if (strlen($email)<5 || !preg_match("/@/",$email))
        die("EmailError");


insertion_callback($name,$email,$password,$qqid,0,$mysqli);


//insertion_callback("katie griffin","katieg198@hotmail.com","f59b88a95f93bec70d09cb6185c46bdfc49d5a92c947a5966d8e2e882570d9deb7e6494d676342670c1abd2536c5c03cf23fb84de506e6eec1e1629cd320d029",39,0,$mysqli);

?>
