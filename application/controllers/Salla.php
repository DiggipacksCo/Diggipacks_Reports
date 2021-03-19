<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salla extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(22) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->library('pagination');
        $this->load->model('Salla_model');
        $this->load->library('form_validation');
        $this->load->helper('utility_helper');
    }

    public function add() {
        $data['customers'] = $this->Salla_model->fetch_salla_customers($this->session->userdata('user_details')['super_id']);
        foreach ($data['customers'] as $key => $item) {
            $data['customers'][$key] = (array) $item;
        }
        foreach ($data['customers'] as $customers) {
            //echo "<pre>";print_r($customers);exit;
            $athentication = $customers['salla_athentication'];
            $SallaOrders = SallacURL($athentication, 0);

            for ($i = 1; $i <= $SallaOrders; $i++) {
                $SallaOrders = SallacURL($athentication, $i);

                foreach ($SallaOrders['data'] as $Order) {
                    //echo "<pre>";print_r($Order);exit;
                    $secKey = $customers['secret_key'];
                    $customerId = $customers['uniqueid'];
                    $formate = "json";
                    $method = "createOrder";
                    $signMethod = "md5";
                    $product = array();
                    foreach ($Order['items'] as $products) {
                        $product[] = array(
                            "sku" => $products['sku'],
                            "description" => $products['name'],
                            "cod" => $products['amounts']['total']['amount'],
                            "piece" => $products['quantity'],
                            "wieght" => $products['weight'],
                        );
                    }
                    $booking_id = $Order['reference_id'];
                    $shipper_ref_no = $Order['id'];
                    $payment_mode = $Order['payment_method'];
                    $check_booking_id = exist_booking_id($booking_id, $customers['id']);

                    if ($payment_mode == 'bank' || $payment_mode == 'credit_card' || $payment_mode == 'apple_pay' || $payment_mode == 'mada' || $payment_mode == 'stc_pay' || $payment_mode == 'free' || $payment_mode == 'paypal') {
                        $total_cod_amt = 0;
                        $booking_mode = 'CC';
                    } else {
                        $booking_mode = 'COD';
                    }

                    $weight = 0;
                    foreach ($Order['items'] as $ITEMs) {
                        $weight = $weight + $ITEMs['weight'];
                    }
                    if ($check_booking_id != '' || $check_booking_id != 0) {
                        echo $booking_id . ' Exist<br>';
                    } else {
                        $param = array(
                            "sender_name" => $customers['name'],
                            "sender_email" => $customers['email'],
                            "origin" => getdestinationfieldshow($customers['city'], 'city'),
                            "sender_phone" => $customers['phone'],
                            "sender_address" => $customers['address'],
                            "receiver_name" => $Order['shipping']['receiver']['name'],
                            "receiver_phone" => $Order['shipping']['receiver']['phone'],
                            "receiver_email" => $Order['shipping']['receiver']['email'],
                            "description" => $Order['status']['name'],
                            "destination" => $Order['shipping']['address']['city'],
                            "BookingMode" => $booking_mode,
                            "receiver_address" => $Order['shipping']['address']['shipping_address'],
                            "reference_id" => $booking_id,
                            "codValue" => $Order['amounts']['total']['amount'],
                            "productType" => 'parcel',
                            "service" => 3,
                            "weight" => $weight,
                            "skudetails" => $product
                        );
                        //echo "<pre>";print_r($param);exit;

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


                        $headers = array(
                            "Content-type: application/json",
                        );
                        $url = "https://api.fastcoo-tech.com/API/createOrder";
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
                        //exit;
                    }
                }
            }
        }
    }

    function add_zid() {
        $data['customers'] = $this->Salla_model->fetch_zid_customers($this->session->userdata('user_details')['super_id']);
        foreach ($data['customers'] as $key => $item) {
            $data['customers'][$key] = (array) $item;
        }
        foreach ($data['customers'] as $customers) {
            //echo "<pre>";print_r($customers);exit;
            $user_agent = $customers['user_Agent'] . "/1.00.00 (web)";
            $manager_token = $customers['manager_token'];
            $store_link = "https://api.zid.sa/v1/managers/store/orders?per_page=100&order_status=ready";
            $ZidOrders = ZidcURL($manager_token, $user_agent, $store_link, 0);
            if ($ZidOrders < 2) {
                $pageCount = $SallaOrders;
            } else {
                $pageCount = 2;
            }
            for ($i = 1; $i <= $pageCount; $i++) {
                $store_link = "https://api.zid.sa/v1/managers/store/orders?per_page=100&order_status=ready&page=" . $i;
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

                    $check_booking_id = exist_booking_id($booking_id, $customers['id']);


                    if ($check_booking_id != '' || $check_booking_id != 0) {
                        echo $booking_id . ' Exist<br>';
                    } else {

                        $result1 = Zid_Order_Details($booking_id, $manager_token, $user_agent);
                        //echo "<pre>";print_r($result1);exit;
                        if ($result1['order']['order_status']['code'] == 'ready') {

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
                                "origin" => getdestinationfieldshow($customers['city'], 'city'),
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
                                "skudetails" => $product
                            );
                            //echo "<pre>";print_r($param);exit;

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


                            $headers = array(
                                "Content-type: application/json",
                            );
                            $url = "https://api.fastcoo-tech.com/API/createOrder";
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
                            exit;
                        }
                    }
                }
            }
        }
    }

}
?>