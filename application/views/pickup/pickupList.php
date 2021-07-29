<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 

    </head>

    <body ng-app="fulfill" >

        <?php $this->load->view('include/main_navbar'); ?>   


        <!-- Page container -->
        <div class="page-container" ng-controller="pickupList" ng-init="loadMore(1, 0);">

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
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>
                        <div class="row" >
                            <div class="col-lg-12" >
                                <div class="loader logloder" ng-show="loadershow"></div>
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong>Pickup List </strong>
                                            <a  ng-click="exportExcel();" > 
                                                <!--<a  ng-click="getExcelDetailsPicklist();" >-->
                                                <i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>   
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected>Select Export Limit</option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  

                                            </select> 


<!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>-->
                                        </h1> 
                                    </div>
                                    <form ng-submit="dataFilter();">

                                        <div class="panel-body " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>AWB No:</strong>
                                                        <input type="text" id="slip_no"name="slip_no" ng-model="filterData.slip_no" class="form-control" placeholder="Enter AWB No."> 

                                                    </div>
                                                </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>From:</strong>
                                                        <input class="form-control date" placeholder="From" id="from" name="from" ng-model="filterData.from">

                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong>To:</strong>
                                                        <input class="form-control date" placeholder="To" id="to"name="to"  ng-model="filterData.to" class="form-control"> 

                                                    </div></div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>PickUp ID</strong>
                                                        <input type="text" id="pickupId" name="pickupId" ng-model="filterData.pickupId" class="form-control" placeholder="Enter PickUp ID"> 

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
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Pickup:</strong>

                                                        <select  id="picked_status" name="picked_status"  ng-model="filterData.picked_status"  class="selectpicker" data-width="100%" >
                                                            <option value="">Select Pickup</option>
                                                            <option value="Y">Yes</option>
                                                            <option value="N">No</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Packed:</strong>

                                                        <select  id="pickup_status" name="pickup_status"  ng-model="filterData.pickup_status"  class="selectpicker" data-width="100%" >
                                                            <option value="">Select Packed</option>
                                                            <option value="Y">Yes</option>
                                                            <option value="N">No</option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Picker:</strong>

                                                        <select  id="assigned_to" name="assigned_to"  ng-model="filterData.assigned_to"  class="form-control" data-width="100%" >
                                                            <option value="">Select Picker</option>

                                                            <option ng-repeat="Pdat in pickerArray" value="{{Pdat.id}}">
                                                                {{Pdat.name}}
                                                            </option>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group" >
                                                        <button type="button" class="btn btn-success">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                        <button  class="btn btn-danger" ng-click="loadMore(1, 1);" >Search</button>

                                                        <a class="btn btn-primary" ng-click="run_pickup_cron();" target="_blank"><i class="fa fa-refresh"></i> Sync</a>
                                                    </div></div>






                                            </div>



                                        </div>

                                        <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>


                        <div class="panel panel-flat" >

                            <div class="panel-body" >


                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*"  style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>Sr.No.</th>
                                                <th>PickUp ID#</th>
                                                <th>Order Count</th>  
                                                <th>Date</th>   
                                                <th>Picked Up</th>
                                                <th>Packed</th>
                                                <th>Assigned To</th>
                                                <th>Warehouse</th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}}  </td>
                                            <td><img src="{{data.pickup_print}}"/><br>{{data.pickupId}}</td>

                                            <td><span class="badge badge-info">Total ({{data.packedcount}})</span><br><br>
                                                <span class="badge badge-danger ">Pending ({{data.unpackedcount}})</span>
                                            </td>
                                            <td>{{data.entrydate}}</td>
                                            <td ng-if="data.picked_status == 'Y'"><span class="badge badge-success">Yes</span></td>
                                            <td ng-if="data.picked_status == 'N'"><span class="badge badge-danger">No</span></td>
                                            <td ng-if="data.pickup_status == 'Y'"><span class="badge badge-success">Yes</span></td>
                                            <td ng-if="data.pickup_status == 'N'"><span class="badge badge-danger">No</span></td>
                                            <td > {{data.assigned_to}}</td>
                                            <td > {{data.wh_id}}</td>


                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>


                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li><a href="pickListView/{{data.pickupId}}" target="_blank"><i class="icon-eye" ></i> View</a></li>
                                                            <li ng-if="data.pack_button == 'Y'"><a ng-click="assignPicker(data.pickupId);"  ><i class="icon-pencil7"></i> Assign Pickup List</a></li>

                                                            <li ng-repeat="data8 in data.forwardedArr"><a href="Printpicklist3PL/{{data.pickupId}}/{{data8.frwd_company_id}}" target="_blank"><i class="icon-file-pdf" ></i>{{data8.company}} Print 3PL</a>
                                                            </li>

                                                            <li ng-if="data.E_city_button == 'Y'"><a href="awbPickupPrint/{{data.pickupId}}/e_city" target="_blank"><i class="icon-file-spreadsheet" ></i>Exception Print</a></li>

                                                            <li><a href="pickListPrint/{{data.pickupId}}" target="_blank"><i class="icon-file-spreadsheet" ></i>Print Pickup List</a></li>

                                                            <li><a href="pickListPrintA4/{{data.pickupId}}" target="_blank"><i class="icon-file-spreadsheet" ></i>Print Pickup List A4</a></li>

                                                            <li><a href="awbPickupPrint/{{data.pickupId}}/FS" target="_blank"><i class="icon-file-pdf"></i>Awb 4*6</a></li>
                                                            <li><a href="awbPickupPrint/{{data.pickupId}}/AF" target="_blank"><i class="icon-file-pdf"></i>Awb A4</a></li>
                                                            <li><a onClick="return confirm('are you sure want to delete?');" href="<?= base_url(); ?>/PickUp/picklistremove/{{data.pickupId}}"><i class="icon-trash"></i>Delete</a></li>


                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td> 
                                        </tr>

                                    </table>   

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1">Load More</button>
                                </div>
                                <hr>
                            </div>
                        </div>


                        <div id="excelcolumnPicklist" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #f3f5f6;">
                                        <center>   <h4 class="modal-title" style="color:#000"><?= lang('lang_Select_Column_to_download'); ?></h4></center>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4">             
                                                <label class="container">

                                                    <input type="checkbox" id='but_checkall' value='Check all' ng-model="listData2.checked" ng-click='checkAll()'/>   <?= lang('lang_SelectAll'); ?> 
                                                    <span class="checkmark"></span>


                                                </label>
                                            </div>

                                            <div class="col-md-12 row">
                                                <div class="col-sm-4">          
                                                    <label class="container">  
                                                        <input type="checkbox" name="pickupId" value="pickupId"   ng-checked="checkall" ng-model="listData2.pickupId"> Picklist ID #
                                                        <span class="checkmark"></span>
                                                    </label>   
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="packedcount" value="packedcount"  ng-checked="checkall" ng-model="listData2.packedcount"> Order Count
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="unpackedcount" value="unpackedcount"  ng-checked="checkall" ng-model="listData2.unpackedcount"> Unpacked Count
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
                                                        <input type="checkbox" name="picked_status" value="picked_status" ng-checked="checkall" ng-model="listData2.picked_status"> Picked Up
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Packed" value="address" ng-checked="checkall" ng-model="listData2.Packed"> Packed
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4"> 
                                                    <label class="container">
                                                        <input type="checkbox" name="assigned_to" value="assigned_to" ng-checked="checkall" ng-model="listData2.assigned_to"> Assigned To
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="quantity" value="warehouse"  ng-checked="checkall" ng-model="listData2.warehouse">Warehouse
                                                        <span class="checkmark"></span>
                                                    </label>   
                                                </div>


                                            </div>  
                                            <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                            <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                                <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShipPicklist(listData2, listData1.exportlimit);"><?= lang('تحميل تقرير الاكسل'); ?></button>  
                                            </div>

                                        </div>

                                    </div>
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
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Assign Picker To {{pickId}} </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form novalidate ng-submit="myForm.$valid && createUser()" >
                                <select ng-model="AssignData.selectedPicker" class="form-control" >
                                    <option ng-repeat="x in pickerArray"  value="{{x.id}}">{{x.username}}</option>
                                </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" ng-click="savePicker();" >Assign Picker</button>
                        </div>
                        </form>          
                    </div>
                </div>
            </div>

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
            <div style="display:none">
                <table class="table table-striped table-hover table-bordered dataTable bg-*" id="downloadtable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Sr.No.</th>
                            <th>PickUp ID#</th>
                            <th>Order Count</th>  
                            <th>Date</th>   
                            <th>Picked Up</th>
                            <th>Assigned To</th>
                            <th class="text-center" ><i class="icon-database-edit2"></i></th>
                        </tr>
                    </thead>
                    <tr ng-if='shipData1 != 0' ng-repeat="data in shipData1"> 

                        <td>{{$index + 1}}  </td>
                        <td><br>{{data.pickupId}}</td>

                        <td><span class="badge badge-info">Total ({{data.packedcount}})</span><br><br>
                            <span class="badge badge-danger ">Pending ({{data.unpackedcount}})</span>
                        </td>
                        <td>{{data.entrydate}}</td>
                        <td ng-if="data.pickup_status == 'Y'"><span class="badge badge-success">Yes</span></td>
                        <td ng-if="data.pickup_status == 'N'"><span class="badge badge-danger">No</span></td>
                        <td > {{data.assigned_to}}</td>



                    </tr>

                </table>   

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
                                    var blobURL = window.URL.createObjectURL(blob);           return blobURL;
}
})()

$("#btnExport").click(function () {
var todaysDate = 'Dispatched Details '+ new Date();
var blobURL = tableToExcel('downloadtable', 'test_table');
$(this).attr('download',todaysDate+'.xls')
$(this).attr('href',blobURL);
});


// "order": [[0, "asc" ]]
$('#s_type').on('change',function(){
if($('#s_type').val()=="SKU"){
$('#s_type_val').attr('placeholder','Enter SKU no.');
}else if($('#s_type').val()=="AWB"){
$('#s_type_val').attr('placeholder','Enter AWB no.');
}

});

     
        </script>
        <script>


                                                                // "order": [[0, "asc" ]]
                                                                $('#s_type').on('change', function(){
                                                        if ($('#s_type').val() == "SKU"){
                                                        $('#s_type_val').attr('placeholder', 'Enter SKU no.');
                                                        } else if ($('#s_type').val() == "AWB"){
                                                        $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                                                        }

                                                        });</script>

        <script type="text/javascript">

                            $('.date').datepicker({

                    format: 'yyyy-mm-dd'

                    });

<!-- /page container -->
        </script>
    </body>
</html>
