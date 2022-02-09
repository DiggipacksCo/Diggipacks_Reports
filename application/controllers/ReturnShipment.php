<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReturnShipment extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Seller_model');
        $this->load->model('ItemInventory_model');
        $this->load->model('Status_model');
        $this->load->model('ReturnShipment_model');
        $this->load->helper('zid');
        $this->load->helper('utility');
    }

    public function index() {
        if (menuIdExitsInPrivilageArray(149) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ReturnShipment/return_page');
    }

    public function updateData() {
        $postData_req = json_decode(file_get_contents('php://input'), true);

        $postData = $postData_req['valid_list'];
        $comment = $postData_req['comment'];
        //$group_slip=array_unique(array_column($postData,'slip_no'));
        //$comment = $postData['comment'];
        if (!empty($postData)) {
            $CheckOtherLocation = array();
            $slip_array = array();
            $error_invalid = [];
            $error_status_invalid = [];
            foreach ($postData as $key => $sku_val) {


                $slip_no = $sku_val['slip_no'];

                $check_slipNo = $this->Shipment_model->getallshipmentdatashow($slip_no);
                if (!empty($check_slipNo)) {
                     if ($check_slipNo['code'] == 'DL' || $check_slipNo['code'] == 'D3PL' || $check_slipNo['code'] == 'POD' || $check_slipNo['code'] == 'ROP' || $check_slipNo['code'] == 'OFD' || $check_slipNo['code'] == 'DOP' || $check_slipNo['code'] == 'FD' || $check_slipNo['delivered'] == '16') {
                        if (!in_array($slip_no, $slip_array)) {

                            $statusvalue[] = array(
                                'user_id' => $this->session->userdata('user_details')['user_id'],
                                'user_type' => 'fulfillment',
                                'slip_no' => $slip_no,
                                'new_status' => 8,
                                'code' => 'RTC',
                                'Activites' => 'Return',
                                'Details' => 'Order Return, Update By ' . getUserNameById($this->session->userdata('user_details')['user_id']),
                                'entry_date' => date('Y-m-d H:i:s'),
                                'super_id' => $this->session->userdata('user_details')['super_id'],
                                'comment' => isset($comment) ? $comment : ''
                            );
                            array_push($slip_array, $slip_no);

                            $shipment_array[] = array(
                                'code' => 'RTC',
                                'delivered' => 8,
                                'close_date' => date('Y-m-d'),
                                //'update_date' => date('Y-m-d H:i:s'),
                                'slip_no' => $slip_no
                            );
                        }

                        $item_sku = trim($sku_val['sku']);
                        $sku_arr = $this->ReturnShipment_model->getalldataitemtablesSKU($item_sku);
                        $SkuID = $sku_arr['id'];
                        $wh_id = $sku_arr['wh_id'];
                        $sku_size = $sku_arr['sku_size'];
                        $item_type = $sku_arr['type'];
                        $first_out = $sku_arr['first_out'];
                        $cust_id = $check_slipNo['cust_id'];
                        $seller_id = $check_slipNo['cust_id'];
                        //$qty = $chargeQty;
                        $total_damage = $sku_val['missing'] + $sku_val['damage'];
                        if ($total_damage > 0) {
                            $qty = $sku_val['piece'] - $total_damage;
                        } else {
                            $qty = $sku_val['piece'];
                        }

                        if ($total_damage > 0) {
                            $damage_iniventory[] = array(
                                'item_sku' => $SkuID,
                                'quantity' => $total_damage,
                                'd_qty' => $sku_val['damage'],
                                'm_qty' => $sku_val['missing'],
                                'order_no' => $slip_no,
                                'shelve_no' => isset($sku_val['deducted_shelve']) ? $sku_val['deducted_shelve'] : "",
                                'stock_location' => '',
                                'itype' => 'B2C',
                                'super_id' => $this->session->userdata('user_details')['super_id'],
                                'updated_by' => $this->session->userdata('user_details')['user_id'],
                                'seller_id' => $cust_id,
                                'update_date' => date("Y-m-d H:i:s"),
                                'order_type' => 'shipment'
                            );
                        }
                        $expdate = "0000-00-00";

                        if ($first_out == 'N') {
                            if ($qty > 0) {

                                $dataNew = $this->ItemInventory_model->find(array('item_sku' => $SkuID, 'expity_date' => $expdate, 'seller_id' => $seller_id, 'itype' => $item_type));
                                //echo "ss";
                                //print_r($dataNew); 
                                foreach ($dataNew as $val2) {
                                    if ($val2->quantity < $sku_size) {
                                        $check = $qty + $val2->quantity;
                                        $shelve_no = isset($val2->shelve_no) ? $val2->shelve_no : "";
                                        if ($check <= $sku_size) {
                                            $lastQtyUp = GetuserToatalLOcationQty($val2->id, 'quantity');
                                            $stock_location_upHistory = GetuserToatalLOcationQty($val2->id, 'stock_location');
                                            $lastQtyUp_up = $lastQtyUp;
                                            $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $SkuID, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $seller_id, 'qty' => $check, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'return', 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $slip_no, 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);
                                            // print_r($activitiesArr);
                                            if ($qty > 0) {
                                                GetAddInventoryActivities($activitiesArr);
                                            }
                                            $this->ItemInventory_model->updateInventory(array('quantity' => $check, 'id' => $val2->id));
                                            //echo $this->db->last_query();
                                            $qty = 0;
                                        } else {
                                            $diff = $sku_size - $val2->quantity;
                                            $lastQtyUp = GetuserToatalLOcationQty($val2->id, 'quantity');
                                            $stock_location_upHistory = GetuserToatalLOcationQty($val2->id, 'stock_location');
                                            $lastQtyUp_up = $lastQtyUp;
                                            $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $SkuID, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $seller_id, 'qty' => $sku_size, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'return', 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $slip_no, 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);
                                            //print_r($activitiesArr);
                                            if ($qty) {
                                                GetAddInventoryActivities($activitiesArr);
                                            }
                                            $this->ItemInventory_model->updateInventory(array('quantity' => $sku_size, 'id' => $val2->id));
                                            // echo $this->db->last_query();
                                            $qty = $qty - $diff;
                                        }
                                    }
                                }
                            }
                        }

                        if ($qty > 0) {
                            if ($sku_size >= $qty)
                                $locationLimit = 1;
                            else {
                                $locationLimit1 = $qty / $sku_size;
                                $locationLimit = ceil($locationLimit1);
                            }
                            $totalLocationCount += $locationLimit;
                            $StockArray = $this->ItemInventory_model->Getallstocklocationdata($seller_id, $locationLimit, $CheckOtherLocation);
                            $TotalInsystemStockLocation += count($StockArray);


                            $stocklocation = $StockArray;
                            $updateaty = $qty;
                            $AddQty = 0;
                            for ($ii = 0; $ii < $locationLimit; $ii++) {
                                if ($sku_size <= $updateaty) {
                                    $AddQty = $sku_size;
                                    $updateaty = $updateaty - $sku_size;
                                } else {
                                    $AddQty = $updateaty;
                                    $updateaty = $updateaty;
                                }

                                $array_added[] = array(
                                    'item_sku' => $SkuID,
                                    'seller_id' => $seller_id,
                                    'expity_date' => $expdate,
                                    'stock_location' => isset($StockArray[$ii]->stock_location) ? $StockArray[$ii]->stock_location : "",
                                    'quantity' => $AddQty,
                                    'itype' => $item_type,
                                    'shelve_no' => isset($sku_val['deducted_shelve']) ? $sku_val['deducted_shelve'] : "",
                                    'wh_id' => $wh_id,
                                    'super_id' => $this->session->userdata('user_details')['super_id']
                                );

                                $activitiesArr_batch[] = array(
                                    'exp_date' => $expdate,
                                    'st_location' => isset($StockArray[$ii]->stock_location) ? $StockArray[$ii]->stock_location : "",
                                    'item_sku' => $SkuID,
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $seller_id,
                                    'qty' => $AddQty,
                                    'p_qty' => 0,
                                    'qty_used' => $AddQty,
                                    'type' => 'return',
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'awb_no' => isset($slip_no) ? $slip_no : "",
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    'shelve_no' => isset($sku_val['deducted_shelve']) ? $sku_val['deducted_shelve'] : ""
                                );

                                array_push($CheckOtherLocation, $StockArray[$ii]->stock_location);
                            }
                        }
                    } else {
                        array_push($error_status_invalid, $slip_no);
                    }
                } else {
                    array_push($error_invalid, $slip_no);
                }
            }

           // echo "<pre>";
           // print_r($shipment_array); die;
            if (!empty($shipment_array)) {

               if ($this->Status_model->insertStatus($statusvalue)) {
                    $citc_req['t_slip_no']=$slip_array;
                    $this->session->set_userdata(array('tracking_citc_req'=>$citc_req));
                   $this->ReturnShipment_model->updateStatus($shipment_array);
               }
            }


            if (!empty($damage_iniventory)) {
                 $this->Shipment_model->Getupdatedamage_inventory($damage_iniventory);
            }

            if (!empty($array_added)) {
                 $this->ReturnShipment_model->add_inventory($array_added,$activitiesArr_batch);
            }





            //  echo "<pre>--";
            //  print_r($slip_array);
        }
        // echo "<pre>";
        //print_r($group_slip);
        $return['success'] = $slip_array;
        $return['invalid_slip'] = $error_invalid;
        $return['invalid_status'] = $error_status_invalid;


        echo json_encode($return);
        exit;
        
    }

    public function validatereturn() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $slipArray = preg_split('/\s+/', trim($_POST['slip_no']));
        $req_awb = array_unique($slipArray);
        //echo print_r($slipArray); exit;

        $valid = array();
        $invalid = array();

        // $slipArray = array();
        // print_r($shipments['result']);exit;

        foreach ($req_awb as $slip_no) {
            $data_n = $this->ReturnShipment_model->GetCheckReturnFulfilstatus($slip_no);
            if(!empty($data_n))
            {
                foreach($data_n as $data)
                {
                
                if ($data['code'] == 'DL' || $data['code'] == 'D3PL' || $data['code'] == 'POD' || $data['code'] == 'ROP' || $data['code'] == 'OFD' || $data['code'] == 'DOP' || $data['code'] == 'FD' || $data['delivered'] == '16') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
                }
            }
            else
            {
              array_push($invalid, array('slip_no' => $slip_no, 'code' => 'Wrong Awb'));
            }
        }

//        foreach ($slipArray as $newData) {
//            $group_slip = array_column($shipments['result'], 'slip_no');
//            print_r($group_slip);
//            $key = array_search($newData, $group_slip);
//
//            if ($key == '') {
//                //echo "sss";
//                array_push($invalid, array('slip_no' => $newData, 'code' => 'Wrong Awb'));
//            }
//            //
//            // echo '<br> slip_no:'.$newData.' || key:'.$key;
//        }
//exit;
        $returnData['valid'] = $valid;
        $returnData['invalid'] = $invalid;
        $returnData['total'] = count($slipArray);
        echo json_encode($returnData);
    }

}

?>