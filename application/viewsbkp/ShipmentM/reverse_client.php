<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/courier_company.js"></script>
    </head>

    <body ng-app="CourierAppPage" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"  ng-controller="CourierComapnyCRL" ng-init="GetCompanylistDrop();"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  > 
                        <!--style="background-color: red;"-->


                    <div class="loader logloder" ng-show="loadershow"></div>
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong><?=lang('lang_Reverse_Shipment_Delivery_Station');?></strong> </h1>
                                    </div>
                                    <div class="panel-body">

                                        <div class="card-body">
                                            <div class="clearfix">

                                                <span class="badge badge badge-pill badge-danger" id="count_val"><?=lang('lang_Dublicates_Orders_are_automatically_removed');?></span>
                                                </br>
                                                <span class="badge badge badge-pill badge-danger mt-10" id="count_val"><?=lang('lang_you_can_reverse_twenty_shipments');?>
                                                </span> 
                                            </div>  

                                            <div class="row" style="margin-top:10px">
                                                <div class="col-md-12" ng-if="invalidSslip_no">
                                                    <div class="alert alert-warning" ng-if="invalidSslip_no" ng-repeat="in_data in invalidSslip_no"><?=lang('lang_Invalid_slip_no');?>. "{{in_data}}"</div>
                                                </div>
                                                <div class="col-md-12" ng-if="Success_msg">
                                                    <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?=lang('lang_Shipment_Forwarded');?></div>
                                                </div>
                                                <div class="col-md-12" ng-if="Error_msg">
                                                    <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                                                </div>




                                                <div class="col-md-12" ng-if="mainstatusEmpty">
                                                    <div class="alert alert-danger" ng-if="mainstatusEmpty">{{mainstatusEmpty}}</div>
                                                </div>
                                                <div class="col-md-12" ng-if="messArray1 != 0">
                                                    <div class="alert alert-danger" ng-repeat="mdata in messArray1"><?=lang('lang_wrong_AWB_no');?> {{mdata}}</div>    
                                                </div>




                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="8" id="show_awb_no" ng-change="scan_awb();"  required="" class="form-control" ng-model="userselected.slip_no"></textarea>
                                                </div>          
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">    
                                                    <div class="form-group" style="margin-top:30px">      
                                                        <a type="button"  class="btn btn-primary" style="margin-top: 0px;color:#fff"><?=lang('lang_Row_Count');?> <span class="" id="count_val"ng-if="scan.awbArray.length > 0"> ( {{scan.awbArray.length}} )</span>	
                                                            <span class="" id="count_val"></span>
                                                        </a>    
                                                    </div>
                                                </div>
                                                <div class="col-md-3">  
                                                    <div class="form-group">
                                                        <label><?=lang('lang_Select_Company');?> </label><br />

                                                        <select  class="select2  form-control" ng-model="userselected.cc_id" style="word-wrap: break-word; width:100% !important"   data-placeholder="Choose Company" required="">

                                                            <option ng-repeat="d_data in DeliveryDropArr" value="{{d_data.cc_id}}">{{d_data.company}}</option>
                                                        </select>  
                                                    </div>
                                                </div>
                                                <!--   <div class="col-md-3">  
                                                 <div class="form-group" ><strong>Pieces:</strong>
                                                    <input type="number" min="1" step="any"  id="box_pieces" ng-model="userselected.box_pieces"  class="form-control">
                                                    </div>                                                                    
                                                  </div>-->
                                                <div class="col-md-3">  
                                                    <div class="form-group">
                                                        <label><?=lang('lang_Comment');?></label><br />
                                                        <textarea rows="2" id="comment"  class="form-control" ng-model="userselected.comment"></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-2" style="margin-bottom: 11px;">   
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-primary form-control" ng-click="BulkForwardCompanyRev();"> <?=lang('lang_Submit');?></button>
                                                </div>
                                            </div>

                                        </div>


                                    </div>

                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /dashboard content --> 

                <!-- /basic responsive table --> 

            </div>
            <!-- /content area --> 
        </div>
        <?php $this->load->view('include/footer'); ?>

        <!-- /page container -->

    </body>
</html>
