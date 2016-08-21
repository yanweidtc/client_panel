<?
if (php_sapi_name() != 'cli')
   die();

require_once 'database.php';
require_once 'functions.php';

$clean = new DB_Sql;
$clean->query("DELETE from agent_cache where updatetime < DATE_SUB(NOW(),INTERVAL 1 WEEK)");
$clean->query("OPTIMIZE TABLE agent_cache ");
$clean->query("OPTIMIZE TABLE agentcx ");
$clean->query("OPTIMIZE TABLE agentlog ");
$clean->query("OPTIMIZE TABLE agents ");
$clean->free();


?>
