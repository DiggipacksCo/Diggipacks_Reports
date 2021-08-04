<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_export extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Shipment_model');
        $this->load->model("excel_export_model");
        $this->load->model("Item_model");
        $this->load->model("Shelve_model");
        $this->load->model('Country_model');
        $this->load->helper('utility');
    }

    // function index()
    // {
    //  $data["shipment_data"] = $this->excel_export_model->shipment_data();
    //  $this->load->view("excel_export_view", $data);
    // }

    function shipments() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            // "Sr.No",
            "AWB No",
            "Item Sku",
            "Status",
            "Quantity",
            "Seller",
            "Date"
        );


        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $shipment_data = $this->excel_export_model->shipment_data();
        $status = $this->Shipment_model->allstatus();
        $item = $this->Item_model->all();
        $seller = $this->Seller_model->all();
        $excel_row = 2;
        $sr = 1;
        foreach ($shipment_data as $shipment) {
            // $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $sr);
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $shipment->slip_no);
            //  if(!empty($item)):
            //   foreach($item as $item_detail): 
            //     if($shipment->sku==$item_detail->id):
            //        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item_detail->sku);
            //     endif;
            //   endforeach;
            // endif;
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $shipment->sku);

            if (!empty($status)):
                foreach ($status as $status_detail):
                    if ($shipment->delivered == $status_detail->id):
                        $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $status_detail->main_status);

                    endif;
                endforeach;
            endif;

            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $shipment->pieces);
// if(!empty($seller)):
//     foreach($seller as $seller_detail): 
//       if($shipment->cust_id==$seller_detail->customer):
//          $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $seller_detail->name);
//       endif;
//     endforeach;
//   endif;
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $shipment->sender_name);


            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $shipment->entrydate);
            $sr++;
            $excel_row++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Shipments.xls"');
        $object_writer->save('php://output');
    }

    public function filtered_excel($filter) {
        // $sku_per_shipment=null;

        $data = explode("&", $filter);
        for ($i = 0; $i < count($data); $i++) {
            $innerData = explode("=", $data[$i]);


            if ($innerData[0] == "exact") {

                $exact = $innerData[1];
            }

            if ($innerData[0] == "awb") {

                $awb = $innerData[1];
            }


            if ($innerData[0] == "sku") {

                $sku = $innerData[1];
            }

            if ($innerData[0] == "from") {

                $from = $innerData[1];
            }

            if ($innerData[0] == "to") {

                $to = $innerData[1];
            }

            if ($innerData[0] == "status") {

                $status = $innerData[1];
            }

            if ($innerData[0] == "seller") {

                $seller = $innerData[1];
            }
        }

        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            // "Sr.No",
            "AWB No",
            "Item Sku",
            "Status",
            "Quantity",
            "Seller",
            "Date"
        );


        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $shipment_data = $this->excel_export_model->filter($awb, $sku, $status, $seller, $to, $from, $exact);

//  echo '<pre>';
//  print_r($shipment_data);
//  echo '</pre>';
// exit();
        $excel_row = 2;
        $sr = 1;
        foreach ($shipment_data as $shipment) {
            // $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $sr);
            $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $shipment->slip_no);
            //  if(!empty($item)):
            //   foreach($item as $item_detail): 
            //     if($shipment->sku==$item_detail->id):
            //        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item_detail->sku);
            //     endif;
            //   endforeach;
            // endif;
            $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $shipment->sku);


            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $shipment->main_status);



            $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $shipment->pieces);
// if(!empty($seller)):
//     foreach($seller as $seller_detail): 
//       if($shipment->cust_id==$seller_detail->customer):
//          $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $seller_detail->name);
//       endif;
//     endforeach;
//   endif;
            $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $shipment->sender_name);


            $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $shipment->entrydate);
            $sr++;
            $excel_row++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Shipments.xls"');
        $object_writer->save('php://output');
    }

    function RTSshipments() {

        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            // "Sr.No",
            "AWB No",
            "Item Sku",
            "Status",
            "Quantity",
            "Seller",
            "Date"
        );


        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $shipment_data = $this->excel_export_model->RTS_shipment_data();
        $status = $this->Shipment_model->allstatus();
        $item = $this->Item_model->all();
        $seller = $this->Seller_model->all();
        $excel_row = 2;
        $sr = 1;
        if (!empty($shipment_data)) {
            foreach ($shipment_data as $shipment) {
                // $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $sr);
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $shipment->slip_no);
                //  if(!empty($item)):
                //   foreach($item as $item_detail): 
                //     if($shipment->sku==$item_detail->id):
                //        $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $item_detail->sku);
                //     endif;
                //   endforeach;
                // endif;
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $shipment->sku);

                if (!empty($status)):
                    foreach ($status as $status_detail):
                        if ($shipment->delivered == $status_detail->id):
                            $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $status_detail->main_status);

                        endif;
                    endforeach;
                endif;

                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $shipment->pieces);
// if(!empty($seller)):
//     foreach($seller as $seller_detail): 
//       if($shipment->cust_id==$seller_detail->customer):
//          $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $seller_detail->name);
//       endif;
//     endforeach;
//   endif;
                $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $shipment->sender_name);


                $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $shipment->entrydate);
                $sr++;
                $excel_row++;
            }

            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Shipments.xls"');
            $object_writer->save('php://output');
        } else {
            redirect('RTS');
        }
    }

    function item_bulk_format() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "Storage Type",
            "Name",
            "Sku",
            "Capacity ",
            "Description",
            "Warehouse",
            "Expire Block",
            "Less Quantity",
            "Expiry days",
            "Color",
            "Length",
            "Width",
            "Height",
            "Image",
            "Weight",
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Item Bulk.xls"');
        $object_writer->save('php://output');
    }

    function Importlocation() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "Hub",
            "City",
            "City Code",
                //"Item Type ",
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Import Location.xls"');
        $object_writer->save('php://output');
    }

    function bulk_format_shelve() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "Shelve No.",
            "City",
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Shelve Upload.xls"');
        $object_writer->save('php://output');
    }

    function Inventory_bulk_format() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "Item SKU",
            "Seller Account No",
            "Quantity",
            "Expire Date(DD-MM-YYYY) ",
            "Shelve No ",
            "Warehouse"
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Item Inventory Upload.xls"');
        $object_writer->save('php://output');
    }

    function add_ItemInventory_bulk() {


        //  die;
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            // echo '<pre>';
            foreach ($object->getWorksheetIterator() as $worksheet) {

                $checkstocklocArray = array();
                $CheckOtherLocation = array();
                $validlocation = array();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $i = 0;
                $invalidlocation = "";
                $totalLocationCount = 0;
                $TotalInsystemStockLocation = 0;
                $palletsA_error = array();
                $anyErrorShow = 1;
                $allRrror = array();
                // echo $highestRow;
                for ($row = 2; $row <= $highestRow; $row++) {


                    $itemskusku = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $selleraccountid = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $itemQty = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                    $expiredate = date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCellByColumnAndRow(3, $row)->getValue())));
                    if (empty($worksheet->getCellByColumnAndRow(3, $row)->getValue()))
                        $expiredate = "0000-00-00";
                    else {
                        $expiredate = date("Y-m-d", strtotime($expiredate));
                    }
                    $palletno = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $warehouseName = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());


                    //$UniqueLocation=GetcheckalreadyLocations($stocklocation,$seller_id);
                    //echo $validskuid."<br>";


                    if (empty($itemskusku)) {
                        $errordata['emptysku'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        $validskuid = GetallitemcheckDuplicate($itemskusku);

                        if ($validskuid == 0) {
                            $anyErrorShow = 1;
                            $errordata['errorsku'][] = $itemskusku;
                            $palletsA_error[] = $row;
                        }
                    }

                    if (empty($warehouseName)) {
                        $errordata['emptywarehouseName'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        $warehouse = Getwarehouse_categoryfield_name($warehouseName, 'id');
                        if ($warehouse == 0) {
                            $anyErrorShow = 1;
                            $errordata['warehouse'][] = $warehouseName;
                            $palletsA_error[] = $row;
                        }
                    }
                    if (empty($selleraccountid)) {
                        $errordata['emptyselleraccountid'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        $seller_id = GetallaccountidBysellerID($selleraccountid);
                        if ($seller_id == 0) {
                            $anyErrorShow = 1;
                            $errordata['seller_id'][] = $selleraccountid;
                            $palletsA_error[] = $row;
                        }
                    }
                    if (empty($itemQty)) {
                        $anyErrorShow = 1;
                        $errordata['emptyqty'][] = $row;
                        $palletsA_error[] = $row;
                    }







           
                    
                    if(empty($palletno))
                    {
                       $errordata['emptypallets'][] = $row;  
                    }
                   else
                   {
                    $PalletsCheck = $this->ItemInventory_model->GetcheckvalidPalletNo($palletno);
                    if ($PalletsCheck == true) {
                        $PalletArrayI = $this->ItemInventory_model->GetcheckPalletInventry($palletno, $seller_id);
                        if (!empty($PalletArrayI)) {
                            // echo $seller_id." ==". $PalletArrayI['seller_id']."<br>";
                            if ($seller_id == $PalletArrayI['seller_id']) {


                                $palletsA = $palletno;
                                $anyErrorShow = 0;
                            } else {
                                if ($seller_id > 0) {

                                    $palletsA_error[] = $row;
                                    $palletsA = "";
                                    $errordata['palletused'][] = $palletno;
                                    $anyErrorShow = 1;
                                }
                            }
                        } else {

                            $palletsA = $palletno;
                            $anyErrorShow = 0;
                        }
                    } else {
                        $anyErrorShow = 1;
                        $errordata['pallets'][] = $palletno;
                        $palletsA_error[] = $row;
                    }
                   }

                    $i++;
                }
             
                
                if(empty($palletsA_error)){
                for ($row = 2; $row <= $highestRow; $row++) {
                    $itemskusku = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $selleraccountid = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $itemQty = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                    $expiredate = date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCellByColumnAndRow(3, $row)->getValue())));
                    if (empty($worksheet->getCellByColumnAndRow(3, $row)->getValue()))
                        $expiredate = "0000-00-00";
                    else {
                        $expiredate = date("Y-m-d", strtotime($expiredate));
                    }
                    $palletno = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $warehouseName = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());

                    $validskuid = GetallitemcheckDuplicate($itemskusku);
                    //$UniqueLocation=GetcheckalreadyLocations($stocklocation,$seller_id);


                    $seller_id = GetallaccountidBysellerID($selleraccountid);

                    $warehouse = Getwarehouse_categoryfield_name($warehouseName, 'id');

                    $PalletsCheck = $this->ItemInventory_model->GetcheckvalidPalletNo($palletno);
                    $palletsA = $palletno;

                    $chargeQty = $itemQty;
                    $ItemskuID = getallitemskubyid($itemskusku);
                    $sku_size = getalldataitemtables($ItemskuID, 'sku_size');
                    $itype = getalldataitemtables($ItemskuID, 'type');

                    $expiredate = $expiredate;
                    $dataNew = $this->ItemInventory_model->find(array('item_sku' => $ItemskuID, 'expity_date' => $expiredate, 'seller_id' => $seller_id, 'itype' => $itype));

                    $qty = $itemQty;

                    if (!empty($dataNew)) {
                        foreach ($dataNew as $val) {
                            if ($val->quantity < $sku_size) {
                                $check = $qty + $val->quantity;
                                $shelve_no = $val->shelve_no;
                                if (empty($shelve_no)) {
                                    $shelve_no = "";
                                }
                                if ($check <= $sku_size) {

                                    $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                                    $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                                    $lastQtyUp_up = $lastQtyUp;
                                    $activitiesArr = array('exp_date' => $expiredate, 'st_location' => $stock_location_upHistory, 'item_sku' => $ItemskuID, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $seller_id, 'qty' => $check, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);
                                    //print_r($activitiesArr);
                                    GetAddInventoryActivities($activitiesArr);
                                    $this->ItemInventory_model->updateInventory(array('quantity' => $check, 'id' => $val->id));
                                    $qty = 0;
                                } else {
                                    $diff = $sku_size - $val->quantity;
                                    $lastQtyUp = GetuserToatalLOcationQty($val->id, 'quantity');
                                    $stock_location_upHistory = GetuserToatalLOcationQty($val->id, 'stock_location');
                                    $lastQtyUp_up = $lastQtyUp;
                                    $activitiesArr = array('exp_date' => $expiredate, 'st_location' => $stock_location_upHistory, 'item_sku' => $ItemskuID, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $seller_id, 'qty' => $sku_size, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'Update', 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);

                                    //print_r($activitiesArr);
                                    GetAddInventoryActivities($activitiesArr);
                                    $this->ItemInventory_model->updateInventory(array('quantity' => $sku_size, 'id' => $val->id));
                                    $qty = $qty - $diff;
                                }
                            }

                            // echo $val['item_sku'];  
                        }
                    }
                    //echo $qty."<br>";
                    if ($qty > 0) {
                        if ($sku_size >= $qty)
                            $locationLimit = 1;
                        else {
                            $locationLimit1 = $qty / $sku_size;
                            $locationLimit = ceil($locationLimit1);
                        }
                        $totalLocationCount += $locationLimit;
                        $StockArray = $this->ItemInventory_model->Getallstocklocationdata($seller_id, $locationLimit, $CheckOtherLocation);
                        $TotalInsystemStockLocation += count($StockArray);


                        $stocklocation = $StockArray;
                        $updateaty = $qty;
                        $AddQty = 0;
                        for ($ii = 0; $ii < $locationLimit; $ii++) {

                            if ($sku_size <= $updateaty) {
                                $AddQty = $sku_size;
                                $updateaty = $updateaty - $sku_size;
                            } else {
                                $AddQty = $updateaty;
                                $updateaty = $updateaty;
                            }

                            $data[] = array(
                                'itype' => $itype,
                                'item_sku' => $ItemskuID,
                                'seller_id' => $seller_id,
                                'quantity' => $AddQty,
                                'update_date' => date("Y/m/d h:i:sa"),
                                'stock_location' => $StockArray[$ii]->stock_location,
                                'expity_date' => $expiredate,
                                'wh_id' => $warehouse,
                                'shelve_no' => $palletsA,
                                'super_id' => $this->session->userdata('user_details')['super_id']
                            );
                            array_push($CheckOtherLocation, $StockArray[$ii]->stock_location);
                        }

                        $errordata['validsku'][] = getalldataitemtables($validskuid, 'sku');
                        // $data[] = array('item_sku' => $validskuid,'quantity'=> $itemQty,'seller_id'=>$seller_id,'stock_location'=>$stocklocation2,'expity_date'=>$expiredate);
                        if ($itype == 'B2C') {
                            $this->ItemInventory_model->GetUpdatePickupchargeInvocie($seller_id, $chargeQty, $chargeQty, $ItemskuID);
                        } else {
                            $this->ItemInventory_model->GetUpdatePickupchargeInvocie($seller_id, $locationLimit, $chargeQty, $ItemskuID);
                        }
                    } else {
                        if ($itype == 'B2C') {
                            $this->ItemInventory_model->GetUpdatePickupchargeInvocieUpdateQty($seller_id, $chargeQty, $chargeQty, $ItemskuID);
                        } else {
                            $this->ItemInventory_model->GetUpdatePickupchargeInvocieUpdateQty($seller_id, $locationLimit, $chargeQty, $ItemskuID);
                        }
                    }


                    $i++;
                }
            }
            }
            //  echo $row;
           //echo "<pre>";
           // print_r($palletsA_error);
            //print_r($errordata);
            //print_r($data);
            //die;
            if (!empty($data)) {
                $Status = $this->ItemInventory_model->add($data);
            }

            if ($Status == TRUE) {

                $this->session->set_flashdata('messarray', $errordata);
                redirect('ItemInventory');
            } else {

                $this->session->set_flashdata('messarray', $errordata);
                redirect('ItemInventory');
            }
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            redirect('ItemInventory');
        }
    }

    function addImportCity() {
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                $alertArray = array();
                $checkdulicateCity = array();
                for ($row = 2; $row <= $highestRow; $row++) {

                    $state = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $city = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $city_code = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $arabic_city = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    if (!empty($state) && !empty($city) && !empty($city_code)) {
                        if (in_array($city, $checkdulicateCity)) {
                            $alertArray['duplicatefile_city'][] = $row;
                        } else {
                            $countryName = $this->Country_model->GetsuperIdForCountry();
                            $checkstateData = $this->Country_model->GetCountryDatacheck($countryName, $state, 'state');
                            if ($checkstateData['state'] == $state) {

                                array_push($checkdulicateCity, $city);
                                $checkCitynames = $this->Country_model->GetCountryDatacheck_city($city);
                                if (empty($checkCitynames)) {
                                    $alertArray['validrowcity'][] = $row;
                                    $addcityArray[] = array('super_id' => $this->session->userdata('user_details')['super_id'], 'country' => $countryName, 'state' => $state, 'city' => $city, 'city_code' => $city_code, 'title' => $arabic_city);
                                } else {
                                    $alertArray['duplicate_city'][] = $row;
                                }
                            } else {

                                $addState = array('super_id' => $this->session->userdata('user_details')['super_id'], 'country' => $countryName, 'state' => $state);
                                $alertArray['validrowtate'][] = $row;
                                $this->Country_model->AddstateData_import($addState);
                                $checkCitynames = $this->Country_model->GetCountryDatacheck_city($city);
                                if (empty($checkCitynames)) {
                                    $alertArray['validrowcity'][] = $row;
                                    $addcityArray[] = array('super_id' => $this->session->userdata('user_details')['super_id'], 'country' => $countryName, 'state' => $state, 'city' => $city, 'city_code' => $city_code, 'title' => $arabic_city);
                                } else {
                                    $addCode = array('city_code' => $city_code);
                                    $addCode_w = array('city' => $checkCitynames['city']);
                                    $this->Country_model->updatecodeData($addCode, $addCode_w);

                                    $alertArray['city_codevalid'][] = $row;
                                    $alertArray['duplicate_city'][] = $row;
                                }
                            }
                        }
                    } else {

                        if (empty($state)) {
                            $alertArray['state_empty'][] = $row;
                        }
                        if (empty($city)) {
                            $alertArray['city_empty'][] = $row;
                        }
                        if (empty($city_code)) {
                            $alertArray['citycode_empty'][] = $row;
                        }
                    }
                }
            }

            // print_r($addcityArray);
            if (!empty($addcityArray)) {
                $this->Country_model->AddcityBatch($addcityArray);
            }
            // die;

            $this->session->set_flashdata('errorA', $alertArray);
            redirect('Country/Importlocations');
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            redirect('Country/Importlocations');
        }
    }

    function importfwd() {
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                $alertArray = array();
                $checkdulicateCity = array();
                for ($row = 2; $row <= $highestRow; $row++) {

                    $awb = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $fawb = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $company = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                
                    if (!empty($awb) && !empty($fawb) && !empty($company)) {

                        $companyId=GetCourCompanyIdName($company); 
                        $checkShip=GetshpmentDataByawb($awb,'id');
                        if($checkShip>0)
                        {
                            
                            $updateArray[]=array('frwd_company_awb'=>$fawb,'slip_no'=>$awb,'frwd_company_id'=>$companyId);

                        }
                        
                    } else {

                        if (empty($state)) {
                            $alertArray['state_empty'][] = $row;
                        }
                        if (empty($city)) {
                            $alertArray['city_empty'][] = $row;
                        }
                        if (empty($city_code)) {
                            $alertArray['citycode_empty'][] = $row;
                        }
                    }
                }
            }

            // print_r($addcityArray);
            if (!empty($updateArray)) {
                $this->Shipment_model->updateStatusBatch($updateArray);
            }
            // die;

            $this->session->set_flashdata('errorA', $alertArray);
            redirect('Shipment/fwdupload');
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            redirect('Shipment/fwdupload');
        }
    }

    function add_item_bulk() {
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            // rmdir($dirname);
            // unlink("assets/item_uploads/proimg.zip");
            // die;
            if (!empty($_FILES['product_images']['name'])) {

                $config['upload_path'] = 'assets/item_uploads/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'zip';
                $config['file_name'] = $_FILES['product_images']['name'];
                // $config['file_name'] =$_FILES['item_path']['name'];
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('product_images')) {

                    $uploadData = $this->upload->data();

                    $small_img = $config['upload_path'] . '' . $uploadData['file_name'];
                } else {

                    $small_img = "";
                }
            } else {
                $small_img = "";
            }

            $zip = new ZipArchive;
            $res = $zip->open("$small_img");

            if ($res === TRUE) {
                $zip->extractTo('/var/www/html/fastcoo-tech/demofulfillment/assets/item_uploads/');
                $zip->close();
                echo 'woot!';
            } else {
                echo 'doh!';
            }



            //  system("unzip /var/www/html/fastcoo-tech/demofulfillment/$small_img.zip"); 
            // echo $small_img; die;
            $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $Skucheck = array();
                $skuData = array();
                $alertArray = array();
                for ($row = 2; $row <= $highestRow; $row++) {
                    $storageName = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $storageArray = $this->Item_model->getcheckstorageid($storageName);


                    $sku = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());
                    $warehouseName = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $expire_block = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());
                    $less_qty = trim($worksheet->getCellByColumnAndRow(7, $row)->getValue());
                    $alert_day = trim($worksheet->getCellByColumnAndRow(8, $row)->getValue());

                    $color = trim($worksheet->getCellByColumnAndRow(9, $row)->getValue());
                    $length = trim($worksheet->getCellByColumnAndRow(10, $row)->getValue());
                    $width = trim($worksheet->getCellByColumnAndRow(11, $row)->getValue());
                    $height = trim($worksheet->getCellByColumnAndRow(12, $row)->getValue());                  
                    $item_path = trim($worksheet->getCellByColumnAndRow(13, $row)->getValue());
                    $weight = trim($worksheet->getCellByColumnAndRow(14, $row)->getValue());

                    if ($expire_block == 'Yes')
                        $expire_block = "Y";
                    else
                        $expire_block = "N";
                    $name = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $capacity = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                    $skuArray = $this->Item_model->GetchekskuDuplicate($sku);
                    // echo $sku."//".$skuArray['sku']."//".$storageArray['id']."<br>";


                    if (!empty($name) && !empty($sku) && !empty($capacity)) {
                        if (empty($storageArray)) {

                            $alertArray['invalidstorage'][] = $storageName;
                        }
                        if (!empty($skuArray)) {
                            $alertArray['alreadyexits'][] = $skuArray['sku'];
                        }

                        $skuArray = $this->Item_model->GetchekskuDuplicate($sku);

                        $warehouse = Getwarehouse_categoryfield_name($warehouseName, 'id');
                        if ($warehouse > 0) {

                            if (!empty($storageArray) && empty($skuArray)) {

                                $name = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                                $capacity = trim($worksheet->getCellByColumnAndRow(3, $row)->getValue());
                                $description = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                                $type = 'B2C'; //$worksheet->getCellByColumnAndRow(5, $row)->getValue();
                                if (in_array($sku, $Skucheck)) {
                                    $alertArray['duplicate'][] = $row;
                                } else {
                                    $alertArray['validrow'][] = $row;
                                    array_push($Skucheck, $sku);

                                    $item_path1 = "assets/item_uploads/$item_path";

                                    rename("/var/www/html/fastcoo-tech/demofulfillment/assets/item_uploads/proimg/$item_path", "/var/www/html/fastcoo-tech/demofulfillment/assets/item_uploads/$item_path");
                                    $data[] = array(
                                        'storage_id' => $storageArray['id'],
                                        'name' => $name,
                                        'type' => $type,
                                        'sku' => $sku,
                                        'description' => $description,
                                        'sku_size' => $capacity,
                                        'wh_id' => $warehouse,
                                        'expire_block' => $expire_block,
                                        'less_qty' => $less_qty,
                                        'alert_day' => $alert_day,
                                        'color' => $color,
                                        'length' => $length,
                                        'width' => $width,
                                        'height' => $height,
                                        'weight' => $weight,
                                        'entry_date' => date('Y-m-d H:i:s'),
                                        'item_path' => $item_path1,
                                        'super_id' => $this->session->userdata('user_details')['super_id']
                                    );
                                }

                                // print_r($skuArray);
                            } else {
                                $alertArray['invalid'] = $row;
                            }
                        } else
                            $alertArray['invalidW'][] = $warehouseName;
                    } else {
                        if (empty($name))
                            $alertArray['emptyname'][] = $row;
                        if (empty($sku))
                            $alertArray['emptysku'][] = $row;
                        if (empty($capacity))
                            $alertArray['emptycapcity'][] = $row;
                    }
                }
            }
            //  echo '<pre>';
            //if(in_array($data))
            //print_r($data); die;


            unlink($small_img);
            $Status = $this->Item_model->add_bulk($data);
            if ($Status == true) {

                //$this->session->set_flashdata('msg','Bulk has been added successfully');  
                $this->session->set_flashdata('errorA', $alertArray);
                redirect('Item');
            } else {
                // $this->session->set_flashdata('error','data error Bulk add failed (duplicate or empty fields)'); 
                $this->session->set_flashdata('errorA', $alertArray);
                redirect('Item');
            }
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            redirect('Item');
        }
    }


    function add_item_weight_bulk() {
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
         
             $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $Skucheck = array();
                $skuData = array();
                $alertArray = array();
                for ($row = 2; $row <= $highestRow; $row++) {
                   

                    $sku = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());                   
                    $weight = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());

                    $skuArray = $this->Item_model->GetchekskuDuplicate($sku);
                    
                    //  echo "<pre><br>";print_r($skuArray); 
                    //  die; 
                   
                    if ( !empty($sku) && !empty($weight)) {
                
                        $skuArray = $this->Item_model->GetchekskuDuplicate($sku);
                            if (!empty($skuArray)) {

                               
                                $weight = $weight;
                                $sku=  $skuArray['sku'];
                                $skuId=  $skuArray['id'];
                            
                                   
                                    $data[] = array(
                                        'id' => $skuId,
                                        'sku' => $sku,                                        
                                        'weight' => $weight,
                                        'entry_date' => date('Y-m-d H:i:s'),                                       
                                        'super_id' => $this->session->userdata('user_details')['super_id']
                                    );
                                

                                // print_r($skuArray);
                            } else {
                                $alertArray['invalid'] = $row;
                            }
                       
                    } else {
                        
                        if (empty($sku))
                            $alertArray['emptysku'][] = $row;
                        if (empty($weight))
                            $alertArray['emptyweight'][] = $row;
                        
                    }
                }
            }
            $Status = $this->Item_model->update_bulk($data);
            
            if ($Status == true) {

                $this->session->set_flashdata('msg','Bulk has been added successfully');  
                
                redirect('Item');
            } else {
                 $this->session->set_flashdata('error','data error Bulk add failed (duplicate or empty fields)'); 
     
                    redirect('Item');
            }
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
                 redirect('Item');
        }
    }

    function add_shelve_bulk() {
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $i = 0;

                $slavesnos = "";
                for ($row = 2; $row <= $highestRow; $row++) {




                    $shelve = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $city = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    if (!empty($shelve)) {
                        $duplicatecheck = getcheckslavenovalid($shelve);

                        if ($duplicatecheck == TRUE) {
                            $data[] = array('city_id' => getIdfromCityName($city), 'shelv_no' => $shelve, 'super_id' => $this->session->userdata('user_details')['super_id']);
                            $successRow[] = $row;
                        } else {
                            $duplicate[] = $shelve;
                        }
                    } else {
                        $emptyrow[] = $row;
                    }
                    $i++;
                }

                //echo $slavesnos;
                //print_r($data);
            }
            $showdunlicate = implode(',', $duplicate);
            $emptyrow = implode(',', $emptyrow);
            $successRow = implode(',', $successRow);
            //echo '<pre>';
            //print_r($showdunlicate);
            //echo '<br>';
            //print_r($emptyrow);
            //   echo '<br>';
            //  print_r($successRow);
            //  die;
            if (!empty($data)) {
                $Status = $this->Shelve_model->add_bulk_shelve_data($data);
            } else
                $Status = false;
            if ($status == true) {
                $this->session->set_flashdata('msg', 'Bulk has been added successfully');
                $this->session->set_flashdata('dupmsg', $showdunlicate);
                $this->session->set_flashdata('successRow', $successRow);
                $this->session->set_flashdata('emptyrow', $emptyrow);
                redirect('add_shelve');
            } else {
                $this->session->set_flashdata('error', 'data error Bulk add failed (duplicate or empty fields)');
                $this->session->set_flashdata('dupmsg', $showdunlicate);
                $this->session->set_flashdata('emptyrow', $emptyrow);
                $this->session->set_flashdata('successRow', $successRow);
                redirect('add_shelve');
            }
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            $this->session->set_flashdata('dupmsg', $emptyrow);
            redirect('add_shelve');
        }
    }

    function ImportDeliveryCityFile() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);
        //$namesArray = array('City');
        $namesArray = array();
        $returnArray = $this->Country_model->GetdeliveryCOmpanyListQry();
       // print_r($returnArray);exit;
        foreach ($returnArray as $val) {
            if(!empty($val['company']))
            array_push($namesArray, $val['company']);
        }


        $table_columns = $namesArray;
        $table_columns[] = "Arabic City Name";
        $table_columns[] = "Esnad City Code";
        $table_columns[] = "Salla";
        $table_columns[] = "Zid";
        $table_columns[] = "Country Code";
        sort($table_columns);
        
        $table_column = array_merge(array("City"),$table_columns);
        $column = 0;

        foreach ($table_column as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }



        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Import Delivery City.xls"');
        $object_writer->save('php://output');
    }

    public function ImportDeliveryCompanyData() {


        $path = $_FILES["file"]["tmp_name"];
        if (!empty($path)) {
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            $activeCompany = $this->Country_model->GetdeliveryCOmpanyListQry();


           // print_r($activeCompany);
            $columnLoop = array();

            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn(); 

              $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
                // echo "gg"; die;
                
                $returnArr = array();
                //print_r($activeCompany);
             
                for ($row2 = 1; $row2 <= 1; $row2++) {
                    count($worksheet->getCellByColumnAndRow($mm, $row2));
                    for ($mm = 1; $mm < $highestColumnIndex; $mm++) {

                       
                            array_push($columnLoop, trim($worksheet->getCellByColumnAndRow($mm, $row2)->getValue()));
                       
                    }
                }
               
                //$columnLoop[] = "Arabic City Name";
                
                
            //    echo "<pre>";
            // print_r($columnLoop); die;
                for ($row = 2; $row <= $highestRow; $row++) {
                    $city = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue()); 
                     //echo $city. "<hr />";
                    $oldCityArr = GetCityAllDataByname($city);
                    $old_id = $oldCityArr['id'];
                    //if ($old_id > 0) {
                        foreach ($columnLoop as $key99 => $colimn) {
                            $counter = $key99 + 1;
                            $dynmicColumnVal = htmlspecialchars(trim($worksheet->getCellByColumnAndRow($counter, $row)->getValue())); 
                           // echo $colimn. "<hr />";
                            $cityArrayName = $this->Country_model->GetCourierCItyNew($old_id, $colimn);
                            //print "<pre>"; print_r($cityArrayName); print "<hr>";die;
                            if (!empty($dynmicColumnVal)) {
                                if ($colimn == 'Ajeek')
                                    $dynmicColumn = 'ajeek_city';
                                if ($colimn == 'Alamalkon')
                                    $dynmicColumn = 'alamalkon';
                                if ($colimn == 'Aramex')
                                    $dynmicColumn = 'aramex_city';
                                if ($colimn == 'Clex')
                                    $dynmicColumn = 'clex';
                                if ($colimn == 'Zajil')
                                    $dynmicColumn = 'zajil';
                                if ($colimn == 'Smsa')
                                    $dynmicColumn = 'samsa_city';
                                if ($colimn == 'Esnad')
                                    $dynmicColumn = 'esnad_city';
                                if ($colimn == 'Esnad City Code')
                                    $dynmicColumn = 'esnad_city_code';
                                if ($colimn == 'Safearrival')
                                    $dynmicColumn = 'safe_arrival';
                                if ($colimn == 'Arabic City Name')
                                    $dynmicColumn = 'title';
                                if ($colimn == 'Aymakan')
                                    $dynmicColumn = 'aymakan';
                                if ($colimn == 'Makhdoom')
                                    $dynmicColumn = 'makhdoom';
                                if ($colimn == 'Labaih')
                                    $dynmicColumn = 'labaih';
                                if ($colimn == 'Shipsy')
                                    $dynmicColumn = 'shipsy_city';
                                if ($colimn == 'Saudi Post')
                                    $dynmicColumn = 'saudipost_id';
                                if ($colimn == 'Shipadelivery')
                                    $dynmicColumn = 'shipsa_city';
                                if ($colimn == 'Barqfleet')
                                    $dynmicColumn = 'barq_city';
                                if ($colimn == 'Saee')
                                    $dynmicColumn = 'saee_city';
                                if ($colimn == 'NAQEL')
                                    $dynmicColumn = 'naqel_city_code';
                                if ($colimn == 'Tamex')
                                    $dynmicColumn = 'tamex_city';
                                if ($colimn == 'Salla')
                                    $dynmicColumn = 'sala';
                                if ($colimn == 'Zid')
                                    $dynmicColumn = 'zid';
                                if ($colimn == 'Thabit')
                                    $dynmicColumn = 'thabit_city';
                                if ($colimn == 'BurqExpres')
                                    $dynmicColumn = 'burq_city';
                                if ($colimn == 'Beez')
                                    $dynmicColumn = 'beez_city';
                                if ($colimn == 'Country Code')
                                    $dynmicColumn = 'country_code';
                                if ($colimn == 'DHL JONES')
                                    $dynmicColumn = 'dhl_jones_city';
                                if ($colimn == 'KwickBox')
                                    $dynmicColumn = 'kwickBox_city';
                                if ($colimn == 'MICGO')
                                    $dynmicColumn = 'MICGO_city';

                                
                                 
                                if (!empty($cityArrayName)) {
                                    $UpdateArray_new[] = array($dynmicColumn => addslashes($dynmicColumnVal), 'id' => $old_id);
                                } else {
                                    $UpdateArray[] = array('city' => $city, $dynmicColumn => addslashes($dynmicColumnVal), 'super_id' => 
                                  //  print_r();
                                    $this->session->userdata('user_details')['super_id']);
                                }
                                //$returnArr['validrowtate'][] = $row . " " . $colimn;
                            }
                        }
                    //}
                    //  echo '<pre>';
                    // print_r($UpdateArray_new);  
                }
            }
         //   die;
            //echo 'New<pre>'; print_r($UpdateArray_new);  print "<hr/>";
            //echo '<pre>';   print_r($UpdateArray);   
            //die;
            if (!empty($UpdateArray)) {

                //die;


                $this->Country_model->InsertCityData_new($UpdateArray);
            }
            if (!empty($UpdateArray_new)) {

                //die;      

                $this->Country_model->GetDataUpdateCIty_new($UpdateArray_new);
            }
            //  die;
            $returnArr['validrowtate'][] = "";
            $this->session->set_flashdata('errorA', $returnArr);
            redirect(base_url() . 'Country/Importdeliverycity');
        } else {
            $this->session->set_flashdata('error', 'Please Select file');
            redirect(base_url() . 'Country/Importdeliverycity');
        }

        //  echo json_encode($returnArr);
    }

}
