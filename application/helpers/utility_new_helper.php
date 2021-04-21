<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');




if(!function_exists('Getcourirercompany')){
      function Getcourirercompany($id=null, $field=null){
        $ci=& get_instance();
        $ci->load->database();
        $sql ="SELECT $field FROM courier_company where id ='$id'";
        $query = $ci->db->query($sql);
        $row=$query->row_array();
        return $row[$field];
        
        
      }
    }


if(!function_exists('site_config_detaiil')){
      function site_config_detaiil($id=null, $field=null){
        $ci=& get_instance();
        $ci->load->database();
        $sql ="SELECT $field FROM site_config where id ='$id'";
        $query = $ci->db->query($sql);
        $row=$query->row_array();
        return $row[$field];
        
        
      }
    }

if (!function_exists('GetCourierslipnoDrop')) {

    function GetCourierslipnoDrop($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,slip_no, entry_date FROM frwd_shipment_log where super_id='" . $ci->session->userdata('user_details')['super_id'] . "' group by slip_no";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}

function SafeArrival_Auth_cURL($counrierArr) {
  $api_url = $counrierArr['api_url']."v1/customer/authenticate"; 
  $postdataarray = array("username" => $counrierArr["user_name"], "password" => $counrierArr["password"], "remember_me" => true);
   $postdata = json_encode($postdataarray);
  
  $headers = array(
    'Accept: application/json',
    'Content-Type: application/json'   
  );

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $postdata,
    CURLOPT_HTTPHEADER => $headers ,
  ));
  
  $response = curl_exec($curl); 
   curl_close($curl);
   $responseArray = json_decode($response, true);
  return $response;
}

function send_data_to_safe_curl($dataJson, $Auth_token, $API_URL) {
  $ch1 = curl_init();
  curl_setopt($ch1, CURLOPT_URL, $API_URL . "v2/customer/order");
  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch1, CURLOPT_HEADER, FALSE);
  curl_setopt($ch1, CURLOPT_POST, TRUE);
  curl_setopt($ch1, CURLOPT_POSTFIELDS, $dataJson);
  curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "Accept: application/json",
      "Authorization: Bearer " . $Auth_token
  ));

  return $response = curl_exec($ch1);
  curl_close($ch1);
}

function safearrival_label_curl($safe_arrival_ID, $Auth_token=null, $APIURL) 
{
      $API_URL =  $APIURL.'v1/customer/orders/airwaybill_mini?ids='.$safe_arrival_ID;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer ".$Auth_token
        ));

       $response = curl_exec($ch);
     //  echo $response; die;  
        curl_close($ch);
        return $response; 
}



function Thabit_Auth_cURL($counrierArr) {
      $api_url = $counrierArr['api_url']."v1/customer/authenticate"; 
      $postdataarray = array("username" => $counrierArr["user_name"], "password" => $counrierArr["password"], "remember_me" => true);
      $postdata = json_encode($postdataarray);
      
      $headers = array(
        'Accept: application/json',
        'Content-Type: application/json'   
      );

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postdata,
        CURLOPT_HTTPHEADER => $headers ,
      ));

      $response = curl_exec($curl); 
      curl_close($curl);
      $responseArray = json_decode($response, true);
      return $response;
}


function thabit_label_curl($safe_arrival_ID, $Auth_token=null, $APIURL) 
{
      $API_URL =  $APIURL.'v1/customer/orders/airwaybill_mini?ids='.$safe_arrival_ID;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer ".$Auth_token
        ));

       $response = curl_exec($ch);
     //  echo $response; die;  
        curl_close($ch);
        return $response; 
}

function send_data_to_thabit_curl($dataJson, $Auth_token, $API_URL) {
  $ch1 = curl_init();
  curl_setopt($ch1, CURLOPT_URL, $API_URL . "v2/customer/order");
  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch1, CURLOPT_HEADER, FALSE);
  curl_setopt($ch1, CURLOPT_POST, TRUE);
  curl_setopt($ch1, CURLOPT_POSTFIELDS, $dataJson);
  curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/json",
      "Accept: application/json",
      "Authorization: Bearer " . $Auth_token
  ));

  return $response = curl_exec($ch1);
  curl_close($ch1);
}
