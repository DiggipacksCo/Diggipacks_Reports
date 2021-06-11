<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if (!function_exists('asset_url()')) {

    function asset_url() {
        return base_url() . 'assets/';
    }

}

if (!function_exists('todcount')) {

    function todcount($char=null,$i=null) {

        $i=$i+1;
        $ci = & get_instance();
        $ci->load->database();
        $SQL_esnad = "select tod_no from tods_tbl where  super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and tod_no like '".$char.$i."' order by id desc limit 1";
        $query = $ci->db->query($SQL_esnad);
        if( $query->num_rows()>0)
        {
           return todcount($char,$i);
        }
        else
        {

            return $i; 
        }
       

       
       
    }

}  

function remove_phone_format($number) {

    $number = ltrim($number, '966 ');
    $number = ltrim($number, '0');
    $number = str_replace(' ', '', $number);
    return $number;
}

function updateEsdadAWB($data) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->db->insert('tbl_esnad_awb_live', $data);
}

if (!function_exists('Get_esnad_awb')) {

    function Get_esnad_awb($start_awb_sequence, $end_awb_sequence) {

        $ci = & get_instance();
        $ci->load->database();
        $SQL_esnad = "select esnad_awb_no from tbl_esnad_awb_live where  super_id='" . $ci->session->userdata('user_details')['super_id'] . "' order by id desc limit 1";
        $query = $ci->db->query($SQL_esnad);
        $result = $query->row_array();
        $ESNAD_AWB = $result['esnad_awb_no'];
       // echo   $ESNAD_AWB; die; 
        if ($ESNAD_AWB >= $start_awb_sequence && $ESNAD_AWB < $end_awb_sequence) {
            return $ESNAD_AWB;
        } else {

            return '0';
        }
    }

}      

function send_data_to_curl($data, $url, $header) {
    $curl_req = curl_init($url);
    curl_setopt($curl_req, CURLOPT_POSTFIELDS, $data);
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
    curl_close($curl_req);
    return $response;
}

if (!function_exists('GetallitemcheckDuplicate')) {

    function GetallitemcheckDuplicate($sku) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM items_m where sku='$sku' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $countdata = $query->num_rows();
        $row = $query->row_array();
        if ($countdata > 0)
            return $row['id'];
        else
            return 0;
    }

}

if (!function_exists('type_of_vehicleFiled')) {

    function type_of_vehicleFiled($id=null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT icon_path FROM type_of_vehicle where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $countdata = $query->num_rows();
        $row = $query->row_array();
        if ($countdata > 0)
            return $row['icon_path'];
        else
            return false;
    }

}



if (!function_exists('GetstockID')) {

    function GetstockID($seller_id=null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT lastno FROM stockLocation where  seller_id='$seller_id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' order by id desc";
        $query = $ci->db->query($sql);
        
        $row = $query->row_array();
        if (!empty($row))
            return $row['lastno'];
        else
            return 0;
    }

}

if (!function_exists('GetCountUnseenManifestNew')) {

    function GetCountUnseenManifestNew($code=null,$other=null) {
        $ci = & get_instance();
        $ci->load->database();
        if($code=='PR')
        {
            $condition=" and seen='0'";
        }
          if($other=='N')
        {
            $condition2=" and confirmO='N'";
        }
        
       $sql = "SELECT count(id) as total_show FROM pickup_request where    super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and  code='$code' $condition $condition2 group by uniqueid";
        $query = $ci->db->query($sql);
       return $query->num_rows();
    }

}

if (!function_exists('GetCountFullfilTicketStatus')) {

    function GetCountFullfilTicketStatus($code=null,$other=null) {
        $ci = & get_instance();
        $ci->load->database();
        
        
       $sql = "SELECT count(id) as total_show FROM ticket where    super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and  status!='complated'  group by ticket_id";
        $query = $ci->db->query($sql);
       return $query->num_rows();
    }

}
if (!function_exists('GetCountManifestTicketStatus')) {

    function GetCountManifestTicketStatus($code=null,$other=null) {
        $ci = & get_instance();
        $ci->load->database();
        
        
       $sql = "SELECT count(id) as total_show FROM pickup_ticket where    super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and  status!='complated'  group by ticket_id";
        $query = $ci->db->query($sql);
       return $query->num_rows();
    }

}
if (!function_exists('GetcheckSlipNo3plButton_bulpage')) {

    function GetcheckSlipNo3plButton_bulpage() {
        $ci = & get_instance();
        $ci->load->database();
        $listingQry = "select shipment_fm.frwd_company_id,shipment_fm.cust_id,courier_company.cc_id,courier_company.company from shipment_fm LEFT JOIN  courier_company ON shipment_fm.frwd_company_id=courier_company.cc_id where shipment_fm.deleted='N'  and shipment_fm.frwd_company_id>0 and shipment_fm.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and courier_company.company!='' group by courier_company.company ORDER BY shipment_fm.frwd_company_id asc";
        $query = $ci->db->query($listingQry);
        $status_update_data = $query->result_array();
        return $status_update_data;
    }

}
if (!function_exists('GetcheckSlipNo3plButton')) {

    function GetcheckSlipNo3plButton($pickupId = null) {
        $ci = & get_instance();
        $ci->load->database();
        $listingQry = "select shipment_fm.frwd_company_id,courier_company.company from pickuplist_tbl INNER JOIN shipment_fm ON pickuplist_tbl.slip_no=shipment_fm.slip_no INNER JOIN  courier_company ON shipment_fm.frwd_company_id=courier_company.id where pickuplist_tbl.deleted='N' and pickuplist_tbl.pickupId='$pickupId' and shipment_fm.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and shipment_fm.frwd_company_id>0 group by shipment_fm.frwd_company_id  ORDER  BY shipment_fm.frwd_company_id asc";
        $query = $ci->db->query($listingQry);
        $status_update_data = $query->result_array();
        return $status_update_data;
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
        $sql = "SELECT * FROM item_inventory where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}
if (!function_exists('GetSellerTableField')) {

    function GetSellerTableField($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM seller_m where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('Getselletdetails')) {
    function Getselletdetails() {

        $ci = & get_instance();
        $ci->load->database();
        $user_id = $ci->session->userdata('user_details')['user_id']; 
        //echo "<pre>";   print_r($ci->session->userdata()); 
         $sql = "SELECT * FROM user where id =" . $user_id;
        $query = $ci->db->query($sql);
        $result = $query->result_array();
     
        return $result;
    }
}

if (!function_exists('Getallstorage_drop')) {
    function Getallstorage_drop() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM storage_table where deleted='N' AND status='Y' AND super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
}
if (!function_exists('GetwherehouseDropShow')) {

    function GetwherehouseDropShow($id = null) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name FROM warehouse_category where deleted='N' and status='Y' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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

function clex_label_curl($Auth_token, $client_awb) {
    $label_url = "http://cockpit.clexsa.com/pdf/download/$client_awb?id=$client_awb&type=A4-consignment_clex_label&return_type=pdf";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $label_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $label_response = curl_exec($ch);
    curl_close($ch);
    $label_json_data = json_encode($label_response);
    $label_res_array = json_decode($label_response, true);
    $label_url_new = $label_res_array['data']['awb_pdf_url'];
    return $label_url_new;
}

function zajil_label_curl($Auth_token,$client_awb) {
        $label_api_url = "https://api.zajil-express.com/api/customer/integration/consignment/shippinglabel/stream?reference_number=$client_awb&is_small=true";
        $headers_label = array(
            "Content-type:application/json",
            "api-key:$Auth_token");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $label_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_label);

        $label_response = curl_exec($ch);
        curl_close($ch);
        return $label_response;
}


if (!function_exists('Getwarehouse_categoryfield')) {

    function Getwarehouse_categoryfield($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM warehouse_category where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('Getwarehouse_categoryfield_name')) {

    function Getwarehouse_categoryfield_name($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT $field FROM warehouse_category where name='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        if(count($result)>0)
        {
        return $result[$field];
        }
 else {
     return 0;
     
 }
    }

}
if (!function_exists('Getwarehouse_Dropdata')) {

    function Getwarehouse_Dropdata() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name FROM warehouse_category where status='Y' and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('GetCourCompanynameId')) {
    function GetCourCompanynameId($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM courier_company where cc_id='$id' and super_id='".$ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        // echo   $ci->db->last_query();
        // die; 
        $result = $query->row_array();
        return $result[$field];
    }
}
if (!function_exists('GetCourCompanynameIdbulkprint')) {
    function GetCourCompanynameIdbulkprint($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM courier_company where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        // echo   $ci->db->last_query();
        // die; 
        $result = $query->row_array();
        return $result[$field];
    }
}
if (!function_exists('GetCourierCompanyStausActive')) {
    function GetCourierCompanyStausActive($name = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT status FROM courier_company where company='$name' and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['status'];
    }
}

if (!function_exists('getdestinationfieldshow_array')) {

    function getdestinationfieldshow_array($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        if (!empty($id)) {
            $sql = "SELECT $field FROM country where id IN ($id) and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
            $query = $ci->db->query($sql);
            $result = $query->result_array();
            foreach ($result as $ndata) {
                $rdata .= $ndata['city'] . ',';
            }
            return $rdata;
        }
    }

}

if (!function_exists('getdestinationfieldshow_auto_array')) {

    function getdestinationfieldshow_auto_array($id = null, $field = null,$super_id) {
        $ci = & get_instance();
        $ci->load->database();
        if (!empty($id)) {
            $sql = "SELECT $field FROM country where id IN ($id) and super_id='".$super_id."'";
            $query = $ci->db->query($sql);
            $result = $query->row_array();
            return  $result[$field];
           
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
        $listingQry = "select slip_no from shipment_fm where deleted='N' and ( slip_no IN  (" . $awb . ")) ORDER  BY FIELD(slip_no," . $awb . ") and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($listingQry);

        if ($query->num_rows()) {
            $status_update_data = $query->result_array();
            //print_r($status_update_data); exit;  
            //
            $fileArray = array();
            foreach ($status_update_data as $key => $val) {

                $filePath = '/var/www/html/diggipack_new/demofulfillment/assets/all_labels/' . $status_update_data[$key]['slip_no'] . '.pdf';
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
            $listingQry = "select frwd_awb_no from shipment where deleted='N' and id IN  (" . $awb . ") and `frwd_throw` LIKE 'smsa' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        } else {
            $listingQry = "select frwd_awb_no from shipment where deleted='N' and ( slip_no IN  (" . $awb . ")) and `frwd_throw` LIKE 'SAMSA' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";

            $query = $ci->db->query($listingQry);


            if ($query->num_rows() <= 0) {
                $listingQry = "select frwd_awb_no from shipment where deleted='N' and  booking_id IN  (" . $awb . ") and `frwd_throw` LIKE 'SAMSA' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ORDER  BY FIELD(booking_id," . $awb . ")";
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
            $listingQry = "select slip_no,frwd_throw,frwd_awb_no from shipment where deleted='N' and id IN  (" . $awb . ") and  and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'`frwd_throw` IN ('ARAMEX')";
        } else {
            $listingQry = "select slip_no,frwd_throw,frwd_awb_no from shipment where deleted='N' and ( slip_no IN  (" . $awb . ")) and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and `frwd_throw` IN ('ARAMEX')";
            $query = $ci->db->query($listingQry);

            if ($query->num_rows() <= 0) {
                $listingQry = "select slip_no,frwd_throw,frwd_awb_no from shipment where deleted='N' and  booking_id IN  (" . $awb . ") and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and `frwd_throw` IN ('ARAMEX') ORDER  BY FIELD(booking_id," . $awb . ")";
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
        $sql = "SELECT seller_m.name as seller_name,shipment_fm.reciever_phone FROM `seller_m` LEFT join shipment_fm on shipment_fm.cust_id=seller_m.customer where shipment_fm.slip_no='" . $slip_no . "' and shipment_fm.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $inventory_dataqry = "select sum(item_inventory.quantity)as quantity from item_inventory left join items_m on item_inventory.item_sku=items_m.id where item_inventory.seller_id='" . $seller_id . "'  and  items_m.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'  and item_inventory.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and items_m.sku like'" . trim($sku) . "'";
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
        $sql = "SELECT uniqueid FROM customer where seller_id='$cust_id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['uniqueid'];
    }

}

if (!function_exists('packedcount')) {

    function packedcount($id = null) {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT count(id) as  packedcount FROM pickuplist_tbl where pickupId='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' $cndition";
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
        $sql = "SELECT uniqueid FROM customer where id='$cust_id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['uniqueid'];
    }

}


if (!function_exists('GetallaccountidBysellerID')) {

    function GetallaccountidBysellerID($uid = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM customer where uniqueid='$uid' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        if(count($result)>0)
        {
        return $result['id'];
        }
        else
        {
            return 0;
        }
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
        $selectslip = "select shipment.reciever_phone,shipment.slip_no,shipment.frwd_awb_no,shipment.frwd_throw,seller_m.name as seller_name from shipment left join seller_m on shipment.cust_id=seller_m.customer where shipment.slip_no='" . trim($slip_no) . "' and shipment.deleted='N' and shipment.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT count(shelve_no) as tpallet FROM item_inventory where super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['tpallet'];
    }

}

if (!function_exists('GetuserToatalLOcationQty')) {

    function GetuserToatalLOcationQty($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM item_inventory where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('invoiceCountnew')) {

    function invoiceCountnew($invoice_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $siteQry = "select count(id) as total_numCount from Payable_invoice_fm where invoice_no='" . $invoice_no . "' ";
        $query = $ci->db->query($siteQry);
        $invoiceCountData = $query->row_array();

        return $invoiceCountData;
    }

}

if (!function_exists('GetcustomerDropdata')) {

    function GetcustomerDropdata() {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT id,uniqueid,name as company FROM customer WHERE  status='Y' and deleted='N' and access_fm='Y' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $statusData = $query->result_array();
        return $statusData;
    }

}

if (!function_exists('getstaff_multycreated')) {

    function getstaff_multycreated($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $siteQry = "select name,id from user where status='Y' and deleted='N'   order by name asc";

        $query = $ci->db->query($siteQry);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('invoiceDetailnew')) {

    function invoiceDetailnew($invoice_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $siteQry = "select SUM(cod_charge) as cod_charge_sum,SUM(return_charge) as return_charge_sum,SUM(service_charge) as service_charge_sum,SUM(cod_amount) as cod_amount_sum,SUM(vat) as vat_sum from Payable_invoice_fm where invoice_no='" . $invoice_no . "'";
        $query = $ci->db->query($siteQry);
        $invoiceCountData = $query->row_array();
        return $invoiceCountData;
    }

}

if (!function_exists('Get_user_name')) {

    function Get_user_name($id = null, $type = null) {


        $ci = & get_instance();
        $ci->load->database();
        if ($type == 'user') {
            $siteQry = "SELECT name FROM user WHERE id='" . $id . "' ";
            $query = $ci->db->query($siteQry);
            $countrydata = $query->result_array();
            $name = $countrydata[0]['name'];
        }
        if ($type == 'customer') {
            // $siteQry = "SELECT name FROM customer WHERE id='" . $id . "' ";
            // $query = $ci->db->query($siteQry);
            // $countrydata = $query->result_array();
            //$name = $countrydata[0]['name'];
            $name = 'Customer';
        }
        if ($type == 'driver') {
            $siteQry = "SELECT messenger_name FROM courier_staff WHERE cor_id='" . $id . "'  ";
            $query = $ci->db->query($siteQry);
            $countrydata = $query->result_array();
            $name = $countrydata[0]['messenger_name'];
        }
        return $name;
    }

}
if (!function_exists('GetuserSkuAllqty')) {

    function GetuserSkuAllqty($seller_id = null, $item_sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM(quantity) as tqty FROM item_inventory where seller_id='$seller_id'  and item_sku='$item_sku' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['tqty'];
    }

}
if (!function_exists('site_configTable')) {

    function site_configTable($field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select $field from site_config where super_id='" . $ci->session->userdata('user_details')['super_id']. "'";
        $query = $ci->db->query($sql);
        //echo $ci->db->last_query();exit;
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('site_config')) {

    function site_config($url = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select * from site_config where site_url like '%" . $url . "%'";
        $query = $ci->db->query($sql);
        $result = $query->row();
        return $result;
    }

}

if (!function_exists('site_config_default')) {

    function site_config_default() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select * from site_config where id=1";
        $query = $ci->db->query($sql);
        $result = $query->row();
        return $result;
    }

}

if (!function_exists('Getsite_configData')) {

    function Getsite_configData($field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM site_config where super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}

if (!function_exists('Getsite_configData_field')) {

    function Getsite_configData_field($field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM site_config where super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

// if(!function_exists('GetcheckSlipNo3plButton_bulpage')){
//	  function GetcheckSlipNo3plButton_bulpage(){
//                 $ci=& get_instance();
//                 $ci->load->database();
//              	 $listingQry = "select shipment_fm.frwd_company_id,courier_company.company from shipment_fm LEFT JOIN  courier_company ON shipment_fm.frwd_company_id=courier_company.id where shipment_fm.deleted='N'  and shipment_fm.frwd_company_id>0 group by shipment_fm.frwd_company_id  ORDER  BY shipment_fm.frwd_company_id asc";
//		 $query = $ci->db->query($listingQry);
//                 $status_update_data=$query->result_array();
//                 return $status_update_data;
//          }
//           }

if (!function_exists('Get_e_citySlipCheck')) {

    function Get_e_citySlipCheck($pickupId = null, $e_city_ids = array()) {
        //print_r($e_city_ids);
        $ci = & get_instance();
        $ci->load->database();
        $ci->db->select('shipment_fm.slip_no');
        $ci->db->from('pickuplist_tbl');
        $ci->db->join('shipment_fm', 'shipment_fm.slip_no=pickuplist_tbl.slip_no');
        $ci->db->where('pickuplist_tbl.pickupId', $pickupId);
        $ci->db->where('pickuplist_tbl.super_id', $ci->session->userdata('user_details')['super_id']);
        $ci->db->where("shipment_fm.frwd_company_id=''");
        $ci->db->where_in('shipment_fm.destination', $e_city_ids);
        $query = $ci->db->get();
        $result = $query->result_array();
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

}

if (!function_exists('PrintPiclist3PL')) {

    function PrintPiclist3PL($pickupId, $frwd_company_id = null) {
        $ci = & get_instance();
        $ci->load->database();

        //all_labels
        $listingQry = "select pickuplist_tbl.slip_no,shipment_fm.label_type,shipment_fm.frwd_company_label from pickuplist_tbl LEFT JOIN shipment_fm ON pickuplist_tbl.slip_no=shipment_fm.slip_no where pickuplist_tbl.pickupId='$pickupId' and shipment_fm.frwd_company_id='$frwd_company_id' ORDER  BY pickuplist_tbl.pickupId asc";
        $query = $ci->db->query($listingQry);

        //echo '<pre>';

        if ($query->num_rows()) {
            $status_update_data = $query->result_array();
            // print_r($status_update_data); exit;  


            $fileArray = array();
            $checkIds = array();
            foreach ($status_update_data as $key => $val) {
                array_push($checkIds, $val['frwd_company_id']);
                if ($val['label_type'] == 1) {
                    $awb_no = $val['slip_no'];

                    if (!file_exists("assets/all_labels/$awb_no.pdf") || filesize("assets/all_labels/$awb_no.pdf") <= 0) {
                        //echo "ssssssss"; 
                        $generated_pdf = file_get_contents($val['frwd_company_label']);
                        $encoded = base64_decode($generated_pdf);
                        //header('Content-Type: application/pdf');
                        file_put_contents("assets/all_labels/$awb_no.pdf", $generated_pdf);
                    }
                }

                //$filePath='https://demosony.fastcoo-solutions.com/fm/assets/all_labels/SOF7362389516.pdf';
                $filePath = '/var/www/html/diggipack_new/demofulfillment/assets/all_labels/' . $status_update_data[$key]['slip_no'] . '.pdf';
                if (file_exists($filePath))
                    array_push($fileArray, $filePath);
            }

            require('../fpdf_new/fpdf.php');
            require('../fpdi/fpdi.php');
            //echo '<pre>';
           // print_r($fileArray);exit;

            $files = $fileArray;

           if (GetCourCompanynameIdbulkprint($frwd_company_id, 'company') == 'Clex' || GetCourCompanynameIdbulkprint($frwd_company_id, 'company') == 'Labaih' ) {
                $pdf = new FPDI('P', 'mm');
            } 
            else if ( GetCourCompanynameIdbulkprint($frwd_company_id, 'company') == 'Shipadelivery') {
                $pdf = new FPDI('L', 'mm', array(102, 160));
            }
            else if (GetCourCompanynameIdbulkprint($frwd_company_id, 'company') == 'Saee'){
                $pdf = new FPDI('P', 'mm', array(250, 175));

            }  else if (GetCourCompanynameIdbulkprint($frwd_company_id, 'company') == 'Beez'){
                $pdf = new FPDI('P', 'mm', array(170, 130));
            }
            else if (GetCourCompanynameIdbulkprint($frwd_company_id, 'company') == 'Barqfleet' || GetCourCompanynameIdbulkprint($frwd_company_id, 'company') == 'GLT' ) {
                $pdf = new FPDI('P', 'mm', array(110, 160));
            } else {
                $pdf = new FPDI('P', 'mm', array(102, 160));
            }


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
            $pdf->Output('I', '3pl-' . date('Ymdhis') . '.pdf');
        }
    }

    //print_r($awb); die();
}

if (!function_exists('PrintPiclist3PL_bulk')) {

    function PrintPiclist3PL_bulk($slip_nos = array(), $frwd_company_id = null) {

        $ci = & get_instance();
        $ci->load->database();
        $ci->db->where('super_id', $ci->session->userdata('user_details')['super_id']);
        $ci->db->select('*');
        $ci->db->from('shipment_fm');
        $ci->db->where_in('slip_no', $slip_nos);
        if (!empty($frwd_company_id)) {
            // $ci->db->where('frwd_company_id',$frwd_company_id);
        }
        $ci->db->order_by('shipment_fm.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $ci->db->get();
        // echo $ci->db->last_query();die;
        $status_update_data = $query->result_array();

        if (sizeof($status_update_data) > 0) {
            // echo "ssss"; die;
            //print_r($status_update_data); exit;  


            $fileArray = array();
            $checkIds = array();
            foreach ($status_update_data as $key => $val) {
                array_push($checkIds, $val['frwd_company_id']);
                if ($val['label_type'] == 1) {
                    $awb_no = $val['frwd_company_awb'];

                    if (!file_exists("assets/all_labels/$awb_no.pdf") || filesize("assets/all_labels/$awb_no.pdf") <= 0) {
                        //echo "ssssssss"; 
                        $generated_pdf = file_get_contents($val['frwd_company_label']);
                        $encoded = base64_decode($generated_pdf);
                        //header('Content-Type: application/pdf');
                        file_put_contents("assets/all_labels/$awb_no.pdf", $generated_pdf);
                    }
                }

                // $filePath='https://demosony.fastcoo-solutions.com/fm/assets/all_labels/SOF7362389516.pdf';
                $filePath = '/var/www/html/diggipack_new/demofulfillment/assets/all_labels/' . $status_update_data[$key]['slip_no'] . '.pdf';

                //echo $filePath; die();
                if (file_exists($filePath))
                    array_push($fileArray, $filePath);
            }

            
            require('../fpdf_new/fpdf.php');
            require('../fpdi/fpdi.php');
            //echo '<pre>';
            //print_r($fileArray);exit;

            $files = $fileArray;
            // echo $frwd_company_id; 
            // echo GetCourCompanynameId($frwd_company_id, 'company');  die;
            if (GetCourCompanynameId($frwd_company_id, 'company') == 'Clex' || GetCourCompanynameId($frwd_company_id, 'company') == 'Labaih' ) {
                $pdf = new FPDI('P', 'mm');
            } 
            else if ( GetCourCompanynameId($frwd_company_id, 'company') == 'Shipadelivery') {
                $pdf = new FPDI('L', 'mm', array(102, 160));
            }
            else if (GetCourCompanynameId($frwd_company_id, 'company') == 'Saee'){
                $pdf = new FPDI('P', 'mm', array(250, 175));
            }  else if (GetCourCompanynameId($frwd_company_id, 'company') == 'Beez'){
                $pdf = new FPDI('P', 'mm', array(170, 130));
            }
            else if (GetCourCompanynameId($frwd_company_id, 'company') == 'SLS'){
                $pdf = new FPDI('P', 'mm', array(200, 275));
            }
            else if (GetCourCompanynameId($frwd_company_id, 'company') == 'Barqfleet' || GetCourCompanynameId($frwd_company_id, 'company') == 'GLT' ) {
                $pdf = new FPDI('P', 'mm', array(110, 160));
            } else {
                $pdf = new FPDI('P', 'mm', array(102, 160));
            }


            
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
            $pdf->Output('I', '3pl-' . date('Ymdhis') . '.pdf');
            die;
        } else {

            return false;
        }
        //  return false;
    }

    //print_r($awb); die();
}



if (!function_exists('GetcheckConditionsAddInventory')) {

    function GetcheckConditionsAddInventory($id) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT itemupdated FROM `pickup_request` WHERE `uniqueid`='$id' and code in ('MSI','DI') and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $row = $query->row_array();
        return $row['itemupdated'];
    }

}
if (!function_exists('getusertypedropdown')) {

    function getusertypedropdown($id = null) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,designation_name FROM designation_tbl where id!='1' and  type='F'";
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
        $sql = "SELECT count(id) as tqty FROM `pickup_request` WHERE `uniqueid`='$uid' and code='RI' and seller_id='$sid' and sku='$sku' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";

        $query = $ci->db->query($sql);
        $row = $query->row_array();
        return $row['tqty'];
    }

}
if (!function_exists('getusertypenameshow')) {

    function getusertypenameshow($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,designation_name FROM designation_tbl where id='$id' ";
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
        $sql = "select id from shipment_fm where  status='Y' and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' $status_condition $current_date_new ";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return count($result);
    }

}

if (!function_exists('getUserNameById')) {

    function getUserNameById($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT username FROM user where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['username'];
    }

}

function DaysNames() {
    $weekOfdays = array();
    $day = date('l');
    $weekOfdays[] = $day;
    $day = strtotime($day);
    $total_days = '6'; //you can increase or decrease the day to display
    for ($i = 1; $i <= $total_days; $i++) {
        $next = strtotime("+$i day", $day);
        $weekOfdays[] = date("l", $next);
    }
    return $weekOfdays;
}

if (!function_exists('getUserNameById_field')) {

    function getUserNameById_field($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM user where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getUserNameById_field')) {

    function getUserNameById_field($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM user where id='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetUserDropDownShowArr')) {

    function GetUserDropDownShowArr($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM user where super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and user_type>0 and system_access_fm='Y' and super_id!=0";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('getcheckslavenovalid')) {

    function getcheckslavenovalid($slave = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM warehous_shelve_no_fm where shelv_no='$slave' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "select (select id from items_m where items_m.sku=diamention_fm.sku and items_m.super_id='" . $ci->session->userdata('user_details')['super_id'] . "')as itmSku,piece from diamention_fm where deleted='N' and slip_no='" . $slip_no . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "select * from diamention_fm where deleted='N' and slip_no='" . $slip_no . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        //echo  $ci->db->last_query; die();                
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('GetdiaMationTableDataFind')) {

    function GetdiaMationTableDataFind($slip_no = null, $sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select * from diamention_fm where deleted='N' and slip_no='" . $slip_no . "' and sku='$sku' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        //echo  $ci->db->last_query; die();                
        $result = $query->row_array();
        return $result;
    }
    

}


if (!function_exists('getallsellerdatabyID')) {

    function getallsellerdatabyID($id = null, $field = null,$super_id =null) {
        $ci = & get_instance();
        $ci->load->database();
        //$sql = "SELECT $field FROM customer where id='$id' and access_fm='Y' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
         $sql = "SELECT $field FROM customer where id='$id' and access_fm='Y' and super_id='" . $super_id . "'";
     
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getallscanQunatitybyID')) {

    function getallscanQunatitybyID($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT count(id) as totalcount FROM package_report where slip_no='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['totalcount'];
    }

}

if (!function_exists('getallskuQuantitybyID')) {

    function getallskuQuantitybyID($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT piece FROM diamention_fm where slip_no='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['piece'];
    }

}

if (!function_exists('getalldataitemtablesSKU')) {

    function getalldataitemtablesSKU($sku = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM items_m where sku='$sku' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('GetallCutomerBysellerId')) {

    function GetallCutomerBysellerId($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM customer where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('Getallstoragetablefield')) {

    function Getallstoragetablefield($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM storage_table where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}


if (!function_exists('getpickuprequestData')) {

    function getpickuprequestData($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM pickup_request where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetshpmentDataByawb')) {

    function GetshpmentDataByawb($awb = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM shipment_fm  where slip_no='$awb' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('GetshipmentRowsDetailsPage')) {

    function GetshipmentRowsDetailsPage($awb = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM shipment_fm  where slip_no='$awb' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}

if (!function_exists('GetCityAllDataByname')) {

    function GetCityAllDataByname($name = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM country where city='$name' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        //echo $ci->db->last_query();exit;
        return $query->row_array();
        //return $result['id'];
    }

}
if (!function_exists('Getallsellerdata')) {

    function Getallsellerdata($ids = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name,company FROM customer where  super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and company!='' and access_fm='Y'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}

if (!function_exists('Getallsellerdata_new')) {

    function Getallsellerdata_new($ids = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,name,company FROM customer where  super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and access_fm='Y'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}

if (!function_exists('Getallinvoicedata')) {

    function Getallinvoicedata($invoice_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,invoice_no,slip_no FROM fixrate_invoice where  super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and invoice_no='C2021028'";
        $query = $ci->db->query($sql);
        return $this->db->last_query(); exit; 
        $result = $query->result_array();

        return $result;
    }

}
if(!function_exists('GetalldashboardClientField')){
      function GetalldashboardClientField($id=null, $field=null){
        $ci=& get_instance();
        $ci->load->database();
        $sql ="SELECT $field FROM customer where id ='$id'";
        $query = $ci->db->query($sql);
        $row=$query->row_array();
        return $row[$field];
        
        
      }
    }



if (!function_exists('GetSinglesellerdata')) {

    function GetSinglesellerdata($id = null,$super_id) {
        $ci = & get_instance();
        $ci->load->database();
        //$sql = "SELECT * FROM customer where  super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and id='$id' and access_fm='Y'";
        $sql = "SELECT * FROM customer where  super_id='" . $super_id . "' and id='$id' and access_fm='Y'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}

if (!function_exists('Getselletdetails_new')) {

    function Getselletdetails_new($super_id) {
        
        $ci = & get_instance();
        $ci->load->database();
        //$sql = "SELECT * FROM user where id ='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $sql = "SELECT * FROM user where id ='" . $super_id . "'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
     // echo   $ci->db->last_query(); exit; 
        return $result;
    }

}

if (!function_exists('getcheckalreadyexitsstorage')) {

    function getcheckalreadyexitsstorage($id = null, $storage_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM storage_rate_table where storage_id='$storage_id' and client_id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $inventory_dataqry = "select item_inventory.*,items_m.sku from item_inventory left join items_m on item_inventory.item_sku=items_m.id where item_inventory.seller_id='" . $seller_id . "' and items_m.sku like'" . trim($sku) . "' and  items_m.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and item_inventory.shelve_no!='' and item_inventory.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and item_inventory.quantity>0 order by item_inventory.id asc";
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

    function CheckStockBackorder_ordergen($seller_id = null, $sku = null, $pieces = null, $slip_no = null, $sku_id = null) {
        $ci = & get_instance();
        $ci->load->database();

        $expire_block = getalldataitemtablesBySku($sku, 'expire_block');
        if ($expire_block == 'Y') {
            $current_date = date("Y-m-d");
            $conditionCheck = " and expiry='N' and expity_date>='$current_date'";
        }
        $inventory_dataqry = "select item_inventory.*,items_m.sku,items_m.weight from item_inventory left join items_m on item_inventory.item_sku=items_m.id where item_inventory.seller_id='" . $seller_id . "' and items_m.sku like'" . trim($sku) . "' and item_inventory.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and items_m.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and item_inventory.quantity>0 $conditionCheck order by  item_inventory.id asc";
        $query = $ci->db->query($inventory_dataqry);
       




        if ($query->num_rows() > 0) {
            $inventory_data = $query->result_array();
            $returnarray = array();
            $totalqty = 0;
            $totalqty1 = 0;
            $stock_location_new=array();
            $locationarray = array();
            $error_array = array();
            $countInventry = count($inventory_data) - 1;
            $finalLoopArray = array();
            $werehouseArr = array();

            foreach ($inventory_data as $key11 => $rdata) {
                if ($totalqty < $pieces) {
                    if ($key11 == 0)
                        array_push($werehouseArr, $rdata['wh_id']);

                    if (in_array($rdata['wh_id'], $werehouseArr)) {
                        $totalqty += $rdata['quantity'];

                        array_push($finalLoopArray, $rdata);
                    }
                    if ($key11 == $countInventry) {
                        if ($totalqty < $pieces) {
                            array_push($error_array, $rdata);
                        }
                    }
                }
            }
            if (empty($error_array)) {
                if ($pieces <= $totalqty) {
                    $newpcs = $pieces;
                    $ii = 0;
                    $palletArrayCeck = array();
                    $shelveno = "";
                    $pCount = sizeof($finalLoopArray) - 1;
                    foreach ($finalLoopArray as $rdata) {

                        array_push($palletArrayCeck, $rdata['shelve_no']);
                        if ($pCount == $ii)
                            $newPalletArr = array_unique($palletArrayCeck);

                        $shelveno = implode(',', $newPalletArr);
                        if ($pieces >= $rdata['quantity']) {


                            $returnarray[$ii]['upqty'] = 0;
                            $oldPeice = $pieces;
                            $pieces = $pieces - $rdata['quantity'];

                            $returnarray[$ii]['tableid'] = $rdata['id'];
                            $returnarray[$ii]['skuid'] = $rdata['item_sku'];
                            $returnarray[$ii]['quantity'] = $rdata['quantity'];
                            $returnarray[$ii]['sku'] = $rdata['sku'];
                            $returnarray[$ii]['slip_no'] = $slip_no;
                            $returnarray[$ii]['seller_id'] = $seller_id;
                            $returnarray[$ii]['shelve_no'] = $shelveno;
                            $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
                            $returnarray[$ii]['totalqty'] = $totalqty;
                            $returnarray[$ii]['pieces'] = $pieces;
                            $returnarray[$ii]['oldPeice'] = $oldPeice;
                            $returnarray[$ii]['st_location'] = $rdata['stock_location'];
                            $returnarray[$ii]['weight'] = $rdata['weight'];

                            
                             array_push($stock_location_new,array('slip_no'=>$slip_no,'sku'=>$rdata['sku'],'stock_location'=>$rdata['stock_location'],'shelve_no'=>$shelveno));
                        } else {
                            if ($pieces > 0) {
                                $oldPeice = $pieces;
                                $returnarray[$ii]['upqty'] = $rdata['quantity'] - $pieces;
                                $returnarray[$ii]['tableid'] = $rdata['id'];
                                $returnarray[$ii]['skuid'] = $rdata['item_sku'];
                                $returnarray[$ii]['quantity'] = $rdata['quantity'];
                                $returnarray[$ii]['sku'] = $rdata['sku'];
                                $returnarray[$ii]['slip_no'] = $slip_no;
                                $returnarray[$ii]['seller_id'] = $seller_id;
                                $returnarray[$ii]['shelve_no'] = $shelveno;
                                $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
                                $returnarray[$ii]['totalqty'] = $totalqty;
                                $returnarray[$ii]['pieces'] = $pieces;
                                $returnarray[$ii]['oldPeice'] = $oldPeice;
                                $returnarray[$ii]['st_location'] = $rdata['stock_location'];
                                $returnarray[$ii]['weight'] = $rdata['weight'];
                                
                              array_push($stock_location_new,array('slip_no'=>$slip_no,'sku'=>$rdata['sku'],'stock_location'=>$rdata['stock_location'],'shelve_no'=>$shelveno));

                                $pieces = 0;
                            } else {


                                //echo $ii;
                                //$returnarray[$ii]['upqty']=$rdata['quantity']; 
                                // $returnarray[$ii]['tableid']=$rdata['id'];
                                // $returnarray[$ii]['skuid']=$rdata['item_sku'];
                                //$returnarray[$ii]['quantity']=$rdata['quantity'];
                                // $returnarray[$ii]['wh_id']=$rdata['wh_id'];
                                // $returnarray[$ii]['st_location'] = $rdata['stock_location'];
                            }
                        }

                        $ii++;
                    }
                    return array('succ' => 1, 'stArray' => $returnarray, 'StockLocation' => $stock_location_new);
                } else {
                    return 'Less Stock';
                }
            } else {
                return 'warehouse diffrent';
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
        $inventory_dataqry = "select *,'" . $sku . "' as sku_name,'" . $pieces . "'  AS peice from item_inventory  where item_sku='" . trim($sku_id) . "' and item_inventory.shelve_no!='' and item_inventory.quantity>'" . $pieces . "'  and item_inventory.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and seller_id='" . $seller_id . "' order by item_inventory.id asc";
        $qyery = $ci->db->query($inventory_dataqry);
        //$inventory_data=$this->dbh->FetchAllResults($inventory_dataqry);
        $inventory_data = $qyery->result_array();
        $inventory_data1 = $qyery->row_array();
        //echo '<pre>';
        foreach ($inventory_data as $key => $ndata) {
            if ($key == 0)
                $stockLocations = $ndata['shelve_no'];
            else
                $stockLocations .= ', ' . $ndata['shelve_no'];
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
        $inventory_data1['shelve_no'] = $stockLocations;
        // print_r($inventory_data);
        return $inventory_data1;
    }

}
if (!function_exists('UpdateStockBackorder_orderGen')) {

    function UpdateStockBackorder_orderGen($data = array(),$stocklocation=array()) {
        $ci = & get_instance();
        $ci->load->database();
        
        
      //  echo '<pre>';
      // print_r($data);
        foreach ($data as $rdata) {
            foreach ($rdata as $finaldata) {

                $updates = "update item_inventory set quantity='" . $finaldata['upqty'] . "' where id='" . $finaldata['tableid'] . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
                $ci->db->query($updates);

                if ($finaldata['slip_no'] != '') {
                    if ($finaldata['oldPeice'] >= $finaldata['quantity']) {
                        $p_qty = $finaldata['quantity'];
                        $qty = 0;
                        $qty_used = $finaldata['quantity'];
                    } else {

                        $p_qty = $finaldata['quantity'];
                        $qty = $finaldata['quantity'] - $finaldata['pieces'];
                        $qty_used = $finaldata['pieces'];
                    }
                    
                    $slip_no=$finaldata['slip_no'];
                 $sku=$finaldata['sku'];
                 $stock_location=$finaldata['st_location'];
                $shelve_no=$finaldata['shelve_no'];
                
                 $stocklocation = "insert into locationDetails (slip_no,sku,stock_location,shelve_no) values('" . $slip_no. "','" . $sku . "','" . $stock_location . "','$shelve_no')";
                 $ci->db->query($stocklocation);

          

                    //echo '<br>'. 
                    $insertdata = "insert into inventory_activity (user_id,seller_id,qty,p_qty,qty_used,item_sku,type,entrydate,awb_no,st_location,super_id) values('" . $ci->session->userdata('user_details')['user_id'] . "','" . $finaldata['seller_id'] . "','" . $qty . "','" . $p_qty . "','" . $qty_used . "','" . $finaldata['skuid'] . "','deducted','" . date('Y-m-d H:i:s') . "','" . $finaldata['slip_no'] . "','" . $finaldata['st_location'] . "','" . $ci->session->userdata('user_details')['super_id'] . "')";
                    $ci->db->query($insertdata);
                }
                if (!empty($finaldata['shelve_no'])) {
                    $updates_dimation = "update diamention_fm set deducted_shelve='" . $finaldata['shelve_no'] . "' where slip_no='" . $finaldata['slip_no'] . "' and sku='" . $finaldata['sku'] . "' and deducted_shelve='' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
                   $ci->db->query($updates_dimation);
                }
                $updates_ship = "update shipment_fm set wh_id='" . $finaldata['wh_id'] . "' where slip_no='" . $finaldata['slip_no'] . "' and wh_id='0' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
                $ci->db->query($updates_ship);
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
                $updates = "update item_inventory set quantity='" . $finaldata['upqty'] . "' where id='" . $finaldata['tableid'] . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
                $ci->db->query($updates);
                if ($finaldata['slip_no'] != '' && $finaldata['pieces'] > 0) {
                    $newqty = $finaldata['totalqty'] - $finaldata['pieces'];
                    $insertdata = "insert into inventory_activity (user_id,seller_id,qty,p_qty,qty_used,item_sku,type,entrydate,awb_no,super_id) values('" . $ci->session->userdata('user_details')['user_id'] . "','" . $finaldata['seller_id'] . "','" . $newqty . "','" . $finaldata['totalqty'] . "','" . $finaldata['pieces'] . "','" . $finaldata['skuid'] . "','deducted','" . date('Y-m-d H:i:s') . "','" . $finaldata['slip_no'] . "','" . $ci->session->userdata('user_details')['super_id'] . "')";
                    $ci->db->query($insertdata);
                }
                $updates_dimation = "update diamention_fm set deducted_shelve='" . $finaldata['shelve_no'] . "' where slip_no='" . $finaldata['slip_no'] . "' and sku='" . $finaldata['sku'] . "' and deducted_shelve='' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
                $ci->db->query($updates_dimation);
                $updates_ship = "update shipment_fm set wh_id='" . $finaldata['wh_id'] . "' where slip_no='" . $finaldata['slip_no'] . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and wh_id='0'";
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
        $sql = "SELECT COUNT(ID) as total_cnt FROM shipment_fm  where  deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and backorder='1' $cndition";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['total_cnt'];
    }

}
if (!function_exists('getcheckalreadyexitsFinance')) {

    function getcheckalreadyexitsFinance($id = null, $cat_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM finance_carges where cat_id='$cat_id' and seller_id='$id' ";
        $query = $ci->db->query($sql);
        $count = $query->num_rows();
        return $count;
    }

}
if (!function_exists('getalluserstoragerates')) {

    function getalluserstoragerates($id = null, $storage_id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM storage_rate_table where storage_id='$storage_id' and client_id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getalluserfinanceRates')) {

    function getalluserfinanceRates($id = null, $cat_id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM finance_carges where cat_id='$cat_id' and seller_id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetallpickupChagresinvoice')) {

    function GetallpickupChagresinvoice($seller_id = null, $mdate = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM($field) as totalcharge FROM orderpickupinvoice where seller_id='$seller_id' and DATE(entrydate)='$mdate' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM(orderpickupinvoice.pickupcharge) as totalcharge FROM orderpickupinvoice join orderoutboundinvoice on orderoutboundinvoice.seller_id= orderpickupinvoice.seller_id where orderpickupinvoice.seller_id='$seller_id' and DATE(orderpickupinvoice.entrydate)='$mdate' and orderoutboundinvoice.slip_no='$slip_no' and orderpickupinvoice.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); 
        $result = $query->row_array();
        return $result['totalcharge'];
    }

}

if (!function_exists('GetSkuStockLocation')) {
     function GetSkuStockLocation($slip_no = null, $sku = null) {
          $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT shelve_no,stock_location FROM locationDetails where  slip_no='$slip_no' and sku='$sku' and deleted='N'";
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); 
        $result = $query->result_array();
        return $result; 
     }
    
}

if (!function_exists('GetallinboundChagres')) {

    function GetallinboundChagres($seller_id = null, $mdate = null, $slip_no = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT SUM(orderpickupinvoice.inbound_charge) as totalcharge FROM orderpickupinvoice join orderoutboundinvoice on orderoutboundinvoice.seller_id= orderpickupinvoice.seller_id where orderpickupinvoice.seller_id='$seller_id' and DATE(orderpickupinvoice.entrydate)='$mdate' and orderoutboundinvoice.slip_no='$slip_no' and orderpickupinvoice.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM(orderpickupinvoice.inventory_charge) as totalcharge FROM orderpickupinvoice join orderoutboundinvoice on orderoutboundinvoice.seller_id= orderpickupinvoice.seller_id where orderpickupinvoice.seller_id='$seller_id' and DATE(orderpickupinvoice.entrydate)='$mdate' and orderoutboundinvoice.slip_no='$slip_no' and orderpickupinvoice.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT sum(storagesinvoices.storagerate) as totalcharge FROM `storagesinvoices` join diamention_fm on storagesinvoices.sku=diamention_fm.sku where diamention_fm.slip_no='$slip_no' and storagesinvoices.seller_id='$seller_id' and storagesinvoices.entrydate = '$mdate' and storagesinvoices.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM($field) as totalcharge FROM orderinvoicepicking where seller_id='$seller_id' and DATE(entrydate)='$mdate' and slip_no='$slip_no' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM(outcharge) as totalcharge FROM orderoutboundinvoice where seller_id='$seller_id' and DATE(entrydate)='$mdate'  and slip_no='$slip_no' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
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
        $sql = "SELECT SUM($field) as totalcharge FROM orderoutboundinvoice where seller_id='$seller_id' and DATE(entrydate)='$mdate' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM($field) as totalcharge FROM storagesinvoices where seller_id='$seller_id' and DATE(entrydate)='$mdate' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM($field) as totalcharge FROM orderinvoicepicking where seller_id='$seller_id' and DATE(entrydate)='$mdate' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM($field) as totalcharge FROM clientportalinvocie where seller_id='$seller_id' and DATE(entrydate)='$mdate' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT SUM($field) as totalcharge FROM skubarcode_print where seller_id='$seller_id' and DATE(entrydate)='$mdate' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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
        $sql = "SELECT id FROM items_m where sku='$sku' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['id'];
    }

}

if (!function_exists('GetskuDetailsForPrint')) {

    function GetskuDetailsForPrint($sku = array()) {
        $ci = & get_instance();
        $ci->load->database();
        //  $sql = "SELECT id FROM items_m where sku='$sku' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $ci->db->select('*');
        $ci->db->from('items_m');
        $ci->db->where_in('sku', $sku);
        $ci->db->where('super_id', $ci->session->userdata('user_details')['super_id']);
        $query = $ci->db->get();
        // echo $ci->db->last_query();die;
        $result = $query->result_array();

        return $result;
    }

}

if (!function_exists('getshelveNobyid')) {

    function getshelveNobyid($sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT shelve_no FROM item_inventory where item_sku='$sku' and quantity>0 and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['shelve_no'];
    }

}


if (!function_exists('GetallremoveskuQty')) {

    function GetallremoveskuQty($sku = null, $seller_id = null, $uniqueid = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM pickup_request where sku='$sku' and code in('MSI','DI') and seller_id='$seller_id' and uniqueid='$uniqueid' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return count($result);
    }

}

if (!function_exists('getalldataitemtables')) {

    function getalldataitemtables($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM items_m where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('getalldataitemtablesBySku')) {

    function getalldataitemtablesBySku($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM items_m where sku='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}





if (!function_exists('GetCourierCompanyDrop')) {

    function GetCourierCompanyDrop($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,company,cc_id FROM courier_company where status='Y' and (api_url!='' || api_url_t!='') and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' order by company";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('GetcuriertableData')) {

    function GetcuriertableData($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM courier_company where status='Y' and deleted='N' and id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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

        if ($usertype == 'user') {

            $sql = "SELECT name as username FROM customer where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        } else {
            $sql = "SELECT username FROM user where id='$id'";
        }
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
        $sql = "SELECT COUNT(ID) as total_cnt FROM shipment_fm  where delivered='" . $id . "' and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and backorder='0' $cndition";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['total_cnt'];
    }

}
if (!function_exists('ManifeststatusCount')) {

    function ManifeststatusCount($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        if ($ci->session->userdata('user_details')['user_type'] != 1) {
            $wh_id = $ci->session->userdata('user_details')['wh_id'];
            $cndition = " and wh_id='$wh_id'";
        }
        $sql = "SELECT id FROM delivery_manifest  where  deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'  $cndition group by m_id";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return count($result);
    }

}
if (!function_exists('getallsratusshipmentid')) {

    function getallsratusshipmentid($shipid = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM shipment_fm  where id='$shipid' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}


if (!function_exists('BookingIdCheck_cust')) {

    function BookingIdCheck_cust($booking_id = null, $cust_id = null, $id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $site_query = "select slip_no from shipment_fm where booking_id='" . trim($booking_id) . "' and cust_id='" . $cust_id . "' and deleted='N' and id!='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'  ";
        $query = $ci->db->query($site_query);
        $result = $query->row_array();
        return $result['slip_no'];
    }

}
if (!function_exists('getAllDestination')) {

    function getAllDestination($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,city FROM country where deleted='N' and city!='' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}
if (!function_exists('getdestinationfieldshow')) {

    function getdestinationfieldshow($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM country where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        //echo $sql;
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}

if (!function_exists('GetErrorShowShipment')) {

    function GetErrorShowShipment($slip_no = null, $booking_id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        //and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'
        $sql = "SELECT $field FROM shipment_pulling_errors where slip_no='$slip_no' and booking_id='$booking_id' ";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

}
if (!function_exists('getdestinationfieldshow_name')) {

    function getdestinationfieldshow_name($id = null, $field = null, $matchfield = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM country where   $matchfield='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
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

if (!function_exists('GetStatusFmTableCodes')) {

    function GetStatusFmTableCodes($slip_no = null, $code = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT entry_date FROM status_fm where slip_no='$slip_no' and code='$code' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['entry_date'];
    }

}



if (!function_exists('checkPrivilageExitsForCustomer')) {

    function checkPrivilageExitsForCustomer($customer_id = null, $privilage_id = null) {

        $ci = & get_instance();
        $ci->load->database();
        $sql = "select privilage_array from set_user_privilege_fm where customer_id='" . $customer_id . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
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
if (!function_exists('packedcount_batch')) {

    function packedcount_batch($id = null) {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT count(id) as  packedcount FROM pickuplist_tbl where pickupId='$id'";
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

if (!function_exists('Get_cust_uid')) {

    function Get_cust_uid($cust_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "select uniqueid from customer where id='" . $cust_id . "'";
        $query = $ci->db->query($sql);
        //echo $ci->db->last_query();exit;
        $result = $query->row_array();
        return $result['uniqueid'];
    }

}


if (!function_exists('unpackedcount_batch')) {

    function unpackedcount_batch($id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT count(id) as  packedcount FROM pickuplist_tbl where picked_status ='N' and  pickupId='$id'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['packedcount'];
    }

}

if (!function_exists('menuIdExitsInPrivilageArray')) {

    function menuIdExitsInPrivilageArray($menu_id) {



        $ci = & get_instance();
        $ci->load->database();
        $sql = "select privilage_array from set_user_privilege_fm where customer_id='" . $ci->session->userdata('user_details')['user_id'] . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        $privielage_array = explode(',', $result['privilage_array']);
//        if ($ci->session->userdata('user_details')['user_type'] == 1) {
//            $return_value = "Y";
//        } else {
        if (in_array($menu_id, $privielage_array)) {
            $return_value = "Y";
        } else {
            $return_value = "N";
        }
        // }

        return $return_value;
    }

}

if (!function_exists('getallprivilegedata_submenu')) {

    function getallprivilegedata_submenu($id = null) {
        $ci = & get_instance();
        $ci->load->database();
//echo "select * from privilege_details_fm where pid='$id' and id not in(106,107) and deleted='N'";
        $query = $ci->db->query("select * from privilege_details_fm where pid='$id' and id not in(106,107) and deleted='N'");
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

}

if (!function_exists('GetSuperAdminAccessIds')) {

    function GetSuperAdminAccessIds() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT privilage_array FROM set_user_privilege_fm where customer_id='" . $ci->session->userdata('user_details')['super_id'] . "'  and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
        $query = $ci->db->query($sql);
        $row = $query->row_array();
        return $row['privilage_array'];
    }

}
if (!function_exists('getIdfromCityName')) {

    function getIdfromCityName($city) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id FROM country where deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and city Like '" . $city . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['id'];
    }

}

if (!function_exists('generate_hash')) {

    function generate_hash($salt, $password, $algo = 'sha256') {
        return hash($algo, $salt . $password);
    }

}

if (!function_exists('barcodeRuntime')) {

    function barcodeRuntime($bar_code_id) {
        // Get pararameters that are passed in through $_GET or set to the default value
        $text = (isset($_GET["text"]) ? $_GET["text"] : $bar_code_id);
        $size = (isset($_GET["size"]) ? $_GET["size"] : "50");
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

function checknewbarrcode($filepath = "", $text = "0", $size = "20", $orientation = "horizontal", $code_type = "code128", $print = false, $SizeFactor = 1) {

    $code_string = "";
    // Translate the $text into barcode the correct $code_type
    if (in_array(strtolower($code_type), array("code128", "code128b"))) {
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
    } elseif (strtolower($code_type) == "code128a") {
        $chksum = 103;
        $text = strtoupper($text); // Code 128A doesn't support lower case
        // Must not change order of array elements as the checksum depends on the array's key to validate final code
        $code_array = array(" " => "212222", "!" => "222122", "\"" => "222221", "#" => "121223", "$" => "121322", "%" => "131222", "&" => "122213", "'" => "122312", "(" => "132212", ")" => "221213", "*" => "221312", "+" => "231212", "," => "112232", "-" => "122132", "." => "122231", "/" => "113222", "0" => "123122", "1" => "123221", "2" => "223211", "3" => "221132", "4" => "221231", "5" => "213212", "6" => "223112", "7" => "312131", "8" => "311222", "9" => "321122", ":" => "321221", ";" => "312212", "<" => "322112", "=" => "322211", ">" => "212123", "?" => "212321", "@" => "232121", "A" => "111323", "B" => "131123", "C" => "131321", "D" => "112313", "E" => "132113", "F" => "132311", "G" => "211313", "H" => "231113", "I" => "231311", "J" => "112133", "K" => "112331", "L" => "132131", "M" => "113123", "N" => "113321", "O" => "133121", "P" => "313121", "Q" => "211331", "R" => "231131", "S" => "213113", "T" => "213311", "U" => "213131", "V" => "311123", "W" => "311321", "X" => "331121", "Y" => "312113", "Z" => "312311", "[" => "332111", "\\" => "314111", "]" => "221411", "^" => "431111", "_" => "111224", "NUL" => "111422", "SOH" => "121124", "STX" => "121421", "ETX" => "141122", "EOT" => "141221", "ENQ" => "112214", "ACK" => "112412", "BEL" => "122114", "BS" => "122411", "HT" => "142112", "LF" => "142211", "VT" => "241211", "FF" => "221114", "CR" => "413111", "SO" => "241112", "SI" => "134111", "DLE" => "111242", "DC1" => "121142", "DC2" => "121241", "DC3" => "114212", "DC4" => "124112", "NAK" => "124211", "SYN" => "411212", "ETB" => "421112", "CAN" => "421211", "EM" => "212141", "SUB" => "214121", "ESC" => "412121", "FS" => "111143", "GS" => "111341", "RS" => "131141", "US" => "114113", "FNC 3" => "114311", "FNC 2" => "411113", "SHIFT" => "411311", "CODE C" => "113141", "CODE B" => "114131", "FNC 4" => "311141", "FNC 1" => "411131", "Start A" => "211412", "Start B" => "211214", "Start C" => "211232", "Stop" => "2331112");
        $code_keys = array_keys($code_array);
        $code_values = array_flip($code_keys);
        for ($X = 1; $X <= strlen($text); $X++) {
            $activeKey = substr($text, ($X - 1), 1);
            $code_string .= $code_array[$activeKey];
            $chksum = ($chksum + ($code_values[$activeKey] * $X));
        }
        $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

        $code_string = "211412" . $code_string . "2331112";
    } elseif (strtolower($code_type) == "code39") {
        $code_array = array("0" => "111221211", "1" => "211211112", "2" => "112211112", "3" => "212211111", "4" => "111221112", "5" => "211221111", "6" => "112221111", "7" => "111211212", "8" => "211211211", "9" => "112211211", "A" => "211112112", "B" => "112112112", "C" => "212112111", "D" => "111122112", "E" => "211122111", "F" => "112122111", "G" => "111112212", "H" => "211112211", "I" => "112112211", "J" => "111122211", "K" => "211111122", "L" => "112111122", "M" => "212111121", "N" => "111121122", "O" => "211121121", "P" => "112121121", "Q" => "111111222", "R" => "211111221", "S" => "112111221", "T" => "111121221", "U" => "221111112", "V" => "122111112", "W" => "222111111", "X" => "121121112", "Y" => "221121111", "Z" => "122121111", "-" => "121111212", "." => "221111211", " " => "122111211", "$" => "121212111", "/" => "121211121", "+" => "121112121", "%" => "111212121", "*" => "121121211");

        // Convert to uppercase
        $upper_text = strtoupper($text);

        for ($X = 1; $X <= strlen($upper_text); $X++) {
            $code_string .= $code_array[substr($upper_text, ($X - 1), 1)] . "1";
        }

        $code_string = "1211212111" . $code_string . "121121211";
    } elseif (strtolower($code_type) == "code25") {
        $code_array1 = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        $code_array2 = array("3-1-1-1-3", "1-3-1-1-3", "3-3-1-1-1", "1-1-3-1-3", "3-1-3-1-1", "1-3-3-1-1", "1-1-1-3-3", "3-1-1-3-1", "1-3-1-3-1", "1-1-3-3-1");

        for ($X = 1; $X <= strlen($text); $X++) {
            for ($Y = 0; $Y < count($code_array1); $Y++) {
                if (substr($text, ($X - 1), 1) == $code_array1[$Y])
                    $temp[$X] = $code_array2[$Y];
            }
        }

        for ($X = 1; $X <= strlen($text); $X += 2) {
            if (isset($temp[$X]) && isset($temp[($X + 1)])) {
                $temp1 = explode("-", $temp[$X]);
                $temp2 = explode("-", $temp[($X + 1)]);
                for ($Y = 0; $Y < count($temp1); $Y++)
                    $code_string .= $temp1[$Y] . $temp2[$Y];
            }
        }

        $code_string = "1111" . $code_string . "311";
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
    $code_length = 20;
    if ($print) {
        $text_height = 30;
    } else {
        $text_height = 0;
    }

    for ($i = 1; $i <= strlen($code_string); $i++) {
        $code_length = $code_length + (integer) (substr($code_string, ($i - 1), 1));
    }

    if (strtolower($orientation) == "horizontal") {
        $img_width = $code_length * $SizeFactor;
        $img_height = $size;
    } else {
        $img_width = $size;
        $img_height = $code_length * $SizeFactor;
    }

    $image = imagecreate($img_width, $img_height + $text_height);
    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);

    imagefill($image, 0, 0, $white);
    if ($print) {
        imagestring($image, 5, 31, $img_height, $text, $black);
    }

    $location = 10;
    for ($position = 1; $position <= strlen($code_string); $position++) {
        $cur_size = $location + ( substr($code_string, ($position - 1), 1) );
        if (strtolower($orientation) == "horizontal")
            imagefilledrectangle($image, $location * $SizeFactor, 0, $cur_size * $SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black));
        else
            imagefilledrectangle($image, 0, $location * $SizeFactor, $img_width, $cur_size * $SizeFactor, ($position % 2 == 0 ? $white : $black));
        $location = $cur_size;
    }

    // Draw barcode to the screen or save in a file
    ob_start();

    imagejpeg($image);
    imagedestroy($image);

    $data = ob_get_contents();

    ob_end_clean();
    $image = "data:image/jpeg;base64," . base64_encode($data);
    return $image;
}

function SallacURL($athentication, $page) {
    $headers = array("Authorization:Bearer $athentication");
    $ch = curl_init("https://api.salla.dev/admin/v2/orders?expanded=1&page=$page"); //the curl for first request pages
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);

    curl_close($ch);
    if (curl_errno($ch)) { //Checking curl error
        echo "Error in curl" . curl_error($ch);
        exit;
    } else { //if there is no error we are proceed
        $result = json_decode($result, true);
        if ($page > 0) {
            return $result;
        } else {
            return $total_pages = $result['pagination']['totalPages'];
        }
    }
}

function ZidcURL($manager_token, $user_agent, $cURL, $page,$athentication) {
    //$athentication = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2NSIsImp0aSI6IjNmMzI5MTExM2Y3Y2U4NjkxNDcwMDgwMDJkMTY4NTY4YWZkNzU5OWEwZmFlMWRkYTk4ODgzMDUxMGU3MDQ0YTZhYTZjZjE0ODkwYjI0OGY1IiwiaWF0IjoxNTk5OTg5ODkwLCJuYmYiOjE1OTk5ODk4OTAsImV4cCI6MTYzMTUyNTg5MCwic3ViIjoiMzMiLCJzY29wZXMiOlsidGhpcmQtcGFydGllcy1hcGlzIl19.VFozu9O0PEUOCzIxbFdZSVQ-mbduyEvl7JqIHpsHGKMzKmwcd8M-CFw9WyKQ9-I9yxYnFLNgzfsw9JuISjMLqzj6ePyKJw88BlTaB74bSXpD5n6FTAWafhTGETAOUNh7Eswxri9fAb5U8LCIpHLXTy0dWWUEPBb8IubxSULyMh49r1kk2p0ZOfBvnHnDQEdNXzIQe4A53Cyhuh6y6IHehY8nE6rxuw5WIItLmgdZQr-2hvzbcdkyzzD8Su0TwaBzT4E5T5LQNwr7HawfMJWayk_k4kXvRSGu-riP1CpbN0dNeRXL2T6sD79qGxi50xCV75efOlUhk-lqBVOlzmjt-JAFVogDuiMvQSFfXi4tazkzZRGC_SVPrz1pPsIW8B_Rgmpp1hlVUOhS5ywph-dlqsCbyWQa_2mkhleFFs9zwTP_ZQkM3-wSnup3hed7iXQCPVttX244SkItWqA2HBElPRo-a82H03gzBt2lCDGUrxCl_uG1go2KxIopW0TbtpnTs_Ajp6QaTuHgouFW-9GcmyoUo75kQ5RMtzQ6svEEXnV87yEUzsD5DuELkDdENpB_vZVwU9VqAxlgZaSy-LLmteBxVpCmhmv14qCxNrZ95zqZ1bZ02r21CnLJtVDCmpHL-vhq4QCvRQQTAiO-cZ8eF3hYhv5vkVjgY3Cr6c-dO3w';
    //echo $cURL;exit;
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $cURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $athentication,
            'X-MANAGER-TOKEN: ' . $manager_token,
            'User-Agent: Fastcoo/1.00.00 (web)',
            'Accept-Language: ar',
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $result = json_decode($response, true);
   
    if ($page == 0) {
        return $result['total_order_count'];
    } else {
        return $result;
    }
}

function Zid_Order_Details($ZO_id, $manager_token, $user_Agent) {
    $athentication = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI2NSIsImp0aSI6IjNmMzI5MTExM2Y3Y2U4NjkxNDcwMDgwMDJkMTY4NTY4YWZkNzU5OWEwZmFlMWRkYTk4ODgzMDUxMGU3MDQ0YTZhYTZjZjE0ODkwYjI0OGY1IiwiaWF0IjoxNTk5OTg5ODkwLCJuYmYiOjE1OTk5ODk4OTAsImV4cCI6MTYzMTUyNTg5MCwic3ViIjoiMzMiLCJzY29wZXMiOlsidGhpcmQtcGFydGllcy1hcGlzIl19.VFozu9O0PEUOCzIxbFdZSVQ-mbduyEvl7JqIHpsHGKMzKmwcd8M-CFw9WyKQ9-I9yxYnFLNgzfsw9JuISjMLqzj6ePyKJw88BlTaB74bSXpD5n6FTAWafhTGETAOUNh7Eswxri9fAb5U8LCIpHLXTy0dWWUEPBb8IubxSULyMh49r1kk2p0ZOfBvnHnDQEdNXzIQe4A53Cyhuh6y6IHehY8nE6rxuw5WIItLmgdZQr-2hvzbcdkyzzD8Su0TwaBzT4E5T5LQNwr7HawfMJWayk_k4kXvRSGu-riP1CpbN0dNeRXL2T6sD79qGxi50xCV75efOlUhk-lqBVOlzmjt-JAFVogDuiMvQSFfXi4tazkzZRGC_SVPrz1pPsIW8B_Rgmpp1hlVUOhS5ywph-dlqsCbyWQa_2mkhleFFs9zwTP_ZQkM3-wSnup3hed7iXQCPVttX244SkItWqA2HBElPRo-a82H03gzBt2lCDGUrxCl_uG1go2KxIopW0TbtpnTs_Ajp6QaTuHgouFW-9GcmyoUo75kQ5RMtzQ6svEEXnV87yEUzsD5DuELkDdENpB_vZVwU9VqAxlgZaSy-LLmteBxVpCmhmv14qCxNrZ95zqZ1bZ02r21CnLJtVDCmpHL-vhq4QCvRQQTAiO-cZ8eF3hYhv5vkVjgY3Cr6c-dO3w';
    $cURL_id = "https://api.zid.sa/v1/managers/store/orders/" . $ZO_id . "/view";
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $cURL_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $athentication,
            'X-MANAGER-TOKEN: ' . $manager_token,
            'User-Agent: ' . 'Fastcoo/1.00.00 (web)',
            'Accept-Language: ar'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;exit;
    $result1 = json_decode($response, true);
    return $result1;
}
function makeTrackUrl($id, $awb,$slip_no)
{
   
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT company_url FROM courier_company where id='$id' and deleted='N' and company_url!='' limit 1";
        $query = $ci->db->query($sql);
        if($query->num_rows()>0)
        {
            $result = $query->row_array();

        return $result['company_url'].$awb;
        }
    
}

function exist_booking_id($booking_id, $cust_id) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "select id from shipment_fm where booking_id='" . $booking_id . "' and cust_id='" . $cust_id . "' and deleted='N'";
    $query = $ci->db->query($sql);
    $countdata = $query->num_rows();
    $row = $query->row_array();
    if ($countdata > 0)
        return $row['id'];
    else
        return false;
}

function getCityCode($city_id = null) {

    $ci = & get_instance();
    $ci->load->database();
    $sql = "select city_code from country where (id='" . $city_id . "' or city='" . $city_id . "') and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
    $query = $ci->db->query($sql);
    $statusData = $query->row_array();
    return $status = $statusData['city_code'];
}

function barcodeRuntime_new($bar_code_id) {
    // Get pararameters that are passed in through $_GET or set to the default value
    $text = (isset($_GET["text"]) ? $_GET["text"] : $bar_code_id);
    $size = (isset($_GET["size"]) ? $_GET["size"] : "40");
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
    $code_length = 20;
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

    $image = "<img src='data:image/jpeg;base64," . base64_encode($data) . "'>";
    return $image;
    // Draw barcode to the screen
    //imagejpeg($image,$path,100);	
    //header ('Content-type: image/png');
    //imagepng($image);
    //imagedestroy($image);
}

function create_sign($param, $secKey, $customerId, $formate, $method, $signMethod) {

    $jsonDataArray = json_encode($param);
    // print_r($jsonDataArray);exit;
    $var = "customerId" . $customerId . "format" . $formate . "method" . $method . "signMethod" . $signMethod . "";
    $all_var_concatinated = $secKey . $var . $jsonDataArray . $secKey;
    $sign = strtoupper(md5($all_var_concatinated));
    return $sign;
}

function MakdoomArrival_Auth_cURL($counrierArr) {
    $ch = curl_init();
    $apiurl = $counrierArr['api_url']."/customer/authenticate";
    $postdata = array("username" => $counrierArr['user_name'], "password" => $counrierArr['password'], "remember_me" => true);
    $postdata = json_encode($postdata);
    curl_setopt($ch, CURLOPT_URL,$apiurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Accept: application/json"
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function send_data_to_makdoom_curl($dataJson, $Auth_token, $API_URL) {
     $ch1 = curl_init();
     curl_setopt($ch1, CURLOPT_URL, $API_URL);
     curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch1, CURLOPT_HEADER, FALSE);
     curl_setopt($ch1, CURLOPT_POST, TRUE);
     curl_setopt($ch1, CURLOPT_POSTFIELDS, $dataJson);
     curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
         "Content-Type: application/json",
         "Accept: application/json",
         "Authorization: Bearer " . $Auth_token
     ));
     $response = curl_exec($ch1);
     curl_close($ch1);
     return $response;
 }
 
 function makdoom_label_curl($client_awb, $Auth_token, $counrierArr) {
     $url = $counrierArr['api_url']."customer/orders/airwaybill_mini?order_numbers=".$client_awb;
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch, CURLOPT_HEADER, FALSE);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
         "Content-Type: application/json",
         "Accept: application/json",
         "Authorization: Bearer " . $Auth_token
     ));
     $response = curl_exec($ch);
     curl_close($ch);
     return $response;
 }

if (!function_exists('GetdeliveryreportArr')) {

    function GetdeliveryreportArr($id = null, $delivered = null,$filterArr) {
        $ci = & get_instance();
        $ci->load->database();

        // $totalno=0;
        if(!empty($filterArr))
        {
            $from_date=$filterArr['from_date'];
            $to_date=$filterArr['to_date'];
            if(!empty($from_date) && !empty($to_date))
            {
             $condition2 .= " and DATE(shipment_fm.entrydate) BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
            }
        }
        if ($delivered == 'running')
            $condition = " and  delivered in(1,2,3,4,5)";
        else
            $condition = " and delivered='$delivered'";
        $getCustName = "SELECT count(id) as totalship FROM shipment_fm  WHERE  deleted='N' and status='Y' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'  and frwd_company_id='$id' $condition $condition2  group by frwd_company_id";
        $query = $ci->db->query($getCustName);
        $cusNameRun = $query->row_array();
        $totalno = $cusNameRun['totalship'];
        if (empty($totalno))
            $totalno = 0;
        return $totalno;
    }

}

function saee_label_curl($client_awb, $Auth_token, $API_URL) {
    $url = $API_URL."/printsticker/pdf/" . $client_awb;
    //$url ="https://corporate.saeex.com/deliveryrequest/printsticker/pdf/OS02415896KS";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json",
        "Accept: application/json",
        "Authorization: Bearer " . $Auth_token
    ));

    $label_response = curl_exec($ch);
    curl_close($ch);
    return $label_response;
}

function ajeek_label_curl($Auth_token, $client_awb, $vendor_id) {
    $label_url = "https://ajeek.net/ajeekWsTest/api/order/printAirwayBill/" . $Auth_token . "/" . $client_awb . "/" . $vendor_id;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $label_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $label_response = curl_exec($ch);
    print_r($label_response);
    curl_close($ch);

    return $label_response;
}

if (!function_exists('Alertcountshowdata')) {

    function Alertcountshowdata($type = "one") {
        $ci = & get_instance();
        $ci->load->database();
        if ($type == 'two')
            $sql = "SELECT COUNT(ID) as total_cnt FROM `item_inventory` WHERE super_id='" . $ci->session->userdata('user_details')['super_id'] . "' GROUP BY `item_sku` HAVING SUM(quantity) < (SELECT less_qty from items_m where id=item_sku and items_m.super_id='" . $ci->session->userdata('user_details')['super_id'] . "') ";
        else
            $sql = "SELECT COUNT(ID) as total_cnt FROM  item_inventory WHERE  expity_date<(CURDATE() + INTERVAL (SELECT alert_day from items_m where id=item_sku) DAY) and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and expity_date>(CURDATE())";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        if ($type == 'two')
            return $query->num_rows();
        else
            return $result['total_cnt'];
    }
}


if (!function_exists('GetCourierCompanyStausActive')) {

    function GetCourierCompanyStausActive($name = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT status FROM courier_company where company='$name' and deleted='N' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result['status'];
    }

}

if (!function_exists('spPrintDetails')) {

    function spPrintDetails($slip_no=null,$cc_id=null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT log  FROM `frwd_shipment_log` WHERE `slip_no`='".$slip_no."' and cc_id='".$cc_id."'  and status='Success' limit 1 "; //and status='Success'
        $query = $ci->db->query($sql);
        $countdata = $query->num_rows();
        $row = $query->row_array();
        if ($countdata > 0)
            return $row['log'];
        else
            return 0;
    }

}



if(!function_exists('Salla_StatusUpdate')){
        function Salla_StatusUpdate($shippers_ref_no, $status,$note,$tracking_number,$tracking_url) {


            $data = array(
                'auth-token' => '$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72y',  
                'status' => $status,
                'note' => $note,
                'tracking_url' => $tracking_url,
                'tracking_number' => $tracking_number
            );


            $url = 'https://s.salla.sa/webhook/diggipacks/order/'.$shippers_ref_no;

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
           $response = json_decode($response);
            if($response->status ==1){
                echo $response->message;
            }
           else{
              // print_r($response);
           }
        }
    }
    
    if (!function_exists('sendQuantityupdatetosalla')) {

    function sendQuantityupdatetosalla($seller_id = null, $sku = null, $customer_id = null) {
        $ci = & get_instance();
        $ci->load->database();
        $customer_id = GetuniqIDbySellerId($seller_id);
        $quantity = Getquantitybyskuname($seller_id, $sku);
        $auth_token = '$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72';
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

function digiQtyUpdate($sku,$qty) {
    $apurl = "https://justwork.in//wp-json/wc/v3/products"; 
    $url =  $apurl.'?sku=' . $sku;
    
    $username = "ck_abdfcda3fd2a45ac0e16aee1d48d0acc85024176";
    $password = "cs_39be11db88120452cfcf769150795152e354a12c";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic ' . base64_encode("$username:$password"),
            "cache-control: no-cache",
        ),
    ));

    $response = curl_exec($curl);
    $response = json_decode($response);
 
    curl_close($curl);

    if ($response) {
        //update qty
        $update_url = $apurl . '/' . $response[0]->id;

        $data = array('stock_quantity' => $qty,'manage_stock'=>1);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $update_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . base64_encode("$username:$password"),
                "cache-control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $result = json_decode($response);
        curl_close($curl);
    }
}