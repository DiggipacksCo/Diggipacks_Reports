<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Finance_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function datainsertCat($data = array(), $editid = null) {
        if ($editid > 0) {
            $this->db->update('finance_cat', $data, array('id' => $editid));
            return 2;
        } else {
            $this->db->insert('finance_cat', $data);
            return 1;
        }
        //echo $this->db->last_query(); die;
    }

    public function getdeleteupdate($id = null) {
        $this->db->update('finance_cat', array('deleted' => 'Y'), array('id' => $id));
        return true;
    }

    public function GetalluserRatesUpdatesQuery($dataArray = array()) {
        $ii = 0;
        $addedArray = array();
        $updateArray = array();
        $conditionA = array();
        $curr_date = date("Y-m-d H:i:sa");
       // echo "sss"; die;
        if (!empty($dataArray)) {
            foreach ($dataArray as $data) {
                $exitsdata = getcheckalreadyexitsFinance($data['seller_id'], $data['id']);
                if ($exitsdata == 0) {      //  echo "sss";                   
                    
                    $addedArray[$ii]['cat_id'] = $data['id'];
                    $addedArray[$ii]['seller_id'] = $data['seller_id'];
                    $addedArray[$ii]['rate'] = $data['rates'];
                    $addedArray[$ii]['entry_date'] = $curr_date;
                   // if($data['setpiece'])
                    //{
                    $addedArray[$ii]['setpiece'] = $data['setpiece'];
                   // }
                    $addedArray[$ii]['super_id'] = $this->session->userdata('user_details')['super_id'];
                } else {
                    $updateArray['rate'] = $data['rates'];
                    if($data['setpiece'])
                    {
                    $updateArray['setpiece'] = $data['setpiece'];
                    }
                    $updateArray['last_updated'] = $curr_date;
                    $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
                    $this->db->update('finance_carges', $updateArray, array('id' => $data['rateid']));
                }
                $ii++;
            }
        }

        if (!empty($addedArray)) {
           
            $this->db->insert_batch('finance_carges', $addedArray);
           // echo "<pre><br/>"; print_r($addedArray);
           //  echo $this->db->last_query();  //die;
            //$this->db->update('storage_table',$data,array('id'=>$editid));
            return true;
        }
        if (!empty($updateArray))
            return true;
        //return $this->db->last_query(); 
    }

    public function addInvoiceUpdateDiscountfix($data=array())
    {
        $this->db->update('fixrate_invoice',$data,array('invoice_no'=>$data['invoice_no']));
   //echo $this->db->last_query(); exit;
	
        return true;
        
    }
    public function Getpaydynamic_edit($id=null)
    {
        $this->db->where('dynamic_invoice.super_id', $this->session->userdata('user_details')['super_id']);
     $this->db->select('*');
     $this->db->from('dynamic_invoice'); 
     //$this->db->where('deleted', 'N');
     $this->db->where('id', $id);
    
    
     $query = $this->db->get();
    // return $this->db->last_query(); die; 
      
    if($query->num_rows()>0)
    {
        return $query->row_array();
    }
    }

    public function Getpayfix_edit($id=null)
    {
        $this->db->where('fixrate_invoice.super_id', $this->session->userdata('user_details')['super_id']);
     $this->db->select('*');
     $this->db->from('fixrate_invoice'); 
     //$this->db->where('deleted', 'N');
     $this->db->where('id', $id);
    
    
     $query = $this->db->get();
    // return $this->db->last_query(); die; 
      
    if($query->num_rows()>0)
    {
        return $query->row_array();
    }
    }

   public function invoice_report($filtr = array()) {
        $this->db->group_by('invoice_no'); 
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('fixrate_invoice');

        if(!empty($filtr['seller_id']))
           $this->db->where('cust_id',$filtr['seller_id']);
           
        if(!empty($filtr['invoice_no']))
           $this->db->where('invoice_no',$filtr['invoice_no']);

        if (!empty($filtr['years'])) 
        $this->db->where('YEAR(invoice_date)', $filtr['years']);
        
        if (!empty($filtr['monthid'])) 
            $this->db->where('MONTH(invoice_date)', $filtr['monthid']);

        $query = $this->db->get();
       // echo  $this->db->last_query(); 
        return $query->result_array();
    }


public function dynamic_invoice_report($data=array())
    {
            
        $this->db->group_by('invoice_no'); 
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('dynamic_invoice');
         if(!empty($data['seller_id']))
       $this->db->where('cust_id',$data['seller_id']);

       if(!empty($data['invoice_no']))
       $this->db->where('invoice_no',$data['invoice_no']);
       if (!empty($data['years'])) 
       $this->db->where('YEAR(invoice_date)', $data['years']);
      
       if (!empty($data['monthid'])) 
           $this->db->where('MONTH(invoice_date)', $data['monthid']);
        $query = $this->db->get();
        //return $this->db->last_query(); 
        return $query->result_array();


    }

public function Geteditinvoicedata($data=array())
    {
     
        $this->db->select('*');
        $this->db->from('fixrate_invoice');

        $this->db->order_by('id', 'DESC');
        if(!empty($data['cust_id']))
       $this->db->where('cust_id',$data['cust_id']);
       if(!empty($data['invoice_no']))
       $this->db->where('invoice_no',$data['invoice_no']);
            
        // $this->db->limit($limit, $start);
        
        $query = $this->db->get();
       
      // $this->db->last_query(); die;
        if($query->num_rows()>0){
         
         $datareturn['result']=$query->result_array();
         $datareturn['count']=$this->getfixrateinvoiceCount($data); 
            return $datareturn;
         //return $page_no.$this->db->last_query();

        }
        else{
          $datareturn['result']='';
          $datareturn['count']=0; 
            return $datareturn;
        }
    }


    public function getfixrateinvoiceCount(array $data)
    {
        $this->db->select('COUNT(id) as idCount');
        $this->db->from('fixrate_invoice');
        $this->db->order_by('id', 'DESC');
        if(!empty($data['cust_id']))
       $this->db->where('cust_id',$data['cust_id']);

        $query = $this->db->get();
        if($query->num_rows()>0){
        }
        else{
         return 0;
        }

    }



public function Geteditinvoicedynamicdata($data=array())
    {
     
        $this->db->select('*');
        $this->db->from('dynamic_invoice');

        $this->db->order_by('id', 'DESC');
        if(!empty($data['cust_id']))
       $this->db->where('cust_id',$data['cust_id']);
       if(!empty($data['invoice_no']))
       $this->db->where('invoice_no',$data['invoice_no']);
            
        // $this->db->limit($limit, $start);
        
        $query = $this->db->get();
       
      // $this->db->last_query(); die;
        if($query->num_rows()>0){
         
          $datareturn['result']=$query->result_array();
         $datareturn['count']=$this->getdynamicinvoiceCount($data); 
            return $datareturn;
         //return $page_no.$this->db->last_query();

        }
        else{
          $datareturn['result']='';
         $datareturn['count']=0; 
            return $datareturn;
        }
    }

    public function getdynamicinvoiceCount(array $data)
    {
        $this->db->select('COUNT(id) as idCount');
        $this->db->from('dynamic_invoice');
        $this->db->order_by('id', 'DESC');
        if(!empty($data['cust_id']))
       $this->db->where('cust_id',$data['cust_id']);
        $query = $this->db->get();
        if($query->num_rows()>0){
        }
        else{
         return 0;
        }

    }


    
    public function Getcateditviewdata($id = null) {
        $this->db->where('id', $id);
        $this->db->select('*');
        $this->db->from('finance_cat');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getalluserchargesData() {
        $this->db->where('deleted', 'N');
        $this->db->where('type', 'Dynamic');
         $this->db->where('status', 'Y');
        $this->db->select('*');
        $this->db->from('finance_cat');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getalluserchargesData_new() {
        $this->db->where('deleted', 'N');
        $this->db->where('type', 'Dynamic');
        $this->db->where('status', 'Y');
        $this->db->select('*');
        $this->db->from('finance_cat');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getallfixratuserchargesData() {
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where('type', 'Fix Rate');
        $this->db->select('*');
        $this->db->from('finance_cat');
        $query = $this->db->get();
        return $query->result_array();
    }

     public function getallfixtypeData() {
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('invoice_type', 'Fix Rate');
        $this->db->select('*');
        $this->db->from('customer');
        $query = $this->db->get();
        return $query->result_array();
    }

     public function getalldynamictypeData() {
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('invoice_type', 'Dynamic');
        $this->db->select('*');
        $this->db->from('customer');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getlistdataCat($page_no) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('deleted', 'N');
        $this->db->select('*');
        $this->db->from('finance_cat');
        $this->db->order_by('name', 'asc');
        $tempdb = clone $this->db;
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->getlistdataCatCount($page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function getlistdataCatCount($page_no) {
        $this->db->where('deleted', 'N');
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('finance_cat');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function GetallpickupchargesqueryData($page_no, $seller_id = null, $otherData = array()) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        // $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('SUM(pickupcharge) as tpickupcharge,SUM(inbound_charge) as tinbound_charge,SUM(no_of_pallets)as totalpallets,SUM(inventory_charge) as tinventory_charge,id,entrydate,seller_id,storage_id');
        $this->db->from('orderpickupinvoice');
        if (!empty($otherData['fromdate']) && !empty($otherData['todate'])) {
            $this->db->where('DATE(entrydate) BETWEEN "' . $otherData['fromdate'] . '" and "' . $otherData['todate'] . '"');
        }
        $this->db->where('seller_id', $seller_id);
        $this->db->group_by('entrydate');
        $tempdb = clone $this->db;
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->GetallpickupchargesqueryDataCount($page_no, $seller_id, $otherData);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetallpickupchargesqueryDataCount($page_no, $seller_id = null, $otherData = array()) {
        //  $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('storagesinvoices');
        if (!empty($otherData['fromdate']) && !empty($otherData['todate'])) {
            $this->db->where('DATE(entrydate) BETWEEN "' . $otherData['fromdate'] . '" and "' . $otherData['todate'] . '"');
        }
        $this->db->where('seller_id', $seller_id);
        $this->db->group_by('entrydate');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function GetallskuandStorageDataQuery($page_no, $seller_id = null, $otherData = array()) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        //$seller_id=4;
        // $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('SUM(storagerate) as totalcharge,SUM(pallets)as totalpallets,id,entrydate,seller_id,storage_id');
        $this->db->from('storagesinvoices');
        if (!empty($otherData['fromdate']) && !empty($otherData['todate'])) {
            $this->db->where('DATE(entrydate) BETWEEN "' . $otherData['fromdate'] . '" and "' . $otherData['todate'] . '"');
        }

        $this->db->where('seller_id', $seller_id);
        $this->db->group_by('entrydate');
        $tempdb = clone $this->db;
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo  $this->db->last_query();
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = count($this->GetallskuandStorageDataQueryCount($page_no, $seller_id, $otherData));
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetallskuandStorageDataQueryCount($page_no, $seller_id = null, $otherData = array()) {
        //  $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('storagesinvoices');
        if (!empty($otherData['fromdate']) && !empty($otherData['todate'])) {
            $this->db->where('DATE(entrydate) BETWEEN "' . $otherData['fromdate'] . '" and "' . $otherData['todate'] . '"');
        }

        $this->db->where('seller_id', $seller_id);
        $this->db->group_by('entrydate');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function GetshowallinvocieDatashow($page_no) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        // $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('SI.*');
        $this->db->from('storagesinvoices as SI');
        $this->db->join('orderpickupinvoice as OPI', 'SI.seller_id=OPI.seller_id', 'left');
        //  $this->db->order_by('name','asc');
        $tempdb = clone $this->db;
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->GetshowallinvocieDatashowCount($page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetshowallinvocieDatashowCount($page_no) {
        //  $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(SI.id) as sh_count');
        $this->db->from('storagesinvoices as SI');
        $this->db->join('orderpickupinvoice as OPI', 'SI.seller_id=OPI.seller_id', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function GetallStorageTypesData($seller_id = null, $entrydate = null) {
        // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->query("SELECT SUM(`storagerate`) as totalstorage,SUM(`pallets`) as totalpallets,`storage_id` FROM `storagesinvoices` WHERE seller_id='$seller_id' and super_id='" . $this->session->userdata('user_details')['super_id'] . "' and entrydate='$entrydate' group by storage_id");
        $data = $query->result_array();
        // echo $this->db->last_query();
        return $data;
    }

    public function transaction_report($page_no, $slip_no, $seller, $to, $from) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $date = date('Y-m-d');
        //$date=date('2020-07-09');
        $this->db->where('orderoutboundinvoice.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('orderoutboundinvoice.*,seller_m.name');
        $this->db->from('orderoutboundinvoice');
        $this->db->join('customer as seller_m', 'seller_m.id = orderoutboundinvoice.seller_id');


        if (!empty($from) && !empty($to)) {
            $where = "DATE(orderoutboundinvoice.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        } else {
            $this->db->where('orderoutboundinvoice.entrydate like "' . $date . '%" ');
        }

        if (!empty($slip_no)) {
            $this->db->where('orderoutboundinvoice.slip_no', $slip_no);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }
        $this->db->order_by('orderoutboundinvoice.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->transactiionReportCount($page_no, $slip_no, $seller, $to, $from);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    

    public function transactiionReportCount($page_no, $slip_no, $seller, $to, $from) {

        $date = date('Y-m-d');
        $this->db->where('orderoutboundinvoice.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(orderoutboundinvoice.id) as idCount');
        $this->db->from('orderoutboundinvoice');
        $this->db->join('customer as seller_m', 'seller_m.id = orderoutboundinvoice.seller_id');


        if (!empty($from) && !empty($to)) {
            $where = "DATE(orderoutboundinvoice.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        } else {
            $this->db->where('orderoutboundinvoice.entrydate like "' . $date . '%" ');
        }

        if (!empty($slip_no)) {
            $this->db->where('orderoutboundinvoice.slip_no', $slip_no);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }



        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {


            return $query->row_array()['idCount'];
            // return $page_no.$this->db->last_query();
        } else {
            return 0;
        }
    }

    public function GetTransactionRepotydownloadQry(array $FilterArr) {


        $limit = 2000;
        $start = $FilterArr['exportlimit'] - $limit;

        $date = date('Y-m-d');
        //$date=date('2020-07-09');
        $this->db->where('orderoutboundinvoice.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('orderoutboundinvoice.*,seller_m.name');
        $this->db->from('orderoutboundinvoice');
        $this->db->join('customer as seller_m', 'seller_m.id = orderoutboundinvoice.seller_id');


        if (!empty($FilterArr['from']) && !empty($FilterArr['to'])) {
            $where = "DATE(orderoutboundinvoice.entrydate) BETWEEN '" . $FilterArr['from'] . "' AND '" . $FilterArr['to'] . "'";
            $this->db->where($where);
        }

        if (!empty($FilterArr['slip_no'])) {
            $this->db->where('orderoutboundinvoice.slip_no', $FilterArr['slip_no']);
        }

        if (!empty($FilterArr['seller'])) {
            $this->db->where('seller_m.id', $FilterArr['seller']);
        }
        $this->db->order_by('orderoutboundinvoice.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            return $data;
        }
    }

}
