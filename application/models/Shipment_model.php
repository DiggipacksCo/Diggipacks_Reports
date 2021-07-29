<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('ItemInventory_model'); 
        $this->load->model('Item_model');
        $this->load->model('Cartoon_model');
        $this->load->model('Seller_model');
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function updateStatusBatch($data) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update_batch('shipment_fm', $data, 'slip_no');
       //echo $this->db->last_query(); 
    }

    public function removeForwarding($data=array()) {
      //  print_r($data); exit;
      if( !empty($data))
      {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('slip_no', $data);
        $query2 = $this->db->update('shipment_fm',array('frwd_company_id'=>0,'frwd_company_awb'=>'','frwd_company_label'=>'','forwarded'=>0));
       //echo  $this->db->last_query(); //exit;
      }
       
    }
    public function updateStatus($data) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('slip_no', $data['where_in']);
        $query2 = $this->db->update('shipment_fm', $data['update']);
       // echo  $this->db->last_query(); //exit;
    }

    public function GetupdatepalletnoShipment($data = array(), $slip_no = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('slip_no', $slip_no);
        $query2 = $this->db->update('shipment_fm', $data);
        return $this->db->last_query();
    }

     public function ShipData(array $slipdata) {

            $this->db->select('*');
            $this->db->from('shipment_fm');
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);        
            $this->db->where_in('slip_no', $slipdata);
            $this->db->where('code','OG');
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

    public function stockdeletepicklistFM( $slip_no){

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('slip_no', $slip_no);
        $query2 = $this->db->update('pickuplist_tbl', array('deleted' =>'Y'));
        return $this->db->last_query();

    }
    public function stockSaveShipmentFM(array $getInventory)
    {
       
        foreach ($getInventory as $updateInventory) {

                $row_qty = 0;
                $Inv_id = $updateInventory['id'];
                $Inv_qty = $updateInventory['quantity'];

                $IT =   $this->db->query("select * from item_inventory where id = '".$Inv_id."'");
                $row_inv = $IT->row_array();

               $row_qty = ($row_inv['quantity'] + $Inv_qty);   
               
               $activitiesArr[] = array('exp_date' => $row_inv['expity_date'], 'st_location' => $updateInventory['stock_location'], 'item_sku' => $row_inv['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $row_inv['seller_id'], 'qty' => $row_qty, 'p_qty' => $row_inv['quantity'], 'qty_used' => $Inv_qty, 'type' => 'Add', 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $updateInventory['slip_no'], 'super_id' => $this->session->userdata('user_details')['super_id'],'shelve_no'=>$updateInventory['shelve_no']);
          

                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
                $this->db->where('id', $Inv_id);
                $this->db->update('item_inventory', array('quantity'=> $row_qty, 'stock_location' =>$updateInventory['stock_location'] ,
                'shelve_no' =>$updateInventory['shelve_no'] ));
              
              //echo  $this->db->last_query(); 
        }

        $this->db->insert_batch('inventory_activity', $activitiesArr);
        //echo"<br><br><br>".  $this->db->last_query(); 

    }

    public function add($data) {


        $array = array(
            'seller_id' => $data['seller'],
            'item_sku' => $data['item_sku']
        );

        // $cartoon_sku=$data['cartoon_sku'];

        $itemInventory = $this->ItemInventory_model->find($array);
        // $cartoonInventory=$this->Cartoon_model->find($cartoon_sku);
        //||!$cartoonInventory

        if (!$itemInventory) {
            return false;
        }
        // && count($cartoonInventory)==1
        elseif (count($itemInventory) == 1) {

            $item_previous_data = $itemInventory[0];
            // $cartoon_previous_data=$cartoonInventory[0];

            $item_previous_quantity = $item_previous_data->quantity;
            // $cartoon_previous_quantity=$cartoon_previous_data->quantity;


            $item_new_quantity = $data['item_quantity'];
            // $cartoon_new_quantity=$data['cartoon_quantity'];

            $item_updated_quantity = $item_previous_quantity - $item_new_quantity;
            // $cartoon_updated_quantity=$cartoon_previous_quantity-$cartoon_new_quantity;

            $item_new_data = array(
                'quantity' => $item_updated_quantity,
                'update_date' => date("Y/m/d h:i:sa")
            );

            // $cartoon_new_data=array(
            //   'quantity'=>$cartoon_updated_quantity,
            //   'update_date'=>date("Y/m/d h:i:sa")
            // );


            $item_inventory_history = array(
                'item_sku' => $data['item_sku'],
                'item_previous_quantity' => $item_previous_quantity,
                'item_new_quantity' => $item_updated_quantity,
                //  'cartoon_sku'=>$data['cartoon_sku'],
                // 'cartoon_previous_quantity'=>$cartoon_previous_quantity,
                // 'cartoon_new_quantity'=>$cartoon_updated_quantity,
                'update_date' => date("Y/m/d h:i:sa"),
                'seller_id' => $data['seller'],
                'status' => $data['status']
            );

            // $this->db->insert('item_inventory_history',$item_inventory_history);
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where($array);
            $this->db->update('item_inventory', $item_new_data);

            // $this->db->where('id',$cartoon_sku);
            // $this->db->update('cartoon_inventory',$cartoon_new_data);

            return $this->db->insert('shipment_fm_m', $data);
        }
    }

    public function all() {


        // $this->db->select('shipment_m.id ,shipment_m.awb_no, items_m.sku  as Item_Sku, seller_m.name as seller_name,shipment_m.status,shipment_m.item_quantity,shipment_m.date');
        // $this->db->from('shipment_m');
        // $this->db->join('items_m', 'items_m.id = shipment_m.item_sku');
        // $this->db->join('seller_m', 'seller_m.id = shipment_m.seller');
        //$this->db->join('cartoon_inventory', 'cartoon_inventory.id = shipment_m.cartoon_sku');


        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $fulfillment = 'Y';
        $this->db->where('fulfillment', $fulfillment);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,customer.name,shipment_fm.entrydate');
        $this->db->from('shipment_fm');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');

        $this->db->order_by('id', 'asc');
        $query = $this->db->get();
        // $fulfillment='Y';
        // $this->db->where('fulfillment',$fulfillment);
        // $this->db->select('id,slip_no,sku,cust_id,delivered,pieces,entrydate');
        // $this->db->order_by('id', 'desc');
        // $query = $this->db->get('shipment_fm');


        if ($query->num_rows() > 0) {

            return $query->result();
            //  print_r($query->result());
            // exit();
        }
    }

  public function getawbdataquery($awbids = array()) {
        // print_r($awbids[0]);
        $awbarray = $awbids;
if(!empty($awbids ))
{
        $counter = 0;
        $conditions = "";
        foreach ($awbarray as $ids) {
            if ($counter == 0)
                $conditions = $ids;
            else
                $conditions .= "','" . $ids;
            $counter++;
        }

        $query = $this->db->query("select * from shipment_fm where (slip_no IN('$conditions') or booking_id IN('$conditions') or frwd_company_awb IN('$conditions')) and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
    }


    public function getawbdataqueryInvoice($awbids = array(),$cust_id=null) {
        // print_r($awbids[0]);
        $awbarray = $awbids;

        $counter = 0;
        $conditions = "";
        foreach ($awbarray as $ids) {
            if ($counter == 0)
                $conditions = $ids;
            else
                $conditions .= "','" . $ids;
            $counter++;
        }

        $query = $this->db->query("select * from shipment_fm where (slip_no IN('$conditions') or booking_id IN('$conditions') or frwd_company_awb IN('$conditions')) and cust_id='".$cust_id."' and code In ('POD','RTC') and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function all_json() {


        // $this->db->select('shipment_m.id ,shipment_m.awb_no, items_m.sku  as Item_Sku, seller_m.name as seller_name,shipment_m.status,shipment_m.item_quantity,shipment_m.date');
        // $this->db->from('shipment_m');
        // $this->db->join('items_m', 'items_m.id = shipment_m.item_sku');
        // $this->db->join('seller_m', 'seller_m.id = shipment_m.seller');
        //$this->db->join('cartoon_inventory', 'cartoon_inventory.id = shipment_m.cartoon_sku');
        $fulfillment = 'Y';
        $this->db->where('fulfillment', $fulfillment);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,customer.name,shipment_fm.entrydate');
        $this->db->from('shipment_fm');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');

        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        // $fulfillment='Y';
        // $this->db->where('fulfillment',$fulfillment);
        // $this->db->select('id,slip_no,sku,cust_id,delivered,pieces,entrydate');
        // $this->db->order_by('id', 'desc');
        // $query = $this->db->get('shipment_fm');


        if ($query->num_rows() > 0) {

            echo json_encode($query->result());
        }
    }

    public function getalltravelhistorydata($awbno = null) {
        $query = $this->db->query("select * from status_fm where slip_no='$awbno' and super_id='" . $this->session->userdata('user_details')['super_id'] . "' order by entry_date DESC");
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function getallshipmentdatashow($id = null) {
        $query = $this->db->query("select * from shipment_fm where (id='$id' || slip_no='$id') and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
        // echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function allstatus() {
        $query = $this->db->get('status_main_cat_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function add_view() {

        //$data['items'] = $this->Item_model->all();

        $data['sellers'] = $this->Seller_model->all();

        // $data['cartoons'] = $this->Cartoon_model->all();
        return $data;
        // $this->db->select('item_inventory.id , item_inventory.item_sku , item_inventory.quantity,item_inventory.update_date , items_m.name');
        // $this->db->from('item_inventory');
        // $this->db->join('items_m', 'items_m.sku = item_inventory.item_sku');
        // $this->db->where('item_inventory.id' , $id);
        // $query = $this->db->get();
        // // $this->db->get_where('seller_m',array('id'=>$id));
        // // $query = $this->db->get('item_inventory');
        // if($query->num_rows()>0){
        //   return $query->row();
        // }
    }

    public function edit_view($id) {
        $this->db->where('id', $id);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('shipment_fm');
     
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function edit($id, $data) {


        $this->db->where('id', $id);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('shipment_fm', $data);
    }

    public function RTS() {


        //   $this->db->select('shipment_m.id , items_m.sku  as Item_Sku, cartoon_inventory.sku as Cartoon_Sku,seller_m.name as seller_name,shipment_m.status,shipment_m.item_quantity,shipment_m.cartoon_quantity,shipment_m.date');
        //   $this->db->where('status',19);
        // $this->db->from('shipment_m');
        // $this->db->join('items_m', 'items_m.id = shipment_m.item_sku');
        // $this->db->join('seller_m', 'seller_m.id = shipment_m.seller');
        // $this->db->join('cartoon_inventory', 'cartoon_inventory.id = shipment_m.cartoon_sku');

        $fulfillment = 'Y';
        $deleted = 'N';

        $conditions = array(
            'fulfillment' => $fulfillment,
            'delivered' => 19,
            'shipment_fm.deleted' => $deleted,
        );
        // $this->db->where($conditions);
        // $this->db->select('id,slip_no,sku,cust_id,delivered,pieces,entrydate');
        // $this->db->order_by('id', 'desc');
        // $query = $this->db->get('shipment_fm');
        // if($query->num_rows()>0){
        //   return $query->result();
        // }

        $this->db->where($conditions);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,customer.name,shipment_fm.entrydate');
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('shipment_fm');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');

        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        // $fulfillment='Y';
        // $this->db->where('fulfillment',$fulfillment);
        // $this->db->select('id,slip_no,sku,cust_id,delivered,pieces,entrydate');
        // $this->db->order_by('id', 'desc');
        // $query = $this->db->get('shipment_fm');


        if ($query->num_rows() > 0) {

            return $query->result();
            //    print_r($query->result());
            // exit();
        }
    }

    public function count() {
        // $fulfillment='Y';
        //  $conditions=array(
        //  'fulfillment'=>$fulfillment,
        //  );
        // $this->db->where($conditions);
        // return $this->db->count_all("shipment");
        $fulfillment = 'Y';
        $deleted = 'N';
        $conditions = array(
            'fulfillment' => $fulfillment,
            'deleted' => $deleted,
             'super_id' => $this->session->userdata('user_details')['super_id'],
        );

        return $this->db->where($conditions)->from('shipment_fm')->count_all_results();
    }

    public function countRTS() {
        $deleted = 'N';
        $fulfillment = 'Y';
        $conditions = array(
            'fulfillment' => $fulfillment,
            'delivered' => 19,
            'deleted' => $deleted,
            'super_id' => $this->session->userdata('user_details')['super_id'],
        );

        return $this->db->where($conditions)->from('shipment_fm')->count_all_results();
    }

    public function find($id) {

        // $this->db->where('seller',$id);
        // $this->db->select('shipment_m.id , items_m.sku  as Item_Sku, cartoon_inventory.sku as Cartoon_Sku,seller_m.name as seller_name,shipment_m.status,shipment_m.item_quantity,shipment_m.cartoon_quantity,shipment_m.date');
        // $this->db->from('shipment_m');
        // $this->db->join('items_m', 'items_m.id = shipment_m.item_sku');
        // $this->db->join('seller_m', 'seller_m.id = shipment_m.seller');
        // $this->db->join('cartoon_inventory', 'cartoon_inventory.id = shipment_m.cartoon_sku');
        // $query = $this->db->get();
   $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $query = $this->db->get('shipment_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find_by_slip_no1($slip_no) {
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get('shipment_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function find_by_slip_no($slip_no) {


        $fulfillment = 'Y';
        $deleted = 'N';

        $conditions = array(
            'fulfillment' => $fulfillment,
            'shipment_fm.slip_no' => $slip_no,
            'shipment_fm.deleted' => $deleted,
        );
        
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where($conditions);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,customer.name,shipment_fm.entrydate');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        $query = $this->db->get();


        if ($query->num_rows() > 0) {

            return $query->result();
        }
    }

    public function find_by_seller($id=null) {

        $fulfillment = 'Y';
        $deleted = 'N';

        $conditions = array(
            'shipment_fm.cust_id' => $id,
            'shipment_fm.fulfillment' => $fulfillment,
            'shipment_fm.deleted' => $deleted,
        );
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where($conditions);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,customer.name,shipment_fm.entrydate');
        $this->db->from('shipment_fm');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        $this->db->group_by('shipment_fm.slip_no');
        

        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
          //echo $this->db->last_query(); die;

        


        

            return $query->result();
        
    }

    public function bulk_update($awb_no, $comment, $status, $cust_id) {
        $user_id = $this->session->userdata('user_details')['user_id'];

        $this->db->where('id', $status);
        $query = $this->db->get('status_main_cat_fm');
        if ($query->num_rows() > 0) {

            $status_detail = $query->result()[0];
        }

        $data = array(
            'status' => $status,
            'comment' => $comment
        );



        $this->db->where('awb_no', $awb_no);
        $query = $this->db->update('shipment_m', $data);


        $data1 = array(
            'new_status' => $status_detail->main_status,
            'comment' => $comment,
            'user_type' => 'fulfillment',
            'user_id' => $user_id,
            'deleted' => 'N',
            'entry_date' => date("Y/m/d h:i:sa"),
            'pickup_date' => date("Y/m/d h:i:sa"),
            'pickup_time' => date("h:i:sa"),
            'slip_no' => $awb_no,
            'code' => $status_detail->code
        );

        $query1 = $this->db->insert('status_fm', $data1);

        $data2 = array(
            'code' => $status_detail->code,
            'status_comment' => $status_detail->main_status,
            'delivered' => $status_detail->id
        );


        $this->db->where('slip_no', $awb_no);
        $query2 = $this->db->update('shipment_fm', $data2);

        $querylast;

        if ($status == 19) {

            $seller_details;
            $items;
            $item_sku;
            $previous_quantity;

            $this->db->where('id', $cust_id);
            $query3 = $this->db->get('customer');

            if ($query3->num_rows() != 0) {
                $seller_details = $query3->result();
            }
            $seller_id = $seller_details[0]->seller_id;

            $this->db->where('slip_no', $awb_no);
            $query4 = $this->db->get('diamention_fm');

            if ($query4->num_rows() > 0) {
                $items = $query4->result();
            }




            for ($i = 0; $i < count($items); $i++) {

                $this->db->where('sku', $items[$i]->sku);
                $query5 = $this->db->get('items_m');

                if ($query5->num_rows() > 0) {
                    $item_sku = $query5->result();
                }

                $details_of_shipment = array(
                    'seller_id' => $seller_id,
                    'item_sku' => $item_sku[0]->id
                );



                $this->db->where($details_of_shipment);
                $query6 = $this->db->get('item_inventory');



                if ($query6->num_rows() > 0) {
                    $previous_quantity = $query6->result();
                }



                $add_in_inventory = array(
                    'quantity' => $previous_quantity[0]->quantity + $items[0]->piece,
                );

                $this->db->where($details_of_shipment);
                $querylast = $this->db->update('item_inventory', $add_in_inventory);
            }
        }

        if ($query && $query1 && $query2 && $querylast) {
            return 1;
        } else {
            return $awb_no;
        }
    }

    public function status_update($data) {

//        
//        $query1=$this->db->insert('status_fm',$data);
//
//        $data2=array(
//          'code'=>$status_detail->code,
//          'status_comment'=>$status_detail->main_status,
//          'delivered'=>$status_detail->id
//        );
//
//       
//       $this->db->where_('slip_no',$awb_no);
//       $query2=$this->db->update('shipment_fm',$data2);
    }

    public function shipmetsInAwb($awb=array()) {
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where_in('slip_no', $awb);

        $query = $this->db->get();

        // return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = array();
            $data['count'] = 0;
            return $data;
        }
    }
     public function shipmetsInAwb_picklist($awb=array()) {
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where_in('slip_no', $awb);

        $query = $this->db->get();

        // return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->row_array();
            $data['count'] = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = array();
            $data['count'] = 0;
            return $data;
        }
    }

    public function shipmetsInAwbAll($awb) {
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select('*');
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');


       // if (!empty($awb)) {
          //  $awb = array_filter($awb);

            $this->db->where_in('slip_no', $awb);
        //}


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = array();
            $data['count'] = 0;
            return $data;
        }
    }
    
    public function shipmetsInAwbAll_dishpacth($awb) {
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select('*');
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.code', 'PK');
        
        


       // if (!empty($awb)) {
          //  $awb = array_filter($awb);

            $this->db->where_in('slip_no', $awb);
        //}


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = array();
            $data['count'] = 0;
            return $data;
        }
    }

    public function get_deducted_shelve_no_details($slip_no) {

        $this->db->select('*');
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('diamention_fm');
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get();
        return $query->result_array();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function GetDiamationDetailsBYslipNo($slip_no = null) {
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('diamention_fm.id,diamention_fm.sku,diamention_fm.cod,diamention_fm.piece,items_m.id as sku_id');
        $this->db->from('diamention_fm');
        $this->db->join('items_m', 'items_m.sku = diamention_fm.sku','LEFT');
        $this->db->where('diamention_fm.slip_no', $slip_no);
        $query = $this->db->get();
        return $query->result_array();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function get_deducted_shelve_no($slip_no) {

        $this->db->select('deducted_shelve,sku,cod');
        $this->db->from('diamention_fm');
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get();
        return $query->result();


        //$this->db->order_by('shipment.id','ASC');
    }
     public function GetpicklistGenrateSkuDetails($slip_no) {

        $this->db->select('sku,piece,cod');
        $this->db->from('diamention_fm');
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get();
        return $query->result();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function getstocklocationDetails($stock_location) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('item_inventory');
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('stock_location', $stock_location);
        $query = $this->db->get();
        return $query->result();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function filter_orderGen($data = array()) {
       // print_r($data);
    if(!empty($data['sort_limit']))
        {
          $LimitArr= explode('-', $data['sort_limit']); 
          $limit=$LimitArr[1];
          //$start=$LimitArr[0];
        }
        else
        {
        $page_no;
        $limit = 100;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        }
        
        if($data['sort_list']=='NO')
        {
            $this->db->order_by('shipment.id', 'desc'); 
        }
        else if($data['sort_list']=='OLD')
        {
             $this->db->order_by('shipment.id', 'asc');
            
        }
        else if($data['sort_list']=='OBD')
        {
             $this->db->order_by('shipment.entrydate');
        }
        else
        {
             $this->db->order_by('shipment.id', 'desc');
        }
        

        // if ($this->session->userdata('user_details')['user_type'] != 1) {
        //     $this->db->where('shipment.wh_id', $this->session->userdata('user_details')['wh_id']);
        // }
        if ($data['s_type'] == 'AWB')
            $awb = $data['s_type_val'];
        if ($data['s_type'] == 'SKU')
            $sku = $data['s_type_val'];
        if ($data['s_type'] == 'REF')
            $refno = $data['s_type_val'];
        if ($data['s_type'] == 'MOBL')
            $mobileno = $data['s_type_val'];
        $fulfillment = 'Y';
        $deleted = 'N';
        //$this->db->where('shipment.fulfillment', $fulfillment);
        $this->db->where('shipment.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment.deleted', $deleted);
        $this->db->where('diamention.deleted', $deleted);
        $this->db->select('shipment.id,shipment.frwd_company_awb,shipment.service_id,shipment.booking_id,shipment.slip_no,diamention.sku,status_main_cat.main_status,diamention.piece,diamention.wieght as wt,diamention.description,diamention.cod,customer.name,customer.id as seller_id,customer.uniqueid,shipment.entrydate,shipment.origin,shipment.destination,shipment.reciever_name,shipment.reciever_address,shipment.reciever_phone,`shipment.sender_name`, `shipment.sender_address`, `shipment.sender_phone`, `shipment.sender_email`, `shipment.mode`, `shipment.total_cod_amt`,shipment.weight,shipment.pieces,shipment.cust_id,shipment.shippers_ac_no,shipment.wh_id,shipment.back_reasons,diamention.wh_id as whid,diamention.id as d_id,diamention.free_sku,shipment.total_cod_amt,shipment.frwd_company_id,shipment.order_type');
        $this->db->from('shipment_fm as shipment');
        $this->db->join('status_main_cat_fm as status_main_cat', 'status_main_cat.id=shipment.delivered');
        $this->db->join('diamention_fm as diamention', '  shipment.slip_no=diamention.slip_no','RIGHT');
        $this->db->join('customer', 'customer.id=shipment.cust_id');

        if (!empty($data['exact'])) {
            $this->db->where('DATE(shipment.entrydate)', $data['exact']);
        }


        if (!empty($data['from']) && !empty($data['to'])) {
            $where = "DATE(shipment.entrydate) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";


            $this->db->where($where);
        }
        $cc_id = $data['cc_id'];
        if (!empty($cc_id)) {
            $this->db->where_in('shipment.frwd_company_id', $cc_id);
        }



        $this->db->where_in('shipment.delivered', 11);



        if (!empty($awb)) {
            $this->db->where('shipment.slip_no', $awb);
        }
         if (!empty($refno)) {
            $this->db->where('shipment.booking_id', $refno)
            ->or_where('shipment.frwd_company_awb',$refno);
        }
         if (!empty($mobileno)) {
            $this->db->where('shipment.reciever_phone', $mobileno);
        }

        if (!empty($sku)) {
            $this->db->where('diamention.sku', $sku);
        }

         if (!empty($data['wh_id'])) {
             $this->db->where('shipment.wh_id', $data['wh_id']);
        }
        if (!empty($data['seller'])) {
            $seller = array_filter($data['seller']);
            $this->db->where_in('shipment.cust_id', $data['seller']);
        }



       


        // $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

      // echo $this->db->last_query(); 
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount_gen($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function shipmCount_gen($data) {


        // if ($this->session->userdata('user_details')['user_type'] != 1) {
        //     $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        // }

        if (!empty($data['cc_id'])) {
            $cc_id = array_filter($data['cc_id']);

            $this->db->where_in('shipment_fm.frwd_company_id', $data['cc_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);

        
        
        if ($data['s_type'] == 'AWB')
            $awb = $data['s_type_val'];
        if ($data['s_type'] == 'SKU')
            $sku = $data['s_type_val'];
        if ($data['s_type'] == 'REF')
            $refno = $data['s_type_val'];
        if ($data['s_type'] == 'MOBL')
            $mobileno = $data['s_type_val'];
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');


        if (!empty($data['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $data['exact']);
        }


        if (!empty($data['from']) && !empty($data['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";


            $this->db->where($where);
        }


 if (!empty($data['wh_id'])) {
             $this->db->where('shipment_fm.wh_id', $data['wh_id']);
        }
        if (!empty($data['status'])) {
            $this->db->where_in('shipment_fm.delivered', 11);
        }

        if (!empty($data['destination'])) {
            $destination = array_filter($data['destination']);

            $this->db->where_in('shipment_fm.destination', $data['destination']);
        }

        if (!empty($data['awb'])) {
            $this->db->where('shipment_fm.slip_no', $data['awb']);
        }
        
        if (!empty($data['refno'])) {
            $this->db->where('shipment_fm.booking_id', $data['refno']);
        }
         if (!empty($data['mobileno'])) {
            $this->db->where('shipment_fm.reciever_phone', $data['mobileno']);
        }

        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */

        if (!empty($data['seller'])) {
            $seller = array_filter($data['seller']);
            $this->db->where_in('shipment_fm.cust_id', $data['seller']);
        }

        if (!empty($data['booking_id'])) {

            $this->db->where('shipment_fm.booking_id', $data['booking_id']);
        }


        $this->db->order_by('shipment_fm.id', 'desc');

        $query = $this->db->get();

     //   echo $this->db->last_query(); 
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function filter_backorder($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment.super_id', $this->session->userdata('user_details')['super_id']);
        //$this->db->where('shipment.fulfillment', $fulfillment);
        $this->db->where('shipment.deleted', $deleted);
        $this->db->select('shipment.id,shipment.service_id,shipment.booking_id,shipment.slip_no,diamention.sku,status_main_cat.main_status,diamention.piece,diamention.wieght as wt,diamention.description,diamention.cod,customer.name,customer.seller_id,customer.uniqueid,shipment.entrydate,shipment.origin,shipment.destination,shipment.reciever_name,shipment.reciever_address,shipment.reciever_phone,`shipment.sender_name`, `shipment.sender_address`, `shipment.sender_phone`, `shipment.sender_email`, `shipment.mode`, `shipment.total_cod_amt`,shipment.weight,shipment.pieces,shipment.cust_id,shipment.shippers_ac_no,shipment.wh_id,shipment.back_reasons,shipment.total_cod_amt');
        $this->db->from('shipment_fm as shipment');
        $this->db->join('status_main_cat_fm as status_main_cat', 'status_main_cat.id=shipment.delivered');
        $this->db->join('diamention_fm as diamention', 'diamention.slip_no = shipment.slip_no');
        $this->db->join('customer', 'customer.id=shipment.cust_id');


        $this->db->where('shipment.backorder', 1);
        if (!empty($exact)) {
            $this->db->where('DATE(shipment.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        if (!empty($delivered)) {

            if (array_key_exists(0, $delivered)) {
                $delivered = array_filter(0, $delivered);
            }


            $this->db->where_in('shipment.delivered', $delivered);
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment.slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('diamention.sku', $sku);
        }

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment.cust_id', $seller);
        }



        $this->db->order_by('shipment.id', 'desc');


        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, 'back');
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter($awb= null, $sku= null, $delivered= null, $seller= null, $to= null, $from= null, $exact= null, $page_no= null, $destination= null, $booking_id= null, $cc_id = null,$is_menifest = null,$refsno=null,$mobileno=null,$wh_id=null,$data=array()) {

        if(!empty($data['sort_limit']))
        {
          $LimitArr= explode('-', $data['sort_limit']); 
          $limit=$LimitArr[1];
          //$start=$LimitArr[0];
        }
        else
        {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        }
        
        if($data['sort_list']=='NO')
        {
            $this->db->order_by('shipment_fm.id', 'desc'); 
        }
        else if($data['sort_list']=='OLD')
        {
             $this->db->order_by('shipment_fm.id', 'asc');
            
        }
        else if($data['sort_list']=='OBD')
        {
             $this->db->order_by('shipment_fm.entrydate');
        }
        else
        {
             $this->db->order_by('shipment_fm.id', 'desc');
        }
        /* if(!empty($delivered)){
          $this->db->where('shipment_fm.delivered', $delivered);
          } */

        $fulfillment = 'Y';
        $deleted = 'N';

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        if(!empty($data['status_o'])){
            $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,shipment_fm.frwd_company_id,shipment_fm.wh_id,shipment_fm.frwd_company_label,shipment_fm.frwd_date,shipment_fm.is_menifest,shipment_fm.code,diamention_fm.free_sku,shipment_fm.total_cod_amt,shipment_fm.no_of_attempt,shipment_fm.3pl_pickup_date,shipment_fm.3pl_close_date,  IFNULL(DATEDIFF(3pl_close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  ) AS transaction_days,shipment_fm.delivered ');    
        }else{
            $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,shipment_fm.frwd_company_id,shipment_fm.wh_id,shipment_fm.frwd_company_label,shipment_fm.frwd_date,shipment_fm.is_menifest,shipment_fm.code,diamention_fm.free_sku,shipment_fm.total_cod_amt,shipment_fm.no_of_attempt,shipment_fm.3pl_pickup_date,shipment_fm.3pl_close_date, IFNULL(DATEDIFF(3pl_close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP(), 3pl_pickup_date) ) AS transaction_days ,shipment_fm.delivered ');
        }
        
        
        $this->db->from('shipment_fm');
        if(empty($data['status_o'])){
            $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        }
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');


        $this->db->where('shipment_fm.backorder', 0);
        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }
        $this->db->group_by('diamention_fm.slip_no');

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }

        // $this->db->where('shipment_fm.slip_no','FST5116125078');

        // echo $delivered;
        //        if (!empty($delivered)) {
        //
        //            // print_r($delivered);
        //            if ($delivered == '1' || $delivered == '4' || $delivered == '5' || $delivered == '7' || $delivered == '8') {
        //                if (array_key_exists(0, $delivered))
        //                    $delivered = array_filter(0, $delivered);
        //            } else
        //                $delivered = array_filter($delivered);
        //
        //            $this->db->where_in('shipment_fm.delivered', $delivered);
        //        }

        if (!empty($delivered) || !empty($data['status_o'])) {

            // print_r($delivered);
            if ($delivered == '1' || $delivered == '4' || $delivered == '5' || $delivered == '7' || $delivered == '8') {
                if (array_key_exists(0, $delivered))
                    $delivered = array_filter(0, $delivered);
            } else
                $delivered = array_filter($delivered);
            
            if(is_numeric($delivered)){
                $this->db->where_in('shipment_fm.delivered', $delivered);
            }else{
                if(isset($data['status_o']) & !empty($data['status_o'])){
                    $o_status = $data['status_o'];
                    if(!empty($delivered)){
                    $delivered = array_merge($o_status,$delivered);
                    }else{
                        $delivered = $o_status;
                }
                
                }
                $this->db->where_in('shipment_fm.code', $delivered);
            }
            
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }
        
        if (!empty($data['order_type'])) {
           if($data['order_type']=='B2B')
             $this->db->where('shipment_fm.order_type', $data['order_type']);
           else
             $this->db->where('shipment_fm.order_type','');  
        }
        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
         if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
        if (!empty($refsno)) {
            $this->db->where('shipment.booking_id', $refsno)
            ->or_where('shipment_fm.frwd_company_awb',$refsno);
        }
         if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }

        if ((!empty($sku)) || (!empty($data['sku']))) {    
			$sku=$data['sku']; 
            $this->db->where('diamention_fm.sku', $sku);
        }
        if (($is_menifest == 0 || $is_menifest == 1) && $is_menifest != null) {

            $this->db->where('shipment_fm.is_menifest', $is_menifest);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }
		
		if (!empty($data['mode'])) {
            $this->db->where('shipment_fm.mode', $data['mode']);   
        }
		
		if (!empty($data['piece'])) {
            $this->db->where('diamention_fm.piece', $data['piece']);   
        }
		
		if (!empty($data['cod'])) {
            $this->db->where('diamention_fm.cod', $data['cod']);   
        }
		
            //$this->db->where('shipment_fm.deleted', 'N');   
		

        if (!empty($seller)) {
            if (sizeof($seller) > 0) {
                $seller = array_filter($seller);
                $this->db->where_in('shipment_fm.cust_id', $seller);
            }
        }



         $this->db->order_by('shipment_fm.id', 'desc');

        // $tempdb = clone $this->db;
        //now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

       //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {


            //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, '', $cc_id,$refsno,$mobileno,$wh_id,$data['order_type']);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

        public function filterViewMApping($page_no = null, $cc_id=null, $data= array()){
        if(!empty($data['sort_limit'])){
            $LimitArr= explode('-', $data['sort_limit']); 
            $limit=$LimitArr[1];
        }else{
            $page_no;
            $limit = 100;
            if(empty($page_no)) {
                $start = 0;
            } else {
                $start = ($page_no - 1) * $limit;
            }
        }
        
        $this->db->where('shipment_mapping_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('shipment_mapping_fm');
        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);
            $this->db->where_in('shipment_mapping_fm.cc_id', $cc_id);
        }
        
        $this->db->order_by('shipment_mapping_fm.id', 'desc');
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->mappingCount($cc_id);
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
        
    }
    
    public function mappingCount($cc_id=null){
        
        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);

            $this->db->where_in('shipment_mapping_fm.cc_id', $cc_id);
        }
        $this->db->select('count(1) as cnt');
        $this->db->where('shipment_mapping_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->from('shipment_mapping_fm');
        $query = $this->db->get();
        $data = $query->row_array();
        return $data['cnt'];
        // return $page_no.$this->db->last_query();
    }
    
    public function checkMappingCompany($data){
        
        $this->db->select('count(1) as cnt');
        $this->db->from('shipment_mapping_fm');
        $this->db->where('cc_id',$data['cc_id']);
        $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
        $query = $this->db->get();
        
        $data = $query->row_array();
        return $data['cnt'];
    }
    public function saveMappingData($data){
        $data_array = array('cc_id'=>$data['cc_id'],'map_data'=>$data['map_data'],'super_id'=>$this->session->userdata('user_details')['super_id']);
        $this->db->insert('shipment_mapping_fm', $data_array);
        //echo $this->db->last_query();   
    }
    
    public function getMappingData($id){
        $this->db->select('*');
        $this->db->from('shipment_mapping_fm');
        $this->db->where('id',$id);
        $this->db->where('super_id',$this->session->userdata('user_details')['super_id']);
        $query = $this->db->get();
        
        $data = $query->row_array();
        return $data;
        
    }
    
    public function updateMappingData($data = array(),$id){
        $this->db->update('shipment_mapping_fm', $data, array('id' => $id));
    }
    
  
    public function filterViewReverse($awb= null, $sku= null, $delivered= null, $seller= null, $to= null, $from= null, $exact= null, $page_no= null, $destination= null, $booking_id= null, $cc_id = null,$is_menifest = null,$refsno=null,$mobileno=null,$wh_id=null,$data=array()) {

        if(!empty($data['sort_limit']))
        {
          $LimitArr= explode('-', $data['sort_limit']); 
          $limit=$LimitArr[1];
          //$start=$LimitArr[0];
        }
        else
        {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        }
        
        if($data['sort_list']=='NO')
        {
            $this->db->order_by('shipment_fm.id', 'desc'); 
        }
        else if($data['sort_list']=='OLD')
        {
             $this->db->order_by('shipment_fm.id', 'asc');
            
        }
        else if($data['sort_list']=='OBD')
        {
             $this->db->order_by('shipment_fm.entrydate');
        }
        else
        {
             $this->db->order_by('shipment_fm.id', 'desc');
        }
        /* if(!empty($delivered)){
          $this->db->where('shipment_fm.delivered', $delivered);
          } */

        $fulfillment = 'Y';
        $deleted = 'N';
        $reverse_forwarded = 1;

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->where('shipment_fm.reverse_forwarded', $reverse_forwarded);
        if(!empty($data['status_o'])){
            $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,shipment_fm.frwd_company_id,shipment_fm.wh_id,shipment_fm.frwd_company_label,shipment_fm.frwd_date,shipment_fm.is_menifest,shipment_fm.code,diamention_fm.free_sku,shipment_fm.total_cod_amt,shipment_fm.no_of_attempt,shipment_fm.3pl_pickup_date,shipment_fm.3pl_close_date,  IFNULL(DATEDIFF(3pl_close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP(), 3pl_pickup_date)  )AS transaction_days,shipment_fm.delivered ');    
        }else{
            $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,shipment_fm.frwd_company_id,shipment_fm.wh_id,shipment_fm.frwd_company_label,shipment_fm.frwd_date,shipment_fm.is_menifest,shipment_fm.code,diamention_fm.free_sku,shipment_fm.total_cod_amt,shipment_fm.no_of_attempt,shipment_fm.3pl_pickup_date,shipment_fm.3pl_close_date,  IFNULL(DATEDIFF(3pl_close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP(), 3pl_pickup_date) ) AS transaction_days,shipment_fm.delivered ');
        }
        
        
        $this->db->from('shipment_fm');
        if(empty($data['status_o'])){
            $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        }
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');


        $this->db->where('shipment_fm.backorder', 0);
        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }
        $this->db->group_by('diamention_fm.slip_no');

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }


        if (!empty($delivered) || !empty($data['status_o'])) {

            // print_r($delivered);
            if ($delivered == '1' || $delivered == '4' || $delivered == '5' || $delivered == '7' || $delivered == '8') {
                if (array_key_exists(0, $delivered))
                    $delivered = array_filter(0, $delivered);
            } else
                $delivered = array_filter($delivered);
            
            if(is_numeric($delivered)){
                $this->db->where_in('shipment_fm.delivered', $delivered);
            }else{
                if(isset($data['status_o']) & !empty($data['status_o'])){
                    $o_status = $data['status_o'];
                    if(!empty($delivered)){
                    $delivered = array_merge($o_status,$delivered);
                    }else{
                        $delivered = $o_status;
                }
                
                }
                $this->db->where_in('shipment_fm.code', $delivered);
            }
            
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }
        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
         if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
        if (!empty($refsno)) {
            $this->db->where('shipment.booking_id', $refsno)
            ->or_where('shipment_fm.frwd_company_awb',$refsno);
        }
         if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }

        if ((!empty($sku)) || (!empty($data['sku']))) {    
			$sku=$data['sku']; 
            $this->db->where('diamention_fm.sku', $sku);
        }
        if (($is_menifest == 0 || $is_menifest == 1) && $is_menifest != null) {

            $this->db->where('shipment_fm.is_menifest', $is_menifest);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }
		
		if (!empty($data['mode'])) {
            $this->db->where('shipment_fm.mode', $data['mode']);   
        }
		
		if (!empty($data['piece'])) {
            $this->db->where('diamention_fm.piece', $data['piece']);   
        }
		
		if (!empty($data['cod'])) {
            $this->db->where('diamention_fm.cod', $data['cod']);   
        }
		
            //$this->db->where('shipment_fm.deleted', 'N');   
		

        if (!empty($seller)) {
            if (sizeof($seller) > 0) {
                $seller = array_filter($seller);
                $this->db->where_in('shipment_fm.cust_id', $seller);
            }
        }



         $this->db->order_by('shipment_fm.id', 'desc');

        // $tempdb = clone $this->db;
        //now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

      // echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {


            //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCountReverse($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, '', $cc_id,$refsno,$mobileno,$wh_id);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter_orderCreated($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $limit = null,$refsno=null,$mobileno=null,$wh_id=null,$data=array(), $cc_id = null) {
        if(!empty($data['sort_limit']))
        {
          $LimitArr= explode('-', $data['sort_limit']); 
          $limit=$LimitArr[1];
         // $start=$LimitArr[0];
        }
        else
        {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        }
        /* if(!empty($delivered)){
          $this->db->where('shipment_fm.delivered', $delivered);
          } */
        
        if($data['sort_list']=='NO')
        {
            $this->db->order_by('shipment_fm.id', 'desc'); 
        }
        else if($data['sort_list']=='OLD')
        {
             $this->db->order_by('shipment_fm.id', 'asc');
            
        }
        else if($data['sort_list']=='OBD')
        {
             $this->db->order_by('shipment_fm.entrydate');
        }
        else
        {
             $this->db->order_by('shipment_fm.id', 'desc');
        }

        $fulfillment = 'Y';
        $deleted = 'N';

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,shipment_fm.frwd_company_id,shipment_fm.wh_id,frwd_company_label,diamention_fm.free_sku');


        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');


        $this->db->where('shipment_fm.backorder', 0);
        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }
       
        $cc_id = $data['cc_id'];

        if (!empty($cc_id)) {
            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }
        //$this->db->group_by('diamention_fm.slip_no');

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        // echo $delivered;
        if (!empty($delivered)) {

            // print_r($delivered);
            if ($delivered == '1' || $delivered == '4' || $delivered == '5') {
                if (array_key_exists(0, $delivered))
                    $delivered = array_filter(0, $delivered);
            } else
                $delivered = array_filter($delivered);
            if ($delivered == 1) {
                $this->db->where_in('shipment_fm.delivered', array(1, 10));
            } else
                $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
         

        if (!empty($refsno)) {
            $this->db->where('shipment.booking_id', $refsno)
            ->or_where('shipment_fm.frwd_company_awb',$refsno);
        }



          if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
          if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }

        if (!empty($sku)) {

            $this->db->where('diamention_fm.sku', $sku);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }
        //echo $this->db->last_query(); die;

        if (!empty($seller)) {
            if (sizeof($seller) > 0) {
                $seller = array_filter($seller);
                $this->db->where_in('shipment_fm.cust_id', $seller);
            }
        }



       // $this->db->order_by('shipment_fm.id', 'asc');

        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;                      

        if ($query->num_rows() > 0) {


            //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount_created($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id,$refsno,$mobileno,$wh_id);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function shipmetsInAwbAll_return($awb = null) {
        // echo $awb;
        //print_r($awb); die;
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');


        //if (!empty($awb)) {
            //  $awb= array_filter($awb);

            $this->db->where('slip_no', $awb);
       // }


        $query = $this->db->get();

        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter_outbound($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination) {
        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.stocklcount,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');




        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        if (!empty($delivered)) {

            if (array_key_exists(0, $delivered)) {
                $delivered = array_filter(0, $delivered);
            }


            $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('diamention_fm.sku', $sku);
        }

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment_fm.cust_id', $seller);
        }



        $this->db->order_by('shipment_fm.id', 'desc');

        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, 1);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetallexpredataQuery($seller_id = null, $sku_no = null) {

        $this->db->where('item_sku', getallitemskubyid($sku_no));
        $this->db->where('seller_id', $seller_id);
        $this->db->where("shelve_no!=''");
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        //$this->db->where("item_inventory.expity_date!='0000-00-00'");
        $this->db->select('shelve_no,stock_location,expity_date');
        $this->db->from('item_inventory');
        //$this->db->limit(1);
        //$this->db->join('items_m','items_m.id=item_inventory.item_sku');
        $query = $this->db->get();
        //echo $this->db->last_query();   
        if ($query->num_rows() > 0) {

            return $query->result_array();
        } else
            return array();
    }

    public function shipmCount_created($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id,$refsno,$mobileno,$wh_id) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');


        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }


        if ($backorder == 'back')
            $this->db->where('shipment_fm.backorder', 1);
        else {


            $this->db->where('shipment_fm.backorder', 0);
            // $this->db->where('shipment.reverse_pickup', 0);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        if (!empty($delivered)) {
            if (array_key_exists(0, $delivered)) {
                $delivered = array_filter(0, $delivered);
            }


            $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
        
         if (!empty($refsno)) {
            $this->db->where('shipment_fm.booking_id', $refsno);
        }
          if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
          if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }


        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */
        $this->db->group_by('shipment_fm.slip_no');

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment_fm.cust_id', $seller);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }


        $this->db->order_by('shipment_fm.id', 'desc');

        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $query->num_rows();
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function shipmCountReverse($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $backorder = null, $cc_id = null,$refsno=null,$mobileno=null,$wh_id=null) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }

        $fulfillment = 'Y';
        $deleted = 'N';
        $reverse_forwarded = 1 ; 
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->where('shipment_fm.reverse_forwarded', $reverse_forwarded);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
      //  $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
      //  $this->db->join('customer', 'customer.id=shipment_fm.cust_id');


        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }


        if ($backorder == 'back')
            $this->db->where('shipment_fm.backorder', 1);
        else {


            $this->db->where('shipment_fm.backorder', 0);
            // $this->db->where('shipment.reverse_pickup', 0);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        if (!empty($delivered)) {
            if (array_key_exists(0, $delivered)) {
                $delivered = array_filter(0, $delivered);
            }


            $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
        
        if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
         if (!empty($refsno)) {
            $this->db->where('shipment_fm.booking_id', $refsno);
        }
         if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }

        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment_fm.cust_id', $seller);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }


        $this->db->order_by('shipment_fm.id', 'desc');

        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $backorder = null, $cc_id = null,$refsno=null,$mobileno=null,$wh_id=null,$order_type=null) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }

        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
      //  $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
      //  $this->db->join('customer', 'customer.id=shipment_fm.cust_id');


        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }


        if ($backorder == 'back')
            $this->db->where('shipment_fm.backorder', 1);
        else {


            $this->db->where('shipment_fm.backorder', 0);
            // $this->db->where('shipment.reverse_pickup', 0);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }

        if (!empty($order_type)) {
           if($order_type=='B2B')
             $this->db->where('shipment_fm.order_type', $order_type);
           else
             $this->db->where('shipment_fm.order_type','');  
        }

        if (!empty($delivered)) {
            if (array_key_exists(0, $delivered)) {
                $delivered = array_filter(0, $delivered);
            }


            $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
        
        if (!empty($wh_id)) {
            $this->db->where('shipment_fm.wh_id', $wh_id);
        }
         if (!empty($refsno)) {
            $this->db->where('shipment_fm.booking_id', $refsno);
        }
         if (!empty($mobileno)) {
            $this->db->where('shipment_fm.reciever_phone', $mobileno);
        }

        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment_fm.cust_id', $seller);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }


        $this->db->order_by('shipment_fm.id', 'desc');

        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function GetupdatedeletedInventory($data = array(), $slip_no = null) {
        $this->db->query("update item_inventory set quantity=quantity+'" . $data['piece'] . "' where item_sku= '" . $data['itmSku'] . "' and super_id='" . $this->session->userdata('user_details')['super_id'] . "' order by id asc limit 1");
        $this->db->query("update shipment_fm set deleted='Y',code='C',delivered='9' where slip_no='" . $slip_no . "' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
        $this->db->query("update status_fm set deleted='Y' where slip_no='" . $slip_no . "' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
        $this->db->query("update diamention_fm set deleted='Y' where slip_no='" . $slip_no . "' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'");
    }

    public function GetupdatedeletedInventory_LM($data = array(), $slip_no = null) {
        $this->db->query("update item_inventory set quantity=quantity+'" . $data['piece'] . "' where item_sku= '" . $data['itmSku'] . "' and super_id='" . $this->session->userdata('user_details')['super_id'] . "' order by id asc limit 1");
    }

    public function GetshipmentUpdate_forward(array $data, $awb = null) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('shipment_fm', $data, array('slip_no' => $awb));
    }

    public function GetstatuInsert_forward(array $data) {
        $this->db->insert('status_fm', $data);
    }

    public function Getalltotalchartmonth($year=null) {
        if(!empty($year))
        {
          $condition.="	and YEAR(entrydate)='$year'";  
        }
        else
        {
          $condition.="	and YEAR(entrydate)='" . date('Y') . "'";     
        }
        $sql = "SELECT MONTHNAME(entrydate) AS name,COUNT(DISTINCT id) as y FROM shipment_fm where status='Y' and deleted='N' and super_id='" . $this->session->userdata('user_details')['super_id'] . "' $condition  GROUP BY name order by id asc";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function GetForwardToclientShipDataQry($slip_no = null) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('slip_no', trim($slip_no));
        $this->db->where_in('delivered', array(4));
        $query = $this->db->get();
        $results = $query->result_array();
        return $results;
    }
    
    public function GetmatchmanifestData($cc_id=null,$slip_array = array()) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('status', 'Y');
        $this->db->where('frwd_company_id', $cc_id);
        $this->db->where('is_menifest', 0);
         $this->db->where('deleted', 'N');
        $this->db->where_in('slip_no', $slip_array);
        $this->db->where_in('delivered', array(5));
        $query = $this->db->get();
        $results = $query->result_array();
        return $results;
    }
     public function GetmatchmanifestData_companydata($slip_array = array()) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('frwd_company_id');
        $this->db->from('shipment_fm');
        $this->db->where('status', 'Y');
        $this->db->where('is_menifest', 0);
         $this->db->where('deleted', 'N');
        $this->db->where_in('slip_no', $slip_array);
        $this->db->where_in('delivered', array(5));
        $this->db->group_by('frwd_company_id');
        $query = $this->db->get();
        $results = $query->result_array();
        return $results;
    }

    public function Getskudetails_forward($slip_no = null) {
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('deducted_shelve,sku,description,piece');
        $this->db->from('diamention_fm');
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get();
        return $query->result_array();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function Getskudetails_ship($slip_no = null) {
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('deducted_shelve,sku,description,piece,cod,free_sku');
        $this->db->from('diamention_fm');
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('slip_no', $slip_no);
        $query = $this->db->get();
        return $query->result_array();


        //$this->db->order_by('shipment.id','ASC');
    }

    public function stocklocation_details($slip_no = null) {
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('diamention_fm.deducted_shelve,diamention_fm.sku,diamention_fm.description,diamention_fm.piece,diamention_fm.cod,diamention_fm.free_sku,shipment_fm.cust_id,shipment_fm.slip_no');
        $this->db->from('diamention_fm');
        $this->db->join('shipment_fm', 'shipment_fm.slip_no=diamention_fm.slip_no', 'LEFT' );
        $this->db->where('diamention_fm.slip_no', $slip_no);
        $this->db->where('diamention_fm.deleted', 'N');
        $query = $this->db->get();

        return $query->result_array();
    }


    public function skuSize($skuno = null) {

        // $qwry = "select  from item_inventory Join items_m on items_m.id = item_inventory.item_sku where item_inventory.seller_id = ".$seller_id."  and item_inventory.super_id ='".$this->session->userdata('user_details')['super_id']."' and item_inventory.shelve_no= '".$shelve_no."' and items_m.sku = '".$skuno."' and items_m.sku_size > (item_inventory.quantity+1)";



        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('items_m.sku_size');
        $this->db->from('items_m');
      
        $this->db->where('items_m.sku', $skuno);
       

        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
           $result=  $query->result_array();
            return  $result[0]['sku_size'];
        }

    }

    public function skuId($skuno = null) {

        // $qwry = "select  from item_inventory Join items_m on items_m.id = item_inventory.item_sku where item_inventory.seller_id = ".$seller_id."  and item_inventory.super_id ='".$this->session->userdata('user_details')['super_id']."' and item_inventory.shelve_no= '".$shelve_no."' and items_m.sku = '".$skuno."' and items_m.sku_size > (item_inventory.quantity+1)";



        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('items_m.id');
        $this->db->from('items_m');
      
        $this->db->where('items_m.sku', $skuno);
       

        $query = $this->db->get();
    
        if ($query->num_rows() > 0) {
           $result=  $query->result_array();
            return  $result[0]['id'];
        }

    }
    
    public function stockInventory($skuno = null,$shelve_no = null,$seller_id = null,$piece=0,$preId=array()) {

        // $qwry = "select  from item_inventory Join items_m on items_m.id = item_inventory.item_sku where item_inventory.seller_id = ".$seller_id."  and item_inventory.super_id ='".$this->session->userdata('user_details')['super_id']."' and item_inventory.shelve_no= '".$shelve_no."' and items_m.sku = '".$skuno."' and items_m.sku_size > (item_inventory.quantity+1)";


        if(!empty($preId))
        {
            $this->db->where_not_in('item_inventory.id', $preId);
        }

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.item_sku as sku_id,item_inventory.seller_id,items_m.sku, items_m.sku_size,item_inventory.stock_location,item_inventory.shelve_no,item_inventory.quantity, item_inventory.id,item_inventory.super_id');
        $this->db->from('item_inventory');
        $this->db->join('items_m','items_m.id = item_inventory.item_sku');
        $this->db->where('items_m.sku', $skuno);
        $this->db->where('items_m.sku_size >= (item_inventory.quantity+'.$piece.')');     
        $this->db->where('item_inventory.seller_id', $seller_id);
        if(!empty($shelve_no))
        $this->db->where('item_inventory.shelve_no', $shelve_no);
        $this->db->limit(1);
     
        $query = $this->db->get();
       //echo  $this->db->last_query(); 
      
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        else
        {

            $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('item_inventory.item_sku as sku_id,item_inventory.seller_id,items_m.sku, items_m.sku_size,item_inventory.stock_location,item_inventory.shelve_no,item_inventory.quantity, item_inventory.id,item_inventory.super_id');
            $this->db->from('item_inventory');
            $this->db->join('items_m','items_m.id = item_inventory.item_sku');
            $this->db->where('items_m.sku', $skuno);
            $this->db->where('items_m.sku_size >= (item_inventory.quantity+'.$piece.')');
            $this->db->where('item_inventory.seller_id', $seller_id);
            $this->db->limit(1);
            
            
            $query = $this->db->get();
           // echo  $this->db->last_query(); 
           
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }

        }
        

 
    }

    public function stockLocationFilterCount($stock_location, $seller_id, $page_no, $data = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('stock_location,');
        $this->db->from('stockLocation');

       
            $this->db->where('`stock_location` NOT IN (SELECT `stock_location` FROM `item_inventory`)', NULL, FALSE);
      
        if (!empty($seller_id)) {
            $seller_id = array_filter($seller_id);

            $this->db->where_in('seller_id', $seller_id);
        }

        if (!empty($stock_location)) {
            $this->db->where('stock_location', $stock_location);
        }

        //$this->db->group_by('pickupId');
        $this->db->order_by('stockLocation.id', 'ASC');


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function backoredrexcel($exportlimit) {
        $page_no;
        $limit = $exportlimit;
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        $fulfillment = 'Y';
        $deleted = 'N';
        //$this->db->where('shipment.fulfillment', $fulfillment);
        $this->db->where('shipment.deleted', $deleted);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('shipment.id,shipment.service_id,shipment.booking_id,shipment.slip_no,diamention.sku,status_main_cat.main_status,diamention.piece,diamention.wieght as wt,diamention.description,diamention.cod,customer.name,customer.seller_id,customer.uniqueid,shipment.entrydate,shipment.origin,shipment.destination,shipment.reciever_name,shipment.reciever_address,shipment.reciever_phone,`shipment.sender_name`, `shipment.sender_address`, `shipment.sender_phone`, `shipment.sender_email`, `shipment.mode`, `shipment.total_cod_amt`,shipment.weight,shipment.pieces,shipment.cust_id,shipment.shippers_ac_no');
        $this->db->from('shipment_fm as shipment');
        $this->db->join('status_main_cat_fm as status_main_cat', 'status_main_cat.id=shipment.delivered');
        $this->db->join('diamention_fm as diamention', 'diamention.slip_no = shipment.slip_no');
        $this->db->join('customer', 'customer.id=shipment.cust_id');


        $this->db->where('shipment.backorder', 1);
        if (!empty($exact)) {
            $this->db->where('DATE(shipment.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        if (!empty($delivered)) {

            if (array_key_exists(0, $delivered)) {
                $delivered = array_filter(0, $delivered);
            }


            $this->db->where_in('shipment.delivered', $delivered);
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment.slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('diamention.sku', $sku);
        }

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment.cust_id', $seller);
        }



        $this->db->order_by('shipment.id', 'asc');


        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            return $data;
        }
    }

    public function filterexcel1(array $filterArr) {
        $exportlimit = $filterArr['exportlimit'];
        $delivered = $filterArr['status'];
        $page_no;
        $limit = 2000;
        $start = $exportlimit - $limit;

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');



        if (!empty($filterArr['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $filterArr['exact']);
        }


        if (!empty($filterArr['from']) && !empty($filterArr['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $filterArr['from'] . "' AND '" . $filterArr['to'] . "'";


            $this->db->where($where);
        }




        if (!empty($delivered)) {

            // print_r($delivered);
            if ($delivered == '1' || $delivered == '4' || $delivered == '5') {
                if (array_key_exists(0, $delivered))
                    $delivered = array_filter(0, $delivered);
            } else
                $delivered = array_filter($delivered);

            $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($filterArr['destination'])) {
            $destination = array_filter($filterArr['destination']);

            $this->db->where_in('shipment_fm.destination', $filterArr['destination']);
        }

        if (!empty($filterArr['awb'])) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        if (!empty($filterArr['sku'])) {
            $this->db->where('diamention_fm.sku', $filterArr['sku']);
        }

        if (!empty($filterArr['seller'])) {
            $seller = array_filter($filterArr['seller']);
            $this->db->where_in('shipment_fm.cust_id', $filterArr['seller']);
        }



        $this->db->order_by('shipment_fm.id', 'desc');
        $this->db->limit($limit, $start);
        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();



        $query = $this->db->get();

        //echo  $this->db->last_query(); die;   
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            return $data;
        }
    }

    public function filterexcelshipment(array $filterArr) {
        $page_no;
        // print_r($exportlimit); die;
        $limit = 2000;
        $start = $filterArr['exportlimit'] - $limit;
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,status_main_cat_fm.main_status,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');

        if (!empty($filterArr['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $filterArr['exact']);
        }


        if (!empty($filterArr['from']) && !empty($filterArr['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $filterArr['from'] . "' AND '" . $filterArr['to'] . "'";


            $this->db->where($where);
        }




        if (!empty($filterArr['status'])) {
            $delivered = $filterArr['status'];
            // print_r($delivered);
            if ($delivered == '1' || $delivered == '4' || $delivered == '5') {
                if (array_key_exists(0, $delivered))
                    $delivered = array_filter(0, $delivered);
            } else
                $delivered = array_filter($delivered);

            $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($filterArr['destination'])) {
            $destination = array_filter($filterArr['destination']);

            $this->db->where_in('shipment_fm.destination', $filterArr['destination']);
        }

        if (!empty($filterArr['awb'])) {
            $this->db->where('shipment_fm.slip_no', $filterArr['awb']);
        }

        if (!empty($filterArr['sku'])) {
            //  $this->db->where('diamention_fm.sku',$filterArr['sku']);
        }

        if (!empty($filterArr['seller'])) {
            $seller = array_filter($filterArr['seller']);
            $this->db->where_in('shipment_fm.cust_id', $filterArr['seller']);
        }



        $this->db->order_by('shipment_fm.id', 'desc');
        $this->db->limit($limit, $start);

        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();



        $query = $this->db->get();

        //echo  $this->db->last_query(); die;   
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = array();
            return $data;
        }
    }

    public function GetcheckOrderDeleteStatusQry($data = array(), $data_w = array()) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('shipment_fm', $data, $data_w);
    }

    public function GetcheckOrderDeleteStatusQry_dimation($data = array(), $data_w = array()) {
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->delete('diamention_fm', $data_w);
    }

    public function filterexceldispatch($filterArr = array()) {
        $page_no;
        $limit = 2000;
        $start = $filterArr['exportlimit'] - $limit;
        $delivered = $filterArr['status'];
        // echo $exportlimit;
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,diamention_fm.wieght as wt,diamention_fm.description,diamention_fm.cod,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,diamention_fm.deducted_shelve,diamention_fm.cod');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');



        if (!empty($filterArr['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $filterArr['exact']);
        }


        if (!empty($filterArr['from']) && !empty($filterArr['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $filterArr['from'] . "' AND '" . $filterArr['to'] . "'";


            $this->db->where($where);
        }




        if (!empty($delivered)) {

            // print_r($delivered);
            if ($delivered == '1' || $delivered == '4' || $delivered == '5' || $delivered == '7' || $delivered == '11') {
                if (array_key_exists(0, $delivered))
                    $delivered = array_filter(0, $delivered);
            } else
                $delivered = array_filter($delivered);

            $this->db->where_in('shipment_fm.delivered', $delivered);
        }

        if (!empty($filterArr['destination'])) {
            $destination = array_filter($filterArr['destination']);

            $this->db->where_in('shipment_fm.destination', $filterArr['destination']);
        }

        if (!empty($filterArr['awb'])) {
            $this->db->where('shipment_fm.slip_no', $filterArr['awb']);
        }

        if (!empty($filterArr['sku'])) {
            $this->db->where('diamention_fm.sku', $filterArr['sku']);
        }

        if (!empty($filterArr['seller'])) {
            $seller = array_filter($filterArr['seller']);
            $this->db->where_in('shipment_fm.cust_id', $filterArr['seller']);
        }




        $this->db->order_by('shipment_fm.id', 'desc');
        $this->db->limit($limit, $start);
        $tempdb = clone $this->db;
//now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();



        $query = $this->db->get();

        // echo  $this->db->last_query(); die;   
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            return $data;
        }
    }

    public function GetUpdateShipmentEdit($data = array(), $data_w = array()) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('shipment_fm', $data, $data_w);
    }

    public function GetUpdateDiamationQry($data = array(), $data_w = array()) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        // $this->db->where($data_w);
      return  $this->db->update_batch('diamention_fm', $data, 'id');
        //return $this->db->last_query();
    }

    public function GetDimationDatansertQry($data = array()) {
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);

       return $this->db->insert_batch('diamention_fm', $data);
       // return $this->db->last_query();
    }
    
    public function createManifest($data=array()) {
        return $this->db->insert_batch('delivery_manifest', $data);

        
    }

    public function addInventory($data=array(),$activitiesArr) {
         $this->db->insert_batch('item_inventory', $data);
         $this->db->insert_batch('inventory_activity', $activitiesArr);
        
    }
    
     public function manifestFilter($filer_data = array(), $page_no = null) {
        $page_no;
        $limit = 100;
        unset($filer_data['status']);
        unset($filer_data['page_no']);

        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        
           // print_r($filer_data);
            if($filer_data['from'] && $filer_data['to'])
            {
              $where = "DATE(entry_date) BETWEEN '" . $filer_data['from'] . "' AND '" . $filer_data['to'] . "'";
                   $this->db->where($where);   
            }
             if(!empty($filer_data['slip_no']))
            {
             
                   $this->db->where('slip_no',$filer_data['slip_no']);   
            }
            if(!empty($filer_data['label']))
            {
             
                   $this->db->where('label',$filer_data['label']);   
            }
            
            
           
       
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*,count(slip_no) as total_ship');
        $this->db->from('delivery_manifest');

        $this->db->where('deleted', 'N');
        $this->db->limit($limit, $start);
        $this->db->group_by('m_id');

        $query = $this->db->get();

       // echo $this->db->last_query(); die;                      

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
             $data['count'] = $this->manifestFilterCount($filer_data ,$page_no);
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
     public function manifestFilterCount($filer_data = array(), $page_no = null) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('DM.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
         if($filer_data['from'] && $filer_data['to'])
            {
              $where = "DATE(entry_date) BETWEEN '" . $filer_data['from'] . "' AND '" . $filer_data['to'] . "'";
                   $this->db->where($where);   
            }
             if(!empty($filer_data['slip_no']))
            {
             
                   $this->db->where('slip_no',$filer_data['slip_no']);   
            }
            if(!empty($filer_data['label']))
            {
             
                   $this->db->where('label',$filer_data['label']);   
            }
        $this->db->where('DM.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('DM.deleted', $deleted);
        $this->db->select('COUNT(DM.id) as sh_count');
        $this->db->from('delivery_manifest as DM');
       
        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }
    
    
    public function manifestListFilter($filer_data = array(), $page_no = null) {
        $page_no;
        $limit = 100;
        unset($filer_data['status']);
        unset($filer_data['page_no']);

        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        if ($filer_data) {
            foreach ($filer_data as $key => $filter) {
              if(!empty($filter))
              {
                if($key == "booking_id"){
                    $this->db->where("s.$key", $filter);
                }
                else{
                    $this->db->where("d.$key", $filter);
                }
              }
                
            }
        }
        $this->db->select('*');
        $this->db->from('delivery_manifest d');
        $this->db->join('shipment_fm s','s.slip_no = d.slip_no','left');


        $this->db->limit($limit, $start);
        

        $query = $this->db->get();
       // echo $this->db->last_query(); 

                          

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $query->num_rows();
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
     public function getSkuDataByAwb($awb) {
        $this->db->select('*');
        $this->db->from('diamention_fm');       
        $this->db->where('slip_no', $awb);
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
        // return $this->db->last_query(); die;
        return $query->result_array();
    }
    
     public function getDeliveryManifest($m_id=null) {
        $this->db->select('*');
        $this->db->from('delivery_manifest');       
        $this->db->where('m_id', $m_id);
        $query = $this->db->get();
        // return $this->db->last_query(); die;
        return $query->result_array();
    }
     public function getShipmentByAwb($awb) {
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('slip_no', $awb);
        $query = $this->db->get();
        // return $this->db->last_query(); die;
        return $query->row_array();
    }
	
	
		 public function alllistexcelDataOrderCreated($data = array(), $filterData = array()) {  

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        //DEFAULT CHARSET=utf8;
      //  echo $dir; die(); 
        /* $page_no;        
          $limit = 5000;
          if(empty($filterData['exportlimit'])){
          $start = 0;
          }else{
          $start = ($filterData['exportlimit']-1)*$limit;
          } */
        
		   $limit = 2000;   
        $start = $filterData['exportlimit'] - $limit; 
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        if ($filterData['s_type'] == 'AWB')
            $awb = $filterData['s_type_val'];
        if ($filterData['s_type'] == 'SKU')
            $sku = $filterData['s_type_val'];
        $fulfillment = 'Y';
        $deleted = 'N';
		if (!empty($filterData['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $filterData['exact']);
        }


        if (!empty($filterData['from']) && !empty($filterData['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $filterData['from'] . "' AND '" . $filterData['to'] . "'";


            $this->db->where($where);
        }



        $this->db->where_in('shipment_fm.delivered', 11);



        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('shipment_fm.sku', $sku);
        }

        if (!empty($filterData['seller'])) {
            $seller = array_filter($filterData['seller']);
            $this->db->where_in('shipment_fm.cust_id', $filterData['seller']);
        }


        $selectQry = "";
        if ($data['checked'] == 1) {       
         
          
            $selectQry .= " shipment_fm.slip_no as AWB_NO, date(shipment_fm.entrydate) AS ENTRY_DATE,";  
            $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
            $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";
            $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
            $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
			$selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";
            $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
            $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
            $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS STATUS ,";
            $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";
            $selectQry .= " shipment_fm.sku AS SKU,";
			$selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
            $selectQry .= " shipment_fm.pieces AS ON PIECES,";
            $selectQry .= " shipment_fm.weight AS ON WEIGHT,";   
           
   
         
        } else {
            if ($data['slip_no'] == 1)  
                $selectQry .= " shipment_fm.slip_no as AWB_NO, ";
          
            if ($data['entrydate'] == 1)
                $selectQry .= " date(shipment_fm.entrydate) AS ENTRY_DATE,time(shipment_fm.entrydate) AS entry_TIME,";
           
            if ($data['shippers_ref_no'] == 1)
                $selectQry .= " shipment_fm.booking_id AS SHIPPER REF No,";
         
            if ($data['origin'] == 1) {
                $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
                //$selectQry.=" country.city AS ORIGIN,";
                //$this->db->join('country','country.id=shipment_fm.origin');
            }
            if ($data['destination'] == 1) {
                $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
                //$this->db->join('country','country.id=shipment_fm.destination');    
            }
           
            if ($data['reciever_name'] == 1)
                $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
            if ($data['reciever_address'] == 1)
                $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
            if ($data['reciever_phone'] == 1)
                $selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";
            if ($data['sku'] == 1)
                $selectQry .= " shipment_fm.sku AS SKU,";
            if ($data['delivered'] == 1) {
                $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS STATUS ,";
                //$this->db->join('status_main_cat','status_main_cat.id=shipment_fm.delivery');    
            }
            if ($data['total_cod_amt'] == 1)
                $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";
        
  
            if ($data['cust_id'] == 1) {
                $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
                //$this->db->join('country','country.id=shipment_fm.destination');    
            }
          if ($data['pieces'] == 1)
                $selectQry .= " shipment_fm.pieces AS ON PIECES,";
            if ($data['weight'] == 1)
                $selectQry .= " shipment_fm.weight AS ON WEIGHT,";
    
              
        }

        $selectQry = rtrim($selectQry, ',');
        $this->db->select($selectQry);

        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->order_by('shipment_fm.id', 'desc');
        $this->db->limit($limit, $start);     
        $query = $this->db->get();
     //echo $this->db->last_query(); die();      
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename.csv";   



        return $data = chr(239) . chr(187) . chr(191) .$this->dbutil->csv_from_result($query, $delimiter, $newline);
    }
	
	
	
    public function alllistexcelDataOrderReturned($data = array(), $filterData = array(),$status=null) {  

        $this->load->dbutil();  
        $this->load->helper('file');
        $this->load->helper('download');
        //DEFAULT CHARSET=utf8;
      //  echo $dir; die(); 
        /* $page_no;        
          $limit = 5000;
          if(empty($filterData['exportlimit'])){
          $start = 0;
          }else{
          $start = ($filterData['exportlimit']-1)*$limit;
          } */
        
//        $limit = 2000;   
//        $start = $filterData['exportlimit'] - $limit; 
//     
	 $fulfillment = 'Y';
        $deleted = 'N';

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
 
 
        $this->db->where('shipment_fm.backorder', 0);
        if (!empty($filterData['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $filterData['exact']);
        }


        if (!empty($filterData['from']) && !empty($filterData['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $filterData['from'] . "' AND '" . $filterData['to'] . "'";


            $this->db->where($where);
        }



        $this->db->where_in('shipment_fm.delivered', $status);



        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('shipment_fm.sku', $sku);
        }

    
		
        if (!empty($filterData['destination'])) {
            $destination = array_filter($filterData['destination']);

            $this->db->where_in('shipment_fm.destination', $destination);
        }
        if (!empty($filterData['cc_id'])) {
            $cc_id = array_filter($filterData['cc_id']);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
         if (!empty($filterData['wh_id'])) {
            $this->db->where('shipment_fm.wh_id', $filterData['wh_id']);
        }
       

        if (!empty($sku)) {

            $this->db->where('diamention_fm.sku', $sku);
        }
        if (($filterData['is_menifest'] == 0 || $filterData['is_menifest'] == 1) && $filterData['is_menifest'] != null) {

            $this->db->where('shipment_fm.is_menifest', $filterData['is_menifest']);
        }

        if (!empty($filterData['booking_id'])) {

            $this->db->where('shipment_fm.booking_id', $filterData['booking_id']);
        }
        //echo $this->db->last_query(); die;

        if (!empty($filterData['seller'])) {  
			$seller=$filterData['seller'];
            if (sizeof($seller) > 0) {
                $seller = array_filter($seller);
                $this->db->where_in('shipment_fm.cust_id', $seller);
            }
        }

        $selectQry = "";
        if ($data['checked'] == 1) {
            
            //$selectQry .= " shipment_fm.slip_no as AWB_NO, ";
            $selectQry .= " shipment_fm.slip_no as AWB_NO, date(shipment_fm.entrydate) AS ENTRY_DATE,";  
            $selectQry .= " shipment_fm.booking_id AS REFRENCE No,";
            $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";
            $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
            $selectQry .= " shipment_fm.sku AS SKU,";
            
            $superID = $this->session->userdata('user_details')['super_id'];
            $selectQry .= " (select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' AND courier_company.super_id= ".$superID.") AS ForwardedCompany ,";
            
            $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
            $selectQry .= " (select company from customer  where customer.id=shipment_fm.cust_id)  AS SELLER,";
            $selectQry .= " (select name from warehouse_category where warehouse_category.id=shipment_fm.wh_id) AS WAREHOUSE ,";
            $selectQry .= " shipment_fm.sender_name AS SENDER NAME,";
            $selectQry .= " shipment_fm.sender_address AS SENDER ADDRESS,";
            $selectQry .= " shipment_fm.sender_phone AS SENDER PHONE,";
            $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
            $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
            $selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";
            $selectQry .= " shipment_fm.pay_invoice_status AS INVOICE PAID,";
            $selectQry .= " shipment_fm.pay_invoice_no AS INVOICE NUMBER,";
            $selectQry .= " shipment_fm.rec_invoice_status AS INVOICE PAYMENT RECEIVED ,";
            $selectQry .= " shipment_fm.mode AS RECEIVER MODE,";
            $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS MAINSTATUS,";
           // $selectQry .= " (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS 3PLSTATUS ,";
             $selectQry .= " IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  ) AS transaction_days,";  
             $selectQry .= " (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1) AS LastStatus ,";
            $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";
            $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
            $selectQry .= " shipment_fm.pieces AS ON PIECES,";
            $selectQry .= " shipment_fm.weight AS ON WEIGHT,";
            $selectQry .= " shipment_fm.status_describtion AS DESCRIPTION,";
            $selectQry .= " shipment_fm.frwd_company_awb AS FORWARD AWB No,";
            $selectQry .= " shipment_fm.3pl_pickup_date AS 3PL Pickup Date,";
            $selectQry .= " shipment_fm.3pl_close_date AS 3PL Closed Date,";
            $selectQry .= " shipment_fm.no_of_attempt AS No Of Attempt,";
            $selectQry .= " shipment_fm.pay_invoice_no AS Transaction Number,";
           
           // $selectQry .= " (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1) AS LastStatus ,";
            
            
            
         //   $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
//            $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";
//            $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
//            $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
//            $selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";
//            $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
//            $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
//            $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS STATUS ,";
//            $selectQry .= " (select name from warehouse_category where warehouse_category.id=shipment_fm.wh_id) AS WAREHOUSE ,";
//            $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";
//            $selectQry .= " shipment_fm.sku AS SKU,";
//            $selectQry .= " (select name from customer where customer.id=shipment_fm.cust_id) AS SELLER ,";
//            $selectQry .= " shipment_fm.pieces AS ON PIECES,";
//            $selectQry .= " shipment_fm.weight AS ON WEIGHT,";
//            
            
   
         
        } else {
            
                if ($data['slip_no'] == 1)
                    $selectQry .= " shipment_fm.slip_no as AWB_NO, ";

                if ($data['sku'] == 1)
                    $selectQry .= " shipment_fm.sku as SKU, ";

                if ($data['entrydate'] == 1)
                    $selectQry .= " date(shipment_fm.entrydate) AS ENTRY_DATE,time(shipment_fm.entrydate) AS entry_TIME,";
                if ($data['booking_id'] == 1)
                    $selectQry .= " shipment_fm.booking_id AS REFRENCE No,";
                if ($data['shippers_ref_no'] == 1)
                    $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";

                if ($data['origin'] == 1) {
                    $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
                }

                if ($data['cc_name'] == 1) {
                    $superID = $this->session->userdata('user_details')['super_id'];
                    $selectQry .= " (select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' AND courier_company.super_id= ".$superID.") AS ForwardedCompany ,";
                }
                if ($data['destination'] == 1) {
                    $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
                    //$this->db->join('country','country.id=shipment_fm.destination');    
                }
                if ($data['sender_name'] == 1)
                    $selectQry .= " shipment_fm.sender_name AS SENDER NAME,";
                if ($data['sender_address'] == 1)
                    $selectQry .= " shipment_fm.sender_address AS SENDER ADDRESS,";
                if ($data['sender_phone'] == 1)
                    $selectQry .= " shipment_fm.sender_phone AS SENDER PHONE,";
                if ($data['reciever_name'] == 1)
                    $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
                if ($data['reciever_address'] == 1)
                    $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
                if ($data['reciever_phone'] == 1)
                    $selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";

                    if ($data['invoice_details'] == 1)
                    {
                        $selectQry .= " shipment_fm.pay_invoice_status AS INVOICE PAID,";
                        $selectQry .= " shipment_fm.pay_invoice_no AS INVOICE NUMBER,";
                        $selectQry .= " shipment_fm.rec_invoice_status AS INVOICE PAYMENT RECEIVED ,";
                    }

                if ($data['mode'] == 1)
                    $selectQry .= " shipment_fm.mode AS RECEIVER MODE,";
                if ($data['delivered'] == 1) {
                    $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS MAINSTATUS,";
                    //$this->db->join('status_main_cat','status_main_cat.id=shipment_fm.delivery');    
                }
                if ($data['status_o'] == 1) {
                    $selectQry .= " (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS 3PLSTATUS ,";
                }
                if ($data['transaction_days'] == 1)
            $selectQry .= " IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  ) AS transaction_days, ";  
       
        
         if ($data['last_status_n'] == 1) {
            $selectQry .= " (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1) AS LastStatus ,";
        }
                if ($data['total_cod_amt'] == 1)
                    $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";


                if ($data['cust_id'] == 1) {
                    $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
                    //$this->db->join('country','country.id=shipment_fm.destination');    
                }
                if ($data['pieces'] == 1)
                    $selectQry .= " shipment_fm.pieces AS ON PIECES,";
                if ($data['weight'] == 1)
                    $selectQry .= " shipment_fm.weight AS ON WEIGHT,";
                if ($data['status_describtion'] == 1)
                    $selectQry .= " shipment_fm.status_describtion AS DESCRIPTION,";

                if ($data['frwd_awb_no'] == 1)
                    $selectQry .= " shipment_fm.frwd_company_awb AS FORWARD AWB No,";

                if ($data['pl3_pickup_date'] == 1)
                    $selectQry .= " shipment_fm.3pl_pickup_date AS 3PL Pickup Date,";

                if ($data['pl3_close_date'] == 1)
                    $selectQry .= " shipment_fm.3pl_close_date AS 3PL Closed Date,";



                if ($data['close_date'] == 1)
                $selectQry .= " shipment_fm.close_date AS CLOSE DATE,";


            if ($data['no_of_attempt'] == 1)
                $selectQry .= " shipment_fm.no_of_attempt AS No Of Attempt,";
            
            if ($data['transaction_no'] == 1)
                $selectQry .= " shipment_fm.pay_invoice_no AS Transaction Number,";
 }
            
        $selectQry = rtrim($selectQry, ',');
        $this->db->select($selectQry);

        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->order_by('shipment_fm.id', 'desc');
        if (isset($filterData['exportlimit']) && !empty($filterData['exportlimit'] )) {
            $this->db->limit($filterData['exportlimit'] );
        }
        //$this->db->limit($limit, $start);     
        $query = $this->db->get();
        // echo $this->db->last_query(); die();      
        $delimiter = ",";
        $newline = "\r\n";
        //$filename = "filename.csv";   



        return $data = chr(239) . chr(187) . chr(191) .$this->dbutil->csv_from_result($query, $delimiter, $newline);
    }
	
	
		 public function alllistexcelDataOrderPacked($data = array(), $filterData = array(),$status=null) {  

        $this->load->dbutil();  
        $this->load->helper('file');
        $this->load->helper('download');
        //DEFAULT CHARSET=utf8;
      //  echo $dir; die(); 
        /* $page_no;        
          $limit = 5000;
          if(empty($filterData['exportlimit'])){
          $start = 0;
          }else{
          $start = ($filterData['exportlimit']-1)*$limit;
          } */
        
		   $limit = 2000;   
        $start = $filterData['exportlimit'] - $limit; 
     
	 $fulfillment = 'Y';
        $deleted = 'N';

        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
 
 
		 $this->db->where('shipment_fm.backorder', 0);
        if (!empty($filterData['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $filterData['exact']);
        }


        if (!empty($filterData['from']) && !empty($filterData['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $filterData['from'] . "' AND '" . $filterData['to'] . "'";


            $this->db->where($where);
        }



        $this->db->where_in('shipment_fm.delivered', $status);



        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('shipment_fm.sku', $sku);
        }

    
		
        if (!empty($filterData['destination'])) {
            $destination = array_filter($filterData['destination']);

            $this->db->where_in('shipment_fm.destination', $destination);
        }
        if (!empty($filterData['cc_id'])) {
            $cc_id = array_filter($filterData['cc_id']);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
         if (!empty($filterData['wh_id'])) {
            $this->db->where('shipment_fm.wh_id', $filterData['wh_id']);
        }
       

        if (!empty($sku)) {

            $this->db->where('diamention_fm.sku', $sku);
        }
        if (($filterData['is_menifest'] == 0 || $filterData['is_menifest'] == 1) && $filterData['is_menifest'] != null) {

            $this->db->where('shipment_fm.is_menifest', $filterData['is_menifest']);
        }

        if (!empty($filterData['booking_id'])) {

            $this->db->where('shipment_fm.booking_id', $filterData['booking_id']);
        }
        //echo $this->db->last_query(); die;

        if (!empty($filterData['seller'])) {  
			$seller=$filterData['seller'];
            if (sizeof($seller) > 0) {
                $seller = array_filter($seller);
                $this->db->where_in('shipment_fm.cust_id', $seller);
            }
        }

        $selectQry = "";
        if ($data['checked'] == 1) {       
         
          
            $selectQry .= " shipment_fm.slip_no as AWB_NO, date(shipment_fm.entrydate) AS ENTRY_DATE,";  
         //   $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
            $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";
            $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
            $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
			$selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";
            $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
            $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
            $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS STATUS ,";
			$selectQry .= " (select name from warehouse_category where warehouse_category.id=shipment_fm.wh_id) AS WAREHOUSE ,";
            $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";
            $selectQry .= " shipment_fm.sku AS SKU,";
			$selectQry .= " (select name from customer where customer.id=shipment_fm.cust_id) AS SELLER ,";
            $selectQry .= " shipment_fm.pieces AS ON PIECES,";
            $selectQry .= " shipment_fm.weight AS ON WEIGHT,";   
            $selectQry .= " (select company from courier_company where courier_company.id=shipment_fm.frwd_company_id) AS FORWARD_COMPANY ,"; 
			$selectQry .= " shipment_fm.frwd_company_awb AS FORWARD_AWB_NO,";
   
         
        } else {
            if ($data['slip_no'] == 1)  
                $selectQry .= " shipment_fm.slip_no as AWB_NO, ";
          
            if ($data['entrydate'] == 1)
                $selectQry .= " date(shipment_fm.entrydate) AS ENTRY_DATE,time(shipment_fm.entrydate) AS entry_TIME,";
           
            if ($data['shippers_ref_no'] == 1)
                $selectQry .= " shipment_fm.booking_id AS SHIPPER REF No,";
         
            if ($data['origin'] == 1) {
                $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
                //$selectQry.=" country.city AS ORIGIN,";
                //$this->db->join('country','country.id=shipment_fm.origin');
            }
            if ($data['destination'] == 1) {
                $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
                //$this->db->join('country','country.id=shipment_fm.destination');    
            }
           
            if ($data['reciever_name'] == 1)
                $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
            if ($data['reciever_address'] == 1)
                $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
            if ($data['reciever_phone'] == 1)
                $selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";
            if ($data['sku'] == 1)
                $selectQry .= " shipment_fm.sku AS SKU,";
            if ($data['delivered'] == 1) {
                $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS STATUS ,";
                //$this->db->join('status_main_cat','status_main_cat.id=shipment_fm.delivery');    
            }
            if ($data['total_cod_amt'] == 1)
                $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";
        
  
            if ($data['cust_id'] == 1) {   
                $selectQry .= " (select name from customer where customer.id=shipment_fm.cust_id) AS SELLER ,";
                //$this->db->join('country','country.id=shipment_fm.destination');    
            } 
             if ($data['last_status_n'] == 1) {
            $selectQry .= " (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1) AS LastStatus ,";
        }
          if ($data['warehouse'] == 1)   
                $selectQry .= " (select name from warehouse_category where warehouse_category.id=shipment_fm.wh_id) AS WAREHOUSE ,";
            if ($data['weight'] == 1)
                $selectQry .= " shipment_fm.weight AS ON WEIGHT,";
    
              
        }

        $selectQry = rtrim($selectQry, ',');
        $this->db->select($selectQry);

        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->order_by('shipment_fm.id', 'desc');
        $this->db->limit($limit, $start);     
        $query = $this->db->get();
    // echo $this->db->last_query(); die();      
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename.csv";   



        return $data = chr(239) . chr(187) . chr(191) .$this->dbutil->csv_from_result($query, $delimiter, $newline);
    }
	
	
	
	  public function countryList() {  
        $this->db->where('country.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id, city');
        $this->db->from('country');   
		$this->db->where('city!=', '');  
        $this->db->order_by('id', 'DESC');
       // $this->db->limit(100);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
	
	
	

  public function alllistexcelData($data = array(), $filterData = array()) {

        $this->load->dbutil();
        $this->load->helper('file');
        //$this->load->helper('download');


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.deleted', $deleted);


        if (!empty($filterData['exact'])) {
            $this->db->where('DATE(shipment_fm.entrydate)', $filterData['exact']);
        }


        if (!empty($filterData['from']) && !empty($filterData['to'])) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $filterData['from'] . "' AND '" . $filterData['to'] . "'";


            $this->db->where($where);
        }


        if (!empty($filterData['status'])) {
            $delivered = $filterData['status'];
            
            if(is_numeric($delivered)){
            if ($delivered == '1' || $delivered == '4' || $delivered == '5') {
                if (array_key_exists(0, $delivered))
                    $delivered = array_filter(0, $delivered);
            } else
                $delivered = array_filter($delivered);

                $this->db->where_in('shipment_fm.delivered', $delivered);
             
            }else{
                $this->db->where_in('shipment_fm.code', $delivered);
        }
        }
        if (!empty($filterData['status_o'])) {
            $this->db->where_in('shipment_fm.code', $filterData['status_o']);
        }

        if (!empty($filterData['destination'])) {
            $destination = array_filter($filterData['destination']);

            $this->db->where_in('shipment_fm.destination', $filterData['destination']);
        }
        
        if (!empty($filterData['cc_id'])) {
            $cc_id = array_filter($filterData['cc_id']);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }

        if (isset($filterData['s_type']) && $filterData['s_type'] == "AWB" && !empty($filterData['s_type_val'])) {
            $this->db->where('shipment_fm.slip_no', $filterData['s_type_val']);
        }

        if (isset($filterData['s_type']) && $filterData['s_type'] == "SKU" && !empty($filterData['s_type_val'])) {
            $sku = $data['s_type_val'];
            $this->db->where('diamention_fm.sku', $sku);
        }

        if (!empty($filterData['seller'])) {
            $seller = array_filter($filterData['seller']);
            $this->db->where_in('shipment_fm.cust_id', $filterData['seller']);
        }

        if (!empty($filterData['mode'])) {
            $this->db->where('shipment_fm.mode', $filterData['mode']);
        }
        if (!empty($filterData['sku'])) {
            $this->db->where('shipment_fm.sku', $filterData['sku']);
        }

        if (!empty($filterData['piece'])) {
            $this->db->where('diamention_fm.piece', $filterData['piece']);
        }
        if (!empty($filterData['booking_id'])) {
            $this->db->where('shipment_fm.booking_id', $filterData['booking_id']);
        }

        if (!empty($filterData['cod'])) {
            $this->db->where('diamention_fm.cod', $filterData['cod']);
        }

        $this->db->where('shipment_fm.backorder', 0);

        $selectQry = "";

        if ($data['slip_no'] == 1)
            $selectQry .= " shipment_fm.slip_no as AWB_NO, ";

        if ($data['entrydate'] == 1)
            $selectQry .= " date(shipment_fm.entrydate) AS ENTRY_DATE,time(shipment_fm.entrydate) AS entry_TIME,";
        if ($data['booking_id'] == 1)
            $selectQry .= " shipment_fm.booking_id AS REFRENCE No,";
        if ($data['shippers_ref_no'] == 1)
            $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";

        if ($data['origin'] == 1) {
            $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
        }
        
        if ($data['cc_name'] == 1) {
            $superID = $this->session->userdata('user_details')['super_id'];
            $selectQry .= " (select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' AND courier_company.super_id= ".$superID." limit 1) AS ForwardedCompany ,";
        }
        if ($data['destination'] == 1) {
            $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
            //$this->db->join('country','country.id=shipment_fm.destination');    
        }
        if ($data['sender_name'] == 1)
            $selectQry .= " shipment_fm.sender_name AS SENDER NAME,";
        if ($data['sender_address'] == 1)
            $selectQry .= " shipment_fm.sender_address AS SENDER ADDRESS,";
        if ($data['sender_phone'] == 1)
            $selectQry .= " shipment_fm.sender_phone AS SENDER PHONE,";
        if ($data['reciever_name'] == 1)
            $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
        if ($data['reciever_address'] == 1)
            $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
        if ($data['reciever_phone'] == 1)
            $selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";

            if ($data['invoice_details'] == 1)
            {
                $selectQry .= " shipment_fm.pay_invoice_status AS INVOICE PAID,";
                $selectQry .= " shipment_fm.pay_invoice_no AS INVOICE NUMBER,";
                $selectQry .= " shipment_fm.rec_invoice_status AS INVOICE PAYMENT RECEIVED ,";
            }
            
        if ($data['mode'] == 1)
            $selectQry .= " shipment_fm.mode AS RECEIVER MODE,";
        if ($data['delivered'] == 1) {
            $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS MAINSTATUS,";
            //$this->db->join('status_main_cat','status_main_cat.id=shipment_fm.delivery');    
        }
        if ($data['status_o'] == 1) {
            $selectQry .= " (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS 3PLSTATUS ,";
        }
        
        if ($data['last_status_n'] == 1) {
            $selectQry .= " (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1) AS LastStatus ,";
        }
        if ($data['total_cod_amt'] == 1)
            $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";


        if ($data['cust_id'] == 1) {
            $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
            //$this->db->join('country','country.id=shipment_fm.destination');    
        }
        if ($data['pieces'] == 1)
            $selectQry .= " shipment_fm.pieces AS ON PIECES,";
        if ($data['weight'] == 1)
            $selectQry .= " shipment_fm.weight AS ON WEIGHT,";
        if ($data['status_describtion'] == 1)
            $selectQry .= " shipment_fm.status_describtion AS DESCRIPTION,";

        if ($data['frwd_awb_no'] == 1)
            $selectQry .= " shipment_fm.frwd_company_awb AS FORWARD AWB No,";

        if ($data['pl3_pickup_date'] == 1)
            $selectQry .= " shipment_fm.3pl_pickup_date AS 3PL Pickup Date,";

        if ($data['pl3_close_date'] == 1)
            $selectQry .= " shipment_fm.3pl_close_date AS 3PL Closed Date,";
            if ($data['close_date'] == 1)
            $selectQry .= " shipment_fm.close_date AS CLOSE DATE,";
 
            
        if ($data['no_of_attempt'] == 1)
            $selectQry .= " shipment_fm.no_of_attempt AS No Of Attempt,";

//        if ($data['transaction_days'] == 1)
//            $selectQry .= " DATEDIFF(3pl_close_date,3pl_pickup_date) AS Transaction Day,";
//            
          if($data['transaction_no'] == 1){
                $selectQry .= " shipment_fm.pay_invoice_no AS Transaction Number,";
          }
        
         if ($data['transaction_days'] == 1)
        $selectQry .= " IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  )  AS transaction_days,";    

        $selectQry = rtrim($selectQry, ',');
        
        
        //echo $selectQry;die;
        $this->db->select($selectQry);

        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->order_by('shipment_fm.id', 'desc');
        if (isset($filterData['exportlimit']) && !empty($filterData['exportlimit'])) {
            $this->db->limit($filterData['exportlimit']);
        }

        $query = $this->db->get();
       // echo $this->db->last_query(); die;
        $delimiter = ",";
        $newline = "\r\n";




        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }
    
	
 public function GetCheckDeleteProceesShipment($awb=array()) {
        $this->db->select('slip_no,delivered,code,cust_id');
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where_in('shipment_fm.code',array('OC','PG','AP','PK','OG'));
        $this->db->where_in('slip_no', $awb);
        $query = $this->db->get();
        // return $this->db->last_query(); die;
        return $query->result_array();
    }
    
     public function DeleteUpdateShipmentData($data=array()) {
        return $this->db->insert_batch('status_fm', $data);

        
    }
    
        public function RemmoveChargesDeleteOrder($data=array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
       return $this->db->update_batch('orderinvoicepicking', $data, 'slip_no');
        // $this->db->last_query();
    }
    
    public function diamention_fmDeleteProcessQry($slip_no=null)
    {
        
              $diamention_query = "select diamention_fm.piece,diamention_fm.sku,items_m.wh_id,items_m.sku_size,items_m.type,items_m.id from diamention_fm LEFT JOIN items_m on  diamention_fm.sku=items_m.sku where deleted='N' and diamention_fm.slip_no='" . $slip_no . "'";
                $query2 = $this->db->query($diamention_query);
                $dia_data = $query2->result_array();
                return $dia_data;
    }
    
    
    
     public function GetopenOrderProcessCheckQry($awb=null) {
        $this->db->select('slip_no,delivered,code,cust_id');
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where_in('shipment_fm.code',array('OC','PG','AP','PK'));
        $this->db->where('slip_no', $awb);
        $query = $this->db->get();
        // return $this->db->last_query(); die;
        return $query->row_array();
    }
    
       public function DeletePickListProcess($awb=null) {
      return  $this->db->delete('pickuplist_tbl', array('slip_no' => $awb)); 
    }
    
     public function RemoveStockLocation($data=array()) {
      return  $this->db->delete('locationDetails',$data,'slip_no'); 
    }


    public function trackinglistexcelData($data = array(),$slipArr=array()) {

        $this->load->dbutil();
        $this->load->helper('file');
        //$this->load->helper('download');


         $slip_no = explode(",", $slipArr);
        
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.deleted', $deleted);

        $this->db->where('shipment_fm.backorder', 0);

        $selectQry = "";

        if ($data['slip_no'] == 1)
            $selectQry .= " shipment_fm.slip_no as AWB_NO, ";

        if ($data['entrydate'] == 1)
            $selectQry .= " date(shipment_fm.entrydate) AS ENTRY_DATE,time(shipment_fm.entrydate) AS entry_TIME,";
        if ($data['booking_id'] == 1)
            $selectQry .= " shipment_fm.booking_id AS REFRENCE No,";
        if ($data['shippers_ref_no'] == 1)
            $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";

        if ($data['origin'] == 1) {
            $selectQry .= " (select city from country where country.id=shipment_fm.origin) AS ORIGIN ,";
            //$selectQry.=" country.city AS ORIGIN,";
            //$this->db->join('country','country.id=shipment_fm.origin');
        }
        if ($data['destination'] == 1) {
            $selectQry .= " (select city from country where country.id=shipment_fm.destination) AS DESTINATION ,";
            //$this->db->join('country','country.id=shipment_fm.destination');    
        }
        if ($data['sender_name'] == 1)
            $selectQry .= " shipment_fm.sender_name AS SENDER NAME,";
        if ($data['sender_address'] == 1)
            $selectQry .= " shipment_fm.sender_address AS SENDER ADDRESS,";
        if ($data['sender_phone'] == 1)
            $selectQry .= " shipment_fm.sender_phone AS SENDER PHONE,";
        if ($data['reciever_name'] == 1)
            $selectQry .= " shipment_fm.reciever_name AS RECEIVER NAME,";
        if ($data['reciever_address'] == 1)
            $selectQry .= " shipment_fm.reciever_address AS RECEIVER ADDRESS,";
        if ($data['reciever_phone'] == 1)
            $selectQry .= " shipment_fm.reciever_phone AS RECEIVER PHONE,";
        if ($data['mode'] == 1)
            $selectQry .= " shipment_fm.mode AS RECEIVER MODE,";
        if ($data['delivered'] == 1) {
            $selectQry .= " (select main_status from status_main_cat_fm where status_main_cat_fm.id=shipment_fm.delivered) AS STATUS ,";
            //$this->db->join('status_main_cat','status_main_cat.id=shipment_fm.delivery');    
        }
        if ($data['total_cod_amt'] == 1)
            $selectQry .= " shipment_fm.total_cod_amt AS COD AMOUNT,";


        if ($data['cust_id'] == 1) {
            $selectQry .= " (select uniqueid from customer where customer.id=shipment_fm.cust_id) AS UNIQUE_ID ,";
            //$this->db->join('country','country.id=shipment_fm.destination');    
        }
        if ($data['pieces'] == 1)
            $selectQry .= " shipment_fm.pieces AS ON PIECES,";
        if ($data['weight'] == 1)
            $selectQry .= " shipment_fm.weight AS ON WEIGHT,";
        if ($data['status_describtion'] == 1)
            $selectQry .= " shipment_fm.status_describtion AS DESCRIPTION,";
        if ($data['last_status_n'] == 1) {
            $selectQry .= " (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1) AS LastStatus ,";
        }

        if ($data['frwd_awb_no'] == 1)
            $selectQry .= " shipment_fm.frwd_company_awb AS FORWARD AWB No,";
        if ($data['close_date'] == 1)
            $selectQry .= " shipment_fm.close_date AS CLOSE DATE,";


        $selectQry = rtrim($selectQry, ',');
        $this->db->select($selectQry);

        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.status', 'Y');
      //  $this->db->where('shipment_fm.deleted', 'N');
        //$this->db->where('diamention_fm.deleted', 'N');
         $this->db->where_in('shipment_fm.slip_no', $slip_no);
        $this->db->order_by('shipment_fm.id', 'desc');
        

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        $delimiter = ",";
        $newline = "\r\n";




        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }


     public function getStatusIDByName($status_name= NULL){
        
        $sql = "SELECT id FROM status_main_cat_fm where main_status='".$status_name."' AND deleted='N' AND status = 'Y' ";
        
        $query = $this->db->query($sql);
        $data = $query->result_array();
        return $data;        
    }


}
