<?php
include("database.php");
include("datediff.php");
require_once("mondiff.php");
if (isset($_POST["reply"]) && isset($_POST["id"]))
{
	$ip=getenv ("REMOTE_ADDR");
	//66.49.254.56
	if ($ip != "66.49.254.56")
	{
	  print "<b><i>Forbidden</i></b>";
	  exit;
	}

	//parsing return data
        $data_array = array();
        $lines = explode("EOL_SIG\n",$_POST["reply"]);
        $titles = explode("\t\t",$lines[0]);
        for($i=1;$i<(count($lines)-1);$i++){
                $data = explode("\t\t",$lines[$i]);
                for($j=0;$j<(count($data));$j++){
                        $data_array[$i-1][$titles[$j]]=$data[$j];
                }
        }
	var_dump($data_array);

	if( $_POST["id"] == "whole"){
		// create agentID array
		$agentID_array = array();
		foreach($data_array as $ag){
			$agentID_array[]=$ag["agentID"];
		}

		// do the clean up
		$dcheck = new DB_Sql;
		$dcheck->query("select * from agents where 1");
		if( $dcheck->num_rows() > 0){
			while($dcheck->next_record()){
				$dagent_id=$dcheck->f("agentID");
				if(($dagent_id != -1) && (!in_array($dagent_id,$agentID_array))){
					$deletion = new DB_Sql;
					$deletion->query("delete from agents where agentID='".$dagent_id."'");
					$deletion->free();
				}
			}
		}
		
		$dcheck->free();
	}



	$ch_ag_id = $data_array[0]["agentID"];
	foreach($data_array as $agent){
		$aemail = $agent["email"];
		$newemail = $agent["newemail"];
		$agent_id = $agent["agentID"];
	//	print "$ch_ag_id\n";
		$ch_flag = false;
		if($ch_ag_id != trim($agent_id)){
			$ch_flag = true;
			$ch_ag_id = trim($agent_id);
		}

			// Create a random salt
			$random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			// Create salted password (Careful not to over season)
			$apassword = hash('sha512', $agent["password"].$random_salt);
			$username = $agent["name"];
			$company = $agent["company"];
			$mfee = $agent["monthlyfee"];
			$nsuspend = $agent["nsuspend"];
				if($nsuspend==""){$nsuspend=0;}
			$nterminate = $agent["nterminate"];
				if($nterminate==""){$nterminate=0;}
			$afee = $agent["addonfee"];
			$activeac = $agent["activeaccount"];
				if($activeac==""){$activeac=0;}
			$monthlyac = $agent["monthlyaccount"];
				if($monthlyac==""){$monthlyac=0;}
			$addons = $agent["addonpackages"];
				if($addons==""){$addons=0;}
			$pinv = $agent["pendinginvoice"];
				if($pinv==""){$pinv=0;}
			$monthlypdi = $agent["monthlypdi"];
				if($monthlypdi==""){$monthlypdi=0;}
			$onetimeRF = $agent["onetimeRF"];
			$notyetaccount = $agent["notyetaccount"];
				if($notyetaccount==""){$notyetaccount=0;}
			$newsignup = $agent["newsignup"];
				if($newsignup==""){$newsignup=0;}
			$onetimeaccount = $agent["onetimeaccount"];
				if($onetimeaccount==""){$onetimeaccount=0;}
			$pdaccount = $agent["pdaccount"];
				if($pdaccount==""){$pdaccount=0;}
			$threepdaccount = $agent["threepdaccount"];
				if($threepdaccount==""){$threepdaccount=0;}
			$sixpdaccount = $agent["sixpdaccount"];
				if($sixpdaccount==""){$sixpdaccount=0;}
			$ninepdaccount = $agent["ninepdaccount"];
				if($ninepdaccount==""){$ninepdaccount=0;}
			$shortcancelled = $agent["shortcancelled"];
				if($shortcancelled==""){$shortcancelled=0;}
			$subagentid = $agent["subagentid"];
			$cancelled = $agent["cancelled"];
				if($cancelled==""){$cancelled=0;}
			$phone = $agent["phone"];
			$phonetwo = $agent["phonetwo"];
			$subtoggle = $agent["subtoggle"];
			$addonrev = $agent["addonrevenue"];
				if($addonrev==""){$addonrev=0;}


			$subdate = $agent["subdates"];
                        $monthlyday = $agent["monthlyday"];
                        $onetimeday = $agent["onetimeday"];
                        $cancelday = $agent["cancelday"];

                        $monthlypaid = $agent["monthlypaidac"];
                        $billable = $agent["billable"];
                        $shipable = $agent["shipable"];
			$cancelstr = $agent["cancelstr"];
                        $cancelamount = $agent["cancelamount"];

		$acheck_sql = "select * from agents where agentID = '".$agent_id."'";


		if($ch_flag){
		// initial all subagent in case of no return from billing, which means no active accounts
		        $subd_update = new DB_sql;
                        $subd_sql = "update agents set pendinginvoice=0, activeaccounts=0, update_time=NOW(), newsignup=0, cancelled=0, pdaccount=0, shortcancelled=0, onetimeaccount=0, threepdaccount=0, sixpdaccount=0, ninepdaccount=0, monthlyaccount=0, monthlypdi=0, notyetaccount=0, addonpackages=0, addonrevenue=0, subdate='', nsuspend=0,nterminate=0 where mainAgent = '".$agent_id."' and type='subagent'";
		//	print $subd_sql."\n";
                        $subd_update->query($subd_sql);
                        $subd_update->free();
		}


		// if it's a subagent, update it
		if($subagentid!=0){




////////////////////////////////////////
// sub date calculation:////////////////
////////////////////////////////////////
                $dstmt = new DB_Sql;
                $dstmt->query("SELECT * from agents where id = '$subagentid'");
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
//			print "$datediff\n";
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
			$sub_sql = "update agents set pendinginvoice='".$pinv."', activeaccounts='".$activeac."', update_time=NOW(), newsignup='".$newsignup."', cancelled='".$cancelled."', pdaccount='".$pdaccount."', shortcancelled='".$acancelled."', onetimeaccount='".$onetimeaccount."', threepdaccount='".$threepdaccount."' , sixpdaccount='".$sixpdaccount."' , ninepdaccount='".$ninepdaccount."', monthlyaccount='".$monthlyac."', monthlypdi='".$monthlypdi."', notyetaccount='".$notyetaccount."', addonpackages='".$addons."', addonrevenue='".$addonrev."', subdate='".$subdate."', cancelstr='".$acancelledstr."', cancelamount='".$acancelledcb."', cuamount='".$cumonthamount."' , shipable='".$shipable."', nsuspend='".$nsuspend."', nterminate='".$nterminate."' where mainAgent = '".$agent_id."' and id = '".$subagentid."'";
		//	echo $sub_sql."\n";

			$sub_update->query($sub_sql);
			$sub_update->free();
		}else{


		$acheck = new DB_Sql;
		$acheck->query($acheck_sql);
		if( $acheck->num_rows() > 0){
			$acheck->next_record();
			//  update
			$agid = $acheck->f("id");
			$old_password=$acheck->f("password");
			$salt=$acheck->f("salt");
		
			$check_password = hash('sha512', $agent["password"].$salt);
			// if password modified
			if($old_password == $check_password){
				$random_salt=$salt;
				$apassword=$old_password;
			}			

			$aupdate = new DB_Sql;
			if($aupdate->query("update agents set username = '".$username."', email = '".$aemail."', agentID='".$agent_id."', password = '".$apassword."', salt='".$random_salt."', monthlyfee='".$mfee."', pendinginvoice='".$pinv."', activeaccounts='".$activeac."', addonfee='".$afee."', addonpackages='".$addons."', onetimeRFee='".$onetimeRF."', update_time=NOW(), newsignup='".$newsignup."', cancelled='".$cancelled."' , phone='".$phone."' , phone2='".$phonetwo."', pdaccount='".$pdaccount."', shortcancelled='".$shortcancelled."', onetimeaccount='".$onetimeaccount."' , threepdaccount='".$threepdaccount."' , sixpdaccount='".$sixpdaccount."' , ninepdaccount='".$ninepdaccount."', monthlyaccount='".$monthlyac."', monthlypdi='".$monthlypdi."', notyetaccount='".$notyetaccount."', subtoggle='".$subtoggle."', addonrevenue='".$addonrev."', monthlyday='".$monthlyday."', onetimeday='".$onetimeday."', cancelday='".$cancelday."', monthlypaid='".$monthlypaid."', billable='".$billable."', cancelstr='".$cancelstr."', cancelamount='".$cancelamount."', company='".$company."', shipable='".$shipable."', neworderemail='".$newemail."', nsuspend='".$nsuspend."', nterminate='".$nterminate."'  where id = '$agid'")){}
			$aupdate->free();

		}else{		// insert new

			$insert_stmt = new DB_Sql;

			$sql = "INSERT INTO agents (username, email, agentID, password, salt, monthlyfee, pendinginvoice, activeaccounts, addonfee, addonpackages, onetimeRFee ,update_time, type, newsignup, cancelled, phone, phone2, pdaccount, shortcancelled, onetimeaccount, threepdaccount, sixpdaccount, ninepdaccount,monthlyaccount, monthlypdi, notyetaccount, subtoggle, addonrevenue,monthlyday,onetimeday,cancelday,monthlypaid, billable,cancelstr,cancelamount,company,shipable,neworderemail,nsuspend,nterminate ) VALUES ('".$username."', '".$aemail."', '".$agent_id."', '".$apassword."', '".$random_salt."', '".$mfee."', '".$pinv."', '".$activeac."', '".$afee."', '".$addons."', '".$onetimeRF."', NOW(), 'main', '".$newsignup."', '".$cancelled."', '".$phone."', '".$phone2."', '".$pdaccount."', '".$shortcancelled."', '".$onetimeaccount."', '".$threepdaccount."', '".$sixpdaccount."', '".$ninepdaccount."', '".$monthlyac."', '".$monthlypdi."', '".$notyetaccount."', '".$subtoggle."', '".$addonrev."', '".$monthlyday."', '".$onetimeday."','".$cancelday."','".$monthlypaid."', '".$billable."', '".$cancelstr."', '".$cancelamount."','".$company."', '".$shipable."','".$newemail."','".$nsuspend."','".$nterminate."' )";
			// Insert to database. 
			if ($insert_stmt->query($sql)) {

			}

			$insert_stmt->free();
		}
		
		$acheck->free();

		}// subagent else

	}// foreach

}else{
                print "No info passed in!";
}

?>
