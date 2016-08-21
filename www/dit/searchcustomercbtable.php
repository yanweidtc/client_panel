<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
   global $n;
if($_POST["sel"]!=""){

	        //Get agent detail
        $user_id = $_SESSION['user_id'];
        $stmt = new DB_Sql;
        if ($stmt->query("SELECT * from agents where id = '$user_id'")) {
                $stmt->next_record();
                $uname = $stmt->f("username");
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
                $subagent= $stmt->f("subAgent");
                $agent_id = $stmt->f("agentID");

                if($utype == "subagent"){
                        $agent_id = $magent."-".$user_id;
                }
        }


//Chris, Number Pool
if($_POST["sel"]=="go"){
$dbsql2="select id, name, phone, phone2, email , status, callagent, agentid, result, callbefore, callafter from agentcx where status='waitcb' and callafter < (NOW() + INTERVAL 45 MINUTE) order by callafter desc";
}else{

$pattern = preg_replace('/[^\da-z]/i', '', $_POST["sel"]);

$checkpattern = preg_replace('/\D/', '', $pattern);
if(strlen($checkpattern)==10){
        //is phone number
        $hyphenp = substr($checkpattern,0,3)."-".substr($checkpattern,3,3)."-".substr($checkpattern,6);
        $whitespp = substr($checkpattern,0,3)." ".substr($checkpattern,3,3)." ".substr($checkpattern,6);


        $pattern = mysql_real_escape_string($pattern);
        $whitespp = mysql_real_escape_string($whitespp);
        $hyphenp = mysql_real_escape_string($hyphenp);

        $dbsql2="SELECT * from agentcx where ( phone like '%$pattern%' OR phone2 like '%$pattern%' OR phone like '%$hyphenp%' OR phone2 like '%$hyphenp%' OR phone like '%$whitespp%' OR phone2 like '%$whitespp%') and status='waitcb' and callafter < (NOW() + INTERVAL 45 MINUTE) order by callafter desc limit 100";


//print "<pre>$dbsql2</pre>";

}else{

        $pattern = mysql_real_escape_string($pattern);


        $dbsql2="SELECT * from agentcx where ( name like '%$pattern%' OR phone like '%$pattern%' OR phone2 like '%$pattern%' ) and status='waitcb' and callafter < (NOW() + INTERVAL 45 MINUTE) order by callafter desc limit 100";


//print "<pre>$dbsql2</pre>";

}

}
$db2=new DB_Sql;
if ($db2->query($dbsql2))
{
        $count=array();
        //Action array
        $actionarr = array();
        $actionarr["act"]="create";
        $actionarr["del"]="delete";
        $actionarr["sus"]="suspend";
        $actionarr["res"]="restore";


        print "<table class=\"table table-hover\">
			<caption><h3>Call Back</h3></br></caption>
                                      <tr>
                                        <td width=\"15%\">Customer Name</td>
                                        <td width=\"25%\">Phone</td>
                                        <td width=\"45%\">Tasks</td>
                                        <td width=\"10%\">Result</td>
                                        <td width=\"5%\">Note</td>
                                      </tr>
                                    \n";
	
        while($db2->next_record()){
		$phone = $db2->f("phone");
                $phone2 = $db2->f("phone2");
                $email = $db2->f("email");
                $name = $db2->f("name");
                $eid = $db2->f("id");
                $status = $db2->f("status");
                $callagent = $db2->f("callagent");
                $callagent_id = $db2->f("agentid");
                $resstr = $db2->f("result");
                $callafter = $db2->f("callafter");
                $callbefore = $db2->f("callbefore");

                if($resstr == ""){
                        $resstr = '<span class="label label-info"> New </span>';
                }


                $phonestr = $phone;
                if($phone2!=""){
                        $phonestr .= " ( or ".$phone2." )";
                }
                //$tflag = $db2->f("testflag");

                $sourcestr=$source;
                if($tflag=="Y"){
                        $sourcestr=$source.'  <span class="label label-danger">TEST ACCOUNT</span>';
                }


                if($status=="active"){
                        $statuslabel = '<span class="label label-success">'.$status.'</span>';
                        $statusclass = "success Active pooltr All";
                        $btn = '<button type="button" class="btn btn-xs btn-warning funcbtn" id="sus'.$source.'">Suspend</button> <button type="button" class="btn btn-xs btn-danger funcbtn" id="del'.$source.'">Delete</button>';
                }else if($status=="process"){
                        $statuslabel = '<span class="label label-info">'.$status.'</span>';
                        $statusclass = "info Process pooltr All";
                        $btn = '<span class="label label-info">Processing '.$actionarr[$ocmd].'</span>';
                }else if($status=="suspend"){
                        $statuslabel = '<span class="label label-warning">'.$status.'</span>';
                        $statusclass = "warning Suspend pooltr All";
                        $btn = '<button type="button" class="btn btn-xs btn-primary funcbtn" id="res'.$source.'">Restore</button>';
                }else if($status=="failed"){
                        $statuslabel = '<span class="label label-danger">'.$status.'</span>&nbsp;&nbsp;&nbsp;<span class="label label-default">'.$ress.'</span>';
                        $statusclass = "NotInterested danger pooltr All";
                        $btn = '<span class="label label-default">Customer not interested</span>';
                }else if($status=="oncall"){
                        $statuslabel = '<span class="label label-primary">'.$status.'</span>';
                        $statusclass = "OnCall success pooltr All";
                        $btn = '<span class="label label-primary">Initializing '.$actionarr[$ocmd].'</span>';

                        $callingstr='<span class="label label-warning">'.$callagent.' is calling</span>';


                        // All Func
                        $btn = '<button type="button" class="btn btn-xs btn-warning funcbtn" id="noansw'.$eid.'">No Answer</button>&nbsp;&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-info funcbtn" id="voicem'.$eid.'">Voicemail</button>&nbsp;&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-danger funcbtn" id="nointr'.$eid.'">Not Interested</button>&nbsp;&nbsp;&nbsp;';
                        //$btn .= '<button type="button" class="btn btn-xs btn-default funcbtn" id="callbl'.$eid.'">Call Back Later</button>&nbsp;&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-default funcbtn callbl" data-id="'.$eid.'" data-toggle="modal" data-target="#myModal" id="callbl'.$eid.'">Call Back Later</button>&nbsp;&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-primary funcbtn" id="techsu'.$eid.'">Tech Support</button>&nbsp;&nbsp;&nbsp;';
//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

			if($callagent_id == $agent_id){
				$phonestr = $callingstr;
			}else{
				$btn = $callingstr;
			}

                }else if($status=="waitcb"){
                        $statuslabel = '<span class="label label-primary">Waiting Callback</span>';
                        $statusclass = "Init CallLater All info NeedCall";

                        // All Func
                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call Back</button>';
			if($callbefore!="0000-00-00 00:00:00"){
                        	$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$callafter.' ==> '.$callbefore.'</span> ) ';
			}else{
                        	$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$callafter.'</span> ) ';
			}
		
//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

                }else if($status=="init"){
                        $statuslabel = '<span class="label label-primary">'.$status.'</span>';
                        $statusclass = "Init NeedCall pooltr All";
                        $btn = '<span class="label label-primary">Initializing '.$actionarr[$ocmd].'</span>';

                        // All Func
                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call </button>';
//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

                }else{
                        $statuslabel = '<span class="label label-default">'.$status.'</span>';
                        $statusclass = "Available pooltr All";
                        $btn = '<button type="button" class="btn btn-mini btn-success funcbtn" id="act'.$source.'">Activate</button>';
                }

			$notestr = '<button type="button" class="btn btn-xs btn-default notebtn" data-id="'.$eid.'" data-name="'.$name.'" data-toggle="modal" data-target="#myNote" id="note'.$eid.'">Note</button>';
                print '<tr class="'.$statusclass.'"><td>'.$name.'</td>
                           <td>'.$phonestr.'</td>
                           <td>'.$btn.'</td>
                           <td>'.$resstr.'</td>
                           <td>'.$notestr.'</td>
                        </tr>';

        }
        print "</table>\n";
}
$db2->free();

}

} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}

?>
