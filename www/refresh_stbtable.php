<?php
include 'db_connect.php';
include 'functions.php';


//	$default = '<a href="update_info.php" id="upd-btn" class="btn btn-mini btn-success">Update Info</a>';

sec_session_start($mysqli,false);


function printSlot($user_id,$pkg_id,$slotid,$mysqli){
	$prestr = '<td>Empty Slot</td>
                                <td>Waiting on registration</td>
                                <td><span class="label label-success">Waiting on registration</span></td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>N/A</td>';

        $stbtn2='<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right"><button class="btn btn-mini btn-danger dslotbtn" id="dslot'.$slotid.'">Remove</button>';



	$ret="";
//      print $pkg_id;
                $new_ctr = 0;
                if ($sqstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? AND email=? AND type = 10 ORDER BY time desc limit 1")) {
                  $sqstmt->bind_param('ii', $user_id, $slotid);
                  $sqstmt->execute(); // Execute the prepared query.
                  $sqstmt->store_result();

                   if($sqstmt->num_rows > 0) { // If queue found
                     $sqstmt->bind_result($q_id,$processed,$valid, $time, $counter);
                     $sqstmt->fetch();
                        if($processed=="done"){
                                if($valid=="Y"){
                                         if($counter < 5){
                                                $ret.= $prestr.$stbtn2.'</div></td>';
                                                $new_ctr = $counter + 1;
                                         }else{
                                                $new_ctr = 5;
                                                $ret.= $prestr.$stbtn2.'</div></td>';
                                         }
                                }else if($valid=="N"){
                                         $ret.= $prestr.$stbtn2.'<span class="label label-important">Failed</span></div></td>';
                                }
                        }else if($processed=="processing"){
                                $ret.= $prestr.'<td><div class="pull-right"><img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span></div></td>';
                        }else if($processed=="skip"){
                                if($counter < 5){
                                        $ret.= $prestr.$stbtn2.'<span class="label label-warning">Skipped due to too many requests</span></div></td>';
                                        $new_ctr = $counter + 1;


                                }else{
                                        $new_ctr = 5;
                                        $ret.= $prestr.$stbtn2.'</div></td>';
                                }

                        }else{
                                $ret.= $prestr.'<td><div class="pull-right"><img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-info">Initializing</span></div></td>';
                        }
                   }else{// if queue not found
                        $ret.= $prestr.$stbtn2.'</div></td>';
                   }
                }else{//if fail DB
                        $ret.= $prestr.$stbtn2.'</div></td>';
                }

        if ($supdate_stmt = $mysqli->prepare("UPDATE members_login SET counter = ? WHERE id = ?")) {
                $supdate_stmt->bind_param('ii', $new_ctr,$q_id);
                // Execute the prepared query.
                $supdate_stmt->execute();
                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
        }

	return $ret;

}











function printSbtn($user_id,$pkg_id,$stbid,$mysqli){

        $stbtn2='<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right"><button class="btn btn-mini btn-danger dstbbtn" id="dtb'.$stbid.'">Remove</button>';



	$ret="";
//      print $pkg_id;
                $new_ctr = 0;
                if ($sqstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? AND pid=? AND type = 5 ORDER BY time desc limit 1")) {
                  $sqstmt->bind_param('ii', $user_id, $stbid);
                  $sqstmt->execute(); // Execute the prepared query.
                  $sqstmt->store_result();

                   if($sqstmt->num_rows > 0) { // If queue found
                     $sqstmt->bind_result($q_id,$processed,$valid, $time, $counter);
                     $sqstmt->fetch();
                        if($processed=="done"){
                                if($valid=="Y"){
                                         if($counter < 5){
                                                $ret.= $stbtn2.'</div></td>';
                                                $new_ctr = $counter + 1;
                                         }else{
                                                $new_ctr = 5;
                                                $ret.= $stbtn2.'</div></td>';
                                         }
                                }else if($valid=="N"){
                                         $ret.= $stbtn2.'<span class="label label-important">Failed</span></div></td>';
                                }
                        }else if($processed=="processing"){
                                $ret.= '<td><div class="pull-right"><img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span></div></td>';
                        }else if($processed=="skip"){
                                if($counter < 5){
                                        $ret.= $stbtn2.'<span class="label label-warning">Skipped due to too many requests</span></div></td>';
                                        $new_ctr = $counter + 1;


                                }else{
                                        $new_ctr = 5;
                                        $ret.= $stbtn2.'</div></td>';
                                }

                        }else{
                                $ret.= '<td><div class="pull-right"><img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-info">Initializing</span></div></td>';
                        }
                   }else{// if queue not found
                        $ret.= $stbtn2.'</div></td>';
                   }
                }else{//if fail DB
                        $ret.= $stbtn2.'</div></td>';
                }

        if ($supdate_stmt = $mysqli->prepare("UPDATE members_login SET counter = ? WHERE id = ?")) {
                $supdate_stmt->bind_param('ii', $new_ctr,$q_id);
                // Execute the prepared query.
                $supdate_stmt->execute();
                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
        }

	return $ret;

}







function grabBday($userid,$pkg_id,$mysqli){
	if ($qstmt2 = $mysqli->prepare("SELECT stblist,maxstb FROM members_pkg WHERE id = ? and mid = ? limit 1")) {
                  $qstmt2->bind_param('ii', $pkg_id,$userid);
                  $qstmt2->execute(); // Execute the prepared query.
                  $qstmt2->store_result();

                   if($qstmt2->num_rows > 0) { // If queue found
                     $qstmt2->bind_result($stblist,$maxstb);
                     $qstmt2->fetch();

		     $ret="";
		     $ret2="";
		     $curstbcount = 0;
			           if($stblist != ""){
					  require_once "encryption.php";
    				          $eaid = $converter->encode($pkg_id);

					   $stbbtn = '<a herf="#">Delete[not functional yet]</a>';
					   $stblist_r = explode("+",$stblist);
					   $curstbcount = sizeof($stblist_r);

					   foreach($stblist_r as $stb_e){
						$stb = explode(",",$stb_e);

						   $stb3 = $stb[3];
						   if($stb[3]==""){
							$stb3 = "N/A";
						   }

						   $stb4="";
						   if(isset($stb[4])){
							$beforedash = explode("_",$stb[4]);
							$stb4=$beforedash[0];
						   }

						   $stb5="";
						   if(isset($stb[5])){
//							$beforedash = explode("_",$stb[4]);
							$stb5=$stb[5];
						   }

						   $estbid = $converter->encode($stb[0]);
						   $stbtn = '<form style="margin: 0 0 1px;" action="delete_cli_stb.php" method="post" onsubmit="return confirm(\'This action will REMOVE this STB box, are you sure to continue?\');"><input type="hidden" id="sid" name="sid" value="'.$estbid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';


						$ret2.= '<tr class="dstb" id="'.$estbid.'">
							<td>'.$stb[0].'</td>
							<td>'.$stb[1].'</td>
							<td><span class="label label-success">'.$stb[2].'</span></td>
							<td>'.$stb4.'</td>
							<td>'.$stb5.'</td>
							<td>'.$stb3.'</td>
							'.printSbtn($userid,$pkg_id,$estbid,$mysqli).'							
						      </tr>';						
//							<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn.'</div></td>
					   }
				}

					$ret= ' <table class="table table-striped" style="width:100%;table-layout:fixed;">
					      <caption><h3>STB Boxes List ( '.$curstbcount.' / '.$maxstb.' )</h3><br></caption>
					    <thead>
					      <tr>
						<th style="width: 5%">ID</th>
						<th style="width: 17%">MAC</th>
						<th style="width: 20%">IP</th>
						<th style="width: 7%">MODEL</th>
						<th style="width: 23%">VERSION</th>
						<th style="width: 15%">Last Seen</th>
						<th style="width: 13%;text-align: right;">Modify</th>
					      </tr>
					    </thead>
					    <tbody>';

					$ret.=$ret2;
						$dslot = 0;
						$dnum = 0;
					   for($l=0;$l<($maxstb-$curstbcount);$l++){
						$stbtn2='<button class="btn btn-mini btn-danger dslotbtn" id="dslot'.$l.'">Remove</button>';

						$ret.= '<tr class="slot'.$l.'" id="slot'.$l.'" >'.printSlot($userid,$pkg_id,$l,$mysqli).'</tr>';
/*							<td>Empty Slot</td>
							<td>Waiting on registration</td>
							<td><span class="label label-success">Waiting on registration</span></td>
							<td>N/A</td>
							<td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn2.'</div></td>
						      </tr>';*/
						$dnum = $l;
					   }

					if ($wqstmt = $mysqli->prepare("SELECT id,processed, valid, time,email,rawpwd, counter FROM members_login WHERE aid = ? AND type = 10 ORDER BY time desc limit 1")) {
					  $wqstmt->bind_param('i', $userid);
					  $wqstmt->execute(); // Execute the prepared query.
					  $wqstmt->store_result();

					   if($wqstmt->num_rows > 0) { // If queue found
					     $wqstmt->bind_result($w_id,$wprocessed,$wvalid, $wtime, $wslot,$wrefflag,$wcounter);
					     $wqstmt->fetch();
						if($wprocessed=="done" && $wrefflag==0){
							$dnum+=1;
							$ret.= '<tr class="slot'.$dnum.'" id="slot'.$dnum.'" data-ref="Y" style="display:none;">'.printSlot($userid,$pkg_id,$dnum,$mysqli).'</tr>';
/*                                                        <td>Empty Slot</td>
                                                        <td>Waiting on registration</td>
                                                        <td><span class="label label-success">Waiting on registration</span></td>
                                                        <td>N/A</td>
                                                        <td onmouseover="break_link=false;" onmouseout="break_link=true;"><div class="pull-right">'.$stbtn2.'</div></td>
                                                      </tr>';*/

							if ($wupdate_stmt = $mysqli->prepare("UPDATE members_login SET rawpwd = 1 WHERE id = ?")) {
								$wupdate_stmt->bind_param('i', $w_id);
								// Execute the prepared query.
								$wupdate_stmt->execute();
								//printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
							}
					        }

					   }
					}


					   $ret .='</tbody></table>';
			return $ret;
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
	$pkg_id = $_POST['pkgid'];

//	print $pkg_id;
		$new_ctr = 0;
                if ($qstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? AND type = 9 ORDER BY time desc limit 1")) {
                  $qstmt->bind_param('i', $user_id);
                  $qstmt->execute(); // Execute the prepared query.
                  $qstmt->store_result();

                   if($qstmt->num_rows > 0) { // If queue found
                     $qstmt->bind_result($q_id,$processed,$valid, $time, $counter);
                     $qstmt->fetch();
                        if($processed=="done"){
                                if($valid=="Y"){
                                         if($counter < 5){
                                                print grabBday($user_id,$pkg_id,$mysqli);
                                                $new_ctr = $counter + 1;
                                         }else{
                                                $new_ctr = 5;
                                                print grabBday($user_id,$pkg_id,$mysqli);
                                         }
                                }else if($valid=="N"){
                                         print '<span class="label label-important">Failed</span>';
                                }
                        }else if($processed=="processing"){
                                print '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span>';
                        }else if($processed=="skip"){
                                if($counter < 5){
                                        print '<span class="label label-warning">Skipped due to too many requests</span>';
                                        $new_ctr = $counter + 1;


                                }else{
                                        $new_ctr = 5;
                                        print grabBday($user_id,$pkg_id,$mysqli);
                                }

                        }else{
                                print '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-info">Initializing</span>';
                        }
                   }else{// if queue not found
                        print grabBday($user_id,$pkg_id,$mysqli);
                   }
                }else{//if fail DB
                        print grabBday($user_id,$pkg_id,$mysqli);
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
