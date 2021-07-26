<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ItemInventory_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Item_model');
        $this->load->model('Seller_model');
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function deleteSku($id) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $this->db->delete('item_inventory');
        // echo $this->db->last_query(); die;
    }

    public function updateInventory($data) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $data['id']);
        $this->db->update('item_inventory', $data);
        // echo $this->db->last_query(); die;
    }

    public function Getcheckvalidpallet($shelve_no = null, $seller_id = null) {
        $query = $this->db->query("SELECT * FROM `warehous_shelve_no_fm` WHERE shelv_no!='(select shelve_no from item_inventory where seller_id=$seller_id and shelve_no=$shelve_no)' and super_id='" . $this->session->userdata('user_details')['super_id'] . "' group by shelv_no");
        $result = $query->result_array();
        return $result;
    }
    

    
    public function add($data, $type = null) {
        //  echo '<pre>';
        //print_r($data); die;
        foreach ($data as $rdata) {

            $array = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'wh_id' => $rdata['wh_id']);
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where($array);
            $query = $this->db->get('item_inventory');
            //echo $this->db->last_query(); 

            if ($query->num_rows() == 1) {

                // echo "tttt"; die;
                $status = "Quantity Increase";
                $previous_data = $query->result()[0];
                $item_previous_quantity = $previous_data->quantity;
                //print_r($data['quantity']);
                $item_new_quantity = $rdata['quantity'];
                $item_updated_quantity = $item_previous_quantity + $item_new_quantity;
                if (!empty($data['shelve_no']))
                    $new_data = array('quantity' => $item_updated_quantity, 'stock_location' => $data['stock_location'], 'shelve_no' => $data['shelve_no']);
                else
                    $new_data = array('quantity' => $item_updated_quantity, 'stock_location' => $data['stock_location']);

                $item_inventory_history[] = array(
                    'item_sku' => $data['item_sku'],
                    'item_previous_quantity' => $item_previous_quantity,
                    'item_new_quantity' => $item_updated_quantity,
                    'update_date' => date("Y/m/d h:i:sa"),
                    'seller_id' => $data['seller_id'],
                    'status' => $status
                );

                if ($type == 'transfer')
                    $activitiesType = 'transfer';
                else if ($type == 'return')
                    $activitiesType = 'return';
                else if ($type == 'delete')
                    $activitiesType = 'delete';
                else
                    $activitiesType = 'Update';
                
                  if(empty($data['shelve_no']))
                    {
                        $data['shelve_no']="";
                    }
                if (!empty($rdata['awb_no']))
                {
                    $activitiesArr = array('exp_date' => $data['expity_date'], 'st_location' => $data['stock_location'], 'item_sku' => $data['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $data['seller_id'], 'qty' => $item_updated_quantity, 'p_qty' => $item_previous_quantity, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $rdata['awb_no'], 'super_id' => $this->session->userdata('user_details')['super_id'],'shelve_no'=>$data['shelve_no']);
                }
                else
                {
                    $activitiesArr = array('exp_date' => $data['expity_date'], 'st_location' => $data['stock_location'], 'item_sku' => $data['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $data['seller_id'], 'qty' => $item_updated_quantity, 'p_qty' => $item_previous_quantity, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'],'shelve_no'=>$data['shelve_no']);
                }
                GetAddInventoryActivities($activitiesArr);

                $this->db->where($array);
                return $this->db->update('item_inventory', $new_data);



                //print_r($this->db->update('item_inventory',$new_data));      
                //echo '</pre>';
                //exit();
                //return  $query->result();
            } else {
                //echo "sssss"; die;

                $status = "Recently Added";
              //  if (!empty($rdata['shelve_no']))
                    $array_added[] = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'quantity' => $rdata['quantity'], 'itype' => $rdata['itype'], 'shelve_no' => $rdata['shelve_no'], 'wh_id' => $rdata['wh_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                // else
                //     $array_added[] = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'quantity' => $rdata['quantity'], 'itype' => $rdata['itype'], 'wh_id' => $rdata['wh_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                // //print_r($data);
                //echo "ddd"; die;

                $item_inventory_history2[] = array(
                    'item_sku' => $rdata['item_sku'],
                    'item_previous_quantity' => 0,
                    'item_new_quantity' => $rdata['quantity'],
                    'update_date' => date("Y/m/d h:i:sa"),
                    'seller_id' => $rdata['seller_id'],
                    'status' => $status,
                    'super_id' => $this->session->userdata('user_details')['super_id']
                );

                if ($type == 'transfer')
                {
                    $activitiesType = 'transfer';
                }
                else if ($type == 'return')
                {
                    $activitiesType = 'return';
                }
                else if ($type == 'delete')
                {
                    $activitiesType = 'delete';
                }
                else
                {
                    $activitiesType = 'Add';
                }
                
                  if(empty($rdata['shelve_no']))
                    {
                        $rdata['shelve_no']="";
                    }
                if (!empty($rdata['awb_no']))
                {
                    $activitiesArr[] = array('exp_date' => $rdata['expity_date'], 'st_location' => $rdata['stock_location'], 'item_sku' => $rdata['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $rdata['seller_id'], 'qty' => $rdata['quantity'], 'p_qty' => 0, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $rdata['awb_no'], 'super_id' => $this->session->userdata('user_details')['super_id'],'shelve_no'=>$rdata['shelve_no']);
                }
                else
                {
                    
                    $activitiesArr[] = array('exp_date' => $rdata['expity_date'], 'st_location' => $rdata['stock_location'], 'item_sku' => $rdata['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $rdata['seller_id'], 'qty' => $rdata['quantity'], 'p_qty' => 0, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'],'shelve_no'=>$rdata['shelve_no']);
                }
            }
        }
        // echo '<pre>';
        ///print_r($activitiesArr); die;
        if (!empty($item_inventory_history)) {
            // $this->db->insert('item_inventory_history',$item_inventory_history);
        }

        if (!empty($item_inventory_history2) && !empty($array_added)) {
            // $this->db->insert_batch('item_inventory_history',$item_inventory_history2);
           if($this->db->insert_batch('item_inventory', $array_added))
           {
            $this->db->insert_batch('inventory_activity', $activitiesArr);
           }
           
            //echo $this->db->last_query(); die;
            //GetAddInventoryActivities($activitiesArr);
        }
        return true;
    }
    public function add_new($data, $type = null) {
        //  echo '<pre>';
        //print_r($data); die;
        foreach ($data as $rdata) {

            $array = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'wh_id' => $rdata['wh_id']);
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where($array);
            $query = $this->db->get('item_inventory');
            //echo $this->db->last_query(); 

            {
               
                $status = "Recently Added";
            
                    $array_added[] = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'quantity' => $rdata['quantity'], 'itype' => $rdata['itype'], 'shelve_no' => $rdata['shelve_no'], 'wh_id' => $rdata['wh_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
               
                                    
                    $activitiesArr[] = array('exp_date' => $rdata['expity_date'], 'st_location' => $rdata['stock_location'], 'item_sku' => $rdata['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $rdata['seller_id'], 'qty' => $rdata['quantity'], 'p_qty' => 0, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'],'shelve_no'=>$rdata['shelve_no']);
                
            }
        }
        
        if ( !empty($array_added)) {
            
           if($this->db->insert_batch('item_inventory', $array_added))
           {
            $this->db->insert_batch('inventory_activity', $activitiesArr);
           }
           
            //echo $this->db->last_query(); die;
            //GetAddInventoryActivities($activitiesArr);
        }
        return true;
    }

    public function add_damage($data, $type = null) {
        //print_r($data); die;
        foreach ($data as $rdata) {

            $array = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'status_type' => $rdata['status_type']);
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where($array);
            $query = $this->db->get('inventory_damage');
            //echo $this->db->last_query(); 

            if ($query->num_rows() == 1) {


                $status = "Quantity Increase";
                $previous_data = $query->result()[0];
                $item_previous_quantity = $previous_data->quantity;
                //print_r($data['quantity']);
                $item_new_quantity = $rdata['quantity'];
                $item_updated_quantity = $item_previous_quantity + $item_new_quantity;
                if (!empty($rdata['shelve_no']))
                    $new_data = array('quantity' => $item_updated_quantity, 'stock_location' => $rdata['stock_location'], 'shelve_no' => $rdata['shelve_no'], 'updated_by' => $this->session->userdata('user_details')['user_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                else
                    $new_data = array('quantity' => $item_updated_quantity, 'stock_location' => $rdata['stock_location'], 'updated_by' => $this->session->userdata('user_details')['user_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);


                $item_inventory_history[] = array(
                    'item_sku' => $data['item_sku'],
                    'item_previous_quantity' => $item_previous_quantity,
                    'item_new_quantity' => $item_updated_quantity,
                    'update_date' => date("Y/m/d h:i:sa"),
                    'seller_id' => $data['seller_id'],
                    'status' => $status,
                    'super_id' => $this->session->userdata('user_details')['super_id']
                );


                if ($type == 'transfer')
                    $activitiesType = 'transfer';
                else if ($type == 'return')
                    $activitiesType = 'return';
                else if ($type == 'delete')
                    $activitiesType = 'delete';
                else
                    $activitiesType = 'Update';


                if (!empty($rdata['awb_no']))
                    $activitiesArr = array('exp_date' => $data['expity_date'], 'st_location' => $data['stock_location'], 'item_sku' => $data['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $data['seller_id'], 'qty' => $item_updated_quantity, 'p_qty' => $item_previous_quantity, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $rdata['awb_no'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                else
                    $activitiesArr = array('exp_date' => $data['expity_date'], 'st_location' => $data['stock_location'], 'item_sku' => $data['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $data['seller_id'], 'qty' => $item_updated_quantity, 'p_qty' => $item_previous_quantity, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id']);


                //print_r($activitiesArr); 

                GetAddInventoryActivities($activitiesArr);
                $this->db->where($array);
                return $this->db->update('inventory_damage', $new_data);



                //print_r($this->db->update('item_inventory',$new_data));      
                //echo '</pre>';
                //exit();
                //return  $query->result();
            } else {

                $status = "Recently Added";
                if (!empty($rdata['shelve_no']))
                    $array_added[] = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'quantity' => $rdata['quantity'], 'itype' => $rdata['itype'], 'shelve_no' => $rdata['shelve_no'], 'updated_by' => $this->session->userdata('user_details')['user_id'], 'status_type' => $rdata['status_type'], 'super_id' => $this->session->userdata('user_details')['super_id'], 'order_type' => 'shipment');
                else
                    $array_added[] = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expity_date'], 'stock_location' => $rdata['stock_location'], 'quantity' => $rdata['quantity'], 'itype' => $rdata['itype'], 'updated_by' => $this->session->userdata('user_details')['user_id'], 'status_type' => $rdata['status_type'], 'super_id' => $this->session->userdata('user_details')['super_id'], 'order_type' => 'shipment');
                $status = "Recently Added";



                //print_r($data);
                //echo "ddd"; die;
                $item_inventory_history2[] = array(
                    'item_sku' => $rdata['item_sku'],
                    'item_previous_quantity' => 0,
                    'item_new_quantity' => $rdata['quantity'],
                    'update_date' => date("Y/m/d h:i:sa"),
                    'seller_id' => $rdata['seller_id'],
                    'status' => $status,
                    'super_id' => $this->session->userdata('user_details')['super_id']
                );


                if ($type == 'transfer')
                    $activitiesType = 'transfer';
                else if ($type == 'return')
                    $activitiesType = 'return';
                else if ($type == 'delete')
                    $activitiesType = 'delete';
                else
                    $activitiesType = 'Add';


                $totalPqty_history = GetuserSkuAllqty($rdata['seller_id'], $rdata['item_sku']);
                $updateTOtalQty = $totalPqty_history + $rdata['quantity'];
                if (!empty($rdata['awb_no']))
                    $activitiesArr[] = array('exp_date' => $rdata['expity_date'], 'st_location' => $rdata['stock_location'], 'item_sku' => $rdata['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $rdata['seller_id'], 'qty' => $updateTOtalQty, 'p_qty' => $totalPqty_history, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $rdata['awb_no'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                else
                    $activitiesArr[] = array('exp_date' => $rdata['expity_date'], 'st_location' => $rdata['stock_location'], 'item_sku' => $rdata['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $rdata['seller_id'], 'qty' => $updateTOtalQty, 'p_qty' => $totalPqty_history, 'qty_used' => $rdata['quantity'], 'type' => $activitiesType, 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id']);
            }
        }
        // echo '<pre>';
        //  print_r($array_added); die;
        if (!empty($item_inventory_history)) {
            // $this->db->insert('item_inventory_history',$item_inventory_history);
        }

        if (!empty($item_inventory_history2) && !empty($array_added)) {
            // $this->db->insert_batch('item_inventory_history',$item_inventory_history2);

            $this->db->insert_batch('inventory_damage', $array_added);
            $this->db->insert_batch('inventory_activity', $activitiesArr);
        }

        /* if(!empty($array_added))
          {
          // $this->db->insert_batch('item_inventory_history',$item_inventory_history2);

          $this->db->insert_batch('inventory_damage',$array_added);
          $this->db->insert_batch('inventory_activity',$activitiesArr);
          } */
        return true;
    }

    public function GetcheckvalidPalletNo($shelv_no = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('warehous_shelve_no_fm');
        $this->db->where('shelv_no', $shelv_no);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else
            return false;
    }

    public function GetcheckPalletInventry($shelve_no = null, $seller_id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,seller_id');
        $this->db->from('item_inventory');
        $this->db->where('shelve_no', $shelve_no);

        $query = $this->db->get();
        //echo  $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function UpdateInventoryPallet($data = array(), $tid = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->where('id', $tid);
        return $this->db->update('item_inventory', $data);
        // echo $this->db->last_query(); die;
    }

    public function all() {


        $this->db->select('item_inventory.id , items_m.sku , item_inventory.quantity,item_inventory.update_date , items_m.name,seller_m.name as seller_name,items_m.description as item_description');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->order_by('item_inventory.id', 'DESC');

        $query = $this->db->get();


        if ($query->num_rows() > 0) {

            return $query->result();
        }
    }

    public function add_view() {

        $data['items'] = $this->Item_model->all();

        $data['sellers'] = $this->Seller_model->all();


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
        $this->db->where('item_inventory.id', $id);
        $this->db->select('item_inventory.id , items_m.sku , item_inventory.quantity,item_inventory.update_date , items_m.name,seller_m.name as seller_name');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            // echo '<pre>';
            //   print_r( $query->result());
            //   echo '</pre>';
            //   exit();
            return $query->result();
        }
    }

    public function updateShelve($id, $data) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('id', $id);
        $this->db->update('item_inventory', $data);
        //return $this->db->last_query(); die;
    }

    public function edit($id, $data, $previous_data) {


        if ($previous_data[0]->quantity < $data['quantity']) {
            $status = "Quantity Increase";
        } elseif ($data['quantity'] == $previous_data[0]->quantity) {
            $status = "Nothing Changed";
        } elseif ($previous_data[0]->quantity > $data['quantity']) {
            $status = "Quantity Decrease";
        }
        $item_inventory_history = array(
            'item_sku' => $previous_data[0]->item_sku,
            'item_previous_quantity' => $previous_data[0]->quantity,
            'item_new_quantity' => $data['quantity'],
            'update_date' => $data['update_date'],
            'seller_id' => $previous_data[0]->seller_id,
            'status' => $status
        );
        // $this->db->insert('item_inventory_history',$item_inventory_history);

        $this->db->where('id', $id);
        return $this->db->update('item_inventory', $data);
    }

    public function check($id) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('item_inventory.stock_location', $id);
        $this->db->select('item_inventory.id , items_m.sku, item_inventory.stock_location  , item_inventory.quantity , items_m.name,item_inventory.shelve_no');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');

        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            // echo '<pre>';
            //   print_r( $query->result());
            //   echo '</pre>';
            //   exit();
            return $query->result();
        }
    }

    public function find($array) {
        // print_r($array['seller_id']);
        // exit();
        // $find=array(
        //   'seller_id'=>$array['seller_id'],
        //   'item_sku'=>$array['item_sku']
        // );
        // exit();
        $this->db->where($array);
        $query = $this->db->get('item_inventory');
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {

            return $query->result();
        }
    }

    public function find_by_sku($sku) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id , items_m.sku , item_inventory.quantity,item_inventory.update_date , items_m.name,seller_m.name as seller_name,items_m.description as item_description');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->order_by('item_inventory.id', 'DESC');
        $this->db->where('items_m.sku', $sku);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {

            return $query->result();
        }
    }

    public function GetalllocationsData($sku) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('stock_location');
        $this->db->from('item_inventory');
        $this->db->where('item_sku', $sku);
        $this->db->where('DATE(expity_date)', date("Y-m-d"));
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function count_all($date = null) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->select('quantity')->get('item_inventory');
        //echo $this->db->last_query(); die;
        $count = 0;
        if ($query->num_rows() > 0) {

            for ($i = 0; $i < $query->num_rows(); $i++) {
                $count += $query->result()[$i]->quantity;
            }
            return $count;
        }
    }

    public function count_all_expire($date = null) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where("expity_date<", date('Y-m-d'));
        $this->db->where("expity_date!='0000-00-00'");


        $query = $this->db->select('quantity')->get('item_inventory');
        //echo $this->db->last_query(); die;
        $count = 0;
        if ($query->num_rows() > 0) {

            for ($i = 0; $i < $query->num_rows(); $i++) {
                $count += $query->result()[$i]->quantity;
            }
            return $count;
        }
    }

    public function getInventory($data) {

        // $this->db->where('seller_id',$data['seller_id']);
        //  $this->db->select('item_inventory.id , items_m.sku , item_inventory.quantity,item_inventory.update_date , items_m.name,item_inventory.seller_id');
        // $this->db->from('item_inventory');
        // $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        // // $array=array(
        // //   'seller_id'=>$data['seller_id'],
        // //  // 'item_sku'=>$data['item_sku']
        // // );
        // // $this->db->where($array);
        // // $query=$this->db->get('item_inventory');
        // $query=$this->db->get();
        // if($query->num_rows()>0){
        // echo json_encode($query->result());
        // }
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where($data);
        $query = $this->db->get('item_inventory');
        //$query=$this->db->get();
        if ($query->num_rows() > 0) {

            echo json_encode($query->result());
        }
    }

    public function getInventory2($data) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('seller_id', $data['seller_id']);
        $this->db->select('item_inventory.id , item_sku,items_m.sku , item_inventory.quantity,item_inventory.update_date , items_m.name,item_inventory.seller_id');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');

        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            echo json_encode($query->result());
        }
    }

    public function getInventoyByLimit($data, $sku) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('seller_id', $data);
        $this->db->where('item_sku', $sku);
        $this->db->where('DATE(update_date)', date('Y-m-d'));
        $this->db->select('*');
        $this->db->from('item_inventory');
        $this->db->order_by('item_inventory.id', 'DESC');

        $query = $this->db->get();
        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function count_find($id) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('seller_id', $id);
        $query = $this->db->select('quantity')->get('item_inventory');
        $count = 0;
        if ($query->num_rows() > 0) {

            for ($i = 0; $i < $query->num_rows(); $i++) {
                $count += $query->result()[$i]->quantity;
            }
            return $count;
        }
        return 0;
    }

    // public function find_by_seller($array){
    //   $this->db->where($array);
    //   $query=$this->db->get('item_inventory');
    //    if($query->num_rows()>0){
    //       return $query->result();
    //       }
    //       return 0;
    // }

    public function find_by_seller($array) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        //   print_r($array);
        //   exit();
        $this->db->where($array);
        $this->db->select('item_inventory.id , items_m.sku , item_inventory.quantity,item_inventory.update_date , items_m.name,seller_m.name as seller_name,items_m.description as item_description');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->order_by('item_inventory.id', 'DESC');

        $query = $this->db->get();
        //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {

            return $query->result();
        }
    }

    public function filter_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no, $type) {



        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('inventory_activity.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('inventory_activity.id,inventory_activity.p_qty,inventory_activity.type,items_m.sku , inventory_activity.qty,inventory_activity.qty_used,inventory_activity.entrydate,items_m.name,seller_m.name as seller_name,items_m.description as item_description,users.username,inventory_activity.awb_no,users.id,inventory_activity.user_id as iuser_id,inventory_activity.st_location,items_m.item_path,inventory_activity.shelve_no');
        $this->db->from('inventory_activity');
        $this->db->join('items_m', 'items_m.id = inventory_activity.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_activity.seller_id');
        $this->db->join('user as users', 'users.id = inventory_activity.user_id', 'left');


        $this->db->where("qty_used>0");
        if (!empty($type)) {
            $this->db->where('inventory_activity.type', $type);
        }
        if (!empty($slip_no)) {
            $this->db->where('inventory_activity.awb_no', $slip_no);
        }
        if (!empty($exact)) {
            $this->db->where('DATE(inventory_activity.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(inventory_activity.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('inventory_activity.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        //  $this->db->group_by(array("inventory_activity.awb_no", "inventory_activity.type"));

        $this->db->order_by('inventory_activity.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
        
    }

    public function filterCount_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no) {



        $this->db->where('inventory_activity.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(inventory_activity.id) as idCount');
        $this->db->from('inventory_activity');
        $this->db->join('items_m', 'items_m.id = inventory_activity.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_activity.seller_id');
        $this->db->join('user as users', 'users.id = inventory_activity.user_id', 'left');

        if (!empty($exact)) {
            $this->db->where('DATE(inventory_activity.entrydate)', $exact);
        }
        if (!empty($slip_no)) {
            $this->db->where('inventory_activity.awb_no', $slip_no);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(inventory_activity.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('inventory_activity.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
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

    public function filter($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no = null, $storage_id, $data = array()) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id,item_inventory.item_sku,item_inventory.shelve_no,item_inventory.stock_location , items_m.sku , item_inventory.quantity,item_inventory.update_date,item_inventory.expity_date,item_inventory.expiry , items_m.name,seller_m.name as seller_name,items_m.description as item_description,seller_m.id as sid,item_inventory.wh_id,item_inventory.seller_id,items_m.item_path');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($exact)) {
            $date = date("Y-m-d", strtotime($exact));
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $date = date("Y-m-d", strtotime($from));
            $date = date("Y-m-d", strtotime($to));
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }


        //echo $quantity;

        if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($shelve_no)) {
            $this->db->where('item_inventory.shelve_no', $shelve_no);
        }

        if (!empty($storage_id)) {
            $this->db->where('items_m.storage_id', $storage_id);
        }
        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $data['stock_location']);
        }

        if (!empty($data['wh_name'])) {
            $this->db->where('warehouse_category.name', $data['wh_name']);
        }

        if (!empty($data['item_description'])) {
            $this->db->where('items_m.description', $data['item_description']);
        }

        if (!empty($data['update_date'])) {
            $date = date("Y-m-d", strtotime($data['update_date']));
            //$this->db->where("item_inventory.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory.update_date)', $data['update_date']);
        }

        if (!empty($data['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($data['expity_date']));
            $this->db->where('DATE(item_inventory.expity_date)', $expity_date);
        }

        if (!empty($data['expiry'])) {
            $this->db->where('item_inventory.expiry', $data['expiry']);
        }


        $this->db->order_by('item_inventory.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //  echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount($quantity, $sku, $seller, $to, $from, $exact, $page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter_totalview($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no = null, $storage_id) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id,item_inventory.item_sku,item_inventory.shelve_no,item_inventory.stock_location , items_m.sku , SUM(item_inventory.quantity) as quantity,item_inventory.update_date,item_inventory.expity_date,item_inventory.expiry , items_m.name,seller_m.name as seller_name,items_m.description as item_description,seller_m.id as sid,item_inventory.wh_id,items_m.item_path');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }
        $this->db->group_by('item_sku');

        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }


        //echo $quantity;

        if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($shelve_no)) {
            $this->db->where('item_inventory.shelve_no', $shelve_no);
        }

        if (!empty($storage_id)) {
            $this->db->where('items_m.storage_id', $storage_id);
        }
        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }



        $this->db->order_by('seller_m.name');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_viewtotal($quantity, $sku, $seller, $to, $from, $exact, $page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select('COUNT(item_inventory.id) as idCount');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        // $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');

        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
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

    public function filterCount_viewtotal($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select('COUNT(item_inventory.id) as idCount');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');


        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }
        $this->db->group_by('item_sku');

        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
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

    public function add_byManifest($UpdateArray) {
        $status = "Quantity Increase";
        foreach ($UpdateArray as $rdata) {


            $skuUpdateQty = GetallremoveskuQty($rdata['sku'], $rdata['seller_id'], $rdata['uniqueid']);

            if ($skuUpdateQty > 0)
                $addedQTY = $rdata['qty'] - $skuUpdateQty;
            else
                $addedQTY = $rdata['qty'];


            $array = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expire_date'], 'stock_location' => $rdata['stock_location']);
            $array_added[] = array('item_sku' => $rdata['item_sku'], 'seller_id' => $rdata['seller_id'], 'expity_date' => $rdata['expire_date'], 'stock_location' => $rdata['stock_location'], 'quantity' => $addedQTY);
            $status = "Recently Added";

            $this->db->where($array);
            $query = $this->db->get('item_inventory');
            //return $this->db->last_query(); 

            if ($query->num_rows() == 1) {


                $previous_data = $query->result()[0];
                $item_previous_quantity = $previous_data->quantity;
                //print_r($data['quantity']);
                $item_new_quantity = $addedQTY;
                $item_updated_quantity = $item_previous_quantity + $item_new_quantity;
                $new_data = array(
                    'quantity' => $item_updated_quantity,
                    'stock_location' => $rdata['stock_location']
                );

                $item_inventory_history[] = array(
                    'item_sku' => $rdata['item_sku'],
                    'item_previous_quantity' => $item_previous_quantity,
                    'item_new_quantity' => $item_updated_quantity,
                    'update_date' => date("Y/m/d h:i:sa"),
                    'seller_id' => $rdata['seller_id'],
                    'status' => $status
                );

                $this->db->where($array);
                $this->db->update('item_inventory', $new_data);



                //print_r($this->db->update('item_inventory',$new_data));      
                //echo '</pre>';
                //exit();
                //return  $query->result();
            } else {
                //print_r($data);
                //echo "ddd"; die;


                $item_inventory_history2[] = array(
                    'item_sku' => $rdata['item_sku'],
                    'item_previous_quantity' => 0,
                    'item_new_quantity' => $addedQTY,
                    'update_date' => date("Y/m/d h:i:sa"),
                    'seller_id' => $rdata['seller_id'],
                    'status' => $status
                );

                //$this->db->insert('item_inventory_history',$item_inventory_history);
                // return $this->db->insert('item_inventory',$data);
            }
        }
        //return $array_added;

        if (!empty($item_inventory_history)) {
            // $this->db->insert_batch('item_inventory_history',$item_inventory_history);
        }
        if (!empty($item_inventory_history2)) {
            //$this->db->insert_batch('item_inventory_history',$item_inventory_history2);
            $this->db->insert_batch('item_inventory', $array_added);
        }
        return $this->db->last_query();
    }

    public function Getallstocklocationdata($seller_id=null, $locationLimit=null, array $data) {
        //print_r($data);
        // echo $locationLimit;
        //$this->db->distinct();
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('`stock_location` NOT IN (SELECT `stock_location` FROM `item_inventory`)', NULL, FALSE);
        if (!empty($data))
            $this->db->where_not_in('stock_location', $data);
        $this->db->where('seller_id', $seller_id);
        $query = $this->db->select('id,seller_id,stock_location')->limit($locationLimit, 0)->get('stockLocation');
        // echo $this->db->last_query(); 
        //limit($locationLimit,0)
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function Getallstocklocationdata_viewpage($seller_id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('`stock_location` NOT IN (SELECT `stock_location` FROM `item_inventory`)', NULL, FALSE);
        $this->db->where('seller_id', $seller_id);
        $query = $this->db->select('id,seller_id,stock_location')->get('stockLocation');
        //  echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function GetUpdateStockLocation($data = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $updatearray = array("stock_location" => $data['locationUp']);
        return $this->db->update("item_inventory", $updatearray, array("id" => $data['id'], 'seller_id' => $data['sid']));
    }

    public function GetUpdateOrderafterQty($InventryArray = array(), $InventryArrayWhere = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        return $this->db->update("item_inventory", $InventryArray, $InventryArrayWhere);
    }

    public function GetUpdateOrderafterInboundCharge($orderpickupinvoiceArray = array(), $orderpickupinvoiceArray_where = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        return $this->db->update("orderpickupinvoice", $orderpickupinvoiceArray, $orderpickupinvoiceArray_where);
    }

    public function GetUpdatePickupchargeInvocie($sid, $newlimitcheck, $qty = null, $sku_id = null) {




        $entrydate = date("Y-m-d H:i:sa");
        $noofpallets = $newlimitcheck; //Getallstoragetablefield($data['storagetype'],'no_of_pallet')

        $SingleInboundChage = getalluserfinanceRates($sid, 6, 'rate');
        $Singleinventory_charge = getalluserfinanceRates($sid, 14, 'rate');
        $totalpallets = $noofpallets * $noofpallets;

        $totalInboundChage = $SingleInboundChage * $noofpallets;
        $totalinventoryCharge = $Singleinventory_charge * $noofpallets;
        $addedArray = array('seller_id' => $sid, 'no_of_pallets' => $noofpallets, 'inbound_charge' => $totalInboundChage, 'entrydate' => $entrydate, 'qty_count' => $qty, 'inventory_charge' => $totalinventoryCharge, 'sku_id' => $sku_id, 'super_id' => $this->session->userdata('user_details')['super_id']);
        //return $addedArray;
        $this->db->insert("orderpickupinvoice", $addedArray);
        //echo $this->db->last_query(); die;
    }

    public function GetUpdatePickupchargeInvocieUpdateQty($sid, $newlimitcheck, $qty = null, $sku_id = null) {


        $sql = "SELECT id,qty_count FROM `orderpickupinvoice` WHERE `sku_id` = '" . $sku_id . "' AND `seller_id` = '" . $sid . "' and super_id='" . $this->session->userdata('user_details')['super_id'] . "' order by id desc limit 1";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $result[0]['id'];
        $data = array('qty_count' => ($result[0]['qty_count'] + $qty));

        $this->db->where('id', $result[0]['id']);
        $this->db->update('orderpickupinvoice', $data);
        //echo $this->db->last_query(); die;
    }

    public function filterRecord($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        //`id`, `pickup_id`, `pickupcharge`, `seller_id`, `entrydate`, `storage_id`, `no_of_pallets`, `inbound_charge`, `qty_count`, `inventory_charge`, `sku_id` 
        $this->db->where('orderpickupinvoice.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('orderpickupinvoice.id,orderpickupinvoice.sku_id,orderpickupinvoice.inbound_charge ,orderpickupinvoice.inventory_charge, items_m.sku , orderpickupinvoice.qty_count,DATE(orderpickupinvoice.entrydate) as entrydate,orderpickupinvoice.no_of_pallets , items_m.name,seller_m.name as seller_name,items_m.sku_size as size,seller_m.id as sid,items_m.item_path');
        $this->db->from('orderpickupinvoice');
        $this->db->join('items_m', 'items_m.id = orderpickupinvoice.sku_id');
        $this->db->join('customer as seller_m', 'seller_m.id = orderpickupinvoice.seller_id');


        if (!empty($exact)) {
            $this->db->where('DATE(orderpickupinvoice.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(orderpickupinvoice.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('orderpickupinvoice.qty_count', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }



        $this->db->order_by('orderpickupinvoice.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterRecordCount($quantity, $sku, $seller, $to, $from, $exact, $page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterRecordCount($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(orderpickupinvoice.id) as idCount');
        $this->db->from('orderpickupinvoice');
        $this->db->join('items_m', 'items_m.id = orderpickupinvoice.sku_id');
        $this->db->join('customer as seller_m', 'seller_m.id = orderpickupinvoice.seller_id');


        if (!empty($exact)) {
            $this->db->where('DATE(orderpickupinvoice.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(orderpickupinvoice.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('orderpickupinvoice.qty_count', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
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

    public function UpdateInventoryMissing($data = array(), $tid = null) {
        $this->db->where('id', $tid);
        return $this->db->update('item_inventory', $data);
        //echo $this->db->last_query(); die;  
    }

    public function GetdeleteRunQuery($id = null) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('quantity');
        $this->db->from('item_inventory');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        if ($result['quantity'] == 0) {
            return $this->db->query("DELETE FROM `item_inventory` WHERE id='$id' and quantity=0");
        }
    }

    public function filterexcelinventory($filterArr = array()) {
        $page_no;
        $limit = 2000;
        $start = $filterArr['exportlimit'] - $limit;

        //echo "ssssss"; die;
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.*,items_m.sku ,items_m.name,seller_m.name as seller_name,items_m.description as item_description,seller_m.id as sid');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');



        if (!empty($filterArr['exact'])) {
            $this->db->where('DATE(item_inventory.update_date)', $filterArr['exact']);
        }


        if (!empty($filterArr['from']) && !empty($filterArr['to'])) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $filterArr['from'] . "' AND '" . $filterArr['to'] . "'";
            $this->db->where($where);
        }




        if (!empty($filterArr['quantity'])) {
            $this->db->where('item_inventory.quantity', $filterArr['quantity']);
        }

        if (!empty($filterArr['shelve_no'])) {
            $this->db->where('item_inventory.shelve_no', $filterArr['shelve_no']);
        }

        if (!empty($filterArr['sku'])) {
            $this->db->where('items_m.sku', $filterArr['sku']);
        }

        if (!empty($filterArr['seller'])) {
            $this->db->where('seller_m.id', $filterArr['seller']);
        }



        $this->db->order_by('item_inventory.id', 'DESC');

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

    public function filterexcelinventory_totalView($filterArr = array()) {
        $page_no;
        $limit = 2000;
        $start = $filterArr['exportlimit'] - $limit;

        //echo "ssssss"; die;
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id,item_inventory.item_sku,item_inventory.shelve_no,item_inventory.stock_location , items_m.sku , SUM(item_inventory.quantity) as quantity,item_inventory.update_date,item_inventory.expity_date,item_inventory.expiry , items_m.name,seller_m.name as seller_name,items_m.description as item_description,seller_m.id as sid,item_inventory.wh_id');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');



        if (!empty($filterArr['exact'])) {
            $this->db->where('DATE(item_inventory.update_date)', $filterArr['exact']);
        }


        if (!empty($filterArr['from']) && !empty($filterArr['to'])) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $filterArr['from'] . "' AND '" . $filterArr['to'] . "'";
            $this->db->where($where);
        }

        $this->db->group_by('item_sku');




        if (!empty($filterArr['quantity'])) {
            $this->db->where('item_inventory.quantity', $filterArr['quantity']);
        }

        if (!empty($filterArr['shelve_no'])) {
            $this->db->where('item_inventory.shelve_no', $filterArr['shelve_no']);
        }

        if (!empty($filterArr['sku'])) {
            $this->db->where('items_m.sku', $filterArr['sku']);
        }

        if (!empty($filterArr['seller'])) {
            $this->db->where('seller_m.id', $filterArr['seller']);
        }



        $this->db->order_by('seller_m.name');

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

    public function filterexcelhistinventory($filterArr = array()) {
        $page_no;
        $limit = 2000;
        $start = $filterArr['exportlimit'] - $limit;

        $this->db->where('inventory_activity.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('inventory_activity.id,inventory_activity.p_qty,inventory_activity.type,items_m.sku , inventory_activity.qty,inventory_activity.qty_used,inventory_activity.entrydate,items_m.name,seller_m.name as seller_name,items_m.description as item_description,users.username,inventory_activity.awb_no,users.user_id,inventory_activity.user_id as iuser_id');
        $this->db->from('inventory_activity');
        $this->db->join('items_m', 'items_m.id = inventory_activity.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_activity.seller_id');
        $this->db->join('user_fm as users', 'users.user_id = inventory_activity.user_id', 'left');


        if (!empty($filterArr['exact'])) {
            $this->db->where('DATE(inventory_activity.entrydate)', $filterArr['exact']);
        }


        if (!empty($filterArr['from']) && !empty($filterArr['to'])) {
            $where = "DATE(inventory_activity.entrydate) BETWEEN '" . $filterArr['from'] . "' AND '" . $filterArr['to'] . "'";
            $this->db->where($where);
        }




        if (!empty($filterArr['quantity'])) {
            $this->db->where('inventory_activity.qty', $filterArr['quantity']);
        }

        if (!empty($filterArr['sku'])) {
            $this->db->where('items_m.sku', $filterArr['sku']);
        }

        if (!empty($filterArr['seller'])) {
            $this->db->where('seller_m.id', $filterArr['seller']);
        }



        $this->db->order_by('inventory_activity.id', 'DESC');



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

    public function SellerDropDataQry($id = null) {
        if (!empty($id))
            $this->db->where("id!='$id'");
        $this->db->order_by('id', 'desc');
        $query = $this->db->get('seller_m');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function GetsellerAllSKuQry($id = null) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where("item_inventory.seller_id", $id);
        $this->db->select('SUM(item_inventory.quantity) as quantity,item_sku,items_m.sku');
        $this->db->from('item_inventory');
        // $this->db->where("item_inventory.quantity>0");
        $this->db->join('items_m', 'item_inventory.item_sku=items_m.id');
        $this->db->group_by('item_inventory.item_sku');
        $query = $this->db->get();
        //print_r($query->result());
        //echo $this->db->last_query(); 
        return $query->result();
    }

    public function GetsellerAllSKuLocationQry($id = null) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where("item_inventory.seller_id", $id);
        $this->db->select('item_inventory.id,item_inventory.quantity,item_inventory.stock_location,item_inventory.shelve_no,item_inventory.item_sku,items_m.sku');
        $this->db->where("item_inventory.quantity>0");
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'item_inventory.item_sku=items_m.id');
        //$this->db->group_by('item_inventory.item_sku');
        $query = $this->db->get();

        //echo $this->db->last_query(); die;
        return $query->result();
    }

    public function filter_history_transfered($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('sku_transfer.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('sku_transfer.fitem_sku,sku_transfer.fromI_id,sku_transfer.`id`, sku_transfer.`from_id`, sku_transfer.`to_id`, sku_transfer.`item_sku`, sku_transfer.`qty`, sku_transfer.`location_st`, sku_transfer.`entry_date`, sku_transfer.`transfer_by`,items_m.sku,users.username');
        $this->db->from('sku_transfer');
        $this->db->join('items_m', 'items_m.id = sku_transfer.item_sku');
        $this->db->join('user_fm as users', 'users.user_id = sku_transfer.transfer_by', 'left');


        if (!empty($exact)) {
            $this->db->where('DATE(sku_transfer.entry_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(sku_transfer.entry_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }

        if (!empty($quantity)) {
            $this->db->where('sku_transfer.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        $this->db->order_by('sku_transfer.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //  echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_history_transfered($quantity, $sku, $seller, $to, $from, $exact, $page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount_history_transfered($quantity, $sku, $seller, $to, $from, $exact, $page_no) {


        $this->db->where('sku_transfer.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(sku_transfer.id) as idCount');
        $this->db->from('sku_transfer');
        $this->db->join('items_m', 'items_m.id = sku_transfer.item_sku');
        $this->db->join('user_fm as users', 'users.user_id = sku_transfer.transfer_by', 'left');
        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->row_array()['idCount'];
            // return $page_no.$this->db->last_query();
        } else {
            return 0;
        }
    }

    public function GetstorageTypes() {
        $this->db->where('storage_table.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('storage_table');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();


        return $query->result_array();
    }

    public function InventoryStockMinusQry(array $posts, $oldQty) {
        $updateQty = $oldQty - $posts['qty'];
        $updates = "update item_inventory set quantity='" . $updateQty . "' where id='" . $posts['fi_id'] . "'";
        $this->db->query($updates);
    }

    public function UpdateTransferHistoryQry(array $data) {
        $this->db->insert('sku_transfer', $data);
    }

    public function alllistexcelDataInventoryHistory($data = array(), $filterData = array()) {

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

        $this->db->where('inventory_activity.super_id', $this->session->userdata('user_details')['super_id']);

        if (!empty($filterData['type'])) {
            $this->db->where('inventory_activity.type', $filterData['type']);
        }
        if (!empty($filterData['slip_no'])) {
            $this->db->where('inventory_activity.awb_no', $filterData['slip_no']);
        }
        if (!empty($filterData['exact'])) {
            $this->db->where('DATE(inventory_activity.entrydate)', $filterData['exact']);
        }


        if (!empty($filterData['from']) && !empty($filterData['to'])) {
            $where = "DATE(inventory_activity.entrydate) BETWEEN '" . $filterData['from'] . "' AND '" . $filterData['to'] . "'";
            $this->db->where($where);
        }




        if (!empty($filterData['quantity'])) {
            $this->db->where('inventory_activity.qty', $filterData['quantity']);
        }

        if (!empty($filterData['sku'])) {
            $this->db->where('items_m.sku', $filterData['sku']);
        }

        if (!empty($filterData['seller'])) {
            $this->db->where('seller_m.id', $filterData['seller']);
        }

        if (!empty($filterData['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $filterData['stock_location']);
        }

        if (!empty($filterData['wh_name'])) {
            $this->db->where('warehouse_category.name', $filterData['wh_name']);
        }

        if (!empty($filterData['item_description'])) {
            $this->db->where('items_m.description', $filterData['item_description']);
        }

        if (!empty($filterData['update_date'])) {
            $date = date("Y-m-d", strtotime($filterData['update_date']));
            //$this->db->where("item_inventory.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory.update_date)', $filterData['update_date']);
        }

        if (!empty($filterData['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($filterData['expity_date']));
            $this->db->where('DATE(item_inventory.expity_date)', $expity_date);
        }

        if (!empty($filterData['expiry'])) {
            $this->db->where('item_inventory.expiry', $filterData['expiry']);
        }


        $selectQry = "";
        if ($data['checked'] == 1) {


            $selectQry .= " (select sku from items_m where items_m.id=inventory_activity.item_sku) AS ITEMSKU ,";
            $selectQry .= " inventory_activity.p_qty AS PREVIOUSQUANTITY,";
            $selectQry .= " inventory_activity.qty AS NEWQUANTITY,";
            $selectQry .= " inventory_activity.qty_used AS QUANTITYUSED,";
            $selectQry .= " (select name from customer where customer.id=inventory_activity.seller_id) AS SELLERNAME,";
            $selectQry .= " (select username from user where user.id=inventory_activity.user_id) AS UPDATEDBY,";
            $selectQry .= " inventory_activity.entrydate AS ENTRYDATE,";
            $selectQry .= " inventory_activity.type AS STATUS,";
            $selectQry .= " inventory_activity.awb_no AS AWBNO,";
            $selectQry .= " inventory_activity.st_location AS STOCKLOCATION,";
        } else {

            if ($data['p_qty'] == 1)
                $selectQry .= "inventory_activity.p_qty AS PREVIOUSQUANTITY,";
            if ($data['sku'] == 1)
                $selectQry .= " (select sku from items_m where items_m.id=inventory_activity.item_sku) AS ITEMSKU ,";

            if ($data['st_location'] == 1) {
                $selectQry .= " inventory_activity.st_location AS STOCKLOCATION ,";
                //$selectQry.=" country.city AS ORIGIN,";
                //$this->db->join('country','country.id=shipment_fm.origin');
            }
            if ($data['qty'] == 1) {
                $selectQry .= " inventory_activity.qty AS NEWQUANTITY,";
            }

            if ($data['qty_used'] == 1) {
                $selectQry .= " inventory_activity.qty_used AS QUANTITYUSED,";
            }
            if ($data['username'] == 1)
                $selectQry .= " (select username from user where user.id=inventory_activity.user_id) AS UPDATEDBY,";

            if ($data['entrydate'] == 1)
                $selectQry .= " inventory_activity.entrydate AS ENTRYDATE,";
            if ($data['seller_name'] == 1)
                $selectQry .= " (select name from customer where customer.id=inventory_activity.seller_id) AS SELLERNAME,";
            if ($data['type'] == 1)
                $selectQry .= " inventory_activity.type AS STATUS,";
            if ($data['awb_no'] == 1)
                $selectQry .= " inventory_activity.awb_no AS AWBNO,";
        }
        $selectQry = rtrim($selectQry, ',');
        $this->db->select($selectQry);

        $this->db->from('inventory_activity');
        //  $this->db->join('items_m', 'items_m.id = inventory_activity.item_sku');
        $this->db->order_by('inventory_activity.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //  echo $this->db->last_query(); die();              
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename.csv";



        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function alllistexcelData($data = array(), $filterData = array()) {

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
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);



        if (!empty($filterData['exact'])) {
            $this->db->where('DATE(item_inventory.update_date)', $filterData['exact']);
        }


        if (!empty($filterData['from']) && !empty($filterData['to'])) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $filterData['from'] . "' AND '" . $filterData['to'] . "'";
            $this->db->where($where);
        }




        if (!empty($filterData['quantity'])) {
            $this->db->where('item_inventory.quantity', $filterData['quantity']);
        }

        if (!empty($filterData['shelve_no'])) {
            $this->db->where('item_inventory.shelve_no', $filterData['shelve_no']);
        }

        if (!empty($filterData['sku'])) {
            $this->db->where('items_m.sku', $filterData['sku']);
        }

        if (!empty($filterData['seller'])) {
            $this->db->where('seller_m.id', $filterData['seller']);
        }

        if (!empty($filterData['storage_id'])) {
            $this->db->where('items_m.storage_id', $filterData['storage_id']);
        }

        $selectQry = "";
        if ($data['checked'] == 1) {


            $selectQry .= " (select name from items_m where items_m.id=item_inventory.item_sku) AS NAME  ,";
            $selectQry .= " (select type from items_m where items_m.id=item_inventory.item_sku) AS ITEMTYPE,";
            $selectQry .= " (select storage_type from storage_table where storage_table.id=items_m.storage_id) AS STORAGETYPE,";
            $selectQry .= " (select sku from items_m where items_m.id=item_inventory.item_sku) AS ITEMSKU ,";
            $selectQry .= " item_inventory.shelve_no AS PALLET NO,";
            $selectQry .= " item_inventory.stock_location AS STOCKLOCATION,";
            $selectQry .= " (select name from warehouse_category where warehouse_category.id=item_inventory.wh_id) AS WAREHOUSE ,";
            $selectQry .= " item_inventory.quantity AS QUANTITY,";
            $selectQry .= " (select name from customer where customer.id=item_inventory.seller_id) AS SELLERNAME,";
            $selectQry .= "(select description from items_m where items_m.id=item_inventory.item_sku) AS DESCRIPTION,";
            $selectQry .= " item_inventory.update_date AS UPDATEDDATE,";
            $selectQry .= " item_inventory.expiry AS EXPIRY STATUS,";
            $selectQry .= " item_inventory.expity_date AS EXPIRYDATE,";
        } else {
            if ($data['name'] == 1)
                $selectQry .= " (select name from items_m where items_m.id=item_inventory.item_sku) AS NAME , ";

            if ($data['item_type'] == 1)
                $selectQry .= "(select type from items_m where items_m.id=item_inventory.item_sku) AS ITEMTYPE,";
            if ($data['sku'] == 1)
                $selectQry .= " (select sku from items_m where items_m.id=item_inventory.item_sku) AS ITEMSKU ,";
            if ($data['sku'] == 1)
                $selectQry .= " shipment_fm.shippers_ref_no AS SHIPPER REF No,";

            if ($data['stock_location'] == 1) {
                $selectQry .= " item_inventory.stock_location AS STOCKLOCATION ,";
                //$selectQry.=" country.city AS ORIGIN,";
                //$this->db->join('country','country.id=shipment_fm.origin');
            }
            if ($data['storage_id'] == 1) {
                $selectQry .= " (select storage_type from storage_table where storage_table.id=items_m.storage_id) AS STORAGETYPE,";
                //$selectQry.=" country.city AS ORIGIN,";
                //$this->db->join('country','country.id=shipment_fm.origin');
            }

            if ($data['shelve_no'] == 1) {
                $selectQry .= " item_inventory.shelve_no AS PALLET NO ,";
                //$this->db->join('country','country.id=shipment_fm.destination');    
            }
            if ($data['wh_name'] == 1)
                $selectQry .= " (select name from warehouse_category where warehouse_category.id=item_inventory.wh_id) AS WAREHOUSE,";
            if ($data['quantity'] == 1)
                $selectQry .= " item_inventory.quantity AS QUANTITY,";
            if ($data['seller_name'] == 1)
                $selectQry .= " (select name from customer where customer.id=item_inventory.seller_id) AS SELLERNAME,";
            if ($data['item_description'] == 1)
                $selectQry .= " (select description from items_m where items_m.id=item_inventory.item_sku) AS DESCRIPTION,";
            if ($data['update_date'] == 1)
                $selectQry .= " item_inventory.update_date AS UPDATEDDATE,";
            if ($data['expiry'] == 1)
                $selectQry .= " item_inventory.expiry AS EXPIRY STATUS,";
            if ($data['expity_date'] == 1)
                $selectQry .= "item_inventory.expity_date AS EXPIRYDATE,";
        }
        $selectQry = rtrim($selectQry, ',');
        $this->db->select($selectQry);

        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->order_by('item_inventory.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query(); die();      
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename.csv";



        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function Getlessqtydataquery($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('item_inventory.id,item_inventory.item_sku ,items_m.less_qty, items_m.sku , SUM(item_inventory.quantity) as quantity,item_inventory.expity_date,item_inventory.expiry , items_m.name,seller_m.name as seller_name,seller_m.id as sid');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');


        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }
        $this->db->group_by('item_inventory.item_sku');
        $this->db->having("SUM(quantity)<(SELECT less_qty from items_m where id=item_sku)");

        $this->db->order_by('item_inventory.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_less($quantity, $sku, $seller, $to, $from, $exact, $page_no, 'less');
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount_less($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select('COUNT(item_inventory.id) as idCount');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        // $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');

        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }

        $this->db->group_by('item_inventory.item_sku');
        $this->db->having("SUM(quantity)<(SELECT less_qty from items_m where id=item_sku)");




        if (!empty($quantity)) {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
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

    public function GetexpirealertDataQuery($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }


        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id,item_inventory.item_sku ,items_m.less_qty, items_m.sku ,item_inventory.quantity,item_inventory.expity_date,item_inventory.expiry , items_m.name,seller_m.name as seller_name,seller_m.id as sid');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');


        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }
        $this->db->where("item_inventory.expity_date<(CURDATE() + INTERVAL (SELECT alert_day from items_m where id=item_sku) DAY)");
        $this->db->where("item_inventory.expity_date>(CURDATE())");

        $this->db->order_by('item_inventory.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_expire($quantity, $sku, $seller, $to, $from, $exact, $page_no, 'exp');
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount_expire($quantity, $sku, $seller, $to, $from, $exact, $page_no) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select('COUNT(item_inventory.id) as idCount');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        // $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');

        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }



        $this->db->where("item_inventory.expity_date<(CURDATE() + INTERVAL (SELECT alert_day from items_m where id=item_sku) DAY)");
        $this->db->where("item_inventory.expity_date>(CURDATE())");




        if (!empty($quantity)) {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
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

    public function Gettopproductdatashow($quantity, $sku, $seller, $to, $from, $exact, $page_no, $data = array()) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }


        if (!empty($data['year'])) {
            $this->db->where('YEAR(entry_date)', $data['year']);
        }

        if (!empty($data['month'])) {
            $this->db->where('MONTH(entry_date)', $data['month']);
        }

        $this->db->where('diamention.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('SUM(diamention.piece) as tqty,diamention.sku,seller_m.name');
        $this->db->from('diamention_fm as diamention');
        //  $this->db->join('items_m', 'items_m.id = diamention.sku');
        $this->db->join('customer as seller_m', 'seller_m.id = diamention.cust_id');

        if (!empty($sku)) {
            $this->db->where('diamention.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('diamention.cust_id', $seller);
        }
        $this->db->group_by('diamention.sku');
        $this->db->having("SUM(diamention.piece)>0");

        $this->db->order_by('tqty', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); 
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_protop($quantity, $sku, $seller, $to, $from, $exact, $page_no, 'protop', $data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount_protop($quantity, $sku, $seller, $to, $from, $exact, $page_no, $pagetype, $data = array()) {


        $this->db->where('diamention.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(diamention.id) as idCount');
        $this->db->from('diamention_fm as diamention');
        //  $this->db->join('items_m', 'items_m.id = diamention.sku');
        $this->db->join('customer as seller_m', 'seller_m.id = diamention.cust_id');


        if (!empty($data['year'])) {
            $this->db->where('YEAR(entry_date)', $data['year']);
        }

        if (!empty($data['month'])) {
            $this->db->where('MONTH(entry_date)', $data['month']);
        }

        if (!empty($sku)) {
            $this->db->where('diamention.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('diamention.cust_id', $seller);
        }
        $this->db->group_by('diamention.sku');
        $this->db->having("SUM(diamention.piece)>0");

        $this->db->order_by('diamention.piece', 'DESC');



        $query = $this->db->get();

        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {


            return $query->num_rows();
            // return $page_no.$this->db->last_query();
        } else {
            return 0;
        }
    }

    public function filter_shelve($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no = null, $storage_id, $data = array()) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id,item_inventory.item_sku,item_inventory.shelve_no,item_inventory.stock_location , items_m.sku , SUM(item_inventory.quantity) as quantity,item_inventory.update_date,item_inventory.expity_date,item_inventory.expiry , items_m.name,seller_m.name as seller_name,items_m.description as item_description,seller_m.id as sid,item_inventory.wh_id,item_inventory.seller_id');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($exact)) {
            $date = date("Y-m-d", strtotime($exact));
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $date = date("Y-m-d", strtotime($from));
            $date = date("Y-m-d", strtotime($to));
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }


        //echo $quantity;
        $this->db->where('item_inventory.shelve_no!=', '');
        $this->db->group_by('item_inventory.shelve_no');

        if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($shelve_no)) {
            $this->db->where('item_inventory.shelve_no', $shelve_no);
        }

        if (!empty($storage_id)) {
            $this->db->where('items_m.storage_id', $storage_id);
        }
        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $data['stock_location']);
        }

        if (!empty($data['wh_name'])) {
            $this->db->where('warehouse_category.name', $data['wh_name']);
        }

        if (!empty($data['item_description'])) {
            $this->db->where('items_m.description', $data['item_description']);
        }

        if (!empty($data['update_date'])) {
            $date = date("Y-m-d", strtotime($data['update_date']));
            //$this->db->where("item_inventory.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory.update_date)', $data['update_date']);
        }

        if (!empty($data['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($data['expity_date']));
            $this->db->where('DATE(item_inventory.expity_date)', $expity_date);
        }

        if (!empty($data['expiry'])) {
            $this->db->where('item_inventory.expiry', $data['expiry']);
        }


        $this->db->order_by('item_inventory.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //  echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_shelve($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no, $storage_id, $data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
    public function filterCount_shelve($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no = null, $storage_id, $data = array())
    {
        
          $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(item_inventory.id) as tcount');
        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($exact)) {
            $date = date("Y-m-d", strtotime($exact));
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $date = date("Y-m-d", strtotime($from));
            $date = date("Y-m-d", strtotime($to));
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }


        //echo $quantity;
        $this->db->where('item_inventory.shelve_no!=', '');
        $this->db->group_by('item_inventory.shelve_no');

        if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($shelve_no)) {
            $this->db->where('item_inventory.shelve_no', $shelve_no);
        }

        if (!empty($storage_id)) {
            $this->db->where('items_m.storage_id', $storage_id);
        }
        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $data['stock_location']);
        }

        if (!empty($data['wh_name'])) {
            $this->db->where('warehouse_category.name', $data['wh_name']);
        }

        if (!empty($data['item_description'])) {
            $this->db->where('items_m.description', $data['item_description']);
        }

        if (!empty($data['update_date'])) {
            $date = date("Y-m-d", strtotime($data['update_date']));
            //$this->db->where("item_inventory.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory.update_date)', $data['update_date']);
        }

        if (!empty($data['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($data['expity_date']));
            $this->db->where('DATE(item_inventory.expity_date)', $expity_date);
        }

        if (!empty($data['expiry'])) {
            $this->db->where('item_inventory.expiry', $data['expiry']);
        }
        


        $this->db->order_by('item_inventory.id', 'DESC');


      

        $query = $this->db->get();
       // echo $this->db->last_query(); die;
          if ($query->num_rows() > 0) {
              return $query->num_rows();
              
          }
          else{
              return 0;
          }
        
    }

    public function filter_shelve_details_Query($data = array()) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id,item_inventory.shelve_no,item_inventory.stock_location , items_m.sku , item_inventory.quantity,item_inventory.expity_date,item_inventory.expiry ,items_m.name,seller_m.name as seller_name,items_m.description as item_description,seller_m.id as sid,item_inventory.wh_id,item_inventory.seller_id,warehouse_category.name as w_name');
        $this->db->from('item_inventory');
        $this->db->where('item_inventory.seller_id', $data['seller_id']);
        $this->db->where('item_inventory.shelve_no', $data['shelve_no']);
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');
        $query = $this->db->get();
        $return = $query->result_array();
        return $return;
    }

    public function inventoryCheckQry($data = array()) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.*,customer.name as cust_name,items_m.sku,customer.id as cust_id');
        $this->db->from('item_inventory');
        $this->db->join('customer', 'customer.id = item_inventory.seller_id');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->where('customer.id', $data['cust_name']);
        $this->db->where('item_inventory.stock_location!=', '');
        //$this->db->group_by('item_inventory.seller_id');
        $query = $this->db->get();
        $return = $query->result_array();
        // echo $this->db->last_query(); die;
        return $return;
    }

    public function GetSaveReportInventpryQry($data = array()) {
        return $this->db->insert_batch('inventory_check', $data);
    }

    public function GetshowingSkuMeadiaDataQry($data = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('name,sku,item_path,description');
        $this->db->from('items_m');


        $this->db->where('items_m.sku', $data['sku']);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function filter_damage($data = array()) {




        $limit = 100;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('inventory_damage.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('inventory_damage.id,inventory_damage.quantity, inventory_damage.item_sku,items_m.sku,items_m.name,seller_m.name as seller_name,seller_m.id as sid,inventory_damage.seller_id,items_m.item_path,inventory_damage.order_type,inventory_damage.return_update');
        $this->db->from('inventory_damage');
        $this->db->join('items_m', 'items_m.id = inventory_damage.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_damage.seller_id');

        if (!empty($data['sku'])) {
            $this->db->where('items_m.sku', $data['sku']);
        }


        $this->db->where('inventory_damage.status_type', 'Damage');

        if (!empty($data['seller'])) {
            $this->db->where('seller_m.id', $data['seller']);
        }
        //$this->db->group_by('inventory_damage.item_sku');
        $this->db->order_by('inventory_damage.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_damage($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter_damage_check($data = array()) {


        $this->db->where('inventory_damage.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('SUM(inventory_damage.quantity) as quantity ,inventory_damage.id, inventory_damage.item_sku,items_m.sku,items_m.name,seller_m.name as seller_name,seller_m.id as sid,inventory_damage.seller_id,inventory_damage.order_type');
        $this->db->from('inventory_damage');
        $this->db->join('items_m', 'items_m.id = inventory_damage.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_damage.seller_id');


        $this->db->where_in('inventory_damage.id', $data);

        $this->db->where('inventory_damage.status_type', 'Damage');


        $this->db->group_by('inventory_damage.item_sku');
        $this->db->order_by('inventory_damage.id', 'DESC');




        $query = $this->db->get();

        // echo $this->db->last_query(); die;    


        return $query->result_array();
    }

    public function filterCount_damage($data = array()) {

        $this->db->where('inventory_damage.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('COUNT(inventory_damage.id) as idCount');
        $this->db->from('inventory_damage');
        $this->db->join('items_m', 'items_m.id = inventory_damage.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_damage.seller_id');

        if (!empty($data['sku'])) {
            $this->db->where('items_m.sku', $data['sku']);
        }
        ///$this->db->group_by('inventory_damage.item_sku');
        $this->db->where('inventory_damage.status_type', 'Damage');

        if (!empty($data['seller'])) {
            $this->db->where('seller_m.id', $data['seller']);
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

}
