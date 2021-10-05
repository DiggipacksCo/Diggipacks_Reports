<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js"></script>
    </head>

    <body ng-app="Appiteminventory">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="InventoryRecord" ng-init="loadMore(1, 0);"> 

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
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            ?>
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading" dir="ltr">
                                        <h1><strong><?=lang('lang_InventoryRecordTable');?></strong>
                                            <a  ng-click="getExcelDetails();" >   
                                                <i class="icon-file-excel pull-right" style="font-size: 35px;">
                                                </i>
                                            </a> 

                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;"  >
                                                <option value="" selected><?=lang('lang_select_export_limit');?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  
                                            </select> 
                                            <!--<a href="<?= base_url('Excel_export/shipments'); ?>"></a>--> 
                                            <!--                                            <a  ng-click="ExportExcelitemInventory();" >
                                                                                            <i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i>
                                                                                        </a>&nbsp;&nbsp; 
                                                                                        <a onclick="printPage('block1');" >
                                                                                            <i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i>
                                                                                        </a>-->
                                        </h1>

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
                                                                <input type="checkbox" id='but_checkall' value='Check all' ng-model="checkall" ng-click='toggleAll()'/>    <?=lang('lang_SelectAll');?>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-md-12 row">
                                                            <div class="col-sm-4">          
                                                                <label class="container">  
                                                                    <input type="checkbox" name="seller_name" value="seller_name"    ng-model="listData2.seller_name"> <?=lang('lang_Seller_Name');?>
                                                                    <span class="checkmark"></span>
                                                                </label>   
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="sku" value="sku"   ng-model="listData2.sku"> <?=lang('lang_Sku');?>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="itype" value="itype"   ng-model="listData2.itype"> <?=lang('lang_Item_Type');?>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="entrydate" value="entrydate"   ng-model="listData2.entrydate"> <?=lang('lang_Entry_Date');?>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="qty_count" value="qty_count"   ng-model="listData2.qty_count"> <?=lang('lang_Quantity');?>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="no_of_pallets" value="no_of_pallets"  ng-model="listData2.no_of_pallets"> <?=lang('lang_NoofPallete');?>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="size" value="size"  ng-model="listData2.size"> <?=lang('lang_Size');?>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                        <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                                        <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                                            <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="InboundRecordExport(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div> 
                                    </div>   

                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 

                                            <!-- Today's revenue --> 

                                            <!-- <div class="panel-body" style="background-color: pink;"> -->

                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >
                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong><?=lang('lang_SKU');?>:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                            </div></td>
                                                        <td ><div class="form-group" ><strong><?=lang('lang_Quantity');?>:</strong>
                                                                <input type="number" min="1" id="quantity"name="quantity"  ng-model="filterData.quantity" class="form-control" placeholder="Enter Quantity">
                                                            </div></td>
                                                        <td ><div class="form-group"><strong><?=lang('lang_From');?>:</strong>
                                                                <input type="date" id="from"name="from" ng-model="filterData.from" class="form-control">
                                                            </div></td>
                                                        <td><div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                                <input type="date" id="to"name="to" ng-model="filterData.to" class="form-control">
                                                            </div></td>
                                                    </tr>
                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong><?=lang('lang_Exactdate');?>:</strong>
                                                                <input type="date" id="exact"name="exact"  ng-model="filterData.exact" class="form-control">
                                                            </div></td>
                                                        <td ><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong> <br>
                                                                <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                                                                    <option value=""><?=lang('lang_Select_Seller');?></option>
                                                                    <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                            <?= $seller_detail->company; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>
                                                        <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?>  <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                        <td><button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button></td>

<!--<td colspan="2">
  <div class="form-group" style="background-color: pink;"><strong><p style="text-align: center;" id="result"><?php //echo "Total ".count($items)." entries";        ?></p></strong>
  style="background-color: pink;width: 80%;" 
         
    </div>
</td>--> 

                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br>
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
                            <!--style="padding-bottom:220px;background-color: lightgray;"--> 

                            <!-- <div class="panel-heading"> --> 
                            <!-- <h5 class="panel-title">Basic responsive table</h5> --> 
                            <!-- <h1><strong>Shipments Table</strong> --><!-- <a href="<?//base_url('Excel_export/shipments');?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></hr> --> 

                            <!-- <div class="heading-elements">
                  <ul class="icons-list">
                    <li><a href="<?// base_url('Excel_export/shipments');?>"><i class="icon-file-excel"></i></a></li>
                            --> 
                            <!-- <li><a data-action="collapse"></a></li>
                  <li><a data-action="reload"></a></li> --> 
                            <!-- <li><a data-action="close"></a></li> --> 
                            <!-- </ul>
                  </div> --> 
                            <!-- <hr> --> 
                            <!-- </div> -->

                            <div class="panel-body" > 
                              <!-- <input type="text" value="{{data1.sku}}" id="check" style="display: none;" name="check" />
                                -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                            <th><?=lang('lang_SrNo');?>.</th>
                                        <th><?=lang('lang_Seller');?></th>
                                        <th><?=lang('lang_Item_Image');?></th>
                                        <th><?=lang('lang_ItemSku');?></th>
                                        <th> <?=lang('lang_Item_Type');?></th>
                                        <th><?=lang('lang_Quantity');?></th>
                                                <th><?=lang('lang_NoofPallete');?></th>
                                                <th><?=lang('lang_Capacity');?></th>

                                                <th><?=lang('lang_Entry_Date');?></th>

<!--<th class="text-center" ><i class="icon-database-edit2"></i></th>--> 
                                            </tr>
                                        </thead>
                                        <tbody id="">


                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}} </td>
                                                <td>{{data.seller_name}}</td>
                                                <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="100">
                                                    <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="100">
                                                </td>
                                                <td>{{data.sku}}</td>
                                                <th>{{data.itype}}</th>

                                                <td ><a  class="btn btn-danger" ng-if="data.entrydate == data.current_date" ng-dblclick="enableEdit($index);" ng-hide="data.enable" >{{data.qty_count}}</a>

                                                    <a  class="btn btn-danger" ng-if="data.entrydate != data.current_date"  ng-hide="data.enable" >{{data.qty_count}}</a>

                                                    <input  ng-show="data.enable" type="text" style="width: 50px;" ng-model="data.new_qty" ><a  class="badge badge-success" ng-show="data.enable" ng-click="saveUpdate($index);"><?=lang('lang_Save');?></a><a  class="badge badge-danger" ng-show="data.enable" ng-click="cancleChange($index);"><?=lang('lang_cancel');?></a> </td> 

                                                <td ><span class="badge badge-success">{{data.no_of_pallets}}</span></td> 
                                                <td ><span class="badge badge-warning">{{data.size}}</span></td>

                                                <td>{{data.entrydate}}</td>

                                            </tr>
                                        </tbody>
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
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">



                        <h5 class="modal-title" id="exampleModalLabel"><?=lang('lang_UpdateLocation');?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form name="myform" novalidate ng-submit="myForm.$valid && GetupdatelocationData()" enctype="multipart/form-data" >
                        <div class="modal-body">
                            <select type="text" name="locationUp" id="locationUp" ng-model="UpdateData.locationUp" class="form-control" required>
                                <option value="error"><?=lang('lang_SelectLocation');?></option>
                                <option ng-repeat="data2 in locationData" value="{{data2.stock_location}}">{{data2.stock_location}}</option>
                            </select>





                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('lang_Close');?></button>
                            <button type="button" class="btn btn-primary" ng-click="GetupdatelocationData();" ><?=lang('lang_Update');?></button>
                        </div>
                    </form>          
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">



                        <h5 class="modal-title" id="exampleModalLabel"><?=lang('lang_UpdateQTY');?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form name="myform" novalidate ng-submit="myForm.$valid && GetUpdateqtydata()" enctype="multipart/form-data" >
                        <div class="modal-body">
                            <span class="badge badge-success" title="Old QTY">{{QtyUpArray.quantity}}</span>
                            <input type="number" class="form-control" required name="newqty" min='0' placeholder="Update Qty" ng-model="QtyUpArray.newqty">





                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('lang_Close');?></button>
                            <button type="button" class="btn btn-primary" ng-click="GetUpdateqtydata();" ><?=lang('lang_Update');?></button>
                        </div>
                    </form>          
                </div>
            </div>
        </div>
        <!-- /page container --> 
        <script>

        </script>
    </body>
</html>
