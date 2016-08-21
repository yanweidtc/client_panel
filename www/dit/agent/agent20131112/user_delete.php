<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
        $user_id = $_SESSION['user_id'];
        $stmt = new DB_Sql;
        if ($stmt->query("SELECT id, type, username, agentID, monthlyfee, onetimeRFee, activeaccounts, addonfee, addonpackages, pendinginvoice, update_time, mainAgent from agents where id = '$user_id'")) {
                $stmt->next_record();
                $uname = $stmt->f("username");
                $agent_id = $stmt->f("agentID");
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
        }
   if($utype == "main"){
   }else if($utype == "sub"){
           echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
           exit;
   }

   if(isset($_POST['uid'])) {
        $uid = filter_var($_POST['uid'], FILTER_VALIDATE_INT);
        if($uid){
		$d_stmt = new DB_Sql;
                // Delete from members database. 
                if ($d_stmt->query("DELETE FROM agents WHERE id = '".$uid."' and type = 'sub'")) {
                }else{
                }
		$d_stmt->free();
        //header('Location: ./user.php');
        }
   }else{
        die('Invalid Id!');
   }
}else{
           echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
}

?>
