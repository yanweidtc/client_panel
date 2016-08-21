<?php
// From control centre
$ip=getenv ("REMOTE_ADDR");
if ($ip != "66.49.208.48")
{
  print "<b><i>Forbidden</i></b>";
  exit;
}
include 'database.php';
include 'functions.php';

if(isset($_POST['cxeid'], $_POST['tname'], $_POST['cxname']) ) {
   //preprocess
   foreach($_POST as $pkey => $postfield){
        $_POST[$pkey]=str_replace("'","",$postfield);
   }


   $cxeid = $_POST['cxeid'];
   $techname = $_POST['tname'];
   $cxname = $_POST['cxname'];

   if($cxeid!=0){
	$event = 'TechSupport '.$techname.' called back '.$cxname.' ( '.$cxeid.' ).';
	logevent('10086',$cxeid,$event,'techsu',true);

	$resstr = '<span class="label label-success"> Tech Support Called Back </span>';
	$susql="UPDATE agentcx set result='$resstr' where id='$cxeid' and status='techsu' limit 1";
	$sudb=new DB_Sql;
	if ($sudb->query($susql)){
	}else{
	}
   }
}
?>
