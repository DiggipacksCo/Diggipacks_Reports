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
        <div class="page-container" ng-controller="CtrInventoryhistory" ng-init="loadMore(1, 0);"> 

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
                        if (!empty($this->session->flashdata('messarray')['expiredate'])) {
                            echo '<div class="alert alert-warning">expire date not valid not valid ' . implode(',', $this->session->flashdata('messarray')['expiredate']) . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                        if (!empty($this->session->flashdata('messarray')['alreadylocation'])) {
                            echo '<div class="alert alert-warning">Stock Location Already exists ' . implode(',', $this->session->flashdata('messarray')['alreadylocation']) . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }


                        if (!empty($this->session->flashdata('messarray')['seller_id'])) {
                            echo '<div class="alert alert-warning">seller account ids not valid ' . implode(',', $this->session->flashdata('messarray')['seller_id']) . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                        if (!empty($this->session->flashdata('messarray')['validsku'])) {
                            echo '<div class="alert alert-success">items has added ' . implode(',', $this->session->flashdata('messarray')['validsku']) . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }


                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('error'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" ng-init="filterData.sku = '<?= $item_sku; ?>';
                                            filterData.seller = '<?= $seller_id; ?>';
                                            search_seller_id = '<?= $seller_id; ?>'" > 
                                <div class="loader logloder" ng-show="loadershow" ></div>
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading" dir="ltr">
                                        <h1 ><strong ng-if="search_seller_id == ''"><?=lang('lang_Inventory_History');?></strong><strong ng-if="search_seller_id != ''"><?=lang('lang_SKU_Details');?></strong><a href="<?= base_url('Excel_export/shipments'); ?>"></a> 

                                            <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a>
                                            <!--  <a  ng-click="ExportExcelitemInventory();" >-->
                                            <a  ng-click="getExcelDetails1();" >
                                                <i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected><?=lang('lang_select_export_limit');?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.k}}" >{{exdata.j}}-{{exdata.k}}</option>  

                                            </select> 
                                        </h1>

<!-- <i class="icon-file-excel pull-right" style="font-size: 35px;"></i> --> 
                                    </div>

                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 
                                            <?php
                                            $totalqty = 100;
                                            $pices = 5;
                                            $pices2 = 0;
                                            for ($ii = 0; $ii <= 2; $ii++) {
                                                if ($ii == 0)
                                                    $pices2 = $totalqty - $pices;
                                                else
                                                    $pices2 = $pices2 - $pices;
                                                // echo $ii;
                                            }
// echo $pices2;
                                            ?>
                                            <!-- Today's revenue --> 

                                            <!-- <div class="panel-body" style="background-color: pink;"> -->

                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >
                                                    <tr style="width: 80%;" ng-if="search_seller_id == ''">
                                                        <td><div class="form-group" ><strong><?=lang('lang_SKU');?>:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                            </div></td>
                                                        <td><div class="form-group" ><strong><?=lang('lang_AWB');?>:</strong>
                                                                <input type="text" id="slip_no" name="slip_no" ng-model="filterData.slip_no"  class="form-control" placeholder="Enter AWB no.">
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
                                                        <td ng-if="search_seller_id == ''"><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong> <br>
                                                                <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                                                                    <option value=""><?=lang('lang_SelectSeller');?></option>
                                                                        <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                        <?= $seller_detail->company; ?>
                                                                        </option>
<?php endforeach; ?>
                                                                </select>
                                                            </div></td>

                                                        <td ><div class="form-group" ><strong><?=lang('lang_Status');?>:</strong> <br>
                                                                <select  id="status" name="status" ng-model="filterData.status" class="selectpicker" data-width="100%" >
                                                                    <option value=""><?=lang('lang_Select_Status');?></option>

                                                                    <option value="Update"><?=lang('lang_Updated');?></option>
                                                                    <option value="Add"><?=lang('lang_Added');?></option>
                                                                    <option value="transfer"><?=lang('lang_Transferred');?></option>
                                                                    <option value="deducted"><?=lang('lang_Deducted');?></option>
                                                                    <option value="delete"><?=lang('lang_Deleted');?></option>
                                                                    <option value="Damage"><?=lang('lang_Damage');?></option>
                                                                    <option value="Missing"><?=lang('lang_Missing');?></option>
                                                                    <option value="return"><?=lang('lang_Return');?></option>
                                                                    <option value="Removed for other Reason"><?=lang('lang_Removed_for_other_Reason');?></option>

                                                                </select>
                                                            </div></td>
                                                        <td colspan="2"><button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button></td>




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

                            <div class="panel-body" > 
                              <!-- <input type="text" value="{{data1.sku}}" id="check" style="display: none;" name="check" />
                                -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                            <th><?=lang('lang_SrNo');?>
                                                <th><?= lang('lang_Item_Image'); ?></th>

                                                <th><?=lang('lang_ItemSku');?></th>
                                                <th><?=lang('lang_Previous_Quantity');?></th>
                                                <th><?=lang('lang_New_Quantity');?></th>
                                                <th><?=lang('lang_Quantity_Used');?></th>   
                                                <th><?=lang('lang_Seller');?></th>
                                                <th><?=lang('lang_UpdatedBy');?></th>
                                                <th><?=lang('lang_Date');?></th>
                                                <th><?=lang('lang_Status');?></th>
                                                <th><?=lang('lang_Stock_Location');?></th>
                                                <th><?=lang('lang_Shelve_No');?>.</th>
                                                <th><?=lang('lang_AWB');?></th>

                                            </tr>
                                        </thead>
                                        <tbody id="">
                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}} </td>
                                                <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="100">
                                                    <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="100">
                                                </td>

                                                <td>{{data.sku}}</td>



                                                <td>
                                                    <span class="badge badge-info" >{{data.p_qty}}</span></td>
                                                <td><span class="badge badge-warning">{{data.qty}}</span></td>   
                                                <td><span class="badge badge-warning">{{data.qty_used}}</span></td>   
                                                <td>{{data.seller_name}}</td>
                                                <td > 

                                                    <span >{{data.username}}</span></td>

                                                <td>{{data.entrydate}}</td>

                                                <td ><span  ng-if="data.type == 'Update'"><?=lang('lang_Updated');?></span>  
                                                    <span  ng-if="data.type == 'Add'"><?=lang('lang_Added');?></span>
                                                    <span  ng-if="data.type == 'transfer'"><?=lang('lang_Transferred');?></span>
                                                    <span ng-if="data.type == 'deducted'"><?=lang('lang_Deducted');?></span>
                                                    <span ng-if="data.type == 'delete'"><?=lang('lang_Deleted');?></span>
                                                    <span ng-if="data.type == 'Damage'"><?=lang('lang_Damage');?></span>
                                                    <span ng-if="data.type == 'Missing'"><?=lang('lang_Missing');?></span>
                                                    <span ng-if="data.type == 'return'"><?=lang('lang_Return');?></span>
                                                    <span ng-if="data.type == 'Removed for other Reason'"><?=lang('lang_Removed_for_other_Reason');?></span>


                                                </td>
                                                <td ><span  ng-if="data.st_location !== ''">{{data.st_location}}</span>  <span  ng-if="data.st_location === ''">--</span></td>
                                                 <td ><span  ng-if="data.shelve_no !== ''">{{data.shelve_no}}</span>  <span  ng-if="data.shelve_no === ''">--</span></td>
                                                <td>{{data.awb_no}}</td>


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

            <div id="InventoryHistoryexcelcolumn" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f3f5f6;">
                            <center>   <h4 class="modal-title" style="color:#000"><?=lang('lang_Select_Column_to_download');?></h4></center>
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
                                            <input type="checkbox" name="sku" value="sku"  ng-checked="checkall" ng-model="listData2.sku"> <?=lang('lang_ItemSku');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="p_qty" value="p_qty" ng-checked="checkall" ng-model="listData2.p_qty"><?=lang('lang_Previous_Quantity');?> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="qty" value="qty" ng-checked="checkall" ng-model="listData2.qty"><?=lang('lang_New_Quantity');?> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="qty_used" value="qty_used" ng-checked="checkall" ng-model="listData2.qty_used"><?=lang('lang_Quantity_Used');?>   
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="seller_name" value="seller_name"  ng-checked="checkall" ng-model="listData2.seller_name"> <?=lang('lang_Seller');?>  
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="username" value="item_description"  ng-checked="checkall" ng-model="listData2.username"> <?=lang('lang_UpdatedBy');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="entrydate" value="entrydate"  ng-checked="checkall" ng-model="listData2.entrydate"> <?=lang('lang_Date');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="type" value="type"  ng-checked="checkall" ng-model="listData2.type"> <?=lang('lang_Status');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="st_location" value="st_location" ng-checked="checkall" ng-model="listData2.st_location"> <?=lang('lang_Stock_Location');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="awb_no" value="awb_no" ng-checked="checkall" ng-model="listData2.awb_no"> <?=lang('lang_AWB');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                <div class="row" style="padding-left: 40%;padding-top: 10px;">    


                                    <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShipInventoryHistory(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>    
                                </div>

                            </div>

                        </div>
                    </div>
                </div>  


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

        <!-- /page container --> 
        <script>
            function printPage()
            {
                var divToPrint = document.getElementById('printTable');
                var htmlToPrint = '' +
                        '<style type="text/css">' +
                        'table th, table td {' +
                        'border:1px solid #000;' +
                        'width:1200px' +
                        '}' +
                        'table th, table td {' +
                        'border:1px solid #000;' +
                        'padding:8px;' +
                        '}' +
                        'table th {' +
                        'padding-top: 12px;' +
                        'padding-bottom: 12px;' +
                        ' text-align: left;' +
                        'border:1px solid #000;' +
                        'padding:0.5em;' +
                        '}' +
                        '</style>';
                htmlToPrint += divToPrint.outerHTML;
                newWin = window.open("");
                newWin.document.write(htmlToPrint);
                newWin.print();
                newWin.close();
            }
        </script>
    </body>
</html>
