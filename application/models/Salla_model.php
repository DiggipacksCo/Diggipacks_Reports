<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salla_model extends CI_Model {
	function __construct(){            
		parent::__construct();
		// $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	
	public function fetch_salla_customers($super_id){
		$this->db->select('*');
                $this->db->where('super_id',$super_id);
		$this->db->where('deleted','N');
		$this->db->where('salla_active','Y');
		$query=$this->db->get('customer');
		//echo $this->db->last_query();exit;
		if($query->num_rows()>0){
		    return $query->result();
		}
	}
	public function fetch_zid_customers($super_id=null, $uniqueid=null){
		$this->db->select('*');
                $this->db->where('uniqueid',$uniqueid);
		$this->db->where('deleted','N');
		$this->db->where('zid_active','Y');
		$query=$this->db->get('customer');
		//echo $this->db->last_query();exit;
		if($query->num_rows()>0){
                    return $query->row_array();
		}
	}
}