<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {

	function __construct() {
		parent::__construct(); 
		$this->load->model('Shipment_model');
		$this->load->model('Seller_model');
		$this->load->model('Item_model');
		$this->load->model('Status_model');
		
	}

	public function index(){

		$search=$this->input->post('tracking_numbers');
    	//$sellers=$this->Seller_model->find2();
    	if(strcasecmp(substr($search, 0, 3),"TAM")==0){
    		$condition=strtoupper($search);
    		//$status=$this->Shipment_model->allstatus();
		  //$shipments = $this->Shipment_model->find_by_slip_no($condition);
    	
	      // if($shipments!=Null){
	     
	    
	    	//$bulk=array(
	    		// 'status'=>$status,
	    		//'sellers'=>$sellers,
	    		//'condition'=>$condition,
	    		//'shipments'=>$shipments,
	    		
			//);
			$this->session->set_flashdata('condition',$condition);
			redirect(base_url('Shipment'));
			//redirect(base_url('ShipmentM/view_shipments/'.$bulk));
		    //$this->load->view('ShipmentM/view_shipments',$bulk);
		    // }else{
		    // 	$this->load->view('ShipmentM/view_shipments');
		    // }

    	}else{
    		$condition=strtoupper($search);
    		// $items = $this->ItemInventory_model->find_by_sku($condition);
	   		//$quantity=$this->ItemInventory_model->count_all();
	   		//$sellers=$this->Seller_model->find2();
	  
	   
	   //	$bulk=array(
    		
    		// 'items'=>$items,
    		// 'sellers'=>$sellers,
    		// 'quantity'=>$quantity
    	//);

    	$this->session->set_flashdata('condition',$condition);
			redirect(base_url('ItemInventory'));
		//$this->load->view('ItemI/view_iteminventory',$bulk);	
		
    	}
    	

		
	}
	

	
}
?>
