<?php
  include_once('./js/header.php');
  $ajax = false;

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

   echo page_head(true,true,$uname);


	function encry($input){
                $pp = 'Mf4QmQHKxFbYFGsLdVdbBMTUGR4CzeWvtcq';
                $newencrypt = new rc4crypt;
                $ret= $newencrypt->endecrypt ("$pp", "$input", en);
                return $ret;
        }
	function decry($input){
                $pp = 'Mf4QmQHKxFbYFGsLdVdbBMTUGR4CzeWvtcq';
                $newencrypt = new rc4crypt;
                $ret= $newencrypt->endecrypt ("$pp", "$input", de);
                return $ret;
        }
	function agname($agid){
		$agparse = explode("-",$agid);
		if(isset($agparse[1])){
			$nsstmt = new DB_Sql;
        		$nsstmt->query("SELECT username from agents where id='$agparse[1]'");
     			$nsstmt->next_record();
			return $nsstmt->f("username");
		}else{
			$nmstmt = new DB_Sql;
                        $nmstmt->query("SELECT username from agents where agentid='$agparse[0]'");
                        $nmstmt->next_record();
                        return $nmstmt->f("username");
		}
	}



if($ubillable=="Y"){
	echo '<br><div id="detailtable"><table class="table table-bordered">
                      <caption><h3>'.$uname.'\'s Customer List</h3><br></caption>
		      <tr>
                                        <td width=\"15%\">Name</td>
                                        <td width=\"10%\">Agent</td>
                                        <td width=\"15%\">Email</td>
                                        <td width=\"10%\">Phone</td>
                                        <td width=\"20%\">Address</td>
                                        <td width=\"10%\">Startdate</td>
                                        <td width=\"10%\">Status</td>
                                        <td width=\"10%\">Panel</td>
		      </tr>
	     ';
}else{
	echo '<br><div id="detailtable"><table class="table table-bordered">
                      <caption><h3>'.$uname.'\'s Customer List</h3><br></caption>
		      <tr>
                                        <td width=\"20%\">Name</td>
                                        <td width=\"10%\">Agent</td>
                                        <td width=\"15%\">Email</td>
                                        <td width=\"10%\">Phone</td>
                                        <td width=\"25%\">Address</td>
                                        <td width=\"10%\">Startdate</td>
                                        <td width=\"10%\">Status</td>
		      </tr>
	     ';

}

	//$enagid = urlencode(encry($agent_id));
	$enagid = $agent_id;
	$dstmt = new DB_Sql;
	//print "<pre>SELECT * from agentcx where agentid = '$enagid'</pre>";
	if ($dstmt->query("SELECT * from agentcx")) {
	
		while($dstmt->next_record()){
			$dname = $dstmt->f("name");
			$demail = $dstmt->f("email");
			$dphone = $dstmt->f("phone");
			$dphone2 = $dstmt->f("phone2");
			$daddress = $dstmt->f("address");
			$dcountry = $dstmt->f("country");
			$dagentid = $dstmt->f("agentid");
			$dusername = $dstmt->f("username");
			$dpassword = $dstmt->f("password");
			$dstartdate = $dstmt->f("startdate");
			$denddate = $dstmt->f("enddate");
			$dsuspend = $dstmt->f("suspend");
			$dinvoice = $dstmt->f("invoice");
			$dcheckid = $dstmt->f("id");


			$dstatus="";
			if($dsuspend == 'Y'){
				$dstatus="Suspended";
			}else if($dinvoice == "N"){
				$dstatus="Pending Invoice";
			}else{
				$dstatus="Normal";
			}

			//print "<pre>$daddress</pre>";
			echo '<tr class="check'.$dcheckid.'">
				<td>'.decry($dname).'</td>
				<td>'.decry($dagentid).'</td>
				<td>'.decry($demail).'</td>
				<td>'.decry($dphone).'</td>
				<td>'.decry($daddress).'</td>
				<td>'.decry($dstartdate).'</td>
				<td>'.$dstatus.'</td>';
		if($ubillable=="Y"){

			echo	'<td><form action="https://client.zazeen.com/process_cli_login.php" method="post" target="_blank">
				    <input type="hidden" id="username" name="username" value="'.decry($dusername).'"/>	
				    <input type="hidden" id="p" name="p" value="'.decry($dpassword).'"/>
				    <input type="hidden" id="from" name="from" value="agent"/>
				    <input type="submit" id="submit" name="submit" value="Access Client Panel"/>
				    </form>
				</td>';
		}
			echo	'</tr>
			';

		}
	}

	echo '</table></div>';
   }
       echo page_foot($ajax);

} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>
