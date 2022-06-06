<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShipmentR extends MY_Controller {

    function __construct() {
        parent::__construct();

        // if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
        //     // Prevent infinite loop by checking that this isn't the login controller               
        //     if ($this->router->class != 'User') {
        //         redirect(base_url());
        //     }
        // }
        $this->load->model('Shipment_model');
        $this->load->model('ShipmentR_model');
        $this->load->model('Seller_model');
        $this->load->model('Status_model');
        $this->load->helper('utility');
        $this->load->model('User_model');
    }

    public function reverse_view() {
        if (menuIdExitsInPrivilageArray(160) == 'N') {
          //  redirect(base_url() . 'notfound');
          //  die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('ShipmentM/reverse_order', $bulk);
    }

    public function reverseCreate_view() {
        if (menuIdExitsInPrivilageArray(161) == 'N') {
           // redirect(base_url() . 'notfound');
           // die;
        }

        $this->load->view('ShipmentM/reverse_create');
    }

    public function filter() {

        $_POST = json_decode(file_get_contents('php://input'), true);


        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'REF')
            $refsno = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'MOBL')
            $mobileno = $_POST['s_type_val'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $wh_id = $_POST['wh_id'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        $booking_id = $_POST['booking_id'];
        if (!empty($_POST['limit']))
            $limit = $_POST['limit'] + 1;
        else
            $limit = 0;


        $shipments = $this->ShipmentR_model->filter($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $limit, $refsno, $mobileno, $wh_id, $_POST);


        //$shiparrayexcel = $shipmentsexcel['result'];
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;



        $tolalShip = $shipments['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        $pageShortArr = $this->pageshortDropData($tolalShip);
        $SiteConfingData = Getsite_configData();
        // print_r($SiteConfingData);
        //echo $SiteConfingData['e_city'];
        $e_city = explode(',', $SiteConfingData['e_city']);

        $picker = $this->User_model->userDropval(4);
        foreach ($shipments['result'] as $rdata) {

            $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);
            if ($rdata['order_type'] == '') {
                $itemID = getallitemskubyid($rdata['sku']);
                $itemtypes = getalldataitemtables($itemID, 'type');
                $shiparray[$ii]['order_type'] = $itemtypes;

                //$shiparray[$ii]['order_type']="";
            } else
                $shiparray[$ii]['order_type'] = $rdata['order_type'];

            if (in_array($rdata['destination'], $e_city) || $rdata['frwd_company_id'] == '') {
                $shiparray[$ii]['generateButton'] = 'Y';
            } else {
                $shiparray[$ii]['generateButton'] = 'N';
            }
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            $shiparray[$ii]['expire_details'] = $expire_data;
            $shiparray[$ii]['skuData'] = $this->Shipment_model->GetpicklistGenrateSkuDetails($rdata['slip_no']);
            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
            $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');

            $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);
            if ($rdata['frwd_company_awb'] != '') {
                $track_url = GetCourCompanynameId($rdata['frwd_company_id'], 'company_url');
                if (!empty($track_url)) {
                    $shiparray[$ii]['frwd_link'] = $track_url . $rdata['frwd_company_awb'];
                } else {
                    $shiparray[$ii]['frwd_link'] = '';
                }
            } else {
                $shiparray[$ii]['frwd_link'] = "";
            }

            //$shiparray='rith';
            $ii++;
        }




        //echo '<pre>';
        //print_r($shiparray);
        //echo json_encode($shiparray);
        // die;
        //$dataArray['excelresult'] = $shiparrayexcel;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['picker'] = $picker;


        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function pageshortDropData($maxval = 0) {
        //echo $maxval; die;

        $min = 100;
        $max = $maxval; // Just chenge this val;
        $s_val = array();
        if ($max <= 100) {
            $sval = array('100');
        } elseif ($max > 100 && $max <= 200) {
            $sval = array('0' => '100', '100' => 200);
        } elseif ($max > 200 && $max <= 500) {
            $sval = array('0' => 100, '100' => '200', '200' => '500');
        } elseif ($max > 500 && $max <= 1000) {
            $sval = array('0' => 100, '100' => '200', '200' => '500', '500' => 1000);
        } elseif ($max > 1000) {
            $repeat = round(($max - 1000) / 500);

            $l = 1000;
            $sval = array('0' => 100, '100' => '200', '200' => '500', '500' => 1000);
            for ($i = 1; $i <= $repeat; $i++) {
                $l = $l + 500;
                $sval[$l - 500] = $l;
            }
        }
        return $sval;
    }

    public function getcreateReverseOrder() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $slip_nos = $postData['slip_no'];
        $comment = isset($postData['comment']) ? $postData['comment'] : "";
        if (!empty($slip_nos)) {
            $SlipNos = preg_replace('/\s+/', ',', $slip_nos);
            $slipData = explode(",", $SlipNos);
            $ready_slip_array = array_unique($slipData);
            $invalid_slipNO = array();
            $succssArray = array();
            $new_dia=array();
            $diamentionArr_mnew=array();
           foreach ($ready_slip_array as $key => $val2) {

                $val = $this->ShipmentR_model->GetshipmentDataQuery($val2);
                if (!empty($val)) {

                    $neworder_number = Generate_awb_number_fm();
                    $shipmentOrderArr = $val;

                    //=========updating old orders==============//
                    $updatedOLD[$key]['reverse_awb'] = $neworder_number;
                    $updatedOLD[$key]['slip_no'] = $val['slip_no'];
                    $updatedOLD[$key]['reverse_type'] = 0;

                    //=======adding new order=================//
                    $data[$key]['reverse_awb'] = '';
                    $data[$key]['wh_id'] = $val['wh_id'];
                    $data[$key]['booking_id'] = $val['slip_no'];
                    $data[$key]['user_id'] = $val['user_id'];
                    $data[$key]['cust_id'] = $val['cust_id'];
                    $data[$key]['shippers_ac_no'] = $val['shippers_ac_no'];
                    $data[$key]['shippers_ref_no'] = $val['booking_id'];
                    $data[$key]['status_describtion'] = isset($shipmentOrderArr['status_describtion']) ? $shipmentOrderArr['status_describtion'] : "";
                    //$data[$key]['status_comment'] = isset($shipmentOrderArr['status_comment']) ? $shipmentOrderArr['status_comment'] : "";
                    $data[$key]['reverse_type'] = 1;
                    $data[$key]['slip_no'] = $neworder_number;
                    $data[$key]['entrydate'] = date('Y-m-d H:i:s');
                    $data[$key]['nrd'] = $val['nrd'];
                    $data[$key]['origin'] = $val['destination'];
                    $data[$key]['destination'] = $val['origin'];
                    $data[$key]['pieces'] = $val['pieces'];
                    $data[$key]['weight'] = $val['weight'];
                    $data[$key]['volumetric_weight'] = $val['volumetric_weight'];
                    $data[$key]['service_charge'] = $val['service_charge'];
                    $data[$key]['mode'] = $val['mode'];
                    $data[$key]['total_cod_amt'] = $val['total_cod_amt'];
                    $data[$key]['service_id'] = $val['service_id'];
                    $data[$key]['sku'] = $val['sku'];
                    $data[$key]['fulfillment'] = $val['fulfillment'];
                    $data[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                    $data[$key]['address2'] = isset($val['address2']) ? $val['address2'] : "";
                    $data[$key]['area_name'] = isset($val['area_name']) ? $val['area_name'] : "";
                    $data[$key]['sender_name'] = addslashes($shipmentOrderArr['reciever_name']);
                    $data[$key]['sender_address'] = addslashes($shipmentOrderArr['reciever_address']);
                    $data[$key]['sender_phone'] = $shipmentOrderArr['reciever_phone'];
                    $data[$key]['sender_email'] = $shipmentOrderArr['reciever_email'];
                    $data[$key]['code'] = 'ROG';
                    $data[$key]['delivered'] = 22;
                    $data[$key]['reciever_name'] = addslashes($shipmentOrderArr['sender_name']);
                    $data[$key]['reciever_address'] = addslashes($shipmentOrderArr['sender_address']);
                    $data[$key]['reciever_phone'] = $shipmentOrderArr['sender_phone'];
                    $data[$key]['reciever_email'] = $shipmentOrderArr['sender_email'];


                    //sku details
                  
                    
                    $diamentionArr = $this->ShipmentR_model->GetshipmentdiamentionDataQuery($val['slip_no']);
                   
                    foreach ($diamentionArr as $nkey => $dval) {
                        $dval['slip_no'] = $neworder_number;
                        $dval['booking_id'] = $val['slip_no'];
                       array_push($diamentionArr_mnew,$dval);
                        
                        
                    }
                    
                    
                    //history details
                    $user_type = 'fulfillment';

                    $Activites = 'Reverse Order Generated';
                    $status_arr[$key]['slip_no'] = $neworder_number;
                    $status_arr[$key]['new_status'] = 22;
                    $status_arr[$key]['pickup_time'] = date('H:i:s');
                    $status_arr[$key]['pickup_date'] = date('Y-m-d H:i:s');
                    $status_arr[$key]['Activites'] = $Activites;
                    $status_arr[$key]['Details'] = $Activites;
                    $status_arr[$key]['comment'] = addslashes($comment);
                    $status_arr[$key]['entry_date'] = date('Y-m-d H:i:s');
                    $status_arr[$key]['user_id'] = $val['user_id'];
                    $status_arr[$key]['user_type'] = $user_type;
                    $status_arr[$key]['code'] = 'ROG';
                    $status_arr[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                    array_push($succssArray, $val2);
                } else {
                    array_push($invalid_slipNO, $val2);
                }
            }
            

           // $this->ShipmentR_model->insertdiamention_fm($diamentionArr);
           // echo "<pre>";
           // print_r($diamentionArr_mnew); die;
            if (!empty($data)) {
                $return = array("status" => 'succ', 'mess' => 'Successfully Order Created');
                $this->ShipmentR_model->insertshipment_fm($data);
                $this->ShipmentR_model->insertdiamention_fm($diamentionArr_mnew);
                $this->ShipmentR_model->insertstatus_fm($status_arr);
                $this->ShipmentR_model->Updateshipment_fm($updatedOLD);
            }
        } else {
            $return = array("status" => 'error', 'mess' => 'Please Enter Valid ABW No.');
        }
        $return['invalid_slipNO'] = $invalid_slipNO;
        $return['Success_msg'] = $succssArray;


        echo json_encode($return);
    }

}

?>