<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CourierCompany_pickup extends MY_Controller {
    
    

    function __construct() {
        parent::__construct();


        $this->load->model('Ccompany_model_pickup');
        $this->load->library('form_validation');
    }

    public function BulkForwardCompanyReady($uniqueid=null, $cc_id=null,$order_type=null,$itemData=array()) {


       
        $CURRENT_TIME = date('H:i:s');
        $CURRENT_DATE = date('Y-m-d H:i:s');
        $shipmentLoopArray = $uniqueid;
        $invalid_slipNO = array();

        $comment = '';
        
       $succssArray=array();

        // print_r($postData); die;
        if (!empty($uniqueid) && !empty($cc_id)) { {
                //print_r($postData);
                //echo $postData['cc_id'];

                $counrierArr_table = $this->Ccompany_model_pickup->GetdeliveryCompanyUpdateQry($cc_id);
                //  print_r($counrierArr_table); die;
                $box_pieces = $postData['otherArr']['box_pieces'];

                $c_id = $counrierArr_table['id'];
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
                $slipNo = $uniqueid;
                // foreach ($shipmentLoopArray as $key => $slipNo) 
                {
                    $ShipArr = $this->Ccompany_model_pickup->GetSlipNoDetailsQry(trim($slipNo),$order_type);
                   // print_r($ShipArr); die;
                    $ShipArr['weight']=1;
                    $recevierArr = GetAllUserSingleData($this->session->userdata('user_details')['user_id']);
                    $recevierArr_new = GetAllUserSingleData($this->session->userdata('user_details')['user_id']);
                    $senderArr = GetSinglesellerdata($ShipArr['seller_id']);
                     $code='AT';
                      $status=6;
                    if($order_type=='return_o')
                    {
                      $recevierArr['name']=$senderArr['name'];  
                      $recevierArr['phone']=$senderArr['phone'];
                      $recevierArr['email']=$senderArr['email'];
                      $recevierArr['branch_location']=$ShipArr['city'];
                      
                       $senderArr['name']=$recevierArr_new['name'];  
                      $senderArr['phone']=$recevierArr_new['phone'];
                      $senderArr['email']=$recevierArr_new['email'];
                      $ShipArr['city']=$recevierArr_new['branch_location'];
                      
                      $code='RTC';
                      $status='7';
                    }
                    
                  //  echo $this->$order_type; die;
                    //echo '<pre>';
                   // print_r($recevierArr);
                  //  print_r($senderArr);
                    
                    //die;
                    // print_r($ShipArr); die;
                    if (!empty($ShipArr)) {
                        $sku_data = $this->Ccompany_model_pickup->Getskudetails_forward($slipNo);
                        $sku_all_names = array();
                        $sku_total = 0;
                        foreach ($sku_data as $key => $val) {
                            $skunames_quantity = $sku_data[$key]['sku'] . "*" . $sku_data[$key]['qty'];
                            $sku_total = $sku_total + $sku_data[$key]['qty'];
                            array_push($sku_all_names, $skunames_quantity);
                        }
                        $sku_all_names = implode(",", $sku_all_names);
                        if ($sku_total != 0) {
                            $complete_sku = $sku_all_names;
                        } else {
                            $complete_sku = $sku_all_names;
                        }
                        $pay_mode = 'CC';
                        $cod_amount = 0;
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
                        if ($company == 'Aramex') {
                           // echo APPPATH; die;
                            $params = $this->Ccompany_model_pickup->AramexArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr);
                          //  echo '<pre>';
//print_r($params);
                            $dataJson = json_encode($params);

                            $headers = array("Content-type:application/json");

                            $url = $api_url;

                            $response = $this->Ccompany_model_pickup->AxamexCurl($url, $headers, $dataJson);

                            $xml2 = new SimpleXMLElement($response);

                            $awb_array = json_decode(json_encode((array) $xml2), TRUE);
                            $check_error = $awb_array['HasErrors'];
                            
                           // print_r($awb_array); die;
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
                                    file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $generated_pdf);

                                    $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';

                                    
                                     $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);

                                    array_push($succssArray, $slipNo);
                                }
                            }
                        } 
                        
                        elseif ($company == 'Safearrival') {
                            $charge_items = array();
                            $Auth_response = SafeArrival_Auth_cURL($counrierArr);

                            $responseArray = json_decode($Auth_response, true);
                            $Auth_token = $responseArray['data']['id_token'];

                            $response = $this->Ccompany_model_pickup->SafeArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr,$Auth_token);

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
                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $generated_pdf);

                                $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';

                               $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);

                                array_push($succssArray, $slipNo);

                                array_push($DataArray, $slipNo);
                            } else if ($safe_response['status'] == 'error') {
                                $returnArr['responseError'][] = $slipNo . ':' . $safe_response['message'];
                            }
                        } 
                        elseif ($company == 'Esnad') {
                            //echo "ddd".$start_awb_sequence; die;
                            $esnad_awb_number = Get_esnad_awb($start_awb_sequence, $end_awb_sequence);
                            $esnad_awb_number = $esnad_awb_number - 1;
                            //echo $esnad_awb_number; die;
                            $response = $this->Ccompany_model_pickup->EsnadArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr, $Auth_token);

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
                                    file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $generated_pdf);
                                 $label_type == 1;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);

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
                            $response_ww = $this->Ccompany_model_pickup->BarqfleethArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr);

                            $response_array = json_decode($response_ww, TRUE);

                            //start

                            if ($response_array['code'] == '0000000') {
                                echo "response_array = " . $response_array['code'];
                                $errre_response = $response_array['errors'];
                                $errre_response = $response_array['message'];
                                array_push($error_array, $errre_response);
                            } else {
                                $Authorization = "eyJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo1NSwiZXhwIjo0NzMyODQ1NDA1fQ.d5sL19DdOaI9U3eLArYqugLtSkXwiwMEj3Ff3FqfKJA";
                                $request_url_label = "https://staging.barqfleet.com/api/v1/merchants/orders/airwaybill/" . $response_array['id'];
                                $headers = array("Content-type:application/json");
                                $firsthead = array(
                                    "Authorization: " . $Authorization,
                                    "Content-Type: application/json",
                                    "Accept: application/json");
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $request_url_label);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($ch, CURLOPT_HEADER, false);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                    "Content-Type: application/json",
                                    "Accept: application/json",
                                    "Authorization: " . $Authorization));

                                $response_label = curl_exec($ch);


                                $info = curl_getinfo($ch);
                                curl_close($ch);

                                $response_array = json_decode($response_label, TRUE);
                                //$client_awb = $slipNo;
                                $client_awb = $response_array['tracking_no'];
                                //$barqfleet_awb_link = $site_url . "/m_labels/" . $slipNo . ".pdf";
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");
                                // $generated_pdf = file_get_contents($response_label);
                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $response_label);
                                $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';
                                //echo "<br><br><br>link =". $fastcoolabel ;
                                //****************************barqfleet label print cURL****************************

                                 $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            }
                            //end
                        } 
                        elseif ($company == 'Makhdoom') {
                            //echo "string";;die;

                            $Auth_response = MakdoomArrival_Auth_cURL($counrierArr);


                            $responseArray = json_decode($Auth_response, true);
                            $Auth_token = $responseArray['data']['id_token'];

                            $response = $this->Ccompany_model_pickup->MakdoonArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr, $Auth_token);

                            $safe_response = json_decode($response, true);
                            //echo "safe_response = "; 
                            //print_r($safe_response);
                            //die;


                            if ($safe_response['status'] == 'success') {
                                $safe_arrival_ID = $safe_response['data']['id'];
                                $client_awb = $safe_response['data']['order_number'];

                                //****************************makdoom arrival label print cURL****************************

                                $label_response = makdoom_label_curl($client_awb, $Auth_token);
                                $safe_label_response = json_decode($label_response, true);
                                $safe_Label = $safe_label_response['data']['value'];

                                $generated_pdf = file_get_contents($safe_Label);
                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $generated_pdf);
                                $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';
                                //echo $fastcoolabel ;
                                //****************************makdoom label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                              $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            }
                        } 
                        elseif ($company == 'NAQEL') {
                            $complicated_awb = $this->Ccompany_model_pickup->NaqelArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr, $Auth_token);

                            $awb_array = json_decode(json_encode((array) $complicated_awb), TRUE);
                            //echo "<pre>";
                            // print_r($awb_array);

                            $HasError = $awb_array['HasError'];

                            $error_message = $awb_array['Message'];
                            if ($awb_array['HasError'] !== true) {

                                $client_awb = $awb_array['WaybillNo'];

                                if (!empty($client_awb)) {
                                    //echo "client_awb = ". $client_awb; echo "<br> <br/>";
                                    $user_name = $counrierArr['user_name'];
                                    $password = $counrierArr['password'];
                                    $xml_for_label = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                                        <soapenv:Header/>
                                        <soapenv:Body>
                                        <tem:GetWaybillSticker>
                                            <tem:clientInfo>
                                                <tem:ClientAddress>
                                                    <tem:PhoneNumber>' . $senderArr['phone'] . '</tem:PhoneNumber>
                                                    <tem:POBox>0</tem:POBox>
                                                    <tem:ZipCode>0</tem:ZipCode>
                                                    <tem:Fax>0</tem:Fax>
                                                    <tem:FirstAddress>' . $senderArr['address'] . '</tem:FirstAddress>
                                                    <tem:Location>' . $sender_city . '</tem:Location>
                                                    <tem:CountryCode>KSA</tem:CountryCode>
                                                    <tem:CityCode>RUH</tem:CityCode>
                                                </tem:ClientAddress>
                                                <tem:ClientContact>
                                                    <tem:Name>' . $senderArr['name'] . '</tem:Name>
                                                    <tem:Email>' . $senderArr['email'] . '</tem:Email>
                                                    <tem:PhoneNumber>' . $senderArr['phone'] . '</tem:PhoneNumber>
                                                    <tem:MobileNo>' . $senderArr['phone'] . '</tem:MobileNo>
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

                                    $url = $counrierArr['api_url'] . "?op=GetWaybillSticker";

                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_for_label);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                    $response = trim(curl_exec($ch));
                                    // print_r($response)  ;                                
                                    curl_close($ch);

                                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $response));
                                    $mediaData = $xml_data->Body->GetWaybillStickerResponse->GetWaybillStickerResult[0];

                                    if (!empty($mediaData)) {
                                        $pdf_label = json_decode(json_encode((array) $mediaData), TRUE);
                                        header('Content-Type: application/pdf');
                                        $img = base64_decode($pdf_label[0]);

                                        $savefolder = $img;
                                        file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $savefolder);

                                        //*********NAQEL arrival label print cURL****************************

                                        $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';
                                        // echo "<br/>".$fastcoolabel;
                                        //die; 
                                        //****************NAQEL label print cURL****************************
                                        $CURRENT_DATE = date("Y-m-d H:i:s");
                                        $CURRENT_TIME = date("H:i:s");

                                       $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                        array_push($succssArray, $slipNo);
                                    }
                                }
                            }
                        } 
                        elseif ($company == 'Saee') {
                            $response = $this->Ccompany_model_pickup->SaeeArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr, $Auth_token);
                            $safe_response = $response;

                            if ($safe_response['success'] == 'true') {
                                $client_awb = $safe_response['waybill'];
                                //****************************Saee arrival label print cURL****************************

                                $label_response = saee_label_curl($client_awb, $Auth_token);
                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $label_response);
                                $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';

                                //****************************Saee label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            }
                        } 
                        elseif ($company == 'Smsa') {

                            $response = $this->Ccompany_model_pickup->SMSAArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr);

                            $xml2 = new SimpleXMLElement($response);
                            $again = $xml2;
                            $a = array("qwb" => $again);

                            $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
                            
                            

                            if (preg_match('/\bFailed\b/', $complicated)) {
                                $returnArr['responseError'][] = $slipNo . ':' . $complicated;
                            } else {
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

                                    $printLabel = $this->Ccompany_model_pickup->PrintLabel($client_awb, $counrierArr['$auth_token'], $counrierArr['api_url']);


                                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $printLabel));
                                    $mediaData = $xml_data->Body->getPDFResponse->getPDFResult[0];
                                    header('Content-Type: application/pdf');
                                    $img = base64_decode($mediaData);

                                    if (!empty($mediaData)) {
                                        $savefolder = $img;

                                        file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $savefolder);

                                        $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';

                                    $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);

                                        array_push($succssArray, $slipNo);
                                    } else {
                                        array_push($error_array, $booking_id . ':' . $db);
                                    }
                                } else {
                                    $returnArr['responseError'][] = $slipNo . ':' . $response;
                                }
                            }
                        } 
                        elseif ($company == 'Labaih') {

                            $response = $this->Ccompany_model_pickup->LabaihArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr);
                            //echo "sss";
                            //print_r($response);
                            if ($response['status'] == 200) {
                                $client_awb = $response['consignmentNo'];
                                $shipmentLabel_url = $response['shipmentLabel'];
                                // $label_response= zajil_label_curl($shipmentLabel_url);
                                $generated_pdf = file_get_contents($shipmentLabel_url);
                                //$encoded = base64_decode($generated_pdf);
                                //header('Content-Type: application/pdf');
                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $generated_pdf);

                                $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';
                                $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            } else {
                                $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                                $returnArr['responseError'][] = $slipNo . ':' . $response['invalid_parameters'][0];
                            }
                        } 
                        elseif ($company == 'Clex') {

                            $response = $this->Ccompany_model_pickup->ClexArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr);
                            //echo $this->session->userdata('user_details')['super_id'];
                            //   print_r($response);
                            if ($response['data'][0]['cn_id']) {
                                $client_awb = $response['data'][0]['cn_id'];
                                $label_url_new = clex_label_curl($Auth_token, $client_awb);
                                $generated_pdf = file_get_contents($label_url_new);
                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $generated_pdf);

                                $fastcoolabel = base_url() . "assets/m_labels/$slipNo.pdf";
                               $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            } else {
                                if ($response['already_exist']) {
                                    $label_url_new = clex_label_curl($Auth_token, $response['consignment_id'][0]);

                                    $generated_pdf = file_get_contents($label_url_new);
                                    file_put_contents("assets/m_labels/$slipNo.pdf", $generated_pdf);
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['already_exist'][0] . " " . $response['consignment_id'][0];
                                } elseif ($response['origin_city'])
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['origin_city'][0];
                                elseif ($response['destination_city'])
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['destination_city'][0];
                                else
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                            }
                        } 
                        elseif ($company == 'Ajeek') {

                            $response = $this->Ccompany_model_pickup->AjeekArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr);
                            //echo $this->session->userdata('user_details')['super_id'];
                          //  print_r($response); //die;
                            // echo "order id = ". $response['contents']['order_id'];
                            if ($response['contents']['order_id']) {
                                $response['contents']['order_id'];
                                $Auth_token = $counrierArr['auth_token'];
                                $vendor_id = $counrierArr['courier_pin_no'];
                                $client_awb = $response['contents']['order_id'];

                                //****************************Saee arrival label print cURL****************************
                                $label_response = ajeek_label_curl($Auth_token, $client_awb, $vendor_id);

                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", $label_response);
                                 $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';

                                //****************************Saee label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                              $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            } else {
                                

                                $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                                $returnArr['responseError'][] = $slipNo . ':' . $response['description'];
                              // print_r($returnArr);
                            }
                        } 
                        elseif ($company == 'Aymakan') {
                            $response = $this->Ccompany_model_pickup->AymakanArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr, $Auth_token);
                            $responseArray = json_decode($response, true);
                            // echo "<br><br><br>";    print_r($responseArray);
                            // echo "<br><br><br>";  

                            if (empty($responseArray['errors'])) {
                                $client_awb = $responseArray['data']['shipping']['tracking_number'];

                                $mediaData = $responseArray['data']['shipping']['label'];
                                //****************************aymakan arrival label print cURL****************************
                                // file_put_contents("AYMAKAN_PDF/" . $awb_no . ".pdf", file_get_contents($mediaData));
                                // $label_response = saee_label_curl($client_awb, $Auth_token);
                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", file_get_contents($mediaData));
                                $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';
                                //echo "<br><br><br>";  
                                // echo $fastcoolabel; exit; 
                                //****************************aymakan label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                               $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            }
                        } 
                        elseif ($company == 'Shipsy') {

                            $response = $this->Ccompany_model_pickup->ShipsyArray($ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $recevierArr, $senderArr,$Auth_token);

                            $response_array = json_decode($response, true);

                            if ($response_array['data'][0]['success'] == 1) {
                                $client_awb = $response_array['data'][0]['reference_number'];

                                //****************************Shipsy label print cURL****************************

                                $shipsyLabel = $this->Ccompany_model_pickup->ShipsyLabelcURL($counrierArr, $client_awb);

                                $mediaData = $shipsyLabel;

                                file_put_contents(NEWAPPPATH."assets/m_labels/$slipNo.pdf", file_get_contents($mediaData));
                                $fastcoolabel = base_url() . 'assets/m_labels/' . $slipNo . '.pdf';
                             $label_type == 0;
                                    
                                    $updateArray = array('code' => $code, 'pstatus' => $status, 'assign_to' => 0, '3pl_awb' => $client_awb, '3pl_name' => $company, '3pl_label' => $fastcoolabel, '3pl_date' => $CURRENT_DATE, 'label_type' => $label_type);


                                    $Update_data = $this->Ccompany_model_pickup->Update_Shipment_Status($updateArray,$slipNo,$order_type,$itemData);
                                array_push($succssArray, $slipNo);
                            } else {

                                $returnArr['responseError'][] = $slipNo . ':' . $response_array['error']['message'];
                            }
                        }
                    } else {
                        array_push($invalid_slipNO, $slipNo);
                    }
                }
            }
        }
        $return['invalid_slipNO'] = $invalid_slipNO;
        $return['Error_msg'] = $returnArr['responseError'];
        //$return['Success_msg']=$returnArr['successAwb'];
        $return['Success_msg'] = $succssArray;


        return $return;
    }

}

?>