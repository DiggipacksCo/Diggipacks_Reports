<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js"></script>
    </head>

    <body ng-app="Appiteminventory">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CtritemInvontaryview_total" ng-init="loadMore(1, 0);">  

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

                        if (!empty($this->session->flashdata('messarray')['warehouse'])) {
                            echo '<div class="alert alert-warning">this warehouse not valid &quot;' . implode(',', $this->session->flashdata('messarray')['warehouse']) . '&quot; <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
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
                        if (!empty($this->session->flashdata('messarray')['pallets'])) {
                            echo '<div class="alert alert-warning">invalid pallets ' . implode(',', $this->session->flashdata('messarray')['pallets']) . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                        if (!empty($this->session->flashdata('messarray')['palletused'])) {
                            echo '<div class="alert alert-warning">this pallets used other seller ' . implode(',', $this->session->flashdata('messarray')['palletused']) . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        }
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            ?>
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading">
                                        <h1><strong>Items Inventory Total</strong><a href="<?= base_url('Excel_export/shipments'); ?>"></a>

                                            <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 
                                            <!--<a  ng-click="ExportExcelitemInventory();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>-->
                                            <a  ng-click="getExcelDetails();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected>Select Export Limit</option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.k}}" >{{exdata.j}}-{{exdata.k}}</option>  

                                            </select> 
                                        </h1>
                                    </div>


                                    <div id="excelcolumn" class="modal fade">
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

                                                                <input type="checkbox" id='but_checkall' value='checkall' ng-model="checkall" ng-click='toggleAll()'/>    SelectAll
                                                                <span class="checkmark"></span>


                                                            </label>
                                                        </div>

                                                        <div class="col-md-12 row">
                                                            <div class="col-sm-4">          
                                                                <label class="container">  
                                                                    <input type="checkbox" name="name" value="name"   ng-model="listData2.name"> Name
                                                                    <span class="checkmark"></span>
                                                                </label>   
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="item_type" value="item_type"  ng-model="listData2.item_type"> Item Type
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="storage_id" value="storage_id"  ng-model="listData2.storage_id"> Storage Type
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="sku" value="sku"  ng-model="listData2.sku"> Item Sku
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="stock_location" value="stock_location" ng-model="listData2.stock_location"> Stock Location
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="shelve_no" value="shelve_no" ng-model="listData2.shelve_no"> Shelve No
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4"> 
                                                                <label class="container">
                                                                    <input type="checkbox" name="wh_name" value="wh_name" ng-model="listData2.wh_name">Warehouse
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="quantity" value="quantity"  ng-model="listData2.quantity"> Quantity
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="seller_name" value="seller_name"  ng-model="listData2.seller_name"> Seller  
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="item_description" value="item_description"  ng-model="listData2.item_description"> Description
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="update_date" value="update_date"  ng-model="listData2.update_date"> Update date
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="expiry" value="expiry"  ng-model="listData2.expiry"> Expire Status
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <label class="container">
                                                                    <input type="checkbox" name="expity_date" value="expity_date" ng-model="listData2.expity_date"> Expire Date
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                        <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                                        <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                                            <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="ViewTotalInventoryExport(listData2, listData1.exportlimit);">Download Excel Report</button>  
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>  

                                    </div> 
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 

                                            <!-- Today's revenue --> 

                                            <!-- <div class="panel-body" style="background-color: pink;"> -->

                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >

                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong>SKU:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                            </div></td>

                                                        <td ><div class="form-group" ><strong>Quantity:</strong>
                                                                <input type="number" min='0'  id="quantity"name="quantity"  ng-model="filterData.quantity" class="form-control" placeholder="Enter Quantity">
                                                            </div></td>
                                                        <td ><div class="form-group"><strong>From:</strong>
                                                                <input type="date" id="from"name="from" ng-model="filterData.from" class="form-control">
                                                            </div></td>
                                                        <td><div class="form-group" ><strong>To:</strong>
                                                                <input type="date" id="to"name="to" ng-model="filterData.to" class="form-control">
                                                            </div></td>
                                                        <td><div class="form-group" ><strong>Exact date:</strong>
                                                                <input type="date" id="exact"name="exact"  ng-model="filterData.exact" class="form-control">
                                                            </div></td>
                                                    </tr>
                                                    <tr style="width: 80%;">


                                                        <td ><div class="form-group" ><strong>Seller:</strong> <br>
                                                                <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                                                                    <option value="">Select Seller</option>
                                                                    <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                            <?= $seller_detail->name; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>
                                                        <td ><div class="form-group" ><strong>Storage Type:</strong> <br>
                                                                <select  id="storage_id" name="storage_id" ng-model="filterData.storage_id" class="selectpicker" data-width="100%" >
                                                                    <option value="">Select Storage Type</option>
                                                                    <?php foreach ($StorageType as $storage_detail): ?>
                                                                        <option value="<?= $storage_detail['id']; ?>">
                                                                            <?= $storage_detail['storage_type']; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>



                                                        <td colspan="2"><button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" >Search</button></td>


<!--<td colspan="2">
  <div class="form-group" style="background-color: pink;"><strong><p style="text-align: center;" id="result"><?php //echo "Total ".count($items)." entries";    ?></p></strong>
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
                            <!-- <h1><strong>Shipments Table</strong> --><!-- <a href="<? //base_url('Excel_export/shipments'); ?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></hr> --> 

                            <!-- <div class="heading-elements">
                  <ul class="icons-list">
                    <li><a href="<? // base_url('Excel_export/shipments'); ?>"><i class="icon-file-excel"></i></a></li>
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
                                                <th>Sr.No.</th>
                                                <th>Seller</th>
                                                <th>Item Type</th>
                                                <th>Item Image</th>
                                                <th>Storage Type</th>
                                                <th>Item Sku</th>


                                                <th>Warehouse</th>

                                                <th>Quantity</th>






                                            </tr>
                                        </thead>
                                        <tbody id="">


                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}}  </td>
                                                <td>{{data.seller_name}}</td> 
                                                <td><span class="badge badge-success" ng-if="data.item_type == 'B2B'">{{data.item_type}}</span><span class="badge badge-warning" ng-if="data.item_type == 'B2C'">{{data.item_type}}</span></td>
                                                <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="80">
                                                    <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="80">
                                                </td>


                                                <td>{{data.storage_id}}</td>
                                                <td>{{data.sku}}</td>



                                                <td ng-if="data.wh_id > 0">{{data.wh_name}}</td>
                                                <td ng-if="data.wh_id == '0'">--</td>

                                                <td ng-if="data.checkreQty == 'Y'"><span class="badge badge-success">{{data.quantity}}</span></td>
                                                <td ng-if="data.checkreQty == 'N'"><span class="badge badge-success">{{data.quantity}}</span></td>








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



                        <h5 class="modal-title" id="exampleModalLabel">Update Location</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form name="myform" novalidate ng-submit="myForm.$valid && GetupdatelocationData()" enctype="multipart/form-data" >
                        <div class="modal-body">
                            <select type="text" name="locationUp" id="locationUp" ng-model="UpdateData.locationUp" class="form-control" required>
                                <option value="error">Select Location</option>
                                <option ng-repeat="data2 in locationData" value="{{data2.stock_location}}">{{data2.stock_location}}</option>
                            </select>





                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" ng-click="GetupdatelocationData();" >Update</button>
                        </div>
                    </form>          
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">



                        <h5 class="modal-title" id="exampleModalLabel">Update QTY</h5>
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" ng-click="GetUpdateqtydata();" >Update</button>
                        </div>
                    </form>          
                </div>
            </div>
        </div>

        <!---------------Qty Deduct Popup---------->
        <div class="modal fade" id="UpdateInventory" tabindex="-1" role="dialog" aria-labelledby="UpdateInventory" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">



                        <h5 class="modal-title" id="UpdateInventory">Update Inventory</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form name="myform" novalidate ng-submit="myForm.$valid && GetupdateMissingInventory()" enctype="multipart/form-data" >
                        <div class="modal-body">
                            <div class="form-group" ><strong>Current Quantity:</strong>

                                <span class="badge badge-success">{{updateArray.quantity}}</span>
                            </div>

                            <div class="form-group" ><strong>Deduct Quantity:</strong>
                                <input type="number" class="form-control" name="upquantity" ng-model="updateArray.upquantity" value="1" min="1" id="upquantity" required>
                                <br>
                                <span class="alert alert-warning mt-10" ng-if="updateArray.upquantity > updateArray.quantity">Please Enter Valid Deduct Quantity</span>
                            </div>
                            <div class="form-group" ><strong>Reason:</strong> 
                                <select type="text" name="updateType" id="updateType" ng-model="updateArray.updateType" class="form-control" required>
                                    <option value="">Select Reason</option>
                                    <option  value="Damage">Damage</option>
                                    <option  value="Missing">Missing</option>
                                    <option  value="Removed for other Reason">Removed for other Reason</option>
                                </select>
                            </div>





                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" ng-if="updateArray.updateType && updateArray.upquantity <= updateArray.quantity" ng-click="GetupdateMissingInventory();" >Update</button>
                        </div>
                    </form>          
                </div>
            </div>
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
