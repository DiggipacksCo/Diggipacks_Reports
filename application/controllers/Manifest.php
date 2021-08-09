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
        $this->load->helper('zid');
        $this->load->model('Ccompany_model');
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
        $super_id = $this->session->userdata('user_details')['super_id'];

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

            if ($this->Manifest_model->GetallpickupRequestData_imtemCheck($rdata['uniqueid']) == true) {
                $manifestarray[$ii]['addBtnI'] = "N";
                $manifestarray[$ii]['skuid'] = getallitemskubyid($rdata['sku']);
                $manifestarray[$ii]['confirmO'] = 'N';
            } else {
                $manifestarray[$ii]['addBtnI'] = "Y";
                $manifestarray[$ii]['skuid'] = 0;
                $manifestarray[$ii]['confirmO'] = 'Y';
            }
            $manifestarray[$ii]['vehicle_type'] = type_of_vehicleFiled($rdata['vehicle_type']);
            //$manifestarray[$ii]['checkArray']=$checkArray;

            if (GetcheckConditionsAddInventory($rdata['uniqueid']) == 'N')
                $manifestarray[$ii]['error'] = 1;
            else
                $manifestarray[$ii]['error'] = 0;
            //$stockLocation[]=$this->Manifest_model->GetallstockLocation($rdata['seller_id']);
            $manifestarray[$ii]['pendingQty'] =$rdata['qtyall'] - $rdata['r_qty']-$rdata['m_qty']-$rdata['d_qty'] ;
            $manifestarray[$ii]['complatedqty'] =$rdata['r_qty'] ;
            $manifestarray[$ii]['sid'] = $rdata['seller_id'];
            if ($ii == 0)
                $seller_ids = $rdata['seller_id'];
            else
                $seller_ids .= ',' . $rdata['seller_id'];

            $manifestarray[$ii]['pstatus'] = GetpickupStatus($rdata['pstatus']);

            if ($rdata['seller_id'] > 0)
           
                $manifestarray[$ii]['seller_id'] =  getallsellerdatabyID($rdata['seller_id'], 'name',$super_id);
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



            if ($rdata['r_3pl_name'])
                $manifestarray[$ii]['r_3pl_name'] = GetCourCompanynameId($rdata['r_3pl_name'], 'company');
            else
                $manifestarray[$ii]['r_3pl_name'] = 'N/A';

            if ($rdata['r_3pl_awb'])
                $manifestarray[$ii]['r_3pl_awb'] = $rdata['r_3pl_awb'];
            else
                $manifestarray[$ii]['r_3pl_awb'] = 'N/A';

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

            $manifestarray[$ii]['editdamage'] =0;
            $manifestarray[$ii]['editmissing'] =0;


            $manifestarray[$ii]['pstatus'] = GetpickupStatus($rdata['pstatus']);
            if ($rdata['seller_id'] > 0)
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name',$rdata['super_id']);
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

    function exportExcelmanifestlist($dataEx = null, $file_name = null) {
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
    


    function updateMissingDamage() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        $id = $dataArray['id'];
        $missing_qty = $dataArray['missing_qty'];
        $damage_qty = $dataArray['damage_qty'];
        $sku = $dataArray['skuno'];

       
        if($missing_qty>0)
        {
        $code='MSI';
        $pstatus=3;
        }

        if($damage_qty>0)

        {
        $code='DI';
        $pstatus = 4;
        }
      
        $updateArray = array('missing_qty' =>  $missing_qty, 'code'=>$code,'pstatus' => $pstatus, 'damage_qty' => $damage_qty, 'id' =>$id);
        $result = $this->Manifest_model->ManifestDMUpdate($updateArray, $id);
        echo json_encode($result); die;
        if ($result == true)
            echo json_encode(array('success' => 'successfully Updated'));
        else
            echo json_encode(array('error' => 'Please Enter Valid SKU No.'));
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

        if ($assign_type == 'CC') {
            $request_return = $this->BulkForwardCompanyReady($uniqueid, $cc_id, $order_type,$dataArray);
            if (!empty($request_return['Success_msg'])) {
                $return = array('status' => "succ",'Success_msg'=>$request_return['Success_msg']);
            } else {
                //{"Error_msg":"60DC2386A8132:018239975375 Is Invalid Phone Number"}
                //$return = $request_return;
                $return = array('status' => "error",'Error_msg'=>$request_return['Error_msg']);
            }
        }
        if ($assign_type == 'D') {

            $updateArray = array('code' => 'AT', 'pstatus' => 6, 'assign_to' => $assignid);
            $result = $this->Manifest_model->Getdriverassignupdate($updateArray, $uniqueid);
            $return = array('status' => "succ");
        }
        echo json_encode($return);
    }

    
    function BulkForwardCompanyReady($uniqueid = null, $cc_id = null, $order_type = null, $dataArray = null){
        
        if(!empty($dataArray['super_id']))
        {
            $user_details['super_id']=$dataArray['super_id'];
            $this->session->set_userdata('user_details', $user_details);
            $super_id = $dataArray['super_id'];
        }else{
            $super_id= $this->session->userdata('user_details')['super_id'];
        }
        
        $CURRENT_TIME = date('H:i:s');
        $CURRENT_DATE = date('Y-m-d H:i:s');
        $dataArray['mid'] = $uniqueid;
        
        $counrierArr_table = $this->Ccompany_model->GetdeliveryCompanyUpdateQry($cc_id,$custid=0,$super_id);
        
        $c_id = $counrierArr_table['cc_id'];
        if ($counrierArr_table['type'] == 'test') {
            $user_name = $counrierArr_table['user_name_t'];
            $password = $counrierArr_table['password_t'];
            $courier_account_no = $counrierArr_table['courier_account_no_t'];
            $courier_pin_no = $counrierArr_table['courier_pin_no_t'];
            $start_awb_sequence = $counrierArr_table['start_awb_sequence_t'];
            $end_awb_sequence = $counrierArr_table['end_awb_sequence_t'];
            $company = $counrierArr_table['company'];
            $api_url = $counrierArr_table['api_url_t'];
            $create_order_url = $counrierArr_table['create_order_url'];
            $company_type = $counrierArr_table['company_type'];
            $auth_token = $counrierArr_table['auth_token_t'];
        } else {
            $user_name = $counrierArr_table['user_name'];
            $password = $counrierArr_table['password'];
            $courier_account_no = $counrierArr_table['courier_account_no'];
            $courier_pin_no = $counrierArr_table['courier_pin_no'];
            $start_awb_sequence = $counrierArr_table['start_awb_sequence'];
            $end_awb_sequence = $counrierArr_table['end_awb_sequence'];
            $company = $counrierArr_table['company'];
            $api_url = $counrierArr_table['api_url'];
            $auth_token = $counrierArr_table['auth_token'];
            $company_type = $counrierArr_table['company_type'];
            $create_order_url = $counrierArr_table['create_order_url'];
        }
        $counrierArr['user_name'] = $user_name;
        $counrierArr['password'] = $password;
        $counrierArr['courier_account_no'] = $courier_account_no;
        $counrierArr['courier_pin_no'] = $courier_pin_no;
        $counrierArr['courier_pin_no'] = $courier_pin_no;
        $counrierArr['start_awb_sequence'] = $start_awb_sequence;
        $counrierArr['end_awb_sequence'] = $end_awb_sequence;
        $counrierArr['company'] = $company;
        $counrierArr['api_url'] = $api_url;
        $counrierArr['company_type'] = $company_type;
        $counrierArr['create_order_url'] = $create_order_url;
        $counrierArr['auth_token'] = $auth_token;
//echo "<pre>";print_r($counrierArr); die;
        if (!empty($dataArray['mid'])) {
            $shipmentLoopArray = $dataArray['mid'];
            $dataArray['cc_id'] = $dataArray['cc_id'];
        } else {
            $midData = explode("\n", $dataArray['mid']);
            $shipmentLoopArray = array_unique($midData);
        }
        
        $alldetails = $this->Ccompany_model->GetMidDetailsQry(trim($dataArray['mid']),$super_id);
        $senderdetails = GetSinglesellerdata(trim($alldetails['seller_id']),$super_id);  //Sender details 
        $receiverdetails = Getselletdetails($super_id); // Receiver details

        $box_pieces1 = $alldetails['boxes'];
        $slipNo = $alldetails['uniqueid'];
        $box_pieces1 = $dataArray['boxes'];
        
       
        
        $succssArray = array();
        $ShipArr = array(
            'sender_name' => $senderdetails['company'],
            'sender_address' => $senderdetails['address'],
            'sender_phone' => $senderdetails['phone'],
            'sender_email' => $senderdetails['email'],
            'origin' => $senderdetails['city'],
            'slip_no' => $alldetails['uniqueid'],
            'mode' =>$pay_mode,
            'pay_mode' => 'CC',
            'total_cod_amt' => 0,
            'pieces' => $alldetails['boxes'],
            'status_describtion' => $alldetails['sku'],
            'weight' => $alldetails['weight'],
            'cust_id' => $alldetails['seller_id'],
            'reciever_name' => $receiverdetails[0]['name'],
            'reciever_address' => $receiverdetails[0]['address'],
            'reciever_phone' => $receiverdetails[0]['phone'],
            'reciever_email' => $receiverdetails[0]['email'],
            'destination' => $receiverdetails[0]['branch_location'],
        );
        $sellername = $ShipArr['sender_name'];
        $complete_sku= $alldetails['sku'];
        
        $pay_mode = trim($ShipArr['mode']);
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($pay_mode == 'COD') {
                $pay_mode = 'P';
                $CashOnDeliveryAmount = array("Value" => $cod_amount,
                        "CurrencyCode" => site_configTable("default_currency"));
                $services = 'CODS';
        } elseif ($pay_mode == 'CC') {
                $pay_mode = 'P';
                $CashOnDeliveryAmount = NULL;
                $services = '';
        }
        
       if ($company == 'Aymakan'){
                            $response = $this->Ccompany_model->AymakanArray($sellername, $ShipArr, $counrierArr, $Auth_token,$c_id,$box_pieces1,$complete_sku, $super_id);
                            $responseArray = json_decode($response, true);
                       
                            if (empty($responseArray['message'])) 
                            {
                                     $client_awb = $responseArray['data']['shipping']['tracking_number'];
                                     
                                    
                                    $tracking_url= $counrierArr['api_url']."bulk_awb/trackings/";
                                         
                                    $aymakanlabel= $this->Ccompany_model->Aymakan_tracking($client_awb, $tracking_url,$auth_token);
                                    $label= json_decode($aymakanlabel,TRUE);
                                      
                                    $mediaData = $label['data']['bulk_awb_url'];
                                       
                                        
                                   
                                //****************************aymakan arrival label print cURL****************************
                                $generated_pdf = file_get_contents($media_data);
                                file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************aymakan label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                 $CURRENT_TIME = date("H:i:s");
                                                             
                                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                               array_push($succssArray, $slipNo); 
                            }   
                            else{
                                  
                                    $returnArr['responseError'][] = $slipNo . ':' . $responseArray['message'].':'.json_encode($responseArray['errors']);
                                    
                            }                                    
        } 
        else if($company == "Clex"){
            $response = $this->Ccompany_model->ClexArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);

            if ($response['data'][0]['cn_id']) {
                $client_awb = $response['data'][0]['cn_id'];
                $label_url_new = clex_label_curl($Auth_token, $client_awb);
                $generated_pdf = file_get_contents($label_url_new);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                array_push($succssArray, $slipNo);
                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
            } else {
                if ($response['already_exist']) {
                    $label_url_new = clex_label_curl($Auth_token, $response['consignment_id'][0]);

                    $generated_pdf = file_get_contents($label_url_new);
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    $returnArr['Error_msg'][] = $slipNo . ':' . $response['already_exist'][0] . " " . $response['consignment_id'][0];
                } elseif ($response['origin_city'])
                    $returnArr['Error_msg'][] = $slipNo . ':' . $response['origin_city'][0];
                elseif ($response['destination_city'])
                    $returnArr['Error_msg'][] = $slipNo . ':' . $response['destination_city'][0];
                else
                    $returnArr['Error_msg'][] = $slipNo . ':' . $response['message'];
            }
        }elseif ($company == 'Esnad') {
            $esnad_awb_number = Get_esnad_awb($start_awb_sequence, $end_awb_sequence);
            //echo $esnad_awb_number; die;
            $esnad_awb_number = $esnad_awb_number - 1;
            $response = $this->Ccompany_model->EsnadArray($sellername, $ShipArr, $counrierArr, $esnad_awb_number, $complete_sku, $Auth_token, $c_id, $box_pieces1,$super_id);

            $responseArray = json_decode($response, true);

            $status = $responseArray['code'];
            if ($status == "2000" || $status == "500") {
                $error_msg = array(
                    "Error_Message " => $responseArray['msg'],
                );
                $errre_response = json_encode($error_msg);
                array_push($error_array, $slipNo . ':' . $error_response['Message']);
                $returnArr['Error_msg'][] = $slipNo . ':' . $responseArray['message'];

                $this->session->set_flashdata('errorloop', $returnArr);
            }
            if ($status == "3000") {
                $error_msg = array(
                    "Error_Message " => $responseArray['msg'],
                    "Awb_NO " => $responseArray['data'][0]['clientOrderNo'],
                    "Esnad_awb_no " => $responseArray['data'][0]['esnadAwbNo'],
                    "Esnad_awb_link" => $responseArray['data'][0]['esnadAwbPdfLink'],
                );
                $errre_response = json_encode($error_msg);
                array_push($error_array, $slipNo . ':' . $responseArray['message']);
                $returnArr['Error_msg'][] = $slipNo . ':' . $responseArray['message'];

                $this->session->set_flashdata('errorloop', $returnArr);
            }
            // echo $status;exit;

            
                
                $description = $responseArray['msg'];
                $client_awb = $responseArray['data'][0]['esnadAwbNo'];
                $success = $responseArray['success'];
                if ($success == TRUE) {
                    
                    $esnad_awb_link = $responseArray['dataObj']['labelUrl'];
                    $generated_pdf = file_get_contents($esnad_awb_link);
                    $encoded = base64_decode($generated_pdf);
                    $client_awb = $responseArray['dataObj']['trackingNo'];
                    //header('Content-Type: application/pdf');
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    
                    
                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $esnad_awb_link, $c_id);
                    

                    array_push($succssArray, $slipNo);

                    array_push($DataArray, $slipNo);

                    $insert_esnad_awb_number = array(
                        'slip_no' => $slipNo,
                        'esnad_awb_no' => $esnad_awb_number,
                        'super_id' => $this->session->userdata('user_details')['super_id']
                    );
                    
                    updateEsdadAWB($insert_esnad_awb_number);
                    $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                }
                if ($status == "1000" && $description == "SUCCESS") {
                    $esnad_awb_link = $responseArray['data'][0]['esnadAwbPdfLink'];
                    $generated_pdf = file_get_contents($esnad_awb_link);
                    $encoded = base64_decode($generated_pdf);
                    //header('Content-Type: application/pdf');
                    file_put_contents("/var/www/html/fastcoo-tech/fulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);
                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $esnad_awb_link, $c_id);

                    array_push($succssArray, $slipNo);

                    array_push($DataArray, $slipNo);

                    $insert_esnad_awb_number = array(
                        'slip_no' => $slipNo,
                        'esnad_awb_no' => $esnad_awb_number,
                        'super_id' => $this->session->userdata('user_details')['super_id']
                    );
                    updateEsdadAWB($insert_esnad_awb_number);
                    $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                }
            }elseif ($company == 'NAQEL') {
                $awb_array = $this->Ccompany_model->NaqelArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $Auth_token, $c_id, $super_id);

                $HasError = $awb_array['HasError'];
                $error_message = $awb_array['Message'];

                if ($awb_array['HasError'] !== true) {
                    $client_awb = $awb_array['WaybillNo'];
                    if (!empty($client_awb)) {
                        $user_name = $counrierArr['user_name'];
                        $password = $counrierArr['password'];
                        $xml_for_label = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                                    <soapenv:Header/>
                                    <soapenv:Body>
                                    <tem:GetWaybillSticker>
                                        <tem:clientInfo>
                                            <tem:ClientAddress>
                                                <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                                <tem:POBox>0</tem:POBox>
                                                <tem:ZipCode>0</tem:ZipCode>
                                                <tem:Fax>0</tem:Fax>
                                                <tem:FirstAddress>' . $ShipArr['sender_address'] . '</tem:FirstAddress>
                                                <tem:Location>' . $sender_city . '</tem:Location>
                                                <tem:CountryCode>KSA</tem:CountryCode>
                                                <tem:CityCode>RUH</tem:CityCode>
                                            </tem:ClientAddress>
                                            <tem:ClientContact>
                                                <tem:Name>' . $ShipArr['sender_name'] . '</tem:Name>
                                                <tem:Email>' . $ShipArr['sender_email'] . '</tem:Email>
                                                <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                                <tem:MobileNo>' . $ShipArr['sender_phone'] . '</tem:MobileNo>
                                            </tem:ClientContact>
                                            <tem:ClientID>' . $user_name . '</tem:ClientID>
                                            <tem:Password>' . $password . '</tem:Password>
                                            <tem:Version>9.0</tem:Version>
                                        </tem:clientInfo>
                                        <tem:WaybillNo>' . $client_awb . '</tem:WaybillNo>
                                        <tem:StickerSize>FourMSixthInches</tem:StickerSize>
                                    </tem:GetWaybillSticker>
                                    </soapenv:Body>
                                    </soapenv:Envelope>';

                        $headers = array(
                            "Content-type: text/xml",
                            "Content-length: " . strlen($xml_for_label),
                        );

                        $url = $counrierArr['api_url'] . "?op=GetWaybillSticker";

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_for_label);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        $response = trim(curl_exec($ch));

                        curl_close($ch);

                        $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $response));
                        $mediaData = $xml_data->Body->GetWaybillStickerResponse->GetWaybillStickerResult[0];

                        if (!empty($mediaData)) {
                            $pdf_label = json_decode(json_encode((array) $mediaData), TRUE);
                            header('Content-Type: application/pdf');
                            $img = base64_decode($pdf_label[0]);
                            $savefolder = $img;
                            file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);
                            //*********NAQEL arrival label print cURL****************************

                            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                            //****************NAQEL label print cURL****************************
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");
                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            array_push($succssArray, $slipNo);
                            $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                        }
                    } else {
                        $returnArr['Error_msg'][] = $slipNo . ':' . $awb_array['Message'];
                    }
            }
        }elseif ($company == 'Saee') {
            $response = $this->Ccompany_model->SaeeArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$super_id);
            $safe_response = $response;

            if ($safe_response['success'] == 'true') {
                $client_awb = $safe_response['waybill'];
                //****************************Saee arrival label print cURL****************************
                $API_URL = $counrierArr['api_url'];
                $label_response = saee_label_curl($client_awb, $Auth_token, $API_URL);
                file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                //****************************Saee label print cURL****************************
                $CURRENT_DATE = date("Y-m-d H:i:s");
                $CURRENT_TIME = date("H:i:s");

                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                array_push($succssArray, $slipNo);
                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
            }else{
                 $returnArr['Error_msg'][] = $slipNo . ':' . $safe_response['error'];
            }
        }else if ($company == 'Aramex') {
            $params = $this->Ccompany_model->AramexArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1, $super_id);
            $dataJson = json_encode($params);
            // echo " Datajson ". $dataJson; die;                                        
            $headers = array("Content-type:application/json");
            $url = $api_url;
            $awb_array = $this->Ccompany_model->AxamexCurl($url, $headers, $dataJson, $c_id, $ShipArr);
            $check_error = $awb_array['HasErrors'];

            //print "<pre>"; print_r($awb_array);die;
            if ($check_error == 'true') {

                if (empty($awb_array['Shipments'])) {
                    $error_response = $awb_array['Notifications']['Notification'];
                    $error_response = json_encode($error_response);
                    array_push($error_array, $slipNo . ':' . $error_response);
                    $returnArr['Error_msg'][] = $slipNo . ':' . $error_response;
                } else {
                    if ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'] == '') {
                        foreach ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification'] as $error_response) {
                            array_push($error_array, $slipNo . ':' . $error_response['Message']);
                            $returnArr['responseError'][] = $slipNo . ':' . $error_response['Message'];
                        }
                    } else {
                        $error_response = $awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'];
                        $error_response = json_encode($error_response);
                        array_push($error_array, $slipNo . ':' . $error_response);
                        $returnArr['Error_msg'][] = $slipNo . ':' . $error_response;
                    }
                }
                array_push($error_msg, $returnArr);
            } else {
                $main_result = $awb_array['Shipments']['ProcessedShipment'];

                $Check_inner_error = $main_result['HasErrors'];
                if ($Check_inner_error == 'false') {
                    $client_awb = $main_result['ID'];
                    $awb_label = $main_result['ShipmentLabel']['LabelURL'];

                    $generated_pdf = file_get_contents($awb_label);
                    $encoded = base64_decode($generated_pdf);
                    header('Content-Type: application/pdf');
                    file_put_contents("/var/www/html/fastcoo-tech/demofulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);

                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);

                    array_push($succssArray, $slipNo);
                    $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                }
            }
        }elseif ($company == 'Ajeek') {

            $response = $this->Ccompany_model->AjeekArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);
            if ($response['contents']['order_id']) {
                $response['contents']['order_id'];
                $Auth_token = $counrierArr['auth_token'];
                $vendor_id = $counrierArr['courier_pin_no'];
                $client_awb = $response['contents']['order_id'];

                //****************************Saee arrival label print cURL****************************
                $label_response = ajeek_label_curl($Auth_token, $client_awb, $vendor_id);

                file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                //****************************Saee label print cURL****************************
                $CURRENT_DATE = date("Y-m-d H:i:s");
                $CURRENT_TIME = date("H:i:s");

                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                array_push($succssArray, $slipNo);
                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
            } else {

                $returnArr['Error_msg'][] = $slipNo . ':' . $response['description'];
            }
        }elseif ($company == 'Barqfleet') {
            $response_ww = $this->Ccompany_model->BarqfleethArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $c_id, $box_pieces1, $super_id);
            $response_array = json_decode($response_ww, TRUE);
            if ($response_array['code'] != '') {
                $returnArr['Error_msg'][] = $slipNo . ':' . $response_array['message'];
            } else {
                $Authorization = $counrierArr['auth_token'];
                $request_url_label = $counrierArr['api_url'] . "/orders/airwaybill/" . $response_array['id'];
                $headers = array("Content-type:application/json");
                $firsthead = array(
                    "Content-Type: application/json",
                    "Authorization: " . $Authorization,
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request_url_label);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $firsthead);
                $response_label = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);
                $client_awb = $response_array['tracking_no'];
                $slip_no = $response_array['merchant_order_id'];
                $barq_order_id = $response_array['id'];
                $CURRENT_DATE = date("Y-m-d H:i:s");
                $CURRENT_TIME = date("H:i:s");
                $generated_pdf = file_get_contents($response_label);
                file_put_contents("assets/all_labels/$slipNo.pdf", $response_label);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                //****************************makdoom label print cURL****************************

                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id, $barq_order_id);
                array_push($succssArray, $slipNo);
                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
            }
        }elseif ($company == 'Labaih') {
            $response = $this->Ccompany_model->LabaihArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);

            if ($response['status'] == 200) {
                $client_awb = $response['consignmentNo'];
                $shipmentLabel_url = $response['shipmentLabel'];

                $generated_pdf = file_get_contents($shipmentLabel_url);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                array_push($succssArray, $slipNo);
                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
            } else {
                $returnArr['Error_msg'][] = $slipNo . ':' . $response['message'];
                //$returnArr['responseError'][] = $slipNo . ':' . $response['invalid_parameters'][0];
            }
        }elseif ($company == 'Makhdoom') {
            
            $Auth_response = MakdoomArrival_Auth_cURL($counrierArr);

            $responseArray = json_decode($Auth_response, true);
            
            $Auth_token = $responseArray['data']['id_token'];

            $response = $this->Ccompany_model->MakdoonArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token, $c_id, $box_pieces1, $super_id);
            //print "<pre>"; print_r($response);die;
            $safe_response = json_decode($response, true);


            if ($safe_response['status'] == 'success') {
                $safe_arrival_ID = $safe_response['data']['id'];
                $client_awb = $safe_response['data']['order_number'];

                //****************************makdoom arrival label print cURL****************************

                $label_response = makdoom_label_curl($client_awb, $Auth_token);
                $safe_label_response = json_decode($label_response, true);
                $safe_Label = $safe_label_response['data']['value'];

                $generated_pdf = file_get_contents($safe_Label);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                //echo $fastcoolabel ;
                //****************************makdoom label print cURL****************************
                $CURRENT_DATE = date("Y-m-d H:i:s");
                $CURRENT_TIME = date("H:i:s");

                //$Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id);
                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                //$Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                array_push($succssArray, $slipNo);
                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
            }else{
                $returnArr['Error_msg'][] = $slipNo . ':' . $safe_response['message'];
            }
        }elseif($company=='Safearrival'){
                        
                $charge_items=array();
                 $Auth_response = SafeArrival_Auth_cURL($counrierArr);  
                
                $responseArray = json_decode($Auth_response, true);
                $Auth_token = $responseArray['data']['id_token'];
                
                $response = $this->Ccompany_model->SafeArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1, $super_id);
               // print "<pre>"; print_r($response);die;
                $safe_response = json_decode($response, true);                     
               //print "<pre>"; print_r($safe_response);die;
                if ($safe_response['status'] == 'success') {
                    $safe_arrival_ID = $safe_response['data']['id'];
                    $client_awb = $safe_response['data']['order_number'];

                    //****************************safe arrival label print cURL****************************
                    $label_response = safearrival_label_curl($safe_arrival_ID, $Auth_token,$counrierArr['api_url']);                       
                    $safe_label_response = json_decode($label_response, true);
                    $safe_Label = $safe_label_response['data']['value'];

                    $generated_pdf = file_get_contents($safe_Label);
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    //****************************safe arrival label print cURL****************************
                    //$Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData);
                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                    
                    
                    //array_push($succssArray, $slipNo);

                    array_push($dataArray, $slipNo);
                    $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                }
                else if($safe_response['status']=='error' || $safe_response['status']==400){
                    $returnArr['Error_msg'][] = $slipNo . ':' . $safe_response['message'];
                }
                
            }elseif($company == 'Saudi Post'){
                $response = $this->Ccompany_model->SPArray($sellername, $ShipArr, $counrierArr, $complete_sku,$Auth_token,$c_id,$box_pieces1,$super_id);
                
                $response = json_decode($response, true);
                                
                if($response['Items'][0]['Message']=='Success'){
                    $client_awb = $response['Items'][0]['Barcode'];
                    
                  
                    $fastcoolabel='SP';
                    //$Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData);
                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                    

                    header('Content-Type: application/pdf');
                    $lableSp=   file_get_contents(base_url().'awbPrint1/'.$slipNo );
                    file_put_contents("assets/all_labels/$slipNo.pdf", $lableSp);
                 
                    array_push($succssArray, $slipNo);
                    $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                }else{
                    $errre_response = $response['Items'][0]['Message'];
                    if($errre_response==''){
                        $errre_response = $response['Message'];
                    }
                    $returnArr['Error_msg'][] = $slipNo . ':' . $errre_response;
                }
            }elseif ($company == 'Shipadelivery') {

                $response = $this->Ccompany_model->ShipadeliveryArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id,$super_id);

                $response_array = json_decode($response, true);
                if (empty($response_array)) {
                    $returnArr['Error_msg'][] = $slipNo . ':' . 'Receiver City Empty ';
                } else {
                    $faultFlag = false;
                     if(isset($response_array['fault']) && !empty($response_array['fault'])){
                         $faultFlag = true;
                         
                     }
                    if ($response_array[0]['code'] == 0 && $faultFlag == false) {
                        $client_awb = $response_array[0]['deliveryInfo']['reference'];

                        $responsepie = $this->Ccompany_model->ShipaDelupdatecURL($counrierArr, $ShipArr, $client_awb, $box_pieces1);
                        $responsepieces = json_decode($responsepie, true);
                        //  echo "<pre>"; print_r($responsepieces); // die; 

                        if ($responsepieces['status'] == 'Success') {
                            $shipaLabel = $this->Ccompany_model->ShipaDelLabelcURL($counrierArr, $client_awb);

                            header('Content-Type: application/pdf');

                            file_put_contents("assets/all_labels/$slipNo.pdf", $shipaLabel);
                            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            array_push($succssArray, $slipNo);
                            $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                        } else {

                            $returnArr['Error_msg'][] = $slipNo . ':' . $responsepieces['action'];
                        }
                    } else {
                        if($faultFlag == true){
                            $returnArr['Error_msg'][] = $slipNo . ':' . $response_array['fault']['faultstring'];
                        }else{
                            $returnArr['Error_msg'][] = $slipNo . ':' . $response_array['info'];
                        }
                    }
                }
        }elseif($company == 'Shipsy'){
                
                $response = $this->Ccompany_model->ShipsyArray($sellername, $ShipArr, $counrierArr, $Auth_token, $box_pieces1,$c_id,$super_id);
                
                $response_array = json_decode($response, true);
                
                if($response_array['data'][0]['success']==1){
                    $client_awb = $response_array['data'][0]['reference_number'];
                    
                    //****************************Shipsy label print cURL****************************
                    
                    $shipsyLabel = $this->Ccompany_model->ShipsyLabelcURL($counrierArr, $client_awb);
                    
                    $mediaData = $shipsyLabel;
                   
                    file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                     $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                    array_push($succssArray, $slipNo);
                    $returnArr['Success_msg'][] = 'Successfully Assigned.';
                }else{
                    
                    $returnArr['Error_msg'][] = $slipNo . ':' . $response_array['data'][0]['message'];
                }
            }elseif ($company == 'Smsa') {

                $response = $this->Ccompany_model->SMSAArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id,$super_id);
                
                $xml2 = new SimpleXMLElement($response);
                $again = $xml2;
                $a = array("qwb" => $again);

                $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);

                if (preg_match('/\bFailed\b/', $complicated)) {
                    $returnArr['Error_msg'][] = $slipNo . ':' . $complicated;
                } else {
                    if ($response != 'Bad Request') {
                        $xml2 = new SimpleXMLElement($response);
                        //echo "<pre>";
                        //print_r($xml2);
                        $again = $xml2;
                        $a = array("qwb" => $again);

                        $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
                        //print_r($complicated); exit;   
                        $abc = array("qwber" => $complicated);

                        $client_awb = (implode(" ", $abc));
                        //print_r($abc);
                        $newRes = explode('#', $client_awb);


                        if (!empty($newRes[1])) {
                            $client_awb = trim($newRes[1]);
                        }

                        $printLabel = $this->Ccompany_model->PrintLabel($client_awb, $counrierArr['$auth_token'], $counrierArr['api_url']);


                        $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $printLabel));
                        $mediaData = $xml_data->Body->getPDFResponse->getPDFResult[0];
                        header('Content-Type: application/pdf');
                        $img = base64_decode($mediaData);

                        if (!empty($mediaData)) {
                            $savefolder = $img;

                            file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);

                            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);

                            array_push($succssArray, $slipNo);
                            $returnArr['Success_msg'][] = 'Successfully Assigned.';
                        } else {
                            array_push($error_array, $booking_id . ':' . $db);
                        }
                    } else {
                        $returnArr['Error_msg'][] = $slipNo . ':' . $response;
                    }
            }
        }else if($company=='Thabit' )
            {   
                $charge_items=array();
                $Auth_response = Thabit_Auth_cURL($counrierArr);
                $responseArray = json_decode($Auth_response, true);                      
                $Auth_token = $responseArray['data']['id_token'];
                
                $thabit_response = $this->Ccompany_model->ThabitArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id, $box_pieces1, $super_id);

                if ($thabit_response['status'] == 'success') 
                {
                    $thabit_order_ID = $thabit_response['data']['id'];
                    $client_awb = $thabit_response['data']['order_number'];

                    //**************************** Thabit label print cURL****************************

                        $label_response = thabit_label_curl($thabit_order_ID, $Auth_token,$counrierArr['api_url']); 
                        $safe_label_response = json_decode($label_response, true);
                        $safe_Label = $safe_label_response['data']['value'];
                        
                        $generated_pdf = file_get_contents($safe_Label);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
              
                    //**************************** Thabit label print cURL****************************
                    							
                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                    							
                    array_push($succssArray, $slipNo);
                    $returnArr['Success_msg'] = 'Successfully Assigned.';
                                     
                }
                else if($thabit_response['status']=='error' || $thabit_response['status'] == 400)
                {
                    $returnArr['Error_msg'][] = $slipNo . ':' . $thabit_response['message'];
                }
            }elseif ($company == 'Zajil') {
                    $response = $this->Ccompany_model->ZajilArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$super_id);
                    //print "<pre>"; print_r($response);die;
                    if (!empty($response['data'])) {
                        $success = $response['data'][0]['success'];
                        if ($response['status'] == 'OK' && $success == 1) {
                            $client_awb = $response['data'][0]['reference_number'];

                            $label_response = zajil_label_curl($auth_token, $client_awb);
                            header("Content-type:application/pdf");
                            file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                            $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            
                            array_push($succssArray, $slipNo);
                            $returnArr['Success_msg'][] = 'Successfully Assigned.';
                        } else {
                            $returnArr['Error_msg'][] = $slipNo . ':' . $response['data'][0]['reason'];
                        }
                    } else {
                        $returnArr['Error_msg'][] = $slipNo . ':' . "invalid details";
                    }
            }elseif ($company== 'Beez'){
                            //print "<pre>"; print_r($sku_data);die;
                            $response = $this->Ccompany_model->BeezArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$sku_data,$super_id);  
                            if(isset($response['Message']) && !empty($response['Message'])){
                                $returnArr['Error_msg'][] = $slipNo . ':' . $response['Message'];
                            }else{
                                $client_awb = $response;
                                $url = 'https://login.beezerp.com/label/pdf/?t='.$client_awb;
                                $generated_pdf = file_get_contents($url); 
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                
                                $beezlabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $beezlabel, $c_id);
                                $returnArr['Success_msg'][] = $slipNo.': Successfully Assigned.';
                                array_push($succssArray, $slipNo);
                            }
            }elseif ($company == 'GLT'){

                        $responseArray = $this->Ccompany_model->GLTArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku,$super_id);
                        $successres = $responseArray['data']['orders'][0]['status'];
                        $error_status = $responseArray['data']['orders'][0]['msg'];

                            if (!empty($successres) && $successres == 'success')
                            {

                                $client_awb = $responseArray['data']['orders'][0]['orderTrackingNumber'];
                                $innser_status = $responseArray['data']['orders'][0]['status'];
                                                         

                                $GltLabel = $this->Ccompany_model->GLT_label($client_awb, $counrierArr, $auth_token);
                                    
                                 file_put_contents("assets/all_labels/$slipNo.pdf", $GltLabel);                            
                                 $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';


                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                                $returnArr['Success_msg'][] = $slipNo.': Successfully Assigned.';
                                array_push($succssArray, $slipNo);
                            }
                            
                            else{
                                $returnArr['Error_msg'][] = $slipNo . ':' .$error_status;
                            }
            }elseif ($company == 'KwickBox'){
                
                $responseArray = $this->Ccompany_model->KwickBoxArray($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku,$super_id);

                $successres = $responseArray['number'];                        
                $error_status = $responseArray['field.'][0];

                //echo $error_status; die;

                if (!empty($successres))
                {

                    $client_awb = $responseArray['number'];
                    $media_data = $responseArray['labelUrl'];                               


                    if (file_put_contents( "assets/all_labels/$slipNo.pdf",file_get_contents($media_data))){
                        
                        $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                        $CURRENT_DATE = date("Y-m-d H:i:s");
                        $CURRENT_TIME = date("H:i:s");

                        $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                        $returnArr['Success_msg'][] = $slipNo.': Successfully Assigned.';
                        array_push($succssArray, $slipNo);
                    }
                }
                else
                {
                    $returnArr['Error_msg'][] = $slipNo . ':' .$error_status;
                }                  
            }
            elseif($company == 'DHL JONES') {
                if(!empty($counrierArr)) { 
                            $api_response = $this->Ccompany_model->DhlJonesArray($sellername,$ShipArr, $counrierArr,$token, $complete_sku, $box_pieces1,$c_id,$super_id);
                            
                            if($api_response['error'] == FALSE) {
                                 $client_awb = $api_response['data']['ShipmentResponse']['ShipmentIdentificationNumber'];
                                 $lableData = $api_response['data']['ShipmentResponse']['Documents'][0]['Document'];
                                 //print "<pre>"; print_r($lableData);die;
                                 $dhlLabel = '';
                                 
                                if (!empty($lableData['DocumentImage'])) {
                                    $encoded = base64_decode($lableData['DocumentImage']);
                                     header('Content-Type: application/pdf');
                                     file_put_contents("assets/all_labels/$slipNo.pdf", $encoded);

                                    $dhlLabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                }

                                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $dhlLabel, $c_id);

                                array_push($succssArray, $slipNo);
                                $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';                                       
                                 
                            } else {                                       
                                $returnArr['Error_msg'] = $slipNo . ':' .$api_response['data']['ShipmentResponse']['Notification'][0]['Message'];
                            }
                } else {
                   $returnArr['Error_msg'][] = $slipNo . ':Token Not Genrated'; 
                }                       
            } 

            elseif($company == 'Tamex'){
                            $responseArray = $this->Ccompany_model->tamexArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode,$c_id,$box_pieces1,$super_id);
                         
                            if ($responseArray['code'] != 0 || empty($responseArray)) {
                                array_push($error_array, $slipNo . ':' . $responseArray['data']);
                                $returnArr['Error_msg'][] = $slipNo . ':' . $responseArray['data'];
                            } elseif ($responseArray['code'] == 0) {

                                $client_awb = $responseArray['tmxAWB'];
                                $API_URL= $counrierArr['api_url'].'print';
                                
                                $generated_pdf = Tamex_label($client_awb, $counrierArr['auth_token'],$API_URL);
                              
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");
                               
                                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                                


                                $details = 'Forwarded to ' . $ClientArr['company'];
                                

                                $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' forwarded to TAMEX';

                                array_push($DataArray, $slipNo);
                            }
            }elseif ($company== 'Fetchr'){ 
                     
                               $responseData = $this->Ccompany_model->fetchrArray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1,$super_id);
                               if($responseData['data'][0]['status'] == 'success')
                                {
                                    $client_awb = $responseData['data'][0]['tracking_no'];
                                    
                                    $label = "https://s3-eu-west-1.amazonaws.com/cms-dhl-pdf-stage-1/label6x4_".$client_awb.".pdf";
                                    
                                  
                                    $generated_pdf = file_get_contents($label);
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf );
                                    
                                   $fetchrlabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                    $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");
                                    $comment = $responseData['message'];
                                   $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fetchrlabel, $c_id);
                                    $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' forwarded to Fetchr';

                                    $this->session->set_flashdata('msg', $returnArr);
                                    array_push($succssArray, $slipNo);
                                 }else{

                                     $returnArr['Error_msg'][] = $slipNo . ':' . $responseData['message'];
                                 } 
            }elseif ($company== 'iMile'){
                            //print "<pre>"; print_r($sku_data);die;
                            $auth_token = $this->Ccompany_model->iMileToken($counrierArr);
                            
                            if(empty($auth_token)){
                                $returnArr['Error_msg'][] = $slipNo . ': Token not genrated';
                            }else{
                                $response = $this->Ccompany_model->iMileArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$auth_token,$super_id);  
                                if($response['code'] == 200  && $response['message'] == 'success'){
                                    $client_awb = $response['data']['expressNo'];
                                    $pdf_encoded_base64 = $response['data']['imileAwb'];
                                    $pdf_file = base64_decode($pdf_encoded_base64);

                                    file_put_contents("assets/all_labels/".$slipNo.".pdf", $pdf_file);
                                    $imile_label = base_url() . "assets/all_labels/$slipNo.pdf";
                                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $imile_label, $c_id);
                                    $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to iMile.';
                                    array_push($succssArray, $slipNo);
                                    
                                }else if($response['code'] == 30001){
                                    $returnArr['Error_msg'][] = $slipNo . ': Customer order number repeated error code';
                                }else{
                                    $returnArr['Error_msg'][] = $slipNo . ':' . $response['message'];
                                }
                                                   
                            }
            }
            elseif ($company == 'Wadha'){
                        $counrierArr['user_name'] = $user_name;
                        $counrierArr['password'] = $password;
                        $counrierArr['api_url'] =$api_url;
                       $Auth_token=$this->Ccompany_model->Wadha_auth($user_name,$password,$api_url); 
                      
                        $responseArray = $this->Ccompany_model->WadhaArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id);  
                                            
                        $successres = $responseArray['status'];                          
                        
                         $error_status = $responseArray['message'];

                        if (!empty($successres) && $successres == 'success')
                        {

                            $client_awb = $responseArray['data']['order_number'];
                             $WadhaLabel = $this->Ccompany_model->Wadha_label($client_awb, $counrierArr, $Auth_token);
                              $label= json_decode($WadhaLabel,TRUE);
                              $media_data = $label['data']['value'];                               

                             $generated_pdf = file_get_contents($media_data);
                             file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                             $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to Wadha.';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['Error_msg'][] = $slipNo . ':' .$error_status;
                        }
                    
            } elseif ($company == 'FDA') { 
                $Auth_token=$this->Ccompany_model->FDA_auth($counrierArr); 
                $responseArray = $this->Ccompany_model->FDAArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id, $complete_sku); 
                   
                $successres = $responseArray['status'];                          
                $error_status = $responseArray['message'];

                if (!empty($successres) && $successres == 'success')
                {
                   $client_awb = $responseArray['data']['order_number'];
                    $FDALabel = $this->Ccompany_model->FDA_label($client_awb, $counrierArr, $Auth_token);
                    $label= json_decode($FDALabel,TRUE);
                    $media_data = $label['data']['value'];                               
                    $generated_pdf = file_get_contents($media_data);
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf'; 

                    $CURRENT_DATE = date("Y-m-d H:i:s");
                    $CURRENT_TIME = date("H:i:s");                              
                    $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                    $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to FDA.';
                    array_push($succssArray, $slipNo);
                }                            
                else
                {
                    $returnArr['Error_msg'][] = $slipNo . ':' .$error_status;
                }                    
            }

            elseif ($company == 'MMCCO')
                    {
                       // print_r($counrierArr);die;
                        $Auth_token=$this->Ccompany_model->MMCCO_auth($counrierArr['user_name'],$counrierArr['password'],$counrierArr['api_url']);
                      
                        $responseArray = $this->Ccompany_model->MMCCOArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$super_id);  
                        //   echo "<br><br><pre>";
                          // print_r($responseArray); DIE;

                        $successres = $responseArray['status'];                         
                        
                         $error_status = $responseArray['message'];

                        if (!empty($successres) && $successres == 'success')
                        {

                            $client_awb = $responseArray['data']['order_number'];
                            $MMCCOLabel = $this->Ccompany_model->MMCCO_label($client_awb, $counrierArr, $Auth_token);
                            $label= json_decode($MMCCOLabel,TRUE);
                            $media_data = $label['data']['value'];                               

                            $generated_pdf = file_get_contents($media_data);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' Data updated successfully.';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['Error_msg'][] = $slipNo . ':' .$error_status;
                        }
                    
            } elseif ($company == 'FedEX')
                    {

                        $responseArray = $this->Ccompany_model->FedEX($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1,$c_id,$super_id);
                       //  echo "<pre>" ; print_r($responseArray); //die;
                        $successres = $responseArray['Code'];
                        $error_status = $responseArray['description'];

                            if (!empty($successres) && $successres == 1)
                            {
                                $client_awb = $responseArray['AirwayBillNumber'];
                                 
                                $label_response = $this->Ccompany_model->FedEX_label($client_awb, $counrierArr,$ShipArr);
                                $pdf_encoded_base64 = $label_response['ReportDoc'];
                                $pdf_file = base64_decode($pdf_encoded_base64);
                               
                                file_put_contents("assets/all_labels/".$slipNo.".pdf", $pdf_file);
                                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                                $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' Data updated successfully.';
                                array_push($succssArray, $slipNo);
                        }                            
                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }
                    elseif ($company== 'MomentsKsa')
                       {
                        
                        $Auth_token= $this->Ccompany_model->Moments_auth($counrierArr); 
                       
                        $responseArray = $this->Ccompany_model->MomentsArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$complete_sku,$super_id);  
                        
                        $successres = $responseArray['errors'];                         
                        
                        $error_status = $responseArray['message'];

                        if (empty($successres))
                        {

                            $client_awb = $responseArray['TrackingNumber'];
                            $MomentLabel = $responseArray['printLableUrl'];
                             
                            $generated_pdf = file_get_contents($MomentLabel);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' Data updated successfully.';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }
                    elseif ($company== 'Postagexp')
                       {
                        
                        $Auth_token=$this->Ccompany_model->Postagexp_auth($counrierArr); 
                      
                        $responseArray = $this->Ccompany_model->PostagexpArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$complete_sku,$super_id); 
                        
                        $successres = $responseArray['errors'];                         
                        $error_status = $responseArray['message'];

                        if (empty($successres))
                        {

                            $client_awb = $responseArray['TrackingNumber'];
                            $PostagexpLabel = $responseArray['printLable'];
                             
                            $generated_pdf = file_get_contents($PostagexpLabel);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            
                            array_push($succssArray, $slipNo);
                            $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' Data updated successfully.';
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }

                    elseif ($company == 'SLS'){   
                        $responseArray = $this->Ccompany_model->SLSArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1,$c_id, $super_id);
                       //  echo "<pre>" ; print_r($responseArray); //die;
                        $successres = $responseArray['status'];
                        $error_status = json_encode($responseArray);

                            if (!empty($successres) && $successres == 1)
                            {
                                $client_awb = $responseArray['tracking_number'];
                                $SLSLabel = $this->Ccompany_model->SLS_label($client_awb, $counrierArr);
                                   
                                file_put_contents("assets/all_labels/$slipNo.pdf", $SLSLabel);                            
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                                $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to SLS.';
                                array_push($succssArray, $slipNo);
                            }
                            
                            else
                            {
                                $returnArr['Error_msg'][] = $slipNo . ':' .$error_status;
                            }
                        
            }elseif($company == 'Bosta'){
                    $tokenResponse =  $this->Ccompany_model->Bosta_token_api($counrierArr);
                    if($tokenResponse['success'] === true){
                            $token = $tokenResponse['token'];
                            $api_response = $this->Ccompany_model->BostaArray($sellername, $ShipArr, $counrierArr,$token, $complete_sku, $box_pieces1,$c_id,$super_id);
                            if($api_response['error'] == FALSE){
                                 $client_awb = $api_response['data']['_id'];
                                 $lableInfo =  $this->Ccompany_model->Bosta_Label_api($counrierArr, $token,$client_awb);
                                 $bostaLabel = '';
                                 if(!empty($lableInfo['data'])){
                                    $encoded = base64_decode($lableInfo['data']);
                                     header('Content-Type: application/pdf');
                                     file_put_contents("assets/all_labels/$slipNo.pdf", $encoded);

                                    $bostaLabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                 }
                                 
                                 $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $bostaLabel, $c_id);
                                 array_push($succssArray, $slipNo);
                                 $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                                
                            }else{
                                $returnArr['Error_msg'] = $slipNo . ':' .$api_response['data']['message'];
                            }
                                    
                    }else{
                       $returnArr['Error_msg'][] = $slipNo . ':Token Not Genrated'; 
                    }
            
            } elseif ($company== 'MICGO') { 
                
                            $Auth_token = $this->Ccompany_model->MICGO_AUTH($counrierArr);                           

                            $responseArray = $this->Ccompany_model->MICGOarray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$Auth_token,$super_id); 

                            $successres = $responseArray['error'];                        
                            $error_status = $responseArray['message'];
                        if (empty($successres))
                        {

                            $client_awb = $responseArray['shipments'][0]['waybill'];
                            $Label = $responseArray['shipments'][0]['shippingLabelUrl'];
                             
                            $generated_pdf = file_get_contents($Label);
                            
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            
                            
                            $micGoLabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");
                            $comment = 'MicGo Forwarding';
                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $micGoLabel, $c_id);
                            array_push($succssArray, $slipNo);                          
                            $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                        } else {
                            $returnArr['Error_msg'][] = $slipNo . ':' .$error_status;
                        }
                    
                    } elseif ($company_type == 'F') { // for all fastcoo clients treat as a CC 
            
                if ($company=='Ejack' ) 
                        {
                        $response = $this->Ccompany_model->Ejack($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$super_id);
                        $response = json_decode($response, true);
                        if($response['error']=='')
                        {
                            $generated_pdf = file_get_contents($response['awb_print_url']);                                
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                            $client_awb = $response['awb'];

                            $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                            $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                            array_push($succssArray, $slipNo);
                            $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                        } else {
                            $returnArr['Error_msg'][] = $slipNo . ':' . $response['refrence_id'];
                        }

                }

                else if ($company=='Emdad' )
                {  
                    
                    $response = $this->Ccompany_model->EmdadArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1, $super_id);
                    $response = json_decode($response, true);
                    
                    $labelUrl = $response['awb_print_url'];
                    if($response['error']=='' && !empty($labelUrl))
                    {
                        $generated_pdf = file_get_contents($response['awb_print_url']);                                
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                        $client_awb = $response['awb'];

                        $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                        $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                        array_push($succssArray, $slipNo);
                         $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                    } else {
                        if(isset($response['Reciever_city']) && !empty($response['Reciever_city'])){
                            $error = $response['Reciever_city'];
                        }else{
                            $error = $response['refrence_id'];
                        }
                        $returnArr['Error_msg'][] = $slipNo . ':' . $error;
                    }

                }else{
                    $response = $this->Ccompany_model->fastcooArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token, $c_id, $box_pieces1 ,$super_id);
                    $responseArray = json_decode($response, true);

                    if ($responseArray['status'] == 200) {
                        $client_awb = $responseArray['awb_no'];
                        $mediaData = $responseArray['label_print'];
                        //****************************fastcoo arrival label print cURL****************************
                        file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        //****************************fastcoo label print cURL****************************
                        $CURRENT_DATE = date("Y-m-d H:i:s");
                        $CURRENT_TIME = date("H:i:s");

                        $Update_data = $this->Ccompany_model->Update_Manifest_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id);
                        array_push($succssArray, $slipNo);
                         $returnArr['Success_msg'][] = $slipNo . ':Successfully Assigned';
                    } else {
                        
                        $returnArr['Error_msg'][] = $slipNo . ':Already Exist' ;
                    }
            }
        }
        return $returnArr;   
    }
    
    
    
    function getupdateassign_return() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        //print "<pre>"; print_r($_POST);die;
        $dataArray = $_POST['singleArr'];
        $itemData = $_POST['itemdata'];
        $uniqueid = strtoupper(uniqid());
        $assignid = $dataArray['assignid'];
        $assign_type = $dataArray['assign_type'];
        $order_type = $dataArray['order_type'];
        $cc_id = $dataArray['cc_id'];






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
           
            $request_return = $this->BulkForwardCompanyReady_Return($uniqueid, $cc_id, $order_type,$dataArray,$itemData);

            
           if (!empty($request_return['Success_msg'])) 
           {
                $this->Manifest_model->GetUpdateDamageInventory($damageorderUpdate);
                $return = array('status' => "succ","Success_msg"=>$request_return['Success_msg']);
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
            $updateArray[$key]['received_qty'] =  $val['scan'];
            $updateArray[$key]['id'] =  $val['o_id'];
            
           
            
        }

          //print_r($updateArray); die;
       
        if (!empty($updateArray)) {
          
            
            $this->Manifest_model->GetManifestUpdateDamageMissiing($updateArray);
            return true;
        }

        echo json_encode($return);
    }

    public function Getnewrequestmanifest() {
        //echo "sssssss"; die;
        $this->db->query("update pickup_request set seen=1 where super_id='" . $this->session->userdata('user_details')['super_id'] . "' and seen=0");
        $this->load->view('manifest/newmanifestrequest');
    }

    public function getpickuplistmanifest() {
        $this->load->view('manifest/pickuplist');
    }

    function GetnewmanifestreqShow() {
        $this->load->model('User_model');
        $assignuser = $this->User_model->userDropval(9);
        $courierData = GetCourierCompanyDrop();
        $super_id = $this->session->userdata('user_details')['super_id']; 



        $_POST = json_decode(file_get_contents('php://input'), true);
        $from = $_POST['from'];
        $to = $_POST['to'];
        $page_no = $_POST['page_no'];
        $seller_id = $_POST['seller_id'];
        $manifestid = $_POST['manifestid'];
        $sort_list = $_POST['sort_list'];
        $filterarray = array('seller_id' => $seller_id, 'manifestid' => $manifestid, 'sort_list' => $sort_list);

        $shipments = $this->Manifest_model->getnewgenratemanifestdata($to, $from, $page_no, $filterarray);
       // echo json_encode($shipments); die;
        $manifestarray = $shipments['result'];
        $ii = 0;
        $seller_ids = "";
        foreach ($shipments['result'] as $rdata) {
            $boxType = '';
            switch($rdata['pack_type']){
                case 'P': $boxType = 'Pallet';break;
                case 'B': $boxType = 'Bins';break;
                case 'S': $boxType = 'Shelve';break;
                case 'R': $boxType = 'Room';break;
                default: //do nothing;
            }
            
            $manifestarray[$ii]['pack_type'] = $boxType;
            if ($ii == 0)
                $seller_ids = $rdata['seller_id'];
            else
                $seller_ids .= ',' . $rdata['seller_id'];

            $manifestarray[$ii]['vehicle_type'] = type_of_vehicleFiled($rdata['vehicle_type']);
            if ($rdata['seller_id'] > 0)
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name',$super_id);
            else
                $manifestarray[$ii]['seller_id'] = 'N/A';
            $ii++;
        }

        	
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
        $super_id = $this->session->userdata('user_details')['super_id'];

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
                $manifestarray[$ii]['seller_id'] = getallsellerdatabyID($rdata['seller_id'], 'name',$super_id);
            else
                $manifestarray[$ii]['seller_id'] = 'N/A';
            if ($rdata['assign_to'] > 0)
                $manifestarray[$ii]['assign_to'] = getUserNameById($rdata['assign_to']);
            else
                $manifestarray[$ii]['assign_to'] = 'N/A';

            if ($rdata['3pl_name'])
                $manifestarray[$ii]['company_name'] = GetCourCompanynameId($rdata['3pl_name'],'company');
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
        ///print "<pre>"; print_r($PostData);die;
        $assignuser = $this->User_model->userDropval(9);
        $courierData = GetCourierCompanyDrop();
        //print "<pre>"; print_r($courierData);die;
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

        // error_reporting(-1);
		// ini_set('display_errors', 1);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $uid = $_POST['list']['uid']; //'5E0DB8692B7A6';
        $sid = $_POST['list']['sid']; //3;//
        $sku = $_POST['list']['sku'];
        $stockArr = $_POST['stockArr'];
        $shelveArr = $_POST['shelveArr'];
        $SkuData = $this->Manifest_model->GetallmanifestskuData_new($uid, $sid, $sku);


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
                $locationLimit1 = $totalqty / $totalsku_size; // 11/3 
                $locationLimit = ceil($locationLimit1);
            }
            
            $requiredLocation= $locationLimit;

            $otherMatchInventory = array('item_sku' => $skuid, 'expity_date' => $expdate, 'seller_id' => $sid, 'wh_id' => $wh_id);
            $skureturnarray1 = $this->Manifest_model->GetallstockLocation_bk($sid, '', $stockArr, $locationLimit, $totalsku_size, $skuid, $otherMatchInventory,$totalqty);
            $newQty=$totalqty;
            //print_r($skureturnarray1); exit;
            if(!empty( $skureturnarray1))
            {
                $required=0;
                $available=0;
           
            foreach( $skureturnarray1 as $keys=>$ndata)
            {
                if($newQty>0) // 5 sku size 3
                {
                    if($newQty>=$totalsku_size)   // 5>=3  
                    {
                       
                        $rmSpace= $totalsku_size-$ndata['quantity']; //3- 1  rm =2 , 3-1=2
                        if($rmSpace>0)
                        {

                            $required++;
                            $available++;
                        $newQty=$newQty-$rmSpace; //5-2=3 , 3-2=1
                        $skureturnarray1[$keys]['filled']=$rmSpace;
                        $stockArr[]=$ndata['stock_location'];
                     }
                    }
                    else
                    {
                        $rmSpace= $totalsku_size-$ndata['quantity'];  // 5-1=4 , 3
                        if($rmSpace>0)
                        {
                        if($rmSpace>=$newQty) // 3>=4
                        {
                            $required++;
                            $available++;
                            $skureturnarray1[$keys]['filled']=$newQty; 
                            $stockArr[]=$ndata['stock_location'];
                            $newQty=0;
                        }
                        else
                        {
                            $required++;
                            $available++;
                            $newQty= $newQty-$rmSpace;  // 4-3
                            $skureturnarray1[$keys]['filled']=$rmSpace; 
                            $stockArr[]=$ndata['stock_location'];
                        }
                    }
                        
                    }
                   
                }
                else
                {
                   unset($skureturnarray1[$keys]);
                }
            }

            array_values($skureturnarray1); 
            $newlimitchecknew += count($skureturnarray1); 
                           
                           // echo $required.'//'. $available;exit;

        }
//echo  $newQty; exit;
        if($newQty==0)
        {
           
        }
        else
        {
            if ($totalsku_size >= $newQty)
            {
                $locationLimit1=1;
                $required = $required+1;
            }
          
          else {
            // echo $totalqty ."==========". $totalsku_size;
            $locationLimit1 = ceil($newQty / $totalsku_size); 
            $required = ($locationLimit1) +$required; 

        $newlimitchecknew += count($skureturnarray1)+$locationLimit2; 

    }
        //$locationLimitnew=$requiredLocation-count($skureturnarray1);

        $otherMatchInventory = array('item_sku' => $skuid, 'expity_date' => $expdate, 'seller_id' => $sid, 'wh_id' => $wh_id);
        $skureturnarray = $this->Manifest_model->GetallstockLocation_new($sid, '', $stockArr, $locationLimit1, $totalsku_size, $skuid, $otherMatchInventory,$totalqty,$skureturnarray,$newQty );
        //echo  count($skureturnarray );

       //$available;exit;
        $available=count($skureturnarray)+$available; 
          
      
        foreach( $skureturnarray as $keyss=>$ndata)
        {
           
            if($newQty>$totalsku_size)
            {
                $rmSpace= 0;
                $newQty=$newQty-$totalsku_size;
                $skureturnarray[$keyss]['filled']=$totalsku_size;
                
            }
            else
            {
                $rmSpace= 0;
                $newQty=$newQty;
                $skureturnarray[$keyss]['filled']=$newQty;  
            }
               
        }

           } 

           if(!empty($skureturnarray1) && !empty($skureturnarray) )
           $skureturnarray2= array_merge_recursive($skureturnarray1,$skureturnarray);
           else if(empty($skureturnarray1) && !empty($skureturnarray) )
           {
            $skureturnarray2  =$skureturnarray;
           }
           else
           {
            $skureturnarray2  =$skureturnarray1;
           }
            // print_r($skureturnarray2);
            // if ($kk == 0) {
            //     $createlocation = array_slice($skureturnarray2, 0, $locationLimit, true);
            // } else {
            //     $createlocation = array_slice($skureturnarray2, $locationLimit, $locationLimit, true);
            // }
            $createlocation=$skureturnarray2;
            // print_r($createlocation);exit;
            // print_r($createlocation);
            //print_r($skureturnarray[$kk]);
           
            if ($storage_type == 'Shelve') {
                ///  $shelveLimit=1;
                $shelveLimit = $locationLimit;
            } else {
                $shelveLimit = $locationLimit;
            }
            $shelveArr = $this->Manifest_model->GetCheckInventoryShelveNo($sid, $skuid, $shelveLimit, $totalsku_size, $shelveArr);

            foreach ($createlocation as $key555 => $val) {

                $skureturnarray3[$key555]['stockLocation'] = $val['stock_location'];
                $skureturnarray3[$key555]['id'] = $val['id'];
                $skureturnarray3[$key555]['filled'] = $val['filled'];
                $skureturnarray3[$key555]['skuid'] = $skuid;
                $skureturnarray3[$key555]['sku'] = $rdata['sku']; 
                $skureturnarray3[$key555]['uid'] = $rdata['uniqueid'];
                $skureturnarray3[$key555]['storage_type'] = $storage_type;

                $skureturnarray3[$key555]['sid'] = $rdata['seller_id'];
                $skureturnarray3[$key555]['capacity'] = $totalsku_size;
                $skureturnarray3[$key555]['boxes'] = $locationLimit;
                $skureturnarray3[$key555]['totalqty'] = $totalqty;
                $skureturnarray3[$key555]['shelveNo'] = $shelveArr[$key555]['shelv_no'];
                $skureturnarray3[$key555]['warehouse_name'] = $warehouse_name;
                $skureturnarray3[$key555]['expire_date'] = $expire_date;
                $skureturnarray3[$key555]['wh_id'] = $wh_id;
            }
           

            $kk++;
        }
        //$avaibale=count($skureturnarray)+count($skureturnarray1);
       
        $sotrageTypes = $this->Manifest_model->getallStoragesTypesData();
        $warehouseArr = Getwarehouse_Dropdata();
        $reurnarray = array('result' => $skureturnarray3, 'uid' => $uid, 'sid' => $sid, 'countarray' => $available, 'countbox' => $required, 'warehouseArr' => $warehouseArr);
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


    public function GetSaveInventoryManifest_bk() {


        $postData = json_decode(file_get_contents('php://input'), true);         
          
        $skus = $postData['skus'];
        $locations = $postData['locations'];
        $seller_id = $skus[0]['sid'];
        $token = GetallCutomerBysellerId($seller_id, 'manager_token');
        $salatoken = GetallCutomerBysellerId($seller_id, 'salla_athentication');
        $skuQtyArray = array(); 
         
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
                // $total_location = $val['total_location'];
                $expire_date = $val['expire_date'];
                $wh_id = $val['wh_id'];

                if (empty($expire_date)) {
                    $expdate = "0000-00-00";
                } else {
                    $expdate = $expire_date;
                }

                if ($first_out == 'N') {
                   // echo 'if'; exit;
                    $dataNew=$locations;

                   
                    foreach ($dataNew as $val2) {
                        if($val2['id']>0 )
                        {  
                             
                           $activitiesArr1=array(); 
                           $preData= $this->Manifest_model-> getPreQuantity($val2['id']);
                           $newQty=($val2['filled']+$preData['quantity']);
                           $activitiesArr1 = array(
                            'exp_date' => $expdate,
                            'st_location' =>$val2['stockLocation'] ,
                            'item_sku' => $skuid,
                            'user_id' => $this->session->userdata('user_details')['user_id'],
                            'seller_id' => $sid,
                            'qty' =>  $newQty ,
                            'p_qty' =>$preData['quantity'],
                            'qty_used' => $val2['filled'],
                            'type' => 'Update',
                            'entrydate' => date("Y-m-d h:i:s"),
                            'super_id' => $this->session->userdata('user_details')['super_id'],
                            'shelve_no' => $val2['shelveNo']
                        );
                           

                        GetAddInventoryActivities($activitiesArr1);
                        $this->ItemInventory_model->updateInventory(array('quantity' => $newQty,'stock_location'=>$val2['stockLocation'], 'shelve_no'=>$val2['shelveNo'],'id' => $val2['id'])); 


                        }
                        else
                        {
                                           
                            $data[] = array(
                                'itype' => $item_type,
                                'item_sku' => $skuid,
                                'seller_id' => $sid,
                                'quantity' => $val2['filled'],
                                'update_date' => date("Y/m/d h:i:sa"),
                                'stock_location' => $val2['stockLocation'],
                                'wh_id' => $wh_id,
                                'shelve_no' => $val2['shelveNo'],
                                'expity_date' => $expdate,
                                'super_id' => $this->session->userdata('user_details')['user_id']
                            );
    

                        }
                        
                        }

                        // echo $val['item_sku'];  
                    }
                    else
                    {
                      
                        if ($qty > 0) {
                            if ($sku_size >= $qty)
                                $locationLimit = 1;
                            else {
                                $locationLimit1 = $qty / $sku_size;
                                $locationLimit = ceil($locationLimit1);
                            }
        
                            $updateaty = $qty;
                            $AddQty = 0;
                           // $locationLimit = count($locations);
                            for ($ii = 0; $ii < $locationLimit; $ii++) {
                               // echo $ii;
        
                                if ($locations[$ii]['sku'] == $val['sku']) {
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
                            
                        }
                        
                    }
                    $skuQtyArray[] = $sku; 
                }
               
             // print_r($data); exit;

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

       
        //  print_r($data); die;
            if (!empty($data)) {
                $result = $this->ItemInventory_model->add_new($data);
               
            }
            if(!empty($manifestUpdate))
            {
             $this->Manifest_model->getupdateconfirmstatus_new($uid, $manifestUpdate);
            }
        
            if (!empty($token)) {

                $zid_store_id = GetallCutomerBysellerId($seller_id, 'zid_sid');
          
            foreach($skuQtyArray as $skuqtyval)
             {      
                if (!empty($token)) {                     
                 //==========update zid stock===============//
                   $zidReqArr = GetAllQtyforSeller($skuqtyval, $seller_id);    
                  // print_r($zidReqArr);                      
                     $quantity = $zidReqArr['quantity']; //+$fArray['qty'];
                     $pid = $zidReqArr['zid_pid'];
                     $token = $token;
                      $storeID = $zid_store_id;
                      $reszid = update_zid_product($quantity, $pid, $token, $storeID,$seller_id,$zidReqArr['sku']);    
                }
                    if (!empty($salatoken)) 
                    {
                        $sallaReqArr =GetAllQtyforSeller($skuqtyval, $seller_id);    
                        $quantity = $sallaReqArr['quantity'] ; //+$fArray['qty'];
                        $pid = $sallaReqArr['sku'];
                        $sallatoken = $salatoken;
                        // echo "<pre>"; print_r($sallaReqArr);
                        $reszid = update_salla_qty_product($quantity, $pid, $sallatoken,$seller_id);  
                    
                    
                    }

                     //=========================================//
                 }
             
             }
          
       

        // check invonry for empty space

        echo json_encode($postData);
    }
    
     public function BulkForwardCompanyReady_Return($uniqueid=null, $order_type=null, $cc_id=null, $dataArray=null,$itemData)
    {
            if(!empty($dataArray['super_id']))
            {
                $user_details['super_id']=$dataArray['super_id'];
                $this->session->set_userdata('user_details', $user_details);
                $super_id = $dataArray['super_id'];
            }else{
                $super_id= $this->session->userdata('user_details')['super_id'];
            }            
            
            $CURRENT_TIME = date('H:i:s');
            $CURRENT_DATE = date('Y-m-d H:i:s');
            $ccID = $dataArray['cc_id']; 
            $dataArray['mid'] = $uniqueid;
            $counrierArr_table = $this->Ccompany_model->GetdeliveryCompanyUpdateQry($ccID,$cust_id,$super_id);          
            
                $c_id = $counrierArr_table['cc_id'];
            
                if ($counrierArr_table['type'] == 'test') {
                    $user_name = $counrierArr_table['user_name_t'];
                    $password = $counrierArr_table['password_t'];
                    $courier_account_no = $counrierArr_table['courier_account_no_t'];
                    $courier_pin_no = $counrierArr_table['courier_pin_no_t'];
                    $start_awb_sequence = $counrierArr_table['start_awb_sequence_t'];
                    $end_awb_sequence = $counrierArr_table['end_awb_sequence_t'];
                    $company = $counrierArr_table['company'];
                    $api_url = $counrierArr_table['api_url_t'];
                    $create_order_url = $counrierArr_table['create_order_url'];  
                    $company_type  = $counrierArr_table['company_type'];                  
                    $auth_token = $counrierArr_table['auth_token_t'];
                } else {
                    $user_name = $counrierArr_table['user_name'];
                    $password = $counrierArr_table['password'];
                    $courier_account_no = $counrierArr_table['courier_account_no'];
                    $courier_pin_no = $counrierArr_table['courier_pin_no'];
                    $start_awb_sequence = $counrierArr_table['start_awb_sequence'];
                    $end_awb_sequence = $counrierArr_table['end_awb_sequence'];
                    $company = $counrierArr_table['company'];
                    $api_url = $counrierArr_table['api_url'];
                    $auth_token = $counrierArr_table['auth_token'];
                    $company_type  = $counrierArr_table['company_type'];
                    $create_order_url = $counrierArr_table['create_order_url']; 
                }
            $counrierArr['user_name'] = $user_name;
            $counrierArr['password'] = $password;
            $counrierArr['courier_account_no'] = $courier_account_no;
            $counrierArr['courier_pin_no'] = $courier_pin_no;
            $counrierArr['courier_pin_no'] = $courier_pin_no;
            $counrierArr['start_awb_sequence'] = $start_awb_sequence;
            $counrierArr['end_awb_sequence'] = $end_awb_sequence;
            $counrierArr['company'] = $company;
            $counrierArr['api_url'] = $api_url;
            $counrierArr['company_type'] = $company_type ;
            $counrierArr['create_order_url'] = $create_order_url;
            $counrierArr['auth_token'] = $auth_token;
          //print "<pre>"; print_r($dataArray);die;  
          if(!empty($dataArray['mid']))
          {
                $shipmentLoopArray = $dataArray['mid']; 
                $dataArray['cc_id']=$dataArray['cc_id'];
          }
          else
          {
                $midData = explode("\n", $dataArray['mid']);
                $shipmentLoopArray = array_unique($midData);
          }
          //print "<pre>"; print_r($itemData);die;
          //$alldetails = $this->Manifest_model->GetMidDetailsQry(trim($dataArray['mid']));

            $getSkuData = $this->Ccompany_model->GetSkuData($itemData,$dataArray['sellerid']);
            //print "<pre>"; print_r($getSkuData);die;
            
            $sku_all_names = array();
            $sku_total = 0;
            foreach ($getSkuData as $key => $val) {
                
                    $skunames_quantity = $getSkuData[$key]['sku'] . "/ Qty:" . $getSkuData[$key]['quantity'];
                    //$sku_total = $sku_total + $sku_data[$key]['piece'];
                    $sku_total = $sku_total + $getSkuData[$key]['quantity'];
                    array_push($sku_all_names, $skunames_quantity);
            }
            $sku_all_names = implode(",", $sku_all_names);
            if ($sku_total != 0) {
                    $complete_sku = $sku_all_names;
            } else {
                    $complete_sku = $sku_all_names;
            }
          
          $receiverdetails = GetSinglesellerdata(trim($dataArray['sellerid']),$super_id);  //Sender details 
          
          $senderdetails  = Getselletdetails($super_id); // Receiver details
          
          $box_pieces1 = $dataArray['boxes'];
          $slipNo = $dataArray['mid']; 
          $succssArray = array();
          $ShipArr = array(
            'sender_name' => $senderdetails[0]['name'],
            'sender_address' => $senderdetails[0]['address'],
            'sender_phone' =>  $senderdetails[0]['phone'],
            'sender_email' => $senderdetails[0]['email'],
            'origin' => $senderdetails[0]['branch_location'],        
            'cust_id' => $dataArray['sellerid'],
            'slip_no' => $dataArray['mid'],
            'mode' => 'CC',
            'pay_mode' => 'CC',
            'total_cod_amt' => 0,
            'pieces' => $dataArray['boxes'],
            'status_describtion' => $complete_sku,
            //'weight' => $alldetails['weight'],

            'reciever_name' => $receiverdetails['company'],
            'reciever_address' => $receiverdetails['address'],
            'reciever_phone' =>  $receiverdetails['phone'],
            'reciever_email' =>  $receiverdetails['email'],
            'destination' => $receiverdetails['city'],
            
            );
            $sellername = $ShipArr['sender_name'];
            //$complete_sku= $alldetails['sku'];
            
            $pay_mode = trim($ShipArr['mode']);
            $cod_amount = $ShipArr['total_cod_amt'];
            if ($pay_mode == 'COD') {
                    $pay_mode = 'P';
                    $CashOnDeliveryAmount = array("Value" => $cod_amount,
                            "CurrencyCode" => site_configTable("default_currency"));
                    $services = 'CODS';
            } elseif ($pay_mode == 'CC') {
                    $pay_mode = 'P';
                    $CashOnDeliveryAmount = NULL;
                    $services = '';
            }
          
            //print "<pre>"; print_r($ShipArr);die;
            if($company=='Aramex'){
                $params = $this->Ccompany_model->AramexArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1,$super_id);
                $dataJson = json_encode($params);
                $headers = array("Content-type:application/json");
                $url = $api_url;
                $awb_array = $this->Ccompany_model->AxamexCurl($url, $headers, $dataJson,$c_id,$ShipArr);
                $check_error = $awb_array['HasErrors'];


                if ($check_error == 'true') {
                    
                    if (empty($awb_array['Shipments'])) {
                        $error_response = $awb_array['Notifications']['Notification'];
                        $error_response = json_encode($error_response);
                        array_push($error_array, $slipNo . ':' . $error_response);
                        $returnArr['responseError'][] = $slipNo . ':' . $error_response;
                    } else {
                        if ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'] == '') {
                            foreach ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification'] as $error_response) {
                                array_push($error_array, $slipNo . ':' . $error_response['Message']);
                                $returnArr['responseError'][] = $slipNo . ':' . $error_response['Message'];
                            }
                        } else {
                            $error_response = $awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'];
                            $error_response = json_encode($error_response);
                            array_push($error_array, $slipNo . ':' . $error_response);
                            $returnArr['responseError'][] = $slipNo . ':' . $error_response;
                        }
                    }
                    array_push($error_msg, $returnArr);
                } else {
                    $main_result = $awb_array['Shipments']['ProcessedShipment'];
                    
                    $Check_inner_error = $main_result['HasErrors'];
                    if ($Check_inner_error == 'false') {
                        $client_awb = $main_result['ID'];
                        $awb_label = $main_result['ShipmentLabel']['LabelURL'];

                        $generated_pdf = file_get_contents($awb_label);
                        $encoded = base64_decode($generated_pdf);
                        header('Content-Type: application/pdf');
                        file_put_contents("/var/www/html/fastcoo-tech/demofulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);

                         $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                         $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                        
                        array_push($succssArray, $slipNo);
                        $returnArr['Success_msg'] = 'Data updated successfully.';
                    }
                }
            }
            elseif($company=='Safearrival'){
                        
                $charge_items=array();
                 $Auth_response = SafeArrival_Auth_cURL($counrierArr);  
                
                $responseArray = json_decode($Auth_response, true);
                $Auth_token = $responseArray['data']['id_token'];
                $response = $this->Ccompany_model->SafeArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);
               // print "<pre>"; print_r($response);die;
                $safe_response = json_decode($response, true);                     
               //print "<pre>"; print_r($safe_response);die;
                if ($safe_response['status'] == 'success') {
                    $safe_arrival_ID = $safe_response['data']['id'];
                    $client_awb = $safe_response['data']['order_number'];

                    //****************************safe arrival label print cURL****************************
                    $label_response = safearrival_label_curl($safe_arrival_ID, $Auth_token,$counrierArr['api_url']);                       
                    $safe_label_response = json_decode($label_response, true);
                    $safe_Label = $safe_label_response['data']['value'];

                    $generated_pdf = file_get_contents($safe_Label);
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    //****************************safe arrival label print cURL****************************
                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                    
                    //array_push($succssArray, $slipNo);

                    array_push($dataArray, $slipNo);
                    $returnArr['Success_msg'] = 'Data updated successfully.';
                }
                else if($safe_response['status']=='error' || $safe_response['status']==400){
                    $returnArr['responseError'][] = $slipNo . ':' . $safe_response['message'];
                }
                
            }
            else if($company=='Thabit' )
            {   
                $charge_items=array();
                $Auth_response = Thabit_Auth_cURL($counrierArr);
                $responseArray = json_decode($Auth_response, true);                      
                $Auth_token = $responseArray['data']['id_token'];
                
                $thabit_response = $this->Ccompany_model->ThabitArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id, $box_pieces1,$super_id);

                if ($thabit_response['status'] == 'success') 
                {
                    $thabit_order_ID = $thabit_response['data']['id'];
                    $client_awb = $thabit_response['data']['order_number'];

                    //**************************** Thabit label print cURL****************************

                        $label_response = thabit_label_curl($thabit_order_ID, $Auth_token,$counrierArr['api_url']); 
                        $safe_label_response = json_decode($label_response, true);
                        $safe_Label = $safe_label_response['data']['value'];
                        
                        $generated_pdf = file_get_contents($safe_Label);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
              
                    //**************************** Thabit label print cURL****************************
                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                    							
                    array_push($succssArray, $slipNo);
                    $returnArr['Success_msg'] = 'Data updated successfully.';
                                     
                }
                else if($thabit_response['status']=='error' || $thabit_response['status'] == 400)
                {
                    $returnArr['responseError'][] = $slipNo . ':' . $thabit_response['message'];
                }
            }
            elseif($company=='Esnad'){
                $esnad_awb_number = Get_esnad_awb($start_awb_sequence, $end_awb_sequence); 
                
                $esnad_awb_number = $esnad_awb_number -1;
                
                $response = $this->Ccompany_model->EsnadArray($sellername, $ShipArr, $counrierArr, $esnad_awb_number, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);
                
                $responseArray = json_decode($response, true);
               //    print "<pre>"; print_r($responseArray);die;
                $status = $responseArray['code'];
                
                if($status == "500"){
                    $error_msg = array(
                        "Error_Message " => $responseArray['errorMsg'],
                    );
                    $errre_response = json_encode($error_msg);
                    array_push($error_array, $slipNo . ':' . $responseArray['errorMsg']);
                    $returnArr['responseError'][] = $slipNo . ':' . $responseArray['errorMsg'];

                    $this->session->set_flashdata('errorloop', $returnArr);
                }
                if ($status == "2000") {
                    $error_msg = array(
                        "Error_Message " => $responseArray['msg'],
                    );
                    $errre_response = json_encode($error_msg);
                    array_push($error_array, $slipNo . ':' . $error_response['Message']);
                    $returnArr['responseError'][] = $slipNo . ':' . $responseArray['msg'];

                    $this->session->set_flashdata('errorloop', $returnArr);
                }
                
                if ($status == "3000") {
                    $error_msg = array(
                        "Error_Message " => $responseArray['msg'],
                        "Awb_NO " => $responseArray['data'][0]['clientOrderNo'],
                        "Esnad_awb_no " => $responseArray['data'][0]['esnadAwbNo'],
                        "Esnad_awb_link" => $responseArray['data'][0]['esnadAwbPdfLink'],
                    );
                    $errre_response = json_encode($error_msg);
                    array_push($error_array, $slipNo . ':' . $responseArray['msg']);
                    $returnArr['responseError'][] = $slipNo . ':' . $responseArray['msg'];

                    $this->session->set_flashdata('errorloop', $returnArr);
                }
                // echo $status;exit;

                if ($status == "1000") {
                    $status = $responseArray['code'];
                    $description = $responseArray['msg'];
                    $client_awb = $responseArray['data'][0]['esnadAwbNo'];
                    if ($status == "1000" && $description == "SUCCESS") {
                        $esnad_awb_link = $responseArray['data'][0]['esnadAwbPdfLink'];
                            $generated_pdf = file_get_contents($esnad_awb_link);
                    $encoded = base64_decode($generated_pdf);
                    //header('Content-Type: application/pdf');
                    file_put_contents("/var/www/html/fastcoo-tech/fulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);
                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                    
                        array_push($succssArray, $slipNo);

                        array_push($DataArray, $slipNo);

                        $insert_esnad_awb_number = array(
                            'slip_no' => $slipNo,
                            'esnad_awb_no' => $esnad_awb_number,
                            'super_id' => $this->session->userdata('user_details')['super_id']
                        );
                        updateEsdadAWB($insert_esnad_awb_number);
                        $returnArr['Success_msg'][] = 'Data updated successfully.';
                    }
                }
               if($responseArray['success'] == 1 && !empty($responseArray['dataObj'])){
                        $esnad_awb_link = $responseArray['dataObj']['labelUrl'];
                        $generated_pdf = file_get_contents($esnad_awb_link);
                        //$encoded = base64_decode($generated_pdf);
                        
                        
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                        $esnadlabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        
                        $client_awb = $responseArray['trackingNo'];
                        $comment = $responseArray['message'];
                    //header('Content-Type: application/pdf');
                        //file_put_contents("/var/www/html/fastcoo-tech/fulfillment/assets/all_labels/$slipNo.pdf", $generated_pdf);
                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $esnadlabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                    
                        array_push($succssArray, $slipNo);

                        array_push($DataArray, $slipNo);

                        $insert_esnad_awb_number = array(
                            'slip_no' => $slipNo,
                            'esnad_awb_no' => $esnad_awb_number,
                            'super_id' => $this->session->userdata('user_details')['super_id']
                        );
                        updateEsdadAWB($insert_esnad_awb_number);
                        $returnArr['Success_msg'][] = 'Data updated successfully.';
               }
            }
            elseif ($company == 'Barqfleet') {
                    $response_ww = $this->Ccompany_model->BarqfleethArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services,$c_id,$box_pieces1,$super_id);
                    $response_array = json_decode($response_ww, TRUE);                        
                   // print "<pre>"; print_r($response_array);die;
                   if ($response_array['code'] != '') {
                         $returnArr['responseError'][] = $slipNo . ':' .$response_array['message'];
                    } 
                    else 
                    {
                         $Authorization = $counrierArr['auth_token'];
                         $request_url_label = $counrierArr['api_url']."/orders/airwaybill/".$response_array['id'];
                         $headers = array("Content-type:application/json");
                         $firsthead = array(
                            "Content-Type: application/json",
                            "Authorization: ". $Authorization,
                         );
                         $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $request_url_label);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($ch, CURLOPT_HEADER, false);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $firsthead);
                            $response_label = curl_exec($ch);                                
                            $info = curl_getinfo($ch);
                            curl_close($ch);
                            $client_awb = $response_array['tracking_no'];                                   
                            $slip_no = $response_array['merchant_order_id'];                                   
                            $barq_order_id = $response_array['id'];
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");
                        $generated_pdf = file_get_contents($response_label);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $response_label);
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                        //****************************makdoom label print cURL****************************

                       $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $esnadlabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                       array_push($succssArray, $slipNo);
                       $returnArr['Success_msg'][] = 'Data updated successfully.';
                   }
                  
                    //end
            } 
            elseif ($company == 'Makhdoom'){
                    
                    $Auth_response = MakdoomArrival_Auth_cURL($counrierArr);                             
                    
                    $responseArray = json_decode($Auth_response, true);
                    $Auth_token = $responseArray['data']['id_token'];

                    $response =$this->Ccompany_model->MakdoonArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1,$super_id);
                    $safe_response = json_decode($response, true);


                    if ($safe_response['status'] == 'success') {
                        $safe_arrival_ID = $safe_response['data']['id'];
                        $client_awb = $safe_response['data']['order_number'];

                        //****************************makdoom arrival label print cURL****************************

                        $label_response = makdoom_label_curl($client_awb, $Auth_token);
                        $safe_label_response = json_decode($label_response, true);
                        $safe_Label = $safe_label_response['data']['value'];

                        $generated_pdf = file_get_contents($safe_Label);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        //echo $fastcoolabel ;

                        //****************************makdoom label print cURL****************************
                         $CURRENT_DATE = date("Y-m-d H:i:s");
                         $CURRENT_TIME = date("H:i:s");

                        
                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                       array_push($succssArray, $slipNo);
                      $returnArr['Success_msg'] = 'Data updated successfully.';
                    }else{
                        $returnArr['responseError'][] = $slipNo . ':' .$safe_response['message'];
                    }
            }
            elseif ($company == 'Zajil') {
                    $response = $this->Ccompany_model->ZajilArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$super_id);
                    //print "<pre>"; print_r($response);die;
                    if (!empty($response['data'])) {
                        $success = $response['data'][0]['success'];
                        if ($response['status'] == 'OK' && $success == 1) {
                            $client_awb = $response['data'][0]['reference_number'];

                            $label_response = zajil_label_curl($auth_token, $client_awb);
                            header("Content-type:application/pdf");
                            file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                            $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                            $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                            
                            array_push($succssArray, $slipNo);
                            $returnArr['Success_msg'][] = 'Data updated successfully.';
                        } else {
                            $returnArr['responseError'][] = $slipNo . ':' . $response['data'][0]['reason'];
                        }
                    } else {
                        $returnArr['responseError'][] = $slipNo . ':' . "invalid details";
                    }
            }
            elseif ($company == 'NAQEL'){
                $awb_array = $this->Ccompany_model->NaqelArray($sellername, $ShipArr,$counrierArr, $complete_sku,$box_pieces1, $Auth_token,$c_id, $super_id);
                 
                 $HasError = $awb_array['HasError'];
                $error_message = $awb_array['Message'];
                
                if ($awb_array['HasError'] !== true) 
                {
                    $client_awb = $awb_array['WaybillNo'];
                        if (!empty($client_awb)) 
                        {
                            $user_name = $counrierArr['user_name'];    
                            $password = $counrierArr['password'];
                            $xml_for_label = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <tem:GetWaybillSticker>
                                    <tem:clientInfo>
                                        <tem:ClientAddress>
                                            <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                            <tem:POBox>0</tem:POBox>
                                            <tem:ZipCode>0</tem:ZipCode>
                                            <tem:Fax>0</tem:Fax>
                                            <tem:FirstAddress>' . $ShipArr['sender_address'] . '</tem:FirstAddress>
                                            <tem:Location>' . $sender_city . '</tem:Location>
                                            <tem:CountryCode>KSA</tem:CountryCode>
                                            <tem:CityCode>RUH</tem:CityCode>
                                        </tem:ClientAddress>
                                        <tem:ClientContact>
                                            <tem:Name>' . $ShipArr['sender_name'] . '</tem:Name>
                                            <tem:Email>' . $ShipArr['sender_email'] . '</tem:Email>
                                            <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                            <tem:MobileNo>' . $ShipArr['sender_phone'] . '</tem:MobileNo>
                                        </tem:ClientContact>
                                        <tem:ClientID>' . $user_name . '</tem:ClientID>
                                        <tem:Password>' . $password . '</tem:Password>
                                        <tem:Version>9.0</tem:Version>
                                    </tem:clientInfo>
                                    <tem:WaybillNo>' . $client_awb . '</tem:WaybillNo>
                                    <tem:StickerSize>FourMSixthInches</tem:StickerSize>
                                </tem:GetWaybillSticker>
                                </soapenv:Body>
                                </soapenv:Envelope>';
                          
                            $headers = array(
                                "Content-type: text/xml",
                                "Content-length: " . strlen($xml_for_label),
                            );

                            $url = $counrierArr['api_url']."?op=GetWaybillSticker";

                            $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_for_label);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                $response = trim(curl_exec($ch));    
                      
                                curl_close($ch);
                            
                            $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $response));
                            $mediaData = $xml_data->Body->GetWaybillStickerResponse->GetWaybillStickerResult[0];
                                         
                                if (!empty($mediaData)) 
                                {
                                    $pdf_label = json_decode(json_encode((array) $mediaData), TRUE);
                                    header('Content-Type: application/pdf');
                                    $img = base64_decode($pdf_label[0]);
                                    $savefolder = $img;
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);
                                    //*********NAQEL arrival label print cURL****************************

                                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                    //****************NAQEL label print cURL****************************
                                     $CURRENT_DATE = date("Y-m-d H:i:s");
                                     $CURRENT_TIME = date("H:i:s");
                                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                   array_push($succssArray, $slipNo);
                                   $returnArr['Success_msg'][] = 'Data updated successfully.';
                                }
                        }
                        else
                           {
                               $returnArr['responseError'][] = $slipNo . ':' . $awb_array['Message'];
                           }
                }
            }
            elseif ($company == 'Saee'){
                    $response = $this->Ccompany_model->SaeeArray($sellername, $ShipArr, $counrierArr, $Auth_token,$c_id,$box_pieces1, $super_id);
                    $safe_response =  $response; 

                    if ($safe_response['success'] == 'true') 
                    {
                              $client_awb = $safe_response['waybill'];
                        //****************************Saee arrival label print cURL****************************
                        $API_URL = $counrierArr['api_url'];
                        $label_response = saee_label_curl($client_awb, $Auth_token,$API_URL );
                        file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                         $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                        //****************************Saee label print cURL****************************
                         $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");

                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                       array_push($succssArray, $slipNo);
                       $returnArr['Success_msg'][] = 'Data updated successfully.';
                      
                    }
            } 
            elseif ($company == 'Smsa'){
               
                    $response = $this->Ccompany_model->SMSAArray($sellername, $ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id, $super_id);
                    
                    $xml2 = new SimpleXMLElement($response);
                    $again = $xml2;
                    $a = array("qwb" => $again);

                    $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);

                    if (preg_match('/\bFailed\b/', $complicated)) {
                        $returnArr['responseError'][] = $slipNo . ':' . $complicated;
                    } 
                    else {
                        if ($response != 'Bad Request') {
                            $xml2 = new SimpleXMLElement($response);
                            //echo "<pre>";
                            //print_r($xml2);
                            $again = $xml2;
                            $a = array("qwb" => $again);

                            $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
                            //print_r($complicated); exit;   
                            $abc = array("qwber" => $complicated);

                            $client_awb = (implode(" ", $abc));
                            //print_r($abc);
                            $newRes = explode('#', $client_awb);


                            if (!empty($newRes[1])) {
                                $client_awb = trim($newRes[1]);
                            }

                            $printLabel = $this->Ccompany_model->PrintLabel($client_awb, $counrierArr['$auth_token'], $counrierArr['api_url']);


                            $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $printLabel));
                            $mediaData = $xml_data->Body->getPDFResponse->getPDFResult[0];
                            header('Content-Type: application/pdf');
                            $img = base64_decode($mediaData);

                            if (!empty($mediaData)) {
                                $savefolder = $img;

                                file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);

                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);

                                array_push($succssArray, $slipNo);
                                $returnArr['Success_msg'][] = 'Data updated successfully.';

                            } else 
                            {
                                array_push($error_array, $booking_id . ':' . $db);
                            }
                        } else {
                            $returnArr['responseError'][] = $slipNo . ':' . $response;
                        }
                    }
            }                                         
            elseif ($company == 'Labaih')
            {       
                    $response = $this->Ccompany_model->LabaihArray($sellername, $ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id, $super_id);
                   
                    if ($response['status'] == 200) {
                        $client_awb = $response['consignmentNo'];
                        $shipmentLabel_url = $response['shipmentLabel'];

                        $generated_pdf = file_get_contents($shipmentLabel_url);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                        array_push($succssArray, $slipNo);
                        $returnArr['Success_msg'][] = 'Data updated successfully.';
                    } 
                    else {
                         $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                        //$returnArr['responseError'][] = $slipNo . ':' . $response['invalid_parameters'][0];
                    }
            } 
            elseif ($company == 'Clex'){
                    
                    $response = $this->Ccompany_model->ClexArray($sellername, $ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id, $super_id );
                    
                    if ($response['data'][0]['cn_id']) {
                        $client_awb = $response['data'][0]['cn_id'];
                         $label_url_new = clex_label_curl($Auth_token, $client_awb);
                         $generated_pdf = file_get_contents($label_url_new);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                        $fastcoolabel = base_url()."assets/all_labels/$slipNo.pdf";
                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                        array_push($succssArray, $slipNo);
                        $returnArr['Success_msg'][] = 'Data updated successfully.';
                    } else {
                        if($response['already_exist'])
                        {
                            $label_url_new = clex_label_curl($Auth_token, $response['consignment_id'][0]);
                            
                            $generated_pdf = file_get_contents($label_url_new);
                           file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $returnArr['responseError'][] = $slipNo . ':' . $response['already_exist'][0]." ".$response['consignment_id'][0];
                        }
                        elseif($response['origin_city'])
                             $returnArr['responseError'][] = $slipNo . ':' . $response['origin_city'][0];
                         elseif($response['destination_city'])
                             $returnArr['responseError'][] = $slipNo . ':' . $response['destination_city'][0];
                        else
                            $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                            
                    }
            }
            elseif ($company=='Emdad') {
                    $response = $this->Ccompany_model->EmdadArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1, $super_id);
                    $response = json_decode($response, true);
                    
                    if($response['error']=='' && !empty($response['awb_print_url']))
                    {
                        $generated_pdf = file_get_contents($response['awb_print_url']);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                        
                        $client_awb = $response['awb'];

                        $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                        array_push($succssArray, $slipNo);
                        $returnArr['Success_msg'][] = 'Data updated successfully.';
                    } else {
                        
                        //$returnArr['responseError'][] = $slipNo . ':' . $response['refrence_id'];
                        $returnArr['responseError'][] = $slipNo . ': Error! please check log' ;
                    }
            }
            elseif ($company == 'Ajeek'){
                    
                    $response = $this->Ccompany_model->AjeekArray($sellername, $ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,  $super_id);
                    if ($response['contents']['order_id']) {
                         $response['contents']['order_id'];
                         $Auth_token = $counrierArr['auth_token'];
                         $vendor_id = $counrierArr['courier_pin_no'];
                         $client_awb = $response['contents']['order_id'];
                         
                        //****************************Saee arrival label print cURL****************************
                         $label_response = ajeek_label_curl($Auth_token, $client_awb, $vendor_id);
                           
                        file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                          $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                          
                        //****************************Saee label print cURL****************************
                         $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");

                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                       array_push($succssArray, $slipNo);
                       $returnArr['Success_msg'][] = 'Data updated successfully.';

                    }  else{
                            
                            $returnArr['responseError'][] = $slipNo . ':' . $response['description'];
                            
                    }
            }
             elseif ($company == 'Aymakan'){
                            $response = $this->Ccompany_model->AymakanArray($sellername, $ShipArr, $counrierArr, $Auth_token,$c_id,$box_pieces1,$complete_sku, $super_id);
                            $responseArray = json_decode($response, true);
                       //print_r( $responseArray );
                            if (empty($responseArray['message'])) 
                            {
                                     $client_awb = $responseArray['data']['shipping']['tracking_number'];
                                     
                                    
                                    $tracking_url= $counrierArr['api_url']."bulk_awb/trackings/";
                                         
                                    $aymakanlabel= $this->Ccompany_model->Aymakan_tracking($client_awb, $tracking_url,$auth_token);
                                    $label= json_decode($aymakanlabel,TRUE);
                                      
                                    $mediaData = $label['data']['bulk_awb_url'];
                                       
                                        
                                   
                                //****************************aymakan arrival label print cURL****************************
                                $generated_pdf = file_get_contents($media_data);
                                file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                //****************************aymakan label print cURL****************************
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                 $CURRENT_TIME = date("H:i:s");
                                                             
                                $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                $returnArr['Success_msg'][] = 'Data updated successfully.';
                               array_push($succssArray, $slipNo); 
                            }   
                            else{
                                  
                                    $returnArr['responseError'][] = $slipNo . ':' . $responseArray['message'].':'.json_encode($responseArray['errors']);
                                    
                            }                                    
                    }
            elseif($company == 'Shipsy'){
                
                $response = $this->Ccompany_model->ShipsyArray($sellername, $ShipArr, $counrierArr, $Auth_token, $box_pieces1,$c_id, $super_id);
                
                $response_array = json_decode($response, true);
                
                if($response_array['data'][0]['success']==1){
                    $client_awb = $response_array['data'][0]['reference_number'];
                    
                    //****************************Shipsy label print cURL****************************
                    
                    $shipsyLabel = $this->Ccompany_model->ShipsyLabelcURL($counrierArr, $client_awb);
                    
                    $mediaData = $shipsyLabel;
                   
                    file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                     $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                    array_push($succssArray, $slipNo);
                    $returnArr['Success_msg'][] = 'Data updated successfully.';
                }else{
                    
                    $returnArr['responseError'][] = $slipNo . ':' . $response_array['error']['message'];
                }
            }
            elseif($company == 'Shipadelivery'){
                
               $response = $this->Ccompany_model->ShipadeliveryArray($sellername, $ShipArr, $counrierArr, $Auth_token,$c_id, $super_id);
            
                $response_array = json_decode($response,true);    
                if(empty($response_array)){
                    $returnArr['responseError'][] = $slipNo . ':' .'Receiver City Empty ';
                }
                else{

                    if($response_array[0]['code']== 0)
                        {
                            $client_awb = $response_array[0]['deliveryInfo']['reference'];

                            $responsepie = $this->Ccompany_model->ShipaDelupdatecURL($counrierArr, $ShipArr, $client_awb ,$box_pieces1);
                            $responsepieces = json_decode($responsepie, true); 
                          //  echo "<pre>"; print_r($responsepieces); // die; 
                           
                                 if ($responsepieces['status']=='Success')
                                 {
                                    $shipaLabel = $this->Ccompany_model->ShipaDelLabelcURL($counrierArr, $client_awb);

                                    header('Content-Type: application/pdf');

                                    file_put_contents("assets/all_labels/$slipNo.pdf", $shipaLabel);
                                     $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                    array_push($succssArray, $slipNo);
                                    $returnArr['Success_msg'][] = 'Data updated successfully.';

                                 }
                                  else{

                                        $returnArr['responseError'][] = $slipNo . ':' . $responsepieces['action'];
                                    }
                        } else{

                            $returnArr['responseError'][] = $slipNo . ':' . $response_array['info'];
                        }
                    }
                                  
                    
                        
            }
            elseif($company == 'Saudi Post'){
                $response = $this->Ccompany_model->SPArray($sellername, $ShipArr, $counrierArr, $complete_sku,$Auth_token,$c_id,$box_pieces1,  $super_id);
                
                $response = json_decode($response, true);
                                
                if($response['Items'][0]['Message']=='Success'){
                    $client_awb = $response['Items'][0]['Barcode'];
                    
                  
                    $fastcoolabel='SP';
                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                    

                    header('Content-Type: application/pdf');
                    $lableSp=   file_get_contents(base_url().'awbPrint1/'.$slipNo );
                    file_put_contents("assets/all_labels/$slipNo.pdf", $lableSp);
                 
                    array_push($succssArray, $slipNo);
                    $returnArr['Success_msg'][] = 'Data updated successfully.';
                }else{
                    $errre_response = $response['Items'][0]['Message'];
                    if($errre_response==''){
                        $errre_response = $response['Message'];
                    }
                    $returnArr['responseError'][] = $slipNo . ':' . $errre_response;
                }
            }elseif ($company== 'Beez'){
                            //print "<pre>"; print_r($sku_data);die;
                            $response = $this->Ccompany_model->BeezArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$getSkuData,$super_id);  
                            if(isset($response['Message']) && !empty($response['Message'])){
                                $returnArr['responseError'][] = $slipNo . ':' . $response['Message'];
                            }else{
                                $client_awb = $response;
                                $url = 'https://login.beezerp.com/label/pdf/?t='.$client_awb;
                                $generated_pdf = file_get_contents($url); 
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                
                                $beezlabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $beezlabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                $returnArr['Success_msg'][] = $slipNo.': Data updated successfully.';
                                array_push($succssArray, $slipNo);
                            }
            }elseif ($company == 'GLT'){

                        $responseArray = $this->Ccompany_model->GLTArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku,$super_id);
                        $successres = $responseArray['data']['orders'][0]['status'];
                        $error_status = $responseArray['data']['orders'][0]['msg'];

                            if (!empty($successres) && $successres == 'success')
                            {

                                $client_awb = $responseArray['data']['orders'][0]['orderTrackingNumber'];
                                $innser_status = $responseArray['data']['orders'][0]['status'];
                                                         

                                $GltLabel = $this->Ccompany_model->GLT_label($client_awb, $counrierArr, $auth_token);
                                    
                                 file_put_contents("assets/all_labels/$slipNo.pdf", $GltLabel);                            
                                 $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';


                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                $returnArr['Success_msg'][] = $slipNo.': Data updated successfully.';
                                array_push($succssArray, $slipNo);
                            }
                            
                            else{
                                $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                            }
            }
            elseif ($company == 'KwickBox')
            {
                $responseArray = $this->Ccompany_model->KwickBoxArray($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku,$super_id);

                $successres = $responseArray['number'];                        
                $error_status = $responseArray['field.'][0];

                if (!empty($successres))
                {
                    $client_awb = $responseArray['number'];
                    $media_data = $responseArray['labelUrl'];                               

                    if (file_put_contents( "assets/all_labels/$slipNo.pdf",file_get_contents($media_data))){
                        
                        $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                        $CURRENT_DATE = date("Y-m-d H:i:s");
                        $CURRENT_TIME = date("H:i:s");

                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                $returnArr['Success_msg'][] = $slipNo.': Data updated successfully.';
                        array_push($succssArray, $slipNo);
                    }
                }                            
                else
                {
                    $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                }                  
            }
            
            elseif($company == 'DHL JONES') {
                        if(!empty($counrierArr)) { 
                                    $api_response = $this->Ccompany_model->DhlJonesArray($sellername,$ShipArr, $counrierArr,$token, $complete_sku, $box_pieces1,$c_id,$super_id);
                                    
                                    if($api_response['error'] == FALSE) {
                                         $client_awb = $api_response['data']['ShipmentResponse']['ShipmentIdentificationNumber'];
                                         $lableData = $api_response['data']['ShipmentResponse']['Documents'][0]['Document'];
                                         //print "<pre>"; print_r($lableData);die;
                                         $dhlLabel = '';
                                         
                                        if (!empty($lableData['DocumentImage'])) {
                                            $encoded = base64_decode($lableData['DocumentImage']);
                                             header('Content-Type: application/pdf');
                                             file_put_contents("assets/all_labels/$slipNo.pdf", $encoded);

                                            $dhlLabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                        }
                                                                           
                                        $CURRENT_DATE = date("Y-m-d H:i:s");
                                        $CURRENT_TIME = date("H:i:s");

                                        $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $dhlLabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);

                                        $returnArr['Success_msg'][] = $slipNo.': Data updated successfully.';
                                       
                                        array_push($succssArray, $slipNo);
                                         
                                    } else {                                       
                                         $returnArr['responseError'][] = $slipNo . ':' .$api_response['data']['ShipmentResponse']['Notification'][0]['Message'];
                                    }
                        } else {
                           $returnArr['Error_msg'][] = $slipNo . ':Token Not Genrated'; 
                        }                       
                    }
            elseif($company == 'Tamex'){
                            $responseArray = $this->Ccompany_model->tamexArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode,$c_id,$box_pieces1,$super_id);
                         
                            if ($responseArray['code'] != 0 || empty($responseArray)) {
                                array_push($error_array, $slipNo . ':' . $responseArray['data']);
                                $returnArr['responseError'][] = $slipNo . ':' . $responseArray['data'];
                            } elseif ($responseArray['code'] == 0) {

                                $client_awb = $responseArray['tmxAWB'];
                                $API_URL= $counrierArr['api_url'].'print';
                                
                                $generated_pdf = Tamex_label($client_awb, $counrierArr['auth_token'],$API_URL);
                              
                                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");
                               
                                $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                
                                $details = 'Forwarded to ' . $ClientArr['company'];
                                

                                $returnArr['Success_msg'][] = $slipNo . ': Data updated successfully.';

                                array_push($DataArray, $slipNo);
                            }
            }elseif ($company== 'Fetchr'){
                     
                               $responseData = $this->Ccompany_model->fetchrArray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1,$super_id);
                               if($responseData['data'][0]['status'] == 'success')
                                {
                                    $client_awb = $responseData['data'][0]['tracking_no'];
                                    
                                    $label = "https://s3-eu-west-1.amazonaws.com/cms-dhl-pdf-stage-1/label6x4_".$client_awb.".pdf";
                                    
                                  
                                    $generated_pdf = file_get_contents($label);
                                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf );
                                    
                                   $fetchrlabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                    $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");
                                    $comment = $responseData['message'];
                                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fetchrlabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                    $returnArr['Success_msg'][] = $slipNo . ' Data updated successfully.';

                                    $this->session->set_flashdata('msg', $returnArr);
                                    array_push($succssArray, $slipNo);
                                 }else{

                                     $returnArr['responseError'][] = $slipNo . ':' . $responseData['message'];
                                 } 
            }elseif ($company== 'iMile'){
                            //print "<pre>"; print_r($sku_data);die;
                            $auth_token = $this->Ccompany_model->iMileToken($counrierArr);
                            
                            if(empty($auth_token)){
                                $returnArr['responseError'][] = $slipNo . ': Token not genrated';
                            }else{
                                $response = $this->Ccompany_model->iMileArray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$auth_token,$super_id);  
                                if($response['code'] == 200  && $response['message'] == 'success'){
                                    $client_awb = $response['data']['expressNo'];
                                    $pdf_encoded_base64 = $response['data']['imileAwb'];
                                    $pdf_file = base64_decode($pdf_encoded_base64);

                                    file_put_contents("assets/all_labels/".$slipNo.".pdf", $pdf_file);
                                    $imile_label = base_url() . "assets/all_labels/$slipNo.pdf";
                                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $imile_label,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                    $returnArr['Success_msg'][] = $slipNo . ' Data updated successfully.';
                                    array_push($succssArray, $slipNo);
                                    
                                }else if($response['code'] == 30001){
                                    $returnArr['responseError'][] = $slipNo . ': Customer order number repeated error code';
                                }else{
                                    $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                                }
                                                   
                            }
            }elseif ($company == 'Wadha'){
                        $counrierArr['user_name'] = $user_name;
                        $counrierArr['password'] = $password;
                        $counrierArr['api_url'] =$api_url;
                       $Auth_token=$this->Ccompany_model->Wadha_auth($user_name,$password,$api_url); 
                      
                        $responseArray = $this->Ccompany_model->WadhaArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id);  
                                            
                        $successres = $responseArray['status'];                          
                        
                         $error_status = $responseArray['message'];

                        if (!empty($successres) && $successres == 'success')
                        {

                            $client_awb = $responseArray['data']['order_number'];
                             $WadhaLabel = $this->Ccompany_model->Wadha_label($client_awb, $counrierArr, $Auth_token);
                              $label= json_decode($WadhaLabel,TRUE);
                              $media_data = $label['data']['value'];                               

                             $generated_pdf = file_get_contents($media_data);
                             file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                             $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                            $returnArr['Success_msg'][] = $slipNo . ' Data updated successfully.';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
            }

            elseif ($company == 'FDA'){                       

                        $Auth_token=$this->Ccompany_model->FDA_auth($counrierArr); 
                        
                        $responseArray = $this->Ccompany_model->FDAArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id, $complete_sku);
                      
                                            
                        $successres = $responseArray['status'];                         
                        $error_status = $responseArray['message'];

                        if (!empty($successres) && $successres == 'success')
                        {

                             $client_awb = $responseArray['data']['order_number'];
                            $FDALabel = $this->Ccompany_model->FDA_label($client_awb, $counrierArr, $Auth_token);
                            $label= json_decode($FDALabel,TRUE);
                            $media_data = $label['data']['value'];                               
                            $generated_pdf = file_get_contents($media_data);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';  

                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                            
                            $returnArr['Success_msg'][] = $slipNo . ' Data updated successfully.';                            
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
            }

            elseif ($company == 'MMCCO')
                    {
                       // print_r($counrierArr);die;
                        $Auth_token=$this->Ccompany_model->MMCCO_auth($counrierArr['user_name'],$counrierArr['password'],$counrierArr['api_url']);
                      
                        $responseArray = $this->Ccompany_model->MMCCOArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$super_id);  
                        //   echo "<br><br><pre>";
                          // print_r($responseArray); DIE;

                        $successres = $responseArray['status'];                         
                        
                         $error_status = $responseArray['message'];

                        if (!empty($successres) && $successres == 'success')
                        {

                            $client_awb = $responseArray['data']['order_number'];
                            $MMCCOLabel = $this->Ccompany_model->MMCCO_label($client_awb, $counrierArr, $Auth_token);
                            $label= json_decode($MMCCOLabel,TRUE);
                            $media_data = $label['data']['value'];                               

                            $generated_pdf = file_get_contents($media_data);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                           $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                            $returnArr['Success_msg'][] = $slipNo . ' Data updated successfully.';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
            } elseif ($company == 'FedEX')
                    {

                        $responseArray = $this->Ccompany_model->FedEX($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1,$c_id,$super_id);
                       //  echo "<pre>" ; print_r($responseArray); //die;
                        $successres = $responseArray['Code'];
                        $error_status = $responseArray['description'];

                            if (!empty($successres) && $successres == 1)
                            {
                                $client_awb = $responseArray['AirwayBillNumber'];
                                 
                                $label_response = $this->Ccompany_model->FedEX_label($client_awb, $counrierArr,$ShipArr);
                                $pdf_encoded_base64 = $label_response['ReportDoc'];
                                $pdf_file = base64_decode($pdf_encoded_base64);
                               
                                file_put_contents("assets/all_labels/".$slipNo.".pdf", $pdf_file);
                                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                                
                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id,$dataArray,$ShipArr,$itemData,$super_id);
                               $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' Data updated successfully.';
                                array_push($succssArray, $slipNo);
                        }                            
                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }
                     elseif ($company== 'MomentsKsa')
                       {
                        
                        $Auth_token=$this->Ccompany_model->Moments_auth($counrierArr); 
                      
                        $responseArray = $this->Ccompany_model->MomentsArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$complete_sku,$super_id);  
                        
                        $successres = $responseArray['errors'];                         
                        
                        $error_status = $responseArray['message'];

                        if (empty($successres))
                        {

                            $client_awb = $responseArray['TrackingNumber'];
                            $MomentLabel = $responseArray['printLableUrl'];
                             
                            $generated_pdf = file_get_contents($MomentLabel);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id,$dataArray,$ShipArr,$itemData,$super_id);
                            $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' Data updated successfully.';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    }
                    elseif ($company== 'Postagexp')
                       {
                        
                        $Auth_token=$this->Ccompany_model->Postagexp_auth($counrierArr); 
                      
                        $responseArray = $this->Ccompany_model->PostagexpArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1,$complete_sku,$super_id); 
                        $successres = $responseArray['errors'];                         
                        $error_status = $responseArray['message'];

                        if (empty($successres))
                        {

                            $client_awb = $responseArray['TrackingNumber'];
                            $PostagexpLabel = $responseArray['printLable'];
                             
                            $generated_pdf = file_get_contents($PostagexpLabel);
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");                               

                            $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel, $c_id,$dataArray,$ShipArr,$itemData,$super_id);
                            $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to Postagexp.';
                            array_push($succssArray, $slipNo);
                        }                            
                        else
                        {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    }elseif ($company == 'Bosta'){
                        
                            $tokenResponse =  $this->Ccompany_model->Bosta_token_api($counrierArr);
                            if($tokenResponse['success'] === true){
                                    $token = $tokenResponse['token'];
                                    
                                    $api_response = $this->Ccompany_model->BostaArray($sellername, $ShipArr, $counrierArr,$token, $complete_sku, $box_pieces1,$c_id,$super_id);
                                    if($api_response['error'] == FALSE){
                                         $client_awb = $api_response['data']['_id'];
                                         $lableInfo =  $this->Ccompany_model->Bosta_Label_api($counrierArr, $token,$client_awb);
                                         $bostaLabel = '';
                                         if(!empty($lableInfo['data'])){
                                            $encoded = base64_decode($lableInfo['data']);
                                             header('Content-Type: application/pdf');
                                             file_put_contents("assets/all_labels/$slipNo.pdf", $encoded);

                                            $bostaLabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                                         }

                                         $CURRENT_DATE = date("Y-m-d H:i:s");
                                         $CURRENT_TIME = date("H:i:s");

                                         $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $bostaLabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                         $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to Bosta.';
                                         array_push($succssArray, $slipNo);

                                    }else{
                                        $returnArr['responseError'] = $slipNo . ':' .$api_response['data']['message'];
                                    }

                            }else{
                               $returnArr['responseError'][] = $slipNo . ':Token Not Genrated'; 
                            }
                        
                    } elseif ($company== 'MICGO') { 
                            $Auth_token = $this->Ccompany_model->MICGO_AUTH($counrierArr);                         

                            $responseArray = $this->Ccompany_model->MICGOarray($sellername, $ShipArr, $counrierArr, $complete_sku,$c_id,$box_pieces1,$Auth_token,$super_id);  
                            $successres = $responseArray['error'];                        
                            $error_status = $responseArray['message'];
                        
                        if (empty($successres))
                        {
                            $client_awb = $responseArray['shipments'][0]['waybill'];
                            $Label = $responseArray['shipments'][0]['shippingLabelUrl'];
                             
                            $generated_pdf = file_get_contents($Label);
                            
                            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);                            
                            
                            $micGoLabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                            $CURRENT_DATE = date("Y-m-d H:i:s");
                            $CURRENT_TIME = date("H:i:s");
                            $comment = 'MicGo Manifest Return CC';
                            $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $micGoLabel, $c_id, $dataArray, $ShipArr, $itemData, $super_id);
                            
                            $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to MICGO.';
                            
                            array_push($succssArray, $slipNo);
                            
                        } else {
                            $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                        }
                    
                    } elseif ($company == 'SLS') {
                        $responseArray = $this->Ccompany_model->SLSArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1,$c_id, $super_id);
                        
                       //  echo "<pre>" ; print_r($responseArray); //die;
                        $successres = $responseArray['status'];
                        $error_status = json_encode($responseArray);

                            if (!empty($successres) && $successres == 1)
                            {
                                $client_awb = $responseArray['tracking_number'];
                                $SLSLabel = $this->Ccompany_model->SLS_label($client_awb, $counrierArr);
                                   
                                file_put_contents("assets/all_labels/$slipNo.pdf", $SLSLabel);                            
                                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                                $CURRENT_DATE = date("Y-m-d H:i:s");
                                $CURRENT_TIME = date("H:i:s");

                                $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                                $returnArr['Success_msg'][] = 'AWB No.' . $slipNo . ' : forwarded to SLS.';
                                array_push($succssArray, $slipNo);
                            }
                            
                            else
                            {
                                $returnArr['responseError'][] = $slipNo . ':' .$error_status;
                            }
            }elseif ($company_type== 'F'){ // for all fastcoo clients treat as a CC 
                      
                $response = $this->Ccompany_model->fastcooArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token,$c_id,$box_pieces1, $super_id);
                $responseArray = json_decode($response, true);                    
               
                if ($responseArray['status']==200) 
                {  
                    $client_awb = $responseArray['awb_no'];                                
                    $mediaData = $responseArray['label_print'];
                    //****************************fastcoo arrival label print cURL****************************
                      file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                      $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    //****************************fastcoo label print cURL****************************
                     $CURRENT_DATE = date("Y-m-d H:i:s");
                     $CURRENT_TIME = date("H:i:s");

                    $Update_data = $this->Ccompany_model->Update_Manifest_Return_Status($slipNo, $client_awb, $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $fastcoolabel,$c_id,$dataArray,$ShipArr,$itemData,$super_id);
                   array_push($succssArray, $slipNo);
                  $returnArr['Success_msg'][] = 'Data updated successfully.';
                }   
                else
                {
                    switch($responseArray['status']){
                                    case '129': $description = $responseArray['msg']; break;
                                    case '201': $description = $responseArray['msg']; break;
                                    case '400': $description = $responseArray['msg']; break;
                                    default : $description = 'Response Not Found.'; break;
                                }
                    $returnArr['responseError'][] = $slipNo . ':' . $description;
                    //array_push($alreadyExist, $slipNo);   
                    
                }                                    
            }

            else
            {
                    $description = json_encode(array('company_type'=>$company_type,"error"=>$company ." Forwarding Code Not Exist."));
                    $status = "Fail";
                            
                    $this->Ccompany_model->shipmentLog($c_id, $description, $status, $slipNo);
                    $returnArr['responseError'][] = $slipNo . ':' . $company ." Forwarding Code Not Exist.";                
                    array_push($invalid_slipNO,$slipNo);
                
            }

       return $returnArr;   
            
        
    }
    
    public function manifestPrint($uniqueid = null) {



       // $view['traking_awb_no'] = array($uniqueid);
        $status_update_data= $this->Manifest_model->GetallcustomerManifestResults($uniqueid);
 // print_r($pickUpId); die;
 $this->load->helper('pdf_helper');
 //$this->load->library('pagination');
 $this->load->library('M_pdf');

 $data['pickupId'] = $uniqueid;

 $CURRENT_DATE = date("Y-m-d H:i:s");
 $destination = getdestinationfieldshow($status_update_data[0]['city'], 'city');
//  echo '<pre>';

// print_r( $status_update_data); die();
 if (!empty($status_update_data)) {

     // echo $this->config->item('base_url_super').site_configTable('logo'); die;
     // echo site_configTable('logo'); die;
     $html .= '<!doctype html><html><head><meta charset="utf-8">';
     $html .= '<title>Menifest </title> ';

     $html .= '<style>.invoice-box {
                                     max-width: 100%;
                                     margin: auto;
                                     padding: 10px;
                                     border: 1px solid #eee;
                                     box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                                     font-size: 12px;
                                     line-height: 24px;
                                     font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                                     color: #555;
                                     height:850px
                             }
                 
                     .invoice-box table {
                             width: 100%;
                             line-height: inherit;
                             text-align: left;
                     }

                     .invoice-box table td {
                             padding: 5px;
                             vertical-align: top;
                     }

                     .invoice-box table tr td:nth-child(2) {
                             text-align: right;
                     }

                     .invoice-box table tr.top table td {
                             padding-bottom: 20px;
                     }

                     .invoice-box table tr.top table td.title {
                             font-size: 45px;
                             line-height: 45px;
                             color: #333;
                     }

                     .invoice-box table tr.information table td {
                             padding-bottom: 40px;
                     }

                     .invoice-box table tr.heading td {
                             background: #eee;
                             border-bottom: 1px solid #ddd;
                             font-weight: bold;
                     }

                     .invoice-box table tr.details td {
                             padding-bottom: 20px;
                     }

                     .invoice-box table tr.item td{
                             border-bottom: 1px solid #eee;
                     }

                     .invoice-box table tr.item.last td {
                             border-bottom: none;
                     }

                     .invoice-box table tr.total td:nth-child(2) {
                             border-top: 2px solid #eee;
                             font-weight: bold;
                     }

                     @media only screen and (max-width: 600px) {
                             .invoice-box table tr.top table td {
                                     width: 100%;
                                     display: block;
                                     text-align: center;
                             }

                             .invoice-box table tr.information table td {
                                     width: 100%;
                                     display: block;
                                     text-align: center;
                             }
                     }
                     table {
                             font-family: arial, sans-serif;

                     }

                     td, th {


                     } 
                     /** RTL **/
                     .rtl {
                             direction: rtl;
                             font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                     }

                     .rtl table {
                             text-align: right;
                     }

                     .rtl table tr td:nth-child(2) {
                             text-align: left;
                     }
                     .margin_top{
                             margin-top:100%;
                     }
                     .footer {
                        position: fixed;
                        left: 250px;
                        bottom: 0;
                        width: 100%;

                        color: #000;
                        text-align: center;
                     }
                     #signaturetitle { 
                       font-weight: bold;
                       font-size: 100%;
                     }

                     #signature {
                       text-align: center;
                       height: 30px;
                       word-spacing: 1px;
                     }
                     </style>
 </head> <body><div class="invoice-box"><table cellpadding="0" cellspacing="0"><tr class="top"><td colspan="6"><table><tr><td class="title"><img src="' .SUPERPATH . site_configTable('logo') . '" width="100"></td><td> 
                             Created: ' . date("F j, Y") . '<br> <br> <br> <br> 
                       ' . $status_update_data[0]['sender_name'] . '
                      <br>
                     Pickup Address:' . $status_update_data[0]['address'] . '<br>
                    Manifest ID: ' . $uniqueid . '<br> 
                           Schedule Date:  ' .$status_update_data[0]['schedule_date']  . '<br> 
</td>
                                                         </tr>
                                                 </table>
                                         </td>
                                 </tr> 

                 <tr class="heading">
                       
                         <td align="center">  SKU</td>
                         <td align="center">  Sku Img</td>
                         <td align="center">  Qty </td>
                         <td align="center">  Expire Date </td>
                         <td align="center">  Description </td>
                        
                 </tr> ';

        


     foreach ($status_update_data as $menifest) {
       
         $html .= '<tr class="item">  
             
            
             <td align="center">' . (!empty($menifest['sku']) ? $menifest['sku'] : 'N/A') . '</td>
             <td align="center">  <img src="'.base_url().getalldataitemtablesSKU($menifest['sku'],'item_path').'" width="65"></td>
             <td align="center">' . (!empty($menifest['qty']) ? $menifest['qty'] : 'N/A') . ' </td>
             <td align="center">' . (!empty($menifest['expire_date']) ? $menifest['expire_date'] : 'N/A') . '</td>
             <td align="center">' . (!empty($menifest['description']) ? $menifest['description'] : 'N/A') . '</td> 
                 </tr>';
     }
 
     $html .= '</table></div><br />';

    
   
    
     $html .= '
            
          

            
     </body>
</html>';

     //  echo $html; die;
     $mpdf = new mPDF('utf-8');
     $mpdf->WriteHTML($html);
     //$mpdf->SetDisplayMode('fullpage'); 
     //$mpdf->Output();
     $mpdf->Output('AWB_print.pdf', 'I');
 }
        
    }

}

?>