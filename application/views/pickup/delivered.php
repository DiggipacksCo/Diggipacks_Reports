<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/pickup.app.js"></script>


</head>

<body ng-app="appPickup" >

<?php $this->load->view('include/main_navbar'); ?>


<!-- Page container -->
<div class="page-container" ng-controller="pickListView" ng-init="loadMore(1,0);">

<!-- Page content -->
<div class="page-content">

<?php $this->load->view('include/main_sidebar'); ?>


<!-- Main content -->
<div class="content-wrapper" >
<!--style="background-color: black;"-->
<?php $this->load->view('include/page_header'); ?>



<!-- Content area -->
<div class="content" ng-init="filterData.pickupId='<?= $pickupId?>'" >
<!--style="background-color: red;"-->
   
<?php 
if($this->session->flashdata('msg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

if($this->session->flashdata('something'))
echo '<div class="alert alert-warning">'.$this->session->flashdata('something').": ".$this->session->flashdata('error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
?>


     <!-- Dashboard content -->
          <div class="row" >
            <div class="col-lg-12" >

              <!-- Marketing campaigns -->
              <div class="panel panel-flat">
                <div class="panel-heading">
                 <h1>
                  <strong>Pickuplist Details <?= $pickupId?></strong>
                  <a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>
                 <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>
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
                        
                         <td>
                          <div class="form-group" ><strong>Pickup Status</strong>
                            <br>
                                    <select  id="s_type" name="s_type" ng-model="filterData.packed" class="selectpicker"  data-width="100%" >

                                      <option value="Y">Yes</option>
                                      <option value="N">No</option>

                                    </select>
                            </div>
                           
                        </td>
                        <td>
                          <div class="form-group" ><strong>AWB or SKU:</strong>
                            <br>
                                    <select  id="s_type" name="s_type" ng-model="filterData.s_type" class="selectpicker"  data-width="100%" >

                                      <option value="AWB">AWB</option>
                                      <option value="SKU">SKU</option>

                                    </select>
                            </div>
                           
                        </td>

                        

                      
                   
                        
                        
                     
                        <td>
                          <div class="form-group" ><strong>AWB or SKU value:</strong>
                            <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                           <!--  <?php// if($condition!=null):?>
                            <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition;?>" >
                          <?php// endif; ?> -->
                          </div>
                        </td>
                      
                       
                        
                        <td><button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                        <td><button  class="btn btn-danger" ng-click="loadMore(1,1);" >Search</button></td>
                       
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

<div class="panel-body" >


<div class="table-responsive" style="padding-bottom:20px;" >
<!--style="background-color: green;"-->
<table class="table table-striped table-hover table-bordered dataTable bg-*" id="downloadtable" style="width:100%;">
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
        <th>COD (SAR)</th>
           
      </tr>
    </thead></table></th>
    
     <th>Expire Details   <table class="table"><thead>
      <tr>
        <th>Shelve No</th>
        <th>Stock Location</th>
        <th>Expire Date)</th>
      </tr>
    </thead></table></th>
 <th>Pickup Status</th>  
         <th>Pickup Date</th>
     <th>Picker</th>  
          <th>Packed By</th>
           <th>Pack Date</th> 
         
          
<th>Seller</th>
<th>Date</th>

</tr>
</thead>
    <tr ng-if='shipData!=0' ng-repeat="data in shipData"> 
    
        <td>{{$index+1}}</td>
        <td>{{data.slip_no}}</td>
          <td>{{data.booking_id}}</td>
        <td>{{data.origin}}</td>
        <td>{{data.destination}}</td>
         <td>{{data.reciever_name}}</td>
          <td>{{data.reciever_address}}</td>
          <td>{{data.reciever_phone}}</td>
        <td>
            <table class="table table-striped table-hover table-bordered dataTable bg-*">
   
    <tbody>
      <tr ng-repeat="data1 in data.sku">
          <td ><span class="label label-primary">{{data1.sku}}</span></td>
        <td><span class="label label-info">{{data1.piece}}</span></td>
       
                <td><span class="label label-danger">{{data1.cod}}</span></td>
      </tr>
                </tbody>
            </table>
            
            </td>
            <td>
            <table class="table table-striped table-hover table-bordered dataTable bg-*">
   
    <tbody>
      <tr ng-repeat="data2 in data.expire_details">
          <td ><span class="label label-primary">{{data2.shelve_no}}</span></td>
        <td><span class="label label-info">{{data2.stock_location}}</span></td>
        <td><span class="label label-danger">{{data2.expity_date}}</span></td>
      </tr>
                </tbody>
            </table>
            
            </td>
        
       <td><span ng-if="data.pickup_status=='Yes'" class="label label-success">{{data.pickup_status}}</span>
          <span ng-if="data.pickup_status=='No'" class="label label-danger">{{data.pickup_status}}</span>
          </td>
            <td><span ng-if="data.pickup_status=='Yes'" class="label label-success">{{data.pickupDate}}</span>
          <span ng-if="data.pickup_status=='No'" class="label label-danger">N/A</span>
          </td>
           <td><span ng-if="data.assigned_to!=null" class="label label-success">{{data.assigned_to}}</span>
          <span ng-if="data.pickup_status==null" class="label label-danger">N/A</span>
          </td>
          
           <td><span ng-if="data.packedBy!=null" class="label label-success">{{data.packedBy}}</span>
          <span ng-if="data.packedBy==null" class="label label-danger">N/A</span>
          </td>
           <td><span ng-if="data.packedBy!=null" class="label label-success">{{data.packDate}}</span>
          <span ng-if="data.packedBy==null" class="label label-danger">N/A</span>
          </td>
        <td>{{data.sender_name}}</td>
        <td>{{data.entrydate}}</td>
    </tr>
    
</table>

    <button ng-hide="shipData.length==totalCount" class="btn btn-info" ng-click="loadMore(count=count+1,0);" ng-init="count=1">Load More</button>
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
    var todaysDate = 'Pickuplist Details <?= $pickupId?>'+ new Date();
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
<!-- /page container -->

</body>
</html>
