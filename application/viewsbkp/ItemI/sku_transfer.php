<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/iteminventory.app.js"></script>
</head>

<body ng-app='Appiteminventory'>
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="Ctrstocktranfer"> 
  
  <!-- Page content -->
  <div class="page-content" ng-init="GetSellerDropDataPage();">
    <?php $this->load->view('include/main_sidebar'); ?>
    
    <!-- Main content -->
    <div class="content-wrapper">
      <?php $this->load->view('include/page_header'); ?>
      
      <!-- Content area -->
      <div class="content">
        <div class="panel panel-flat">
          <div class="panel-heading">
            <h1><strong>Sku Transfer</strong></h1>
          </div>
          <hr>
          
          <div class="panel-body">
          
           <span class="error">{{showqtyerror2}}</span>
            <form  method="post">
            <input type="hidden" name="qtycout" id="qtycout" ng-model="showform.qtycout">
              <fieldset class="scheduler-border">
                <legend class="scheduler-border">From Seller</legend>
                 <div class="form-group">
                <label for="from_id"><strong>Seller:</strong></label>
                <select name="from_id" id="from_id"  class="js-example-basic-multiple bootstrap-select form-control" ng-model="filterData.from_id" ng-change="GetSellerDropDataPageTo(filterData.from_id);"  data-width="100%" required>
                  <option value="">Please select Seller</option>
                  <option ng-repeat="fdata in SellerArr" value="{{fdata.id}}">{{fdata.name}}</option>
                </select>
              </div>
              <div class="form-group">
                <label for="item_sku"><strong>Sku:</strong></label>
                <select name="fitem_sku" id="fi_id" ng-model="filterData.fi_id"  class="js-example-basic-multiple bootstrap-select form-control" data-width="100%" required>
                  <option value="">Select Sku</option>
                  <option ng-repeat="sdata in fromSkuDetails" value="{{sdata.id}}">{{sdata.sku}}({{sdata.quantity}}/{{sdata.stock_location}})</option>
                </select>
              </div>
                </fieldset>
                
              <fieldset class="scheduler-border" >
                <legend class="scheduler-border">To Seller</legend>
                 <div class="form-group">
                <label for="to_id"><strong>Seller#:</strong></label>
                <select name="to_id" id="to_id" class="js-example-basic-multiple bootstrap-select form-control" ng-model="filterData.to_id" ng-change="GetSellerStockLocation(filterData.to_id);" data-width="100%" required>
                  <option value="">Please select seller</option>
                  <option ng-repeat="tdata in SellerArrTO" value="{{tdata.id}}">{{tdata.name}}</option>
                </select>
               
              </div>
              <div class="form-group">
                <label for="item_sku"><strong>Sku:</strong></label>
                <select name="titem_sku" id="titem_sku" ng-model="filterData.titem_sku"   class="js-example-basic-multiple bootstrap-select form-control" data-width="100%" required>
                  <option value="">Select Sku</option>
                  <option ng-repeat="sdata in ToskuArr" value="{{sdata.item_sku}}">{{sdata.sku}}</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1"><strong>Quantity:</strong></label>
                <input  type="number" class="form-control" name='qty' ng-model="filterData.qty" ng-blur="GettosellerStockLOcationhow()" value="1" min="1" id="qty"  required>
                  <span class="error">{{showqtyerror}}</span>
              </div>
              <div class="form-group">
                <label for="location_st"><strong>Stock Location:</strong></label>
                <select name="location_st" id="location_st" ng-model="filterData.location_st"   class="js-example-basic-multiple bootstrap-select form-control" multiple  data-width="100%" required>
                  <option value="">Select Stock Location</option>
                  <option ng-repeat="data in StockLOcation" value="{{data.stock_location}}">{{data.stock_location}}</option>
                </select>
                 <span class="error">{{CountLocationerr}}</span>
              </div>
                </fieldset>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border">Other Details</legend>
                 
              <div class="form-group">
<label for="expity_date"><strong>Expire Date:</strong></label>
<input  type="text" class="form-control" ng-model="filterData.expity_date" name='expity_date'  id="expity_date1"  >
</div>
                </fieldset>
              
             
                <div class="form-group" ng-if="filterData.to_id && filterData.from_id && filterData.qty && filterData.titem_sku">
              <button type="button" class="btn btn-success" ng-click="GetUpdateStockQty();">Transfer</button>
              </div>
            </form>
          </div>
        </div>
        <?php $this->load->view('include/footer'); ?>
      </div>
      <!-- /content area --> 
      
    </div>
    <!-- /main content --> 
    
  </div>
  <!-- /page content --> 
  
</div>
<!-- /page container --> 



  <script> $(".js-example-basic-multiple").select2();</script>
  <style>
fieldset.scheduler-border {
	border: 1px groove #ddd !important;
	padding: 0 1.4em 1.4em 1.4em !important;
	margin: 0 0 1.5em 0 !important;
	-webkit-box-shadow:  0px 0px 0px 0px #000;
	box-shadow:  0px 0px 0px 0px #000;
}
legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width:auto;
	padding:0 10px;
	border-bottom:none;
}
</style>
</body>
</html>
