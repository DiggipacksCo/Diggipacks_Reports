<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attribute_model extends CI_Model {
	function __construct(){            
	 
		parent::__construct();
		$this->load->model('ItemCategory_model');
		// $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	public function add($data){
		return $this->db->insert('item_attributes_m',$data);
		// $this->load->model('ItemInventory_model');
		// $this->ItemInventory_model->add($data['sku']);

	}
	public function addValues($data){
		return $this->db->insert('attributes_values',$data);
		// $this->load->model('ItemInventory_model');
		// $this->ItemInventory_model->add($data['sku']);

	}

	public function all(){
		 
		$query = $this->db->get('item_attributes_m');
		if($query->num_rows()>0){
				return $query->result();
			
		}
		
	}

	public function allAttributes(){
		 
		$query = $this->db->get('attributes');
		if($query->num_rows()>0){
				return $query->result();
			
			}
		
	}
	
	public function count() {
		return $this->db->count_all("item_attributes_m");
	}


	public function edit_view($id){
		//$data=[];
		$this->db->where('id' , $id);
		$query = $this->db->get('item_attributes_m');
		//$category = $this->ItemCategory_model->category($query->row()->category_id);
		// if($query->num_rows()>0 ){
		// 	$data['attribute']= $query->row();
		// 	$data['category'] = $category;
		// 	return $data;
		// }
		if($query->num_rows()>0){
      		return $query->row();
   		}
		 
	}

	public function edit($id,$data){
	
		$this->db->where('id',$id);
		return $this->db->update('item_attributes_m',$data);
	}

	public function findwithcategory($category_id){
    $this->db->where('category_id',$category_id);
    $query=$this->db->get('item_attributes_m');

	    if($query->num_rows()>0){
	      echo json_encode($query->result());

	    }

    }

    public function findAttributes($data){

    $this->db->where($data);
    $query=$this->db->get('item_attributes_m');

      if($query->num_rows()>0){
       echo json_encode($query->result());
         // print_r($query->result());
         // exit();
      }

  	}

  	public function findAttributes2($data){
  
    $this->db->where($data);
    $query=$this->db->get('item_attributes_m');

      if($query->num_rows()>0){
        return $query->result();
      }

  	}

  //   public function find($category_id){
  //   $this->db->where('category_id',$category_id);
  //   $query=$this->db->get('item_attributes_m');

	 //    if($query->num_rows()>0){
	 //       $array=array(
	 //       	'number' => $query->num_rows(),
	 //       	'data'=>$query->result()
	 //       );
	 //       return $array;
	 //    }
 	// }

 	public function find($id){
    $this->db->where('id',$id);
    $query=$this->db->get('attributes');

	    if($query->num_rows()>0){
	       return $query->result();
	    }
 	}

 	public function findid($data){
    $this->db->where($data);
    $query=$this->db->get('item_attributes_m');

	    if($query->num_rows()>0){
	       return $query->result();       
	    }
	    else{
	    	return 0;
	    }
 	}


 	public function findValues($category_id,$attribute_id,$item_id){
 	$data=array(
 		'attribute_id' =>$attribute_id ,
 		'category_id'=>$category_id ,
 		'item_id'=>$item_id
 	 );
 	// print_r($data);
  //   exit();
    $this->db->where($data);
    $query=$this->db->get('attributes_values');
    // print_r($query->result());
    // exit();

	    if($query->num_rows()>0){
	    	//print_r($query->num_rows());
	       //print_r($query->result());
	       return $query->result();
	    }
 	}

 	public function updatevalue($data,$value){
		$this->db->where($data);
		return $this->db->update('attributes_values',$value);
 	}

 	public function delete($data){
 		$this->db->where($data);
		return $this->db->delete('attributes_values');
 	}

}