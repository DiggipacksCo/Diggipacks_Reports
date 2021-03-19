<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
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
                                    <div class="panel-heading">
                                        <h1 ><strong ng-if="search_seller_id == ''">Inventory History</strong><strong ng-if="search_seller_id != ''">Sku Details</strong><a href="<?= base_url('Excel_export/shipments'); ?>"></a> 

                                            <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a>
                                            <!--  <a  ng-click="ExportExcelitemInventory();" >-->
                                            <a  ng-click="getExcelDetails1();" >
                                                <i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected>Select Export Limit</option>
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
                                                        <td><div class="form-group" ><strong>SKU:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                            </div></td>
                                                        <td><div class="form-group" ><strong>AWB:</strong>
                                                                <input type="text" id="slip_no" name="slip_no" ng-model="filterData.slip_no"  class="form-control" placeholder="Enter AWB no.">
                                                            </div></td>
                                                        <td ><div class="form-group" ><strong>Quantity:</strong>
                                                                <input type="number" min="1" id="quantity"name="quantity"  ng-model="filterData.quantity" class="form-control" placeholder="Enter Quantity">
                                                            </div></td>
                                                        <td ><div class="form-group"><strong>From:</strong>
                                                                <input type="date" id="from"name="from" ng-model="filterData.from" class="form-control">
                                                            </div></td>
                                                        <td><div class="form-group" ><strong>To:</strong>
                                                                <input type="date" id="to"name="to" ng-model="filterData.to" class="form-control">
                                                            </div></td>
                                                    </tr>
                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong>Exact date:</strong>
                                                                <input type="date" id="exact"name="exact"  ng-model="filterData.exact" class="form-control">
                                                            </div></td>
                                                        <td ng-if="search_seller_id == ''"><div class="form-group" ><strong>Seller:</strong> <br>
                                                                <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                                                                    <option value="">Select Seller</option>
                                                                        <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                        <?= $seller_detail->name; ?>
                                                                        </option>
<?php endforeach; ?>
                                                                </select>
                                                            </div></td>

                                                        <td ><div class="form-group" ><strong>Status:</strong> <br>
                                                                <select  id="status" name="status" ng-model="filterData.status" class="selectpicker" data-width="100%" >
                                                                    <option value="">Select Status</option>

                                                                    <option value="Update">Updated</option>
                                                                    <option value="Add">Added</option>
                                                                    <option value="transfer">Transferred</option>
                                                                    <option value="deducted">Deducted</option>
                                                                    <option value="delete">Deleted</option>
                                                                    <option value="Damage">Damage</option>
                                                                    <option value="Missing">Missing</option>
                                                                    <option value="return">Return</option>
                                                                    <option value="Removed for other Reason">Removed for other Reason</option>

                                                                </select>
                                                            </div></td>
                                                        <td colspan="2"><button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" >Search</button></td>




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
                                                <th>Sr.No.</th>
                                                <th>Item Image</th>

                                                <th>Item Sku</th>
                                                <th>Previous Quantity</th>
                                                <th>New Quantity</th>
                                                <th>Quantity Used</th>   
                                                <th>Seller</th>
                                                <th>Updated By</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Stock Location</th>
                                                <th>Shelve No.</th>
                                                <th>AWB</th>

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

                                                <td ><span  ng-if="data.type == 'Update'">Updated</span>  
                                                    <span  ng-if="data.type == 'Add'">Added</span>
                                                    <span  ng-if="data.type == 'transfer'">Transferred</span>
                                                    <span ng-if="data.type == 'deducted'">Deducted</span>
                                                    <span ng-if="data.type == 'delete'">deleted</span>
                                                    <span ng-if="data.type == 'Damage'">Damage</span>
                                                    <span ng-if="data.type == 'Missing'">Missing</span>
                                                    <span ng-if="data.type == 'return'">Return</span>
                                                    <span ng-if="data.type == 'Removed for other Reason'">Removed for other Reason</span>


                                                </td>
                                                <td ><span  ng-if="data.st_location !== ''">{{data.st_location}}</span>  <span  ng-if="data.st_location === ''">--</span></td>
                                                 <td ><span  ng-if="data.shelve_no !== ''">{{data.shelve_no}}</span>  <span  ng-if="data.shelve_no === ''">--</span></td>
                                                <td>{{data.awb_no}}</td>


                                            </tr>
                                        </tbody>
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

            <div id="InventoryHistoryexcelcolumn" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f3f5f6;">
                            <center>   <h4 class="modal-title" style="color:#000">Select Column to download</h4></center>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">             
                                    <label class="container">

                                        <input type="checkbox" id='but_checkall' value='Check all' ng-model="checkall" ng-click='toggleAll()'/>    SelectAll
                                        <span class="checkmark"></span>


                                    </label>
                                </div>

                                <div class="col-md-12 row">

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="sku" value="sku"  ng-checked="checkall" ng-model="listData2.sku"> Item Sku
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="p_qty" value="p_qty" ng-checked="checkall" ng-model="listData2.p_qty"> Previous Quantity
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="qty" value="qty" ng-checked="checkall" ng-model="listData2.qty"> New Quantity
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="qty_used" value="qty_used" ng-checked="checkall" ng-model="listData2.qty_used"> Quantity Used  
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="seller_name" value="seller_name"  ng-checked="checkall" ng-model="listData2.seller_name"> Seller  
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="username" value="item_description"  ng-checked="checkall" ng-model="listData2.username"> Updated By
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="entrydate" value="entrydate"  ng-checked="checkall" ng-model="listData2.entrydate"> Date
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="type" value="type"  ng-checked="checkall" ng-model="listData2.type"> Status
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="st_location" value="st_location" ng-checked="checkall" ng-model="listData2.st_location"> Stock Location
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="awb_no" value="awb_no" ng-checked="checkall" ng-model="listData2.awb_no"> AWB
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                <div class="row" style="padding-left: 40%;padding-top: 10px;">    


                                    <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShipInventoryHistory(listData2, listData1.exportlimit);">Download Excel Report</button>    
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
