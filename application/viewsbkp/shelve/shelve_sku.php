<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
    </head>

    <body ng-app="shelve" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="shelvSku">

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>
                    <div class=""  >
                        <div class="page-header page-header-default">
                            <div class="page-header-content">
                                <div class="page-title">
                                    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?=lang('lang_Update');?> <?=lang('lang_Shelve');?></span> </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading" dir="ltr">
                                    <h5 class="panel-title"><?=lang('lang_Update_Shelve_Inventory');?></h5>
                                    <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a> 
                                    <!--<div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                    <li><a data-action="reload"></a></li>
                                                    <li><a data-action="close"></a></li>
                                                </ul>
                                            </div>--> 
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <param name="SRC" value="y" />
                                                    <div style="display:none">
                                                        <audio id="audio" controls>
                                                            <source src="<?= base_url('assets/apx_tone_alert_7.mp3'); ?>" type="audio/ogg">
                                                        </audio>
                                                        <audio id="audioSuccess" controls>
                                                            <source src="<?= base_url('assets/filling-your-inbox.mp3'); ?>" type="audio/ogg">
                                                        </audio>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" id="scan_shelve" my-enter="scan_shelve();" ng-model="scan.shelve_no"class="form-control" placeHolder='Shelve No.' />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" id="StockLocation" my-enter="StockLocation()" ng-model="scan.StockLocation"class="form-control" placeHolder='Stock Location' />
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-4">
                                                       <div class="form-group">
                                                         <input type="text" id="scan_awb" my-enter="shelveSelected()" ng-model="scan.sku"class="form-control" placeHolder='SKU' />
                                                       </div>
                                                     </div>-->


                                                    <!--
                                                    <div class="col-md-3" >  <div class="form-group"> <input class="btn btn-info" type="button" onclick="create_zip();" value="Export Completed" ></div></div>
                                                     <div class="col-md-3" >  <div class="form-group"><input class="btn btn-info" type="button" onclick="create_zip1();" value="Export ALL" ></div></div>
                                                    -->

                                                    <div class="container" ng-if="selectedStockLocationData">
                                                        <ul class="list-unstyled row">
                                                            <li class="list-item col-sm-2 list-group-item-success "><strong><?=lang('lang_Stock_Location');?></strong></li>
                                                            <li class="list-item col-sm-2 list-group-item-warning"><strong><?=lang('lang_SKU');?></strong></li>
                                                            <li class="list-item col-sm-2 list-group-item-danger"><strong><?=lang('lang_Item');?></strong></li>
                                                            <li class="list-item col-sm-2 list-group-item-info"><strong><?=lang('lang_Quantity');?></strong></li>
                                                        </ul>
                                                        <ul class="list-unstyled row"ng-repeat=" data in selectedStockLocationData" >
                                                            <li class="list-item col-sm-2 list-group-item-success ">{{data.stock_location}}</li>
                                                            <li class="list-item col-sm-2 list-group-item-warning">{{data.sku}}</li>
                                                            <li class="list-item col-sm-2 list-group-item-danger">{{data.name}}</li>
                                                            <li class="list-item col-sm-2 list-group-item-info">{{data.quantity}}</li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div ng-if="completeArray.length > 2" class="alert alert-danger"><?=lang('lang_Please_Verify_the_Packing_Limit_Exceed');?>! </div>
                                                        <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                                                        <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                            </div>
                                            <!--contenttitle--> 
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body"><?=lang('lang_List');?></div>
                                            </div>
                                            <table class="table table-striped table-bordered table-hover" id="downloadtable">
                                                <thead>
                                                    <tr>
                                                    <th class="head1"><?=lang('lang_SrNo');?>.</th>
                                                        <th class="head0"><?=lang('lang_Stock_Location');?>.</th>
                                                        <th class="head1"><?=lang('lang_Item');?></th>
                                                        <th class="head0"><?=lang('lang_Shelve');?></th>
                                                        <th class="head1"><?=lang('lang_SKU');?></th>  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr   ng-repeat="data in scanedArray|reverse track by $index ">
                                                        <td>{{$index + 1}}</td>
                                                        <td><span class="label label-primary">{{data.stock_location}}</span></td>
                                                        <td><span class="label label-success">{{data.name}}</span></td>
                                                        <td><span class="label label-warning">{{data.shelve_no}}</span></td>
                                                        <td  ><span class="label label-info" >{{data.sku}}</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                                                           // alert("sssssssss");
                                                                var todaysDate = 'shelve Details ' + new Date();
                                                                        var blobURL = tableToExcel('downloadtable', 'test_table');
                                                                        $(this).attr('download', todaysDate + '.xls')
                                                                        $(this).attr('href', blobURL);
                                                                        });
// "order": [[0, "asc" ]]
                                                                       

     
        </script>

