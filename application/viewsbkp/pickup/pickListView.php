<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/pickup.app.js"></script>


    </head>

    <body ng-app="appPickup" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="pickListView" ng-init="loadMore(1, 0);">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" ng-init="filterData.pickupId = '<?= $pickupId ?>'" >
                        <!--style="background-color: red;"-->

                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>


                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong>Pickuplist Details <?= $pickupId ?></strong>
                                            <a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>
<!--                                            <a id="pdf" onclick="printPageview();"><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>-->
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments');       ?>" -->
                         <!-- href="<? //base_url('Pdf_export/all_report_view');       ?>" -->
                                        <!-- Quick stats boxes -->
                                        <div class="row " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->

                                                <div class="col-md-3"> <div class="form-group" ><strong>AWB No.:</strong>
                                                        <input type="text" id="slip_no" name="slip_no"  ng-model="filterData.slip_no"  class="form-control" placeholder="Enter AWB no.">

                                                    </div></div>



                                                <div class="col-md-3"> <div class="form-group" ><strong>Pickup Status</strong>
                                                        <br>
                                                        <select  id="picked_status" name="picked_status" ng-model="filterData.picked_status" class="selectpicker"  data-width="100%" ><option value="">Pickup Status</option>

                                                            <option value="Y">Yes</option>
                                                            <option value="N">No</option>

                                                        </select>


                                                    </div></div>

                                                <div class="col-md-3"> <div class="form-group" ><strong>Packed Status</strong>
                                                        <br>
                                                        <select  id="pickup_status" name="pickup_status" ng-model="filterData.pickup_status" class="selectpicker"  data-width="100%" ><option value="">Packed Status</option>

                                                            <option value="Y">Yes</option>
                                                            <option value="N">No</option>

                                                        </select>


                                                    </div></div>

                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Picker:</strong>

                                                        <select  id="assigned_to" name="assigned_to"  ng-model="filterData.assigned_to"  class="form-control" data-width="100%" >
                                                            <option value="">Select Picker</option>

                                                            <option ng-repeat="Pdat in pickerArr" value="{{Pdat.id}}">
                                                                {{Pdat.name}}
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>



                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Seller:</strong>
                                                        <?php
                                                        $sellerArr = Getallsellerdata();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="sender_name" name="sender_name"  ng-model="filterData.sender_name"  class="selectpicker" data-width="100%" >
                                                            <option value="">Select Seller</option>
                                                            <?php foreach ($sellerArr as $data): ?>
                                                                <option value="<?= $data['company']; ?>">
                                                                    <?= $data['company']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Warehouse:</strong>
                                                        <?php
                                                        $warehouseArr = Getwarehouse_Dropdata();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-model="filterData.wh_id"  class="selectpicker" data-width="100%" >
                                                            <option value="">Select Warehouse</option>
                                                            <?php foreach ($warehouseArr as $data): ?>
                                                                <option value="<?= $data['id']; ?>">
                                                                    <?= $data['name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3"> <div class="form-group" ><strong>From:</strong>
                                                        <input class="form-control date" placeholder="From" id="from" name="from" ng-model="filterData.from">

                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong>To:</strong>
                                                        <input class="form-control date" placeholder="To" id="to"name="to"  ng-model="filterData.to" class="form-control"> 

                                                    </div></div>
                                                <div class="col-md-3"><button  class="btn btn-danger" ng-click="loadMore(1, 1);" >Search</button>&nbsp;<button type="button" class="btn btn-success">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></div>




                                            </div>



                                        </div>
                                        <br><br>

                                        <!-- /quick stats boxes -->
                                        </div>
                                        </div>
                                        </div>
                                        <!-- /dashboard content -->
                                        <!-- Basic responsive table -->
                                        <div class="panel panel-flat" >

                                            <div class="panel-body" >


                                                <div class="table-responsive" style="padding-bottom:20px;" >
                                                    <!--style="background-color: green;"-->
                                                    <table class="table table-striped table-hover table-bordered" id="downloadtable" style="width:100%;">
                                                        <thead>
                                                            <tr>
                                                                <th>Sr.No.</th>
                                                                <th>AWB No.</th>
                                                                <th>Ref. No.</th>
                                                                <th>Origin</th>  
                                                                <th>Destination</th>
                                                                <th>Receiver</th>
                                                                <th>Receiver Address</th>
                                                                <th>Receiver Mobile</th>
                                                                <th>Item Sku Detail   <table class="table"><thead>
                                                                            <tr>
                                                                                <th>SKU</th>
                                                                                <th>Qty</th>
                                                                                <th>COD (<?= site_configTable("default_currency"); ?>)</th>

                                                                            </tr>
                                                                        </thead></table></th>

                                                                <th>Deducted Shelve NO<table class="table"><thead>
                                                                            <tr>
                                                                                <th>Shelve No</th>
                                                                                <th>SKU</th>
                                                                            </tr>
                                                                        </thead></table></th>  




                                                                <th>Pickup Status</th>  
                                                                <th>Pickup Date</th>
                                                                <th>Picker</th>  
                                                                <th>Packed By</th>
                                                                <th>Pack Date</th> 


                                                                <th>Seller</th>
                                                                 <th>Tods No.</th>
                                                                <th>Warehouse</th>
                                                                <th>Date</th>
                                                                 <th>Action</th>

                                                            </tr>
                                                        </thead>
                                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                                            <td>{{$index + 1}}</td>
                                                            <td>{{data.slip_no}}</td>
                                                            <td>{{data.booking_id}}</td>
                                                            <td>{{data.origin}}</td>
                                                            <td>{{data.destination}}</td>
                                                            <td>{{data.reciever_name}}</td>
                                                            <td>{{data.reciever_address}}</td>
                                                            <td>{{data.reciever_phone}}</td>
                                                            <td>
                                                                <table class="table table-striped table-hover table-bordered ">

                                                                    <tbody>
                                                                        <tr ng-repeat="data1 in data.sku">
                                                                            <td ><span class="label label-primary">{{data1.sku}}</span></td>
                                                                            <td><span class="label label-info">{{data1.piece}}</span></td>

                                                                            <td><span class="label label-danger">{{data1.cod}}</span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>

                                                            </td>

                                                            <td><table class="table table-striped table-hover table-bordered">

                                                                    <tbody>
                                                                        <tr ng-repeat="data100 in data.deducted_shelve_no">
                                                                            <td ><span class="label label-primary">{{data100.deducted_shelve}}</span></td>
                                                                            <td><span class="label label-info">{{data100.sku}}</span></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table></td>
                                                            <td><span ng-if="data.picked_status == 'Yes'" class="label label-success">{{data.picked_status}}</span>
                                                                <span ng-if="data.picked_status == 'No'" class="label label-danger">{{data.picked_status}}</span>
                                                            </td>
                                                            <td><span ng-if="data.picked_status == 'Yes'" class="label label-success">{{data.pickedDate}}</span>
                                                                <span ng-if="data.picked_status == 'No'" class="label label-danger">N/A</span>
                                                            </td>
                                                            <td><span ng-if="data.assigned_to != null" class="label label-success">{{data.assigned_to}}</span>
                                                                <span ng-if="data.pickup_status == null" class="label label-danger">N/A</span>
                                                            </td>

                                                            <td><span ng-if="data.packedBy != null" class="label label-success">{{data.packedBy}}</span>
                                                                <span ng-if="data.packedBy == null" class="label label-danger">N/A</span>
                                                            </td>
                                                            <td><span ng-if="data.packedBy != null" class="label label-success">{{data.packDate}}</span>
                                                                <span ng-if="data.packedBy == null" class="label label-danger">N/A</span>
                                                            </td>
                                                            <td>{{data.sender_name}}</td>
                                                             <td>{{data.tods_barcode}}</td>
                                                            
                                                            
                                                            <td>{{data.wh_id}}</td>
                                                            <td>{{data.entrydate}}</td>
                                                            <td><a  class="btn btn-danger" confirmed-click="GetremoveOrderPicklist(data.slip_no);" 
    ng-confirm-click="Would you like to remove order from picklist?" ng-if="data.pickup_status!='Yes'">Back To Order</a>
                                                            <a  class="btn btn-danger" ng-if="data.pickup_status=='Yes'" disabled>Back To Order</a>
                                                            </td>
                                                        </tr>

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





                    </div>
                    <script>

            var tableToExcel = (function () {
                        var uri = 'data:application/vnd.ms-excel;base64,', template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                                                ,    base64    =    function (s)  {
                                                    return window.btoa(unescape(encodeURIComponent(s)))
                                                }
                                        ,    format    =    function (s,    c)  {
                                            return s.replace(/{(\w+)}/g,    function (m,    p)  {
                                                return c[p];
                                            })
                                        }
                                        return function (table,    name)  {
                                            if    (!table.nodeType)
                                                table    =    document.getElementById(table)
                                            var    ctx    =    {worksheet:  name ||    'Worksheet',    table:  table.innerHTML}
                                            var    blob    =    new    Blob([format(template,    ctx)]);
                                            var    blobURL    =    window.URL.createObjectURL(blob);
                                            return blobURL;
                                        }
                                    })()

                            $("#btnExport").click(function  ()  {
                                var      todaysDate      =      'Pickuplist Details <?= $pickupId ?>' + new Date();
                                var blobURL = tableToExcel('downloadtable', 'test_table');
                                $(this).attr('download', todaysDate + '.xls')
                                $(this).attr('href', blobURL);
                            });
// "order": [[0, "asc" ]]
                            $('#s_type').on('change', function () {
                                if ($('#s_type').val() == "SKU") {
                                    $('#s_type_val').attr('placeholder', 'Enter SKU no.');
                                } else if ($('#s_type').val() == "AWB") {
                                    $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                                }

                            });
                            function printPageview()
                            {
                            var divToPrint = document.getElementById('downloadtable');
var htmlToPrint = ''+
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
'padding-top: 12px;'+
'padding-bottom: 12px;'+
' text-align: left;'+
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

                    <!-- /page container -->

                    </body>
                    </html>
