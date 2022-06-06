<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
<?php $this->load->view('include/file'); ?>

<script src='https://code.responsivevoice.org/responsivevoice.js'></script>
<script src='<?=base_url();?>assets/js/angular/business.app.js'></script>


<style>
/* Hiding the checkbox, but allowing it to be focused */
.badgebox
{
    opacity: 0;
}

.badgebox + .badge
{
    /* Move the check mark away when unchecked */
    text-indent: -999999px;
    /* Makes the badge's width stay the same checked and unchecked */
	width: 27px;
}

.badgebox:focus + .badge
{
    /* Set something to make the badge looks focused */
    /* This really depends on the application, in my case it was: */
    
    /* Adding a light border */
    box-shadow: inset 0px 0px 5px;
    /* Taking the difference out of the padding */
}

.badgebox:checked + .badge
{
    /* Move the check mark back when checked */
	text-indent: 0;
}
.checkbox label:after, 
.radio label:after {
    content: '';
    display: table;
    clear: both;
}

.checkbox .cr,
.radio .cr {
    position: relative;
    display: inline-block;
    border: 1px solid #a9a9a9;
    border-radius: .25em;
    width: 1.3em;
    height: 1.3em;
    float: left;
    margin-right: .5em;
}

.radio .cr {
    border-radius: 50%;
}

.checkbox .cr .cr-icon,
.radio .cr .cr-icon {
    position: absolute;
    font-size: .8em;
    line-height: 0;
    top: 50%;
    left: 20%;
}

.radio .cr .cr-icon {
    margin-left: 0.04em;
}

.checkbox label input[type="checkbox"],
.radio label input[type="radio"] {
    display: none;
}

.checkbox label input[type="checkbox"] + .cr > .cr-icon,
.radio label input[type="radio"] + .cr > .cr-icon {
    transform: scale(3) rotateZ(-20deg);
    opacity: 0;
    transition: all .3s ease-in;
}

.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
.radio label input[type="radio"]:checked + .cr > .cr-icon {
    transform: scale(1) rotateZ(0deg);
    opacity: 1;
}

.checkbox label input[type="checkbox"]:disabled + .cr,
.radio label input[type="radio"]:disabled + .cr {
    opacity: .5;
}
.radio label, .checkbox label {
  
     padding-left: 0px !important; 
   
}
.radio label, .checkbox label {
     padding-left: 0px !important; 
}
</style>
</head>

<body ng-app="BusinessApp" >

<?php $this->load->view('include/main_navbar'); ?>


<!-- Page container -->
<div class="page-container" ng-controller="scanShipment">

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
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?=lang('lang_Packaging');?></span> </h4>
      </div>
    </div>
 </div>
  
  <!-- Content area -->
 <div class="">
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title"><?=lang('lang_Order_Packaging');?> B2B</h5>
        
      </div>
      <div class="panel-body">
        <div class="row">
            
            
             <div class="col-md-12" ng-show="PrintBtnallAwb">
                      <div class="panel panel-default">
                          <div class="panel-body">
                              <form action="<?=base_url();?>PickUp/BulkPrintAllLabels_p" target="_blank" method="post">
                              <input type="hidden" name="PrintAllAWB" value="{{awbArray_print}}">
                              
                              <input type="submit" class="btn btn-primary pull-right" value="Print All AWB" >
                             </form></div>
</div>
                 
           </div>   
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
                   
                         
                 
               <div class="col-md-2">
                  <div class="form-group">
                 
                      
                      <input type="text" id="" my-enter="scan_awb();" ng-disabled="AwbnoButton" ng-model="scan.slip_no"class="form-control" placeHolder='AWB' />
                  </div>
                </div>
           <div class="col-md-1" ng-show="GetremoveBtn" >
               <div class="form-group"><a class="" ng-click="GetremoveShipemtData(scan.slip_no);"><i class="far fa-times-circle fa-3x" style="color:red;"></i></a></div></div>
              <div class="col-md-2">
                  <div class="form-group">
                 
                      <input type="text" id="scan_awb" my-enter="packuShip('SK')" ng-disabled="skunoButton" ng-model="scan.sku" class="form-control" placeHolder='SKU' />
                  </div>
                </div>
            <div class="col-md-1">
                  <div class="form-group">
                 
                    <input type="text" id="scan_awb_p" my-enter="packuShip('PC')" ng-model="scan.pieces" class="form-control" placeHolder='Pieces' />
                  </div>
                </div>
           
            <div class="col-md-1">
                  <div class="form-group">
                 
                      <input type="text"   ng-model="scan_new.box_no" ng-blur="GetCheckBoxNo();" class="form-control" placeHolder='Box No.' />
                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                 
                      <input type="float"   ng-model="scan_new.weight" class="form-control" placeHolder='weight' />
                  </div>
                </div>
               <div class="col-md-3">
                  <div class="form-group"  ng-if="completeArray.length>20 ">
                    <input type="button" ng-click="finishScan();" value='<?=lang('lang_Verify');?> 'class="btn btn-danger" />
                  </div>
                   <div class="form-group" ng-if="completeArray.length<=20">
             
                      
                    <input type="button" ng-click="finishScan();" value='<?=lang('lang_Verify');?> 'class="btn btn-primary" />
                  </div>
                  
                  
                </div> 
           <div class="col-md-12">
            <div class="form-group"> <label for="primary" class="btn btn-primary"><?=lang('lang_Special_Packing');?> <input type="checkbox" ng-model="specialtype.specialpack" id="primary" class="badgebox" name="specialpack" ng-change="Getallspecialpackstatus(specialtype.specialpack);"><span class="badge">&check;</span></label></div>
           </div>
            <div class="col-md-3" ng-if="specialtype.specialpack">
            <div class="form-group">   <div class="radio">
            <label style="font-size: 2em">
                <input type="radio" name="specialpacktype" ng-model="specialtype.specialpacktype" ng-change="Getallspecialpackstatus(specialtype.specialpacktype);" value="warehouse" checked>
                <span class="cr"><i class="cr-icon fa fa-circle"></i></span>
                <?=lang('lang_warehouse');?>
            </label>
             
        </div></div>
        
           </div>
           <div class="col-md-2" ng-if="specialtype.specialpack">
            <div class="form-group">  
             <div class="radio">
            <label style="font-size: 2em"><input type="radio" ng-model="specialtype.specialpacktype" ng-change="Getallspecialpackstatus(specialtype.specialpacktype);"  name="specialpacktype" value="seller">
                <span class="cr"><i class="cr-icon fa fa-circle"></i></span>
                <?=lang('lang_Seller');?>
            </label>
       
        </div></div>
           </div>
     <div class="col-lg-12">
                   
                <div ng-if="completeArray.length>20" class="alert alert-danger"><?=lang('lang_Please_Verify_the_Packing_Limit_Exceed');?> ! </div>
                  <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                  <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                </div>
            <div class="col-md-3">
                  <div class="form-group"> &nbsp; </div>
                </div></div>
               
                <div>&nbsp;</div>
                <div>&nbsp;</div>
             
             </div> 
              <!--contenttitle--> 
             </div>
             
             <div class="col-md-6">
             <div class="panel panel-default">
  <div class="panel-body"><?=lang('lang_Sort');?></div>
</div>
                   <div class="table-responsive" style="padding-bottom:20px;" >
              <table class="table table-striped table-bordered table-hover" id="show_messanger_print1">
                <thead>
                  <tr>
                  <th class="head1"><?=lang('lang_SrNo');?>.</th>
                  <th class="head0"><?=lang('lang_AWB');?>.</th>
                  <th class="head1"><?=lang('lang_SKU');?>.</th>
                  <th class="head0"><?=lang('lang_Total');?></th> 
                  <th class="head1"><?=lang('lang_Scaned');?></th>
<!--                  <th class="head0"><?=lang('lang_Extra');?></th>-->
                    
<!--                   	  <th class="head1">Remove</th>-->
                  </tr>
                </thead>
                <tbody>
                
               
                <tr   ng-repeat="data in shipData|reverse ">
                  <td>{{$index+1}}</td>
                    <td><span class="label label-primary">{{data.slip_no}}</span></td>
                    <td><span class="label label-warning">{{data.sku}}</span></td>
                    <!--ng-if="data.piece>data.scaned"-->
                   <td   >  <span class="badge badge badge-pill badge-info" >{{data.piece}}</span></td> 
                    <!---->
                    <td   ng-if="data.piece==data.scaned">  <span class="badge badge badge-pill badge-success" >{{data.scaned}}</span></td>
                      <!---->
                       <td  ng-if="data.piece>data.scaned"><span class="badge badge badge-pill badge-danger" >{{data.scaned}}</span></td>
                    <td  ng-if="data.piece<data.scaned"><span class="badge badge badge-pill badge-danger" >{{data.scaned}}</span></td>
                      <!--data.piece==data.scaned-->
<!--                     <td ng-if="data.piece==data.scaned" ><span class="badge badge badge-pill badge-success" >{{data.scaned}}</span></td>-->
<!--                     <td ng-if="data.extra>0" ><span class="badge badge badge-pill badge-danger" >{{data.extra}}</span></td>-->
<!--                      <td ng-if="data.extra==0" ><span class="badge badge badge-pill badge-warning" >{{data.extra}}</span></td>-->
                 
<!--
                    <td><a ng-click="removeData(data.slip_no)">
          <span class="glyphicon glyphicon-remove"></span>
        </a></td>
-->
                </tr>
               
                  </tbody>
              </table>
            </div>
                 </div>
             <div class="col-md-6">
                <div class="panel panel-default">
  <div class="panel-body"><?=lang('lang_Completed');?></div>
</div><div class="table-responsive" style="padding-bottom:20px;" >
              <table class="table table-striped table-bordered table-hover" id="show_messanger_print">
                <thead>
                  <tr>
                     <th class="head1"><?=lang('lang_SrNo');?>.</th>  
                    <th class="head0"><?=lang('lang_AWB');?>.</th>
                     <th class="head0">3PL</th>
                      <th class="head0"><?=lang('lang_TPL_AWB');?>.</th>
                     <th class="head1"><?=lang('lang_Print');?></th>
                   
                   
                   
                  </tr>
                </thead>
                <tbody>
                
               
                <tr   ng-repeat="data in completeArray">
                  <td>{{$index+1}}</td>
                  
                   
                  <td>{{data.slip_no}}</td>
                  <td>{{data.frwd_company_id}}</td>
                  <td>{{data.frwd_company_awb}}</td>
                  <td><a class="btn btn-success" href="{{data.print_url}}" target="_blank">Print {{data.buttontype}}</a></td>
                  </tr>
               
                  </tbody>
              </table>
     </div>
            </div>
            
            <div>&nbsp;</div>
             <div class="col-lg-12">
                 <div class="row">
                     
                     <div class="col-md-2" style="padding:15px;" ng-repeat="md_data in SKuMediaArr|reverse" ng-if="SKuMediaArr!=''">
                       <div style="display:inline-block; border:solid 1px #808080; padding:15px" id="GetSkuId{{$index}}">
                <div>
                    <img class="img-responsive" alt="{{md_data.sku}}" src="{{md_data.item_path}}" width="100" />
                    <br />
                    <h2 class="pull-right">{{md_data.piece}}</h2>
                    <h3> <?=lang('lang_QTY');?>: </h3>
                     <h2><a href="#">{{md_data.sku}}</a></h2>
                    <br />
                    <p class="text-justify"></p>
                </div>

            </div>
        </div>
                    
                   
</div>
           
                  </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div id="test_print" ></div>


         