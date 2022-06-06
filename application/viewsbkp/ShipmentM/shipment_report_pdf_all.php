<?php
	
	tcpdf();
	$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	ob_start();

	if(!empty($shipment)){
	for($i=0;$i<count($shipment);$i++){
	// $z=192;
	// for($j=0;$j<count($shipment);$j++){

	// if($shipment[$i]!=$shipment[$j]){
	$obj_pdf->SetCreator(PDF_CREATOR);
	$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$obj_pdf->SetDefaultMonospacedFont('helvetica');
	//$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$obj_pdf->SetFont('helvetica', '', 9);
	$obj_pdf->setFontSubsetting(false);
	
	$obj_pdf->AddPage();
	$obj_pdf->Rect('5','30','200','236');
    $obj_pdf->Rect('6','31','198','234');
    $obj_pdf->Rect('7','32','97','50');
    $obj_pdf->Rect('106','32','97','50');
    $obj_pdf->Rect('7','84','196','36');
    $obj_pdf->Rect('7','136','196','128');
	$obj_pdf->Rect('7','136','98','18');
	$obj_pdf->Rect('105','136','98','18');
	//*********Table**********//

	//*********Table Rows**********//
	$obj_pdf->Rect('7','180','196','10');
	// $obj_pdf->Rect('7','190','196','10');
	// $obj_pdf->Rect('7','200','196','10');
	// $obj_pdf->Rect('7','210','196','10');
	// $obj_pdf->Rect('7','220','196','10');
	// $obj_pdf->Rect('7','230','196','10');
	// $obj_pdf->Rect('7','240','196','10');
	// $obj_pdf->Rect('7','250','196','10');
	//*********Table Rows**********//

	 //*********Table Columns**********//
	$obj_pdf->Rect('7','180','62','80');
	$obj_pdf->Rect('69','180','90','80');
	$obj_pdf->Rect('159','180','44','80');
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

	

	
	

		
	///**********Working For Image*******///
// $image_file =file_get_contents('assets/images/logo.png');
// $obj_pdf->Image('@'.$image_file,12,12,50,14);
	///**********Working For Image*******///



$obj_pdf->SetTitle('Shipments_Report');
//$obj_pdf->Text(4,268,'(For Staff Use Only)');
$obj_pdf->Text(131,268,'Copyright © 2016-2018. Fastcoo All rights reserved.');
$obj_pdf->SetFont('helvetica','B',15);
$obj_pdf->Text(87,12,'TAM EXPRESS');
$obj_pdf->Text(10,36,'From : ');
$obj_pdf->Text(10,55,'Mobile : ');
$obj_pdf->Text(10,65,'City : ');
$obj_pdf->Text(10,140,'Weight : ');
$obj_pdf->Text(20,182,'SKU');
$obj_pdf->Text(82,182,'Description');
$obj_pdf->Text(162,182,'Quantity');
$obj_pdf->Text(62,165,'Booking Date : ');
$obj_pdf->Text(82,125,'COD : ');
$obj_pdf->Text(108,36,'To : ');
$obj_pdf->Text(108,55,'Mobile : ');
$obj_pdf->Text(108,65,'City : ');
$obj_pdf->Text(108,140,'Pieces : ');


$style['position'] = 'C';
$obj_pdf->SetFont('aealarabiya','',15);

$obj_pdf->write1DBarcode($shipment[$i]->slip_no, 'C128', 30,87, 200, 25, 0.7, $style, 'N');
$obj_pdf->Cell(0, 0,$shipment[$i]->slip_no , 0, 2,'C');

$obj_pdf->SetFont('helvetica','',15);
$obj_pdf->Text(35,36,$shipment[$i]->sender_name);
$obj_pdf->Text(35,55,$shipment[$i]->sender_phone);
$obj_pdf->Text(35,64,$shipment[$i]->sender_address);
$obj_pdf->Text(35,140,$shipment[$i]->weight.' (KG)');
$obj_pdf->Text(35,220,'');

$obj_pdf->Text(100,125,$shipment[$i]->total_cod_amt.' (SR)');
$obj_pdf->Text(105,165,$shipment[$i]->entrydate);

$obj_pdf->Text(133,36,$shipment[$i]->reciever_name);
$obj_pdf->Text(133,55,$shipment[$i]->reciever_phone);
$obj_pdf->Text(133,65,$shipment[$i]->reciever_address);
$obj_pdf->Text(133,140,$shipment[$i]->pieces.' (PCS)');
		
$obj_pdf->Text(9,192,$shipment[$i]->sku);
$obj_pdf->Text(70,192,$shipment[$i]->description);
$obj_pdf->Text(162,192,$shipment[$i]->piece);
	
	// }
	// else{
	//     $z=$z+10;
	// 	$obj_pdf->Text(10,$z,$shipment[$i]->sku);
	// 	$obj_pdf->Text(78,$z,$shipment[$i]->description);
	// 	$obj_pdf->Text(162,$z,$shipment[$i]->piece);
 //      }                 
	// }
  }               
}
$content = ob_get_contents();	
 		ob_end_clean();
	$obj_pdf->writeHTML($content, true, false, true, false, '');
	$obj_pdf->Output('Shipments_Report.pdf', 'I');
	//I for view and D for download
	//$obj_pdf->Output($data['shipment'][0]->slip_no.'_Report.pdf', 'I');
	
?>