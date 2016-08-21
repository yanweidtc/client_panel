<?php
include 'db_connect.php';
include 'functions.php';

sec_session_start($mysqli,false);

//$validarray= array("ccname","ccnumber","ccexpiry","email","phone","phone2");

	$default = '';
if(login_check($mysqli,false) == true && isset($_GET["field"]) ) {	
	     require_once "encryption.php";
	     $tmpfield = $converter->decode($_GET["field"]);

	$field=preg_replace("/[^a-zA-Z0-9\,]/","",$tmpfield);
	if($field != $tmpfield){
		print "bad attempt!";
		exit;
	}

	$user_id = $_SESSION['user_id'];

		if ($qstmt2 = $mysqli->prepare("SELECT p.id,p.stblist FROM members m, members_pkg p WHERE p.mid=m.id and m.id=? limit 1")) {
		  $qstmt2->bind_param('i', $user_id);
		  $qstmt2->execute(); // Execute the prepared query.
                  $qstmt2->store_result();

                   if($qstmt2->num_rows > 0) { // If queue found
                     $qstmt2->bind_result($aid,$stblist);
                     $qstmt2->fetch();

			if($stblist != ""){
				   $eaid = $converter->encode($aid);
				   $stblist_r = explode("+",$stblist);
				   foreach($stblist_r as $stb_e){
 					   $stb = explode(",",$stb_e);
					   if($field == $stb[0]){
						$stb3 = $stb[3];
						   if($stb[3]==""){
							$stb3 = "N/A";
						   }
					
						   $estbid = $converter->encode($stb[0]);
						   $stbtn = '<form style="margin: 0 0 1px;float:right;" action="delete_cli_stb.php" method="post" onsubmit="return confirm(\'This action will REMOVE this STB box, are you sure to continue?\');"><input type="hidden" id="sid" name="sid" value="'.$estbid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';



				$new_ctr = 0;
				if ($qstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? and type= 5 and pid = ? ORDER BY time desc limit 1")) {
				  $qstmt->bind_param('is', $user_id,$estbid);
				  $qstmt->execute(); // Execute the prepared query.
				  $qstmt->store_result();

				   if($qstmt->num_rows > 0) { // If queue found
				     $qstmt->bind_result($q_id,$processed,$valid, $time, $counter);
				     $qstmt->fetch();
					if($processed=="done"){
						if($valid=="Y"){
							 if($counter < 5){
								$stbtn = '<span class="label label-success">Success</span>&nbsp;&nbsp;'.$stbtn;
								$new_ctr = $counter + 1;
							 }else{
								$new_ctr = 5;
							 }
						}else if($valid=="N" || $valid=="E"){
							 $stbtn = '<span class="label label-important">Failed</span>&nbsp;&nbsp;'.$stbtn;
						}
					}else if($processed=="processing"){
						$stbtn = '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span>';
					}else if($processed=="skip"){
						if($counter < 5){
							$stbtn = '<span class="label label-warning">Skipped due to too many requests</span>&nbsp;&nbsp;'.$stbtn;
							$new_ctr = $counter + 1;


						}else{
							$new_ctr = 5;
						}

					}else{
						$stbtn =  '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-info">Initializing</span>';
					}
				   }else{// if queue not found
				   }
				}else{//if fail DB
				}

			if ($update_stmt = $mysqli->prepare("UPDATE members_login SET counter = ? WHERE id = ?")) {
				$update_stmt->bind_param('ii', $new_ctr,$q_id);
				// Execute the prepared query.
				$update_stmt->execute();
				//printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
			}

					$stb4="";
					   if(isset($stb[4])){
						$beforedash = explode("_",$stb[4]);
						$stb4=$beforedash[0];
					   }

					   $stb5="";
					   if(isset($stb[5])){
	//                                                      $beforedash = explode("_",$stb[4]);
						$stb5=$stb[5];
					   }
						echo '
							<td>'.$stb[0].'</td>
							<td>'.$stb[1].'</td>
							<td><span class="label label-success">'.$stb[2].'</span></td>
							<td>'.$stb4.'</td>
                                                        <td>'.$stb5.'</td>
							<td>'.$stb3.'</td>
							<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn.'</div></td>
						      ';
					   }else{
						print $default;
					   }
				   }
			   }else{
				print $default;
			   }
			}else{//if found
				print $default;
			}
			
		}else{//if fail db
			print $default;
		}


}else{	//login session check fail
	log_bad("Tried direct access refreshstb",$mysqli);
}

?>
