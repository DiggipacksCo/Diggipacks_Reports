<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class CourierCompany extends MY_Controller  {


	function __construct() {
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
        
    public function forwardshipments() {
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
	
    
    public function GetUpdateActiveStatus()
    {
         $_POST = json_decode(file_get_contents('php://input'), true);
         $data=array('status'=>$_POST['status']);
         $data_w=array('id'=>$_POST['id']);
         $return=$this->Ccompany_model->GetUpdateDeliveryCOmpany($data,$data_w);
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
        $invalid_slipNO=array();
		$succssArray=array();
		if($postData['comment']!=''){
			$comment = $postData['comment'];
		}else{
			$comment = '';
		}
             // print_r($postData); die;
        if(!empty($shipmentLoopArray))
        {
            if(!empty($postData))
            {
                //print_r($postData);
               $box_pieces=$postData['otherArr']['box_pieces'];
			   $box_pieces1=$postData['box_pieces'];
                //echo $postData['cc_id'];
             $counrierArr_table=$this->Ccompany_model->GetdeliveryCompanyUpdateQry($postData['cc_id']);
            // print_r($counrierArr); die;
             
              $c_id=$counrierArr_table['id'];
              if ($counrierArr_table['type'] == 'test') {
                    $user_name = $counrierArr_table['user_name_t'];
                    $password = $counrierArr_table['password_t'];
                    $courier_account_no = $counrierArr_table['courier_account_no_t'];
                    $courier_pin_no = $counrierArr_table['courier_pin_no_t'];
                    $start_awb_sequence = $counrierArr_table['start_awb_sequence_t'];
                    $end_awb_sequence = $counrierArr_table['end_awb_sequence_t'];
                    $company = $counrierArr_table['company'];
                    $api_url = $counrierArr_table['api_url_t'];
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
                $counrierArr['auth_token'] = $auth_token;
			 
            foreach ($shipmentLoopArray as $key => $slipNo) 
            {
                $ShipArr=$this->Ccompany_model->GetSlipNoDetailsQry(trim($slipNo));
                if(!empty($ShipArr))
                {
					$sku_data = $this->Ccompany_model->Getskudetails_forward($slipNo);
					$sku_all_names = array();
					$sku_total = 0;
					foreach ($sku_data as $key => $val) {
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
    							"CurrencyCode" => "SAR");
    						$services = 'CODS';
    					} elseif ($pay_mode == 'CC') {
    						$pay_mode = 'P';
    						$CashOnDeliveryAmount = NULL;
    						$services = '';
    					}
					if($company=='Aramex'){
						$params = $this->Ccompany_model->AramexArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services);
						
						$dataJson = json_encode($params);
                       // echo " Datajson ". $dataJson; die;
												
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
								file_put_contents("/var/www/html/fastcoo-tech/fulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);

								echo $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
								
								$Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
								
								array_push($succssArray, $slipNo);
							}
						}
					}
                    elseif($company=='Safearrival'){
						$charge_items=array();
						$Auth_response = SafeArrival_Auth_cURL($counrierArr);
						
						$responseArray = json_decode($Auth_response, true);
						$Auth_token = $responseArray['data']['id_token'];
						
						$response = $this->Ccompany_model->SafeArray($ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id);
						
						$safe_response = json_decode($response, true);
						
						if ($safe_response['status'] == 'success') {
							$safe_arrival_ID = $safe_response['data']['id'];
							$client_awb = $safe_response['data']['order_number'];

							//****************************safe arrival label print cURL****************************
							
							$label_response = safearrival_label_curl($safe_arrival_ID, $Auth_token);
							
							$safe_label_response = json_decode($label_response, true);
							$safe_Label = $safe_label_response['data']['value'];
							$generated_pdf = file_get_contents($safe_Label);
							$encoded = base64_decode($generated_pdf);
							//header('Content-Type: application/pdf');
							file_put_contents("/var/www/html/fastcoo-tech/fulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);

							$fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

							//****************************safe arrival label print cURL****************************

							$Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
							
							array_push($succssArray, $slipNo);

							array_push($DataArray, $slipNo);
						}else if($safe_response['status']=='error'){
							$returnArr['responseError'][] = $slipNo . ':' . $safe_response['message'];
						}
					}
                    elseif($company=='Esnad'){
					    $esnad_awb_number = Get_esnad_awb($start_awb_sequence, $end_awb_sequence); 
						//echo $esnad_awb_number; die;
						$esnad_awb_number = $esnad_awb_number -1;
						$response = $this->Ccompany_model->EsnadArray($ShipArr, $counrierArr, $esnad_awb_number, $complete_sku, $Auth_token,$c_id);
						
						$responseArray = json_decode($response, true);
						
						$status = $responseArray['code'];
						if ($status == "2000") {
							$error_msg = array(
								"Error_Message " => $responseArray['msg'],
							);
							$errre_response = json_encode($error_msg);
							array_push($error_array, $slipNo . ':' . $error_response['Message']);
							$returnArr['responseError'][] = $slipNo . ':' . $responseArray['msg'];

							$this->session->set_flashdata('errorloop', $returnArr);
						}
						if ($status == "3000") {
							$error_msg = array(
								"Error_Message " => $responseArray['msg'],
								"Awb_NO " => $responseArray['data'][0]['clientOrderNo'],
								"Esnad_awb_no " => $responseArray['data'][0]['esnadAwbNo'],
								"Esnad_awb_link" => $responseArray['data'][0]['esnadAwbPdfLink'],
							);
							$errre_response = json_encode($error_msg);
							array_push($error_array, $slipNo . ':' . $responseArray['msg']);
							$returnArr['responseError'][] = $slipNo . ':' . $responseArray['msg'];

							$this->session->set_flashdata('errorloop', $returnArr);
						}
						// echo $status;exit;

						if ($status == "1000") {
							$status = $responseArray['code'];
							$description = $responseArray['msg'];
							$client_awb = $responseArray['data'][0]['esnadAwbNo'];
							if ($status == "1000" && $description == "SUCCESS") {
								$esnad_awb_link = $responseArray['data'][0]['esnadAwbPdfLink'];
									$generated_pdf = file_get_contents($esnad_awb_link);
							$encoded = base64_decode($generated_pdf);
							//header('Content-Type: application/pdf');
							file_put_contents("/var/www/html/fastcoo-tech/fulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);
								$Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $esnad_awb_link,$c_id,1);
							
								array_push($succssArray, $slipNo);

								array_push($DataArray, $slipNo);

								$insert_esnad_awb_number = array(
									'slip_no' => $slipNo,
									'esnad_awb_no' => $esnad_awb_number,
                                    'super_id' => $this->session->userdata('user_details')['super_id']
								);
								updateEsdadAWB($insert_esnad_awb_number);
							}
						}
					}
                    elseif ($company == 'Barqfleet') {
                            $response_ww = $this->Ccompany_model->BarqfleethArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services,$c_id);
                              
                               $response_array = json_decode($response_ww, TRUE);                        
                                 //echo "<pre>"; print_r($response_array['message']); die; 
                            //start

                            if ($response_array['code'] != '') {
                                 $returnArr['responseError'][] = $slipNo . ':' .$response_array['message'];
                            } 
                            else 
                            {
                                 $Authorization = $counrierArr['auth_token'];
                               
                                 $request_url_label = "https://staging.barqfleet.com/api/v1/merchants/orders/airwaybill/".$response_array['id'];
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
                               array_push($succssArray, $slipNo);

                           }
                          
                            //end
                        } 
                    elseif ($company == 'Makhdoom'){
                        //echo "string";;die;

                            $Auth_response = MakdoomArrival_Auth_cURL($counrierArr);
                               

                            $responseArray = json_decode($Auth_response, true);
                            $Auth_token = $responseArray['data']['id_token'];

                            $response =$this->Ccompany_model->MakdoonArray($ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id);

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
                               array_push($succssArray, $slipNo);
                              
                            }
                    }
                    elseif ($company == 'Zajil') {
                            //print_r($counrierArr); die;
                            $response = $this->Ccompany_model->ZajilArray($ShipArr, $counrierArr, $complete_sku,$c_id);
                            // print_r($response); die;
                            if (!empty($response['data'])) {
                                $success = $response['data'][0]['success'];
                                if ($response['status'] == 'OK' && $success == true) {
                                    $client_awb = $response['data'][0]['reference_number'];

                                    $label_response = zajil_label_curl($auth_token, $client_awb);
                                    header("Content-type:application/pdf");
                                    //print_r($label_response); die;
                                    //$generated_pdf = file_get_contents($label_response);
                                    //$encoded = base64_decode($generated_pdf);
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                                    $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";

                                    $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                    array_push($succssArray, $slipNo);
                                } else {
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['data'][0]['reason'];
                                }
                            } else {
                                $returnArr['responseError'][] = $slipNo . ':' . "invalid details";
                            }
                    }
                    elseif ($company == 'NAQEL'){
                        $awb_array = $this->Ccompany_model->NaqelArray($ShipArr,$counrierArr, $complete_sku,$box_pieces, $Auth_token,$c_id);
                         
                         $HasError = $awb_array['HasError'];
                        $error_message = $awb_array['Message'];
                        
                        if ($awb_array['HasError'] !== true) 
                        {

                             $client_awb = $awb_array['WaybillNo'];

                                if (!empty($client_awb)) 
                                {
                                    //echo "client_awb = ". $client_awb; echo "<br> <br/>";
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
                                      //print_r($xml_for_label);//exit;
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
                                           array_push($succssArray, $slipNo);
                                        }
                                }
                                else
                                   {
                                       $returnArr['responseError'][] = $slipNo . ':' . $awb_array['Message'];
                                   }
                        }
                    }
                    elseif ($company == 'Saee'){
                            $response = $this->Ccompany_model->SaeeArray($ShipArr, $counrierArr, $Auth_token,$c_id);
                            $safe_response =  $response; 

                            if ($safe_response['success'] == 'true') 
                            {
                                      $client_awb = $safe_response['waybill'];
                                //****************************Saee arrival label print cURL****************************

                                $label_response = saee_label_curl($client_awb, $Auth_token);
                                file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                                 $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************Saee label print cURL****************************
                                 $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                               array_push($succssArray, $slipNo);
                              
                            }                                       
                    } 
                    elseif ($company == 'Smsa'){
                       
                            $response = $this->Ccompany_model->SMSAArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$c_id);
                            
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

                                        array_push($succssArray, $slipNo);

                                    } else 
                                    {
                                        array_push($error_array, $booking_id . ':' . $db);
                                    }
                                } else {
                                    $returnArr['responseError'][] = $slipNo . ':' . $response;
                                }
                            }
                    }                                         
                    elseif ($company == 'Labaih'){
                                             
                            $response = $this->Ccompany_model->LabaihArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$c_id);
                            //echo "sss";
                            //print_r($response);
                            if ($response['status'] == 200) {
                                $client_awb = $response['consignmentNo'];
                                $shipmentLabel_url = $response['shipmentLabel'];
                                 
                                $generated_pdf = file_get_contents($shipmentLabel_url);
                                    //$encoded = base64_decode($generated_pdf);
                                    //header('Content-Type: application/pdf');
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                               	$Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                array_push($succssArray, $slipNo);
                            } 
                            else {
                                $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                                 $returnArr['responseError'][] = $slipNo . ':' . $response['invalid_parameters'][0];
                            }
                    } 
                    elseif ($company == 'Clex'){
                            
                            $response = $this->Ccompany_model->ClexArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$c_id);
                            //echo $this->session->userdata('user_details')['super_id'];
                         //   print_r($response);
                            if ($response['data'][0]['cn_id']) {
                                $client_awb = $response['data'][0]['cn_id'];
                                 $label_url_new = clex_label_curl($Auth_token, $client_awb);
                                 $generated_pdf = file_get_contents($label_url_new);
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                                $fastcoolabel = base_url()."assets/all_labels/$slipNo.pdf";
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
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
                    }
                    elseif ($company=='Emdad') {
                            //print_r($counrierArr);exit;
                            
                            $response = $this->Ccompany_model->EmdadArray($ShipArr, $counrierArr, $complete_sku,$c_id);
                            //echo $response; die;
                            $response = json_decode($response, true);
                                //print_r($response);exit;
                            if($response['error']=='')
                            {
                                $generated_pdf = file_get_contents($response['awb_print_url']);
                                
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                
                                $client_awb = $response['awb'];

                                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                array_push($succssArray, $slipNo);
                            } else {
                                $returnArr['responseError'][] = $slipNo . ':' . $response['refrence_id'];
                            }
                        }
                    elseif ($company == 'Ajeek'){
                            
                            $response = $this->Ccompany_model->AjeekArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$c_id);
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
                               array_push($succssArray, $slipNo);

                            }  else{
                                    
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['description'];
                                    
                            }
                    }
                    elseif ($company == 'Aymakan'){
                            $response = $this->Ccompany_model->AymakanArray($ShipArr, $counrierArr, $Auth_token,$c_id);
                            $responseArray = json_decode($response, true);
                            //$err = $responseArray['errors']['reference'][0];
                           
                            if (empty($responseArray['errors'])) 
                            {
                                     $client_awb = $responseArray['data']['shipping']['tracking_number'];
                                     $mediaData = $responseArray['data']['shipping']['label'];
                                //****************************aymakan arrival label print cURL****************************
                                file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                                 $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************aymakan label print cURL****************************
                                 $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");
                                                             
                                $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                               array_push($succssArray, $slipNo); 
                            }   
                            else{
                                  
                                    $returnArr['responseError'][] = $slipNo . ':' . $responseArray['errors']['reference'][0];
                                    
                            }                                    
                    } 
                    elseif($company == 'Shipsy'){
						
                        $response = $this->Ccompany_model->ShipsyArray($ShipArr, $counrierArr, $Auth_token, $box_pieces1,$c_id);
                        
                        $response_array = json_decode($response, true);
                        
                        if($response_array['data'][0]['success']==1){
                            $client_awb = $response_array['data'][0]['reference_number'];
                            
                            //****************************Shipsy label print cURL****************************
                            
                            $shipsyLabel = $this->Ccompany_model->ShipsyLabelcURL($counrierArr, $client_awb);
                            
                            $mediaData = $shipsyLabel;
                           
                            file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                             $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                            array_push($succssArray, $slipNo);
                        }else{
                            
                            $returnArr['responseError'][] = $slipNo . ':' . $response_array['error']['message'];
                        }
                    }
                    elseif($company == 'Shipadelivery'){
                        
                        $response = $this->Ccompany_model->ShipadeliveryArray($ShipArr, $counrierArr, $Auth_token,$c_id);
                        
                        $response_array = json_decode($response,true);    
                          //echo "<br><pre> Response shipa = ";   print_r($response_array);
                             // die; 
                        if(empty($response_array)){
                            $returnArr['responseError'][] = $slipNo . ':' .'Receiver City Empty ';
                        }
                        else{
                            if($response_array[0]['code']== 0 )
                                {
                                     $client_awb = $response_array[0]['deliveryInfo']['reference'];                           

                                    $shipaLabel = $this->Ccompany_model->ShipaDelLabelcURL($counrierArr, $client_awb);

                                    header('Content-Type: application/pdf');

                                    file_put_contents("assets/all_labels/$slipNo.pdf", $shipaLabel);
                                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                    $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                    array_push($succssArray, $slipNo);
                                }
                                else{

                                    $returnArr['responseError'][] = $slipNo . ':' . $response_array['info'];
                                }
                        }                     
                            
                                
                    }
                    elseif($company == 'Saudi Post'){
                        $response = $this->Ccompany_model->SPArray($ShipArr, $counrierArr, $Auth_token,$c_id);
                        
                        $response = json_decode($response, true);
                                        
                        if($response['Items'][0]['Message']=='Success'){
                            $client_awb = $response['Items'][0]['Barcode'];
                            
                            $fastcoolabel = '';
                            
                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                            array_push($succssArray, $slipNo);
                        }else{
                            $errre_response = $response['Items'][0]['Message'];
                            if($errre_response==''){
                                $errre_response = $response['Message'];
                            }
                            $returnArr['responseError'][] = $slipNo . ':' . $errre_response;
                        }
                    }

                }
                else
                {
                    array_push($invalid_slipNO,$slipNo);
                    
                }
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

	

	

}
?>