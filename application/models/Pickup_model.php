<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pickup_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function get_deducted_shelve_no($slip_no) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('deducted_shelve,sku');
        $this->db->from('diamention_fm');
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get();
        return $query->result();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function pickListFilterShip_bulk($slip_nos = array()) {
        // print_r($slip_nos);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where_in('slip_no', $slip_nos);
        $this->db->order_by('shipment_fm.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query(); exit;
        if ($query->num_rows() > 0) {

            $data = $query->result_array();

            return $data;
        } else {
            $data = array();

            return $data;
        }
    }

    public function GetskuDetailsRTF($slip_no) {

        
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('diamention_fm.sku,diamention_fm.piece,diamention_fm.cod,items_m.item_path');
        $this->db->from('diamention_fm');
        $this->db->join('items_m','items_m.sku = diamention_fm.sku');
        $this->db->where('diamention_fm.slip_no', $slip_no);
        $query = $this->db->get();
      /// echo $this->db->last_query(); die;
        return $query->result();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function addInventoryHistory($data = array()) {

        return $this->db->insert('inventory_activity', $data);
        // echo $this->db->last_query(); die;
    }

    public function packOrder($updateArray) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update_batch('pickuplist_tbl', $updateArray, 'slip_no');
        return $this->db->last_query();
    }

    public function packOrderNew($updateArray) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update_batch('shipment_fm', $updateArray, 'slip_no');
        return $this->db->last_query();
    }

    public function assignPicker($data) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where($data['where']);
        $query2 = $this->db->update('pickuplist_tbl', $data['update']);
        return $this->db->last_query();
    }

    public function generatePicup($data) {


        $query1 = $this->db->insert_batch('pickuplist_tbl', $data);
        return $this->db->last_query();
    }

    public function GetallDatapickingChargeAdded($data) {


        $query1 = $this->db->insert_batch('orderinvoicepicking', $data);
        return $this->db->last_query();
    }

    public function GetalloutboundDataAdded($data) {


        $query1 = $this->db->insert_batch('orderoutboundinvoice', $data);
        return $this->db->last_query();
    }

    public function GetallskuDataDetails($slip_no) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('pieces,slip_no,cust_id');
        $this->db->from('shipment_fm');
        //$this->db->join('items_m','items_m.sku = diamention_fm.sku');
        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get();
        return $data = $query->row_array();


        //$this->db->order_by('shipment_fm.id','ASC');
    }

    public function GetallcheckFirstEntry($sid = null) {
        $current = date('Y-m-d');

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('orderoutboundinvoice');
        //$this->db->join('items_m','items_m.sku = diamention_fm.sku');
        $this->db->where('seller_id', $sid);
        $this->db->where('DATE(entrydate)', $current);

        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->num_rows();




        //$this->db->order_by('shipment_fm.id','ASC');
    }

    public function fetch_all_seller() {
        $this->db->select('id,name');

        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function pickListFilterShip($pickupId) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');

        $this->db->where('slip_no IN (select slip_no from pickuplist_tbl where pickupId="' . $pickupId . '")');

        $this->db->order_by('shipment_fm.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        //return $page_no.$this->db->last_query(); exit;
        if ($query->num_rows() > 0) {

            $data = $query->result_array();

            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetCheckReturnFulfilstatus($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId) {
        $this->db->select('*');
        $this->db->from('shipment_fm');
        if (!empty($awb)) {
            $this->db->where('slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('sku', $sku);
        }
        
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('delivered', array('5', '7'));
        $this->db->where_in('code', array('POD', 'DL'));
        $this->db->order_by('slip_no', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        // $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = 1;
            return $data;
            //  $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
    public function GetCheckCancelOrderQry($awb=null) {
        $this->db->select('*');
        $this->db->from('shipment_fm');       
        $this->db->where('slip_no', $awb);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('delivered', array('11'));
        $this->db->where_in('code', array('OG'));

      
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        // $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = 1;
            return $data;
            //  $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function pickListFilterAll($pickupId) {

        $this->db->select('*');
        $this->db->from('pickuplist_tbl');
        if (!empty($awb)) {
            $this->db->where('slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('sku', $sku);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('pickupId', $pickupId);
        $this->db->order_by('pickuplist_tbl.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $data = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function pickListFilterAll_awb($pickupId) {

        $this->db->select('pickuplist_tbl.*,shipment_fm.destination as dest_cty,shipment_fm.entrydate as ship_entrydate,shipment_fm.cust_id,shipment_fm.status_describtion,shipment_fm.reciever_phone');
        $this->db->from('pickuplist_tbl');
        $this->db->join('shipment_fm', 'shipment_fm.slip_no=pickuplist_tbl.slip_no');
        $this->db->where('pickuplist_tbl.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('pickuplist_tbl.deleted', 'N');
        $this->db->where('pickuplist_tbl.pickupId', $pickupId);
        $this->db->group_by('pickuplist_tbl.slip_no');
        $this->db->order_by('pickuplist_tbl.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $data = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function pickListFilterAll_awb_track($pickupId) {

        $this->db->select('pickuplist_tbl.*,shipment_fm.destination as dest_cty,shipment_fm.entrydate as ship_entrydate,shipment_fm.cust_id,shipment_fm.status_describtion,shipment_fm.reciever_phone');
        $this->db->from('pickuplist_tbl');
        $this->db->join('shipment_fm', 'shipment_fm.slip_no=pickuplist_tbl.slip_no');
        $this->db->where('pickuplist_tbl.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('pickuplist_tbl.deleted', 'N');
        $this->db->where('shipment_fm.slip_no', $pickupId);
        $this->db->group_by('pickuplist_tbl.slip_no');
        $this->db->order_by('pickuplist_tbl.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $data = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function pickListFilterNotPicked($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('`id`, `pickupId`, `assigned_to`, `slip_no`, `origin`, `destination`, `reciever_name`, `reciever_address`, `reciever_phone`, `sku`, `pickup_status`, `piece`, `entrydate`, `pickupDate`,sender_name,print_url');
        $this->db->from('pickuplist_tbl');
        if (!empty($awb)) {
            $this->db->where('slip_no', $awb);
        }
        $this->db->where('deleted', 'N');
        if (!empty($sku)) {
            $this->db->where('sku', $sku);
        }
        if (!empty($pickupId)) {
            $this->db->where('pickupId', $pickupId);
        }
        $this->db->where("assigned_to>0");

        $this->db->where('pickup_status', 'N');

        $this->db->order_by('pickuplist_tbl.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        //  echo $this->db->last_query();exit;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = 1;
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    
     public function pickListFilterNotPicked_tod($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('`id`, `pickupId`, `assigned_to`, `slip_no`, `origin`, `destination`, `reciever_name`, `reciever_address`, `reciever_phone`, `sku`, `pickup_status`, `piece`, `entrydate`, `pickupDate`,sender_name,print_url,tem_uid,tem_tod,tods_barcode');
        $this->db->from('pickuplist_tbl');
        if (!empty($awb)) {
            $this->db->where('tods_barcode', $awb);
        }
        $this->db->where('deleted', 'N');
        if (!empty($sku)) {
            $this->db->where('sku', $sku);
        }
        if (!empty($pickupId)) {
            $this->db->where('pickupId', $pickupId);
        }
        $this->db->where("assigned_to>0");

        $this->db->where('pickup_status', 'N');

        $this->db->order_by('pickuplist_tbl.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        //  echo $this->db->last_query();exit;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = 1;
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    public function pickListFilter($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId, $data = array()) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }


        if (!empty($data['slip_no'])) {
            $this->db->where('slip_no', $data['slip_no']);
        }

        if (!empty($data['pickup_status'])) {
            $this->db->where('pickup_status', $data['pickup_status']);
        }
        if (!empty($data['picked_status'])) {
            $this->db->where('picked_status', $data['picked_status']);
        }
        if (!empty($data['wh_id'])) {
            $this->db->where('wh_id', $data['wh_id']);
        }
        if (!empty($data['assigned_to'])) {
            $this->db->where('assigned_to', $data['assigned_to']);
        }
        if (!empty($data['sender_name'])) {
            $this->db->where('sender_name', $data['sender_name']);
        }

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('pickuplist_tbl');

        $this->db->where('deleted', 'N');


        if (!empty($sku)) {
            $this->db->where('sku', $sku);
        }
        if (!empty($pickupId)) {
            $this->db->where('pickupId', $pickupId);
        }
        $this->db->order_by('pickuplist_tbl.id', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //return $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->pickuplistCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId, $data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function pickuplistCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId, $data = array()) {

        $this->db->select('id');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('pickuplist_tbl');
        $this->db->where('pickupId', $pickupId);
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('deleted', 'N');
        if (!empty($from) && !empty($to)) {
            $where = "DATE(entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }

        if (!empty($data['slip_no'])) {
            $this->db->where('slip_no', $data['slip_no']);
        }

        if (!empty($data['pickup_status'])) {
            $this->db->where('pickup_status', $data['pickup_status']);
        }
        if (!empty($data['picked_status'])) {
            $this->db->where('picked_status', $data['picked_status']);
        }
        if (!empty($data['wh_id'])) {
            $this->db->where('wh_id', $data['wh_id']);
        }
        if (!empty($data['assigned_to'])) {
            $this->db->where('assigned_to', $data['assigned_to']);
        }
        if (!empty($data['sender_name'])) {
            $this->db->where('sender_name', $data['sender_name']);
        }
        //$this->db->group_by('pickupId');
        $this->db->order_by('pickuplist_tbl.id', 'ASC');


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function filter($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $slip_no, $data = array()) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }

        if (!empty($data['pickupId'])) {
            $this->db->where('pickupId', $data['pickupId']);
        }
        if (!empty($slip_no)) {
            $this->db->where('slip_no', $slip_no);
        }

        if (!empty($data['pickup_status'])) {
            $this->db->where('pickup_status', $data['pickup_status']);
        }
        if (!empty($data['picked_status'])) {
            $this->db->where('picked_status', $data['picked_status']);
        }
        if (!empty($data['wh_id'])) {
            $this->db->where('wh_id', $data['wh_id']);
        }
        if (!empty($data['assigned_to'])) {
            $this->db->where('assigned_to', $data['assigned_to']);
        }

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('deleted', 'N');
        $this->db->select('COUNT(id) as id_count,id,entrydate,pickupId,pickup_status,assigned_to,slip_no,wh_id,picked_status,pickedDate');
        $this->db->from('pickuplist_tbl');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] == 4)
            $this->db->where('assigned_to', $this->session->userdata('user_details')['user_id']);
        $this->db->group_by('pickupId');
        $this->db->order_by('pickuplist_tbl.id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();



        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->pickupCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $slip_no, $data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function pickupCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $slip_no, $data = array()) {


        if (!empty($from) && !empty($to)) {
            $where = "DATE(entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        if (!empty($data['pickupId'])) {
            $this->db->where('pickupId', $data['pickupId']);
        }
        if (!empty($slip_no)) {
            $this->db->where('slip_no', $slip_no);
        }

        if (!empty($data['pickup_status'])) {
            $this->db->where('pickup_status', $data['pickup_status']);
        }
        if (!empty($data['picked_status'])) {
            $this->db->where('picked_status', $data['picked_status']);
        }
        if (!empty($data['wh_id'])) {
            $this->db->where('wh_id', $data['wh_id']);
        }
        if (!empty($data['assigned_to'])) {
            $this->db->where('assigned_to', $data['assigned_to']);
        }

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');

        $this->db->select('id');
        $this->db->from('pickuplist_tbl');
        $this->db->group_by('pickupId');
        $this->db->order_by('pickuplist_tbl.id', 'ASC');


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function filterexcel1($filterArr = array()) {
        $page_no;
        $limit = 2000;
        $start = $filterArr['exportlimit'] - $limit;

        if (!empty($filterArr['from']) && !empty($filterArr['to'])) {
            $where = "DATE(entrydate) BETWEEN '" . $filterArr['from'] . "' AND '" . $filterArr['to'] . "'";


            $this->db->where($where);
        }

        if (!empty($filterArr['slip_no'])) {
            $this->db->where('slip_no', $filterArr['slip_no']);
        }
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->select('COUNT(id) as id_count,id,entrydate,pickupId,pickup_status,assigned_to,slip_no,packedBy,packDate');
        $this->db->from('pickuplist_tbl');
        if ($this->session->userdata('user_details')['user_type'] == 4)
            $this->db->where('assigned_to', $this->session->userdata('user_details')['user_id']);
        $this->db->group_by('pickupId');
        $this->db->order_by('pickuplist_tbl.id', 'desc');
        $this->db->limit($limit, $start);
        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();



        $query = $this->db->get();

        ///echo  $this->db->last_query(); die;   
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            return $data;
        }
    }

    public function PickedListSingleviewDataQry($data = array()) {

        if ($this->session->userdata('user_details')['user_type'] != '1')
        {
            $this->db->where_in('wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        if (!empty($data['from']) && !empty($data['to'])) {
            $where = "DATE(entrydate) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";


            $this->db->where($where);
        }

        if (!empty($data['pickupId'])) {
            $this->db->where('pickupId', $data['pickupId']);
        }
        if (!empty($data['slip_no'])) {
            $this->db->where('slip_no',$data['slip_no']);
        }

     
        if (!empty($data['assigned_to'])) {
            $this->db->where('assigned_to', $data['assigned_to']);
        }
        $this->db->select('*');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('pickuplist_tbl');
        $this->db->where('picked_status', $data['picked_status']);
        if ($this->session->userdata('user_details')['user_type'] == 4)
            $this->db->where('assigned_to', $this->session->userdata('user_details')['user_id']);
// $this->db->group_by('pickupId');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
       //  echo $this->db->last_query(); 

        return $data = $query->result_array();
    }

    public function pickListFilterNotPicked_single($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != '1')
            $this->db->where_in('wh_id', $this->session->userdata('user_details')['wh_id']);
        $this->db->select('`id`, `pickupId`, `assigned_to`, `slip_no`, `origin`, `destination`, `reciever_name`, `reciever_address`, `reciever_phone`, `sku`, `pickup_status`, `piece`, `entrydate`, `pickupDate`,sender_name');
        $this->db->from('pickuplist_tbl');
        if (!empty($awb)) {
            $this->db->where('slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('sku', $sku);
        }
        if (!empty($pickupId)) {
            $this->db->where('pickupId', $pickupId);
        }


        $this->db->where('picked_status', 'N');
//$this->db->where('picked_status','Y');

        $this->db->order_by('pickuplist_tbl.id', 'ASC');
// $this->db->limit($limit, $start);
        $query = $this->db->get();
//return $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = 1;
            return $data;
// return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function PickedListBatchviewDataQry($data = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != '1')
        {
            $this->db->where_in('wh_id', $this->session->userdata('user_details')['wh_id']);
        }
         if (!empty($data['from']) && !empty($data['to'])) {
            $where = "DATE(entrydate) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";


            $this->db->where($where);
        }

        if (!empty($data['pickupId'])) {
            $this->db->where('pickupId', $data['pickupId']);
        }
        if (!empty($data['slip_no'])) {
            $this->db->where('slip_no',$data['slip_no']);
        }

     
        if (!empty($data['assigned_to'])) {
            $this->db->where('assigned_to', $data['assigned_to']);
        }
        $this->db->select('*');
        $this->db->from('pickuplist_tbl');

        $this->db->where('pickup_status', 'N');
        $this->db->where('picked_status', 'N');
        if ($this->session->userdata('user_details')['user_type'] == 4)
            $this->db->where('assigned_to', $this->session->userdata('user_details')['user_id']);
        $this->db->group_by('pickupId');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();

        return $data = $query->result_array();
    }

    public function PickedListDataQry($data = array()) {
// if($this->session->userdata('user_details')['user_type']!='1')
// $this->db->where_in('wh_id', $this->session->userdata('user_details')['wh_id']); 
        $this->db->select('*');
        $this->db->from('pickuplist_tbl');
        $this->db->where('pickupId', $data['pickupId']);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] == 4)
            $this->db->where('assigned_to', $this->session->userdata('user_details')['user_id']);
        $this->db->where('picked_status', 'N');
// $this->db->group_by('pickupId');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();

        return $data = $query->result_array();
    }

    public function PickedOrderbatch($data = array(), $pickupId = null) {
        return $this->db->update('pickuplist_tbl', $data, array('pickupId' => $pickupId));
    }

    
    public function Get3pldispatchCheckData($data = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where("(booking_id='" . $data['slip_no'] . "' or frwd_company_awb='" . $data['slip_no'] . "')");
        $this->db->where('code', 'PK');
        $this->db->where('frwd_company_awb!=', '');
        $this->db->order_by('shipment_fm.id', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        // echo $this->db->last_query(); exit;
        if ($query->num_rows() > 0) {

            $data = $query->result_array();

            return $data;
        } else {
            $data = array();

            return $data;
        }
    }

    public function Update3PLOrder($updateArray = array(), $data = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update_batch('shipment_fm', $updateArray, 'slip_no');

        $this->db->insert_batch('status_fm', $data);

        // return $this->db->last_query();
    }

    public function generatescanreport($data) {


        $query1 = $this->db->insert_batch('package_report', $data);
        return $this->db->last_query();
    }

    public function GetallPackagingQuery($page_no, $slip_no = null, $otherData = array()) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        // $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('package_report');
        //$this->db->join('pickuplist_tbl','pickuplist_tbl.slip_no = package_report.slip_no');
        if (!empty($otherData['fromdate']) && !empty($otherData['todate'])) {
            $this->db->where('DATE(entrydate) BETWEEN "' . $otherData['fromdate'] . '" and "' . $otherData['todate'] . '"');
        }
        if (!empty($slip_no)) {
            $this->db->where('slip_no', $slip_no);
        }

        // $this->db->group_by('entrydate');    
        $tempdb = clone $this->db;
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->GetallPackagingQueryCount($page_no, $slip_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetallPackagingQueryCount($page_no, $slip_no = null) {
        //  $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('package_report');
        $this->db->where('slip_no', $slip_no);
        $this->db->group_by('entrydate');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }
    
     public function StaffpickingReportQry($PostData=array()) {
       
        if (!empty($PostData['from_date']) && !empty($PostData['to_date'])) {
            $where = "DATE(entrydate) BETWEEN '" . $PostData['from_date'] . "' AND '" .$PostData['to_date'] . "'";
            $this->db->where($where);
        }


         if(!empty($PostData['assigned_to']))
        {
         $this->db->where('assigned_to',$PostData['assigned_to']);
        }
       
        $this->db->where('assigned_to!=','');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('count(id) as total_orders,slip_no,sku,pickedDate,assigned_to,entrydate');
        $this->db->from('pickuplist_tbl');

        $this->db->where('deleted', 'N');
        $this->db->where('picked_status', 'Y');

        $this->db->group_by('DATE(pickedDate)');
        $this->db->order_by('pickuplist_tbl.id', 'ASC');
       
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
       return $query->result_array();
    }
    
       public function userDropval($type = NULL,$PostData=array()) {
        $this->db->where('system_access_fm', 'Y');
$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if (!empty($type)) {
            $this->db->where('user_type', $type);
        }
        if(!empty($PostData['assigned_to']))
        {
         $this->db->where('id',$PostData['assigned_to']);
        }
        $this->db->select('id,name,company');
        $this->db->where('is_deleted', '0');
         $this->db->where('deleted', 'N');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('user');
       // echo $this->db->last_query(); die;
       
            return $query->result_array();
       
    }
    
    public function StaffpickingReportQry_sku($assigned_to,$date) {
       
        
        $this->db->where('assigned_to',$assigned_to);
        $this->db->where('DATE(pickedDate)',$date);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('sku');
        $this->db->from('pickuplist_tbl');

        $this->db->where('deleted', 'N');
        $this->db->where('picked_status', 'Y');
        $this->db->order_by('pickuplist_tbl.id', 'ASC');
       
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
       return $query->result_array();
    }
    
    


}
