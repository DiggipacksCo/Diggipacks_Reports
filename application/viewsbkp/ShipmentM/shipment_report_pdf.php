<?php

tcpdf();
$custom_layout = array('101.6', '152.4');
$obj_pdf = new TCPDF('P', PDF_UNIT,$custom_layout, true, 'UTF-8', false);
ob_start();	
if(!empty($data['shipment'][0]->pieces)){

	for($s=0;$s<$data['shipment'][0]->pieces;$s++){
		$number=$s;
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
		$obj_pdf->Rect('1','1','100','130');
		$obj_pdf->Rect('1.5','1.5','99','43');
	///////Column 1///////////////////////
		$obj_pdf->Rect('1.5','1.5','33','20');
		$obj_pdf->Rect('1.5','21.5','49.5','23');
	////////Column 2////////////////////
		$obj_pdf->Rect('34.5','1.5','33','20');


	////////Column 3////////////////////
		$obj_pdf->Rect('67.5','1.5','33','20');
		$obj_pdf->Rect('51','21.5','49.5','23');

	/////////AWB Bar Code//////////
		$obj_pdf->Rect('1.5','52.5','99','20');
	///////////AWB No//////////////////////
		$obj_pdf->Rect('1.5','72.5','99','6');
	/////////Acount No AND DATE//////////////////////
		$obj_pdf->Rect('1.5','78.5','49.5','8');
		$obj_pdf->Rect('51','78.5','49.5','8');
	/////////Weight AND Pieces/////////////////////
		$obj_pdf->Rect('1.5','86.5','49.5','8');
		$obj_pdf->Rect('51','86.5','49.5','8');

	//////////REFRENCE BAR CODE///////////////
		$obj_pdf->Rect('1.5','94.5','99','14');
	//////////Reference Number//////////////
		$obj_pdf->Rect('1.5','108.5','99','8');
	///////////////Code Value/////////////////
		$obj_pdf->Rect('1.5','44.5','99','8');
	// /////////Description ////////////////
	// $obj_pdf->Rect('1.5','134.5','99','6');

	// ob_start();	
	///**********Working For Image*******///


		$image_file =file_get_contents('assets/images/tamcologo.jpg');
		$obj_pdf->Image('@'.$image_file,3,2,30,18);



		// $content = ob_get_contents();
		// ob_end_clean();

		$style['position'] = 'C';
/////////////////////////here QR Code No 2d ////////////////
		$obj_pdf->write2DBarcode($data['shipment'][0]->slip_no, 'QRCODE,H', 70.5,4,33,20, $style, 'N');
//////////////////////here Pass AWB NO too////////////////////////////////////////
		$obj_pdf->write1DBarcode($data['shipment'][0]->slip_no, 'C128', 3.5, 54.5, 62, 16, 0.7, $style, 'N');
///////////////////////here Pass Reference No ////////////////////////////////
		$obj_pdf->write1DBarcode($data['shipment'][0]->booking_id, 'C128', 3.5, 95.5, 62, 12, 0.7, $style, 'N');
//$obj_pdf->SetFont('aealarabiya','',9);
//////////////////////here////////////////////////////////////////
		$obj_pdf->SetTitle($data['shipment'][0]->slip_no);

		$obj_pdf->Text(2,22,'From: ');
		$obj_pdf->Text(2,28,'Mobile: ');
		$obj_pdf->Text(2,31,'Address: ');
		$obj_pdf->Text(2,80,'Account No: ');
		$obj_pdf->Text(2,89,'Weight: ');

		$obj_pdf->Text(52,80,'Booking Date: ');
		$obj_pdf->Text(37,47,'COD: ');
		$obj_pdf->Text(52,89,'Pieces: ');
		$obj_pdf->Text(52,22,'To: ');
		$obj_pdf->Text(52,28,'Mobile: ');
		$obj_pdf->Text(52,31,'Address: ');
		$obj_pdf->Text(2,120,'Description: ');
		$number++;
		$obj_pdf->Text(37,73.5,$data['shipment'][0]->slip_no);
		$obj_pdf->Text(64,73.5,$number.'/'.$data['shipment'][0]->pieces);

		$obj_pdf->Text(37,110.5,$data['shipment'][0]->booking_id);






//////////////////////here////////////////////////////////////////
		$obj_pdf->SetFont('helvetica','',7);

		$obj_pdf->SetFont('aealarabiya','B',20);
		$obj_pdf->Text(75,10,$data['city_code']);
		$obj_pdf->SetFont('aealarabiya','',7);
		$obj_pdf->Text(12,22.6,$data['shipment'][0]->sender_name);

		
		$obj_pdf->Text(14,28.5,$data['shipment'][0]->sender_phone);
		$obj_pdf->MultiCell(42,10,$data['shipment'][0]->sender_address.', '.$data['city_code1'], 0, 'L', false,2, 3, 35, '', true);


		$obj_pdf->Text(15,89.5,$data['shipment'][0]->weight.' (KG)');

		$obj_pdf->Text(46,47.5,$data['shipment'][0]->total_cod_amt.' (SR)');
		$obj_pdf->Text(73,80.7,$data['shipment'][0]->entrydate);
		$obj_pdf->Text(20,80.7,$data['account_no']);
		$obj_pdf->Text(58,22.6,$data['shipment'][0]->reciever_name);

		$obj_pdf->Text(64,28.5,$data['shipment'][0]->reciever_phone);
		$obj_pdf->MultiCell(42,10,$data['shipment'][0]->reciever_address.', '.$data['city_code2'], 0, 'L', false,2, 53, 35, '', true);

		$obj_pdf->Text(65,89.5,$data['shipment'][0]->pieces.' (PCS)');
		$obj_pdf->Text(20,120.7,$data['shipment'][0]->status_describtion);



	}   
}        
$content = ob_get_contents();
ob_end_clean();
$obj_pdf->writeHTML($content, true, false, true, false, '');
$obj_pdf->Output($data['shipment'][0]->slip_no.'_Report.pdf', 'I');
	//I for view and D for download
	//$obj_pdf->Output($data['shipment'][0]->slip_no.'_Report.pdf', 'I');

?>