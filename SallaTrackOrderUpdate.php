<?php

date_default_timezone_set("Asia/Riyadh");

class SallaTrackOrderUpdate {

// Properties
    public $db;
    public $api_url;
    public $super_id;

    public function __construct() {

        $this->api_url = "https://s.salla.sa/webhook/diggipacks/order/"; 
      
        $this->super_id = 5;


        $db_host = 'ajouldb-db-instance-1.ctikm53hr4st.us-east-1.rds.amazonaws.com';
        $db_user = 'ajoulMaster';
        $db_password = "Ajouldb118";
        $db_name = 'diggipacks_db';

        $this->db = new mysqli($db_host, $db_user, $db_password, $db_name);
        $this->db->set_charset("UTF8");
        if ($this->db->connect_error) {
            die('Connect Error (' . $this->db->connect_errno . ') '
                    . $this->db->connect_error);
        }
    }

    public function allOrders() {
        $customers = $this->fetchSallaCustomers();
 echo '<pre>';
      //  print_r( $customers); exit;

        if ($customers) {
            foreach ($customers as $customer) {
                if ($customer['salla_provider'] == 1) {
                    $auth_token = $customer['salla_provider_token'];
                   
                        $t_url = "https://track.diggipacks.com";
                    
                    
                    $t_url = $this->addhttp($t_url);
                    $orders = $this->sallaOrders($customer);

                  //  print_r( $orders); exit;
                    
                    if ($orders) {
                        foreach ($orders as $order) {
                            $shippers_ref_no = $order['booking_id'];
                            $tracking_number = $order['frwd_company_awb'];
                          echo '<br>'. $tracking_url = $t_url . '/' . $order['slip_no']; 

                            if ($order['code'] == 'POD') {
                                $status = 9;
                                $note = 'delivered';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);
                            } else if ($order['code'] == 'RTC' || $order['code'] == 'C') {
                                $status = 5;
                                $note = 'cancelled';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);
                                //Quantity update here
                                $this->sendQuantityupdatetosalla($order['slip_no'], $order['super_id'], $order['cust_id'], $customer);
                            } else if ($order['code'] == 'DL') {
                                $status = 8;
                                $note = 'delivering';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);
                                //Quantity update here
                                $this->sendQuantityupdatetosalla($order['slip_no'], $order['super_id'], $order['cust_id'], $customer);
                            } else if ($order['code'] == 'D3PL') {
                                $status = 8;
                                $note = 'delivering';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);
                                //Quantity update here
                                //$this->sendQuantityupdatetosalla($order['slip_no'], $order['super_id'], $order['cust_id'], $customer);
                            }
                        }
                    }
                }
            }
        }
    }

    private function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    private function sallaOrders($customers) {
        $today = date('Y-m-d');
        $sql = "select sh.*,s.entry_date from shipment_fm sh left join status_fm s on s.slip_no = sh.slip_no where sh.cust_id='" . $customers['cust_id'] . "' "
                . "and sh.deleted='N' and (sh.code='RTC' or sh.code='POD' or sh.code ='D3PL' or sh.code='DL') "
                . "and sh.deliver_status='0'  ";
        $result = $this->db->query($sql);
        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $orders;
    }

    private function is_valid_domain_name($domain_name) {
        return (preg_match("/^([a-zd](-*[a-zd])*)(.([a-zd](-*[a-zd])*))*$/i", $domain_name) //valid characters check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^.]{1,63}(.[^.]{1,63})*$/", $domain_name) ); //length of every label
    }

    function Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer) {
        $data = array(
            'auth-token' => '$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72y',
            'status' => $status,
            'note' => $note,
            'tracking_url' => $tracking_url,
            'tracking_number' => $tracking_number
        );


   echo  $url ='https://s.salla.sa/webhook/diggipacks/order/' . $shippers_ref_no; 
      echo  $dataJson = json_encode($data,JSON_UNESCAPED_SLASHES);
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
        echo ($response);  
    }

    function sendQuantityupdatetosalla($slip_no, $super_id, $seller_id, $customer) {
        $sql = "select sku from diamention_fm where slip_no = '" . $slip_no . "' and deleted = 'N'";
        $result = $this->db->query($sql);
        $skus = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $auth_token = $customer['salla_provider_token'];

        foreach ($skus as $sku) {
            $query = "select SUM(iv.quantity) as quantity,im.sku from items_m im "
                    . " left join item_inventory iv on iv.item_sku = im.id "
                    . " where im.sku = '" . $sku . "' and im.super_id='" . $super_id . "' "
                    . " and iv.seller_id ='" . $seller_id . "'";
            $result = $this->db->query($query);
            $skuQtys = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($skuQtys as $sku) {
                $request_array = array('auth-token' => $auth_token,
                    'customerId' => $seller_id,
                    'quantity' => $sku->quantity
                );

                $url = $customer['salla_track_url'] . "/product/" . $sku->sku;
                $json_data = json_encode($request_array);
                $this->qtyUpdate($url, $json_data);
            }
        }
    }

    private function qtyUpdate($url, $json_data) {
        $header = array("Content-type:application/json");
        $curl_req = curl_init($url);
        curl_setopt($curl_req, CURLOPT_POSTFIELDS, $json_data);
        $curl_options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_FOLLOWLOCATION => true
        );

        curl_setopt_array($curl_req, $curl_options);
        $response = curl_exec($curl_req);
        //print_r($response);exit;
        curl_close($curl_req);
        return $response;
    }

    /**
     * description: fetch all salla customers
     * @return type array
     */
    private function fetchSallaCustomers() {

      echo  $sql = "select c.id as cust_id,s.salla_provider,s.site_url,s.salla_provider_token,c.email,c.phone,c.user_Agent,c.address,c.seller_id,c.super_id,salla_athentication,salla_active,uniqueid,name,city, order_status,company from customer c "
                . " left join site_config s on s.super_id = c.super_id where  s.super_id= '" . $this->super_id . "' and s.salla_provider='1' ";

        $result = $this->db->query($sql);
        $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $customers;
    }

}

$salla = new SallaTrackOrderUpdate();
$salla->allOrders();
?>

