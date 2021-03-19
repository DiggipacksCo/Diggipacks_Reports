<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemCategory_model extends CI_Model {
function __construct(){            
   
    parent::__construct();
   
    // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
  }

  public function add($data){

    $result=$this->db->insert('item_category_m',$data); 
    if($result==1){ 
      return $result;
    }
    else{
      $result=$this->db->error();
      return  $result['code'];
    }
  }

  public function all(){
    $query = $this->db->get('item_category_m');
    if($query->num_rows()>0){
            return $query->result();
        
        }
    
  }

  public function allMain(){
    $this->db->where('main_id',0);
    $query = $this->db->get('item_category_m');
    if($query->num_rows()>0){
            return $query->result();
        
        }
    
  }

  public function allSub(){
    $this->db->where_not_in('main_id',0);
    $query = $this->db->get('item_category_m');
    if($query->num_rows()>0){
            return $query->result();
        
        }
    
  }

  public function count() {
    return $this->db->count_all("item_category_m");
  }


  public function edit_view($id){
    $this->db->where('id' , $id);
    // $this->db->get_where('seller_m',array('id'=>$id));
    $query = $this->db->get('item_category_m');
    if($query->num_rows()>0){
      return $query->row();
    }
    
  }

  public function edit($id,$data){
  
  
    $this->db->where('id',$id);
    return $this->db->update('item_category_m',$data);
  }

  

  public function find($category_id){
    $this->db->where('id',$category_id);
    $query=$this->db->get('item_category_m');

      if($query->num_rows()>0){
        echo json_encode($query->result());
        // print_r($query->result());
        // exit();
      }

  }

  public function findMain($category_id){
    $this->db->where('main_id',$category_id);
    $query=$this->db->get('item_category_m');

      if($query->num_rows()>0){
        echo json_encode($query->result());
        // print_r($query->result());
        // exit();
      }

  }


    public function category($id){
      $this->db->where('id' , $id);
      // $this->db->get_where('seller_m',array('id'=>$id));
      $query = $this->db->get('item_category_m');
      if($query->num_rows()>0){
        return $query->row();
      }
    }

    public function find2($category_id){
      $this->db->where('id',$category_id);
      $query=$this->db->get('item_category_m');

      if($query->num_rows()>0){
       return $query->result();
        // print_r($query->result());
        // exit();
      }

    }
}