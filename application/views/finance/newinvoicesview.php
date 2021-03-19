<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
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
<div class="page-container" ng-controller="CTR_allinvoicesView" ng-init="getallseller('Fix Rate');invoiceReport(1,1);">

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
<div class="panel-heading">
  <h1> <strong>All Charges Invoices</strong> 
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
          <td><div class="form-group" ><strong>Sellers:</strong>
              <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control">
                <option value="">Select Seller</option>
                <option ng-repeat="sdata in sellerdata"  value="{{sdata.id}}">{{sdata.name}}</option>
              </select>
            </div></td>
             <td><div class="form-group" ><strong>Year:</strong>
              <select id="years" name="years" ng-model="filterData.years" class="form-control">
                <option value="">Select Year</option>
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
          <td><div class="form-group"> <strong>Months:</strong>
              <select id="monthid"name="monthid" ng-model="filterData.monthid" class="form-control">
                <option value="">Select Month</option>
                <option ng-repeat="num in [0,1,2,3,4,5,6,7,8,9,10,11]"  value="{{$index + 1}}">{{$index | month}}</option>
              </select>
            </div></td>
         
          <td><button  class="btn btn-danger" ng-click="invoiceReport(1,1);" >Get Details</button></td>
          <td>
          <a class="btn btn-danger" ng-click= "run_shell_fixrate();" target="_blank" >Sync</a>
           

          </td>
        </tr>
      </tbody>
    </table>
    <br>
    <div id="today-revenue"></div>   
  </div>
</div>

<!-- /quick stats boxes -->
</div>
</div>
</div>
<form>
<div class="panel panel-flat" >
  <div class="panel-body" >
    <div class="table-responsive" style="padding-bottom:20px;"  > 
      <!--style="background-color: green;"-->
      <table class="table table-striped table-hover table-bordered dataTable bg-* display nowrap" style="width:100%; overflow:scroll;">
        <thead>
          <tr>
            <th>Sr No.</th>
            <th>Invoice no</th>
             <th>Seller Name  </th>             
             <th>Month </th>             
             <!-- <th>Pickup Charge</th>
             <th>Handline Fees </th>
             <th>Special Packing </th>
             <th>Return Charge </th>
             <th>Shipping Charge </th>
             <th>Onhold Charge </th>
             <th>Storaage Charge </th>
             <th> Vat Charges </th>
             <th>Total Charges</th> -->
              <th>Create Date </th>
             <th>Pay Status </th>
             <th>Pay Date </th>
             <th>Pay Updated By </th>
             <th>Created By </th>
             <th>Action </th>
          </tr>
        </thead>
        
        <tr ng-if='showlistData!=0' ng-repeat="data in showlistData">
           <td>{{$index+1}} </td>
          <td>{{data.invoice_no}} </td>
          <td>{{data.customerName}}</td>
          <td>{{data.month}}</td>
         <!--  <td style="width:25%;"> SAR {{data.pickup_charge}} </td>
            <td> SAR {{data.handline_fees}}</td>
            <td> SAR {{data.special_packing_seller -- data.special_packing_warehouse}}<br>
            <td> SAR {{data.return_charge}}</td>
            <td> SAR {{data.shipping_charge}}</td>
            <td> SAR {{data.onhold_charges}}</td>
            <td> SAR {{data.storage_charge}}</td>
            <td>{{data.vat_percent}}</td>
       
          <td>SAR {{data.total_charges}} </td> -->
          <td style="width:15%;"> {{data.invoice_date}}</td>
          <td><span ng-if= "data.pay_status=='Y'">Yes</span> <span ng-if= "data.pay_status!='Y'">No</span> </td>
          <td style="width:15%;"> {{data.pay_date}}</td>
          <td style="width:15%;"> <span ng-if= "data.pay_update_by <= 0">NA </span> <span ng-if= "data.pay_update_by>0">{{data.payby}}</span> </td>
          <td> {{data.username}}</td>
          <td class="text-center"><ul class="icons-list">
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                <ul class="dropdown-menu dropdown-menu-right">
                  <li ><a  href="<?=base_url();?>"><i class="icon-money fa fa-money" ></i> Pay</a></li>
                  <li ><a  href="<?=base_url();?>viewinvoice/{{data.invoice_no}}"><i class="icon-eye" ></i>  View Invoice </a></li>
                </ul>
              </li>
            </ul></td>
            <td>
              
          </tr>
        
      </table>
     <!--  <button ng-hide="showlistData.length==totalCount" class="btn btn-info" ng-click="invoiceReport(count=count+1,0);" ng-init="count=1">Load More</button> -->
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
</div>
 
<!-- /page container -->

</body>
</html>
