<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
      <title><?= lang('lang_Inventory'); ?></title>
      <?php $this->load->view('include/file'); ?>
      <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
      <script src='<?=base_url();?>assets/js/angular/returnlm.app.js'></script>
      <style>
         /* Hiding the checkbox, but allowing it to be focused */
         .badgebox {
         opacity: 0;
         }
         .badgebox + .badge {
         /* Move the check mark away when unchecked */
         text-indent: -999999px;
         /* Makes the badge's width stay the same checked and unchecked */
         width: 27px;
         }
         .badgebox:focus + .badge {
         /* Set something to make the badge looks focused */
         /* This really depends on the application, in my case it was: */
         /* Adding a light border */
         box-shadow: inset 0px 0px 5px;/* Taking the difference out of the padding */
         }
         .badgebox:checked + .badge {
         /* Move the check mark back when checked */
         text-indent: 0;
         }
         .checkbox label:after, .radio label:after {
         content: '';
         display: table;
         clear: both;
         }
         .checkbox .cr, .radio .cr {
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
         .checkbox .cr .cr-icon, .radio .cr .cr-icon {
         position: absolute;
         font-size: .8em;
         line-height: 0;
         top: 50%;
         left: 20%;
         }
         .radio .cr .cr-icon {
         margin-left: 0.04em;
         }
         .checkbox label input[type="checkbox"], .radio label input[type="radio"] {
         display: none;
         }
         .checkbox label input[type="checkbox"] + .cr > .cr-icon, .radio label input[type="radio"] + .cr > .cr-icon {
         transform: scale(3) rotateZ(-20deg);
         opacity: 0;
         transition: all .3s ease-in;
         }
         .checkbox label input[type="checkbox"]:checked + .cr > .cr-icon, .radio label input[type="radio"]:checked + .cr > .cr-icon {
         transform: scale(1) rotateZ(0deg);
         opacity: 1;
         }
         .checkbox label input[type="checkbox"]:disabled + .cr, .radio label input[type="radio"]:disabled + .cr {
         opacity: .5;
         }
         .radio label, .checkbox label {
         padding-left: 0px !important;
         }
         .radio label, .checkbox label {
         padding-left: 0px !important;
         }
         .select2-container--open.select2-container--above .select2-selection--single, .select2-container--open.select2-container--above .select2-selection--multiple {
         border-bottom-color: #ddd !important;
         }
         .select2-selection--multiple:not([class*=bg-]):not([class*=border-]) {
         border-color: transparent;
         border-bottom-color: #ddd;
         }
      </style>
   </head>
   <body ng-app="ReturnLmApp" >
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
                  <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?= lang('lang_Return_To_Fulfilment'); ?></span> </h4>
               </div>
            </div>
         </div>
         <!-- Content area -->
         <div class="">
            <div class="panel panel-flat">
               <div class="panel-heading">
                  <h5 class="panel-title" dir="ltr"><?= lang('lang_Return_To_Fulfilment'); ?> <a href="<?=base_url();?>returnLM" class="btn btn-danger pull-right text-white mt-5"><?= lang('lang_Cancel'); ?></a> </h5>
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
                                    <input type="text" id="" my-enter="scan_awb();" ng-disabled="awbcolmunBtn" ng-model="scan.slip_no"class="form-control" placeHolder='<?= lang('lang_AWB'); ?>'' />
                                 </div>
                              </div>
                              <div class="col-md-3">
                                 <div class="form-group">
                                    <input type="text" id="scan_awb" my-enter="packuShip()" ng-model="scan.sku" class="form-control" placeHolder='<?= lang('lang_SKU'); ?>' />
                                 </div>
                              </div>
                              <div class="col-md-2">
                                 <div class="form-group"  ng-if="completeArray.length>2 ">
                                    <input type="button" ng-click="finishScan();" ng-disabled="btnfinal" value='<?= lang('lang_Verify'); ?>''class="btn btn-danger" />
                                 </div>
                                 <div class="form-group" ng-if="completeArray.length<=2">
                                    <input type="button" ng-click="finishScan();" ng-disabled="btnfinal" value='<?= lang('lang_Verify'); ?>''class="btn btn-primary" />
                                 </div>
                              </div>
                              <!-- -->
                              <table ng-show="boxshow1"  class="table table-striped table-bordered table-hover">
                              <tr>     
                              <th> <?= lang('lang_Seller'); ?> </th>                                              
                                        <th><?= lang('lang_SKU'); ?>  </th>                                        
                                        <th><?= lang('lang_QTY'); ?></th>
                                        <th><?= lang('lang_Capacity'); ?></th>
                                        <th><?= lang('lang_Shelve_No'); ?></th>
                                        <th><?= lang('lang_Stock_Location'); ?></th> 
                                    </tr>
                              <tr ng-repeat="stockdat in LocalItem" > 
                              <td><span class="label label-primary">{{stockdat.seller}}</span> </td>
                               <td><span class="label label-primary">{{stockdat.sku}}</span> </td>
                               <td><span class="label label-info">{{stockdat.piece}}</span></td>
                               <td><span class="label label-info">{{stockdat.sku_size}}</span></td>
                               <td><div ng-repeat="sct1 in stockdat.local" > 
                             
                               
                               <div ng-repeat="sct11 in sct1" ng-init="id_value=0">   
                             
                                   <input type="text" class="form-control" my-enter="setFocus($parent.$parent.$index+$parent.$index+$index,'sh')"  id="sh_{{$parent.$parent.$index+$parent.$index+$index}}"ng-model="LocalItem[$parent.$parent.$index].local[$parent.$index][$index].shelve_no"/>
                                 </div> 
                                  </td>
                               <!-- <td ng-repeat ="sct in stockdat.local"><span class="label label-info" >{{sct.id}}</span></td> -->
                                <td>
                                <div ng-repeat="sct122 in stockdat.local"> 
                                <div ng-repeat="sct in sct122" >    
                                   <input type="text" class="form-control" my-enter="setFocus($parent.$parent.$index+$parent.$index+$index,'st')"  id="st_{{$parent.$parent.$index+$parent.$index+$index}}" ng-model="LocalItem[$parent.$parent.$index].local[$parent.$index][$index].stock_location"/>
                                  
                                </div>
                               </td> 
                                     </tr>
                                                                                   
                              </table>





                              <p ng-show="boxshow1">&nbsp;</p>
                              <div class="col-md-3" ng-show="boxshow1">
                                 <div class="form-group">
                                    <label for="primary" class="btn btn-primary"><?= lang('lang_Damage'); ?>/<?= lang('lang_Missing'); ?>
                                    <input type="checkbox" ng-model="specialtype.specialpack" id="primary" class="badgebox" name="specialpack" ng-change="Getallspecialpackstatus(specialtype.specialpack);">
                                    <span class="badge">&check;</span></label>
                                 </div>
                              </div>
                              <div class="col-md-3" ng-if="specialtype.specialpack">
                                 <div class="form-group">
                                    <div class="radio">
                                       <label style="font-size: 2em">
                                       <input type="radio" name="specialpacktype" ng-model="specialtype.specialpacktype" ng-change="Getallspecialpackstatus(specialtype.specialpacktype);" value="Damage" checked>
                                       <span class="cr"><i class="cr-icon fa fa-circle"></i></span> <?= lang('lang_Damage'); ?> </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-2" ng-if="specialtype.specialpack">
                                 <div class="form-group">
                                    <div class="radio">
                                       <label style="font-size: 2em">
                                       <input type="radio" ng-model="specialtype.specialpacktype" ng-change="Getallspecialpackstatus(specialtype.specialpacktype);"  name="specialpacktype" value="Mising">
                                       <span class="cr"><i class="cr-icon fa fa-circle"></i></span>  <?= lang('lang_Missing'); ?></label>
                                    </div>
                                 </div>
                              </div>
                              <!--
                                 <div class="col-md-3" >  <div class="form-group"> <input class="btn btn-info" type="button" onclick="create_zip();" value="Export Completed" ></div></div>
                                  <div class="col-md-3" >  <div class="form-group"><input class="btn btn-info" type="button" onclick="create_zip1();" value="Export ALL" ></div></div>
                                 -->
                              <div class="col-lg-12">
                                 <div ng-if="completeArray.length>2" class="alert alert-danger"> <?= lang('lang_Please_Verify_the_Packing_Limit_Exceed'); ?>! </div>
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
                     <div class="col-md-7" ng-show="tableshow">
                        <div class="panel panel-default">
                           <div class="panel-body">Sort</div>
                        </div>
                        <table class="table table-striped table-bordered table-hover" id="show_messanger_print1">
                           <thead>
                              <tr>
                                 <th class="head1">Sr.No.</th>
                                 <th class="head0">Awb.</th>
                                 <th class="head0">Item Image</th>
                                 <th class="head1">SKU.</th>
                                 <th class="head0">Total</th>
                                 <th class="head1">Scaned</th>
                                 <!--<th class="head0">Extra</th>--> 
                                 <!--                       <th class="head1">Remove</th>--> 
                              </tr>
                           </thead>
                           <tbody>
                              <tr   ng-repeat="data in shipData|reverse ">
                                 <td>{{$index+1}}</td>
                                 <td><span class="label label-primary">{{data.slip_no}}</span></td>
                                 <td><img ng-if="data.item_path!=''" src="<?=base_url();?>{{data.item_path}}" width="80">
                                    <img ng-if="data.item_path==''" src="<?=base_url();?>assets/nfd.png" width="100">
                                 </td>
                                 <td><span class="label label-warning">{{data.sku}}</span></td>
                                 <td ng-if="data.piece>data.scaned"><span class="badge badge badge-pill badge-info" >{{data.piece}}</span></td>
                                 <td ng-if="data.piece==data.scaned"><span class="badge badge badge-pill badge-success" >{{data.piece}}</span></td>
                                 <td ng-if="data.piece>data.scaned"><span class="badge badge badge-pill badge-danger" >{{data.scaned}}</span></td>
                                 <td ng-if="data.piece==data.scaned"><span class="badge badge badge-pill badge-success" >{{data.scaned}}</span></td>
                                 <td ng-if="data.extra>0"><span class="badge badge badge-pill badge-danger" >{{data.extra}}</span></td>
                                 <!--<td ng-if="data.extra==0" ><span class="badge badge badge-pill badge-warning" >{{data.extra}}</span></td>--> 
                                 <!--
                                    <td><a ng-click="removeData(data.slip_no)">
                                    <span class="glyphicon glyphicon-remove"></span>
                                    </a></td>
                                    --> 
                              </tr>
                           </tbody>
                        </table>
                     </div>
                     <div class="col-md-5" ng-show="tableshow">
                        <div class="panel panel-default">
                           <div class="panel-body">Completed</div>
                        </div>
                        <table class="table table-striped table-bordered table-hover" id="show_messanger_print">
                           <thead>
                              <tr>
                                 <th class="head1">Sr.No.</th>
                                 <th class="head0">AWB.</th>
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
      <div id="test_print" ></div>
      <script> $(".js-example-basic-multiple").select2();</script>