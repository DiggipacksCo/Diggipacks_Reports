<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cartoon extends MY_Controller {

	function __construct() {
		parent::__construct(); 
 
		$this->load->model('Cartoon_model');
		// $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	public function index(){
		$data['cartoons'] = $this->Cartoon_model->all();
		$this->load->view('CartoonI/view_cartoons',$data);
		
	}

	public function add_view(){
		 $this->load->view('CartoonI/add_cartoon');
		
	}



	// public function search(){
	// 	$search = $this->input->post('search');
	// 	$query = $this->Seller_model->getSeller($search);
  	// 	echo json_encode ($query);

	// }






	public function add(){
	
		$data = array(
			'sku' => $this->input->post('sku'),
			'name' => $this->input->post('name'),
			'size' => $this->input->post('size'),
			'quantity' => $this->input->post('quantity'),
			'update_date'=>date("Y/m/d h:i:sa")
		);
		$this->Cartoon_model->add($data);
		
		$this->session->set_flashdata('msg', $this->input->post('name').'   has been added successfully');

		
		redirect('Cartoon');

	 
	}



	public function edit_view($id){
		// $id = $this->input->get('id');
		$cartoon = $this->Cartoon_model->edit_view($id);
		$this->load->view('CartoonI/cartoon_detail',[
			'cartoon'=>$cartoon,
		]);
		
	}

	public function edit($id){
		// $id=$this->input->post('id');
		$cartoon = $this->Cartoon_model->edit_view($id);
		$postQuantity = $this->input->post('quantity');
		if($cartoon->quantity == $postQuantity  ){

		$data = array(
		
			'name' => $this->input->post('name'),
			'sku' => $this->input->post('sku'),
			'size' => $this->input->post('size'),
			'quantity' => $this->input->post('quantity'),
			'update_date'=>date("Y/m/d h:i:sa")
			// 'phone2' => $this->input->post('phone2')
		);
	}else{
		$data = array(
		
			'name' => $this->input->post('name'),
			'sku' => $this->input->post('sku'),
			'size' => $this->input->post('size'),
			'quantity' => $this->input->post('quantity'),
			'update_date' => date("Y/m/d h:i:sa")
		);
	}
		// print($id);
		// exit();

		$this->Cartoon_model->edit($id,$data);
		$this->session->set_flashdata('msg', $this->input->post('name').'   has been updated successfully');
		redirect('Cartoon');

	}

	public function getInventory(){
			
		$cartoon_sku=  $this->input->post('cartoon_sku');
		return $this->Cartoon_model->getInventory($cartoon_sku);


	}

}
?>
