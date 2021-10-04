<?php

class Excel_export_model extends CI_Model {

    function shipment_data() {
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->where('fulfillment', $fulfillment);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,shipment_fm.sender_name,shipment_fm.delivered,shipment_fm.pieces,,shipment_fm.entrydate,');
        $this->db->from('shipment_fm');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function RTS_shipment_data() {

        $fulfillment = 'Y';
        $delivered = 19;
        $deleted = 'N';
        $conditions = array(
            'fulfillment' => $fulfillment,
            'delivered' => $delivered,
            'deleted' => $deleted,
        );

        $this->db->where($conditions);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,shipment_fm.sender_name,shipment_fm.delivered,shipment_fm.pieces,,shipment_fm.entrydate,');
        $this->db->from('shipment_fm');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    function contract_data() {
        $query = $this->db->get("contracts");
        return $query->result();
    }

    function customer_data() {
        $query = $this->db->get("customer");
        return $query->result();
    }

    public function filter($awb, $sku, $delivered, $seller, $to, $from, $exact) {

        $fulfillment = 'Y';
        $deleted = 'N';

        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,customer.name,shipment_fm.entrydate,shipment_fm.sender_name,shipment_fm.sender_phone,shipment_fm.sender_address,shipment_fm.weight,shipment_fm.pieces,shipment_fm.origin,shipment_fm.destination,shipment_fm.total_cod_amt,shipment_fm.entrydate,shipment_fm.reciever_name,shipment_fm.reciever_phone,shipment_fm.reciever_address');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');

        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        if (!empty($exact)) {
            $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }
        if (!empty($delivered)) {
            $this->db->where('shipment_fm.delivered', $delivered);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        if (!empty($sku)) {
            $this->db->where('diamention_fm.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('shipment_fm.cust_id', $seller);
        }

        $this->db->order_by('shipment_fm.id', 'desc');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function forwardShipmentExport($filter) {
        $limit = 5000;
        if (isset($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }
        $selectors_rr = array();
        if (isset($filter['listData2'])) {
            foreach ($filter['listData2'] as $key => $val) {
                $selectors_rr[] = 'shipment_fm.' . $key . " as " . ucfirst(str_replace('_', ' ', $key));
            }

            $selectors = implode(',', $selectors_rr);
        } else {
            $selectors = '*';
        }


        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', 'Y');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where_not_in('shipment_fm.code', 'RTC', 'DL', 'POD', 'C');

        $this->db->select($selectors);
        $this->db->from('shipment_fm');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');

        if (isset($filter['filterData']['destination']) && !empty($filter['filterData']['destination'])) {
            $destination = array_filter($filter['filterData']['destination']);
            $this->db->where_in('shipment_fm.destination', $destination);
        }
        if (isset($filter['filterData']['warehouse']) && !empty($filter['filterData']['warehouse'])) {
            $warehouse = array_filter($filter['filterData']['warehouse']);
            $this->db->where_in('shipment_fm.wh_id', $warehouse);
        }

        if (isset($filter['filterData']['s_type_val']) && !empty($filter['filterData']['s_type_val'])) {
            $this->db->where('shipment_fm.slip_no', $filter['filterData']['s_type_val']);
        }

        if (isset($filter['filterData']['booking_id']) && !empty($filter['filterData']['booking_id'])) {
            $this->db->where_in('booking_id', explode(' ', $filter['filterData']['booking_id']));
        }

        if (isset($filter['filterData']['mode']) && !empty($filter['filterData']['mode'])) {
            $this->db->where('shipment_fm.mode', $filter['filterData']['mode']);
        }
        if (isset($filter['filterData']['sku_val']) && !empty($filter['filterData']['sku_val'])) {
            $this->db->where('shipment_fm.sku', $filter['filterData']['sku_val']);
        }

        if (isset($filter['filterData']['origin']) && !empty($filter['filterData']['origin'])) {
            $this->db->where('diamention_fm.origin', $filter['filterData']['origin']);
        }

        $this->db->order_by('shipment_fm.id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function InboundRecordExport($filter) {
        $limit = 5000;

        if (isset($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }
        $selector = array();
        if (isset($filter['listData2']['seller_name'])) {
            $selector[] = "seller_m.name as Seller Name";
        }
        if (isset($filter['listData2']['sku'])) {
            $selector[] = "items_m.sku as Sku";
        }
        if (isset($filter['listData2']['itype'])) {
            // $selector[] = "items_m.sku";
        }
        if (isset($filter['listData2']['entrydate'])) {
            $selector[] = "orderpickupinvoice.entrydate as Entrydate";
        }
        if (isset($filter['listData2']['qty_count'])) {
            $selector[] = "orderpickupinvoice.qty_count Quantity";
        }
        if (isset($filter['listData2']['no_of_pallets'])) {
            $selector[] = "orderpickupinvoice.no_of_pallets as Pallete";
        }
        if (isset($filter['listData2']['size'])) {
            $selector[] = "items_m.sku_size as Size";
        }

        $selectColumns = implode(',', $selector);


        $this->db->where('orderpickupinvoice.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select($selectColumns);
        $this->db->from('orderpickupinvoice');
        $this->db->join('items_m', 'items_m.id = orderpickupinvoice.sku_id');
        $this->db->join('customer as seller_m', 'seller_m.id = orderpickupinvoice.seller_id');


        if (isset($filter['filterData']['exact']) && !empty($filter['filterData']['exact'])) {
            $this->db->where('DATE(orderpickupinvoice.entrydate)', $filter['filterData']['exact']);
        }

        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['to'])) {
            $this->db->where('DATE(orderpickupinvoice.entrydate) >= ', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['to']) && !empty($filter['filterData']['to'])) {
            $this->db->where('DATE(orderpickupinvoice.entrydate) <= ', $filter['filterData']['to']);
        }

        if (isset($filter['filterData']['quantity']) && !empty($filter['filterData']['quantity'])) {
            $this->db->where('orderpickupinvoice.qty_count', $filter['filterData']['quantity']);
        }

        if (isset($filter['filterData']['sku']) && !empty($filter['filterData']['sku'])) {
            $this->db->where('items_m.sku', $filter['filterData']['sku']);
        }

        if (isset($filter['filterData']['seller']) && !empty($filter['filterData']['seller'])) {
            $this->db->where('seller_m.id', $filter['filterData']['seller']);
        }

        $this->db->order_by('orderpickupinvoice.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();

        //echo $this->db->last_query();    die;

        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function ItemInventoryExport($filter) {

        $this->load->dbutil();
        $limit = 2000;
        if (isset($filter['filterData']['exportlimit']) && !empty($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);

        if (isset($filter['filterData']['seller']) && !empty($filter['filterData']['seller'])) {
            $this->db->where('seller_m.id', $filter['filterData']['seller']);
        }
        if (isset($filter['filterData']['sku']) && !empty($filter['filterData']['sku'])) {
            $this->db->where('items_m.sku', $filter['filterData']['sku']);
        }
        if (isset($filter['filterData']['quantity']) && !empty($filter['filterData']['quantity'])) {
            $this->db->where('item_inventory.quantity', $filter['filterData']['quantity']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['from'])) {
            $this->db->where('DATE(item_inventory.update_date) >=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['to']) && !empty($filter['filterData']['to'])) {
            $this->db->where('DATE(item_inventory.update_date) <=', $filter['filterData']['to']);
        }

        if (isset($filter['filterData']['exact']) && !empty($filter['filterData']['exact'])) {
            $this->db->where('DATE(item_inventory.update_date)', $filter['filterData']['exact']);
        }
        if (isset($filter['filterData']['storage_id']) && !empty($filter['filterData']['storage_id'])) {
            $this->db->where('items_m.storage_id', $filter['filterData']['storage_id']);
        }
        if (isset($filter['filterData']['shelve_no']) && !empty($filter['filterData']['shelve_no'])) {
            $this->db->where('item_inventory.shelve_no', $filter['filterData']['shelve_no']);
        }
        if (isset($filter['filterData']['stock_location']) && !empty($filter['filterData']['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $filter['filterData']['stock_location']);
        }
        if (isset($filter['filterData']['wh_name']) && !empty($filter['filterData']['wh_name'])) {
            $this->db->where('warehouse_category.name', $filter['filterData']['wh_name']);
        }
        if (isset($filter['filterData']['item_description']) && !empty($filter['filterData']['item_description'])) {
            $this->db->where('items_m.description', $filter['filterData']['item_description']);
        }
        if (isset($filter['filterData']['update_date']) && !empty($filter['filterData']['update_date'])) {
            $this->db->where('DATE(item_inventory.update_date)', $filter['filterData']['update_date']);
        }
        if (isset($filter['filterData']['expity_date']) && !empty($filter['filterData']['expity_date'])) {
            $this->db->where('item_inventory.expity_date', $filter['filterData']['expity_date']);
        }


        $selectQry = array();
        if (isset($filter['listData2']['name']) && !empty($filter['listData2']['name'])) {
            $selectQry[] = " (select name from items_m where items_m.id=item_inventory.item_sku) AS Name";
        }
        if (isset($filter['listData2']['sku']) && !empty($filter['listData2']['sku'])) {
            $selectQry[] = " (select sku from items_m where items_m.id=item_inventory.item_sku) AS ItemSku";
        }
        if (isset($filter['listData2']['item_type']) && !empty($filter['listData2']['item_type'])) {
            $selectQry[] = "(select type from items_m where items_m.id=item_inventory.item_sku) AS ItemType";
        }
        if (isset($filter['listData2']['storage_id']) && !empty($filter['listData2']['storage_id'])) {
            $selectQry[] = " (select storage_type from storage_table where storage_table.id=items_m.storage_id) AS StorageType";
        }
        if (isset($filter['listData2']['stock_location']) && !empty($filter['listData2']['stock_location'])) {
            $selectQry[] = " item_inventory.stock_location AS StockLocation";
        }
        if (isset($filter['listData2']['shelve_no']) && !empty($filter['listData2']['shelve_no'])) {
            $selectQry[] = " item_inventory.shelve_no AS Shelve NO";
        }
        if (isset($filter['listData2']['wh_name']) && !empty($filter['listData2']['wh_name'])) {
            $selectQry[] = " (select name from warehouse_category where warehouse_category.id=item_inventory.wh_id) AS Warehouse";
        }
        if (isset($filter['listData2']['quantity']) && !empty($filter['listData2']['quantity'])) {
            $selectQry[] = " item_inventory.quantity AS QUANTITY";
        }
        if (isset($filter['listData2']['seller_name']) && !empty($filter['listData2']['seller_name'])) {
            //$selectQry[] = " (select name from customer where customer.id=item_inventory.seller_id) AS SellerName";
            $selectQry[] = " (select company from customer where customer.id=item_inventory.seller_id) AS SellerName";
        }
        if (isset($filter['listData2']['item_description']) && !empty($filter['listData2']['item_description'])) {
            $selectQry[] = " (select description from items_m where items_m.id=item_inventory.item_sku) AS Description,";
        }
        if (isset($filter['listData2']['update_date']) && !empty($filter['listData2']['update_date'])) {
            $selectQry[] = "item_inventory.update_date as UpdateDate";
        }
        if (isset($filter['listData2']['expity_date']) && !empty($filter['listData2']['expity_date'])) {
            $selectQry[] = "item_inventory.expity_date as ExpityDate";
        }
        if (isset($filter['listData2']['expiry']) && !empty($filter['listData2']['expiry'])) {
            $selectQry[] = "item_inventory.expiry as Expity";
        }

        $select_str = implode(',', $selectQry);

        $this->db->select($select_str);

        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');
        $this->db->order_by('item_inventory.id', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function ViewTotalInventoryExport($filter) {

        $this->load->dbutil();
        $limit = 2000;
        if (isset($filter['filterData']['exportlimit']) && !empty($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);

        if (isset($filter['filterData']['seller']) && !empty($filter['filterData']['seller'])) {
            $this->db->where('seller_m.id', $filter['filterData']['seller']);
        }
        if (isset($filter['filterData']['sku']) && !empty($filter['filterData']['sku'])) {
            $this->db->where('items_m.sku', $filter['filterData']['sku']);
        }
        if (isset($filter['filterData']['quantity']) && !empty($filter['filterData']['quantity'])) {
            $this->db->where('item_inventory.quantity', $filter['filterData']['quantity']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['from'])) {
            $this->db->where('DATE(item_inventory.update_date) >=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['to']) && !empty($filter['filterData']['to'])) {
            $this->db->where('DATE(item_inventory.update_date) <=', $filter['filterData']['to']);
        }

        if (isset($filter['filterData']['exact']) && !empty($filter['filterData']['exact'])) {
            $this->db->where('DATE(item_inventory.update_date)', $filter['filterData']['exact']);
        }
        if (isset($filter['filterData']['storage_id']) && !empty($filter['filterData']['storage_id'])) {
            $this->db->where('items_m.storage_id', $filter['filterData']['storage_id']);
        }
        if (isset($filter['filterData']['shelve_no']) && !empty($filter['filterData']['shelve_no'])) {
            $this->db->where('item_inventory.shelve_no', $filter['filterData']['shelve_no']);
        }
        if (isset($filter['filterData']['stock_location']) && !empty($filter['filterData']['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $filter['filterData']['stock_location']);
        }
        if (isset($filter['filterData']['wh_name']) && !empty($filter['filterData']['wh_name'])) {
            $this->db->where('warehouse_category.name', $filter['filterData']['wh_name']);
        }
        if (isset($filter['filterData']['item_description']) && !empty($filter['filterData']['item_description'])) {
            $this->db->where('items_m.description', $filter['filterData']['item_description']);
        }
        if (isset($filter['filterData']['update_date']) && !empty($filter['filterData']['update_date'])) {
            $this->db->where('DATE(item_inventory.update_date)', $filter['filterData']['update_date']);
        }
        if (isset($filter['filterData']['expity_date']) && !empty($filter['filterData']['expity_date'])) {
            $this->db->where('item_inventory.expity_date', $filter['filterData']['expity_date']);
        }


        $selectQry = array();
        if (isset($filter['listData2']['name']) && !empty($filter['listData2']['name'])) {
            $selectQry[] = " (select name from items_m where items_m.id=item_inventory.item_sku) AS Name";
        }
        if (isset($filter['listData2']['sku']) && !empty($filter['listData2']['sku'])) {
            $selectQry[] = " (select sku from items_m where items_m.id=item_inventory.item_sku) AS ItemSku";
        }
        if (isset($filter['listData2']['item_type']) && !empty($filter['listData2']['item_type'])) {
            $selectQry[] = "(select type from items_m where items_m.id=item_inventory.item_sku) AS ItemType";
        }
        if (isset($filter['listData2']['storage_id']) && !empty($filter['listData2']['storage_id'])) {
            $selectQry[] = " (select storage_type from storage_table where storage_table.id=items_m.storage_id) AS StorageType";
        }
        if (isset($filter['listData2']['stock_location']) && !empty($filter['listData2']['stock_location'])) {
            $selectQry[] = " item_inventory.stock_location AS StockLocation";
        }
        if (isset($filter['listData2']['shelve_no']) && !empty($filter['listData2']['shelve_no'])) {
            $selectQry[] = " item_inventory.shelve_no AS Shelve NO";
        }
        if (isset($filter['listData2']['wh_name']) && !empty($filter['listData2']['wh_name'])) {
            $selectQry[] = " (select name from warehouse_category where warehouse_category.id=item_inventory.wh_id) AS Warehouse";
        }
        if (isset($filter['listData2']['quantity']) && !empty($filter['listData2']['quantity'])) {
            $selectQry[] = " item_inventory.quantity AS QUANTITY";
        }
        if (isset($filter['listData2']['seller_name']) && !empty($filter['listData2']['seller_name'])) {
            $selectQry[] = " (select name from customer where customer.id=item_inventory.seller_id) AS SellerName";
        }
        if (isset($filter['listData2']['item_description']) && !empty($filter['listData2']['item_description'])) {
            $selectQry[] = " (select description from items_m where items_m.id=item_inventory.item_sku) AS Description,";
        }
        if (isset($filter['listData2']['update_date']) && !empty($filter['listData2']['update_date'])) {
            $selectQry[] = "item_inventory.update_date as UpdateDate";
        }
        if (isset($filter['listData2']['expity_date']) && !empty($filter['listData2']['expity_date'])) {
            $selectQry[] = "item_inventory.expity_date as ExpityDate";
        }
        if (isset($filter['listData2']['expiry']) && !empty($filter['listData2']['expiry'])) {
            $selectQry[] = "item_inventory.expiry as Expity";
        }

        $select_str = implode(',', $selectQry);

        $this->db->select($select_str);

        $this->db->from('item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->order_by('seller_m.name', 'asc');
        $this->db->group_by('item_inventory.item_sku');
        $this->db->limit($limit);
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function ViewSlaveExport($filter) {

        $this->load->dbutil();
        $limit = 2000;
        if (isset($filter['filterData']['exportlimit']) && !empty($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }

        $this->db->where('wf.super_id', $this->session->userdata('user_details')['super_id']);

        if (isset($filter['filterData']['shelve']) && !empty($filter['filterData']['shelve'])) {
            $this->db->where('wf.shelv_no', $filter['filterData']['shelve']);
        }
        if (isset($filter['filterData']['city_id']) && !empty($filter['filterData']['city_id'])) {
            $city = array_filter($filter['filterData']['city_id']);
            $this->db->where_in('wf.city_id', $city);
        }


        $selectQry = array();
//        if (isset($filter['listData2']['city_id']) && !empty($filter['listData2']['city_id'])) {
//            $selectQry[] = " cc.city as City";
//        }
//        if (isset($filter['listData2']['country_id']) && !empty($filter['listData2']['country_id'])) {
//            $selectQry[] = " cn.country as Country";
//        }
//        if (isset($filter['listData2']['shelv_location']) && !empty($filter['listData2']['shelv_location'])) {
//            $selectQry[] = " ws.shelv_location as ShelvLocation";
//        }
        if (isset($filter['listData2']['shelv_no']) && !empty($filter['listData2']['shelv_no'])) {
            $selectQry[] = " wf.shelv_no as ShelvNo";
        }

        $select_str = implode(',', $selectQry);

        $this->db->select($select_str);
        $this->db->from('warehous_shelve_no_fm wf');
        $this->db->join('country cn', 'cn.id=wf.country_id', 'left');
        $this->db->join('country cc', 'cc.id=wf.city_id', 'left');
        $this->db->join('warehous_shelve ws', 'ws.id=wf.shelv_location', 'left');
        $this->db->order_by('wf.id', 'ASC');
        $this->db->limit($limit);
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    function historyViewExport($filter) {

        $this->load->dbutil();


        $limit = 2000;
        if (isset($filter['filterData']['exportlimit']) && !empty($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }
        if (isset($filter['filterData']['sku']) && !empty($filter['filterData']['sku'])) {
            $this->db->where('im.sku', $filter['filterData']['sku']);
        }
        if (isset($filter['filterData']['seller']) && !empty($filter['filterData']['seller'])) {
            $this->db->where('sm.id', $filter['filterData']['seller']);
        }
        if (isset($filter['filterData']['slip_no']) && !empty($filter['filterData']['slip_no'])) {
            $this->db->where('ia.awb_no', $filter['filterData']['slip_no']);
        }
        if (isset($filter['filterData']['quantity']) && !empty($filter['filterData']['quantity'])) {
            $this->db->where('ia.qty', $filter['filterData']['quantity']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['from'])) {
            $this->db->where('DATE(ia.entrydate)>=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['to']) && !empty($filter['filterData']['to'])) {
            $this->db->where('DATE(ia.entrydate)<=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['exact']) && !empty($filter['filterData']['exact'])) {
            $this->db->where('DATE(ia.entrydate)', $filter['filterData']['exact']);
        }
        if (isset($filter['filterData']['status']) && !empty($filter['filterData']['status'])) {
            $this->db->where('ia.type', $filter['filterData']['status']);
        }

        $selectQry = array();
        if (isset($filter['listData2']['sku']) && !empty($filter['listData2']['sku'])) {
            $selectQry[] = "im.sku as Sku";
        }
        if (isset($filter['listData2']['qty_used']) && !empty($filter['listData2']['qty_used'])) {
            $selectQry[] = "ia.qty_used as QuantityUsed";
        }
        if (isset($filter['listData2']['p_qty']) && !empty($filter['listData2']['p_qty'])) {
            $selectQry[] = "ia.p_qty as PreviousQuantity";
        }
        if (isset($filter['listData2']['qty']) && !empty($filter['listData2']['qty'])) {
            $selectQry[] = "ia.qty as NewQuantity";
        }
        if (isset($filter['listData2']['seller_name']) && !empty($filter['listData2']['seller_name'])) {
            $selectQry[] = "sm.name as SellerName";
        }
        if (isset($filter['listData2']['username']) && !empty($filter['listData2']['username'])) {
            $selectQry[] = "u.username as Username";
        }
        if (isset($filter['listData2']['entrydate']) && !empty($filter['listData2']['entrydate'])) {
            $selectQry[] = "ia.entrydate as Entrydate";
        }
        if (isset($filter['listData2']['type']) && !empty($filter['listData2']['type'])) {
            $selectQry[] = "ia.type as Status";
        }
        if (isset($filter['listData2']['st_location']) && !empty($filter['listData2']['st_location'])) {
            $selectQry[] = "ia.st_location as StockLocation";
        }
        if (isset($filter['listData2']['awb_no']) && !empty($filter['listData2']['awb_no'])) {
            $selectQry[] = "ia.awb_no as AWB";
        }
        $selectQry = implode(',', $selectQry);
        

        $this->db->where('ia.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select($selectQry);
        $this->db->from('inventory_activity ia');
        $this->db->join('items_m im', 'im.id = ia.item_sku');
        $this->db->join('customer as sm', 'sm.id = ia.seller_id');
        $this->db->join('user as u', 'u.id = ia.user_id', 'left');
        $this->db->limit($limit);
        $query = $this->db->get();
       
        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

}
