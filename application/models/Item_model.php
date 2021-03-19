<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

    function __construct() {

        parent::__construct();
        $this->load->model('ItemCategory_model');
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function add($data) {

        $this->db->insert('items_m', $data);
        return $this->db->insert_id();
    }

    public function GetAllStorageTypes() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->order_by('storage_type', 'asc');
        $query = $this->db->get('storage_table');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function getcheckstorageid($name = null) {
        $this->db->where('storage_type', $name);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $query = $this->db->get('storage_table');
        // echo $this->db->last_query()."<br>";
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function add_bulk($data) {

        if ($this->db->insert_batch('items_m', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function all() {
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('items_m.`id`, items_m.`sku`, items_m.`name`, items_m.`description`,items_m.`type`,  items_m.`sku_size`,items_m.`wh_id`,`storage_table`.`storage_type`,items_m.`item_path`');
        $this->db->from('items_m');
        $this->db->join('storage_table', 'items_m.storage_id = storage_table.id');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function filter(array $data) {
        $limit = 100;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('items_m.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        if (!empty($data['name']))
            $this->db->where('items_m.name', $data['name']);
            if (!empty($data['wh_id']))
            $this->db->where('items_m.wh_id', $data['wh_id']);

            if (!empty($data['storage_id']))
            $this->db->where('items_m.storage_id', $data['storage_id']);

            if (!empty($data['description']))
            $this->db->where('items_m.description', $data['description']);
            
            
             if (!empty($data['from']) && !empty($data['to'])) {
               $where = "DATE(items_m.entry_date) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";


               $this->db->where($where);
        }
            
            
        if (!empty($data['sku']))
            $this->db->where('items_m.sku', $data['sku']);
        if (!empty($data['sku_size']))
            $this->db->where('items_m.sku_size', $data['sku_size']);
        $this->db->select('items_m.`id`, items_m.`sku`, items_m.`name`, items_m.`description`,items_m.`type`,items_m.`wh_id`,  items_m.`sku_size`,items_m.`item_path`,items_m.`less_qty`,items_m.`alert_day`,items_m.`color`,items_m.`length`,items_m.`width`,items_m.`height`,items_m.`expire_block`,storage_id,entry_date');
        $this->db->from('items_m');
      //  $this->db->join('storage_table', 'items_m.storage_id = storage_table.id');
        $this->db->order_by('items_m.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        
      

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount($data);
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount($data=array()) {
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('items_m.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        
         if (!empty($data['name']))
            $this->db->where('items_m.name', $data['name']);
            if (!empty($data['wh_id']))
            $this->db->where('items_m.wh_id', $data['wh_id']);

            if (!empty($data['storage_id']))
            $this->db->where('items_m.storage_id', $data['storage_id']);

            if (!empty($data['description']))
            $this->db->where('items_m.description', $data['description']);
            
            
             if (!empty($data['from']) && !empty($data['to'])) {
               $where = "DATE(items_m.entry_date) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";


               $this->db->where($where);
        }
            
            
        if (!empty($data['sku']))
            $this->db->where('items_m.sku', $data['sku']);
        if (!empty($data['sku_size']))
            $this->db->where('items_m.sku_size', $data['sku_size']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(items_m.id) as sh_count');

        $this->db->from('items_m');
      //  $this->db->join('storage_table', 'items_m.storage_id = storage_table.id');
        $this->db->order_by('items_m.id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
        }
        return 0;
    }

    public function count() {
        
       // $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
         $conditions = array(
          
            'super_id' => $this->session->userdata('user_details')['super_id'],
        );
        return $this->db->where($conditions)->from('items_m')->count_all_results();
         echo $this->db->last_query();
    }

    public function edit_view($id) {

        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('items_m');
        // $category = $this->ItemCategory_model->category($query->row()->item_category);
        // $sub_category=$this->ItemCategory_model->category($query->row()->item_subcategory);
        // $data2 =array(
        // 	'category_id'=>$query->row()->item_category,
        // 	'sub_category_id'=>$query->row()->item_subcategory
        // );
        // $attributes=$this->Attribute_model->findAttributes2($data2);
        if ($query->num_rows() > 0) {
            $data['item'] = $query->row();
            // $data['category'] = $category;
            // $data['sub_category'] = $sub_category;
            // $data['attributes']=$attributes;
            return $data;
        }
    }

    public function edit($id, $data) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        return $this->db->update('items_m', $data);
    }

    public function editAttributeValues($data, $check) {
        $this->db->where($check);
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('items_m', $data);
    }

    public function find($id) {
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('items_m');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function findBySku($sku) {
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('sku', $sku);
        $query = $this->db->get('items_m');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function GetchekskuDuplicate($sku = null) {
        $this->db->where('sku', $sku);
        $this->db->select('sku');
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('items_m');
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function GetchekskuDuplicate_new($sku = null) { 
        $this->db->where('sku', $sku);
        $this->db->select('sku');
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('items_m');
        if ($query->num_rows() > 0) {
            
            return false;
        }
        else
        {
            return true;
        }
    }

    public function Getallskubarcodeadddata($data = array()) {
        return $this->db->insert('skubarcode_print', $data);
    }

    //public function findItemsByShipment($slip_no){
    // $this->db->where('slip_no' , $slip_no);
    // $query = $this->db->get('diamention_fm');
    // if($query->num_rows()>0 ){
    // 	return $query->result();
    // }
    //}
    // public function find_by_item_sku($id){
    // 	$this->db->where('id' , $id);	
    // 	$query = $this->db->get('items_m');
    // 	if($query->num_rows()>0 ){
    // 		echo json_encode($query->result());
    // 	}
    // }
}
