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

	//Chris, Number Pool
	if(isset($_POST["cate"]) && $_POST["cate"]!=""){
		//Init
		$countarray=array(
		    "failed" => 0,
		    "init" => 0,
		    "invald" => 0,
		    "oncall" => 0,
		    "techsu" => 0,
		    "waitcb" => 0,
		    "zorder" => 0
		);

		$activearr=array(
			"All"=>"",
			"NeedCall"=>"",
			"OnCall"=>"",
			"CallLater"=>"",
			"French"=>"",
			"TechCallBack"=>"",
			"NotInterested"=>"",
			"Invalid"=>"",
			"Ordered"=>""
		);

		$category = $_POST["cate"];
		$activearr[$category]=" active";


/*		if($_POST["cate"]=="All"){
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
		}*/

	$dbsql2="SELECT COUNT(*) as count, status FROM `agentcx` WHERE 1 GROUP BY status";
	$db2=new DB_Sql;
	if ($db2->query($dbsql2))
	{
		while($db2->next_record()){
                        $key = $db2->f("status");
                        $value = $db2->f("count");
                        $countarray[$key]=$value;
                }
	}
	$db2->free();

	$frenchcount=0;
	// Count French
	$dbsql3="SELECT COUNT(*) as count, result FROM `agentcx` WHERE result='<span class=\"label label-info\"> French </span>'";
	$db3=new DB_Sql;
	if ($db3->query($dbsql3))
	{
		while($db3->next_record()){
                        $value = $db3->f("count");
			$frenchcount=$value;
                }
	}
	$db3->free();

echo '<ul class="nav nav-pills">
  	  <li class="shortcut defaultsh'.$activearr["All"].'">
            <a href="#">All ('.array_sum($countarray).')</a>
          </li>
          <li class="shortcut'.$activearr["NeedCall"].'">
            <a href="#">Need Call ('.( $countarray["init"] + $countarray["waitcb"] ).')</a>
          </li>
          <li class="shortcut'.$activearr["OnCall"].'">
            <a href="#">On Call ('.$countarray["oncall"].')</a>
          </li>
          <li class="shortcut'.$activearr["CallLater"].'">
            <a href="#">Call Later ('.$countarray["waitcb"].')</a>
          </li>
          <li class="shortcut'.$activearr["French"].'">
            <a href="#">French ('.$frenchcount.')</a>
          </li>
          <li class="shortcut'.$activearr["NotInterested"].'">
            <a href="#">Not Interested ('.$countarray["failed"].')</a>
          </li>
          <li class="shortcut'.$activearr["Invalid"].'">
            <a href="#">Invalid ('.$countarray["invald"].')</a>
          </li>
          <li class="shortcut'.$activearr["TechCallBack"].'">
            <a href="#">Tech Call Back ('.$countarray["techsu"].')</a>
          </li>
          <li class="shortcut'.$activearr["Ordered"].'">
            <a href="#">Ordered ('.$countarray["zorder"].')</a>
          </li>
        </ul>';


	}else{
	echo '<ul class="nav nav-pills">
		  <li class="shortcut defaultsh'.$activearr["All"].'">
		    <a href="#">All ('.array_sum($countarray).')</a>
		  </li>
		  <li class="shortcut'.$activearr["NeedCall"].'">
		    <a href="#">Need Call ('.( $countarray["init"] + $countarray["waitcb"] ).')</a>
		  </li>
		  <li class="shortcut'.$activearr["OnCall"].'">
		    <a href="#">On Call ('.$countarray["oncall"].')</a>
		  </li>
		  <li class="shortcut'.$activearr["CallLater"].'">
		    <a href="#">Call Later ('.$countarray["waitcb"].')</a>
		  </li>
		  <li class="shortcut'.$activearr["French"].'">
		    <a href="#">French ('.$frenchcount.')</a>
		  </li>
		  <li class="shortcut'.$activearr["NotInterested"].'">
		    <a href="#">Not Interested ('.$countarray["failed"].')</a>
		  </li>
		  <li class="shortcut'.$activearr["Invalid"].'">
		    <a href="#">Invalid ('.$countarray["invald"].')</a>
		  </li>
		  <li class="shortcut'.$activearr["TechCallBack"].'">
		    <a href="#">Tech Call Back ('.$countarray["techsu"].')</a>
		  </li>
		  <li class="shortcut'.$activearr["Ordered"].'">
		    <a href="#">Ordered ('.$countarray["zorder"].')</a>
		  </li>
		</ul>';

	}
}else{
//		print $catestr." ($number) ";
echo '<ul class="nav nav-pills">
  	  <li class="shortcut defaultsh active">
            <a href="#">All (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Need Call (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">On Call (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Call Later (0)</a>
          </li>
	  <li class="shortcut">
	    <a href="#">French (0)</a>
	  </li>
          <li class="shortcut">
            <a href="#">Not Interested (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Invalid (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Tech Call Back (0)</a>
          </li>
          <li class="shortcut">
            <a href="#">Ordered (0)</a>
          </li>
        </ul>';

}
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}

?>
