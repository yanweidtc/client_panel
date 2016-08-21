<?php

if (php_sapi_name() != 'cli')
   die();

require_once 'functions.php';
require_once 'db_connect.php';


$darray=array();
//delete all user data if no response for 5 min
	//grab sesid
	 if ($sesstmt = $mysqli->prepare("SELECT id,sesid,sestime FROM members")) {
	      $sesstmt->execute(); // Execute the prepared query.
	      $sesstmt->store_result();
	      if($sesstmt->num_rows > 0){
                $sesstmt->bind_result($ocid,$osesid, $osestime);
                while($sesstmt->fetch()){
			// check session file modification time
			$filename = '/var/lib/php5/sess_'.$osesid;
			if (file_exists($filename)) {
			    //echo "$filename was last accessed: " . date("F d Y H:i:s.", fileatime($filename));
			    //$otime = strtotime('+5 minutes', $osestime);
			    $time_now = mktime();
			    $atime = strtotime('+20 minutes', fileatime($filename));
			    //$atime = strtotime('+10 seconds', fileatime($filename));
			    //echo "$filename was last accessed: " . fileatime($filename)." and needs to compare to ".$otime."\n";
			    if($time_now > $atime){
				//get it to the delete-list
				$darray[]=$ocid;
			    }			    
			}	
	
			//for sesid=0(Temp Fix for IE bug)
			if($osesid==0){
			    $time_now2 = mktime();
			    $atime2 = strtotime('+20 minutes', $osestime);
			    if($time_now2 > $atime2){
				//get it to the delete-list
				$darray[]=$ocid;
			    }			    
			}
		}
	      }// num_rows

	}

	var_dump($darray);	
	
	foreach($darray as $cid){
		// Delete from pkg database.
		if ($c_stmt = $mysqli->prepare("DELETE FROM members_pkg WHERE mid = ?")) {
		   $c_stmt->bind_param('i', $cid);
		   // Execute the prepared query.
		   $c_stmt->execute();
		   $c_stmt->close();
		   //printf("Error: %s.\n", $c_stmt->sqlstate);
		}else{
		}

		// Delete from members database.
		if ($dm_stmt = $mysqli->prepare("DELETE FROM members WHERE id = ?")) {
		   $dm_stmt->bind_param('i', $cid);
		   // Execute the prepared query.
		   $dm_stmt->execute();
		   $dm_stmt->close();
		   //printf("Error: %s.\n", $d_stmt->sqlstate);
		}else{
		}
	}

?>
