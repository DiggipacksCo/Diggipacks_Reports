<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemCategory extends MY_Controller {

	function __construct() {
		parent::__construct(); 
		
		$this->load->model('ItemCategory_model');
		$this->load->model('Attribute_model');
		// $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	public function index(){
	
		$data["itemcategories"] =$this->ItemCategory_model->all();
		//$data["attributes"] =$this->Attribute_model->all();

		 // print_r($data["attributes"]);
		 // exit();
		$this->load->view('ItemCM/view_itemcategories',$data);
		
	}

	public function add_view(){
		
		if(($this->session->userdata('user_details') != ''))
		{
			// print_r($this->session->userdata('user_details'));
			// exit();
			$data["all_categories"]=$this->ItemCategory_model->all();
			$data["all_attributes"]=$this->Attribute_model->all();
			$this->load->view('ItemCM/add_itemcategory',$data);
			
		}
		else{
			redirect(base_url().'Login');
		}
		
	}

	


	public function add(){

		// print_r($this->input->post('attributes_ids'));
		//  exit();
		//  'attributes_id'=>$this->input->post('attributes_ids')
		$data = array(

			'name' => $this->input->post('name'),			
		);
		$this->ItemCategory_model->add($data);
		
		$this->session->set_flashdata('msg', $this->input->post('name').'   has been added successfully');

		
		redirect('ItemCategory');

		
	}

	public function addSubCategory(){
		
		$data = array(

			'name' => $this->input->post('name'),
			'main_id'=> $this->input->post('categories_ids')
		);

		$result=$this->ItemCategory_model->add($data);
		if($result==1){
		$this->session->set_flashdata('msg', $this->input->post('name').'   has been added successfully');
		}
		else{
			if($result==1062){
			$this->session->set_flashdata('msg', 'Duplicate data is not allowed.!!!!');
			}
		}
		
		redirect('ItemCategory');

		
	}

		public function add_subCategory_view(){
		
		if(($this->session->userdata('user_details') != ''))
		{
			
			$data["all_categories"]=$this->ItemCategory_model->allMain();
			
			$this->load->view('ItemCM/add_itemsubcategory',$data);
			
		}
		else{
			redirect(base_url().'Login');
		}
		
	}


	public function edit_view($id){
		
		$data['all_categories']=$this->ItemCategory_model->allMain();
		//$data['all_attributes']=$this->Attribute_model->all();
		$data['itemcategory'] = $this->ItemCategory_model->edit_view($id);
		
		$this->load->view('ItemCM/itemcategory_detail',$data);
		
	}

	public function edit($id){
		
	
		$data = array(
			
			'name' => $this->input->post('name'),
			'main_id'=>$this->input->post('dd_category')
		);

		$this->ItemCategory_model->edit($id,$data);
		$this->session->set_flashdata('msg', 'id#'.$id.' has been updated successfully');
		redirect('ItemCategory');

	}

	// public function find2(){
	// 	$category_id=$this->input->post('category_id');
    //  return $this->ItemCategory_model->find($category_id);
	// }

	public function find(){
		$category_id=$this->input->post('category_id');
    	return $this->ItemCategory_model->find($category_id);
	}

	public function findMain(){
		$category_id=$this->input->post('category_id');
    	return $this->ItemCategory_model->findMain($category_id);
	}
}
?>
