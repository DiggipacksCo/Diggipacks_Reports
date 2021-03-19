<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>


    </head>

    <body ng-app="fulfill" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container"  ng-controller="shelveView" ng-init="loadMore(1, 0);">

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
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Pallet Table</strong>
                                    <a  ng-click="Shelveviewexportdata();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;

                                    <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a>
                                </h1>


                                <hr>

                            </div>

                            <div class="panel-body" >

                                <!-- Dashboard content -->
                                <div class="row" >
                                    <div class="col-lg-12" >

                                        <!-- Marketing campaigns -->
                                        <div class="panel panel-flat">

                                            <form ng-submit="dataFilter();">
                                            <!-- href="<? // base_url('Excel_export/shipments'); ?>" -->
                                 <!-- href="<? //base_url('Pdf_export/all_report_view'); ?>" -->
                                                <!-- Quick stats boxes -->
                                                <div class="panel-body " >
                                                    <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                        <!-- Today's revenue -->

                                                        <!-- <div class="panel-body" > -->

                                                        <table class="table table-bordered table-hover" style="width: 100%;">
                                                            <!-- width="170px;" height="200px;" -->
                                                            <tbody >
                                                                <tr style="width: 80%;">


                                                                    <td>
                                                                        <div class="form-group" ><strong>Pallet NO:</strong>
                                                                            <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.shelve"  class="form-control" placeholder="Pallet No.">

                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group" ><strong>City:</strong>
                                                                            <br>
                                                                            <?php
                                                                            $destData = getAllDestination();

                                                                            //print_r($destData);
                                                                            ?>
                                                                            <select  id="destination" name="destination"  ng-model="filterData.destination" multiple class="selectpicker" data-width="100%" >

                                                                                <option value="">Select Destination</option>
                                                                                <?php foreach ($destData as $data): ?>
                                                                                    <option value="<?= $data['id']; ?>"><?= $data['city']; ?></option>
<?php endforeach; ?>

                                                                            </select>
                                                                        </div> 
                                                                    </td>



                                                                    <td><button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                                    <td><button  class="btn btn-danger" ng-click="loadMore(1, 1);" >Search</button></td>

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

                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable">
                                        <thead>
                                            <tr>
<!--                                                <th>Sr.No.</th>-->

                                                <th style="text-align: center;">Pallet No.</th>
<!--                                                <th>City</th>-->

 <!-- <th>Category</th> -->

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

<!--                                            <td>{{$index + 1}}</td>-->
                                            <td style="text-align: center;"><img src="{{data.barcode}}"/><br>{{data.shelve_no}}</td>

<!--                                            <td><span class="label label-info">{{data.city_id}}</span></td>-->


                                        </tr>

                                    </table>

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1">Load More</button>
                                </div>
                                <hr>


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

    </div>
    <!-- /page container -->

    <script>
        $(document).ready(function () {
            var table = $('#example').DataTable({});
        });
    </script>
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