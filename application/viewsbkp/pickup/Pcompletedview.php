<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>
            <?= lang('lang_Inventory'); ?>
        </title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/pickedSingle.app.js"></script>
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
    </head>

    <body ng-app="AppPickedPageSingle" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="PickedViewCtr"  ng-init="PickedCompeletedViewCtr();">

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>
                    <div class="">
                        <div class="page-header page-header-default">
                            <div class="page-header-content">
                                <div class="page-title">
                                    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">
                                            <?= lang('lang_Picking_Single_List'); ?> <?= lang('lang_Completed'); ?>
                                        </span> </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h5 class="panel-title"> 
                                        <?= lang('lang_Picking_Single_List'); ?> <?= lang('lang_Completed'); ?>
                                    </h5>
                                    <!--<div class="heading-elements">
                                                <ul class="icons-list">
                                                    <li><a data-action="collapse"></a></li>
                                                    <li><a data-action="reload"></a></li>
                                                    <li><a data-action="close"></a></li>
                                                </ul>
                                            </div>--> 
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="show_messanger_print1">
                                                    <thead>
                                                        <tr>
                                                            <th class="head1"><?= lang('lang_SrNo'); ?>
                                                                .</th>
                                                            <th class="head0"><?= lang('lang_Awb_No'); ?>
                                                                .</th>
                                                            <?php if ($this->session->userdata('user_details')['user_type'] != '4') { ?>
                                                                <th class="head1" align="center" style="text-align:center;"><?= lang('lang_SKU_Details'); ?>
                                                                    <table class="table table-striped table-bordered table-hover">
                                                                        <tr>
                                                                             <td><?= lang('lang_Item_Image'); ?></td>
                                                                            <td><?= lang('lang_SKU'); ?></td>
                                                                            <td><?= lang('lang_Pieces'); ?></td>
                                                                        </tr>
                                                                    </table></th>
                                                            <?php } ?>
                                                              <!--<th class="head1"><?= lang('lang_Location'); ?></th>-->
                                                            <?php if ($this->session->userdata('user_details')['user_type'] != '4') { ?>
                                                                <th class="head1"><?= lang('lang_Picker'); ?></th>
                                                            <?php } ?>
                                                            <th class="head1"><?= lang('lang_Status'); ?></th>
                                                            <th class="head1"><?= lang('lang_Action'); ?></th>

<!--                   	  <th class="head1">Remove</th>--> 
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr   ng-repeat="data in SingeListComArr|reverse ">
                                                            <td>{{$index + 1}}</td>
                                                            <td><span class="label label-primary">{{data.slip_no}}</span></td>
                                                            <?php if ($this->session->userdata('user_details')['user_type'] != '4') { ?>
                                                                <td><table class="table table-striped table-bordered table-hover">
                                                                        <tr ng-repeat="data2 in data.skuDetails">
                                                                             <td><img ng-if="data2.item_path!=''" src="<?=base_url();?>{{data2.item_path}}" width="80">
                    <img ng-if="data2.item_path==''" src="<?=base_url();?>assets/nfd.png" width="100">
                    </td>
                                                                            <td><span class="label label-info">{{data2.sku}}</span></td>
                                                                            <td><span class="label label-warning">{{data2.piece}}</span></td>
                                                                        </tr>
                                                                    </table></td>
                                                            <?php } ?>
                                                             <!-- <td>{{data.location}}</td>-->
                                                            <?php if ($this->session->userdata('user_details')['user_type'] != '4') { ?>
                                                                <td><span ng-if="data.assigned_to != 0">{{data.picker}}</span> <span ng-if="data.assigned_to == 0">N/A</span></td>
                                                            <?php } ?>
                                                            <td ng-if="data.picked_status == 'Y'"><span class="badge badge-success">
                                                                    <?= lang('lang_Yes'); ?>
                                                                </span></td>
                                                            <td ng-if="data.picked_status == 'N'"><span class="badge badge-danger">
                                                                    <?= lang('lang_NO'); ?>
                                                                </span></td>
                                                            <td ng-if="data.picked_status == 'N'"><a href="<?= base_url(); ?>pickedSingle/{{data.slip_no}}" class="btn btn-info">
                                                                    <?= lang('lang_Update'); ?>
                                                                </a></td>
                                                            <td ng-if="data.picked_status == 'Y'"><span class="badge badge-success">
                                                                    <?= lang('lang_Updated'); ?>
                                                                </span></td>

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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="test_print" ></div>
