<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shelve_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function insertStockLocation(array $data) {

        // return $data; exit;
        $query1 = $this->db->insert_batch('stockLocation', $data);
        return $this->db->last_query();
        exit;
    }

    public function inserttodsLocation(array $data) {

        // return $data; exit;
        $query1 = $this->db->insert_batch('tods_tbl', $data);
        return $this->db->last_query();
    }

    public function stockLocationFilter($stock_location, $seller_id, $page_no, $data = array()) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        $this->db->where('stockLocation.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('stockLocation.seller_id', $seller_id);
        if ($data['type'] == 'AS') {
            $this->db->where('`stock_location`  IN (SELECT `stock_location` FROM `item_inventory`)', NULL, FALSE);
        }
        if ($data['type'] == 'UN') {
            $this->db->where('`stock_location` NOT IN (SELECT `stock_location` FROM `item_inventory`)', NULL, FALSE);
        }
        $this->db->select('`stockLocation`.`stock_location`,seller_m.name');
        $this->db->from('stockLocation');
        $this->db->join('customer as seller_m', 'seller_m.id = stockLocation.seller_id');
        // if(!empty($seller_id))
        // {
        // $seller_id= array_filter($seller_id);
        // }

        if (!empty($stock_location)) {
            $this->db->where('stockLocation.stock_location', $stock_location);
        }



        $this->db->order_by('stockLocation.id', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->stockLocationFilterCount($stock_location, $seller_id, $page_no, $data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function todsfiltershowQry($data = array()) {

        $limit = 100;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('tods_tbl.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('tods_tbl');
        $this->db->order_by('tods_tbl.id', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
       
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->todsCount($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function todsCount($data = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('tods_tbl');
        $this->db->order_by('tods_tbl.id', 'ASC');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->num_rows();
            return $data;
        }
        return 0;
    }

    public function stockLocationFilterCount($stock_location, $seller_id, $page_no, $data = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('stockLocation');

        if ($data['type'] == 'AS') {
            $this->db->where('`stock_location`  IN (SELECT `stock_location` FROM `item_inventory`)', NULL, FALSE);
        }
        if ($data['type'] == 'UN') {
            $this->db->where('`stock_location` NOT IN (SELECT `stock_location` FROM `item_inventory`)', NULL, FALSE);
        }
        if (!empty($seller_id)) {
            $seller_id = array_filter($seller_id);

            $this->db->where_in('seller_id', $seller_id);
        }

        if (!empty($stock_location)) {
            $this->db->where('stock_location', $stock_location);
        }

        //$this->db->group_by('pickupId');
        $this->db->order_by('stockLocation.id', 'ASC');


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function selveFilter($city, $shelve, $page_no, $querycheck = false) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); // return "ssssssss"; die;
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        $this->db->select('*');

        $this->db->from('warehous_shelve_no_fm');
        //$this->db->where_not_in('shelv_no','(select shelve_no from item_inventory)');


        if (!empty($city)) {
            $city = array_filter($city);

            $this->db->where_in('city_id', $city);
        }

        if (!empty($shelve)) {
            $this->db->where('shelv_no', $shelve);
        }


        $this->db->order_by('warehous_shelve_no_fm.id', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();


        //return $this->db->last_query();exit;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shelveFilterCount($city, $shelve, $page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function shelveFilterCount($city, $shelve, $page_no) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('warehous_shelve_no_fm');
        if (!empty($city)) {
            $city = array_filter($city);

            $this->db->where_in('city_id', $city);
        }
        if (!empty($shelve)) {
            $this->db->where('shelv_no', $shelve);
        }
        //$this->db->group_by('pickupId');
        $this->db->order_by('warehous_shelve_no_fm.id', 'ASC');


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function add_bulk_shelve_data($data) {

        if ($this->db->insert_batch('warehous_shelve_no_fm', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkShelve($id = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($id != null)
            $this->db->where('shelv_no', $id);
        $query = $this->db->get('warehous_shelve_no_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function GetcheckshelaveUse($shelv_no = null) {
        $query = $this->db->select('seller_id')->from('item_inventory')->where('super_id', $this->session->userdata('user_details')['super_id'])->where('shelve_no', $shelv_no)->where("quantity>0")->limit(1)->get();
        // echo $this->db->last_query();
        return $query->row_array()['seller_id'];
    }

    public function chekinventoryshalveno($stocklocation = null) {
        $query = $this->db->select('shelve_no,seller_id')->from('item_inventory')->where('stock_location', $stocklocation)->where('super_id', $this->session->userdata('user_details')['super_id'])->where("quantity>0")->get();
        // echo $this->db->last_query(); die;
        return $query->row_array();
    }

}
