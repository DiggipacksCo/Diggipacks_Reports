<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zone_model extends CI_Model {
	function __construct(){            
		parent::__construct();
		// $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	
	public function add_company($data){
		
   
        $this->db->trans_start();
		$this->db->insert('zone_list_fm',$data);
		//echo $this->db->last_query();
		$insert_id= $this->db->insert_id();
        $this->db->trans_complete();
		return $insert_id;
		
	}

	public function add_company_customer($data){
		
   
        $this->db->trans_start();
		$this->db->insert('zone_list_customer_fm',$data);
		//echo $this->db->last_query();
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
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->select('id,city');
        $this->db->where('city!=','');
		$this->db->where('deleted','N');
        $this->db->order_by('city');
		$query=$this->db->get('country');

		if($query->num_rows()>0){
			return $query->result();
		}

	}


	public function fetch_all_cities_new(){
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->select('id,city');
        $this->db->where('city!=','');
		$this->db->where('deleted','N');
        $this->db->order_by('city');
		$query=$this->db->get('country');

		if($query->num_rows()>0){
			return $query->result_array();
		}

	}

	public function all(){
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
   		$this->db->order_by('id', 'desc');
		$query = $this->db->get('zone_list_fm');
		//echo $this->db->last_query(); die;
		if($query->num_rows()>0){
				return $query->result();
			
			}

	}
	
	public function all_customer(){
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
	   $this->db->order_by('id', 'desc');
	$query = $this->db->get('zone_list_customer_fm');
	//echo $this->db->last_query(); die;
	if($query->num_rows()>0){
			return $query->result();
		
		}

}	


public function previousCity_customer($id=null){
	if($id!=null)
	{
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

		$this->db->where('id',$id);
		$this->db->order_by('id', 'desc');
		$query = $this->db->get('zone_list_customer_fm');

		//echo $this->db->last_query(); die;
		if($query->num_rows()>0){
		return $query->row_array();

		}
}
}
	public function previousCity($id=null){
		if($id!=null)
		{
			$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

			$this->db->where('id',$id);
			$this->db->order_by('id', 'desc');
			$query = $this->db->get('zone_list_fm');

			//echo $this->db->last_query(); die;
			if($query->num_rows()>0){
			return $query->row_array();

			}
	}
	}
	
	public function count() {
            
		return $this->db->count_all("zone_list_fm");
	}


	public function edit_view($id){
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->where('id' , $id);
		$query = $this->db->get('zone_list_fm');
		if($query->num_rows()>0){
			return $query->row_array();
		}
		
	}
	public function edit_view_customerdata($id){
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->where('id' , $id);
		$query = $this->db->get('zone_list_fm');
		if($query->num_rows()>0){
			return $query->row_array();
		}
		
	}

	
	public function edit_custimer($id,$data){
	
		$this->db->where('id',$id);
                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		return $this->db->update('zone_list_fm',$data);
	}


	public function find($id){ 
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->where('id' , $id);
		// $this->db->get_where('seller_m',array('id'=>$id));
		$query = $this->db->get('zone_list_fm');
		if($query->num_rows()==1){
			return $query->row();
		}
	}

	public function custList(){
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		 $this->db->where('access_fm', 'Y');
	  $this->db->order_by('id', 'desc');
		   
   $query = $this->db->get('customer');
   //echo $this->db->last_query(); die;
   if($query->num_rows()>0){
		   return $query->result();
	   
	   }

}

	public function Zone(){
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->where('id',0);
		$query=$this->db->get('zone_list_fm');

		if($query->num_rows()>0){
			return $query->result();
		}
	}

	public function ZoneCustomer(){
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
	$this->db->where('id',0);
	$query=$this->db->get('zone_list_customer_fm');

	if($query->num_rows()>0){
		return $query->result();
	}
}
	public function customer($seller_id,$customer_id){
		$data=array(
			'id'=>$seller_id
		);
                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

		$this->db->where('id',$customer_id);
		return $this->db->update('zone_list_fm',$data);


	}
	public function update_seller_id($seller_id,$customer_id){
		$data=array(
			'zone_list_fm'=>$customer_id
		);
                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

		$this->db->where('id',$seller_id);
		return $this->db->update('zone_list_fm',$data);

	}
	public function find_customer($id){
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->where('id',$id);
		$query=$this->db->get('zone_list_fm');

		if($query->num_rows()>0){
			return $query->row();
		}
	}

	public function find_customer_sellerm($id){
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->where('id',$id);
		$query=$this->db->get('zone_list_fm');

		if($query->num_rows()>0){
			return $query->result();
		}
	}

	public function find_customer_sellerm_cust($id){
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
	$this->db->where('id',$id);
	$query=$this->db->get('zone_list_customer_fm');

	if($query->num_rows()>0){
		return $query->result();
	}
}

	public function find1()
	{
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		
		$query=$this->db->get('zone_list_fm');

		if($query->num_rows()>0){
			
			return $query->result();
		}
	}


        
  public function UpdateZoneCompanyLIst(array $data,$id=null)
  {
      $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
	 return $this->db->update('zone_list_fm',$data,array('id'=>$id));
  }

  public function UpdateZoneCompanyLIstCustomer(array $data,$id=null)
  {
      $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
	 return $this->db->update('zone_list_customer_fm',$data,array('id'=>$id));
  }
	public function find2()
	{
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		$this->db->where('id!=',0);
		$query=$this->db->get('zone_list_fm');

		if($query->num_rows()>0){
			return $query->result();
		}
	}
}