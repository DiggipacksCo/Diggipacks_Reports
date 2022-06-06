<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>

    <body ng-app="Appiteminventory">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CtrShelveNoReport" ng-init="loadMore(1, 0);">  

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

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading" dir="ltr">
                                        <h1><strong><?= lang('lang_Shelve_Report'); ?></strong><a href="<?= base_url('Excel_export/shipments'); ?>"></a>

                                            <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;
                                            <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 


                                        </h1>

<!-- <i class="icon-file-excel pull-right" style="font-size: 35px;"></i> --> 
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
                                                        <td><div class="form-group" ><strong><?= lang('lang_Shelve_No'); ?>:</strong>
                                                                <input type="shelve_no" id="sku"name="shelve_no" ng-model="filterData.shelve_no"  class="form-control" placeholder="Enter Shelve No.">
                                                            </div></td>





                                                        <td ><div class="form-group" ><strong><?= lang('lang_Seller'); ?>:</strong> <br>
                                                                <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                                                                    <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                                                    <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                            <?= $seller_detail->company; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>










                                                        <td colspan="1"><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>


                                                        <td colspan="1"> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" style="margin-left: 7%" ><?= lang('lang_Search'); ?></button></td>
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

                            <!-- </div> -->

                            <div class="panel-body" > 
                              <!-- <input type="text" value="{{data1.sku}}" id="check" style="display: none;" name="check" />
                                -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>

                                                <th><?= lang('lang_Pallet_No'); ?>.</th>
                                                <th><?= lang('lang_Quantity'); ?></th>

                                                <th><?= lang('lang_Seller'); ?></th>

                                                <th  ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody id="">


                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}}  </td>

                                                <td >{{data.shelve_no}}</td>


                                                <td>{{data.quantity}}</td><td>{{data.seller_name}}</td>
                                                <td><a class="btn btn-info" ng-click="GetShowShelveDetails(data.seller_id, data.shelve_no);"><?= lang('lang_Details'); ?></a></td>



                                            </tr>
                                        </tbody>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table -->
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area --> 


                    <div style="display: none;" > 
                        <!--style="background-color: green;"-->
                         <table class="table table-striped table-hover table-bordered" id="printTable"  style="width:100%;">

                            <thead>
                                <tr>
                                    <th><?= lang('lang_SrNo'); ?>.</th>

                                    <th><?= lang('lang_Pallet_No'); ?>.</th>
                                    <th><?= lang('lang_Quantity'); ?></th>

                                    <th><?= lang('lang_Seller'); ?></th>
                                    
                                    <th> Details <table class="table table-striped table-hover table-bordered"><tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>

                                                <th><?= lang('lang_Sku'); ?></th>
                                                <th><?= lang('lang_Quantity'); ?></th>

                                                <th><?= lang('lang_Stock_Location'); ?></th>
                                                <th><?= lang('lang_warehouse'); ?></th>


                                            </tr></table></th>


                                </tr>
                            </thead>
                            <tbody >


                                <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                    <td>{{$index + 1}}  </td>

                                    <td >{{data.shelve_no}}</td>


                                    <td>{{data.quantity}}</td><td>{{data.seller_name}}</td>
                                    <td><table class="table table-striped table-hover table-bordered"> <tr  ng-repeat="data_s in data.detailsArr">
                                                <td>{{$index + 1}}  </td>
                                                <td>{{data_s.sku}}  </td>
                                                <td>{{data_s.quantity}}  </td>
                                                <td>{{data_s.stock_location}}  </td>
                                                <td>{{data_s.w_name}}  </td>
                                            </tr></table></td>



                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <!-- /main content --> 

            </div>
            <!-- /page content --> 


            <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">



                            <h5 class="modal-title" id="exampleModalLabel" dir="ltr"><?= lang('lang_Details'); ?> ({{showPopData.shelve_no}})</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                <thead>
                                    <tr>
                                        <th><?= lang('lang_SrNo'); ?>.</th>

                                        <th><?= lang('lang_Sku'); ?></th>
                                        <th><?= lang('lang_Quantity'); ?></th>

                                        <th><?= lang('lang_Stock_Location'); ?></th>
                                        <th><?= lang('lang_warehouse'); ?></th>


                                    </tr>
                                </thead>
                                <tbody id="">


                                    <tr ng-if='detailsListArr != 0' ng-repeat="data in detailsListArr">
                                        <td>{{$index + 1}}  </td>
                                        <td>{{data.sku}}  </td>
                                        <td>{{data.quantity}}  </td>
                                        <td>{{data.stock_location}}  </td>
                                        <td>{{data.w_name}}  </td>
                                    </tr>
                                </tbody>
                            </table>






                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('lang_Close'); ?></button>

                        </div>

                    </div>
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
           var todaysDate = 'shelve Report '+ new Date();
           var blobURL = tableToExcel('printTable', 'Reports');
           $(this).attr('download',todaysDate+'.xls')
           $(this).attr('href',blobURL);
       });




        </script>
        <script type="text/javascript">

                                        $('.date').datepicker({

                                format: 'yyyy-mm-dd'

                                });

        </script>

    </body>
</html>
