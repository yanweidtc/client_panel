<?php
include("database.php");
if (isset($_POST["reply"]) && isset($_POST["id"]))
{
	$ip=getenv ("REMOTE_ADDR");
	//66.49.254.56
	if ($ip != "66.49.254.56")
	{
	  print "<b><i>Forbidden</i></b>";
	  exit;
	}

	//parsing return data
        $data_array = array();
        $lines = explode("EOL_SIG\n",$_POST["reply"]);
        $titles = explode("\t\t",$lines[0]);
        for($i=1;$i<(count($lines)-1);$i++){
                $data = explode("\t\t",$lines[$i]);
                for($j=0;$j<(count($data));$j++){
                        $data_array[$i-1][$titles[$j]]=$data[$j];
                }
        }
//	var_dump($data_array);

	// create agentID array
	$agentID_array = array();
	foreach($data_array as $ag){
		$agentID_array[]=$ag["agentid"];
	}

	// do the clean up
	$dcheck = new DB_Sql;
	$dcheck->query("select * from agenthistory where 1");
	if( $dcheck->num_rows() > 0){
		while($dcheck->next_record()){
			$dagent_id=$dcheck->f("agentid");
			$his_id=$dcheck->f("id");
			if($dagent_id!=""){
				$explode_id = explode($dagent_id,"-");
				$mainagent= $explode_id[0];
				if(($mainagent != -1) && (!in_array($mainagent,$agentID_array))){
					$deletion = new DB_Sql;
					$deletion->query("delete from agenthistory where id='".$his_id."'");
					$deletion->free();
				}
			}
		}
	}
	
	$dcheck->free();

	foreach($data_array as $agenthis){
		$time = $agenthis["time"];
		$agent_id = $agenthis["agentid"];
		$revenue = $agenthis["revenue"];

			
		$acheck_sql = "select * from agenthistory where agentid = '".$agent_id."' and time= '".$time."'";

		$acheck = new DB_Sql;
		$acheck->query($acheck_sql);
		if( $acheck->num_rows() > 0){
			//update
			$acheck->next_record();
			$hisid = $acheck->f("id");

			$aupdate = new DB_Sql;
			if($aupdate->query("update agenthistory set revenue= '".$revenue."' where id='$hisid'")){}
			$aupdate->free();


		}else{		// insert new

			$insert_stmt = new DB_Sql;
			
			$sql = "INSERT INTO agenthistory (agentid, time , revenue ) VALUES ('".$agent_id."', '".$time."', '".$revenue."')";
			// Insert to database. 
			if ($insert_stmt->query($sql)) {

			}

			$insert_stmt->free();
		}
		
		$acheck->free();


	}// foreach

}else{
                print "No info passed in!";
}

?>
