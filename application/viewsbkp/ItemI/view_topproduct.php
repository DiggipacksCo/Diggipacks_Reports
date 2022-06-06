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
<div class="page-container" ng-controller="Ctrtopdispatchpro" ng-init="loadMore(1,0);"> 
  
  <!-- Page content -->
  <div class="page-content">
    <?php $this->load->view('include/main_sidebar'); ?>
    
    <!-- Main content -->
    <div class="content-wrapper" > 
      <!--style="background-color: black;"-->
      <?php $this->load->view('include/page_header'); ?>
      
      <!-- Content area -->
      <div class="content" > 
    
        <div class="row" >
          <div class="col-lg-12" > 
            
            <!-- Marketing campaigns -->
            <div class="panel panel-flat" >
              <div class="panel-heading"dir="ltr">
                <h1><strong><?=lang('lang_Top_Product_Dispatch');?></strong><a href="<?= base_url('Excel_export/shipments');?>"></a> <!--<a  ng-click="ExportExcelitemInventory();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>-->&nbsp;&nbsp; <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> </h1>
                
                <!-- <i class="icon-file-excel pull-right" style="font-size: 35px;"></i> --> 
              </div>
              
              <!-- Quick stats boxes -->
              <div class="panel-body">
                <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 
                  
                  <!-- Today's revenue --> 
                  
                  <!-- <div class="panel-body" style="background-color: pink;"> -->
                   <?php
                              
                                                    $cutoff = 2020;
                                                    $now = date('Y');

                                                    // build years menu
                                                    $yearDrop .= '<select name="year" class="form-control" ng-model="filterData.year"><option value="">Year</option>' . PHP_EOL;
                                                    for ($y = $now; $y >= $cutoff; $y--) {

                                                        if ($postData['year'] == $y)
                                                            $yearDrop .= '  <option value="' . $y . '" selected>' . $y . '</option>' . PHP_EOL;
                                                        else
                                                            $yearDrop .= '  <option value="' . $y . '">' . $y . '</option>' . PHP_EOL;
                                                    }
                                                    $yearDrop .= '</select>' . PHP_EOL;

                                                    // build months menu
                                                    $monthDrop .= '<select name="month" class="form-control" ng-model="filterData.month">'
                                                            . '<option value="">Month</option>' . PHP_EOL;

                                                    for ($im = 1; $im <= 12; $im++) {
                                                        $timestamp = mktime(0, 0, 0, $im);
                                                        $label = date("F", $timestamp);
                                                        if ($postData['month'] == $im)
                                                            $monthDrop .= '<option value="' . $im . '" selected>' . $label . '</option>' . PHP_EOL;
                                                        else
                                                            $monthDrop .= '<option value="' . $im . '">' . $label . '</option>' . PHP_EOL;
                                                    }



                                                    $monthDrop .= '</select>' . PHP_EOL;

                                              
                                                    ?>
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
                              <?= $seller_detail->company;?>
                              </option>
                              <?php endforeach;?>
                            </select>
                          </div></td>
                          
                            <td><div class="form-group" ><strong><?=lang('lang_Year');?>:</strong>
                          <?=$yearDrop;?>
                          </div></td>
                            <td><div class="form-group" ><strong><?=lang('lang_Month');?>:</strong>
                            <?=$monthDrop;?>
                          </div></td>
                      </tr>
                      <tr>
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
         
          
          <div class="panel-body" > 
          
            <div class="table-responsive" style="padding-bottom:20px;" > 
              <!--style="background-color: green;"-->
              <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
                <thead>
                  <tr>
                    <th><?=lang('lang_SrNo');?>.</th>
                 
                     <th><?=lang('lang_Seller');?></th>
                    <th><?=lang('lang_ItemSku');?></th>
                  
                    <th><?=lang('lang_Quantity');?></th>
                    
                    <!--<th class="text-center" ><i class="icon-database-edit2"></i></th>--> 
                  </tr>
                </thead>
                <tbody id="">
                
                
                  <tr ng-if='shipData!=0' ng-repeat="data in shipData">
                    <td>{{$index+1}}  </td>
                 
                   
                    <td>{{data.company}}</td>
                  
                    <td ><span class="badge badge-warning">{{data.sku}}</span></td>
                  
                    <td ><span class="badge badge-success">{{data.tqty}}</span></td>
                  
                   
                   
                   
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

  
</div>

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
