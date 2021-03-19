<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RTS extends MY_Controller {

	function __construct() {
		parent::__construct(); 
		$this->load->model('Shipment_model');
		$this->load->model('Seller_model');
		$this->load->model('Item_model');
		// $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	public function index(){
		//$status=$this->Shipment_model->allstatus();
    	$data['shipments'] = $this->Shipment_model->RTS();
    	//if($shipments!=Null){
    	// for($i=0;$i<count($shipments);$i++){
    		
    	// 	$sellers[$i]=$this->Seller_model->find_customer_sellerm($shipments[$i]->cust_id);

    	// }

 
    	// for($i=0;$i<count($sellers);$i++){
    	// 	$items[$i]=$this->Item_model->find($shipments[$i]->sku);
    	// }

    	// $bulk=array(
    	// 	'status'=>$status,
    	// 	'shipments'=>$shipments,
    	// 	'items'=>$items,
    	// 	'sellers'=>$sellers
    	// );
    	// print_r($shipments[0]);
    	// exit();
    	

    	$this->load->view('RTS/view_rts',$data);
    //}
  //   	}else{
		// // $data['status']=$this->Shipment_model->allstatus();
  // //   	$data['shipments'] = $this->Shipment_model->RTS(); 
		// $this->load->view('RTS/view_rts');
		// }
	}
	

	// public function add_view(){

	// 	$data = $this->Shipment_model->add_view();
	 	
	// 	$this->load->view('ShipmentM/add_shipment' , $data);
	// }


	// public function add(){
	// 	// print_r($this->input->post('sku'));
	// 	// echo '<pre>';
	// 	// print_r($this->input->post('seller'));
	// 	// echo '</pre>';
	// 	// 	print_r($this->input->post('quantity'));
	// 	// exit();
		

	// 	$data = array(
	// 		'item_sku' => $this->input->post('item_sku'),
	// 		'cartoon_sku' => $this->input->post('cartoon_sku'),
	// 		'seller' => $this->input->post('seller'),
	// 		'status'=>$this->input->post('status'),
	// 		'item_quantity' => $this->input->post('item_quantity'),
	// 		'cartoon_quantity' => $this->input->post('cartoon_quantity'),
	// 		'date'=>date("Y/m/d h:i:sa")
	// 	);
	// 	if($this->Shipment_model->add($data))
	// 	{
	// 		$this->session->set_flashdata('msg',' Data  has been added successfully');
	// 		redirect(base_url('Shipment'));
		
	// 	}
	// 	else{
	// 		$this->session->set_flashdata('msg','No such data exist in inventory or Multiple ids assign to same seller with same item sku. Add Shipment failed');
	// 		redirect(base_url('Shipment'));
	// 	}
	// }



	// public function edit_view($id){
	// 	// $id = $this->input->get('id');
	// 	$item = $this->ItemInventory_model->edit_view($id);
	 
	// 	$this->load->view('ItemI/iteminventory_detail',[
	// 		'item'=>$item,
	// 	]);
		
	// }
	// public function edit($id){
	// 	// $id=$this->input->post('id');
	// 	$item = $this->ItemInventory_model->edit_view($id);
	// 	$postQuantity = $this->input->post('quantity');
	// 	if($item->quantity != $postQuantity  ){
	// 		$data = array(
	// 			'quantity' => $this->input->post('quantity'),
	// 			'update_date' => date("Y/m/d h:i:sa")
	// 		);
	 
	// }else{
	// 	redirect('ItemInventory');
	// }
	// 	// print($id);
	// 	// exit();

	// 	$this->ItemInventory_model->edit($id,$data);
	// 	$this->session->set_flashdata('msg', $this->input->post('name').'   has been updated successfully');
	// 	redirect('ItemInventory');

	// }

}
?>
