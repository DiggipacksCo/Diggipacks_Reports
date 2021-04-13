<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shelve extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->library('pagination');
        $this->load->model('Shelve_model');
        $this->load->helper('utility');

        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function index() {
        
    }

    public function shelveSelected() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        $id = $_POST['int_id']; //'431'; //
        //$_POST['shelve_no']="A1/S0/1";
        //$_POST['StockLocation']="ERAM-3356911533";
        $shelve = array('shelve_no' => $_POST['shelve_no']);

        $this->load->model('ItemInventory_model');
        $seller_id = $this->Shelve_model->GetcheckshelaveUse($_POST['shelve_no']);
        $return = "";
        $ShelveArray = $this->Shelve_model->chekinventoryshalveno($_POST['StockLocation']);
        //echo $ShelveArray['seller_id']."==$seller_id";
        if (!empty($seller_id)) {
            if (!empty($ShelveArray)) {
                if ($ShelveArray['seller_id'] == $seller_id) {

                    $shipments = $this->ItemInventory_model->updateShelve($id, $shelve);
                } else {
                    $return = 302;
                }
            }
        } else
            $shipments = $this->ItemInventory_model->updateShelve($id, $shelve);

        if (empty($return)) {
            echo json_encode($_POST);
            exit();
        } else {
            echo json_encode($return);
            exit();
        }
    }

    
    
    
    public function showtods() {

     $this->load->view('shelve/showtods');
    }
    public function showStock($type=null) {


        
        $this->load->model('Seller_model');
        //echo "sssssss"; die;
        // $status=$this->Shipment_model->allstatus();
        $sellers = $this->Seller_model->find1();
        //$status=$this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
                'type'=>$type,
            'type1'=>$type

                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('shelve/showStock', $bulk);
    }

    public function generateStock() {
        
  

        $this->load->library('form_validation');
        $this->form_validation->set_rules('stockCount', 'Number Of Stock Location', 'trim|required');
        $this->form_validation->set_rules('seller', 'Seller', 'trim|required');
        $this->form_validation->set_rules('charname', 'Location Latters', 'trim|required|max_length[4]');

        
        
 


        if ($this->form_validation->run() == FALSE) {
            $this->generateStockLocation();
        } else {
            $numbers = $this->input->post('stockCount');
            $seller = $this->input->post('seller');
            $charname = $this->input->post('charname');
            $insertData = array();
            
            $old_no=GetstockID($seller);
            $jj=1;
            for ($i = 0; $i < $numbers; $i++) {
               $lastno= $old_no+$jj;
                $locno= sprintf("%'06d",$old_no+$jj);
                $newlocno=$seller.$seller+$this->session->userdata('user_details')['super_id'].$locno;

                //$insertData[$i]['stock_location2'] = $charname."-".$this->generateRandomString(8);
                
                // $insertData[$i]['stock_location'] = strtoupper($charname . '-' . abs(crc32(uniqid())));
                $insertData[$i]['stock_location'] = strtoupper($charname . '-' .$newlocno);
                $insertData[$i]['seller_id'] = $seller;
                $insertData[$i]['super_id'] = $this->session->userdata('user_details')['super_id'];
                 $insertData[$i]['lastno'] = $lastno;
                
                 $jj++;
            }
          //  echo '<pre>';
           // print_r($insertData);
           //  die;
            $this->Shelve_model->insertStockLocation($insertData);
            redirect(base_url('showStock'));
        }
    }
    
     public function generatetodsfrm() {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('stockCount', 'Number Of TOD', 'trim|required');
        $this->form_validation->set_rules('charname', 'Start Latters', 'trim|required|max_length[4]');

        if ($this->form_validation->run() == FALSE) {
            $this->generatetods();
        } else {
            $numbers = $this->input->post('stockCount');
            $charname = $this->input->post('charname');
            $insertData = array();
            $cnt=0;
            for ($i = 0; $i < $numbers; $i++) {

              
                
                    
                    $cnt=todcount($charname,$i);
                    
                   

                    $insertData[$i]['tod_no'] = strtoupper($charname.$cnt);
            
                //$insertData[$i]['stock_location2'] = $charname."-".$this->generateRandomString(8);
                
                $insertData[$i]['super_id'] = $this->session->userdata('user_details')['super_id'];
               
            }
            // echo '<pre>';
            // print_r($insertData); die;
            $this->Shelve_model->inserttodsLocation($insertData);
            redirect(base_url('showtods'));
        }
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    
    
    
     public function GenerateTods() {
       
     $this->load->view('shelve/GenerateTods');
    }
    public function generateStockLocation() {
        if (menuIdExitsInPrivilageArray(1) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->model('Seller_model');
        //echo "sssssss"; die;
        // $status=$this->Shipment_model->allstatus();
        $sellers = $this->Seller_model->find1();
        //$status=$this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers

                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('shelve/generateStockLocation', $bulk);
    }

    public function checkStockLocation() {
        $this->load->model('ItemInventory_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        //echo json_encode($_POST );
//		
        $StockLocation = $_POST['StockLocation'];
//        $shelve= $_POST['shelve'];
//        $city=$_POST['destination'];
//       
//		
//		
        $shipments = $this->ItemInventory_model->check($StockLocation);
        echo json_encode($shipments);
    }

    public function shelveFilter() {
        // print("heelo");
        // exit();
        $_POST = json_decode(file_get_contents('php://input'), true);

        $page_no = $_POST['page_no'];
        $shelve = $_POST['shelve'];
        $city = $_POST['destination'];



        $shipments = $this->Shelve_model->selveFilter($city, $shelve, $page_no);

        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = $shipments['result'];

        $tolalShip = count($shiparray);
        $downlaoadData = 2000;
        $j = 0;
        $expoertdropArr = array();
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }

        $ii = 0;
        foreach ($shipments['result'] as $rdata) {

            $shiparray[$ii]['barcode'] = barcodeRuntime($rdata['shelv_no']);

            $shiparray[$ii]['shelve_no'] = $rdata['shelv_no'];
            $shiparray[$ii]['city_id'] = getdestinationfieldshow($rdata['city_id'], 'city');

            $ii++;
        }

        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
         $dataArray['dropexport'] = $expoertdropArr;
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function stockLocationFilter() {
        // print("heelo");
        // exit();
        $_POST = json_decode(file_get_contents('php://input'), true);

        $page_no = $_POST['page_no'];
        $stock_location = $_POST['stock_location'];
        $seller_id = $_POST['seller_id'];



        $shipments = $this->Shelve_model->stockLocationFilter($stock_location, $seller_id, $page_no,$_POST);
        //echo json_encode($shipments); die;
        //echo ($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = $shipments['result'];
        $ii = 0;
        foreach ($shipments['result'] as $rdata) {

            $shiparray[$ii]['barcode'] = barcodeRuntime($rdata['stock_location']);




            $ii++;
        }

        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }
    
     public function todsfiltershow() {
        // print("heelo");
        // exit();
        $_POST = json_decode(file_get_contents('php://input'), true);

     
       $shipments = $this->Shelve_model->todsfiltershowQry($_POST);
        //echo json_encode($shipments); die;
        //echo ($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = $shipments['result'];
        $ii = 0;
        foreach ($shipments['result'] as $rdata) {

            $qrpath="https://lm.fastcoo-tech.com/application/third_party/qrcodegen.php?data=".$rdata['tod_no'];
            
           $qrcode= base64_encode($qrpath);
           
           $path = $qrpath;
           $type = pathinfo($path, PATHINFO_EXTENSION);
           $data = file_get_contents($path);
           $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            $shiparray[$ii]['barcode'] = barcodeRuntime($rdata['tod_no']);
            $shiparray[$ii]['qrcode'] = $base64;




            $ii++;
        }

        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function checkShelve() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Shelve_model->selveFilter($city, $_POST['shelve_no'], '');
        echo json_encode($shipments);
    }

    public function shelve_sku() {



        $this->load->view('shelve/shelve_sku');
    }

    public function showstockViewExport() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataArray = $_POST;
        $slip_data = array();
        $file_name = date('Ymdhis') . '.xls';

        //echo json_encode($dataArray); die;
        //echo json_encode($_POST);exit;
        $key = 0;
//     foreach($dataArray as $data)
//    { 
//     array_push( $slip_data ,$data['slip_no']);
//     $statusvalue[$key]['user_id']=$this->session->userdata('user_details')['user_id'];  
//     $statusvalue[$key]['user_type']='fulfillment';      
//     $statusvalue[$key]['slip_no']=$data['slip_no'];
//     $statusvalue[$key]['new_status']=4;
//     $statusvalue[$key]['code']='PK';
//     $statusvalue[$key]['Activites']='Order Packed';
//     $statusvalue[$key]['Details']='Order Packed By '. getUserNameById($this->session->userdata('user_details')['user_id']);
//     $statusvalue[$key]['entry_date']=date('Y-m-d H:i:s');
//    /*-------------/Status Array-----------*/  
//      $picklistValue[$key]['slip_no']=$data['slip_no'];
//     $picklistValue[$key]['packedBy']=$this->session->userdata('user_details')['user_id'];  
//     $picklistValue[$key]['packDate']=date('Y-m-d H:i:s');
//     $picklistValue[$key]['pickupDate']=date('Y-m-d H:i:s');
//     $picklistValue[$key]['pickup_status']='Y';
//     $picklistValue[$key]['packFile']=$file_name;     
//        
//    $key++;
//    } 

        echo json_encode($this->exportshowstock($dataArray, $file_name));
    }

    function shelveScanExport() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $_POST;
        $file_name = 'shelveScaned.xls';
        //  echo json_encode( $data); exit;
        $dataArray = $data;
        $returnarray = array();
        $kk = 0;
        foreach ($dataArray as $data) {
            $returnarray[$kk]['city_id'] = $data['city_id'];
            $returnarray[$kk]['shelv_no'] = $data['shelv_no'];
            $kk++;
        }


        array_unshift($returnarray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($returnarray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Shelve No.')
                ->setCellValue('B1', '	City');


        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        echo json_encode($response);
    }

    function Shelveviewexportdata() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $_POST;
        $file_name = 'shelveScaned.xls';
        //  echo json_encode( $data); exit;
        $dataArray = $data;


        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'SKU')
                ->setCellValue('C1', 'Stock Location')
                ->setCellValue('D1', 'Quantity')
                ->setCellValue('E1', 'Item')
                ->setCellValue('F1', 'Shelve No.');


        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        echo json_encode($response);
    }

    function exportshowstock($dataEx, $file_name) {



        $dataArray = array();
        $i = 0;
        foreach ($dataEx as $data) {
            $dataArray[$i]['stock_location'] = $data['stock_location'];
            $dataArray[$i]['name'] = $data['name'];

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
                ->setCellValue('A1', 'Stock Location')
                ->setCellValue('B1', 'Name(Seller)');


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

//die(json_encode($response));
    }

    public function view_shelve() {



        $this->load->view('shelve/view_shelve');
    }

    public function add_view() {

        if (($this->session->userdata('user_details') != '')) {

            $data["all_attributes"] = $this->Attribute_model->allAttributes();
            $data["main_categories"] = $this->ItemCategory_model->allMain();
            $data["sub_categories"] = $this->ItemCategory_model->allSub();
            $data["attributes"] = $this->Attribute_model->all();
            $this->load->view('ItemM/add_item', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function add_bulk_shelve() {
        $this->load->view('shelve/add_bulk_shelve');
    }

    public function add() {
        // $category= $this->input->post('dd_category');
        // $sub_category=$this->input->post('dd_subcategory');
        // $data=array(
        // 	'category_id'=>$category,
        // 	'sub_category_id'=>$sub_category
        // );
        // $category_details=$this->Attribute_model->findAttributes2($data);
        // for ($i=0; $i < count($category_details); $i++) { 
        // 	$attributes_id=$category_details[$i]->attributes_id;	
        // }
        // $attributes_values="";
        // $attribute=explode(",",$attributes_id);
        // for ($i=0; $i <count($attribute) ; $i++) {
        // 	if($i==count($attribute)-1){
        // 	$attributes_values=$attributes_values.$this->input->post($attribute[$i]);	
        // 	}
        // 	else{
        // 	$attributes_values=$attributes_values.$this->input->post($attribute[$i]).',';
        // 	}
        // }

        $data2 = array(
            'name' => $this->input->post('name'),
            'sku' => $this->input->post('sku'),
            'description' => $this->input->post('description')
                //'item_category' => $category,
                //'item_subcategory'=>$sub_category,
                //'attributes_values'=>$attributes_values,
        );

        $item_id = $this->Item_model->add($data2);

        $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');


        redirect('Item');
    }

    public function edit_view($id) {

        $data = $this->Item_model->edit_view($id);

        // $all_attributes= $this->Attribute_model->allAttributes();
        // $attributes_id=explode(",",$data['attributes'][0]->attributes_id);
        // for ($i=0; $i < count($attributes_id); $i++) { 
        // 	$attribute=$this->Attribute_model->find($attributes_id[$i]);
        // 	$attributes_name[$i]=$attribute[0]->name;
        // }
        // $attributes_value=explode(",",$data['item']->attributes_values);
        // $main_categories=$this->ItemCategory_model->allMain();
        // $sub_categories=$this->ItemCategory_model->allSub();
        // $all_attributes=$this->Attribute_model->allAttributes();
        // print_r($data['item']->description);
        // exit();
        // $all_categories=$this->ItemCategory_model->all();
        $this->load->view('ItemM/item_detail', [
            'item' => $data['item'],
                // 'category_details'=>$data['category'],
                // 'sub_category_details'=>$data['sub_category'],
                // 'attributes_value'=>$attributes_value,
                // 'attributes_id'=>$attributes_id,
                // 'attributes_name'=>$attributes_name,
                // 'total_attributes'=>count($attributes_id),
                // 'main_categories'=>$main_categories,
                // 'sub_categories'=>$sub_categories,
                // 'all_attributes'=>$all_attributes
                //'all_categories'=>$all_categories
        ]);
    }

    public function edit($item_id) {

        $data = array(
            'name' => $this->input->post('name'),
            'sku' => $this->input->post('sku'),
            'description' => $this->input->post('description')
        );

        $this->Item_model->edit($item_id, $data);

        // $category= $this->input->post('dd_category');
        // $sub_category= $this->input->post('dd_subcategory');
        // $item=$this->Item_model->find($item_id);
        // $previous_category=$item[0]->item_category;
        // $previous_subcategory=$item[0]->item_subcategory;
        // if($category==$previous_category){
        // 	if ($sub_category==$previous_subcategory) {
        // 		$data=array(
        // 			'category_id'=>$previous_category,
        // 			'sub_category_id'=>$previous_subcategory
        // 		);
        // 		$previous_attributes_id=$this->Attribute_model->findAttributes2($data);
        // 		$attributes_id=explode(",",$previous_attributes_id[0]->attributes_id);
        // 		for ($i=0; $i < count($attributes_id); $i++) { 
        // 			if($this->input->post($i)==NULL){
        // 				$attributes_value[$i]=$this->input->post($attributes_id[$i]);
        // 			}
        // 			else{
        // 				$attributes_value[$i]=$this->input->post($i);
        // 			}
        // 		}		
        // 		$attributes_values="";
        // 		for ($i=0; $i < count($attributes_id); $i++) { 
        // 			if($i==count($attributes_id)-1){
        // 				$attributes_values=$attributes_values.$attributes_value[$i];	
        // 			}
        // 			else{
        // 				$attributes_values=$attributes_values.$attributes_value[$i].',';
        // 			}
        // 		}
        // 		$data= array(
        // 			'name' => $this->input->post('name'),
        // 			'sku' => $this->input->post('sku'),
        // 			'attributes_values' => $attributes_values,
        // 		);
        // 		$this->Item_model->edit($item_id,$data);
        // 		$this->session->set_flashdata('msg','id#'. $item_id.' has been updated successfully');
        // 		redirect('Item');
        // 	}
        // 	else{
        // 		$data=array(
        // 			'category_id'=>$previous_category,
        // 			'sub_category_id'=>$sub_category
        // 		);
        // 		$new_attributes_id=$this->Attribute_model->findAttributes2($data);	
        // 		$attributes_id=explode(",",$new_attributes_id[0]->attributes_id);
        // 		for ($i=0; $i < count($attributes_id); $i++) { 
        // 				$attributes_value[$i]=$this->input->post($attributes_id[$i]);
        // 		}	
        // 		$attributes_values="";
        // 		for ($i=0; $i < count($attributes_id); $i++) { 
        // 			if($i==count($attributes_id)-1){
        // 				$attributes_values=$attributes_values.$attributes_value[$i];	
        // 			}
        // 			else{
        // 				$attributes_values=$attributes_values.$attributes_value[$i].',';
        // 			}
        // 		}
        // 		$data= array(
        // 			'name' => $this->input->post('name'),
        // 			'sku' => $this->input->post('sku'),
        // 			'item_subcategory'=>$this->input->post('dd_subcategory'),
        // 			'attributes_values' => $attributes_values,
        // 		);
        // 		$this->Item_model->edit($item_id,$data);
        // 		$this->session->set_flashdata('msg','id#'. $item_id.' has been updated successfully');
        // 		redirect('Item');
        // 	}
        // }
        // else{
        // 	 	$data=array(
        // 			'category_id'=>$category,
        // 			'sub_category_id'=>$sub_category
        // 		);
        // 		$new_attributes_id=$this->Attribute_model->findAttributes2($data);	
        // 		$attributes_id=explode(",",$new_attributes_id[0]->attributes_id);
        // 		for ($i=0; $i < count($attributes_id); $i++) { 
        // 				$attributes_value[$i]=$this->input->post($attributes_id[$i]);
        // 		}	
        // 		$attributes_values="";
        // 		for ($i=0; $i < count($attributes_id); $i++) { 
        // 			if($i==count($attributes_id)-1){
        // 				$attributes_values=$attributes_values.$attributes_value[$i];	
        // 			}
        // 			else{
        // 				$attributes_values=$attributes_values.$attributes_value[$i].',';
        // 			}
        // 		}
        // 		$data= array(
        // 			'name' => $this->input->post('name'),
        // 			'sku' => $this->input->post('sku'),
        // 			'item_category'=>$this->input->post('dd_category'),
        // 			'item_subcategory'=>$this->input->post('dd_subcategory'),
        // 			'attributes_values' => $attributes_values,
        // 		);
        // 		$this->Item_model->edit($item_id,$data);


        $this->session->set_flashdata('msg', 'id#' . $item_id . ' has been updated successfully');
        redirect('Item');

        // }
    }

}

?>
