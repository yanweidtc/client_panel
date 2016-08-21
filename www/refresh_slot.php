<?php
include 'db_connect.php';
include 'functions.php';


//	$default = '<a href="update_info.php" id="upd-btn" class="btn btn-mini btn-success">Update Info</a>';

sec_session_start($mysqli,false);

function grabBday($userid,$pkg_id,$slotid,$mysqli){
	if ($qstmt2 = $mysqli->prepare("SELECT stblist,maxstb FROM members_pkg WHERE id = ? and mid = ? limit 1")) {
                  $qstmt2->bind_param('ii', $pkg_id,$userid);
                  $qstmt2->execute(); // Execute the prepared query.
                  $qstmt2->store_result();

                   if($qstmt2->num_rows > 0) { // If queue found
                     $qstmt2->bind_result($stblist,$maxstb);
                     $qstmt2->fetch();

		     $ret="";
//			$stbtn2='<button type="button" class="btn-mini btn-danger dslotbtn" id="dslot'.$slotid.'">Remove</button>';
					
                   $stblist_r = explode("+",$stblist);
                   $curstbcount = sizeof($stblist_r);

		     if( ($slotid+$curstbcount) <= $maxstb){
			$ret.= '<td>Empty Slot</td>
				<td>Waiting on registration</td>
				<td><span class="label label-success">Waiting on registration</span></td>
				<td>N/A</td>';
//				<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn2.'</div></td>';
			return $ret;
		     }else{
			return '';
		     }
		    }else{//if found
			return '';
		    } 

	}else{//if fail db
		return '';
	}	
}

//print grabBday(299,299,$mysqli);
//print "Hreer";

if(login_check($mysqli,false) == true) {
	$user_id = $_SESSION['user_id'];
	if(isset($_POST['pkg'])){
		$pkg_id = $_POST['pkg'];
	}else if(isset($_GET['pkg'])){
		$pkg_id = $_GET['pkg'];
	}

	$slotid = $_POST['slot'];

	$stbtn2='<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right"><button class="btn btn-mini btn-danger dslotbtn" id="dslot'.$slotid.'">Remove</button>';


//	print $pkg_id;
		$new_ctr = 0;
                if ($qstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? AND email=? AND type = 10 ORDER BY time desc limit 1")) {
                  $qstmt->bind_param('ii', $user_id, $slotid);
                  $qstmt->execute(); // Execute the prepared query.
                  $qstmt->store_result();

                   if($qstmt->num_rows > 0) { // If queue found
                     $qstmt->bind_result($q_id,$processed,$valid, $time, $counter);
                     $qstmt->fetch();
                        if($processed=="done"){
                                if($valid=="Y"){
                                         if($counter < 5){
                                                print grabBday($user_id,$pkg_id,$slotid,$mysqli).$stbtn2.'</div></td>';
                                                $new_ctr = $counter + 1;
                                         }else{
                                                $new_ctr = 5;
                                                print grabBday($user_id,$pkg_id,$slotid,$mysqli).$stbtn2.'</div></td>';
                                         }
                                }else if($valid=="N"){
                                         print grabBday($user_id,$pkg_id,$slotid,$mysqli).$stbtn2.'<span class="label label-important">Failed</span></div></td>';
                                }
                        }else if($processed=="processing"){
                                print grabBday($user_id,$pkg_id,$slotid,$mysqli).'<td><div class="pull-right"><img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span></div></td>';
                        }else if($processed=="skip"){
                                if($counter < 5){
                                        print grabBday($user_id,$pkg_id,$slotid,$mysqli).$stbtn2.'<span class="label label-warning">Skipped due to too many requests</span></div></td>';
                                        $new_ctr = $counter + 1;


                                }else{
                                        $new_ctr = 5;
                                        print grabBday($user_id,$pkg_id,$slotid,$mysqli).$stbtn2.'</div></td>';
                                }

                        }else{
                                print grabBday($user_id,$pkg_id,$slotid,$mysqli).'<td><div class="pull-right"><img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-info">Initializing</span></div></td>';
                        }
                   }else{// if queue not found
                        print grabBday($user_id,$pkg_id,$slotid,$mysqli).$stbtn2.'</div></td>';
                   }
                }else{//if fail DB
                        print grabBday($user_id,$pkg_id,$slotid,$mysqli).$stbtn2.'</div></td>';
                }

        if ($update_stmt = $mysqli->prepare("UPDATE members_login SET counter = ? WHERE id = ?")) {
                $update_stmt->bind_param('ii', $new_ctr,$q_id);
                // Execute the prepared query.
                $update_stmt->execute();
                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
        }





}else{	//login session check fail
	log_bad("Tried direct access refreshstbtable",$mysqli);
}

?>
