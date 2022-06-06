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
</head>

<body ng-app="Appfinance" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CTR_setuserrate" ng-init="getallsellerdynamictype();">

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
<div class="panel-heading">
  <h1> <strong><?=lang('lang_Set_FinanceRate');?> </strong> 
    <!--  <a  ng-click="exportmanifestlist();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>--> 
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
          <td><div class="form-group" ><strong><?=lang('lang_Sellers');?>:</strong>
              <select id="seller_id"name="seller_id" ng-model="sellerdata.seller_id" class="form-control">
                <option value=""><?=lang('lang_SelectSeller');?></option>
                <option ng-repeat="sdata in sellerdata"  value="{{sdata.id}}">{{sdata.company}}</option>
              </select>
            </div>
          </td>
           <!--  <td>
              <input type ="radio" class="btn btn-danger" ng-click="" > Fix Rate &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type ="radio" class="btn btn-danger" ng-click="" > Dynamic 
            </td> -->
            <td>
              <button  class="btn btn-danger" ng-click="getallsetratedata();" ><?=lang('lang_Get_Details');?></button> 
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
      <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
        <thead>
          <tr>
            
           
          <th><?=lang('lang_Category_Name');?></th>
             <th><?=lang('lang_Description');?></th>
             <th><?=lang('lang_Rates');?></th>
            
            
          </tr>
        </thead>
        
        <tr ng-if='TypesData!=0' ng-repeat="data in TypesData">
        <!--   <td>{{data.name}}<strong>({{data.type}})</strong></td> -->
         <td>{{data.name}}</td>
           <td>{{data.description}}</td>
          
          <td ng-if="data.name=='Outbound Charge' || data.name=='Return' || data.name=='Pickup Charge box'"> <input type="text" name="setrate"  class="form-control" ng-model="TypesData[$index].rates"  style="width:40%; float:left;"><input type="text" name="setpiece"  class="form-control" ng-model="TypesData[$index].setpiece" placeholder="pieces" style="width:40%;"></td>
           <td ng-if="data.name!='Outbound Charge' && data.name!='Return'  && data.name!='Pickup Charge box'"> <input type="text" name="setrate"  class="form-control" ng-model="TypesData[$index].rates"   ></td>
          
         
        </tr>
        <tr><td colspan="4" align="right"> <button  class="btn btn-info" ng-show = "IsVisible" ng-click="getUpdateratesdata();"><?=lang('lang_Update');?></button></td></tr>
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

</body>
</html>
