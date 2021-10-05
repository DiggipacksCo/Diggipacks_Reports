<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class InvocieCron extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper("utility_helper");
    }

    public function Getportelrentelrun() {

        $PickupArray = $this->Getallrenteldata();
        $rentarray = array();
        $currentdate = date("Y-m-d H:i:s");
        foreach ($PickupArray as $data) {
            $rentcharge = $this->getalluserfinanceRates($data['id'], $data['super_id']);
            $rentarray[] = array('seller_id' => $data['id'], 'rentcharge' => $rentcharge, 'entrydate' => $currentdate, 'super_id' => $data['super_id']);
        }
        if (!empty($rentarray)) {
            $matchdate = date("Y-m-d");
            $this->db->select('*');
            $this->db->from('clientportalinvocie');
            $this->db->where('DATE(entrydate)', $matchdate);
            $query = $this->db->get();

            if ($query->num_rows() == 0) {
              //   echo "sssss"; die;
                echo $query1 = $this->db->insert_batch('clientportalinvocie', $rentarray);
            }
        }
       /// echo "<pre>";
      //  print_r($rentarray); die;
    }

    public function GetallusersPalletsNo() {
        $sql = "SELECT $field FROM finance_carges where cat_id='$cat_id' and seller_id='$id'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

    public function getalluserfinanceRates($id = null, $super_id = 0) {
        $sql = "SELECT id FROM item_inventory where seller_id='$id' and super_id='$super_id' and shelve_no!='' group by shelve_no";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return count($result);
    }

    public function Getallrenteldata() {

        $this->db->select('id,super_id');
        $this->db->from('customer');

        $this->db->where('access_fm', 'Y');
        $this->db->where('deleted', 'N');

        $query = $this->db->get();
        // $this->db->last_query();
        return $data = $query->result_array();
    }

    public function getruninvocie() {


        $this->db->select('entrydate');
        $this->db->from("storagesinvoices");
        $this->db->where("deleted=", 0);
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $data = $query->result();
        $res_date = $data[0]->entrydate;
        $t_date = date('Y-m-d');
        $check_date = strtotime($res_date);
        $today_date = strtotime($t_date);
        if ($check_date != $today_date ) {



            $this->db->select('*');
            $this->db->from('customer');
             $this->db->where('access_fm', 'Y');
             $this->db->where('deleted', 'N');
            // $this->db->where('id', '760');
            $querySeller = $this->db->get();
            $SellerArray = $querySeller->result();
            $whole_Data = array();
            foreach ($SellerArray as $rdata) {

                $this->db->select('count(item_inventory.stock_location) as total_sku,item_inventory.item_sku,item_inventory.seller_id,items_m.storage_id,item_inventory.super_id');
                $this->db->from("item_inventory");
                $this->db->join('items_m','items_m.id=item_inventory.item_sku');
                $this->db->where('quantity>0');
                $this->db->where('item_inventory.seller_id', $rdata->id);
                 $this->db->where('item_inventory.super_id', $rdata->super_id);
                $this->db->where("item_inventory.stock_location!=''");
                //$this->db->group_by("storage_id");
                //$this->db->join('seller_m','seller_m.id=item_inventory.seller_id');
                $this->db->group_by(array('items_m.storage_id'));
                $query = $query = $this->db->get();
                //echo $this->db->last_query(); die;
                $resultData=$query->result_array();
                $totalPallet=0;
                $totalRates=0;
                foreach($resultData as $key=>$InventoryRows)
                {
                   $InventoryRows['total_sku'];
                    
                    // $storage_date = $this->get_sku_storage_id($InventoryRows['item_sku'],$InventoryRows['super_id']);
                       $rate = $this->get_rate_by_st_sellid($InventoryRows['seller_id'], $InventoryRows['storage_id'],$InventoryRows['super_id']);
                        $storage_total_rate = $rate *$InventoryRows['total_sku'];
                        $totalRates+=$storage_total_rate;

                        $storage_date = $this->get_sku_storage_id($InventoryRows['item_sku'],$InventoryRows['super_id']); 
                    
                        $storage_id = $storage_date->storage_id;
                        $sku_name = $storage_date->sku;
                       
                       // $rate = $this->get_rate_by_st_sellid($items_data->seller_id, $storage_id,$rdata->super_id);
                        //$storage_total_rate = $rate * $total_sku;
                        $invoic_data = array("storage_id" => $storage_id,
                            "seller_id" => $InventoryRows['seller_id'],
                            "storagerate" => $totalRates,
                            "entrydate" => date("Y-m-d"),
                            "sku" => $sku_name,
                            "pallets" => $InventoryRows['total_sku'],
                             "super_id" => $InventoryRows['super_id'],);
                        array_push($whole_Data, $invoic_data);
                }
                
               // echo $totalPallet; 
                 //echo $this->db->last_query(); die;
              
            }


            // echo '<pre>';
            // print_r($whole_Data); die;

             $this->db->insert_batch('storagesinvoices', $whole_Data);
              echo $this->db->last_query()."<br>";
        }
    }

    public function get_sku_storage_id($item_sku=null,$super_id=null) {
        $this->db->select('storage_id,sku');
        $this->db->from("items_m");
        $this->db->where('id', $item_sku);
         $this->db->where('super_id', $super_id);
        $query = $this->db->get();
       // echo $this->db->last_query();
        $data = $query->result();
        return $data[0];
    }

    public function get_rate_by_st_sellid($seller_id, $storage_id,$super_id) {

        $this->db->select('st_rate.rate');
        $this->db->from('storage_rate_table as st_rate');
        $this->db->where("st_rate.client_id=", $seller_id);
        $this->db->where("st_rate.storage_id", $storage_id);
        $this->db->where("st_rate.super_id", $super_id);
        $this->db->join("storage_table st", "st.id=st_rate.storage_id", "inner");
        $this->db->where("st.deleted=", 'N');
        $this->db->where("st.status=", 'Y');
        $query = $this->db->get();
          // echo $this->db->last_query()."<br>";
        $rate = $query->row('rate');
        return $rate;
    }

    public function getruninvocie11() {
        $todayDate = date("Y-m-d H:i:sa");
        $result = $this->StorageinvocieQuery(date("Y-m-d"));
        $ii = 0;
        //$totalqtysku="";
        //$todayDate=date("Y-m-d H:i:sa");

        $addedArray = array();
        foreach ($result as $rdata) {
            $addedArray[$ii]['sku'] = $rdata['sku'];
            $addedArray[$ii]['qty'] = $rdata['quantity'];
            ;
            $addedArray[$ii]['storage_id'] = $rdata['id'];
            $addedArray[$ii]['storagerate'] = $rdata['rate'];
            $addedArray[$ii]['pallets'] = $rdata['no_of_pallet'];
            $addedArray[$ii]['seller_id'] = $rdata['seller_id'];
            $addedArray[$ii]['entrydate'] = $todayDate;

            $ii++;
        }
        // echo '<pre>';
        //print_r($addedArray); die;
        if (!empty($addedArray)) {
            echo $this->db->insert_batch('storagesinvoices', $addedArray);
        }
    }

    function StorageinvocieQuery($todayDate) {
        $this->db->select('*');
        $this->db->from('storagesinvoices');
        $this->db->where('DATE(entrydate)', $todayDate);
        $query = $this->db->get();
        //echo $this->db->last_query();


        if ($query->num_rows() == 0 || 1 == 1) {
            echo '11';
            $this->db->select('ST.id,IM.sku,ST.id,SRT.rate,ST.no_of_pallet,ITMI.seller_id,ITMI.quantity,ITMI.item_sku');
            $this->db->from('item_inventory as ITMI');
            //$this->db->join('storage_rate_table as SRT', 'ITMI.seller_id = SRT.client_id', 'right outer');
            $this->db->join('storage_table as ST', 'ST.id =SRT.storage_id ', 'right outer');
            $this->db->join('items_m as IM', 'IM.id =ITMI.item_sku ', 'right outer');
            $this->db->where('ITMI.quantity>0');
            //$this->db->group_by('ITMI.item_sku');
            $query = $this->db->get();
            //print_r($this->db->error());
            echo $this->db->last_query();
            die;
            // print_r($query->result_array());
            $data = $query->result_array();
            return $data;
        } else {
            echo '22';
            return array();
        }
    }

    function gettotalskutotalqty($skuid) {
        $this->db->select('SUM(`quantity`) as totalqty');
        $this->db->from('item_inventory');
        $this->db->where('item_sku', $skuid);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['totalqty'];
    }

}

?>