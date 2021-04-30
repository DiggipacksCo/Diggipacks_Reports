<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ccompany_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    
    public function ccNamebiid($ccid=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        
        if(!empty($ccid))
        {
        $this->db->where('id', $ccid);
        }
        $this->db->where('deleted', 'N');
        $this->db->order_by('company');
        $this->db->select('company');
        $query = $this->db->get('courier_company');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            $result= $query->row_array();
             return  $result['company'];
        }
    }


    public function all($data=array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        
        if(!empty($data['cc_id']))
        {
        $this->db->where('id', $data['cc_id']);
        }
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

    public function GetUpdateDeliveryCOmpanySeller($data = array(), $data_w) {
        return $this->db->update('courier_company_seller', $data, $data_w);
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

    public function GetSlipNoDetailsQry($slip_no = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('slip_no', $slip_no);
        $this->db->where('deleted', 'N');
        // $this->db->where('status', 'Y');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->row_array();
    }

   

    public function GetdeliveryCompanyUpdateQry($cc_id = null,$ShipArr_custid = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('cc_id', $cc_id);
        $this->db->select('*');
        $this->db->from('courier_company_seller');
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where('cust_id', $ShipArr_custid);
        $this->db->order_by("company");
        $query = $this->db->get();
        //echo $this->db->last_query();//exit;

        if ($query->num_rows()> 0)
        {
            //echo "num rows = ".$query->num_rows(); 
            // echo $this->db->last_query();exit;
            return $query->row_array();
        }
        else 
        {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('cc_id', $cc_id);
            $this->db->select('*');
            $this->db->from('courier_company');
            $this->db->where('deleted', 'N');
            $this->db->where('status', 'Y');
            $this->db->order_by("company");
            $query = $this->db->get();
           // echo $this->db->last_query();exit;
            return $query->row_array();

        }
        

    }



    public function getdestinationfieldshow($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT $field FROM country where id='$id' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }


    public function Update_Manifest_Status($slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null, $barq_order_id= null) 
    {

       if ($company == 'Esnad'){
            $label_type = 1;
        }
        elseif ($company == 'Barqfleet')
        { 
                $label_type = 0;
                $updateArr = array('3pl_date' => $CURRENT_DATE, '3pl_name' => $c_id, '3pl_awb' => trim($client_awb), '3pl_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type,'code' => 'AT', 'pstatus' => 6);   
                  
        }
        else
        {
            $label_type = 0;
            $updateArr = array('3pl_date' => $CURRENT_DATE, '3pl_name' => $c_id, '3pl_awb' => trim($client_awb), '3pl_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type,'code' => 'AT', 'pstatus' => 6);
        }
        
        $this->GetmanifestUpdate_forward($updateArr, $slipNo);
        return true;
    }

    public function GetSkuData($itemData = array(),$sellerID= array()){
        $sql = "SELECT items_m.sku, inventory_damage.quantity  FROM `inventory_damage` JOIN items_m ON items_m.id=inventory_damage.item_sku WHERE inventory_damage.id  IN (".implode(',',$itemData).") and inventory_damage.seller_id=".$sellerID." and inventory_damage.return_update='N' and items_m.super_id=1";
        $ci = & get_instance();
        $ci->load->database();
        $query = $ci->db->query($sql);
        $result = $query->result_array();    
        return $result;
    }
    
    public function get_damage_sku_details($itemID,$sellerID){
        $sql = "SELECT items_m.sku, inventory_damage.quantity FROM `inventory_damage` JOIN items_m ON items_m.id=inventory_damage.item_sku WHERE inventory_damage.id=".$itemID."   and inventory_damage.seller_id=".$sellerID." and inventory_damage.return_update='N' and items_m.super_id=1 and inventory_damage.status_type='Damage' ";
        $ci = & get_instance();
        $ci->load->database();
        $query = $ci->db->query($sql);
        return  $query->result_array();
    }
    
    
    public function Update_Manifest_Return_Status($slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null,$dataArray,$shiparray,$itemData){
        
        switch($company){
            case 'Esnad': $label_type = 1; break;
            case 'Barqfleet': $label_type = 0; break;
            default: $label_type = 0; break;
        }
      //  print "<pre>"; print_r($this->session->userdata);die;
        
        if(!empty($itemData) && count($itemData)>0){
            foreach($itemData as $itemid){
                
                $result = $this->Ccompany_model->get_damage_sku_details($itemid,$dataArray['sellerid']);
                print "<pre>"; print_r($result);
                if(is_array($result) && count($result)>0){
                    $updateArr = array(
                        'uniqueid'=>$slipNo,
                        'seller_id'=>$dataArray['sellerid'],
                        'sku'=>$result[0]['sku'],
                        'qty'=>$result[0]['quantity'],
                        'user_id'=>$this->session->userdata('user_details')['user_id'],
                        'user_type'=>$this->session->userdata('user_details')['user_type'],
                        'super_id'=>$this->session->userdata('user_details')['super_id'],
                        'address'=>$shiparray['reciever_address'],
                        'city'=>$shiparray['destination'],
                        'boxes'=>$dataArray['boxes'],
                        'pack_type'=>$dataArray['pack_type'],
                        'return_type'=>'Y',
                        'r_3pl_date' => $CURRENT_DATE,
                        'r_3pl_name' => $c_id,
                        'r_3pl_awb' => trim($client_awb),
                        'r_3pl_label' => $fastcoolabel,
                        'label_type' => $label_type,
                        'code' => 'AT',
                        'pstatus' => 6
                    );
                    
                    $this->db->insert('pickup_request', $updateArr);
                    //echo $this->db->last_query();die;
                    $data = array('return_update'=>'Y');
                    $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
                    $this->db->update('inventory_damage', $data, array('id' => $itemid));
                }
            }
        }
        return true;
    }

    public function GetmanifestUpdate_forward(array $data, $awb = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('pickup_request', $data, array('uniqueid' => $awb));
     echo $this->db->last_query();
    }

    public function Getskudetails_forward($slip_no=null)	{
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('diamention_fm.sku,diamention_fm.description,diamention_fm.piece,diamention_fm.cod,items_m.name');
        $this->db->from('diamention_fm');
        $this->db->join('items_m', 'items_m.sku=diamention_fm.sku');
        $this->db->where('diamention_fm.slip_no',$slip_no);
        $query = $this->db->get();
        return $query->result_array();
        }

    public function GetshipmentUpdate_forward(array $data, $awb = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('shipment_fm', $data, array('slip_no' => $awb));
       //   echo $this->db->last_query();
    }

    public function GetstatuInsert_forward(array $data) {

        $this->db->insert('status_fm', $data);
        //echo $this->db->last_query();
    }

    public function AramexArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$box_pieces1= null ) 
    {
        $sender_city = $this->getdestinationfieldshow($ShipArr['origin'], 'aramex_city');
        $reciever_city = $this->getdestinationfieldshow($ShipArr['destination'], 'aramex_city');
        $date = (int) microtime(true) * 1000;
        
        
        if(empty($box_pieces1)){
            $box_pieces = 1;
        }
        else { 
             $box_pieces = $box_pieces1 ; }

        if($ShipArr['weight']==0)
        {  $weight= 1;
        }
        else { $weight = $ShipArr['weight'] ; }
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
                        'Reference1' => $ShipArr['booking_id'],
                        'Reference2' => '',
                        'AccountNumber' => $counrierArr['courier_account_no'],
                        'PartyAddress' => array(
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
                        'Contact' => array(
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
                    'Consignee' => array(
                        'Reference1' => '',
                        'Reference2' => '',
                        'AccountNumber' => '',
                        'PartyAddress' => array(
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
                        'Contact' => array(
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
                            'Value' => $weight,
                            //'Value' => '1',
                        ),
                        'ChargeableWeight' => NULL,
                        'DescriptionOfGoods' => $complete_sku,
                        'GoodsOriginCountry' => 'SA',
                        'NumberOfPieces' => $box_pieces,
                        'ProductGroup' => 'DOM',
                        'ProductType' => 'CDS',
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
                    'ForeignHAWB' => $ShipArr['slip_no'],
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
       //echo "<pre>"; print_r($params); exit; 
        return $params;
    }

    public function AxamexCurl($url = null, array $headers, $dataJson = null, $c_id = null, array $ShipArr) {
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
            
        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);

        return $awb_array;
    }

    public function Update_Shipment_Status($slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null, $barq_order_id= null,$qty=null) 
    {
        $updateArr = array(); 
       if ($company == 'Esnad'){
            $label_type = 1;
            $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id);         

        }
        elseif ($company == 'Barqfleet')
          {  $label_type = 0;
              $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id);         
          }
        elseif ($company == 'Saudi Post')
        {  $label_type = 0;
            $updateArr = array('pieces'=>$qty,'frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id);         
          }
      else
        {
            $label_type = 0;
            $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type);
        }
  
        $this->GetshipmentUpdate_forward($updateArr, $slipNo);
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
            'user_id' => $this->session->userdata('user_details')['user_id'],
            'user_type' => 'fulfillment',
            'comment' => $comment,
            'code' => 'FWD',
            'super_id' => $this->session->userdata('user_details')['super_id'],
        );
        $this->GetstatuInsert_forward($statusArr);
        //send_message($slipNo);

        return true;
    }

    public function SafeArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1=null) {
        $sender_city_safe = getdestinationfieldshow($ShipArr['origin'], 'safe_arrival');
        $receiver_city_safe = getdestinationfieldshow($ShipArr['destination'], 'safe_arrival');

       $API_URL = $counrierArr['api_url'];
       
       if(empty($box_pieces1))
       { $box_pieces = 1;  }
         else { $box_pieces = $box_pieces1 ; }

        if($ShipArr['weight']==0)
            {  $weight= 1;
            }
            else { $weight = $ShipArr['weight'] ; }

        if($ShipArr['mode'] == "COD"){
            $pay_mode = "cash";
            $paid = 0;
        }
        else {
            $pay_mode = "credit_balance";
            $paid = 1;
        }

        $sender_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['sender_name'],
            "email" => $ShipArr['sender_email'],
            "street" => $ShipArr['sender_address'],
            "city" => array(
                "id" =>$sender_city_safe
            ),
            "country" => array(
                "id" => 191
            ),
           "phone" =>$ShipArr['sender_phone'],
        );
        
        
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => $ShipArr['reciever_email'],
            "street" => $ShipArr['reciever_address'],
            "city" => array(
                "id" => $receiver_city_safe
            ),

            "phone" => $ShipArr['reciever_phone'],
        );
        $dimensions = array(
            "weight" => $weight
        );
        $package_type = array(
            "courier_type" => 'IN_5_DAYS'
        );
        $charge_items = array(
            array(
                "paid" => $paid,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode']
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => "credit_balance",
            "payer" => "recipient",
            "parcel_value" => $ShipArr['total_cod_amt'],
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => $box_pieces,
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );

      //  echo "<br/><pre>";print_r($param); //die;


        $header = array(
            "Authorization" => "Bearer ".$Auth_token,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );

        $dataJson = json_encode($param);
      $response = send_data_to_safe_curl($dataJson, $Auth_token, $API_URL); 

        
        $logresponse =   json_encode($response);  
        $successres = $safe_response['status'];
            //echo "<pre>"; print_r($response)   ;    //die;
       
        if($successres == "success") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);

        return $response;
    }

    public function ThabitArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1=null) 
    {
      
        $sender_city_safe = getdestinationfieldshow($ShipArr['origin'], 'city');
        $receiver_city_safe = getdestinationfieldshow($ShipArr['destination'], 'safe_arrival');

       $API_URL = $counrierArr['api_url'];
        if(empty($box_pieces1))
       { $box_pieces = 1;  }
         else { $box_pieces = $box_pieces1 ; }

        if($ShipArr['weight']==0)
            {  $weight= 1;
            }
            else { $weight = $ShipArr['weight'] ; }

        if($ShipArr['mode'] == "COD"){
            $pay_mode = "cash";
            $paid = 0;
        }
        else {
            $pay_mode = "credit_balance";
            $paid = 1;
        }

        $sender_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['sender_name'],
            "email" => $ShipArr['sender_email'],
            "street" => html_entity_decode($ShipArr['sender_address']),
            "city" => array(
                "code" =>strtolower($sender_city_safe)
            ),
            "country" => array(
                "id" => 191
            ),
           "phone" =>$ShipArr['sender_phone'],
        );
        
        
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => $ShipArr['reciever_email'],
            "street" => html_entity_decode($ShipArr['reciever_address']),
            "city" => array(
                "id" => $receiver_city_safe
            ),
            "country" => array(
                "id" => 191
            ),

            "phone" => $ShipArr['reciever_phone'],
        );
        $dimensions = array(
            "weight" => $weight
        );
        $package_type = array(
            "courier_type" => 'express_delivery'
        );
        $charge_items = array(
            array(
                "paid" => $paid,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode']
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => "credit_balance",
            "payer" => "recipient",
            "parcel_value" => $ShipArr['total_cod_amt'],
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => $box_pieces,
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );
        
        $header = array(
            "Authorization" => "Bearer ".$Auth_token,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );

        $dataJson = json_encode($param);
        $response = send_data_to_thabit_curl($dataJson, $Auth_token, $API_URL);      
        $logresponse =   json_encode($response);  
        $successres = $safe_response['status'];
       
       
        if($successres == "success") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);

        return $response;
    }

    public function EsnadArray(array $ShipArr, array $counrierArr, $esnad_awb_number = null, $complete_sku = null, $Auth_token = null,$c_id=null,$box_pieces1=null) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'esnad_city');        
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'esnad_city');
        $receiver_cityID = getdestinationfieldshow($ShipArr['destination'], 'esnad_city_code');

        $declared_charge = $ShipArr['total_cod_amt'];
        $iscod = false;
        $cod_amount = $ShipArr['total_cod_amt'];
        
        if ($ShipArr['mode'] == 'COD') {
            $pay_mode = "COD";
            $declared_charge = 0;
            $iscod = true;
        } else {
            $pay_mode = "PP";
            $cod_amount = 0;
            $iscod = false;
            $declared_charge = $ShipArr['total_cod_amt'];
        }
        $comp_api_url = $counrierArr['api_url'];  
        
        $Auth_token = $counrierArr['auth_token'];     
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
        
        $param = array(
                "currency"=>"SAR",
                "codAmount"=>$cod_amount,
                "customerCode"=> "Kedan",
                "customerNo"=> $ShipArr['slip_no'],
                "trackingNo"=> $ShipArr['slip_no'],
                "ifpickup"=> false,
                "token"=>$Auth_token,
                "isCod"=> $iscod,
                "orderAmount" =>$declared_charge,
                "packageList"=> array(array(
                    "packageCode"=> $complete_sku,
                    "packageHeight"=> 0,
                    "packageLength"=>0,
                    "packageVolume"=> $box_pieces,
                    "packageWeight"=> (float)$weight,
                    "packageWidth"=>0
                    )
                ),
                "receiver"=>array(
                    "address"=>html_entity_decode($ShipArr['reciever_address']) ,
                    "cityId"=> $receiver_cityID,//(int)$ShipArr['destination'],
                    "cityName"=> $receiver_city,
                    "countryId"=> 1876,
                    "countryName"=> "Saudi Arabia",
                    "name"=> $ShipArr['reciever_name'],
                    "phone"=> $ShipArr['reciever_phone']
                ),
                "sender"=>array(
                    "address"=> html_entity_decode($ShipArr['sender_address']),
                    "cityName"=> $sender_city,
                    "countryId"=> 1876,
                    "countryName"=> "Saudi Arabia",
                    "name"=>$ShipArr['sender_name'],
                    "phone"=> $ShipArr['sender_phone']
                ),
                
                "totalInnerCount"=>1,
                "totalPackageCount"=> 1,
                "totalWeight"=>$weight,
                "totalVolume"=>$box_pieces,
                
            
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

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        return $response;
}


    public function LabaihArray($ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'labaih');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'labaih');
        $lat = getdestinationfieldshow($ShipArr['origin'], 'latitute');
        $lang = getdestinationfieldshow($ShipArr['origin'], 'longitute');
        $declared_charge = $ShipArr['total_cod_amt'];
        $api_url = $counrierArr['api_url'];
        $senderData= Getsite_configData();
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'COD';
        } else {
            $cod_collection_mode = 'PREPAID';
            $cod_amount = 0;
        }
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       
        if(!empty($receiver_city ))
        {

        

        $comp_api_url = $counrierArr['api_url']."order/create";
        $pickupDate = date("Y-m-d");
        $deliveryDate = date('Y-m-d', strtotime($pickupDate . '+ 2 days'));
        if(!empty($lat)&& !empty($lang))
       $latlang= $lat . ',' . $lang;
       else
       $latlang="";

       if(!empty($ShipArr['dest_lat']) && !empty($ShipArr['dest_lng']))
             $lattitute= $ShipArr['dest_lat'] . ',' . $ShipArr['dest_lng'];
       else
          $lattitute="";

      
        $apikey= $api_url."order/pickupPoints?api_key=".$counrierArr['auth_token'];
        $pickuppointarray = json_decode(file_get_contents($apikey),true);
        $pickuppoint = $pickuppointarray['data'][0]['pickupPointName'];


        $Data_array = array(
            'api_key' => $counrierArr['auth_token'],            
            'customerOrderNo' => $ShipArr['slip_no'], 
            'noOfPieces' => $box_pieces,
            'weightKg' => $weight,
            'itemDescription' => $complete_sku,
            'paymentMethod' => $cod_collection_mode,
            'paymentAmount' => $cod_amount,
            'consigneeName' => $ShipArr['reciever_name'], 
            'consigneeEmail' => $ShipArr['reciever_email'],
            'consigneeMobile' => $ShipArr['reciever_phone'],
            'consigneePhone' => $ShipArr['reciever_phone'], 
            'consigneeCity' => $receiver_city, 
            'consigneeAddress' => $ShipArr['reciever_address'],
            'consigneeFlatFloor' => '',
            'consigneeLatLong' => $lattitute,
            'consigneeSplInstructions' => $ShipArr['status_describtion'],
            'store' => $ShipArr['sender_name'], 
            'shipperName' => $senderData['company_name'],
            'shipperMobile' => $senderData['phone'],
            'shipperEmail' => $senderData['email'],
            'shipperCity' => $sender_city,
            'shipperDistrict' => $sender_city,
            'shipperAddress' => $senderData['company_address'],
            'shipperLatLong' =>  $latlang,
            'pickuppoint_id'=> $pickuppoint,
        );
        

        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
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
            $response_array = json_decode($response, true);   
            $logresponse =   json_encode($response_array);  
            $successres = $response_array['status'];

            if($successres == 200) 
                {
                    $successstatus  = "Success";
                }else {
                    $successstatus  = "Fail";
                }
        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);

        return $response_array;
        }
        else
        {
            $logresponse = "Receiver city empty";
            $successstatus  = "Fail";
            $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
            return $response_array=array('message'=>'receiver city empty');
        }



    }
    public function ClexArray($ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'clex');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'clex');
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
              
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
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
            'consignee_phone' => $ShipArr['reciever_phone'],
            'consignee_alternate_country_code' => '',
            'consignee_alternate_phone' => '',
            'pieces_count' => $box_pieces,
            'order_date' => date('d-m-Y'),
            'commodity_description' => $complete_sku,
            'pieces' => array(array(
                    'weight_actual' => $weight,
                    'volumetric_width' => '',
                    'volumetric_height' =>'',
                    'volumetric_depth' =>'',
                ))
        );
        $dataJson = json_encode($request_data);
        $access_token = $counrierArr['auth_token'];

        $headers = array(
            "Content-type:application/json",
            "Access-token:$access_token");

        $ch = curl_init($comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);

        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['message'];
        $error = $response_array['error'];

        if($successres == 'Succesfully added.' || $error == false) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        return $response_array;
    }

    public function AjeekArray($ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id) {
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

               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
      
        $items_detail = array(
                array(
                    "description" => $complete_sku,
                    "length" => '',
                    "width" => '',
                    "height" => ''
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
                "pieces" => $box_pieces,
                "total_weight" => $weight,
                "order_items_detail" => $items_detail,
                "api_key" => $api_key,
        );
      
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
      
        if($successres =="Done") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse, $successstatus, $ShipArr['slip_no']);
        return $response_array;
    }

    public function shipmentLog($c_id = null,$description= null,$status= null,$slip_no= null){
        $CURRENT_DATE = date("Y-m-d H:i:s");

        $logarr  = array(
            'slip_no' => $slip_no, 
            'cc_id' => $c_id, 
            'log' => $description, 
            'status' =>$status, 
            'super_id' => $this->session->userdata('user_details')['super_id'], 
            'entry_date' =>$CURRENT_DATE, 
        );       

        $retr = $this->GetlogInsert($logarr); 

    }
    public function GetlogInsert($data = array()) {

            $this->db->insert('frwd_shipment_log', $data);
           // echo $this->db->last_query(); die;
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

    public function BarqfleethArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null, $c_id = null,$box_pieces1=null) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'city');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'city');
        $lat = getdestinationfieldshow($ShipArr['origin'], 'latitute');
        $lang = getdestinationfieldshow($ShipArr['origin'], 'longitute');
        $declared_charge = $ShipArr['total_cod_amt'];

        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }

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
        //$request_url = $counrierArr['api_url'];
        $request_url = $counrierArr['api_url']."/login";
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
                    "qty" =>  $box_pieces,
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
        //   echo "<pre>"; print_r($params); 
        // die; 

        $headers = array("Content-type:application/json");
        $url = $counrierArr['api_url']."/orders";
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

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        return $response_ww;
    }
    public function ZajilArray($ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'zajil');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'zajil');
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       
        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'CASH';
            
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
                    'weight' => $weight,
                    'declared_value' => $declared_charge,
                    'declared_price' => '',
                    'cod_amount' => $cod_amount,
                    'cod_collection_mode' => $cod_collection_mode,
                    'prepaid_amount' => '',
                    'num_pieces' => $box_pieces,
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
                                'weight' => $weight,
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

        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['data'][0]['success'];        
        if($response['status'] == 'OK' && $successres == true) 
            {
                $successstatus  = "Success";
            } else {
                $successstatus  = "Fail";
            }
        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        return $response_array;
    }

    public function MakdoonArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1=null) {
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'makhdoom');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'makhdoom');
        $API_URL = $counrierArr['api_url'];
        $create_order_url = $counrierArr['create_order_url'];
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       

        $sender_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['sender_name'],
            "email" => $ShipArr['sender_email'],
            'apartment' => '',
            'building' => '',
            "street" => $ShipArr['sender_address'],
            "city" => array(
                'code' => $sender_city
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
                'code' => $receiver_city
            ),
            'country' => array(
                'id' => 191,
            ),
            "phone" => $ShipArr['reciever_phone'],
            'landmark' => '',
        );
        $dimensions = array(
            "weight" => $weight,
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
            "piece_count" => $box_pieces,
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );
       
        $dataJson = json_encode($param);
        $response = send_data_to_makdoom_curl($dataJson, $Auth_token, $create_order_url);
        $logresponse =   json_encode($response);  
        $successres = $response['status'];
        
        if($successres == 'success') 
        {
            $successstatus  = "success";
        } else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        return $response;
    }

    public function SaeeArray(array $ShipArr, array $counrierArr, $Auth_token = null,$c_id,$box_pieces1) {
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'city');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'city');
        $lat = getdestinationfieldshow($ShipArr['origin'], 'latitute');
        $lang = getdestinationfieldshow($ShipArr['origin'], 'longitute');
        $API_URL = $counrierArr['api_url'];
        $Secretkey = $counrierArr['auth_token'];

        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       
        if ($ShipArr['mode'] == 'COD') {
            $BookingMode = 'COD';
            $codValue = $ShipArr['total_cod_amt'];
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
            "quantity" => $box_pieces,
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
        $live_url = $API_URL."/new?secret=$Secretkey";
        $headers = array("Content-type:application/json");

        if(empty($receiver_city)){
            $response = array('error' => 'Receiver city is empty ');
            return $response; 
        }
        else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $live_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
            $response = curl_exec($ch);
            curl_close($ch);
        // echo  $response  ;    

            $response = json_decode($response, true); //die; 
            $logresponse =   json_encode($response);  
            $successres = $response['success']; 

            if($successres == 'true') 
            {
                $successstatus  = "Success";
            }else {
                $successstatus  = "Fail";
            }

            $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
            return $response;
        }
    }


    public function AymakanArray(array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null,$box_pieces1) {
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'Aymakan');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'Aymakan');
        $store = getallsellerdatabyID($ShipArr['cust_id'], 'company');        
        $entry_date = date('Y-m-d H:i:s');
        $pickup_date = date("Y-m-d", strtotime($entry_date));
        $API_URL = $counrierArr['api_url'];
        $api_key = $counrierArr['auth_token'];
        $currency = "SAR";  

         if(empty($box_pieces1))
         {
            $box_pieces = 1;
         }
         else
         { 
            $box_pieces = $box_pieces1 ; 
         }
 
         if($ShipArr['weight']==0)
         {  
            $weight= 1;
         }
         else 
         { 
            $weight = $ShipArr['weight'] ; 
         }        

        if ($ShipArr['mode'] == 'COD') {
            $price_set = 113;
            $is_cod = 1;
            $cod_amount = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['mode'] == 'CC') {
            $is_cod = 0;
            $price_set = 364;
            $cod_amount = 0;
        }
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
            "weight" => $weight,
            "pieces" => $box_pieces
        );     
        $json_final_date = json_encode($all_param_data);
        $headers = array(
            "Accept:application/json",
            "Authorization: $api_key");

        if(empty($receiver_city))
        {
            $resp = array(
                'errors' => array(
                    'reference' => array(                       
                             'Receiver city is empty'                       
                    ),
                ),
            );
            $response =   json_encode($resp); 
            return $response; 
        }
        else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $API_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
            $response = curl_exec($ch);
            curl_close($ch);
            $responseArray = json_decode($response, true);
            $logresponse =   json_encode($response);  
            $successres = $responseArray['errors'];

            if(empty($successres)) 
            {
                $successstatus  = "Success";
            }else {
                $successstatus  = "Fail";
            }

            $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
            return $response;
        }
    }

    public function SMSAArray($ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id) {
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'samsa_city');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'samsa_city');
        $store = getallsellerdatabyID($ShipArr['cust_id'], 'company');
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] == 'COD') {
            $codValue = $cod_amount;
        } else {
            $codValue = 0;
        }
        if ($complete_sku == '') {
            $complete_sku = 'Goods';
        }
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        {  $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
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
                  <cAddr1>' . $ShipArr['reciever_address'] . '</cAddr1>
                  <cAddr2></cAddr2>
                  <shipType>DLV</shipType>
                  <PCs>' . $box_pieces . '</PCs>
                  <cEmail>' . $ShipArr['reciever_email'] . '</cEmail>
                  <carrValue>2</carrValue>
                  <carrCurr>2</carrCurr>
                  <codAmt>' . $codValue . '</codAmt>
                  <weight>' . $weight. '</weight>
                  <custVal>2</custVal>
                  <custCurr>3</custCurr>
                  <insrAmt>34</insrAmt>
                  <insrCurr>3</insrCurr>
                  <itemDesc>' .  $complete_sku . '</itemDesc>
                  <sName>' .$store . '</sName>
                  <sContact>' . $ShipArr['sender_name'] . '</sContact>
                  <sAddr1>' .$ShipArr['sender_address'] . '</sAddr1>
                  <sAddr2></sAddr2>
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

                 $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$c_id);


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

public function NaqelArray(array $ShipArr, array $counrierArr, $complete_sku = null, $box_pieces1 = null, $Auth_token = null, $c_id = null) 
{
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'naqel_city_code');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'naqel_city_code');
            if ($ShipArr['mode'] == 'CC') {
                    $BillingType = 1;
                } elseif ($ShipArr['mode'] == "COD") {
                    $BillingType = 5;
                }
            if(empty($box_pieces1))
                {
                    $box_pieces = 1;
                }
                else
                { 
                     $box_pieces = $box_pieces1 ; 
                }
        
            if($ShipArr['weight']==0)
                {  
                    $weight= 1;
                }
                else { 
                    $weight = $ShipArr['weight'] ; 
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
                                    <tem:PicesCount>' . $box_pieces . '</tem:PicesCount>
                                    <tem:Weight>' . $weight. '</tem:Weight>
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
               
                $headers = array(
                    "Content-type: text/xml",
                    "Content-length: ".strlen($xml_new),
                );
                if(empty($receiver_city)){
                    $awb_array = array('Message' => 'Receiver city is empty ');
                    return $awb_array; 
                }
                else{
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
                $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $respon);
                $xml2 = new SimpleXMLElement($respon);  
                $again = $xml2;
                $a = array("qwb" => $again);

                $complicated_awb = ($a['qwb']->Body->CreateWaybillResponse->CreateWaybillResult);
                curl_close($ch);

                 $awb_array = json_decode(json_encode((array) $complicated_awb), TRUE);
                 $logresponse =   json_encode($awb_array);  
                    $successres = $awb_array['HasError'];
                   
                    if($successres!== true) 
                    {
                        $successstatus  = "Success";
                    } else {
                        $successstatus  = "Fail";
                    }

                    $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
                     return $awb_array;
                }
            

}
    public function ShipsyArray(array $ShipArr, array $counrierArr, $complete_sku = null, $box_pieces1 = null, $c_id = null) {
        //print_r($ShipArr);exit;
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'shipsy_city');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'shipsy_city');
            if ($ShipArr['mode'] == 'COD') {
                    $total_cod_amt = $ShipArr['total_cod_amt'];
                } elseif ($ShipArr['mode'] == "CC") {
                    $total_cod_amt = 0;
                }
				       
                if(empty($box_pieces1))
                {
                    $box_pieces = 1;
                }
                else
                { 
                     $box_pieces = $box_pieces1 ; 
                }
        
                if($ShipArr['weight']==0)
                {  
                    $weight= 1;
                }
                else { 
                    $weight = $ShipArr['weight'] ; 
                }
               
                $consignments[] = Array
                                (
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
                                            "weight" => $weight,
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
                                                            "description" => $complete_sku,
                                                            "declared_value" => $total_cod_amt,
                                                            "weight" => $weight,
                                                            "height" => '',
                                                            "length" =>'',
                                                            "width" => ''
                                                        )

                                                )

                                       
                                );
        $all_param_array = Array(
                            "is_international" => '',
                            "consignments" => $consignments

                        );
        $param = json_encode($all_param_array);
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

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);

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
    
    public function ShipadeliveryArray(array $ShipArr, array $counrierArr, $auth_token = null, $c_id = null) {
        ini_set('default_charset', 'UTF-8');
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'shipsa_city');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'shipsa_city');
       
        if ($ShipArr['mode'] == 'COD') {
            $total_cod_amt = $ShipArr['total_cod_amt'];
            $paymentMethod = 'CashOnDelivery';
        }elseif ($ShipArr['mode'] == "CC") {
            $total_cod_amt = 0;
            $paymentMethod = 'Prepaid';
        }
        $description =  $complete_sku;

        if($description==''){
            $description = 'GOODS';
        }
               
             
        
        $number  =  $ShipArr['reciever_phone']; 
        $number = ltrim($number, '966 ');
        $number = ltrim($number, '0');
        $number = '0' . $number;
        $number = str_replace(' ', '', $number);
        
        $Sender = array(
            'name' => $ShipArr['sender_name'],
            'address' => $ShipArr['sender_address'],
            'phone' => $ShipArr['sender_phone'],
            'email' => $ShipArr['sender_email'],
        );
        $Recipient = array(
            'name' => $ShipArr['reciever_name'],
            'address' => $ShipArr['reciever_address'],
            'phone' => $number,
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

      
        if (empty($param[0]['recipient']['city']))
        {
            
            $response = $this->shipmentLog($c_id,'receiver city empty ','Fail', $ShipArr['slip_no']);
            return $response;
        }
        else {
            
              $curl = curl_init();        
                  curl_setopt_array($curl, array(
                  CURLOPT_URL => $counrierArr['api_url']."?apikey=".$counrierArr['auth_token'],
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
                    'x-api-key:'.$counrierArr['auth_token'],
                    'Accept: application/json'
                  ),
                ));

                $response = curl_exec($curl);
                 curl_close($curl);
                 $logresponse =   json_encode($response);                   
                 $response_array = json_decode($response, true);
                 $successres = $response_array[0]['code'];
                    if($successres==0) 
                        {
                           $successstatus  = "Success";
                        }
                    else 
                        {
                            $successstatus  = "Fail";
                        }

                $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
                return $response;
        }


        
      
    }
    
    public function ShipaDelupdatecURL(array $counrierArr,array $ShipArr,$client_awb = null,$box_pieces1=null) {
                    if ($ShipArr['mode'] == 'COD') {
                        $total_cod_amt = $ShipArr['total_cod_amt'];
                        $paymentMethod = 'CashOnDelivery';
                    }
                    elseif ($ShipArr['mode'] == "CC") {
                        $total_cod_amt = 0;
                        $paymentMethod = 'Prepaid';
                    }
                         
                    if(empty($box_pieces1))
                    {
                        $box_pieces = 1;
                    }
                    else
                    { 
                        $box_pieces = $box_pieces1 ; 
                    }

                    if($ShipArr['weight']==0)
                    {  
                        $weight= 1;
                    }
                    else { 
                        $weight = $ShipArr['weight'] ; 
                    }
                
                    $valpiecesarray =  array (
                          'ready' => true,
                          'weight' => (float)$weight,
                          'quantity' => (int)$box_pieces                     
                        );

                    $valpieces =  json_encode($valpiecesarray);                       
                    $curl = curl_init();
                    $client_awb = trim($client_awb); 
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $counrierArr['api_url'].'/'.$client_awb."?apikey=".$counrierArr['auth_token'],
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'PATCH',
                          CURLOPT_POSTFIELDS => $valpieces,
                          CURLOPT_HTTPHEADER => array(
                            'Accept: application/json',
                            'Content-Type: application/json',
                            'x-api-key:'. $counrierArr['auth_token']
                          ),
                        ));
                         $responsepieces = curl_exec($curl);

                            curl_close($curl);
                       return  $responsepieces; 

    }
    public function ShipaDelLabelcURL(array $counrierArr, $client_awb = null) 
    {

        $cURL12 = $counrierArr['api_url'].'/'.$client_awb."/pdf?apikey=".$counrierArr['auth_token']."&template=sticker-6x4&copies=1";
      
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $cURL12,
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
                            
    public function SPArray(array $ShipArr, array $counrierArr, $complete_sku = null,$Auth_token=null, $c_id = null,$box_pieces1=null){
        
        $username = $counrierArr['user_name'];
        $password = $counrierArr['password'];
        $authdata = 'grant_type=password&UserName='.$username.'&password='.$password;

       // print_r( $ShipArr['sku_data']); exit;
        $api_url = $counrierArr['api_url'];
        $curl = curl_init();
       
        curl_setopt_array($curl, array(
          CURLOPT_URL =>  $api_url.'token',
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
        $tokenArray = json_decode($response, true);
        $token=$tokenArray['access_token'];
        
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'saudipost_id');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'saudipost_id');
        
        if ($ShipArr['mode'] == "COD") {
            $PaymentType = 2;
            $total_cod_amt = $ShipArr['total_cod_amt'];
        }else{
            $PaymentType = 1;
            $total_cod_amt = 0;
        }

    
$itemArray=array();
for($ii=1;$ii<$box_pieces;$ii++)
{
  $peiceArray=array(

    "PieceWeight"=> (float)$weight,
    "PiecePrice"=> 0,
    "PieceDescription"=> $complete_sku

  );
array_push($itemArray,$peiceArray);

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
            "Items"=> array(array(
                "ReferenceId"=> $ShipArr['slip_no'],
                "Barcode"=> null,
                "PaymentType"=> $PaymentType,
                "ContentPrice"=> 0,
                "ContentDescription"=> $complete_sku,
                "Weight"=> $weight,
                "BoxLength"=> '',
                "BoxWidth"=> '',
                "BoxHeight"=> '',
                "ContentPriceVAT"=> 0,
                "DeliveryCost"=> 0,
                "DeliveryCostVAT"=> 0,
                "TotalAmount"=> $total_cod_amt,
                "CustomerVAT"=> 0,
                "SaudiPostVAT"=> 0,
                "PiecesCount"=> $box_pieces,
                "ItemPieces"=>$itemArray,
            
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
    //  echo '<br>'. $counrierArr['api_url'].'api/CreditSale/AddUPDSPickupDelivery';
    //    echo 'copy : https://gateway-minasa.sp.com.sa/APIGateway/api/CreditSale/AddUPDSPickupDelivery'; exit;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'].'api/CreditSale/AddUPDSPickupDelivery',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$param,
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer '.$token,
            'Content-Type: application/json',
          ),
        ));
       $response = curl_exec($curl);
        curl_close($curl);
         $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        $successres = $response_array['Items'][0]['Message'];
        if($successres=='Success') 
        {
            $successstatus  = "Success";
        } else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        return $response;
    }


//Naqel ends 
    public function EmdadArray($ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1) 
    {
        $sender_email = $counrierArr['user_name']; //provided by company  :  (column name: password || date
        $password = $counrierArr['password'];
        $url = $counrierArr['api_url'];
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       
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
        $weight = $weight;
       

        //weight should be in kg.:(column name: weight || date type
        $NumberOfParcel = $box_pieces; //(column name: pieces || date type: int || value: 5)

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

        $response = curl_exec($ch);

        curl_close($ch);
        $logresponse =   json_encode($response);  
        $successres = $response['error'];
     
        if($successres == '') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        
        return $response;
    }

    public function Ejack($ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1) 
    {
        $sender_email = $counrierArr['user_name']; //provided by company  :  (column name: password || date
        $password = $counrierArr['password'];
        $url = $counrierArr['api_url'];
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       
        $Receiver_name = $ShipArr['reciever_name'];
        $Receiver_email = $ShipArr['reciever_email'];
        $Receiver_phone = $ShipArr['reciever_phone'];
        $Receiver_address = $ShipArr['reciever_address'];
        if (empty($Receiver_address)) {
            $Receiver_address = 'N/A';
        }

        $Reciever_city = getdestinationfieldshow($ShipArr['destination'], 'city');
        
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
        $s_city = getdestinationfieldshow($ShipArr['origin'], 'city');

        $pay_mode = $ShipArr['mode']; //paymode either CASH or COD:(column name: mode || date
        $codValue = $ShipArr['total_cod_amt']; //COD charges.  :  (column name:     total_cod_amt || date type:
        $product_price = $ShipArr['declared_charge']; //(column name: declared_charge || date type: int || value: 11)
        $booking_id = $ShipArr['slip_no']; // send awb number ajoul
        $shipper_refer_number = $ShipArr['booking_id']; // ajoul ki booking id
        $weight = $weight;
       

        //weight should be in kg.:(column name: weight || date type
        $NumberOfParcel = $box_pieces; //(column name: pieces || date type: int || value: 5)

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

        $response = curl_exec($ch);

        curl_close($ch);
        $logresponse =   json_encode($response);  
        $successres = $response['error'];
     
        if($successres == '') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
        
        return $response;
    }

    public function fastcooArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1 = null) {
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'city');
        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'city');
        $entry_date = date('Y-m-d H:i:s');
        $pickup_date = date("Y-m-d", strtotime($entry_date));
   
        $url =  $counrierArr['api_url'];
        $secKey = $counrierArr['auth_token'];
        $customerId =$counrierArr['courier_account_no'];
        $formate    = "json";
        $method     = "createOrder";
        $signMethod = "md5";
    
    
        if ($ShipArr['mode'] == 'COD') {
            $cod_amount = $ShipArr['total_cod_amt'];
    
        } elseif ($ShipArr['mode'] == 'CC') {         
            $cod_amount = 0;
        }
        if(empty($box_pieces1))
            {
                $box_pieces = 1;
            }
            else
            { 
                 $box_pieces = $box_pieces1 ; 
            }
    
            if($ShipArr['weight']==0)
            {  
                $weight= 1;
            }
            else { 
                $weight = $ShipArr['weight'] ; 
            }
    
        if (empty($receiver_city)){
            $resp = array('msg' => 'receiver city empty');
            $response = json_encode($resp);
            return $response ;
        }
        else {
            $skudetails = array(array(
                "piece"=>  $box_pieces,
                "weight"=> $weight,
                "BookingMode"=> $ShipArr['mode'],
                ));
            $alldata = array(
                    "customerId" => $customerId ,
                    "secret_key" => $secKey, 
                    "BookingMode" => $ShipArr['mode'],
                    "codValue" => $cod_amount,
                    "reference_id" => $ShipArr['slip_no'],
                    "origin" => $sender_city,
                    "destination" => $receiver_city,
                    "service" => 3,
                    "sender_name" =>  $ShipArr['sender_name'],
                    "sender_address" => $ShipArr['sender_address'],
                    "sender_phone" =>  $ShipArr['sender_phone'],
                    "sender_email" => $ShipArr['sender_email'],
                    "receiver_name" => $ShipArr['reciever_name'],
                    "receiver_address" => $ShipArr['reciever_address'],
                    "receiver_phone" => $ShipArr['reciever_phone'],
                    "receiver_email" =>  $ShipArr['reciever_email'],
                    "description" => $complete_sku,
                    "pieces"=>  $box_pieces,
                    "weight"=> $weight,
                    "skudetails" => $skudetails,                    
                );
              
                $sign = create_sign($alldata, $secKey, $customerId, $formate, $method, $signMethod);
                $data_array = array(
                  "sign"       => $sign,
                  "format"     => $formate,
                  "signMethod" => $signMethod,
                  "param"      => $alldata,
                  "method"     => $method,
                  "customerId" => $customerId,
                 );
         
            $dataJson = json_encode($data_array);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Accept: application/json"
            ));
            $response = curl_exec($ch);
    
            curl_close($ch);
       
            return $response;
        }
    }
    
	public function all_ccSeller($id= null )
    {
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
         $this->db->where('cust_id', $id);
	     $this->db->order_by('id', 'desc');	
         $this->db->order_by('deleted', 'N');		  
        $query = $this->db->get('courier_company_seller');
        //echo $this->db->last_query(); die;
        if($query->num_rows()>0){
                return $query->result();
            }
        else {
           
           if($this->getsellerCC($id))
           {
                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
                $this->db->where('cust_id', $id);
                $this->db->order_by('id', 'desc');	
                $this->db->order_by('deleted', 'N');		  
                $query = $this->db->get('courier_company_seller');
                //echo $this->db->last_query(); die;
                if($query->num_rows()>0){
                        return $query->result();
                    }
           }
        }

    }	

    public function getsellerCC($cust_id = null)
    {
        $ccdata = $this->all();
        foreach($ccdata as $couSel){
            $sellearray[] = array(
                'image' =>$couSel ->image,
                'user_name' =>$couSel ->user_name,
                'company_url' => $couSel ->company_url,
                'password' =>$couSel ->password,
                'courier_account_no' => $couSel ->courier_account_no,
                'courier_pin_no' => $couSel ->courier_pin_no,
                'start_awb_sequence' =>$couSel ->start_awb_sequence,
                'end_awb_sequence' => $couSel ->end_awb_sequence,
                'company' => $couSel ->company,
                'status' => $couSel ->status,
                'deleted' => $couSel ->deleted,
                'entrydate' => $couSel ->entrydate,
                'auth_token' => $couSel ->auth_token,
                'api_url' => $couSel ->api_url,
                'user_name_t' => $couSel ->user_name_t,
                'password_t' =>$couSel ->password_t,
                'courier_account_no_t' =>$couSel ->courier_account_no_t,
                'courier_pin_no_t' => $couSel ->courier_pin_no_t,
                'start_awb_sequence_t' => $couSel ->start_awb_sequence_t,
                'end_awb_sequence_t' =>$couSel ->end_awb_sequence_t,
                'auth_token_t' => $couSel ->auth_token_t,
                'api_url_t' =>$couSel ->api_url_t,
                'type' => $couSel ->type,
                'super_id' => $couSel ->super_id,
                'cc_id' => $couSel ->cc_id,
                'company_type' => $couSel ->company_type,
                'capacity' => $couSel ->capacity,
                'n_column' => $couSel ->n_column,
                'delivery_days' => $couSel ->delivery_days,
                'cust_id' => $cust_id,

            );     
        }

       $output =   $this->db->insert_batch('courier_company_seller', $sellearray);
          return $output; 
       // die; 
    }


}






