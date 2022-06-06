<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bulkdownload_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function ShipData(array $slipdata) {

        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('slip_no', $slipdata);
        $this->db->where('code', 'OG');
        //echo "<br> last_query = ";

        $query = $this->db->get();
        //return  $this->db->result_array();
        if ($query->num_rows() > 0) {

            return $query->result_array();
            //  print_r($query->result());
            // exit();
        }
        // return  $this->db->last_query();
        // die; 
    }

    
     public function shipmCount($data=array()) 
    {



        if (!empty($data['cc_id'])) {
            $cc_id = array_filter($data['cc_id']);

            $this->db->where_in('shipment_fm.frwd_company_id', $data['cc_id']);
        }

        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.super_id!=6');
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
       // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        
        if(!empty($data['backorder']))
        {
           

            $this->db->where('shipment_fm.backorder', $data['backorder']); 
            $this->db->where('shipment_fm.delivered', '11'); 
        }
        if(!empty($data['wh_id']))
        {
            $wh_id = array_filter($data['wh_id']);

            $this->db->where_in('shipment_fm.super_id', $data['wh_id']); 
        }
        if (!empty($data['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $data['exact']);
        }

           // $this->db->where('shipment_fm.backorder', 0);
           
      

        if (!empty($data['from']) && !empty($data['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";


            $this->db->where($where);
        }
       
        if (!empty($data['f_from']) && !empty($data['f_to'])) {
           
            $where1 = "DATE(shipment_fm.frwd_date) BETWEEN '" . $data['f_from'] . "' AND '" . $data['f_to'] . "'";


            $this->db->where($where1);
        }
        
         if (!empty($data['from_c']) && !empty($data['to_c'])) {
            $where = "DATE(shipment_fm.close_date) BETWEEN '" . $data['from_c'] . "' AND '" . $data['to_c'] . "'";


            $this->db->where($where);
        }
        if (isset($data['reverse_type'])) {
            $this->db->where('shipment_fm.reverse_type', $data['reverse_type']);
        }

        if (!empty($data['order_type'])) {
           if($data['order_type']=='B2B')
             $this->db->where('shipment_fm.order_type', $data['order_type']);
           else
             $this->db->where('shipment_fm.order_type','');  
        }

      
 $delivered=$data['status'];
        if (!empty($data['status'])) {
           
           $this->db->where_in('shipment_fm.code', $delivered);
            
        }
          if (!empty($data['mode'])) {
           
           $this->db->where('shipment_fm.mode', $data['mode']);
            
        }
       

        if (!empty($data['destination'])) {
            $destination = array_filter($data['destination']);

            $this->db->where_in('shipment_fm.destination', $data['destination']);
        }

        if (!empty($data['slip_no'])) {
            $this->db->where('shipment_fm.slip_no', $data['slip_no']);
        }
        if($data['s_type']=='close_date')
        {
            if(!empty($data['s_type_val']))
            {
             $this->db->where('DATE(shipment_fm.close_date)', $data['s_type_val']);
            }
        }
        
        // if (!empty($wh_id)) {
        //     $this->db->where('shipment_fm.wh_id', $wh_id);
        // }
        if(!empty($data['country'])){    
            if(!empty($wh_id_arr) && count($wh_id_arr)>0){
                $this->db->where_in('shipment_fm.wh_id', implode(",",$wh_id_arr));
            }else{
                $this->db->where_in('shipment_fm.wh_id', NULL);
            }
        }
         if (!empty($data['booking_id'])) {
            $this->db->where('shipment_fm.booking_id', $data['booking_id']);
        }
         if (!empty($data['reciever_phone'])) {
            $this->db->where('shipment_fm.reciever_phone', $data['reciever_phone']);
        }

        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */

        if (!empty($data['seller'])) {
            $seller = array_filter($data['seller']);
            $this->db->where_in('shipment_fm.cust_id', $seller);
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
    
    public function alllistexcelData($postData = array()) {
$super_id = $this->session->userdata('user_details')['super_id'];
        $this->load->dbutil();
        $this->load->helper('file');
        
         $limit = 5000;
            if (!empty($postData['exportlimit'])) {
                $start = $postData['exportlimit'] - $limit;
            } else {
                $start = 0;
            }
       if (!empty($postData['seller'])) {

                $this->db->where_in('shipment_fm.cust_id', $postData['seller']);
            }

            if (!empty($postData['mode'])) {

                $this->db->where_in('shipment_fm.mode', $postData['mode']);
            }
            if (!empty($postData['from']) && !empty($postData['to'])) {
                $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $postData['from'] . "' AND '" . $postData['to'] . "'";
                $this->db->where($where);
            }

            if (!empty(['destination'])) {
                $this->db->where_in('shipment_fm.destination', $postData['destination']);
            }
            if (!empty(['status'])) {
                $this->db->where_in('shipment_fm.code', $postData['status']);
            }
           // $this->db->where("shipment_fm.slip_no","DGF19270953993");
            $this->db->select("`shipment_fm`.`id`,
 `shipment_fm`.`service_id`,
 `shipment_fm`.`booking_id`,
 `shipment_fm`.`slip_no`,
 `diamention_fm`.`sku`,
 `status_main_cat_fm`.`main_status`,
 `diamention_fm`.`piece`,
 `diamention_fm`.`wieght` as `wt`,
 `diamention_fm`.`description`,
 `diamention_fm`.`cod`,
 `customer`.`name`,
 `customer`.`company`,
 `customer`.`seller_id`,
 `customer`.`uniqueid`,
 `shipment_fm`.`entrydate`,
(select city from country where country.id=shipment_fm.origin) AS origin,
(select country from country where country.id=shipment_fm.origin) AS originCountry,
  (select city from country where country.id=shipment_fm.destination) AS destination,
  (select country from country where country.id=shipment_fm.destination) AS destinationCountry,
  (select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' AND courier_company.super_id= " . $super_id . " limit 1) AS ForwardedCompany,
 `shipment_fm`.`reciever_name`,
 `shipment_fm`.`reciever_address`,
 `shipment_fm`.`reciever_phone`,
 `shipment_fm`.`sender_name`,
 `shipment_fm`.`sender_address`,
 `shipment_fm`.`sender_phone`,
 `shipment_fm`.`order_type`,
 `shipment_fm`.`sender_email`,
 `shipment_fm`.`mode`,
 `shipment_fm`.`total_cod_amt`,
 `shipment_fm`.`weight`,
 `shipment_fm`.`pieces`,
 `shipment_fm`.`cust_id`,
 `shipment_fm`.`shippers_ac_no`,
 `shipment_fm`.`frwd_company_awb`,
 `diamention_fm`.`deducted_shelve`,
 shipment_fm.shippers_ref_no,
 customer.uniqueid,
 shipment_fm.status_describtion,
 (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS threePLSTATUS,
 shipment_fm.pay_invoice_no,
 shipment_fm.pay_invoice_status,
 shipment_fm.rec_invoice_status,
 shipment_fm.3pl_pickup_date,
 shipment_fm.frwd_date,
 shipment_fm.no_of_attempt,
 shipment_fm.close_date,
 shipment_fm.laststatus_first,
 shipment_fm.fd1_date,
 shipment_fm.laststatus_second,
 shipment_fm.fd2_date,
 shipment_fm.laststatus_last,
 shipment_fm.fd3_date,
 IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  )  AS transaction_days,
shipment_fm.frwd_company_awb");
            $this->db->from('shipment_fm');
            $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
            $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
            $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
            $fulfillment = 'Y';
            $deleted = 'N';
            $this->db->where('shipment_fm.fulfillment', $fulfillment);
            $this->db->where('shipment_fm.deleted', $deleted);
            $this->db->where('shipment_fm.backorder', '0');
            $this->db->where('shipment_fm.super_id', $super_id);
            $this->db->where('diamention_fm.super_id', $super_id);


            $this->db->limit($limit, $start);
            $query = $this->db->get();
           // echo $this->db->last_query(); die;
        $delimiter = ",";
        $newline = "\r\n";




        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }
   
    

}
