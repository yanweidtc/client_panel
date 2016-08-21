<?php

$exec_job_cmd = `curl "http://interface.acanac.com/zazeenlogin.php?user=masteradmin&password=CAgxaDVYmzg9GWdLa43FehCKXvmZwtQd&directxml=1&item=<function>accountinfo</function><accountid>1070</accountid><agent>623411</agent>"`;
//    $exec_job_cmd = `echo testing`;
//    $exec_job_ret = exec($exec_job_cmd, $exec_job_out, $exec_job_err);
    print "<pre>$exec_job_cmd</pre>";
//    $job_split = explode("/",$exec_job_out[0]);
/*
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
require_once "class.rc4crypt.php";

sec_session_start();
$ip=getenv ("REMOTE_ADDR");
//66.49.254.56
if ($ip != "66.49.254.13")
{
   print '<div class="alert alert-danger alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <strong>Error!</strong> You are accessing from an external IP!.
                        </div>';
                exit;
}


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
                //$agent_id = $magent."-".$user_id;
                $agent_id = $magent;
        }

	
	$actstr="";
 	if($_POST['act']=="sus"){
		$actstr="suspendaccount";
	}else if($_POST['act']=="res"){
		$actstr="unsuspendaccount";
	}else if($_POST['act']=="tem"){
		$actstr="terminateaccount";
	}else if($_POST['act']=="utm"){
		$actstr="unterminateaccount";
	}else{
		   print '<div class="alert alert-danger alert-dismissable">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Error!</strong> Invalid Action.
			</div>';
		exit;
	}	


} else {
   print '<div class="alert alert-danger alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <strong>Error!</strong> Session Timedout, please re-login.
                        </div>';
                exit;
}
*/

?>

