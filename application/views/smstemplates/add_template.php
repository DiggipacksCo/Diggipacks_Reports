<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
       
       
    </head>

    <body >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-app="templateApp" ng-controller="templatesCtrl"> 

            <!-- Page content -->
            <div class="page-content" ng-init="showStatusDrop();">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper">
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h1><strong><?=lang('lang_Add_Template');?></strong></h1>
                            </div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>
                                <div class="col-md-8">
                                <form class="stdform col-md-12" name="add_sms" enctype="multipart/form-data">
                                    <div class="form-group"  >
                                        <label><?=lang('lang_Status');?></label>
                                        <span id="heading_id" class=""></span> 
                                        <select  name="main_status"  ng-change="subStatus();"  class="form-control"   data-show-subtext="true" data-live-search="true" required  data-width="100%"   ng-model="templateArray.status_id"  >

                                            <option ng-repeat="st1 in statuslist" value="{{st1.id}}">{{st1.main_status}}</option>
                                        </select>


                                        <span class="text-danger" ng-show="add_sms.status_name.$error.required"><?=lang('lang_Status_Required');?></span>
                                    </div>   
                                    <div class="form-group" ng-if="Substatuslist">   
                                        <label><?=lang('lang_Sub_Status');?></label><span id="heading_id" class=""></span>

                                        <select    name="sub_status" class="form-control" ng-model="templateArray.sub_status" Required >

                                            <option ng-repeat="st in Substatuslist" value="{{st.id}}">{{st.sub_status}}</option>
                                        </select>
                                        <span class="text-danger" ng-show="add_sms.sub_status.$error.required"><?=lang('lang_Sub_Status_Required');?></span>
                                    </div>

                                   <div class="form-group">
                                        <label><?=lang('lang_Arabic_SMS');?></label>
                                        <span id="content_id" class=""></span>
                                        <textarea class="form-control mt-15" my-text=""  name="arabic_sms" rows="6" placeholder="Textarea" ng-model="templateArray.arabic_sms" Required></textarea>
                                        <span class="text-danger" ng-show="add_sms.arabic_sms.$error.required"><?=lang('lang_Arabic_Sms_Required');?></span>
                                    </div>
                                    <div class="form-group">
                                        <label><?=lang('lang_Activity');?></label><br>
                                        <?=lang('lang_Yes');?>   <input type="radio" id="arabic_status" name="arabic_status" ng-model="templateArray.arabic_status" value="Y" ng-checked="false"/>&nbsp;&nbsp;
                                        <?=lang('lang_No');?>   <input type="radio" id="arabic_status" name="arabic_status" ng-model="templateArray.arabic_status" value="N" ng-checked="false"/>  
                                    </div>    
                               <!--       <div class="form-group">
                                        <label>English Sms</label>
                                        <span id="content_id" class=""></span>
                                        <textarea class="form-control mt-15" my-text="" name="english_sms" rows="6" placeholder="Textarea" ng-model="templateArray.english_sms" Required></textarea>
                                        <span class="text-danger" ng-show="add_sms.english_sms.$error.required">English Sms Required}</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Activity</label><br>
                                        Yes     <input type="radio" id="english_status" name="english_status" ng-model="templateArray.english_status" value="Y" ng-checked="false"/>&nbsp;&nbsp;
                                        No   <input type="radio" id="english_status" name="english_status" ng-model="templateArray.english_status" value="N" ng-checked="false"/>
                                    </div>  -->

                                    <input name="id" type="hidden" value="">
                                    <input name="submit" type="submit" class="btn btn-primary" value="Submit" ng-disabled="add_sms.$invalid" ng-click="AddTemplateform(templateArray);"> 


                                </form>  
                                </div>
                                
                                <div class="col-md-4">
									
								<div class="list-group">
								<a href="#" class="list-group-item active"><?=lang('lang_Variables');?></a>
								<a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param('AWB_NO');">AWB_NO</span></h4> (#AWB Number of shipment.)</a>
								<!-- <a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param('SCHEDULE_URL')";>SCHEDULE_URL</span></h4> (URL to schedule shipment by sms.)</a> -->
								<!-- <a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param('FEED_BACK_URL')";>FEED_BACK_URL</span></h4> (Feedback URL.)</a> -->
								<a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param( 'CUSTOMER_NAME')";>CUSTOMER_NAME</span></h4> (Name of receiver.)</a>
                                <a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param( 'TRACKING_URL')";>TRACKING_URL</span></h4> (TRACKING URL.)</a>
                                <a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param( '3PL_COMPANY')";>3PL_COMPANY</span></h4> (3PL COMPANY.)</a>
								<a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param('SENDER_NAME')";>SENDER_NAME</span></h4> (Name of sender.)</a>
								<!-- <a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param('DRIVER_MOBILE')";>DRIVER_MOBILE</span></h4> (#Driver Mobile No..)</a> -->
								<!-- <a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param('DRIVER_NAME')";>DRIVER_NAME</span></h4> (#Driver Name.)</a> -->
								<a href="#" class="list-group-item"> <h4><span class="badge badge-info" ng-click="add_param('CUST_CARE_MOBILE')";>CUST_CARE_MOBILE</span></h4> (#Customer care  No..)</a>

  									</div>
									</div>
                            </div>
                        </div>
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area --> 

                </div>
                <!-- /main content --> 

            </div>
            <!-- /page content --> 

        </div>
        <!-- /page container --> 
        <!--/script> --> 
       
       
        
    </body>
</html>
