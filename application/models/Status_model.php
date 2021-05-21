<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status_model extends CI_Model {
  function __construct(){            
    parent::__construct();
    // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
  }

 
    
    
public function insertStatus($data){
    
       // return $data; exit;
       return $query1=$this->db->insert_batch('status_fm',$data);
        //echo $this->db->last_query();   
  }

  public function insertStatussingle($data){
    
    // return $data; exit;
    return $query1=$this->db->insert('status_fm',$data);
     //echo $this->db->last_query();   
}
  
  public function allstatus(){
   $query = $this->db->get('status_main_cat_fm');

    if($query->num_rows()>0){
        return $query->result();
      
    }
  }
    
public function BulkStatus(){
    
    $id=array('2','4');
    $this->db->where_in('id',$id);
   $query = $this->db->get('status_main_cat_fm');
    
    if($query->num_rows()>0){
        return $query->result();
      
    }
  }    
 
      public function find($id=null){

       if($id!=null)
       $this->db->where('id',$id);
       $query = $this->db->get('status_main_cat_fm');

        if($query->num_rows()>0){
            return $query->result();
          
        }

      }


      
}