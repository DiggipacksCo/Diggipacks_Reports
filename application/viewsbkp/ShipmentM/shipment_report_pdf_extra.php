<?php
	
	tcpdf();
	$custom_layout = array('101.6', '152.4');
	$obj_pdf = new TCPDF('P', PDF_UNIT,$custom_layout, true, 'UTF-8', false);
	$obj_pdf->SetCreator(PDF_CREATOR);
	//$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	//$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$obj_pdf->SetDefaultMonospacedFont('helvetica');
	//$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	//$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$obj_pdf->setPrintHeader(false);
    $obj_pdf->setPrintFooter(false);
	$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$obj_pdf->SetFont('helvetica', '', 9);
	$obj_pdf->setFontSubsetting(false);
	$obj_pdf->AddPage();
	$obj_pdf->Rect('1','11','100','130');
	$obj_pdf->Rect('1.5','11.5','99','43');
	///////Column 1///////////////////////
	$obj_pdf->Rect('1.5','11.5','33','20');
	$obj_pdf->Rect('1.5','31.5','49.5','23');
	////////Column 2////////////////////
	$obj_pdf->Rect('34.5','11.5','33','20');
	

	////////Column 3////////////////////
	$obj_pdf->Rect('67.5','11.5','33','20');
	$obj_pdf->Rect('51','31.5','49.5','23');

	/////////AWB Bar Code//////////
	$obj_pdf->Rect('1.5','62.5','99','20');
	///////////AWB No//////////////////////
	$obj_pdf->Rect('1.5','82.5','99','6');
	/////////Acount No AND DATE//////////////////////
	$obj_pdf->Rect('1.5','88.5','49.5','8');
	$obj_pdf->Rect('51','88.5','49.5','8');
	/////////Weight AND Pieces/////////////////////
	$obj_pdf->Rect('1.5','96.5','49.5','8');
	$obj_pdf->Rect('51','96.5','49.5','8');

	//////////REFRENCE BAR CODE///////////////
	$obj_pdf->Rect('1.5','104.5','99','14');
	//////////Reference Number//////////////
	$obj_pdf->Rect('1.5','118.5','99','8');
	///////////////Code Value/////////////////
	$obj_pdf->Rect('1.5','54.5','99','8');
	// /////////Description ////////////////
	// $obj_pdf->Rect('1.5','134.5','99','6');

	// $obj_pdf->Cell(0, 0,$data['shipment'][0]->slip_no , 0, 2,'C');
 //    $obj_pdf->Rect('6','31','198','234');
 //    $obj_pdf->Rect('7','32','97','77');
 //    $obj_pdf->Rect('106','32','97','77');
 //    $obj_pdf->Rect('7','110','196','25');
 //    $obj_pdf->Rect('7','136','196','128');
	// $obj_pdf->Rect('7','150','98','15');
	// $obj_pdf->Rect('105','150','98','15');
	// //*********Table**********//

	// //*********Table Rows**********//
	// $obj_pdf->Rect('7','180','196','10');
	// $obj_pdf->Rect('7','190','196','10');
	// $obj_pdf->Rect('7','200','196','10');
	// $obj_pdf->Rect('7','210','196','10');
	// $obj_pdf->Rect('7','220','196','10');
	// $obj_pdf->Rect('7','230','196','10');
	// $obj_pdf->Rect('7','240','196','10');
	// $obj_pdf->Rect('7','250','196','10');
	//*********Table Rows**********//

	 //*********Table Columns**********//
	// $obj_pdf->Rect('7','180','62','80');
	// $obj_pdf->Rect('69','180','90','80');
	// $obj_pdf->Rect('159','180','44','80');
	//     ///*******1******///
	// 	$obj_pdf->Rect('7','180','50','10');
	// 	$obj_pdf->Rect('57','180','90','10');
	// 	$obj_pdf->Rect('147','180','56','10');
	//  ///*******1******///
	
	//     ///*******2******///
	//  	$obj_pdf->Rect('7','190','50','10');
	// 	$obj_pdf->Rect('57','190','90','10');
	// 	$obj_pdf->Rect('147','190','56','10');
		
	//  ///*******2******///
	
	//     ///*******3******///
	//  	$obj_pdf->Rect('7','200','50','10');
	// 	$obj_pdf->Rect('57','200','90','10');
	// 	$obj_pdf->Rect('147','200','56','10');
		
	//  ///*******3******///

	// 	///*******4******///
	//  	$obj_pdf->Rect('7','210','50','10');
	// 	$obj_pdf->Rect('57','210','90','10');
	// 	$obj_pdf->Rect('147','210','56','10');
		
	//  ///*******4******///
		
	// 	///*******5******///
	//  	$obj_pdf->Rect('7','220','50','10');
	// 	$obj_pdf->Rect('57','220','90','10');
	// 	$obj_pdf->Rect('147','220','56','10');
		
	//  ///*******5******///
		
	// 	///*******6******///
	//  	$obj_pdf->Rect('7','230','50','10');
	// 	$obj_pdf->Rect('57','230','90','10');
	// 	$obj_pdf->Rect('147','230','56','10');
		
	//  ///*******6******///
		
	// 	///*******7******///
	//  	$obj_pdf->Rect('7','240','50','10');
	// 	$obj_pdf->Rect('57','240','90','10');
	// 	$obj_pdf->Rect('147','240','56','10');
		
	//  ///*******7******///
	// 	///*******7******///
	//  	$obj_pdf->Rect('7','250','50','10');
	// 	$obj_pdf->Rect('57','250','90','10');
	// 	$obj_pdf->Rect('147','250','56','10');
		
	//  ///*******7******///
	 //*********Table Columns**********//

	//*********Table**********//

	

	
	ob_start();	
	///**********Working For Image*******///

$image_file =file_get_contents('assets/images/tamcologo.jpg');
$obj_pdf->Image('@'.$image_file,3,12,30,18);
	/**********Working For Image*******///


$content = ob_get_contents();
		ob_end_clean();
		

$style['position'] = 'C';
/////////////////////////here QR Code No 2d ////////////////
$obj_pdf->write2DBarcode($data['shipment'][0]->slip_no, 'QRCODE,H', 70.5,14,33,20, $style, 'N');
//////////////////////here Pass AWB NO too////////////////////////////////////////
$obj_pdf->write1DBarcode($data['shipment'][0]->slip_no, 'C128', 3.5, 64.5, 62, 16, 0.7, $style, 'N');
///////////////////////here Pass Reference No ////////////////////////////////
$obj_pdf->write1DBarcode($data['shipment'][0]->booking_id, 'C128', 3.5, 105.5, 62, 12, 0.7, $style, 'N');

//////////////////////here////////////////////////////////////////
$obj_pdf->SetTitle($data['shipment'][0]->slip_no);
// //$obj_pdf->Text(4,268,'(For Staff Use Only)');
// $obj_pdf->Text(131,268,'Copyright © 2016-2018. Fastcoo All rights reserved.');
// $obj_pdf->SetFont('helvetica','B',15);
// $obj_pdf->Text(87,12,'TAM EXPRESS');
$obj_pdf->Text(2,32,'From: ');
$obj_pdf->Text(2,38,'Mobile: ');
$obj_pdf->Text(2,41,'City: ');
$obj_pdf->Text(2,90,'Account No: ');
$obj_pdf->Text(2,99,'Weight: ');
// $obj_pdf->Text(20,182,'SKU');
// $obj_pdf->Text(82,182,'Description');
// $obj_pdf->Text(162,182,'Quantity');
$obj_pdf->Text(52,90,'Booking Date: ');
$obj_pdf->Text(37,57,'COD: ');
$obj_pdf->Text(52,99,'Pieces: ');
$obj_pdf->Text(52,32,'To: ');
$obj_pdf->Text(52,38,'Mobile: ');
$obj_pdf->Text(52,41,'City: ');
$obj_pdf->Text(37,83.5,$data['shipment'][0]->slip_no);
$obj_pdf->Text(37,120.5,$data['shipment'][0]->booking_id);
$obj_pdf->Text(2)
///////////////Pass AWB no/////////////////





//////////////////////here////////////////////////////////////////
$obj_pdf->SetFont('helvetica','',7);
//aefurat
//$obj_pdf->SetFont('aealarabiya','',15);
$obj_pdf->SetFont('aealarabiya','',9);

$obj_pdf->Text(12,33,$data['shipment'][0]->sender_name);
//$obj_pdf->SetFont('aefurat','',15);
//////////////////////here////////////////////////////////////////
//$obj_pdf->MultiCell(70,10,$data['shipment'][0]->sender_name, 0, 'L', false,2, 33, 36, '', true);
 $obj_pdf->Text(14,38.5,$data['shipment'][0]->sender_phone);
$obj_pdf->MultiCell(32,10,$data['shipment'][0]->sender_address, 0, 'L', false,2, 14, 41, '', true);

//$obj_pdf->Text(12,48,$data['shipment'][0]->sender_address);
// $obj_pdf->setCellPaddings(1, 1, 1, 1);
// $obj_pdf->setCellMargins(1, 1, 1, 1);	
//$data['shipment'][0]->sender_address	

//////////////////////here////////////////////////////////////////
// $obj_pdf->MultiCell(70,10,$data['shipment'][0]->sender_address , 0, 'L', false,2, 28, 70, '', true);
$obj_pdf->Text(15,99.5,$data['shipment'][0]->weight.' (KG)');
// $obj_pdf->Text(35,220,'');
$obj_pdf->Text(46,58,$data['shipment'][0]->total_cod_amt.' (SR)');
 $obj_pdf->Text(73,90.5,$data['shipment'][0]->entrydate);
 $obj_pdf->Text(20,91,$data['account_no']);
$obj_pdf->Text(62,32,$data['shipment'][0]->reciever_name);
// $obj_pdf->MultiCell(70,10,$data['shipment'][0]->reciever_name, 0, 'L', false,2, 127, 36, '', true);
$obj_pdf->Text(64,38.5,$data['shipment'][0]->reciever_phone);
$obj_pdf->MultiCell(32,10,$data['shipment'][0]->reciever_address, 0, 'L', false,2, 64, 41, '', true);
//$obj_pdf->Text(133,65,$data['shipment'][0]->reciever_address);
//$obj_pdf->Cell(0, 0, 'TEST CELL STRETCH: no stretch', 1, 1, 'C', 0, '', 0);
// $obj_pdf->setCellPaddings(1, 1, 1, 1);
// $obj_pdf->setCellMargins(1, 1, 1, 1);		
//$data['shipment'][0]->reciever_address
//////////////////////here////////////////////////////////////////
// $obj_pdf->MultiCell(70,10,$data['shipment'][0]->reciever_address , 0, 'L', false,2, 126, 70, '', true);
$obj_pdf->Text(65,99.5,$data['shipment'][0]->pieces.' (PC)');
			
$j=182;
if($sku==Null){

}                         
else{
	
  for($i=0;$i<count($sku);$i++){      
  		$j=$j+10;                
	   // $obj_pdf->Text(9,$j,$sku[$i]);
	   // $obj_pdf->Text(70,$j,$data['shipment'][0]->status_describtion);
	   // $obj_pdf->Text(162,$j,$piece[$i]);
   }
                       
}                          
                   

	$obj_pdf->writeHTML($content, true, false, true, false, '');
	$obj_pdf->Output($data['shipment'][0]->slip_no.'_Report.pdf', 'I');
	//I for view and D for download
	//$obj_pdf->Output($data['shipment'][0]->slip_no.'_Report.pdf', 'I');
	
?>