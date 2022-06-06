
<?php

/*$number = cal_days_in_month(CAL_GREGORIAN, 10, date('Y')); // 31
echo "There were {$number} days in August 2003";
die;*/
?>
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

<style>
    #header {
  display: table-header-group;
  
}
table.report-container {
    page-break-after:always;
}
thead.report-header {
    display:table-header-group;
}

#mainC {
  display: table-row-group;
}

#footer {
  display: table-footer-group;
}
    
</style>
</head>

<body ng-app="Appfinance" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CTR_allinvoicesView" ng-init="getallseller();">

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

<!-- Marketing campaigns -->
<div class="panel panel-flat">
<div class="panel-heading" dir="ltr">
  <h1> <strong><?=lang('lang_All_Charges_Invoices');?></strong> 
<!--     <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;-->
 
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
          <td><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong>
              <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control">
                <option value=""><?=lang('lang_SelectSeller');?></option>
                <option ng-repeat="sdata in sellerdata"  value="{{sdata.id}}">{{sdata.name}}</option>
              </select>
            </div></td>
             <td><div class="form-group" ><strong><?=lang('lang_Year');?>:</strong>
              <select id="years" name="years" ng-model="filterData.years" class="form-control">
                <option value=""><?=lang('lang_select');?> <?=lang('lang_Year');?></option>
              <?php
              
  // Sets the top option to be the current year. (IE. the option that is chosen by default).
  $currently_selected = date('Y'); 
  // Year to start available options at
  $earliest_year = 2019; 
  // Set your latest year you want in the range, in this case we use PHP to just set it to the current year.
  $latest_year = date('Y'); 

  
  // Loops over each int[year] from current year, back to the $earliest_year [1950]
  foreach ( range( $latest_year, $earliest_year ) as $i ) {
    // Prints the option with the next year in range.
    print '<option value="'.$i.'"'.($i === $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';
  }
 
  ?>
              </select>
            </div></td>
          <td><div class="form-group" ><strong><?=lang('lang_Months');?>:</strong>
              <select id="monthid"name="monthid" ng-model="filterData.monthid" class="form-control">
                <option value=""><?=lang('lang_Select_Month');?></option>
                <option ng-repeat="num in [0,1,2,3,4,5,6,7,8,9,10,11]"  value="{{$index + 1}}">{{$index | month}}</option>
              </select>
            </div></td>
         
          <td><button  class="btn btn-danger" ng-click="loadMore(1,0);" ><?=lang('lang_Get_Details');?></button></td>
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
    <div class="table-responsive" style="padding-bottom:20px;" id="printTable" > 
      <!--style="background-color: green;"-->
       <table ng-show="tableshow" class="report-container" border="1">
      <tr>
      <td>
      
          <div id="mainC">
          
      <table ng-show="tableshow" class="table table-striped table-hover table-bordered dataTable bg-* display nowrap"  style="width:100%;">
        <thead >
            
            <tr><th colspan="14" align="center" style="text-align:center;"><?=lang('lang_Tax_Invoice');?></th></tr>
            
            <tr>
        <th colspan="5">
        
        UID Account Number:- {{sellerArr.uniqueid}}<br>
Customers Name:- {{sellerArr.company}} <br>
Address:- {{sellerArr.address}},{{sellerArr.city}} , Saudi Arabia - <br>
Bank Account Number:-{{sellerArr.account_number}} - <br>
Account Manager:-{{sellerArr.account_manager}}<br>
Vat Id No.:- {{sellerArr.vat_no}}- <br>
Currency:-<?= site_configTable("default_currency");?>
        </th>
        <th colspan="4" align="center" style="text-align:center;"><img src="<?=SUPERPATH.Getsite_configData_field('logo');?>" width="200" style="vertical-align:middle;"></th>
        <th colspan="5">Address &ndash; <?=Getsite_configData_field('company_address');?><br>
Vat Id No.:- <?=Getsite_configData_field('vat');?> - <br>
<br>
</th>
       </tr>
          <tr>
          
            <th>Date</th>
           <!-- <th>Type of Storage</th>
            <th>Pallets</th>-->
            <th>Pickup</th>
            <th>Inbound</th>
            <th>Outbound</th>
           <!-- <th>Delivery LM</th>
            <th>Delivery Other Partners</th>
            <th>COD Charges</th>-->
            <th>Daily Space Rental</th>
            <th>Packaging</th>
            <th>Picking</th>
            <th> Special Packing Seller</th>
           
           
            <th>Inventory</th>
            <th>Client Portal</th>
            <th>Barcode Printing</th>
            <th>Return</th>
            <th>VAT(15%)</th>
           <!-- <th>Additional Charges</th>-->
            <th>Total Amount</th>
           <!-- <th class="text-center" ><i class="icon-database-edit2"></i></th>-->
            
          </tr>
          
        </thead>
        
        <tr ng-if='showlistData!=0' ng-repeat="data in showlistData">
         
          <td width="150">{{data.date}}</td>
          <!--<td>{{data.sku}}</td>
          <td>{{data.qty}}</td>-->
          <td >{{data.pickupcharge}}</td>
          <td>{{data.inbound_charge}}</td>
           <td>{{data.outchargebound}}</td>
          <td>{{data.storagerate}}</td>
          <td>{{data.packaging_charge}}</td>
          <td>{{data.picking_charge}}</td>
          <td>{{data.special_packing_charge}}</td>
          <td>{{data.inventory_charge}}</td>
          <td>{{data.rentcharge}}</td>
          <td>{{data.barcode_print}}</td>
          <td>{{data.returncharge}}</td>
          <td>{{data.taxshow | number : 2}}</td>
          
         
          <td>{{data.totalrowcharge | number : 2}}</td>
        <!--  <td>{{data.seller_name}}</td>
          <td>{{data.seller_name}}</td>
          <td>{{data.seller_name}}</td>
          <td>{{data.seller_name}}</td>-->
          
          
         
         <!--  <td class="text-center"><ul class="icons-list">
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                <ul class="dropdown-menu dropdown-menu-right">
            
                  
                </ul>
              </li>
            </ul></td>
         -->
        </tr>
        
      </table>

              
              <table ng-show="tableshow" class="table table-striped table-hover table-bordered">
                   <tr>
                       <td colspan="4" align="center">Summary</td> 
                  </tr>
                  <tr>
                      <td>Pickup Fees </td> <td>{{summaryArr.totalshow_pickupcharge}} </td><td>Inbound Fees </td> <td>{{summaryArr.totalshow_inbound_charge}} </td>
                  </tr>
                  <tr>
                      <td>Outbound Fees </td> <td>{{summaryArr.totalshow_outchargebound}} </td><td>Daily Rental Fees </td> <td>{{summaryArr.totalshow_storagerate}} </td>
                  </tr>
                  <tr>
                      <td>Packaging Fees </td> <td>{{summaryArr.totalshow_picking_charge}} </td><td>Special Packages Fees </td> <td>{{summaryArr.totalshow_special_packing_charge}}</td>
                  </tr>
                   <tr>
                      <td>Inventory Fees </td> <td>{{summaryArr.totalshow_inventory_charge}} </td><td>Client Portal </td> <td>{{summaryArr.totalshow_rentcharge}} </td>
                  </tr>
                   <tr>
                      <td>Printing SKU Fees </td> <td>{{summaryArr.totalshow_barcode_print}} </td><td>Return Fees </td> <td>{{summaryArr.totalshow_returncharge}} </td>
                  </tr>
                  
                   <tr id="disdisplay">
           <td >Discount</td><td><input type="text" name="discountval" id="discountval" ng-model="disArray.discountval" class="form-control" ng-blur="GetalluserdiscountDatashow(disArray.discountval);"></td>
       </tr>
                  <tr>
                      <td>Total Fees Before VAT 15% </td> <td>{{totalamot | number : 2}} </td>
                  </tr>
                  <tr><td>VAT 15% </td> <td>{{totalvat | number : 2}} </td></tr>
                   <tr><td >Discount Show</td><td>{{showdiscount | number : 2}}</td></tr>
                   <tr>
                      <td>Total Fees with VAT 15% </td> <td>{{totalcharges | number : 2}} </td>
                  </tr>
                  
                  
              </table>
     <!-- <button ng-show="tableshow" ng-hide="showlistData.length==totalCount" class="btn btn-info" ng-click="loadMore(count=count+1,0);" ng-init="count=1">Load More</button>-->
     </div>
      </td>
     </tr>
     </table>
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
 
<!-- /page container -->
<script>

function printPage()
{
   document.getElementById('disdisplay').style.display="none";
   var divToPrint = document.getElementById('printTable');
    var htmlToPrint = '' +
        '<style type="text/css">' +
		 'table th, table td {' +
        'border:1px solid #000;' +
        'width:1200px' +
        '}' +
        'table th, table td {' +
        'border:' +
        'padding:8px;' +
        '}' +
		'table th {' +
		'padding-top: 12px;'+
		'padding-bottom: 12px;'+
		' text-align: left;'+
        'border:' +
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
    var todaysDate = 'All Charges Invoices '+ new Date();
    var blobURL = tableToExcel('printTable', 'invoice');
    $(this).attr('download',todaysDate+'.xls')
    $(this).attr('href',blobURL);
});




</script>
</body>
</html>
