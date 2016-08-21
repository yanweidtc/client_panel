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
$dbsql2="select id, name, phone, phone2, email , status, callagent, agentid, result, callbefore, callafter, note, callnum, unlockflag,company from agentcx where !( status='oncall' and agentid='$agent_id' ) order by case when status='failed' then 2 else 1 end, status, result limit 100";
$dbsql2="SELECT  l.*
FROM    (
        SELECT  tstatus,
                COALESCE(
                (
                SELECT  id
                FROM    agentcx li
                WHERE   CONCAT( li.status, li.result) = dlo.tstatus
                ORDER BY
                        CONCAT( li.status, li.result), li.id
                LIMIT 50, 1
                ), CAST(0xFFFFFFFF AS DECIMAL)) AS mid
        FROM    (
                SELECT  DISTINCT CONCAT( status, result ) as tstatus
                FROM    agentcx dl
                ) dlo
        ) lo, agentcx l
WHERE   CONCAT( l.status,l.result) >= lo.tstatus
        AND CONCAT( l.status,l.result) <= lo.tstatus
        AND l.id <= lo.mid
ORDER BY case when l.status='failed' then 2 else 1 end, l.status, l.result";
}else{
$dbsql2="select SOURCE,DESTINATION,status,cmd,testflag,res from ChinaTelecom where (status='active' or status='suspend' or status='process' or status='init' or status='failed') and DESTINATION='".$_POST['sel']."' limit 1";
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


        print "<table class=\"table table-hover searchable\">
                                      <tr>
                                        <td width=\"12%\">Customer Name</td>
                                        <td width=\"22%\">Phone</td>
                                        <td width=\"46%\">Tasks</td>
                                        <td width=\"10%\">Result</td>
                                        <td width=\"5%\">Note</td>
                                        <td width=\"5%\">History</td>
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
                $notetext = $db2->f("note");
                $calltimes = $db2->f("callnum");
                $unlockflag = $db2->f("unlockflag");
                $company = $db2->f("company");

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
                }else if($status=="zorder"){
                        $statuslabel = '<span class="label label-success">'.$ress.'</span>';
                        $statusclass = "Ordered success pooltr All";
                        $btn = '<span class="label label-success">Customer submitted an order</span>';
                }else if($status=="invald"){
                        $statuslabel = '<span class="label label-danger">'.$status.'</span>&nbsp;&nbsp;&nbsp;<span class="label label-default">'.$ress.'</span>';
                        $statusclass = "Invalid danger pooltr All";
			if($resstr=='<span class="label label-danger"> Invalid Number </span>'){
                        	$btn = '<span class="label label-default">The phone number is invalid.</span>';
			}else{
                        	$btn = '<span class="label label-default">Customer not interested</span>';
			}
                }else if($status=="failed"){
                        $statuslabel = '<span class="label label-danger">'.$status.'</span>&nbsp;&nbsp;&nbsp;<span class="label label-default">'.$ress.'</span>';
                        $statusclass = "NotInterested danger pooltr All";
			if($resstr=='<span class="label label-danger"> Invalid Number </span>'){
                        	$btn = '<span class="label label-default">The phone number is invalid.</span>';
			}else{
                        	$btn = '<span class="label label-default">Customer not interested</span>';
			}
                }else if($status=="oncall"){
                        $statuslabel = '<span class="label label-primary">'.$status.'</span>';
                        $statusclass = "OnCall success pooltr All";
                        $btn = '<span class="label label-primary">Initializing '.$actionarr[$ocmd].'</span>';

                        $callingstr='<span class="label label-warning">'.$callagent.' is calling</span>';


                        // All Func
                        $btn = '<button type="button" class="btn btn-xs btn-warning funcbtn" id="noansw'.$eid.'">No Answer</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-info funcbtn" id="voicem'.$eid.'">Voicemail</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-danger funcbtn" id="nointr'.$eid.'">Not Interested</button>&nbsp;&nbsp;';
                        //$btn .= '<button type="button" class="btn btn-xs btn-default funcbtn" id="callbl'.$eid.'">Call Back Later</button>&nbsp;&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-default funcbtn callbl" data-id="'.$eid.'" data-toggle="modal" data-target="#myModal" id="callbl'.$eid.'">Call Back Later</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-primary funcbtn techsu" data-id="'.$eid.'" data-toggle="modal" data-target="#myModal2" id="techsu'.$eid.'"><span class="glyphicon glyphicon-wrench"></span>&nbsp;&nbsp;Tech Support</button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-primary funcbtn zorder" id="zorder'.$eid.'">&nbsp;&nbsp;Order Now</button>&nbsp;&nbsp;';
//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

			if($callagent_id == $agent_id){
				$phonestr = $callingstr;
			}else{
				if($utype=="main"){
					$btn = $callingstr.'&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-danger funcbtn hangup" id="hangup'.$eid.'"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;Force Hang Up </button>';
				}else{
					$btn = $callingstr;
				}
			}

                }else if($status=="techsu"){
                        $statuslabel = '<span class="label label-primary">Waiting Callback</span>';
                        $statusclass = "Init TechCallBack pooltr All info";

                        // All Func
			if($calltimes>=4){
				if($unlockflag!="Y"){
					$btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$eid.'" disabled> Call Back ( Locked )</button>';
				}else{
					$btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call Back </button>';
				}

				if($utype=="main" && $unlockflag!="Y"){
					$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-default funcbtn" id="unlock'.$eid.'"> Unlock </button>';
				}
			}else{
				$btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call Back </button>';
			}
			if($calltimes>0){
				$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$calltimes.' times</span>';
			}

			if($callbefore!="0000-00-00 00:00:00"){
                        	$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$callafter.' ==> '.$callbefore.'</span> ) ';
			}else{
                        	$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$callafter.'</span> ) ';
			}
		
//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

                }else if($status=="waitcb"){
                        $statuslabel = '<span class="label label-primary">Waiting Callback</span>';
                        $statusclass = "Init CallLater pooltr All info NeedCall";

			// How many times called
				

/*                        // All Func
			if($calltimes>=4){
				$btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$eid.'" disabled> Call Back ( Locked )</button>';
			}else{
				$btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call Back </button>';
			}
			if($calltimes>0){
				$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$calltimes.' times</span>';
			}

			if($callbefore!="0000-00-00 00:00:00"){
                        	$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$callafter.' ==> '.$callbefore.'</span> ) ';
			}else{
                        	$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( <span class="label label-primary">'.$callafter.'</span> ) ';
			}*/
		

			                        // All Func
                        if($calltimes>=4){
                                if($unlockflag!="Y"){
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$eid.'" disabled> Call Back ( Locked )</button>';
                                }else{
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call Back </button>';
                                }

                                if($utype=="main" && $unlockflag!="Y"){
                                        $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-default funcbtn" id="unlock'.$eid.'"> Unlock </button>';
                                }
                        }else{
                                $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call Back </button>';
                        }

                        if($calltimes>0){
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$calltimes.' times</span>';
                        }

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

/*                        // All Func
			if($calltimes>=4){
				$btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$eid.'" disabled> Call Back ( Locked )</button>';
			}else{
				$btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call Back </button>';
			}
			if($calltimes>0){
				$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$calltimes.' times</span>';
			}*/


                        // All Func
                        if($calltimes>=4){
                                if($unlockflag!="Y"){
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn disabled" id="oncall'.$eid.'" disabled> Call ( Locked )</button>';
                                }else{
                                        $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call </button>';
                                }

                                if($utype=="main" && $unlockflag!="Y"){
                                        $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-xs btn-default funcbtn" id="unlock'.$eid.'"> Unlock </button>';
                                }
                        }else{
                                $btn = '<button type="button" class="btn btn-xs btn-success funcbtn" id="oncall'.$eid.'"> Call </button>';
                        }
                        if($calltimes>0){
                                $btn .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-info"> Called '.$calltimes.' times</span>';
                        }


//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

                }else{
                        $statuslabel = '<span class="label label-default">'.$status.'</span>';
                        $statusclass = "Available pooltr All";
                        $btn = '<button type="button" class="btn btn-mini btn-success funcbtn" id="act'.$source.'">Activate</button>';
                }

		if($notetext==""){
			$notestr = '<button type="button" class="btn btn-xs btn-default notebtn" data-id="'.$eid.'" data-name="'.$name.'" data-toggle="modal" data-target="#myNote" id="note'.$eid.'">Note</button>';
		}else{
			$notestr = '<button type="button" class="btn btn-xs btn-primary notebtn" data-id="'.$eid.'" data-name="'.$name.'" data-toggle="modal" data-target="#myNote" id="note'.$eid.'">Note</button>';
		}

		$histbtn = '<a class="btn btn-xs btn-default" href="history.php?eid='.$eid.'"><span class="glyphicon glyphicon-list-alt"></span>&nbsp;&nbsp;Log </a>';

		$companystr='';
		if($company=='acanac'){
			$companystr='<span class="label label-primary" data-toggle="tooltip" data-placement="top" title="Acanac customer"> A</span>&nbsp;';
		}else if($company=='distributel'){
			$companystr='<span class="label label-success" data-toggle="tooltip" data-placement="top" title="Distributel customer"> D</span>&nbsp;';
		}

                print '<tr class="'.$statusclass.'">
			   <td>'.$companystr.$name.'</td>
                           <td>'.$phonestr.'</td>
                           <td>'.$btn.'</td>
                           <td>'.$resstr.'</td>
                           <td>'.$notestr.'</td>
                           <td>'.$histbtn.'</td>
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
