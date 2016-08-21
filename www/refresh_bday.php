<?php
include 'db_connect.php';
include 'functions.php';


//	$default = '<a href="update_info.php" id="upd-btn" class="btn btn-mini btn-success">Update Info</a>';

sec_session_start($mysqli,false);

function grabBday($userid,$mysqli){
	if ($qstmt2 = $mysqli->prepare("SELECT birthday FROM members WHERE id = ? limit 1")) {
                  $qstmt2->bind_param('i', $userid);
                  $qstmt2->execute(); // Execute the prepared query.
                  $qstmt2->store_result();

                   if($qstmt2->num_rows > 0) { // If queue found
                     $qstmt2->bind_result($bday);
                     $qstmt2->fetch();

			$bstr = substr($bday,5);
				if($bstr=="00-00"){
					return '<a href="update_bday.php" id="updbday-btn" class="btn btn-mini btn-success">Submit Birthday</a>';
				}else{
					return $bstr;
				}
			}else{//if found
				return '';
			}

	}else{//if fail db
		return '';
	}	
}


if(login_check($mysqli,false) == true) {
	$user_id = $_SESSION['user_id'];



	$new_ctr = 0;
		if ($qstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? AND type = 6 ORDER BY time desc limit 1")) {
		  $qstmt->bind_param('i', $user_id);
		  $qstmt->execute(); // Execute the prepared query.
		  $qstmt->store_result();

		   if($qstmt->num_rows > 0) { // If queue found
		     $qstmt->bind_result($q_id,$processed,$valid, $time, $counter);
		     $qstmt->fetch();
			if($processed=="done"){
				if($valid=="Y"){
					 if($counter < 5){
					 	print grabBday($user_id,$mysqli).'&nbsp;&nbsp;<span class="label label-success">Success</span>';
						$new_ctr = $counter + 1;
					 }else{
						$new_ctr = 5;
					 	print grabBday($user_id,$mysqli);
					 }
				}else if($valid=="N"){
					 print '<span class="label label-important">Failed</span>&nbsp;&nbsp;<a href="update_bday.php" id="updbday-btn" class="btn btn-mini btn-success">Submit Birthday</a>';
				}
			}else if($processed=="processing"){
				print '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span>';
			}else if($processed=="skip"){
				if($counter < 5){
					print '<span class="label label-warning">Skipped due to too many requests</span>&nbsp;&nbsp;<a href="update_bday.php" id="updbday-btn" class="btn btn-mini btn-success">Submit Birthday</a>';
					$new_ctr = $counter + 1;
					
					
				}else{
					$new_ctr = 5;
					print grabBday($user_id,$mysqli);
				}

			}else{
				print '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-info">Initializing</span>';
			}
		   }else{// if queue not found
			print grabBday($user_id,$mysqli);	
		   }
		}else{//if fail DB
			print grabBday($user_id,$mysqli);
		}

        if ($update_stmt = $mysqli->prepare("UPDATE members_login SET counter = ? WHERE id = ?")) {
                $update_stmt->bind_param('ii', $new_ctr,$q_id);
                // Execute the prepared query.
                $update_stmt->execute();
                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
        }
	


}else{	//login session check fail
	log_bad("Tried direct access refreshbday",$mysqli);
}

?>
