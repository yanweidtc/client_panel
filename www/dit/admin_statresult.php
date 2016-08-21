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
        if ($stmt->query("SELECT id, type, username, agentID, monthlyfee, onetimeRFee, activeaccounts, addonfee, addonpackages, pendinginvoice, update_time, mainAgent from agents where id = '$user_id'")) {
                $stmt->next_record();
		$monthfee = $stmt->f("monthlyfee");
		$addonfee = $stmt->f("addonfee");
		$onetimeRF = $stmt->f("onetimeRFee");
                $uname = $stmt->f("username");
                $agent_id = $stmt->f("agentID");
                $magent_id = $agent_id;
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
        }
//   echo page_head(true,true,$uname);
      echo '<script type="text/javascript">
         var break_link=true;
               $(document).ready(function(){
                        $(\'.tooltiptr\').tooltip();
                });
         </script>';


	// Variables
	//2014-01-24
	list($start,$end)=explode(",",$_POST['date']);

	$startstr=$start;
	$endstr=$end;
	if($start=="Week"){
		$startstr="Monday this week 1:01am";
		$endstr="Monday next week 2:00am";
	}else if($start=="Today"){
		$startstr="Today 1:01am";
		$endstr="Tomorrow 2:00am";
	}else if($start=="Yesterday"){
		$startstr="Yesterday 1:01am";
		$endstr="Today 2:00am";
	}else if($start=="Month"){
		$startstr="first day of ".date('M Y')." 1:01am";
		$endstr="last day of ".date('M Y')." 2:00am";
	}else{
		$startstr=$start." 1:01am";
		if($end=="" || trim($end)==trim($start)){
			$endstr = $start.' + 1 day 2:00am';
		}else{
			$endstr = $end.' +1 day 2:00am';
		}
	}

	$epstart = strtotime($startstr);
	$epend = strtotime($endstr);

	$epstartstr = date("Y-m-d H:i:s",$epstart);
	$ependstr = date("Y-m-d H:i:s",$epend);

	$datestr = date("Y-m-d H:i:s",$epstart)." to ".date("Y-m-d H:i:s",$epend);

	//Count calls
	//Init 
	$countarray=array(
	    "callbl" => 0,
            "hangup" => 0,
            "noansw" => 0,
            "nointr" => 0,
            "oncall" => 0,
            "sendem" => 0,
            "sorder" => 0,
            "techsu" => 0,
            "unlock" => 0,
            "voicem" => 0,
            "zorder" => 0
	);

	$unicountarray=array(
	    "callbl" => 0,
            "hangup" => 0,
            "noansw" => 0,
            "nointr" => 0,
            "oncall" => 0,
            "sendem" => 0,
            "sorder" => 0,
            "techsu" => 0,
            "unlock" => 0,
            "voicem" => 0,
            "zorder" => 0
	);
        $db2=new DB_Sql;
	$dbsql2="select action, COUNT(*) as count ,COUNT(DISTINCT eid) as unicount from agentlog where updatetime > '$epstartstr' and updatetime < '$ependstr' and agentid not like '623411' group by action";
        if ($db2->query($dbsql2))
        {
		while($db2->next_record()){
			$key = $db2->f("action");
			$value = $db2->f("count");
			$univalue = $db2->f("unicount");
			$countarray[$key]=$value;
			$unicountarray[$key]=$univalue;
		}
        }
        $db2->free();



	/*$ordercount=0;
        $db4=new DB_Sql;
	$dbsql4="select COUNT(*) as count from agentcx where ordersub > '$epstartstr' and ordersub < '$ependstr' and agentid <> '623411' and agentid<>'419721-587'";
        if ($db4->query($dbsql4))
        {
		while($db4->next_record()){
			$ordercount = $db4->f("count");
		}
        }
        $db4->free();*/


	$customers=array(
	    "callbl" => 0,
            "hangup" => 0,
            "noansw" => 0,
            "nointr" => 0,
            "oncall" => 0,
            "sendem" => 0,
            "sorder" => 0,
            "techsu" => 0,
            "unlock" => 0,
            "voicem" => 0,
            "zorder" => 0
	);
        $db3=new DB_Sql;
	$dbsql3="SELECT l.action,COUNT(*) as fcount
FROM    (
	SELECT id, eid, MAX(updatetime) as maxu, updatetime
	FROM agentlog
	WHERE updatetime > '$epstartstr'
	  AND updatetime < '$ependstr'
	GROUP BY eid DESC
	) lo, agentlog l
WHERE l.eid=lo.eid
	AND l.updatetime = lo.maxu
	AND l.agentid not like '623411'
	AND ( l.action = 'sendem'
	OR l.action = 'nointr'
	OR l.action = 'sorder')
GROUP BY l.action";

        if ($db3->query($dbsql3))
        {
		while($db3->next_record()){
			$ckey = $db3->f("action");
			$customers[$ckey]=$db3->f("fcount");
		}
        }
        $db3->free();


//	print "<pre>".print_r($countarray,true)."</pre>";
        $statable = ' <div class="dwarp span12" style="margin-left: 0px;">
                <table  class="table table-condensed" width="100%" height="100%" cellpadding=0 cellspacing=0 style="border: 2px solid #000">
                    <caption><h3>'.$datestr.' Stats</h3></br></caption>
                    <thead>
                      <tr bgcolor="gray" style="color:white; font-size: 12px;">
<!--                        <th width="26%">Graph</th>-->
                        <th>Detail</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had one or more [OnCall] action." class="tooltiptr">
<!--                     <td rowspan="10"><canvas id="canvas" height="140" width="140" style="display: block;margin-left: auto;margin-right: auto;"></canvas></td>-->
                     <td>Total Processed Calls:</td>
                     <td>'.$unicountarray["oncall"].'</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers whose last action is: '."\n".'[Sent Email]-'.$customers["sendem"].','."\n".' [Not Interested]-'.$customers["nointr"].','."\n".' or [Ordered]-'.$customers["sorder"].'." class="tooltiptr">
                     <td>Total Customers reached:</td>
                     <td>'.($customers["nointr"]+$customers["sendem"]+$customers["sorder"]).'</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had one or more [No Answer] action." class="tooltiptr">
                     <td>No Answered Calls:</td>
                     <td>'.$unicountarray["noansw"].'</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had one or more [Voice Mail] action." class="tooltiptr">
                     <td>Voicemail Calls:</td>
                     <td>'.$unicountarray["voicem"].'</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had one or more [Tech Support Request] action." class="tooltiptr">
                     <td>Tech Support requests:</td>
                     <td>'.$unicountarray["techsu"].'</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had one or more [Send Email] action." class="tooltiptr">
                     <td>Emails sent:</td>
                     <td>'.$unicountarray["sendem"].'</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had one or more [Not Interest] action." class="tooltiptr">
                     <td>Not Interested:</td>
                     <td>'.$unicountarray["nointr"].'</td>
                   </tr>';
//                 <td>'.$unicountarray["sorder"].'</td>
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had [Order Submit] action." class="tooltiptr">
                     <td>Orders:</td>
                     <td>'.$customers["sorder"].'</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had action'."\n".' [Order Submit]-'.$unicountarray["sorder"].' '."\n".'over [On Call]-'.$unicountarray["oncall"].'" class="tooltiptr">
                     <td>Order Percentage (Calls):</td>
                     <td>'.round((($unicountarray["sorder"])/$unicountarray["oncall"]*100),2).'% orders / calls</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had action'."\n".' [Order Submit]-'.$customers["sorder"].' '."\n".'over Customer Reached-'.($customers["nointr"]+$customers["sendem"]+$customers["sorder"]).'" class="tooltiptr">
                     <td>Order Percentage (Customers):</td>
                     <td>'.round((($customers["sorder"])/($customers["nointr"]+$customers["sendem"]+$customers["sorder"])*100),2).'% orders / customers</td>
                   </tr>';
        $statable.='<tr data-toggle="tooltip" data-placement="top" title="Number of unique customers had action'."\n".' [Order Submit]-'.$customers["sorder"].' '."\n".'after action [Call Back]-'.$unicountarray["callbl"].'." class="tooltiptr">
                     <td>Call back conversions:</td>
                     <td>'.round((($customers["sorder"])/($unicountarray["callbl"])*100),2).'% orders / callbacks</td>
                   </tr>';

        $statable.='</tbody></table></div>';

	print $statable;




//   echo page_foot($ajax);
} else {
	   echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
}


?>

