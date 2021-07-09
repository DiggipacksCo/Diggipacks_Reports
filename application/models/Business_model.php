<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Business_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function generatescanreport($data) {


        $query1 = $this->db->insert_batch('package_report', $data);
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

    public function packOrder($updateArray) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update_batch('pickuplist_tbl', $updateArray, 'slip_no');
        return $this->db->last_query();
    }
    
     public function UpdateDiamation($data=array(),$data_w=array()) {

         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
         $this->db->where($data_w);
         $this->db->update('diamention_fm', $data);
         return $this->db->last_query();
    }

    
    public function GetdiamantionData($slip_no=null)
    {
        $query=$this->db->query("SELECT sum(piece) as piece FROM `diamention_fm` WHERE slip_no='$slip_no'");
        $result=$query->row_array();
        return $result['piece'];
    }
    public function packOrderNew($updateArray) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update_batch('shipment_fm', $updateArray, 'slip_no');
        return $this->db->last_query();
    }

    public function GetallDatapickingChargeAdded($data) {


        $query1 = $this->db->insert_batch('orderinvoicepicking', $data);
        return $this->db->last_query();
    }
    
     public function InsertStatusPieceData($data=array()) {


       $this->db->insert_batch('status_fm', $data);
        return $this->db->last_query();
    }

    public function pickListFilterNotPicked($awb = null) {

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('pickuplist_tbl.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('pickuplist_tbl.id, pickuplist_tbl.`pickupId`, pickuplist_tbl.`assigned_to`, pickuplist_tbl.`slip_no`, pickuplist_tbl.`origin`, pickuplist_tbl.`destination`, pickuplist_tbl.`reciever_name`, pickuplist_tbl.`reciever_address`, pickuplist_tbl.`reciever_phone`, pickuplist_tbl.`sku`, pickuplist_tbl.`pickup_status`, pickuplist_tbl.`piece`, pickuplist_tbl.`entrydate`, pickuplist_tbl.`pickupDate`,pickuplist_tbl.sender_name,pickuplist_tbl.print_url,pickuplist_tbl.weight');
        $this->db->from('pickuplist_tbl');
        $this->db->join('shipment_fm', 'shipment_fm.slip_no=pickuplist_tbl.slip_no','left');

        $this->db->where('pickuplist_tbl.slip_no', $awb);

        $this->db->where('pickuplist_tbl.deleted', 'N');
        $this->db->where('shipment_fm.order_type', 'B2B');


        $this->db->where("pickuplist_tbl.assigned_to>0");

        $this->db->where('pickuplist_tbl.pickup_status', 'N');

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

}
