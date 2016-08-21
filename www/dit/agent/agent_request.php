<?php
include 'database.php';
include 'functions.php';
require_once "class.rc4crypt.php";

sec_session_start();
if(login_check() == true) {
   global $n;
      $user_id = $_SESSION['user_id'];
        $stmt = new DB_Sql;
        if ($stmt->query("SELECT id, type, username, agentID, monthlyfee, onetimeRFee, activeaccounts, addonfee, addonpackages, pendinginvoice, update_time, mainAgent,subAgent,newsignup,billable from agents where id = '$user_id'")) {
                $stmt->next_record();
                $monthfee = $stmt->f("monthlyfee");
                $activeaccount = $stmt->f("activeaccounts");
                $addonfee = $stmt->f("addonfee");
                $addonpackages = $stmt->f("addonpackages");
                $pdis = $stmt->f("pendinginvoice");
                $update_time = $stmt->f("update_time");
                $onetimeRF = $stmt->f("onetimeRFee");
                $agent_id = $stmt->f("agentID");
                $uname = $stmt->f("username");
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
                $newsignup = $stmt->f("newsignup");
                $ubillable = $stmt->f("billable");

                $subagent= $stmt->f("subAgent");

        $today = date('M');
        $month = date('F');
        $fullday = date("Y-m-d");

        if($utype == "subagent"){
                $agent_id = $magent."-".$user_id;
        }
        function encry($input){
                $pp = 'Mf4QmQHKxFbYFGsLdVdbBMTUGR4CzeWvtcq';
                $newencrypt = new rc4crypt;
                $ret= $newencrypt->endecrypt("$pp","$input","en");
                return $ret;
        }
        function decry($input){
                $pp = 'Mf4QmQHKxFbYFGsLdVdbBMTUGR4CzeWvtcq';
                $newencrypt = new rc4crypt;
                $ret= $newencrypt->endecrypt("$pp","$input","de");

                return htmlspecialchars($ret);
        }

	//print "catchi1\n";
	$reqarr=array(1,2,3,4);
	if (isset($_POST["req"]) && isset($_POST["agentid"])){
			if($_POST["agentid"]!=$agent_id){
				echo "id does not match!";	
				exit;
			}

			$reqtype=$_POST["req"];
			if(!in_array($reqtype,$reqarr)){
				echo "invalid req type!";	
				exit;
			}

			$acheck_sql = "select * from agent_request where agentid='".$agent_id."' and ( status='init' or status='process' ) and type='".$reqtype."'";
			$acheck = new DB_Sql;
			$acheck->query($acheck_sql);
			if( $acheck->num_rows() > 0){
				//do nothing
			}else{		// insert new

				$insert_stmt = new DB_Sql;

				$sql = "INSERT INTO agent_request ( agentid,type,time,status ) VALUES ('".$agent_id."','".$reqtype."',NOW(),'init' )";
				// Insert to database. 
				if ($insert_stmt->query($sql)) {

				}else{
					print "failed\n";
				}

				$insert_stmt->free();
			}
			
	}else{
			print "No info passed in!";
	}
    }// if query
}else{
	echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}

?>
