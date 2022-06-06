<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<?php $this->load->view('include/file'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
</head>

<body ng-app="fulfill" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="deliveryManifest" ng-init="loadMore(1,0);">

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

<div class="row" >
<div class="col-lg-12" >
<div class="loader logloder" ng-show="loadershow"></div>
<!-- Marketing campaigns -->
<div class="panel panel-flat">
<div class="panel-heading">
  <h1> <strong><?=lang('lang_delivery_Manifest_list');?> </strong> <!--<a  ng-click="exportExcel();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>
    <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
      <option value="" selected>Select Export Limit</option>
      <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>
    </select>-->
    
    <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>--> 
  </h1>
</div>
<form ng-submit="dataFilter();">
<!-- href="<?// base_url('Excel_export/shipments');?>" --> 
<!-- href="<?//base_url('Pdf_export/all_report_view');?>" --> 
<!-- Quick stats boxes -->
<div class="table-responsive " >
  <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 
    
    <!-- Today's revenue --> 
    
    <!-- <div class="panel-body" > -->
    
    <table class="table table-bordered table-hover" style="width: 100%;">
      <!-- width="170px;" height="200px;" -->
      <tbody >
        <tr style="width: 80%;">
          <td><div class="form-group" ><strong><?=lang('lang_AWB_No');?>:</strong>
              <input type="text" id="slip_no"name="slip_no" ng-model="filterData.slip_no" class="form-control" ng-enter="loadMore(1,1);" placeholder="Enter AWB No.">
            </div></td>
          <td><div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
              <input class="form-control date" id="from" name="from" ng-model="filterData.from" placeholder="From Date">
            </div></td>
          <td><div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
              <input class="form-control date" id="to"name="to"  ng-model="filterData.to"  placeholder="To Date">
            </div></td>
        </tr>
        <tr>
             
             <td><div class="form-group" ><strong><?=lang('lang_label');?>:</strong>
               <select class="form-control" ng-model="filterData.label">
                         <option value=""><?=lang('lang_select');?></option>
                         <option value="Y"><?=lang('lang_Yes');?></option>
                         <option value="N"><?=lang('lang_No');?></option>
                         
                     </select>
            </div></td>
          <td> <button  class="btn btn-danger" ng-click="loadMore(1,1);" ><?=lang('lang_Search');?></button>
              &nbsp;<button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
             </td>
          
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
<div class="panel panel-flat" >
  <div class="panel-body" >
    <div class="table-responsive" style="padding-bottom:20px;" > 
      <!--style="background-color: green;"-->
      <table class="table table-striped table-hover table-bordered dataTable bg-*"  style="width:100%;">
        <thead>
          <tr>
          <th><?=lang('lang_SrNo');?>.</th>
            <th>  <?=lang('lang_Manifest_ID');?>#</th>
            <th><?=lang('lang_Order_Count');?></th>
            <th> <?=lang('lang_Date');?>  </th>            
            <th class="text-center" ><i class="icon-database-edit2"></i></th>
          </tr>
        </thead>
        <tr ng-if='shipData!=0' ng-repeat="data in shipData">
          <td>{{$index+1}} </td>
          <td><img src="{{data.pickup_print}}"/><br>
            {{data.m_id}}</td>
          <td><span class="badge badge-info">Total ({{data.total_ship}})</span>
            </td>
          <td>{{data.entry_date}}</td>
         
          
          <td class="text-center"><ul class="icons-list">
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                <ul class="dropdown-menu dropdown-menu-right">
                  <li><a href="manifestView/{{data.m_id}}" target="_blank"><i class="icon-eye" ></i>  <?=lang('lang_View');?></a></li>  
                  <li><a href="manifestPrint_d/{{data.m_id}}" target="_blank"><i class="icon-file-pdf"></i><?=lang('lang_Print_Manifest');?></a></li>                 
                  </ul>
              </li>
            </ul></td>
        </tr>
      </table>
      <button ng-hide="shipData.length==totalCount" class="btn btn-info" ng-click="loadMore(count=count+1,0);" ng-init="count=1"><?=lang('lang_Load_More');?></button>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel"><?=lang('lang_Assign_Picker_To');?> {{pickId}} </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
</div>
<div class="modal-body">
<form novalidate ng-submit="myForm.$valid && createUser()" >
  <select ng-model="AssignData.selectedPicker" class="form-control" >
    <option ng-repeat="x in pickerArray"  value="{{x.user_id}}">{{x.username}}</option>
  </select>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('lang_Close');?></button>
    <button type="button" class="btn btn-primary" ng-click="savePicker();" ><?=lang('lang_Assign_Picker');?></button>
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
      <th><?=lang('lang_SrNo');?>.</th>
        <th> <?=lang('lang_PickUp_ID');?></th>
        <th><?=lang('lang_Order_Count');?></th>
        <th> <?=lang('lang_Date');?>  </th>
        <th><?=lang('lang_Picked_Up');?></th>
        <th><?=lang('lang_Assigned_To');?></th>
        <th class="text-center" ><i class="icon-database-edit2"></i></th>
      </tr>
    </thead>
    <tr ng-if='shipData1!=0' ng-repeat="data in shipData1">
      <td>{{$index+1}} </td>
      <td><br>
        {{data.pickupId}}</td>
      <td><span class="badge badge-info"><?=lang('lang_Total');?> ({{data.packedcount}})</span><br>
        <br>
        <span class="badge badge-danger "><?=lang('lang_Pending');?> ({{data.unpackedcount}})</span></td>
      <td>{{data.entrydate}}</td>
      <td ng-if="data.pickup_status=='Y'"><span class="badge badge-success"><?=lang('lang_Yes');?></span></td>
      <td ng-if="data.pickup_status=='N'"><span class="badge badge-danger"><?=lang('lang_No');?></span></td>
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
  var blobURL = window.URL.createObjectURL(blob);
    return blobURL;
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
 $('#s_type').on('change',function(){
          if($('#s_type').val()=="SKU"){
            $('#s_type_val').attr('placeholder','Enter SKU no.');
          }else if($('#s_type').val()=="AWB"){
            $('#s_type_val').attr('placeholder','Enter AWB no.');
          }

        });

     
</script> 
<script type="text/javascript"> 

    $('.date').datepicker({

       format: 'yyyy-mm-dd'

     });

<!-- /page container -->
</script>
</body>
</html>
