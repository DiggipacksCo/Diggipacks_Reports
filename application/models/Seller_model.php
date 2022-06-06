<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Seller_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function add($data) {
        $this->db->insert('customer', $data);
        return $this->db->insert_id();
    }

    public function add_customer($data) {


        $this->db->trans_start();
        $this->db->insert('customer', $data);
        //echo $this->db->last_query(); die;
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    // public function all($limit , $start){
    // 	$this->db->limit($limit, $start);
    // 	$query = $this->db->get('customer');
    // 	if($query->num_rows()>0){
    // 			// return $query->result();
    // 		foreach ($query->result() as $row) {
    // 			$data[] = $row;
    // 		}
    // 		return $data;
    // 	}
    // }

    public function fetch_all_cities() {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,city');
        $this->db->where('deleted', 'N');
        $this->db->where("city!=''");
        $query = $this->db->get('country');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
    

    public function all() {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('access_fm', 'Y');
        $this->db->order_by('id', 'desc');

        $query = $this->db->get('customer');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function count() {
        $conditions = array(
            'super_id' => $this->session->userdata('user_details')['super_id'],
            'access_fm'=>'Y',
        );
        return $this->db->where($conditions)->from('customer')->count_all_results();
    }

    public function edit_view($id) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('customer');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function edit_view_customerdata($id) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('customer');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }


    public function edit($id, $data) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);

        //echo $this->db->last_query(); die;
        return $this->db->update('customer', $data);
    }

    public function edit_custimer($id, $data) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        //print_r($data);exit;
        $this->db->where('id', $id);
        return $this->db->update('customer', $data);
    }

    public function find($id) {
        $this->db->where('access_fm', 'Y');
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        // $this->db->get_where('seller_m',array('id'=>$id));
        $query = $this->db->get('customer');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function customers() {
        $this->db->where('access_fm', 'Y');
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('seller_id', 0);
        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function customer($seller_id, $customer_id) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $data = array(
            'seller_id' => $seller_id
        );

        $this->db->where('id', $customer_id);
        return $this->db->update('customer', $data);
    }

    public function update_seller_id($seller_id, $customer_id) {
        $data = array(
            'customer' => $customer_id
        );

        $this->db->where('id', $seller_id);
        return $this->db->update('customer', $data);
    }

    public function find_customer($id) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function find_customer_sellerm($id) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('customer');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find1() {

        $this->db->where('access_fm', 'Y');
        // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find2() {
        $this->db->where('access_fm', 'Y');
        // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        //$this->db->where('seller_id!=',0);
        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function zidproduct($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('customer');
        //echo $this->db->last_query(); die;
        $row = $query->row_array();
        return $row['zid_sid'];
    }



    public function update_zid($id, $data) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        return $this->db->update('customer', $data);
    }

    public function update_salla($id, $data) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        //print_r($data);exit;
        $this->db->where('id', $id);
        
        return $this->db->update('customer', $data);
        //echo $this->db->last_query(); die;
        //return $abd ;  
    }

    public function zidCities() {
        $this->db->select('id,zid');
        $this->db->from('country');
        $this->db->where('deleted', 'N');
        $this->db->where('zid !=', '');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function deleteDeliveryOption($id) { 
        /* check if already exist then update */
        $this -> db -> where('id', $id);
        $this -> db -> delete('zid_deliver_options');
        //echo $this->db->last_query(); die;
    }


    public function DeliveryOptionUpdate($id,$status) { 
        /* check if already exist then update */
        
    
            $this->db->where('id',$id);
           
            $this->db->update('zid_deliver_options', array('subscribed'=>$status));
     
        //echo $this->db->last_query(); die;
    }
    public function zidDeliveryOptionUpdate($data) { 
        /* check if already exist then update */
        
       
            $this->db->insert('zid_deliver_options', $data);
        
        //echo $this->db->last_query(); die;
    }

    public function deliverOptionExist($cust_id=null,$zid_delivery_name=null) {
        $this->db->select('*');
        $this->db->from('zid_deliver_options');
        $this->db->where('cust_id',$cust_id);
        $this->db->where('zid_delivery_name', $zid_delivery_name);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function deliverOptions($cust_id=null) {
        $this->db->select('*');
        $this->db->from('zid_deliver_options');
        $this->db->where('cust_id',$cust_id);
       
        $query = $this->db->get();
        return $query->result_array();
    }
    public function deliverOptionsByid($id=null) {
        $this->db->select('*');
        $this->db->from('zid_deliver_options');
        $this->db->where('id',$id);
       
        $query = $this->db->get();
        return $query->row_array();
    }
    
     public function update_shopify($id, $data) {
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        return $this->db->update('customer', $data);
         
    }
    
    public function update_Woocommerce($id, $data) {
        
      //  $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $this->db->update('customer', $data);
        //echo $this->db->last_query();die;
    }
    


}
