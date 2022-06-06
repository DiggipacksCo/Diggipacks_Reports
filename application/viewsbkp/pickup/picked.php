<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<?php $this->load->view('include/file'); ?>
<script src="<?=base_url();?>assets/js/angular/picked.app.js"></script>
<script src='https://code.responsivevoice.org/responsivevoice.js'></script>

</head>

<body ng-app="AppPickedPage" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="PickedViewCtr" ng-init="PickerShipmentList('<?=$pickupId;?>');">

<!-- Page content -->
<div class="page-content">
<?php $this->load->view('include/main_sidebar'); ?>

<!-- Main content -->
<div class="content-wrapper" >
<!--style="background-color: black;"-->
<?php $this->load->view('include/page_header'); ?>
<div class=""  >
  <input type="text" name="destination[]" ng-model="inputValue" style="display:none"/>
  <div class="page-header page-header-default">
    <div class="page-header-content">
      <div class="page-title">
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?=lang('lang_Picking');?></span> </h4>
      </div>
    </div>
  </div>
  
  <!-- Content area -->
  <div class="">
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title"><?=lang('lang_Order');?> <?=lang('lang_Picking');?></h5>
          <a  href="<?=base_url();?>pickedBatchView" class="btn btn-danger pull-right"><?=lang('lang_Back');?> To <?=lang('lang_List');?></a><br>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <param name="SRC" value="y" />
                <div style="display:none">
                  <audio id="audio" controls>
                    <source src="<?= base_url('assets/apx_tone_alert_7.mp3');?>" type="audio/ogg">
                  </audio>
                  <audio id="audioSuccess" controls>
                    <source src="<?= base_url('assets/filling-your-inbox.mp3');?>" type="audio/ogg">
                  </audio>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <input type="text" id="" my-enter="scan_awb();" ng-model="scan.slip_no"class="form-control" placeHolder='SKU' />
                  </div>
                </div>
                  <div class="col-md-3">
               
                 
                   <div class="form-group">
             
                      
                    <input type="button" ng-disabled="btnfinal"  ng-click="finishScan();" value='<?=lang('lang_Verify');?>'class="btn btn-primary" />
                  </div>
                  </div>
                <div class="col-lg-12">
                  <div ng-if="completeArray.length>500" class="alert alert-danger"><?=lang('lang_Please_Verify_the_Packing_Limit_Exceed');?>! </div>
                  <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                  <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group"> &nbsp; </div>
                </div>
              </div>
              <div>&nbsp;</div>
              <div>&nbsp;</div>
            </div>
            <!--contenttitle--> 
          </div>
          <div class="col-lg-12">
          <div >
            <div class="panel panel-default">
              <div class="panel-body"><?=lang('lang_Sort');?></div>
            </div>
              <div class="table-responsive">
            <table class="table-striped table-bordered table-hover" role="table" id="show_messanger_print1">
              <thead role="rowgroup">
                <tr role="row">
                  <th class="head1" role="columnheader" >#</th>
                  
                 
                  <th class="head1" role="columnheader">SKU</th>
                  <th class="head1" role="columnheader"><?=lang('lang_Ref_No');?>.</th>
                  <th class="head0" role="columnheader"><?=lang('lang_Total');?></th>
                  <th class="head1" role="columnheader"><?=lang('lang_Scaned');?></th>
                 <!-- <th class="head0">Location</th>-->
                   <!--<th class="head0">Picker</th>-->
                  
                 
                  <!--                   	  <th class="head1">Remove</th>--> 
                </tr>
              </thead>
             <tbody role="rowgroup" >
                <tr   ng-repeat="data in shipData|reverse " role="row">
                    <td role="cell" class="sr_id">{{$index+1}}</td>
               
                 <td role="cell"><span class="label label-info">{{data.sku_view}}</span></td>
                <td role="cell">
                <table role="table">
                <tr ng-repeat="data1 in data.slip_details " role="row">
                
                <td role="cell"> {{data1}}</td>
                
                </tr>

                </table>
               
                
                <td ng-if="data.piece>data.scaned" role="cell"  ><span class="badge badge badge-pill badge-info" >{{data.piece}}</span></td>
                <td ng-if="data.piece==data.scaned" role="cell"  ><span class="badge badge badge-pill badge-success" >{{data.piece}}</span></td>
                <td ng-if="data.piece>data.scaned" role="cell" ><span class="badge badge badge-pill badge-danger" >{{data.scaned}}</span></td>
                <td ng-if="data.piece==data.scaned" role="cell" ><span class="badge badge badge-pill badge-success" >{{data.scaned}}</span></td>
                </td>
                
                 
                  
                <!--  <td ><span class="badge badge badge-pill badge-info" >{{data.location}}</span></td>-->
                 <!-- <td ><span class="badge badge badge-pill badge-info" >{{data.picker}}</span></td>-->
                
                </tr>
              </tbody>
            </table>
            </div>
          </div>
            <div>&nbsp;</div>
          <div >
            <div class="panel panel-default">
              <div class="panel-body"><?=lang('lang_Completed');?></div>
            </div>
              <div class="table-responsive">
            <table class="table-striped table-bordered table-hover" id="show_messanger_print">
              <thead>
                <tr>
                  <th class="head1">#</th>
                  <th class="head0"><?=lang('lang_Sort');?>.</th>
                </tr>
              </thead>
              <tbody>
                <tr   ng-repeat="data in completeArray">
                  <td>{{$index+1}}</td>
                  <td>{{data.slip_no}}</td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="test_print" ></div>
<style>
    
    table { 
  width: 100%; 
  border-collapse: collapse; 
}
/* Zebra striping */
tr:nth-of-type(odd) { 
/*  background: #eee; */
}
th { 
/*  background: #333; */
/*  color: white; */
  font-weight: bold; 
}
td, th { 
  padding: 10px; 
  border: 1px solid #ccc; 
  text-align: left; 
}
@media
	  only screen 
    and (max-width: 760px), (min-device-width: 768px) 
    and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}

    tr {
      margin: 0 0 1rem 0;
    }
      
    tr:nth-child(odd) {
      background: #ccc;
    }
    
		td {
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50%;
		}

		td:before {
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 0;
			left: 6px;
			width: 35%;
			padding-right: 10px;
			white-space: nowrap;
		}

	
	    
                .sr_id:nth-of-type(1):before { content: "#"; }
	
         td:nth-of-type(2):before { content: "SKU"; }
         td:nth-of-type(3):before { content: "Ref. No."; }
	td:nth-of-type(4):before { content: "Total"; }
        td:nth-of-type(5):before { content: "Scaned"; }
        
	
	}
</style>
