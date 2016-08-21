<?php
	include("/home/zazeen/public_html/agent/database.php");
	include("/home/zazeen/public_html/agent/datediff.php");
	require_once("/home/zazeen/public_html/agent/mondiff.php");

	$time = date("Y-m");

	$st_db = new DB_Sql;
	$st_db->query("select * from agents where type='subagent'");
	while($st_db->next_record()){

		        $st_monthfee = $st_db->f("monthlyfee");
                        $st_activeaccount = $st_db->f("activeaccounts");
                        $st_pdis = $st_db->f("pendinginvoice");
                        $st_onetimeRF = $st_db->f("onetimeRFee");
                        $st_agent_id = $st_db->f("agentID");
                        $st_uname = $st_db->f("username");
                        $st_utype = $st_db->f("type");
                        $st_magent = $st_db->f("mainAgent");
                        $st_uid = $st_db->f("id");
                        $st_newsignup = $st_db->f("newsignup");
                        $st_onetimeaccount = $st_db->f("onetimeaccount");
                        $st_cancelled = $st_db->f("cancelled");
                        $st_addonrevenue = $st_db->f("addonrevenue");
                        $st_addonfee = $st_db->f("addonfee");
                        $st_monthlyaccount = $st_db->f("monthlyaccount");
                        $st_monthlypdi = $st_db->f("monthlypdi");
			$st_subdate = $st_db->f("subdate");


                        $st_email= $st_db->f("email");
                        if($st_activeaccount == ""){
                                $st_activeaccount=0;
                        }
                        if($st_pdis == ""){
                                $st_pdis=0;
                        }
                        if($st_newsignup == ""){
                                $st_newsignup=0;
                        }
                        if($st_cancelled == ""){
                                $st_cancelled=0;
                        }

                        $revenue = (($st_monthfee*($st_monthlyaccount-$st_monthlypdi))+($st_onetimeRF * ($st_onetimeaccount)) + (($st_addonfee / 100)*$st_addonrevenue));

			$agent_id = $st_magent."-".$st_uid;


			if($st_subdate!=""){
				$month_subdate=array();
				$subdate_array = explode(',',$st_subdate);	
				$monthsale=0;
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

					if($startdate!=""){
						$monthdiff = mon_diff($time, substr($startdate,0,-3));
						//echo "$agent_id, monthdiff".$monthdiff."\n";
						$mdiff = intval(substr($monthdiff,1,strlen($monthdiff)-1));
						//monthly sales number
						if(strpos(" ".$monthdiff,"+") != false && $mdiff >= 1 && $mdiff<2){
							$month_subdate[]=$dsubdate;
							if($inv_flag!="N" && $suspend == "N"){
								$monthsale++;
							}
						}
					}
				}
				$dtail = implode(",",$month_subdate);

				
			
				$acheck_sql = "select * from agent_sales where agentid = '".$agent_id."' and month= '".$time."'";

				$acheck = new DB_Sql;
				$acheck->query($acheck_sql);
				if( $acheck->num_rows() > 0){
					//update
					$acheck->next_record();
					$hisid = $acheck->f("id");

					$aupdate = new DB_Sql;
					echo "update agent_sales set salenum= '".$monthsale."', detail='".$st_subdate."' where id='$hisid'"."\n";
					if($aupdate->query("update agent_sales set salenum= '".$monthsale."', detail='".$dtail."', name='".$st_uname."' where id='$hisid'")){}
					$aupdate->free();


				}else{		// insert new

					$insert_stmt = new DB_Sql;
					
					$sql = "INSERT INTO agent_sales (agentid, month , salenum, detail, mainAgent, name ) VALUES ('".$agent_id."', '".$time."', '".$monthsale."', '".$dtail."', '".$st_magent."', '".$st_uname."')";
					echo $sql."\n";
					// Insert to database. 
					if ($insert_stmt->query($sql)) {

					}

					$insert_stmt->free();
				}
				
				$acheck->free();
				
			}


	}// while


?>
