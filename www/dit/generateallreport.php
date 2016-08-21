<?
  include_once('./js/header.php');
  $ajax = false;

include 'database.php';
include 'functions.php';
   global $n;

	function array2csv(array &$array)
	{
	   if (count($array) == 0) {
	     return null;
	   }
	   ob_start();
	   $df = fopen("/var/www/dit/reports/all_agent_report_" . date("Y-m-d") . ".csv", 'w');
	   fputcsv($df, array('Timestamp','Agent Name or ID','Customer Name','Customer Number','Last Action','Last Action Timestamp','Result/Disposition(Customer Final State)'));
	   foreach ($array as $row) {
	      fputcsv($df, $row);
	   }
	   fclose($df);
	   return ob_get_clean();
	}

	$exarr=array();

	$dbsql3="SELECT cx.updatetime,nl.agentid, nl.eid, cx.name, cx.phone, cx.result, ag.username, cx.ordersub,al.action,nl.mupdatetime
FROM  	 (
           SELECT agentid,eid, MAX(updatetime) as mupdatetime
           FROM agentlog
           WHERE agentid<>'623411' AND agentid<>'623411-562' AND agentid<>'818367' AND eid<>'603'
		   GROUP BY agentid,eid
         ) nl,
		 agentcx cx, agents ag, agentlog al
WHERE   cx.id = nl.eid
        AND ag.ag_id = nl.agentid
		AND nl.agentid = al.agentid
		AND nl.eid = al.eid
		AND nl.mupdatetime = al.updatetime";

	$db2=new DB_Sql;
	if ($db2->query($dbsql3))
	{
		while($db2->next_record()){
			$str = $db2->f("result");
			$start  = strpos($str, '>');
			$end    = strpos($str, '<', $start + 1);
			$length = $end - $start;
			$result = substr($str, $start + 1, $length - 1);
			
			//Last Action words
			$actstr=$db2->f("action");
			$action = $db2->f("action");
			if($action == 'noansw'){
				$actstr="Called - No Answer";
			}else if($action == 'oncall'){
				$actstr="Calling";
			}else if($action == 'voicem'){
				$actstr="Called - Reached Voice Mail";
			}else if($action == 'callbl'){
				$actstr="Called - Request Call Back Later";
			}else if($action == 'nointr'){
				$actstr="Called - No Interest";
			}else if($action == 'techsu'){
				$actstr="Called - Request Tech Support";
			}else if($action == 'hangup'){
				$actstr="Admin - Forced Hang Up";
			}else if($action == 'sendem'){
				$actstr="Called - Request Send Email";
			}else if($action == 'invald'){
				$actstr="Called - Invalid Number";
			}else if($action == 'emsent'){
				$actstr="Auto - Email Sent";
			}else if($action == 'fsubmt'){
				$actstr="Admin - Force Order Submission";
			}else if($action == 'zorder'){
				$actstr="Order - Filling The Order Form";
			}else if($action == 'sorder'){
				$actstr="Order - Order Form Submitted";
			}

			$exentry=array();	
			if($db2->f("ordersub")!="0000-00-00 00:00:00"){
				$exentry[0]=$db2->f("ordersub");
				$exentry[1]=$db2->f("username").' ('.$db2->f("agentid").')';
				$exentry[2]=$db2->f("name");
				$exentry[3]=$db2->f("phone");
				$exentry[4]=$actstr;
				$exentry[5]=$db2->f("mupdatetime");
				$exentry[6]=' Order Submitted ';
			}else{
				$exentry[0]=$db2->f("updatetime");
				$exentry[1]=$db2->f("username").' ('.$db2->f("agentid").')';
				$exentry[2]=$db2->f("name");
				$exentry[3]=$db2->f("phone");
				$exentry[4]=$actstr;
				$exentry[5]=$db2->f("mupdatetime");
				$exentry[6]=$result;
			}
			$exarr[]=$exentry;
		}
	}
	$db2->free();


	array2csv($exarr);

	//Send Emails
	        require_once('/var/www/dit/phpmailer/PHPMailer-master/PHPMailerAutoload.php');

                //Create a new PHPMailer instance
                $mail = new PHPMailer();
                // Set PHPMailer to use the sendmail transport
                $mail->isSendmail();
                //Set who the message is to be sent from
                $mail->setFrom('ditreport@zazeen.com','Zazeen DIT Report');
                //Set an alternative reply-to address
                $mail->addReplyTo('ditreport@zazeen.com','Zazeen DIT Report');
                //Set who the message is to be sent to
//                $mail->addAddress('jamie.mallory@dependableit.com');
                $mail->addAddress('scheduling@dependableit.com');
//                $mail->addAddress('chris@canaca.com');
		// New emails
                $mail->addAddress('jenn.ryan@dependableit.com');
                $mail->addAddress('ashley.simoes@dependableit.com');
                $mail->addAddress('julia.deeks@dependableit.com');
                //Set the subject line
                $mail->Subject = 'Daily Report from CRM panel ( '.date("Y-m-d H:i:s").' )';
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                $content = '<HTML><HEAD>
                                <META http-equiv="Content-Type" content="text/html; charset=UTF-8">
                                <META http-equiv="X-UA-Compatible" content="IE=8"></HEAD>
                                <BODY>
                                 <P>Auto_Generate_Report, please do not reply to the sender, if you have any questions please contant Chris at chris@canaca.com.</P>
				</BODY>
			    </HTML>';
                $mail->msgHTML($content);
                //Replace the plain text body with one created manually
                $mail->AltBody = 'Auto_Generate_Report, please do not reply to the sender, if you have any questions please contant Chris at chris@canaca.com.';

                //Attach a file
                $mail->addAttachment("/var/www/dit/reports/all_agent_report_" . date("Y-m-d") . ".csv");
                //$mail->addAttachment('/var/www/dit/phpmailer/EMC_edited2/Zazeen_Poster.pdf');

                //send the message, check for errors
                if (!$mail->send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                    echo "Message sent!";
		}

	die();

?>
