<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
    class Cartoon_model extends CI_Model {
    	function __construct(){            
   
    parent::__construct();
   
    // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
  }
    

    
     
    public function add($data){
		return $this->db->insert('cartoon_inventory',$data);
    }

    public function all(){
        $query = $this->db->get('cartoon_inventory');
		if($query->num_rows()>0){
				return $query->result();
			
			}

    }	

    public function edit_view($id){
		$this->db->where('id' , $id);
		// $this->db->get_where('seller_m',array('id'=>$id));
		$query = $this->db->get('cartoon_inventory');
		if($query->num_rows()>0){
			return $query->row();
	}
		 
	}

	public function edit($id,$data){
		$this->db->where('id',$id);
		return $this->db->update('cartoon_inventory',$data);
	}
 
	// public function all(){
	// 	$query = $this->db->get('cartoon_inventory');
	// 	if($query->num_rows()>0){
	// 		return $query->result();
	// }
	// }
	public function count() {
		return $this->db->count_all("cartoon_inventory");
	}

	 public function find($cartoon_sku){
    // print_r($array['seller_id']);
    // exit();
    // $find=array(
    //   'seller_id'=>$array['seller_id'],
    //   'item_sku'=>$array['item_sku']
    // );
    // print_r($find);
    // exit();
    $this->db->where('id',$cartoon_sku);
    $query=$this->db->get('cartoon_inventory');
     if($query->num_rows()==1){
       
        return $query->result();
       
        
        }
        return false;
  
  }

  public function count_all() {
    $query=$this->db->select('quantity')->get('cartoon_inventory');
    $count=0;
    if($query->num_rows()>0){
       
       for ($i=0; $i < $query->num_rows(); $i++) { 
          $count+=$query->result()[$i]->quantity;
       }
        return $count;
    }
  }
  
  public function getInventory($data){

    
    $this->db->where('id',$data);
    $query=$this->db->get('cartoon_inventory');

    if($query->num_rows()==1){
    echo json_encode($query->row());

    }

  }

}