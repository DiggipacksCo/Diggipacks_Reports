<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
        <script src='<?= base_url(); ?>assets/js/angular/cancel_order.app.js'></script>

    </head>

    <body ng-app="CancelApp" >
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
                                    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?=lang('lang_Return_To_Fulfilment');?></span> </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading" dir="ltr">
                                    <h5 class="panel-title"> <?=lang('lang_Order_Cancel');?><a href="<?= base_url(); ?>cancelOrder" class="btn btn-danger pull-right text-white mt-5"><?=lang('lang_Reset');?></a> </h5>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <param name="SRC" value="y" />
                                                    <div style="display:none">
                                                        <audio id="audio" controls>
                                                            <source src="<?= base_url('assets/apx_tone_alert_7.mp3'); ?>" type="audio/ogg">
                                                        </audio>
                                                        <audio id="audioSuccess" controls>
                                                            <source src="<?= base_url('assets/filling-your-inbox.mp3'); ?>" type="audio/ogg">
                                                        </audio>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" id="" my-enter="scan_awb();" ng-disabled="awbcolmunBtn" ng-model="scan.slip_no"class="form-control" placeHolder='AWB' />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <select class="form-control"ng-model="scan.charge_type" required>
                                                                <option value=""><?=lang('lang_Select_Cancel_Type');?></option>
                                                                <option value="Y"><?=lang('lang_With_Fees');?></option>
                                                                <option value="N"><?=lang('lang_Without_Fees');?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group"  ng-if="shipData.length > 2">
                                                            <input type="button" ng-click="finishScan();" ng-disabled="btnfinal" value='<?=lang('lang_Verify');?>'class="btn btn-danger" />
                                                        </div>
                                                        <div class="form-group" ng-if="shipData.length <= 2">
                                                            <input type="button" ng-click="finishScan();" ng-disabled="btnfinal" value='<?=lang('lang_Verify');?>'class="btn btn-primary" />
                                                        </div>
                                                    </div> 
                                                    
                                                    <div class="col-lg-12">
                                                        <div ng-if="shipData.length > 2" class="alert alert-danger"> <?=lang('lang_Please_Verify_the_Cancel_Order_Limit_Exceed');?>! </div>
                                                        <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                                                        <div ng-if='notice' class="alert alert-danger">{{notice}} </div>
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
                                        <div class="col-md-12" ng-show="tableshow">
                                            <div class="panel panel-default">
                                                <div class="panel-body">Sort</div>
                                            </div>
                                            <div class="table-responsive" style="padding-bottom:20px;" >
                                                <table class="table table-striped table-bordered table-hover" >
                                                    <thead>
                                                        <tr>
                                                            <th class="head1"><?=lang('lang_SrNo');?>.</th>
                                                            <th class="head0"><?=lang('lang_AWB');?></th>
                                                            <th class="head0"><?=lang('lang_Booking_ID');?></th>
                                                            <th class="head1"><?=lang('lang_Sender');?></th>
                                                            <th class="head0"><?=lang('lang_Receiver');?></th>
                                                            <th class="head1"><?=lang('lang_Origin');?></th>
                                                            <th class="head1"><?=lang('lang_Destination');?></th>
                                                            <th class="head1"><?=lang('lang_Receiver_Mobile');?></th>
                                                            <th class="head1"><?=lang('lang_Receiver_Address');?></th>
                                                            <th class="head1"><?=lang('lang_Seller');?></th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr   ng-repeat="data in shipData|reverse ">
                                                            <td>{{$index + 1}}</td>
                                                            <td><span class="label label-primary">{{data.slip_no}}</span></td>
                                                            <td>{{data.booking_id}}</td>
                                                            <td>{{data.sender_name}}</td>
                                                            <td>{{data.reciever_name}}</td>
                                                            <td>{{data.origin_name}}</td>
                                                            <td>{{data.destination_name}}</td>
                                                            <td>{{data.reciever_phone}}</td>
                                                            <td>{{data.reciever_address}}</td>
                                                            <td>{{data.cust_name}}</td>
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


