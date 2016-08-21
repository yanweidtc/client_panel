<?php
  include_once('./js/header.php');
  $ajax = false;

include 'db_connect.php';
include 'functions.php';
 
sec_session_start($mysqli); // Our custom secure way of starting a php session.


 global $n;
 echo page_head(true,false);

if(isset($_POST['username'], $_POST['p'])) {

        // filter name/email
        $username=preg_replace("/[^a-zA-Z0-9_\-\. ]/","",$_POST['username']);
        $password=preg_replace("/[^a-zA-Z0-9_\-\.]/","",$_POST['p']);
        if(isset($_POST['from'])){
                $fagent=preg_replace("/[^a-zA-Z0-9_\-\. ]/","",$_POST['from']);
        }else{
                $fagent='';
        }



        $loginstr="login";
        $processingstr="login";

        $rpwd=hash('sha512', $password."fixsaltforchecking");
        // Create a random salt
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        // Create salted password (Careful not to over season)
        $password = hash('sha512', $password.$random_salt);




      // retrieve id of the member, if for some reason not cleaned
        if ($cstmt = $mysqli->prepare("SELECT id FROM members WHERE username = ? and agent=''")) {
          $cstmt->bind_param('s', $username);
          $cstmt->execute(); // Execute the prepared query.
          $cstmt->store_result();

           if($cstmt->num_rows > 1) { // If queue already exists
             $cstmt->bind_result($mid); // get variables from result.
             $cstmt->fetch();
                echo '<pre>Only two logins are allowed at the same time.</pre>';
                echo '<br><a href="cli_login.php" class="btn btn-info">Back to Login</a>';
                //echo '<br><a href="logout.php" class="btn btn-info">Logout out the previous session and back to Login</a>';
                echo page_foot($ajax);
                exit;
             // provide qid to job.php if not locked
          }
        }




/*
      // retrieve id of the login+queue, if Already submitted
        if ($stmt = $mysqli->prepare("SELECT id FROM members_login WHERE name = ? AND rawpwd = ? AND processed = ?")) {
     $stmt->bind_param('sss', $username, $rpwd, $processingstr);
          $stmt->execute(); // Execute the prepared query.
          $stmt->store_result();

           if($stmt->num_rows > 0) { // If queue already exists
             $stmt->bind_result($qid); // get variables from result.
             $stmt->fetch();
                echo '<pre>We are currently processing your request #'.$qid.', this might take up to one minute.</pre>';
                echo '<br><a href="cli_login.php" class="btn btn-info">Back to Login</a>';
                echo page_foot($ajax);
                exit;
             // provide qid to job.php if not locked
          }
        }
*/

      // add name/pwd combination to the queue
      if ($insert_stmt = $mysqli->prepare("INSERT INTO members_login (name, rawpwd, password, data, processed,type,agent) VALUES (?, ?, ?, ?, ?, 0,?)")) {
           $insert_stmt->bind_param('ssssss', $username,$rpwd, $random_salt,$password,$loginstr,$fagent);
           // Execute the prepared query.
           $insert_stmt->execute();
           //printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
      }else{
        echo "Database fail";
      }


        // Again, after insertion, retrieve id of the queue, if Already submitted
        if ($re_stmt = $mysqli->prepare("SELECT id FROM members_login WHERE name = ? AND data = ? AND processed = ?")) {
          $re_stmt->bind_param('sss', $username, $password, $loginstr);
          $re_stmt->execute(); // Execute the prepared query.
          $re_stmt->store_result();

           if($re_stmt->num_rows > 0) { // If queue already exists
             $re_stmt->bind_result($qqid); // get variables from result.
             $re_stmt->fetch();
          }
        }

        //echo '<h2>Your request #'.$qqid.' has been initialed, this might take up to one minute to gather your real-time account information.</h2>';
        echo '<br><a href="cli_login.php" class="btn btn-info">Back to Login</a>';

      // ajax call and progress bar
  /* echo '<script type="text/javascript">
                var myApp;

                myApp = myApp || (function () {
                var pleaseWaitDiv = $(\'<div class="modal" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h2>Loading...please wait</h2></div><div class="modal-body"><div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%"><span class="sr-only">45% Complete</span></div></div></div></div></div></div>\');
                    return {
                        showPleaseWait: function() {
                            pleaseWaitDiv.modal();
                        },
                        hidePleaseWait: function () {
                            pleaseWaitDiv.modal(\'hide\');
                        },

                    };
                })();

              <!--  myApp.showPleaseWait(); -->
                $.ajax({
                type: \'POST\',
                url: \'check_cli_login.php\',
                async:true,
                timeout:50000,
                data: {
                    name: "'.$username.'",
                    qqid: "'.$qqid.'",
                    p: "'.$password.'"
                },
                error: function(x, t, m) {
                        if(t==="timeout") {
                            alert("Timeout..");
                            window.location.href = "main_cli_list.php";
                        } else {
                            alert(t);
                            window.location.href = "main_cli_list.php";
                        }
                    }
            }).done(function(msg) {
    //alert(msg);
                if(msg === "success"){
                    myApp.hidePleaseWait();
                    window.location.href = "main_cli_list.php";
                }else if(msg === "NameError" || msg === "PasswordError"){
                    myApp.hidePleaseWait();
                    alert("Invalid Username or Password provided!");
                }else if(msg === "LoginError"){
                    myApp.hidePleaseWait();
                    alert("Error process login!");
                    window.location.href = "cli_login.php?error=1";
                }else if(msg === "DBError"){
                    myApp.hidePleaseWait();
                    alert("Database connection Error!");
                    window.location.href = "cli_login.php?error=1";
                }else if(msg === "Invalid"){
                    myApp.hidePleaseWait();
                    alert("Name and Password combination not found! Please try again.");
                    window.location.href = "cli_login.php?error=1";
                }else if(msg === "QueueError"){
                    myApp.hidePleaseWait();
                    alert("Database internal Error!");
                    window.location.href = "cli_login.php?error=1";
                }else if(msg === "Skip"){
                    myApp.hidePleaseWait();
                    alert("Your submitted too many requests in 1 min, therefore this action is skiped.");
                    window.location.href = "cli_login.php?error=1";
                }else if(msg === "Timeout"){
                    myApp.hidePleaseWait();
                    alert("Server Timeout!");
                    window.location.href = "cli_login.php?error=1";
                }else{
                    myApp.hidePleaseWait();
                    alert("Unknown Error!");
                    window.location.href = "cli_login.php?error=1";

                }
            });
</script>';
*/
}else{
        log_bad("Login with no username/pwd",$mysqli);

}
        // call
        echo page_foot($ajax);



?>

