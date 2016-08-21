<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {

print '<script type="text/javascript" src="./js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>';
//       <script type="text/javascript" src="./js/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>';

if(isset($_GET['eid'])){

        //filter
        $eid = preg_replace("/[^0-9]/","",$_GET['eid']);
	$act = 'callbl';

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


print '<div class="input-append date form_datetime" data-date="2013-02-21T15:25:00Z">
    <input size="16" type="text" value="" readonly>
    <span class="add-on"><i class="icon-remove"></i></span>
    <span class="add-on"><i class="icon-calendar"></i></span>
</div>
 
<script type="text/javascript">
    $(".form_datetime").datetimepicker({
        format: "dd MM yyyy - hh:ii",
        autoclose: true,
        todayBtn: true,
        startDate: "2013-02-14 10:00",
        minuteStep: 10
    });
</script>  ';

print '<input size="16" type="text" value="2012-06-15 14:45" readonly class="form_datetime">
 
<script type="text/javascript">
    $(".form_datetime").datetimepicker({format: \'yyyy-mm-dd hh:ii\'});
</script> ';


}


if(isset($_POST['eid'],$_POST['date'],$_POST['time'])){

        //Chris, Number fuctions
        $dbsql="select id,status from agentcx where id='$eid' limit 1";
        $db=new DB_Sql;
        if ($db->query($dbsql))
        {
                if($db->num_rows() > 0){
                        $db->next_record();
                        $uid = $db->f("id");
                        $cstatus = $db->f("status");
                        //$ccmd = $db->f("cmd");

                                $newsql="";
                                //For new calls
                                if(($cstatus=='init' && $act=='oncall') || ($cstatus=='failed' && $act=='act')){
                                        //Canada number
					
					$resstr = '<span class="label label-success"> Oncall </span>';
                                        $susql="UPDATE agentcx set status='oncall',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
/*                                                print '<div>
                                                  <strong>Success!</strong> Request '.$act.' submitted for Chinese number '.$phone.' ( bind with '.$canumber.' ).
                                                </div>';*/
                                                exit;
                                        }else{
/*                                                print '<div>
                                                  <strong>Warning!</strong> Can\'t execute update!
                                                </div>';*/
                                                exit;

                                        }
                                }


				//No Answer or Voicemail
                                if($act=='noansw'){
                                        //Canada number
					$resstr = '<span class="label label-warning"> No Answer </span>';
                                        $susql="UPDATE agentcx set status='init',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
                                }
				

                                if($act=='voicem'){
                                        //Canada number
					$resstr = '<span class="label label-warning"> Voice Mail </span>';
                                        $susql="UPDATE agentcx set status='init',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
                                }


				//nointr:not interested
                                if($act=='nointr'){
                                        //Canada number
					$resstr = '<span class="label label-danger"> Not Interested </span>';
                                        $susql="UPDATE agentcx set status='failed',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
                                }


                                //?check current status?
                                //$usql="UPDATE agentcx set status='init',cmd='$act' where id='$uid' limit 1";
                                //$udb=new DB_Sql;
                                /*if ($udb->query($usql)){
                                        print '<div class="alert alert-success alert-dismissable">
                                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                          <strong>Success!</strong> Request '.$act.' submitted for Chinese number '.$phone.'.
                                        </div>';
                                        exit;
                                }else{
                                        print '<div class="alert alert-warning alert-dismissable">
                                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                          <strong>Warning!</strong> Can\'t excute update!
                                        </div>';
                                        exit;

                                }*/


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
}
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}
