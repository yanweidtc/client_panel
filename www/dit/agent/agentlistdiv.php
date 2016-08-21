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
                $stmtx = new DB_Sql;
                $stmtx->query("SELECT company,billable from agents where agentID = '$magent'");
                $stmtx->next_record();
                $company = $stmtx->f("company");
                $ubillable = $stmtx->f("billable");
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




	echo '<div class="cxchdiv" style="display:none;"></div>';
	echo '<div id="loadingImg2" class="span12" style="display:none;margin-left: 0px;">
		<img style="display: block;margin-left: auto;margin-right: auto;" src="images/loading.gif">
		<center><h4>Loading data...</h4></center>
	      </div>';


	//$enagid = urlencode(encry($agent_id));
	$enagid = $agent_id;
	$dstmt = new DB_Sql;
	//print "<pre>SELECT * from agentcx where agentid = '$enagid'</pre>";
	if ($dstmt->query("SELECT * from agentcx where agentid like '%$enagid%'")) {
		if($dstmt->num_rows()==0){
			//display loading or refresh btn.
			$cxchstmt = new DB_Sql;
			if ($cxchstmt->query("SELECT * from agent_request where agentid='$enagid' and ( status='init' or status='process' ) and type=1")) {
				if($cxchstmt->num_rows()>0){
					//Loading Image
					echo '<div id="loadingImg" class="span12" style="margin-left: 0px;">
						<img style="display: block;margin-left: auto;margin-right: auto;" src="images/loading.gif">
						<center><h4>Loading data...</h4></center>
					      </div>';
				}else{
					//btn
					echo '<center><h4>Empty list...</h4></center>';
					echo '<br><div id="btn_wrap" align="center"><button class="btn btn-large btn-block" id="cxchbtn" type="button">Resend Request</button></div><br>';
					echo '<script type="text/javascript">
						$("#cxchbtn").click(function(){
							$(".cxchdiv").load("agent_request.php", { \'req\':1, \'agentid\':\''.$enagid.'\',\'data\': \'\' }, function() {
								$("#loadingImg2").show();
								$("#btn_wrap").hide();
							});
						});
						</script>';
				}
			}
			exit;
		}else{	
		//display loading or refresh btn.
                        $cxchstmt = new DB_Sql;
                        if ($cxchstmt->query("SELECT * from agent_request where agentid= '$enagid' and ( status='init' or status='process' ) and type=1")) {
                                if($cxchstmt->num_rows()>0){
                                        //Loading Image
                                        echo '<div id="loadingImg" class="span12" style="margin-left: 0px;">
                                                <img style="display: block;margin-left: auto;margin-right: auto;" src="images/loading.gif">
                                                <center><h4>Loading data...</h4></center>
                                              </div>';
					exit;
                                }else{
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

			$( "#showtermbtn" ).click(function() {
			    if( $(this).text()==="Show Terminated Accounts"){
			       $(\'.termtr\').show();
			       $(this).text("Hide Terminated Accounts");
			    }
			    else {
				   $(\'.termtr\').hide();
				   $(this).text("Show Terminated Accounts");
			    }
			});

/*			$( ".funcbtn" ).click(function() {
				//loadprocess script,     
				
			});*/
			
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
				$dcheckaid = decry(stripslashes($dstmt->f("aid")));

				$acid = $dcheckaid;

				$dstatus="";
				$dpanelbtn='<form action="https://client.zazeen.com/process_cli_login.php" method="post" target="_blank" style="margin-bottom: 0px;float:left;">
                                            <input type="hidden" id="username" name="username" value="'.decry($dusername).'"/>
                                            <input type="hidden" id="p" name="p" value="'.decry($dpassword).'"/>';
if($ubillable=="Y"){
	$dpanelbtn.= '                                            <input type="hidden" id="from" name="from" value="billagent"/>';
}else{
	$dpanelbtn.= '                                            <input type="hidden" id="from" name="from" value="agent"/>';
}
$dpanelbtn.= '                                            <input type="submit" id="submit" class="btn btn-mini btn-default" name="submit" value="Panel"/>
                                            </form>';
				$termf='';
				$termc='';
				if(decry($dsuspend) == 'Y'){
					$dstatus="<font color=red>Terminated</font>";
					$termf=' style="display:none;"';
					$termc=' termtr';
					$opbtn='<button type="button" class="btn btn-mini btn-info funcbtn" id="utm'.$acid.'">Unterminate</button>';
				}else if(decry($ddstatus) == "s"){
					$dstatus="<font color=orange>Suspended</font>";
					$opbtn=$dpanelbtn.'&nbsp;&nbsp;<button type="button" class="btn btn-mini btn-primary funcbtn" id="res'.$acid.'">Unsuspend</button>';
				}else if(decry($dinvoice) == "N" && $ubillable!="Y"){
					//$dstatus="<font color=blue>Pending Invoice</font>";
					$dstatus="<div style=\"float:left;\"><font color=green>Active&nbsp;&nbsp;</font></div><img style=\"display: block;float:left;height:12px;width:12px\" src=\"images/p-icon.png\">";
					$opbtn='&nbsp;&nbsp;<button type="button" class="btn btn-mini btn-warning funcbtn" id="sus'.$acid.'">Suspend</button>
					    &nbsp;<button type="button" class="btn btn-mini btn-danger funcbtn" id="tem'.$acid.'">Terminate</button>';
				}else{
					$dstatus="<font color=green>Active</font>";
					$opbtn=$dpanelbtn.'&nbsp;&nbsp;<button type="button" class="btn btn-mini btn-warning funcbtn" id="sus'.$acid.'">Suspend</button>
					    &nbsp;<button type="button" class="btn btn-mini btn-danger funcbtn" id="tem'.$acid.'">Terminate</button>';
				}


				$opbtn = '<div class="limg'.$dcheckaid.'" style="display:none"><img style="display: block;margin-left: auto;margin-right: auto;" src="images/loading.gif"></div><div class="obtn'.$dcheckaid.'">'.$opbtn.'</div>';

				echo '<tr class="check'.$dcheckaid.' main'.$termc.'"'.$termf.'>';

			if($ubillable=="Y"){
				echo'		<td>'.decry($dusername).'</td>';
			}
				echo'	<td>'.decry($dname).'</td>
					<td>'.decry($daddress).'</td>
					<td>'.$dstatus.'</td>';
			if($ubillable=="Y"){

				echo	   '<td>'.$opbtn.'
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


			}//while

			echo '</table></div>';
			
			echo '<br><div id="btn_wrapterm" align="center"><button class="btn btn-large btn-block" id="showtermbtn" type="button">Show Terminated Accounts</button></div><br>';
	     }//else
	}	//if innerquery
//       echo page_foot($ajax);
   }// if query

} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>
