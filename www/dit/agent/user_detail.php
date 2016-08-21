<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
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

	if($utype == "subagent"){
                $agent_id = $magent."-".$user_id;
                $stmtx = new DB_Sql;
                $stmtx->query("SELECT company,billable from agents where agentID = '$magent'");
                $stmtx->next_record();
                $company = $stmtx->f("company");
                $ubillable = $stmtx->f("billable");
        }
		$subagent= $stmt->f("subAgent");

	$today = date('M');
	$month = date('F');
	$fullday = date("Y-m-d");


   echo page_head(true,true,$uname);

   if($utype != "main"){
	echo 'You are not authorized to access this page, please login as main Agent. <a href="login.php">Back</a> <br/>';
	exit;
   }



 
  if(isset($_GET['id'])) {
        $uid = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if($uid){
      echo '<script type="text/javascript">
        	 var break_link=true;
            </script>';
      echo '<style type="text/css">
		.table tbody tr:hover td,
		.table tbody tr:hover th {
		    background-color: transparent;
		}
	    </style>';
   


	$dstmt = new DB_Sql;
	if ($dstmt->query("SELECT * from agents where id = '$uid'")) {
		$dstmt->next_record();
		$dmonthfee = $dstmt->f("monthlyfee");
		$dactiveaccount = $dstmt->f("activeaccounts");
		$daddonfee = $dstmt->f("addonfee");
		$daddonpackages = $dstmt->f("addonpackages");
		$dpdis = $dstmt->f("pendinginvoice");
		$dupdate_time = $dstmt->f("update_time");
		$donetimeRF = $dstmt->f("onetimeRFee");
		$dagent_id = $dstmt->f("agentID");
		$duname = $dstmt->f("username");
		$dutype = $dstmt->f("type");
		$dmagent = $dstmt->f("mainAgent");
		$dnewsignup = $dstmt->f("newsignup");
		$dcancelled = $dstmt->f("cancelled");
		$demail = $dstmt->f("email");
		$dnewemail = $dstmt->f("neworderemail");
		//print "<pre>$dnewemail</pre>";
		$dphone = $dstmt->f("phone");
		$dphone2 = $dstmt->f("phone2");
		$dpdaccount = $dstmt->f("pdaccount");
		$donetimeaccount = $dstmt->f("onetimeaccount");
		$dshortcancelled = $dstmt->f("shortcancelled");
		$dthreepdaccount = $dstmt->f("threepdaccount");
		$dsixpdaccount = $dstmt->f("sixpdaccount");
		$dninepdaccount = $dstmt->f("ninepdaccount");
		$dmonthlyaccount = $dstmt->f("monthlyaccount");
		$dmonthlypdi= $dstmt->f("monthlypdi");
		$dnotyetaccount= $dstmt->f("notyetaccount");
		$daddonrev= $dstmt->f("addonrevenue");
		$duid = $dstmt->f("id");
		$dsip = $dstmt->f("SIP");

                $dmonthlyday = $dstmt->f("monthlyday");
                $donetimeday = $dstmt->f("onetimeday");
                $dcancelday = $dstmt->f("cancelday");
		$dmonthlypaid= $dstmt->f("monthlypaidac");
		
		//Cancel charge backs
                $dcancelstr = $dstmt->f("cancelstr");
                $dcancelamount = $dstmt->f("cancelamount");
		
		//Cancel charge backs
                $dcancelstr = $dstmt->f("cancelstr");
                $dcancelamount = $dstmt->f("cancelamount");

                $dcone=0;
                $dcmon=0;
                $dcancelstr_r = explode(",",$dcancelstr);
                foreach( $dcancelstr_r as $cstr){
                        if($cstr!=""){
                                $cstr_r = explode("+",$cstr);
                                if($cstr_r[0] == "O"){
                                        $dcone++;
                                }

                                if($cstr_r[1] != ""){
                                        $dcmon+=substr($cstr_r[1],2);
                                }
                        }
                }
                if($dcone!=0 || $dcmon!=0){
                        $dcstr = "Onetime Chargeback * ".$dcone.", Monthly Chargeback * ".$dcmon;
                }else{
                        $dcstr = "";
                }



		$phonerow=3;
		if($dphone2 != ""){
			$phonerow=4;
		}


   	echo        '        <div class="pull-right">'.$n;
if($dutype=="subagent"){
        echo        '          <a class="btn btn-primary" href="user_edit.php?id='.$uid.'&from=detail">Edit Sub-Agent</a>'.$n;
        $button_string="Sub-Agent ";
}
        echo        '          <a class="btn btn-primary" href="revenue_history.php?sid='.$duid.'">'.$button_string.'Revenue History</a>'.$n;
	echo        '          <a class="btn btn-primary" href="main.php">Back</a>'.$n;
	echo        '        </div>'.$n.
           	    '        <div class="clearfix"></div>'.$n;
	if($dcancelled == ""){
		$dcancelled=0;
	}
	if($dactiveaccount == ""){
		$dactiveaccount=0;
	}
	if($dpdis == ""){
		$dpdis=0;
	}
	if($dnewsignup == ""){
		$dnewsignup=0;
	}

		$dsubagent= $dstmt->f("subAgent");

	$ag_id = $dmagent.'-'.$uid;
	if($dutype == "main"){
		$ag_id = $dagent_id;
	}
/*
        // List details of this agent
             echo '</br><table class="table">
                      <caption><h3>'.$duname.'\'s Revenue Details ( '.$month.' )</h3></br><h4 style="color:gray">AgentID: '.$ag_id.'</h4></br><h4 style="color:gray"> Last update on: '.$dupdate_time.'</h4></br></caption>
                    <thead>
                      <tr>
                        <th>Referral Monthly Fee</th>
                        <th>Active Accounts</th>
                        <th>Pending Invoices</th>
                        <th>Revenue From Monthly Referral</th>
                      </tr>
                    </thead>
                    <tbody>';

                echo '<tr>
                        <td>$'.$dmonthfee.'</td>
                        <td>'.$dactiveaccount.'</td>
                        <td>'.$dpdis.'</td>	
                        <td>$'.(($dmonthfee*($dactiveaccount-$dpdis)) + ($donetimeRF*$dnewsignup)).'</td>	
                        </tr>';
             
             echo '</tbody></table><hr>';

             echo '</br><table class="table table-hover">
                    <thead>
                      <tr>
                        <th>One-time Referral Fee</th>
                        <th>New-signup Accounts</th>
                        <th>Cancelled Accounts</th>
                        <th>Revenue From One-time Referral</th>
                      </tr>
                    </thead>
                    <tbody>';

                echo '<tr>
                        <td>$'.$donetimeRF.'</td>
                        <td>'.$dnewsignup.'</td>
                        <td>'.$pdis.'</td>	
                        <td>$'.($onetimeRF*$newsignup).'</td>	
                        </tr>';
             
             echo '</tbody></table><hr>';
*/




	     echo '</br><div id="detailtable"><table class="table table-bordered">
                      <caption><h3>'.$duname.'\'s Revenue Details ( '.$month.' )</h3></br><h4 style="color:gray">Last update on: '.$dupdate_time.'</h4></br></caption>';
		if($dsip!=""){$phonerow+=1;}
		if($dnewemail!="" && $ubillable=="Y"){$phonerow+=1;}

                echo '<tr>
			<td rowspan="'.$phonerow.'"><b>Profile</b></td>
                        <td>AgentID:</td>
                        <td>'.$ag_id.'</td>
		      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Email: </td>
                        <td>'.$demail.'</td>
		      </tr>';

		if($dnewemail!="" && $ubillable=="Y"){
			echo '<tr>
				<td style="border-left: 1px solid #DDDDDD;">New-Order Email: </td>
				<td>'.$dnewemail.'</td>
			      </tr>';
		}

	echo	      '<tr>
                        <td style="border-left: 1px solid #DDDDDD;">Phone: </td>
                        <td>'.$dphone.'</td>
                      </tr>';
		if($dphone2!=""){
			echo '<tr>
				<td style="border-left: 1px solid #DDDDDD;">Phone2: </td>
				<td>'.$dphone2.'</td>
			      </tr>';
		
		}
		if($dsip!=""){
			echo '<tr>
				<td style="border-left: 1px solid #DDDDDD;">SIP: </td>
				<td>'.$dsip.'</td>
			      </tr>';
		
		}
                /*echo '<tr>
			<td rowspan="5"><b>Total Account Status</b></td>
                        <td>Total Active Accounts:</td>
                        <td>$'.$dactiveaccount.'</td>
		      </tr>
		      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Pending Invoices:</td>
                        <td>'.$dpdis.'</td>
		      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Total Cancelled Accounts:</td>
                        <td>'.$dcancelled.'</td>
		      </tr>';*/

if($ubillable=="Y"){$rowsss=4;}else{$rowsss=5;}

                echo '<tr>
                        <td rowspan="'.$rowsss.'"><b>Monthly Referral</b></td>
                        <td>Referral Monthly Fee:</td>
                        <td>$'.$dmonthfee.'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Monthly fee cashable days:</td>
                        <td>'.$dmonthlyday.' days</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Active Accounts for > '.$dmonthlyday.' days:</td>
                        <td>'.$dmonthlyaccount.'</td>
                      </tr>';
if($ubillable!="Y"){
                      echo'<tr>
                        <td style="border-left: 1px solid #DDDDDD;">Pending Invoices:</td>
                        <td>'.$dmonthlypdi.'</td>
                      </tr>';
}
                 echo'     <tr>     
                        <td style="border-left: 1px solid #DDDDDD;">Revenue From Monthly Referral:</td>
                        <td>$'.$dmonthfee*($dmonthlyaccount-$dmonthlypdi).'( $'.($dmonthlypaid*$dmonthfee).' Paid )</td>       
                      </tr>';


                echo '<tr>
			<td rowspan="4"><b>Add-on Referral</b></td>
                        <td>Add-on Referral Fee:</td>
                        <td>'.$daddonfee.'%</td>
		      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Add-on Packages</td>
                        <td>'.$daddonpackages.'</td>
		      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Total Add-on Revenue:</td>
                        <td>$'.$daddonrev.'</td>
		      </tr>
		      <tr>     
                        <td style="border-left: 1px solid #DDDDDD;">Revenue From Add-on Referral:</td>
                        <td>$'.round((($daddonfee/100)*$daddonrev),2).'</td>       
                      </tr>';
            
                echo '<tr>
                        <td rowspan="10"><b>One-time Referral</b></td>
                        <td>One-time Referral Fee:</td>
                        <td>$'.$donetimeRF.'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">One-time fee cashable days:</td>
                        <td>'.$donetimeday.' days</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Accounts start this month:</td>
                        <td>'.$dnewsignup.'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Accounts not yet started:</td>
                        <td>'.$dnotyetaccount.'</td>
                      </tr>
		      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Charge-back Accounts Due to Cancellation Within '.$dcancelday.' days ( -$ '.$dcancelamount.' ) :</td>
                        <td>'.$dshortcancelled.'&nbsp;&nbsp;'.$dcstr.'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Cashable today ('.$fullday.'):</td>
                        <td>'.$donetimeaccount.'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Cashable whithin 30 days:</td>
                        <td>'.($dthreepdaccount-$donetimeaccount).'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Cashable whithin 60 days:</td>
                        <td>'.$dsixpdaccount.'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Cashable whithin 90 days:</td>
                        <td>'.$dninepdaccount.'</td>
                      </tr>
                      <tr>     
                        <td style="border-left: 1px solid #DDDDDD;">Revenue From One-time Referral:</td>
                        <td>$'.($donetimeRF*($donetimeaccount)).'</td>  
                      </tr>';

                echo '<tr>
                        <td rowspan="4"><b>Total</b></td>
                        <td>Pending One-time Revenue (30 days):</td>
                        <td>$'.($donetimeRF*($dthreepdaccount-$donetimeaccount)).'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Pending One-time Revenue (60 days):</td>
                        <td>$'.($donetimeRF*($dsixpdaccount)).'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Pending One-time Revenue (90 days):</td>
                        <td>$'.($donetimeRF*($dninepdaccount)).'</td>
                      </tr>
                      <tr>
                        <td style="border-left: 1px solid #DDDDDD;">Cashable Revenue:</td>
                        <td>$'.(($dmonthfee*($dmonthlyaccount-$dmonthlypdi))+($donetimeRF*($donetimeaccount)) + round((($daddonfee/100)*$daddonrev),2)).'</td>
                      </tr>';
             echo '</table><hr></div>';


		}// if dstmt
		$dstmt->free();


	}// if stmt
	   $stmt->free();


	}else{ // if uid
		echo 'Error: Invalid id. <a href="login.php">Back</a> <br/>';
	}
  }else{// if get

	echo 'Error: Empty id. <a href="login.php">Back</a> <br/>';
  }


   echo page_foot($ajax);
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}


?>

