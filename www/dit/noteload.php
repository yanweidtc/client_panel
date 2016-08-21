<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {

if(isset($_POST['eid'])){

        //filter
        $eid = preg_replace("/[^0-9]/","",$_POST['eid']);
	$act = 'loadnote';

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
        $dbsql="select id,status,note from agentcx where id='$eid' limit 1";
        $db=new DB_Sql;
        if ($db->query($dbsql))
        {
                if($db->num_rows() > 0){
                        $db->next_record();
                        $uid = $db->f("id");
                        $cstatus = $db->f("status");
                        $note = $db->f("note");
			print $note;

                }
        }
}

} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}
