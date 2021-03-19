<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Storage_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function datainsert($data = array(), $editid = null) {
        if ($editid > 0) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->update('storage_table', $data, array('id' => $editid));
            return 2;
        } else {
            $this->db->insert('storage_table', $data);
            return 1;
        }
        //echo $this->db->last_query();die;
    }

    public function getalldataupdatequery($dataArray = array()) {


        $ii = 0;
        $addedArray = array();
        $updateArray = array();
        $conditionA = array();
        foreach ($dataArray as $data) {
            $exitsdata = getcheckalreadyexitsstorage($data['seller_id'], $data['id']);
            if ($exitsdata == 0) {
                $addedArray[$ii]['storage_id'] = $data['id'];
                $addedArray[$ii]['client_id'] = $data['seller_id'];
                $addedArray[$ii]['rate'] = $data['rates'];
                $addedArray[$ii]['super_id'] = $this->session->userdata('user_details')['super_id'];
            } else {
                $updateArray['rate'] = $data['rates'];
                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
                $this->db->update('storage_rate_table', $updateArray, array('id' => $data['rateid']));
            }
            $ii++;
        }

        if (!empty($addedArray)) {
            $this->db->insert_batch('storage_rate_table', $addedArray);
            //$this->db->update('storage_table',$data,array('id'=>$editid));
            return true;
        }
        if (!empty($updateArray))
            return true;
    }

   
    public function editviewquery($id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $this->db->select('*');
        $this->db->from('storage_table');
        $query = $this->db->get();
        return $query->row_array();
    }


    public function datainsertdefault($data = array(), $editid = null) {
        if ($editid > 0) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->update('storage_table_defaultcharge', $data, array('id' => $editid));
            return 2;
        } else {
            $this->db->insert('storage_table_defaultcharge', $data);
            return 1;
        }
        //echo $this->db->last_query();die;
    }


    public function editviewchargesquery($id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
       // $this->db->where('cust_id', $id);
        $this->db->select('*');
        $this->db->from('storage_table_defaultcharge');
        $query = $this->db->get();
     //  echo  $this->db->last_query(); exit; 
        return $query->row_array();
    }

    public function getalltypesetrate($seller_id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->select('*');
        $this->db->from('storage_table');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getlistdata($page_no) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->select('*');
        $this->db->from('storage_table');
        $this->db->order_by('id', 'desc');

        $tempdb = clone $this->db;
        $this->db->limit($limit, $start);
        $query = $this->db->get();
      

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->getlistdataCount($page_no);
             return $data;
        } 
        else {
             $storage_type= array("Bins",  "Shelve", "Room", "Pallet");
             $no_of_pallet = 0;
             $entrydate = date("Y-m-d h:i:sa");
             $status = 'Y';
             $deleted = 'N';
             $super_id = $this->session->userdata('user_details')['super_id'];
             $rate = 0;
             foreach($storage_type as $dd) {
                    $cargval = array(
                                'storage_type' =>  $dd, 
                                'no_of_pallet' =>  $no_of_pallet, 
                                'entrydate' =>  $entrydate, 
                                'status' =>  $status, 
                                'deleted' =>  $deleted, 
                                'super_id' =>  $super_id, 
                                'rate' =>  $rate,
                                ); 
                       $cho =  $this->db->insert('storage_table', $cargval);
                }
                     
                    $data['result'] = '';
                    $data['count'] = 0;
                     return $data;
        }
    }

    public function getlistdataCount($page_no) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('storage_table');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function getSellerStorageCharges($id,$page_no) {
       
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        } 
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where('cust_id', $id);
        $this->db->select('*');
        $this->db->from('storage_table_defaultcharge');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();     
      

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->getlistdataCountdefault($id,$page_no);
            
             return $data;
        } 
        else {
             $storage_type= array("Bins",  "Shelve", "Room", "Pallet");
             $no_of_pallet = 0;
             $entrydate = date("Y-m-d h:i:sa");
             $status = 'Y';
             $deleted = 'N';
             $super_id = $this->session->userdata('user_details')['super_id'];
             $rate = 0;
             $cust_id=$id;
             foreach($storage_type as $dd) {
                    $cargval = array(
                                'storage_type' =>  $dd, 
                                'no_of_pallet' =>  $no_of_pallet, 
                                'entrydate' =>  $entrydate, 
                                'status' =>  $status, 
                                'deleted' =>  $deleted, 
                                'super_id' =>  $super_id, 
                                'rate' =>  $rate,
                                'cust_id' =>  $id,
                                ); 
                       $cho =  $this->db->insert('storage_table_defaultcharge', $cargval);
                }
                     
                    $data['result'] = '';
                    $data['count'] = 0;
                     return $data;
        }
    }
    public function getlistdataCountdefault($id,$page_no) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where('cust_id', $id);
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('storage_table_defaultcharge');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $page_no.$data[0]['sh_count'];
            //echo  $this->db->last_query();
        }
        return 0;
    }


}
