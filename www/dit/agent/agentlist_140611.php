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
                $ret= $newencrypt->endecrypt("$pp","$input","en");
                return $ret;
        }
	function decry($input){
                $pp = 'Mf4QmQHKxFbYFGsLdVdbBMTUGR4CzeWvtcq';
		$newencrypt = new rc4crypt;
                $ret= $newencrypt->endecrypt("$pp","$input","de");

                return htmlspecialchars($ret);
        }
	function agname($agid){
		$agparse = explode("-",$agid);
		$nmstmt = new DB_Sql;
		$nmstmt->query("SELECT username,billable,company from agents where agentid='$agparse[0]'");
		$nmstmt->next_record();
		$nname = $nmstmt->f("username");
		$nbill = $nmstmt->f("billable");
		$ncompany = $nmstmt->f("company");

		if(isset($agparse[1]) && $nbill!="Y"){
			$nsstmt = new DB_Sql;
        		$nsstmt->query("SELECT username from agents where id='$agparse[1]'");
     			$nsstmt->next_record();
			return $nsstmt->f("username");
		}else if($nbill=="Y"){
			return $ncompany;
		}else{
                        return $nname;
		}
	}



        echo '<script type="text/javascript">
        $(document).ready(function(){
                $(\'[class^="sub"]\').hide();

                $( ".showdbtn" ).click(function() {
                        var classname = $(this).attr(\'id\');
                        var subname = \'sub\'+classname;

                    if( $(this).text()==="+"){
                       $(\'.\'+subname).show();
                       $(this).text("-");
                    }
                    else {
                           $(\'.\'+subname).hide();
                           $(this).text("+");
                    }
                });
        });
        </script>';



	echo '<div class="input-group">
		<span class="input-group-addon">Search:</span>
		<input class="filter form-control" id="prependedInput" type="text" style="width:250px;" placeholder="eg. Number, Status">
	      </div>
	      <div class="clearfix"></div>';

        echo '<script>
         $(\'input.filter\').keyup(function() {
            var rex = new RegExp($(this).val(), \'i\');
            $(\'.searchable tr\').hide();
            $(\'.searchable tr:first-child\').show();
                $(\'.searchable tr.main\').filter(function() {
                    return ( rex.test($(this).text()));
                }).show();
            });
        </script>';






if($ubillable=="Y"){
	/*echo '<br><div id="detailtable"><table class="table table-bordered">
                      <caption><h3>'.$uname.'\'s Customer List</h3><br></caption>
		      <tr>
                                        <td width=\"10%\">Username</td>
                                        <td width=\"10%\">Name</td>
                                        <td width=\"10%\">Agent</td>
                                        <td width=\"15%\">Email</td>
                                        <td width=\"10%\">Phone</td>
                                        <td width=\"15%\">Address</td>
                                        <td width=\"10%\">Startdate</td>
                                        <td width=\"10%\">Status</td>
                                        <td width=\"10%\">Panel</td>
		      </tr>
	     ';
*/
	echo '<br><div id="detailtable"><table class="table table-bordered searchable">
                      <caption><h3>'.agname($agent_id).'\'s Customer List</h3><br></caption>
		      <tr>
                                        <td width="10%">Username</td>
                                        <td width="20%">Name</td>
                                        <td width="25%">Address</td>
                                        <td width="10%">Status</td>
                                        <td width="25%">Tasks</td>
                                        <td width="10%">More</td>
		      </tr>
	     ';
}else{
	echo '<br><div id="detailtable"><table class="table table-bordered searchable">
                      <caption><h3>'.agname($agent_id).'\'s Customer List</h3><br></caption>
		      <tr>
                                        <td width="30%">Name</td>
                                        <td width="30%">Address</td>
                                        <td width="20%">Status</td>
                                        <td width="20%">More</td>
		      </tr>
	     ';

}

	//$enagid = urlencode(encry($agent_id));
	$enagid = $agent_id;
	$dstmt = new DB_Sql;
	//print "<pre>SELECT * from agentcx where agentid = '$enagid'</pre>";
	if ($dstmt->query("SELECT * from agentcx where agentid like '%$enagid%'")) {
	
		while($dstmt->next_record()){
			$dname = stripslashes($dstmt->f("name"));
			$demail = stripslashes($dstmt->f("email"));
			$dphone = stripslashes($dstmt->f("phone"));
			$dphone2 = stripslashes($dstmt->f("phone2"));
			$daddress = stripslashes($dstmt->f("address"));
			$dcountry = stripslashes($dstmt->f("country"));
			$dagentid = stripslashes($dstmt->f("agentid"));
			$dusername = stripslashes($dstmt->f("username"));
			$dpassword = stripslashes($dstmt->f("password"));
			$dstartdate = stripslashes($dstmt->f("startdate"));
			$denddate = stripslashes($dstmt->f("enddate"));
			$dsuspend = stripslashes($dstmt->f("suspend"));
			$ddstatus = stripslashes($dstmt->f("status"));
			$dinvoice = stripslashes($dstmt->f("invoice"));
			$dcheckid = stripslashes($dstmt->f("id"));


			$dstatus="";
			if(decry($dsuspend) == 'Y'){
				$dstatus="<font color=red>Terminated</font>";
				$opbtn='&nbsp;&nbsp;<button type="button" class="btn btn-mini btn-info funcbtn" id="utm'.$acid.'">Unterminate</button>';
			}else if(decry($ddstatus) == "s"){
				$dstatus="<font color=orange>Suspended</font>";
				$opbtn='&nbsp;&nbsp;<button type="button" class="btn btn-mini btn-primary funcbtn" id="res'.$acid.'">Unsuspend</button>';
			}else if(decry($dinvoice) == "N" && $ubillable!="Y"){
				$dstatus="<font color=blue>Pending Invoice</font>";
				$opbtn='&nbsp;&nbsp;<button type="button" class="btn btn-mini btn-warning funcbtn" id="sus'.$acid.'">Suspend</button>
                                    &nbsp;<button type="button" class="btn btn-mini btn-danger funcbtn" id="sus'.$acid.'">Terminate</button>';
			}else{
				$dstatus="<font color=green>Active</font>";
				$opbtn='&nbsp;&nbsp;<button type="button" class="btn btn-mini btn-warning funcbtn" id="sus'.$acid.'">Suspend</button>
                                    &nbsp;<button type="button" class="btn btn-mini btn-danger funcbtn" id="sus'.$acid.'">Terminate</button>';
			}

			echo '<tr class="check'.$dcheckid.' main">';

		if($ubillable=="Y"){
			echo'		<td>'.decry($dusername).'</td>';
		}
			echo'	<td>'.decry($dname).'</td>
				<td>'.decry($daddress).'</td>
				<td>'.$dstatus.'</td>';
		if($ubillable=="Y"){

			echo	'<td><form action="https://client.zazeen.com/process_cli_login.php" method="post" target="_blank" style="margin-bottom: 0px;float:left;">
				    <input type="hidden" id="username" name="username" value="'.decry($dusername).'"/>	
				    <input type="hidden" id="p" name="p" value="'.decry($dpassword).'"/>
				    <input type="hidden" id="from" name="from" value="agent"/>
				    <input type="submit" id="submit" class="btn btn-mini btn-default funcbtn" name="submit" value="Panel"/>
				    </form>'.$opbtn.'
				</td>';
		}
			echo	'<td>[<a id="'.$dcheckid.'" href="#" class="showdbtn">+</a>]</td>';
			echo	'</tr>
			';
			echo '<tr class="sub'.$dcheckid.'">
				<td colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Agent: '.agname($dagentid).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Email: '.decry($demail).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		if($ubillable=="Y"){
		echo 	'	Phone: '.decry($dphone).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				StartDate: '.decry($dstartdate).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>&nbsp;</td>';
		}else{
		echo 	'	Phone: '.decry($dphone).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				StartDate: '.decry($dstartdate).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
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

