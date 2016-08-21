<?php
  include_once('./js/header.php');
  $ajax = false;

include './db_connect.php';
include './functions.php';
require "class.rc4crypt.php";



sec_session_start($mysqli);
if(login_check($mysqli,false) == true) {


   global $n; 

      $user_id = $_SESSION['user_id'];
      if(isset($_GET['pkg'])){
          $pkg_id = $_GET['pkg'];
      }else if(isset($_POST['pkg'])){
          $pkg_id = $_POST['pkg'];
      }
      if ($stmt = $mysqli->prepare("SELECT cid,email,truename, company, address, country, phone, phone2, cctype, ccname,ccnumber,ccexpiry,update_time FROM members WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();

        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($ccid,$cus_email,$cus_name,$cus_company,$cus_address, $cus_country, $cus_phone, $cus_phone2, $cus_cctype, $cus_ccname, $cus_ccnumber, $cus_ccexpiry, $cus_updatetime); // get variables from result.
           $stmt->fetch();

        }}


//   echo page_head(true,false,$cus_name);


	if ($qstmt2 = $mysqli->prepare("SELECT password,acid FROM members_pkg WHERE id = ? and mid = ? limit 1")) {
                  $qstmt2->bind_param('ii', $pkg_id,$user_id);
                  $qstmt2->execute(); // Execute the prepared query.
                  $qstmt2->store_result();

                   if($qstmt2->num_rows > 0) { // If queue found
                     $qstmt2->bind_result($epwd,$acid);
                     $qstmt2->fetch();

                    }else{//if found
				log_bad("Tried direct access ref_stb",$mysqli);
                    }

        }else{//if fail db
		log_bad("Tried direct access ref_stb",$mysqli);	  
        }



	if(isset($_POST['pkg'])) {
		//numerics filter
//		print "<pre>".print_r($_POST,true)."</pre>";
		// Infos
		$data_str="cid:".$ccid."+pkgid:".$_POST['pkg']."+acid:".$acid;

	//	print $data_str;
		$newencrypt2 = new rc4crypt;

		$rc4pass="3vcud2m9l48cdfbhzb6s2xaym2m5uruv8q4";
		$data_str= $newencrypt2->endecrypt ("$rc4pass", "$data_str", $case);

		$queuestr="updaterefstb";
		      // add name/pwd combination to the queue
	      if ($insert_stmt = $mysqli->prepare("INSERT INTO members_login (name, data, processed,aid,type,time) VALUES (?, ?, ?, ?, 9, NOW())")) {
		   $insert_stmt->bind_param('sssi', $cus_name,$data_str,$queuestr, $user_id);
		   // Execute the prepared query.
		   $insert_stmt->execute();
		   //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
	      }else{
		echo "Database fail";
	      }

		   $insert_stmt->close();


//		header('Location: ./main_cli_pkg2.php?id='.$pkg_id.'');
	// 	print success	

	}else{
//		header('Location: ./main_cli_pkg2.php?id='.$pkg_id.'');
	}
} else {
	log_bad("Tried direct access ref_stb",$mysqli);
}


?>
