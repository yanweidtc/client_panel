<?php
include 'database.php';
include 'functions.php';
sec_session_start(); // Our custom secure way of starting a php session. 
 
if(isset($_POST['email'], $_POST['password'])) { 
   $email = $_POST['email'];
   $password = $_POST['password'];
   if(login($email, $password) == true) {
      // Login success
      //echo 'Success: You have been logged in!';
      header('Location: ./main.php');
   } else {
      // Login failed
      header('Location: ./login.php?error=1');
   }
} else { 
   // The correct POST variables were not sent to this page.
   echo 'Invalid Request';
}
?>
