<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');





if (!function_exists('asset_url()')) {

    function asset_url() {
        return base_url() . 'assets/';
    }

}
if (!function_exists('GetallitemcheckDuplicate')) {

    function GetallitemcheckDuplicate($sku) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM items_m where sku='$sku'";
        $query = $ci->db->query($sql);
        $countdata = $query->num_rows();
        $row = $query->row_array();
        if ($countdata > 0)
            return $row['id'];
        else
            return false;
    }

}

function GetrequestShippongCompany($data = array()) {


    $Allarray = array('awb' => $data);
    $url = "https://demotrack.fastcoo-solutions.com/API/API/RequestShippingCompany";
    $dataJson = json_encode($Allarray);
    $headers = array("Content-type: application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    $response = curl_exec($ch);
    //  print_r($response);
    // die;
}

if (!function_exists('GetinventoryTableData')) {

    function GetinventoryTableData($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM item_inventory where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}
if (!function_exists('GetSellerTableField')) {

    function GetSellerTableField($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM seller_m where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetwherehouseDropShow')) {

    function GetwherehouseDropShow($id = null) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name FROM warehouse_category where deleted='N' and status='Y'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        $userdrop = '<select class="form-control" name="wh_id" id="wh_id" required><option value="">Please Select</option>';
        foreach ($result as $row) {
            if ($row['id'] == $id)
                $userdrop .= '<option value="' . $row['id'] . '" selected="selected">' . $row['name'] . '</option>';
            else
                $userdrop .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
        $userdrop .= '</select>';
        return $userdrop;
    }

}
if (!function_exists('Getwarehouse_categoryfield')) {

    function Getwarehouse_categoryfield($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM warehouse_category where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('Getwarehouse_categoryfiename')) {

    function Getwarehouse_categoryfiename($name = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM warehouse_category where name='$name'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('GetCourCompanynameId')) {

    function GetCourCompanynameId($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM courier_company where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('getdestinationfieldshow_array')) {

    function getdestinationfieldshow_array($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        if (!empty($id)) {
            $sql = "SELECT $field FROM country where id IN ($id)";
            $query = $ci->db->query($sql);
            $result = $query->result_array();
            foreach ($result as $ndata) {
                $rdata .= $ndata['city'] . ',';
            }
            return $rdata;
        }
    }

}
if (!function_exists('Print_getall3plfm')) {

    function Print_getall3plfm($awbData, $type = 'awb') {
        $ci = & get_instance();
        $ci->load->database();

        $awb = implode(",", $awbData);
        if (empty($awb)) {
            $awb = "'" . $awbData . "'";
        }
        //all_labels
        $listingQry = "select slip_no from shipment_fm where deleted='N' and ( slip_no IN  (" . $awb . ")) ORDER  BY FIELD(slip_no," . $awb . ")";
        $query = $ci->db->query($listingQry);

        if ($query->num_rows()) {
            $status_update_data = $query->result_array();
            //print_r($status_update_data); exit;  
            //
            $fileArray = array();
            foreach ($status_update_data as $key => $val) {

                $filePath = '/var/www/html/fastcoo-solution/track/all_labels/' . $status_update_data[$key]['slip_no'] . '.pdf';
                if (file_exists($filePath))
                    array_push($fileArray, $filePath);
            }

            require('../lm/fpdf_new/fpdf.php');
            require('../lm/fpdi/fpdi.php');

            //print_r($fileArray);exit;

            $files = $fileArray;

            if ($type == 'moovo')
                $pdf = new FPDI('P', 'mm');
            else
                $pdf = new FPDI('P', 'mm', array(101, 152));

            // iterate over array of files and merge
            foreach ($files as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($i = 0; $i < $pageCount; $i++) {
                    $tpl = $pdf->importPage($i + 1, '/MediaBox');
                    $pdf->addPage();
                    $pdf->useTemplate($tpl);
                }
            }

            // output the pdf as a file (http://www.fpdf.org/en/doc/output.htm)
            $pdf->Output('D', '3pl-' . date('Ymdhis') . '.pdf');
        }
    }

    //print_r($awb); die();
}
if (!function_exists('print_shipment_smsa')) {

    function print_shipment_smsa($awbData, $type = 'awb') {
        $ci = & get_instance();
        $ci->load->database();

        $awb = implode(",", $awbData);
        if (empty($awb)) {
            $awb = "'" . $awbData . "'";
        }

        if ($type == 'id') {
            $listingQry = "select frwd_awb_no from shipment where deleted='N' and id IN  (" . $awb . ") and `frwd_throw` LIKE 'smsa'";
        } else {
            $listingQry = "select frwd_awb_no from shipment where deleted='N' and ( slip_no IN  (" . $awb . ")) and `frwd_throw` LIKE 'SAMSA'";

            $query = $ci->db->query($listingQry);


            if ($query->num_rows() <= 0) {
                $listingQry = "select frwd_awb_no from shipment where deleted='N' and  booking_id IN  (" . $awb . ") and `frwd_throw` LIKE 'SAMSA' ORDER  BY FIELD(booking_id," . $awb . ")";
            }
        }
        $query = $ci->db->query($listingQry);

        if ($query->num_rows()) {
            $status_update_data = $query->result_array();
            //print_r($status_update_data); exit;  
            //
            $fileArray = array();
            foreach ($status_update_data as $key => $val) {

                $filePath = 'smsa_label/' . $status_update_data[$key]['frwd_awb_no'] . '.pdf';
                if (file_exists($filePath))
                    array_push($fileArray, $filePath);
            }

            require('../lm/fpdf_new/fpdf.php');
            require('../lm/fpdi/fpdi.php');

            //print_r($fileArray);exit;

            $files = $fileArray;


            $pdf = new FPDI('P', 'mm', array(101, 152));

            // iterate over array of files and merge
            foreach ($files as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($i = 0; $i < $pageCount; $i++) {
                    $tpl = $pdf->importPage($i + 1, '/MediaBox');
                    $pdf->addPage();
                    $pdf->useTemplate($tpl);
                }
            }

            // output the pdf as a file (http://www.fpdf.org/en/doc/output.htm)
            $pdf->Output('D', 'smsa-' . date('Ymdhis') . '.pdf');
        }
    }

    //print_r($awb); die();
}

if (!function_exists('print_shipment_aramex')) {

    function print_shipment_aramex($awbData, $type = 'awb') {
        $ci = & get_instance();
        $ci->load->database();
        $awb = implode(",", $awbData);
        if (empty($awb)) {
            $awb = "'" . $awbData . "'";
        }
        if ($type == 'id') {
            $listingQry = "select slip_no,frwd_throw,frwd_awb_no from shipment where deleted='N' and id IN  (" . $awb . ") and `frwd_throw` IN ('ARAMEX')";
        } else {
            $listingQry = "select slip_no,frwd_throw,frwd_awb_no from shipment where deleted='N' and ( slip_no IN  (" . $awb . ")) and `frwd_throw` IN ('ARAMEX')";
            $query = $ci->db->query($listingQry);

            if ($query->num_rows() <= 0) {
                $listingQry = "select slip_no,frwd_throw,frwd_awb_no from shipment where deleted='N' and  booking_id IN  (" . $awb . ") and `frwd_throw` IN ('ARAMEX') ORDER  BY FIELD(booking_id," . $awb . ")";
            }
        }
        $query = $ci->db->query($listingQry);

        if ($query->num_rows()) {
            $status_update_data = $query->result_array();
            $fileArray = array();

            foreach ($status_update_data as $key => $val) {
                if ($status_update_data[$key]['frwd_throw'] == 'ARAMEX') {
                    $filePath = 'aramex_label/' . $status_update_data[$key]['slip_no'] . '.pdf';
                }
                if (file_exists($filePath))
                    array_push($fileArray, $filePath);
            }
            //print_r($filePath); die();

            require('../lm/fpdf_new/fpdf.php');
            require('../lm/fpdi/fpdi.php');


            $files = $fileArray;



            $pdf = new FPDI('P', 'mm', array(110, 170));

            foreach ($files as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($i = 0; $i < $pageCount; $i++) {
                    $tpl = $pdf->importPage($i + 1, '/MediaBox');
                    $pdf->addPage();
                    $pdf->useTemplate($tpl);
                }
            }

            // output the pdf as a file (http://www.fpdf.org/en/doc/output.htm)
            $pdf->Output('D', 'ARAMEX-' . date('Y-m-d h:i:s') . '.pdf');
        }
    }

}


if (!function_exists('sms_prepared')) {

    function sms_prepared($slip_no) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT seller_m.name as seller_name,shipment_fm.reciever_phone FROM `seller_m` LEFT join shipment_fm on shipment_fm.cust_id=seller_m.customer where shipment_fm.slip_no='" . $slip_no . "'";
        $result = $ci->db->query($sql);
        $result_data = $result->row_array();
        //echo "sssssssss";
        //print_r($result_data);
        $seller_name = $result_data['seller_name'];
        $number = $result_data['reciever_phone'];
        $sendMessage = "select templates from msg_template where id='25'";
        $template = $ci->db->query($sendMessage);
        $row = $template->row_array();
        $dataVal = str_replace('booking_id', $slip_no, $row['templates']);
        $dataVal = str_replace('seller', $seller_name, $dataVal);
        // $dataVal=str_replace('LINK','',$dataVal);
        SEND_SMS($number, $dataVal);
        return true;
    }

}


if (!function_exists('SEND_SMS')) {


    function SEND_SMS($number, $message) {


        $number = ltrim($number, '966 ');
        $number = ltrim($number, '0');
        $number = '0' . $number;
        $number = str_replace(' ', '', $number);

        // echo $number."///".$message;exit;
        $params = array(
            'username' => 'Track', //username used in HQSMS
            'password' => 'abtrackcd',
            'numbers' => $number, //destination number
            'sender' => 'TRACK', //sender name have to be activated
            'message' => $message,
            'unicode' => 'E', 'return' => 'full'
        );
        $data = '?' . http_build_query($params);
        $url = "https://www.safa-sms.com/api/sendsms.php" . $data;
        file_get_contents($url);
//die;
// Call API and get return message
//fopen($url,"r");
        /* if(file_get_contents($url)){
          return true;
          }

          else{return true;} */
    }

}

if (!function_exists('Getquantitybyskuname')) {

    function Getquantitybyskuname($seller_id = null, $sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $inventory_dataqry = "select sum(item_inventory.quantity)as quantity from item_inventory left join items_m on item_inventory.item_sku=items_m.id where item_inventory.seller_id='" . $seller_id . "' and items_m.sku like'" . trim($sku) . "'";
        $query = $ci->db->query($inventory_dataqry);
        $result = $query->row_array();
        return $result['quantity'];
    }

}
if (!function_exists('sendQuantityupdatetosalla')) {

    function sendQuantityupdatetosalla($seller_id = null, $sku = null, $customer_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $customer_id = GetuniqIDbySellerId($seller_id);
        $quantity = Getquantitybyskuname($seller_id, $sku);
        $auth_token = "776B01D80BA626B26AA023CA0F7D16DA";
        $request_array = array('auth-token' => $auth_token,
            'customerId' => $customer_id,
            'quantity' => $quantity);
        $url = "https://s.salla.sa/webhook/track/product/" . $sku;
        $json_data = json_encode($request_array);
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
// exit("sadf");
// print_r($json_data);exit;
        curl_setopt_array($curl_req, $curl_options);
        $response = curl_exec($curl_req);
        //print_r($response);exit;
        curl_close($curl_req);
        return $response;
    }

}

if (!function_exists('GetuniqIDbySellerId')) {

    function GetuniqIDbySellerId($cust_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT uniqueid FROM customer where seller_id='$cust_id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['uniqueid'];
    }

}

if (!function_exists('packedcount')) {

    function packedcount($id = null) {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT count(id) as  packedcount FROM pickuplist_tbl where pickupId='$id' $cndition";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['packedcount'];
    }

}

if (!function_exists('unpackedcount')) {

    function unpackedcount($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT count(id) as  packedcount FROM pickuplist_tbl where pickup_status ='N' and  pickupId='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['packedcount'];
    }

}

if (!function_exists('Getuniqueidbycustid')) {

    function Getuniqueidbycustid($cust_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT uniqueid FROM customer where id='$cust_id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['uniqueid'];
    }

}


if (!function_exists('GetallaccountidBysellerID')) {

    function GetallaccountidBysellerID($uid = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT seller_id FROM customer where uniqueid='$uid'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['seller_id'];
    }

}

if (!function_exists('GetAddInventoryActivities')) {

    function GetAddInventoryActivities($data = array()) {
        $ci = & get_instance();
        $ci->load->database();
        $ci->db->insert('inventory_activity', $data);
        // echo $ci->db->last_query();
    }

}

if (!function_exists('GetSkuTranferHistoryUpdate')) {

    function GetSkuTranferHistoryUpdate($data = array()) {
        $ci = & get_instance();
        $ci->load->database();
        $ci->db->insert('sku_transfer', $data);
    }

}
if (!function_exists('send_message')) {

    function send_message($slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $selectslip = "select shipment.reciever_phone,shipment.slip_no,shipment.frwd_awb_no,shipment.frwd_throw,seller_m.name as seller_name from shipment left join seller_m on shipment.cust_id=seller_m.customer where shipment.slip_no='" . trim($slip_no) . "' and shipment.deleted='N'";
        $query = $ci->db->query($selectslip);
        $FetchSlip = $query->result_array();
        if (!empty($FetchSlip)) {
            $slip_no = $FetchSlip[0]['slip_no'];
            $frwd_throw = $FetchSlip[0]['frwd_throw'];
            $frwd_awb_no = $FetchSlip[0]['frwd_awb_no'];
            $full_forward_info = $frwd_throw . '(' . $frwd_awb_no . ')';
            $number = $FetchSlip[0]['reciever_phone'];
            $seller_name = $FetchSlip[0]['seller_name'];
            $sendMessage = "select templates from msg_template where id='24'";
            $query2 = $ci->db->query($sendMessage);
            $template = $query2->row_array();
            $dataVal = str_replace('booking_id', $slip_no, $template['templates']);
            $dataVal = str_replace('LINK', $full_forward_info, $dataVal);
            $dataVal = str_replace('seller', $seller_name, $dataVal);
            TRACK_SMS($number, $dataVal);
        }
    }

}

function TRACK_SMS($receiver_phone, $message) {

    $receiver_phone = ltrim($receiver_phone, '966 ');
    $receiver_phone = ltrim($receiver_phone, '0');
    $receiver_phone = '0' . $receiver_phone;
    $receiver_phone = str_replace(' ', '', $receiver_phone);

    // echo $number."///".$message;exit;
    $params = array(
        'username' => 'Track', //username used in HQSMS
        'password' => 'abtrackcd',
        'numbers' => $receiver_phone, //destination number
        'sender' => "ANYTHING", //sender name have to be activated
        'message' => $message,
        'unicode' => 'E', 'return' => 'full'
    );
    $data = '?' . http_build_query($params);

    $url = "https://www.safa-sms.com/api/sendsms.php" . $data;
// print_r($url);exit;

    if (file_get_contents($url)) {
        return true;
    }
}

if (!function_exists('GettotalpalletsCount')) {

    function GettotalpalletsCount($uid = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT count(shelve_no) as tpallet FROM item_inventory ";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['tpallet'];
    }

}

if (!function_exists('GetuserToatalLOcationQty')) {

    function GetuserToatalLOcationQty($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM item_inventory where id='$id' ";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetuserSkuAllqty')) {

    function GetuserSkuAllqty($seller_id = null, $item_sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM(quantity) as tqty FROM item_inventory where seller_id='$seller_id'  and item_sku='$item_sku'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['tqty'];
    }

}
if (!function_exists('Getsite_configData')) {

    function Getsite_configData() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM site_config ";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}


if (!function_exists('GetcheckConditionsAddInventory')) {

    function GetcheckConditionsAddInventory($id) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT itemupdated FROM `pickup_request` WHERE `uniqueid`='$id' and code in ('MSI','DI')";
        $query = $ci->db->query($sql);
        $row = $query->row_array();
        return $row['itemupdated'];
    }

}
if (!function_exists('getusertypedropdown')) {

    function getusertypedropdown($id = null) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,designation_name FROM designation_tbl";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        $userdrop = '<select class="form-control" name="usertype" id="usertype"><option value="">Please Select</option>';
        foreach ($result as $row) {
            if ($row['id'] == $id)
                $userdrop .= '<option value="' . $row['id'] . '" selected="selected">' . $row['designation_name'] . '</option>';
            else
                $userdrop .= '<option value="' . $row['id'] . '">' . $row['designation_name'] . '</option>';
        }
        $userdrop .= '</select>';
        return $userdrop;
    }

}

if (!function_exists('GetManifestInventroyUpdateQty')) {

    function GetManifestInventroyUpdateQty($uid, $sid, $sku) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT count(id) as tqty FROM `pickup_request` WHERE `uniqueid`='$uid' and code='RI' and seller_id='$sid' and sku='$sku'";

        $query = $ci->db->query($sql);
        $row = $query->row_array();
        return $row['tqty'];
    }

}
if (!function_exists('getusertypenameshow')) {

    function getusertypenameshow($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,designation_name FROM designation_tbl where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['designation_name'];
    }

}
if (!function_exists('get_total_current')) {

    function get_total_current($status = null) {


        $date1 = date('Y-m-d');


        $current_date_new = '';
        //	if($current_date == 1){
        $current_date = date('Y-m-d');
        $current_date_new = "	 and DATE(entrydate)='" . $current_date . "' ";
        //}
        if ($status_slug == '11' || $status_slug == '6') {
            $current_date = date('Y-m-d');
            $current_date_new = "	 and DATE(delever_date)='" . $current_date . "' ";
        }
        $total = 0;
        $status_condition = "and delivered='" . $status . "'";
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select id from shipment_fm where  status='Y' and deleted='N' $status_condition $current_date_new ";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return count($result);
    }

}

if (!function_exists('getUserNameById')) {

    function getUserNameById($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT username FROM user_fm where user_id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['username'];
    }

}
if (!function_exists('getcheckslavenovalid')) {

    function getcheckslavenovalid($slave = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM warehous_shelve_no where shelv_no='$slave'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        if ($query->num_rows() == 0)
            return true;
        else
            return false;
        //echo '<pre>';
        //print_r($result);
    }

}

if (!function_exists('Getallskudatadetails')) {

    function Getallskudatadetails($slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select (select id from items_m where items_m.sku=diamention_fm.sku)as itmSku,piece from diamention_fm where deleted='N' and slip_no='" . $slip_no . "'";
        $query = $ci->db->query($sql);
        //echo  $ci->db->last_query; die();                
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('Getallskudatadetails_tracking')) {

    function Getallskudatadetails_tracking($slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select * from diamention_fm where deleted='N' and slip_no='" . $slip_no . "'";
        $query = $ci->db->query($sql);
        //echo  $ci->db->last_query; die();                
        $result = $query->result_array();
        return $result;
    }

}

if (!function_exists('getallsellerdatabyID')) {

    function getallsellerdatabyID($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM seller_m where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getalldataitemtablesSKU')) {

    function getalldataitemtablesSKU($sku = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM items_m where sku='$sku'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('GetallCutomerBysellerId')) {

    function GetallCutomerBysellerId($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM customer where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('Getallstoragetablefield')) {

    function Getallstoragetablefield($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM storage_table where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}


if (!function_exists('getpickuprequestData')) {

    function getpickuprequestData($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM pickup_request where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetshpmentDataByawb')) {

    function GetshpmentDataByawb($awb = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM shipment_fm  where slip_no='$awb'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('Getallsellerdata')) {

    function Getallsellerdata($ids) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name FROM seller_m";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('getcheckalreadyexitsstorage')) {

    function getcheckalreadyexitsstorage($id = null, $storage_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM storage_rate_table where storage_id='$storage_id' and client_id='$id'";
        $query = $ci->db->query($sql);
        $count = $query->num_rows();
        return $count;
    }

}
if (!function_exists('CheckStockBackorder')) {

    function CheckStockBackorder($seller_id = null, $sku = null, $pieces = null, $slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();

        //echo $pieces."<br>";		 
        $inventory_dataqry = "select item_inventory.*,items_m.sku from item_inventory left join items_m on item_inventory.item_sku=items_m.id where item_inventory.seller_id='" . $seller_id . "' and items_m.sku like'" . trim($sku) . "' and item_inventory.shelve_no!='' and item_inventory.quantity>0 order by item_inventory.id asc";
        $qyery = $ci->db->query($inventory_dataqry);
        //$inventory_data=$this->dbh->FetchAllResults($inventory_dataqry);
        //	print_r($inventory_data); exit;  

        if ($qyery->num_rows() > 0) {
            $inventory_data = $qyery->result_array();
            $returnarray = array();

            //print_r($inventory_data);
            //echo array_sum($inventory_data['quantity']);
            $totalqty = 0;
            $totalqty1 = 0;
            $locationarray = array();
            foreach ($inventory_data as $rdata) {
                $totalqty += $rdata['quantity'];
            }

            //print_r($returnarray);
            //echo '<br>xxx'. $pieces;
            if ($pieces <= $totalqty) {
                $newpcs = $pieces;
                $ii = 0;

                foreach ($inventory_data as $rdata) {

                    //echo $newpcs."<br>";



                    if ($pieces >= $rdata['quantity']) {
                        //echo "$pieces>=".$rdata['quantity']."";
                        //echo $ii;

                        $returnarray[$ii]['upqty'] = 0;
                        //$newpcs=$newpcs-$rdata['quantity'];	
                        $pieces = $pieces - $rdata['quantity'];
                        $returnarray[$ii]['tableid'] = $rdata['id'];
                        $returnarray[$ii]['skuid'] = $rdata['item_sku'];
                        $returnarray[$ii]['quantity'] = $rdata['quantity'];
                        $returnarray[$ii]['sku'] = $rdata['sku'];
                        $returnarray[$ii]['slip_no'] = $slip_no;
                        $returnarray[$ii]['shelve_no'] = $rdata['shelve_no'];
                        $returnarray[$ii]['seller_id'] = $rdata['seller_id'];
                        $returnarray[$ii]['totalqty'] = $totalqty;
                        $returnarray[$ii]['pieces'] = $pieces;
                        $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
                    } else {
                        if ($pieces > 0) {


                            // echo $ii;


                            $returnarray[$ii]['upqty'] = $rdata['quantity'] - $pieces;
                            $returnarray[$ii]['tableid'] = $rdata['id'];
                            $returnarray[$ii]['skuid'] = $rdata['item_sku'];
                            $returnarray[$ii]['quantity'] = $rdata['quantity'];
                            $returnarray[$ii]['sku'] = $rdata['sku'];
                            $returnarray[$ii]['slip_no'] = $slip_no;
                            $returnarray[$ii]['shelve_no'] = $rdata['shelve_no'];
                            $returnarray[$ii]['seller_id'] = $rdata['seller_id'];
                            $returnarray[$ii]['totalqty'] = $totalqty;
                            $returnarray[$ii]['pieces'] = $pieces;
                            $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
                            $pieces = 0;
                        } else {

                            //echo $ii;
                            $returnarray[$ii]['upqty'] = $rdata['quantity'];
                            $returnarray[$ii]['tableid'] = $rdata['id'];
                            $returnarray[$ii]['skuid'] = $rdata['item_sku'];
                            $returnarray[$ii]['quantity'] = $rdata['quantity'];
                            $returnarray[$ii]['seller_id'] = $rdata['seller_id'];
                            $returnarray[$ii]['totalqty'] = $totalqty;
                            $returnarray[$ii]['pieces'] = $pieces;
                            $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
                        }
                    }

                    //echo $returnarray[$ii]['upqty']."==".$rdata['quantity']."<br>";
                    //echo '<br>yy'. $pieces.'//'.$rdata['sku'];

                    $ii++;
                }
                //print_r($locationarray);
                return array('succ' => 1, 'stArray' => $returnarray, 'StockLocation' => $locationarray);
            } else {
                return 'Less Stock';
            }
        } else {
            return 'Invalid SKU';
        }
    }

}


if (!function_exists('CheckStockBackorder_ordergen')) {

    function CheckStockBackorder_ordergen($seller_id=null, $sku=null, $pieces=null, $slip_no=null,$sku_id=null) {
        $ci = & get_instance();
        $ci->load->database();
        //echo $pieces."<br>";		 
        $inventory_dataqry = "select item_inventory.*,items_m.sku from item_inventory left join items_m on item_inventory.item_sku=items_m.id where item_inventory.seller_id='" . $seller_id . "' and items_m.sku like'" . trim($sku) . "' and item_inventory.quantity>0 order by item_inventory.id asc";
        $query = $ci->db->query($inventory_dataqry);


        if ($query->num_rows() > 0) {
            $inventory_data = $query->result_array();
          //  print_r( $inventory_data);
            $returnarray = array();


              $totalqty=0;
			 $totalqty1=0;
             $locationarray=array();
             $error_array=array();
             $countInventry=count($inventory_data)-1;
             $finalLoopArray=array();
             $werehouseArr=array();

			foreach($inventory_data as $key11=>$rdata)
			{
               if($totalqty<$pieces)
               {

            
                if($key11==0)
                 array_push($werehouseArr,$rdata['wh_id']);

                if(in_array($rdata['wh_id'],$werehouseArr))
                {
                    $totalqty+=$rdata['quantity'];

                    array_push($finalLoopArray,$rdata);

                }
                if($key11==$countInventry)
                {
                    if($totalqty<$pieces) 
                    {
                        array_push($error_array,$rdata);
                    }
                }
                
               }	
				
			}
			
			//print_r($finalLoopArray);
				//echo '<br>xxx'. $pieces;
    		if($pieces<=$totalqty){
				$newpcs=$pieces;
				$ii=0;
				//$werehouseArr=array();
				foreach($finalLoopArray as $rdata)
			   {
				   
				//echo $newpcs."<br>";
					//array_push($werehouseArr,$rdata);
					//if($rdata['wh_id']==$werehouseArr['wh_id'])
					//{
						
						
					if($pieces>=$rdata['quantity'])
					{
					//echo "$pieces>=".$rdata['quantity']."";
						//echo $ii;
					
					$returnarray[$ii]['upqty']=0;
					//$newpcs=$newpcs-$rdata['quantity'];	
					$pieces=$pieces-$rdata['quantity'];
					$returnarray[$ii]['tableid']=$rdata['id'];
					$returnarray[$ii]['skuid']=$rdata['item_sku'];
					$returnarray[$ii]['quantity']=$rdata['quantity'];
					$returnarray[$ii]['sku']=$rdata['sku'];
					$returnarray[$ii]['slip_no']=$slip_no;
					$returnarray[$ii]['shelve_no']=$rdata['shelve_no'];
					$returnarray[$ii]['wh_id']=$rdata['wh_id'];
					}
					else
					{
						if($pieces>0)
						{
						
						
						  // echo $ii;
						
							
						 $returnarray[$ii]['upqty']=$rdata['quantity']-$pieces; 
						 $returnarray[$ii]['tableid']=$rdata['id'];
						$returnarray[$ii]['skuid']=$rdata['item_sku'];
						$returnarray[$ii]['quantity']=$rdata['quantity'];
						$returnarray[$ii]['sku']=$rdata['sku'];
						$returnarray[$ii]['slip_no']=$slip_no;
						$returnarray[$ii]['shelve_no']=$rdata['shelve_no'];
						$returnarray[$ii]['wh_id']=$rdata['wh_id'];
						 $pieces=0;
						}
						else
						{
							
						//echo $ii;
                                // $returnarray[$ii]['upqty']=$rdata['quantity']; 
								//  $returnarray[$ii]['tableid']=$rdata['id'];
								//  $returnarray[$ii]['skuid']=$rdata['item_sku'];
						        // $returnarray[$ii]['quantity']=$rdata['quantity'];
								// $returnarray[$ii]['wh_id']=$rdata['wh_id'];
								 
								 
							}
						  
						 
						
						}
						
						//echo $returnarray[$ii]['upqty']."==".$rdata['quantity']."<br>";
					//echo '<br>yy'. $pieces.'//'.$rdata['sku'];
					
					$ii++;
					
						
					//}
					
			 }
				//print_r($locationarray);
    			return array('succ'=>1,'stArray'=>$returnarray,'StockLocation'=>$locationarray);
    		} else {
                return 'Less Stock';
            }
        } else {
            return 'Invalid SKU';
        }
    }

}

if (!function_exists('CheckStockBackorder_ordergen_new')) {

    function CheckStockBackorder_ordergen_new($seller_id = null, $sku = null, $pieces = null, $slip_no = null, $sku_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        // echo $slip_no; 
        //echo $pieces."<br>";		 
        $inventory_dataqry = "select *,'".$sku."' as sku_name,'".$pieces."'  AS peice from item_inventory  where item_sku='" . trim($sku_id) . "' and item_inventory.shelve_no!='' and item_inventory.quantity>'".$pieces."' and seller_id='".$seller_id."' order by item_inventory.id asc";
        $qyery = $ci->db->query($inventory_dataqry);
        //$inventory_data=$this->dbh->FetchAllResults($inventory_dataqry);
        $inventory_data = $qyery->result_array();
        $inventory_data1 = $qyery->row_array();
        //echo '<pre>';
        foreach($inventory_data as $key=>$ndata)
        {
            if($key==0)
            $stockLocations= $ndata['shelve_no'];
            else
            $stockLocations.= ', '.$ndata['shelve_no'];

        }
        $returnarray[$ii]['upqty'] = 0;
        //$newpcs=$newpcs-$rdata['quantity'];	
        $pieces = $pieces - $rdata['quantity'];
        $returnarray[$ii]['tableid'] = $rdata['id'];
        $returnarray[$ii]['skuid'] = $rdata['item_sku'];
        $returnarray[$ii]['quantity'] = $rdata['quantity'];
        $returnarray[$ii]['sku'] = $rdata['sku'];
        $returnarray[$ii]['slip_no'] = $slip_no;
        $returnarray[$ii]['shelve_no'] = $rdata['shelve_no'];
        $returnarray[$ii]['seller_id'] = $rdata['seller_id'];
        $returnarray[$ii]['totalqty'] = $totalqty;
        $returnarray[$ii]['pieces'] = $pieces;
        $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
        $inventory_data1['shelve_no']=$stockLocations;
        // print_r($inventory_data);
        return $inventory_data1;
       
    }

}
if (!function_exists('UpdateStockBackorder_orderGen')) {

    function UpdateStockBackorder_orderGen($data = array()) {
        $ci = & get_instance();
        $ci->load->database();
        // echo '<pre>';
        // 	print_r($data); 






        foreach ($data as $rdata) {
            foreach ($rdata as $finaldata) {
               $updates = "update item_inventory set quantity='" . $finaldata['upqty'] . "' where id='" . $finaldata['tableid'] . "'";
             $ci->db->query($updates);
                if ($finaldata['slip_no'] != '' && $finaldata['pieces'] > 0) {
                    $newqty = $finaldata['totalqty'] - $finaldata['pieces'];
                    $insertdata = "insert into inventory_activity (user_id,seller_id,qty,p_qty,qty_used,item_sku,type,entrydate,awb_no) values('" . $ci->session->userdata('user_details')['user_id'] . "','" . $finaldata['seller_id'] . "','" . $newqty . "','" . $finaldata['totalqty'] . "','" . $finaldata['pieces'] . "','" . $finaldata['skuid'] . "','deducted','" . date('Y-m-d H:i:s') . "','" . $finaldata['slip_no'] . "')";
                    //$ci->db->query($insertdata);
                }
                $updates_dimation = "update diamention_fm set deducted_shelve='" . $finaldata['shelve_no'] . "' where slip_no='" . $finaldata['slip_no'] . "' and sku='" . $finaldata['sku'] . "' and deducted_shelve=''";
                // $ci->db->query($updates_dimation);
                $updates_ship = "update shipment_fm set wh_id='" . $finaldata['wh_id'] . "' where slip_no='" . $finaldata['slip_no'] . "' and wh_id='0'";
                // $ci->db->query($updates_ship);
                //echo $ci->db->last_query();
            }
        }
    }

}
if (!function_exists('UpdateStockBackorder')) {

    function UpdateStockBackorder($data = array()) {
        $ci = & get_instance();
        $ci->load->database();
        //echo 'ttt<pre>';
        //print_r($data); die;






        foreach ($data as $rdata) {
            foreach ($rdata as $finaldata) {
                $updates = "update item_inventory set quantity='" . $finaldata['upqty'] . "' where id='" . $finaldata['tableid'] . "'";
                $ci->db->query($updates);
                if ($finaldata['slip_no'] != '' && $finaldata['pieces'] > 0) {
                    $newqty = $finaldata['totalqty'] - $finaldata['pieces'];
                    $insertdata = "insert into inventory_activity (user_id,seller_id,qty,p_qty,qty_used,item_sku,type,entrydate,awb_no) values('" . $ci->session->userdata('user_details')['user_id'] . "','" . $finaldata['seller_id'] . "','" . $newqty . "','" . $finaldata['totalqty'] . "','" . $finaldata['pieces'] . "','" . $finaldata['skuid'] . "','deducted','" . date('Y-m-d H:i:s') . "','" . $finaldata['slip_no'] . "')";
                    $ci->db->query($insertdata);
                }
                $updates_dimation = "update diamention_fm set deducted_shelve='" . $finaldata['shelve_no'] . "' where slip_no='" . $finaldata['slip_no'] . "' and sku='" . $finaldata['sku'] . "' and deducted_shelve=''";
                $ci->db->query($updates_dimation);
                $updates_ship = "update shipment_fm set wh_id='" . $finaldata['wh_id'] . "' where slip_no='" . $finaldata['slip_no'] . "' and wh_id='0'";
                $ci->db->query($updates_ship);


                //echo $ci->db->last_query();
            }
        }
    }

}
if (!function_exists('statusCount_back')) {

    function statusCount_back($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        if ($ci->session->userdata('user_details')['user_type'] != 1) {
            $wh_id = $ci->session->userdata('user_details')['wh_id'];
            $cndition = " and wh_id='$wh_id'";
        }
        $sql = "SELECT COUNT(ID) as total_cnt FROM shipment_fm  where  deleted='N' and backorder='1' $cndition";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['total_cnt'];
    }

}
if (!function_exists('getcheckalreadyexitsFinance')) {

    function getcheckalreadyexitsFinance($id = null, $cat_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM finance_carges where cat_id='$cat_id' and seller_id='$id'";
        $query = $ci->db->query($sql);
        $count = $query->num_rows();
        return $count;
    }

}
if (!function_exists('getalluserstoragerates')) {

    function getalluserstoragerates($id = null, $storage_id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM storage_rate_table where storage_id='$storage_id' and client_id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getalluserfinanceRates')) {

    function getalluserfinanceRates($id = null, $cat_id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM finance_carges where cat_id='$cat_id' and seller_id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetallpickupChagresinvoice')) {

    function GetallpickupChagresinvoice($seller_id = null, $mdate = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM orderpickupinvoice where seller_id='$seller_id' and DATE(entrydate)='$mdate'";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query();
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}

if (!function_exists('GetallpickupChagres')) {

    function GetallpickupChagres($seller_id = null, $mdate = null, $slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM(orderpickupinvoice.pickupcharge) as totalcharge FROM orderpickupinvoice join orderoutboundinvoice on orderoutboundinvoice.seller_id= orderpickupinvoice.seller_id where orderpickupinvoice.seller_id='$seller_id' and DATE(orderpickupinvoice.entrydate)='$mdate' and orderoutboundinvoice.slip_no='$slip_no'";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); 
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}

if (!function_exists('GetallinboundChagres')) {

    function GetallinboundChagres($seller_id = null, $mdate = null, $slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM(orderpickupinvoice.inbound_charge) as totalcharge FROM orderpickupinvoice join orderoutboundinvoice on orderoutboundinvoice.seller_id= orderpickupinvoice.seller_id where orderpickupinvoice.seller_id='$seller_id' and DATE(orderpickupinvoice.entrydate)='$mdate' and orderoutboundinvoice.slip_no='$slip_no'";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); 
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}

if (!function_exists('GetallinventoryChagres')) {

    function GetallinventoryChagres($seller_id = null, $mdate = null, $slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM(orderpickupinvoice.inventory_charge) as totalcharge FROM orderpickupinvoice join orderoutboundinvoice on orderoutboundinvoice.seller_id= orderpickupinvoice.seller_id where orderpickupinvoice.seller_id='$seller_id' and DATE(orderpickupinvoice.entrydate)='$mdate' and orderoutboundinvoice.slip_no='$slip_no'";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); 
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}


if (!function_exists('Getalldailyrenteltransportreport')) {

    function Getalldailyrenteltransportreport($seller_id = null, $mdate = null, $slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT sum(storagesinvoices.storagerate) as totalcharge FROM `storagesinvoices` join diamention_fm on storagesinvoices.sku=diamention_fm.sku where diamention_fm.slip_no='$slip_no' and storagesinvoices.seller_id='$seller_id' and storagesinvoices.entrydate = '$mdate'";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); 
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}


if (!function_exists('GetallpackingChargetransport')) {

    function GetallpackingChargetransport($seller_id = null, $mdate = null, $slip_no = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM orderinvoicepicking where seller_id='$seller_id' and DATE(entrydate)='$mdate' and slip_no='$slip_no'";
        $query = $ci->db->query($sql);
        //return $this->db->last_query();
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}


if (!function_exists('GetalloutboundtransportChagres')) {

    function GetalloutboundtransportChagres($seller_id = null, $mdate = null, $slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM(outcharge) as totalcharge FROM orderoutboundinvoice where seller_id='$seller_id' and DATE(entrydate)='$mdate'  and slip_no='$slip_no' ";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); 
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}

if (!function_exists('GetalloutboundChargeinvoice')) {

    function GetalloutboundChargeinvoice($seller_id = null, $mdate = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM orderoutboundinvoice where seller_id='$seller_id' and DATE(entrydate)='$mdate'";
        $query = $ci->db->query($sql);
        //return $this->db->last_query();
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}
if (!function_exists('GetalldailyrentelChargesinvocie')) {

    function GetalldailyrentelChargesinvocie($seller_id = null, $mdate = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM storagesinvoices where seller_id='$seller_id' and DATE(entrydate)='$mdate'";
        $query = $ci->db->query($sql);
        //return $this->db->last_query();
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}
if (!function_exists('GetallpackingChargeinvoices')) {

    function GetallpackingChargeinvoices($seller_id = null, $mdate = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM orderinvoicepicking where seller_id='$seller_id' and DATE(entrydate)='$mdate'";
        $query = $ci->db->query($sql);
        //return $this->db->last_query();
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}
if (!function_exists('GetallPortelRentelChargesInvocie')) {

    function GetallPortelRentelChargesInvocie($seller_id = null, $mdate = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM clientportalinvocie where seller_id='$seller_id' and DATE(entrydate)='$mdate'";
        $query = $ci->db->query($sql);
        //return $this->db->last_query();
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}

if (!function_exists('Getbarcode_printInvoiceData')) {

    function Getbarcode_printInvoiceData($seller_id = null, $mdate = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM skubarcode_print where seller_id='$seller_id' and DATE(entrydate)='$mdate'";
        $query = $ci->db->query($sql);
        //return $this->db->last_query();
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}



if (!function_exists('GetpickupStatus')) {

    function GetpickupStatus($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT name FROM pickup_status where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['name'];
    }

}

if (!function_exists('getallitemskubyid')) {

    function getallitemskubyid($sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM items_m where sku='$sku'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['id'];
    }

}

if (!function_exists('getshelveNobyid')) {

    function getshelveNobyid($sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT shelve_no FROM item_inventory where item_sku='$sku' and quantity>0";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['shelve_no'];
    }

}


if (!function_exists('GetallremoveskuQty')) {

    function GetallremoveskuQty($sku = null, $seller_id = null, $uniqueid = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM pickup_request where sku='$sku' and code in('MSI','DI') and seller_id='$seller_id' and uniqueid='$uniqueid'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return count($result);
    }

}

if (!function_exists('getalldataitemtables')) {

    function getalldataitemtables($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM items_m where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getalldataitemtablesBySku')) {

    function getalldataitemtablesBySku($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM items_m where sku='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}


if (!function_exists('GetCourierCompanyDrop')) {

    function GetCourierCompanyDrop($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,company FROM courier_company where status='Y' and deleted='N'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('GetcuriertableData')) {

    function GetcuriertableData($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM courier_company where status='Y' and deleted='N' and id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}
if (!function_exists('getpromodegenrate')) {

    function getpromodegenrate($count = 8) {
        return (strtoupper(substr(md5(time()), 0, $count)));
    }

}


if (!function_exists('getUserNameByIdType')) {

    function getUserNameByIdType($id = null, $usertype = null, $Api_Integration = null) {

        $ci = & get_instance();
        $ci->load->database();

        if ($usertype == 'customer') {
            if ($Api_Integration == 'YES')
                $sql = "SELECT name as username FROM customer where seller_id='$id'";
            else
                $sql = "SELECT name as username FROM customer where id='$id'";
        } else
            $sql = "SELECT username FROM user_fm where user_id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['username'];
    }

}


if (!function_exists('statusCount')) {

    function statusCount($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        if ($ci->session->userdata('user_details')['user_type'] != 1) {
            $wh_id = $ci->session->userdata('user_details')['wh_id'];
            $cndition = " and wh_id='$wh_id'";
        }
        $sql = "SELECT COUNT(ID) as total_cnt FROM shipment_fm  where delivered='" . $id . "' and deleted='N' and backorder='0' $cndition";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['total_cnt'];
    }

}
if (!function_exists('getallsratusshipmentid')) {

    function getallsratusshipmentid($shipid = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM shipment_fm  where id='$shipid'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}


if (!function_exists('BookingIdCheck_cust')) {

    function BookingIdCheck_cust($booking_id = null, $cust_id = null, $id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $site_query = "select slip_no from shipment_fm where booking_id='" . trim($booking_id) . "' and cust_id='" . $cust_id . "' and deleted='N' and id!='$id'  ";
        $query = $ci->db->query($site_query);
        $result = $query->row_array();
        return $result['slip_no'];
    }

}
if (!function_exists('getAllDestination')) {

    function getAllDestination($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,city FROM country where deleted='N' and city!=''";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('getdestinationfieldshow')) {

    function getdestinationfieldshow($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM country where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getallmaincatstatus')) {

    function getallmaincatstatus($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM status_main_cat_fm where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}



if (!function_exists('checkPrivilageExitsForCustomer')) {

    function checkPrivilageExitsForCustomer($customer_id = null, $privilage_id = null) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "select privilage_array from set_user_privilage_fm where customer_id='" . $customer_id . "' ";
        $query = $ci->db->Query($sql);
        $data = $query->row_array();
        $privilage = $data['privilage_array'];

        $privilage_array = explode(',', $privilage);

        if (in_array($privilage_id, $privilage_array)) {
            return 'Y';
        } else {
            return 'N';
        }
    }

}

if (!function_exists('menuIdExitsInPrivilageArray')) {

    function menuIdExitsInPrivilageArray($menu_id) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "select privilage_array from set_user_privilage_fm where customer_id='" . $ci->session->userdata('user_details')['user_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        $privielage_array = explode(',', $result['privilage_array']);
        if ($ci->session->userdata('user_details')['user_type'] == 0) {
            $return_value = "Y";
        } else {
            if (in_array($menu_id, $privielage_array))
                $return_value = "Y";
            else
                $return_value = "N";
        }

        return $return_value;
    }

}

if (!function_exists('getIdfromCityName')) {

    function getIdfromCityName($city) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM country where deleted='N' and city Like '" . $city . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['id'];
    }

}

if (!function_exists('generate_hash')) {

    function generate_hash($salt, $password, $algo = 'sha256') {
        return hash($algo, $salt . $password);
    }

    if (!function_exists('barcodeRuntime')) {

        function barcodeRuntime($bar_code_id) {
            // Get pararameters that are passed in through $_GET or set to the default value
            $text = (isset($_GET["text"]) ? $_GET["text"] : $bar_code_id);
            $size = (isset($_GET["size"]) ? $_GET["size"] : "80");
            $orientation = (isset($_GET["orientation"]) ? $_GET["orientation"] : "horizontal");
            $code_type = (isset($_GET["codetype"]) ? $_GET["codetype"] : "code128");
            $code_string = "";

            // Translate the $text into barcode the correct $code_type
            if (strtolower($code_type) == "code128") {
                $chksum = 104;
                // Must not change order of array elements as the checksum depends on the array's key to validate final code
                $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "\`" => "111422", "a" => "121124", "b" => "121421", "c" => "141122", "d" => "141221", "e" => "112214", "f" => "112412", "g" => "122114", "h" => "122411", "i" => "142112", "j" => "142211", "k" => "241211", "l" => "221114", "m" => "413111", "n" => "241112", "o" => "134111", "p" => "111242", "q" => "121142", "r" => "121241", "s" => "114212", "t" => "124112", "u" => "124211", "v" => "411212", "w" => "421112", "x" => "421211", "y" => "212141", "z" => "214121", "{" => "412121", "|" => "111143", "}" => "111341", "~" => "131141", "DEL" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "FNC 4" => "114131", "CODE A" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
                $code_keys = array_keys($code_array);
                $code_values = array_flip($code_keys);
                for ($X = 1; $X <= strlen($text); $X++) {
                    $activeKey = substr($text, ($X - 1), 1);
                    $code_string .= $code_array[$activeKey];
                    $chksum = ($chksum + ($code_values[$activeKey] * $X));
                }
                $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

                $code_string = "211214" . $code_string . "2331112";
            } elseif (strtolower($code_type) == "codabar") {
                $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "-", "$", ":", "/", ".", "+", "A", "B", "C", "D");
                $code_array2 = array("1111221", "1112112", "2211111", "1121121", "2111121", "1211112", "1211211", "1221111", "2112111", "1111122", "1112211", "1122111", "2111212", "2121112", "2121211", "1121212", "1122121", "1212112", "1112122", "1112221");

                // Convert to uppercase
                $upper_text = strtoupper($text);

                for ($X = 1; $X <= strlen($upper_text); $X++) {
                    for ($Y = 0; $Y < count($code_array1); $Y++) {
                        if (substr($upper_text, ($X - 1), 1) == $code_array1[$Y])
                            $code_string .= $code_array2[$Y] . "1";
                    }
                }
                $code_string = "11221211" . $code_string . "1122121";
            }

            // Pad the edges of the barcode
            $code_length = 40;
            for ($i = 1; $i <= strlen($code_string); $i++)
                $code_length = $code_length + (integer) (substr($code_string, ($i - 1), 1));

            if (strtolower($orientation) == "horizontal") {
                $img_width = $code_length;
                $img_height = $size;
            } else {
                $img_width = $size;
                $img_height = $code_length;
            }

            $image = imagecreate($img_width, $img_height);
            $black = imagecolorallocate($image, 0, 0, 0);
            $white = imagecolorallocate($image, 255, 255, 255);

            imagefill($image, 0, 0, $white);

            $location = 10;
            for ($position = 1; $position <= strlen($code_string); $position++) {
                $cur_size = $location + ( substr($code_string, ($position - 1), 1) );
                if (strtolower($orientation) == "horizontal")
                    imagefilledrectangle($image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black));
                else
                    imagefilledrectangle($image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black));
                $location = $cur_size;
            }

            ob_start();

            imagejpeg($image);
            imagedestroy($image);

            $data = ob_get_contents();

            ob_end_clean();

            $image = "data:image/jpeg;base64," . base64_encode($data);
            return $image;
            // Draw barcode to the screen
            //imagejpeg($image,$path,100);	
            //header ('Content-type: image/png');
            //imagepng($image);
            //imagedestroy($image);
        }

    }
}