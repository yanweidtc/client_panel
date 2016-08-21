<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {
        $user_id = $_SESSION['user_id'];
        $stmt = new DB_Sql;
        if ($stmt->query("SELECT id, type, username, agentID, monthlyfee, onetimeRFee, activeaccounts, addonfee, addonpackages, pendinginvoice, update_time, mainAgent from agents where id = '$user_id'")) {
                $stmt->next_record();
                $uname = $stmt->f("username");
                $agent_id = $stmt->f("agentID");
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
        }

   if($utype == "main"){
   }else if($utype == "sub"){
           echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
           exit;
   }

	if(isset($_POST['uid'])) {
		$upid = filter_var($_POST['uid'], FILTER_VALIDATE_INT);
		if($upid){
			$u_stmt = new DB_Sql;
		   $username = $_POST['username'];
		   $email = $_POST['email'];
		   $password = $_POST['p']; // The hashed password.

		// Create a random salt
		$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		// Create salted password (Careful not to over season)
		$password = hash('sha512', $password.$random_salt);

	
			// Select from agents database. 
                        if($u_stmt->query("update agents set username = '".$username."', email = '".$email."', password = '".$password."', salt='".$random_salt."', update_time=NOW() where id = '$upid'")){}
			$u_stmt->free();

        	header('Location: ./user.php');
		exit;
		}
	}


   echo page_head(true,true,$uname);

   if(isset($_GET['id'])) {
        $uid = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        if($uid){
		
		$d_stmt = new DB_Sql;
                // Select from agents database. 
                if ($d_stmt->query("SELECT * FROM agents WHERE id = '".$uid."'")) {
		    $d_stmt->next_record();
		    $dname = $d_stmt->f("username");
		    $demail = $d_stmt->f("email");
		    $dpassword = "********";

                }else{
                }
		$d_stmt->free();
                       echo '<script type="text/javascript" src="sha512.js"></script>
         <script type="text/javascript" src="forms.js"></script>';

   echo '  <legend>Edit User</legend>
        <div class="span6" style="margin-left:25%">
        <form class="form-horizontal" action="user_edit.php" method="post" name="reg_form">
        <fieldset>
           <div class="control-group">
             <label class="control-label" for="username">Username:</label>
             <div class="controls">
                <input type="text" id="username" name="username" value="'.$dname.'" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="email">Email:</label>
             <div class="controls">
                <input type="text" id="email" name="email" value="'.$demail.'" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="password">Password:</label>
             <div class="controls">
                <input type="password" id="password" name="password" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="repassword">Confirm Password:</label>
             <div class="controls">
                <input type="password" id="repassword" name="repassword" />
             </div>
           </div>
           </br>
                <input type="hidden" id="uid" name="uid" value="'.$uid.'"/>
           <div class="control-group">
                <button value="submit" class="btn btn-primary pull-right" onclick="reghash(this.form, this.form.password, this.form.repassword, this.form.email,this.form.username);">Edit Sub User</button>
           </div>
        </fieldset>
        </form>
        </div>';
		
        //header('Location: ./user.php');
        }
   }else{
        die('Invalid Id!');
	
   }
}else{
           echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
}

?>
