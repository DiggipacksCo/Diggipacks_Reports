<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zid extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('utility_helper');
        $this->load->model('Zid_model');
    }

    public function getOrder($uniqueid) {

        $customers = $this->Zid_model->fetch_zid_customers($uniqueid);
        $user_agent = $customers['user_Agent'];
        $manager_token = $customers['manager_token'];
        $store_link = "https://api.zid.sa/v1/managers/store/orders?per_page=100&order_status=" . $customers['zid_status'];
        $ZidTotalOrders = ZidcURL($manager_token, $user_agent, $store_link, 0);
        $pageCount = ceil($ZidTotalOrders / 100);

        for ($i = 1; $i <= $pageCount; $i++) {
            $store_link = "https://api.zid.sa/v1/managers/store/orders?per_page=100&order_status=" . $customers['zid_status'] . "&page=" . $i;
            $ZidOrders = ZidcURL($manager_token, $user_agent, $store_link, $i);
            //echo "<pre>";print_r($ZidOrders);exit;
            foreach ($ZidOrders['orders'] as $Order) {
                //echo "<pre>";print_r($Order);exit;
                $secKey = $customers['secret_key'];
                $customerId = $customers['uniqueid'];
                $formate = "json";
                $method = "createOrder";
                $signMethod = "md5";
                $product = array();

                $booking_id = $Order['id'];

                if ($customers['access_fm'] == 'Y') {
                    $check_booking_id = exist_booking_id($booking_id, $customers['id']);
                }

                if ($customers['access_lm'] == 'Y') {
                    $check_booking_id = $this->Zid_model->existLmBookingId($booking_id, $customers['id']);
                }

                if ($check_booking_id != '' || $check_booking_id != 0) {
                    echo $booking_id . ' Exist<br>';
                } else {

                    $result1 = Zid_Order_Details($booking_id, $manager_token, $user_agent);
                    //echo "<pre>";print_r($result1);exit;
                    if ($result1['order']['order_status']['code'] == $customers['zid_status']) {

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
                            "BookingMode" => ($result1['order']['payment']['method']['name'] == 'Cash on Delivery' ? 'COD' : 'CC'),
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
                        if ($customers['access_fm'] == 'Y') {
                            if ($_SERVER['HTTP_HOST'] == "dev-fm.fastcoo.net") {
                                $url = "https://api.fastcoo.net/API/createOrder";
                            } else {
                                $url = "https://api.fastcoo-tech.com/API/createOrder";
                            }

                            //$url = "http://apilm.com/API/createOrder";
                            $this->orderCurl($url, $dataJson);
                        }

                        if ($customers['access_lm'] == 'Y') {
//                            $url = "https://api.fastcoo-tech.com/API/createLmOrder";
//                            $this->orderCurl($url, $dataJson);
                        }
                    }
                }
            }
        }
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