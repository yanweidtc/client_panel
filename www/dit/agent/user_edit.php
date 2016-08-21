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
		if($_POST['type']=="subagent"){
			if(! (isset($_POST['onetimeRF']) && isset($_POST['monthlyfee']))){
				exit;
			}
		}

                // check two day boundaries
                $monthlyday = filter_var($_POST['monthlyday'], FILTER_VALIDATE_INT);
                $onetimeday = filter_var($_POST['onetimeday'], FILTER_VALIDATE_INT);
                $cancelday = filter_var($_POST['cancelday'], FILTER_VALIDATE_INT);
                if($monthlyday===false || $onetimeday===false || $cancelday===false){
                   echo 'Invalid Cashable Days! <a href="user.php">Back</a> <br/>';
                   exit;
                }



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




////////////////////////////////////////
// sub date calculation:////////////////
////////////////////////////////////////
                $dstmt = new DB_Sql;
                $dstmt->query("SELECT * from agents where id = '$upid'");
                $dstmt->next_record();
                $dmonthlyday = $dstmt->f("monthlyday");
                $monthfee = $dstmt->f("monthlyfee");
                $donetimeday = $dstmt->f("onetimeday");


                $now = date("Y-m-d");

                $subdate_array = explode(',',substr($subdate,1,-1));

                //re-initial statistics
                $monthlyac=0;
                $monthlypdi=0;
                $onetimeaccount=0;
                $threepdaccount=0;
                $sixpdaccount=0;
                $ninepdaccount=0;

		//Charge Back
		$acancelled=0;
		$acancelledcb=0;
		$acancelledstr="";

		//Cumulative month fees
		$cumonthamount=0;

                foreach ($subdate_array as $dsubdate){
                        $sub = explode('+',$dsubdate);
                        $startdate = $sub[0];
                        $suspend = $sub[1];
                        $onetimepaid = $sub[2];
                        $inv_flag = $sub[3];
                        $monthlyprecord = $sub[4];

			$enddate = "0000-00-00";
                        if($suspend!="N" && $suspend!=""){
                                $enddate = $sub[5];
                        }

                        // datediff
                        /*$datetime1 = date_create($startdate);
                        $datetime2 = date_create($datenow);
                        $datetime3 = date_create($enddate);
                        $interval = date_diff($datetime1, $datetime2);
                        $datediff = $interval->format('%R%a');
                        $daydiff = intval(substr($datediff,1,strlen($datediff)-1));*/
			$datediff = date_difference($startdate,$now);

			// variable cancel day
                        if($enddate!="0000-00-00"){
				$datediff2 = date_difference($startdate, $enddate);
                                                $mondiff = intval(substr($datediff2,1,strlen($datediff2)-1));
				if( $mondiff <= $dcancelday && $suspend != "N"){
					$pflag=false;

					if($onetimepaid!="N" && $onetimepaid!="P"){
						$acancelledstr.="O";
						$pflag=true;
						$acancelledcb+=$onetimeRF;
					}
					if($monthlyprecord!=""){
						$pflag=true;
						$int4= mon_diff($monthlyprecord, $startdate);
						if(strpos(" ".$int4,"+") != false){
							$mon4=substr($int4,1);
							$acancelledstr.="+M*".$mon4;
							$acancelledcb+=$monthfee*$mon4;
						}
					}

					$acancelledstr.=",";

					//cancelled with 90 days
					if($pflag){
						$acancelled++;
						if($subagentid!=0){
							$subagent[$subagentid]["acancelled"]++;
						}
					}

				}

			}

                        $daydiff = intval(substr($datediff,1,strlen($datediff)-1));




                        //monthly referral
                        if($suspend == "N" && strpos(" ".$datediff,"+") != false && $daydiff > $dmonthlyday){
                                $monthlyac++;
                                if($inv_flag=="N"){
                                        $monthlypdi++;
                                }
                        }






				/////////////////////////cumulate monthly fee//////////////////////////////
				if(strpos(" ".$datediff,"+") != false && $daydiff > $dmonthlyday){

						if($monthlyprecord!=""){
							$int1= mon_diff2($today,$monthlyprecord);
							if(strpos(" ".$int1,"+") != false){
								$mon1=substr($int1,1);

								if($mon1 > 0){
									//categories
									$cumonthamount+=$monthfee * $mon1;
								}
							}
						}else{
							$int2= mon_diff2($today,$startdate);
							if(strpos(" ".$int2,"+") != false){
								$mon2=substr($int2,1);

								if($mon2 > 0){
									//categories
									$cumonthamount+=$monthfee * $mon2;
								}
							}

						}



				}

				/////////////////////////cumulate monthly fee//////////////////////////////



			//one-time referral
                        if($suspend == "N" && $onetimepaid=="N"){
				
                                //Original date
                                if( strpos(" ".$datediff,"+") != false && $daydiff > $donetimeday){
                                        $onetimeaccount++;
                                }

                                //30 days later
                                $datediff30 = date_difference2($startdate,$now,$donetimeday,30);
                                if( strpos(" ".$datediff30,"+") != false ){
                                        $threepdaccount++;
                                }

                                //60 days later
                                $datediff60 = date_difference2($startdate,$now,$donetimeday,60);
                                if( strpos(" ".$datediff60,"+") != false ){
                                        $sixpdaccount++;
                                }

                                //90 days later
                                $datediff90 = date_difference2($startdate,$now,$donetimeday,90);
                                if( strpos(" ".$datediff90,"+") != false ){
                                        $ninepdaccount++;
                                }

                        }


                }//foreach subdate_array

                $ninepdaccount=$ninepdaccount-$sixpdaccount;
                $sixpdaccount=$sixpdaccount-$threepdaccount;


////////////////////////////////////////
// sub date calculation end/////////////
////////////////////////////////////////



                        $sub_update = new DB_sql;
			$sub_sql = "update agents set pendinginvoice='".$pinv."', activeaccounts='".$activeac."', update_time=NOW(), newsignup='".$newsignup."', cancelled='".$cancelled."', pdaccount='".$pdaccount."', shortcancelled='".$acancelled."', onetimeaccount='".$onetimeaccount."', threepdaccount='".$threepdaccount."' , sixpdaccount='".$sixpdaccount."' , ninepdaccount='".$ninepdaccount."', monthlyaccount='".$monthlyac."', monthlypdi='".$monthlypdi."', addonpackages='".$addons."', addonrevenue='".$addonrev."', subdate='".$subdate."', cancelstr='".$acancelledstr."', cancelamount='".$acancelledcb."', cuamount='".$cumonthamount."' where type = 'subagent' and id = '".$upid."'";
                        $sub_update->query($sub_sql);
                        $sub_update->free();




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

   echo '  <legend>Edit Agent: '.$d_ag_id.'</legend>
        <div class="span6" style="margin-left:25%">
        <form class="form-horizontal" action="user_edit.php" method="post" name="edit_form">
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
                <input type="password" id="password" name="password" placeholder="Leave blank if unwill to modify…"/>
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="repassword">Confirm Password:</label>
             <div class="controls">
                <input type="password" id="repassword" name="repassword" placeholder="Leave blank if unwill to modify…"/>
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="phone">Phone:</label>
             <div class="controls">
                <input type="text" id="phone" name="phone" value="'.$dphone.'" />
             </div>
           </div>';
if($dtype == "subagent"){
echo	  ' <div class="control-group">
           <label class="control-label" for="onetimeRF">One-time Referral Fee:</label>
             <div class="controls">
                <input type="text" id="onetimeRF" name="onetimeRF" value="'.$donetimeRF.'"/>
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="onetimeday">One-time Cashable Days:</label>
             <div class="controls">
                <input type="text" id="onetimeday" name="onetimeday" value="'.$donetimeday.'"/> days
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="monthlyfee">Referral Monthly Fee:</label>
             <div class="controls">
                <input type="text" id="monthlyfee" name="monthlyfee" value="'.$dmonthfee.'"/>
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="monthlyday">Monthly Cashable Days:</label>
             <div class="controls">
                <input type="text" id="monthlyday" name="monthlyday" value="'.$dmonthlyday.'"/> days
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="cancelday">Cancellation Charge-back Days:</label>
             <div class="controls">
                <input type="text" id="canelday" name="cancelday" value="'.$dcancelday.'"/> days
             </div>
           </div>
           <div class="control-group">
           <label class="control-label" for="addonfee">Add-on Fee:</label>
             <div class="controls">
                <input type="text" id="addonfee" name="addonfee" value="'.$daddonfee.'"/> %
             </div>
           </div>';

	if($user_id==540){
	   echo '<div class="control-group">
           <label class="control-label" for="sip">SIP ( Optional ):</label>
             <div class="controls">
                <input type="text" id="sip" name="sip" value="'.$dsip.'"/>
             </div>
           </div>';
	}
}
echo       '</br>
                <input type="hidden" id="uid" name="uid" value="'.$uid.'"/>
                <input type="hidden" id="from" name="from" value="'.$from.'"/>
           <div class="control-group">
                <button value="submit" class="btn btn-primary pull-right" onclick="reghash(this.form, this.form.password, this.form.repassword, this.form.email,this.form.username);">Save</button>
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
