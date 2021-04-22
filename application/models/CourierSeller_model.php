<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CourierSeller_model extends CI_Model {
	function __construct(){            
		parent::__construct();
		// $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}
    
   public function updateCourier($data=array())
   {
	$this->db->update_batch('sellerCourier', $data, 'id'); 
   }


	public function getSellerAddCourier($seller_id=NULL)
	{
        
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);		
		//$this->db->where('status', 'Y');		
		$this->db->where('deleted', 'N');	
		$this->db->select('*');
        $this->db->from('courier_company');
		$query1 = $this->db->get();
		$data = $query1->result_array();

		if(!empty($data)){
			$i = 0;
			foreach ($data as $cc_val) {
				$sel = $this->db->query("select * from sellerCourier where seller_id = '".$seller_id."' and cc_id='".$cc_val['cc_id']."'");
				if($sel->num_rows() > 0){
				}
				else
				{
					$i++;
						if($cc_val['status']==  'Y'){
							$statusVal = '0';
						}
						else {
							$statusVal = '1';
						} 
					$instertData=  array(
						'seller_id' => $seller_id, 
						'cc_id' => $cc_val['cc_id'], 
						'priority' => $i, 
						'status' => $statusVal  , 
						'super_id' => $this->session->userdata('user_details')['super_id'] 
					);
					$this->db->insert('sellerCourier', $instertData);
				}

			}
		
		}
			$getInt = $this->getSellerCourier($seller_id);
		return $getInt;
		

        

	}

	public function getSellerCourier($seller_id=null)
{	

 		$this->db->select('sellerCourier.*,courier_company.company');
        $this->db->from('sellerCourier');
		$this->db->join('courier_company', 'sellerCourier.cc_id = courier_company.cc_id');
		$this->db->where('sellerCourier.super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->where('courier_company.super_id',$this->session->userdata('user_details')['super_id']);
		$this->db->where('sellerCourier.seller_id',$seller_id);
		$query = $this->db->get();
		//echo "<br><br><br>". $this->db->last_query(); die; 
		return $data = $query->result_array();
		
	}



	
	public  function GetUpdatePackagedetailsCustomers($cust_id,$plansDetailsArray){
		$query = $this->db->query("SELECT * FROM customer_package_details where cust_id='$cust_id'");
		if($query->num_rows()==0)
		{
			$this->db->insert("customer_package_details",$plansDetailsArray);
		}
		else
		{
			$addArray=array('entrydate'=>date('Y-m-d H:i:s'),'totalorders'=>$plansDetailsArray['totalorders'],'api_integration'=>$plansDetailsArray['api_integration'],'sku_printed'=>$plansDetailsArray['sku_printed'],'skuperorder'=>$plansDetailsArray['skuperorder'],'pins'=>$plansDetailsArray['pins']);
			$this->db->update("customer_package_details",$addArray,array('cust_id'=>$cust_id));
		}
		
		
	  }
	  
	  
	  public function add_distributor($customer_info=array())
	{
			$this->db->insert("distributor",$customer_info);
	}
	
	
	
	public function update_distributor($data=array(),$id=null)
	{
		if($id)
		return $this->db->update('distributor',$data,array('id'=>$id));
		
		
		
	}
}