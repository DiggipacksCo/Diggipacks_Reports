<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/ordergen.app.js"></script>
    </head>

    <body ng-app="AppOrderGen" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="OrderGenCRTL" ng-init="loadMore(1, 0);"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 
                        <!--style="background-color: red;"-->
                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>
                        <div class="alert alert-warning" ng-repeat="err in errorBackorder"><p id="target"></p> this  sku {{err.sku}}  Qty {{err.qty}} or pallet no. not available or item qty expired
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1> <strong><?=lang('lang_OrderGenerated');?></strong> <a  ng-click="exportExcel('#downloadtable');" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected><?=lang('lang_select_export_limit');?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>
                                            </select>
                                            <!-- <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>--> 
                                            <!--<a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>--> 
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments'); ?>" --> 
                                    <!-- href="<? //base_url('Pdf_export/all_report_view'); ?>" --> 
                                        <!-- Quick stats boxes -->
                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                <!-- Today's revenue --> 

                                                <!-- <div class="panel-body" > -->
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Type');?>:</strong> <br>
                                                        <select  id="s_type" name="s_type" ng-model="filterData.s_type" class="selectpicker"  data-width="100%" >
                                                            <option value="AWB"><?=lang('lang_AWB');?></option>
<!--                                                            <option value="SKU">SKU</option>-->
                                                            <option value="REF"><?=lang('lang_Reference');?> #</option>
                                                            <option value="MOBL"><?=lang('lang_Mobile_No');?>.</option>
                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Search_value');?>:</strong>
                                                        <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                                                        <!--  <?php // if($condition!=null): ?>
                                                              <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition; ?>" >
                                                        <?php // endif; ?> --> 
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong> <br>
                                                        <select  id="seller" name="seller"  ng-model="filterData.seller" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_SelectSeller');?></option>
                                                            <?php foreach ($sellers as $seller_detail): ?>
                                                                <option value="<?= $seller_detail->id; ?>">
                                                                    <?= $seller_detail->name; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div></div>
                                                               <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_company');?>:</strong>
                                                        <br>
                                                        <?php
                                                        //$destData = getAllDestination();
                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Company');?></option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_warehouse');?>:</strong> <br>
                                                        <?php
                                                        $warehouseArr = Getwarehouse_Dropdata();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-model="filterData.wh_id"  class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_Selectwarehousename');?></option>
                                                                <?php foreach ($warehouseArr as $data): ?>
                                                                <option value="<?= $data['id']; ?>">
                                                                <?= $data['name']; ?>
                                                                </option>
<?php endforeach; ?>
                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Destination');?>:</strong> <br>
                                                        <?php
                                                        $destData = getAllDestination();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-model="filterData.destination" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_Select_Destination');?></option>
                                                                <?php foreach ($destData as $data): ?>
                                                                <option value="<?= $data['id']; ?>">
                                                                <?= $data['city']; ?>
                                                                </option>
<?php endforeach; ?>
                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Exactdate');?>:</strong>
                                                        <input type="date" id="exact"name="exact" ng-model="filterData.exact"  class="form-control">
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                        <input type="date" id="from"name="from" ng-model="filterData.from" class="form-control">
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input type="date" id="to"name="to"  ng-model="filterData.to" class="form-control">
                                                    </div></div>
                                                     <div class="col-md-2"><div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.sort_list" ng-change="loadMore(1, 1);">

                                                            <option value=""><?=lang('lang_Short_List');?></option>


                                                            <option value="NO"><?=lang('lang_Newest_Order');?></option>
                                                        <option value="OLD"><?=lang('lang_Oldest_Order');?></option>
                                                        <option value="OBD"><?=lang('lang_Order_By_Date');?></option>
                                                            

                                                        </select>

                                                    </div></div>
                                                <div class="col-md-2"><div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.sort_limit" ng-change="loadMore(1, 1);">

                                                            <option value=""><?=lang('lang_Short');?></option>


                                                            <option ng-repeat="(key,value) in dropshort" value="{{key}}-{{value}}">{{value}}</option>

                                                        </select>

                                                    </div></div>
                                                <div class="col-md-4">
                                                </div> 
                                                <div class="col-md-12"><div class="form-group" > <button  class="btn btn-danger ml-10" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button>
                                                        <button type="button" class="btn btn-success ml-10"><?=lang('lang_Total');?>  <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                        <button  class="btn btn-info ml-10" ng-confirm-click="You are going to Create Order, This Action will change the Order status! Are you sure?" ng-click="CreateOrderCheck();" ><?=lang('lang_Create_Order');?></button>
                                                        
                                                          <?php if (menuIdExitsInPrivilageArray(122) == 'Y') { ?>
                                                        <button  class="btn btn-danger ml-10" ng-confirm-click="Are you sure want delete Orders?" ng-click="removemultipleorder();" ><?=lang('lang_Delete');?></button>
                                                          <?php  } ?>          
                                                    </div>
                                                </div>
                                                




                                            </div>
                                        </div>

                                        <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="loader logloder" ng-show="loadershow"></div>


                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.
                                                    <input type="checkbox" ng-model="selectedAll"  ng-change="selectAll();" /></th>
                                                    <th><?=lang('lang_AWB_No');?>.</th>
                                                <th><?=lang('lang_Ref_No');?>.</th>
                                                <th><?=lang('lang_TPL_company');?></th>
                                                <th><?=lang('lang_Tpl_frwd_no');?>.</th>
                                                <td><?=lang('lang_Origin');?></td>
                                                <th><?=lang('lang_Destination');?></th>
                                                <th><?=lang('lang_Receiver');?></th>
                                                    <th><?=lang('lang_Receiver_Address');?></th>
                                                    <th><?=lang('lang_Receiver_Mobile');?></th>
                                                 <th><?=lang('lang_Payment_Mode');?></th>
                                                 <th><?=lang('lang_Total_COD');?></th>
                                                <th><?=lang('lang_Item_Sku_Detail');?>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                            <th><?=lang('lang_SKU');?></th>
                                                                 <th><?=lang('lang_Gift_Item');?>  </th>
                                                                <th><?=lang('lang_QTY');?></th>
                                                               <!--  <th>COD (SAR)</th> -->
                                                            </tr>
                                                        </thead>
                                                    </table></th>
                                                  <!-- <th>Expire Details   <table class="table"><thead>
                                    <tr>
                                      <th>Pallet No</th>
                                      <th>Stock Location</th>
                                      <th>Expire Date)</th>
                                    </tr>
                                  </thead></table></th>--> 
                                                  <!-- <th>Cartoon Sku#</th> --> 

<!-- <th>Cartoon Quantity</th> -->
                                                <th><?=lang('lang_Seller');?></th>
                                                <th><?=lang('lang_warehouse');?></th>
                                                <th><?=lang('lang_Reason');?> </th>
                                                <th><?=lang('lang_Date');?>  </th>
                                                <td colspan="2" align="center"><?=lang('lang_Action');?> </td>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}} 
                                                <input type="checkbox" ng-if="data.reciever_name != '' && data.reciever_phone != '' && data.origin_valid != 0 && data.destination_valid != 0" value="{{data.slip_no}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" />
                                                <input type="checkbox" ng-if="data.reciever_name == '' || data.reciever_phone == '' || data.origin_valid == 0 || data.destination_valid == 0" disabled  /></td>
                                            <!--                  value="{{data.slip_no}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()"-->
                                            <td>{{data.slip_no}}</td>
                                            <td>{{data.booking_id}}</td>
                                            <td>{{data.cc_name}}</td>
                                             <td>{{data.frwd_company_awb}}</td> 
                                            <td>{{data.origin}}</td>
                                            <td>{{data.destination}}</td>
                                            <td>{{data.reciever_name}}</td>
                                            <td>{{data.reciever_address}}</td>
                                            <td>{{data.reciever_phone}}</td>
                                               <td>{{data.mode}}</td>  
                                               <!--  <span ><br>({{data.total_cod_amt}})</span> -->
                                                 <td>{{data.total_cod_amt}}</td>
                                           
                                            <td><table class="table table-striped table-hover table-bordered dataTable">
                                                    <tbody>
                                                        <tr ng-repeat="data1 in data.skuData">
                                                            <td ><span class="label label-primary">{{data1.sku}}</span></td>
                                                             <td><span  ng-if="data1.free_sku=='N'" class="label label-warning">No</span> <span ng-if="data1.free_sku=='Y'" class="label label-primary">Yes</span></td>
                                                            <td><span class="label label-info">{{data1.piece}}</span></td>
                                                          <!--   <td><span class="label label-danger">{{data1.cod}}</span></td> -->
                                                        </tr>
                                                    </tbody>
                                                </table></td>
                                              <!-- <td>
                                        <table class="table table-striped table-hover table-bordered dataTable bg-*">
                               
                                <tbody>
                                  <tr ng-repeat="data2 in data.expire_details">
                                      <td ><span class="label label-primary">{{data2.shelve_no}}</span></td>
                                    <td><span class="label label-info">{{data2.stock_location}}</span></td>
                                    <td><span class="label label-danger">{{data2.expity_date}}</span></td>
                                  </tr>
                                            </tbody>
                                        </table>
                                        
                                        </td>
                                            -->

                                            <td>{{data.name}}</td>
                                            <td>{{data.wh_id}}</td>
                                            <td>{{data.back_reasons}}</td>
                                            <td>{{data.entrydate}}</td>
                                              
                                            <td><?php if (menuIdExitsInPrivilageArray(122) == 'Y') { ?><a class="btn btn-danger" ng-click="GetcheckOrderDeleteStatus(data.slip_no);" ng-confirm-click="Are You Sure Want To Delete Order?"><?=lang('lang_Delete');?></a>  <?php } ?></td>
                                            
                                              <?php if (menuIdExitsInPrivilageArray(123) == 'Y') { ?>
                                            <td><a class="btn btn-info" ng-click="GetEditshipemtPopProcee($index);" ng-confirm-click="Are You Sure Want To Edit Order?"><?=lang('lang_Edit');?></a></td> <?php } ?>
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></button>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table --> 

                        <!---------------Qty Deduct Popup---------->
                        <div class="modal fade" id="UpdateShipemtData" tabindex="-1" role="dialog" aria-labelledby="UpdateShipemtData" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#DBB828;">
                                        <h5 class="modal-title text-white" id="UpdateShipemtData"><?=lang('lang_Edit_Shipment');?> ({{ShipmentEditArr.slip_no}})</h5>
                                        <br>
                                    </div>
                                    <form name="myform" novalidate ng-submit="myForm.$valid && GetUpdateShipmetTableData()"  >
                                        <div class="modal-body">
                                            <table class="table">
                                                <tr>
                                                    <td style="border: none;"><div class="form-group" ><strong><?=lang('lang_Reference');?> #:</strong>
                                                            <input type="text" class="form-control" name="booking_id" ng-model="ShipmentEditArr.booking_id"  id="booking_id" required>
                                                        </div></td>
                                                    <td style="border: none;"><div class="form-group" ><strong><?= lang('lang_Receiver_Name'); ?>:</strong>
                                                            <input type="text" class="form-control" name="reciever_name" ng-model="ShipmentEditArr.reciever_name"  id="reciever_name" required>
                                                        </div></td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;"><div class="form-group" ><strong><?= lang('lang_Receiver_Mobile'); ?> :</strong>
                                                            <input type="text" class="form-control" name="reciever_phone" ng-model="ShipmentEditArr.reciever_phone" id="reciever_phone" required>
                                                        </div></td>
                                                    <td style="border: none;"><div class="form-group" ><strong><?=lang('lang_Destination');?> :</strong>
                                                            <select  ng-model="ShipmentEditArr.destination_id"  class="form-control" data-width="100%" >
                                                                <option value=""><?=lang('lang_Select_Destination');?></option>
                                                                <option ng-repeat="cndata in countryArr" value="{{cndata.id}}">{{cndata.city}}</option>
                                                            </select>
                                                        </div></td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;" ><div class="form-group" ><strong><?= lang('lang_Receiver_Address'); ?> :</strong>
                                                            <input type="text" class="form-control" name="reciever_address" ng-model="ShipmentEditArr.reciever_address"  id="reciever_address" required>
                                                        </div></td>
                                                    <td style="border: none;" ><div class="form-group" ><strong><?=lang('lang_warehouse');?>  :</strong>
                                                            <select  ng-model="ShipmentEditArr.whid"  class="form-control" data-width="100%" >
                                                                <option value=""><?=lang('lang_Selectwarehousename');?></option>
                                                                <option ng-repeat="w_data in warehouseArr" value="{{w_data.id}}">{{w_data.name}}</option>
                                                            </select>
                                                        </div></td>
                                                </tr>

                                              <!--  <tr>
                                                    <td style="border: none;" ><div class="form-group" ><strong>Total COD Amount :</strong>
                                                            <input type="text" class="form-control" name="total_cod_amt" ng-model="ShipmentEditArr.total_cod_amt"  id="total_cod_amt" required>
                                                        </div></td>
                                                    <td style="border: none;" ><div class="form-group" >&nbsp;</td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="2" style="border: none;"><table class="table table-striped table-hover table-bordered">
                                                            <tr><td colspan="4" align="right"> <a ng-click="GetAddNewrowsSku();"> <i class="fa fa-plus-square fa-2x"></i></a></td></tr>
                                                            <tr>
                                                                <td>SKU</td>
                                                                <td>COD</td>
                                                                <td>Pieces</td>
                                                                <td>Action</td>
                                                            </tr>
                                                            <tr ng-repeat="sdata in ShipmentEditArr.skuData">
                                                                <td><div class="form-group" >
                                                                        <input type="text"  class="form-control"  ng-model="ShipmentEditArr.skuData[$index].sku" ng-blur="GetCheckDuplicationSku(ShipmentEditArr.skuData[$index].sku, $index);" required>
                                                                    </div></td>
                                                                <td><div class="form-group" >
                                                                        <input type="text"  class="form-control"  ng-model="ShipmentEditArr.skuData[$index].cod" ng-blur="GetCheckDuplicationSku(ShipmentEditArr.skuData[$index].sku, $index);" required>
                                                                    </div></td>
                                                                <td><div class="form-group" >
                                                                        <input type="text"   class="form-control"  ng-model="ShipmentEditArr.skuData[$index].piece" ng-blur="GetCheckDuplicationSku(ShipmentEditArr.skuData[$index].sku, $index);" required>
                                                                    </div></td>
                                                                <td><a ng-click="GetremoverowsskuId(ShipmentEditArr.skuData[$index].d_id, $index, ShipmentEditArr.skuData[$index].sku, ShipmentEditArr.slip_no);" ng-confirm-click="Are You Sure Want To remove this sku?"><i class="fa fa-minus-square fa-2x" style="color:red;"></i></a></td>
                                                            </tr>
                                                        </table></td>
                                                </tr> -->
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" ng-click="CloseModelPage();"><?=lang('lang_Close');?></button>
                                            <button type="button" class="btn btn-primary" ng-if="ShipmentEditArr.reciever_name && ShipmentEditArr.booking_id && ShipmentEditArr.reciever_phone&& ShipmentEditArr.total_cod_amt && ShipmentEditArr.reciever_address && ShipmentEditArr.destination_id && ShipmentEditArr.whid" ng-click="GetUpdateShipmetTableData();" ><?=lang('lang_Update');?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
<?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

            <div style="display:none">
                <table class="table table-striped table-hover table-bordered dataTable bg-*" id="downloadtable" style="width:100%;">
                    <thead>
                        <tr>
                        <th><?=lang('lang_SrNo');?>.</th>
                            <th><?=lang('lang_AWB_No');?>1.</th>
                            <th><?=lang('lang_Ref_No');?>.</th>
                            <th><?=lang('lang_Destination');?></th>
                            <th><?=lang('lang_Receiver');?></th>
                            <th><?=lang('lang_Receiver_Address');?></th>
                            <th><?=lang('lang_Receiver_Mobile');?></th>
                            <th><?=lang('lang_Item_Sku_Detail');?> 
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?=lang('lang_SKU');?></th>
                                            <th><?=lang('lang_QTY');?></th>
                                            <th><?=lang('lang_COD_SAR');?> (<?= site_configTable("default_currency"); ?>)</th>
                                        </tr>
                                    </thead>
                                </table></th>
                              <!-- <th>Expire Details   <table class="table"><thead>
                            <tr>
                              <th>Pallet No</th>
                              <th>Stock Location</th>
                              <th>Expire Date)</th>
                            </tr>
                          </thead></table></th>--> 
                              <!-- <th>Cartoon Sku#</th> --> 

<!-- <th>Cartoon Quantity</th> -->
                            <th><?=lang('lang_Seller');?></th>
                            <th><?=lang('lang_Date');?>  </th>
                        </tr>
                    </thead>
                    <tr ng-if='shipData1 != 0' ng-repeat="data in shipData1">
                        <td>{{$index + 1}} </td>
                        <td>{{data.slip_no}}</td>
                        <td>{{data.booking_id}}</td>
                        <td>{{data.destination}}</td>
                        <td>{{data.reciever_name}}</td>
                        <td>{{data.reciever_address}}</td>
                        <td>{{data.reciever_phone}}</td>
                        <td><table class="table table-striped table-hover table-bordered dataTable bg-*">
                                <tbody>
                                    <tr ng-repeat="data1 in data.skuData">
                                        <td ><span class="label label-primary">{{data1.sku}}</span></td>
                                        <td><span class="label label-info">{{data1.piece}}</span></td>
                                        <td><span class="label label-danger">{{data1.cod}}</span></td>
                                    </tr>
                                </tbody>
                            </table></td>
                          <!-- <td>
                                <table class="table table-striped table-hover table-bordered dataTable bg-*">
                       
                        <tbody>
                          <tr ng-repeat="data2 in data.expire_details">
                              <td ><span class="label label-primary">{{data2.shelve_no}}</span></td>
                            <td><span class="label label-info">{{data2.stock_location}}</span></td>
                            <td><span class="label label-danger">{{data2.expity_date}}</span></td>
                          </tr>
                                    </tbody>
                                </table>
                                
                                </td>
                        -->

                        <td>{{data.name}}</td>
                        <td>{{data.entrydate}}</td>
                    </tr>
                </table>
            </div>

<!-- <script>
var $rows = $('tbody tr');
$('#search').keyup(function() {
var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

$rows.show().filter(function() {
var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
return !~text.indexOf(val);
}).hide();
});
</script> -->

        </div>
        <script>

                          var tableToExcel = (function() {
                          var uri = 'data:application/vnd.ms-excel;base64,'
                                  , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                                                      , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                                              , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
                                              return function(table, name) {
                                              if (!table.nodeType) table = document.getElementById(table)
                                                      var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                                              var blob = new Blob([format(template, ctx)]);
                                                      var blobURL = window.URL.createObjectURL(blob);
                                                      return blobURL;
                                              }
                                              })()

                                              $("#btnExport").click(function () {
                                      var todaysDate = 'OrderCreated Details ' + new Date();
                                              var blobURL = tableToExcel('downloadtable', 'test_table');
                                              $(this).attr('download', todaysDate + '.xls')
                                              $(this).attr('href', blobURL);
                                              });
        // "order": [[0, "asc" ]]
                                              $('#s_type').on('change', function(){
//                                      if ($('#s_type').val() == "SKU"){
//                                      $('#s_type_val').attr('placeholder', 'Enter SKU no.');
//                          }else
                              if($('#s_type').val()=="AWB"){
                            $('#s_type_val').attr('placeholder','Enter AWB no.');
                          }
                          else if($('#s_type').val()=="REF"){
                            $('#s_type_val').attr('placeholder','Reference #.');
                          }
                           else if($('#s_type').val()=="MOBL"){
                            $('#s_type_val').attr('placeholder','Mobile No.');
                          }
                  

                        });
     
        </script> 
        <script>

                                              $("#target").focus();
        // "order": [[0, "asc" ]]



        </script> 
        <!-- /page container -->
        <style>
            /* Hiding the checkbox, but allowing it to be focused */
            .badgebox
            {
                opacity: 0;
            }

            .badgebox + .badge
            {
                /* Move the check mark away when unchecked */
                text-indent: -999999px;
                /* Makes the badge's width stay the same checked and unchecked */
                width: 27px;
            }

            .badgebox:focus + .badge
            {
                /* Set something to make the badge looks focused */
                /* This really depends on the application, in my case it was: */

                /* Adding a light border */
                box-shadow: inset 0px 0px 5px;
                /* Taking the difference out of the padding */
            }

            .badgebox:checked + .badge
            {
                /* Move the check mark back when checked */
                text-indent: 0;
            } 
        </style>
    </body>
</html>
