<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ccompany_auto_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

      
    public function GetSlipNoDetailsQry($slip_no=null,$super_id) {
        
        $this->db->where('super_id', $super_id);
       
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('slip_no', $slip_no);
        $this->db->where('deleted', 'N');
       // $this->db->where('status', 'Y');
        $query = $this->db->get();
		//echo $this->db->last_query();exit;
      return  $query->row_array();
    }
      
    
    public function GetdeliveryCompanyUpdateQry($cc_id=null,$super_id=null) {
        
         $this->db->where('super_id', $super_id);
        $this->db->where('id', $cc_id);
        $this->db->select('*');
        $this->db->from('courier_company');
        $this->db->where('deleted', 'N');
       // $this->db->where('forwarded', 0);
        
        //$this->db->where_not_in('code', 'RTC','DL','POD','C');
        $this->db->where('status', 'Y');
        $this->db->order_by("company");
        $query = $this->db->get();
       // echo $this->db->last_query();
      return  $query->row_array();
    }
	
	
	
	public function getdestinationfieldshow($id=null,$field=null,$super_id){
	
                
		 $sql ="SELECT $field FROM country where id='$id'";
		$query = $this->db->query($sql);
		$result=$query->row_array();
		return $result[$field];
	}
	
	public function Getskudetails_forward($slip_no=null,$super_id)	{
            $this->db->where('super_id', $super_id);

	$this->db->select('sku,description,piece,cod');
	$this->db->from('diamention_fm');
	$this->db->where('slip_no',$slip_no);
	$query = $this->db->get();
	return $query->result_array();
	}
	
	public function GetshipmentUpdate_forward(array $data,$awb=null){
           // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		 $this->db->update('shipment_fm',$data,array('slip_no'=>$awb));
                 $this->db->query("UPDATE `zone_list_fm` SET `todayCount`=todayCount+1 where cc_id='".$data['frwd_company_id']."'");
              // echo $this->db->last_query(); die;
	}
	
	public function GetstatuInsert_forward(array $data){
            
		$this->db->insert('status_fm',$data);
		//echo $this->db->last_query();
        }
	
	public function AramexArray(array $ShipArr, array $counrierArr, $complete_sku=null, $pay_mode=null, $CashOnDeliveryAmount=null, $services=null,$super_id=null){
		$sender_city = $this->getdestinationfieldshow($ShipArr['origin'], 'aramex_city',$super_id);
		$reciever_city = $this->getdestinationfieldshow($ShipArr['destination'], 'aramex_city',$super_id);
		$date = (int) microtime(true) * 1000;
		$params = array(
			'ClientInfo' =>array(
				'UserName' => $counrierArr['user_name'],
				'Password' => $counrierArr['password'],
				'Version' => 'v1',
				'AccountNumber' => $counrierArr['courier_account_no'],
				'AccountPin' => $counrierArr['courier_pin_no'],
				'AccountEntity' => 'RUH',
				'AccountCountryCode' => 'SA'
			),
			'LabelInfo' => array("ReportID" => 9729, "ReportType" => "URL"),
			'Shipments' =>array(
				0 =>array(
					'Reference1' => '',
					'Reference2' => '',
					'Reference3' => '',
					'Shipper' =>array(
						'Reference1' => $ShipArr['booking_id'],
						'Reference2' => '',
						'AccountNumber' => $counrierArr['courier_account_no'],
						'PartyAddress' =>array(
							'Line1' => $ShipArr['sender_address'],
							'Line2' => '',
							'Line3' => '',
							'City' => $sender_city,
							'StateOrProvinceCode' => '',
							'PostCode' => '0000',
							'CountryCode' => 'SA',
							'Longitude' => 0,
							'Latitude' => 0,
							'BuildingNumber' => NULL,
							'BuildingName' => NULL,
							'Floor' => NULL,
							'Apartment' => NULL,
							'POBox' => NULL,
							'Description' => NULL,
						),
						'Contact' =>array(
							'Department' => '',
							'PersonName' => $ShipArr['sender_name'],
							'Title' => '',
							'CompanyName' => $ShipArr['sender_name'],
							'PhoneNumber1' => $ShipArr['sender_phone'],
							'PhoneNumber1Ext' => '',
							'PhoneNumber2' => '',
							'PhoneNumber2Ext' => '',
							'FaxNumber' => '',
							'CellPhone' => $ShipArr['sender_phone'],
							'EmailAddress' => $ShipArr['sender_email'],
							'Type' => '',
						),
					),
					'Consignee' =>array(
						'Reference1' => '',
						'Reference2' => '',
						'AccountNumber' => '',
						'PartyAddress' =>array(
							'Line1' => $ShipArr['reciever_address'],
							'Line2' => '',
							'Line3' => '',
							'City' => $reciever_city,
							'StateOrProvinceCode' => '',
							'PostCode' => '0000',
							'CountryCode' => 'SA',
							'Longitude' => 0,
							'Latitude' => 0,
							'BuildingNumber' => '',
							'BuildingName' => '',
							'Floor' => '',
							'Apartment' => '',
							'POBox' => NULL,
							'Description' => '',
							),
						'Contact' =>array(
							'Department' => '',
							'PersonName' => $ShipArr['reciever_name'],
							'Title' => '',
							'CompanyName' => $ShipArr['reciever_name'],
							'PhoneNumber1' => $ShipArr['reciever_phone'],
							'PhoneNumber1Ext' => '',
							'PhoneNumber2' => '',
							'PhoneNumber2Ext' => '',
							'FaxNumber' => '',
							'CellPhone' => $ShipArr['reciever_phone'],
							'EmailAddress' => $ShipArr['reciever_email'],
							'Type' => '',
						),
					),
					'ThirdParty' =>array(
						'Reference1' => '',
						'Reference2' => '',
						'AccountNumber' => '',
						'PartyAddress' =>array(
							'Line1' => '',
							'Line2' => '',
							'Line3' => '',
							'City' => '',
							'StateOrProvinceCode' => '',
							'PostCode' => '',
							'CountryCode' => '',
							'Longitude' => 0,
							'Latitude' => 0,
							'BuildingNumber' => NULL,
							'BuildingName' => NULL,
							'Floor' => NULL,
							'Apartment' => NULL,
							'POBox' => NULL,
							'Description' => NULL,
						),
						'Contact' =>array(
							'Department' => '',
							'PersonName' => '',
							'Title' => '',
							'CompanyName' => '',
							'PhoneNumber1' => '',
							'PhoneNumber1Ext' => '',
							'PhoneNumber2' => '',
							'PhoneNumber2Ext' => '',
							'FaxNumber' => '',
							'CellPhone' => '',
							'EmailAddress' => '',
							'Type' => '',
						),
					),
					'ShippingDateTime' => "/Date(" . $date . ")/",
					'DueDate' => "/Date(" . $date . ")/",
					'Comments' => '',
					'PickupLocation' => '',
					'OperationsInstructions' => '',
					'AccountingInstrcutions' => '',
					'Details' =>array(
						'Dimensions' => NULL,
						'ActualWeight' =>array(
							'Unit' => 'KG',
							//'Value' => $weight,
							'Value' => '1',
						),
						'ChargeableWeight' => NULL,
						'DescriptionOfGoods' => $complete_sku,
						'GoodsOriginCountry' => 'SA',
						'NumberOfPieces' => 1,
						'ProductGroup' => 'DOM',
						'ProductType' => 'ONP',
						'PaymentType' => $pay_mode,
						'PaymentOptions' => "",
						'CustomsValueAmount' => NULL,
						'CashOnDeliveryAmount' => $CashOnDeliveryAmount,
						'InsuranceAmount' => NULL,
						'CashAdditionalAmount' => NULL,
						'CashAdditionalAmountDescription' => '',
						'CollectAmount' => NULL,
						'Services' => $services,
						'Items' =>array(),
					),
					'Attachments' =>array(),
					'ForeignHAWB' => $ShipArr['slip_no'],
					'TransportType ' => 0,
					'PickupGUID' => '',
					'Number' => NULL,
					'ScheduledDelivery' => NULL,
				),
			),
			'Transaction' =>array(
				'Reference1' => '',
				'Reference2' => '',
				'Reference3' => '',
				'Reference4' => '',
				'Reference5' => '',
			)
		);
		
		return $params;
	}
	
	public function AxamexCurl($url = null, array $headers, $dataJson = null, $c_id = null, array $ShipArr,$super_id=null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $xml2 = new SimpleXMLElement($response);
        $awb_array = json_decode(json_encode((array) $xml2), TRUE);
        $logresponse =   json_encode($awb_array);
        $successres = $awb_array['HasErrors'];
       
            if($successres == 'true') 
            {
                $successstatus  = "Fail";
            }else {
                $successstatus  = "Success";
            }
            
        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);

        return $awb_array;
    }
	
	public function Update_Shipment_Status($slipNo=null, $client_awb=null, $CURRENT_TIME=null, $CURRENT_DATE=null, $company=null, $comment=null, $fastcoolabel=null,$c_id=null,$super_id=null){
		
             if($company=='Esnad' || $company=='Labaih' || $company=='Clex')
             {
            $label_type='1';
             }
        else
        {
            $label_type='0';
        }
		$updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type);
                
                

		///echo "<br/><pre>";
		//print_r($updateArr);


		$this->GetshipmentUpdate_forward($updateArr, $slipNo,$super_id);
		
		//$returnArr['successAwb'][] = 'AWB No.' . $slipNo . ' forwarded to ARAMEX';
		
		$details = 'Forwarded to ' . $company;
		$statusArr = array(
			'slip_no' => $slipNo,
			'new_location' => $this->session->userdata('user_details')['city'],
			'new_status' => 10,
			'pickup_time' => $CURRENT_TIME,
			'pickup_date' => $CURRENT_DATE,
			'Activites' => 'Forward to Delivery Station',
			'Details' => $details,
			'entry_date' => $CURRENT_DATE,
			'user_id' => $super_id,
			'user_type' => 'fulfillment',
			'comment' => $comment,
			'code' => 'FWD',
			'super_id' => $super_id,
		);
		$this->GetstatuInsert_forward($statusArr);
		//send_message($slipNo);
		
		return true;
	}
	
	public function SafeArray(array $ShipArr, array $counrierArr, $complete_sku=null, $Auth_token=null,$super_id=null){
		$sender_city_safe = $this->getdestinationfieldshow($ShipArr['origin'], 'safe_arrival',$super_id);
		$receiver_city_safe = $this->getdestinationfieldshow($ShipArr['destination'], 'safe_arrival',$super_id);
		
		$API_URL = $counrierArr['api_url'];

		$sender_data = array(
			"address_type" => "residential",
			"name" => $ShipArr['sender_name'],
			"email" => $ShipArr['sender_email'],
			"street" => $ShipArr['sender_address'],
			"city" => array(
				"id" => $sender_city_safe
			),
			"phone" => $ShipArr['sender_phone']
		);
		$recipient_data = array(
			"address_type" => "residential",
			"name" => $ShipArr['reciever_name'],
			"email" => $ShipArr['reciever_email'],
			"street" => $ShipArr['reciever_address'],
			"city" => array(
				"id" => $receiver_city_safe
			),
			"phone" => $ShipArr['reciever_phone']
		);
		$dimensions = array(
			"weight" => $ShipArr['weight']
		);
		$package_type = array(
			"courier_type" => 'IN_5_DAYS'
		);
		$charge_items = array(
			array(
				"paid"=> false,
				"charge" => $ShipArr['total_cod_amt'],
				"charge_type" => $ShipArr['mode']
			),
			array(
				"paid"=> false,
				"charge" => 0,
				"charge_type" => 'service_custom'
			)
		);

		$param = array(
			"sender_data" => $sender_data,
			"recipient_data" => $recipient_data,
			"dimensions" => $dimensions,
			"package_type" => $package_type,
			"charge_items" => $charge_items,
			"recipient_not_available" => "do_not_deliver",
			"payment_type" => "cash",
			"payer" => "recipient",
			//"parcel_value" => 100,
			"fragile" => true,
			"note" => $complete_sku,
			"piece_count" => 1,  //$ShipArr['pieces'],
			"force_create" => true,
			"reference_id" => $ShipArr['slip_no']
		);

		$header = array(
			"Authorization" => "Bearer " . $responseArray['data']['id_token'],
			"Content-Type" => "application/json",
			"Accept" => "application/json"
		);

		$dataJson = json_encode($param);

		$response = send_data_to_safe_curl($dataJson, $Auth_token, $API_URL);
		$logresponse =   json_encode($response);  
        $successres = $safe_response['status'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres == "success") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

          $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
		return $response;
	}
	
	public function EsnadArray(array $ShipArr, array $counrierArr, $esnad_awb_number=null, $complete_sku=null, $Auth_token=null,$super_id=null,$c_id=null){
		$receiver_city = $this->getdestinationfieldshow($ShipArr['destination'], 'esnad_city',$super_id);
		 $sender_city = $this->getdestinationfieldshow($ShipArr['origin'], 'esnad_city',$super_id); 
		$declared_charge = $ShipArr['total_cod_amt'];
		$cod_amount = $ShipArr['total_cod_amt'];
		
		if ($ShipArr['mode'] == 'COD') {
			$pay_mode = "COD";
			$declared_charge = 0;
		} else {
			$pay_mode = "PP";
			$cod_amount = 0;
		}
		
		$comp_api_url = $counrierArr['api_url'];
		$Auth_token = $counrierArr['auth_token'];
						
		
		$param = array(
			array(
				"esnadAwbNo" => $esnad_awb_number,
				"clientOrderNo" => $ShipArr['slip_no'],
				"orderType" => "DOM",
				"deliveryService" => "EXP",
				"consignor" => $ShipArr['sender_name'],
				"pickupAddress" => $ShipArr['sender_address'],
				"pickupContact" => $ShipArr['sender_phone'],
				"originCity" => $sender_city,
				"originCountry" => "SA",
				"consignee" => $ShipArr['reciever_name'],
				"deliveryAddress" => $ShipArr['reciever_address'],
				"deliveryContact" => $ShipArr['reciever_phone'],
				"destCity" => $receiver_city,
				"destCountry" => "SA",
				"returnName" => "",
				"returnAddress" => "", //return address
				"returnPincode" => "", // return zip
				"returnContact" => "", // return contact
				"returnCity" => "", // return city
				"returnCountry" => "", // return country
				"productDescription" => $complete_sku,
				"paymentMode" => $pay_mode,
				"amountToCollect" => $cod_amount,
				"pcs" => 1,//$pieces,
				"declaredValue" => $declared_charge,
				"packageWeight" => $ShipArr['weight'],
				"productDetails" => array(
					"productHscode"=>$complete_sku,
				)
			)
		);
		
		$dataJson = json_encode($param);
		$headers = array(
			"Content-Type: application/json",
			"token: $Auth_token"
		);
		$response = send_data_to_curl($dataJson, $comp_api_url, $headers);
		$logresponse =   json_encode($response); 
        $responseArray = json_decode($response, true); 
        $successres = $responseArray['code'];
        if($successres  == "1000") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);

		return $response;
	}
        
        
    public function LabaihArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$super_id=null,$c_id=null) {
        $receiver_city = $this->getdestinationfieldshow($ShipArr['destination'], 'labaih',$super_id);
        $sender_city = $this->getdestinationfieldshow($ShipArr['origin'], 'labaih',$super_id);
        $lat = $this->getdestinationfieldshow($ShipArr['origin'], 'latitute',$super_id);
        $lang = $this->getdestinationfieldshow($ShipArr['origin'], 'longitute',$super_id);
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'COD';
            // $cod_amount=0;
        } else {
            $cod_collection_mode = 'PREPAID';
            $cod_amount = 0;
        }
       //echo $box_pieces; die;
        if($box_pieces>0)
        {
          $pieces=$box_pieces;
        }
        else
        {
          $pieces= $ShipArr['pieces'];
        }

        
        $comp_api_url = $counrierArr['api_url'];
        
        $pickupDate=date("Y-m-d");
        $deliveryDate=date('Y-m-d',strtotime($pickupDate.'+ 2 days'));

        $Data_array=
     array(
        'api_key'=>$counrierArr['auth_token'], /***/
        'pickupDate'=>$pickupDate, /***/
        'deliveryDate'=>$deliveryDate, /***/
        'customerOrderNo'=>$ShipArr['slip_no'], /***/
        'noOfPieces'=>$pieces,
        'weightKg'=>$ShipArr['weight'],
        'dimensionsCm'=>$complete_sku,
        'itemDescription'=>$ShipArr['status_describtion'],
        'paymentMethod'=>$cod_collection_mode,
        'paymentAmount'=>$cod_amount,
        'consigneeName'=>$ShipArr['reciever_name'], /***/
        'consigneeEmail'=>$ShipArr['reciever_email'],
        'consigneeMobile'=>$ShipArr['reciever_phone'],
        'consigneePhone'=>$ShipArr['reciever_phone'],  /***/
        'consigneeCity'=>$receiver_city, /***/
        'consigneeCommunity'=>$receiver_city,
        'consigneeAddress'=>$ShipArr['reciever_address'], /***/
        'consigneeFlatFloor'=>'',
        'consigneeLatLong'=>$ShipArr['dest_lat'].','.$ShipArr['dest_lng'],
        'consigneeSplInstructions'=>$ShipArr['status_describtion'],
        'store'=>$ShipArr['sender_name'], /***/
        'shipperName'=>$ShipArr['sender_name'], /***/
        'shipperMobile'=>$ShipArr['sender_phone'], /***/
        'shipperEmail'=>$ShipArr['sender_email'],
        'shipperCity'=>$sender_city,
        'shipperDistrict'=>$sender_city,
        'shipperAddress'=>$ShipArr['sender_address'],
        'shipperLatLong'=>$lat.','.$lang,
        
    );
//print_r($Data_array); die;
  $headers = array(
            "Content-type:application/x-www-form-urlencoded",
           "cache-control: no-cache"
           );
  
  
   $dataJson=http_build_query($Data_array);
  // echo $dataJson;  die;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        curl_close($ch);
      //  print_r($response);
        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['status'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres == 200) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

         $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response_array;
    }

    public function ClexArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$super_id=null,$c_id=null)
    {
        $receiver_city = $this->getdestinationfieldshow($ShipArr['destination'], 'clex',$super_id);
        $sender_city = $this->getdestinationfieldshow($ShipArr['origin'], 'clex',$super_id);
        $comp_api_url = $counrierArr['api_url'];
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] == 'COD') {
                            $billing_type = 'COD';
                            // $cod_amount=0;
                        } else {
                            $billing_type = 'PREPAID';
                            $cod_amount = 0;
                        }
                        if ($ShipArr['weight'] == 0) {
                            $weight = 1;
                        }
                        else
                        {
                            $weight=$ShipArr['weight'];
                        }
                        
                        
                         if($box_pieces>0)
        {
          $pieces=$box_pieces;
        }
        else
        {
          $pieces= $ShipArr['pieces'];
        }
                        
                        $request_data = array(
                            'shipment_reference_number' => $ShipArr['slip_no'],
                            'shipment_type' => 'delivery',
                            'billing_type' => $billing_type,
                            'collect_amount' => $cod_amount,
                            'primary_service' => 'delivery',
                            'secondary_service' => '',
                            'item_value' => '',
                            'consignor' => $ShipArr['sender_name'],
                            'consignor_email' => $ShipArr['sender_email'],
                            'origin_city' => $sender_city,
                            'origin_area_new' => '',
                            'consignor_street_name' => $ShipArr['sender_address'],
                            'consignor_building_name' => '',
                            'consignor_address_house_appartment' => '',
                            'consignor_address_landmark' => '',
                            'consignor_country_code' => '+966',
                            'consignor_phone' => remove_phone_format($ShipArr['sender_phone']),
                            'consignor_alternate_country_code' => '',
                            'consignor_alternate_phone' => '',
                            'consignee' => $ShipArr['reciever_name'],
                            'consignee_email' => $ShipArr['receiver_email'],
                            'destination_city' => $receiver_city,
                            'destination_area_new' => '',
                            'consignee_street_name' => $ShipArr['reciever_address'],
                            'consignee_building_name' => '',
                            'consignee_address_house_appartment' => '',
                            'consignee_address_landmark' => '',
                            'consignee_country_code' => '+966',
                            'consignee_phone' => remove_phone_format($ShipArr['reciever_phone']),
                            'consignee_alternate_country_code' => '',
                            'consignee_alternate_phone' => '',
                            'pieces_count' => $pieces,
                            'order_date' => date('d-m-Y'),
                            'commodity_description' => $complete_sku,
                            'pieces' => array(array(
                                    'weight_actual' => '10',
                                    'volumetric_width' => $weight,
                                    'volumetric_height' => $weight,
                                    'volumetric_depth' => $weight,
                                ))
                        );
                       //  echo "<pre>";print_r($request_data);echo "</br>";exit;
                        $dataJson = json_encode($request_data);
                        $access_token=$counrierArr['auth_token'];
                       
                        $headers = array(
                            "Content-type:application/json",
                            "Access-token:$access_token");
                         // echo "<pre>";print_r($headers);echo "</br>";exit;

                        $ch = curl_init($comp_api_url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

                        $response = curl_exec($ch);
                        	//echo "<pre>";print_r($response);echo "</br>";//exit;
                        curl_close($ch);
                        $response_array = json_decode($response, true);
                         $response_array = json_decode($response, true);
					        $logresponse =   json_encode($response_array);  
					        $successres = $response_array['error'];
					        echo "<pre>"; print_r($response_array)   ;    //die;
					        if($successres == 'false') 
					        {
					            $successstatus  = "Success";
					        }else {
					            $successstatus  = "Fail";
					        }

					         $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
					                        return $response_array;
        
    }
 

public function BarqfleethArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null, $c_id = null, $super_id = null) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'city');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'city');
        $lat = getdestinationfieldshow($ShipArr['origin'], 'latitute');
        $lang = getdestinationfieldshow($ShipArr['origin'], 'longitute');
        $declared_charge = $ShipArr['total_cod_amt'];

        //echo "sadsdsad"; print_r($ShipArr); 
        //die;

        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'COD';
            $cod_amount = 0;
        } else {
            $cod_collection_mode = 'PREPAID';
            $cod_amount = 0;
        }

        $comp_api_url = $counrierArr['api_url'];

        $pickupDate = date("Y-m-d");
        $deliveryDate = date('Y-m-d', strtotime($pickupDate . '+ 2 days'));

        $params = array(
            "email" => $counrierArr['user_name'],
            "password" => $counrierArr['password']
        );

        $data = json_encode($params);
        $request_url = "https://staging.barqfleet.com/api/v1/merchants/login";
        $firstheader = array(
            "Authorization: " . $counrierArr['auth_token'],
            "Content-Type: application/json",
            "Accept: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $firstheader);
        $response = curl_exec($ch);
        curl_close($ch);

        $response2 = json_decode($response, true);
        $Authorization = $response2['token'];
        $params = array(
            "invoice_total" => $ShipArr['total_cod_amt'],
            "payment_type" =>$cod_amount,
            "shipment_type" => "instant_delivery",
            "hub_id" => 240,
            "hub_code" => "FASTCOO",
            "merchant_order_id" => $ShipArr['slip_no'],
            "customer_details" => array(
                "first_name" => $ShipArr['sender_name'],
                "last_name" => "",
                "country" => "Saudi Arabia",
                "city" => $receiver_city,
                "mobile" => $ShipArr['reciever_phone'],
                "address" => $ShipArr['reciever_address']
            ),
            "products" => array(
                array(
                    "serial_no" => $sku_name,
                    "qty" => 1,
                    "sku" => '',
                    "color" => '',
                    "brand" => '',
                    "name" => '',
                    "price" => ''
                )
            ),
            "destination" => array(
                "latitude" => '',
                "longitude" => ''
            )
        );

        $dataJson = json_encode($params);

        //echo "<pre>"; print_r($params); 
        //die; 

        $headers = array("Content-type:application/json");
        $url = "https://staging.barqfleet.com/api/v1/merchants/orders";
        $firstheaderr = array(
            "Authorization: " . $Authorization,
            "Content-Type: application/json",
            "Accept: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $firstheaderr);
        $response_ww = curl_exec($ch);
       curl_close($ch);
        $logresponse =   json_encode($response_ww);  
        $response_array = json_decode($response_ww, TRUE); 
        $successres = $response_array['code'];
    
        if($successres != '') 
        {
            $successstatus  = "Fail";
        }else {
            $successstatus  = "Success";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response_ww;
    }

    public function MakdoonArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null,$super_id,$c_id) {
        $sender_city = $this->getdestinationfieldshow($ShipArr['origin'], 'makhdoom',$super_id);
        $receiver_city = $this->getdestinationfieldshow($ShipArr['destination'], 'makhdoom',$super_id);
        $API_URL = $counrierArr['api_url'];

        $sender_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['sender_name'],
            "email" => $ShipArr['sender_email'],
            'apartment' => '',
            'building' => '',
            "street" => $ShipArr['sender_address'],
            "city" => array(
                "code" => $sender_city
            ),
            'country' => array(
                    'id' => 191,
                ),
            "phone" => $ShipArr['sender_phone']
        );
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => $ShipArr['reciever_email'],
            "street" => $ShipArr['reciever_address'],
            "city" => array(
                "code" => $receiver_city
            ),
            'country' => array(
                    'id' => 191,
                ),
            "phone" => $ShipArr['reciever_phone'],
            'landmark' => '',
        );
        $dimensions = array(
            "weight" => $ShipArr['weight'],
            'width' => 0,
            'length' => 0,
            'height' => 0,
            'unit' => '',
            'domestic' => true,
        );
        $package_type = array(
            "courier_type" => 'EXPRESS_DELIVERY'
        );
        $charge_items = array(
            array(
                "paid" => false,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode'],
                'payer' => 'sender',
            ),
            array(
                "paid" => false,
                "charge" => 0,
                "charge_type" => 'service_custom'
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => "cash",
            "payer" => "recipient",
            //"parcel_value" => 100,
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => 1, //$ShipArr['pieces'],
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );
          // echo '<pre>';
           //print_r($param);
            // die;
        $dataJson = json_encode($param);

        $response = send_data_to_makdoom_curl($dataJson, $Auth_token, $API_URL);
         $logresponse =   json_encode($response);  
        $successres = $response['status'];
       // echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres == 'success') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
            
        return $response;
    }

	public function SMSAArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$super_id) {
        $receiver_city = $this->getdestinationfieldshow($ShipArr['destination'], 'samsa_city',$super_id,$c_id);
        $sender_city = $this->getdestinationfieldshow($ShipArr['origin'], 'samsa_city',$super_id);
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
       

       // print_r($ShipArr); exit;
        if ($ShipArr['mode'] == 'COD') {
            $codValue = $cod_amount;
        } else {
            $codValue = 0;
        }
        if ($complete_sku == '') {
            $complete_sku = 'Goods';
        }
        $comp_api_url = $counrierArr['api_url'];

        $SMSAXML = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <addShip xmlns="http://track.smsaexpress.com/secom/">
                  <passKey>' . $counrierArr['auth_token'] . '</passKey>
                  <refNo>' . $ShipArr['slip_no'] . '</refNo>
                  <sentDate>' . date('d/m/Y') . '</sentDate>
                  <idNo>' . $ShipArr['booking_id'] . '</idNo>
                  <cName>' . $ShipArr['reciever_name'] . '</cName>
                  <cntry>KSA</cntry>
                  <cCity>' . $receiver_city . '</cCity>
                  <cZip>' . $ShipArr['sender_zip'] . '</cZip>
                  <cPOBox>45</cPOBox>
                  <cMobile>' . $ShipArr['reciever_phone'] . '</cMobile>
                  <cTel1>' . $ShipArr['reciever_phone'] . '</cTel1>
                  <cTel2>' . $ShipArr['reciever_phone'] . '</cTel2>
                  <cAddr1>' . htmlentities(strip_tags($ShipArr['reciever_address'], ENT_COMPAT, 'UTF-8')) . '</cAddr1>
                  <cAddr2>' . htmlentities(strip_tags($ShipArr['reciever_address'], ENT_COMPAT, 'UTF-8')) . '</cAddr2>
                  <shipType>DLV</shipType>
                  <PCs>' . $ShipArr['pieces'] . '</PCs>
                  <cEmail>' . $ShipArr['reciever_email'] . '</cEmail>
                  <carrValue>2</carrValue>
                  <carrCurr>2</carrCurr>
                  <codAmt>' . $codValue . '</codAmt>
                  <weight>' . $ShipArr['weight'] . '</weight>
                  <custVal>2</custVal>
                  <custCurr>3</custCurr>
                  <insrAmt>34</insrAmt>
                  <insrCurr>3</insrCurr>
                  <itemDesc>' . htmlentities(strip_tags($ShipArr['status_describtion'], ENT_COMPAT, 'UTF-8')) . '</itemDesc>
                  <sName>' . htmlentities(strip_tags($ShipArr['sender_name'])) . '</sName>
                  <sContact>' . $ShipArr['sender_name'] . '</sContact>
                  <sAddr1>' . htmlentities(strip_tags($ShipArr['sender_address'], ENT_COMPAT, 'UTF-8')) . '</sAddr1>
                  <sAddr2>' . htmlentities(strip_tags($ShipArr['sender_address'], ENT_COMPAT, 'UTF-8')) . '</sAddr2>
                  <sCity>' . $sender_city . '</sCity>
                  <sPhone>' . $ShipArr['sender_phone'] . '</sPhone>
                  <sCntry>KSA</sCntry>
                  <prefDelvDate>20/02/2019</prefDelvDate>
                  <gpsPoints>2</gpsPoints>
                </addShip>
                 <getPDF xmlns="http://track.smsaexpress.com/secom/">
                  <awbNo>' . $pdfawb . '</awbNo>
                  <passKey>' . $counrierArr['auth_token'] . '</passKey>
                </getPDF>
            </soap:Body>
        </soap:Envelope>';


        $headers = array(
            "Content-type: text/xml;charset=utf-8",
            "Accept: application/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://track.smsaexpress.com/secom/addShip",
            "Content-length: " . strlen($SMSAXML),
        );
        $cookiePath = tempnam('/tmp', 'cookie');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $SMSAXML);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

       
        $check = $response;
        $respon = trim($check);
        $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $response);

       
        $xml2 = new SimpleXMLElement($respon);
        $again = $xml2;
        $a = array("qwb" => $again);

        $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);

        if (preg_match('/\bFailed\b/', $complicated)) {
            $ret = $complicated;

        } 
             if (empty($ret)) 
            {
                $successstatus  = "Success";
            }else {
                $successstatus  = "Fail";
            }


         $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$super_id);
        return $respon;
    }

  	public function ZajilArray($ShipArr, $counrierArr, $complete_sku, $c_id, $super_id) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'zajil');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'zajil');
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'CASH';
            // $cod_amount=0;
        } else {
            $cod_collection_mode = '';
            $cod_amount = 0;
        }


        $comp_api_url = $counrierArr['api_url'];

        $data_request = array(
            'consignments' => array(
                array(
                    'customer_code' => $counrierArr['user_name'],
                    'reference_number' => '',
                    'load_type' => 'NON-DOCUMENT',
                    'description' => $complete_sku,
                    'service_type_id' => 'B2B',
                    'cod_favor_of' => '',
                    'dimension_unit' => 'cm',
                    'length' => '',
                    'width' => '',
                    'height' => '',
                    'weight_unit' => 'kg',
                    'weight' => $ShipArr['weight'],
                    'declared_value' => $declared_charge,
                    'declared_price' => '',
                    'cod_amount' => $cod_amount,
                    'cod_collection_mode' => $cod_collection_mode,
                    'prepaid_amount' => '',
                    'num_pieces' => $ShipArr['pieces'],
                    'customer_reference_number' => $ShipArr['slip_no'],
                    'is_risk_surcharge_applicable' => true,
                    'origin_details' =>
                    array(
                        'name' => $ShipArr['sender_name'],
                        'phone' => $ShipArr['sender_phone'],
                        'alternate_phone' => '',
                        'address_line_1' => $ShipArr['sender_address'],
                        'address_line_2' => '',
                        'city' => $sender_city,
                        'state' => ''
                    ),
                    'destination_details' =>
                    array(
                        'name' => $ShipArr['reciever_name'],
                        'phone' => $ShipArr['reciever_phone'],
                        'alternate_phone' => '',
                        'address_line_1' => $ShipArr['reciever_address'],
                        'address_line_2' => '',
                        'city' => $receiver_city,
                        'state' => ''
                    ),
                    'pieces_detail' =>
                    array(
                        'description' => $description,
                        'declared_value' => $declared_charge,
                        'weight' => $ShipArr['weight'],
                        'height' => '',
                        'length' => '',
                        'width' => ''
                    )
        )));
        $comp_auth_token = $counrierArr['auth_token'];
        $headers = array(
            "Content-type:application/json",
            "api-key:$comp_auth_token");

        $dataJson = json_encode($data_request);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        curl_close($ch);
        //print_r($response);
        $response_array = json_decode($response, true);
          $logresponse =   json_encode($response_array);  
        $successres = $response_array['data'][0]['success'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($response['status'] == 'OK' && $successres == true) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

         $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response_array;
    }
	public function NaqelArray(array $ShipArr, array $counrierArr, $complete_sku = null, $box_pieces = null, $Auth_token = null, $c_id = null, $super_id = null) 
	 {
	        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'naqel_city_code');
	        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'naqel_city_code');
	            if ($ShipArr['mode'] == 'CC') {
	                    $BillingType = 1;
	                } elseif ($ShipArr['mode'] == "COD") {
	                    $BillingType = 5;
	                }
	             $API_URL = $counrierArr['api_url'];    
	             $user_name = $counrierArr['user_name'];    
	             $password = $counrierArr['password'];
	            $xml_new = '<?xml version="1.0" encoding="utf-8"?>
	                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
	                        <soapenv:Header/>
	                        <soapenv:Body>
	                            <tem:CreateWaybill>
	                                <tem:_ManifestShipmentDetails>
	                                    <tem:ClientInfo>
	                                    <tem:ClientAddress>
	                                        <tem:PhoneNumber>'.$ShipArr['sender_phone'].'</tem:PhoneNumber>
	                                        <tem:POBox></tem:POBox>
	                                        <tem:ZipCode></tem:ZipCode>
	                                        <tem:Fax></tem:Fax>
	                                        <tem:FirstAddress>'.$ShipArr['sender_address'].'</tem:FirstAddress>
	                                        <tem:Location>' . $sender_city . '</tem:Location>
	                                        <tem:CountryCode>KSA</tem:CountryCode>
	                                        <tem:CityCode>' . $sender_city . '</tem:CityCode>
	                                    </tem:ClientAddress>

	                                    <tem:ClientContact>
	                                        <tem:Name>' . $ShipArr['sender_name'] . '</tem:Name>
	                                        <tem:Email>' . $ShipArr['sender_email'] . '</tem:Email>
	                                        <tem:PhoneNumber>'.$ShipArr['sender_phone'] . '</tem:PhoneNumber>
	                                        <tem:MobileNo>' . $ShipArr['sender_phone'] . '</tem:MobileNo>
	                                    </tem:ClientContact>

	                                    <tem:ClientID>'.$user_name.'</tem:ClientID>
	                                    <tem:Password>'.$password.'</tem:Password>
	                                    <tem:Version>9.0</tem:Version>
	                                    </tem:ClientInfo>

	                                    <tem:ConsigneeInfo>
	                                    <tem:ConsigneeName>' .$ShipArr['reciever_name'].'</tem:ConsigneeName>
	                                    <tem:Email>' . $ShipArr['reciever_email'] . '</tem:Email>
	                                    <tem:Mobile>' . $ShipArr['reciever_phone'] . '</tem:Mobile>
	                                    <tem:PhoneNumber>' . $ShipArr['reciever_phone'] . '</tem:PhoneNumber>
	                                    <tem:Address>' .$receiver_city . '</tem:Address>
	                                    <tem:CountryCode>KSA</tem:CountryCode>
	                                    <tem:CityCode>' . $receiver_city .'</tem:CityCode>
	                                    </tem:ConsigneeInfo>

	                                    <tem:BillingType>' . $BillingType . '</tem:BillingType>
	                                    <tem:PicesCount>' . $ShipArr['pieces'] . '</tem:PicesCount>
	                                    <tem:Weight>' . $ShipArr['weight'] . '</tem:Weight>
	                                    <tem:DeliveryInstruction> </tem:DeliveryInstruction>
	                                    <tem:CODCharge>' . $ShipArr['total_cod_amt'] . '</tem:CODCharge>
	                                    <tem:CreateBooking>false</tem:CreateBooking>
	                                    <tem:isRTO>false</tem:isRTO>
	                                    <tem:GeneratePiecesBarCodes>false</tem:GeneratePiecesBarCodes>
	                                    <tem:LoadTypeID>36</tem:LoadTypeID>
	                                    <tem:DeclareValue>0</tem:DeclareValue>
	                                    <tem:GoodDesc>' . $complete_sku . '</tem:GoodDesc>
	                                    <tem:RefNo>' .  $ShipArr['slip_no'] . '</tem:RefNo>
	                                    <tem:InsuredValue>0</tem:InsuredValue>
	                                    <tem:GoodsVATAmount>0</tem:GoodsVATAmount>
	                                    <tem:IsCustomDutyPayByConsignee>false</tem:IsCustomDutyPayByConsignee>
	                                </tem:_ManifestShipmentDetails>
	                            </tem:CreateWaybill>
	                        </soapenv:Body>
	                        </soapenv:Envelope>';   
	              //  echo "<pre>"; print_r($xml_new); //exit; 
	                $headers = array(
	                    "Content-type: text/xml",
	                    "Content-length: ".strlen($xml_new),
	                );

	                $url = $API_URL;
	                $ch = curl_init();
	                curl_setopt($ch, CURLOPT_URL, $url);
	                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	                curl_setopt($ch, CURLOPT_POST, true);
	                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_new);
	                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	                $response = curl_exec($ch);
	                $check = $response;
	                $respon = trim($check);

	                // echo "<br><br><pre> respon = "; print_r($respon);

	                $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $respon);
	                $xml2 = new SimpleXMLElement($respon);  
	                $again = $xml2;
	                $a = array("qwb" => $again);

	               $complicated_awb = ($a['qwb']->Body->CreateWaybillResponse->CreateWaybillResult);
                curl_close($ch);

                 $awb_array = json_decode(json_encode((array) $complicated_awb), TRUE);
                 $logresponse =   json_encode($awb_array);  
                    $successres = $awb_array['HasError'];
                    echo $c_id;
                    if($successres!== true) 
                    {
                        $successstatus  = "Success";
                    } else {
                        $successstatus  = "Fail";
                    }

                       $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
                           

                return $awb_array;

	}
	public function SaeeArray(array $ShipArr, array $counrierArr, $Auth_token = null,$c_id,$super_id) {
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'city');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'city');
        $lat = getdestinationfieldshow($ShipArr['origin'], 'latitute');
        $lang = getdestinationfieldshow($ShipArr['origin'], 'longitute');

        $API_URL = $counrierArr['api_url'];
        $Secretkey = $counrierArr['auth_token'];

        $weight = $ShipArr['weight'];

        if ($ShipArr['mode'] == 'COD') {
            $BookingMode = 'COD';
            $codValue = 0;
        } elseif ($ShipArr['mode'] == 'CC') {
            $BookingMode = 'CC';
            $codValue = 0;
        }


        $param = array(
            "ordernumber" => $ShipArr['slip_no'],
            "cashondelivery" => $codValue,
            "name" => $ShipArr['reciever_name'],
            "mobile" => $ShipArr['reciever_phone'],
            "mobile2" => '',
            "streetaddress" => $ShipArr['reciever_address'],
            "streetaddress2" => '',
            "district" => '',
            "city" => $receiver_city,
            "state" => '',
            "zipcode" => $ShipArr['reciever_zip'],
            "custom_value" => '',
            "hs_code" => 'FASTCOO',
            "category_id" => '',
            "weight" => $weight,
            "quantity" => $ShipArr['pieces'],
            "description" => "",
            "email" => $ShipArr['reciever_email'],
            "pickup_address_id" => '',
            "Pickup_address_code" => '',
            "sendername" => $ShipArr['sender_name'],
            "sendermail" => $ShipArr['sender_email'],
            "senderphone" => $ShipArr['sender_phone'],
            "senderaddress" => $ShipArr['sender_address'],
            "sendercity" => $sender_city,
            'sendercountry' => '',
            "sender_hub" => '',
            "latitude" => $lat,
            "longitude" => $lang,
        );
        $all_param_data = json_encode($param);
        $live_url = "https://corporate.saeex.com/deliveryrequest/new?secret=$Secretkey";
        $headers = array("Content-type:application/json");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $live_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
        $response = curl_exec($ch);
        curl_close($ch);      

        $response = json_decode($response, true);
        $logresponse =   json_encode($response);  
        $successres = $response['success']; 
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres == 'true') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

          $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response;
    }

    public function EmdadArray($ShipArr, $counrierArr, $complete_sku, $c_id, $super_id) {
        $sender_email = $counrierArr['user_name']; //provided by company  :  (column name: password || date
        $password = $counrierArr['password'];
        $url = $counrierArr['api_url'];
        //print_r($ShipArr);exit;
        $Receiver_name = $ShipArr['reciever_name'];
        $Receiver_email = $ShipArr['reciever_email'];
        $Receiver_phone = $ShipArr['reciever_phone'];
        $Receiver_address = $ShipArr['reciever_address'];
        if (empty($Receiver_address)) {
            $Receiver_address = 'N/A';
        }

        $Reciever_city = getdestinationfieldshow($ShipArr['destination'], 'emdad_city');
        
        $product_type = 'Parcel'; //beone ka database
        $service = '2'; // beone wali
        $description = $ShipArr['status_describtion'];
        if (empty($description)) {
            $description = 'N/A';
        }

        // this is prodect name (column name: status_describtion

        $ajoul_booking_id = $ShipArr['booking_id'];
        $s_name = $ShipArr['sender_name'];
        $s_address = $ShipArr['sender_address'];
        $s_zip = $ShipArr['sender_zip'];
        $s_phone = $ShipArr['sender_phone'];
        $s_city = getdestinationfieldshow($ShipArr['origin'], 'emdad_city');

        $pay_mode = $ShipArr['mode']; //paymode either CASH or COD:(column name: mode || date
        $codValue = $ShipArr['total_cod_amt']; //COD charges.  :  (column name:     total_cod_amt || date type:
        $product_price = $ShipArr['declared_charge']; //(column name: declared_charge || date type: int || value: 11)
        $booking_id = $ShipArr['slip_no']; // send awb number ajoul
        $shipper_refer_number = $ShipArr['booking_id']; // ajoul ki booking id
        $weight = $ShipArr['weight'];
        if ($weight == 0) {
            $weight = 1;
        }

        //weight should be in kg.:(column name: weight || date type
        $NumberOfParcel = $ShipArr['pieces']; //(column name: pieces || date type: int || value: 5)

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

        $response = curl_exec($ch);
      // print_r($response);exit;
         $logresponse =   json_encode($response);  
        $successres = $response['error'];
        //echo "<pre>"; print_r($response)  ;    die;
        if($successres == '') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        curl_close($ch);
        
        return $response;
    }
    public function AjeekArray($ShipArr, $counrierArr, $complete_sku, $box_pieces, $c_id, $super_id) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'ajeek_city');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'ajeek_city');
       $latitude = getdestinationfieldshow($ShipArr['origin'], 'latitute');
       $Longitude = getdestinationfieldshow($ShipArr['origin'], 'longitute');
        $api_key = $counrierArr['auth_token'];
        $vendor_id = $counrierArr['courier_pin_no'];
        $user_id = $counrierArr['courier_account_no'];
        $branch_id = $counrierArr['password'];
        $comp_api_url = $counrierArr['api_url'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] == 'COD') {
            $billing_type = 1;
            $cod_amount = $ShipArr['total_cod_amt'];
        } else {
            $billing_type = 2;
            $cod_amount = 0;
        }

        if ($ShipArr['weight'] == 0) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }

        if ($box_pieces > 0) {
            $pieces = $box_pieces;
        } else {
            $pieces = $ShipArr['pieces'];
        }

        $items_detail = array(
                array(
                    "description" => "parcel1",
                    "length" => $weight,
                    "width" => $weight,
                    "height" => $weight
                )
        );
        $number = $ShipArr['reciever_phone'];
        $number = ltrim($number, '966');
        $number = ltrim($number, '0');
        $number = '00966' . $number;
        $number = str_replace(' ', '', $number);


        
        $request_data = array(
                "user_id" => $user_id,
                "cust_first_name" => $ShipArr['reciever_name'],
                "cust_last_name" => " ",
                "cust_mobil" => $number,
                "vendor_id" => $vendor_id,
                "branch_id" => $branch_id,
                "payment_type_id" => 1,
                "cords" => $Longitude.','.$latitude,
                "address" => 'KSA '.$receiver_city,
                "bill_amount" => $cod_amount,
                "preorder" => "false",
                "bill_reference_no " => $ShipArr['slip_no'],
                "pieces" => $pieces,
                "total_weight" => $weight,
                "order_items_detail" => $items_detail,
                "api_key" => $api_key,
        );
       // echo "<pre>"; print_r($request_data); exit; 
        $dataJson = json_encode($request_data);
        $headers = array (
            "Content-Type: application/json"
        );
        $ch = curl_init($comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['description'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres =="Done") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse, $successstatus, $ShipArr['slip_no'],$super_id);
        return $response_array;
    }
    public function AymakanArray(array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $super_id = null) {
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'Aymakan');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'Aymakan');
         $store = getallsellerdatabyID($ShipArr['cust_id'], 'company');
        
        $entry_date = date('Y-m-d H:i:s');
        $pickup_date = date("Y-m-d", strtotime($entry_date));

        $API_URL = $counrierArr['api_url'];
        $api_key = $counrierArr['auth_token'];
         $currency = "SAR";

        $weight = $ShipArr['weight'];

        if ($ShipArr['mode'] == 'COD') {
            $price_set = 113;
            $is_cod = 1;
            $cod_amount = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['mode'] == 'CC') {
            $is_cod = 0;
            $price_set = 364;
            $cod_amount = 0;
        }

        echo "<pre>";
        $all_param_data = array(
           "requested_by" => $ShipArr['sender_name'],
           "fulfilment_customer_name" => $store,
            "declared_value" => $ShipArr['total_cod_amt'],
            "declared_value_currency" => $currency,
            "price_set" => $price_set,
            "reference" => $ShipArr['slip_no'],
            "is_cod" => $is_cod,
            "cod_amount" => $cod_amount,
            "currency" => $currency,
            "delivery_name" => $ShipArr['reciever_name'],
            "delivery_email" => $ShipArr['reciever_email'],
            "delivery_city" => $receiver_city,
            "delivery_address" => $ShipArr['reciever_address'],
            "delivery_country" => 'SA',
            "delivery_phone" => $ShipArr['reciever_phone'],
            "delivery_description" => $item_description,
            "collection_name" => $ShipArr['sender_name'],
            "collection_address" => $ShipArr['sender_address'],
            "collection_email" => $ShipArr['sender_email'],
            "collection_city" => $sender_city,
            "collection_postcode" => $s_zip,
            "collection_country" => 'SA',
            "collection_phone" => $ShipArr['sender_phone'],
            "pickup_date" => $pickup_date,
            "weight" => $ShipArr['weight'],
            "pieces" =>$ShipArr['pieces']
        );
       // print_r($all_param_data);
        //exit;
        $json_final_date = json_encode($all_param_data);
        // print_r($json_final_date);exit;
        $headers = array(
            "Accept:application/json",
            "Authorization: $api_key");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
        $response = curl_exec($ch);
        //echo "<br><br><br>";    print_r($response); //exit; 
        curl_close($ch);
        $responseArray = json_decode($response, true);
        $logresponse =   json_encode($response);  
        $successres = $responseArray['errors'];
        echo "<pre>"; print_r($response)   ;   // die;
        if(empty($successres)) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response;
    }
    public function ShipsyArray(array $ShipArr, array $counrierArr, $box_pieces = null, $c_id = null, $super_id = null) {
        //print_r($ShipArr);exit;
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'shipsy_city');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'shipsy_city');
            if ($ShipArr['mode'] == 'COD') {
                    $total_cod_amt = $ShipArr['total_cod_amt'];
                } elseif ($ShipArr['mode'] == "CC") {
                    $total_cod_amt = 0;
                }
				if($box_pieces==0){
					$box_pieces = $ShipArr['pieces'];
				}else{
					$box_pieces = $box_pieces;
				}
                $consignments[] = Array
                                (
                                    //[0] => Array
                                        //(
                                            "customer_code" => "FASTCOO",
                                            "reference_number" => '',
                                            "service_type_id" => "PREMIUM",
                                            "load_type" => "NON-DOCUMENT",
                                            "description" => "",
                                            "inco_terms" => "",
                                            "shipment_purpose" => "",
                                            "product_code" => "",
                                            "cod_favor_of" => "",
                                            "cod_collection_mode" => "",
                                            "dimension_unit" => "",
                                            "length" => "",
                                            "width" => "",
                                            "height" => "",
                                            "weight_unit" => "kg",
                                            "weight" => $ShipArr['weight'],
                                            "declared_value" =>"", 
                                            "cod_amount" => $total_cod_amt,
                                            "num_pieces" => $box_pieces,
                                            "customer_reference_number" => $ShipArr['slip_no'],
                                            "is_risk_surcharge_applicable" => 1,
                                            "origin_details" => Array
                                                (
                                                    "name" => $ShipArr['sender_name'],
                                                    "phone" => $ShipArr['sender_phone'],
                                                    "alternate_phone" => '',
                                                    "address_line_1" => $ShipArr['sender_address'],
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" => $sender_city,
                                                    "state" => '',
                                                    "email" => $ShipArr['sender_email'],
													
                                                ),

                                            "destination_details" => Array
                                                (
                                                    "name" => $ShipArr['reciever_name'],
                                                    "phone" => $ShipArr['reciever_phone'],
                                                    "alternate_phone" => "",
                                                    "address_line_1" => $ShipArr['reciever_address'],
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" => $receiver_city,
                                                    "state" => '',
                                                    "email" => $ShipArr['reciever_email'],
                                                ),

                                            "pieces_detail" => Array
                                                (
                                                    [0] => Array
                                                        (
                                                            "description" => $ShipArr['sku'],
                                                            "declared_value" => $total_cod_amt,
                                                            "weight" => $ShipArr['volumetric_weight'],
                                                            "height" => '',
                                                            "length" =>'',
                                                            "width" => ''
                                                        )

                                                )

                                        //)

                                );
        $all_param_array = Array(
                            "is_international" => '',
                            "consignments" => $consignments

                        );
        $param = json_encode($all_param_array);
        //echo $param;exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$param,
          CURLOPT_HTTPHEADER => array(
            'api-key:'.$counrierArr['auth_token'],
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
         $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        $successres = $response_array['data'][0]['success'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres==1) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response;
        //exit;
    }
    
    public function ShipsyLabelcURL(array $counrierArr, $client_awb = null) {
        $url = str_replace('softdata', 'shippinglabel/link?reference_number=', $counrierArr['api_url']);
        $url = $url.$client_awb;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'api-key:'.$counrierArr['auth_token'],
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response, true);
        
        $labelURL = $response['data']['url'];
        $labelURL = str_replace('isSmall=false', 'isSmall=true', $labelURL);
        
        return $labelURL;
        
    }
    
    public function ShipadeliveryArray(array $ShipArr, array $counrierArr, $auth_token = null, $c_id = null, $super_id = null) {
        
        ini_set('default_charset', 'UTF-8');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'shipsa_city');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'shipsa_city');
        
        if ($ShipArr['mode'] == 'COD') {
            $total_cod_amt = $ShipArr['total_cod_amt'];
            $paymentMethod = 'CashOnDelivery';
        } elseif ($ShipArr['mode'] == "CC") {
            $total_cod_amt = 0;
            $paymentMethod = 'Prepaid';
        }
        $description = $complete_sku;
        if($description==''){
            $description = 'GOODS';
        }
        
        $Sender = array(
            'name' => $ShipArr['sender_name'],
            'address' => utf8_encode($ShipArr['sender_address']),
            'phone' => $ShipArr['sender_phone'],
            'email' => $ShipArr['sender_email'],
        );
        $Recipient = array(
            'name' => $ShipArr['reciever_name'],
            'address' => utf8_encode($ShipArr['reciever_address']),
            'phone' => $ShipArr['reciever_phone'],
            'email' => $ShipArr['reciever_email'],
            'city' => $receiver_city,
        );
        $param[] = array(
            'id' => $ShipArr['slip_no'],
            'amount' => (float)$total_cod_amt,
            'paymentMethod' => $paymentMethod,
            'orderCategory' => 'NEXTDAY',
            'description' => $description,
            'typeDelivery' => 'forward',
            'sender' => $Sender,
            'recipient' => $Recipient
        );
        
        $paramArray = json_encode($param);
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'].$counrierArr['auth_token'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$paramArray,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-key: 4iG5n5JBJR9hGDSXAYUWWg5ZZwgOhfQ8',
            'Accept: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
         $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        $successres = $response_array[0]['code'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres==0) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response;
    }
    
    public function ShipaDelLabelcURL(array $counrierArr, $client_awb = null) {
        $cURL12 = "https://sandbox-api.shipadelivery.com/orders?apikey=";
        $cURL1 = str_replace("?apikey=", "/$client_awb/pdf?apikey=", $cURL12);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $cURL1.$counrierArr['auth_token'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    
    public function SPArray(array $ShipArr, array $counrierArr, $complete_sku = null, $c_id = null, $super_id = null){
        
        $username = $counrierArr['user_name'];
        $password = $counrierArr['password'];
        $authdata = 'grant_type=password&UserName='.$username.'&password='.$password;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://updsstg.sp.com.sa/csapi/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $authdata,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $token = json_decode($response, true);
        
        
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'saudipost_id');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'saudipost_id');
        
        if ($ShipArr['mode'] == "COD") {
            $PaymentType = 2;
            $total_cod_amt = $ShipArr['total_cod_amt'];
        }else
        {
            $PaymentType = 1;
            $total_cod_amt = 0;
        }

        $param = array(
            "CRMAccountId" => $counrierArr['courier_account_no'],
            "BranchId"=> 0,
            "PickupType"=> 1,
            "RequestTypeId"=> 1,
            "CustomerName"=> $ShipArr['reciever_name'],
            "CustomerMobileNumber"=> $ShipArr['reciever_phone'],
            "SenderName"=> $ShipArr['sender_name'],
            "SenderMobileNumber"=> $ShipArr['sender_phone'],
            "Items"=> array(
                array(
                    "ReferenceId"=> $ShipArr['slip_no'],
                    "Barcode"=> null,
                    "PaymentType"=> $PaymentType,
                    "ContentPrice"=> 0,
                    "ContentDescription"=> "Goods",
                    "Weight"=> $ShipArr['weight'],
                    "BoxLength"=> 0,
                    "BoxWidth"=> 0,
                    "BoxHeight"=> 0,
                    "ContentPriceVAT"=> 0,
                    "DeliveryCost"=> 0,
                    "DeliveryCostVAT"=> 0,
                    "TotalAmount"=> $total_cod_amt,
                    "CustomerVAT"=> 0,
                    "SaudiPostVAT"=> 0,

                    "SenderAddressDetail"=> array(
                        "AddressTypeID"=> "6",
                        "AddressLine1"=> $ShipArr['sender_address'],
                        "AddressLine2"=> "SP",
                        "LocationID"=> $sender_city
                    ),
                    "ReceiverAddressDetail"=> array(
                        "AddressTypeID"=> "6",
                        "AddressLine1"=> $ShipArr['reciever_address'],
                        "AddressLine2"=> "SP",
                        "LocationID"=> $receiver_city
                    )
                )
            )
        );

        $param = json_encode($param);
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$param,
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer '.$token['access_token'],
            'Content-Type: application/json',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        $successres = $response['Items'][0]['Message'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres=='Success') 
        {
            $successstatus  = "Success";
        } else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id);
        return $response;
    }


 	public function PrintLabel($SMSAAWB, $Passkey, $url) {
        $xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <getPDF xmlns="http://track.smsaexpress.com/secom/">
                    <awbNo>' . $SMSAAWB . '</awbNo>
                    <passKey>' . $Passkey . '</passKey>
                </getPDF>
            </soap:Body>
        </soap:Envelope>';
        $headers = array(
            "Content-type: text/xml;charset=utf-8",
            "Accept: application/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://track.smsaexpress.com/secom/getPDF",
            "Content-length: " . strlen($xml),
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = trim(curl_exec($ch));
        return $response;
    }

 public function shipmentLog($c_id = null,$description= null,$status= null,$slip_no= null,$super_id= null){
        $CURRENT_DATE = date("Y-m-d H:i:s");
        $logarr  = array(
            'slip_no' => $slip_no, 
            'cc_id' => $c_id, 
            'log' => $description, 
            'status' =>$status, 
            'super_id' => $super_id, 
            'entry_date' =>$CURRENT_DATE, 
        );       

        $retr = $this->GetlogInsert($logarr); 

    }
     public function GetlogInsert($data = array()) {

            $this->db->insert('frwd_shipment_log', $data);
            //echo $this->db->last_query(); die;
        }


}
