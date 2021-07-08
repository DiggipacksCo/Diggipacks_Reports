<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/courier_company.js"></script> 
    </head>

    <body ng-app="CourierAppPage" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="forward_shipment_view" ng-init="loadMore(1, 0);" >

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
                        <div class="row" style="margin-top:10px">
                            <div class="col-md-12" ng-if="invalidSslip_no">
                                <div class="alert alert-warning" ng-if="invalidSslip_no" ng-repeat="in_data in invalidSslip_no"><?=lang('lang_Invalid_slip_no');?>. "{{in_data}}"</div>
                            </div>
                            <div class="col-md-12" ng-if="Success_msg">
                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?=lang('lang_Shipment_Forwarded');?></div>
                            </div>
                            <div class="col-md-12" ng-if="Error_msg">
                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                            </div>

                            <div class="col-md-12" ng-if="mainstatusEmpty">
                                <div class="alert alert-danger" ng-if="mainstatusEmpty">{{mainstatusEmpty}}</div>
                            </div>
                            <div class="col-md-12" ng-if="messArray1 != 0">
                                <div class="alert alert-danger" ng-repeat="mdata in messArray1"><?=lang('lang_wrong_AWB_no');?> {{mdata}}</div>    
                            </div>
                        </div>

                        <div class="loader logloder" ng-show="loadershow"></div>

                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong><?=lang('lang_Forward_to_TPL');?></strong>

                                            <a ng-click="getExcelDetails();" >   
                                                <i class="icon-file-excel pull-right" style="font-size: 35px;">
                                                </i>
                                            </a> 

                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;"  >
                                                <option value="" selected><?=lang('lang_select_export_limit');?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  
                                            </select> 
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="excelcolumn" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: <?= DEFAULTCOLOR; ?>;">
                                        <center>   <h4 class="modal-title" style="color:#000"><?= lang('lang_Select_Column_to_download'); ?></h4></center>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4">             
                                                <label class="container">

                                                    <input type="checkbox" id='but_checkall' value='Check all' ng-model="checkall" ng-click='toggleAll()'/>    <?= lang('lang_SelectAll'); ?>
                                                    <span class="checkmark"></span>


                                                </label>
                                            </div>

                                            <div class="col-md-12 row">
                                                <div class="col-sm-4">          
                                                    <label class="container">  
                                                        <input type="checkbox" name="Date" value="Date"    ng-model="listData2.entrydate"> <?= lang('lang_Date'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>   
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Reference" value="Reference"   ng-model="listData2.booking_id"><?= lang('lang_Reference'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Shipper_Reference" value="Shipper_Reference"   ng-model="listData2.shippers_ref_no"> <?= lang('lang_shipper_Refrence'); ?> #
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="AWB" value="AWB"   ng-model="listData2.slip_no"> <?= lang('lang_AWB_No'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Origin" value="Origin"  ng-model="listData2.origin"> <?= lang('lang_Origin'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Destination" value="Destination"  ng-model="listData2.destination"> <?= lang('lang_Destination'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Sender" value="Sender"  ng-model="listData2.sender_name"><?= lang('lang_Sender'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Sender_Address" value="Sender_Address"   ng-model="listData2.sender_address"> <?= lang('lang_Sender_Address'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Sender_Phone" value="Sender_Phone"   ng-model="listData2.sender_phone"> <?= lang('lang_Sender_Phone'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Receiver" value="Receiver"   ng-model="listData2.reciever_name"> <?= lang('lang_Receiver_Name'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Recevier_Address" value="Recevier_Address"   ng-model="listData2.reciever_address"> <?= lang('lang_Receiver_Address'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Receiver_Phone" value="Receiver_Phone"   ng-model="listData2.reciever_phone"><?= lang('lang_Receiver_Mobile'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Mode" value="Mode"  ng-model="listData2.mode"> <?= lang('lang_Mode'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Status" value="Status"  ng-model="listData2.delivered"> <?= lang('lang_Status'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="COD_Amount" value="COD_Amount"   ng-model="listData2.total_cod_amt"> <?= lang('lang_COD_Amount'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>




                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="UID_Account" value="UID_Account"  ng-model="listData2.cust_id"> <?= lang('lang_UID_Account'); ?>
                                                        <span class="checkmark"></span> 
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Pieces" value="Pieces"  ng-model="listData2.pieces" > <?= lang('lang_Pieces'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Weight" value="Weight"  ng-model="listData2.weight" > <?= lang('lang_Weight'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Description" value="Description"  ng-model="listData2.status_describtion" > <?= lang('lang_Description'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <!-- <div class="col-sm-4">
                                                     <label class="container">
                                                         <input type="checkbox" name="Forward_through" value="Forward_through"  ng-model="listData2.frwd_throw" > Forward through
                                                         <span class="checkmark"></span> 
                                                     </label>
                                                 </div> -->
                                                <div class="col-sm-4">    
                                                    <label class="container">
                                                        <input type="checkbox" name="frwd_company_awb" value="frwd_company_awb"  ng-model="listData2.frwd_company_awb"> <?= lang('lang_Forwarded_AWB_No'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>  
                                                



                                            </div>
                                            <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                            <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                                <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="forwardShipmentsExport(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div> 
                        </div>   

                        <!-- Dashboard content -->

                        <form  method="post" >
                            <div class="row" >
                                <div class="col-lg-12" >

                                    <!-- Marketing campaigns -->
                                    <div class="panel panel-flat">
                                        <div class="panel-heading">
                                        </div>

 <!-- href="<? // base_url('Excel_export/shipments');                                 ?>" -->
<!-- href="<? //base_url('Pdf_export/all_report_view');                                 ?>" -->
                                        <!-- Quick stats boxes -->
                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >
                                                        <tr style="width: 80%;">
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_warehouse');?>:</strong>
                                                                    <?php
                                                                    $whData = Getwarehouse_Dropdata();

                                                                    //print_r($destData);
                                                                    ?>
                                                                    <select  id="warehouse" name="warehouse"  ng-model="filterData.warehouse" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                                        <option value=""><?=lang('lang_Selectwarehousename');?></option>
                                                                        <?php foreach ($whData as $data): ?>
                                                                            <option value="<?= $data['id']; ?>"><?= $data['name']; ?></option>
                                                                        <?php endforeach; ?>

                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Origin');?>:</strong>
                                                                    <br>
                                                                    <?php
                                                                    $destData = getAllDestination();

                                                                    //print_r($destData);
                                                                    ?>
                                                                    <select  id="origin" name="origin"  ng-model="filterData.origin" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                                        <option value=""><?=lang('lang_selectOrigin');?></option>
                                                                        <?php foreach ($destData as $data): ?>
                                                                            <option value="<?= $data['id']; ?>"><?= $data['city']; ?></option>
                                                                        <?php endforeach; ?>

                                                                    </select>
                                                                </div> 
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Destination');?>:</strong>
                                                                    <br>
                                                                    <?php
                                                                    $destData = getAllDestination();

                                                                    //print_r($destData);
                                                                    ?>
                                                                    <select  id="destination" name="destination"  ng-model="filterData.destination" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                                        <option value=""><?=lang('lang_Select_Destination');?></option>
                                                                        <?php foreach ($destData as $data): ?>
                                                                            <option value="<?= $data['id']; ?>"><?= $data['city']; ?></option>
                                                                        <?php endforeach; ?>

                                                                    </select>
                                                                </div> 
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_sku_value');?>:</strong>
                                                                    <input type="text" id="sku_val" name="sku_val"  ng-model="filterData.sku_val"  class="form-control" placeholder="Enter Sku.">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_AWB_value');?>:</strong>
                                                                    <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr style="width: 100%;">

                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Ref_No');?>:</strong>
                                                                    <input  id="booking_id" name="booking_id"  ng-model="filterData.booking_id" class="form-control" placeholder="Enter Ref no."> 

                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Payment_type');?>:</strong>
                                                                    <br>
                                                                    <select  id="mode" name="mode" ng-model="filterData.mode" class="selectpicker"  data-width="100%" >
                                                                        <option value="COD"><?=lang('lang_COD');?></option>
                                                                        <option value="CC"><?=lang('lang_CC');?></option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td ><button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>

                                                            <td colspan=""><a  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></a></td>

                                                            <td colspan=""><a ng-click ="runshell();"class="btn btn-danger" >Sync</a></td>

                                                            

                                                        </tr>


                                                    </tbody>
                                                </table>
                                                <br>

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >
                                                        <tr style="width: 100%;">
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Please_Select');?> <?=lang('lang_Client');?>:</strong>

                                                                    <select  id="cc_id" ng-model="userselected.cc_id"  class="form-control"  >

                                                                        <option value=""><?=lang('lang_Please_Select');?> <?=lang('lang_Client');?></option>
                                                                        <?php
                                                                        foreach (GetCourierCompanyDrop() as $val) {
                                                                            echo'<option value="' . $val['cc_id'] . '">' . $val['company'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong>Pieces:</strong>

                                                                    <input type="number" min="1" step="any"  id="box_pieces" ng-model="userselected.box_pieces"  class="form-control"  >


                                                                </div>
                                                            </td>
                                                            <td ><button  class="btn btn-primary form-control" ng-click="Getforwared3plcompany();" ><i class="fa fa-refresh"></i> <?=lang('lang_Submit');?></button></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><div class="alert alert-info"><?=lang('lang_System_will_accept_only_ten_shipments_in_a_single_request');?>.</div></td>
                                                        </tr>
                                                    </tbody>
                                                </table>


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
                                                    <th><?=lang('lang_SrNo');?>. <input type="checkbox" ng-model="selectedAll" ng-change="selectAll();"></th>
                                                    <th><?=lang('lang_AWBNo');?>.</th>
                                                    <th><?=lang('lang_Ref_No');?>.</th>
                                                <th><?=lang('lang_Origin');?></th>  
                                               <th><?=lang('lang_Destination');?></th>
                                               <th><?=lang('lang_Seller');?></th>
                                                 <th><?=lang('lang_warehouse');?></th>
                                                    <th><?=lang('lang_Payment_Mode');?></th>

                                                    <th><?=lang('lang_Date');?></th>

                                                </tr>
                                            </thead>
                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                                <td>{{$index + 1}} <input type="checkbox"  class="checkBoxClass" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" value="{{data.slip_no}}"> </td>
                                                <td>{{data.slip_no}}</td>
                                                <td>{{data.booking_id}}</td>
                                                <td>{{data.origin}}</td>
                                                <td>{{data.destination}}</td>
                                                <td>{{data.name}}</td>
                                                <td>{{data.wh_id}}</td>
                                                <td>{{data.mode}}</td>

                                                <td>{{data.entrydate}}</td>
                                            </tr>

                                        </table>

                                        <a ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></a>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <!-- /basic responsive table -->
                        </form>
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->


                </div>
                <!-- /main content -->


            </div>
            <!-- /page content -->
        </div>

        <!-- /page container -->


        <script>
            $(document).ready(function () {
                $("#ckbCheckAll").click(function () {
                    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
                });
            });
        </script>
    </body>
</html>
