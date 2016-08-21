<?php
if (php_sapi_name() != 'cli')
   die();

include 'database.php';
include 'functions.php';

// Delete from agentlist database.

//$dbsqlo="select id, name, agentid, callagent, updatetime,result from agentcx where result='<span class=\"label label-success\"> Order Submitted </span>'";
$dbsqlo="SELECT l.id, l.name, l.agentid, l.callagent, l.updatetime, l.result, l.ordersub
FROM   agentcx l
WHERE l.id NOT IN (SELECT eid from (
        SELECT id, eid,action , MAX( updatetime ) AS maxu, updatetime
		FROM agentlog
		WHERE action = 'sorder'
		GROUP BY eid DESC
        )lo )
        AND l.ordersub<>'0000-00-00 00:00:00'";
$dm_stmt = new DB_Sql;
if ($dm_stmt->query($dbsqlo)) {
        while($dm_stmt->next_record()){
                $name = $dm_stmt->f("name");
                $eid = $dm_stmt->f("id");
                $callagent = $dm_stmt->f("callagent");
                $callagent_id = $dm_stmt->f("agentid");
		$utime = $dm_stmt->f("updatetime");
		$utimeep = strtotime($utime) + 60;
//		$uutime = date("Y-m-d H:i:s",$utimeep);
		$uutime = $dm_stmt->f("ordersub");

		$event = 'Agent '.$callagent.' ( '.$callagent_id.' ) submitted an order for '.$name.' ( '.$eid.' ).';
		addorderlog($eid,$callagent_id,$uutime,$event);
		
	}
}

?>

