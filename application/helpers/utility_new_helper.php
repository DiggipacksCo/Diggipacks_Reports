<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');




if(!function_exists('Getcourirercompany')){
      function Getcourirercompany($id=null, $field=null){
        $ci=& get_instance();
        $ci->load->database();
        $sql ="SELECT $field FROM courier_company where id ='$id'";
        $query = $ci->db->query($sql);
        $row=$query->row_array();
        return $row[$field];
        
        
      }
    }


if(!function_exists('site_config_detaiil')){
      function site_config_detaiil($id=null, $field=null){
        $ci=& get_instance();
        $ci->load->database();
        $sql ="SELECT $field FROM site_config where id ='$id'";
        $query = $ci->db->query($sql);
        $row=$query->row_array();
        return $row[$field];
        
        
      }
    }

if (!function_exists('GetCourierslipnoDrop')) {

    function GetCourierslipnoDrop($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT id,slip_no, entry_date FROM frwd_shipment_log where super_id='" . $ci->session->userdata('user_details')['super_id'] . "' group by slip_no";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}