<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/dispatch3pl.js"></script>

    </head>

    <body ng-app="3PLdispatchApp" ng-controller="CTR_scan3PLDispacth">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" >

            <!-- Page content -->
            <div class="page-content" ng-init="loadMore(1, 0)">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                        <!--style="background-color: red;"-->





                        <!-- Basic responsive table -->
                        <div class="panel panel-flat"  >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?= lang('lang_Dispatch_To_tpl'); ?></strong>

                                    <a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>



                                </h1>

                                <hr>

                            </div>

                            <div class="panel-body" >

                                <param name="SRC" value="y" />
                                <div style="display:none">
                                    <audio id="audio" controls>
                                        <source src="<?= base_url('assets/apx_tone_alert_7.mp3'); ?>" type="audio/ogg">
                                    </audio>
                                    <audio id="audioSuccess" controls>
                                        <source src="<?= base_url('assets/filling-your-inbox.mp3'); ?>" type="audio/ogg">
                                    </audio>      
                                </div>

                                <div class="col-lg-12">

                                    <div ng-if='Message_new' class="alert alert-success"><?= lang('lang_Order_Dispatched_to'); ?> <span style="font-size: 20px;">{{Message_new}} </span></div>
                                    <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                                    <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                                </div>

                                <table class="table table-bordered table-hover" style="width: 100%;">
                                    <!-- width="170px;" height="200px;" -->
                                    <tbody >

                                        <tr style="width: 80%;">
                                            <td><div class="form-group" >
                                                    <input type="text" id="slip_no" name="slip_no" my-enter="scan_awb();" ng-model="scan.slip_no"  class="form-control" placeholder="Scan 3PL AWB or Reference Number">
                                                </div></td>


                                            <td><button type="button" class="btn btn-success" style="margin-left: 7%"> <?= lang('lang_Total'); ?><span class="badge">{{awbArray.length}}</span></td>

                                        </tr>

                                    </tbody>
                                </table>

                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_AWB'); ?></th>
                                                <th><?= lang('lang_TPL_AWB'); ?></th>
                                                <th><?= lang('lang_Ref_No'); ?>#</th>
                                                <th><?= lang('lang_TPL_company'); ?></th>
                                                <th><?= lang('lang_Origin'); ?></th>

                                                <th> <?= lang('lang_Destination'); ?></th>
                                                <th> <?= lang('lang_Seller'); ?></th>
                                                <th> <?= lang('lang_Description'); ?> </th>


                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr ng-if="awbArray != 0" ng-repeat="data in awbArray">
                                                <td>{{$index + 1}}</td>

                                                <td>{{data.slip_no}}</td>
                                                <td>{{data.frwd_company_awb}}</td>
                                                <td>{{data.booking_id}}</td>
                                                <td>{{data.frwd_company_id}}</td>
                                                <td>{{data.origin}}</td>
                                                <td>{{data.destination}}</td>
                                                 <td>{{data.cust_name}}</td>

                                                <td>{{data.status_describtion}}</td>



                                            </tr>

                                        </tbody>
                                    </table>


                                </div>


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

        </div>
        <div style="display:none;">
            <table id="downloadtable">
                <thead>
                    <tr>
                    <th><?= lang('lang_SrNo'); ?>.</th>
                     <th><?= lang('lang_AWB'); ?></th>
                     <th><?= lang('lang_TPL_AWB'); ?></th>
                     <th><?= lang('lang_Ref_No'); ?>#</th>
                     <th><?= lang('lang_TPL_company'); ?></th>
                     <th><?= lang('lang_Origin'); ?></th>

                     <th> <?= lang('lang_Destination'); ?></th>         
                     <th> <?= lang('lang_Description'); ?> </th>


                    </tr>
                </thead>
                <tbody>
                    <tr ng-if="awbArray != 0" ng-repeat="data in awbArray">
                        <td>{{$index + 1}}</td>

                        <td>{{data.slip_no}}</td>
                        <td>{{data.frwd_company_awb}}</td>
                        <td>{{data.booking_id}}</td>
                        <td>{{data.frwd_company_id}}</td>
                        <td>{{data.origin}}</td>
                        <td>{{data.destination}}</td>

                        <td>{{data.status_describtion}}</td>



                    </tr>
                </tbody>
            </table>
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
                                                      var todaysDate = 'Items Table ' + new Date();
                                                      var blobURL = tableToExcel('downloadtable', 'test_table');
                                                      $(this).attr('download', todaysDate + '.xls')
                                                              $(this).attr('href', blobURL);
                                                      });
                                                      function printPage()
                                                              {
                                                              var divToPrint = document.getElementById('printTable');
                                                              var htmlToPrint = '' +
                                                                      '<style type="text/css">' +
                                                                                          'table {' +
                                                                                          'border:1px solid #000;' +
                         '}'+
                         'table th, table td {' +
       
                'width:1200px' +
                '}' +
                'table th, table td {' +
       
                'padding:8px;' +
                '}' +
                        'table th {' +
                        'padding-top: 12px;'+
                        'padding-bottom: 12px;'+
                        ' text-align: left;'+
       
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