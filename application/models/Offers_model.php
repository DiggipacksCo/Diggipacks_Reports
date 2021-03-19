<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offers_model extends CI_Model {
	function __construct(){            
		parent::__construct();
		// $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	public function add($data){
		$this->db->insert('customer',$data);
		return $this->db->insert_id();
		
	}
	
	public function add_customer($data){
		
   
        $this->db->trans_start();
		$this->db->insert('customer',$data);
		$insert_id= $this->db->insert_id();
        $this->db->trans_complete();
		return $insert_id;
		
	}
	// public function all($limit , $start){
	// 	$this->db->limit($limit, $start);
	// 	$query = $this->db->get('seller_m');
	// 	if($query->num_rows()>0){
	// 			// return $query->result();
	// 		foreach ($query->result() as $row) {
	// 			$data[] = $row;
	// 		}
	// 		return $data;
	// 	}
		
	// }
	   
	public function fetch_all_cities(){
           //  $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->select('id,city');
		$this->db->where('deleted','N');
		$query=$this->db->get('country');

		if($query->num_rows()>0){
			return $query->result();
		}

	}

	public function all_gift($filterArr=array()){
		$page_no;
          $limit = 100;
        if(empty($filterArr['page_no'])){
            $start = 0;
        }else{
            $start = ($filterArr['page_no']-1)*$limit;
        }  
		
		if(!empty($filterArr['qty']))
		{
			
			//$this->db->where('qty' , $filterArr['qty']);
		}
		if(!empty($filterArr['promocode']))
		{
			
			$this->db->where('promocode' , $filterArr['promocode']);
		}
		if(!empty($filterArr['seller_id']))
		{
			$this->db->where('seller_id' , $filterArr['seller_id']);
		}
		if(!empty($filterArr['sku']))
		{
			//$id=getalldataitemtablesSKU($filterArr['sku'],'id');
			$this->db->where('main_item' , $filterArr['sku']);
		}
                 $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('*');
		 $this->db->from('promo_gift_tbl');
		 $this->db->group_by('promocode');
   		$this->db->order_by('id', 'desc');
		 $this->db->limit($limit, $start);
		 
		$query = $this->db->get();
		//echo $this->db->last_query();
		 if($query->num_rows()>0){
         
          $data['result']=$query->result_array();
         $data['count']=$this->allCount_gift($filterArr); 
            return $data;
         // return $page_no.$this->db->last_query();

        }
        else{
          $data['result']='';
         $data['count']=0; 
            return $data;
        }

	}	
        
        public function allCount_gift(array $filterArr)
	{
            
            if(!empty($filterArr['promocode']))
		{
			
			$this->db->where('promocode' , $filterArr['promocode']);
		}
		if(!empty($filterArr['seller_id']))
		{
			$this->db->where('seller_id' , $filterArr['seller_id']);
		}
             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		  $this->db->select('COUNT(id) as idCount');
		  $this->db->from('promo_gift_tbl');
		   $this->db->group_by('promocode');
		   $query = $this->db->get();
                 //  echo $this->db->last_query();
                  
           if($query->num_rows()>0){
            return count($query->result_array());
        }
        else{
         return 0;
        }
	}
	
        
        public function all($filterArr=array()){
		$page_no;
          $limit = 100;
        if(empty($filterArr['page_no'])){
            $start = 0;
        }else{
            $start = ($filterArr['page_no']-1)*$limit;
        }  
		
		if(!empty($filterArr['qty']))
		{
			
			$this->db->where('qty' , $filterArr['qty']);
		}
		if(!empty($filterArr['promocode']))
		{
			
			$this->db->where('promocode' , $filterArr['promocode']);
		}
		if(!empty($filterArr['seller_id']))
		{
			$this->db->where('seller_id' , $filterArr['seller_id']);
		}
		if(!empty($filterArr['sku']))
		{
			$id=getalldataitemtablesSKU($filterArr['sku'],'id');
			$this->db->where('main_item' , $id);
		}
                 $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('*');
		 $this->db->from('promo_tbl');
		 $this->db->group_by('promocode');
   		$this->db->order_by('id', 'desc');
		 $this->db->limit($limit, $start);
		 
		$query = $this->db->get();
		//echo $this->db->last_query();
		 if($query->num_rows()>0){
         
          $data['result']=$query->result_array();
         $data['count']=$this->allCount($filterArr); 
            return $data;
         // return $page_no.$this->db->last_query();

        }
        else{
          $data['result']='';
         $data['count']=0; 
            return $data;
        }

	}	
	public function all_orders($filterArr=array()){
		$page_no;
          $limit = 100;
        if(empty($filterArr['page_no'])){
            $start = 0;
        }else{
            $start = ($filterArr['page_no']-1)*$limit;
        }  
		
		if(!empty($filterArr['qty']))
		{
			
			$this->db->where('qty' , $filterArr['qty']);
		}
		if(!empty($filterArr['promocode']))
		{
			
			$this->db->where('promo_code' , trim($filterArr['promocode']));
		}
		if(!empty($filterArr['seller_id']))
		{
			$this->db->where('seller_id' , $filterArr['seller_id']);
		}
		if(!empty($filterArr['sku']))
		{
			$id=getalldataitemtablesSKU($filterArr['sku'],'id');
			$this->db->where('sku' ,trim($filterArr['sku']));
		}
                 $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('*');
		 $this->db->from('promo_history');
		 $this->db->group_by('slip_no');
   		$this->db->order_by('id', 'desc');
		 $this->db->limit($limit, $start);
		 
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		 if($query->num_rows()>0){
         
          $data['result']=($query->result_array());
         $data['count']=count($this->allCount_orders($filterArr)); 
            return $data;
         // return $page_no.$this->db->last_query();

        }
        else{
          $data['result']='';
         $data['count']=0; 
            return $data;
        }

	}	
	
        
        public function all_orders_gift($filterArr=array()){
		$page_no;
          $limit = 100;
        if(empty($filterArr['page_no'])){
            $start = 0;
        }else{
            $start = ($filterArr['page_no']-1)*$limit;
        }  
		
		if(!empty($filterArr['qty']))
		{
			
			//s$this->db->where('qty' , $filterArr['qty']);
		}
		if(!empty($filterArr['promocode']))
		{
			
			$this->db->where('promo_code' , trim($filterArr['promocode']));
		}
		if(!empty($filterArr['seller_id']))
		{
			$this->db->where('seller_id' , $filterArr['seller_id']);
		}
		if(!empty($filterArr['sku']))
		{
			$id=getalldataitemtablesSKU($filterArr['sku'],'id');
			$this->db->where('sku' ,trim($filterArr['sku']));
		}
                 $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('*');
		 $this->db->from('promo_history_gift');
		 $this->db->group_by('slip_no');
   		$this->db->order_by('id', 'desc');
		 $this->db->limit($limit, $start);
		 
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		 if($query->num_rows()>0){
         
          $data['result']=($query->result_array());
         $data['count']=count($this->allCount_orders_gift($filterArr)); 
            return $data;
         // return $page_no.$this->db->last_query();

        }
        else{
          $data['result']='';
         $data['count']=0; 
            return $data;
        }

	}	
        
        public function allCount_orders_gift()
	{
             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		//echo "sssss"; die;
		  $this->db->select('id');
		  $this->db->from('promo_history_gift');
		 $this->db->group_by('slip_no');
		
		   $query = $this->db->get();
		  //  echo $this->db->last_query(); die;
           if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
         return 0;
        }
        }
	public function allCount_orders()
	{
             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		//echo "sssss"; die;
		  $this->db->select('id');
		  $this->db->from('promo_history');
		 $this->db->group_by('slip_no');
		
		   $query = $this->db->get();
		  //  echo $this->db->last_query(); die;
           if($query->num_rows()>0){
            return $query->result_array();
        }
        else{
         return 0;
        }
	}
	
	public function GetALlskuAQtyQry_gift($promocode=null)
	{
             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('main_item,gift_item');
		 $this->db->from('promo_gift_tbl');
		 $this->db->where('promocode' , $promocode);
		 $query = $this->db->get();
		 return $query->result();
	}
        
        public function GetALlskuAQtyQry_gift_edit($promocode=null)
	{
             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('main_item');
		 $this->db->from('promo_gift_tbl');
		 $this->db->where('promocode' , $promocode);
		 $query = $this->db->get();
		 return $query->result();
	}
        
        public function GetALlskuAQtyQry($promocode=null)
	{
             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('main_item,qty');
		 $this->db->from('promo_tbl');
		 $this->db->where('promocode' , $promocode);
		 $query = $this->db->get();
		 return $query->result();
	}
	public function GetALlskuAQtyQry_orders($promocode=null)
	{
             $this->db->where('promo_history.super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('promo_history.sku,promo_history.qty,items_m.item_path');
                 $this->db->join('items_m','items_m.sku=promo_history.sku');
		 $this->db->from('promo_history');
		 $this->db->where('promo_history.slip_no' , $promocode);
		 $query = $this->db->get();
		 return $query->result();
	}
        
        public function GetALlskuAQtyQry_orders_gift($promocode=null)
	{
             $this->db->where('promo_history_gift.super_id',$this->session->userdata('user_details')['super_id']);
		 $this->db->select('promo_history_gift.sku,items_m.item_path');
                 $this->db->join('items_m','items_m.sku=promo_history_gift.sku');
		  $this->db->from('promo_history_gift');
		 $this->db->where('promo_history_gift.slip_no' , $promocode);
		 $query = $this->db->get();
              //ss   echo $this->db->last_query();die;
		 return $query->result();
	}
	public function allCount()
	{
             $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		  $this->db->select('COUNT(id) as idCount');
		  $this->db->from('promo_tbl');
		   $this->db->group_by('promocode');
		   $query = $this->db->get();
           if($query->num_rows()>0){
            return $query->row_array()['idCount'];
        }
        else{
         return 0;
        }
	}
	
	public function count() {
		return $this->db->count_all("seller_m");
	}
	public function getinsertoffersdata($data=array()){
		
   
       $this->db->insert_batch('promo_tbl', $data); 
	  // echo $this->db->last_query();
		 return $this->db->insert_id();
		
	}
        
        public function getinsertoffersdata_gift($data=array()){
		
   
       $this->db->insert_batch('promo_gift_tbl', $data); 
	  // echo $this->db->last_query();
		 return $this->db->insert_id();
		
	}
          public function getinsertoffersdata_gift_update($data=array(),$promocode=null){
		
   
      return $this->db->update('promo_gift_tbl', $data,array('promocode'=>$promocode)); 
	  // echo $this->db->last_query();
		
		
	}
        
       public function DeleteOfferData($promocode=null)
       {
         $this->db->query("delete from promo_gift_tbl where promocode='$promocode'");  
       }
	
public function getallsellersdata(){
     $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
      $this->db->where('access_fm','Y');
		$query = $this->db->get('customer');
		if($query->num_rows()>0){
			return $query->result_array();
		}
		
	} 
	public function getallitemsdata($id=null){
		$query = $this->db->query("select items_m.id,items_m.sku from items_m LEFT JOIN item_inventory on items_m.id=item_inventory.item_sku where item_inventory.seller_id='".$id."' and item_inventory.super_id='".$this->session->userdata('user_details')['super_id']."' group by item_inventory.item_sku");
		
		if($query->num_rows()>0){
			return $query->result_array();
		}
		
	}
	public function GetupdateOfferdata(array $data,$id=null)
	{
            $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		return $this->db->update('promo_tbl',$data,array('promocode'=>$id));
	}
        
        public function GetupdateOfferdata_gift(array $data,$id=null)
	{
            $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		return $this->db->update('promo_gift_tbl',$data,array('promocode'=>$id));
	}

	public function edit_view($id){
            $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->where('id' , $id);
		$query = $this->db->get('promo_tbl');
		if($query->num_rows()>0){
			return $query->row_array();
		}
		
	}
        
        public function edit_view_gift($id){
            $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->where('promocode' , $id);
		$query = $this->db->get('promo_gift_tbl');
		if($query->num_rows()>0){
			return $query->row_array();
		}
		
	}
	public function edit_view_customerdata($id){
            $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->where('seller_id' , $id);
		$query = $this->db->get('customer');
		if($query->num_rows()>0){
			return $query->row_array();
		}
		
	}

	public function edit($id,$data){
            $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
	
		$this->db->where('id',$id);
		return $this->db->update('customer',$data);
	}
	public function edit_custimer($id,$data){
	
            $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->where('id',$id);
		return $this->db->update('customer',$data);
	}


	public function find($id){
		$this->db->where('id' , $id);
		// $this->db->get_where('seller_m',array('id'=>$id));
		$query = $this->db->get('customer');
		if($query->num_rows()==1){
			return $query->row();
		}
	}

	public function customers(){
		$this->db->where('id',0);
		$query=$this->db->get('customer');

		if($query->num_rows()>0){
			return $query->result();
		}
	}
	public function customer($seller_id,$customer_id){
		$data=array(
			'seller_id'=>$seller_id
		);

		$this->db->where('id',$customer_id);
		return $this->db->update('customer',$data);


	}
	public function update_seller_id($seller_id,$customer_id){
		$data=array(
			'customer'=>$customer_id
		);

		$this->db->where('id',$seller_id);
		return $this->db->update('seller_m',$data);

	}
	public function find_customer($id){
		$this->db->where('seller_id',$id);
		$query=$this->db->get('customer');

		if($query->num_rows()>0){
			return $query->row();
		}
	}

	public function find_customer_sellerm($id){
		$this->db->where('customer',$id);
		$query=$this->db->get('seller_m');

		if($query->num_rows()>0){
			return $query->result();
		}
	}

	public function find1()
	{
		
		$query=$this->db->get('seller_m');

		if($query->num_rows()>0){
			return $query->result();
		}
	}

	public function find2()
	{
		$this->db->where('seller_id!=',0);
		$query=$this->db->get('customer');

		if($query->num_rows()>0){
			return $query->result();
		}
	}
}