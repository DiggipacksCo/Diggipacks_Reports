<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class LastMile_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function addlmIncoice($addedArray=array())
    {
        $this->db->insert_batch('Payable_invoice_fm', $addedArray);
           $this->db->last_query(); 
		   return true;
    }
    

    
    public function calculateReturn($cust_id=null)
    {
       

        $this->db->select('finance_carges.seller_id,finance_carges.rate, finance_carges.id, finance_carges.setpiece, finance_cat.type, finance_cat.name ');
        $this->db->from('finance_carges');
        $this->db->join('finance_cat', 'finance_carges.cat_id = finance_cat.id');
       // $this->db->where('cc_id',$filtr['seller_id']);
        $this->db->where('finance_carges.seller_id',$cust_id);
        $this->db->where_in('finance_cat.name',array('Additional Return','Return'));
        $query = $this->db->get();
        // echo  $this->db->last_query(); 

         
        if($query->num_rows()>0)
        {
            return $query->result_array();
        }
    }

	public function updateShipmet($data=array())
    {
        
        $this->db->update_batch('shipment_fm',$data,'slip_no');
		//echo $this->db->last_query();
            return true;
        
        
    }
    public function GetupdateFinalInvocie($data=array(),$dataW=array())
    {
        
        $this->db->update('Payable_invoice_fm',$data,$dataW);
       // echo $this->db->last_query(); exit;
         $this->db->update('shipment_fm',array('pay_invoice_status'=>'YES','pay_invoice_no'=>$dataW['invoice_no']),array('pay_invoice_no'=>$dataW['invoice_no']));
  // echo $this->db->last_query(); exit;
        return true;
        
        
    }

	public function addInvoiceUpdateDiscount($data=array())
    {
        $this->db->update('Payable_invoice_fm',$data,array('invoice_no'=>$data['invoice_no']));
// echo $this->db->last_query(); exit;
	
        return true;
        
    }

    public function addInvoiceUpdate($data=array(),$dataW=array())
    {
        $this->db->update('Payable_invoice_fm',$data,$dataW);
        ///echo $this->db->last_query();
		$this->db->update('shipment_fm',array('rec_invoice_status'=>'YES','pay_invoice_no'=>$dataW['invoice_no']),array('pay_invoice_no'=>$dataW['invoice_no']));

        return true;
        
    }
    public function Getpay_edit($id=null)
		{
			$this->db->where('Payable_invoice_fm.super_id', $this->session->userdata('user_details')['super_id']);
		 $this->db->select('*');
         $this->db->from('Payable_invoice_fm'); 
		 $this->db->where('deleted', 'N');
		 $this->db->where('id', $id);
		
		
         $query = $this->db->get();
         // return $this->db->last_query(); die; 
		  
		if($query->num_rows()>0)
		{
			return $query->row_array();
		}
		}
	public function codreceivablePrintQry($invoice_no=null)
		{
			$query=$this->db->query("select * from Payable_invoice_fm where invoice_no='".$invoice_no."' and deleted='N' and super_id='".$this->session->userdata('user_details')['super_id']."'");
			//echo $this->db->last_query(); die;
			return $query->result_array();
		}
    public function getviewPayableInvoice($data=array()) 
	{ 
		$data['page_no'];
          $limit = 20;
        if(empty($data['page_no']))
		{
            $start = 0;
        }
		else
		{
         $start = ($data['page_no']-1)*$limit;
		} 
		
		if(!empty($data))
			{
			
				if(!empty($data['cust_id']))
				{
					 $cust_data=implode(',',$_POST['cust_id']);
					 $cond1="Payable_invoice.cust_id IN (".$cust_data.")";
					$this->db->where($cond1);
				}
				if(!empty($data['created']))
				{
					 $created=implode(',',$_POST['created']); 
					 $cond2="Payable_invoice.invoice_created_by IN (".$created.")";
					 $this->db->where($cond2);
				}
				if(!empty($data['paid']))
				{
					 $paid=implode(',',$data['paid']); 
					 $cond3=" Payable_invoice.cod_paid_by IN (".$paid.")";
					  $this->db->where($cond3);
				}
				if(!empty($data['received']))
				{
					 $received=implode(',',$data['received']); 
					 $cond4=" Payable_invoice.receivable_paid_by IN (".$received.")";
					  $this->db->where($cond4);
				}
				if(!empty($data['invoices']))
				{
					 //$invoices=implode("','",$data['invoices']); 
					 $cond5="(Payable_invoice.invoice_no = '".$data['invoices']."') ";
					  $this->db->where($cond5);
				}
				
				if(!empty($data['mode']))
				{
					 
					 $cond6="Payable_invoice.mode = '".$data['mode']."' ";
					  $this->db->where($cond6);
				}
				if(!empty($data['status']))
				{
					 
					 $cond7=" Payable_invoice.status = '".$data['status']."' ";
					  $this->db->where($cond7);
				}
				if(!empty($data['p_date1']) && !empty($data['p_date2'])  )
			{
			
			$cond8=" DATE(Payable_invoice.cod_paid_date) BETWEEN '".$data['p_date1']."' AND '".$data['p_date2']."'";
			 $this->db->where($cond8);
				}
				
					if(!empty($data['c_date1']) && !empty($data['c_date2'])  )
			{
			
			$cond9="DATE(Payable_invoice.invoice_created_date) BETWEEN '".$data['c_date1']."' AND '".$data['c_date2']."'";
			 $this->db->where($cond9);
				}
				
				if(!empty($data['r_date1']) && !empty($data['r_date2'])  )
			{
			
			$cond10="DATE(Payable_invoice.receivable_paid_date) BETWEEN '".$data['r_date1']."' AND '".$data['r_date2']."'";
			 $this->db->where($cond10);
				}	
				
					if(!empty($data['cl_date1']) && !empty($data['cl_date2'])  )
			{
			
			$cond11="DATE(Payable_invoice.close_date) BETWEEN '".$data['cl_date1']."' AND '".$data['cl_date2']."'";
			$this->db->where($cond11);
				}	
				
		
			}
			
			$this->db->where('Payable_invoice.super_id', $this->session->userdata('user_details')['super_id']);
		 $this->db->select("*,Payable_invoice.id as pid,customer.name,customer.uniqueid,customer.company");
         $this->db->from('Payable_invoice_fm as Payable_invoice');
         $this->db->join('customer', 'customer.id = Payable_invoice.cust_id AND Payable_invoice.awb_no!="" ');
		$this->db->where('Payable_invoice.deleted','N');
		$this->db->group_by("Payable_invoice.invoice_no");
		$this->db->order_by('Payable_invoice.id','DESC'); 
			
		 $this->db->limit($limit, $start); 
		
		
         $query = $this->db->get();
       //echo $this->db->last_query(); die; 
		  
		if($query->num_rows()>0)
		{
			$data['result']=$query->result_array();
            $data['count']=$this->getviewPayableInvoiceCount($data);  
			return $data;
		}
	}
	
	public function getviewPayableInvoiceCount($data=array())
	{
		$this->db->where('Payable_invoice.super_id', $this->session->userdata('user_details')['super_id']);
			$this->db->where('deleted', 'N');
			$this->db->select('COUNT(id) as sh_count');  
			 $this->db->from('Payable_invoice');
			 $this->db->group_by("Payable_invoice.invoice_no");
			  $query = $this->db->get();
				if($query->num_rows()>0){
				$data= $query->result_array();   
				return $data[0]['sh_count'];     
				}
				return 0; 
	}
    public function calculateShipCharge($cust_id=null)
    {

        $this->db->select('city_id,price,flat_price,max_weight,cc_id,city_id');
        $this->db->from('zone_list_customer_fm');
       // $this->db->where('cc_id',$filtr['seller_id']);
        $this->db->where('cust_id',$cust_id);
        $query = $this->db->get();
        if( $query ->num_rows()>0)
        {
            return $query->result_array();
        }
        else
        {
            $this->db->select('city_id,price,flat_price,max_weight,cc_id,city_id');
            $this->db->from('zone_list_fm');
            //$this->db->where('cc_id',$filtr['seller_id']);
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
           
            $query = $this->db->get();
            return $query->result_array();
        }
        
       
            

    }

    public function checkInvoiceExist($shipment = array()) {
       
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('Payable_invoice_fm');

        if(!empty($shipment))
        $this->db->where_in('awb_no',$shipment);

          

        $query = $this->db->get();
       // echo  $this->db->last_query(); 
        return $query->result_array();
    }

    


}
