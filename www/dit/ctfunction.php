<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
sec_session_start();
if(login_check() == true) {


if(isset($_POST['action'],$_POST['eid'])){

        //filter
        $eid = preg_replace("/[^0-9]/","",$_POST['eid']);
        $actarr = array('oncall','voicem','noansw','nointr','techsu','unlock','hangup','sendem','zorder','sorder','invald','fsubmt','french');
        if(!in_array($_POST['action'],$actarr)){
                print '<div class="alert alert-danger alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <strong>Error!</strong> Invalid action type!
                        </div>';
                exit;
        }
        $act = $_POST['action'];

        //Get agent detail
	$user_id = $_SESSION['user_id'];
        $stmt = new DB_Sql;
        if ($stmt->query("SELECT * from agents where id = '$user_id'")) {
                $stmt->next_record();
                $uname = $stmt->f("username");
                $utype = $stmt->f("type");
                $magent = $stmt->f("mainAgent");
                $subagent= $stmt->f("subAgent");
                $agent_id = $stmt->f("agentID");


		if($utype == "subagent"){
			$agent_id = $magent."-".$user_id;
		}
        }
	$stmt->free();


        $today = date('M');




        //Chris, Number fuctions
        $dbsql="select id,status,name,phone,email,callnum,callagent,agentid,unlockflag from agentcx where id='$eid' limit 1";
        $db=new DB_Sql;
        if ($db->query($dbsql))
        {
                if($db->num_rows() > 0){
                        $db->next_record();
                        $uid = $db->f("id");
                        $cstatus = $db->f("status");
                        $cxname = $db->f("name");
                        $cxnumber = $db->f("phone");
                        $cxemail = $db->f("email");
                        $cxcallnum = $db->f("callnum");
                        $oncallagent = $db->f("callagent");
                        $oncallagentid = $db->f("agentid");
                        $unlockflag = $db->f("unlockflag");
                        //$ccmd = $db->f("cmd");

                                $newsql="";
                                //For new calls
				if($cstatus=='oncall' && $act=='oncall'){
					print '<div class="alert alert-danger alert-dismissable">
						  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						  <strong>Error!</strong> '.$oncallagent.' has been on this call!
						</div>';
					exit;
				}


                                if(($cstatus=='init' && $act=='oncall') || ($cstatus=='waitcb' && $act=='oncall') || ($cstatus=='techsu' && $act=='oncall')){

					$read = new DB_Sql;
					$read->query("select id from agentcx where agentid='$agent_id' and status='oncall'");
					if($read->num_rows() > 0){
						print '<div class="alert alert-danger alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Error!</strong> You can only issue one call at a time!
							</div>';
						exit;
					}else{
						addcall($eid);

						if(($cxcallnum+1)>=4 && $unlockflag!="Y"){
							$cxcallnumstr = ($cxcallnum+1)." <span class=\"label label-primary\"> Locked </span>";
						}else{
							$cxcallnumstr = ($cxcallnum+1);
						}

						//Canada number
						if($cstatus=='init' && $act=='oncall'){
							$oncallevent = 'Agent '.$uname.' ( '.$agent_id.' ) issued a New Call to '.$cxname.' ( '.$uid.' ). #'.$cxcallnumstr;
							logevent($agent_id,$uid,$oncallevent,$act);
						}else{
							$oncallevent = 'Agent '.$uname.' ( '.$agent_id.' ) issued a Call Back to '.$cxname.' ( '.$uid.' ). #'.$cxcallnumstr;
							logevent($agent_id,$uid,$oncallevent,$act);
						}

						$resstr = '<span class="label label-success"> Oncall </span>';
						$susql="UPDATE agentcx set status='oncall',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
						$sudb=new DB_Sql;
						if ($sudb->query($susql)){
	/*                                                print '<div>
							  <strong>Success!</strong> Request '.$act.' submitted for Chinese number '.$phone.' ( bind with '.$canumber.' ).
							</div>';*/
							exit;
						}else{
	/*                                                print '<div>
							  <strong>Warning!</strong> Can\'t execute update!
							</div>';*/
							exit;

						}
						$sudb->free();
					}
					$read->free();


                                }


				//No Answer or Voicemail
                                if($act=='noansw'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) called '.$cxname.' ( '.$uid.' ) received no answer.';
					logevent($agent_id,$uid,$event,$act);
					

                                        //Canada number
					$resstr = '<span class="label label-warning"> No Answer </span>';
                                        $susql="UPDATE agentcx set status='init',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }

				

				//French Call
                                if($act=='french'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) called '.$cxname.' ( '.$uid.' ), transferred to French queue.';
					logevent($agent_id,$uid,$event,$act);	

                                        //Canada number
					$resstr = '<span class="label label-info"> French </span>';
                                        $susql="UPDATE agentcx set status='init',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }

                                if($act=='voicem'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) called '.$cxname.' ( '.$uid.' ), left a voicemail msg.';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-info"> Voice Mail </span>';
                                        $susql="UPDATE agentcx set status='init',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }


				//nointr:not interested
                                if($act=='nointr'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) called '.$cxname.' ( '.$uid.' ), customer not interested.';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-danger"> Not Interested </span>';
                                        $susql="UPDATE agentcx set status='failed',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }

				//invald:invalid number
                                if($act=='invald'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) called '.$cxname.' ( '.$uid.' ), the numbers are invalid.';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-danger"> Invalid Number </span>';
                                        $susql="UPDATE agentcx set status='invald',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }

				//techsu:tech support
                                if($act=='techsu'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) sent an tech support request for '.$cxname.' ( '.$uid.' ).';
					logevent($agent_id,$uid,$event,$act);

					if($_POST['sdtp_input1']!=""){
						$callafter = substr($_POST['sdtp_input1'],0,-2)."00";
					}else{
						$callafter = "0000-00-00 00:00:00";

						print '<div class="alert alert-danger alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Error!</strong> Please choose a valid time!
							</div>';
						exit;
					}

					if($_POST['sdtp_input2']!=""){
						$callbefore = substr($_POST['sdtp_input2'],0,-2)."00";
					}else{
						$callbefore = "0000-00-00 00:00:00";
					}

					// curl post
					$curlret= `curl -ks -m 20 --data "number='$cxnumber'&name='$cxname'&type='Request Tech Support - From DIT'&from='DIT'&email='$cxemail'&cdate='$callafter'&crange='$callbefore'&cxeid='$uid'" https://66.49.208.48/techsupport.php`;

					if(strpos($curlret,'Success!') !== false){
						$resstr = '<span class="label label-primary"> Tech Support Requested </span>';
						$susql="UPDATE agentcx set status='techsu',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr',callafter='$callafter', callbefore='$callbefore' where id='$uid' limit 1";
						$sudb=new DB_Sql;
						if ($sudb->query($susql)){
						}else{
						}
						$sudb->free();
					}else{
						//do nothing

					}
					print $curlret;
                                }



				//unlock:unlock a call
                                if($act=='unlock'){
					if(($cxcallnum+1)>=4){
						$cxcallnumstr = ($cxcallnum+1)." <span class=\"label label-success\"> Unlocked </span>";
					}else{
						$cxcallnumstr = ($cxcallnum+1);
					}

					$event = 'Admin '.$uname.' ( '.$agent_id.' ) unlocked '.$cxname.' ( '.$uid.' )\'s call limit. #'.$cxcallnumstr;
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
                                        $susql="UPDATE agentcx set unlockflag='Y',updatetime=NOW() where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }

                                if($act=='hangup'){
					$event = 'Admin '.$uname.' ( '.$agent_id.' ) forced hangup on Agent '.$oncallagent.' ( '.$oncallagentid.' ) calling '.$cxname.' ( '.$uid.' ).';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-danger"> Manual Hangup </span>';
                                        $susql="UPDATE agentcx set status='init',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }

                                if($act=='fsubmt'){
					$event = 'Admin '.$uname.' ( '.$agent_id.' ) forced an order submission with Agent '.$oncallagent.' ( '.$oncallagentid.' ) on customer '.$cxname.' ( '.$uid.' ).';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-success"> Order Submitted </span>';
                                        $susql="UPDATE agentcx set status='zorder',updatetime=NOW(),ordersub=NOW(),result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();

					/*if(isset($_POST["promo"]) && $_POST["promo"]=='7038' && $uid!=603){
						addtrial(1);
					}*/
                                }

                                if($act=='sendem'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) request an email send to '.$cxname.' ( '.$uid.' ).';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-primary"> Emailed ( Later ) </span>';
					if(isset($_POST["group"]) && $_POST["group"]=="Y"){
                                        	$susql="UPDATE agentcx set updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr', emailflag='Y' where id='$uid' limit 1";
					}else{
                                        	$susql="UPDATE agentcx set status='init',updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr', emailflag='Y' where id='$uid' limit 1";
					}
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
						print '<div class="alert alert-success alert-dismissable">
							  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  <strong>Success!</strong> The request has been recorded, we will send the email once we finished the template.
							</div>';
						$sudb->free();
						exit;
                                        }else{
                                        }
                                }

				//zorder: enter the order form
                                if($act=='zorder'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) is filling up an order form for '.$cxname.' ( '.$uid.' ).';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-success"> Submiting Order </span>';
                                        $susql="UPDATE agentcx set updatetime=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
                                        }else{
                                        }
					$sudb->free();
                                }

				//sorder: order submitted
                                if($act=='sorder'){
					$event = 'Agent '.$uname.' ( '.$agent_id.' ) submitted an order for '.$cxname.' ( '.$uid.' ).';
					logevent($agent_id,$uid,$event,$act);

                                        //Canada number
					$resstr = '<span class="label label-success"> Order Submitted </span>';
                                        $susql="UPDATE agentcx set status='zorder',updatetime=NOW(),ordersub=NOW(),agentid='$agent_id',callagent='$uname',result='$resstr' where id='$uid' limit 1";
                                        $sudb=new DB_Sql;
                                        if ($sudb->query($susql)){
						print $event;
                                        }else{
                                        }

					if(isset($_POST["promo"]) && $_POST["promo"]=='7038' && $uid!=603){
						addtrial(1);
					}
					$sudb->free();
                                }
                                //?check current status?
                                //$usql="UPDATE agentcx set status='init',cmd='$act' where id='$uid' limit 1";
                                //$udb=new DB_Sql;
                                /*if ($udb->query($usql)){
                                        print '<div class="alert alert-success alert-dismissable">
                                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                          <strong>Success!</strong> Request '.$act.' submitted for Chinese number '.$phone.'.
                                        </div>';
                                        exit;
                                }else{
                                        print '<div class="alert alert-warning alert-dismissable">
                                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                          <strong>Warning!</strong> Can\'t excute update!
                                        </div>';
                                        exit;

                                }*/


                }else{

                        print '<div class="alert alert-danger alert-dismissable">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                  <strong>Error!</strong> Can\'t find related Chinese number!
                                </div>';
                        exit;

                }
        }else{
		$db->free();
                print '<div class="alert alert-danger alert-dismissable">
                          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                          <strong>Error!</strong> Can\'t connect to database!
                        </div>';
                exit;
        }
}
} else {
   echo 'You are not authorized to access this page, please login. <a href="login.php">Back</a> <br/>';
}
?>
