<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {

if(isset($_POST['eid'],$_POST['notetext'])){

        //filter
        $eid = preg_replace("/[^0-9]/","",$_POST['eid']);
	$act = 'noteupdate';
	$notetext = addslashes($_POST['notetext']);
	

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
        $dbsql="select id,status from agentcx where id='$eid' limit 1";
        $db=new DB_Sql;
        if ($db->query($dbsql))
        {
                if($db->num_rows() > 0){
					
					$resstr = '<span class="label label-primary"> Waiting Callback </span>';
                                        $susql="UPDATE agentcx set note='$notetext',updatetime=NOW() where id='$eid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
						print '<div class="alert alert-success alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Success!</strong> Note successfully Saved!
							</div>';
                                                exit;
                                        }else{
						print '<div class="alert alert-danger alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Error!</strong> Can\'t submit request!
							</div>';
                                                exit;

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
