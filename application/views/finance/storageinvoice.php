<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<?php $this->load->view('include/file'); ?>
<script src="<?=base_url();?>assets/js/angular/finance.app.js"></script>
</head>

<body ng-app="Appfinance" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CTR_StorageinvoiceView" ng-init="getallseller();"> 

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
if($this->session->flashdata('succmsg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('succmsg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

if($this->session->flashdata('errormess'))
echo '<div class="alert alert-warning">'.$this->session->flashdata('errormess').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
?>
<div class="row" >
<div class="col-lg-12" >

<!-- Marketing campaigns -->
<div class="panel panel-flat">
<div class="panel-heading" dir="ltr">
  <h1> <strong><?=lang('lang_StorageChargesInvoices');?></strong> 
    <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;
 
                 <a onclick="printPage();" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 
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
          <td><div class="form-group" ><strong><?=lang('lang_Sellers');?>:</strong>
              <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control">
                <option value=""><?=lang('lang_SelectSeller');?></option>
                <option ng-repeat="sdata in sellerdata"  value="{{sdata.id}}">{{sdata.name}}</option>
              </select>
            </div></td>
             <td><div class="form-group" ><strong><?=lang('lang_From_Date');?>:</strong>
               <input type="text" name="fromdate" id="fromdate" ng-model="filterData.fromdate" class="form-control">
            </div></td>
              <td><div class="form-group" ><strong><?=lang('lang_To_Date');?>:</strong>
             <input type="text" name="todate" id="todate" ng-model="filterData.todate" class="form-control">
            </div></td>
          
          <td><button  class="btn btn-danger" ng-click="loadMore(1,1);" ><?=lang('lang_Get_Details');?></button></td>
        
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
    <div class="table-responsive" style="padding-bottom:20px;" ng-if='totalCount>0' > 
      <!--style="background-color: green;"-->
      <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
        <thead>
          <tr>
          <th><?=lang('lang_SrNo');?></th>
            <th><?=lang('lang_Date');?></th>
           <!-- <th>SKU</th>
            <th>QTY</th>-->
            <!--<th align="center" style="text-align:center;">Storage Details<table class="table table-striped table-hover table-bordered dataTable bg-*"><tr><th>Type</th><th>Pallets</th><th>Rates</th></tr></table></th>-->
            <th><?=lang('lang_Total_Pallets');?></th>
            <th><?=lang('lang_TotalRate');?></th>
            <th><?=lang('lang_Seller');?></th>
           <!-- <th class="text-center" ><i class="icon-database-edit2"></i></th>-->
            
          </tr>
          
        </thead>
        
        <tr ng-if='showlistData!=0' ng-repeat="data in showlistData">
          <td>{{$index+1}} </td>
          <td>{{data.entrydate | dateOnly}}</td>
          
        <!--  <td>{{data.sku}}</td>
          <td>{{data.qty}}</td>-->
        <!--  <td><table class="table table-striped table-hover table-bordered dataTable bg-*" ng-repeat="data2 in data.storageArray"><tr><td >{{data2.storagetypename}}</td><td>{{data2.totalpallets | number:2}}</td><td>{{data2.totalstorage}}</td></tr></table></td>-->
          
         <!-- <td>{{data.storage_type}}</td>-->
          <td>{{data.totalpallets | number:2}}</td>
           <td>{{data.totalcharge}}</td>
          <td>{{data.seller_name}}</td>
         
          <!-- <td class="text-center"><ul class="icons-list">
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                <ul class="dropdown-menu dropdown-menu-right">
      
                  
                  
                   <li><a   ><i class="icon-pencil7"></i> </a></li>
                  
                </ul>
              </li>
            </ul></td>-->
         
        </tr>
      </table>
      <button ng-hide="showlistData.length==totalCount" class="btn btn-info" ng-click="loadMore(count=count+1,0);" ng-init="count=1"><?=lang('lang_Load_More');?></button>
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

</div>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>
  $( function() {
    $( "#fromdate" ).datepicker({
      changeMonth: true,
      changeYear: true,
	dateFormat: 'yy-mm-dd',
	//minDate: 0
	maxDate: new Date()
    });
  } );
   $( function() {
    $( "#todate" ).datepicker({
      changeMonth: true,
      changeYear: true,
	dateFormat: 'yy-mm-dd',
	//minDate: 0
	maxDate: new Date()
    });
  } );
  
  
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
    var todaysDate = 'Storage Charges Invoices '+ new Date();
    var blobURL = tableToExcel('printTable', 'test_table');
    $(this).attr('download',todaysDate+'.xls')
    $(this).attr('href',blobURL);
});



</script>
<!-- /page container -->

</body>
</html>
