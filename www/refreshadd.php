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

		if ($qstmt2 = $mysqli->prepare("SELECT addon FROM members_pkg WHERE id = ? limit 1")) {
		  $qstmt2->bind_param('i', $aid);
		  $qstmt2->execute(); // Execute the prepared query.
                  $qstmt2->store_result();

                   if($qstmt2->num_rows > 0) { // If queue found
                     $qstmt2->bind_result($addonstr);
                     $qstmt2->fetch();

			     $addon_array = explode(",",$addonstr);
			     foreach($addon_array as $addon_entry){
				$addon=explode("+",$addon_entry);
				if($addon[2]==$addid){
					if($act=='s'){
						if($addon[3]=="r"){
							$astatus = "Active";
							$alabel = "label-success";
						}else{
							$astatus = "Non-active";
							$alabel = "label-default";
						}
						print '<span class="label '.$alabel.'">'.$astatus.'</span>';	
					}else if($act=='t'){
						if($addon[4]!=""){
							$trdate = $addon[4];
						}else{
							$trdate = 'N/A';
						}
						print $trdate;
					}
				}
			     }

			}else{//if found
				print $default;
			}
			
		}else{//if fail db
			print $default;
		}


}else{	//login session check fail
	log_bad("Tried direct access refreshadd",$mysqli);
}

?>
