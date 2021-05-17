<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function add_warehouse($data) {


        $this->db->trans_start();
        $this->db->insert('warehouse_category', $data);
        //echo $this->db->last_query();
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $insert_id = $this->db->insert_id();
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



    public function fetch_all_cities() {

    $citylist = Array('Riyadh','Jeddah','Dammam');
        $this->db->select('id,city');
        $this->db->where('city!=', '');
        $this->db->where_in('city', $citylist);
        $this->db->where('deleted', 'N');
       $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('country');
       // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function edit_msg_temp($id) {
        $this->db->select('*');
        $this->db->from('warehouse_category');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id=', $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            return $data;
        }
        return array();
    }

    public function all() {
        $this->db->order_by('id', 'desc');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('warehouse_category');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function count() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->count_all("zone_list_fm");
    }

    public function edit_view($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('zone_list_fm');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function edit_view_customerdata($id) {
        $this->db->where('id', $id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('warehouse_category');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function edit_custimer($id, $data) {

        $this->db->where('id', $id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('warehouse_category', $data);
    }

    public function find($id) {
        $this->db->where('id', $id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        // $this->db->get_where('seller_m',array('id'=>$id));
        $query = $this->db->get('zone_list_fm');
        if ($query->num_rows() == 1) {
            return $query->row();
        }
    }

    public function Zone() {
        $this->db->where('id', 0);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function customer($seller_id, $customer_id) {
        $data = array(
            'id' => $seller_id
        );
$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $customer_id);
        return $this->db->update('zone_list_fm', $data);
    }

    public function update_seller_id($seller_id, $customer_id) {
        $data = array(
            'zone_list' => $customer_id
        );
$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $seller_id);
        return $this->db->update('zone_list_fm', $data);
    }

    public function find_customer($id) {
        $this->db->where('id', $id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function find_customer_sellerm($id) {
        $this->db->where('id', $id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find1() {
$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find2() {
        $this->db->where('id!=', 0);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('zone_list_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

}
