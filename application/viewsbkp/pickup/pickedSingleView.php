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
             <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>

    <body ng-app="AppPickedPageSingle" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="PickedViewCtr"  ng-init="Getlistviewpickingview(1,0);">

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
                                            <?= lang('lang_Picking_Single_List'); ?>
                                        </span> </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        <?= lang('lang_Picking_Single_List'); ?>
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
                                    
                                       <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->
                                                <div class="col-md-3">
                                            <div class="form-group" ><strong><?= lang('lang_AWB_No'); ?>:</strong>
                                                        <input type="text" id="slip_no"name="slip_no" ng-model="filterdata.slip_no" class="form-control" placeholder="Enter AWB No."> 

                                                    </div>
                                                </div>
                                        <div class="col-md-3"> <div class="form-group" ><strong><?= lang('lang_From'); ?>:</strong>
                                                        <input class="form-control date" placeholder="From" id="from" name="from" ng-model="filterdata.from">

                                                    </div></div>
                                        <div class="col-md-3"><div class="form-group" ><strong><?= lang('lang_To'); ?>:</strong>
                                                        <input class="form-control date" placeholder="To" id="to"name="to"  ng-model="filterdata.to" class="form-control"> 

                                                    </div></div>
<!--                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>PickUp ID</strong>
                                                        <input type="text" id="pickupId" name="pickupId" ng-model="filterdata.pickupId" class="form-control" placeholder="Enter PickUp ID"> 

                                                    </div>
                                                </div>-->
                                             
                                               
                                                
                                           
                                                <div class="col-md-3">
                                            <div class="form-group" ><strong><?= lang('lang_Picker'); ?>:</strong>
                                                      
                                                        <select  id="assigned_to" name="assigned_to"  ng-model="filterdata.assigned_to"  class="form-control" data-width="100%" >
                                                    <option value=""><?= lang('lang_Select_Picker'); ?></option>
                                                            
                                                            <option ng-repeat="Pdat in pickerArray" value="{{Pdat.id}}">
                                                             {{Pdat.name}}
                                                                </option>
                                                           
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="form-group" >
                                                       
                                                        <button  class="btn btn-danger" ng-click="Getlistviewpickingview(1, 1);" ><?= lang('lang_Search'); ?></button>
                                                    
                                                    
                                                    </div></div>






                                            </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="" width="100%" id="show_messanger_print1" role="table">
                                                    <thead role="rowgroup">
                                                        <tr role="row">
                                                            <th class="head1" role="columnheader"><?= lang('lang_SrNo'); ?>.</th>
                                                            <th class="head0" role="columnheader"><?= lang('lang_Awb_No'); ?>.</th>
                                                            <?php if ($this->session->userdata('user_details')['user_type'] != '4') { ?>
                                                                <th class="head1"  role="columnheader"align="center" style="text-align:center;"><?= lang('lang_SKU_Details'); ?>
                                                                    <table class="table table-striped table-bordered table-hover" >
                                                                        <tr>
                                                                            <td><?= lang('lang_Item_Image'); ?></td>
                                                                            <td><?= lang('lang_SKU'); ?></td>
                                                                            <td><?= lang('lang_Pieces'); ?></td>
                                                                        </tr>
                                                                    </table></th>
                                                            <?php } ?>
                                                              <!--<th class="head1"><?= lang('lang_Location'); ?></th>-->
                                                            <?php if ($this->session->userdata('user_details')['user_type'] != '4') { ?>
                                                                <th role="columnheader" class="head1"><?= lang('lang_Picker'); ?></th>
                                                            <?php } ?>
                                                            <th role="columnheader" class="head1"><?= lang('lang_Status'); ?></th>
                                                            <th role="columnheader" class="head1"><?= lang('lang_Action'); ?></th>

<!--                   	  <th class="head1">Remove</th>--> 
                                                        </tr>
                                                    </thead>
                                                    <tbody role="rowgroup">
                                                        <tr   ng-repeat="data in SingeListArr|reverse " role="row">
                                                            <td role="cell">{{$index + 1}}</td>
                                                            <td role="cell"><span class="label label-primary">{{data.slip_no}}</span></td>
                                                            <?php if ($this->session->userdata('user_details')['user_type'] != '4') { ?>
                                                                <td role="cell"><table class="table table-striped table-bordered table-hover">
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
                                                                <td role="cell"><span ng-if="data.assigned_to != 0">{{data.picker}}</span> <span ng-if="data.assigned_to == 0">N/A</span></td>
                                                            <?php } ?>
                                                            <td role="cell" ng-if="data.picked_status == 'Y'"><span class="badge badge-success">
                                                                    <?= lang('lang_Yes'); ?>
                                                                </span></td>
                                                            <td ng-if="data.picked_status == 'N'"><span class="badge badge-danger">
                                                                    <?= lang('lang_NO'); ?>
                                                                </span></td>
                                                            <td role="cell" ng-if="data.picked_status == 'N'"><a href="<?= base_url(); ?>pickedSingle/{{data.slip_no}}" class="btn btn-info">
                                                                    <?= lang('lang_Update'); ?>
                                                                </a></td>
                                                            <td role="cell" ng-if="data.picked_status == 'Y'"><span class="badge badge-success">
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

                            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
                                padding: -1px !important;
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
                                width: 45%;
                                padding-right: 10px;
                                white-space: nowrap;
                            }

                            /*
                            Label the data
                You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
                            */
                            td:nth-of-type(1):before { content: "Sr.No."; }
                            td:nth-of-type(2):before { content: "Awb No."; }
                            td:nth-of-type(3):before { content: "Status"; }
                            td:nth-of-type(4):before { content: "Action"; }
                        }
                    </style>
                    
                    <script type="text/javascript">

                                    $('.date').datepicker({

                            format: 'yyyy-mm-dd'

                            });


        </script>
