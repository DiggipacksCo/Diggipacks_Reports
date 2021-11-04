<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('Getwarehouse_Dropdata')) {

    function Getwarehouse_Dropdata() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name FROM warehouse_category where status='Y' and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}

if (!function_exists('Getallstorage_drop')) {

    function Getallstorage_drop() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM storage_table where deleted='N' AND status='Y' AND super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}

function AddSKUfromZid($data = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->insert('items_m', $data);
 // echo $ci->db->last_query();exit;
}

function exist_zidsku_id($sku, $super_id) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "select id from items_m where sku='" . $sku . "' and super_id='" . $super_id . "'";
    $query = $ci->db->query($sql);
    $countdata = $query->num_rows();
    $row = $query->row_array();
    //echo $ci->db->last_query();exit;
    if ($countdata > 0)
        return $row['id'];
    else
        return false;
}

function GetAllQtyforSellerby_ID($sku = null, $cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->select('items_m.zid_pid,SUM(item_inventory.quantity) as quantity,items_m.sku as sku');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.id', $sku);
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $query = $ci->db->get();
    return $row = $query->row_array();
}



function GetAllQtyforSeller($sku = null, $cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->select('items_m.zid_pid,customer.manager_token,SUM(item_inventory.quantity) as quantity,items_m.sku as sku');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.sku', $sku);
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $ci->db->group_by('item_inventory.item_sku');
    $query = $ci->db->get();
   // echo $ci->db->last_query();
    return $row = $query->row_array();
}




function GetAllQtyforSeller_new($cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->select('items_m.zid_pid,customer.manager_token,customer.zid_sid, SUM(item_inventory.quantity) as quantity,items_m.sku');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
    // $ci->db->where('items_m.sku', $sku);
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $ci->db->group_by('item_inventory.item_sku');
    $query = $ci->db->get();
    //echo $ci->db->last_query();
    return $row = $query->result_array();
}

//*************************Quantity Update function in Zid*************************//


function update_zid_product($quantity = null, $pid = null, $token = null, $storeID = null,$cust_id=null,$sku) 
{
    $param = array(
        'quantity' => $quantity,
        'id' => $pid,
    );
    $bearer = site_configTable('zid_provider_token');
    $param = json_encode($param);
    $curl = curl_init();
    $url = "https://api.zid.dev/app/v2/products/" . $pid . "/";
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$bearer,
            'X-MANAGER-TOKEN: ' . $token,
            'STORE-ID: ' . $storeID,
            'ROLE: Manager',
            'Content-Type: application/json',
            'Accept-Language: en',
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $ci = & get_instance();
    $ci->load->database();
    $datalog = array(
  
        'log'=> $response ,
        'cust_id'=>  $cust_id,
       'sku'=>$sku,
       'qty'=>$quantity,
        'system_name'=> 'zid',
        'super_id'=>  $ci->session->userdata('user_details')['super_id']
    );
    
    
    
  
    
    $ci->db->insert('salla_out_log', $datalog);
}

//**************************************************************************//


function updateZidStatus($orderID = null, $token = null, $status = null, $code = null, $label = null, $trackingurl = null,$cust_id=null) {
    //echo 'werwqerwqrewqerwqrwqerqew'.$token.'testerewrwrwerewrwererweer';
    $url = 'https://api.zid.dev/app/v2/managers/store/orders/' . $orderID . '/change-order-status';
    $curl = curl_init();
    $bearer = site_configTable('zid_provider_token');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('order_status' => $status, 'waybill_url' => $label, 'tracking_url' => $trackingurl, 'tracking_number' => $code),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$bearer,
            'X-MANAGER-TOKEN: ' . $token,
            'User-Agent: Rashof/1.00.00 (web)',
            'Accept-Language: en',
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $ci = & get_instance();
    $ci->load->database();
   
    $datalog = array(
        'slip_no' =>  $slip_no,
        'status_id' =>  $status,
        'note' =>  $trackingurl,
        'log'=> $response ,
        'cust_id'=>  $cust_id,
        'booking_id'=> $orderID,
        'system_name'=> 'zid',
        'super_id'=>  $ci->session->userdata('user_details')['super_id']
    );
    
    
    
   
    
    $ci->db->insert('salla_out_log', $datalog);
}

function ZidPcURL($storeID, $store_link, $bearer,$token) {

  
    $curl = curl_init();
    $header=array(
        
        'Authorization: Bearer ' . $bearer,
        'STORE-ID: ' . $storeID,
        'Content-Type: application/json',
        'Accept-Language: ar',
        'ROLE: manager',
        'X-MANAGER-TOKEN: '.$token,


    );
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $store_link,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => $header
));

     $response = curl_exec($curl); 

    curl_close($curl);
    return $response;
}

function checkZidSkuExist($sku, $pid) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "select id from items_m where sku='" . $sku . "' and zid_pid = '" . $pid . "'";
    $query = $ci->db->query($sql);
   //echo $ci->db->last_query(); exit;
    $countdata = $query->num_rows();
    return $query->row_array();
}



function deliveryOption($cust_id) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "SELECT  `zid_delivery_name`FROM `zid_deliver_options` WHERE `cust_id` = '" . $cust_id . "'";
    $query = $ci->db->query($sql);
$result=$query->row_array();
    
    return $result['zid_delivery_name'];
}


function deliveryOption_new($cust_id) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "SELECT  `zid_delivery_name`FROM `zid_deliver_options` WHERE `cust_id` = '" . $cust_id . "'";
    $query = $ci->db->query($sql);
$result=$query->result_array();
$retArray=array();
foreach($result as $r)
    {
      array_push( $retArray,$r['zid_delivery_name']);
    }
    return $retArray;
}


//*************************Quantity Update function in Salla*************************//
function update_salla_qty_product($quantity = null, $pid = null, $token = null,$cust_id=null) 
{
   
    
    $param=array('quantity'=>$quantity);
    $request = json_encode($param);
     $url = "https://api.salla.dev/admin/v2/products/quantities/bySku/". $pid ;
  
  
    $curl = curl_init();

       curl_setopt_array($curl, [
           CURLOPT_URL => $url,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "PUT",
           CURLOPT_POSTFIELDS => $request,
           CURLOPT_HTTPHEADER => [
               "Authorization: Bearer " . $token,
               "Accept-Language: AR",
               "Content-Type: application/json",
              
           ],
       ]);
 $response = curl_exec($curl);
        curl_close($curl);
        $ci = & get_instance();
        $ci->load->database();
        //$this->ci->load->library('session');
        $datalog = array(
        
            'log'=> $response ,
            'cust_id'=>  $cust_id,
        'sku'=>$pid,
        'qty'=>$quantity,
            'system_name'=> 'salla',
            'super_id'=>  $ci->session->userdata('user_details')['super_id']
        );





    $ci->db->insert('salla_out_log', $datalog);

}

function update_status_salla($status = null, $note = null, $token = null,$id=null,$cust_id=null,$slip_no=null) 
{
   

    $param = array(
        'status_id' => $status,
        'note' => $note,
    );
    $request = json_encode($param);
    
    $url = "https://api.salla.dev/admin/v2/orders/" . $id . "/status"; 
    $curl = curl_init();

       curl_setopt_array($curl, [
           CURLOPT_URL => $url ,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 30,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => $request,
           CURLOPT_HTTPHEADER => [
               "Authorization: Bearer " . $token,
               "Accept-Language: AR",
               "Content-Type: application/json",
              
           ],
       ]);
 $response = curl_exec($curl);
    $err = curl_error($curl);

    $ci = & get_instance();
    $ci->load->database();
    $datalog = array(
    'slip_no' =>  $slip_no,
    'status_id' =>  $status,
    'note' =>  $note,
    'log'=> $response ,
    'cust_id'=>  $cust_id,
    'booking_id'=> $id,
    'system_name'=> 'salla',
    'super_id'=>  $ci->session->userdata('user_details')['super_id']
    );




    $ci->db->insert('salla_out_log', $datalog);

    /// echo $ci->db->last_query();exit;
}
