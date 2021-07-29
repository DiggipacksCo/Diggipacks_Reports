<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends MY_Controller {

	function __construct() {
		parent::__construct(); 
		if(menuIdExitsInPrivilageArray(21)=='N')
		{
			redirect(base_url().'notfound'); die;
			
		}
       
		$this->load->model('Finance_model');
		$this->load->model('Storage_model');
		
		//$this->load->helper('utility');  
		// $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	public function run_shell_dynamic() {
		
		exec("php /var/www/html/diggipack_new/fs_files/fm-track/dynamic_invoice.php > /dev/null 2>&1 &"); //dynamic_invoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/return_dynamicinvoice.php > /dev/null 2>&1 &"); //return_dynamicinvoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/storage_charges.php > /dev/null 2>&1 &"); //storage_charges
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/storage_dynamic_invoice.php > /dev/null 2>&1 &"); //storage_dynamic_invoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/inventory_invoice.php > /dev/null 2>&1 &"); //inventory_invoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/onhold_dynamicinvoice.php > /dev/null 2>&1 &"); //onhold_dynamicinvoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/pickup_dynamicinvoice.php > /dev/null 2>&1 &"); //pickup_dynamicinvoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/cancel_dynamic_invoice.php > /dev/null 2>&1 &"); //cancel_dynamic_invoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/inbound_dynamicinvoice.php > /dev/null 2>&1 &"); //inbound_dynamicinvoice
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/storage_calculation.php > /dev/null 2>&1 &"); //storage_calculation
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/skubarcode_invoice.php > /dev/null 2>&1 &"); //skubarcode_invoice        
        sleep(5);
        exec("php /var/www/html/diggipack_new/fs_files/fm-track/storage_dynamic_invoice.php > /dev/null 2>&1 &"); //storage_dynamic_invoice      
		 
        return true;
    }

	public function run_shell_fixrate() {
	
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/fixrate_invoice.php > /dev/null 2>&1 &",$output); //fixrate_invoice
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/return_fixrateinvoice.php > /dev/null 2>&1 &", $output1); //return_fixrateinvoice
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/storage_charges.php > /dev/null 2>&1 &", $output2);  //storage_charges
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/storage_fixrate_invoice.php > /dev/null 2>&1 &", $output7); //storage_fixrate_invoice
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/inventory_invoice.php > /dev/null 2>&1 &", $output4);  //inventory_invoice
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/onhold_fixrateinvoice.php > /dev/null 2>&1 &", $output5); //onhold_fixrateinvoice
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/pickup_fixrateinvoice.php > /dev/null 2>&1 &", $output6);	 //pickup_fixrateinvoice        
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/cancel_fixrate_invoice.php > /dev/null 2>&1 &", $output8); //cancel_fixrate_invoice
            sleep(5);           
            exec("php /var/www/html/diggipack_new/fs_files/fm-track/storage_fixrate_invoice.php > /dev/null 2>&1 &", $output7);				
	        return true;
	    }

	
   public function Viewinvoice($invoiceNo = null){
	 	
	  	$return = $this->Finance_model->Geteditinvoicedata(array('invoice_no'=>$invoiceNo));
	  		$ItemArray= $return['result'];
			  $chargesArray=array();
			  $slipNOCharges=array();
			  $viewInfo=array();
		foreach($ItemArray as $key=>$val)
		 {
			 if($key==0);
			 {
				array_push($viewInfo,$val); 
			 }

			if($val['portal_charge'] > 0)
					{
						$chargesArray['portal_charge']=$val['portal_charge'];
					}

			if($val['booking_id'] == '')
			 {
				$chargesArray['storage_charges']=$chargesArray['storage_charges']+$val['storage_charges']; 
				$chargesArray['pickup_charge']=$chargesArray['pickup_charge']+$val['pickup_charge'];
				$chargesArray['onhold_charges']=$chargesArray['onhold_charges']+$val['onhold_charges'];
			 }
			 else
			 {
				 			 
				$chargesArray['cancel_charge']=$chargesArray['cancel_charge']+$val['cancel_charge'];
				$chargesArray['handline_fees']=$chargesArray['handline_fees']+$val['handline_fees'];
				$chargesArray['return_charge']=$chargesArray['return_charge']+$val['return_charge'];
				$chargesArray['shipping_charge']=$chargesArray['shipping_charge']+$val['shipping_charge'];
				if($val['special_packing_seller']>0)
				{
					$chargesArray['special_packing']=$chargesArray['special_packing']+$val['special_packing_seller'];	
				}
				else
				{
					$chargesArray['special_packing']=$chargesArray['special_packing']+$val['special_packing_warehouse'];
				}
				array_push($slipNOCharges,$val);
			 }
			 
		 }

		 $data['totalValue']= $chargesArray;
		 $data['invoiceData']= $slipNOCharges;
		 $data['invoiceDatainfo']= $viewInfo;
                 
		$this->load->view('finance/viewinvoice', $data);
	 }

public function ViewinvoiceDynamic($invoiceNo = null){
	 	
	  	$return = $this->Finance_model->Geteditinvoicedynamicdata(array('invoice_no'=>$invoiceNo));
	  		$ItemArray= $return['result'];
			  $chargesArray=array();
			  $slipNOCharges=array();
		//	echo '<pre>'; print_r($ItemArray); die;
		$viewInfo=array();
		foreach($ItemArray as $key=>$val)
		 {
			 if($key==0);
			 {
				array_push($viewInfo,$val); 
			 }
	if($val['portal_charge'] > 0)
					{
						$chargesArray['portal_charge']=$val['portal_charge'];
					}

			 if($val['booking_id'] == '')
			 {
			
				$chargesArray['pickup_charge']=$chargesArray['pickup_charge']+$val['pickup_charge'];
				$chargesArray['storage_charge']= $chargesArray['storage_charge']+$val['storage_charge'];
				$chargesArray['onhold_charges']=$chargesArray['onhold_charges']+$val['onhold_charges'];
				$chargesArray['inventory_charge']=$chargesArray['inventory_charge']+$val['inventory_charge'];
				$chargesArray['sku_barcode_print']= ($chargesArray['sku_barcode_print'] + $val['sku_barcode_print']);		

				$chargesArray['cancel_charge']=($chargesArray['cancel_charge']+$val['cancel_charge']);
				$chargesArray['packing_charge']=$chargesArray['packing_charge']+$val['packing_charge'];
				$chargesArray['picking_charge']=$chargesArray['picking_charge']+$val['picking_charge'];
				$chargesArray['dispatch_charge']=$chargesArray['dispatch_charge']+$val['dispatch_charge'];
				$chargesArray['outbound_charge']=$chargesArray['outbound_charge']+$val['outbound_charge'];
				$chargesArray['box_charge']= $chargesArray['box_charge']+$val['box_charge'];
				$chargesArray['inbound_charge']=$chargesArray['inbound_charge']+$val['inbound_charge'];
				$chargesArray['return_charge']=$chargesArray['return_charge']+$val['return_charge'];
				$chargesArray['shipping_charge']=$chargesArray['shipping_charge']+$val['shipping_charge'];
				$chargesArray['pallet_charge']=$chargesArray['pallet_charge']+$val['pallet_charge'];

			 }
			 else
			 {

			 	
				$chargesArray['cancel_charge']=($chargesArray['cancel_charge']+$val['cancel_charge']);
				$chargesArray['packing_charge']=$chargesArray['packing_charge']+$val['packing_charge'];
				$chargesArray['picking_charge']=$chargesArray['picking_charge']+$val['picking_charge'];
				$chargesArray['dispatch_charge']=$chargesArray['dispatch_charge']+$val['dispatch_charge'];
				$chargesArray['outbound_charge']=$chargesArray['outbound_charge']+$val['outbound_charge'];
				$chargesArray['box_charge']=$chargesArray['box_charge']+$val['box_charge'];
				$chargesArray['portal_charge'] = $chargesArray['portal_charge'];
				$chargesArray['inbound_charge']=$chargesArray['inbound_charge']+$val['inbound_charge'];
				$chargesArray['return_charge']=$chargesArray['return_charge']+$val['return_charge'];
				$chargesArray['shipping_charge']=$chargesArray['shipping_charge']+$val['shipping_charge'];
				$chargesArray['pallet_charge']=$chargesArray['pallet_charge']+$val['pallet_charge'];
					if($val['special_packing_seller']>0)
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$val['special_packing_seller'];	
					}
					else
					{
						$chargesArray['special_packing']=$chargesArray['special_packing']+$val['special_packing_warehouse'];
					}
					array_push($slipNOCharges,$val);
			 }
			 
		 }
		 //echo "<br/><pre>"; print_r($ItemArray[0]['portal_charge']);
			// die;

		 $data['totalValue']= $chargesArray;
		 $data['invoiceData']= $slipNOCharges;
		 $data['invoiceDatainfo']= $viewInfo;
                 
		$this->load->view('finance/viewinvoice_dynamic', $data);
	 }



	 public function getaddviewfinancecat($id=null){
	 
      $view['editid']=$id;
	  $view['editdata']=$this->Finance_model->Getcateditviewdata($id);
      $this->load->view('finance/addcat',$view);
	 } 
	  public function getallsellerchargesset($id=null){
	 
     // $view['editid']=$id;
	 // $view['editdata']=$this->Finance_model->Getcateditviewdata($id);
      $this->load->view('finance/setsellercharges');
	 } 
	 
	  public function getallfixrateCharges($id=null){
	 
     // $view['editid']=$id;
	 // $view['editdata']=$this->Finance_model->Getcateditviewdata($id);
      $this->load->view('finance/viewfixrateCharges');
	 } 
	 
	 
	  public function addfinCat()
	 {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('type', 'type', 'trim|required');
		
		 if ($this->form_validation->run()== FALSE)
		 {
			  $this->getaddviewfinancecat();
		 }
		 else
		 {
			 
			 $editid=$this->input->post('editid');
			 $name=$this->input->post('name');
			  $type=$this->input->post('type');
			  $description=$this->input->post('description');
			
			 $data=array('name'=>$name,'type'=>$type,'description'=>$description);
			// print_r($data); die;
			 $res=$this->Finance_model->datainsertCat($data,$editid); 
			 if($res==1)
			 {
				 $this->session->set_flashdata('succmsg','has been added successfully');
		         redirect(base_url().'viewfinancecategory');
			 }
			 else if($res==2)
			 {
				 $this->session->set_flashdata('succmsg','has been updated successfully');
		         redirect(base_url().'viewfinancecategory');
			 }
			 else
			 {
				 $this->session->set_flashdata('errormess','try again');
		         redirect(base_url().'viewfinancecategory');
			 }
			 
			 
		 }
	 }
	 
	  public function Activein($id=null,$stats=null)
	 {
		 if($id && $stats)
		 {
		 $data=array('status'=>$stats);
		  $res=$this->Finance_model->datainsertCat($data,$id);
		  if($stats=='Y')
			   $this->session->set_flashdata('succmsg','has been Active successfully');
			   else
			    $this->session->set_flashdata('succmsg','has been Inactive successfully');
		      // redirect(base_url().'viewfinancecategory');
		 
		 }
		 redirect(base_url().'viewfinancecategory');
	 }
	 public function getremovecategory($id=null)
	 {
		  $res=$this->Finance_model->getdeleteupdate($id);
		  if($res==TRUE)
		  {
			   $this->session->set_flashdata('succmsg','has been deleted successfully');
		       redirect(base_url().'viewfinancecategory');
		  }
	 }
	 public function GetallsellerStorageinvocielist()
	 {
		 $_POST = json_decode(file_get_contents('php://input'), true);
		 $page_no= $_POST['page_no']; 
		 $seller_id= $_POST['seller_id'];
		 $fromdate= $_POST['fromdate'];
		 $todate= $_POST['todate']; 
		$otherfilter=array('fromdate'=>$fromdate,'todate'=>$todate);
		 $QueryData=$this->Finance_model->GetallskuandStorageDataQuery($page_no,$seller_id,$otherfilter);
		 $returnArray=$QueryData['result'];
		// $typesArray=array();
		 $ii=0;
		// $jj=0;
		//echo'<pre>';
		 foreach($QueryData['result'] as $rdata)
		 {
			 $returnArray[$ii]['seller_name']=getallsellerdatabyID($rdata['seller_id'],'name');
			 $typedataArray=$this->Finance_model->GetallStorageTypesData($rdata['seller_id'],$rdata['entrydate']);
			 $typesArray=$typedataArray;
			// print_r($typesArray);
			  //$jj=0;
			 foreach($typedataArray as $jj=>$tdata)
			 {
			 $typesArray[$jj]['storagetypename']=Getallstoragetablefield($tdata['storage_id'],'storage_type');
			// $jj++;
			 }
			 
			 $returnArray[$ii]['storageArray']=$typesArray;
			 $returnArray[$ii]['storage_type']=Getallstoragetablefield($rdata['storage_id'],'storage_type');
			 $ii++;
		 }
		 $dataArray['result']=$returnArray;
         $dataArray['count']=$QueryData['count'];
		 echo json_encode($dataArray);
	 }
	 
	  public function GetallUsersPickupChargesData()
	 {
		 $_POST = json_decode(file_get_contents('php://input'), true);
		 $page_no= $_POST['page_no']; 
		 $seller_id= $_POST['seller_id'];
		 $fromdate= $_POST['fromdate'];
		 $todate= $_POST['todate']; 
		$otherfilter=array('fromdate'=>$fromdate,'todate'=>$todate);
		 $QueryData=$this->Finance_model->GetallpickupchargesqueryData($page_no,$seller_id,$otherfilter);
		 $returnArray=$QueryData['result'];
		// $typesArray=array();
		 $ii=0;
		// $jj=0;
		 foreach($QueryData['result'] as $rdata)
		 {
			 $returnArray[$ii]['seller_name']=getallsellerdatabyID($rdata['seller_id'],'name');
			/* $typedataArray=$this->Finance_model->GetallStorageTypesData($rdata['seller_id'],$rdata['entrydate']);
			 $typesArray=$typedataArray;
			  $jj=0;
			 foreach($typedataArray as $tdata)
			 {
			 $typesArray[$jj]['storagetypename']=Getallstoragetablefield($tdata['storage_id'],'storage_type');
			 $jj++;
			 }
			 $returnArray[$ii]['storageArray']=$typesArray;*/
			 $returnArray[$ii]['storage_type']=Getallstoragetablefield($rdata['storage_id'],'storage_type');
			 $ii++;
		 }
		 $dataArray['result']=$returnArray;
         $dataArray['count']=$result['count'];
		 echo json_encode($dataArray);
	 }
	 
	 
	 public function GetallinvocieShowData()
	{
		 $_POST = json_decode(file_get_contents('php://input'), true);
		  $page_no= $_POST['page_no']; 
		  $monthid= $_POST['monthid']; //1011
		  $seller_id= $_POST['seller_id']; //42
                  $years= $_POST['years']; //42
		  
		  $totalDays = cal_days_in_month(CAL_GREGORIAN, $monthid, $years);
		  $monthdaysArray=array();
		//  $totalcharge=0;
		$totalvat="";
		$totalamtshow="";
		$checkVatDate="07";
		//if($checkVatDate>$monthid)
		//$VatRealtime=5;
		//else
		$VatRealtime=15;
		//echo $VatRealtime; die;
                $sellerArr=GetSinglesellerdata($seller_id);
                $sellerArr['city']=getdestinationfieldshow($sellerArr['city'],'city');
                $totalshow_pickupcharge=0;
                $totalshow_inbound_charge=0;
                $totalshow_inventory_charge=0;
                 $totalshow_outchargebound=0;
                 $totalshow_storagerate=0;
                 $totalshow_packaging_charge=0;
                 $totalshow_special_packing_charge=0;
                 $totalshow_picking_charge=0;
                 $totalshow_rentcharge=0;
                 $totalshow_barcode_print=0;
                 $totalshow_returncharge=0;
                
		  for ($x = 1; $x <= $totalDays; $x++)
		  {
			 $time=mktime(12, 0, 0, $monthid, $x, $years);
			if(date('m', $time)==$monthid)
				$monthdaysArray[$x]['date']=date('Y-m-d', $time);
				$monthdaysArray[$x]['pickupcharge']=GetallpickupChagresinvoice($seller_id,date('Y-m-d', $time),'pickupcharge');
				$monthdaysArray[$x]['inbound_charge']=GetallpickupChagresinvoice($seller_id,date('Y-m-d', $time),'inbound_charge');
				$monthdaysArray[$x]['inventory_charge']=GetallpickupChagresinvoice($seller_id,date('Y-m-d', $time),'inventory_charge');
				$monthdaysArray[$x]['outchargebound']=GetalloutboundChargeinvoice($seller_id,date('Y-m-d', $time),'outcharge');
				$monthdaysArray[$x]['storagerate']=GetalldailyrentelChargesinvocie($seller_id,date('Y-m-d', $time),'storagerate');
				$monthdaysArray[$x]['packaging_charge']=GetallpackingChargeinvoices($seller_id,date('Y-m-d', $time),'packaging_charge');
				$monthdaysArray[$x]['special_packing_charge']=GetallpackingChargeinvoices($seller_id,date('Y-m-d', $time),'special_packing_charge');
                                
				$monthdaysArray[$x]['picking_charge']=GetallpackingChargeinvoices($seller_id,date('Y-m-d', $time),'picking_charge');
				$monthdaysArray[$x]['rentcharge']=GetallPortelRentelChargesInvocie($seller_id,date('Y-m-d', $time),'rentcharge');
				$monthdaysArray[$x]['barcode_print']=Getbarcode_printInvoiceData($seller_id,date('Y-m-d', $time),'rate');
				$monthdaysArray[$x]['returncharge']=GetalloutboundChargeinvoice($seller_id,date('Y-m-d', $time),'returncharge');
				$totalcharges1=$monthdaysArray[$x]['pickupcharge']+$monthdaysArray[$x]['inbound_charge']+$monthdaysArray[$x]['outchargebound']+$monthdaysArray[$x]['storagerate']+$monthdaysArray[$x]['packaging_charge']+$monthdaysArray[$x]['picking_charge']+$monthdaysArray[$x]['rentcharge']+$monthdaysArray[$x]['inventory_charge']+$monthdaysArray[$x]['barcode_print']+$monthdaysArray[$x]['returncharge'];
				//if($checkVatDate<)
				$monthdaysArray[$x]['taxshow']=$VatRealtime/100*$totalcharges1;
				$totalcharges=$monthdaysArray[$x]['taxshow']+$totalcharges1;
				//$totalcharges_down=$monthdaysArray[$x]['taxshow']+$totalcharges1;
				$totalvat+=$VatRealtime/100*$totalcharges1;
				$totalamtshow+=$totalcharges;
				$totalamtshow_down+=$totalcharges1;
				$totaltaxchargesshow=$totalamtshow_down+$totalvat;
				//$monthdaysArray[$x]['totaltaxchargesshow']=$totalamtshow;
				$monthdaysArray[$x]['totalrowcharge']=$totalcharges;
                               // echo "ssss".$monthdaysArray[$x]['pickupcharge'];
                                $totalshow_pickupcharge+=$monthdaysArray[$x]['pickupcharge'];
                                $totalshow_inbound_charge+=$monthdaysArray[$x]['inbound_charge'];
                                $totalshow_inventory_charge+=$monthdaysArray[$x]['inventory_charge'];
                                $totalshow_outchargebound+=$monthdaysArray[$x]['outchargebound'];
                                $totalshow_storagerate+=$monthdaysArray[$x]['storagerate'];
                                $totalshow_packaging_charge+=$monthdaysArray[$x]['packaging_charge'];
                                $totalshow_special_packing_charge+=$monthdaysArray[$x]['special_packing_charge'];
                                $totalshow_picking_charge+=$monthdaysArray[$x]['picking_charge'];
                                $totalshow_rentcharge+=$monthdaysArray[$x]['rentcharge'];
                                $totalshow_barcode_print+=$monthdaysArray[$x]['barcode_print'];
                                $totalshow_returncharge+=$monthdaysArray[$x]['returncharge'];
                                
		  }
		// echo $totalamtshow; 
                  
                  
                  $totalArray=array(
                      'totalshow_pickupcharge'=>$totalshow_pickupcharge,
                      'totalshow_inbound_charge'=>$totalshow_inbound_charge,
                      'totalshow_inventory_charge'=>$totalshow_inventory_charge,
                      'totalshow_outchargebound'=>$totalshow_outchargebound,
                      'totalshow_storagerate'=>$totalshow_storagerate,
                      'totalshow_packaging_charge'=>$totalshow_packaging_charge,
                      'totalshow_special_packing_charge'=>$totalshow_special_packing_charge,
                      //'totalshow_pickupcharge'=>$totalshow_pickupcharge,
                      'totalshow_picking_charge'=>$totalshow_picking_charge,
                      'totalshow_rentcharge'=>$totalshow_rentcharge,
                      'totalshow_returncharge'=>$totalshow_barcode_print,
                      'totalshow_barcode_print'=>$totalshow_returncharge
                     );
                 // print_r($totalArray);
		
		 
		 echo json_encode(array('result'=>$monthdaysArray,'totalvat'=>$totalvat,'totalamtshow'=>$totalamtshow_down,'totalcharges'=>$totaltaxchargesshow,'VatRealtime'=>$VatRealtime,'sellerArr'=>$sellerArr,'summary_total'=>$totalArray));
	}
	

public function GettransportReportShowData()
	{
		 $_POST = json_decode(file_get_contents('php://input'), true);
		 //print_r($_POST);
		  $page_no= $_POST['page_no']; 
		  $slip_no= $_POST['slip_no']; 
		  $from= $_POST['from']; 
		  $to=$_POST['to'];
		  $seller=$_POST['seller_id'];
			if(empty($from)){
				$date1=date('Y-m-d');
			}else{
				$date1=$from;
			}
			$month=explode('-',$date1);
			$monthid=$month[1];
			
			$totalvat="";
			$totalamtshow="";
			$checkVatDate="07";
			if($checkVatDate>$monthid)
			$VatRealtime=5;
			else
			$VatRealtime=15;
	
	              $VatRealtime=15;
		 	$transactionResult=$this->Finance_model->transaction_report($page_no,$slip_no,$seller,$to,$from);
			$ItemArray=$transactionResult['result'];
			
		$kk=0;
		$jj = 0;
			 
		$tolalShip = $transactionResult['count'];    
        $downlaoadData = 2000; 
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k); 
            }
            $j = $k;
        }
		
	
	 	foreach($transactionResult['result'] as $rdata) 
		{
			$date=explode(' ',$rdata['entrydate']);
		//$ItemArray[$kk]['pickupcharge']=GetallpickupChagres($rdata['seller_id'],$date[0] ,$rdata['slip_no'],'pickupcharge');
		//	$ItemArray[$kk]['inbound_charge']=GetallinboundChagres($rdata['seller_id'],$date[0],$rdata['slip_no'],'inbound_charge');
		//$ItemArray[$kk]['inventory_charge']=GetallinventoryChagres($rdata['seller_id'],$date[0],$rdata['slip_no'],'inventory_charge');
		$ItemArray[$kk]['outcharge']=GetalloutboundtransportChagres($rdata['seller_id'],$date[0],$rdata['slip_no'],'outcharge');
		//$ItemArray[$kk]['storagerate']=Getalldailyrenteltransportreport($rdata['seller_id'],$date[0],$rdata['slip_no'],'storagerate');
		$ItemArray[$kk]['packaging_charge']=GetallpackingChargetransport($rdata['seller_id'],$date[0],$rdata['slip_no'],'packaging_charge'); 
		$ItemArray[$kk]['special_packing_charge']=GetallpackingChargetransport($rdata['seller_id'],$date[0],$rdata['slip_no'],'special_packing_charge'); 
		$ItemArray[$kk]['picking_charge']=GetallpackingChargetransport($rdata['seller_id'],$date[0],$rdata['slip_no'],'picking_charge');

		$totalcharges1=$ItemArray[$kk]['outcharge']+$ItemArray[$kk]['special_packing_charge']+$ItemArray[$kk]['packaging_charge']+$ItemArray[$kk]['picking_charge'];
		$ItemArray[$kk]['taxshow']=$VatRealtime/100*$totalcharges1;
 		$totalcharges=$ItemArray[$kk]['taxshow']+$totalcharges1;
		$totalvat+=$VatRealtime/100*$totalcharges1;
		$totalamtshow_down+=$totalcharges1;
		$totaltaxchargesshow=$totalamtshow_down+$totalvat;
		$ItemArray[$kk]['totalrowcharge']=$totalcharges;
				
		 $kk++;
		}
		//echo '<pre>';
		//print_r($palletArray);
		$returnArray['dropexport'] = $expoertdropArr; 
		$returnArray['result']=$ItemArray;
		$returnArray['count']=$transactionResult['count'];
		// echo $totalamtshow; 
		
		 
		 echo json_encode($returnArray);
}

	 function GetTransactionRepotydownload()
    {
         ini_set('memory_limit','5000000M');
		 ini_set ( 'max_execution_time', 1200); 
         $_POST = json_decode(file_get_contents('php://input'), true); 
			
			 $shipmentsexcel=$this->Finance_model->GetTransactionRepotydownloadQry($_POST);  
			// die;
			 $shiparray1=$shipmentsexcel['result'];
		//echo json_encode($shipments); die;
		$ii=0;
		$jj = 0;
		
      

		$dataArray=$shiparray1 ;  
			
			$DatafileArray=array();
			$i=0;
	 foreach($dataArray as $data)
    {
      
	    $date=explode(' ',$data['entrydate']);
		//$ItemArray[$kk]['pickupcharge']=GetallpickupChagres($rdata['seller_id'],$date[0] ,$rdata['slip_no'],'pickupcharge');
	//	$ItemArray[$kk]['inbound_charge']=GetallinboundChagres($rdata['seller_id'],$date[0],$rdata['slip_no'],'inbound_charge');
		//$ItemArray[$kk]['inventory_charge']=GetallinventoryChagres($rdata['seller_id'],$date[0],$rdata['slip_no'],'inventory_charge');
		$outcharge=GetalloutboundtransportChagres($rdata['seller_id'],$date[0],$data['slip_no'],'outcharge');
		//$ItemArray[$kk]['storagerate']=Getalldailyrenteltransportreport($rdata['seller_id'],$date[0],$rdata['slip_no'],'storagerate');
		$packaging_charge=GetallpackingChargetransport($data['seller_id'],$date[0],$data['slip_no'],'packaging_charge'); 
		$special_packing_charge=GetallpackingChargetransport($data['seller_id'],$date[0],$data['slip_no'],'special_packing_charge'); 
		$picking_charge=GetallpackingChargetransport($data['seller_id'],$date[0],$data['slip_no'],'picking_charge');

		$totalcharges1=$outcharge+$special_packing_charge+$packaging_charge+$picking_charge;
		$taxshow=$VatRealtime/100*$totalcharges1;
 		$totalcharges=$taxshow+$totalcharges1;
		$totalvat+=$VatRealtime/100*$totalcharges1;
		$totalamtshow_down+=$totalcharges1;
		$totaltaxchargesshow=$totalamtshow_down+$totalvat;
		$totalrowcharge=$totalcharges;
		
		
        $DatafileArray[$i]['slip_no']=$data['slip_no'];
		 $DatafileArray[$i]['name']=$data['name'];
		$DatafileArray[$i]['outcharge']=$outcharge;
		$DatafileArray[$i]['packaging_charge']=$packaging_charge;
		$DatafileArray[$i]['picking_charge']=$picking_charge;
		$DatafileArray[$i]['special_packing_charge']=$special_packing_charge;
		$DatafileArray[$i]['taxshow']=$taxshow;
		$DatafileArray[$i]['totalrowcharge']=$totalrowcharge;
		$DatafileArray[$i]['entrydate']=$data['entrydate'];
		
			
      
     $i++ ; 
    }
	
	
    array_unshift($DatafileArray,'');
    $this->load->library("excel");
    $doc = new PHPExcel();

	  $doc->getActiveSheet()->fromArray($DatafileArray);
	  $from = "A1"; // or any value
	  $to = "K1"; // or any value
	  $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );
	  $doc->setActiveSheetIndex(0)
				->setCellValue('A1', 'AWB No.')
				->setCellValue('B1', 'Seller')
				->setCellValue('C1', 'Outbound Charge')
				->setCellValue('D1', 'Packaging')
				->setCellValue('E1', 'Picking')
				->setCellValue('F1', 'Special Packing')
				->setCellValue('G1', 'VAT')
				->setCellValue('H1', 'Total Amount')
				->setCellValue('I1', 'Date');
			
	$objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

			ob_start();
			$objWriter->save("php://output");
			$xlsData = ob_get_contents();
			ob_end_clean();

			$response =  array(
					'op' => 'ok',
					'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
				);

			die(json_encode($response));
  }
	
    public function categoryView(){

      $this->load->view('finance/viewcat');
	 } 
	 
	 public function setStorageRate(){
      $view['sellerData']=Getallsellerdata(); 
      $this->load->view('storage/setusertype',$view);
	 } 
	 public function GetstorageTypesInvoicesView(){
		 
		// echo "ssssss";  die;
      //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
      $this->load->view('finance/storageinvoice');
	  
	 } 
	 public function GetallpickupChrgesinvoices(){
		 
		// echo "ssssss";  die;
      //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
      $this->load->view('finance/pickupinvoice');
	  
	 } 
	 public function GetallfinanceInvocieView(){
		 
		// echo "ssssss";  die;
      //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
      $this->load->view('finance/orderinvoicesview');
	  
	 } 
	 public function GetallNewfinanceInvocieView(){
		 
		// echo "ssssss";  die;
      //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
      $this->load->view('finance/newinvoicesview');
	  
	 } 


	 public function discountUpdate()
    {
            
            $dataArray=$dataArray= $this->input->post();
		//	print_r($dataArray);
            $invoice_no = $dataArray['invoice_no'];
			$discount = $dataArray['discount'];
		$CURRENT_DATE=date("Y-m-d H:i:s");
			 
		$updateinvoiceAarrayW=array('invoice_no'=>$dataArray['invoice_no'],'cust_id'=>$dataArray['cust_id'],'discount'=>$discount);
		$res_data=$this->Finance_model->addInvoiceUpdateDiscount($updateinvoiceAarrayW);
            
		$this->session->set_flashdata('msg', 'Discount updated!'); 

			
		redirect(base_url('invoices_dynamic'));
    }

	public function discountUpdatefix()
    {
            
            $dataArray=$dataArray= $this->input->post();
			//print_r($dataArray);
            $invoice_no = $dataArray['invoice_no'];
			$discount = $dataArray['discount'];
		$CURRENT_DATE=date("Y-m-d H:i:s");
			 
		$updateinvoiceAarrayW=array('invoice_no'=>$dataArray['invoice_no'],'cust_id'=>$dataArray['cust_id'],'discount'=>$discount);
		$res_data=$this->Finance_model->addInvoiceUpdateDiscountfix($updateinvoiceAarrayW);
            
		$this->session->set_flashdata('msg', 'Discount updated!'); 

			
		redirect(base_url('newinvoicesView'));
    }



	public function payfixinvoice($invoice_no)
    {
            
           //echo $invoice_no;  exit;
			//print_r($dataArray);
           
		$CURRENT_DATE=date("Y-m-d H:i:s");
			 
		$updateinvoiceAarrayW=array('invoice_no'=>$invoice_no,'pay_status'=>'Y','pay_date'=>date('Y-m-d'),'pay_update_by'=>$this->session->userdata('user_details')['user_id']);
		
		//print_r($updateinvoiceAarrayW); exit;
		$res_data=$this->Finance_model->addInvoiceUpdateDiscountfix($updateinvoiceAarrayW);
         
		$this->session->set_flashdata('msg', $invoice_no.' Invoice Paid!'); 

			
		redirect(base_url('newinvoicesView'));
    }

	 public function GetallNewfinanceInvocieDynamicView(){
		 
		// echo "ssssss";  die;
      //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
      $this->load->view('finance/invoices_dynamic');
	  
	 } 
      
	   public function transaction_report(){
		 
		// echo "ssssss";  die;
      //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
      $this->load->view('finance/transaction_report');
	  
	 } 
	 
	    
	public function getallsellerdata()
	{
		   $_POST = json_decode(file_get_contents('php://input'), true);
		  // $returndata = GetallsellerdataInv($_POST['type']); 
		   $returndata = Getallsellerdata($_POST['type']); 
		  
		   echo json_encode($returndata);
	}

public function getallinvoicedata()
	{
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $returndata = Getallinvoicedata(); 
		  
		   echo json_encode($returndata);
	}

	public function getallsellerfixtypedata()
	{
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $returndata=$this->Finance_model->getallfixtypeData(); 

		   echo json_encode($returndata);
	}

	public function getallsellerdynamictypedata()
	{
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $returndata=$this->Finance_model->getalldynamictypeData(); 

		   echo json_encode($returndata);
	}

	public function GetallfinanceCategory()
	{
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $returndata=$this->Finance_model->getalluserchargesData(); 
		   echo json_encode($returndata);
	}

	public function GetinvoiceReportShowData()
	{
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $returndata=$this->Finance_model->invoice_report($_POST); 
		   foreach ($returndata as $key => $custname) {
		   		$returndata[$key]['customerName'] =  getallsellerdatabyID($custname['cust_id'],'name');
		   		$returndata[$key]['username'] =  getUserNameById($custname['super_id'],'username');
		   		$returndata[$key]['payby'] =  getUserNameById($custname['pay_update_by'],'username');
		   	
		   		 $returndata[$key]['month'] =   date("F", strtotime($custname['invoice_date']));

		   }

		   echo json_encode($returndata);
	}
	public function GetdynamicinvoiceReportShowData()
	{
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $returndata=$this->Finance_model->dynamic_invoice_report($_POST); 
		   foreach ($returndata as $key => $custname) {
		   		//echo "<pre>"; print_r($custname);
		   		$returndata[$key]['customerName'] =  getallsellerdatabyID($custname['cust_id'],'name');
		   		$returndata[$key]['username'] =  getUserNameById($custname['super_id'],'username');
		   		$returndata[$key]['payby'] =  getUserNameById($custname['pay_updated_by'],'username');
		   		 $returndata[$key]['month'] =   date("F", strtotime($custname['invoice_date']));
		   }
		   echo json_encode($returndata);
	}
	
	
	public function Getallusercharges()
	{
		
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $seller_id=$_POST['seller_id'];
		   $returndata=$this->Finance_model->getalluserchargesData_new();
		   $typearray=$returndata;
		   $ii=0;
		   foreach($typearray as $rdata)
		   {
			  $typearray[$ii]['setpiece']=getalluserfinanceRates($seller_id,$rdata['id'],'setpiece');
			   $typearray[$ii]['rates']=getalluserfinanceRates($seller_id,$rdata['id'],'rate');
			  $typearray[$ii]['rateid']=getalluserfinanceRates($seller_id,$rdata['id'],'id');
			  $typearray[$ii]['seller_id']=$seller_id;
			   $ii++;
		   }
		   echo json_encode($typearray);
	}

	public function Getallfixratusercharges()
	{
		
		   $_POST = json_decode(file_get_contents('php://input'), true);
		   $seller_id=$_POST['seller_id'];		  
		   $returndata=$this->Finance_model->getallfixratuserchargesData();
		    $typearray=$returndata;
		   $ii=0;
		   foreach($typearray as $rdata)
		   {
			  $typearray[$ii]['setpiece']=getalluserfinanceRates($seller_id,$rdata['id'],'setpiece');
			   $typearray[$ii]['rates']=getalluserfinanceRates($seller_id,$rdata['id'],'rate');
			  $typearray[$ii]['rateid']=getalluserfinanceRates($seller_id,$rdata['id'],'id');
			  $typearray[$ii]['seller_id']=$seller_id;
			   $ii++;
		   }
		   echo json_encode($typearray);
		  
	}
	


	public function ShowEditpay(){
		$_POST = json_decode(file_get_contents('php://input'), true);
		 $id=$_POST['id'];
		$returndata=$this->Finance_model->Getpaydynamic_edit($id); 
		 echo json_encode($returndata);
	   
   }
   public function ShowEditpayfix(){
	$_POST = json_decode(file_get_contents('php://input'), true);
	 $id=$_POST['id'];
	$returndata=$this->Finance_model->Getpayfix_edit($id); 
	 echo json_encode($returndata);
   
}
	
	
    public function add_storage($id=null){

      $view['editid']=$id;
	 // $view['editdata']=$this->Finance_model->editviewquery($id); 
      $this->load->view('storage/add_storage',$view);
	 } 
	 public function geteditviewdata(){
		  $_POST = json_decode(file_get_contents('php://input'), true);
		   $id=$_POST['id'];
		  $returndata=$this->Finance_model->editviewquery($id); 
		   echo json_encode($returndata);
		 
	 }
	 public function GetAllUsersSetFinanceCharges(){
		  $_POST = json_decode(file_get_contents('php://input'), true);
		   $dataArray=$_POST;
		  
		 // print_r($dataArray);
		   $result=$this->Finance_model->GetalluserRatesUpdatesQuery($dataArray);
		  //$returndata=$this->Finance_model->editviewquery($id); 
		   echo json_encode($result);
		 
	 }
	 
	 
	
    

  

}
?>