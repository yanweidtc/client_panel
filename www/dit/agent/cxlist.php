<?php
include("database.php");
include("datediff.php");
require_once("mondiff.php");
require_once "class.rc4crypt.php";

$ip=getenv ("REMOTE_ADDR");
//66.49.254.56
if ($ip != "66.49.254.56")
{
  print "<b><i>Forbidden</i></b>";
  exit;
}

//print "catchi1\n";
if (isset($_POST["reply"]) && isset($_POST["id"]))
{

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



	//$realparse = urldecode($_POST["reply"]);
	$realparse = $_POST["reply"];
	//print $realparse;
	//parsing return data
        $data_array = array();
        $lines = explode("EOL_SIG\n",$realparse);
        $titles = explode("\t\t",$lines[0]);
        for($i=1;$i<(count($lines)-1);$i++){
                $data = explode("\t\t",$lines[$i]);
                for($j=0;$j<(count($data));$j++){
                        $data_array[$i-1][$titles[$j]]=$data[$j];
                }
        }
	//print "catch\n";
//	var_dump($data_array);
//	print sizeof($data_array)."\n";
	
	$uidarr = array();

	$ctr = 0;
	foreach($data_array as $agentcx){

		print $ctr."==\n";
		$ctr++;
/*		$name = addslashes($agentcx['name']);
		$email = addslashes($agentcx['email']);
		$phone = addslashes($agentcx['phone']);
		$phone2 = addslashes($agentcx['phone2']);
		$address = addslashes($agentcx['address']);
		$country = addslashes($agentcx['country']);
		$agentid = addslashes($agentcx['agentid']);
		$username = addslashes($agentcx['username']);
		$password = addslashes($agentcx['password']);
		$startdate = addslashes($agentcx['startdate']);
		$enddate = addslashes($agentcx['enddate']);
		$suspend = addslashes($agentcx['suspend']);
		$status = addslashes($agentcx['status']);
		$invoice = addslashes($agentcx['invoice']);*/

		$name = addslashes(encry($agentcx['name']));
                $email = addslashes(encry($agentcx['email']));
                $phone = addslashes(encry($agentcx['phone']));
                $phone2 = addslashes(encry($agentcx['phone2']));
                $address = addslashes(encry($agentcx['address']));
                $country = addslashes(encry($agentcx['country']));
                $agentid = addslashes($agentcx['agentid']);
                $username = addslashes(encry($agentcx['username']));
                $password = addslashes(encry($agentcx['password']));
                $startdate = addslashes(encry($agentcx['startdate']));
                $enddate = addslashes(encry($agentcx['enddate']));
                $suspend = addslashes(encry($agentcx['suspend']));
                $status = addslashes(encry($agentcx['status']));
                $aid = addslashes(encry($agentcx['aid']));
                $invoice = addslashes(encry($agentcx['invoice']));

		if(!in_array($agentid,$uidarr)){
			$uidarr[]=$agentid;
		}

		$acheck_sql = "select * from agentcx where username = '".$username."' and agentid='".$agentid."'";
		$acheck = new DB_Sql;
		$acheck->query($acheck_sql);
		if( $acheck->num_rows() > 0){
			$acheck->next_record();
                        //  update
                        $agcxid = $acheck->f("id");

			print "Update $agcxid \n";
			$aupdate = new DB_Sql;
//			print "update agentcx set name='".$name."',email='".$email."',phone='".$phone."',phone2='".$phone2."',address='".$address."',country='".$country."',agentid='".$agentid."',username='".$username."',password='".$password."',startdate='".$startdate."',enddate='".$enddate."',suspend='".$suspend."',invoice='".$invoice."' where id = '$agcxid'"."\n";
			if($aupdate->query("update agentcx set name='".$name."',email='".$email."',phone='".$phone."',phone2='".$phone2."',address='".$address."',country='".$country."',agentid='".$agentid."',username='".$username."',password='".$password."',startdate='".$startdate."',enddate='".$enddate."',suspend='".$suspend."',invoice='".$invoice."',status='".$status."',updatetime=NOW(), aid='".$aid."' where id = '$agcxid'")){
			}else{
				print "failed\n";
			}
			$aupdate->free();

		}else{		// insert new

			print "Insert \n";
			$insert_stmt = new DB_Sql;

			$sql = "INSERT INTO agentcx ( name,email,phone,phone2,address,country,agentid,username,password,startdate,enddate,suspend,invoice,status,updatetime,aid ) VALUES ('".$name."', '".$email."', '".$phone."', '".$phone2."', '".$address."', '".$country."', '".$agentid."', '".$username."', '".$password."', '".$startdate."', '".$enddate."', '".$suspend."', '".$invoice."', '".$status."', NOW(), '".$aid."')";
			// Insert to database. 
			print $sql."\n";
			if ($insert_stmt->query($sql)) {

			}else{
				print "failed\n";
			}

			$insert_stmt->free();
		}
		
		$acheck->free();
	}

	//update done
	foreach($uidarr as $udid){
		$aupdate = new DB_Sql;
		if($aupdate->query("update agent_request set status='done' where agentid like '$udid'")){
		}else{
		}
		$aupdate->free();
        }

	//update done for empty agents
	if($_POST["id"]!=""){
		$empidarr = explode(",",$_POST["id"]);
		foreach($empidarr as $empid){
			if($empid!=""){
				$aupdate = new DB_Sql;
				if($aupdate->query("update agent_request set status='done' where agentid like '$empid'")){
				}else{
				}
				$aupdate->free();
			}
		}
	}
}else{
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
                        }
                        $aupdate->free();
        }

        print $ret;	
}

?>
