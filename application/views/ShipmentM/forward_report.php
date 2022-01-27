<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>


        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>

    <body ng-app="fulfill" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="forward_view" ng-init="loadMore(1, 0);" >

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
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong><?=lang('lang_All_Orders');?></strong>
                                            <!--<a  ng-click="exportExcel();" >-->
                                                <a ng-click ="runshell();" ><i class="icon-sync pull-right" style="font-size: 35px;"></i></a>
                                            <a  ng-click="getExcelDetails();" >   
                                                <i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> 

                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;"  >
                                                <option value="" selected><?=lang('lang_select_export_limit');?></option>
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
                                                <!-- <div class="col-md-3"> <div class="form-group" ><strong>Search Type:</strong>
                                                        <br>
                                                        <select  id="s_type" name="s_type" ng-model="filterData.s_type" class="selectpicker"  data-width="100%" >

                                                            <option value="AWB"><?=lang('lang_AWB');?></option>                                                        <option value="close_date">Close Date</option>


                                                        </select>
                                                    </div></div> -->
                                                <div class="col-md-3"> <div class="form-group" ><strong>Search Value:</strong>
                                                        <input type="text" id="s_type_val"  name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                                                        
                                                        <input type="text" id="s_type_val_date" style="display:none;"  name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control date" placeholder="Enter AWB no.">
                                                        <!--  <?php // if($condition!=null):      ?>
                                                         <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition; ?>" >
                                                        <?php // endif;  ?> -->
                                                    </div></div>
                                                
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong>
                                                        <br>
                                                        <select  id="seller" name="seller"  ng-model="filterData.seller" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?=lang('lang_SelectSeller');?></option>
                                                            <?php foreach ($sellers as $seller_detail): ?>
                                                                <option value="<?= $seller_detail->id; ?>"><?= $seller_detail->company; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?= lang('lang_Destination'); ?> Country/HUB:</strong>
                                                        <br>
                                                        <?php
                                                        $destData = countryList();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-change="showCity();" ng-model="filterData.country"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                                                            <option value=""><?= lang('lang_Select_Destination'); ?></option>
                                                            <?php foreach ($destData as $data) { ?>
                                                                <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div></div>
                                                 <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_Destination');?> City:</strong>
                                                        <br>
                                                       
                                                        <select  id="city" name="city" multiple  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" ng-model="filterData.destination"   >
                       
                     <option ng-repeat="cData in citylist"  data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                                                        </select>
                                                    </div></div>
                                            </div>
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Ref_No');?>:</strong>
                                                        <input  id="booking_id" name="booking_id"  ng-model="filterData.booking_id" class="form-control" placeholder="Enter Ref no."> 

                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_Exactdate');?>:</strong><br/>
                                                        <input  class="form-control date" id="exact" name="exact" ng-model="filterData.exact" >   

                                                    </div> </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_company');?>:</strong>
                                                        <br>
                                                        <?php
                                                        //$destData = getAllDestination();
                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Company');?></option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['cc_id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Payment_Mode');?>:</strong><br/>

                                                        <select  id="mode" name="cc_id"  ng-model="filterData.mode"   class="form-control" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Mode');?></option>
                                                            <option value="COD"><?=lang('lang_COD');?></option>
                                                            <option value="CC"><?=lang('lang_CC');?></option>



                                                        </select>
                                                    </div>  </div>
                                            </div>
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                <div class="col-md-3"> 
                                                    <div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.from" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.to" class="form-control"> 

                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">  <div class="form-group" ><strong><?=lang('lang_Main');?>  <?=lang('lang_Status');?>:</strong>
                                                        <br>
                                                        <select  id="status" name="status" ng-model="filterData.status" class="selectpicker" multiple data-show-subtext="true" data-live-search="true" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Status');?></option>
                                                            <?php foreach ($status as $status_detail): 
                                                                    if($status_detail->main_status == "3PL Updates"){
                                                                        continue;
                                                                        //$status_detail->code = "3PL";
                                                                    }
                                                                ?>
                                                                <option value="<?= $status_detail->code; ?>"><?= $status_detail->main_status; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-3 other_status">
                                                    <div class="form-group" ><strong>3PL <?=lang('lang_Status');?>:</strong>
                                                        <br>
                                                        <select  id="status_o" name="status_other"  ng-model="filterData.status_o"  class="selectpicker"  multiple data-show-subtext="true" data-live-search="true" data-width="100%" >
                                                            <option value=""><?=lang('lang_Select_Status');?></option>
                                                            <?php 
                                                                foreach ($status_3pl as $status_detail):
                                                                    if($status_detail['code'] == 'DLM') {continue;}
                                                            ?>
                                                                <option value="<?= $status_detail['code']; ?>"><?= $status_detail['sub_status']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>   
                                                </div>
                                            </div>
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Quantity');?>:</strong>
                                                        <input type="number" class="form-control" id="piece" name="piece"  ng-model="filterData.piece" class="form-control" placeholder="Enter Qauntity"> 

                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_SKU');?>:</strong>
                                                        <input class="form-control" id="sku" name="sku"  ng-model="filterData.sku" class="form-control" placeholder="Enter SKU"> 

                                                    </div></div>
                                                <div class="col-md-3">  <div class="form-group" ><strong><?=lang('lang_COD_Amount');?>:</strong>
                                                        <input class="form-control" type="number" id="cod" name="cod"  ng-model="filterData.cod" class=" " placeholder="Enter COD Amount"> 

                                                    </div></div>
                                              
                                                <div class="col-md-2" style="margin-top: 20px;"><div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.sort_limit" ng-change="loadMore(1, 1);">
                                                            <option value=""><?=lang('lang_Short');?></option>
                                                            <option ng-repeat="(key,value) in dropshort" value="{{key}}-{{value}}">{{value}}</option>
                                                        </select>
                                                    </div></div>
                                            </div>
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                
                                                 <div class="col-md-3"> 
                                                    <div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.from_c" placeholder="From Close Date" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.to_c" placeholder="To Close Date" class="form-control"> 

                                                    </div>
                                                </div>


                                                <div class="col-md-3"> 
                                                    <div class="form-group" ><strong>Forward Date <?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.f_from" placeholder="From Forward Date" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Forward Date <?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.f_to" placeholder="To Forward Date" class="form-control"> 

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                <div class="col-md-2">
                                                    <div class="form-group" ><strong><?=lang('lang_Mobile');?>:</strong>
                                                        <input class="form-control" id="reciever_phone" name="reciever_phone"  ng-model="filterData.reciever_phone" class="form-control" placeholder="Enter Mobile No."> 
                                                    </div>
                                                </div>
                                                 <div class="col-md-2" style="margin-top: 20px;"><div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.sort_list" ng-change="loadMore(1, 1);">
                                                            <option value=""><?=lang('lang_Short_List');?></option>
                                                            <option value="NO"><?=lang('lang_Newest_Order');?></option>
                                                            <option value="OLD"><?=lang('lang_Oldest_Order');?></option>
                                                            <option value="OBD"><?=lang('lang_Order_By_Date');?></option>
                                                        </select>
                                                    </div>
                                                 </div>
                                                    <div class="col-md-2" style="margin-top: 20px;">
                                                        <div class="form-group" >
                                                            <select class="form-control"  ng-model="filterData.order_type">
                                                                <option value="">Order Type</option>
                                                                <option  value="B2B">B2B</option>
                                                                <option  value="B2C">B2C</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <div class="col-md-5"><div class="form-group" >
                                                        <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button>
                                                        <button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                        
                                                         <?php if (menuIdExitsInPrivilageArray(122) == 'Y') { ?>
                                                        <button  class="btn btn-danger ml-10" ng-confirm-click="Are you sure want delete Orders?" ng-click="removemultipleorder();" ><?=lang('lang_Delete');?></button>
                                                         <?php } ?>
                                                    </div>
                                                </div>


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
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                        <thead>

                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.  <input type="checkbox" ng-model="selectedAll"  ng-change="selectAll();" /></th>
                                                <th><?=lang('lang_Order_Type');?></th>
                                                <th><?=lang('lang_AWBNo');?>.</th>
                                                <th><?=lang('lang_Forwarded_AWB_No');?>.</th>
                                                 <th><?=lang('lang_Detail');?></th>
                                                <th><?=lang('lang_Ref_No');?>.</th>
                                                <th><?=lang('lang_Origin');?></th>  
                                               <th><?=lang('lang_Destination');?></th>
                                                <th><?=lang('lang_Receiver');?></th>
                                               <th><?=lang('lang_Receiver_Address');?></th>
                                                <th><?=lang('lang_Receiver_Mobile');?></th>
                                                <th><?=lang('lang_Item_Detail');?></th>
                                                <!-- <th>Cartoon Sku#</th> -->
                                                <th><?=lang('lang_Payment_Mode');?></th>
                                                 <th><?=lang('lang_Payment_Date');?></th>
                                                <th><?=lang('lang_Status');?></th>
                                               <!-- <th>Quantity</th>-->
                                                <!-- <th>Cartoon Quantity</th> -->
                                               <th><?=lang('lang_Seller');?></th>
                                                 <th><?=lang('lang_warehouse');?></th>
                                                 <th><?=lang('lang_Date');?></th>
                                                <th><?=lang('lang_No_of_Attempt');?></th>
                                                <th><?=lang('lang_tpl_Pickup_Date');?></th>
                                                <th><?=lang('lang_tpl_Closed_Date');?></th>
                                                 <th>Close Date</th>
                                                <th><?=lang('lang_Transaction');?> <?=lang('lang_Day');?></th>
                                                 
                                                <th>Transaction Timer</th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>  
                                        </thead>   
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}} 
                                                <input type="checkbox" value="{{data.slip_no}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" />
                                            </td>    
                                            <td><span class="label label-success" ng-if="data.order_type == 'B2B'">{{data.order_type}}</span>
                                                <span class="label label-warning" ng-if="data.order_type == 'B2C'">{{data.order_type}}</span></td>
                                            <td>{{data.slip_no}} </td>     
                                            <td>{{data.frwd_company_awb}}  </td> 
                                            <td>{{data.details}}</td>
                                            <td>{{data.booking_id}}</td>
                                            <td>{{data.origin}}</td>
                                            <td>{{data.destination}}</td>
                                            <td>{{data.reciever_name}}</td>
                                            <td>{{data.reciever_address}}</td>
                                            <td>{{data.reciever_phone}}</td>   
                                            <td><a  ng-click="GetInventoryPopup(data.slip_no);"><span class="label label" style="background-color:<?= DEFAULTCOLOR; ?>;"><?=lang('lang_Get_Details');?></span></a></td>
                                            <td>{{data.mode}}
                                            <span ><br>({{data.total_cod_amt}})</span></td>
                                            <td>--</td>
<!--                                            <td>{{data.main_status}}</td>-->
                                            <td>{{data.status}}</td>
                                            <!--<td>{{data.piece}}</td>-->
                                            <td>{{data.company}}</td>
                                            <td>{{data.wh_id}}</td>
                                            <td>{{data.entry_date}}</td>
                                            <td>{{data.no_of_attempt}}</td>
                                            <td>{{data.pl3_pickup_date}}</td>
                                            <td>{{data.pl3_closed_date}}</td>
                                             <td>{{data.close_date}}</td>
                                            <td>{{data.transaction_days}}</td>
                                            <td >

                                            <span ng-if="data.pl3_pickup_date!=null && data.close_date==null" bs-time-counter date="data.mydate" direction="'up'">
      
                                           <div class="alert alert-success ">{{ days }} days, {{ hours }}:{{ minutes }}:{{ seconds }} </div> </span>
                                            </td>
                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>   

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                              <li ng-if="data.code=='OG' || data.code=='OC' || data.code=='PG' || data.code=='AP' || data.code=='PK' || data.code=='DL'"><a href="<?= base_url(); ?>Shipment/edit_view/{{data.id}}"><i class="icon-pencil7"></i> Edit </a></li>
<!--                                                            <li><a href="<?= base_url(); ?>Shipment/edit_view/{{data.id}}"><i class="icon-pencil7"></i> <?=lang('lang_Change_Destination');?> </a></li>    -->
                                                            <li><a class="dropdown-item" href="<?= base_url(); ?>awbPrint1/{{data.slip_no}}" target="_blank" style="  word-wrap: break-word;"><i class="fa fa-print fa-fw"></i><?=lang('lang_Label_A4');?> </a> </li>     
                                                            <!-- <li><a ng-if="data.frwd_company_awb != ''" class="dropdown-item" href="<?= base_url(); ?>Printpicklist3PL_bulk/{{data.slip_no}}/{{data.frwd_company_id}}" target="_blank"> <i class="fa fa-print fa-fw"></i><? //=lang('lang_TPL_AWB');?></a> </li>    -->
                                                            <li><a ng-if="data.frwd_company_label != ''" class="dropdown-item" href="{{data.frwd_company_label}}" target="_blank"> <i class="fa fa-print fa-fw"></i><?=lang('lang_TPL_AWB');?></a> </li>   
                                                            <li ng-if="data.product_invoice!=null"><a class="dropdown-item" href="<?= base_url(); ?>assets/product_invoice/{{data.slip_no}}.pdf" target="_blank"> <i class="fa fa-print fa-fw"></i>Print Invoice</a> </li>   

                                                               <?php //if (menuIdExitsInPrivilageArray(124) == 'Y') { ?>
                                                           <!--  <li><a ng-if="data.delivered == 'OC' || data.code == 'PG' || data.code == 'AP' || data.code == 'PK'" class="dropdown-item" ng-click="GetProcessOpenOrder(data.slip_no);" ng-confirm-click="are you sure want to open order?"> <i class="fa fa-openid fa-fw"></i>Open Order</a> </li>  -->
                                                            <?php //} ?> 

                                                            <?php if (menuIdExitsInPrivilageArray(124) == 'Y') { ?>
                                                            <li>
                                                                <a ng-if="data.code == 'OC' || data.code == 'PG' || data.code == 'AP' || data.code == 'PK'" class="dropdown-item" ng-click="GetStockLocationPopup(data.slip_no);"> <span><i class="fa fa-openid fa-fw"></i> <?=lang('lang_Open_Order');?> </span> </a>
                                                            </li> 
                                                            <?php } ?>


                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>

                                        </tr>

                                    </table>

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></button>
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
<!--- get stock location details popup starts -->
 <div id="StocklocationModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger" style="background-color:<?= DEFAULTCOLOR; ?>;border-color:<?= DEFAULTCOLOR; ?>">
                            <h6 class="modal-title">Item Detail</h6>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>

                        <div class="modal-body">
                       
                            <table class="table">
                                <thead>
                                    <tr>                                      
                                    <th><?=lang('lang_SKU');?> </th>
                                    <th><?=lang('lang_QTY');?></th>
                                        <th><?=lang('lang_Shelve_No');?></th>
                                        <th><?=lang('lang_Stock_Location');?></th> 
                                    </tr>
                                </thead>
                                <tbody>
                             
                                    <tr ng-repeat="stockdat in itemData1" > 
                               
                                        <td><span class="label label-primary">{{stockdat.sku}}</span> </td>
                                        <td><span class="label label-info">{{stockdat.piece}}</span></td>
                                        <td><div ng-repeat="sct1 in stockdat.local" > 
                                         <div ng-repeat="sct11 in sct1" ng-init="id_value=0">    
                                           
                                            <input type="text" class="form-control" my-enter="setFocus($parent.$parent.$index+$parent.$index+$index,'sh')"  id="sh_{{$parent.$parent.$index+$parent.$index+$index}}" ng-model="itemData1[$parent.$parent.$index].local[$parent.$index][$index].shelve_no"/>
                                           
                                          </div> </td>
                                        <!-- <td ng-repeat ="sct in stockdat.local"><span class="label label-info" >{{sct.id}}</span></td> -->
                                         <td>
                                         <div ng-repeat="sct122 in stockdat.local"> 
                                         <div ng-repeat="sct in sct122" >    
                                            <input type="text" class="form-control" my-enter="setFocus($parent.$parent.$index+$parent.$index+$index,'st')"  id="st_{{$parent.$parent.$index+$parent.$index+$index}}" ng-model="itemData1[$parent.$parent.$index].local[$parent.$index][$index].stock_location"/>
                                           
                                          </div> 
                                        </td> 
                                              </tr>
                                  <tr> 
                                    <td colspan="4"><a ng-click="savedetails();" class="btn btn-primary"> <span> <i class="fa fa-btn"></i> <?=lang('lang_Save');?> </span>
                                    </a></td>
                                  </tr>
                                </tbody>
                               
                            </table>
                        </div>
                    </div>
                </div>


            </div>
<!--- get stock location details popup ends -->
            <div id="deductQuantityModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger" style="background-color:<?= DEFAULTCOLOR; ?>;border-color:<?= DEFAULTCOLOR; ?>">
                            <h6 class="modal-title"><?=lang('lang_Item_Sku_Detail');?></h6>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>

                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th><?=lang('lang_SKU');?> </th>
                                         <th><?=lang('lang_Gift_Item');?>  </th>
                                         <th><?=lang('lang_QTY');?></th>
                                    <th><<?=lang('lang_Deducted_Shelve_NO');?></th>
                                        <th><?=lang('lang_COD');?> (<?= site_configTable("default_currency"); ?>)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="dataship in shipData1">
                                        <td><span class="label label-primary">{{dataship.sku}}</span> </td>
                                         <td><span  ng-if="dataship.free_sku=='N'" class="label label-warning">No</span> <span ng-if="dataship.free_sku=='Y'" class="label label-primary"><?=lang('lang_Yes');?></span></td>
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

                                        <input type="checkbox" id='but_checkall' value='Check all' ng-model="checkall" ng-click='toggleAll()'/>   <b><?= lang('lang_SelectAll'); ?></b>
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
                                            <input type="checkbox" name="Mode" value="Mode"  ng-model="listData2.mode"> <?=lang('lang_Payment_Mode');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Status" value="Status"  ng-model="listData2.delivered"><?= lang('lang_Main'); ?> <?= lang('lang_Status'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Status_O" value="Status_O"  ng-model="listData2.status_o"> 3PL Status
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                      <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="last_status_n" value="last_status_n"  ng-model="listData2.last_status_n"> Last Status
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
                                            <input type="checkbox" name="Forward_awb" value="Forward_awb"  ng-model="listData2.frwd_awb_no"> <?= lang('lang_Forwarded_AWB_No'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>  
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="transaction_no" value="transaction_no"  ng-model="listData2.transaction_no"> <?= lang('lang_Transaction_Number'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="invoice_details" value="invoice_details"  ng-model="listData2.invoice_details"> <?=lang('lang_invoice');?> <?=lang('lang_Details');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="pl3_pickup_date" value="pl3_pickup_date"  ng-model="listData2.pl3_pickup_date"> <?=lang('lang_tpl_Pickup_Date');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="frwd_date" value="frwd_date"  ng-model="listData2.frwd_date"> 3PL Forward Date
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                  <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="transaction_days" value="transaction_days"  ng-model="listData2.transaction_days"> Transaction Days
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="no_of_attempt" value="no_of_attempt"  ng-model="listData2.no_of_attempt"> <?=lang('lang_No_of_Attempt');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="cc_name" value="cc_name"  ng-model="listData2.cc_name"> <?=lang('lang_Forwarded_Company');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="close_date" value="close_date"  ng-model="listData2.close_date"> <?=lang('lang_close_date');?>
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
     <script type="text/javascript">
       
       $("#s_type_val_date").hide();
            $(document).ready(function() {
            $('#s_type').on('change', function () {
//                if ($('#s_type').val() == "SKU") {
//                    $('#s_type_val').attr('placeholder', 'Enter SKU no.');
//                } else 
               if ($('#s_type').val() == "AWB") {
                
                    $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                    $('#s_type_val').prop('readonly', false);
                     // $("#s_type_val").removeClass("date_new");
                      // $( ".date_new" ).datepicker( "option", "disabled", true );
                   
                      $('#s_type_val').val("");
                       $("#s_type_val_date").hide();
                         $("#s_type_val").show();
                     
                }
                else if ($('#s_type').val() == "close_date") {
                     $('#s_type_val_date').val("");
                    $('#s_type_val_date').attr('placeholder', 'Enter Close Date.');
                     $("#s_type_val_date").addClass("date_new");
                     $('#s_type_val_date').prop('readonly', true);
                     $("#s_type_val_date").show();
                       $("#s_type_val").hide();
                     $('.date_new').datepicker({

                format: 'yyyy-mm-dd'

            });
                }

            });
             });


        </script>
        <!-- /page container -->
        <script type="text/javascript">

            $('.date').datepicker({

                format: 'yyyy-mm-dd'

            });


 $('.date_new').datepicker({

                format: 'yyyy-mm-dd'

            });

        </script>
    </body>
</html>
