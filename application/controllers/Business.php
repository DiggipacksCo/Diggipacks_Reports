<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Business extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Status_model');
        $this->load->model('Business_model');
        $this->load->helper('zid');
        $this->load->helper('utility');
    }

    public function packing_b2b() {
        if (menuIdExitsInPrivilageArray(9) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('pickup/packing_b2b');
    }

    public function packFinish() {
     
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;

       $newslipArray=array();
         $newslipArray_sku=array();
       
          $boxArr = $dataArray['boxArr'];
        $SpecialArr = $dataArray['SpecialArr'];

        $slip_data = array();
        $Pickingcharge = array();
        $file_name = date('Ymdhis') . '.xls';
        $key = 0;

        $shippingArr = array();

        $reportArr = array();
        foreach ($_POST['exportData'] as $reportrows) {
            $entrydate = date('Y-m-d H:i:s');
            $insertskureport = array(
                'slip_no' => $reportrows['slip_no'],
                'sku' => $reportrows['sku'],
                'quantity' => $reportrows['piece'],
                'qty_scan' => $reportrows['scaned'],
                'qty_extra' => $reportrows['extra'],
                'updated_by' => $this->session->userdata('user_details')['user_id'],
                'entrydate' => $entrydate,
                'super_id' => $this->session->userdata('user_details')['super_id'],
            );
            array_push($reportArr, $insertskureport);
        }

        foreach ($dataArray['shipData'] as $data) {
            $check_exist=GetCheckpackStatus($data['slip_no']);
             
             if(empty($check_exist))
             {
            array_push($shippingArr, array('slip_no' => $data['slip_no']));
            array_push($slip_data, $data['slip_no']);
            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $statusvalue[$key]['slip_no'] = $data['slip_no'];
            $statusvalue[$key]['new_status'] = 4;
            $statusvalue[$key]['code'] = 'PK';
            $statusvalue[$key]['Activites'] = 'Order Packed';
            $statusvalue[$key]['Details'] = 'Order Packed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
            $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
            /* -------------/Status Array----------- */
            $picklistValueNew[$key]['slip_no'] = $data['slip_no'];
            $picklistValue[$key]['slip_no'] = $data['slip_no'];
            $picklistValue[$key]['packedBy'] = $this->session->userdata('user_details')['user_id'];
            $picklistValue[$key]['packDate'] = date('Y-m-d H:i:s');
            $picklistValue[$key]['pickupDate'] = date('Y-m-d H:i:s');
            $picklistValue[$key]['pickup_status'] = 'Y';
            $picklistValue[$key]['packFile'] = $file_name;
            if ($SpecialArr['specialpack'] == TRUE)
                $specialpack = "Y";
            else
                $specialpack = "N";

            $picklistValue[$key]['specialpack'] = $specialpack;
            $picklistValueNew[$key]['special_packaging'] = $specialpack;

            if ($SpecialArr['specialpacktype']) {
                $picklistValue[$key]['specialpacktype'] = $SpecialArr['specialpacktype'];
                $picklistValueNew[$key]['pack_type'] = $SpecialArr['specialpacktype'];
            } else {
                $picklistValue[$key]['specialpacktype'] = "";
                $picklistValueNew[$key]['pack_type'] = "";
            }

            if (!empty($boxArr['box_no'])) {
                $box_no = $boxArr['box_no'];
            } else {
                $box_no = 1;
            }

            $getallskuArray = $this->Business_model->GetallskuDataDetails($data['slip_no']);
            $totalPieces = $getallskuArray['pieces'];
            $seller_id = GetallCutomerBysellerId($getallskuArray['cust_id'], 'id');
            $PackagingCharge = getalluserfinanceRates($seller_id, 12, 'rate');
            $PinckingCharge = getalluserfinanceRates($seller_id, 13, 'rate');
            $special_packing_charge = getalluserfinanceRates($seller_id, 9, 'rate');
            $special_packing_charge_ware = getalluserfinanceRates($seller_id, 10, 'rate');
            $box_charge = getalluserfinanceRates($seller_id, 19, 'rate');
            $totalspecial_packing_charge = $special_packing_charge * 1; //* $totalPieces
            $totalpackaging = $PackagingCharge * 1; //$totalPieces
            $totalpacking = $PinckingCharge * 1; //$totalPieces
            $Pickingcharge[$key]['seller_id'] = $seller_id;
            $Pickingcharge[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
            $Pickingcharge[$key]['slip_no'] = $data['slip_no'];
            $picklistValue[$key]['tods_barcode'] = '';
            $picklistValue[$key]['weight'] = $boxArr['weight'];
            $picklistValueNew[$key]['no_of_boxes'] = $totalPieces;
            //  echo $data['box_no']."dddd". $box_no;
            if ($box_no > 0) {
                $totalbox_charge = $box_charge * $box_no;
                $Pickingcharge[$key]['box_charge'] = $totalbox_charge;
            }
            $Pickingcharge[$key]['packaging_charge'] = $totalpackaging;
            $Pickingcharge[$key]['picking_charge'] = $totalpacking;

            if ($SpecialArr['specialpacktype'] == 'seller')
                $Pickingcharge[$key]['special_packing_charge'] = $totalspecial_packing_charge;
            else
                $Pickingcharge[$key]['special_packing_charge'] = $special_packing_charge_ware;

            $Pickingcharge[$key]['entrydate'] = date("Y-m-d H:i:sa");
            $Pickingcharge[$key]['pieces'] = $totalPieces;


            $key++;
             }
        }

        //print_r($dataArray['exportData']); die;
        
        
        

        $statusvaluenew = array();
        // echo $dataArray['exportData'][0]['weight'].'//'.$boxArr['weight'];exit;
        if ($dataArray['exportData'][0]['weight'] != $boxArr['weight']) {
            //die;
            $statusvaluenew['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvaluenew['user_type'] = 'fulfillment';
            $statusvaluenew['slip_no'] = $dataArray['exportData'][0]['slip_no'];
            $statusvaluenew['new_status'] = 4;
            $statusvaluenew['code'] = 'PK';
            $statusvaluenew['Activites'] = 'Weight updated ';
            $statusvaluenew['Details'] = 'Weight updated from ' . $dataArray['exportData'][0]['weight'] . ' Kg  to ' . $boxArr['weight'] . ' Kg  by ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvaluenew['entry_date'] = date('Y-m-d H:i:s');
            $statusvaluenew['super_id'] = $this->session->userdata('user_details')['super_id'];

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
                'weight' => $boxArr['weight']
            );
        } else {

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
            );
        }


        $shipData = array();


        $shipData['where_in'] = $slip_data;
        $shipData['update'] = $updateArray;


        if (!empty($reportArr)) {
            $this->Business_model->generatescanreport($reportArr);
        }

        if ($this->Business_model->packOrder($picklistValue)) {
            //print_r($picklistValueNew);
            $this->Business_model->packOrderNew($picklistValueNew);
            // GetrequestShippongCompany($shippingArr);
            //echo  print_r($this->Status_model->insertStatus($statusvalue)); exit;
            if ($this->Status_model->insertStatus($statusvalue)) {
                //print_r($statusvalue);
                $this->Business_model->GetallDatapickingChargeAdded($Pickingcharge);

                $this->Shipment_model->updateStatus($shipData);
                if (!empty($statusvaluenew)) {
                    $this->Status_model->insertStatussingle($statusvaluenew);
                }
               
                 foreach($dataArray['exportData'] as $JJ=>$rows)
                {
              
                 if ($rows['piece'] != $rows['scaned']) {
                     
                    $statusvaluenew_piece[$JJ]['user_id'] = $this->session->userdata('user_details')['user_id'];
                    $statusvaluenew_piece[$JJ]['user_type'] = 'fulfillment';
                    $statusvaluenew_piece[$JJ]['slip_no'] = $rows['slip_no'];
                    $statusvaluenew_piece[$JJ]['new_status'] = 4;
                    $statusvaluenew_piece[$JJ]['code'] = 'PK';
                    $statusvaluenew_piece[$JJ]['Activites'] = 'Piece updated ';
                    $statusvaluenew_piece[$JJ]['Details'] = 'SKU '.$rows['sku'].' Piece updated from ' . $rows['piece'] . '  to ' . $rows['scaned'] . '  by ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                    $statusvaluenew_piece[$JJ]['entry_date'] = date('Y-m-d H:i:s');
                    $statusvaluenew_piece[$JJ]['super_id'] = $this->session->userdata('user_details')['super_id'];

                   
                    $update_array_dia=array("piece"=>$rows['scaned']);
                    $update_array_dia_w=array("sku"=>$rows['sku'],"slip_no"=>$rows['slip_no']);
                   // $this->Business_model->UpdateDiamation($update_array_dia,$update_array_dia_w);
                    

                 }   
                 
                   if(!in_array($rows['slip_no'],$newslipArray))
                     {
                        array_push($newslipArray,$rows['slip_no']);
                        array_push($newslipArray_sku,array('slip_no'=>$rows['slip_no'],'pieces'=>$rows['scaned']));
                      }
                     else
                     {
                      
                        $slip_key=array_search($rows['slip_no'], array_column($newslipArray_sku, 'slip_no'));
                        $newslipArray_sku[$slip_key]['pieces']=$newslipArray_sku[$slip_key]['pieces']+$rows['scaned'];
                         
                     }
                }
              
                
                if(!empty($newslipArray_sku))
                {
               // $this->Business_model->packOrderNew($newslipArray_sku);
                }
                
                if(!empty($statusvaluenew_piece))
                {
                     $this->Business_model->InsertStatusPieceData($statusvaluenew_piece);
                    
                }
                
                //$this->exportExcel($_POST['exportData'], $file_name)

                echo json_encode();
            }
        }
    }

    public function packCheck() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Business_model->pickListFilterNotPicked($_POST['slip_no']);
       // print_r($shipments); die;
        $ReturnArray = $shipments['result'];

        // print_r( $shipments); exit;
        foreach ($ReturnArray as $key => $val) {
            $frwd_company_id = GetshpmentDataByawb($val['slip_no'], 'frwd_company_id');
            $sku_details = json_decode($val['sku'], true);
            foreach ($sku_details as $newkey => $skudata) {
                $singleSku = $this->ItemInventory_model->GetshowingSkuMeadiaDataQry($skudata);
                $skuArrayDatap[$newkey]['sku'] = $skudata['sku'];
                $skuArrayDatap[$newkey]['piece'] = $skudata['piece'];
                $skuArrayDatap[$newkey]['cod'] = $skudata['cod'];
                if (file_exists($singleSku['item_path']) && $singleSku['item_path'] != '') {

                    $skuArrayDatap[$newkey]['item_path'] = base_url() . $singleSku['item_path'];
                } else {
                    $skuArrayDatap[$newkey]['item_path'] = base_url() . "assets/nfd.png";
                }
                // [{"sku":"TESTBOX","piece":"1","cod":"116.00"}]
            }
            $ReturnArray[$key]['sku'] = json_encode($skuArrayDatap);
            if ($frwd_company_id != '') {
                $ReturnArray[$key]['frwd_company_id'] = GetCourCompanynameId($frwd_company_id, 'company');
                $ReturnArray[$key]['frwd_company_awb'] = GetshpmentDataByawb($val['slip_no'], 'frwd_company_awb');
                $ReturnArray[$key]['print_url'] = base_url() . 'assets/all_labels/' . $val['slip_no'] . '.pdf';
            }
        }
      //  echo "ssss";
      //echo  count($ReturnArray);
        $return['result'] = $ReturnArray;
        $return['count'] =$shipments['count'];
        echo json_encode($return);
    }

}

?>