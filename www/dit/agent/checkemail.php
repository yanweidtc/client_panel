<?php
include("database.php");
if (isset($_POST["email"]))
{
	$ip=getenv ("REMOTE_ADDR");
	//66.49.188.64
	if ($ip != "66.49.188.64")
	{
	  print "<b><i>Forbidden</i></b>";
	  exit;
	}


	// do the clean up
	$dcheck = new DB_Sql;
	$dcheck->query("select * from agents where email='".$_POST["email"]."'");
	if( $dcheck->num_rows() > 0){
		print "yes";
	}else{
		print "no";
	}

	$dcheck->free();


}else{
                print "No info passed in!";
}

?>
