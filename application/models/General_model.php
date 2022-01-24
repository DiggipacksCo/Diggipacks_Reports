<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class General_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }


    public function smsUpdate($data = array(), $id = null) {
         $this->db->update('sms_setting', $data, array('id' => $id));

        return $this->db->last_query(); die; 
    }

    public function getEditSMS($id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('sms_setting');
        //$this->db->where('status', '1');
        $this->db->where('id', $id);


        $query = $this->db->get();
        // return $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function sms_detail($data = array()) {

        return $this->db->insert('sms_setting', $data);
        //echo $this->db->last_query(); // die;
    }

    public function GetsmsConfigrationDataQry() {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('sms_setting');
        $query = $this->db->get();
        return $query->row_array();
    }
    public function getSellerAddCourier() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->select('*');
        $this->db->from('courier_company');
        $query1 = $this->db->get();
        $data = $query1->result_array();

        if (!empty($data)) {
            $i = 0;
            foreach ($data as $cc_val) {
                //echo "<pre>";print_r($cc_val);
                $sel = $this->db->query("select * from  sellerDefaultCourier where cc_id = '" . $cc_val['id'] . "'");
                if ($sel->num_rows() > 0) {
                    
                } else {
                    $i++;
                    if ($cc_val['status'] == 'Y') {
                        $statusVal = '0';
                    } else {
                        $statusVal = '1';
                    }
                    $instertData = array(
                        'cc_id' => $cc_val['id'],
                        'priority' => $i,
                        'status' => $statusVal,
                        'super_id' => $this->session->userdata('user_details')['super_id']
                    );
                    $this->db->insert('sellerDefaultCourier', $instertData);
                }
            }
        }
        $getInt = $this->getSellerCourier();
        return $getInt;
    }

    public function updateCourier($dataupdate = array()) {

        $this->db->update_batch('sellerDefaultCourier', $dataupdate, 'id');
        echo $this->db->last_query();
        //die;
    }

    public function getSellerCourier() {

        $this->db->select('sellerDefaultCourier.*,courier_company.company');
        $this->db->from('sellerDefaultCourier');
        $this->db->join('courier_company', 'sellerDefaultCourier.cc_id = courier_company.id');
        $this->db->where('sellerDefaultCourier.super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get();
        return $data = $query->result_array();
    }

    public function getShipmentLogview($awb, $page_no,$cc_id,$status) {

        $page_no;
            $limit = 100;
            if (empty($page_no)) {
                $start = 0;
            } else {
                $start = ($page_no - 1) * $limit;
            }
            $this->db->select('*');
            $this->db->from('frwd_shipment_log');
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            if ($status != '')
                $this->db->where('status', $status);
    
            if ($cc_id != '')
                $this->db->where('cc_id', $cc_id);
            if ($awb != '')
                $this->db->where('slip_no', $awb );
                $this->db->order_by('id','DESC'); 
                $this->db->limit($limit, $start);
              
            $query = $this->db->get();
           // echo  $this->db->last_query();exit;
            if ($query->num_rows() > 0) {
    
    
                //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
                $data['result'] = $query->result_array();
                $data['count'] = $this->getShipmentLogCount($awb, $page_no,$cc_id,$status);
                return $data;
                // return $page_no.$this->db->last_query();
            } else {
                $data['result'] = '';
                $data['count'] = 0;
                return $data;
            }
        }
        public function getShipmentLogCount($awb, $page_no,$cc_id,$status) {
    
            
                $this->db->select('COUNT(id) as sh_count');
                $this->db->from('frwd_shipment_log');
                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
                if ($status != '')
                    $this->db->where('status', $status);
        
                if ($cc_id != '')
                    $this->db->where('cc_id', $cc_id);
                if ($awb != '')
                    $this->db->where('slip_no', $awb );
                    $this->db->order_by('id','DESC'); 
                    
                $query = $this->db->get();
           
                if ($query->num_rows() > 0) {
    
                    $data = $query->result_array();
                    return $data[0]['sh_count'];
                    // return $page_no.$this->db->last_query();
                }
                return 0;
            }
        public function getShipmentLogviewfilter($status = null) {
            $this->db->select('*');
            $this->db->from('frwd_shipment_log');
            $this->db->where('status', $status);
            $query = $this->db->get();
            return $data = $query->result_array();
        }

    public function GetallcompanyDetails() {
        $this->db->select('*');
        $this->db->from('site_config');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get();
        return $data = $query->row_array();
    }

    public function Getupdatecompnaydata($data = array()) {
        
        return $this->db->update('site_config', $data, array('super_id' => $this->session->userdata('user_details')['super_id']));
        echo $this->db->last_query();
        die;
    }

    public function checkOld($password = null) {
        
         $checkpass=md5($password); 
        $this->db->where('id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('user');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('password', $checkpass);

        $query = $this->db->get();
        //  echo md5(trim($password)).'//'.$password;
        /// echo $this->db->last_query(); die; 

        if ($query->num_rows() > 0) {
            return true;
        } else
            return false;
    }

    public function updatePassword($data = array(), $id) {
        $this->db->where('id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('user', $data);
        //echo $this->db->last_query();
    }

    public function getReverseShipmentLog($awb, $page_no,$cc_id,$status){
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->select('*');
        $this->db->from('frwd_reverse_ship_log');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($status != '')
            $this->db->where('status', $status);

        if ($cc_id != '')
            $this->db->where('cc_id', $cc_id);
        if ($awb != '')
            $this->db->where('slip_no', $awb );
            $this->db->or_where('frwd_slip_no', $awb );
            $this->db->order_by('id','DESC'); 
            $this->db->limit($limit, $start);
            
        $query = $this->db->get();
        // echo  $this->db->last_query();exit;
        if ($query->num_rows() > 0) {


        
            $data['result'] = $query->result_array();
            $data['count'] = $this->getReverseShipmentLogCount($awb, $page_no,$cc_id,$status);
            return $data;
            
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }

    }
    public function getReverseShipmentLogCount($awb, $page_no,$cc_id,$status) {
    
            
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('frwd_reverse_ship_log');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($status != '')
            $this->db->where('status', $status);

        if ($cc_id != '')
            $this->db->where('cc_id', $cc_id);
        if ($awb != '')
            $this->db->where('slip_no', $awb );
            $this->db->order_by('id','DESC'); 
            
        $query = $this->db->get();
   
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
        }
        return 0;
    }

}
