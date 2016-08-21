<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';

$stmt = new DB_Sql;
$stmt->query("SELECT id, agentID, mainAgent from agents");

	while($stmt->next_record()){
		$agent_id = $stmt->f("agentID");
		$editid = $stmt->f("id");
		$ag_id="";
		if($agent_id=="-1"){
			$ag_id=$stmt->f("mainAgent")."-".$editid;
		}else{
			$ag_id=$stmt->f("agentID");
		}

	        echo "update set ag_id='$ag_id' where id='$editid'\n";	
		$check = new DB_Sql;
		$check->query("update agents set ag_id='$ag_id' where id='$editid'");
		$check->free();
	}

$stmt->free();

?>
