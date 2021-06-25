<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LastmileInvoice extends MY_Controller {

	function __construct() {
		parent::__construct(); 
		if(menuIdExitsInPrivilageArray(21)=='N')
		{
			redirect(base_url().'notfound'); die;
			
		}
       
		$this->load->model('LastMile_model');
		$this->load->model('Seller_model');
		$this->load->model('Shipment_model');
		
		$this->load->helper('utility');  
		// $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}
	
	public function GetcustomerShowdata()
	{
		$return=GetcustomerDropdata();
		 echo json_encode($return);
	}

	public function GetstaffDropData()
{
	$return=getstaff_multycreated();
	echo json_encode($return);
}
	public function showPayableInvoiceData()
    {
    $_POST = json_decode(file_get_contents('php://input'), true);
      $returnArray=$this->LastMile_model->getviewPayableInvoice($_POST); 
    $maniarray=$returnArray['result'];
	foreach($maniarray as $key=>$val)
		{
			$maniarray[$key]['cod_paid_by1']=Get_user_name($val['cod_paid_by'],'user');
			$maniarray[$key]['invoice_created_by']=Get_user_name($val['invoice_created_by'],'user');
			$maniarray[$key]['receivable_paid_by']=Get_user_name($val['receivable_paid_by'],'user');
			$maniarray[$key]['cod_paid_by']=Get_user_name($val['cod_paid_by'],'user');
			
			
			$invocieCountArray=invoiceCountnew($val['invoice_no']);
			$InvocieDetailsArray=invoiceDetailnew($val['invoice_no']);
			
			$maniarray[$key]['invoiceCount']=$invocieCountArray['total_numCount'];
			$maniarray[$key]['monthly_invoice_no']=$monthly_invoice_no['total_numCount'];
			$maniarray[$key]['cod_charge_sum']=$InvocieDetailsArray['cod_charge_sum'];
			$maniarray[$key]['return_charge_sum']=$InvocieDetailsArray['return_charge_sum'];
			$maniarray[$key]['service_charge_sum']=$InvocieDetailsArray['service_charge_sum'];
			$maniarray[$key]['vat_sum']=$InvocieDetailsArray['vat_sum'];
			$maniarray[$key]['cod_amount_sum']=$InvocieDetailsArray['cod_amount_sum'];
			
		
		}
        $dataArray['result']=$maniarray;   
        $dataArray['count']=$returnArray['count']; 
    echo json_encode($dataArray); 
    }

	public function viewLmInvoice() {

		$sellers = $this->Seller_model->find2();
        

        $bulk = array(
            
            'sellers' => $sellers,
           
        );
        
		$this->load->view('lminvoice/viewLmInvoice',$bulk);
		
	}
	public function createInvoice() {

		$sellers = $this->Seller_model->find2();
        

        $bulk = array(
            
            'sellers' => $sellers,
           
        );
        
		$this->load->view('lminvoice/create_invoice',$bulk);
		
	}



	public function PaymentConfirmUpdaye()
{
	$dataArray= $this->input->post();
	


	       if (!empty($_FILES['pro_image']['name'])) {
           $config['upload_path'] = 'assets/invoice_copy/';
           $config['overwrite'] = TRUE;
           $config['allowed_types'] = 'jpg|jpeg|png|gif';
           $config['file_name'] =$d1='invoice'.mktime(date(h),date(i),date(s),date(m),date(d),date(y)); 

           $this->load->library('upload', $config);
           $this->upload->initialize($config);

           if ($this->upload->do_upload('pro_image')) {
               $uploadData = $this->upload->data();
               $imgpath = $config['upload_path'] . '' . $uploadData['file_name'];
           } 
       } 
	
		
			//if(!empty($imgpath))
			{
				$CURRENT_DATE=date("Y-m-d H:i:s");
				$updateinvoiceAarray=array('cod_pay_status'=>'Y','cod_paid_by'=>$this->session->userdata('user_details')['user_id'],'cod_paid_date'=>$CURRENT_DATE,'pay_voucher'=>$imgpath);
				$updateinvoiceAarrayW=array('invoice_no'=>$dataArray['invoice_no'],'cust_id'=>$dataArray['cust_id']);
				
				$return1=$this->LastMile_model->GetupdateFinalInvocie($updateinvoiceAarray,$updateinvoiceAarrayW);	
				
	
			}
			$this->session->set_flashdata('msg', 'Successfully updated!');

			
			redirect(base_url('viewLmInvoice'));
                 
			
}


public function payableInvoice_update()
    {
            $_POST = json_decode(file_get_contents('php://input'), true);
            $dataArray=$_POST;
            $invoice_no = $dataArray['invoice_no'];
		$CURRENT_DATE=date("Y-m-d H:i:s");
			 $updateinvoiceAarrayW=array('invoice_no'=>$dataArray['invoice_no'],'cust_id'=>$dataArray['cust_id']);
			$updateinvoiceAarray=array('receivable_pay_status'=>'Y','receivable_paid_by'=>$this->session->userdata('user_details')['user_id'],'receivable_paid_date'=>$CURRENT_DATE,'rec_voucher'=>$dataArray['rec_voucher']);
           $res_data=$this->LastMile_model->addInvoiceUpdate($updateinvoiceAarray,$updateinvoiceAarrayW);
            
            //===============================================//
        
         
         echo json_encode($res_data);
    }

	public function ShowEditpay()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $table_id=$_POST['id'];
        
        $returnArray=$this->LastMile_model->Getpay_edit($table_id);
    
         echo json_encode($returnArray);
    }
	public function codreceivablePrint($invoice_no=null)
	{
		$result=$this->LastMile_model->codreceivablePrintQry($invoice_no);
		$view['invoiceData']=$result;
	
		$this->load->view('lminvoice/bulkallinvoice',$view);
	}

	public function CreateInvoiceCalulation()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
		$show_awb_no=$_POST['slip_no'];
		$cust_id=$_POST['cust_id'];
		$invoiceNo=$this->session->userdata('user_details')['super_id'].date('Ymd').$cust_id;
		$date=date('Y-m-d');
		$invoiceCheck= $this->LastMile_model->checkInvoiceExist($show_awb_no);
		
		$vat=site_configTable('default_service_tax');
		
		$bank_fees=getallsellerdatabyID($cust_id,'bank_fees',$this->session->userdata('user_details')['super_id']);
		$areadyExit=array();
		foreach($invoiceCheck as $key1=>$val1)
		{

		   if(in_array($val1['awb_no'],$slipData))
		   {
			   array_push( $areadyExit,$val1['awb_no']);

		   }
		}

		$finalArray = array_values(array_diff($show_awb_no, $areadyExit));
		$shipmentdata= $this->Shipment_model->getawbdataquery($finalArray);
		$chargeData= $this->LastMile_model->calculateShipCharge($cust_id);
		$returnData= $this->LastMile_model->calculateReturn($cust_id);
		//print_r($chargeData); exit;
		foreach($returnData as $rdata)
		{
			if($rdata['name']=='Additional Return')
			$additionalReturn=$rdata['rate'];


			if($rdata['name']=='Return')
			{
			$return = $rdata['rate'];
			$setPiece=$rdata['setpiece'];
			}
		}
		
		
		foreach($shipmentdata as $key=>$val)
		{
			if($val['code']=='POD')
			{
				foreach($chargeData as $key1=>$val1)
				{
					$cityArray=json_decode($val1['city_id'],true);
					//echo $val['destination'];
				//echo '<br>'.	in_array($val['destination'],$cityArray); exit;
					//print_r($cityArray);
					if($val['frwd_company_id']==$val1['cc_id'] && in_array($val['destination'],$cityArray)==true)
					{
					 	 $keyCheck=$key1;
						//break;	
					}
				}
				
				//$keyCheck = array_search($val['cc_id'], array_column($chargeData, 'cc_id'));
				$flat_price=$chargeData[$keyCheck]['price'];
				$price=$chargeData[$keyCheck]['flat_price'];
				$max_weight=$chargeData[$keyCheck]['max_weight'];

				if($val['weight']>$max_weight)
				{
					$additionalWeight=$val['weight']-$max_weight;
				}
				else
				{
					$additionalWeight=0;
				}

				$shipCharge=$price+($flat_price*$additionalWeight);
				$return_charge = 0;
		 }else
			{
				$pieces=$val['pieces'];
				if($pieces>$setPiece)
				{

				$addPcs=$pieces-$setPiece;
				}
				else
				{
				$addPcs=0;
				}

				$return_charge = ($return + ($additionalReturn*$addPcs));
				$shipCharge=0;

			}
			if($val['mode']=='COD' && $val['code']=='POD')
			{
				$codAmount=$val['total_cod_amt'];
			}
			else
			{
				$codAmount=0;
			}
			$invoiceArray[]= array(
			
			'invoice_no' => $invoiceNo,
			'entry_date' => $date,
			'cust_id' => $cust_id,
			'receiver_name' => $val['reciever_name'],
			'origin' => $val['origin'],
			'destination' => $val['destination'],
			'awb_no' => $val['slip_no'],
			'refrence_no' => $val['booking_id'],
			'qty' => $val['pieces'],
			'weight' => $val['weight'],
			'mode' => $val['mode'],
			'bank_fees'=>$bank_fees,
			'cod_charge' => '0.00',
			'return_charge' => $return_charge,
			'service_charge' => $shipCharge,
			'cod_amount' => $codAmount,
			'vat' => $val['close_date'],
			'close_date' => $vat,
			'invoice_created_by' => $this->session->userdata('user_details')['user_id'],
			'invoice_created_date' => $date,
			'invoice_date' => $date,
			'super_id' =>  $this->session->userdata('user_details')['super_id']
			);
			$where_in[]=array('slip_no'=>$val['slip_no'],'pay_invoice_no'=>$invoiceNo);
	
		}
		
		if(!empty($invoiceArray))
		{
			
			$this->LastMile_model->updateShipmet($where_in); 
			$this->LastMile_model->addlmIncoice($invoiceArray);
		}
		echo json_encode($invoiceArray );
	}


	public function checkInvoice()
	{
		$_POST = json_decode(file_get_contents('php://input'), true);
		$show_awb_no=$_POST['slip_no'];
		$cust_id=$_POST['seller'];
		$SlipNos = preg_replace('/\s+/', ',', $show_awb_no);
        $slip_arr = explode(",", $SlipNos);
        $slipData = array_unique($slip_arr);
		// echo '<pre>';
		// print_r($slipData); exit;
         $data['traking_awb_no'] =$slipData;
        
		 $invoiceCheck= $this->LastMile_model->checkInvoiceExist($slipData);
		 
		 $areadyExit=array();
		 foreach($invoiceCheck as $key1=>$val1)
		 {

			if(in_array($val1['awb_no'],$slipData))
			{
				array_push( $areadyExit,$val1['awb_no']);

			}
		 }

		 $finalArray = array_values(array_diff($slipData, $areadyExit));
		 $shipmentdata= $this->Shipment_model->getawbdataquery($finalArray);

		 $belongToOther=array();
		 $Available=array();
		 $statusNotcorrect=array();
		 $destinationIssue=array();
		 $statusArray=array('POD','RTC');

		 foreach($shipmentdata as $key=>$val)
		 {
			if($val['cust_id']!=$cust_id)
		    {
				array_push( $belongToOther,$val['slip_no']);
			}
			elseif(!in_array($val['code'],$statusArray))
			{
				array_push( $statusNotcorrect,$val['slip_no']);	
			}
			elseif(empty($val['destination']))
			{
				array_push( $destinationIssue,$val['slip_no']);	
			}
			else
			{
				array_push( $Available,$val['slip_no']);	
			}
		 }

		 $finalArray['belongToOther']=$belongToOther;
		 $finalArray['statusNotcorrect']=$statusNotcorrect;
		 $finalArray['destinationIssue']=$destinationIssue;
		 $finalArray['Available']=$Available;
		 $finalArray['areadyExit']=$areadyExit;
		 
		

		echo json_encode($finalArray );
	}
	

}
?>