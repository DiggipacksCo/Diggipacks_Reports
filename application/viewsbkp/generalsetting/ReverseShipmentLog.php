<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?=base_url();?>assets/js/angular/generalSetting.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>

    <body ng-app="log" >
        <?php $this->load->view('include/main_navbar'); ?>
        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_log_view" ng-init="loadReverseShipment(1, 0);" >
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
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>
                        <div class="loader logloder" ng-show="loadershow"></div>
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong>Reverse <?=lang('lang_Forward_Log');?></strong>
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                        <!-- Quick stats boxes -->
                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                <!-- <div class="panel-body" > -->
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_AWB');?> :</strong>
                                                        <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.slip_no"  class="form-control" placeholder="Enter AWB no.">
                                                        <!--  <?php // if($condition!=null):      ?>
                                                         <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition; ?>" >
                                                        <?php // endif;  ?> -->
                                                    </div></div>
                                                <div class="col-md-3">  <div class="form-group" ><strong><?=lang('lang_Status');?>:</strong>
                                                        <br>
                                                        <select  id="status" name="status" ng-model="filterData.status" class="selectpicker"  data-show-subtext="true" data-live-search="true" data-width="100%" >
                                                            <option value=""><?=lang('lang_Select_Status');?></option>
                                                            <option value="Success"><?=lang('lang_Success');?></option>     
                                                            <option value="Fail"><?=lang('lang_Fail');?></option>     
                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_company');?>:</strong>
                                                        <br>
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_Select_Company');?></option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                </div> 
                                            </div>
                                                <div class="col-md-5"><div class="form-group" >
                                                        <button  class="btn btn-danger" ng-click="loadReverseShipment(1, 1);" ><?=lang('lang_Search');?></button>
                                                        <button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                </div>
                                            </div>
                                                <div id="today-revenue"></div>
                                                <!-- </div> panel-body-->
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
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?=lang('lang_AWB_No');?>.</th>
                                                <th>Forwarded <?=lang('lang_AWB_No');?>.</th>
                                                <th><?=lang('lang_Status');?></th>
                                                <th><?=lang('lang_company');?></th>
                                                <th><?=lang('lang_Entry_Date');?></th>
                                                <th><?=lang('lang_Log');?></th> 
                                                <th>Request</th>
                                            </tr>  
                                        </thead>  
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 
                                            <td>{{$index + 1}} 
                                            <td>{{data.slip_no}}</td>
                                            <td>{{data.frwd_slip_no}}</td>
                                            <td>{{data.status}}</td>
                                            <td>{{data.cc_name}}</td>
                                            <td>{{data.update_date}}</td>
                                            <td>
                                            <i class="fa fa-clipboard" data-ng-click="copyHrefToClipboard(data.log)"></i>    
                                            <br>
                                           <code> {{data.log | truncate}}</code>
                                           <textArea style="display:none">{{data.log}}</textArea>
                                        </td>
                                            <td> <i class="fa fa-clipboard" data-ng-click="copyHrefToClipboard(data.request)"></i>
                                            <br>
                                            <code>{{data.request | truncate}}}</code> <textArea style="display:none">{{data.request}}</textArea></td>
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadReverseShipment(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></button>
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
        </div>
    </body>
</html>