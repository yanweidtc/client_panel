<?php

include 'functions.php';
include 'db_connect.php';
sec_session_start($mysqli);

//delete all user data
if(login_check($mysqli,false) == true) {

        //$cid = filter_var($_POST['cid'], FILTER_VALIDATE_INT);
	$cid = $_SESSION['user_id'];

	echo $cid;

                                // Delete from pkg database.
                                if ($c_stmt = $mysqli->prepare("DELETE FROM members_pkg WHERE mid = ?")) {
                                   $c_stmt->bind_param('i', $cid);
                                   // Execute the prepared query.
                                   $c_stmt->execute();
                                   $c_stmt->close();
                                   //printf("Error: %s.\n", $c_stmt->sqlstate);
                                }else{
                                }

        // Delete from members database.
        if ($dm_stmt = $mysqli->prepare("DELETE FROM members WHERE id = ?")) {
           $dm_stmt->bind_param('i', $cid);
           // Execute the prepared query.
           $dm_stmt->execute();
           $dm_stmt->close();
           //printf("Error: %s.\n", $d_stmt->sqlstate);
        }else{
        }

	// Unset all session values
	$_SESSION = array();
	// get session parameters 
	$params = session_get_cookie_params();
	// Delete the actual cookie.
	setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	// Destroy session
	session_destroy();
//	header('Location: ./cli_login.php');
	header('Location: http://www.zazeen.com/');


}else{
	echo 'You are not authorized to access this page or your session has been timed out, please login. <a href="cli_login.php">Back</a> <br/>';

}

?>
