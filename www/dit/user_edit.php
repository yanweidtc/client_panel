<?php
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'datediff.php';
include 'functions.php';
require_once("mondiff.php");
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
   }else if($utype == "subuser"){
           echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
           exit;
   }

	if(isset($_POST['uid']) && isset($_POST['username']) && isset($_POST['p']) && isset($_POST['email'])) {


		$upid = filter_var($_POST['uid'], FILTER_VALIDATE_INT);
		if($upid){
			$u_stmt = new DB_Sql;
		   $username = $_POST['username'];
		   $email = $_POST['email'];
		   $password = $_POST['p']; // The hashed password.
		

		   $type = $_POST['type'];

	   // TODO add filters for those values
	   $onetimeRF = $_POST['onetimeRF'];
	   $addonfee = $_POST['addonfee'];
	   $monthlyfee = $_POST['monthlyfee'];
	   $phone = $_POST['phone'];

	$tfrom = $_POST['from'];	

	   // SIP
	   if(isset($_POST['sip']) && $_POST['sip']!=""){
		$esip = ", SIP = '".$_POST['sip']."' ";
	   }else{
		$esip = "";
	   }

		// Create a random salt
		$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
		// Create salted password (Careful not to over season)
		$password = hash('sha512', $password.$random_salt);



	$check = new DB_Sql;
        $check->query("select * from agents where email='".$email."' and id<>'".$upid."'");
        if($check->num_rows()>0){
                //echo "Sorry, this email has already been used...";
                //sleep(1);
                header('Location: ./user_edit.php?id='.$_POST["uid"].'&error=1');
                //exit;
	}else{

	
		   $pwdsql= ", password = '".$password."' , salt='".$random_salt."'";
		   if($_POST['p']==""){
			$pwdsql='';
		   }		   
			// Select from agents database. 
                        if($u_stmt->query("update agents set username = '".$username."', email = '".$email."'".$pwdsql.$esip." ,update_time=NOW(), onetimeRFee='".$onetimeRF."', monthlyfee='".$monthlyfee."', phone='".$phone."', addonfee='".$addonfee."', monthlyday='".$monthlyday."', onetimeday='".$onetimeday."',cancelday='".$cancelday."' where id = '$upid'")){}
			$u_stmt->free();


		if($tfrom == "detail"){
			header('Location: ./user_detail.php?id='.$upid.'');
		}else{
        		header('Location: ./user.php');
		}

		exit;
		}

		}
	}


   echo page_head(true,true,$uname);

   
   if(isset($_GET['id']) || ( isset($_POST['uid']) && isset($_POST['from']) ) ) {
	$from = "";
	if(isset($_GET['id'])){
        	$uid = filter_var($_GET['id'], FILTER_VALIDATE_INT);
	}else if( isset($_POST['uid']) && isset($_POST['from']) ){
		$uid = filter_var($_POST['uid'], FILTER_VALIDATE_INT);
		$from =$_POST['from'];
	}

		if(isset($_GET['from'])){
			$from = $_GET['from'];
		}

        if($uid){

        echo        '        <div class="pull-right">'.$n;
        echo        '          <a class="btn btn-primary" href="user.php">Manage Sub-accounts</a>'.$n;
	if($from=="detail"){
        	echo        '          <a class="btn btn-primary" href="user_detail.php?id='.$uid.'">Back to Revenue Detail</a>'.$n;
	}
        echo        '        </div><br><br>'.$n;

		$d_stmt = new DB_Sql;
                // Select from agents database. 
                if ($d_stmt->query("SELECT * FROM agents WHERE id = '".$uid."'")) {
		    $d_stmt->next_record();
		    $dname = $d_stmt->f("username");
		    $demail = $d_stmt->f("email");
		    $dtype = $d_stmt->f("type");
			$dmonthfee = $d_stmt->f("monthlyfee");
			$daddonfee = $d_stmt->f("addonfee");
			$donetimeRF = $d_stmt->f("onetimeRFee");
                        $donetimeday = $d_stmt->f("onetimeday");
                        $dmonthlyday = $d_stmt->f("monthlyday");
                        $dcancelday = $d_stmt->f("cancelday");

			$dphone = $d_stmt->f("phone");
			    $dpassword = "********";
		    $dmagent = $d_stmt->f("mainAgent");
		    $dsagent = $d_stmt->f("subAgent");
		    $dsip = $d_stmt->f("SIP");
		    $d_ag_id = $dmagent.'-'.$uid; 
			if($dtype == "subuser"){
				$d_ag_id = $dmagent.'-'.$dsagent.'-'.$uid;
			}

                }else{
                }
		$d_stmt->free();
                       echo '<script type="text/javascript" src="sha512.js"></script>
         <script type="text/javascript" src="forms.js"></script>';

if(isset($_GET['error']) && $_GET['error']){
		print '<div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Failed!</strong> Email is already used.
                </div>';
}

//        <div class="span6" style="margin-left:25%">
   echo '  <legend>Edit Agent: '.$d_ag_id.'</legend>
        <div class="col-md-4 col-md-offset-4">
        <form class="form-horizontal" action="user_edit.php" method="post" name="edit_form" role="form">
        <fieldset>
           <div class="control-group">
             <label class="control-label" for="username">Username:</label>
             <div class="controls">
                <input type="text" id="username" class="form-control" name="username" value="'.$dname.'" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="email">Email:</label>
             <div class="controls">
                <input type="text" id="email" class="form-control" name="email" value="'.$demail.'" />
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="password">Password:</label>
             <div class="controls">
                <input type="password" id="password" name="password" class="form-control" placeholder="Leave blank if unwill to modify…"/>
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="repassword">Confirm Password:</label>
             <div class="controls">
                <input type="password" id="repassword" name="repassword" class="form-control" placeholder="Leave blank if unwill to modify…"/>
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="phone">Phone:</label>
             <div class="controls">
                <input type="text" id="phone" name="phone" class="form-control" value="'.$dphone.'" />
             </div>
           </div>';

echo       '</br>
                <input type="hidden" id="uid" name="uid" value="'.$uid.'"/>
                <input type="hidden" id="from" name="from" value="'.$from.'"/>
           <div class="control-group">
                <button value="submit" class="btn btn-primary pull-right" onclick="reghash(this.form, this.form.password, this.form.repassword, this.form.email,this.form.username);">Save</button>
           </div>
        </fieldset>
        </form>
        </div><div class="clearfix"></div>';
		
        //header('Location: ./user.php');
	echo page_foot($ajax);
        }
   }else{
        die('Invalid Id!');
	
   }
}else{
           echo 'You are not authorized to access this page as sub-user, please login with the main account. <a href="login.php">Back</a> <br/>';
}

?>
