<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bulkdownload extends MY_Controller {

    function __construct() {
        parent::__construct();
        //if (menuIdExitsInPrivilageArray(154) == 'N') {
        // redirect(base_url() . 'notfound');
        //  die;
        // }
        $this->load->model('Bulkdownload_model');
    }

    public function filter() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $count = $this->Bulkdownload_model->shipmCount($postData);


        $tolalShip = $count;
        $downlaoadData = 50000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }

        $dataArray['dropexport'] = $expoertdropArr;

        $dataArray['count'] = $count;

        echo json_encode($dataArray);
    }

    public function index() {

        $this->load->model('Shipment_model');
        $this->load->model('Seller_model');
        $this->load->model('Status_model');
        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $data = $this->Shipment_model->getStatusIDByName('3PL Updates');

        $result = array();
        if (!empty($data)) {
            $result = getallstatusbyid($data[0]['id']);
        }
        $bulk = array(
            'sellers' => $sellers,
            'status' => $status,
            'status_3pl' => $result,
        );

        $this->load->view('reports/bulkdownload', $bulk);
    }

    public function export() {

//die;
//        ini_set('display_errors', '1');
//        ini_set('display_startup_errors', '1');
//        error_reporting(E_ALL);
        //echo $this->input->post('searchval'); die;
        $postData = json_decode($this->input->post('searchval'), true);
        // echo print_r($postData);  
        // die;


        if ($postData['exportlimit'] > 0) {
            $limit = 50000;
            if (!empty($postData['exportlimit'])) {
                $start = $postData['exportlimit'] - $limit;
            } else {
                $start = 0;
            }


            //$super_id = $this->session->userdata('user_details')['super_id'];

            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 60000); //increase max_execution_time to 10 min if data set is very large
            //create a file
            $filename = "Bulk Shipment Report " . date("Y.m.d") . ".csv";
            $csv_file = fopen('php://output', 'w');
            fputs($csv_file, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
            // header('Content-Encoding: UTF-8');
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Type: text/csv");
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            if(!empty($postData['backorder']))
        {
           

            $this->db->where('shipment_fm.backorder', $postData['backorder']); 
            $this->db->where('shipment_fm.delivered', '11'); 
        }
        $this->db->where('shipment_fm.super_id!=6');
            if(!empty($postData['wh_id']))
            {
               
    
                $this->db->where_in('shipment_fm.super_id', $postData['wh_id']); 
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

            if (!empty($postData['f_from']) && !empty($postData['f_to'])) {

                $where1 = "DATE(shipment_fm.frwd_date) BETWEEN '" . $postData['f_from'] . "' AND '" . $postData['f_to'] . "'";


                $this->db->where($where1);
            }

            if (!empty($postData['from_c']) && !empty($postData['to_c'])) {
                $where = "DATE(shipment_fm.close_date) BETWEEN '" . $postData['from_c'] . "' AND '" . $postData['to_c'] . "'";


                $this->db->where($where);
            }

            if (!empty($postData['destination'])) {
                $this->db->where_in('shipment_fm.destination', $postData['destination']);
            }
            if (!empty($postData['status'])) {
                // echo "sssssss"; die;
                $this->db->where_in('shipment_fm.code', $postData['status']);
            }

            // echo "rrr";
            // die;
            // $this->db->where("shipment_fm.slip_no","DGF19270953993");
            // (select country from country where country.id=shipment_fm.origin) AS originCountry,
            //   (select country from country where country.id=shipment_fm.destination) AS destinationCountry,
            //IF (shipment_fm.delivered='19', (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1),'') AS LastStatus ,
            $this->db->select("`shipment_fm`.`id`,
 `shipment_fm`.`service_id`,
 `shipment_fm`.`booking_id`,
 `shipment_fm`.`slip_no`,
`status_main_cat_fm`.`main_status`,
`customer`.`name`,
 `customer`.`company`,
 `customer`.`seller_id`,
 `customer`.`uniqueid`,
 `shipment_fm`.`entrydate`,
(select city from country where country.id=shipment_fm.origin) AS origin,
(select city from country where country.id=shipment_fm.destination) AS destination,
(select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' limit 1) AS ForwardedCompany,
 `shipment_fm`.`reciever_name`,
 `shipment_fm`.`reciever_address`,
 `shipment_fm`.`reciever_phone`,
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
shipment_fm.shippers_ref_no,
 customer.uniqueid,
 customer.company as SENDER_NAME,
 shipment_fm.status_describtion,
 (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS threePLSTATUS,
 shipment_fm.pay_invoice_no,
 shipment_fm.pay_invoice_status,
 shipment_fm.rec_invoice_status,
 date(shipment_fm.entrydate) AS ENTRY_DATE,
 time(shipment_fm.entrydate) AS entry_TIME,
 shipment_fm.3pl_pickup_date,
 shipment_fm.frwd_date,
 shipment_fm.no_of_attempt,
 shipment_fm.close_date,
   IF (shipment_fm.reverse_type='1','Reverse order','Fullfillment order') AS ShipmentType ,
shipment_fm.laststatus_first,
shipment_fm.fd1_date,
shipment_fm.laststatus_second,   
shipment_fm.fd2_date,
 shipment_fm.laststatus_last,
 shipment_fm.fd3_date,
 shipment_fm.3pl_close_date,
 IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  )  AS transaction_days,
shipment_fm.frwd_company_awb");
            $this->db->from('shipment_fm');
            $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
            //  $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
            $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
            $fulfillment = 'Y';
            $deleted = 'N';
            $this->db->where('shipment_fm.fulfillment', $fulfillment);
            $this->db->where('shipment_fm.deleted', $deleted);
            $this->db->where('shipment_fm.backorder', '0');
            // $this->db->where('shipment_fm.super_id', $super_id);
            //$this->db->where('diamention_fm.super_id', $super_id);


            $this->db->limit($limit, $start);
            $query = $this->db->get();
            //or
            // echo $this->db->last_query(); die;


            $results = $query->result_array();

            // The column headings of your .csv file


            $header_row = array("AWB_NO", "ENTRY_DATE", "entry_TIME", "REFRENCE No", "SHIPPER REF No", "ORIGIN", "ForwardedCompany", "DESTINATION", "SENDER_NAME", "SENDER ADDRESS", "SENDER PHONE", "RECEIVER NAME", "RECEIVER ADDRESS", "RECEIVER PHONE", "INVOICE PAID", "INVOICE NUMBER", "INVOICE PAYMENT RECEIVED", "RECEIVER MODE", "MAINSTATUS", "3PLSTATUS", "LastStatus", "COD AMOUN", "UNIQUE_ID", "ON PIECES", "ON WEIGHT", "DESCRIPTION", "FORWARD AWB No.", "3PL Pickup Date", "3PL Closed Date", "CLOSE DATE", "No Of Attempt", "Transaction Number", "ShipmentType", "transaction_days", "FD First Status", "FD1 Date", "FD Second Status", "FD2 Date", "FD Last Status", "FD3 Date");
            //  $header_row = array("Ref. No.", "AWB NO", "SHIPPER REF No", "Origin Country", "Origin", "Destination Country", "Destination", "Sender Name", "Sender Address", "Sender Phone", "Receiver Name", "Receiver Address", "Receiver Phone", "Status", "Seller", "Entry Date", "SKU", "Qty", "COD", "Weight", "Deducted Shelve NO", "ForwardedCompany", "ForwardedCompany AWB", "Total COD", "Payment Mode", "Shipper Account No.", "UID Account", "DESCRIPTION", "3PLSTATUS", "INVOICE NUMBER", "INVOICE PAID", "INVOICE PAYMENT RECEIVED", "3PL Pickup Date", "3PL_FORWORD_DATE", "Transaction Days", "No of Attempt", "Close Date", "Failed 1st Status", "Failed 2nd Status", "Failed Last Status");
            fputcsv($csv_file, $header_row, ',', '"');

            // Each iteration of this while loop will be a row in your .csv file where each field corresponds to the heading of the column
            foreach ($results as $result) {
                // Array indexes correspond to the field names in your db table(s)
                $row = array(
                    $result['slip_no'],
                    $result['ENTRY_DATE'],
                    $result['entry_TIME'],
                    $result['booking_id'],
                    $result['shippers_ref_no'],
                    //$result['originCountry'],
                    $result['origin'],
                    $result['ForwardedCompany'],
                    // $result['destinationCountry'],
                    $result['destination'],
                    $result['company'],
                    $result['sender_address'],
                    $result['sender_phone'],
                    $result['reciever_name'],
                    addslashes($result['reciever_address']),
                    $result['reciever_phone'],
                    $result['pay_invoice_status'],
                    $result['pay_invoice_no'],
                    $result['rec_invoice_status'],
                    $result['mode'],
                    $result['main_status'],
                    $result['threePLSTATUS'],
                    '',
                    $result['total_cod_amt'],
                    $result['uniqueid'],
                    $result['pieces'],
                    $result['weight'],
                    $result['status_describtion'],
                    $result['frwd_company_awb'],
                    $result['3pl_pickup_date'],
                    $result['3pl_close_date'],
                    $result['close_date'],
                    $result['no_of_attempt'],
                    $result['pay_invoice_no'],
                    $result['ShipmentType'],
                    $result['transaction_days'],
                    $result['laststatus_first'],
                    $result['fd1_date'],
                    $result['laststatus_second'],
                    $result['fd2_date'],
                    $result['laststatus_last'],
                    $result['fd3_date'],
                        //  $result['company'],
                        // $result['sku'],
//                    $result['cod'],
//                    $result['wt'],
//                    $result['deducted_shelve'],
//                    
//                   
//                   
//                    $result['mode'],
//                    $result['shippers_ac_no'],
//                  
//                  
//                    
//                  
//                    
//                   
//                   
//                    $result['frwd_date'],
                );

                fputcsv($csv_file, $row, ',', '"');
            }

            fclose($csv_file);

            //reditect(base_url());
        }
    }

    public function getexceldata() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataAray = $this->Bulkdownload_model->alllistexcelData($_POST);

        $file_name = 'shipments.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

}
