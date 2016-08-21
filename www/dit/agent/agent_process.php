<?php
// Zazeen blilling server
$ip=getenv ("REMOTE_ADDR");
//66.49.254.56.
if ($ip != "66.49.254.56")
{
  print "<b><i>Forbidden</i></b>";
  exit;
}



include 'database.php';
//Chris 2014-06-10
if (isset($_POST["reply"]) && isset($_POST["id"]))
{
        print "here\n";
          print "ID=".$_POST["id"]."\n";
          print "Valid?=".$_POST["valid"]."\n";
          print "reply=".$_POST["reply"]."\n";


/*
        // retrieve info with queue id.
        if ($r_stmt = $mysqli->prepare("select id,name,email,password,data,aid,processed,type,agent from members_login where id = ? AND processed <> \"done\"")) {
          $r_stmt->bind_param('i', $_POST["id"]);
          $r_stmt->execute(); // execute the prepared query.
          $r_stmt->store_result();

           if($r_stmt->num_rows > 0) { // if queued request exists
             $r_stmt->bind_result($r_id,$r_name,$r_email,$r_password,$r_data,$r_aid,$r_processed,$r_type,$r_agent); // get variables from result.
             $r_stmt->fetch();

          }else{
                echo "Queue not exist! output this line to trigger error on the other side";
          }
        }


        // TODO:Change this to deletion, or make schedule delete
        if ($update_stmt = $mysqli->prepare("UPDATE members_login SET processed = \"done\" WHERE id = ?")) {
                $update_stmt->bind_param('i', $_POST["id"]);
                // Execute the prepared query.
                $update_stmt->execute();
                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
        }


                if(isset($_POST["valid"])){
                                if ($update_stmt2 = $mysqli->prepare("UPDATE members_login SET valid = ? WHERE id = ?")) {
                                $update_stmt2->bind_param('si', $_POST["valid"],$_POST["id"]);
                                // Execute the prepared query.
                                $update_stmt2->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                                }
                }else{
                        echo "Connection Error!";
                }


        //$name="Mehran Fallahpour";
        //$email="mehran@canaca.com";

        //parsing return data
        $data_array = array();
        $lines = explode("\n",$_POST["reply"]);
        $titles = explode("\t",$lines[0]);
        for($i=1;$i<(count($lines)-1);$i++){
                $data = explode("\t",$lines[$i]);
                for($j=0;$j<(count($data));$j++){
                        $data_array[$i-1][$titles[$j]]=$data[$j];
                }
        }

        if(count($data_array) == 0){
                print "Invalid name/email combo or Empty return";
                // update status
                $valid = "N";
                $done_str = "done";
                if ($update_vstmt = $mysqli->prepare("UPDATE members_login SET valid = ?, processed = ? WHERE id=?")) {
                        $update_vstmt->bind_param('ssi', $valid, $done_str, $_POST["id"]);
                        // Execute the prepared query.
                        $update_vstmt->execute();
                        //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                }
                $update_vstmt->free();
                exit;
        }
        var_dump($data_array);



        // Insert to members database as new login info.
        if($r_data == ""){
                die("Empty r_data!");
        }

        if($_POST["valid"]=="Login"){                   // Login request!!

                $type="customer";
                // Retrieve random salt
                $random_salt = $r_password;

                // Insert to database.
                if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt, type, truename, company, address, country, phone, ccname, cctype, ccnumber,ccexpiry, cid, phone2, invoices, qid, update_time, sesid, sestime,agent,birthday) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), 0, UNIX_TIMESTAMP(NOW()),?,?)")) {
                   $insert_stmt->bind_param('sssssssssssssssssiss', $r_name, $data_array[0]["emailaddress"], $r_data, $random_salt,$type,$data_array[0]["name"],$data_array[0]["company"],$data_array[0]["address"],$data_array[0]["country"],$data_array[0]["phonenumber"],$data_array[0]["ccname"],$data_array[0]["cctype"],$data_array[0]["ccnumber"],$data_array[0]["ccexpiry"], $data_array[0]["cid"], $data_array[0]["phone2"], $data_array[0]["invoices"], $r_id,$r_agent,$data_array[0]["birthday"]);
                   // Execute the prepared query.
                   $insert_stmt->execute();
                   //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                }


        // retrieve user_id for adding packages
        if ($rm_stmt = $mysqli->prepare("select id from members where username = ? and qid = ?")) {
          $rm_stmt->bind_param('si', $r_name, $r_id);
          $rm_stmt->execute(); // execute the prepared query.
          $rm_stmt->store_result();

           if($rm_stmt->num_rows > 0) { // if queued request exists
             $rm_stmt->bind_result($user_id); // get variables from result.
             $rm_stmt->fetch();

          }else{
                echo "Problem retrieving member id! output this line to trigger error on the other side";
          }
        }



        // Insert to members_pkg to link the accounts to members
        foreach($data_array as $pkg){
                $pkg_des = explode("-",$pkg['pname']);
                $pkg_name = trim($pkg_des[1]);
                $status = "Active";
                if($pkg['suspend']=='Y'){
                        $status="Suspended";
                }

                if ($insert_pkg_stmt = $mysqli->prepare("INSERT INTO members_pkg (name, pkgname, raw_pkgname, mid, status, start_date, update_time, addon, nextdue, acid, stblist,trackingnumber) VALUES (?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), ?, ?, ?, ?, ?)")) {
                   $insert_pkg_stmt->bind_param('sssisssssss', $r_name, $pkg_name, $pkg['pname'], $user_id, $status, $pkg['startdate'], $pkg['addon'], $pkg['nextdue'], $pkg['accountid'], $pkg['stblist'], $pkg['TrackingNumber']);
                   // Execute the prepared query.
                   $insert_pkg_stmt->execute();
//                 printf("<pre>Error: %s.\n</pre>", $insert_pkg_stmt->error);
                }


        }

        }// if Login////////////////////////////////////////////////////////////////////

        if($r_aid != "" && $r_aid != 0 && $_POST["valid"]=='Y' && $r_type==1 ){ // If Update Info!!
                                echo "UPDATING Customer INFO\n";
                                $ccexp = $data_array[0]['ccmonth'].'/'.$data_array[0]['ccyear'];
                                if ($update_stmt3 = $mysqli->prepare("UPDATE members SET phone = ? , phone2 = ?, email = ? , cctype = ? , ccname = ? , ccnumber = ? , ccexpiry = ? WHERE id = ?")) {
                                $update_stmt3->bind_param('sssssssi', $data_array[0]['phone'], $data_array[0]['phone2'], $data_array[0]['email'], $data_array[0]['cctype'], $data_array[0]['ccname'], $data_array[0]['ccnumber'], $ccexp,$r_aid);
                                // Execute the prepared query.
                                $update_stmt3->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                                }
        }



        if($r_aid != "" && $r_aid != 0 && $_POST["valid"]=='Y' && $r_type==2 ){ // If Update Phone!!
                                echo "UPDATING PHONE INFO\n";
                                if ($update_stmt4 = $mysqli->prepare("UPDATE members SET phone = ? , phone2 = ?  WHERE id = ?")) {
                                $update_stmt4->bind_param('ssi', $data_array[0]['phone'], $data_array[0]['phone2'], $r_aid);
                                // Execute the prepared query.
                                $update_stmt4->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                                }
        }

        if($r_aid != "" && $r_aid != 0 && $_POST["valid"]=='Y' && $r_type==3 ){ // If Update Email!!
                                echo "UPDATING EMAIL INFO\n";
                                if ($update_stmt5 = $mysqli->prepare("UPDATE members SET email = ?  WHERE id = ?")) {
                                $update_stmt5->bind_param('si', $data_array[0]['email'], $r_aid);
                                // Execute the prepared query.
                                $update_stmt5->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                                }
        }

        if($r_aid != "" && $r_aid != 0 && $_POST["valid"]=='Y' && $r_type==4 ){ // If Update ADDON!!
                                echo "UPDATING ADDON INFO\n";



                                if ($update_stmt6 = $mysqli->prepare("UPDATE members_pkg SET addon = ?  WHERE id = ?")) {
                                $update_stmt6->bind_param('si', $data_array[0]['addonstr'], $data_array[0]['uid']);
                                // Execute the prepared query.
                                $update_stmt6->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                                }
        }



        if($r_aid != "" && $r_aid != 0 && $_POST["valid"]=='Y' && $r_type==5 ){ // If Delete STB!!
                // retrieve user_id for adding packages
                if ($rm2_stmt = $mysqli->prepare("select stblist from members_pkg where name = ? and id = ? limit 1")) {
                  $rm2_stmt->bind_param('si', $r_name, $data_array[0]['uid']);
                  $rm2_stmt->execute(); // execute the prepared query.
                  $rm2_stmt->store_result();

                   if($rm2_stmt->num_rows > 0) { // if queued request exists
                     $rm2_stmt->bind_result($stb_list); // get variables from result.
                     $rm2_stmt->fetch();

                        $stb_list_r = explode("+",$stb_list);
                        $stb_after = array();
                        foreach($stb_list_r as $stb_e){
                                $stbb = explode(",",$stb_e);
                                if($stbb[0] == $data_array[0]['sid']){
                                        // add null
                                }else{
                                        $stb_after[]=$stb_e;
                                }
                        }

                        $stb_after_str = implode("+",$stb_after);

                                echo "UPDATING STB INFO\n";
                                if ($update_stmt7 = $mysqli->prepare("UPDATE members_pkg SET stblist = ?  WHERE id = ?")) {
                                        $update_stmt7->bind_param('si', $stb_after_str, $data_array[0]['uid']);
                                        // Execute the prepared query.
                                        $update_stmt7->execute();
                                }

                  }else{
                        echo "Problem retrieving stb_pkg id! output this line to trigger error on the other side";
                  }
                }
        }


        if($r_aid != "" && $r_aid != 0 && $_POST["valid"]=='Y' && $r_type==6 ){ // If Update Email!!
                                echo "UPDATING BDAY INFO\n";
                                if ($update_stmt8 = $mysqli->prepare("UPDATE members SET birthday = ?  WHERE id = ?")) {
                                $update_stmt8->bind_param('si', $data_array[0]['birthday'], $r_aid);
                                // Execute the prepared query.
                                $update_stmt8->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                                }
        }*/

        exit;

}else{
/*
        function checklimit($name, $request, $time, $mysqli) {
           if($request=="done" || $request=="skip"){
                return false;
           }else{
                   // All login attempts are counted from the past 1 mins.
                   $valid_attempts = $time - (1 * 60);

                   if ($cstmt = $mysqli->prepare("SELECT time FROM members_login WHERE name = ? AND process = ? AND processed <> \"skip\" AND time > '$valid_attempts'")) {
                      $cstmt->bind_param('ss', $name, $request);
                      // Execute the prepared query.
                      $cstmt->execute();
                      $cstmt->store_result();
                      // If there has been more than 20 requests
                      if($cstmt->num_rows > 20) {
                         return true;
                      } else {
                         return false;
                      }
                   }
            }
        }




        // select one from the queue
        if ($stmt = $mysqli->prepare("SELECT id,name,password,data,processed,aid,time,type FROM members_login WHERE processed <> \"done\" AND processed <> \"skip\" AND processed <> \"processing\" ORDER BY id")) {
          $stmt->execute(); // execute the prepared query.
          $stmt->store_result();

           if($stmt->num_rows > 0) { // if queued request exists
             $stmt->bind_result($q_id,$q_name,$q_password,$q_data,$q_processed,$q_aid,$q_time,$q_type); // get variables from result.
             while($stmt->fetch()){
                if(checklimit($q_name,$q_processed,$q_time,$mysqli)){
                        // too many attempts in 1 min
                        if ($skip_stmt = $mysqli->prepare("UPDATE members_login SET processed = \"skip\" WHERE id = ?")) {
                                $skip_stmt->bind_param('i', $q_id);
                                // Execute the prepared query.
                                $skip_stmt->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                        }

                        break;
                }else{
                        if ($on_stmt = $mysqli->prepare("UPDATE members_login SET processed = \"processing\" WHERE id = ?")) {
                                $on_stmt->bind_param('i', $q_id);
                                // Execute the prepared query.
                                $on_stmt->execute();
                                //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
                        }

                        if($q_processed == "login"){
                             print "id=".$q_id."\n";
                             print "name=".$q_name."\n";
                             print "password=".$q_password."\n";
                             print "data=".$q_data."\n";
                             print "portal=0\n";
                             break;
                        }else if($q_processed == "updateinfo"){
                             print "id=".$q_id."\n";
                             print "name=".$q_name."\n";
                             print "data=".$q_data."\n";
                             print "portal=1\n";
                             break;
                        }else if($q_processed == "updateph"){
                             print "id=".$q_id."\n";
                             print "name=".$q_name."\n";
                             print "data=".$q_data."\n";
                             print "portal=2\n";
                             break;
                        }else if($q_processed == "updateem"){
                             print "id=".$q_id."\n";
                             print "name=".$q_name."\n";
                             print "data=".$q_data."\n";
                             print "portal=3\n";
                             break;
                        }else if($q_processed == "updateadd"){
                             print "id=".$q_id."\n";
                             print "name=".$q_name."\n";
                             print "data=".$q_data."\n";
                             print "portal=4\n";
                             break;
                        }else if($q_processed == "deletestb"){
                             print "id=".$q_id."\n";
                             print "name=".$q_name."\n";
                             print "data=".$q_data."\n";
                             print "portal=5\n";
                             break;
                        }else if($q_processed == "updatebday"){
                             print "id=".$q_id."\n";
                             print "name=".$q_name."\n";
                             print "data=".$q_data."\n";
                             print "portal=6\n";
                             break;
                        }else{
                             //print "Invalid process type!";
                        }
                }
             }//while
          }else{
                print "no queued request, output this line to trigger error on the other side\n";
          }
        }
*/
                             print "id=1\n";
                             print "agentid=623411\n";
                             print "check=pattern\n";
                             print "data=acid:948\n";
                             print "portal=2\n";
}
?>
