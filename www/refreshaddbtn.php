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
	$fe = explode(",",$field);
	$act = substr($fe[0],0,1);
	$addid = substr($fe[0],1);
	$aid = $fe[1];
	$ccid = $fe[2];
	$acid = $fe[3];

	$eccid = $converter->encode($ccid);
     $eacid = $converter->encode($acid);
     $eaid = $converter->encode($aid);

                $edaddon = "";


	//check if available
	if ($qsstmt = $mysqli->prepare("SELECT addon FROM members_pkg WHERE id=? limit 1")) {
                  $qsstmt->bind_param('i', $aid);
                  $qsstmt->execute(); // Execute the prepared query.
                  $qsstmt->store_result();

                   if($qsstmt->num_rows > 0) { // If queue found
                     $qsstmt->bind_result($addons);
                     $qsstmt->fetch();
			$addon_r = explode(",",$addons);
			foreach($addon_r as $addon_e){
				$adon = explode("+",$addon_e);
				if($adon[2]==$addid){
					if($adon[3]=="r"){	
						$edaddon = $converter->encode('d'.$addid);
                				$edaddon2 = $converter->encode('a'.$addid);
						//print "<pre>".print_r($edaddon2,true)." $addid</pre>";
						$default = '<form style="margin: 0 0 1px;float:right;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'This action will REMOVE this service, are you sure to continue?\n\nPlease note that the change will take affect at the end of billing cycle.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$edaddon.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';
					}else{
						$edaddon = $converter->encode('a'.$addid);
                				$edaddon2 = $converter->encode('d'.$addid);
						//print "<pre>".print_r($edaddon2,true)." $addid</pre>";
						$default = '<form style="margin: 0 0 1px;float:right;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'Thank you for choosing Zazeen IPTV! Your change will be updated soon.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$edaddon.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Activate" class="btn-mini btn-success"/></form>';

					}
				}
			}
                   }else{// if queue not found
                   }
                }else{//if fail DB
                }







/*
	if($act=="d"){
                $edaddon = $converter->encode('d'.$addid);
                $edaddon2 = $converter->encode('a'.$addid);
                        $default = '<form style="margin: 0 0 1px;float:right;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'This action will REMOVE this service, are you sure to continue?\n\nPlease note that the change will take affect at the end of billing cycle.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$edaddon.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';
                        $default2 = '<form style="margin: 0 0 1px;float:right;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'Thank you for choosing Zazeen IPTV! Your change will be updated soon.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$edaddon2.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Activate" class="btn-mini btn-success"/></form>';
        }else if($act=="a"){
                $edaddon = $converter->encode('a'.$addid);
                $edaddon2 = $converter->encode('d'.$addid);
                        $default = '<form style="margin: 0 0 1px;float:right;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'Thank you for choosing Zazeen IPTV! Your change will be updated soon.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$edaddon.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Activate" class="btn-mini btn-success"/></form>';
                        $default2 = '<form style="margin: 0 0 1px;float:right;" action="delete_cli_pkg.php" method="post" onsubmit="return confirm(\'This action will REMOVE this service, are you sure to continue?\n\nPlease note that the change will take affect at the end of billing cycle.\');"><input type="hidden" id="cid" name="cid" value="'.$eccid.'"><input type="hidden" id="pid" name="pid" value="'.$edaddon2.'"><input type="hidden" id="aid" name="aid" value="'.$eacid.'"><input type="hidden" id="uid" name="uid" value="'.$eaid.'"><input type="submit" name="submit" value="Remove" class="btn-mini btn-danger"/></form>';
	}
*/


        $new_ctr = 0;
                if ($qstmt = $mysqli->prepare("SELECT id,processed, valid, time, counter FROM members_login WHERE aid = ? and type= 4 and pid = ? ORDER BY time desc limit 1")) {
                  $qstmt->bind_param('is', $user_id,$edaddon);
                  $qstmt->execute(); // Execute the prepared query.
                  $qstmt->store_result();

                   if($qstmt->num_rows > 0) { // If queue found
                     $qstmt->bind_result($q_id,$processed,$valid, $time, $counter);
                     $qstmt->fetch();
                        if($processed=="done"){
                                if($valid=="Y"){
                                         if($counter < 5){
                                                print '<span class="label label-success">Success</span>&nbsp;&nbsp;'.$default;
                                                $new_ctr = $counter + 1;
                                         }else{
                                                $new_ctr = 5;
						//print "<pre>".print_r($counter,true)." $addid</pre>";
                                                print $default;
                                         }
                                }else if($valid=="N" || $valid=="E"){
                                         print '<span class="label label-important">Failed</span>&nbsp;&nbsp;'.$default;
                                }
                        }else if($processed=="processing"){
                                print '<img id="upd-img" src="ajax-loader.gif"></img>&nbsp&nbsp<span class="label label-success">Updating</span>';
                        }else if($processed=="skip"){
                                if($counter < 5){
                                        print '<span class="label label-warning">Skipped due to too many requests</span>&nbsp;&nbsp;'.$default;
                                        $new_ctr = $counter + 1;


                                }else{
                                        $new_ctr = 5;
					//print "<pre>".print_r($counter,true)." $addid</pre>";
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
	log_bad("Tried direct access refreshaddbtn",$mysqli);
}

?>
