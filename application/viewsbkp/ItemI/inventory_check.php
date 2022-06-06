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
        <script src='<?= base_url(); ?>assets/js/angular/inventory_check.js'></script>


    </head>

    <body ng-app="InventoryCheckApp" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="scanInventory" ng-init="GetCustomerNamesData();">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?> 





                    <div class=""  >
                        <input type="text" name="destination[]" ng-model="inputValue" style="display:none"/>
                      

                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h5 class="panel-title"> <?=lang('lang_Inventory_Check');?></h5>

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



                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            
                                                            <select ng-disabled="cust_nameBtn"  class="form-control" ng-model="scan.cust_name" ng-change="scan_customer();">
                                                                
                                                                <option value=""><?=lang('lang_Select_Customer');?></option>
                                                                <option ng-repeat="cust_data in CustDropArr" value="{{cust_data.id}}">{{cust_data.company}}</option>
                                                                
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

                                                            <input type="text" id="scan_sku_id" my-enter="CheckCustomerInventory_sku()" ng-model="scan.sku" class="form-control" placeHolder='SKU'/>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">





                                                        <div class="form-group">
 <a class="btn btn-info"  type="button" id="btnExport" ><?=lang('lang_export_Report');?></a>
                                                            <a class="btn btn-success" ng-show="ExportBtnShow" ng-confirm-click="Are you sure want save report?" confirmed-click="GetSaveReportInventpry();" >t<?=lang('Lang_Save_Report');?> </a>

                                                           
                                                         
                                                            <input type="button" ng-click="EnableLocaion();" ng-show="nextBtnShow" value='Enable Location'class="btn btn-danger" />
                                                        </div>


                                                    </div> 







                                                   



                                                    <div class="col-lg-12">


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
                                                <div class="panel-body"><?=lang('lang_Sort');?></div>
                                            </div>
                                            <div class="table-responsive" style="padding-bottom:20px;" >
                                                <table class="table table-striped table-bordered table-hover" id="downloadtable" ng-init="shipData.total = {}">
                                                    <thead>
                                                        <tr>
                                                            <th class="head1"><?=lang('lang_Sr_No');?>.</th>
                                                            <th class="head0"><?=lang('lang_Customer');?></th>
                                                            <th class="head1"><?=lang('lang_Stock_Location');?></th>
                                                            <th class="head1"><?=lang('lang_SKU');?></th>
                                                            <th class="head1"><?=lang('lang_StorageType');?></th>
                                                            <th class="head1"><?=lang('lang_Capacity');?></th>
                                                            <th class="head0"><?=lang('lang_Total');?></th> 
                                                            <th class="head1"><?=lang('lang_Scaned');?></th>
                                                            <th class="head0"><?=lang('lang_Extra');?></th>

<!--                   	  <th class="head1">Remove</th>-->
                                                        </tr>
                                                    </thead>
                                                    <tbody ng-init = "piece=0">
                                                        
                                                        
                                                        


                                                        <tr ng-repeat="data in shipData|reverse "  >
                                                            <td ng-init = "piece = piece + parseInt(data.piece)">{{$index + 1}}</td>


                                                            <td><span class="label label-primary">{{data.cust_name}}</span></td>
                                                            <td><span class="label label-primary">{{data.stock_location}}</span></td>

                                                            <td><span class="label label-warning">{{data.sku}}</span></td>
                                                            <td><span class="label label-info">{{data.storage_type}}</span></td>
                                                            <td><span class="label label-warning">{{data.sku_size}}</span></td>
                                                            <td ng-if="data.piece > data.scaned"  >  <span class="badge badge badge-pill badge-info" >{{data.piece}}</span></td>
                                                            <td ng-if="data.piece == data.scaned"  >  <span class="badge badge badge-pill badge-success" >{{data.piece}}</span></td>


                                                            <td ng-if="data.piece > data.scaned"  ><span class="badge badge badge-pill badge-danger" >{{data.scaned}}</span></td>


                                                            <td ng-if="data.piece == data.scaned" ><span class="badge badge badge-pill badge-success" >{{data.scaned}}</span></td>
                                                            <td ng-if="data.extra > 0" ><span class="badge badge badge-pill badge-danger" >{{data.extra}}</span></td>
                                                            <td ng-if="data.extra == 0" ><span class="badge badge badge-pill badge-warning" >{{data.extra}}</span></td>

                                                            <!--
                                                                <td><a ng-click="removeData(data.slip_no)">
                                                                      <span class="glyphicon glyphicon-remove"></span>
                                                                    </a></td>
                                                            -->
                                                        </tr>
                                                        <tr>
                                                            <th colspan="4"><?=lang('lang_Total');?> </th>
                                                            <td >{{totalpiece}} </td>
                                                            <td> {{total.scaned}}</td>
                                                            <td> {{total.extra}}</td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="panel panel-default">
                                                <div class="panel-body"><?=lang('lang_Completed');?></div>
                                            </div><div class="table-responsive" style="padding-bottom:20px;" >
                                                <table class="table table-striped table-bordered table-hover" >
                                                    <thead>
                                                        <tr>
                                                            <th class="head1"><?=lang('lang_Sr_No');?>.</th>

                                                            <th class="head0"><?=lang('lang_Stock_Location');?></th>




                                                        </tr>
                                                    </thead>
                                                    <tbody>


                                                        <tr   ng-repeat="data in completeArray">
                                                            <td>{{$index + 1}}</td>


                                                            <td>{{data.stock_location}}</td>

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
                                                            ,   base64   =   function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                                                    ,   format   =   function(s,   c) { return s.replace(/{(\w+)}/g,   function(m,   p) { return c[p];   }) }
                                                    return function(table,   name) {
                                                    if   (!table.nodeType) table   =   document.getElementById(table)
                                                            var   ctx   =   {worksheet: name ||   'Worksheet',   table: table.innerHTML}
                                                    var   blob   =   new   Blob([format(template,   ctx)]);
                                                    var   blobURL   =   window.URL.createObjectURL(blob);
                                                    return blobURL;
                                                    }
                                                    })()

                                                    $("#btnExport").click(function () {
                                                       // alert("sssss");
                                            var     todaysDate     =     'Inventory Report ' + new Date();
                                            var blobURL = tableToExcel('downloadtable', 'test_table');
                                            $(this).attr('download', todaysDate + '.xls')
                                                    $(this).attr('href', blobURL);
                                            });

                    </script>
                    
                     </body>
</html>



