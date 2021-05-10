<?php

date_default_timezone_set("Asia/Riyadh");

class SallaShipment {

// Properties
    public $db;
    public $api_url;
    public $auth_token;
    public $fastcoo_order_api;

    public function __construct() {

        $this->api_url = "http://s.salla.sa/webhook/track/order/";
        $this->auth_token = "776B01D80BA626B26AA023CA0F7D16DA";
        $this->fastcoo_order_api = "https://api.fastcoo-tech.com/API/createOrder";

        $db_host = 'ajouldb-db-instance-1.ctikm53hr4st.us-east-1.rds.amazonaws.com';
        $db_user = 'ajoulMaster';
        $db_password = "Ajouldb118";
        $db_name = 'fastcoo_online_db_v4';

        $this->db = new mysqli($db_host, $db_user, $db_password, $db_name);
        $this->db->set_charset("UTF8");
        if ($this->db->connect_error) {
            die('Connect Error (' . $this->db->connect_errno . ') '
                    . $this->db->connect_error);
        }
    }

    public function allOrders() {
        $customers = $this->fetchSallaCustomers();
        //echo "<pre>";print_r($customers);die;
        if ($customers) {
            foreach ($customers as $customer) {
                if ($customer['salla_active'] == 'Y' && $customer['salla_athentication'] != '') {
                    $salla_from_date = $customer['salla_from_date'];
                    $order_status = $customer['order_status'];
                    $order_city = $customer['salla_city'];
                    //if ($customer['id'] == "279") {
                        $this->sallaOrders($customer, $salla_from_date, $order_status, $order_city);
                    //}
                }
            }
        }
    }

    private function sallaOrders($customers, $salla_from_date, $order_status, $order_city) {


        $athentication = $customers['salla_athentication'];

        $SallaTotalOrders = $this->SallacURL($athentication, 0, $salla_from_date, $order_status, $order_city);

        $secKey = $customers['secret_key'];
        $customerId = $customers['uniqueid'];

        for ($i = 1; $i <= $SallaTotalOrders; $i++) {
            $SallaOrders = $this->SallacURL($athentication, $i, $salla_from_date, $order_status, $order_city);

            if (isset($SallaOrders['data'])) {
                foreach ($SallaOrders['data'] as $Order) {

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
                        $description12[] = $products['quantity'] . ' * ' . $products['name'];
                        $description1 = implode(", ", $description12);
                    }
                    $booking_id = $Order['reference_id'];
                    $shipper_ref_no = $Order['id'];
                    $payment_mode = $Order['payment_method'];

                    if (strtoupper($payment_mode) == 'COD') {
                        $booking_mode = 'COD';
                    } else {
                        $total_cod_amt = 0;
                        $booking_mode = 'CC';
                    }

                    $weight = 0;
                    foreach ($Order['items'] as $ITEMs) {
                        $weight = $weight + $ITEMs['weight'];
                    }

                    $check_booking_id = 0; //$this->exist_booking_id($booking_id, $customers['id'], $customers['super_id']);

                    if ($check_booking_id != '' || $check_booking_id != 0) {
                        echo $booking_id . ' Exist<br>';
                    } else {
                        $param = array(
                            "sender_name" => $customers['company'],
                            "sender_email" => $customers['email'],
                            "origin" => $this->getdestinationfieldshow($customers['city'], 'city', $customers['super_id']),
                            "sender_phone" => $customers['phone'],
                            "sender_address" => $customers['address'],
                            "receiver_name" => $Order['shipping']['receiver']['name'],
                            "receiver_phone" => $Order['shipping']['receiver']['phone'],
                            "receiver_email" => $Order['shipping']['receiver']['email'],
                            "description" => ($description1 == '' ? 'GOODS' : $description1),
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
                        $sign = $this->create_sign($param, $secKey, $customerId, $formate, $method, $signMethod);

                        $data_array = array(
                            "sign" => $sign,
                            "format" => $formate,
                            "signMethod" => $signMethod,
                            "param" => $param,
                            "method" => $method,
                            "customerId" => $customerId,
                        );

                        $dataJson = json_encode($data_array);
//                        print_r($dataJson);
//                        exit;
                        $headers = array(
                            "Content-type: application/json",
                        );

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_URL, $this->fastcoo_order_api);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

                        $response = curl_exec($ch);

                        echo '<pre>';
                        echo $response;
                    }
                }
            }
        }
    }

    private function getdestinationfieldshow($id, $field, $super_id) {

        $sql = "SELECT $field FROM country where id='$id' and super_id='" . $super_id . "'";
        $query = $this->db->query($sql);
        $shipment = mysqli_fetch_array($query, MYSQLI_ASSOC);

        return $shipment[$field];
    }

    private function create_sign($param, $secKey, $customerId, $formate, $method, $signMethod) {

        $jsonDataArray = json_encode($param);

        $var = "customerId" . $customerId . "format" . $formate . "method" . $method . "signMethod" . $signMethod . "";
        $all_var_concatinated = $secKey . $var . $jsonDataArray . $secKey;
        $sign = strtoupper(md5($all_var_concatinated));
        return $sign;
    }

    private function exist_booking_id($booking_id, $cust_id, $super_id) {

        $sql = "select id from shipment_fm where booking_id='" . $booking_id . "' and cust_id='" . $cust_id . "' and super_id='" . $super_id . "' and deleted='N' limit 1";

        $result = $this->db->query($sql);
        $shipment = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($shipment > 0)
            return $shipment['id'];
        else
            return false;
    }

    private function SallacURL($athentication, $page, $salla_from_date, $order_status) {
//        return $this->sallactest();
//        die;
        $url = "https://api.salla.dev/admin/v2/orders?expanded=1&from_date=$salla_from_date&page=$page&status[]=" . $order_status;
      
        //echo $url = "https://api.salla.dev/admin/v2/orders?expanded=1&page=".$page."&from_date=".$salla_from_date;
        $headers = array("Authorization:Bearer $athentication");
        $ch = curl_init($url); //the curl for first request pages
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        if (curl_errno($ch)) { //Checking curl error
            echo "Error in curl" . curl_error($ch);
            exit;
        } else { //if there is no error we are proceed
            $result = json_decode($result, true);
            //echo "<pre>";print_r($result);exit;
            if ($page > 0) {
                return $result;
            } else {

                return $total_pages = $result['pagination']['totalPages'];
            }
        }
        curl_close($ch);
    }
    

    /**
     * description: fetch all salla customers
     * @return type array
     */
    private function fetchSallaCustomers() {
        $sql = "select id,secret_key,email,phone,user_Agent,address,seller_id,super_id,zid_active,manager_token,user_Agent as zid_user_Agent,salla_athentication,salla_active,salla_from_date, uniqueid,name,city, order_status,salla_city,company from customer where salla_active = 'Y' and status= 'Y' AND salla_access = 'FM'";
        $result = $this->db->query($sql);
        $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $customers;
    }

    private function s_curl($url, $request) {
        $headers = array(
            "Content-type: application/json",
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        $response = curl_exec($ch);

        echo '<pre>';
        echo ($response);
    }

}

$salla = new SallaShipment();
$salla->allOrders();
?>

