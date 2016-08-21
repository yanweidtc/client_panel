<?
require_once('/var/www/dit/phpmailer/fpdf.php'); 
require_once('/var/www/dit/emailpdf/fpdi.php');
$pdf = new FPDI();

$pdf->AddPage(); 

$pdf->setSourceFile('/var/www/dit/phpmailer/EMC_edited2/Zazeen_Poster.pdf'); 

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
$pdf->Write(0, 'Firstname Lastname ,');
//force the browser to download the output
$pdf->Output('/var/www/dit/emailpdf/test_generated.pdf', 'F');

?>
