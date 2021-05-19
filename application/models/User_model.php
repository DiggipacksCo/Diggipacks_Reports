<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->user_fm_id:'1';
    }

    public function GetallusersCheckeamil($email = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('is_deleted', '0');
        $this->db->where('email', $email);


        $query = $this->db->get('user');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() == 0)
            return true;
        else
            return false;
    }

    public function add($data) {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
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


    public function all() {
        $this->db->where('system_access_fm', 'Y');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('is_deleted', '0');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('user');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function userDropval($type = NULL) {
        $this->db->where('system_access_fm', 'Y');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if (!empty($type)) {
            $this->db->where('user_type', $type);
        }
        $this->db->where('is_deleted', '0');
        $this->db->where('deleted', 'N');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('user');
        //return  $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function count() {
        return $this->db->count_all("customer");
    }

    public function edit_view($id = null) {
        $this->db->where('system_access_fm', 'Y');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        // $this->db->get_where('seller_m',array('id'=>$id));
        $query = $this->db->get('user');
        //print_r($this->db->last_query());   
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function edit_view_access($id = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        // $this->db->get_where('seller_m',array('id'=>$id));
        $query = $this->db->get('template_access');
        //print_r($this->db->last_query());   
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function edit($id, $data) {
        $this->db->where('system_access_fm', 'Y');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
        //] print_r($this->db->last_query()); die;   
    }

    public function deleteupdatequery($id, $data) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
        // print_r($this->db->last_query()); die;   
    }

    public function alldetails($id = null) {

        $query = $this->db->query("select * from user where id='$id' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function getallprivilegedata($id = null) {

        $SuperAccessIDs = GetSuperAdminAccessIds();
        $ids_array = explode(',', $SuperAccessIDs);
        $this->db->where_in('id', $ids_array);
        $this->db->select('*');
        $this->db->from('privilege_details_fm');
        $this->db->where("pid=0");
        $this->db->where("deleted", 'N');
        $query = $this->db->get();

        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function setCustomerPrivilageUpdate($data = array()) {

        $query = $this->db->query("select id,privilage_array from set_user_privilege_fm where customer_id='" . $data['customer_id'] . "' and deleted='N' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            $query_data = $query->row_array();
            $privilage_array = explode(',', $query_data['privilage_array']);

            if (in_array($data['privilage_id'], $privilage_array)) {
                if ($data['onoff_true_false'] == 'false') {
                    foreach (array_keys($privilage_array, $data['privilage_id']) as $key) {
                        unset($privilage_array[$key]);
                    }

                    array_values($privilage_array);
                }
            } else {
                array_push($privilage_array, $data['privilage_id']);
            }
            $insert_privilage_array = '';

            $insert_privilage_array = implode(',', $privilage_array);
            $insert_privilage_array = rtrim($insert_privilage_array, ',');
            $insert_privilage_array = ltrim($insert_privilage_array, ',');
            $this->db->update("set_user_privilege_fm", array('privilage_array' => $insert_privilage_array), array('customer_id' => $data['customer_id']));
            return true;
        } else {
            $addArray = array('customer_id' => $data['customer_id'], 'privilage_array' => $data['privilage_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
            $this->db->insert('set_user_privilege_fm', $addArray);
            return true;
        }
    }

    public function ShowPickerDataList() {
        $this->db->where('system_access_fm', 'Y');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('user_type', 4);
        $this->db->where('deleted', 'N');
        $this->db->where('is_deleted', 0);
        $this->db->where('status', 'Y');
        // $this->db->get_where('seller_m',array('id'=>$id));
        $query = $this->db->get('user');
        //print_r($this->db->last_query());   

        return $query->result_array();
    }

    public function showaccesstemplatelistQry() {

        $this->db->where('template_access.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('template_access.*,designation_tbl.designation_name');
        $this->db->from('template_access');
        $this->db->join('designation_tbl', 'designation_tbl.id=template_access.d_id');

        $query = $this->db->get();
        //print_r($this->db->last_query());   

        return $query->result_array();
    }

    public function designation_tblDaata() {
        $this->db->where('type', 'F');
        $query = $this->db->get('designation_tbl');
        return $query->result_array();
    }

    public function userCategoryData() {
         $SuperAccessIDs = GetSuperAdminAccessIds();
        $ids_array = explode(',', $SuperAccessIDs);
        $this->db->where_in('id', $ids_array);
        $this->db->where('pid', 0);
        $this->db->where('deleted', 'N');
        $query = $this->db->get('privilege_details_fm');
        return $query->result_array();
    }

    public function GetSubCatDatashowQry($pids = array(),$editPid=array()) {
        if(!empty($pids))
        {
         
          $this->db->where_in('pid', $pids); 
        }
       
        else
        {   
            $editPid=explode(',',$editPid);
            $this->db->where_in('pid', $editPid);
            
        }
        $this->db->select('*');
        $this->db->from('privilege_details_fm');
        
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
       // echo $this->db->last_query();
        return $query->result_array();
    }

    public function add_newTemplate($data = array()) {
        return $this->db->insert('template_access', $data);
    }

    public function GetcheckExitsType($d_id = null) {
        $this->db->where('d_id', $d_id);
        $query = $this->db->get('template_access');
        return $query->row_array();
    }

    public function add_newTemplate_update($data = array(), $d_id = null) {
        return $this->db->update('template_access', $data, array('d_id' => $d_id));
    }

    public function GetmainCattDatashowQry($id = array()) {
        $this->db->where_in('id', $id);
        $this->db->where('deleted', 'N');
        $this->db->select('privilege_name');
        $this->db->from('privilege_details_fm');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetCheckPrivilageDataValid($cust_id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('customer_id', $cust_id);
        $this->db->where('deleted', 'N');
        $this->db->select('*');
        $this->db->from('set_user_privilege_fm');

        $query = $this->db->get();
        return $query->row_array();
    }

    public function PrivilageAdduserUpdate($data = array(), $customer_id = null) {
        return $this->db->update('set_user_privilege_fm', $data, array('customer_id' => $customer_id));
    }

    public function PrivilageAdduser($data = array()) {
        return $this->db->insert('set_user_privilege_fm', $data);
    }

}
