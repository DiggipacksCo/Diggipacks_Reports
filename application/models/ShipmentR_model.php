<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShipmentR_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('ItemInventory_model');
        $this->load->model('Item_model');
        $this->load->model('Cartoon_model');
        $this->load->model('Seller_model');
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

     public function GetshipmentDataQuery($awb=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('reverse_awb', '');
        $this->db->where('reverse_type', 0);
        $this->db->where('delivered', 7);
        $this->db->where('code', 'POD');
        $this->db->where('slip_no', $awb);
        $this->db->select('*')->from('shipment_fm');
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row_array();
    }
    
     public function GetshipmentdiamentionDataQuery($awb=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('slip_no', $awb);
        $this->db->select('`sku`, `description`, `deducted_shelve`, `booking_id`, `slip_no`, `cod`, `piece`, `length`, `width`, `height`, `wieght`, `deleted`, `wh_id`, `super_id`, `cust_id`, `entry_date`, `free_sku`, `zid_pid`')->from('diamention_fm');
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->result_array();
    }
    
     public function insertshipment_fm($data=array()) {
       $this->db->insert_batch('shipment_fm',$data); 
      // echo $this->db->last_query(); die;
    }
    public function insertdiamention_fm($data=array()) {
       $this->db->insert_batch('diamention_fm',$data); 
    }
    public function insertstatus_fm($data=array()) {
       $this->db->insert_batch('status_fm',$data); 
    }
    
    public function Updateshipment_fm($data=array()) {
       $this->db->update_batch('shipment_fm',$data,'slip_no'); 
    }
    public function filter($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $limit = null, $refsno = null, $mobileno = null, $wh_id = null, $data = array(), $cc_id = null) {
        if (!empty($data['sort_limit'])) {
            $LimitArr = explode('-', $data['sort_limit']);
            $limit = $LimitArr[1];
            // $start=$LimitArr[0];
        } else {
            $page_no;
            $limit = 100;
            if (empty($page_no)) {
                $start = 0;
            } else {
                $start = ($page_no - 1) * $limit;
            }
        }
        /* if(!empty($delivered)){
          $this->db->where('shipment_fm.delivered', $delivered);
          } */

        if ($data['sort_list'] == 'NO') {
            $this->db->order_by('shipment_fm.id', 'desc');
        } else if ($data['sort_list'] == 'OLD') {
            $this->db->order_by('shipment_fm.id', 'asc');
        } else if ($data['sort_list'] == 'OBD') {
            $this->db->order_by('shipment_fm.entrydate');
        } else {
            $this->db->order_by('shipment_fm.id', 'desc');
        }

        $fulfillment = 'Y';
        $deleted = 'N';

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('shipment_fm.id,shipment_fm.product_invoice,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,shipment_fm.frwd_company_id,shipment_fm.wh_id,shipment_fm.frwd_company_label,shipment_fm.frwd_date,shipment_fm.is_menifest,shipment_fm.code,diamention_fm.free_sku,shipment_fm.total_cod_amt,shipment_fm.no_of_attempt,shipment_fm.3pl_pickup_date,shipment_fm.3pl_close_date, IFNULL(DATEDIFF(3pl_close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP(), 3pl_pickup_date) ) AS transaction_days ,shipment_fm.delivered,shipment_fm.close_date');


        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        $this->db->where('diamention_fm.deleted', 'N');

        $this->db->where('shipment_fm.backorder', 0);
        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }

        $cc_id = $data['cc_id'];

        if (!empty($cc_id)) {
            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }
        //$this->db->group_by('diamention_fm.slip_no');

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        // echo $delivered;
    
                $this->db->where_in('shipment_fm.delivered', 23);
        
                

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }


        if (!empty($refsno)) {
            $this->db->where('shipment.booking_id', $refsno)
                    ->or_where('shipment_fm.frwd_company_awb', $refsno);
        }



        if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
        if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }

        if (!empty($sku)) {

            $this->db->where('diamention_fm.sku', $sku);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }
        //echo $this->db->last_query(); die;

        if (!empty($seller)) {
            if (sizeof($seller) > 0) {
                $seller = array_filter($seller);
                $this->db->where_in('shipment_fm.cust_id', $seller);
            }
        }



        // $this->db->order_by('shipment_fm.id', 'asc');

        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;                      

        if ($query->num_rows() > 0) {


            //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount_reverse($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $refsno, $mobileno, $wh_id);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    
     public function shipmCount_reverse($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $refsno, $mobileno, $wh_id) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        // $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        //  $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        // $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        // $this->db->where('diamention_fm.deleted', 'N');
        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }


        if ($backorder == 'back')
            $this->db->where('shipment_fm.backorder', 1);
        else {


            $this->db->where('shipment_fm.backorder', 0);
            // $this->db->where('shipment.reverse_pickup', 0);
        }



        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



       $this->db->where_in('shipment_fm.delivered', 23);

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        if (!empty($refsno)) {
            $this->db->where('shipment_fm.booking_id', $refsno);
        }
        if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
        if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }


        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment_fm.cust_id', $seller);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }




        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

}
