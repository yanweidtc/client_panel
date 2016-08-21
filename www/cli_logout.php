<?php
  include_once('./js/header.php');
  $ajax = false;

include './db_connect.php';
include './functions.php';
sec_session_start();
if(login_check($mysqli,false) == true) {

   if(isset($_POST['cid'])) {
        $cid = filter_var($_POST['cid'], FILTER_VALIDATE_INT);
	if($cid){
	   if($s_stmt = $mysqli->prepare("SELECT id FROM members_pkg WHERE mid = ?")) {
		$s_stmt->bind_param('i', $cid);
		$s_stmt->execute();
		$s_stmt->store_result();

		if($s_stmt->num_rows > 0){
			$s_stmt->bind_result($aid);
			while($s_stmt->fetch()){
				// Delete from comments database. 
				if ($d_stmt = $mysqli->prepare("DELETE FROM members_comments WHERE aid = ?")) {    
				   $d_stmt->bind_param('i', $aid);
				   // Execute the prepared query.
				   $d_stmt->execute();
				   $d_stmt->close();
				   //printf("Error: %s.\n", $d_stmt->sqlstate);
				}else{
				}

				// Delete from pkg database. 
				if ($c_stmt = $mysqli->prepare("DELETE FROM members_pkg WHERE id = ?")) { 
				   $c_stmt->bind_param('i', $aid);
				   // Execute the prepared query.
				   $c_stmt->execute();
				   $c_stmt->close();
				   //printf("Error: %s.\n", $c_stmt->sqlstate);
				}else{
				}
			}// fetch
	       }// if num_rows
	}// if select aid


	// Delete from members database. 
	if ($dm_stmt = $mysqli->prepare("DELETE FROM members WHERE id = ?")) {    
	   $dm_stmt->bind_param('i', $cid);
	   // Execute the prepared query.
	   $dm_stmt->execute();
	   $dm_stmt->close();
	   //printf("Error: %s.\n", $d_stmt->sqlstate);
	}else{
	}
      }//if cid
	header('Location: ./cli_login.php');	
   }else{
	die('Invalid Id!');
   }
}else{
	echo 'You are not authorized to access this page, please login. <br/>';
}


?>
