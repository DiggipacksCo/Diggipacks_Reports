<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/iteminventory.app.js"></script>
</head>

<body ng-app="Appiteminventory">
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CtrStockTranferdlistpage" ng-init="loadMore(1,0);"> 
  
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
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

if($this->session->flashdata('error'))
echo '<div class="alert alert-warning">'.$this->session->flashdata('error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
?>
        <!-- Dashboard content -->
        <div class="row" >
          <div class="col-lg-12" > 
            
            <!-- Marketing campaigns -->
            <div class="panel panel-flat" >
              <div class="panel-heading">
                <h1><strong>Stock Transferred History</strong><!--<a href="<?= base_url('Excel_export/shipments');?>"></a> <a  ng-click="ExportExcelitemInventory();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>-->&nbsp;&nbsp; <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> </h1>
                
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
                        <td><div class="form-group" ><strong>SKU:</strong>
                            <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                          </div></td>
                        <td ><div class="form-group" ><strong>Quantity:</strong>
                            <input type="number" min="1" id="quantity"name="quantity"  ng-model="filterData.quantity" class="form-control" placeholder="Enter Quantity">
                          </div></td>
                        <td ><div class="form-group"><strong>From:</strong>
                            <input type="date" id="from"name="from" ng-model="filterData.from" class="form-control">
                          </div></td>
                        <td><div class="form-group" ><strong>To:</strong>
                            <input type="date" id="to"name="to" ng-model="filterData.to" class="form-control">
                          </div></td>
                      </tr>
                      <tr style="width: 80%;">
                        <td><div class="form-group" ><strong>Exact date:</strong>
                            <input type="date" id="exact"name="exact"  ng-model="filterData.exact" class="form-control">
                          </div></td>
                        <td ><div class="form-group" ><strong>Seller:</strong> <br>
                            <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                              <option value="">Select Seller</option>
                              <?php foreach($sellers as $seller_detail):?>
                              <option value="<?= $seller_detail->id;?>">
                              <?= $seller_detail->name;?>
                              </option>
                              <?php endforeach;?>
                            </select>
                          </div></td>
                        <td><button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                        <td><button  class="btn btn-danger" ng-click="loadMore(1,1);" >Search</button></td>
                        
                        <!--<td colspan="2">
                          <div class="form-group" style="background-color: pink;"><strong><p style="text-align: center;" id="result"><?php //echo "Total ".count($items)." entries";?></p></strong>
                          style="background-color: pink;width: 80%;" 
                                 
                            </div>
                        </td>--> 
                        
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
                    <th>Sr.No.</th>
                
                    <th>From Item Sku</th>
                    <th>From Seller</th>
                     <th>To Item Sku</th>
                    <th>To Seller</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    
                  <th>Tranfer By</th>
                    <th>Date</th>
                   
                 
                  </tr>
                </thead>
                <tbody id="">
                  <tr ng-if='shipData!=0' ng-repeat="data in shipData">
                    <td>{{$index+1}} </td>
                
                   <td>{{data.fitem_sku}}</td>
                    <td>{{data.from_id}}</td>
                    <td>{{data.sku}}</td>
                  
                    <td>{{data.to_id}}</td>
                  
                   
                    <td><span class="badge badge-warning">{{data.qty}}</span></td>
                    <td>{{data.location_st}}</td>
                  
                 
                   <td ng-if="data.type!='deducted'">{{data.username}}</td>
                  
                    <td>{{data.entry_date}}</td>
                   
                  
                  
                  </tr>
                </tbody>
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
