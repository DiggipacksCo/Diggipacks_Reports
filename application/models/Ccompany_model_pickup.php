<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ccompany_model_pickup extends CI_Model {

    function __construct() {
        parent::__construct();
        
        
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function all() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->order_by('company');
        $this->db->select('*');
        $query = $this->db->get('courier_company');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function GetUpdateDeliveryCOmpany($data = array(), $data_w) {
        return $this->db->update('courier_company', $data, $data_w);
        echo $this->db->last_query();
        die;
    }

    public function GetCompanylistDropQry() {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('id,cc_id,company');
        $this->db->from('courier_company');
        $this->db->where('deleted', 'N');
        $this->db->where("(api_url!='' or api_url_t!='')");
        $this->db->where('status', 'Y');

        $this->db->order_by("company");
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function GetSlipNoDetailsQry($slip_no = null,$order_type=null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('*');
        if($order_type=='return_o')
        {
        $this->db->from('pickup_request_return');
        }
        else
        {
          $this->db->from('pickup_request');   
        }
        $this->db->where('uniqueid', $slip_no);
        $this->db->group_by('uniqueid');
        // $this->db->where('status', 'Y');
        $query = $this->db->get();
      //  echo $this->db->last_query();exit;
        return $query->row_array();
    }

    public function GetdeliveryCompanyUpdateQry($cc_id = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $cc_id);
        $this->db->select('*');
        $this->db->from('courier_company');
        $this->db->where('deleted', 'N');
        // $this->db->where('forwarded', 0);
        //$this->db->where_not_in('code', 'RTC','DL','POD','C');
        $this->db->where('status', 'Y');
        $this->db->order_by("company");
        $query = $this->db->get();
     //  echo $this->db->last_query();
        return $query->row_array();
    }

    public function getdestinationfieldshow($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT $field FROM country where id='$id' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

    public function Getskudetails_forward($slip_no = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('sku,qty');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $slip_no);
         $this->db->group_by('sku');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetshipmentUpdate_forward(array $data, $awb = null) {
        
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('pickup_request', $data, array('uniqueid' => $awb));
        //   echo $this->db->last_query();
    }
     public function GetshipmentUpdate_forward_return(array $data, $awb = null) {
        
         $data['return_type']='Y';
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('pickup_request_return', $data, array('uniqueid' => $awb));
          $this->db->query("delete from pickup_request_return where return_type='N'");
       
        //   echo $this->db->last_query();
    }

    public function GetstatuInsert_forward(array $data) {

        $this->db->insert('status_fm', $data);
        //echo $this->db->last_query();
    }

    public function AramexArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null) {
        
         
        $sender_city = $this->getdestinationfieldshow($ShipArr['city'], 'aramex_city');
        $reciever_city = $this->getdestinationfieldshow($recevierArr['branch_location'], 'aramex_city');
        $date = (int) microtime(true) * 1000;
        $params = array(
            'ClientInfo' => array(
                'UserName' => $counrierArr['user_name'],
                'Password' => $counrierArr['password'],
                'Version' => 'v1',
                'AccountNumber' => $counrierArr['courier_account_no'],
                'AccountPin' => $counrierArr['courier_pin_no'],
                'AccountEntity' => 'RUH',
                'AccountCountryCode' => 'SA'
            ),
            'LabelInfo' => array("ReportID" => 9729, "ReportType" => "URL"),
            'Shipments' => array(
                0 => array(
                    'Reference1' => '',
                    'Reference2' => '',
                    'Reference3' => '',
                    'Shipper' => array(
                        'Reference1' => $ShipArr['uniqueid'],
                        'Reference2' => '',
                        'AccountNumber' => $counrierArr['courier_account_no'],
                        'PartyAddress' => array(
                            'Line1' => $senderArr['address'],
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
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $senderArr['name'],
                            'Title' => '',
                            'CompanyName' => $senderArr['name'],
                            'PhoneNumber1' => $senderArr['phone'],
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $senderArr['phone'],
                            'EmailAddress' => $senderArr['email'],
                            'Type' => '',
                        ),
                    ),
                    'Consignee' => array(
                        'Reference1' => '',
                        'Reference2' => '',
                        'AccountNumber' => '',
                        'PartyAddress' => array(
                            'Line1' => $recevierArr['address'],
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
                            'Description' => $ShipArr['description'],
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $recevierArr['name'],
                            'Title' => '',
                            'CompanyName' => $recevierArr['name'],
                            'PhoneNumber1' => $recevierArr['phone'],
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $recevierArr['phone'],
                            'EmailAddress' => $recevierArr['email'],
                            'Type' => '',
                        ),
                    ),
                    'ThirdParty' => array(
                        'Reference1' => '',
                        'Reference2' => '',
                        'AccountNumber' => '',
                        'PartyAddress' => array(
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
                        'Contact' => array(
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
                    'Details' => array(
                        'Dimensions' => NULL,
                        'ActualWeight' => array(
                            'Unit' => 'KG',
                            //'Value' => $weight,
                            'Value' => '1',
                        ),
                        'ChargeableWeight' => NULL,
                        'DescriptionOfGoods' => $ShipArr['description'],
                        'GoodsOriginCountry' => 'SA',
                        'NumberOfPieces' => $ShipArr['boxes'],
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
                        'Items' => array(),
                    ),
                    'Attachments' => array(),
                    'ForeignHAWB' => $ShipArr['uniqueid'],
                    'TransportType ' => 0,
                    'PickupGUID' => '',
                    'Number' => NULL,
                    'ScheduledDelivery' => NULL,
                ),
            ),
            'Transaction' => array(
                'Reference1' => '',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => '',
            )
        );
        return $params;
    }

    public function AxamexCurl($url = null, array $headers, $dataJson = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function Update_Shipment_Status($updateArr=array(),$slipNo=null,$order_type=null,$allArr=array()) {

       

        
if($order_type=='return_o')
{
    
    
          $this->GetshipmentUpdate_forward_return($updateArr, $slipNo);       
        
         
              
}
else
{
   $this->GetshipmentUpdate_forward($updateArr, $slipNo,$order_type); 
}

       

       

        return true;
    }

    public function SafeArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null,$Auth_token = null) {
        $sender_city_safe = getdestinationfieldshow($ShipArr['city'], 'safe_arrival');
        $receiver_city_safe = getdestinationfieldshow($recevierArr['branch_location'], 'safe_arrival');

        $API_URL = $counrierArr['api_url'];

        $sender_data = array(
            "address_type" => "residential",
            "name" => $senderArr['name'],
            "email" => $senderArr['email'],
            "street" => $senderArr['address'],
            "city" => array(
                "id" => $sender_city_safe
            ),
            "phone" => $senderArr['phone']
        );
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $recevierArr['name'],
            "email" => $recevierArr['email'],
            "street" => $recevierArr['address'],
            "city" => array(
                "id" => $receiver_city_safe
            ),
            "phone" => $recevierArr['phone']
        );
        $dimensions = array(
            "weight" => $ShipArr['weight']
        );
        $package_type = array(
            "courier_type" => 'IN_5_DAYS'
        );
        $charge_items = array(
            array(
                "paid" => false,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode']
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
            "piece_count" => 1, //$ShipArr['boxes'],
            "force_create" => true,
            "reference_id" => $ShipArr['uniqueid']
        );

        $header = array(
            "Authorization" => "Bearer " . $responseArray['data']['id_token'],
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );

        $dataJson = json_encode($param);

        $response = send_data_to_safe_curl($dataJson, $Auth_token, $API_URL);
        return $response;
    }

    public function EsnadArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null, $Auth_token = null) {
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'esnad_city');
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'esnad_city');
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] == 'COD1') {
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
                "clientOrderNo" => $ShipArr['uniqueid'],
                "orderType" => "DOM",
                "deliveryService" => "EXP",
                "consignor" => $senderArr['name'],
                "pickupAddress" => $senderArr['address'],
                "pickupContact" => $senderArr['phone'],
                "originCity" => $sender_city,
                "originCountry" => "SA",
                "consignee" => $recevierArr['name'],
                "deliveryAddress" => $recevierArr['address'],
                "deliveryContact" => $recevierArr['phone'],
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
                "pcs" => 1, //$pieces,
                "declaredValue" => $declared_charge,
                "packageWeight" => $ShipArr['weight'],
                "productDetails" => array(
                    "productHscode" => $complete_sku,
                )
            )
        );

        $dataJson = json_encode($param);
        $headers = array(
            "Content-Type: application/json",
            "token: $Auth_token"
        );
        $response = send_data_to_curl($dataJson, $comp_api_url, $headers);
        return $response;
    }

    public function LabaihArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null) {
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'labaih');
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'labaih');
        $lat = $ShipArr['lat'];
        $lang = $ShipArr['lng'];
        $declared_charge = $ShipArr['total_cod_amt'];

       $senderData= $senderArr;
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] === 'COD11') {
            $cod_collection_mode = 'COD';
            // $cod_amount=0;
        } else {
            $cod_collection_mode = 'PREPAID';
            $cod_amount = 0;
        }
        //echo $box_pieces; die;
        if ($box_pieces > 0) {
            $pieces = $box_pieces;
        } else {
            $pieces = $ShipArr['boxes'];
        }

        $comp_api_url = $counrierArr['api_url'];
        $pickupDate = date("Y-m-d");
        $deliveryDate = date('Y-m-d', strtotime($pickupDate . '+ 2 days'));

        $Data_array = array(
                    'api_key' => $counrierArr['auth_token'], 
                    'pickupDate' => $pickupDate, 
                    'deliveryDate' => $deliveryDate,
                    'customerOrderNo' => $ShipArr['uniqueid'], 
                    'noOfPieces' => $pieces,
                    'weightKg' => $ShipArr['weight'],
                    'dimensionsCm' => $complete_sku,
                    'itemDescription' => $ShipArr['description'],
                    'paymentMethod' => $cod_collection_mode,
                    'paymentAmount' => $cod_amount,
                    'consigneeName' => $recevierArr['name'], 
                    'consigneeEmail' => $recevierArr['email'],
                    'consigneeMobile' => $recevierArr['phone'],
                    'consigneePhone' => $recevierArr['phone'], 
                    'consigneeCity' => $receiver_city, 
                    'consigneeCommunity' => $receiver_city,
                    'consigneeAddress' => $recevierArr['address'],
                    'consigneeFlatFloor' => '',
                    'consigneeLatLong' => $ShipArr['dest_lat'] . ',' . $ShipArr['dest_lng'],
                    'consigneeSplInstructions' => $ShipArr['description'],
                    'store' => $senderArr['name'], /*                     * */
                    'shipperName' => $senderData['name'], /*                     * */
                    'shipperMobile' => $senderData['phone'], /*                     * */
                    'shipperEmail' => $senderData['email'],
                    'shipperCity' => $sender_city,
                    'shipperDistrict' => $sender_city,
                    'shipperAddress' => $senderData['name'],
                    'shipperLatLong' => $lat . ',' . $lang,
        );
        //print_r($Data_array); die;
        $headers = array(
            "Content-type:application/x-www-form-urlencoded",
            "cache-control: no-cache"
        );

        $dataJson = http_build_query($Data_array);

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
        return $response_array;
    }

    public function ClexArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null) {
       // echo "ss".$recevierArr['branch_location']."sssss";
        
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'clex');
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'clex');
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
        } else {
            $weight = $ShipArr['weight'];
        }


        if ($box_pieces > 0) {
            $pieces = $box_pieces;
        } else {
            $pieces = $ShipArr['boxes'];
        }

        $request_data = array(
            'shipment_reference_number' => $ShipArr['uniqueid'],
            'shipment_type' => 'delivery',
            'billing_type' => $billing_type,
            'collect_amount' => $cod_amount,
            'primary_service' => 'delivery',
            'secondary_service' => '',
            'item_value' => '',
            'consignor' => $senderArr['name'],
            'consignor_email' => $senderArr['email'],
            'origin_city' => $sender_city,
            'origin_area_new' => '',
            'consignor_street_name' => $senderArr['address'],
            'consignor_building_name' => '',
            'consignor_address_house_appartment' => '',
            'consignor_address_landmark' => '',
            'consignor_country_code' => '+966',
            'consignor_phone' => remove_phone_format($senderArr['phone']),
            'consignor_alternate_country_code' => '',
            'consignor_alternate_phone' => '',
            'consignee' => $recevierArr['name'],
            'consignee_email' => $recevierArr['email'],
            'destination_city' => $receiver_city,
            'destination_area_new' => '',
            'consignee_street_name' => $recevierArr['address'],
            'consignee_building_name' => '',
            'consignee_address_house_appartment' => '',
            'consignee_address_landmark' => '',
            'consignee_country_code' => '+966',
            'consignee_phone' => remove_phone_format($recevierArr['phone']),
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
         // echo "<pre>";print_r($request_data);echo "</br>";exit;
        $dataJson = json_encode($request_data);
        $access_token = $counrierArr['auth_token'];

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
        return $response_array;
    }

    public function AjeekArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null) {
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'ajeek_city');
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'ajeek_city');
       $latitude = $ShipArr['lat'];
       $Longitude = $ShipArr['lng'];
        
        $api_key = $counrierArr['auth_token'];
        $vendor_id = $counrierArr['courier_pin_no'];
        $user_id = $counrierArr['courier_account_no'];
        $branch_id = $counrierArr['password'];
        $comp_api_url = $counrierArr['api_url'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] == 'COD1') {
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
            $pieces = $ShipArr['boxes'];
        }

        $items_detail = array(
                array(
                    "description" => "parcel1",
                    "length" => $weight,
                    "width" => $weight,
                    "height" => $weight
                )
        );
        
        $request_data = array(
                "user_id" => $user_id,
                "cust_first_name" => $recevierArr['name'],
                "cust_last_name" => " ",
                "cust_mobil" => '966'.$recevierArr['phone'],
                "vendor_id" => $vendor_id,
                "branch_id" => $branch_id,
                "payment_type_id" => 1,
                "cords" => $Longitude.','.$latitude,
                "address" => 'KSA '.$receiver_city,
                "bill_amount" => $cod_amount,
                "preorder" => "false",
                "bill_reference_no " => $ShipArr['uniqueid'],
                "pieces" => $pieces,
                "total_weight" => $weight,
                "order_items_detail" => $items_detail,
                "api_key" => $api_key,
        );
       // echo "<pre>"; print_r($request_data); echo "</br></br>"; //exit;
        $dataJson = json_encode($request_data);
        $headers = array (
            "Content-Type: application/json"
        );
      //  echo "<pre>"; print_r($headers); echo "</br></br>"; //exit;

        
        $ch = curl_init($comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
       // echo "<pre>";print_r($response); echo "</br>"; exit;
        curl_close($ch);
        $response_array = json_decode($response, true);
         // echo "<pre>";print_r($response_array); echo "</br>"; exit;
        return $response_array;
    }

    public function forwardshfilter($awb, $warehouse, $origin, $destination, $forwarded_type, $mode, $sku, $booking_id, $page_no) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        /* if(!empty($delivered)){
          $this->db->where('shipment_fm.delivered', $delivered);
          } */

        $fulfillment = 'Y';
        $deleted = 'N';
        $e_city = Getsite_configData();
        $e_City = explode(",", $e_city['e_city']);


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            //$this->db->where('shipment_fm.wh_id',$this->session->userdata('user_details')['wh_id']);
        }
        //$this->db->where_in('shipment_fm.destination', $e_City);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->where_not_in('shipment_fm.code', 'RTC', 'DL', 'POD', 'C');
        $this->db->where('shipment_fm.forwarded', $forwarded_type);
        $this->db->select('shipment_fm.id,shipment_fm.sku,shipment_fm.mode, shipment_fm.booking_id, shipment_fm.slip_no, shipment_fm.origin, shipment_fm.destination, shipment_fm.wh_id, shipment_fm.entrydate, shipment_fm.frwd_company_awb, shipment_fm.frwd_company_label, shipment_fm.frwd_company_id, customer.name, customer.seller_id, customer.uniqueid');
        $this->db->from('shipment_fm');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        if ($forwarded_type == 1) {
            $this->db->join('courier_company', 'courier_company.id=shipment_fm.frwd_company_id');
        }



        // echo $delivered;

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }
        if (!empty($warehouse)) {
            $warehouse = array_filter($warehouse);

            $this->db->where_in('shipment_fm.wh_id', $warehouse);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
        if (!empty($booking_id)) {
            $this->db->where_in('booking_id', explode(' ', $booking_id));
        }

        if (!empty($mode)) {
            $this->db->where('shipment_fm.mode', $mode);
        }
        if (!empty($sku)) {
            $this->db->where('shipment_fm.sku', $sku);
        }

        if (!empty($origin)) {

            $this->db->where('diamention_fm.origin', $origin);
        }




        $this->db->order_by('id', 'desc');

        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //  echo $this->db->last_query();    

        if ($query->num_rows() > 0) {


            //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $cc_id, $forwarded_type);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $cc_id = null, $forwarded_type) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.forwarded', $forwarded_type);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        if ($status_dashbord == 1) {
            $this->db->join('status_fm', 'status_fm.slip_no=shipment_fm.slip_no');
        }
        if ($forwarded_type == 1) {
            $this->db->join('courier_company', 'courier_company.id=shipment_fm.frwd_company_id');
        }


        if (!empty($exact)) {
            if ($status_dashbord == 1) {
                $this->db->where('DATE(status_fm.entry_date)', $exact);
            } else {
                $this->db->where('DATE(shipment_fm.entrydate)', $exact);
            }
        }


        if ($backorder == 'back')
            $this->db->where('shipment_fm.backorder', 1);
        else {


            $this->db->where('shipment_fm.backorder', 0);
            // $this->db->where('shipment.reverse_pickup', 0);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        if (!empty($delivered)) {
            if ($status_dashbord == 1) {

                $this->db->where_in('status_fm.new_status', $delivered);
            }
            if ($status_dashbord != 1) {
                // print_r($delivered);
                if ($delivered == '1' || $delivered == '4' || $delivered == '5' || $delivered == '2') {
                    if (array_key_exists(0, $delivered))
                        $delivered = array_filter(0, $delivered);
                } else {
                    if ($delivered[0] == '')
                        $delivered = "";
                    $delivered = array_filter($delivered);
                }

                $this->db->where_in('shipment_fm.delivered', $delivered);
            }
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment_fm.cust_id', $seller);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }


        $this->db->order_by('shipment_fm.id', 'desc');

        $query = $this->db->get();

        // echo $this->db->last_query(); 
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function BarqfleethArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null) {
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'barq_city');
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'barq_city');
        $lat = $ShipArr['lat'];
        $lang =  $ShipArr['lng'];
       
        $declared_charge = $ShipArr['total_cod_amt'];

        //echo "sadsdsad"; print_r($ShipArr); 
        //die;

        $cod_amount = 0;

        $cod_collection_mode = 0;

        $comp_api_url = $counrierArr['api_url'];

        $pickupDate = date("Y-m-d");
        $deliveryDate = date('Y-m-d', strtotime($pickupDate . '+ 2 days'));

        $params = array(
            "email" => $counrierArr['user_name'],
            "password" => $counrierArr['password']
        );

        $data = json_encode($params);
        //$request_url = $counrierArr['api_url'];
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
            "payment_type" => $cod_collection_mode,
            "shipment_type" => "instant_delivery",
            "hub_id" => 240,
            "hub_code" => "FASTCOO",
            "merchant_order_id" => $ShipArr['uniqueid'],
            "customer_details" => array(
                "first_name" => $senderArr['name'],
                "last_name" => "",
                "country" => "Saudi Arabia",
                "city" => $receiver_city,
                "mobile" => $recevierArr['phone'],
                "address" => $recevierArr['address']
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
        
        return  $response_ww ;
    }

    public function MakdoonArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null, $Auth_token = null) {
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'makhdoom');
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'makhdoom');
        $API_URL = $counrierArr['api_url'];

        $sender_data = array(
            "address_type" => "residential",
            "name" => $senderArr['name'],
            "email" => $senderArr['email'],
            'apartment' => '',
            'building' => '',
            "street" => $senderArr['address'],
            "city" => array(
                'code' => $sender_city
            ),
            'country' => array(
                'id' => 191,
            ),
            "phone" => $senderArr['phone']
        );
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $recevierArr['name'],
            "email" => $recevierArr['email'],
            "street" => $recevierArr['address'],
            "city" => array(
                'code' => $receiver_city
            ),
            'country' => array(
                'id' => 191,
            ),
            "phone" => $recevierArr['phone'],
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
            "piece_count" => 1, //$ShipArr['boxes'],
            "force_create" => true,
            "reference_id" => $ShipArr['uniqueid']
        );

        $dataJson = json_encode($param);

        $response = send_data_to_makdoom_curl($dataJson, $Auth_token, $API_URL);

        return $response;
    }

    public function SaeeArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null, $Auth_token = null) {
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'city');
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'city');
        $lat = $ShipArr['lat'];
        $lang =  $ShipArr['lng'];

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
            "ordernumber" => $ShipArr['uniqueid'],
            "cashondelivery" => $codValue,
            "name" => $recevierArr['name'],
            "mobile" => $recevierArr['phone'],
            "mobile2" => '',
            "streetaddress" => $recevierArr['address'],
            "streetaddress2" => '',
            "district" => '',
            "city" => $receiver_city,
            "state" => '',
            "zipcode" => $ShipArr['reciever_zip'],
            "custom_value" => '',
            "hs_code" => 'FASTCOO',
            "category_id" => '',
            "weight" => $weight,
            "quantity" => $ShipArr['boxes'],
            "description" => "",
            "email" => $recevierArr['email'],
            "pickup_address_id" => '',
            "Pickup_address_code" => '',
            "sendername" => $senderArr['name'],
            "sendermail" => $senderArr['email'],
            "senderphone" => $senderArr['phone'],
            "senderaddress" => $senderArr['address'],
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
        return $response;
    }


    public function AymakanArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null, $Auth_token = null) {
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'Aymakan');
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'Aymakan');
        $entry_date = date('Y-m-d H:i:s');
        $pickup_date = date("Y-m-d", strtotime($entry_date));

        $API_URL = $counrierArr['api_url'];
        $api_key = $counrierArr['auth_token'];
         $currency = "SAR";

        $weight = $ShipArr['weight'];

        if ($ShipArr['mode'] == 'CODpp') {
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
           "requested_by" => $senderArr['name'],
            "declared_value" => $ShipArr['total_cod_amt'],
            "declared_value_currency" => $currency,
            "price_set" => $price_set,
            "reference" => $ShipArr['uniqueid'],
            "is_cod" => $is_cod,
            "cod_amount" => $cod_amount,
            "currency" => $currency,
            "delivery_name" => $recevierArr['name'],
            "delivery_email" => $recevierArr['email'],
            "delivery_city" => $receiver_city,
            "delivery_address" => $recevierArr['address'],
            "delivery_country" => 'SA',
            "delivery_phone" => $recevierArr['phone'],
            "delivery_description" => $item_description,
            "collection_name" => $senderArr['name'],
            "collection_address" => $senderArr['address'],
            "collection_email" => $senderArr['email'],
            "collection_city" => $sender_city,
            "collection_postcode" => $s_zip,
            "collection_country" => 'SA',
            "collection_phone" => $senderArr['phone'],
            "pickup_date" => $pickup_date,
            "weight" => $ShipArr['weight'],
            "pieces" =>$ShipArr['boxes']
        );
        print_r($all_param_data);
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
        //echo "<br><br><br>";    print_r($response); exit; 
        curl_close($ch);
        return $response;
    }

    public function SMSAArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null) {
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'samsa_city');
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'samsa_city');
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] == 'COD1') {
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
                  <refNo>' . $ShipArr['uniqueid'] . '</refNo>
                  <sentDate>' . date('d/m/Y') . '</sentDate>
                  <idNo>' . $ShipArr['uniqueid'] . '</idNo>
                  <cName>' . $recevierArr['name'] . '</cName>
                  <cntry>KSA</cntry>
                  <cCity>' . $receiver_city . '</cCity>
                  <cZip>' . $ShipArr['sender_zip'] . '</cZip>
                  <cPOBox>45</cPOBox>
                  <cMobile>' . $recevierArr['phone'] . '</cMobile>
                  <cTel1>' . $recevierArr['name'] . '</cTel1>
                  <cTel2>' . $recevierArr['name'] . '</cTel2>
                  <cAddr1>' . $recevierArr['address'] . '</cAddr1>
                  <cAddr2>' . $recevierArr['address'] . '</cAddr2>
                  <shipType>DLV</shipType>
                  <PCs>' . $ShipArr['boxes'] . '</PCs>
                  <cEmail>' . $recevierArr['email'] . '</cEmail>
                  <carrValue>2</carrValue>
                  <carrCurr>2</carrCurr>
                  <codAmt>' . $codValue . '</codAmt>
                  <weight>' . $ShipArr['weight'] . '</weight>
                  <custVal>2</custVal>
                  <custCurr>3</custCurr>
                  <insrAmt>34</insrAmt>
                  <insrCurr>3</insrCurr>
                  <itemDesc>' . $ShipArr['description']. '</itemDesc>
                  <sName>' .    $senderArr['name'] . '</sName>
                  <sContact>' . $senderArr['phone'] . '</sContact>
                  <sAddr1>' . $senderArr['address'] . '</sAddr1>
                  <sAddr2>' . $senderArr['address'] . '</sAddr2>
                  <sCity>' . $sender_city . '</sCity>
                  <sPhone>' . $senderArr['phone'] . '</sPhone>
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
        return $respon;
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

    public function GetallperformationDetailsQry($frwd_throw = null, $status = null, $from = null, $to = null) {
     if ($frwd_throw != 0)
            $condition .= " and shipment_fm.frwd_company_id='" . $frwd_throw . "'";
        //$objSmarty->assign("frwd_throw", $_REQUEST['frwd_throw']);

        $from_date = $from;
        $to_date = $to;
        if ($from_date != 0 && $to_date != 0) {
            $condition .= " and DATE(shipment_fm.entrydate) BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
        }

        $delivered = $status;
        if ($delivered == 'running') {
            $condition .= " and  delivered in(1,2,3,4,5)";
        } else {
            $condition .= " and delivered='$delivered'";
        }

        $query = $this->db->query("SELECT courier_company.company,shipment_fm.* FROM shipment_fm join courier_company on shipment_fm.frwd_company_id= courier_company.id WHERE  shipment_fm.deleted='N' and shipment_fm.status='Y' and shipment_fm.super_id='".$this->session->userdata('user_details')['super_id']."'   $condition");
        // echo $this->db->last_query(); 
        return $query->result_array();
    }

    //Naqel Start

 public function NaqelArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null, $Auth_token = null) 
 {
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'naqel_city_code');
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'naqel_city_code');
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
                                        <tem:PhoneNumber>'.$senderArr['phone'].'</tem:PhoneNumber>
                                        <tem:POBox></tem:POBox>
                                        <tem:ZipCode></tem:ZipCode>
                                        <tem:Fax></tem:Fax>
                                        <tem:FirstAddress>'.$senderArr['address'].'</tem:FirstAddress>
                                        <tem:Location>' . $sender_city . '</tem:Location>
                                        <tem:CountryCode>KSA</tem:CountryCode>
                                        <tem:CityCode>' . $sender_city . '</tem:CityCode>
                                    </tem:ClientAddress>

                                    <tem:ClientContact>
                                        <tem:Name>' . $senderArr['name'] . '</tem:Name>
                                        <tem:Email>' . $senderArr['email'] . '</tem:Email>
                                        <tem:PhoneNumber>'.$senderArr['phone'] . '</tem:PhoneNumber>
                                        <tem:MobileNo>' . $senderArr['phone'] . '</tem:MobileNo>
                                    </tem:ClientContact>

                                    <tem:ClientID>'.$user_name.'</tem:ClientID>
                                    <tem:Password>'.$password.'</tem:Password>
                                    <tem:Version>9.0</tem:Version>
                                    </tem:ClientInfo>

                                    <tem:ConsigneeInfo>
                                    <tem:ConsigneeName>' .$recevierArr['name'].'</tem:ConsigneeName>
                                    <tem:Email>' . $recevierArr['email'] . '</tem:Email>
                                    <tem:Mobile>' . $recevierArr['phone'] . '</tem:Mobile>
                                    <tem:PhoneNumber>' . $recevierArr['name'] . '</tem:PhoneNumber>
                                    <tem:Address>' .$receiver_city . '</tem:Address>
                                    <tem:CountryCode>KSA</tem:CountryCode>
                                    <tem:CityCode>' . $receiver_city .'</tem:CityCode>
                                    </tem:ConsigneeInfo>

                                    <tem:BillingType>' . $BillingType . '</tem:BillingType>
                                    <tem:PicesCount>' . $ShipArr['boxes'] . '</tem:PicesCount>
                                    <tem:Weight>' . $ShipArr['weight'] . '</tem:Weight>
                                    <tem:DeliveryInstruction> </tem:DeliveryInstruction>
                                    <tem:CODCharge>' . $ShipArr['total_cod_amt'] . '</tem:CODCharge>
                                    <tem:CreateBooking>false</tem:CreateBooking>
                                    <tem:isRTO>false</tem:isRTO>
                                    <tem:GeneratePiecesBarCodes>false</tem:GeneratePiecesBarCodes>
                                    <tem:LoadTypeID>36</tem:LoadTypeID>
                                    <tem:DeclareValue>0</tem:DeclareValue>
                                    <tem:GoodDesc>' . $complete_sku . '</tem:GoodDesc>
                                    <tem:RefNo>' .  $ShipArr['uniqueid'] . '</tem:RefNo>
                                    <tem:InsuredValue>0</tem:InsuredValue>
                                    <tem:GoodsVATAmount>0</tem:GoodsVATAmount>
                                    <tem:IsCustomDutyPayByConsignee>false</tem:IsCustomDutyPayByConsignee>
                                </tem:_ManifestShipmentDetails>
                            </tem:CreateWaybill>
                        </soapenv:Body>
                        </soapenv:Envelope>';   
                //echo "<pre>"; print_r($xml_new); //exit; 
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
                  //echo "<br><br><pre> complicated_awb = "; print_r($complicated_awb); die;

                return $complicated_awb;
            
            

    }
    public function ShipsyArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$recevierArr=null,$senderArr=null, $Auth_token = null) {
        //print_r($ShipArr);exit;
        $sender_city = getdestinationfieldshow($ShipArr['city'], 'shipsy_city');
        $receiver_city = getdestinationfieldshow($recevierArr['branch_location'], 'shipsy_city');
            if ($ShipArr['mode'] == 'COD') {
                    $total_cod_amt = $ShipArr['total_cod_amt'];
                } elseif ($ShipArr['mode'] == "CC") {
                    $total_cod_amt = 0;
                }
				if($box_pieces==0){
					$box_pieces = $ShipArr['boxes'];
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
                                            "description" =>$ShipArr['description'],
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
                                            "customer_reference_number" => $ShipArr['uniqueid'],
                                            "is_risk_surcharge_applicable" => 1,
                                            "origin_details" => Array
                                                (
                                                    "name" => $senderArr['name'],
                                                    "phone" => $senderArr['phone'],
                                                    "alternate_phone" => '',
                                                    "address_line_1" => $senderArr['address'],
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" => $sender_city,
                                                    "state" => '',
                                                    "email" => $senderArr['email'],
													
                                                ),

                                            "destination_details" => Array
                                                (
                                                    "name" => $recevierArr['name'],
                                                    "phone" => $recevierArr['phone'],
                                                    "alternate_phone" => "",
                                                    "address_line_1" => $recevierArr['address'],
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" => $receiver_city,
                                                    "state" => '',
                                                    "email" => $recevierArr['email'],
                                                ),

                                            "pieces_detail" => Array
                                                (
                                                    [0] => Array
                                                        (
                                                            "description" => $ShipArr['description'],
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


//Naqel ends 





}
