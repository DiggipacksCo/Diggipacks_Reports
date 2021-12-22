<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Reports_model');
        $this->load->model('Shipment_model');
        $this->load->model('Ccompany_model');
        $this->load->helper('utility');
        
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function client_report() {


        $this->load->view('reports/client_report');
    }

    public function GetClientOrderReports() {

        $postData = json_decode(file_get_contents('php://input'), true);

        $shipments = $this->Reports_model->GetClientReportDispatchQry($postData);
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
        foreach ($shipments['result'] as $rdata) {

            
            
            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
           // $shiparray[$ii]['sku_details'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');

            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];

            $shiparray[$ii]['deducted_shelve_no'] = $this->Reports_model->GetdimationDetails($rdata['slip_no']);

            //$shiparray='rith';
            $ii++;
        }


        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }
    
      public function performance_details_3pl($frwd_throw=null,$status=null,$from=null,$to=null){
		
          $data['Urldata'] = array('frwd_throw' => $frwd_throw, 'status' => $status, 'from' => $from, 'to' => $to);
		//$data['DetailsArr'] = $this->Reports_model->GetallperformationDetailsQry_3pl($frwd_throw,$status,$from,$to);
	
		$this->load->view('reports/performance_details_3pl',$data);
		
	}
	
	
	
	public function performance_3pl(){
           
          $data['postData'] = $this->input->post();
                
                 if($data['postData']['clfilter']==1){
                   $data['postData']=array();  
                 }
		$data['sellers'] = $this->Reports_model->all_3pl($data['postData']);
              
	
		$this->load->view('reports/performance_3pl',$data);
		
	}
        
           public function performance_details_filter() {

        
        $filterArr = json_decode(file_get_contents('php://input'), true);


        
        $dataArray = $this->Reports_model->GetallperformationDetailsQry_filter($filterArr);

         echo json_encode($dataArray);
    }
    
     public function view_damage_inventory() {
        $this->load->model('ItemInventory_model');
         
        $sellers = $this->Seller_model->find1();
      
        $bulk = array('sellers' => $sellers);
        $this->load->view('reports/view_iteminventory_damage', $bulk);
    }
 public function filter_damage() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $items = $this->Reports_model->filter_damage($_POST);
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
           
            
            $ItemArray[$kk]['update_date'] = date("d-m-Y H:i:s",strtotime($rdata['update_date']));
            $ItemArray[$kk]['item_type'] = $rdata['type'];
            $ItemArray[$kk]['sku_size'] = $rdata['sku_size'];
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield($rdata['storage_id'], 'storage_type');
            $kk++;
        }
        //echo '<pre>';
        //print_r($ItemArray);die;
        $returnArray['query'] = $items['query'];
        $returnArray['count'] = $items['count'];
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        echo json_encode($returnArray);
    }
}

?>