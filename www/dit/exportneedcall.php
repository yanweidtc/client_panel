<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
   global $n;

	function array2csv(array &$array)
	{
	   if (count($array) == 0) {
	     return null;
	   }
	   ob_start();
	   $df = fopen("php://output", 'w');
	   //fputcsv($df, array_keys(reset($array)));
	   foreach ($array as $row) {
	      fputcsv($df, $row);
	   }
	   fclose($df);
	   return ob_get_clean();
	}

	function download_send_headers($filename) {
	    // disable caching
	    $now = gmdate("D, d M Y H:i:s");
	    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	    header("Last-Modified: {$now} GMT");

	    // force download  
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");

	    // disposition / encoding on response body
	    header("Content-Disposition: attachment;filename={$filename}");
	    header("Content-Transfer-Encoding: binary");
	}


	$exarr=array();

	$dbsql2="select name,phone,phone2 from agentcx where status='init' or status='waitcb'";
	$db2=new DB_Sql;
	if ($db2->query($dbsql2))
	{
		while($db2->next_record()){
			$exentry=array();	
			$exentry[0]=$db2->f("name");
			$exentry[1]=$db2->f("phone");
			$exentry[2]=$db2->f("phone2");
			$exarr[]=$exentry;
		}
	}
	$db2->free();


	download_send_headers("call_export_" . date("Y-m-d") . ".csv");
	echo array2csv($exarr);
	die();

} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}

?>
