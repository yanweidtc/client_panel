<?php
if (php_sapi_name() != 'cli')
   die();

include 'database.php';
include 'functions.php';

// Delete from agentlist database.
$agid = $_SESSION['agent_id'];
$dm_stmt = new DB_Sql;
if ($dm_stmt->query("DELETE FROM agentcx WHERE DATE_ADD(updatetime, INTERVAL 19 MINUTE) < NOW()")) {
}

?>

