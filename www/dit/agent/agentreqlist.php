<?php

$ip=getenv ("REMOTE_ADDR");
//66.49.254.56
if ($ip != "66.49.254.56")
{
  print "<b><i>Forbidden</i></b>";
  exit;
}


include 'database.php';
include 'functions.php';
require_once "class.rc4crypt.php";


	$ret="";
	$processid=array();
	$stmt = new DB_Sql;
	if ($stmt->query("SELECT id,agentid from agent_request where type=1 and status='init'")) {
		while($stmt->next_record()){
			$agid = $stmt->f("agentid");
			$processid[]=$stmt->f("id");
			$ret.=$agid.",";
		}
	}

	foreach($processid as $proid){
			$aupdate = new DB_Sql;
			if($aupdate->query("update agent_request set status='process' where id = '$proid'")){
			}else{
				print "failed\n";
			}
			$aupdate->free();
	}

	print $ret;
?>

