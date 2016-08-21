<?php
function sec_session_start() {
        $session_name = 'sec_session_id'; // Set a custom session name
        $secure = false; // Set to true if using https.
        $httponly = true; // This stops javascript being able to access the session id. 
 
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(); // regenerated the session, delete the old one.  
}

function login($email, $password) {
   // Using prepared Statements means that SQL injection is not possible. 
   		$email_check=trim(preg_replace("/[^a-zA-Z0-9\-_ @\.]/","",$email));
                $password_check=trim(preg_replace("/[^a-zA-Z0-9\-_@#]/","",$password));
                $bad=false;
                if ($email_check!=trim($email))
                {
                        print "<font color=red>E-Mail address is invalid character, only alphanumeric and -_@ are allowed</font><br>\n";
                        $bad=true;
                }
                if ($password_check!=trim($password))
                {
                        print "<font color=red>Password has invalid character, only alphanumeric and - _@# are allowed</font><br>\n";
                        $bad=true;
                }
                if ($bad){
			return false;
                        exit;
		}
      $stmt = new DB_Sql;
      if($stmt->query("SELECT id, username, password, salt FROM agents WHERE email = '$email' LIMIT 1")) { 
        $stmt->next_record();
        $password = hash('sha512', trim($password).$stmt->f("salt")); // hash the password with the unique salt.
      if($stmt->num_rows() == 1) { // If the user exists
         // We check if the account is locked from too many login attempts
         if(checkbrute($user_id) == true) { 
            // Account is locked
            // Send an email to user saying their account is locked
	    $stmt->free();
            return false;
         } else {
         if($stmt->f("password") == $password) { // Check if the password in the database matches the password the user submitted. 
            // Password is correct!
 
 
               $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
               $user_id = preg_replace("/[^0-9]+/", "", $stmt->f("id")); // XSS protection as we might print this value
               $_SESSION['user_id'] = $user_id; 
               $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $stmt->f("username")); // XSS protection as we might print this value
               $_SESSION['username'] = $username;
               $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
               // Login successful.
		$stmt->free();
               return true;    
         } else {
            // Password is not correct
            // We record this attempt in the database
            $now = time();
	    $hist = new DB_Sql;
	    $userid = $stmt->f("id");
            $hist->query("INSERT INTO login_attempts (user_id, time) VALUES ('$userid', '$now')");
	    $stmt->free();
	    $hist->free();
            return false;
         }
      }
      } else {
         // No user exists. 
	 $stmt->free();
         return false;
      }
   }
}



function checkbrute($user_id) {
   // Get timestamp of current time
   $now = time();
   // All login attempts are counted from the past 2 hours. 
   $valid_attempts = $now - (2 * 60 * 60); 
 
   $stmt = new DB_Sql;
   if ($stmt->query("SELECT time FROM login_attempts WHERE user_id = '$user_id' AND time > '$valid_attempts'")) { 
      // If there has been more than 5 failed logins
      if($stmt->num_rows() > 5) {
         return true;
      } else {
         return false;
      }
   }
}


function login_check() {
   // Check if all session variables are set
   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
     $user_id = $_SESSION['user_id'];
     $login_string = $_SESSION['login_string'];
     $username = $_SESSION['username'];
 
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
     $stmt = new DB_Sql;
     if ($stmt->query("SELECT password FROM agents WHERE id = '$user_id' LIMIT 1")) { 
	$stmt->next_record();
	$password = $stmt->f("password");	
	$num_r = $stmt->num_rows();
	$stmt->free();
        if($num_r == 1) { // If the user exists
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
     return false;
   }
}



?>
