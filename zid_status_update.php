<?php

date_default_timezone_set("Asia/Riyadh");


date_default_timezone_set("Asia/Riyadh");

class ZidStatusUpdate {

// Properties
    public $db;
    public $api_url;
    public $auth_token;

    public function __construct() {

        $this->store_link = "https://api.zid.sa/v1/";
        $this->athentication = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2NSIsImp0aSI6IjNmMzI5MTExM2Y3Y2U4NjkxNDcwMDgwMDJkMTY4NTY4YWZkNzU5OWEwZmFlMWRkYTk4ODgzMDUxMGU3MDQ0YTZhYTZjZjE0ODkwYjI0OGY1IiwiaWF0IjoxNTk5OTg5ODkwLCJuYmYiOjE1OTk5ODk4OTAsImV4cCI6MTYzMTUyNTg5MCwic3ViIjoiMzMiLCJzY29wZXMiOlsidGhpcmQtcGFydGllcy1hcGlzIl19.VFozu9O0PEUOCzIxbFdZSVQ-mbduyEvl7JqIHpsHGKMzKmwcd8M-CFw9WyKQ9-I9yxYnFLNgzfsw9JuISjMLqzj6ePyKJw88BlTaB74bSXpD5n6FTAWafhTGETAOUNh7Eswxri9fAb5U8LCIpHLXTy0dWWUEPBb8IubxSULyMh49r1kk2p0ZOfBvnHnDQEdNXzIQe4A53Cyhuh6y6IHehY8nE6rxuw5WIItLmgdZQr-2hvzbcdkyzzD8Su0TwaBzT4E5T5LQNwr7HawfMJWayk_k4kXvRSGu-riP1CpbN0dNeRXL2T6sD79qGxi50xCV75efOlUhk-lqBVOlzmjt-JAFVogDuiMvQSFfXi4tazkzZRGC_SVPrz1pPsIW8B_Rgmpp1hlVUOhS5ywph-dlqsCbyWQa_2mkhleFFs9zwTP_ZQkM3-wSnup3hed7iXQCPVttX244SkItWqA2HBElPRo-a82H03gzBt2lCDGUrxCl_uG1go2KxIopW0TbtpnTs_Ajp6QaTuHgouFW-9GcmyoUo75kQ5RMtzQ6svEEXnV87yEUzsD5DuELkDdENpB_vZVwU9VqAxlgZaSy-LLmteBxVpCmhmv14qCxNrZ95zqZ1bZ02r21CnLJtVDCmpHL-vhq4QCvRQQTAiO-cZ8eF3hYhv5vkVjgY3Cr6c-dO3w';

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

    /**
     * @param type $user_id
     * @return type array()
     * description: fetch all order with POD,DL,RTF
     */
    private function getOrdres($user_id) {
        $sql2 = "SELECT booking_id, slip_no, code, zid_status_update FROM shipment_fm WHERE deleted = 'N' AND status = 'Y' AND code IN('POD', 'DL', 'RTF') AND cust_id = '" . $user_id . "'";

        $result = $this->db->query($sql2);

        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $orders;
    }

    public function updateOrders() {

        $customers = $this->fetchZidCustomers();

        if ($customers) {
            foreach ($customers as $customer) {
                $user_id = $customer['id'];
                //if ($user_id = 683) {
                    $manager_token = $customer['manager_token'];
                    $user_Agent = "Fastcoo/1.00.00 (web)";//$customer['user_Agent'] . "/1.00.00 (web)";
                    //echo "<pre>";print_r($customer);exit;
                    $orders = $this->getOrdres($user_id);

                    if ($orders) {
                        foreach ($orders as $order) {
                            $booking_id = $order['booking_id'];

                            $status = "";
                            $note = "";
                            $tracking_url = "";
                            if ($order['code'] == "POD") {
                                $status = "delivered";
                                $updatedStatus = explode(",", $order['zid_status_update']);
                                if (in_array("POD", $updatedStatus)) {
                                    $cronActive = 0;
                                } else {
                                    $cronActive = 1;
                                }
                            } 

                            if ($cronActive == 1) {
                                $zid_update = $this->zidUpdate($booking_id, $status, $manager_token, $user_Agent);
                                if ($zid_update['message']['description'] == 'Order status has been changed successfully') {
                                    $this->updateShipment($order['slip_no'], $order['code'], $order['zid_status_update']);
                                }
                                echo $order['slip_no'] . " with booking id is " . $order['booking_id'];
                                echo "<pre>";
                                print_r($zid_update);
                            }
                        }
                    } else {
                        /* order not found */
                    }
                //}
            }
        } else {
            /* customer not found */
        }
    }

    private function updateShipment($slip_no, $code, $status) {
        if ($status == '') {
            $code = $code;
        } else {
            $code = $status . ',' . $code;
        }
        $sql = "UPDATE shipment_fm SET zid_status_update='" . $code . "' WHERE slip_no='" . $slip_no . "' LIMIT 1";
        $this->db->query($sql);
    }

    /**
     * description: fetch all salla customers
     * @return type array
     */
    private function fetchZidCustomers() {
        $sql = "select id,seller_id,uniqueid,name,city, manager_token, user_Agent from customer where zid_active = 'Y' and manager_token!='' AND status= 'Y' and access_fm='Y' ";
        $result = $this->db->query($sql);
        $customers = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //echo "<pre>";print_r($customers);exit;
        return $customers;
    }

    function zidUpdate($booking_id, $status, $manager_token, $user_Agent) {
        $header = array(
            "Authorization" => "Bearer " . $this->athentication,
            "X-MANAGER-TOKEN" => $manager_token,
            "User-Agent" => 'Fastcoo/1.00.00 (web)',
            "Accept-Language" => "en"
        );
        //echo "<pre>";print_r($header);exit;
        $url = "https://api.zid.sa/v1/managers/store/orders/" . $booking_id . "/change-order-status";

        $status_update = $this->s_curl($url, $header, $status);
        return $status_update;
    }

    private function s_curl($url, $header, $status) {
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
            CURLOPT_POSTFIELDS => array('order_status' => $status),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $header['Authorization'],
                'X-MANAGER-TOKEN: ' . $header['X-MANAGER-TOKEN'],
                'User-Agent: ' . 'Fastcoo/1.00.00 (web)',
                'Accept-Language: en'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        return $response;
    }

}

$zid = new ZidStatusUpdate();
$zid->updateOrders();
?>


