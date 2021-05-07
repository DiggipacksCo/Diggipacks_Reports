<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>

        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
        <script src='<?= base_url(); ?>assets/js/angular/updateManifest.js'></script>

    </head>

    <body ng-app="updateManifest" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
     
        <div class="page-container" ng-controller="scanInventory"  <?php if(!empty($result)) { ?> ng-init="GetUrlData('<?= $result[0]['uniqueid']; ?>','<?= $result[0]['seller_id']; ?>');" <?php } ?> >

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?> 

                    <div class=""  >
                        <input type="text"  name="destination[]" ng-model="inputValue" style="display:none"/>


                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        Inventory Update For Manifest #<?= $result[0]['uniqueid']; ?></h5>

                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">

                                                <?php if(!empty($result)) {?>
        <div  >
<?php }  else { ?>
    <div  class="alert alert-warning">Update Sku details First Storage type and capacity may be not set.  <a href="<?=base_url('Item'); ?>"> Click Here </a>   </div>
<?php exit; } ?>
                                                    <param name="SRC" value="y" />
                                                    <div style="display:none">
                                                        <audio id="audio" controls>
                                                            <source src="<?= base_url('assets/apx_tone_alert_7.mp3'); ?>" type="audio/ogg">
                                                        </audio>
                                                        <audio id="audioSuccess" controls>
                                                            <source src="<?= base_url('assets/filling-your-inbox.mp3'); ?>" type="audio/ogg">
                                                        </audio>      
                                                    </div>



                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <select ng-disabled="cust_nameBtn"  class="form-control" ng-model="scan.sku" ng-change="scan_sku();">

                                                                <option value="">Select Sku</option>

                                                                <option ng-repeat="s_data in skuLoopArr" value="{{s_data.sku}}">{{s_data.sku}}</option>

                                                            </select>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">

                                                            <input type="text" id="scan_stocklocation_id" my-enter="GetcheckStockLocation();"  ng-model="scan.stock_location" class="form-control" ng-disabled="location_nameBtn"  placeHolder='Stock Location' />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">

                                                            <input type="text" id="scan_shelve_id" my-enter="GetCheckShelveNoScan()" ng-model="scan.shelve" class="form-control" ng-disabled="shelve_nameBtn" placeHolder='Shelve'/>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">



                                                   

                                                        <div class="form-group">
                                                            <a class="btn btn-info" ng-show="ExportBtnShow" type="button" id="btnExport" >Export Report</a>
                                                            <a class="btn btn-success" ng-show="AddInventoryBtn" ng-confirm-click="Are you sure want Add Inventory?"  confirmed-click="GetSaveReportInventpry();" >Add Inventory </a>




                                                        </div>


                                                    </div> 

                                                    <div class="col-lg-12">

                                                    
                                                    <div ng-if='warning1' class="alert alert-warning"> Stock location Not Added for this seller/sku, Please Add Stock Location. <a href="<?=base_url('generateStockLocation')?>">click here </a> to add stock location. </div>


                                                    <div ng-if='warning2' class="alert alert-warning"> Only {{countarray}} Stock location is Available for seller/sku, Required {{countbox}}, Please Add {{countbox-countarray}} more Stock Location. <a href="<?=base_url('generateStockLocation')?>">click here </a> to add stock location. </div>
                                                   
                                                        <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                                                        <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group"> &nbsp; </div>
                                                    </div></div>

                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>

                                            </div> 
                                            <!--contenttitle--> 
                                        </div>

                                        <div class="col-md-8">
                                            <div class="panel panel-default">
                                                <div class="panel-body">Sort</div>
                                            </div>
                                            <div class="table-responsive" style="padding-bottom:20px;" >
                                                <table class="table table-striped table-bordered table-hover" id="downloadtable">
                                                    <thead>
                                                        <tr>
                                                            <th class="head1">Sr.No.</th>
                                                            <th class="head0">SKU</th>
                                                            <th class="head1">Capacity</th>
                                                            <th class="head1">Storage</th>
                                                            <th class="head1">Stock Location</th>
                                                            <th class="head1">Shelve No.</th>
                                        <!--                                                            <th class="head0">Qty</th> -->




                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr   ng-repeat="data in Menidata|reverse ">
                                                            <td>{{$index + 1}}</td>
                                                            <td><span class="label label-primary">{{data.sku}}</span></td>

                                                            <td>  <span class="badge badge badge-pill badge-info" >{{data.capacity}}</span></td>
                                                            <td>  <span class="badge badge badge-pill badge-warning" >{{data.storage_type}}</span></td>
                                                            <td><span ng-if="data.l_status == 'pending'"  class="label label-danger">{{data.stockLocation}}</span>
                                                                <span ng-if="data.l_status == 'completed'"  class="label label-success">{{data.stockLocation}}</span>
                                                            </td>

                                                            <td><span ng-if="data.s_status == 'pending'"  class="label label-danger">{{data.shelveNo}}</span>
                                                                <span ng-if="data.s_status == 'completed'"  class="label label-success">{{data.shelveNo}}</span>
                                                            </td>


        <!--                                                              <td>  <span class="badge badge badge-pill badge-info" >{{data.totalqty}}</span></td>-->



                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                                <div class="panel-body">Completed</div>
                                            </div><div class="table-responsive" style="padding-bottom:20px;" >
                                                <table class="table table-striped table-bordered table-hover" >
                                                    <thead>
                                                        <tr>
                                                            <th class="head1">Sr.No.</th>

                                                            <th class="head0">SKU</th>
                                                            <th>QTY</th>




                                                        </tr>
                                                    </thead>
                                                    <tbody>


                                                        <tr   ng-repeat="data2 in newCompeleteArr">
                                                            <td>{{$index + 1}}</td>


                                                            <td>{{data2.sku}}</td>
                                                            <td>{{data2.totalqty}}</td>

                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

                                                                        <script>
        var tableToExcel = (function() {
var uri = 'data:application/vnd.ms-excel;base64,', template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                    , base64 = function (s) {
                    return window.btoa(unescape(encodeURIComponent(s))) }
            , format = function (s, c) {
            return s.replace(/{(\w+)}/g, function(m, p) { return c[p];
            }
            ) }
            return function (table, name) {
            if (!table.nodeType)
                    table = document.getElementById(table)
                    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            var blob = new Blob([format(template, ctx)]);
            var blobURL = window.URL.createObjectURL(blob);
            return blobURL;
            }
            }
            )()

            $("#btnExport").click(function () {

var todaysDate='Inventory Report ' + new Date();
var blobURL = tableToExcel('downloadtable', 'stock_details');
$(this).attr('download', todaysDate + '.xls')
$(this).attr('href', blobURL);
});

        </script>

    </body>
</html>



