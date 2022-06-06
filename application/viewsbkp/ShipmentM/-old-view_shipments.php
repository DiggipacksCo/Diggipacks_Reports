<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>


        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>

    <body ng-app="fulfill" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_view" ng-init="loadMore(1, 0);" >

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

                        <div class="loader logloder" ng-show="loadershow"></div>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong>All  Orders</strong>
                                            <!--<a  ng-click="exportExcel();" >-->
                                            <a  ng-click="getExcelDetails();" >   
                                                <i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> 

                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;"  >
                                                <option value="" selected>Select Export Limit</option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  

                                            </select> 
<!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>-->
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments');    ?>" -->
                         <!-- href="<? //base_url('Pdf_export/all_report_view');    ?>" -->
                                        <!-- Quick stats boxes -->
                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->
                                                <div class="col-md-3"> <div class="form-group" ><strong>AWB or SKU:</strong>
                                                        <br>
                                                        <select  id="s_type" name="s_type" ng-model="filterData.s_type" class="selectpicker"  data-width="100%" >

                                                            <option value="AWB">AWB</option>
                                                            <option value="SKU">SKU</option>


                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>AWB or SKU value:</strong>
                                                        <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                                                        <!--  <?php // if($condition!=null):    ?>
                                                         <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition; ?>" >
                                                        <?php // endif;  ?> -->
                                                    </div></div>
                                                <div class="col-md-3">  <div class="form-group" ><strong>Status:</strong>
                                                        <br>
                                                        <select  id="status" name="status" ng-model="filterData.status" class="selectpicker" multiple data-show-subtext="true" data-live-search="true" data-width="100%" >

                                                            <option value="">Select Status</option>
                                                            <?php foreach ($status as $status_detail): ?>
                                                                <option value="<?= $status_detail->id; ?>"><?= $status_detail->main_status; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div> </div>
                                                <div class="col-md-3"><div class="form-group" ><strong>Seller:</strong>
                                                        <br>
                                                        <select  id="seller" name="seller"  ng-model="filterData.seller" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value="">Select Seller</option>
                                                            <?php foreach ($sellers as $seller_detail): ?>
                                                                <option value="<?= $seller_detail->id; ?>"><?= $seller_detail->name; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>Destination:</strong>
                                                        <br>
                                                        <?php
                                                        $destData = getAllDestination();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-model="filterData.destination" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value="">Select Destination</option>
                                                            <?php foreach ($destData as $data): ?>
                                                                <option value="<?= $data['id']; ?>"><?= $data['city']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> </div>
                                                <div class="col-md-3"><div class="form-group" ><strong>Ref. No:</strong>
                                                        <input  id="booking_id" name="booking_id"  ng-model="filterData.booking_id" class="form-control" placeholder="Enter Ref no."> 

                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>Exact date:</strong><br/>
                                                        <input  class="form-control date" id="exact" name="exact" ng-model="filterData.exact" >   

                                                    </div> </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>Company:</strong>
                                                        <br>
                                                        <?php
                                                        //$destData = getAllDestination();
                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value="">Select Company</option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>From:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.from" class="form-control"> 

                                                    </div> </div>
                                                <div class="col-md-3"><div class="form-group" ><strong>To:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.to" class="form-control"> 

                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong>Payment Mode:</strong><br/>

                                                        <select  id="mode" name="cc_id"  ng-model="filterData.mode"   class="form-control" data-width="100%" >

                                                            <option value="">Select Mode</option>
                                                            <option value="COD">COD</option>
                                                            <option value="CC">CC</option>


                                                        </select>
                                                    </div>  </div>
                                                <div class="col-md-3"><div class="form-group" ><strong>Qauntity:</strong>
                                                        <input type="number" class="form-control" id="piece" name="piece"  ng-model="filterData.piece" class="form-control" placeholder="Enter Qauntity"> 

                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>SKU:</strong>
                                                        <input class="form-control" id="sku" name="sku"  ng-model="filterData.sku" class="form-control" placeholder="Enter SKU"> 

                                                    </div></div>
                                                <div class="col-md-3">  <div class="form-group" ><strong>COD Amount:</strong>
                                                        <input class="form-control" type="number" id="cod" name="cod"  ng-model="filterData.cod" class=" " placeholder="Enter COD Amount"> 

                                                    </div></div>
                                                <div class="col-md-2" style="margin-top: 20px;"><div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.sort_limit" ng-change="loadMore(1, 1);">

                                                            <option value="">Short</option>


                                                            <option ng-repeat="(key,value) in dropshort" value="{{key}}-{{value}}">{{value}}</option>

                                                        </select>

                                                    </div></div>
                                                <div class="col-md-5"><div class="form-group" >
                                                        <button  class="btn btn-danger" ng-click="loadMore(1, 1);" >Search</button>
                                                        <button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                          <button  class="btn btn-danger ml-10" ng-confirm-click="Are you sure want delete Orders?" ng-click="removemultipleorder();" >Delete</button>





                                                    </div></div>

                                              









                                                <div id="today-revenue"></div>
                                                <!-- </div> panel-body-->

                                                <!-- /today's revenue -->

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


                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
                                        <thead>

                                            <tr>
                                                <th>Sr.No.  <input type="checkbox" ng-model="selectedAll"  ng-change="selectAll();" /></th>
                                                <th>Order Type</th>
                                                <th>AWB No .</th>
                                                <th>Forwarded AWB No.</th>
                                                <th>Forwarded Company</th>
                                                <th>Ref. No.</th>
                                                <th>Origin</th>  
                                                <th>Destination</th>
                                                <th>Receiver</th>
                                                <th>Receiver Address</th>
                                                <th>Receiver Mobile</th>
                                                <th>Item Details</th>
                                                <!-- <th>Cartoon Sku#</th> -->
                                                <th>Payment Mode</th>
                                                <th>Status</th>
                                               <!-- <th>Quantity</th>-->
                                                <!-- <th>Cartoon Quantity</th> -->
                                                <th>Seller</th>
                                                <th>Warehouse</th>
                                                <th>Date</th>  
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>  
                                        </thead>  
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}} 
                                            <input type="checkbox" value="{{data.slip_no}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" />
                                                
                                            <td><span class="label label-success" ng-if="data.order_type == 'B2B'">{{data.order_type}}</span>
                                                <span class="label label-warning" ng-if="data.order_type == 'B2C'">{{data.order_type}}</span></td>
                                            <td><a href="<?php base_url() ?>TrackingDetails/{{data.slip_no}}"  target="_blank"> {{data.slip_no}}  </a></td>     
                                            <td>{{data.frwd_company_awb}}</td> 
                                            <td>{{data.cc_name}}</td>
                                            <td>{{data.booking_id}}</td>
                                            <td>{{data.origin}}</td>
                                            <td>{{data.destination}}</td>
                                            <td>{{data.reciever_name}}</td>
                                            <td>{{data.reciever_address}}</td>
                                            <td>{{data.reciever_phone}}</td>   
                                            <td><a  ng-click="GetInventoryPopup(data.slip_no);"><span class="label label" style="background-color:<?=DEFAULTCOLOR;?>;">Get Details</span></a></td>
                                            <td>{{data.mode}}</td>  
                                            <td>{{data.main_status}}</td>
                                            <!--<td>{{data.piece}}</td>-->
                                            <td>{{data.name}}</td>
                                            <td>{{data.wh_id}}</td>
                                            <td>{{data.entrydate}}</td>
                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>   

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li><a href="<?= base_url(); ?>Shipment/edit_view/{{data.id}}"><i class="icon-pencil7"></i> Change Destination </a></li>    
                                                            <li><a class="dropdown-item" href="<?= base_url(); ?>awbPrint1/{{data.slip_no}}" target="_blank" style="  word-wrap: break-word;"><i class="fa fa-print fa-fw"></i>Label A4 </a> </li>     
                                                            <li><a ng-if="data.frwd_company_awb != ''" class="dropdown-item" href="<?= base_url(); ?>Printpicklist3PL_bulk/{{data.slip_no}}/{{data.frwd_company_awb}}" target="_blank"> <i class="fa fa-print fa-fw"></i>3PL AWB</a> </li>   
                                                            
                                                              <li><a ng-if="data.delivered== 'OC' || data.code== 'PG' || data.code== 'AP' || data.code== 'PK'" class="dropdown-item" ng-click="GetProcessOpenOrder(data.slip_no);" ng-confirm-click="are you sure want to open order?"> <i class="fa fa-openid fa-fw"></i>Open Order</a> </li> 


                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>

                                        </tr>

                                    </table>

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1">Load More</button>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table -->
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->


                </div>
                <!-- /main content -->


            </div>
            <!-- /page content -->

            <div id="deductQuantityModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger" style="background-color:<?=DEFAULTCOLOR;?>;border-color:<?=DEFAULTCOLOR;?>">
                            <h6 class="modal-title">Item Sku Detail</h6>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>

                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SKU </th>
                                        <th>Qty</th>
                                        <th>Deducted Shelve NO</th>
                                        <th>COD (SAR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="dataship in shipData1">
                                        <td><span class="label label-primary">{{dataship.sku}}</span></td>
                                        <td><span class="label label-info">{{dataship.piece}}</span></td>
                                        <td><span class="label label-info">{{dataship.deducted_shelve}}</span></td>
                                        <td><span class="label label-danger">{{dataship.cod}}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>


            <div id="excelcolumn" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: <?=DEFAULTCOLOR;?>;">
                            <center>   <h4 class="modal-title" style="color:#000"><?= lang('lang_Select_Column_to_download'); ?></h4></center>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">             
                                    <label class="container">

                                        <input type="checkbox" id='but_checkall' value='Check all' ng-model="listData2.checked" ng-click='checkAll()'/>    <?= lang('lang_SKU'); ?>
                                        <span class="checkmark"></span>


                                    </label>
                                </div>

                                <div class="col-md-12 row">
                                    <div class="col-sm-4">          
                                        <label class="container">  
                                            <input type="checkbox" name="Date" value="Date"   ng-checked="checkall" ng-model="listData2.entrydate"> <?= lang('lang_Date'); ?>
                                            <span class="checkmark"></span>
                                        </label>   
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Reference" value="Reference"  ng-checked="checkall" ng-model="listData2.booking_id"><?= lang('lang_Reference'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Shipper_Reference" value="Shipper_Reference"  ng-checked="checkall" ng-model="listData2.shippers_ref_no"> <?= lang('lang_shipper_Refrence'); ?> #
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="AWB" value="AWB"  ng-checked="checkall" ng-model="listData2.slip_no"> <?= lang('lang_AWB_No'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Origin" value="Origin" ng-checked="checkall" ng-model="listData2.origin"> <?= lang('lang_Origin'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Destination" value="Destination" ng-checked="checkall" ng-model="listData2.destination"> <?= lang('lang_Destination'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender" value="Sender" ng-checked="checkall" ng-model="listData2.sender_name"><?= lang('lang_Sender'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender_Address" value="Sender_Address"  ng-checked="checkall" ng-model="listData2.sender_address"> <?= lang('lang_Sender_Address'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender_Phone" value="Sender_Phone"  ng-checked="checkall" ng-model="listData2.sender_phone"> <?= lang('lang_Sender_Phone'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Receiver" value="Receiver"  ng-checked="checkall" ng-model="listData2.reciever_name"> <?= lang('lang_Receiver_Name'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Recevier_Address" value="Recevier_Address"  ng-checked="checkall" ng-model="listData2.reciever_address"> <?= lang('lang_Receiver_Address'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Receiver_Phone" value="Receiver_Phone"  ng-checked="checkall" ng-model="listData2.reciever_phone"><?= lang('lang_Receiver_Mobile'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Mode" value="Mode" ng-checked="checkall" ng-model="listData2.mode"> <?= lang('lang_Mode'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Status" value="Status" ng-checked="checkall" ng-model="listData2.delivered"> <?= lang('lang_Status'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="COD_Amount" value="COD_Amount"  ng-checked="checkall" ng-model="listData2.total_cod_amt"> <?= lang('lang_COD_Amount'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>




                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="UID_Account" value="UID_Account" ng-checked="checkall" ng-model="listData2.cust_id"> <?= lang('lang_UID_Account'); ?>
                                            <span class="checkmark"></span> 
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Pieces" value="Pieces" ng-checked="checkall" ng-model="listData2.pieces" > <?= lang('lang_Pieces'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Weight" value="Weight" ng-checked="checkall" ng-model="listData2.weight" > <?= lang('lang_Weight'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Description" value="Description" ng-checked="checkall" ng-model="listData2.status_describtion" > <?= lang('lang_Description'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <!-- <div class="col-sm-4">
                                         <label class="container">
                                             <input type="checkbox" name="Forward_through" value="Forward_through" ng-checked="checkall" ng-model="listData2.frwd_throw" > Forward through
                                             <span class="checkmark"></span> 
                                         </label>
                                     </div> -->
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="Forward_awb" value="Forward_awb" ng-checked="checkall" ng-model="listData2.frwd_awb_no"> <?= lang('lang_Forwarded_AWB_No'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>  
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="transaction_no" value="transaction_no" ng-checked="checkall" ng-model="listData2.transaction_no"> <?= lang('lang_Transaction_Number'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>



                                </div>
                                <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                    <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShip1(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
                                </div>

                            </div>

                        </div>
                    </div>
                </div>  


            </div>   





        </div>
        <script>


            // "order": [[0, "asc" ]]
            $('#s_type').on('change', function () {
                if ($('#s_type').val() == "SKU") {
                    $('#s_type_val').attr('placeholder', 'Enter SKU no.');
                } else if ($('#s_type').val() == "AWB") {
                    $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                }

            });


        </script>
        <!-- /page container -->
        <script type="text/javascript">

            $('.date').datepicker({

                format: 'yyyy-mm-dd'

            });

        </script>
    </body>
</html>
