<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
   global $n;
$number =0;
if($_POST["cate"]!=""){

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
	$stmt->free();

	//Chris, Number Pool
	if(isset($_POST["cate"]) && $_POST["cate"]!=""){
		if($_POST["cate"]=="All"){
			$dbsql2="select id from agentcx where 1";
			$catestr="All";
		}else if($_POST["cate"]=="NeedCall"){
			$dbsql2="select id from agentcx where status='init' or status='waitcb'";
			$catestr="Need Call";
		}else if($_POST["cate"]=="OnCall"){
			$dbsql2="select id from agentcx where status='oncall'";
			$catestr="On Call";
		}else if($_POST["cate"]=="CallLater"){
			$dbsql2="select id from agentcx where status='waitcb'";
			$catestr="Call Later";
		}else if($_POST["cate"]=="TechCallBack"){
			$dbsql2="select id from agentcx where status='techsu'";
			$catestr="Tech Call Back";
		}else if($_POST["cate"]=="NotInterested"){
			$dbsql2="select id from agentcx where status='failed'";
			$catestr="Not Interested";
		}else if($_POST["cate"]=="Invalid"){
			$dbsql2="select id from agentcx where status='invald'";
			$catestr="Invalid";
		}else if($_POST["cate"]=="Ordered"){
			$dbsql2="select id from agentcx where status='sorder' or status='zorder'";
			$catestr="Ordered";
		}

	$db2=new DB_Sql;
	if ($db2->query($dbsql2))
	{
		$number = $db2->num_rows();
		echo $catestr." ($number) ";

	}
	$db2->free();

	}else{
		print $catestr." ($number) ";
	}
}else{
		print $catestr." ($number) ";
}
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}

?>
