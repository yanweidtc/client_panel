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
$dbsql2="select id, name, phone, phone2, email , status, callagent, agentid, result, callbefore, callafter,company, note from agentcx where status='oncall' and agentid='$agent_id'";
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


/*                                      <tr>
                                        <td width=\"15%\">Customer Name</td>
                                        <td width=\"25%\">Phone</td>
                                        <td width=\"45%\">Tasks</td>
                                        <td width=\"10%\">Result</td>
                                        <td width=\"5%\">Note</td>
                                      </tr>*/
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
		$company = $db2->f("company");
		$notetext = $db2->f("note");

                if($resstr == ""){
                        $resstr = '<span class="label label-info"> New </span>';
                }

        print "<h4>Your Call: </h4>
		<table class=\"table table-hover\">
			<tr>
                                        <td width=\"15%\">Customer Name</td>
                                        <td width=\"28%\">Phone</td>
                                        <td width=\"42%\">Tasks</td>
                                        <td width=\"10%\">Result</td>
                                        <td width=\"5%\">Note</td>
                                      </tr>
                                    \n";

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
                }else if($status=="oncall"){
                        $statuslabel = '<span class="label label-primary">'.$status.'</span>';
                        $statusclass = "OnCall success All";
                        $btn = '<span class="label label-primary">Initializing '.$actionarr[$ocmd].'</span>';

                        $callingstr='<span class="label label-warning">'.$callagent.' is calling</span>';

			$actarr = $_POST["actarr"];
			$aarr = explode(",",$actarr);
			$activearr = Array(
				"noansw" => "",
				"voicem" => "",
				"invald" => "",
				"nointr" => "",
				"callbl" => "",
				"techsu" => "",
				"sendem" => "",
				"zorder" => ""
			);
			$iconarr = Array(
				"noansw" => "",
				"voicem" => "",
				"invald" => "",
				"nointr" => "",
				"callbl" => "",
				"techsu" => "",
				"sendem" => "",
				"zorder" => ""
			);

			foreach($aarr as $acc){
				$activearr[$acc]=" active";
				$iconarr[$acc]=" <span class=\"glyphicon glyphicon-ok icon-success\"></span>";
			}



			$callblstr="";
			if(isset($_POST["cbarr"]) && $_POST["cbarr"]!="" && $activearr["callbl"]==" active"){
				list($callafter,$callbefore)=explode(",",$_POST["cbarr"]);
				if($callbefore!="0000-00-00 00:00:00" && $callbefore!=""){
					$callblstr .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-primary">'.$callafter.' ==> '.$callbefore.'</span>  ';
				}else{
					$callblstr .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-primary">'.$callafter.'</span>  ';
				}
			}

			$techcallblstr="";
			if(isset($_POST["techcbarr"]) && $_POST["techcbarr"]!="" && $activearr["techsu"]==" active"){
				list($tcallafter,$tcallbefore)=explode(",",$_POST["techcbarr"]);
				if($tcallbefore!="0000-00-00 00:00:00" && $tcallbefore!=""){
					$techcallblstr .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-primary">'.$tcallafter.' ==> '.$tcallbefore.'</span>  ';
				}else{
					$techcallblstr .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="label label-primary">'.$tcallafter.'</span>  ';
				}
			}


                        // All Func
                        $btn = '<button type="button" class="btn btn-xs btn-warning yfuncbtn'.$activearr["noansw"].'" id="noansw'.$eid.'">No Answer</button>&nbsp;&nbsp;&nbsp;'.$iconarr["noansw"].'<br>';
                        $btn .= '<button type="button" class="btn btn-xs btn-info yfuncbtn'.$activearr["voicem"].'" id="voicem'.$eid.'">Voicemail</button>&nbsp;&nbsp;&nbsp;'.$iconarr["voicem"].'<br>';
                        $btn .= '<button type="button" class="btn btn-xs btn-danger yfuncbtn'.$activearr["invald"].'" id="invald'.$eid.'">Invalid Phone</button>&nbsp;&nbsp;&nbsp;'.$iconarr["invald"].'<br>';
                        $btn .= '<button type="button" class="btn btn-xs btn-danger yfuncbtn'.$activearr["nointr"].'" id="nointr'.$eid.'">Not Interested</button>&nbsp;&nbsp;&nbsp;'.$iconarr["nointr"].'<br>';
                        //$btn .= '<button type="button" class="btn btn-xs btn-default funcbtn" id="callbl'.$eid.'">Call Back Later</button>&nbsp;&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-xs btn-default yfuncbtn callbl'.$activearr["callbl"].'" data-id="'.$eid.'" data-target="#myModal" id="callbl'.$eid.'">Call Back Later</button>&nbsp;&nbsp;&nbsp;'.$iconarr["callbl"].$callblstr.'<br>';
			$btn .= '<button type="button" class="btn btn-xs btn-primary yfuncbtn techsu'.$activearr["techsu"].'" data-id="'.$eid.'" data-target="#myModal2" id="techsu'.$eid.'"><span class="glyphicon glyphicon-wrench"></span>&nbsp;&nbsp;Tech Support</button>&nbsp;&nbsp;'.$iconarr["techsu"].$techcallblstr.'<br>';
			$btn .= '<button type="button" class="btn btn-xs btn-primary yfuncbtn sendem'.$activearr["sendem"].'" id="sendem'.$eid.'"><span class="glyphicon glyphicon-envelope"></span>&nbsp;&nbsp;Send Email</button>&nbsp;&nbsp;'.$iconarr["sendem"].'<br>';
                        $btn .= '<div class="pull-right"><button type="button" class="btn btn-sm btn-success donebtn" id="godone'.$eid.'"> Done </button>&nbsp;&nbsp;';
                        $btn .= '<button type="button" class="btn btn-sm btn-success donebtn zorder" id="zorder'.$eid.'"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;&nbsp;Order Now </button>&nbsp;&nbsp;</div>';
//                      $btn .= '<button type="button" class="btn btn-mini btn-success funcbtn" id="oncall'.$eid.'"></button>';

			if($callagent_id == $agent_id){
				$phonestr = $phonestr."<br>".$callingstr;
			}else{
				$btn = $callingstr;
			}

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
