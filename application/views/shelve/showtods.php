<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/tods.js"></script>

    </head>

    <body ng-app="TodsAPp" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"  ng-controller="TodsCtrl" ng-init="loadMore(1, 0);">

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  >
                        <!--style="background-color: red;"-->
                        <?php if ($this->session->flashdata('msg')): ?>
                            <?= '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                        <?php elseif ($this->session->flashdata('error')): ?>
                            <?= '<div class="alert alert-danger">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                        <?php endif; ?>

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat"  >

                            <div class="panel-heading" dir="ltr"> 

                                <h1><strong>  <?= lang('lang_Show_Tod'); ?></strong>


                                    <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a>
                                </h1>

                                <hr>
                            </div>
                            <div class="panel-body" >



                                <div class="table-responsive" style="padding-bottom:20px;" > 

                                    <br>
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered" >
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th colspan="2" style="text-align: center;"><?= lang('lang_Tod'); ?> </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}}</td>
                                            <td align="center"><img src="{{data.barcode}}"/><br>
                                                {{data.tod_no}}</td>
                                            <td align="center"> <img src="{{data.qrcode}}"   />    
                                                </td>
                                          
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0<?= $type; ?>);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>



                                </div>
                                <hr>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div style="display:none;">
                        <table class="table table-striped table-hover table-bordered dataTable" id="printTable" >
                            <thead>
                                <tr>

                                    <th colspan="2" style="text-align: center;"><?= lang('lang_Tod'); ?> </th>




                                </tr>
                                
                            </thead>
                            <tbody>
                            </tbody>
                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">

                              <td align="center"><img src="{{data.barcode}}"/><br>
                                                {{data.tod_no}}<br><img src="{{data.qrcode}}"   />  </td>

                            </tr>
                        </table>

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
    <!-- /page container --> 


    <script>



        function printPage()
        {
            var divToPrint = document.getElementById('printTable');
            var htmlToPrint = '' +
                    '<style type="text/css">' +
                    'table {' +
                    'border:1px solid #000;' +
                    '}' +
                    'table th, table td {' +
                    'width:1200px' +
                    '}' +
                    'table th, table td {' +
                    'padding:8px;' +
                    '}' +
                    'table th {' +
                    'padding-top: 12px;' +
                    'padding-bottom: 12px;' +
                    ' text-align: left;' +
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