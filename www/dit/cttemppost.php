<?
//  include_once('./js/header.php');
//  $ajax = false;

include 'database.php';
include 'functions.php';
/*sec_session_start();
if(login_check() == true) {*/

			        $uid = preg_replace("/[^0-9]/","",$_POST['eid']);
        			$act = $_POST['action'];

                                if($act=='sorder'){
					$poststr=$_POST["ppst"];

                                        //Canada number
					$resstr = '<span class="label label-success"> Order Submitted </span>';
                                        $susql="UPDATE agentcx set tmppost='$poststr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
						print "p";
                                        }else{
                                        }
                                }
/*
if(isset($_POST['action'],$_POST['eid'])){


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
        $dbsql="select id,status,name,phone,email,callnum,callagent,agentid,unlockflag from agentcx where id='$eid' limit 1";
        $db=new DB_Sql;
        if ($db->query($dbsql))
        {
                if($db->num_rows() > 0){
                        $db->next_record();
                        $uid = $db->f("id");
                        $cstatus = $db->f("status");
                        $cxname = $db->f("name");
                        $cxnumber = $db->f("phone");
                        $cxemail = $db->f("email");
                        $cxcallnum = $db->f("callnum");
                        $oncallagent = $db->f("callagent");
                        $oncallagentid = $db->f("agentid");
                        $unlockflag = $db->f("unlockflag");
                        //$ccmd = $db->f("cmd");

                                $newsql="";

				//sorder: order submitted

                }else{

                        print '<div class="alert alert-danger alert-dismissable">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <strong>Error!</strong> Can\'t find related Chinese number!
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
}*/
/*} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}*/
?>
