<?php

date_default_timezone_set("Asia/Riyadh");

class SallaTrackOrderUpdate {
 
// Properties
    public $db;
    public $api_url;
    public $super_id;

    public function __construct() {
        error_reporting(-1);
        ini_set('display_errors', 1);
        ini_set('memory_limit',-1);
        $this->api_url = "http://s.salla.sa/webhook/diggipacks/order/";
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
     //print_r( $customers);
       
        if ($customers) {
            foreach ($customers as $customer) {
                if ($customer['salla_provider'] == 1 || 1==1 ) {
                    $auth_token = '$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72y';
                    $tracking_url1 = "https://track.diggipacks.com";

                    // if (!$this->is_valid_domain_name($tracking_url1)) {
                    //     $tracking_url1 = "https://track.diggipacks.com";
                    // }

                    $new_tracking_url = $this->addhttp($tracking_url1);
                    $orders = $this->sallaOrders($customer);

                      //print_r( $orders); exit;
                        $DL_array=array('5','16','17','19','21') ;
                        $RTC_array=array('8','18') ;
                        
                    if ($orders) {
                        foreach ($orders as $order) {

                            if(in_array($order['delivered'],$DL_array))
                            {
                                $order['code']='DL';
                            }
                            if(in_array($order['delivered'],$RTC_array))
                            {
                                $order['code']='RTC';
                            }
                            echo '<pre>';
                          //  print_r($order); exit;
                            $shippers_ref_no = $order['booking_id'];
                            echo $tracking_number = $order['frwd_company_awb'];
                            echo'<br>' . $tracking_url = $new_tracking_url . '/' . $order['slip_no'];

                            if ($order['code'] == 'POD' && $order['salla_track_status_updated'] != 1) {
                                //echo 'ty'; exit;
                                $status = 9;
                                $note = 'delivered';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);
                                $this->shipmentUpdate($order['slip_no'], 1);
                            } else if ($order['code'] == 'RTC' && $order['salla_track_status_updated'] != 0) {
                              
                                $status = 5;
                                $note = 'cancelled';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);

                                //Quantity update here
                                //$this->sendQuantityupdatetosalla($order['slip_no'], $order['super_id'], $order['cust_id'], $customer);
                                $this->shipmentUpdate($order['slip_no'], 0);
                            } else if ($order['code'] == 'DL' && $order['salla_track_status_updated'] != 3) {
                                $status = 8;
                                $note = 'delivering';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);
                                //Quantity update here
                                //$this->sendQuantityupdatetosalla($order['slip_no'], $order['super_id'], $order['cust_id'], $customer);
                                $this->shipmentUpdate($order['slip_no'], 3);
                            } else if ($order['code'] == 'D3PL' && $order['salla_track_status_updated'] != 2) {
                                $status = 8;
                                $note = 'delivering';
                                $this->Salla_StatusUpdate($shippers_ref_no, $status, $note, $tracking_number, $tracking_url, $customer);
                                //Quantity update here
                               // $this->sendQuantityupdatetosalla($order['slip_no'], $order['super_id'], $order['cust_id'], $customer);
                                $this->shipmentUpdate($order['slip_no'], 2);
                            }
                            //}
                        }
                    }
                }
            }
        }
    }

    private function shipmentUpdate($slip_no, $salla_track_status_updated) {
        $cond='';
        if($salla_track_status_updated==1 || $salla_track_status_updated==5)
        {
            $cond=" ,deliver_status=1";
        }
        $sql = "update shipment_fm set salla_track_status_updated = '" . $salla_track_status_updated . "' ". $cond." where slip_no='" . $slip_no . "' ";
        $result = $this->db->query($sql);
    }

    private function addhttp($url) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    private function sallaOrders($customers) {
        $today = date('Y-m-d');
        $lastdate = date('Y-m-d',strtotime('-30 days'));
       echo '<br>'. $sql = "select * from shipment_fm where cust_id='" . $customers['cust_id'] . "' and delivered NOT IN ('1','2','3','4','9','11') and deleted='N' and deliver_status='0' ";
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
echo 'xxx';

        $url = 'https://s.salla.sa/webhook/diggipacks/order/' . $shippers_ref_no;
        $dataJson = json_encode($data);
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
        $customerId= $customer['uniqueid'];

        foreach ($skus as $sku) {
            $query = "select SUM(iv.quantity) as quantity,im.sku from items_m im "
                    . " left join item_inventory iv on iv.item_sku = im.id "
                    . " where im.sku = '" . $sku['sku'] . "' and im.super_id='" . $super_id . "' "
                    . " and iv.seller_id ='" . $seller_id . "'";

            $result = $this->db->query($query);
            $skuQtys = mysqli_fetch_all($result, MYSQLI_ASSOC);
            foreach ($skuQtys as $sku) {
                $request_array = array('auth-token' => $auth_token,
                    'customerId' =>  $customerId,
                    'quantity' => $sku['quantity']
                );
                $url = "https://s.salla.sa/webhook/diggipacks/product/" . $sku['sku']; 
            
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
       echo $response = curl_exec($curl_req); 
        //print_r($response);exit;
        curl_close($curl_req);
        return $response;
    }

    /**
     * description: fetch all salla customers
     * @return type array
     */
    private function fetchSallaCustomers() {

      echo  $sql = "select c.id as cust_id,c.uniqueid,s.salla_provider,s.site_url,s.salla_provider_token,c.email,c.phone,c.user_Agent,c.address,c.seller_id,c.super_id,salla_athentication,salla_active,uniqueid,name,city, order_status,company from customer c  left join site_config s on s.super_id = c.super_id where c.salla_access='FM' and s.super_id IN (5,54)  ";

        $result = $this->db->query($sql);
        $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $customers;
    }

}

$salla = new SallaTrackOrderUpdate();
$salla->allOrders();
?>

