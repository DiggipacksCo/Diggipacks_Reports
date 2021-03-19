<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ItemInventory extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(2) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->model('ItemInventory_model');

        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function SkutranferaddView() {
        //  echo "ssssssss"; die;
        /* if(menuIdExitsInPrivilageArray(26)=='N')
          {
          redirect(base_url().'notfound'); die;
          } */
        $this->load->view('ItemI/sku_transfer');
    }
    public function inventory_check() {
      
        $this->load->view('ItemI/inventory_check');
    }

    
    public function StockTranferedlistview() {
        //  echo "ssssssss"; die;
        /* if(menuIdExitsInPrivilageArray(34)=='N')
          {
          redirect(base_url().'notfound'); die;
          } */
        $quantity = $this->ItemInventory_model->count_all();
        $sellers = $this->Seller_model->find1();
        // $data['items'] = $this->ItemInventory_model->all();
        $condition = null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }

        //$items = $this->ItemInventory_model->all();
        $bulk = array(
            //'items'=>$items,
            'sellers' => $sellers,
            'quantity' => $quantity,
            'condition' => $condition,
        );
        $this->load->view('ItemI/transferedlist', $bulk);
    }
    
    
    
    public function view_lessqty(){
		$quantity=$this->ItemInventory_model->count_all();
		$sellers=$this->Seller_model->find1();
		
	    // $data['items'] = $this->ItemInventory_model->all();
	    $condition=null;
		if($this->session->flashdata('condition'))
		{
			$condition=$this->session->flashdata('condition');
		}

	   	//$items = $this->ItemInventory_model->all();
	   	$bulk=array(
    		
    		//'items'=>$items,
    		'sellers'=>$sellers,
    		'quantity'=>$quantity,
    		'condition'=>$condition,
    	);
		$this->load->view('ItemI/view_lessqty',$bulk);
	}
        
        public function topdispatchproduct(){
		$quantity=$this->ItemInventory_model->count_all();
		$sellers=$this->Seller_model->find1();
		
	    // $data['items'] = $this->ItemInventory_model->all();
	    $condition=null;
		if($this->session->flashdata('condition'))
		{
			$condition=$this->session->flashdata('condition');
		}

	   	//$items = $this->ItemInventory_model->all();
	   	$bulk=array(
    		
    		//'items'=>$items,
    		'sellers'=>$sellers,
    		'quantity'=>$quantity,
    		'condition'=>$condition,
    	);
		$this->load->view('ItemI/view_topproduct',$bulk);
	}
	public function view_expirealert(){
		$quantity=$this->ItemInventory_model->count_all();
		$sellers=$this->Seller_model->find1();
		
	    // $data['items'] = $this->ItemInventory_model->all();
	    $condition=null;
		if($this->session->flashdata('condition'))
		{
			$condition=$this->session->flashdata('condition');
		}

	   	//$items = $this->ItemInventory_model->all();
	   	$bulk=array(
    		
    		//'items'=>$items,
    		'sellers'=>$sellers,
    		'quantity'=>$quantity,
    		'condition'=>$condition,
    	);
		$this->load->view('ItemI/view_expirealert',$bulk);
	}

    public function index() {

        $quantity = $this->ItemInventory_model->count_all();
        $sellers = $this->Seller_model->find1();
        $StorageType = $this->ItemInventory_model->GetstorageTypes();
        // $data['items'] = $this->ItemInventory_model->all();
        $condition = null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }


        //$items = $this->ItemInventory_model->all();
        $bulk = array(
            //'items'=>$items,
            'sellers' => $sellers,
            'quantity' => $quantity,
            'condition' => $condition,
            'StorageType' => $StorageType
        );
        $this->load->view('ItemI/view_iteminventory', $bulk);
    }
    public function shelve_report() {

        $quantity = $this->ItemInventory_model->count_all();
        $sellers = $this->Seller_model->find1();
        $StorageType = $this->ItemInventory_model->GetstorageTypes();
        // $data['items'] = $this->ItemInventory_model->all();
        $condition = null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }


        //$items = $this->ItemInventory_model->all();
        $bulk = array(
            //'items'=>$items,
            'sellers' => $sellers,
            'quantity' => $quantity,
            'condition' => $condition,
            'StorageType' => $StorageType
        );
        $this->load->view('ItemI/shelve_report', $bulk);
    }

    public function ViewTotalInventory() {

        $quantity = $this->ItemInventory_model->count_all();
        $sellers = $this->Seller_model->find1();
        $StorageType = $this->ItemInventory_model->GetstorageTypes();
        // $data['items'] = $this->ItemInventory_model->all();
        $condition = null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }


        //$items = $this->ItemInventory_model->all();
        $bulk = array(
            //'items'=>$items,
            'sellers' => $sellers,
            'quantity' => $quantity,
            'condition' => $condition,
            'StorageType' => $StorageType
        );

        $this->load->view('ItemI/view_iteminventory_total', $bulk);
    }

    public function historyview($item_sku=null,$seller_id=null) {
        $quantity = $this->ItemInventory_model->count_all();
        $sellers = $this->Seller_model->find1();
        // $data['items'] = $this->ItemInventory_model->all();
        $condition = null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }

        //$items = $this->ItemInventory_model->all();
        $bulk = array(
            //'items'=>$items,
            'sellers' => $sellers,
            'quantity' => $quantity,
            'condition' => $condition,
            'item_sku'=>$item_sku,
            'seller_id'=>$seller_id
        );
        // print_r($bulk); die;
        $this->load->view('ItemI/inventory_history', $bulk);
    }

    public function add_view() {

        $data = $this->ItemInventory_model->add_view();
        // print_r($data); die;
        $this->load->view('ItemI/add_iteminventory', $data);
    }

    public function add_bulk_view() {

        $data = $this->ItemInventory_model->add_view();
        $this->load->view('ItemI/add_bulk', $data);
    }

    public function inventoryaddConfirm() {
        $data['IData'] = $this->input->post();
        $tempdata = array('sku' => $this->input->post('sku'), 'message' => 'Thanks for joining!');
        $this->session->set_tempdata($tempdata, NULL, 500);
        $this->session->tempdata('sku');
        $this->load->view('ItemI/view_iconfirm', $data);
    }

    public function InboundRecord() {
        //echo 'xxxxx';exit;
        $quantity = $this->ItemInventory_model->count_all();
        $sellers = $this->Seller_model->find1();
        // $data['items'] = $this->ItemInventory_model->all();
        $condition = null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }

        //$items = $this->ItemInventory_model->all();
        $bulk = array(
            //'items'=>$items,
            'sellers' => $sellers,
            'quantity' => $quantity,
            'condition' => $condition,
        );

        // print_r($bulk);exit;
        $this->load->view('ItemM/InboundRecord', $bulk);
    }

    public function add() {

        $chargeQty = $this->input->post('quantity');
        $sku_size = getalldataitemtables($this->input->post('sku'), 'sku_size');
        $item_type = getalldataitemtables($this->input->post('sku'), 'type');
        $wh_id = $this->input->post('wh_id');

        // check invonry for empty space
        if (empty($this->input->post('expity_date')))
            $expdate = "0000-00-00";
        else
            $expdate = $this->input->post('expity_date');
        $dataNew = $this->ItemInventory_model->find(array('item_sku' => $this->input->post('sku'), 'expity_date' => $expdate, 'seller_id' => $this->input->post('seller'), 'itype' => $item_type, 'wh_id' => $wh_id));

        //print_r($dataNew); 
        $qty = $this->input->post('quantity');


        foreach ($dataNew as $val) {
            if ($val->quantity < $sku_size) {

                //echo '<br> 2//'.$qty.'//'. $val->quantity.'//';
                $check = $qty + $val->quantity;
                if ($check <= $sku_size) {

                    $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                    $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                    $lastQtyUp_up = $lastQtyUp;
                    $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $this->input->post('sku'), 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $this->input->post('seller'), 'qty' => $check, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"),'super_id' => $this->session->userdata('user_details')['super_id']);

                    GetAddInventoryActivities($activitiesArr);
                    $this->ItemInventory_model->updateInventory(array('quantity' => $check, 'id' => $val->id));
                    $qty = 0;
                } else {

                    $diff = $sku_size - $val->quantity;
                    $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                    $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                    $lastQtyUp_up = $lastQtyUp;
                    $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $this->input->post('sku'), 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $this->input->post('seller'), 'qty' => $sku_size, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"),'super_id' => $this->session->userdata('user_details')['super_id']);

                    GetAddInventoryActivities($activitiesArr);
                    $this->ItemInventory_model->updateInventory(array('quantity' => $sku_size, 'id' => $val->id));
                    $qty = $qty - $diff;
                }
            }

            // echo $val['item_sku'];  
        }

        //  echo '<br>'. $qty; exit;
        if ($qty > 0) {
            if ($sku_size >= $qty)
                $locationLimit = 1;
            else {
                $locationLimit1 = $qty / $sku_size;
                $locationLimit = ceil($locationLimit1);
            }


            if (!empty($this->input->post('stock_location')) && $locationLimit > 0) {

                $locationLimit;
                $stocklocation = $this->input->post('stock_location');
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

                    $data[] = array(
                        'itype' => $item_type,
                        'item_sku' => $this->input->post('sku'),
                        'seller_id' => $this->input->post('seller'),
                        'quantity' => $AddQty,
                        'update_date' => date("Y/m/d h:i:sa"),
                        'stock_location' => $stocklocation[$ii],
                        'wh_id' => $this->input->post('wh_id'),
                        'expity_date' => $this->input->post('expity_date'),
                        'super_id' => $this->session->userdata('user_details')['user_id']
                    );
                }
                //	echo '<pre>';
                //print_r($data);
                // die;

                $this->ItemInventory_model->add($data);
                //die;
                //  $this->ItemInventory_model->addHistoryInbound($data);
                if ($item_type == 'B2B')
                    $this->ItemInventory_model->GetUpdatePickupchargeInvocie($this->input->post('seller'), $locationLimit, $this->input->post('quantity'), $this->input->post('sku'));
                else
                    $this->ItemInventory_model->GetUpdatePickupchargeInvocie($this->input->post('seller'), $chargeQty, $this->input->post('quantity'), $this->input->post('sku'));

                //die;
                redirect(base_url('ItemInventory'));
            } else {


                $this->session->set_flashdata('error', 'Please Select ' . $locationLimit . " Location");
                redirect(base_url('ItemInventory/add_view'));
            }
        } else {
            if ($item_type == 'B2B')
                $this->ItemInventory_model->GetUpdatePickupchargeInvocieUpdateQty($this->input->post('seller'), $locationLimit, $this->input->post('quantity'), $this->input->post('sku'));
            else
                $this->ItemInventory_model->GetUpdatePickupchargeInvocieUpdateQty($this->input->post('seller'), $qty, $this->input->post('quantity'), $this->input->post('sku'));
            redirect(base_url('ItemInventory'));
        }
    }

    public function edit_view($id) {

        $item1 = $this->ItemInventory_model->edit_view($id);

        $item = $item1[0];
        $this->load->view('ItemI/iteminventory_detail', [
            'item' => $item,
        ]);
    }

    public function edit($id) {
        $array = array(
            'id' => $id,
        );
        $previous_data = $this->ItemInventory_model->find($array);


        $data = array(
            'quantity' => $this->input->post('quantity'),
            'update_date' => date("Y/m/d h:i:sa")
        );
        $item = $this->ItemInventory_model->edit($id, $data, $previous_data);

        redirect(base_url('ItemInventory'));
    }

    public function getInventory() {

        $data = array(
            'item_sku' => $this->input->post('item_sku'),
            'seller_id' => $this->input->post('seller')
        );

        return $this->ItemInventory_model->getInventory($data);
        // print_r($iteminventory_detail.length());
        // exit();
        //$data=$this->Item_model->
    }

    public function getInventory2() {

        $data = array(
            'seller_id' => $this->input->post('seller')
        );

        return $this->ItemInventory_model->getInventory2($data);
        // print_r($iteminventory_detail.length());
        // exit();
        //$data=$this->Item_model->
    }

    public function filterRecord() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];



        $items = $this->ItemInventory_model->filterRecord($quantity, $sku, $seller, $to, $from, $exact, $page_no);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($ItemArray as $rdata) {
            $ItemArray[$kk]['itype'] = getalldataitemtablesSKU($rdata['sku'], 'type');
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['entrydate']));
            $ItemArray[$kk]['enable'] = 0;
            $ItemArray[$kk]['new_qty'] = $ItemArray[$kk]['qty_count'];
            $ItemArray[$kk]['diff'] = 0;
            $ItemArray[$kk]['current_date'] = date('Y-m-d');

            $kk++;
        }
        //print_r($ItemArray);
        $returnArray['result'] = $ItemArray;
        $returnArray['count'] = $items['count'];
        echo json_encode($returnArray);
    }

    public function GetUpdatePalletNoData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $palletno = $_POST['palletno']; //'A3/S0/2';//;
        $tid = $_POST['tid']; //'519';//
        $sid = $_POST['sid']; //'12';//
        $PalletsCheck = $this->ItemInventory_model->GetcheckvalidPalletNo($palletno);
        if ($PalletsCheck == true) {
            $PalletArrayI = $this->ItemInventory_model->GetcheckPalletInventry($palletno, $sid);
            // print_r($PalletArrayI);
            if (!empty($PalletArrayI)) {

                if ($sid == $PalletArrayI['seller_id']) {
                    $updateArray = array('shelve_no' => $palletno);
                    $PalletArrayI = $this->ItemInventory_model->UpdateInventoryPallet($updateArray, $tid);
                    $return = $PalletArrayI;
                } else {
                    $return = 301;
                }
            } else {
                $updateArray = array('shelve_no' => $palletno);
                $PalletArrayI = $this->ItemInventory_model->UpdateInventoryPallet($updateArray, $tid);
                $return = $PalletArrayI;
            }
        } else {
            $return = 302;
        }


        echo json_encode($return);
    }

    public function filter() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); die;
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $shelve_no = $_POST['shelve_no'];
        $storage_id = $_POST['storage_id'];



        $items = $this->ItemInventory_model->filter($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no, $storage_id, $_POST);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));

            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';
            $ItemArray[$kk]['item_type'] = getalldataitemtables($rdata['item_sku'], 'type');
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield(getalldataitemtables($rdata['item_sku'], 'storage_id'), 'storage_type');
            //if($rdata['shelve_no']!='')

            $ItemArray[$kk]['wh_name'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');

            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($palletArray);
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        echo json_encode($returnArray);
    }

    public function filter_totalview() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); die;
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $shelve_no = $_POST['shelve_no'];
        $storage_id = $_POST['storage_id'];



        $items = $this->ItemInventory_model->filter_totalview($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no, $storage_id);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));

            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';
            $ItemArray[$kk]['item_type'] = getalldataitemtables($rdata['item_sku'], 'type');
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield(getalldataitemtables($rdata['item_sku'], 'storage_id'), 'storage_type');
            //if($rdata['shelve_no']!='')

            $ItemArray[$kk]['wh_name'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');

            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($palletArray);
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        echo json_encode($returnArray);
    }

    public function filter_history() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $shelve_no = $_POST['shelve_no'];
        $slip_no = $_POST['slip_no'];
        $type = $_POST['status'];



        $items = $this->ItemInventory_model->filter_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no, $type);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));

            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';
            $ItemArray[$kk]['item_type'] = getalldataitemtables($rdata['item_sku'], 'type');
            //if($rdata['shelve_no']!='')
            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($palletArray);
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        $returnArray['count'] = $items['count'];
        echo json_encode($returnArray);
    }

    public function GetFieldnameinventory() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $skuname = getalldataitemtables($_POST['sku'], 'sku');
        $sellerName = getallsellerdatabyID($_POST['seller_id'], 'name');
        $returnArray = array('skuname' => $skuname, 'sellerName' => $sellerName);
        echo json_encode($returnArray);
    }

    public function showiteminventoryexport() {

        $_POST = json_decode(file_get_contents('php://input'), true);



        $dataArray = $_POST;
        $slip_data = array();
        $file_name = 'Items_Inventory' . date('Ymdhis') . '.xls';

        //echo json_encode($dataArray); die;
        //echo json_encode($_POST);exit;
        $key = 0;
        echo json_encode($this->exportiteminventoryexport($dataArray, $file_name));
    }

    public function updateQty() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); die;

        $itype = $_POST['itype'];
        $sku_size = $_POST['size'];
        $id = $_POST['id'];
        $qty_count = $_POST['qty_count'];
        if ($itype == 'B2C')
            $no_of_pallets = $_POST['qty_count'];
        else
            $no_of_pallets = $_POST['no_of_pallets'];
        $sku_size = $_POST['size'];
        $inbound_charge = $_POST['inbound_charge'];
        $inventory_charge = $_POST['inventory_charge'];
        $sku_id = $_POST['sku_id'];
        $sid = $_POST['sid'];
        $diff = $_POST['diff'];


        // GetUpdatePickupchargeInvocie
        if ($itype == 'B2C') {

            $noofpallets = $no_of_pallets;
            $SingleInboundChage = getalluserfinanceRates($sid, 6, 'rate');
            $Singleinventory_charge = getalluserfinanceRates($sid, 14, 'rate');
            $totalpallets = $noofpallets * $noofpallets;

            $totalInboundChage = $SingleInboundChage * $noofpallets;
            $totalinventoryCharge = $Singleinventory_charge * $noofpallets;
            $inbound_charge = $totalInboundChage;
            $qty_count = $qty_count;

            $inventory_charge = $totalinventoryCharge;
        } else {
            $inbound_charge = $inbound_charge;
            $qty_count = $qty_count;
            $no_of_pallets = $no_of_pallets;
            $inventory_charge = $inventory_charge;
        }
        $orderpickupinvoiceArray = array("inbound_charge" => $inbound_charge, 'qty_count' => $qty_count, 'no_of_pallets' => $no_of_pallets, 'inventory_charge' => $inventory_charge);
        $orderpickupinvoiceArray_where = array("id" => $id);

        //print_r($orderpickupinvoiceArray); die;
        //$this->ItemInventory_model->GetUpdateOrderafterQty($InventryArray,$InventryArrayWhere);
        if ($this->ItemInventory_model->GetUpdateOrderafterInboundCharge($orderpickupinvoiceArray, $orderpickupinvoiceArray_where)) {

            $skuData = $this->ItemInventory_model->getInventoyByLimit($sid, $sku_id);


            foreach ($skuData as $dataNew) {
                if ($diff > 0) {


                    if ($diff > $dataNew['quantity']) {

                        $diff = $diff - $dataNew['quantity'];
                        $this->ItemInventory_model->deleteSku($dataNew['id']);
                    } else {
                        // echo '<br>2';
                        $diff1 = $dataNew['quantity'] - $diff;
                        if ($diff1 == 0) {
                            $diff = 0;
                            // echo '<br>3';
                            $this->ItemInventory_model->deleteSku($dataNew['id']);
                        } else {
                            $diff = $diff - $dataNew['quantity'];
                            // echo '<br>4';  
                            $activitiesArr = array('exp_date' => $dataNew['expity_date'], 'st_location' => $dataNew['stock_location'], 'item_sku' => $sku_id, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $sid, 'qty' => $qty_count, 'p_qty' => $dataNew['quantity'], 'qty_used' => $qty_count, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"));
                            GetAddInventoryActivities($activitiesArr);

                            $this->ItemInventory_model->updateInventory(array('id' => $dataNew['id'], 'quantity' => $diff1));
                        }
                    }
                }
            }
        }

        echo json_encode($skuData);
    }

    public function GetallStockLocation() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $quantity = $_POST['quantity'];
        $seller_id = $_POST['seller_id'];
        $skuID = $_POST['sku'];
        $sku_size = getalldataitemtables($skuID, 'sku_size');
        if ($sku_size >= $quantity)
            $locationLimit = 1;
        else {
            $locationLimit1 = $quantity / $sku_size;
            $locationLimit = ceil($locationLimit1);
        }

        $result = $this->ItemInventory_model->Getallstocklocationdata($seller_id, $locationLimit);

        $returnarray = $result;
        $returnarray['CountLocation'] = $locationLimit;
        echo json_encode($returnarray);
    }

    public function GetstocklocationDataDrop() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $seller_id = $_POST['sid'];
        $tbl_id = $_POST['id'];
        $result = $this->ItemInventory_model->Getallstocklocationdata_viewpage($seller_id);
        echo json_encode($result);
    }

    public function GetupdateStockLocationData() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $seller_id = $_POST['sid'];
        $tbl_id = $_POST['id'];
        $locationUp = $_POST['locationUp'];

        $result = $this->ItemInventory_model->GetUpdateStockLocation($_POST);
        echo json_encode($result);
    }

    public function GetUpdateQtyInventry() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $newqty = $_POST['newqty'];
        $tbl_id = $_POST['id'];
        $qty = $_POST['quantity'];
        $sid = $_POST['sid'];
        $date = $_POST['update_date'];
        $item_sku = $_POST['item_sku'];

        $sku_size = getalldataitemtables($item_sku, 'sku_size');
        $StockLocations = $this->ItemInventory_model->GetalllocationsData();
        if ($sku_size >= $newqty) {
            //$locationLimit=1;
            $locationLimit1 = $newqty / $sku_size;
            $locationLimit = ceil($locationLimit1);

            $SingleInboundChage = getalluserfinanceRates($sid, 6, 'rate');
            $Singleinventory_charge = getalluserfinanceRates($sid, 14, 'rate');


            $totalInboundChage = $SingleInboundChage * $locationLimit;
            $totalinventoryCharge = $Singleinventory_charge * $locationLimit;

            $InventryArray = array("quantity" => $newqty);
            $InventryArrayWhere = array("seller_id" => $sid, "id" => $tbl_id, "quantity" => $qty);

            $orderpickupinvoiceArray = array("inbound_charge" => $totalInboundChage, 'qty_count' => $newqty, 'inventory_charge' => $totalinventoryCharge);
            $orderpickupinvoiceArray_where = array("seller_id" => $sid, 'entrydate' => $date);
            //$this->ItemInventory_model->GetUpdateOrderafterQty($InventryArray,$InventryArrayWhere);
            //$this->ItemInventory_model->GetUpdateOrderafterInboundCharge($orderpickupinvoiceArray,$orderpickupinvoiceArray_where);

            $return = true;
        } else
            $return = false;

        //$result=$this->ItemInventory_model->GetUpdateStockLocation($_POST);
        echo json_encode($orderpickupinvoiceArray_where);
    }

    function exportiteminventoryexport($dataEx, $file_name) {
        $dataArray = array();
        $i = 0;
        foreach ($dataEx as $data) {
            $dataArray[$i]['name'] = $data['name'];
            $dataArray[$i]['sku'] = $data['sku'];
            $dataArray[$i]['stock_location'] = $data['stock_location'];
            $dataArray[$i]['shelve_no'] = $data['shelve_no'];
            $dataArray[$i]['quantity'] = $data['quantity'];
            $dataArray[$i]['seller_name'] = $data['seller_name'];
            $dataArray[$i]['item_description'] = $data['item_description'];
            $dataArray[$i]['update_date'] = $data['update_date'];

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
                ->setCellValue('A1', 'Name')
                ->setCellValue('B1', 'Item Sku')
                ->setCellValue('C1', 'Stock Location')
                ->setCellValue('D1', 'Shelve No.')
                ->setCellValue('E1', 'Quantity')
                ->setCellValue('F1', 'Seller')
                ->setCellValue('G1', 'Description')
                ->setCellValue('H1', 'Update date');
        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');
        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        return $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );
    }

    function exportexcelinventory() {
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST); die;
        $exportlimit = $_POST['exportlimit'];
        $shipmentsexcel = $this->ItemInventory_model->filterexcelinventory($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        // print_r($shiparray1);
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;



        $dataArray = $shiparray1;

        $DatafileArray = array();
        $i = 0;
        foreach ($dataArray as $data) {

            $DatafileArray[$i]['name'] = $data['name'];
            $DatafileArray[$i]['item_type'] = getalldataitemtables($data['item_sku'], 'type');
            $DatafileArray[$i]['storage_id'] = Getallstoragetablefield(getalldataitemtables($data['item_sku'], 'storage_id'), 'storage_type');
            $DatafileArray[$i]['sku'] = $data['sku'];
            $DatafileArray[$i]['stock_location'] = $data['stock_location'];
            $DatafileArray[$i]['shelve_no'] = $data['shelve_no'];
            $DatafileArray[$i]['quantity'] = $data['quantity'];
            $DatafileArray[$i]['seller_name'] = $data['seller_name'];
            $DatafileArray[$i]['item_description'] = $data['item_description'];
            $DatafileArray[$i]['update_date'] = $data['update_date'];


            if ($data['expiry'] == 'Y')
                $DatafileArray[$i]['expiry'] = 'Yes';
            else
                $DatafileArray[$i]['expiry'] = 'No';

            if ($data['expity_date'] != '0000-00-00')
                $DatafileArray[$i]['expity_date'] = $data['expity_date'];
            else
                $DatafileArray[$i]['expity_date'] = '----';

            $i++;
        }
        //echo '<pre>';
//	print_r($dataArray); die;

        array_unshift($DatafileArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($DatafileArray);
        $from = "A1"; // or any value
        $to = "L1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Name')
                ->setCellValue('B1', 'Item Type')
                ->setCellValue('C1', 'Storage Type')
                ->setCellValue('D1', 'Item Sku')
                ->setCellValue('E1', 'Stock Location')
                ->setCellValue('F1', 'Pallet No.')
                ->setCellValue('G1', 'Quantity')
                ->setCellValue('H1', 'Seller')
                ->setCellValue('I1', 'Description')
                ->setCellValue('J1', 'Update date')
                ->setCellValue('K1', 'Expire Status')
                ->setCellValue('L1', 'Expire Date');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));
    }

    function exportexcelinventory_totalView() {
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST); die;
        $exportlimit = $_POST['exportlimit'];
        $shipmentsexcel = $this->ItemInventory_model->filterexcelinventory_totalView($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        // print_r($shiparray1);
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;



        $dataArray = $shiparray1;

        $DatafileArray = array();
        $i = 0;
        foreach ($dataArray as $data) {


            $DatafileArray[$i]['item_type'] = getalldataitemtables($data['item_sku'], 'type');
            $DatafileArray[$i]['storage_id'] = Getallstoragetablefield(getalldataitemtables($data['item_sku'], 'storage_id'), 'storage_type');
            $DatafileArray[$i]['sku'] = $data['sku'];
            $DatafileArray[$i]['quantity'] = $data['quantity'];
            $DatafileArray[$i]['seller_name'] = $data['seller_name'];


            $i++;
        }
        //echo '<pre>';
//	print_r($dataArray); die;

        array_unshift($DatafileArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($DatafileArray);
        $from = "A1"; // or any value
        $to = "L1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Item Type')
                ->setCellValue('B1', 'Storage Type')
                ->setCellValue('C1', 'Item Sku')
                ->setCellValue('D1', 'Quantity')
                ->setCellValue('E1', 'Seller');


        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));
    }

    function exportexcelhistoinventory() {
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exportlimit = $_POST['exportlimit'];
        $shipmentsexcel = $this->ItemInventory_model->filterexcelhistinventory($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;



        $dataArray = $shiparray1;

        $DatafileArray = array();
        $i = 0;
        foreach ($dataArray as $data) {

            $DatafileArray[$i]['sku'] = $data['sku'];
            $DatafileArray[$i]['p_qty'] = $data['p_qty'];
            $DatafileArray[$i]['qty_used'] = $data['qty_used'];
            $DatafileArray[$i]['qty'] = $data['qty'];
            $DatafileArray[$i]['seller_name'] = $data['seller_name'];

            if ($data['type'] == 'deducted')
                $DatafileArray[$i]['updated_by'] = $data['seller_name'];
            else
                $DatafileArray[$i]['updated_by'] = $data['username'];

            $DatafileArray[$i]['entrydate'] = $data['entrydate'];
            $DatafileArray[$i]['type'] = $data['type'];
            $DatafileArray[$i]['awb_no'] = $data['awb_no'];



            $i++;
        }
        array_unshift($DatafileArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($DatafileArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Item Sku')
                ->setCellValue('B1', 'Previous Quantity')
                ->setCellValue('C1', 'New Quantity')
                ->setCellValue('D1', 'Used Quantity')
                ->setCellValue('E1', 'Seller')
                ->setCellValue('F1', 'Updated By')
                ->setCellValue('G1', 'Date')
                ->setCellValue('H1', 'Status')
                ->setCellValue('I1', 'AWB');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));
    }

    public function showiteminventoryexport_history() {

        $_POST = json_decode(file_get_contents('php://input'), true);



        $dataArray = $_POST;
        $slip_data = array();
        $file_name = 'Items_Inventory' . date('Ymdhis') . '.xls';

        //echo json_encode($dataArray); die;
        //echo json_encode($_POST);exit;
        $key = 0;
        echo json_encode($this->exportiteminventoryexport_history($dataArray, $file_name));
    }

    function exportiteminventoryexport_history($dataEx, $file_name) {
        $dataArray = array();
        $i = 0;
        foreach ($dataEx as $data) {

            $dataArray[$i]['sku'] = $data['sku'];
            $dataArray[$i]['p_qty'] = $data['p_qty'];
            $dataArray[$i]['qty'] = $data['qty'];
            $dataArray[$i]['seller_name'] = $data['seller_name'];

            if ($data['type'] == 'deducted')
                $dataArray[$i]['updated_by'] = $data['seller_name'];
            else
                $dataArray[$i]['updated_by'] = $data['username'];

            $dataArray[$i]['entrydate'] = $data['entrydate'];
            $dataArray[$i]['type'] = $data['type'];
            $dataArray[$i]['awb_no'] = $data['awb_no'];



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
                ->setCellValue('A1', 'Item Sku')
                ->setCellValue('B1', 'Previous Quantity')
                ->setCellValue('C1', 'New Quantity')
                ->setCellValue('D1', 'Seller')
                ->setCellValue('E1', 'Updated By')
                ->setCellValue('F1', 'Date')
                ->setCellValue('G1', 'Status')
                ->setCellValue('H1', 'AWB');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');
        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        return $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );
    }

    public function GetDeleteInvenntory() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $table_id = $_POST['id'];
        $return = $this->ItemInventory_model->GetdeleteRunQuery($table_id);
        print_r($return);
    }

    public function GetUpdateMissingOrDamgeQty() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST);
        $QtyUp = $_POST['quantity'] - $_POST['upquantity'];
        $inventoryUpdateArr = array(
            'quantity' => $QtyUp,
            'update_date' => date("Y/m/d h:i:sa"));
        //'updated_by'=>$this->session->userdata('user_details')['user_id']);

        $inventoryHistoryUodate = array(
            'seller_id' => $_POST['sid'],
            'qty' => $QtyUp,
            'item_sku' => $_POST['item_sku'],
            'p_qty' => $_POST['quantity'],
            'type' => $_POST['updateType'],
            'exp_date' => $_POST['expity_date'],
            'st_location' => $_POST['stock_location'],
            'entrydate' => date("Y/m/d h:i:s"),
            'qty_used' => $_POST['upquantity'],
            //'awb_no'=>$_POST['awb_no'],
            //'update_date' => date("Y/m/d h:i:sa"),
            'user_id' => $this->session->userdata('user_details')['user_id']);
        //print_r($inventoryHistoryUodate); die;
        GetAddInventoryActivities($inventoryHistoryUodate);
        $return = $this->ItemInventory_model->UpdateInventoryMissing($inventoryUpdateArr, $_POST['id']);
        echo json_encode($return);
    }

    public function GetsellerDropDataFrom() {
        // $this->load->model('Seller_model');
        $return = $this->ItemInventory_model->SellerDropDataQry();
        echo json_encode($return);
    }

    public function GetshowStockLocationToseller() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST);
        $inventory_id = $_POST['fi_id'];

        $quantity = $_POST['qty'];
        $seller_id = $_POST['to_id'];
        $skuID = $_POST['titem_sku'];
        if ($quantity > 0 && $inventory_id && $seller_id && $skuID) {
            $InventoryArr = GetinventoryTableData($inventory_id);
            if ($InventoryArr['quantity'] >= $quantity) {
                $sku_size = getalldataitemtables($skuID, 'sku_size');
                if ($sku_size >= $quantity)
                    $locationLimit = 1;
                else {
                    $locationLimit1 = $quantity / $sku_size;
                    $locationLimit = ceil($locationLimit1);
                }

                $result = $this->ItemInventory_model->Getallstocklocationdata($seller_id, $locationLimit);
                //print_r($result); die; 
                //$returnarray=$result;
                $returnarray['CountLocation'] = $locationLimit;
                $returnarray['stocklocation'] = $result;
                $returnarray['error'] = "00";
            } else {
                $returnarray['CountLocation'] = $locationLimit;
                $returnarray['stocklocation'] = $result;
                $returnarray['error'] = "302";
            }
        } else {
            $returnarray['CountLocation'] = $locationLimit;
            $returnarray['stocklocation'] = $result;
            $returnarray['error'] = 301;
        }

        echo json_encode($returnarray);
    }

    public function GetSellerStockLocationDrop() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        //$stocklocation=$this->ItemInventory_model->GetSellerStockLocationDropQry($_POST['toid']);
        $skuqty = $this->ItemInventory_model->GetsellerAllSKuQry($_POST['toid']);
        //print_r($skuqty);
        $return['stocklocation'] = array();
        $return['skuqty'] = $skuqty;
        echo json_encode($return);
    }

    public function GetStockReadyToTransfer() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); die;
        if (!empty($_POST['from_id']) && !empty($_POST['fi_id']) && !empty($_POST['to_id']) && !empty($_POST['titem_sku']) && !empty($_POST['qty']) && !empty($_POST['location_st'])) {
            if (!empty($_POST['expity_date']))
                $_POST['expity_date'] = $_POST['expity_date'];
            else
                $_POST['expity_date'] = "";

            $InventoryArr = GetinventoryTableData($_POST['fi_id']);
            if ($InventoryArr['quantity'] >= $_POST['qty']) {

                $chargeQty = $_POST['qty'];
                $sku_size = getalldataitemtables($_POST['titem_sku'], 'sku_size');
                $item_type = getalldataitemtables($_POST['titem_sku'], 'type');
                $expity_date = $_POST['expity_date'];

                if (empty($expity_date))
                    $expdate = "0000-00-00";
                else
                    $expdate = $expity_date;
                $dataNew = $this->ItemInventory_model->find(array('item_sku' => $_POST['titem_sku'], 'expity_date' => $expdate, 'seller_id' => $_POST['to_id'], 'itype' => $item_type));
                $qty = $this->input->post('qty');
                foreach ($dataNew as $val) {
                    if ($val->quantity < $sku_size) {

                        //echo '<br> 2//'.$qty.'//'. $val->quantity.'//';
                        $check = $qty + $val->quantity;
                        if ($check <= $sku_size) {

                            if ($check > 0) {
                                $activitiesType = 'transfer';
                                $activitiesArr = array('exp_date' => $val->expity_date, 'st_location' => $val->stock_location, 'item_sku' => $val->item_sku, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $val->seller_id, 'qty' => $check, 'p_qty' => $val->quantity, 'qty_used' => $qty, 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"));
                                GetAddInventoryActivities($activitiesArr);
                            }
                            $HisToryArr_sku = array(
                                'fitem_sku' => $InventoryArr['item_sku'],
                                'from_id' => $_POST['from_id'],
                                'to_id' => $_POST['to_id'],
                                'item_sku' => $_POST['titem_sku'],
                                'qty' => $_POST['qty'],
                                'entry_date' => date("Y/m/d h:i:sa"),
                                'location_st' => $val->stock_location,
                                'fromI_id' => $_POST['fi_id'],
                                'transfer_by' => $this->session->userdata('user_details')['user_id'],
                            );
                            GetSkuTranferHistoryUpdate($HisToryArr_sku);
                            $this->ItemInventory_model->updateInventory(array('quantity' => $check, 'id' => $val->id));
                            $qty = 0;
                        } else {
                            $diff = $sku_size - $val->quantity;



                            $activitiesType = 'transfer';
                            $activitiesArr = array('exp_date' => $val->expity_date, 'st_location' => $val->stock_location, 'item_sku' => $val->item_sku, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $val->seller_id, 'qty' => $sku_size, 'p_qty' => $val->quantity, 'qty_used' => $qty, 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"));
                            $HisToryArr_sku = array(
                                'fitem_sku' => $InventoryArr['item_sku'],
                                'from_id' => $_POST['from_id'],
                                'to_id' => $_POST['to_id'],
                                'item_sku' => $_POST['titem_sku'],
                                'qty' => $_POST['qty'],
                                'entry_date' => date("Y/m/d h:i:sa"),
                                'location_st' => $val->stock_location,
                                'fromI_id' => $_POST['fi_id'],
                                'transfer_by' => $this->session->userdata('user_details')['user_id'],
                            );
                            GetSkuTranferHistoryUpdate($HisToryArr_sku);
                            GetAddInventoryActivities($activitiesArr);
                            $this->ItemInventory_model->updateInventory(array('quantity' => $sku_size, 'id' => $val->id));
                            $qty = $qty - $diff;
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
                    $StockArray = $_POST['location_st'];
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
                        // $stocklocation=$_POST['location_st'];
                        $data[] = array(
                            'itype' => $item_type,
                            'item_sku' => $_POST['titem_sku'],
                            'seller_id' => $_POST['to_id'],
                            'quantity' => $AddQty,
                            'update_date' => date("Y/m/d h:i:sa"),
                            'stock_location' => $StockArray[$ii],
                            'expity_date' => $expity_date);
                    }
                }
                $HisToryArr = array(
                    'fitem_sku' => $InventoryArr['item_sku'],
                    'from_id' => $_POST['from_id'],
                    'to_id' => $_POST['to_id'],
                    'item_sku' => $_POST['titem_sku'],
                    'qty' => $_POST['qty'],
                    'entry_date' => date("Y/m/d h:i:sa"),
                    'location_st' => implode(',', $_POST['location_st']),
                    'fromI_id' => $_POST['fi_id'],
                    'transfer_by' => $this->session->userdata('user_details')['user_id'],
                );
                //echo'<pre>';
                //print_r($HisToryArr);
                $this->ItemInventory_model->InventoryStockMinusQry($_POST, $InventoryArr['quantity']);
                if (!empty($data)) {

                    //die;
                    $this->ItemInventory_model->add($data, 'transfer');
                    $this->ItemInventory_model->UpdateTransferHistoryQry($HisToryArr);
                }
                //echo json_encode($data);
                //die;
                $return = true;
            } else
                $return = "Stock Not available";
        } else
            $return = "All field required";

        echo json_encode($return);
    }

    public function GetsellerDropDataTo() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $tosellers = $this->ItemInventory_model->SellerDropDataQry($_POST['toid']);
        $skuqty = $this->ItemInventory_model->GetsellerAllSKuQry($_POST['toid']);
        $locationArr = $this->ItemInventory_model->GetsellerAllSKuLocationQry($_POST['toid']);
        $return['tosellers'] = $tosellers;
        $return['skuqty'] = $skuqty;
        $return['locationArr'] = $locationArr;
        echo json_encode($return);
    }

    public function filter_history_transfered() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];



        $items = $this->ItemInventory_model->filter_history_transfered($quantity, $sku, $seller, $to, $from, $exact, $page_no);
        $newArray = $items['result'];
        foreach ($newArray as $key => $val) {
            $newArray[$key]['to_id'] = GetSellerTableField($val['to_id'], 'name');
            $newArray[$key]['from_id'] = GetSellerTableField($val['from_id'], 'name');
            $newArray[$key]['fitem_sku'] = getalldataitemtables($val['fitem_sku'], 'sku');
        }
        $return['result'] = $newArray;
        $return['count'] = $items['count'];

        echo json_encode($return);
    }   
	
	  public function getexceldataInventoryHistory() {  

       // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $_POST['listData2'];
		$data1 = $_POST['filterData'];

  
      $dataAray = $this->ItemInventory_model->alllistexcelDataInventoryHistory($data, $_POST['filterData']);     

          $file_name = 'Inventory History.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);  
    }
	
	public function getexceldata() {

       // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $_POST['listData2'];
		$data1 = $_POST['filterData'];

  
      $dataAray = $this->ItemInventory_model->alllistexcelData($data, $_POST['filterData']);    

          $file_name = 'Inventory.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }
    
    public function GetlessqtyData(){
		
        
        $_POST = json_decode(file_get_contents('php://input'), true);
		$exact =$_POST['exact'];
		$quantity=$_POST['quantity'];
		$sku=$_POST['sku'];
		$from=$_POST['from'];
		$to=$_POST['to'];
		$seller=$_POST['seller'];
        $page_no=$_POST['page_no'];

		
		
		$items=$this->ItemInventory_model->Getlessqtydataquery($quantity,$sku,$seller,$to,$from,$exact,$page_no);
		$ItemArray=$items['result'];
		//print_r($ItemArray);
		$kk=0;
		//echo '<pre>';
		$currentDate=date("Y-m-d");
		foreach($items['result'] as $rdata)
		{
			//echo $rdata['update_date'];
		 $ExitsDate=date("Y-m-d", strtotime($rdata['update_date']));
		
		    if($currentDate==$ExitsDate)
			   $ItemArray[$kk]['checkreQty']='Y';
			   else
			   $ItemArray[$kk]['checkreQty']='N';
			    $ItemArray[$kk]['item_type']=getalldataitemtables($rdata['item_sku'],'type');
				
			$kk++;
		}
		//print_r($ItemArray);
		$returnArray['result']=$ItemArray;
		echo json_encode($returnArray);

			
		
		
	}
        
        public function GetexpirealertData(){
		
        
        $_POST = json_decode(file_get_contents('php://input'), true);
		$exact =$_POST['exact'];
		$quantity=$_POST['quantity'];
		$sku=$_POST['sku'];
		$from=$_POST['from'];
		$to=$_POST['to'];
		$seller=$_POST['seller'];
        $page_no=$_POST['page_no'];

		
		
		$items=$this->ItemInventory_model->GetexpirealertDataQuery($quantity,$sku,$seller,$to,$from,$exact,$page_no);
		$ItemArray=$items['result'];
		//print_r($ItemArray);
		$kk=0;
		//echo '<pre>';
		$currentDate=date("Y-m-d");
		foreach($items['result'] as $rdata)
		{
			//echo $rdata['update_date'];
		 $ExitsDate=date("Y-m-d", strtotime($rdata['update_date']));
		
		    if($currentDate==$ExitsDate)
			   $ItemArray[$kk]['checkreQty']='Y';
			   else
			   $ItemArray[$kk]['checkreQty']='N';
			    $ItemArray[$kk]['item_type']=getalldataitemtables($rdata['item_sku'],'type');
				
			$kk++;
		}
		//print_r($ItemArray);
		$returnArray['result']=$ItemArray;
		echo json_encode($returnArray);

			
		
		
	}
        
        public function Gettop10productshow(){
		
     
        $_POST = json_decode(file_get_contents('php://input'), true);
		$exact =$_POST['exact'];
		$quantity=$_POST['quantity'];
		$sku=$_POST['sku'];
		$from=$_POST['from'];
		$to=$_POST['to'];
		$seller=$_POST['seller'];
        $page_no=$_POST['page_no'];

		
		
		$items=$this->ItemInventory_model->Gettopproductdatashow($quantity,$sku,$seller,$to,$from,$exact,$page_no);
		$ItemArray=$items['result'];
		//print_r($ItemArray);
		$kk=0;
		//echo '<pre>';
		$currentDate=date("Y-m-d");
		foreach($items['result'] as $rdata)
		{
			//echo $rdata['update_date'];
		 $ExitsDate=date("Y-m-d", strtotime($rdata['update_date']));
		
		    if($currentDate==$ExitsDate)
			   $ItemArray[$kk]['checkreQty']='Y';
			   else
			   $ItemArray[$kk]['checkreQty']='N';
			    $ItemArray[$kk]['item_type']=getalldataitemtables($rdata['item_sku'],'type');
				
			$kk++;
		}
		//print_r($ItemArray);
		$returnArray['result']=$ItemArray;
                $returnArray['count']=$items['count'];;
		echo json_encode($returnArray);

			
		
		
	}
        
         public function filter_shelve() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); die;
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $shelve_no = $_POST['shelve_no'];
        $storage_id = $_POST['storage_id'];



        $items = $this->ItemInventory_model->filter_shelve($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no, $storage_id, $_POST);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));

            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';
            $ItemArray[$kk]['item_type'] = getalldataitemtables($rdata['item_sku'], 'type');
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield(getalldataitemtables($rdata['item_sku'], 'storage_id'), 'storage_type');
            //if($rdata['shelve_no']!='')

            $ItemArray[$kk]['wh_name'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');

            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($palletArray);
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        echo json_encode($returnArray);
    }
    
    public  function filter_shelve_details()
    {
        $PostData = json_decode(file_get_contents('php://input'), true);
        $detailsArr = $this->ItemInventory_model->filter_shelve_details_Query($PostData);
         echo json_encode($detailsArr);
       
        
    }
    
     public function inventoryCheck()
    {
          $PostData = json_decode(file_get_contents('php://input'), true);
          $detailsArr = $this->ItemInventory_model->inventoryCheckQry($PostData);
            echo json_encode($detailsArr);
        
    }
    
    public function GetSaveReportInventpry()
    {
      $PostData = json_decode(file_get_contents('php://input'), true);  
     
      if(!empty($PostData))
      {
          $inventry_id=strtoupper(uniqid());
          $insertinventoryData=$PostData;
          foreach($insertinventoryData as $key=>$val)
          {
              unset($insertinventoryData[$key]['sku1']);
              $insertinventoryData[$key]['super_id']=$this->session->userdata('user_details')['super_id'];
              $insertinventoryData[$key]['uid']=$inventry_id;
              $insertinventoryData[$key]['update_by']=$this->session->userdata('user_details')['user_id'];
              $insertinventoryData[$key]['entry_date']=date("Y-m-d H:i:s");
              
          }
        if(!empty($insertinventoryData))
        {
             $this->ItemInventory_model->GetSaveReportInventpryQry($insertinventoryData);
        }
        
          
      }
       echo json_encode($PostData);
    }
    
    public function GetshowingSkuMeadiaData()
    {
         $PostData = json_decode(file_get_contents('php://input'), true);  
         $return=$this->ItemInventory_model->GetshowingSkuMeadiaDataQry($PostData); 
       if (file_exists($return['item_path']) && $return['item_path']!='') {
         $return['item_path']=base_url().$return['item_path'];
       }
       else
       {
            $return['item_path']=base_url()."assets/nfd.png";
       }
      echo json_encode($return);
    }
	
	
	
	

}

?>
