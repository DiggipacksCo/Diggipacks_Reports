<?php

defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(-1);
		ini_set('display_errors', 1);
class Zid extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('utility_helper');
       
        $this->load->helper('zid_helper');
        $this->load->model('Zid_model');
    }
    public function getOrder($uniqueid) { 
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataJson = json_encode($_POST);
        //==================log write start========
        
        $fr = fopen('zidLog/zid'. $_POST['id'] . '.json', 'w+');
        fwrite($fr, $dataJson);
         
        fclose($fr);

        ignore_user_abort();
        $file = fopen("zidlock/zidcron".$_POST['id'].".lock", "w+");;

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file,LOCK_EX|LOCK_NB))
        {
            echo "Unable to obtain lock, the previous process is still going on."; 
        }
        else
        {
            //Lock obtained, start doing some work now
           // sleep(10);//sleep for 10 seconds
            $this->zidOrders($uniqueid,$_POST); exit;
            echo "Work completed!";
             // release lock
            flock($file,LOCK_UN);
        }

        fclose($file);
    }


        private function zidOrders($uniqueid,$postData) {
            $Order=$postData;
            $customers = $this->Zid_model->fetch_zid_customers($uniqueid);
           
           // $customers['zid_status']='Ready';
            //print_r($postData); exit;
             $deliveryOption=deliveryOption($customers['id']); 
            $manager_token = $customers['manager_token'];
            $Bearer = site_configTableSuper_id('zid_provider_token',$customers['super_id']); 
              
                  
                    $secKey = $customers['secret_key'];
                    $customerId = $customers['uniqueid'];
                    $formate = "json";
                    $method = "createOrder";
                    $signMethod = "md5";
                    $product = array();
                 
                  
                 $booking_id = $Order['id'];
                   
                   
                   
    
                    if ($customers['access_fm'] == 'Y') {
                        $check_booking_id = exist_booking_id($booking_id, $customers['id']);
    
                        print_r( $check_booking_id);
                    }
    
                    if ($customers['access_lm'] == 'Y') {
                        $check_booking_id = $this->Zid_model->existLmBookingId($booking_id, $customers['id']);
                        print_r( $check_booking_id);
                    }
    
                    if (!empty($check_booking_id)) {
    
                      
                       
                        if($check_booking_id['code']=='POD')
                        {
                           
                        // if(!empty($check_booking_id['frwd_company_label']))
                        // $lable=$check_booking_id['frwd_company_label'];
                        // else
                        // $lable='https://api.fastcoo-tech.com/API/print/'.$check_booking_id['slip_no'];
    
    
                        // $trackingurl=makeTrackUrl($check_booking_id['cc_id'],$check_booking_id['frwd_company_awb']);
                      
                        
                        // updateZidStatus($booking_id, $manager_token, 'delivered', $check_booking_id['slip_no'], $lable, $trackingurl);
                        }
                        elseif($check_booking_id['code']=='B')
                        {
                        //     $lable='https://api.fastcoo-tech.com/API/print/'.$check_booking_id['slip_no'];
    
                        //     $trackingurl=TRACKURL_LM.$check_booking_id['awb_no'];
       
                        //    // $trackingurl=makeTrackUrl($check_booking_id['cc_id'],$check_booking_id['frwd_company_awb']);
                         
                           
                        //     updateZidStatus($booking_id, $manager_token, 'preparing', $check_booking_id['awb_no'], $lable, $trackingurl);
           
                        }
    
                        echo $booking_id . ' Exist<br>';
                    } else {
    
                       // echo 'xxxxx'; exit;
                        $result1['order'] = $Order; 
                    
                  //  echo  $customers['zid_status'];
                  // print_r( $result1); exit;
                 
                    if ($result1['order']['order_status']['code'] == $customers['zid_status'] && ( trim($result1['order']['shipping']['method']['name']) ==  trim($deliveryOption) || trim($result1['order']['shipping']['method']['name'])=='DIGGIPACKS' ) ) 
                    {
                   
                        $weight = 0;
                        foreach ($result1['order']['products'] as $ITEMs) {
                            $weight = $weight + $ITEMs['weight']['value'];
                        }
                        $product = array();

                        foreach ($result1['order']['products'] as $products) {

                            $product[] = array(
                                "sku" => $products['sku'],
                                "description" => '',
                                "cod" => $products['total'],
                                "piece" => $products['quantity'],
                                "wieght" => $products['weight']['value'],
                            );
                        }
                        if($result1['order']['has_different_consignee']==true )
                        {

                           
                            $recName=$result1['order']['consignee']['name'];
                            $recMobile=$result1['order']['consignee']['mobile'];
                            $recEmail=$result1['order']['consignee']['email'];
                        }
                        else
                        {
                            $recName=$result1['order']['customer']['name'];
                            $recMobile=$result1['order']['customer']['mobile'];
                            $recEmail=$result1['order']['customer']['email'];
                           
                        }
                        $param = array(
                            "sender_name" => $customers['name'],
                            "sender_email" => $customers['email'],
                            "origin" => $this->Zid_model->getdestinationfieldshow($customers['city'], 'city', $customers['super_id']),
                            "sender_phone" => $customers['phone'],
                            "sender_address" => $customers['address'],
                            "receiver_name" => $recName,
                            "receiver_phone" => $recMobile,
                            "receiver_email" => $recEmail,
                            "description" => $result1['message']['description'],
                            "destination" => $result1['order']['shipping']['address']['city']['name'],
                            "BookingMode" => ($result1['order']['payment']['method']['code'] == 'zid_cod' ? 'COD' : 'CC'),
                            "receiver_address" => $result1['order']['shipping']['address']['formatted_address'] . ' ' . $result1['order']['shipping']['address']['street'] . ' ' . $result1['order']['shipping']['address']['district'],
                            "reference_id" => $booking_id,
                            "codValue" => $result1['order']['order_total'],
                            "productType" => 'parcel',
                            "service" => 3,
                            "weight" => $weight,
                            "skudetails" => $product,
                            "zid_store_id" => $result1['order']['store_id'],
                            "order_from" => "zid"
                        );                       


                        $sign = create_sign($param, $secKey, $customerId, $formate, $method, $signMethod);

                        $data_array = array(
                            "sign" => $sign,
                            "format" => $formate,
                            "signMethod" => $signMethod,
                            "param" => $param,
                            "method" => $method,
                            "customerId" => $customerId,
                        );

                        $dataJson = json_encode($data_array); 
                    
                      
                        if ($customers['zid_access'] == 'FM') {

                        
                           
                                echo $url = "https://api.diggipacks.com/API/createOrder";
                        

                            //$url = "http://apilm.com/API/createOrder";
                            $resps = $this->sendRequest($url, $dataJson);
                        }
                        //echo " <br><br><br><br>resp = <pre>"; echo $resps ;  exit;

                     


                        if ($customers['zid_access'] == 'LM') {
                            $url = "https://api.diggipacks.com/API/createLmOrder";
                          $resps =  $this->sendRequest($url, $dataJson);
                        }
                        print_r($resps); 
                    }
                
            
        }
    }

    private function sendRequest($url, $dataJson) {


        $headers = array(
            "Content-type: application/json",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        echo '<pre>';
        echo $response;
    }



}

?>