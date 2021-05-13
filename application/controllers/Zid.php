<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zid extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('utility_helper');
       
        $this->load->helper('zid_helper');
        $this->load->model('Zid_model');
    }
    public function getOrder($uniqueid) {
        // error_reporting(-1);
		// ini_set('display_errors', 1);
     
        ignore_user_abort();
        $file = fopen("/var/www/html/diggipack_new/zidcron.lock", "w+");;

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file,LOCK_EX|LOCK_NB))
        {
            echo "Unable to obtain lock, the previous process is still going on."; 
        }
        else
        {
            //Lock obtained, start doing some work now
            sleep(10);//sleep for 10 seconds
            $this->zidOrders($uniqueid);
            echo "Work completed!";
             // release lock
            flock($file,LOCK_UN);
        }

        fclose($file);
        /*
        $filehandle = fopen("/var/www/html/fastcoo-tech/zidcron.lock", "c+");

      
        $filehandle = fopen("/var/www/html/fastcoo-tech/zidcron.lock", "w+");
 
        if (flock($filehandle, LOCK_EX | LOCK_NB)) {
            $this->zidOrders($uniqueid);
            sleep(10);
            flock($filehandle, LOCK_UN);  // don't forget to release the lock
        } else {
            $myfile = fopen("/var/www/html/fastcoo-tech/zidlogs.txt", "a") or die("Unable to open file!");
            $txt = $uniqueid ."==cron run at: " . date('Y-m-d H:i:s');
            fwrite($myfile, "\n" . $txt);
            fclose($myfile);
            // throw an exception here to stop the next cron job
        }

        fclose($filehandle);
         
         */
        
    }

    private function zidOrders($uniqueid) {
        $customers = $this->Zid_model->fetch_zid_customers($uniqueid);
        echo $deliveryOption=deliveryOption($customers['id']); 
        $user_agent = "Fastcoo/1.00.00 (web)"; //$customers['user_Agent'];
        $manager_token = $customers['manager_token'];
        $Bearer = $this->config->item('zid_authorization');
        $store_link = "https://api.zid.sa/v1/managers/store/orders?per_page=100&sort_by=asc&order_status=" . $customers['zid_status'];
        $ZidTotalOrders = ZidcURL($manager_token, $user_agent, $store_link, 0,$Bearer);
        $pageCount = ceil($ZidTotalOrders / 100);

        for ($i = 1; $i <= $pageCount; $i++) {
            $store_link = "https://api.zid.sa/v1/managers/store/orders?per_page=100&sort_by=asc&order_status=" . $customers['zid_status'] . "&page=" . $i;
            $ZidOrders = ZidcURL($manager_token, $user_agent, $store_link, $i,$Bearer);
 
           
           //  echo '<pre>'.$customers['zid_status'];
          //  print_r( $ZidOrders); die(); 
          
 
            foreach ($ZidOrders['orders'] as $Order) {
              
                $secKey = $customers['secret_key'];
                $customerId = $customers['uniqueid'];
                $formate = "json";
                $method = "createOrder";
                $signMethod = "md5";
                $product = array();            
              
                $booking_id = $Order['id'];

              // echo "<pre>";  print_r($customers['id']);  die; 

                if ($customers['access_fm'] == 'Y') {
                    $check_booking_id = exist_booking_id($booking_id, $customers['id']);
                }

                if ($customers['access_lm'] == 'Y') {
                    $check_booking_id = $this->Zid_model->existLmBookingId($booking_id, $customers['id']);
                }

                if (!empty($check_booking_id)) {

                  
                   
                    if($check_booking_id['code']=='POD')
                    {
                       
                    // if(!empty($check_booking_id['frwd_company_label']))
                    // $lable=$check_booking_id['frwd_company_label'];
                    // else
                    // $lable='https://api.fastcoo-tech.com/API/print/'.$check_booking_id['slip_no'];


                    // $trackingurl=makeTrackUrl($check_booking_id['cc_id'],$check_booking_id['frwd_company_awb']);
                  
                    
                    updateZidStatus($booking_id, $manager_token, 'delivered', $check_booking_id['slip_no'], $lable, $trackingurl);
                    }
                    echo $booking_id . ' Exist<br>';

                } else {

                    $result1 = Zid_Order_Details($booking_id, $manager_token, $user_agent); 

                    
                    // if($booking_id == 7176231 )
                    // {
                    //     echo '<pre>';
                    //     echo '<br>'.$result1['order']['order_status']['code'];
                    //     echo '<br>'.print_r($result1['order']['shipping']['method']['name']);
                    //     echo '<br>'. trim($deliveryOption);
                    //     echo '<br>'.$customers['zid_status'];
                    //     print_r( $result1);  
                    //     die();
                    // }
                   
                 
                    if ($result1['order']['order_status']['code'] == $customers['zid_status'] &&  trim($result1['order']['shipping']['method']['name']) ==  trim($deliveryOption) ) 
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
                        
                        $param = array(
                            "sender_name" => $customers['name'],
                            "sender_email" => $customers['email'],
                            "origin" => $this->Zid_model->getdestinationfieldshow($customers['city'], 'city', $customers['super_id']),
                            "sender_phone" => $customers['phone'],
                            "sender_address" => $customers['address'],
                            "receiver_name" => $result1['order']['customer']['name'],
                            "receiver_phone" => $result1['order']['customer']['mobile'],
                            "receiver_email" => $result1['order']['customer']['email'],
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
                    //   echo "dataJson =  ".$dataJson;
                    //   echo "<br><br>"; 
                    //   echo $customers['zid_access'];
                      
                        if ($customers['zid_access'] == 'FM') {
                            if ($_SERVER['HTTP_HOST'] == "dev-fm.fastcoo.net") 
                            {
                               echo  $url = "https://api.diggipacks.com/API/createOrder";
                            } else {
                                echo $url = "https://api.diggipacks.com/API/createOrder";
                            }

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


    private function orderCurl($url, $dataJson) {
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/fastcootech/fullfillment/';
        } else {
            $file_path = $file_path = $_SERVER['DOCUMENT_ROOT'] . '/';
        }

        $filehandle = fopen($file_path . "zidcron.lock", "c+");
        if (flock($filehandle, LOCK_EX | LOCK_NB)) {
            /* order create by cron start */
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
            /* order create by cron end */

            flock($filehandle, LOCK_UN);  // don't forget to release the lock
        } else {
            $myfile = fopen($file_path . "zidcron.txt", "a") or die("Unable to open file!");
            $txt = "cron run at: " . date('Y-m-d H:i:s');
            fwrite($myfile, "\n" . $txt);
            fclose($myfile);
            // throw an exception here to stop the next cron job
        }

        fclose($filehandle);
    }

}

?>