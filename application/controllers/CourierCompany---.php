<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class CourierCompany extends MY_Controller  { 


	function __construct() {
        // error_reporting(-1);
		// ini_set('display_errors', 1);
		parent::__construct(); 
		if(menuIdExitsInPrivilageArray(22)=='N')
		{
			//redirect(base_url().'notfound'); die;
			
		}  
		
		$this->load->model('Ccompany_model');
		$this->load->model('Shipment_model');
		$this->load->model('ItemInventory_model');
		$this->load->library('form_validation');

	}

	
	public function cCompany(){
		$this->load->view('courierCompany/view_company');               
	}
        
    public function forwardshipments(){
        $this->load->view("ShipmentM/forward_shipments");
    }
    
  

    public function forwardedshipments() {
        $this->load->view("ShipmentM/forwarded_shipments");
    }
    
    public function GetCompanylistDrop()
    {   
         $return=$this->Ccompany_model->GetCompanylistDropQry();
         echo json_encode($return);
    }
        
    public function GetshowcompanyList()
        {
            $postArr = json_decode(file_get_contents('php://input'), true);
            $return = $this->Ccompany_model->all();
            echo json_encode($return);
            
        }
 
    public function GetshowcompanySeller()
    {
        $postArr = json_decode(file_get_contents('php://input'), true);
        $return = $this->Ccompany_model->all_ccSeller($postArr['id']);
        echo json_encode($return);
        
    }
        
    public function forwardedfilter() {
        //print("heelo"); 
        //exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        $page_no = $_POST['page_no'];
         $awb = $_POST['s_type_val'];
        $warehouse = $_POST['warehouse'];
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $forwarded_type = $_POST['forwarded_type'];
        $mode = isset($_POST['mode']) ? $_POST['mode'] : '';
        $sku = isset($_POST['sku_val']) ? $_POST['sku_val'] : '';
        $booking_id = isset($_POST['booking_id']) ? $_POST['booking_id'] : '';

        $shipments = $this->Ccompany_model->forwardshfilter($awb, $warehouse, $origin, $destination, $forwarded_type, $mode, $sku, $booking_id,$page_no);

        //echo '<pre>';
        //$shiparrayexcel = $shipmentsexcel['result'];
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $tolalShip = $shipments['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        foreach ($shipments['result'] as $rdata) {

           
            if ($rdata['order_type'] == '') {
                $itemID = getallitemskubyid($rdata['sku']);
                $itemtypes = getalldataitemtables($itemID, 'type');
                $shiparray[$ii]['order_type'] = $itemtypes;

                //$shiparray[$ii]['order_type']="";
            } else
                $shiparray[$ii]['order_type'] = $rdata['order_type'];
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            //$shiparray[$ii]['expire_details'] = $expire_data;
            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');

            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
            $shiparray[$ii]['sku'] = $rdata['sku'];
            $shiparray[$ii]['mode'] = $rdata['mode'];

            $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);

            //$shiparray='rith';
            $ii++;
        }




        //echo '<pre>';
        //print_r($shiparray);
        //echo json_encode($shiparray);
        // die;
        //$dataArray['excelresult'] = $shiparrayexcel;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

        
    public function GetCompanyChnagesSave()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $RequestArr=$_POST;
        $UpdateArr=array(
            'user_name'=>$RequestArr['user_name'],
            'company_url'=>$RequestArr['company_url'],
            'password'=>$RequestArr['password'],
            'courier_account_no'=>$RequestArr['courier_account_no'],
            'courier_pin_no'=>$RequestArr['courier_pin_no'],
            'start_awb_sequence'=>$RequestArr['start_awb_sequence'],
            'end_awb_sequence'=>$RequestArr['end_awb_sequence'],
            'auth_token'=>$RequestArr['auth_token'],
            'api_url'=>$RequestArr['api_url'],
            'user_name_t'=>$RequestArr['user_name_t'],        
            'password_t'=>$RequestArr['password_t'],
            'courier_account_no_t'=>$RequestArr['courier_account_no_t'],
            'courier_pin_no_t'=>$RequestArr['courier_pin_no_t'],
            'start_awb_sequence_t'=>$RequestArr['start_awb_sequence_t'],
            'end_awb_sequence_t'=>$RequestArr['end_awb_sequence_t'],
            'auth_token_t'=>$RequestArr['auth_token_t'],
            'api_url_t'=>$RequestArr['api_url_t'],
       );
        $UpdateArr_w=array('id'=>$RequestArr['id']);
        
        $return=$this->Ccompany_model->GetUpdateDeliveryCOmpany($UpdateArr,$UpdateArr_w);
         echo json_encode($return);
    }

    public function GetCompanyChnagesSaveSeller()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $RequestArr=$_POST;
        $UpdateArr=array(
            'user_name'=>$RequestArr['user_name'],
            'company_url'=>$RequestArr['company_url'],
            'password'=>$RequestArr['password'],
            'courier_account_no'=>$RequestArr['courier_account_no'],
            'courier_pin_no'=>$RequestArr['courier_pin_no'],
            'start_awb_sequence'=>$RequestArr['start_awb_sequence'],
            'end_awb_sequence'=>$RequestArr['end_awb_sequence'],
            'auth_token'=>$RequestArr['auth_token'],
            'api_url'=>$RequestArr['api_url'],
            'user_name_t'=>$RequestArr['user_name_t'],        
            'password_t'=>$RequestArr['password_t'],
            'courier_account_no_t'=>$RequestArr['courier_account_no_t'],
            'courier_pin_no_t'=>$RequestArr['courier_pin_no_t'],
            'start_awb_sequence_t'=>$RequestArr['start_awb_sequence_t'],
            'end_awb_sequence_t'=>$RequestArr['end_awb_sequence_t'],
            'auth_token_t'=>$RequestArr['auth_token_t'],
            'api_url_t'=>$RequestArr['api_url_t'],
       );
        $UpdateArr_w=array('id'=>$RequestArr['id']);
        
        $return=$this->Ccompany_model->GetUpdateDeliveryCOmpanySeller($UpdateArr,$UpdateArr_w);
         echo json_encode($return);
    }
	
    
    public function GetUpdateActiveStatus()
    {
         $_POST = json_decode(file_get_contents('php://input'), true);
         $data=array('status'=>$_POST['status']);
         $data_w=array('id'=>$_POST['id']);
         $return=$this->Ccompany_model->GetUpdateDeliveryCOmpany($data,$data_w);
         echo json_encode($return);
    }


    public function GetUpdateActiveStatusSeller()
    {
         $_POST = json_decode(file_get_contents('php://input'), true);
         $data=array('status'=>$_POST['status']);
         $data_w=array('id'=>$_POST['id']);
         $return=$this->Ccompany_model->GetUpdateDeliveryCOmpanySeller($data,$data_w);
         echo json_encode($return);
    }
    
    public function GetUpdateLIveStatus()
    {
         $_POST = json_decode(file_get_contents('php://input'), true);
         $data=array('type'=>$_POST['status']);
         $data_w=array('id'=>$_POST['id']);
         $return=$this->Ccompany_model->GetUpdateDeliveryCOmpany($data,$data_w);
         echo json_encode($return);
    }

    public function GetUpdateLIveStatusSeller()
    {
         $_POST = json_decode(file_get_contents('php://input'), true);
         $data=array('type'=>$_POST['status']);
         $data_w=array('id'=>$_POST['id']);
         $return=$this->Ccompany_model->GetUpdateDeliveryCOmpanySeller($data,$data_w);
         echo json_encode($return);
    }
    
     //============3pl order calcel=============//
    public function GetCanelBplOrder() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        if (!empty($_POST['slip_no'])) {
            $slipArray = array('forwarded' => 0);
            $this->Ccompany_model->GetshipmentUpdate_forward($slipArray, $_POST['slip_no']);
            $return = true;
        } else
            $return = true;
        echo json_encode($return);
    }

    //=========================================//
    
    public function BulkForwardCompanyReady()
    {
      
        $postData = json_decode(file_get_contents('php://input'), true);
        
        $CURRENT_TIME = date('H:i:s');
        $CURRENT_DATE = date('Y-m-d H:i:s');

        if(!empty($postData['super_id']))
        {  
            
            $user_details['super_id']=$postData['super_id'];
            $this->session->set_userdata('user_details', $user_details);
            $shipmentLoopArray[] = $postData['slip_no'];
           
             $super_id = $postData['super_id'];
            $CURRENT_TIME = date('H:i:s');
            $CURRENT_DATE = date('Y-m-d H:i:s');
        }
        else
        {
            $super_id= $this->session->userdata('user_details')['super_id'];
         if(!empty($postData['slip_arr']) && !empty($postData['otherArr']))
        {
           $shipmentLoopArray = $postData['slip_arr']; 
           $postData['cc_id']=$postData['otherArr']['cc_id'];
        }
        else
        {
            $slipData = explode("\n", $postData['slip_no']);
            $shipmentLoopArray = array_unique($slipData);
        }
    }
        //print_r($shipmentLoopArray);exit; 
            $invalid_slipNO=array();
            $succssArray=array();
            if($postData['comment']!=''){
                $comment = $postData['comment'];
            }else{
                $comment = '';
            }
          
        if(!empty($shipmentLoopArray))
        { 
           
            if(!empty($postData))
            {
          
                
                $box_pieces=$postData['otherArr']['box_pieces'];
			    $box_pieces1= $postData['box_pieces'];
                 
            foreach ($shipmentLoopArray as $key => $slipNo) 
            {
              //print_r($shipmentLoopArray);exit; 
             
              
                $ShipArr=$this->Ccompany_model->GetSlipNoDetailsQry(trim($slipNo),$super_id);
                
                if(!empty($postData['cc_id'])){
                    
                   // $courier_id = $postData['cc_id'];
                   // $courier_data = $this->forwardShipment($postData['slip_no'], $super_id);
                    $courier_id = $postData['cc_id'];
                    //$zone_id = $courier_data[0]['id'];
                    //$zone_cust_id = $courier_data[0]['cust_id'];
                  // echo "<pre>";  print_r($courier_data);exit;
                }

                else{

                    $courier_data = $this->forwardShipment($postData['slip_no'], $super_id);                    
                    $courier_id = $courier_data[0]['cc_id'];
                    $zone_id = $courier_data[0]['id'];
                    $zone_cust_id = $courier_data[0]['cust_id'];
                   // echo "<pre>"; print_r($courier_data); //exit;
                    
                   
                }
                $ShipArr_custid =  $ShipArr['cust_id'];
                $counrierArr_table=$this->Ccompany_model->GetdeliveryCompanyUpdateQry($courier_id,$ShipArr_custid,$super_id);   
              //  echo $super_id ;
                  $c_id = $counrierArr_table['cc_id'];
               // $c_id = $counrierArr_table['id'];
         
             //print_r($zone_id);exit;

                $cc_id = $counrierArr_table['cc_id'];

              if ($counrierArr_table['type'] == 'test') {
                    $user_name = $counrierArr_table['user_name_t'];
                    $password = $counrierArr_table['password_t'];
                    $courier_account_no = $counrierArr_table['courier_account_no_t'];
                    $courier_pin_no = $counrierArr_table['courier_pin_no_t'];
                    $start_awb_sequence = $counrierArr_table['start_awb_sequence_t'];
                    $end_awb_sequence = $counrierArr_table['end_awb_sequence_t'];
                    $company = $counrierArr_table['company'];
                    $api_url = $counrierArr_table['api_url_t'];
                    $company_type  = $counrierArr_table['company_type'];
                    $create_order_url = $counrierArr_table['create_order_url'];                    
                    $auth_token = $counrierArr_table['auth_token_t'];
                } else {
                    $user_name = $counrierArr_table['user_name'];
                    $password = $counrierArr_table['password'];
                    $courier_account_no = $counrierArr_table['courier_account_no'];
                    $courier_pin_no = $counrierArr_table['courier_pin_no'];
                    $start_awb_sequence = $counrierArr_table['start_awb_sequence'];
                    $end_awb_sequence = $counrierArr_table['end_awb_sequence'];
                    $company = $counrierArr_table['company'];
                    $api_url = $counrierArr_table['api_url'];
                    $auth_token = $counrierArr_table['auth_token'];
                    $company_type  = $counrierArr_table['company_type'];
                    $create_order_url = $counrierArr_table['create_order_url']; 
                }
                $counrierArr['user_name'] = $user_name;
                $counrierArr['password'] = $password;
                $counrierArr['courier_account_no'] = $courier_account_no;
                $counrierArr['courier_pin_no'] = $courier_pin_no;
                $counrierArr['courier_pin_no'] = $courier_pin_no;
                $counrierArr['start_awb_sequence'] = $start_awb_sequence;
                $counrierArr['end_awb_sequence'] = $end_awb_sequence;
                $counrierArr['company'] = $company;
                $counrierArr['api_url'] = $api_url;
                $counrierArr['create_order_url'] = $create_order_url;
                $counrierArr['company_type'] = $company_type ;
                $counrierArr['auth_token'] = $auth_token;
                 $super_id = $ShipArr['super_id'];
               
            //  echo "<pre>"; print_r($counrierArr); //die; 
         
			 
                if(!empty($ShipArr))
                {
                  
                    $sku_data = $this->Ccompany_model->Getskudetails_forward($slipNo);
                    $sku_all_names = array();
                    $sku_total = 0;
                    $totalcustomerAmt=0;
                    foreach ($sku_data as $key => $val) {
                            $totalcustomerAmt+=$sku_data[$key]['cod'];
                            $skunames_quantity = $sku_data[$key]['name'] . "/ Qty:" . $sku_data[$key]['piece'];
                            $sku_total = $sku_total + $sku_data[$key]['piece'];
                            array_push($sku_all_names, $skunames_quantity);
                    }
                    $sku_all_names = implode(",", $sku_all_names);
                    if ($sku_total != 0) {
                            $complete_sku = $sku_all_names;
                    } else {
                            $complete_sku = $sku_all_names;
                    }
                    $pay_mode = trim($ShipArr['mode']);
                    $cod_amount = $ShipArr['total_cod_amt'];
                    if ($pay_mode == 'COD') {
                            $pay_mode = 'P';
                            $CashOnDeliveryAmount = array("Value" => $cod_amount,
                                    "CurrencyCode" => site_configTable("default_currency"));
                            $services = 'CODS';
                    } elseif ($pay_mode == 'CC') {
                            $pay_mode = 'P';
                            $CashOnDeliveryAmount = NULL;
                            $services = '';
                    }
                    
                    
                    if($company=='Aramex'){
                            $params = $this->Ccompany_model->AramexArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1,$super_id);

                            $dataJson = json_encode($params);
                            $headers = array("Content-type:application/json");
                            $url = $api_url;
                            $awb_array = $this->Ccompany_model->AxamexCurl($url, $headers, $dataJson,$c_id,$ShipArr);
                            $check_error = $awb_array['HasErrors'];
                            if ($check_error == 'true') {

                                    if (empty($awb_array['Shipments'])) {
                                            $error_response = $awb_array['Notifications']['Notification'];
                                            $error_response = json_encode($error_response);
                                            array_push($error_array, $slipNo . ':' . $error_response);
                                            $returnArr['responseError'][] = $slipNo . ':' . $error_response;
                                    } else {
                                            if ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'] == '') {
                                                    foreach ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification'] as $error_response) {
                                                            array_push($error_array, $slipNo . ':' . $error_response['Message']);
                                                            $returnArr['responseError'][] = $slipNo . ':' . $error_response['Message'];
                                                    }
                                            } else {
                                                    $error_response = $awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'];
                                                    $error_response = json_encode($error_response);
                                                    array_push($error_array, $slipNo . ':' . $error_response);
                                                    $returnArr['responseError'][] = $slipNo . ':' . $error_response;
                                            }
                                    }
                                    array_push($error_msg, $returnArr);
                            } else {
                                $main_result = $awb_array['Shipments']['ProcessedShipment'];

                                $Check_inner_error = $main_result['HasErrors'];
                                if ($Check_inner_error == 'false') {
                                        $client_awb = $main_result['ID'];
                                        $awb_label = $main_result['ShipmentLabel']['LabelURL'];

                                        $generated_pdf = file_get_contents($awb_label);
                                        $encoded = base64_decode($generated_pdf);
                                        header('Content-Type: application/pdf');
                                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                        $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                        $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);

                                        array_push($succssArray, $slipNo);
                                }
                            }
                    }else
                    if($company=='Aramex International'){
                            //print "<pre>"; print_r($ShipArr);die;
                            $params = $this->Ccompany_model->AramexArrayAdvance($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1,$super_id,$totalcustomerAmt);
                            $dataJson = json_encode($params);
                            //echo $dataJson;die;
                            $headers = array("Content-type:application/json");
                            $url = $api_url;
                            $awb_array = $this->Ccompany_model->AxamexCurl($url, $headers, $dataJson,$c_id,$ShipArr);
                            //print "<pre>"; print_r($awb_array);die;
                            $check_error = $awb_array['HasErrors'];
                            if ($check_error == 'true') {

                                    if (empty($awb_array['Shipments'])) {
                                            $error_response = $awb_array['Notifications']['Notification'];
                                            $error_response = json_encode($error_response);
                                            array_push($error_array, $slipNo . ':' . $error_response);
                                            $returnArr['responseError'][] = $slipNo . ':' . $error_response;
                                    } else {
                                            if ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'] == '') {
                                                    foreach ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification'] as $error_response) {
                                                            array_push($error_array, $slipNo . ':' . $error_response['Message']);
                                                            $returnArr['responseError'][] = $slipNo . ':' . $error_response['Message'];
                                                    }
                                            } else {
                                                    $error_response = $awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'];
                                                    $error_response = json_encode($error_response);
                                                    array_push($error_array, $slipNo . ':' . $error_response);
                                                    $returnArr['responseError'][] = $slipNo . ':' . $error_response;
                                            }
                                    }
                                    array_push($error_msg, $returnArr);
                            } else {
                                $main_result = $awb_array['Shipments']['ProcessedShipment'];

                                $Check_inner_error = $main_result['HasErrors'];
                                if ($Check_inner_error == 'false') {
                                        $client_awb = $main_result['ID'];
                                        $awb_label = $main_result['ShipmentLabel']['LabelURL'];

                                        $generated_pdf = file_get_contents($awb_label);
                                        $encoded = base64_decode($generated_pdf);
                                        header('Content-Type: application/pdf');
                                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                        $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                        $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);

                                        array_push($succssArray, $slipNo);
                                }
                            }
                        
                    }elseif($company=='Safearrival'){
                        
                            $charge_items=array();
                             $Auth_response = SafeArrival_Auth_cURL($counrierArr);  

                            $responseArray = json_decode($Auth_response, true);                      
                            $Auth_token = $responseArray['data']['id_token'];   						
                            $response = $this->Ccompany_model->SafeArray($ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);
                            $safe_response = json_decode($response, true);                     

                            if ($safe_response['status'] == 'success') {
                                    $safe_arrival_ID = $safe_response['data']['id'];
                                    $client_awb = $safe_response['data']['order_number'];

                                    //****************************safe arrival label print cURL****************************
							
                                    $label_response=safearrival_label_curl($safe_arrival_ID,$Auth_token,$counrierArr['api_url']);                       
                              
                                    $safe_label_response = json_decode($label_response, true);
                                    $safe_Label = $safe_label_response['data']['value'];

                                    $generated_pdf = file_get_contents($safe_Label);
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                    //****************************safe arrival label print cURL****************************

                                    $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                    $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);

                                    array_push($succssArray, $slipNo);

                                    array_push($DataArray, $slipNo);
                            }else if($safe_response['status']=='error'){
                                $returnArr['responseError'][] = $slipNo . ':' . $safe_response['message'];
                            }
                            
                    }elseif($company=='Thabit'){
                       
                            $charge_items=array();
                            $Auth_response = Thabit_Auth_cURL($counrierArr);
                            $responseArray = json_decode($Auth_response, true);                      
                            $Auth_token = $responseArray['data']['id_token'];
                            $response = $this->Ccompany_model->ThabitArray($ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);
                            $thabit_response = json_decode($response, true);   
                            if ($thabit_response['status'] == 'success' ) {
                                $thabit_order_ID = $thabit_response['data']['id'];
                                $client_awb = $thabit_response['data']['order_number'];

                                //**************************** Thabit label print cURL****************************
                                $label_response = thabit_label_curl($thabit_order_ID, $Auth_token,$counrierArr['api_url']); 
                                $safe_label_response = json_decode($label_response, true);
                                $safe_Label = $safe_label_response['data']['value'];

                                $generated_pdf = file_get_contents($safe_Label);
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                    //**************************** Thabit label print cURL****************************
                                    $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                    $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                    array_push($succssArray, $slipNo);
                                    array_push($DataArray, $slipNo);
                                    
                            }else if($thabit_response['status']=='error'){
                                
                                    $returnArr['responseError'][] = $slipNo . ':' . $thabit_response['status'];
                            }
                    }else if($company=='Esnad'){
                        $esnad_awb_number = Get_esnad_awb($start_awb_sequence, $end_awb_sequence); 
                        $esnad_awb_number = $esnad_awb_number -1;
                        $Auth_token = $counrierArr['auth_token'];                      
                        $response = $this->Ccompany_model->EsnadArray($ShipArr, $counrierArr, $esnad_awb_number, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);                      
                        $responseArray = json_decode($response, true); 
                        
                        $status = $responseArray['success'];
                        if($status == false)
                        {
                                $error_array = array(
                                        "Error_Message " => $responseArray['message'],
                                );
                                $error_response = json_encode($error_msg);
                                array_push($error_array, $slipNo . ':' . $error_response['message']);
                                $returnArr['responseError'][] = $slipNo . ':' . $responseArray['message'];                
                                $this->session->set_flashdata('errorloop', $returnArr);
                        }
                        if($status == true){
                         
                            $description = $responseArray['message'];
                            $client_awb = $responseArray['dataObj']['trackingNo'];
                            $esnad_awb_link = $responseArray['dataObj']['labelUrl'];
                       
                            $generated_pdf = file_get_contents($esnad_awb_link);
                          
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf'; 

                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $esnad_awb_link,$c_id);
                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                
                            array_push($succssArray, $slipNo);
                            array_push($DataArray, $slipNo);
                            $insert_esnad_awb_number = array(
                                    'slip_no' => $slipNo,
                                    'esnad_awb_no' => $esnad_awb_number,
                                    'super_id' => $this->session->userdata('user_details')['super_id']
                            );
                            updateEsdadAWB($insert_esnad_awb_number);
                        }
                        
                    }elseif ($company == 'Barqfleet') {
                            $response_ww = $this->Ccompany_model->BarqfleethArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services,$c_id,$box_pieces1,$super_id);
                            $response_array = json_decode($response_ww, TRUE);
                            
                            if ($response_array['code'] != '') {
                                 $returnArr['responseError'][] = $slipNo . ':' .$response_array['message'];
                                 
                            }else{
                                 $Authorization = $counrierArr['auth_token'];
                                 $request_url_label = $counrierArr['api_url']."/orders/airwaybill/".$response_array['id'];
                                 $headers = array("Content-type:application/json");
                                 $firsthead = array(
                                    "Content-Type: application/json",
                                    "Authorization: ". $Authorization,
                                 );
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $request_url_label);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $firsthead);
                                $response_label = curl_exec($ch);                                
                                $info = curl_getinfo($ch);
                                curl_close($ch);
                                $client_awb = $response_array['tracking_no'];                                   
                                $slip_no = $response_array['merchant_order_id'];                                   
                                $barq_order_id = $response_array['id'];
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");
                                $generated_pdf = file_get_contents($response_label);
                                file_put_contents("assets/all_labels/$slipNo.pdf", $response_label);
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************makdoom label print cURL****************************

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id, $barq_order_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                               array_push($succssArray, $slipNo);

                           }
                          
                            //end
                    }elseif ($company == 'Makhdoom'){
                            $Auth_response = MakdoomArrival_Auth_cURL($counrierArr);                             

                            $responseArray = json_decode($Auth_response, true);
                            $Auth_token = $responseArray['data']['id_token'];

                            $response =$this->Ccompany_model->MakdoonArray($ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);

                            $safe_response = json_decode($response, true);
     

                            if ($safe_response['status'] == 'success') {
                                $safe_arrival_ID = $safe_response['data']['id'];
                                $client_awb = $safe_response['data']['order_number'];

                                //****************************makdoom arrival label print cURL****************************

                                $label_response = makdoom_label_curl($client_awb, $Auth_token);
                                $safe_label_response = json_decode($label_response, true);
                                $safe_Label = $safe_label_response['data']['value'];

                                $generated_pdf = file_get_contents($safe_Label);
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                //echo $fastcoolabel ;

                                //****************************makdoom label print cURL****************************
                                 $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                array_push($succssArray, $slipNo);
                              
                            }
                            
                    }elseif ($company == 'Zajil') {
                            $response = $this->Ccompany_model->ZajilArray($ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$super_id);
                            if (!empty($response['data'])) {
                                $success = $response['data'][0]['success'];
                                if ($response['status'] == 'OK' && $success == true) {
                                    $client_awb = $response['data'][0]['reference_number'];

                                    $label_response = zajil_label_curl($auth_token, $client_awb);
                                    header("Content-type:application/pdf");
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                                    $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                    $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                    $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                    array_push($succssArray, $slipNo);
                                } else {
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['data'][0]['reason'];
                                }
                            } else {
                                $returnArr['responseError'][] = $slipNo . ':' . "invalid details";
                            }
                            
                    }elseif ($company == 'NAQEL'){
                        $awb_array = $this->Ccompany_model->NaqelArray($ShipArr,$counrierArr, $complete_sku,$box_pieces1, $Auth_token,$c_id,$super_id);
                        $HasError = $awb_array['HasError'];
                        $error_message = $awb_array['Message'];
                        
                        if ($awb_array['HasError'] =='false') 
                        {
                           
                            $client_awb = $awb_array['WaybillNo'];
                                if (!empty($client_awb)) 
                                {
                                    $user_name = $counrierArr['user_name'];    
                                    $password = $counrierArr['password'];
                                    $xml_for_label = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                                        <soapenv:Header/>
                                        <soapenv:Body>
                                        <tem:GetWaybillSticker>
                                            <tem:clientInfo>
                                                <tem:ClientAddress>
                                                    <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                                    <tem:POBox>0</tem:POBox>
                                                    <tem:ZipCode>0</tem:ZipCode>
                                                    <tem:Fax>0</tem:Fax>
                                                    <tem:FirstAddress>' . $ShipArr['sender_address'] . '</tem:FirstAddress>
                                                    <tem:Location>' . $sender_city . '</tem:Location>
                                                    <tem:CountryCode>KSA</tem:CountryCode>
                                                    <tem:CityCode>RUH</tem:CityCode>
                                                </tem:ClientAddress>
                                                <tem:ClientContact>
                                                    <tem:Name>' . $ShipArr['sender_name'] . '</tem:Name>
                                                    <tem:Email>' . $ShipArr['sender_email'] . '</tem:Email>
                                                    <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                                    <tem:MobileNo>' . $ShipArr['sender_phone'] . '</tem:MobileNo>
                                                </tem:ClientContact>
                                                <tem:ClientID>' . $user_name . '</tem:ClientID>
                                                <tem:Password>' . $password . '</tem:Password>
                                                <tem:Version>9.0</tem:Version>
                                            </tem:clientInfo>
                                            <tem:WaybillNo>' . $client_awb . '</tem:WaybillNo>
                                            <tem:StickerSize>FourMSixthInches</tem:StickerSize>
                                        </tem:GetWaybillSticker>
                                        </soapenv:Body>
                                        </soapenv:Envelope>';
                                  
                                    $headers = array(
                                        "Content-type: text/xml",
                                        "Content-length: " . strlen($xml_for_label),
                                    );

                                    $url = $counrierArr['api_url']."?op=GetWaybillSticker";

                                    $ch = curl_init();
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_for_label);
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                        $response = trim(curl_exec($ch));    
                              
                                        curl_close($ch);
                                    
                                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $response));
                                    $mediaData = $xml_data->Body->GetWaybillStickerResponse->GetWaybillStickerResult[0];
                                                 
                                        if (!empty($mediaData)) 
                                        {
                                            $pdf_label = json_decode(json_encode((array) $mediaData), TRUE);
                                            header('Content-Type: application/pdf');
                                            $img = base64_decode($pdf_label[0]);
                                            $savefolder = $img;
                                            file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);
                                            //*********NAQEL arrival label print cURL****************************

                                            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                            //****************NAQEL label print cURL****************************
                                             $CURRENT_DATE = date("Y-m-d H:i:s");
                                             $CURRENT_TIME = date("H:i:s");
                                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                           array_push($succssArray, $slipNo);
                                        }
                                }
                                else
                                   {
                                       $returnArr['responseError'][] = $slipNo . ':' . $awb_array['Message'];
                                   }
                        }
                    }elseif ($company == 'Saee'){
                             $response = $this->Ccompany_model->SaeeArray($ShipArr, $counrierArr, $Auth_token,$c_id,$box_pieces1,$super_id);
                             $safe_response =  $response; 
                            // echo "<pre>";  print_r($safe_response); 
                            if ($safe_response['success'] == 'true') 
                            {
                                      $client_awb = $safe_response['waybill'];
                                //****************************Saee arrival label print cURL****************************
                                $API_URL = $counrierArr['api_url'];
                                $label_response = saee_label_curl($client_awb, $Auth_token,$API_URL );
                                file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                                 $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************Saee label print cURL****************************
                                 $CURRENT_DATE = date("Y-m-d H:i:s");
                                 $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                array_push($succssArray, $slipNo);
                              
                            }  
                            else {
                                $returnArr['responseError'][] = $slipNo . ':' . $response['error'];
                               //$returnArr['responseError'][] = $slipNo . ':' . $response['invalid_parameters'][0];
                           }                                     
                    }elseif ($company == 'Smsa'){
                       
                            $response = $this->Ccompany_model->SMSAArray($ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,$super_id);
                            
                            $xml2 = new SimpleXMLElement($response);
                            $again = $xml2;
                            $a = array("qwb" => $again);

                            $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);

                            if (preg_match('/\bFailed\b/', $complicated)) {
                                $returnArr['responseError'][] = $slipNo . ':' . $complicated;
                            } 
                            else {
                                if ($response != 'Bad Request') {
                                    $xml2 = new SimpleXMLElement($response);
                                    //echo "<pre>";
                                    //print_r($xml2);
                                    $again = $xml2;
                                    $a = array("qwb" => $again);

                                    $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
                                    //print_r($complicated); exit;   
                                    $abc = array("qwber" => $complicated);

                                    $client_awb = (implode(" ", $abc));
                                    //print_r($abc);
                                    $newRes = explode('#', $client_awb);


                                    if (!empty($newRes[1])) {
                                        $client_awb = trim($newRes[1]);
                                    }

                                    $printLabel = $this->Ccompany_model->PrintLabel($client_awb, $counrierArr['$auth_token'], $counrierArr['api_url']);


                                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $printLabel));
                                    $mediaData = $xml_data->Body->getPDFResponse->getPDFResult[0];
                                    header('Content-Type: application/pdf');
                                    $img = base64_decode($mediaData);

                                    if (!empty($mediaData)) {
                                        $savefolder = $img;

                                        file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);

                                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                        $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                        $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);

                                        array_push($succssArray, $slipNo);

                                    } else 
                                    {
                                        array_push($error_array, $booking_id . ':' . $db);
                                    }
                                } else {
                                    $returnArr['responseError'][] = $slipNo . ':' . $response;
                                }
                            }
                            
                    }elseif ($company == 'Labaih'){       
                            $response = $this->Ccompany_model->LabaihArray($ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,$super_id);
                           
                            if ($response['status'] == 200) {
                                $client_awb = $response['consignmentNo'];
                                $shipmentLabel_url = $response['shipmentLabel'];

                                $generated_pdf = file_get_contents($shipmentLabel_url);
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                               	$Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                   $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                array_push($succssArray, $slipNo);
                            } 
                            else {
                                 $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                                //$returnArr['responseError'][] = $slipNo . ':' . $response['invalid_parameters'][0];
                            }
                            
                    }elseif ($company == 'Clex'){
                            
                        $response = $this->Ccompany_model->ClexArray($ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,$super_id);
                        //echo $this->session->userdata('user_details')['super_id'];
                     //   print_r($response);
                        if ($response['data'][0]['cn_id']) {
                            $client_awb = $response['data'][0]['cn_id'];
                             $label_url_new = clex_label_curl($Auth_token, $client_awb);
                             $generated_pdf = file_get_contents($label_url_new);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                            $fastcoolabel = base_url()."assets/all_labels/$slipNo.pdf";
                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                            array_push($succssArray, $slipNo);
                        } else {
                            if($response['already_exist'])
                            {
                                $label_url_new = clex_label_curl($Auth_token, $response['consignment_id'][0]);
                                
                                $generated_pdf = file_get_contents($label_url_new);
                               file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $returnArr['responseError'][] = $slipNo . ':' . $response['already_exist'][0]." ".$response['consignment_id'][0];
                            }
                            elseif($response['origin_city'])
                                 $returnArr['responseError'][] = $slipNo . ':' . $response['origin_city'][0];
                             elseif($response['destination_city'])
                                 $returnArr['responseError'][] = $slipNo . ':' . $response['destination_city'][0];
                            else
                                $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                                
                        }
                        
                    }elseif ($company == 'Ajeek'){
                            
                            $response = $this->Ccompany_model->AjeekArray($ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,$super_id);
                            if ($response['contents']['order_id']) {
                                 $response['contents']['order_id'];
                                 $Auth_token = $counrierArr['auth_token'];
                                 $vendor_id = $counrierArr['courier_pin_no'];
                                 $client_awb = $response['contents']['order_id'];
                                 
                                //****************************Saee arrival label print cURL****************************
                                 $label_response = ajeek_label_curl($Auth_token, $client_awb, $vendor_id);
                                   
                                file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                                  $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                  
                                //****************************Saee label print cURL****************************
                                 $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                               array_push($succssArray, $slipNo);

                            }  else{
                                    
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['description'];
                                    
                            }
                    }elseif ($company == 'Aymakan'){
                            
                            $response = $this->Ccompany_model->AymakanArray($ShipArr, $counrierArr, $Auth_token,$c_id,$box_pieces1,$complete_sku,$super_id);
                            $responseArray = json_decode($response, true);
                            if (empty($responseArray['message'])) 
                            {
                                 $client_awb = $responseArray['data']['shipping']['tracking_number'];


                                $tracking_url= $counrierArr['api_url']."bulk_awb/trackings/";

                                $aymakanlabel= $this->Ccompany_model->Aymakan_tracking($client_awb, $tracking_url,$auth_token);
                                $label= json_decode($aymakanlabel,TRUE);

                                $mediaData = $label['data']['bulk_awb_url'];
                                       
                                        
                                   
                                //****************************aymakan arrival label print cURL****************************
                                $generated_pdf = file_get_contents($media_data);
                                file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************aymakan label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                 $CURRENT_TIME = date("H:i:s");
                                                             
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                array_push($succssArray, $slipNo); 
                            }   
                            else{
                                  
                                    $returnArr['responseError'][] = $slipNo . ':' . $responseArray['message'].':'.json_encode($responseArray['errors']);
                                    
                            }    
                    
                    }elseif($company == 'Shipsy'){
						
                        $response = $this->Ccompany_model->ShipsyArray($ShipArr, $counrierArr, $Auth_token, $box_pieces1,$c_id,$super_id);
                        
                        $response_array = json_decode($response, true);
                        
                        if($response_array['data'][0]['success']==1){
                            $client_awb = $response_array['data'][0]['reference_number'];
                            
                            //****************************Shipsy label print cURL****************************
                            
                            $shipsyLabel = $this->Ccompany_model->ShipsyLabelcURL($counrierArr, $client_awb);
                            
                            $mediaData = $shipsyLabel;
                           
                            file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                             $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                            array_push($succssArray, $slipNo);
                        }else{
                            
                            $returnArr['responseError'][] = $slipNo . ':' . $response_array['error']['message'];
                        }
                        
                    }elseif(trim($company) == 'Shipadelivery'){
                     
                       $response = $this->Ccompany_model->ShipadeliveryArray($ShipArr, $counrierArr, $Auth_token,$c_id,$super_id); 
                    
                        $response_array = json_decode($response,true); 
                      
                        if(empty($response_array)){
                            $returnArr['responseError'][] = $slipNo . ':' .'Receiver City Empty ';
                        }
                        else{

                            if($response_array[0]['code']== 0)
                                {
                                    $client_awb = $response_array[0]['deliveryInfo']['reference'];
                                
                                    $responsepie = $this->Ccompany_model->ShipaDelupdatecURL($counrierArr, $ShipArr, $client_awb ,$box_pieces1,$super_id);
                                   
                               
                                    $responsepieces = json_decode($responsepie, true); 
                                  
                                   
                                         if ($responsepieces['status']=='Success')
                                         {
                                            $shipaLabel = $this->Ccompany_model->ShipaDelLabelcURL($counrierArr, $client_awb);

                                            header('Content-Type: application/pdf');

                                            file_put_contents("assets/all_labels/$slipNo.pdf", $shipaLabel);
                                            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                            array_push($succssArray, $slipNo);

                                         }
                                          else{

                                                $returnArr['responseError'][] = $slipNo . ':' . $responsepieces['action'];
                                            }
                                } else{

                                    $returnArr['responseError'][] = $slipNo . ':' . $response_array['info'];
                                }
                            }
                                          
                            
                                
                    }elseif($company == 'Saudi Post'){
                        $response = $this->Ccompany_model->SPArray($ShipArr, $counrierArr,$complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);
                        
                        $response = json_decode($response, true);
                                        
                        if($response['Items'][0]['Message']=='Success'){
                            $client_awb = $response['Items'][0]['Barcode'];
                            
                          
                            $fastcoolabel='SP';
                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$barq_order_id,$box_pieces1);
                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                            

                            header('Content-Type: application/pdf');
                            $lableSp=   file_get_contents(base_url().'awbPrint1/'.$slipNo );
                            file_put_contents("assets/all_labels/$slipNo.pdf", $lableSp);
                          
                            array_push($succssArray, $slipNo);
                        }else{
                            $errre_response = $response['Items'][0]['Message'];
                            if($errre_response==''){
                                $errre_response = $response['Message'];
                            }
                            $returnArr['responseError'][] = $slipNo . ':' . $errre_response;
                        }
                    }elseif ($company== 'Beez'){
                            //print "<pre>"; print_r($sku_data);die;
                            $response = $this->Ccompany_model->BeezArray($ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$sku_data,$super_id);  
                            if(isset($response['Message']) && !empty($response['Message'])){
                                $returnArr['responseError'][] = $slipNo . ':' . $response['Message'];
                            }else{
                                
                                $client_awb = $response;
                                $url = 'https://login.beezerp.com/label/pdf/?t='.$client_awb;
                                $generated_pdf = file_get_contents($url); 
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                
                                $beezlabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $beezlabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                array_push($succssArray, $slipNo);
                            }
                    }elseif ($company == 'GLT')
                    {

                        $responseArray = $this->Ccompany_model->GLTArray($ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku,$super_id);
                        $successres = $responseArray['data']['orders'][0]['status'];
                        $error_status = $responseArray['data']['orders'][0]['msg'];

                            if (!empty($successres) && $successres == 'success')
                            {

                                $client_awb = $responseArray['data']['orders'][0]['orderTrackingNumber'];
                                $innser_status = $responseArray['data']['orders'][0]['status'];
                                                         

                                $GltLabel = $this->Ccompany_model->GLT_label($client_awb, $counrierArr, $auth_token);
                                    
                                 file_put_contents("assets/all_labels/$slipNo.pdf", $GltLabel);                            
                                 $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';


                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);

                                array_push($succssArray, $slipNo);
                            }
                            
                            else
                            {

                                $returnArr['responseError'][] = $slipNo . ':' .$error_status;

                            }
                        
                    }
                    elseif($company == 'Tamex')
                    {
                        
                       $response = $this->Ccompany_model->tamexArray($ShipArr, $counrierArr, $complete_sku, $pay_mode,$c_id,$box_pieces1,$super_id);
                       

                         $responseArray = json_decode($response, true);
                      
                      
                            if ($responseArray['code'] != 0 || empty($response)) {
                                array_push($error_array, $slipNo . ':' . $responseArray['data']);
                                $returnArr['responseError'][] = $slipNo . ':' . $responseArray['data'];
                            } elseif ($responseArray['code'] == 0) {

                                  $client_awb = $responseArray['tmxAWB'];
                                 $API_URL= $counrierArr['api_url'].'print';
                                
                                $generated_pdf = Tamex_label($client_awb, $counrierArr['auth_token'],$API_URL);
                              
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);


                                  $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");
                               
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);


                                $details = 'Forwarded to ' . $ClientArr['company'];
                                

                                $returnArr['successAbw'][] = 'AWB No.' . $slipNo . ' forwarded to TAMEX';

                                $this->session->set_flashdata('msg', $returnArr);

                                array_push($DataArray, $slipNo);
                            }
                    }
                    elseif ($company== 'Fetchr'){ 
                     
                               $responseData = $this->Ccompany_model->fetchrArray($ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1,$super_id);
                               if($responseData['data'][0]['status'] == 'success')
                                {
                                    $client_awb = $responseData['data'][0]['tracking_no'];
                                    
                                    $label = "https://s3-eu-west-1.amazonaws.com/cms-dhl-pdf-stage-1/label6x4_".$client_awb.".pdf";
                                    
                                  
                                    $generated_pdf = file_get_contents($label);
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf );
                                    
                                   $fetchrlabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                    $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");
                                    $comment = $responseData['message'];
                                   $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fetchrlabel,$c_id);
                                   $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                    $returnArr['successAbw'][] = 'AWB No.' . $slipNo . ' :'. $responseData['message'];

                                    $this->session->set_flashdata('msg', $returnArr);
                                    array_push($succssArray, $slipNo);
                                 }else{

                                     $returnArr['responseError'][] = $slipNo . ':' . $responseData['data'][0]['message'];
                                 } 
                    }
                    elseif ($company== 'iMile'){
                        //print "<pre>"; print_r($sku_data);die;
                        $auth_token = $this->Ccompany_model->iMileToken($counrierArr);
                        
                        if(empty($auth_token)){
                            $returnArr['responseError'][] = $slipNo . ': Token not genrated';
                        }else{
                            $response = $this->Ccompany_model->iMileArray($ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$auth_token,$super_id);  
                            if($response['code'] == 200  && $response['message'] == 'success'){
                                $client_awb = $response['data']['expressNo'];
                                $pdf_encoded_base64 = $response['data']['imileAwb'];
                                $pdf_file = base64_decode($pdf_encoded_base64);

                                file_put_contents("assets/all_labels/".$slipNo.".pdf", $pdf_file);
                                $imile_label = base_url() . "assets/all_labels/$slipNo.pdf";
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $imile_label,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                $returnArr['successAbw'][] = 'AWB No.' . $slipNo . ' forwarded to iMile';
                                array_push($succssArray, $slipNo);
                                
                            }else if($response['code'] == 30001){
                                $returnArr['responseError'][] = $slipNo . ': Customer order number repeated error code';
                            }else{
                                $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                            }
                                               
                        }
                    }
                    elseif ($company == 'Wadha')
                    {
                        $counrierArr['user_name'] = $user_name;
                        $counrierArr['password'] = $password;
                        $counrierArr['api_url'] =$api_url;
                       $Auth_token=$this->Ccompany_model->Wadha_auth($user_name,$password,$api_url); 
                      
                        $responseArray = $this->Ccompany_model->WadhaArray($ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$super_id);  
                                            
                        $successres = $responseArray['status'];                          
                        
                         $error_status = $responseArray['message'];

                        if (!empty($successres) && $successres == 'success')
                        {

                            $client_awb = $responseArray['data']['order_number'];
                             $WadhaLabel = $this->Ccompany_model->Wadha_label($client_awb, $counrierArr, $Auth_token);
                              $label= json_decode($WadhaLabel,TRUE);
                              $media_data = $label['data']['value'];                               

                             $generated_pdf = file_get_contents($media_data);
                             file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                             $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                            $returnArr['successAbw'][] = 'AWB No.' . $slipNo . ' forwarded to Wadha';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }

                    }elseif ($company == 'SLS')
                    {

                        $responseArray = $this->Ccompany_model->SLSArray($ShipArr, $counrierArr, $complete_sku, $box_pieces1,$c_id,$super_id);
                       //  echo "<pre>" ; print_r($responseArray); //die;
                        $successres = $responseArray['status'];
                        $error_status = json_encode($responseArray);

                            if (!empty($successres) && $successres == 1)
                            {
                                $client_awb = $responseArray['tracking_number'];
                                $SLSLabel = $this->Ccompany_model->SLS_label($client_awb, $counrierArr);
                                   
                                file_put_contents("assets/all_labels/$slipNo.pdf", $SLSLabel);                            
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                $returnArr['successAbw'][] = 'AWB No.' . $slipNo . ' forwarded to SLS';
                            array_push($succssArray, $slipNo);
                        }                            
                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }
                    elseif ($company == 'FedEX')
                    {

                        $responseArray = $this->Ccompany_model->FedEX($ShipArr, $counrierArr, $complete_sku, $box_pieces1,$c_id,$super_id);
                       //  echo "<pre>" ; print_r($responseArray); //die;
                        $successres = $responseArray['Code'];
                        $error_status = $responseArray['description'];

                            if (!empty($successres) && $successres == 1)
                            {
                                $client_awb = $responseArray['AirwayBillNumber'];
                                 
                                $label_response = $this->Ccompany_model->FedEX_label($client_awb, $counrierArr,$ShipArr);
                                $pdf_encoded_base64 = $label_response['ReportDoc'];
                                $pdf_file = base64_decode($pdf_encoded_base64);
                               
                                file_put_contents("assets/all_labels/".$slipNo.".pdf", $pdf_file);
                                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                $returnArr['successAbw'][] = 'AWB No.' . $slipNo . ' forwarded to SLS';
                            array_push($succssArray, $slipNo);
                        }                            
                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }
                    elseif ($company== 'MomentsKsa')
                       {
                        
                        $Auth_token=$this->Ccompany_model->Moments_auth($counrierArr); 
                      
                        $responseArray = $this->Ccompany_model->MomentsArray($ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$complete_sku,$super_id); 
                        
                        
                        $successres = $responseArray['errors'];                         
                        
                        $error_status = $responseArray['message'];

                        if (empty($successres))
                        {

                            $client_awb = $responseArray['TrackingNumber'];
                            $MomentLabel = $responseArray['printLableUrl'];
                             
                            $generated_pdf = file_get_contents($MomentLabel);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }
                    elseif ($company== 'Postagexp')
                       {
                        
                        $Auth_token=$this->Ccompany_model->Postagexp_auth($counrierArr); 
                      
                        $responseArray = $this->Ccompany_model->PostagexpArray($ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$complete_sku,$super_id); 
                        $successres = $responseArray['errors'];                         
                        $error_status = $responseArray['message'];

                        if (empty($successres))
                        {

                            $client_awb = $responseArray['TrackingNumber'];
                            $PostagexpLabel = $responseArray['printLable'];
                             
                            $generated_pdf = file_get_contents($PostagexpLabel);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }


                    elseif ($company_type== 'F')
                    { // for all fastcoo clients treat as a CC 
                      
                        
                        if ($company=='Ejack' ) 
                        {
                                $response = $this->Ccompany_model->Ejack($ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$super_id);
                                $response = json_decode($response, true);
                                if($response['error']=='')
                                {
                                    $generated_pdf = file_get_contents($response['awb_print_url']);                                
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                    
                                    $client_awb = $response['awb'];
        
                                    $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                    $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                    $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                    array_push($succssArray, $slipNo);
                                } else {
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['refrence_id'];
                                }
                            
                        }

                        else if ($company=='Emdad' )
                        {
                            $response = $this->Ccompany_model->EmdadArray($ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1);
                            $response = json_decode($response, true);
                            if($response['error']=='')
                            {
                                $generated_pdf = file_get_contents($response['awb_print_url']);                                
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                
                                $client_awb = $response['awb'];

                                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                array_push($succssArray, $slipNo);
                            } else {
                                $returnArr['responseError'][] = $slipNo . ':' . $response['refrence_id'];
                            }
                        
                        }

                        else
                        {
                            $response = $this->Ccompany_model->fastcooArray($ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);
                            $responseArray = json_decode($response, true);     
                            if($responseArray['status']==200) 
                            {  
                                $client_awb = $responseArray['awb_no'];                                
                                $mediaData = $responseArray['label_print'];
                                //****************************fastcoo label print cURL****************************

                                file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************fastcoo label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id,$zone_id,$super_id);
                                array_push($succssArray, $slipNo);                           
                            }                               
                            else
                            {
                                array_push($alreadyExist, $slipNo); 
                                $returnArr['responseError'][] = $slipNo . ':' . $responseArray['msg'];  
                            } 
                        }                                   
                    } //end company type F code 

                }
                // else
                // {
                //     array_push($invalid_slipNO,$slipNo);
                    
                // }
           }
        }
        } 
        $return['invalid_slipNO']=$invalid_slipNO;
        $return['Error_msg']=$returnArr['responseError'];
        //$return['Success_msg']=$returnArr['successAwb'];
        $return['Success_msg']=$succssArray;
      
        
        echo json_encode($return);
    }
    
    public function performance_details($frwd_throw=null,$status=null,$from=null,$to=null){
		
		$data['DetailsArr'] = $this->Ccompany_model->GetallperformationDetailsQry($frwd_throw,$status,$from,$to);
		$this->load->view('courierCompany/performance_details',$data);
    }
	
	
	
    public function performance(){

        $data['postData'] = $this->input->post();

        if($data['postData']['clfilter']==1){
            $data['postData']=array();  
        }

        $data['sellers'] = $this->Ccompany_model->all($data['postData']);

        $this->load->view('courierCompany/performance',$data);

    }


    public function forwardShipment($awb = null, $super_id = null) {

        $fullData = $this->shipDetail($awb, $super_id);
    //   print_r($fullData);exit;
        if (!empty($fullData)) {
            
           // echo "customer default <br/>" ; 
            $lastArray = array();
            foreach ($fullData as $data) {
        
                $dataArray = $this->zonListDatacustomer($data['cc_id'], $data['destination'], $super_id,$data['cust_id']);
                // echo '<pre>';
                // print_r($dataArray);exit;
            
                if (!empty($dataArray)) {
                    return $dataArray;
                    break;
                }
            }
        }
        else{
           // echo "default <br/>" ; 
            $fullData = $this->shipDetailDefault($awb, $super_id);
            $lastArray = array();
            foreach ($fullData as $data) {
        
                $dataArray = $this->zonListDatadefault($data['cc_id'], $data['destination'], $super_id,$data['cust_id']);           
                if (!empty($dataArray)) {
                    return $dataArray;
                    break;
                }
            }

        }
    }

    public function zonListDatadefault($ccid, $dest, $super_id,$cust_id) {
        //echo $dest."<br>";
         
                $this->db->select('id,cc_id,city_id');
                $this->db->from('zone_list_fm');
                $this->db->where('zone_list_fm.super_id', $super_id);
                $this->db->where('capacity>todayCount');
                $this->db->where('cc_id', $ccid);
        
                $query1 = $this->db->get();
             // echo $this->db->last_query()."<br>"; die; 
                $result = $query1->result_array();
                if ($query1->num_rows()> 0)
                {
                    $result = $query1->result_array();
                
                    $rData = array();
                    foreach ($result as $n) {
                        if (in_array($dest, json_decode($n['city_id'], true))) {
                            array_push($rData, $n);
                        }
                    }
                }

                if (!empty($rData)) {
                    return $rData;
                } else {
                    return false;
                }
    }



    public function zonListDatacustomer($ccid, $dest, $super_id,$cust_id) {
        //echo $dest."<br>";
            $this->db->select('id,cc_id,city_id,cust_id');
            $this->db->from('zone_list_customer_fm');
            $this->db->where('zone_list_customer_fm.super_id', $super_id);
            $this->db->where('capacity > todayCount');
            $this->db->where('cust_id',$cust_id);
            $this->db->where('cc_id', $ccid);

            $query = $this->db->get();
           // echo $this->db->last_query()."<br>";// die ; 

            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();    
                $rData = array();
                foreach ($result as $n) {
                    if (in_array($dest, json_decode($n['city_id'], true))) {
                        array_push($rData, $n);
                    }
                }
            }

            if (!empty($rData)) {
                return $rData;
            } else {
                return false;
            }
    }


    public function shipDetailDefault($slip_no, $super_id) {

        $this->db->select('shipment_fm.cust_id,shipment_fm.destination,sellerDefaultCourier.cc_id,sellerDefaultCourier.priority');
        $this->db->from('shipment_fm');
        $this->db->join('sellerDefaultCourier', 'sellerDefaultCourier.super_id = shipment_fm.super_id');
        $this->db->where('shipment_fm.slip_no', $slip_no);
        $this->db->where('shipment_fm.super_id', $super_id);
        $this->db->where('sellerDefaultCourier.status', '0');
        $this->db->order_by('sellerDefaultCourier.priority', 'ASC');
        $query = $this->db->get();
        // echo "shipDetailDefault = ". $this->db->last_query(); die;
        $result = $query->result_array();

        return $result;
    }

    public function shipDetail($slip_no, $super_id) {

        $this->db->select('shipment_fm.cust_id,shipment_fm.destination,sellerCourier.cc_id,sellerCourier.priority');
        $this->db->from('shipment_fm');
        $this->db->join('sellerCourier', 'sellerCourier.seller_id = shipment_fm.cust_id');
        $this->db->where('shipment_fm.slip_no', $slip_no);
        $this->db->where('shipment_fm.super_id', $super_id);
        $this->db->where('sellerCourier.status', '0');
        $this->db->order_by('sellerCourier.priority', 'ASC');
        $query = $this->db->get();
        //echo "shipDetail = ". $this->db->last_query(); die; 
        $result = $query->result_array();

        return $result;
    }

// reverse order 

    public function BulkForwardCompanyReverse(){
            echo "lnlklkl"; die ; 
    }
}
?>