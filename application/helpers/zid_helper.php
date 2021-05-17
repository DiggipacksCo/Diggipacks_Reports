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
    if ($countdata > 0)
        return $row['id'];
    else
        return false;
}

function GetAllQtyforSeller($sku = null, $cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->select('items_m.zid_pid,customer.manager_token,SUM(item_inventory.quantity) as quantity');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->join('customer', 'customer.id=item_inventory.seller_id');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.sku', $sku);
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $ci->db->group_by('item_inventory.item_sku');
    $query = $ci->db->get();
    //echo $ci->db->last_query();
    return $row = $query->row_array();
}

function GetAllQtyforSellerby_ID($sku = null, $cust_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->select('items_m.zid_pid,SUM(item_inventory.quantity) as quantity');
    $ci->db->from('item_inventory');
    $ci->db->join('items_m', 'items_m.id=item_inventory.item_sku');
    $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
    $ci->db->where('items_m.id', $sku);
    $ci->db->where('item_inventory.seller_id', $cust_id);
    $query = $ci->db->get();
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
   echo $ci->db->last_query();
    return $row = $query->result_array();
}

//*************************Quantity Update function in Zid*************************//
function update_zid_product($quantity = null, $pid = null, $token = null, $storeID = null) {


    $param = array(
        'quantity' => $quantity,
        'id' => $pid,
    );
    $param = json_encode($param);
    $curl = curl_init();
    $url = "https://api.zid.sa/v1/products/" . $pid . "/";
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
            'Access-Token: ' . $token,
            'STORE-ID: ' . $storeID,
            'ROLE: Manager',
            'Content-Type: application/json',
            'Accept-Language: en',
        ),
    ));

     $response = curl_exec($curl); 

    curl_close($curl);
}


//**************************************************************************//
function updateZidStatus($orderID = null, $token = null, $status = null, $code = null, $label = null, $trackingurl = null) {
    //echo 'werwqerwqrewqerwqrwqerqew'.$token.'testerewrwrwerewrwererweer';
    $url = 'https://api.zid.sa/v1/managers/store/orders/' . $orderID . '/change-order-status';
    $curl = curl_init();

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
            'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2NSIsImp0aSI6IjNmMzI5MTExM2Y3Y2U4NjkxNDcwMDgwMDJkMTY4NTY4YWZkNzU5OWEwZmFlMWRkYTk4ODgzMDUxMGU3MDQ0YTZhYTZjZjE0ODkwYjI0OGY1IiwiaWF0IjoxNTk5OTg5ODkwLCJuYmYiOjE1OTk5ODk4OTAsImV4cCI6MTYzMTUyNTg5MCwic3ViIjoiMzMiLCJzY29wZXMiOlsidGhpcmQtcGFydGllcy1hcGlzIl19.VFozu9O0PEUOCzIxbFdZSVQ-mbduyEvl7JqIHpsHGKMzKmwcd8M-CFw9WyKQ9-I9yxYnFLNgzfsw9JuISjMLqzj6ePyKJw88BlTaB74bSXpD5n6FTAWafhTGETAOUNh7Eswxri9fAb5U8LCIpHLXTy0dWWUEPBb8IubxSULyMh49r1kk2p0ZOfBvnHnDQEdNXzIQe4A53Cyhuh6y6IHehY8nE6rxuw5WIItLmgdZQr-2hvzbcdkyzzD8Su0TwaBzT4E5T5LQNwr7HawfMJWayk_k4kXvRSGu-riP1CpbN0dNeRXL2T6sD79qGxi50xCV75efOlUhk-lqBVOlzmjt-JAFVogDuiMvQSFfXi4tazkzZRGC_SVPrz1pPsIW8B_Rgmpp1hlVUOhS5ywph-dlqsCbyWQa_2mkhleFFs9zwTP_ZQkM3-wSnup3hed7iXQCPVttX244SkItWqA2HBElPRo-a82H03gzBt2lCDGUrxCl_uG1go2KxIopW0TbtpnTs_Ajp6QaTuHgouFW-9GcmyoUo75kQ5RMtzQ6svEEXnV87yEUzsD5DuELkDdENpB_vZVwU9VqAxlgZaSy-LLmteBxVpCmhmv14qCxNrZ95zqZ1bZ02r21CnLJtVDCmpHL-vhq4QCvRQQTAiO-cZ8eF3hYhv5vkVjgY3Cr6c-dO3w',
            'X-MANAGER-TOKEN: ' . $token,
            'User-Agent: Rashof/1.00.00 (web)',
            'Accept-Language: en',
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response;
}

function ZidPcURL($storeID, $store_link, $bearer) {

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
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $bearer,
            'STORE-ID: ' . $storeID,
        ),
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