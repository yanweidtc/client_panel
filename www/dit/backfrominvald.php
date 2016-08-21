<?php
if (php_sapi_name() != 'cli')
   die();

include 'database.php';
include 'functions.php';

// Delete from agentlist database.

//$dbsqlo="select id, name, agentid, callagent, updatetime,result from agentcx where result='<span class=\"label label-success\"> Order Submitted </span>'";
$dbsqlo="SELECT l.id, l.name, l.agentid, l.callagent, l.updatetime, l.result, l.ordersub
FROM   agentcx l
WHERE l.status='invald'";
$dm_stmt = new DB_Sql;
if ($dm_stmt->query($dbsqlo)) {
        while($dm_stmt->next_record()){
                $name = $dm_stmt->f("name");
                $eid = $dm_stmt->f("id");
                $callagent = $dm_stmt->f("callagent");
                $callagent_id = $dm_stmt->f("agentid");
		$utime = $dm_stmt->f("updatetime");
		
		$uutime=date("Y-m-d H:i:s");

		$callagent_id=10086;
		$event = 'Agent AutoRobot ( 10086 ) bring '.$name.' ( '.$eid.' ) back from invalid number pool.';
		//addorderlog($eid,$callagent_id,$uutime,$event);

		$resstr='<span class="label label-danger"> Retry - Invalid Number </span>';
		//Update
		$susql="UPDATE agentcx set status='init',updatetime=NOW(),result='$resstr' where id='$eid' limit 1";
		$sudb=new DB_Sql;
		if ($sudb->query($susql)){
		}else{
		}
		$sudb->free();
		
		
	}
}

?>

