<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';

$salearr = array();
$stmt = new DB_Sql;
if ($stmt->query("SELECT * from agents where SIP <> ''")) {
	while($stmt->next_record()){
		$activeaccount = $stmt->f("activeaccounts");
		$gsip = $stmt->f("SIP");

		$salearr[]=$gsip.','.$activeaccount;	
	}
	$result = implode('+',$salearr);
	$result = str_replace("'","",$result);
        $result = str_replace("("," ",$result);
        $result = str_replace(")"," ",$result);
        print $result;
        $result=urlencode($result);
        print `curl -ks -m 10 -d id='whole' -d reply='$result' https://66.49.208.48/graborder.php`;

}

?>

