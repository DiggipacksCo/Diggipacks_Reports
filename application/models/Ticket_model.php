<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends CI_Model {
  function __construct(){            
    parent::__construct();
  
  }
 public function filter($page_no,$filterarray=array()){
	 
          $page_no;
          $limit = 100;
        if(empty($page_no)){
            $start = 0;
        }else{
            $start = ($page_no-1)*$limit;
        }    
          
                  $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
         $this->db->select('id,ticket_id,seller_id,pickup_id,subject,message,status,entrydate');
        $this->db->from('pickup_ticket');
		
		if($filterarray['seller_id'])
		$this->db->where('seller_id',$filterarray['seller_id']);
		if($filterarray['searchstatus'])
		$this->db->where('status',$filterarray['searchstatus']);
		$this->db->where('deleted','N');
        $this->db->order_by('id','ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
       
      if($query->num_rows()>0){
         
          $data['result']=$query->result_array();
         $data['count']=$this->ticketCount($page_no,$filterarray); 
            return $data;
          //return $page_no.$this->db->last_query();

        }
        else{
          $data['result']='';
         $data['count']=0; 
            return $data;
        }
       
      }
	   public function ticketCount($page_no,$filterarray=array()){
         $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
         $this->db->select('id');
        $this->db->from('pickup_ticket');
		if($filterarray['seller_id'])
		$this->db->where('seller_id',$filterarray['seller_id']);
		if($filterarray['searchstatus'])
		$this->db->where('status',$filterarray['searchstatus']);
		$this->db->where('deleted','N');
        $this->db->order_by('id','ASC');
       
    
        $query = $this->db->get();
       
      //return $this->db->last_query(); die;
        if($query->num_rows()>0){
         
       $data= $query->num_rows();
        return $data;    
         // return $page_no.$this->db->last_query();

        }
        return 0;
      }
	  
	   public function filter_fulfil($page_no,$filterarray=array()){
	 
          $page_no;
          $limit = 100;
        if(empty($page_no)){
            $start = 0;
        }else{
            $start = ($page_no-1)*$limit;
        }    
          
         $this->db->select('id,ticket_id,seller_id,awb_no,subject,message,status,entrydate');
        $this->db->from('ticket');
		 $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		if($filterarray['seller_id'])
		$this->db->where('seller_id',$filterarray['seller_id']);
		if($filterarray['searchstatus'])
		$this->db->where('status',$filterarray['searchstatus']);
		$this->db->where('deleted','N');
        $this->db->order_by('id','ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
      // echo $this->db->last_query(); die;
      if($query->num_rows()>0){
         
          $data['result']=$query->result_array();
         $data['count']=$this->ticketCount_fulfil($page_no,$filterarray); 
            return $data;
          //return $page_no.$this->db->last_query();

        }
        else{
          $data['result']='';
         $data['count']=0; 
            return $data;
        }
       
      }
	   public function ticketCount_fulfil($page_no,$filterarray=array()){
         $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
         $this->db->select('id');
        $this->db->from('ticket');
		if($filterarray['seller_id'])
		$this->db->where('seller_id',$filterarray['seller_id']);
		if($filterarray['searchstatus'])
		$this->db->where('status',$filterarray['searchstatus']);
		$this->db->where('deleted','N');
        $this->db->order_by('id','ASC');
       
    
        $query = $this->db->get();
       
      //return $this->db->last_query(); die;
        if($query->num_rows()>0){
         
       $data= $query->num_rows();
        return $data;    
         // return $page_no.$this->db->last_query();

        }
        return 0;
      }
	  public function getticketdetailsdata_fm($ticket_id=null)
	  {
               $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('*');
        $this->db->from('ticket');
		$this->db->where('ticket_id',$ticket_id);
		$this->db->where('deleted','N');
        $query = $this->db->get();
        if($query->num_rows()>0){
        $data= $query->row_array();
        return $data;    
        }
        return array();  
	  }
	  public function getticketdetailsdata($ticket_id=null)
	  {
               $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('*');
        $this->db->from('pickup_ticket');
		$this->db->where('ticket_id',$ticket_id);
		$this->db->where('deleted','N');
        $query = $this->db->get();
        if($query->num_rows()>0){
        $data= $query->row_array();
        return $data;    
        }
        return array();  
	  }
	  public function gettickethistorydata($ticket_id=null)
	  {
               $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('*');
        $this->db->from('ticket_history');
		$this->db->where('ticket_id',$ticket_id);
        $query = $this->db->get();
		//return $this->db->last_query();
        if($query->num_rows()>0){
        $data= $query->result_array();
        return $data;    
        }
        return array();  
	  }
	  public function gettickethistorydata_fm($ticket_id=null)
	  {
               $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('*');
        $this->db->from('ticket_history_fm');
		$this->db->where('ticket_id',$ticket_id);
        $query = $this->db->get();
		//return $this->db->last_query();
        if($query->num_rows()>0){
        $data= $query->result_array();
        return $data;    
        }
        return array();  
	  }
	  
	  public function getticketupdatequery_fm(array $data,$data_w=array())
	  {
               $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		
			return $this->db->update("ticket",$data,$data_w);
	      // echo $this->db->last_query(); 
			
	  }
	   public function getticketupdatequery($id=null)
	  {
                $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('*');
        $this->db->from('pickup_ticket');
		$this->db->where('id',$id);
		//$this->db->where('deleted','N');
        $query = $this->db->get();
		//return $this->db->last_query();
        if($query->num_rows()>0){
        $data= $query->row_array();
			if($data['status']!='complated')
			{
				//return "ssssssss";
                             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
			return $this->db->update("pickup_ticket",array('status'=>'complated','updateon'=>date('Y-m-d')),array('id'=>$id));
			}
			else
			return false;
        }
		else
		return false;
	  }
	  
	  public function getupdatereplyMessage($data=array())
	  {
               $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('*');
        $this->db->from('pickup_ticket');
		$this->db->where('id',$data['tid']);
		//$this->db->where('deleted','N');
		 //$this->db->limit(1, 0);
        $query = $this->db->get();
		//return $this->db->last_query();
        if($query->num_rows()>0){
           $historyData= $query->row();
			
			$update_array=array('seller_id'=>$historyData->seller_id,'ticket_id'=>$data['tid'],'message'=>$data['replymess'],'status'=>'message','entrydate'=>date("Y-m-d H:i:sa"),'position'=>'right','user_id'=>$this->session->userdata('user_details')['user_id'],'super_id'=>$this->session->userdata('user_details')['super_id']);
			 $this->db->insert("ticket_history",$update_array);
			
	     }
	  }
	   public function getupdatereplyMessage_fm($data=array())
	  {
                $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('*');
        $this->db->from('ticket');
		$this->db->where('id',$data['tid']);
		//$this->db->where('deleted','N');
		 //$this->db->limit(1, 0);
        $query = $this->db->get();
		//return $this->db->last_query();
        if($query->num_rows()>0){
           $historyData= $query->row();
			
			$update_array=array('seller_id'=>$historyData->seller_id,'ticket_id'=>$data['tid'],'message'=>$data['replymess'],'status'=>'message','entrydate'=>date("Y-m-d H:i:sa"),'position'=>'right','user_id'=>$this->session->userdata('user_details')['user_id'],'super_id'=>$this->session->userdata('user_details')['super_id']);
			 $this->db->insert("ticket_history_fm",$update_array);
			
	     }
	  }
	  
	  
	

}