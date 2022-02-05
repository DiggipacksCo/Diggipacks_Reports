<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReturnShipment_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function GetCheckReturnFulfilstatus($awb =null) {
        if(!empty($awb))
        {
        $this->db->select('shipment_fm.id as sh_id,items_m.id as sku_id,items_m.sku_size as capacity,items_m.storage_id,shipment_fm.slip_no as awb_no,shipment_fm.code,shipment_fm.delivered,shipment_fm.cust_id,diamention_fm.*');
        $this->db->from('shipment_fm');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no=shipment_fm.slip_no');
        $this->db->join('items_m', 'diamention_fm.sku=items_m.sku');

        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        // $this->db->where_in('delivered', array('5', '7'));
         $this->db->where("(shipment_fm.slip_no='$awb' or frwd_company_awb='$awb')");
       // $this->db->where('shipment_fm.slip_no', $awb);
       // $this->db->where_in('shipment_fm.code', array('DL'));
        $this->db->order_by('shipment_fm.slip_no', 'ASC');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
       // echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            return   $query->result_array();
        } else {
          
            return array();
        }
        }
    }

    public function getalldataitemtablesSKU($sku = null) {

        $sql = "SELECT * FROM items_m where sku='$sku' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

    
    public function add_inventory($data=array(), $data_h=array()) {
       if ($this->db->insert_batch('item_inventory', $data)) {
          // echo $this->db->last_query(); die;
                $this->db->insert_batch('inventory_activity', $data_h);
            }
        
    }
    
    
    public function updateStatus($data=array())
    {
         $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
         $this->db->update_batch('shipment_fm',$data,'slip_no');
        // echo $this->db->last_query();
    }

}
