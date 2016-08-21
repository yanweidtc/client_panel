<?php
include 'database.php';
include 'functions.php';
sec_session_start();

// Delete from agentlist database.
$agid = $_SESSION['agent_id'];
$dm_stmt = new DB_Sql;
if ($dm_stmt->query("DELETE FROM agentcx WHERE agentid like '%$agid%'")) {
}

// Unset all session values
$_SESSION = array();
// get session parameters 
$params = session_get_cookie_params();
// Delete the actual cookie.
setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
// Destroy session
session_destroy();
header('Location: ./login.php');

?>

