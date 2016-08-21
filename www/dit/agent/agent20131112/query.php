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

/*        print "here\n";
        print "ID=".$_POST["id"]."\n";
        print $_POST["reply"]."\n";
*/
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
		$agentID_array[]=$ag["agentID"];
	}

	// do the clean up
	$dcheck = new DB_Sql;
	$dcheck->query("select * from agents where 1");
	if( $dcheck->num_rows() > 0){
		while($dcheck->next_record()){
			$dagent_id=$dcheck->f("agentID");
			if(($dagent_id != -1) && (!in_array($dagent_id,$agentID_array))){
				$deletion = new DB_Sql;
				$deletion->query("delete from agents where agentID='".$dagent_id."'");
				$deletion->free();
			}
		}
	}
	
	$dcheck->free();

	foreach($data_array as $agent){
		$aemail = $agent["email"];
		$agent_id = $agent["agentID"];

			// Create a random salt
			$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			// Create salted password (Careful not to over season)
			$apassword = hash('sha512', $agent["password"].$random_salt);
			$username = $agent["name"];
			$mfee = $agent["monthlyfee"];
			$afee = $agent["addonfee"];
			$activeac = $agent["activeaccount"];
			$addons = $agent["addonpackages"];
			$pinv = $agent["pendinginvoice"];
			$onetimeRF = $agent["onetimeRF"];

		$acheck = new DB_Sql;
		$acheck->query("select * from agents where agentID = '".$agent_id."'");
		if( $acheck->num_rows() > 0){
			$acheck->next_record();
			//  update
			$agid = $acheck->f("id");
			$old_password=$acheck->f("password");
			$salt=$acheck->f("salt");
		
			$check_password = hash('sha512', $agent["password"].$salt);
			// if password modified
			if($old_password == $check_password){
				$random_salt=$salt;
				$apassword=$old_password;
			}			

			$aupdate = new DB_Sql;
			if($aupdate->query("update agents set username = '".$username."', email = '".$aemail."', agentID='".$agent_id."', password = '".$apassword."', salt='".$random_salt."', monthlyfee='".$mfee."', pendinginvoice='".$pinv."', activeaccounts='".$activeac."', addonfee='".$afee."', addonpackages='".$addons."', onetimeRFee='".$onetimeRF."', update_time=NOW() where id = '$agid'")){}
			$aupdate->free();

		}else{		// insert new

			$insert_stmt = new DB_Sql;

			$sql = "INSERT INTO agents (username, email, agentID, password, salt, monthlyfee, pendinginvoice, activeaccounts, addonfee, addonpackages, onetimeRFee ,update_time, type ) VALUES ('".$username."', '".$aemail."', '".$agent_id."', '".$apassword."', '".$random_salt."', '".$mfee."', '".$pinv."', '".$activeac."', '".$afee."', '".$addons."', '".$onetimeRF."', NOW(), 'main')";
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
