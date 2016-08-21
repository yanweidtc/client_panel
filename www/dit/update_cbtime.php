<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {

if(isset($_POST['eid'],$_POST['dtp_input1'],$_POST['dtp_input2'])){

        //filter
        $eid = preg_replace("/[^0-9]/","",$_POST['eid']);
	$act = 'callbl';
	if($_POST['dtp_input1']!=""){
		$callafter = substr($_POST['dtp_input1'],0,-2)."00";
	}else{
		$callafter = "0000-00-00 00:00:00";

		print '<div class="alert alert-danger alert-dismissable">
			  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			  <strong>Error!</strong> Please choose a valid time!
			</div>';
		exit;
	}

	if($_POST['dtp_input2']!=""){
		$callbefore = substr($_POST['dtp_input2'],0,-2)."00";
	}else{
		$callbefore = "0000-00-00 00:00:00";
	}
	

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


        $today = date('M');

        //Chris, Number fuctions
        $dbsql="select id,status,name from agentcx where id='$eid' limit 1";
        $db=new DB_Sql;
        if ($db->query($dbsql))
        {
                if($db->num_rows() > 0){
                        $db->next_record();
                        $uid = $db->f("id");
                        $cstatus = $db->f("status");
                        $cxname = $db->f("name");
                        //$ccmd = $db->f("cmd");

                                $newsql="";
                                //For new calls
                                if($act=='callbl'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) called '.$cxname.' ( '.$uid.' ), customer requires call back after '.$callafter;
					if($callbefore=="0000-00-00 00:00:00"){
						$event.=' .';
					}else{
						$event.=', before '.$callbefore.'. ';
					}
                                        logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					
					$resstr = '<span class="label label-primary"> Waiting Callback </span>';
                                        $susql="UPDATE agentcx set status='waitcb',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr',callafter='$callafter', callbefore='$callbefore' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
						print '<div class="alert alert-success alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Success!</strong> '.$cxname.'\'s call back request submitted!
							</div>';
                                                exit;
                                        }else{
						print '<div class="alert alert-danger alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Error!</strong> Can\'t submit request!
							</div>';
                                                exit;

                                        }
                                }
                }else{

                        print '<div class="alert alert-danger alert-dismissable">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <strong>Error!</strong> Can\'t find related entry!
                                </div>';
                        exit;

                }
        }else{
                print '<div class="alert alert-danger alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <strong>Error!</strong> Can\'t connect to database!
                        </div>';
                exit;
        }
}
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}
