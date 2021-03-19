<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute extends MY_Controller {

	function __construct() {

		parent::__construct(); 
		
		$this->load->model('Attribute_model');
		$this->load->model('ItemCategory_model');
		$this->load->model('Item_model');

	}

	public function index(){
		
		$data["all_attributes"] =$this->Attribute_model->allAttributes();
		$data["main_categories"]=$this->ItemCategory_model->allMain();
		$data["sub_categories"]=$this->ItemCategory_model->allSub();
		$data["attributes"]=$this->Attribute_model->all();
		$this->load->view('AttributeM/view_attributes',$data);
		
	}


	public function add_view(){
		
		if($this->session->userdata('user_details'))
		{
			$data['main_categories']=$this->ItemCategory_model->allMain();
			$data['sub_categories']=$this->ItemCategory_model->allSub();
			$data['all_attributes']=$this->Attribute_model->allAttributes();

			$this->load->view('AttributeM/add_attribute',$data);
					
		}
		else{
			redirect(base_url().'Login');
		}
		
	}

	public function add(){
		
		// print_r($this->input->post('attributes_ids'));
		// exit();
		$data=array(
			'category_id'=>$this->input->post('category_id'),
			'sub_category_id'=>$this->input->post('subcategory_id')
		);
		$result=$this->Attribute_model->findid($data);
		print_r($result);
		
		if($result==0){
		$data2 = array(
			'category_id'=>$this->input->post('category_id'),
			'sub_category_id'=>$this->input->post('subcategory_id'),
			'attributes_id'=>$this->input->post('attributes_ids')
				
		);

		$this->Attribute_model->add($data2);
		$this->session->set_flashdata('msg', $this->input->post('name').'   has been added successfully');
		redirect('Attribute');
		}
		else{
			$attributes_id=$result[0]->attributes_id.','.$this->input->post('attributes_ids');
			$id=$result[0]->id;
			$data3 = array(
			'attributes_id'=>$attributes_id
			);
			$this->Attribute_model->edit($id,$data3);
			$this->session->set_flashdata('msg', 'id#'.$id.' has been updated successfully');
			redirect('Attribute');
		}
			
	}



	public function edit_view($id){


		$data['all_attributes']=$this->Attribute_model->allAttributes();
		$data['attribute'] = $this->Attribute_model->edit_view($id);
		$data['attributes']=explode(",",$data['attribute']->attributes_id);
		$data['category']=$this->ItemCategory_model->find2($data['attribute']->category_id);
		$data['sub_category']=$this->ItemCategory_model->find2($data['attribute']->sub_category_id);
		$this->load->view('AttributeM/attribute_detail',$data);
		
	}

	public function edit($id){
		$attributes=$this->input->post('attributes_ids');
		$category_id=$this->input->post('dd_category');
		$sub_category_id=$this->input->post('dd_subcategory');
		$data = array(
			'attributes_id'=>$attributes
		);
		$this->Attribute_model->edit($id,$data);
		$check=array(
			'item_category'=>$category_id,
			'item_subcategory'=>$sub_category_id
		);
		$data2=array(
			'attributes_values'=>""
		);
		$this->Item_model->editAttributeValues($data2,$check);
		$this->session->set_flashdata('msg', $this->input->post('name').'   has been updated successfully');
		redirect('Attribute');

	}

	
    public function findwithcategory(){
    	$category_id=$this->input->post('category_id');
    	return $this->Attribute_model->findwithcategory($category_id);
    }

    public function findAttributes(){
		$data=array(
			'category_id'=>$this->input->post('category_id'),
			'sub_category_id'=>$this->input->post('sub_category_id')
		);
    	return $this->Attribute_model->findAttributes($data);
	}

  
}
?>
