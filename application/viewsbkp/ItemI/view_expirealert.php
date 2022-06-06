<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<?php $this->load->view('include/file'); ?>
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/iteminventory.app.js"></script>
</head>

<body ng-app="Appiteminventory">
<?php


 $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CtrStockexpireAlert" ng-init="loadMore(1,0);"> 
  
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
		
if(!empty($this->session->flashdata('messarray')['expiredate']))
{
	echo '<div class="alert alert-warning">expire date not valid not valid '.implode(',',$this->session->flashdata('messarray')['expiredate']).' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
if(!empty($this->session->flashdata('messarray')['alreadylocation']))
{
	echo '<div class="alert alert-warning">Stock Location Already exists '.implode(',',$this->session->flashdata('messarray')['alreadylocation']).' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
if(!empty($this->session->flashdata('messarray')['seller_id']))
{
	echo '<div class="alert alert-warning">seller account ids not valid '.implode(',',$this->session->flashdata('messarray')['seller_id']).' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
if(!empty($this->session->flashdata('messarray')['validsku']))
{
	echo '<div class="alert alert-success">items has added '.implode(',',$this->session->flashdata('messarray')['validsku']).' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
if($this->session->flashdata('msg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'?>
        <!-- Dashboard content -->
        <div class="row" >
          <div class="col-lg-12" > 
            
            <!-- Marketing campaigns -->
            <div class="panel panel-flat" >
              <div class="panel-heading" dir="ltr">
                <h1><strong><?=lang('lang_Expirydate_alert');?></strong><a href="<?= base_url('Excel_export/shipments');?>"></a> <!--<a  ng-click="ExportExcelitemInventory();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>-->&nbsp;&nbsp; <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> </h1>
                
                <!-- <i class="icon-file-excel pull-right" style="font-size: 35px;"></i> --> 
              </div>
              
              <!-- Quick stats boxes -->
              <div class="panel-body">
                <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 
                  
                  <!-- Today's revenue --> 
                  
                  <!-- <div class="panel-body" style="background-color: pink;"> -->
                  
                  <table class="table table-bordered table-hover" style="width: 100%;">
                    <!-- width="170px;" height="200px;" -->
                    <tbody >
                      <tr style="width: 80%;">
                        <td><div class="form-group" ><strong><?=lang('lang_SKU');?>:</strong>
                            <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                          </div></td>
                      
                        <td ><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong> <br>
                            <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                              <option value=""><?=lang('lang_SelectSeller');?></option>
                              <?php foreach($sellers as $seller_detail):?>
                              <option value="<?= $seller_detail->id;?>">
                              <?= $seller_detail->name;?>
                              </option>
                              <?php endforeach;?>
                            </select>
                          </div></td>
                        <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                        <td><button  class="btn btn-danger" ng-click="loadMore(1,1);" ><?=lang('lang_Search');?></button></td>
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
          <!--style="padding-bottom:220px;background-color: lightgray;"--> 
          
          <!-- <div class="panel-heading"> --> 
          <!-- <h5 class="panel-title">Basic responsive table</h5> --> 
          <!-- <h1><strong>Shipments Table</strong> --><!-- <a href="<?//base_url('Excel_export/shipments');?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></hr> --> 
          
          <!-- <div class="heading-elements">
<ul class="icons-list">
  <li><a href="<?// base_url('Excel_export/shipments');?>"><i class="icon-file-excel"></i></a></li>
 --> 
          <!-- <li><a data-action="collapse"></a></li>
<li><a data-action="reload"></a></li> --> 
          <!-- <li><a data-action="close"></a></li> --> 
          <!-- </ul>
</div> --> 
          <!-- <hr> --> 
          <!-- </div> -->
          
          <div class="panel-body" > 
            <!-- <input type="text" value="{{data1.sku}}" id="check" style="display: none;" name="check" />
 -->
            
            <div class="table-responsive" style="padding-bottom:20px;" > 
              <!--style="background-color: green;"-->
              <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
                <thead>
                  <tr>
                  <th><?=lang('lang_SrNo');?>.</th>
                 
                   <th><?=lang('lang_Item_Type');?></th>
                  <th><?=lang('lang_ItemSku');?></th>
                  
                    <th><?=lang('lang_Quantity');?></th>
                    <th><?=lang('lang_Seller');?></th>
                 
                    <th><?=lang('lang_Expire_Status');?></th>
                    <th><?=lang('lang_Expire_Date');?></th>
                    <!--<th class="text-center" ><i class="icon-database-edit2"></i></th>--> 
                  </tr>
                </thead>
                <tbody id="">
                
                
                  <tr ng-if='shipData!=0' ng-repeat="data in shipData">
                    <td>{{$index+1}}  </td>
                 
                    <td><span class="badge badge-success" ng-if="data.item_type=='B2B'">{{data.item_type}}</span><span class="badge badge-warning" ng-if="data.item_type=='B2C'">{{data.item_type}}</span></td>
                    <td>{{data.sku}}</td>
                  
                   
                  
                    <td ng-if="data.checkreQty=='Y'"><span class="badge badge-success">{{data.quantity}}</span></td>
                    <td ng-if="data.checkreQty=='N'"><span class="badge badge-success">{{data.quantity}}</span></td>
                    <td>{{data.seller_name}}</td>
                   
                    <td ng-if='data.expiry=="Y"'><span class="badge badge-danger"><?=lang('lang_YES');?></span></td>
                    <td ng-if='data.expiry=="N"'><span class="badge badge-success"><?=lang('lang_NO');?></span></td>
                    <td>{{data.expity_date !== '0000-00-00' ? data.expity_date : "---"}}</td>
                  </tr>
                </tbody>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
   
    
      
        <h5 class="modal-title" id="exampleModalLabel"><?=lang('lang_UpdateLocation');?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form name="myform" novalidate ng-submit="myForm.$valid && GetupdatelocationData()" enctype="multipart/form-data" >
      <div class="modal-body">
     <select type="text" name="locationUp" id="locationUp" ng-model="UpdateData.locationUp" class="form-control" required>
     <option value="error"><?=lang('lang_SelectLocation');?></option>
     <option ng-repeat="data2 in locationData" value="{{data2.stock_location}}">{{data2.stock_location}}</option>
     </select>
      
     
      
    
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('lang_Close');?></button>
        <button type="button" class="btn btn-primary" ng-click="GetupdatelocationData();" ><?=lang('lang_Update');?></button>
      </div>
          </form>          
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
   
    
      
        <h5 class="modal-title" id="exampleModalLabel"><?=lang('lang_UpdateQTY');?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form name="myform" novalidate ng-submit="myForm.$valid && GetUpdateqtydata()" enctype="multipart/form-data" >
      <div class="modal-body">
     <span class="badge badge-success" title="Old QTY">{{QtyUpArray.quantity}}</span>
      <input type="number" class="form-control" required name="newqty" min='0' placeholder="<?=lang('lang_UpdateQTY');?>" ng-model="QtyUpArray.newqty">
      
     
      
    
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('lang_Close');?></button>
        <button type="button" class="btn btn-primary" ng-click="GetUpdateqtydata();" ><?=lang('lang_Update');?></button>
      </div>
          </form>          
    </div>
  </div>
</div>
<!-- /page container --> 
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
</body>
</html>
