<?php
require_once 'db_connect.php';

// More secured session
function sec_session_start($mysqli,$flag=true) {
        $session_name = 'sec_session_id'; // Set a custom session name
        $secure = true; // Set to true if using https.
        $httponly = true; // This stops javascript being able to access the session id. 
 
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
	//echo "<pre>".print_r($cookieParams,true)."</pre>";
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(); // regenerated the session, delete the old one.  
	
	if($flag){
		if(isset($_SESSION['user_id'])){
			$sesuid=$_SESSION['user_id'];
			$sesid=session_id();
			if ($sesstmt = $mysqli->prepare("SELECT sesid FROM members WHERE id = ? LIMIT 1")) {
			      $sesstmt->bind_param('i', $sesuid); // Bind "$email" to parameter.
			      $sesstmt->execute(); // Execute the prepared query.
			      $sesstmt->store_result();
			      $sesstmt->bind_result($osesid); // get variables from result.
			      $sesstmt->fetch();
				if($sesstmt->num_rows == 1) {
					if ($sesupdate_stmt5 = $mysqli->prepare("UPDATE members SET sesid = ?, sestime=UNIX_TIMESTAMP(NOW())  WHERE id = ?")) {
					$sesupdate_stmt5->bind_param('si', $sesid, $sesuid);
					// Execute the prepared query.
					$sesupdate_stmt5->execute();
					//printf("<pre>Error: %s.\n</pre>", $insert_stmt->error);
					}	
				}

			}
		}
	}
	//echo "<pre>".print_r(session_save_path(),true)."</pre>";
}

function login($email, $password, $qid, $mysqli, $admin) {
	$ip=getenv ("REMOTE_ADDR");

   // Using prepared Statements means that SQL injection is not possible. 
   if($admin){ $table = "admins"; $his_table = "admin_login_attempts"; $identity = "email"; }else{ $table="members"; $his_table="login_attempts"; $identity = "username";}
   if ($stmt = $mysqli->prepare("SELECT id, username, password, salt FROM {$table} WHERE {$identity} = ? AND qid = ? LIMIT 1")) { 
      $stmt->bind_param('si', $email, $qid); // Bind "$email" to parameter.
      $stmt->execute(); // Execute the prepared query.
      $stmt->store_result();
      $stmt->bind_result($user_id, $username, $db_password, $salt); // get variables from result.
      $stmt->fetch();
      if($admin){
      	$password = hash('sha512', $password.$salt); // hash the password with the unique salt.
      }
 
      if($stmt->num_rows == 1) { // If the user exists
         // We check if the account is locked from too many login attempts
         if(checkbrute($user_id, $mysqli, $admin) == true) { 
            // Account is locked
            // Send an email to user saying their account is locked
            return false;
         } else {
         if($db_password == $password) { // Check if the password in the database matches the password the user submitted. 
            // Password is correct!
 
 
               $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
               $user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
               $_SESSION['user_id'] = $user_id; 
               $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
               $_SESSION['username'] = $username;
               $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
               // Login successful.
               return true;    
         } else {
            // Password is not correct
            // We record this attempt in the database
            $now = time();
            $mysqli->query("INSERT INTO {$his_table} (user_id, time) VALUES ('$ip', '$now')");
            return false;
         }
      }
      } else {
         // No user exists. 
         return false;
      }
   }
}

function checkbrute($user_id, $mysqli, $admin) {
   If($admin){ $table = "admins"; $his_table = "admin_login_attempts";}else{ $table="members"; $his_table="login_attempts";}

	$ip=getenv ("REMOTE_ADDR");
   // Get timestamp of current time
   $now = time();
   // All login attempts are counted from the past 5 minute. 
   $valid_attempts = $now - ( 5 * 60); 
 
   if ($stmt = $mysqli->prepare("SELECT time FROM {$his_table} WHERE ip = ? AND time > '$valid_attempts'")) { 
      $stmt->bind_param('s', $ip); 
      // Execute the prepared query.
      $stmt->execute();
      $stmt->store_result();
      // If there has been more than 20 failed logins
      if($stmt->num_rows > 20) {
         return true;
      } else {
         return false;
      }
   }
}



function admin_check($mysqli,$admin) {
   // Check if all session variables are set
   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
     $user_id = $_SESSION['user_id'];
     $login_string = $_SESSION['login_string'];
     $username = $_SESSION['username'];
 
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
     if ($stmt = $mysqli->prepare("SELECT password,type FROM admins WHERE id = ? LIMIT 1")) { 
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
 
        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($password,$type); // get variables from result.
           $stmt->fetch();
           $login_check = hash('sha512', $password.$user_browser);
           if($login_check == $login_string) {
              // Logged In
              if($type == 'admin' && $admin){
		return true;
	      }else if($type == 'user' && !$admin){
		return true;
	      }else{
		return false;
	      }
           } else {
              // Not logged in
              return false;
           }
        } else {
            // Not logged in
            return false;
        }
     } else {
        // Not logged in
        return false;
     }
   } else {
     // Not logged in
     return false;
   }
}

function login_check($mysqli, $admin) {
   If($admin){ $table = "admins"; $his_table = "admin_login_attempts";}else{ $table="members"; $his_table="login_attempts";}

   // Check if all session variables are set
   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
     $user_id = $_SESSION['user_id'];
     $login_string = $_SESSION['login_string'];
     $username = $_SESSION['username'];
 
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
     if ($stmt = $mysqli->prepare("SELECT password FROM {$table} WHERE id = ? LIMIT 1")) { 
        $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
        $stmt->execute(); // Execute the prepared query.
        $stmt->store_result();
 
        if($stmt->num_rows == 1) { // If the user exists
           $stmt->bind_result($password); // get variables from result.
           $stmt->fetch();
           $login_check = hash('sha512', $password.$user_browser);
           if($login_check == $login_string) {
              // Logged In!!!!
              return true;
           } else {
              // Not logged in
              return false;
           }
        } else {
            // Not logged in
            return false;
        }
     } else {
        // Not logged in
        return false;
     }
   } else {
     // Not logged in
	// session time-out
/*	$sid = session_id();
	echo "ID====".$sid."\n";
	var_dump($_SESSION);*/
     return false;
   }
}


function log_bad($msg,$mysqli,$name=""){
	echo 'You are not authorized to access this page or your session has been timed out, please login. <a href="cli_login.php">Back</a> <br/>';
	$ip=getenv ("REMOTE_ADDR");
        if ($stmt22 = $mysqli->prepare("SELECT id FROM bad_attempts WHERE ip=? and name=? and msg=? LIMIT 1")) {
        $stmt22->bind_param('sss',$ip,$name,$msg); // Bind "$user_id" to parameter.
        $stmt22->execute(); // Execute the prepared query.
        $stmt22->store_result();

	   if($stmt22->num_rows > 0) { // If the user exists
		   $stmt22->bind_result($bid); // get variables from result.
		   $stmt22->fetch();

		if ($update_stmt22 = $mysqli->prepare("UPDATE bad_attempts SET time=UTC_TIMESTAMP() WHERE id = ?")) {
			$update_stmt22->bind_param('i', $bid);
			$update_stmt22->execute();
		}

	   }else{
		if ($log_stmt = $mysqli->prepare("INSERT INTO bad_attempts (ip, name, msg, time) VALUES (?, ?, ?, UTC_TIMESTAMP())")) {
			   $log_stmt->bind_param('sss', $ip, $name, $msg);
			   // Execute the prepared query.
			   $log_stmt->execute();
		}
	   }
	}
	exit;
}

function log_attempt($qid,$mysqli){
	 $ip=getenv ("REMOTE_ADDR");
	 $now = time();
	if ($tlog_stmt = $mysqli->prepare("INSERT INTO login_attempts (request_id, ip, time) VALUES (?, ?, ?)")) {
                   $tlog_stmt->bind_param('iss', $qid,$ip, $now);
                   // Execute the prepared query.
                   $tlog_stmt->execute();
        }
}



function emailagent($mysqli,$email,$event){
	//Send Emails
                require_once('/var/www/dit/phpmailer/PHPMailer-master/PHPMailerAutoload.php');

                //Create a new PHPMailer instance
                $mail = new PHPMailer();
                // Set PHPMailer to use the sendmail transport
                $mail->isSendmail();
                //Set who the message is to be sent from
                $mail->setFrom('autoreport@zazeen.com','Zazeen Customer Panel Auto Report');
                //Set an alternative reply-to address
                $mail->addReplyTo('autoreport@zazeen.com','Zazeen Customer Panel Auto Report');
                //Set who the message is to be sent to
                $mail->addAddress($email);
                //Set the subject line
                $mail->Subject = 'Customer Panel Action Report from Zazeen ( '.date("Y-m-d H:i:s").' )';
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                $content = '<HTML><HEAD>
                                <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
                                <META http-equiv="X-UA-Compatible" content="IE=8"></HEAD>
                                <BODY>
				 <P>'.$event.'</P>
                                 <P>--- Auto_Generate_Report, please do not reply to the sender, if you have any questions please contant Chris at chris@canaca.com.</P>
                                </BODY>
                            </HTML>';
                $mail->msgHTML($content);
                //Replace the plain text body with one created manually
                $mail->AltBody = $event.'        <br>--- Auto_Generate_Report, please do not reply to the sender, if you have any questions please contant Chris at chris@canaca.com.';

                //send the message, check for errors
                if (!$mail->send()) {
                    $ret= "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    $ret= "Message sent!";
                }	
	
}

