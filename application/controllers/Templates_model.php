<?php

class Templates_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getSmsData($data = array()) {
        $page_no;
        $limit = 100;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        if (!empty($data['sms_templates_fm.status_id'])) {
            $status_name = $data['status_name'];
            $this->db->where('sms_templates_fm.status_id', $status_name);
        }
        $this->db->where('sms_templates_fm.super_id', $this->session->userdata('super_id'));
        $this->db->select('sms_templates_fm.*,status_main_cat.main_status,status_category.sub_status');
        $this->db->from('sms_templates_fm');
        $this->db->order_by('sms_templates_fm.id', 'DESC');
        $this->db->limit($limit, $start);
        // $this->db->where('sms_templates_fm.status','Y');
        $this->db->where('sms_templates_fm.deleted', 'N');
        $this->db->join('status_main_cat', 'sms_templates_fm.status_id=status_main_cat.id', 'left outer');
        $this->db->join('status_category', 'sms_templates_fm.sub_status=status_category.id', 'left outer');
        $query = $this->db->get();
        //return $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->getSmsDataCount($data);
            return $data;
        }
    }

    public function getStatusDropData() {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->from('status_main_cat_fm');
        $this->db->group_by('main_status');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function getSubstatus($id = null) {
        $this->db->select('*');
        $this->db->from('status_category_fm');
        $this->db->where_in('main_status', $id);
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function getSmsDataCount() {
        if (!empty($data['status_name'])) {
            $status_name = $data['status_name'];
            $this->db->where('status_name', $status_name);
        }


        $this->db->where('super_id', $this->session->userdata('super_id'));

        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('sms_templates_fm');
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
        }
        return 0;
    }

    public function insertsmsdata($data = array()) {
        
        return $this->db->insert('sms_templates_fm', $data);
        //return $this->db->insert_id();
    }

    public function UpdateSmsDataQry($data = array(), $id = null) {
        return $this->db->update('sms_templates_fm', $data, array('id' => $id));
    }

    public function getnotifydelete($data = array(), $id = null) {
        return $this->db->update('notification', $data, array('id' => $id));
    }

    public function QueryEditData($id = null) {
        $this->db->where('super_id', $this->session->userdata('super_id'));
        $this->db->select('*');
        $this->db->from('sms_templates_fm');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('id', $id);


        $query = $this->db->get();
        // return $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function getTempateByStatus($id = null) {
        $this->db->where('super_id', $this->session->userdata('super_id'));
        $this->db->select('*');
        $this->db->from('sms_templates_fm');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('status_id', $id);


        $query = $this->db->get();
        // return $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function smsUpdate($data = array(), $id = null) {
        return $this->db->update('sms_templates_fm', $data, array('id' => $id));
        //$this->db->last_query(); die(); 
    }

}

?>