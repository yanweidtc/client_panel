<?php
include 'database.php';
include 'functions.php';


//Select emailflag
//$dbsql="select name,aid,emailflag from agentcx where emailflag='Y' limit 1";
$dbsql="select id,name,aid,emailflag,tmail from agentcx where emailflag='Y' order by updatetime desc";
//$dbsql="select id,name,aid,emailflag,tmail from agentcx where emailflag='Y'";
$db=new DB_Sql;
if ($db->query($dbsql))
{
	if($db->num_rows() > 0){
		while($db->next_record()){
		$name = $db->f("name");
		$cid = $db->f("aid");
		$email = $db->f("tmail");
		$uid = $db->f("id");


//		$email="chris@canaca.com";
//		$email="sheepcross@gmail.com";
		print "Go email $name at $email\n";

		require_once('/var/www/dit/phpmailer/PHPMailer-master/PHPMailerAutoload.php');

		//Create a new PHPMailer instance
		$mail = new PHPMailer();
		// Set PHPMailer to use the sendmail transport
		$mail->isSendmail();
		//Set who the message is to be sent from
		$mail->setFrom('sales@zazeen.com','Zazeen TV');
		//Set an alternative reply-to address
		$mail->addReplyTo('sales@zazeen.com','Zazeen TV');
		//Set who the message is to be sent to
		$mail->addAddress($email);
//		$mail->addAddress('chris@canaca.com');
		//$mail->addAddress('sheepcross@gmail.com');
		//$mail->addAddress('sheepcross@hotmail.com');
		//Set the subject line
		$mail->Subject = 'Invitation from Zazeen TV!';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$content = '<HTML><HEAD>
				<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<META http-equiv="X-UA-Compatible" content="IE=8"></HEAD>
				<BODY>
				 <P>Dear '.$name.',</P>';
		$zazeentmp = file_get_contents('/var/www/dit/phpmailer/EMC_edited2/EMC_edited2.html');
		$content .= substr($zazeentmp,3);
		$mail->msgHTML($content);
		//$mail->msgHTML(file_get_contents('/var/www/dit/emailtempate.tmp'));
		//Replace the plain text body with one created manually
		$mail->AltBody = file_get_contents('/var/www/dit/emailtempate.tmp');

		/*******************************Generate the pdf with the name*********************************/
		require_once('/var/www/dit/phpmailer/fpdf.php');
		require_once('/var/www/dit/emailpdf/fpdi.php');
/*		$pdf = new FPDI();

		$pdf->AddPage();

		$pdf->setSourceFile('/var/www/dit/phpmailer/Zazeenemail.pdf');

		// import page 1
		$tplIdx = $pdf->importPage(1);
		//use the imported page and place it at point 0,0; calculate width and height
		//automaticallay and ajust the page size to the size of the imported page
		$pdf->useTemplate($tplIdx, 0, 0, 0, 0, true);

		// now write some text above the imported page
		$pdf->SetFont('Arial', '', '10');
		$pdf->SetTextColor(0,0,0);
		//set position in pdf document
		$pdf->SetXY(33, 53.5);
		//first parameter defines the line height
		$pdf->Write(0, $name.',');

		//Add page2
	        $pdf->endPage();
		$tplIdx = $pdf->importPage(2);
		$pdf->AddPage();

		//force the browser to download the output
		$pdf->Output('/var/www/dit/emailpdf/Zazeen_Poster.pdf', 'F');*/


		// Original file with multiple pages 
		$fullPathToFile = '/var/www/dit/phpmailer/Zazeenemail.pdf';

		require_once('/var/www/dit/pdfclass.php');

		// initiate PDF
		$pdf = new PDF();
//		$pdf->setFontSubsetting(true);


		// add a page
		$pdf->AddPage();

		// The new content
                $pdf->SetFont('Arial', '', '10');
                $pdf->SetTextColor(0,0,0);
                //set position in pdf document
                $pdf->SetXY(33, 53.5);
                //first parameter defines the line height
                $pdf->Write(0, $name.',');

		// THIS PUTS THE REMAINDER OF THE PAGES IN
		if($pdf->numPages>1) {
		    for($i=2;$i<=$pdf->numPages;$i++) {
//			$pdf->endPage();
			$pdf->_tplIdx = $pdf->importPage($i);
			$pdf->AddPage();
		    }
		}

		$pdf->Output('/var/www/dit/emailpdf/Zazeen_Poster.pdf', 'F');

		/*******************************Generate the pdf with the name End*********************************/

		//Attach a file
		$mail->addAttachment('/var/www/dit/emailpdf/Zazeen_Poster.pdf');
		//$mail->addAttachment('/var/www/dit/phpmailer/EMC_edited2/Zazeen_Poster.pdf');

		//send the message, check for errors
		if (!$mail->send()) {
		    echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
		    echo "Message sent for $name!";



		//Confirm email sented
	        $event = 'Email sent for '.$name.' ( '.$uid.' ).';
		logevent('10086',$uid,$event,'emsent');

				//Canada number
		$resstr = '<span class="label label-primary"> Emailed </span>';
		$susql="UPDATE agentcx set updatetime=NOW(), result='$resstr', emailflag='S' where id='$uid' limit 1";
		$sudb=new DB_Sql;
		if ($sudb->query($susql)){
	       		print $event;
		}else{
		}

		}

	  }
	}
}

?>
