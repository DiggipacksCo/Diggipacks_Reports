<?php

class RoutsManagement_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getShowroute($searchroute = null, $page_no) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('root_fm');
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        if (!empty($searchroute)) {
            $this->db->where("route LIKE '%$searchroute%' or routecode LIKE '%$searchroute%'");
        }
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');

        $query = $this->db->get();
        //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->getShowrouteCount($searchroute, $page_no);
            return $data;
        }
    }

    public function getShowrouteCount($searchroute, $page_no) {
        if (!empty($searchroute)) {
            $this->db->where('route', 'routecode', $searchroute);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('root_fm');
        $this->db->order_by('id', 'DESC');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
        }
        return 0;
    }

    public function getexcelroutrtabl() {
        $this->db->select('*');
        $this->db->from('root_fm');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->order_by('id', 'DESC');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //	echo  $this->db->last_query(); die;   
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function getroutedelete($data = array(), $id = null) {
        return $this->db->update('root_fm', $data, array('id' => $id));
    }

    public function getRoute($data = array()) {
        return $this->db->insert('root_fm', $data);

        // echo $this->db->last_query(); die;     
    }

    public function InsertCityList($data = array()) {
        return $this->db->insert('country', $data);
    }

    public function checkRoute($routecode = null,$id=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        if(!empty($id))
        {
           $this->db->where('id!=',$id);  
        }
        $this->db->from('root_fm');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('route', $routecode);


        $query = $this->db->get();
        // echo $this->db->last_query(); die; 

        if ($query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function Getroutelist_edit($id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('root_fm');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('id', $id);


        $query = $this->db->get();
        // return $this->db->last_query(); die; 

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function routeUpdate($data = array(), $id = null) {
        return $this->db->update('root_fm', $data, array('id' => $id));
    }

    public function GetCityRouteDrop($data = array()) {
        // print_r($data);
        if (!empty($data))
            $country = $data['country'];
        else
            $country = "";
        //$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        //$this->db->distinct();
        $this->db->select('*');
        $this->db->from('country');
       // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where("city!=''");
        ///->db->group_by('state');
        $this->db->where('country', $country);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $query = $this->db->get();
        //echo $this->db->last_query(); die; 
        return $query->result_array();
    }

    public function AddedRutesData($data = array(), $editid = null) {
       // echo $editid; die;
        if ($editid > 0) {
            $this->db->update('root_fm', $data, array('id' => $editid));
            return 2;
        } else {
            $this->db->insert('root_fm', $data);
            return 1;
        }

        // echo $this->db->last_query(); die;     
    }

}

?>