<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(dirname(__FILE__) . "/CourierCompany_pickup.php");

class Manifest extends CourierCompany_pickup {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(17) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Manifest_model');
        $this->load->model('Seller_model');
        $this->load->model('Item_model');
        $this->load->model('Status_model');
        $this->load->model('Shelve_model');
        $this->load->model('Pickup_model');
        $this->load->helper('utility');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function getmenifestlist() {

        $this->load->view('manifest/menifestlist');
    }

    public function show_assignedlist() {

        $this->load->view('manifest/menifestlist_assign');
    }

    public function updateManifest($uniqueid) {

        // echo $uniqueid;
        $data = $this->Manifest_model->filterUpdate(1, array("manifestid" => $uniqueid));
        //    echo "<pre>";
        //     print_r($data); exit;
        $this->load->view('manifest/updateManifest', $data);
    }

    public function return_manifest_view() {

        $this->load->view('return_manifest/return_manifest');
    }

    public function filter() {
        $this->load->model('User_model');
        $assignuser = $this->User_model->userDropval(9);
        $_POST = json_decode(file_get_contents('php://input'), true);

        $page_no = $_POST['page_no'];
        $seller_id = $_POST['seller_id'];
        $driverid = $_POST['driverid'];
        $manifestid = $_POST['manifestid'];
        $sort_list = $_POST['sort_list'];

        $filterarray = array('seller_id' => $seller_id, 'manifestid' => $manifestid, 'driverid' => $driverid, 'sort_list' => $sort_list);
        $shipments = $this->Manifest_model->filter($page_no, $filterarray);

        $manifestarray = $shipments['result'];
        $ii = 0;
        $seller_ids = "";
        foreach ($shipments['result'] as $rdata) {
            $checkArray = count($this->Manifest_model->GetallpickupRequestData_imtemCheck($rdata['uniqueid']));
            if ($checkArray == 0) {
                $manifestarray[$ii]['addBtnI'] = "N";
                $manifestarray[$ii]['skuid'] = getallitemskubyid($rdata['sku']);
            } else {
                $manifestarray[$ii]['addBtnI'] = "Y";
                $manifestarray[$ii]['skuid'] = 0;
            }
            $manifestarray[$ii]['vehicle_type'] = type_of_vehicleFiled($rdata['vehicle_type']);
            //$manifestarray[$ii]['checkArray']=$checkArray;

            if (GetcheckConditionsAddInventory($rdata['uniqueid']) == 'N')
                $manifestarray[$ii]['error'] = 1;
            else
                $manifestarray[$ii]['error'] = 0;
            //$stockLocation[]=$this->Manifest_model->GetallstockLocation($rdata['seller_id']);
            $manifestarray[$ii]['totalqtycount'] = $this->Manifest_model->getManifestReceviedUpdatesCount($rdata);
            $manifestarray[$ii]['complatedqty'] = $this->Manifest_model->getManifestReceviedUpdatesCountComp($rdata);
            $manifestarray[$ii]['sid'] = $rdata['seller_id'];
            if ($ii == 0)
                $seller_ids = $rdata['seller_id'];
            else
                $seller_ids .= ',' . $rdata['seller_id'];

            $manifestarray[$ii]['pstatus'] = GetpickupStatus($rdata['pstatus']);
            if ($rdata['seller_id'] > 0)
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name');
            else
                $manifestarray[$ii]['seller_id'] = 'N/A';
            if ($rdata['assign_to'] > 0)
                $manifestarray[$ii]['assign_to'] = getUserNameById($rdata['assign_to']);
            else
                $manifestarray[$ii]['assign_to'] = 'N/A';

            if ($rdata['staff_id'] > 0)
                $manifestarray[$ii]['staff_name'] = getUserNameById($rdata['staff_id']);
            else
                $manifestarray[$ii]['staff_name'] = 'N/A';



            if ($rdata['3pl_name'])
                $manifestarray[$ii]['company_name'] = $rdata['3pl_name'];
            else
                $manifestarray[$ii]['company_name'] = 'N/A';

            if ($rdata['3pl_awb'])
                $manifestarray[$ii]['company_awb'] = $rdata['3pl_awb'];
            else
                $manifestarray[$ii]['company_awb'] = 'N/A';

            if ($rdata['city'] > 0)
                $manifestarray[$ii]['city'] = getdestinationfieldshow($rdata['city'], 'city');
            else
                $manifestarray[$ii]['city'] = 'N/A';

            if ($rdata['address'])
                $manifestarray[$ii]['address'] = $rdata['address'];
            else
                $manifestarray[$ii]['address'] = 'N/A';
            $manifestarray[$ii]['company_label'] = $rdata['3pl_label'];

            $ii++;
        }
        $sellers = Getallsellerdata($seller_ids);
        $dataArray['result'] = $manifestarray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['assignuser'] = $assignuser;
        $dataArray['sellers'] = $sellers;
        //$dataArray['stockLocation']=$stockLocation;
        //echo '<pre>';
        //print_r($manifestarray);
        //exit();
        echo json_encode($dataArray);
    }

    public function filter_return() {
        $this->load->model('User_model');
        $assignuser = $this->User_model->userDropval(9);
        $_POST = json_decode(file_get_contents('php://input'), true);

        $page_no = $_POST['page_no'];
        $seller_id = $_POST['seller_id'];
        $driverid = $_POST['driverid'];
        $manifestid = $_POST['manifestid'];
        $filterarray = array('seller_id' => $seller_id, 'manifestid' => $manifestid, 'driverid' => $driverid);
        $shipments = $this->Manifest_model->filter_return($page_no, $filterarray);

        $manifestarray = $shipments['result'];
        $ii = 0;
        $seller_ids = "";
        foreach ($shipments['result'] as $rdata) {
            $checkArray = count($this->Manifest_model->GetallpickupRequestData_imtemCheck($rdata['uniqueid']));
            if ($checkArray == 0) {
                $manifestarray[$ii]['addBtnI'] = "N";
                $manifestarray[$ii]['skuid'] = getallitemskubyid($rdata['sku']);
            } else {
                $manifestarray[$ii]['addBtnI'] = "Y";
                $manifestarray[$ii]['skuid'] = 0;
            }
            //$manifestarray[$ii]['checkArray']=$checkArray;

            if (GetcheckConditionsAddInventory($rdata['uniqueid']) == 'N')
                $manifestarray[$ii]['error'] = 1;
            else
                $manifestarray[$ii]['error'] = 0;
            //$stockLocation[]=$this->Manifest_model->GetallstockLocation($rdata['seller_id']);
            $manifestarray[$ii]['totalqtycount'] = $this->Manifest_model->getManifestReceviedUpdatesCount($rdata);
            $manifestarray[$ii]['complatedqty'] = $this->Manifest_model->getManifestReceviedUpdatesCountComp($rdata);
            $manifestarray[$ii]['sid'] = $rdata['seller_id'];
            if ($ii == 0)
                $seller_ids = $rdata['seller_id'];
            else
                $seller_ids .= ',' . $rdata['seller_id'];

            $manifestarray[$ii]['pstatus'] = GetpickupStatus($rdata['pstatus']);
            if ($rdata['seller_id'] > 0)
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name');
            else
                $manifestarray[$ii]['seller_id'] = 'N/A';
            if ($rdata['assign_to'] > 0)
                $manifestarray[$ii]['assign_to'] = getUserNameById($rdata['assign_to']);
            else
                $manifestarray[$ii]['assign_to'] = 'N/A';



            if ($rdata['3pl_name'])
                $manifestarray[$ii]['company_name'] = $rdata['3pl_name'];
            else
                $manifestarray[$ii]['company_name'] = 'N/A';

            if ($rdata['3pl_awb'])
                $manifestarray[$ii]['company_awb'] = $rdata['3pl_awb'];
            else
                $manifestarray[$ii]['company_awb'] = 'N/A';

            if ($rdata['city'] > 0)
                $manifestarray[$ii]['city'] = getdestinationfieldshow($rdata['city'], 'city');
            else
                $manifestarray[$ii]['city'] = 'N/A';

            if ($rdata['address'])
                $manifestarray[$ii]['address'] = $rdata['address'];
            else
                $manifestarray[$ii]['address'] = 'N/A';
            $manifestarray[$ii]['company_label'] = $rdata['3pl_label'];

            $ii++;
        }
        $sellers = Getallsellerdata($seller_ids);
        $dataArray['result'] = $manifestarray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['assignuser'] = $assignuser;
        $dataArray['sellers'] = $sellers;
        //$dataArray['stockLocation']=$stockLocation;
        //echo '<pre>';
        //print_r($manifestarray);
        //exit();
        echo json_encode($dataArray);
    }

    public function GetalladdskuotherDrops() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $return['store'] = $this->Item_model->GetAllStorageTypes();
        $return['skudetails'] = $this->Manifest_model->GetallpickupRequestData_imtemCheck($_POST['uid']);
        echo json_encode($return);
    }

    public function manifestListFilter() {
        // print("heelo");
        // exit();
        $_POST = json_decode(file_get_contents('php://input'), true);
        //  print_r($_POST);

        $shipments = $this->Manifest_model->manifestviewListFilter($_POST);

        // json_encode($_POST);exit();
        //getdestinationfieldshow();
        $manifestarray = $shipments['result'];
        $ii = 0;
        foreach ($shipments['result'] as $rdata) {

            $manifestarray[$ii]['item_path'] = getalldataitemtablesSKU($rdata['sku'], 'item_path');


            $manifestarray[$ii]['pstatus'] = GetpickupStatus($rdata['pstatus']);
            if ($rdata['seller_id'] > 0)
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name');
            else
                $manifestarray[$ii]['seller_id'] = 'N/A';
            if ($rdata['assign_to'] > 0)
                $manifestarray[$ii]['assign_to'] = getUserNameById($rdata['assign_to']);
            else
                $manifestarray[$ii]['assign_to'] = 'N/A';


            $ii++;
        }

        $dataArray['result'] = $manifestarray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function getmanifestdetailsview($id = null, $type = null) {
        $data['manifest_id'] = $id;
        $data['type'] = $type;
        $this->load->view('manifest/manifestView', $data);
    }

    public function manifestlistexportview() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataArray = $_POST;
        $slip_data = array();
        $file_name = date('Ymdhis') . '.xls';
        // echo json_encode($dataArray[0]['code']);exit;


        $key = 0;
        if ($dataArray[0]['code'] == 'PR')
            echo json_encode($this->exportExcelmanifestlist($dataArray, $file_name));
        else {
            echo json_encode($this->exportExcelmanifestlist_pickup($dataArray, $file_name));
        }
    }

    function exportExcelmanifestlist($dataEx, $file_name) {
        $dataArray = array();
        $i = 0;
        foreach ($dataEx as $data) {

            $dataArray[$i]['uniqueid'] = $data['uniqueid'];
            $dataArray[$i]['qty'] = $data['qty'];
            $dataArray[$i]['seller_id'] = $data['seller_id'];
            $dataArray[$i]['req_date'] = $data['req_date'];



            $i++;
        }

        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Manifest ID')
                ->setCellValue('B1', 'QTY')
                ->setCellValue('C1', 'Seller')
                ->setCellValue('D1', 'Request Date');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');
        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();
        return $response = array('op' => 'ok', 'file_name' => $file_name, 'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData));
    }

    function exportExcelmanifestlist_pickup($dataEx, $file_name) {
        $dataArray = array();
        $i = 0;
        foreach ($dataEx as $data) {


            $dataArray[$i]['uniqueid'] = $data['uniqueid'];
            $dataArray[$i]['qty'] = $data['qty'];
            $dataArray[$i]['assign_to'] = $data['assign_to'];
            $dataArray[$i]['pstatus'] = $data['pstatus'];
            $dataArray[$i]['code'] = $data['code'];
            $dataArray[$i]['seller_id'] = $data['seller_id'];
            $dataArray[$i]['req_date'] = $data['req_date'];


            $i++;
        }

        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Manifest ID')
                ->setCellValue('B1', 'QTY')
                ->setCellValue('C1', 'Assign TO')
                ->setCellValue('D1', 'Status')
                ->setCellValue('E1', 'Code')
                ->setCellValue('F1', 'Seller')
                ->setCellValue('G1', 'Request Date');
        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');
        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();
        return $response = array('op' => 'ok', 'file_name' => $file_name, 'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData));
    }

    function getupdateManifestStatus() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        $table_manifestid = $dataArray['mid'];
        $sku = $dataArray['skuno'];
        $user_id = $this->session->userdata('user_details')['user_id'];
        $user_type = $this->session->userdata('user_details')['user_type'];
        $updateArray = array('code' => 'RI', 'pstatus' => 2, 'user_id' => $user_id, 'user_type' => $user_type);
        $result = $this->Manifest_model->ManifestStatusUpdate($updateArray, $table_manifestid, $sku);
        //echo json_encode($result); die;
        if ($result == true)
            echo json_encode(array('success' => 'successfully Updated'));
        else
            echo json_encode(array('error' => 'Please Enter Valid SKU No.'));
    }

    function getupdateassign() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        $uniqueid = $dataArray['mid'];
        $assignid = $dataArray['assignid'];
        $assign_type = $dataArray['assign_type'];
        $order_type = $dataArray['order_type'];
        $cc_id = $dataArray['cc_id'];
        // print_r($dataArray); die;
        if ($assign_type == 'CC') {
            $request_return = $this->BulkForwardCompanyReady($uniqueid, $cc_id, $order_type);
            if (!empty($request_return['Success_msg'])) {
                $return = array('status' => "succ");
            } else {
                $return = $request_return;
            }
        }
        if ($assign_type == 'D') {

            $updateArray = array('code' => 'AT', 'pstatus' => 6, 'assign_to' => $assignid);
            $result = $this->Manifest_model->Getdriverassignupdate($updateArray, $uniqueid);
            $return = array('status' => "succ");
        }


        echo json_encode($return);
    }

    function getupdateassign_return() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST['singleArr'];
        $itemData = $_POST['itemdata'];
        $uniqueid = $dataArray['mid'];
        $assignid = $dataArray['assignid'];
        $assign_type = $dataArray['assign_type'];
        $order_type = $dataArray['order_type'];
        $cc_id = $dataArray['cc_id'];

        // print_r($_POST); die;

        $itemArr = $this->ItemInventory_model->filter_damage_check($itemData);

        $sendCourier = array('singleArr' => $dataArray, 'itemdata' => $itemArr);

        if ($assign_type == 'CC') {
            $uid = strtoupper(uniqid());
            $request_date = date("Y-m-d");
            foreach ($itemArr as $key => $val) {
                //==============create new order==================//

                $damageorderUpdate[$key]['return_update'] = 'Y';
                $damageorderUpdate[$key]['id'] = $val['id'];

                $orderCreateArr[$key]['uniqueid'] = $uid;
                $orderCreateArr[$key]['seller_id'] = $val['seller_id'];
                $orderCreateArr[$key]['sku'] = $val['sku'];
                $orderCreateArr[$key]['qty'] = $val['quantity'];
                $orderCreateArr[$key]['boxes'] = $dataArray['boxes'];
                $orderCreateArr[$key]['pack_type'] = $dataArray['pack_type'];
                $orderCreateArr[$key]['assign_to'] = $assignid;
                $orderCreateArr[$key]['city'] = getUserNameById_field($this->session->userdata('user_details')['user_id'], 'branch_location');
                if (!empty($val['expire_date'])) {
                    $orderCreateArr[$key]['expire_date'] = $val['expire_date'];
                }
                $orderCreateArr[$key]['req_date'] = $request_date;
                $orderCreateArr[$key]['pstatus'] = 7;
                $orderCreateArr[$key]['code'] = 'RTC';
                $orderCreateArr[$key]['return_type'] = 'N';

                $orderCreateArr[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $orderCreateArr[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                //===============================================//
            }
            $result = $this->Manifest_model->Getdriverassignupdate_return($orderCreateArr);


            $request_return = $this->BulkForwardCompanyReady($uid, $cc_id, $order_type, $sendCourier);
            if (!empty($request_return['Success_msg'])) {
                $this->Manifest_model->GetUpdateDamageInventory($damageorderUpdate);

                $return = array('status' => "succ");
            } else {
                $return = $request_return;
            }
        }
        if ($assign_type == 'D') {

            $uid = strtoupper(uniqid());
            $request_date = date("Y-m-d");
            foreach ($itemArr as $key => $val) {
                //==============create new order==================//

                $damageorderUpdate[$key]['return_update'] = 'Y';
                $damageorderUpdate[$key]['id'] = $val['id'];

                $orderCreateArr[$key]['uniqueid'] = $uid;
                $orderCreateArr[$key]['seller_id'] = $val['seller_id'];
                $orderCreateArr[$key]['sku'] = $val['sku'];
                $orderCreateArr[$key]['qty'] = $val['quantity'];
                $orderCreateArr[$key]['boxes'] = $dataArray['boxes'];
                $orderCreateArr[$key]['pack_type'] = $dataArray['pack_type'];
                $orderCreateArr[$key]['assign_to'] = $assignid;
                $orderCreateArr[$key]['city'] = getUserNameById_field($this->session->userdata('user_details')['user_id'], 'branch_location');
                if (!empty($val['expire_date'])) {
                    $orderCreateArr[$key]['expire_date'] = $val['expire_date'];
                }
                $orderCreateArr[$key]['address'] = getUserNameById_field($this->session->userdata('user_details')['user_id'], 'address');
                $orderCreateArr[$key]['req_date'] = $request_date;
                $orderCreateArr[$key]['pstatus'] = 7;
                $orderCreateArr[$key]['code'] = 'RTC';
                $orderCreateArr[$key]['return_type'] = 'Y';

                $orderCreateArr[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $orderCreateArr[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                //===============================================//
            }
            $result = $this->Manifest_model->Getdriverassignupdate_return($orderCreateArr);
            $this->Manifest_model->GetUpdateDamageInventory($damageorderUpdate);
            $return = array('status' => "succ");
        }


        echo json_encode($return);
    }

    function GetnotfoundstausCtr() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        //echo json_encode($dataArray); die;
        $upid = $dataArray['upid'];
        $upstatus = $dataArray['upstatus'];
        if ($upstatus == 'MSI')
            $pstatus = 3;
        if ($upstatus == 'DI')
            $pstatus = 4;
        $updateArray = array('code' => $upstatus, 'pstatus' => $pstatus);
        $result = $this->Manifest_model->GetNotfoundStatusUpdates($updateArray, $upid);
        echo json_encode($result);
    }

    public function GetUpdateMissingdamageAll() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $updteIdsArr = $postData['listIds'];
        $type = $postData['type'];
        if ($type == 'D') {
            $code = 'DI';
            $pstatus = 4;
        }
        if ($type == 'M') {
            $code = 'MSI';
            $pstatus = 3;
        }
        if (!empty($updteIdsArr)) {
            foreach ($updteIdsArr as $key => $val) {

                $updateArray[$key]['id'] = $val;
                $updateArray[$key]['code'] = $code;
                $updateArray[$key]['pstatus'] = $pstatus;
            }
            // print_r($updateArray);
            if (!empty($updateArray)) {
                $return = $this->Manifest_model->GetManifestUpdateDamageMissiing($updateArray);
            }
        }
        echo json_encode($return);
    }

    function check_shelve() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST); die;
        $dataArray = $this->Shelve_model->GetcheckshelaveUse($_POST);

        if (!empty($dataArray))
            echo json_encode(array('status' => false));
        else
            echo json_encode(array('status' => true));
    }

    function getmanifestrecviedUpdate() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST); die;
        $dataArray = $_POST;

        $uniqueid = $dataArray[0]['mid'];
        $seller_id = $dataArray[0]['seller_id'];
        $pstatus = 5;
        $code = 'PU';

        foreach ($dataArray as $key => $val) {
            $updateArray[$key]['code'] = 'RI';
            $updateArray[$key]['pstatus'] = 2;
            $updateArray[$key]['sku'] = $val['sku'];
            $newUpdateArray = array(
                'code' => 'RI',
                'pstatus' => 2,
            );
            $updateArray_w = array('uniqueid' => $uniqueid, 'seller_id' => $seller_id, 'code' => $code, 'pstatus' => $pstatus, 'sku' => $val['sku']);
            $this->Manifest_model->getManifestReceviedUpdates_new_single($newUpdateArray, $updateArray_w, $val['scan']);
        }
        $return = true;
        if (!empty($updateArray)) {
            $return = true;
            // $return= $this->Manifest_model->getManifestReceviedUpdates_new($updateArray, $updateArray_w);
        }

        echo json_encode($return);
    }

    public function Getnewrequestmanifest() {
        //echo "sssssss"; die;
        $this->db->query("update pickup_request set seen=1 where super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
        $this->load->view('manifest/newmanifestrequest');
    }

    public function getpickuplistmanifest() {
        $this->load->view('manifest/pickuplist');
    }

    function GetnewmanifestreqShow() {
        $this->load->model('User_model');
        $assignuser = $this->User_model->userDropval(9);
        $courierData = GetCourierCompanyDrop();



        $_POST = json_decode(file_get_contents('php://input'), true);
        $from = $_POST['from'];
        $to = $_POST['to'];
        $page_no = $_POST['page_no'];
        $seller_id = $_POST['seller_id'];
        $manifestid = $_POST['manifestid'];
        $sort_list = $_POST['sort_list'];
        $filterarray = array('seller_id' => $seller_id, 'manifestid' => $manifestid, 'sort_list' => $sort_list);

        $shipments = $this->Manifest_model->getnewgenratemanifestdata($to, $from, $page_no, $filterarray);

        $manifestarray = $shipments['result'];
        $ii = 0;
        $seller_ids = "";
        foreach ($shipments['result'] as $rdata) {
            if ($ii == 0)
                $seller_ids = $rdata['seller_id'];
            else
                $seller_ids .= ',' . $rdata['seller_id'];

            $manifestarray[$ii]['vehicle_type'] = type_of_vehicleFiled($rdata['vehicle_type']);
            if ($rdata['seller_id'] > 0)
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name');
            else
                $manifestarray[$ii]['seller_id'] = 'N/A';
            $ii++;
        }

        ///	echo json_encode($sellers); die;
        $sellers = Getallsellerdata($seller_ids);
        $dataArray['result'] = $manifestarray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['assignuser'] = $assignuser;
        $dataArray['sellers'] = $sellers;
        $dataArray['courierData'] = $courierData;

        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    function Getpickuplistshow() {
        $this->load->model('User_model');
        $assignuser = $this->User_model->userDropval(9);
        $_POST = json_decode(file_get_contents('php://input'), true);

        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller_id = $_POST['seller_id'];
        $driverid = $_POST['driverid'];
        $manifestid = $_POST['manifestid'];
        $sort_list = $_POST['sort_list'];
        $page_no = $_POST['page_no'];
        //echo json_encode($_POST); die;
        $filterarray = array('seller_id' => $seller_id, 'driverid' => $driverid, 'manifestid' => $manifestid, 'sort_list' => $sort_list);
        $shipments = $this->Manifest_model->getpickuplistdatashow($to, $from, $page_no, $filterarray);
//echo json_encode($shipments); die;

        $manifestarray = $shipments['result'];
        $ii = 0;
        $seller_ids = "";
        foreach ($shipments['result'] as $rdata) {

            if ($ii == 0)
                $seller_ids = $rdata['seller_id'];
            else
                $seller_ids .= ',' . $rdata['seller_id'];
            $manifestarray[$ii]['pstatus'] = GetpickupStatus($rdata['pstatus']);
            if ($rdata['seller_id'] > 0)
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name');
            else
                $manifestarray[$ii]['seller_id'] = 'N/A';
            if ($rdata['assign_to'] > 0)
                $manifestarray[$ii]['assign_to'] = getUserNameById($rdata['assign_to']);
            else
                $manifestarray[$ii]['assign_to'] = 'N/A';

            if ($rdata['3pl_name'])
                $manifestarray[$ii]['company_name'] = $rdata['3pl_name'];
            else
                $manifestarray[$ii]['company_name'] = 'N/A';

            if ($rdata['3pl_awb'])
                $manifestarray[$ii]['company_awb'] = $rdata['3pl_awb'];
            else
                $manifestarray[$ii]['company_awb'] = 'N/A';

            if ($rdata['city'] > 0)
                $manifestarray[$ii]['city'] = getdestinationfieldshow($rdata['city'], 'city');
            else
                $manifestarray[$ii]['city'] = 'N/A';

            if ($rdata['address'])
                $manifestarray[$ii]['address'] = $rdata['address'];
            else
                $manifestarray[$ii]['address'] = 'N/A';
            $manifestarray[$ii]['company_label'] = $rdata['3pl_label'];
            $ii++;
        }
        $sellers = Getallsellerdata($seller_ids);
        //echo json_encode($sellers); die;
        $dataArray['result'] = $manifestarray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['assignuser'] = $assignuser;
        $dataArray['sellers'] = $sellers;
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function getupdatepickupimagedata() {
        //$_POST = json_decode(file_get_contents('php://file'), true);
        // echo json_encode($_POST); die;

        $manifestid = $this->input->post('manifestid');
        if (!empty($manifestid) && !empty($_FILES['imagepath']['name'])) {
            if (!empty($_FILES['imagepath']['name'])) {
                $config['upload_path'] = 'assets/pickupfile/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['logo_path']['name'];
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('imagepath')) {
                    $uploadData = $this->upload->data();
                    $small_img = $config['upload_path'] . '' . $uploadData['file_name'];
                }
            }
            $updateArray = array('code' => 'PU', 'pstatus' => 5, 'pickimg' => $small_img);
            $result = $this->Manifest_model->getpickedupupdatestatus($updateArray, $manifestid);
        }
        echo json_encode($result);
    }

    public function GetItemInventoryDataadd() {

        $this->load->model('ItemInventory_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); die;
        $uid = $_POST['uid'];
        $sid = $_POST['sid'];
        $SkuData = $this->Manifest_model->GetallmanifestskuData($uid, $sid);
        // print_r($_POST);
        // $totalqty="";
        // $totalsku_size=0;
        //echo '<pre>';
        $skureturnarray = array();
        $kk = 0;
        $newlimitcheck = 0;
        $totaladdQtyInvoice = 0;
        // echo '<pre>';
        $checkpalletError = array();
        $error = array();
        foreach ($SkuData as $key5 => $rdata) {
            // print_r($rdata);
            $palletno = $_POST['result'][$key5]['shelveNo'];
            // $expire_date=$_POST['result'][$key5]['expire_date'];
            $wh_id = $_POST['result'][$key5]['wh_id'];

            if (!empty($palletno)) {
                $PalletsCheck = $this->ItemInventory_model->GetcheckvalidPalletNo($palletno);
                if ($PalletsCheck == true) {
                    $PalletArrayI = $this->ItemInventory_model->GetcheckPalletInventry($palletno, $sid);
                    if (!empty($PalletArrayI)) {
                        if ($sid == $PalletArrayI['seller_id']) {
                            if (empty($checkpalletError)) {
                                //echo 'ssssss';	  
                                $dammageMissngQty = $this->Manifest_model->GetmissingQtyCHeck($uid, $sid, $rdata['sku']);
                                $rdata['qty'] = $rdata['qty'] - $dammageMissngQty;
                                $totaladdQtyInvoice += $rdata['qty'];
                                $totalqty = $rdata['qty'];
                                $skuid = getallitemskubyid(trim($rdata['sku']));
                                $totalsku_size = getalldataitemtables($skuid, 'sku_size');
                                $item_type = getalldataitemtables($skuid, 'type');
                                $first_out = getallsellerdatabyID($sid, 'first_out');
                                //echo $rdata['expire_date'];
                                if (empty($rdata['expire_date']))
                                    $expdate = "0000-00-00";
                                else
                                    $expdate = trim($rdata['expire_date']);
                                $qty = $totalqty;
                                $sku_size = $totalsku_size;
                                if ($first_out == 'N') {

                                    $dataNew = $this->ItemInventory_model->find(array('item_sku' => $skuid, 'expity_date' => $expdate, 'seller_id' => $sid, 'itype' => $item_type, 'itype' => $item_type, 'wh_id' => $wh_id));
                                    //print_r($dataNew); die;


                                    foreach ($dataNew as $val) {
                                        if ($val->quantity < $sku_size) {

                                            //echo '<br> 2//'.$qty.'//'. $val->quantity.'//';
                                            $check = $qty + $val->quantity;

                                            $shelve_no = $val->shelve_no;
                                            if (empty($shelve_no)) {
                                                $shelve_no = "";
                                            }
                                            if ($check <= $sku_size) {

                                                $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                                                $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                                                $lastQtyUp_up = $lastQtyUp;
                                                $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $skuid, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $sid, 'qty' => $check, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);


                                                GetAddInventoryActivities($activitiesArr);
                                                $this->ItemInventory_model->updateInventory(array('quantity' => $check, 'id' => $val->id));
                                                $qty = 0;
                                            } else {

                                                $diff = $sku_size - $val->quantity;
                                                $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                                                $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                                                $lastQtyUp_up = $lastQtyUp;
                                                $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $skuid, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $sid, 'qty' => $sku_size, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);

                                                GetAddInventoryActivities($activitiesArr);
                                                $this->ItemInventory_model->updateInventory(array('quantity' => $sku_size, 'id' => $val->id));
                                                $qty = $qty - $diff;
                                            }
                                        }


                                        // echo $val['item_sku'];  
                                    }
                                }

//echo $qty;
                                if ($qty > 0) {
                                    if ($totalsku_size >= $qty)
                                        $locationLimit = 1;
                                    else {
                                        $locationLimit1 = $qty / $totalsku_size;
                                        $locationLimit = ceil($locationLimit1);
                                    }
                                    $newlimitcheck += $locationLimit;
                                    // echo $sid;
                                    $skureturnarray2 = $this->Manifest_model->GetallstockLocation($sid);
                                    if ($kk == 0)
                                        $stocklocation12 = array_slice($skureturnarray2, 0, $locationLimit, true);
                                    else
                                        $stocklocation12 = array_slice($skureturnarray2, $locationLimit, $locationLimit, true);

                                    $stocklocation = array_values($stocklocation12);
                                    // $stocklocation=$this->input->post('stock_location');
                                    //print_r($stocklocation);
                                    $updateaty = $totalqty;
                                    $AddQty = 0;
                                    for ($ii = 0; $ii < $locationLimit; $ii++) {
                                        //  echo $kk."<br>";
                                        if ($totalsku_size <= $updateaty) {
                                            $AddQty = $totalsku_size;
                                            $updateaty = $updateaty - $totalsku_size;
                                        } else {
                                            $AddQty = $updateaty;
                                            $updateaty = $updateaty;
                                        }



                                        $data[] = array(
                                            'itype' => $item_type,
                                            'item_sku' => $skuid,
                                            'seller_id' => $sid,
                                            'quantity' => $AddQty,
                                            'update_date' => date("Y/m/d h:i:sa"),
                                            'stock_location' => $stocklocation[$ii]->stock_location,
                                            'expity_date' => $rdata['expire_date'],
                                            'wh_id' => $wh_id,
                                            //'shelve_no' => $palletno,
                                            'super_id' => $this->session->userdata('user_details')['user_id']
                                        );
                                    }
                                    // echo $locationLimit."<br>";
                                    // 
                                    //print_r($skureturnarray[$kk]);

                                    $kk++;
                                    if ($item_type == 'B2B') {
                                        $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $newlimitcheck, $totaladdQtyInvoice, $skuid);
                                    } else {
                                        $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $totalqty, $totaladdQtyInvoice, $skuid);
                                    }
                                } else {

                                    if ($item_type == 'B2B') {
                                        $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $newlimitcheck, $totaladdQtyInvoice, $skuid);
                                    } else {
                                        $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $totalqty, $totaladdQtyInvoice, $skuid);
                                    }
                                }
                                $error['success'][] = $rdata['sku'];
                            }
                        } else {
                            $error['alreadypallet'][] = $palletno;
                            array_push($checkpalletError, $palletno);
                        }
                    } else {
                        if (empty($checkpalletError)) {
                            // echo 'tttt';	  

                            $dammageMissngQty = $this->Manifest_model->GetmissingQtyCHeck($uid, $sid, $rdata['sku']);
                            $rdata['qty'] = $rdata['qty'] - $dammageMissngQty;
                            $totaladdQtyInvoice += $rdata['qty'];
                            $totalqty = $rdata['qty'];
                            $skuid = getallitemskubyid(trim($rdata['sku']));
                            $totalsku_size = getalldataitemtables($skuid, 'sku_size');
                            $item_type = getalldataitemtables($skuid, 'type');
                            //echo $rdata['expire_date'];
                            $first_out = getallsellerdatabyID($sid, 'first_out');
                            if (empty($rdata['expire_date']))
                                $expdate = "0000-00-00";
                            else
                                $expdate = trim($rdata['expire_date']);

                            $qty = $totalqty;

                            $sku_size = $totalsku_size;

                            if ($first_out == 'N') {

                                $dataNew = $this->ItemInventory_model->find(array('item_sku' => $skuid, 'expity_date' => $expdate, 'seller_id' => $sid, 'itype' => $item_type, 'itype' => $item_type, 'wh_id' => $wh_id));
                                //print_r($dataNew); die;


                                foreach ($dataNew as $val) {
                                    if ($val->quantity < $sku_size) {

                                        //echo '<br> 2//'.$qty.'//'. $val->quantity.'//';
                                        $check = $qty + $val->quantity;
                                        if ($check <= $sku_size) {

                                            $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                                            $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                                            $lastQtyUp_up = $lastQtyUp;
                                            $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $skuid, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $sid, 'qty' => $check, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id']);

                                            GetAddInventoryActivities($activitiesArr);
                                            $this->ItemInventory_model->updateInventory(array('quantity' => $check, 'id' => $val->id));
                                            $qty = 0;
                                        } else {

                                            $diff = $sku_size - $val->quantity;
                                            $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                                            $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                                            $lastQtyUp_up = $lastQtyUp;
                                            $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $skuid, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $sid, 'qty' => $sku_size, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id']);

                                            GetAddInventoryActivities($activitiesArr);
                                            $this->ItemInventory_model->updateInventory(array('quantity' => $sku_size, 'id' => $val->id));
                                            $qty = $qty - $diff;
                                        }
                                    }


                                    // echo $val['item_sku'];  
                                }
                            }

                            if ($qty > 0) {
                                if ($totalsku_size >= $qty)
                                    $locationLimit = 1;
                                else {
                                    $locationLimit1 = $qty / $totalsku_size;
                                    $locationLimit = ceil($locationLimit1);
                                }
                                $newlimitcheck += $locationLimit;
                                // echo $sid;
                                $skureturnarray2 = $this->Manifest_model->GetallstockLocation($sid);
                                if ($kk == 0)
                                    $stocklocation12 = array_slice($skureturnarray2, 0, $locationLimit, true);
                                else
                                    $stocklocation12 = array_slice($skureturnarray2, $locationLimit, $locationLimit, true);

                                $stocklocation = array_values($stocklocation12);
                                // $stocklocation=$this->input->post('stock_location');
                                //print_r($stocklocation);
                                $updateaty = $totalqty;
                                $AddQty = 0;
                                for ($ii = 0; $ii < $locationLimit; $ii++) {
                                    //  echo $kk."<br>";
                                    if ($totalsku_size <= $updateaty) {
                                        $AddQty = $totalsku_size;
                                        $updateaty = $updateaty - $totalsku_size;
                                    } else {
                                        $AddQty = $updateaty;
                                        $updateaty = $updateaty;
                                    }



                                    $data[] = array(
                                        'itype' => $item_type,
                                        'item_sku' => $skuid,
                                        'seller_id' => $sid,
                                        'quantity' => $AddQty,
                                        'update_date' => date("Y/m/d h:i:sa"),
                                        'stock_location' => $stocklocation[$ii]->stock_location,
                                        'expity_date' => $rdata['expire_date'],
                                        'wh_id' => $wh_id,
                                        // 'shelve_no' => $palletno,
                                        'super_id' => $this->session->userdata('user_details')['user_id']
                                    );
                                }
                                // echo $locationLimit."<br>";
                                // 
                                //print_r($skureturnarray[$kk]);

                                $kk++;
                                if ($item_type == 'B2B') {
                                    $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $newlimitcheck, $totaladdQtyInvoice, $skuid);
                                } else {
                                    $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $totalqty, $totaladdQtyInvoice, $skuid);
                                }
                            } else {

                                if ($item_type == 'B2B') {
                                    $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $newlimitcheck, $totaladdQtyInvoice, $skuid);
                                } else {
                                    $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($_POST, $totalqty, $totaladdQtyInvoice, $skuid);
                                }
                            }


                            $error['success'][] = $rdata['sku'];
                        }
                    }
                } else {
                    $error['invalidpallet'][] = $palletno;
                    array_push($checkpalletError, $palletno);
                }
            } else {
                $error['emptypallet'][] = $rdata['sku'];
                array_push($checkpalletError, $rdata['sku']);
            }
        }


        // echo "ssss";
        // echo '<pre>';
        // print_r($data);
        // die;
        // die;
        if (!empty($error['success'])) {
            // echo "ss";  
            if (!empty($data)) {
                $result = $this->ItemInventory_model->add($data);
            }
            $result1 = $this->Manifest_model->getupdateconfirmstatus($uid);
        }
        //echo '<pre>';
        //print_r($data);
        echo json_encode($error);
    }

    public function getUpdatemanifestSuggestion() {

        // $this->load->model('ItemInventory_model');
//echo '<pre>';
        $_POST = json_decode(file_get_contents('php://input'), true);
        $uid = $_POST['uid']; //'5E0DB8692B7A6';
        $sid = $_POST['sid']; //3;//
        $SkuData = $this->Manifest_model->GetallmanifestskuData($uid, $sid);


        // $totalqty="";
        // $totalsku_size=0;

        $skureturnarray = array();
        $kk = 0;
        $newlimitcheck = 0;
        $totallocationarray = 0;
        foreach ($SkuData as $rdata) {
            // $totalqty=$rdata['qty'];
            $totalqty = GetManifestInventroyUpdateQty($uid, $sid, $rdata['sku']);

            $skuid = getallitemskubyid($rdata['sku']);
            $totalsku_size = getalldataitemtables($skuid, 'sku_size');
            $wh_id = getalldataitemtables($skuid, 'wh_id');
            $warehouse_name = Getwarehouse_categoryfield($wh_id, 'name');

            $shelveNo = getshelveNobyid($skuid);
            if ($totalsku_size >= $totalqty)
                $locationLimit = 1;
            else {
                // echo $totalqty ."==========". $totalsku_size;
                $locationLimit1 = $totalqty / $totalsku_size;
                $locationLimit = ceil($locationLimit1);
            }
            $newlimitcheck += $locationLimit;
            // echo $locationLimit."<br>";
            // 
            $skureturnarray2 = $this->Manifest_model->GetallstockLocation($sid);
            // print_r($skureturnarray2);
            if ($kk == 0) {
                $createlocation = array_slice($skureturnarray2, 0, $locationLimit, true);
            } else {
                $createlocation = array_slice($skureturnarray2, $locationLimit, $locationLimit, true);
            }

            // print_r($createlocation);
            //print_r($skureturnarray[$kk]);
            $totallocationarray += count($createlocation);
            $skureturnarray[$kk]['stockLocation'] = array_values($createlocation);
            $skureturnarray[$kk]['sku'] = $rdata['sku'];
            $skureturnarray[$kk]['boxes'] = $locationLimit;
            $skureturnarray[$kk]['shelveNo'] = $shelveNo;
            $skureturnarray[$kk]['warehouse_name'] = $warehouse_name;
            $skureturnarray[$kk]['wh_id'] = $wh_id;

            $kk++;
        }
        //  echo '<pre>';
        // print_r($skureturnarray);
        //$arraycheck=array_filter($skureturnarray);
        // echo '<pre>';
        // print_r($skureturnarray);
        //echo $totallocationarray;
        // die;
        // echo count($skureturnarray);

        $sotrageTypes = $this->Manifest_model->getallStoragesTypesData();
        $warehouseArr = Getwarehouse_Dropdata();
        $reurnarray = array('result' => $skureturnarray, 'uid' => $uid, 'sid' => $sid, 'countbox' => $newlimitcheck, 'countarray' => $totallocationarray, 'warehouseArr' => $warehouseArr);
        echo json_encode($reurnarray);
    }

    public function Getallsellerstocklocations() {

        // $this->load->model('ItemInventory_model');
//echo '<pre>';
        $_POST = json_decode(file_get_contents('php://input'), true);
        $uid = $_POST['uid']; //'5E0DB8692B7A6';
        $sid = $_POST['sid']; //3;//
        $SkuData = $this->Manifest_model->GetallmanifestskuData($uid, $sid);


        // $totalqty="";
        // $totalsku_size=0;

        $skureturnarray = array();
        $kk = 0;
        $newlimitcheck = 0;
        $totallocationarray = 0;
        foreach ($SkuData as $rdata) {
            // $totalqty=$rdata['qty'];
            $totalqty = GetManifestInventroyUpdateQty($uid, $sid, $rdata['sku']);

            $skuid = getallitemskubyid($rdata['sku']);
            $totalsku_size = getalldataitemtables($skuid, 'sku_size');
            $wh_id = getalldataitemtables($skuid, 'wh_id');
            $warehouse_name = Getwarehouse_categoryfield($wh_id, 'name');

            $shelveNo = getshelveNobyid($skuid);
            if ($totalsku_size >= $totalqty)
                $locationLimit = 1;
            else {
                // echo $totalqty ."==========". $totalsku_size;
                $locationLimit1 = $totalqty / $totalsku_size;
                $locationLimit = ceil($locationLimit1);
            }
            $newlimitcheck += $locationLimit;
            // echo $locationLimit."<br>";
            // 
            $skureturnarray2 = $this->Manifest_model->GetallstockLocation($sid);
            // print_r($skureturnarray2);
            if ($kk == 0) {
                $createlocation = array_slice($skureturnarray2, 0, $locationLimit, true);
            } else {
                $createlocation = array_slice($skureturnarray2, $locationLimit, $locationLimit, true);
            }

            // print_r($createlocation);
            //print_r($skureturnarray[$kk]);
            $totallocationarray += count($createlocation);
            $skureturnarray[$kk]['stockLocation'] = array_values($createlocation);
            $skureturnarray[$kk]['sku'] = $rdata['sku'];
            $skureturnarray[$kk]['boxes'] = $locationLimit;
            $skureturnarray[$kk]['shelveNo'] = $shelveNo;
            $skureturnarray[$kk]['warehouse_name'] = $warehouse_name;
            $skureturnarray[$kk]['wh_id'] = $wh_id;

            $kk++;
        }
        //  echo '<pre>';
        // print_r($skureturnarray);
        //$arraycheck=array_filter($skureturnarray);
        // echo '<pre>';
        // print_r($skureturnarray);
        //echo $totallocationarray;
        // die;
        // echo count($skureturnarray);

        $sotrageTypes = $this->Manifest_model->getallStoragesTypesData();
        $warehouseArr = Getwarehouse_Dropdata();
        $reurnarray = array('result' => $skureturnarray, 'uid' => $uid, 'sid' => $sid, 'countbox' => $newlimitcheck, 'countarray' => $totallocationarray, 'warehouseArr' => $warehouseArr);
        echo json_encode($reurnarray);
    }

    public function GetupdateOnholdData() {
        // $this->load->model('ItemInventory_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        $uid = $_POST['uid'];
        $sid = $_POST['sid'];
        $stockLocation = $this->Manifest_model->getUpdateHoldOnData($uid, $sid);
        echo json_encode($_POST);
    }

    public function GetallskuDetailsByOneGroup() {
        $postdata = json_decode(file_get_contents('php://input'), true);
        $mid = $postdata['mid'];
        $returnresult = $this->Manifest_model->GetallskuDetailsByOneGroupQry($mid);
        echo json_encode($returnresult);
    }

    public function GetreturnCourierDropShow() {
        $this->load->model('User_model');
        $PostData = json_decode(file_get_contents('php://input'), true);
        $assignuser = $this->User_model->userDropval(9);
        $courierData = GetCourierCompanyDrop();
        $return = array("assignuser" => $assignuser, "courierData" => $courierData);
        echo json_encode($return);
    }

    public function GetStaffListDrop() {
        $return = GetUserDropDownShowArr();
        echo json_encode($return);
    }

    public function GetUpdateStaffAssign() {
        $postdata = json_decode(file_get_contents('php://input'), true);

        if (!empty($postdata['staff_id'])) {
            $uniqueid = $postdata['mid'];
            $updateArr = array("staff_id" => $postdata['staff_id'], 'assign_date' => date("Y-m-d H:i:s"));

            // print_r($updateArr);
            $return = $this->Manifest_model->GetUpdateStaffAssignQry($updateArr, $uniqueid);
        }
        // print_r($postdata);
        echo json_encode($return);
    }

    public function GetUpdateManifestStockLocation() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $uid = $_POST['list']['uid']; //'5E0DB8692B7A6';
        $sid = $_POST['list']['sid']; //3;//
        $sku = $_POST['list']['sku'];
        $stockArr = $_POST['stockArr'];
        $shelveArr = $_POST['shelveArr'];
        $SkuData = $this->Manifest_model->GetallmanifestskuData_new($uid, $sid, $sku);


        // $totalqty="";
        // $totalsku_size=0;

        $skureturnarray = array();
        $kk = 0;
        $newlimitcheck = 0;
        $totallocationarray = 0;
        foreach ($SkuData as $rdata) {
            // $totalqty=$rdata['qty'];
            $expire_date = $rdata['expire_date'];
            if (empty($expire_date)) {
                $expdate = "0000-00-00";
            } else {
                $expdate = $expire_date;
            }
            $totalqty = GetManifestInventroyUpdateQty($uid, $sid, $rdata['sku']);

            $skuid = $rdata['item_sku'];
            $totalsku_size = $rdata['sku_size'];
            $storage_type = $rdata['storage_type'];
            $wh_id = $rdata['wh_id'];
            $warehouse_name = Getwarehouse_categoryfield($wh_id, 'name');

            $shelveNo = getshelveNobyid($skuid);
            if ($totalsku_size >= $totalqty)
                $locationLimit = 1;
            else {
                // echo $totalqty ."==========". $totalsku_size;
                $locationLimit1 = $totalqty / $totalsku_size;
                $locationLimit = ceil($locationLimit1);
            }
            $newlimitcheck += $locationLimit;
            // echo $locationLimit."<br>";
            // 
            $otherMatchInventory = array('item_sku' => $skuid, 'expity_date' => $expdate, 'seller_id' => $sid, 'wh_id' => $wh_id);
            $skureturnarray2 = $this->Manifest_model->GetallstockLocation_new($sid, '', $stockArr, $locationLimit, $totalsku_size, $skuid, $otherMatchInventory);
            // print_r($skureturnarray2);
            if ($kk == 0) {
                $createlocation = array_slice($skureturnarray2, 0, $locationLimit, true);
            } else {
                $createlocation = array_slice($skureturnarray2, $locationLimit, $locationLimit, true);
            }
            // print_r($createlocation);
            // print_r($createlocation);
            //print_r($skureturnarray[$kk]);
            $totallocationarray += count($createlocation);
            if ($storage_type == 'Shelve') {
                ///  $shelveLimit=1;
                $shelveLimit = $locationLimit;
            } else {
                $shelveLimit = $locationLimit;
            }
            $shelveArr = $this->Manifest_model->GetCheckInventoryShelveNo($sid, $skuid, $shelveLimit, $totalsku_size, $shelveArr);

            foreach ($createlocation as $key555 => $val) {

                $skureturnarray[$key555]['stockLocation'] = $val['stock_location'];
                $skureturnarray[$key555]['skuid'] = $skuid;
                $skureturnarray[$key555]['sku'] = $rdata['sku'];
                $skureturnarray[$key555]['uid'] = $rdata['uniqueid'];
                $skureturnarray[$key555]['storage_type'] = $storage_type;

                $skureturnarray[$key555]['sid'] = $rdata['seller_id'];
                $skureturnarray[$key555]['capacity'] = $totalsku_size;
                $skureturnarray[$key555]['boxes'] = $locationLimit;
                $skureturnarray[$key555]['totalqty'] = $totalqty;
                $skureturnarray[$key555]['shelveNo'] = $shelveArr[$key555]['shelv_no'];
                $skureturnarray[$key555]['warehouse_name'] = $warehouse_name;
                $skureturnarray[$key555]['expire_date'] = $expire_date;
                $skureturnarray[$key555]['wh_id'] = $wh_id;
            }


            $kk++;
        }


        $sotrageTypes = $this->Manifest_model->getallStoragesTypesData();
        $warehouseArr = Getwarehouse_Dropdata();
        $reurnarray = array('result' => $skureturnarray, 'uid' => $uid, 'sid' => $sid, 'countbox' => $newlimitcheck, 'countarray' => $totallocationarray, 'warehouseArr' => $warehouseArr);
        echo json_encode($reurnarray);
    }

    public function GetSkulistForUpdateInventory() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->Manifest_model->filterUpdate(1, array("manifestid" => $_POST['uid']));
        echo json_encode($data);
    }

    public function GetSaveInventoryManifest_new() {
        $postData = json_decode(file_get_contents('php://input'), true);
        // print_r($postData);

        $skus = $postData['skus'];
        $locations = $postData['locations'];
       // echo '<pre>';
       /// print_r($locations);
        if (!empty($postData)) {
            $uid = $skus[0]['uid'];
            foreach ($skus as $key => $val) {

                $sku = $val['sku'];
                $skuid = GetallitemcheckDuplicate($sku);
                $chargeQty = $val['totalqty'];
                $sku_size = $val['capacity'];
                $item_type = getalldataitemtables($skuid, 'type');
                $qty = $val['totalqty'];

                $sid = $val['sid'];
                $first_out = getallsellerdatabyID($sid, 'first_out');
                $total_location = $val['total_location'];

                if ($qty > 0) {
                    if ($sku_size >= $qty)
                        $locationLimit = 1;
                    else {
                        $locationLimit1 = $qty / $sku_size;
                        $locationLimit = ceil($locationLimit1);
                    }
                    $updateaty = $val['totalqty'];
                    $AddQty = 0;
                    $locationLimit=count($locations);
                    for ($ii = 0; $ii < $locationLimit; $ii++) {
                        
                        if($locations[$ii]['sku']==$val['sku'])
                        {
                        if ($sku_size <= $updateaty) {
                            $AddQty = $sku_size;
                            $updateaty = $updateaty - $sku_size;
                        } else {
                            $AddQty = $updateaty;
                            $updateaty = $updateaty;
                        }
                        // echo $AddQty;
                        $shelveNo = $locations[$ii]['shelveNo'];
                        $wh_id = $locations[$ii]['wh_id'];

                        $expire_date = $locations[$ii]['expire_date'];

                        if (empty($expire_date)) {
                            $expdate = "0000-00-00";
                        } else {
                            $expdate = $expire_date;
                        }

                        $data[] = array(
                            'itype' => $item_type,
                            'item_sku' => $skuid,
                            'seller_id' => $sid,
                            'quantity' => $AddQty,
                            'update_date' => date("Y/m/d h:i:sa"),
                            'stock_location' => $locations[$ii]['stockLocation'],
                            'wh_id' => $wh_id,
                            'shelve_no' => $shelveNo,
                            'expity_date' => $expdate,
                            'super_id' => $this->session->userdata('user_details')['user_id']
                        );
                        }
                    }


                    $manifestUpdate[] = array(
                        'sku' => $sku,
                        'confirmO' => 'Y',
                        'on_hold' => 'N',
                    );
                    $chargeArr = array(
                        'uid' => $uid,
                        'sid' => $sid,
                    );
                    
                    

                    if ($item_type == 'B2B') {
                        $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($chargeArr, $locationLimit, $chargeQty, $skuid);
                    } else {
                         $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($chargeArr, $qty, $chargeQty, $skuid);
                    }
                }
            }


           // echo '<pre>';
          //  print_r($data);
           // die;
            if (!empty($data)) {
                 $result = $this->ItemInventory_model->add($data);
                 $this->Manifest_model->getupdateconfirmstatus_new($uid,$manifestUpdate);
            }
        }

        // check invonry for empty space

        echo json_encode($postData);
    }

    public function GetCheckShelveNoForAddInventory() {
        // $postData = json_decode(file_get_contents('php://input'), true);
        // $shelve = $postData['list']['shelve'];
        // $return = $this->Manifest_model->GetCheckValidShelveNoIn($shelve);
        echo json_encode(true);
    }

}

?>