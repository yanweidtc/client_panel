<?php
include 'db_connect.php';
include 'functions.php';


	$default = '<a href="update_email.php" id="upd-pbtn" class="btn btn-mini btn-success">Update Email</a>';

sec_session_start($mysqli);

if(login_check($mysqli,false) == true) {
	$user_id = $_SESSION['user_id'];

	$new_ctr = 0;
		if ($qstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? and type= 3 ORDER BY time desc limit 1")) {
		  $qstmt->bind_param('i', $user_id);
		  $qstmt->execute(); // Execute the prepared query.
		  $qstmt->store_result();

		   if($qstmt->num_rows > 0) { // If queue found
		     $qstmt->bind_result($q_id,$processed,$valid, $time, $counter);
		     $qstmt->fetch();
			if($processed=="done"){
				if($valid=="Y"){
					 if($counter < 5){
					 	print '<span class="label label-success">Success</span>&nbsp;&nbsp;<a href="update_email.php" id="upd-pbtn" class="btn btn-mini btn-success">Update Email</a>';
						$new_ctr = $counter + 1;
					 }else{
						$new_ctr = 5;
					 	print $default;
					 }
				}else if($valid=="N"){
					 print '<span class="label label-important">Failed</span>&nbsp;&nbsp;<a href="update_email.php" id="upd-pbtn" class="btn btn-mini btn-success">Update Email</a>';
				}
			}else if($processed=="processing"){
				print '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span>';
			}else if($processed=="skip"){
				if($counter < 5){
					print '<span class="label label-warning">Skipped due to too many requests</span>&nbsp;&nbsp;<a href="update_email.php" id="upd-pbtn" class="btn btn-mini btn-success">Update Email</a>';
					$new_ctr = $counter + 1;
					
					
				}else{
					$new_ctr = 5;
					print $default;
				}

			}else{
				print '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-info">Initializing</span>';
			}
		   }else{// if queue not found
			print $default;	
		   }
		}else{//if fail DB
			print $default;
		}

        if ($update_stmt = $mysqli->prepare("UPDATE members_login SET counter = ? WHERE id = ?")) {
                $update_stmt->bind_param('ii', $new_ctr,$q_id);
                // Execute the prepared query.
                $update_stmt->execute();
                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
        }
	


}else{	//login session check fail
	log_bad("Tried direct access refreshemail",$mysqli);
}

?>
