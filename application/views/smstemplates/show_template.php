<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>-->
        <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />-->  
    </head>

    <body >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-app="templateApp" ng-controller="templatesCtrl"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper">
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" ng-init="showStatusDrop();">
                        <div class="panel panel-flat">
                            <div class="panel-heading">

                                <h1 class="hk-sec-title"><?=lang('lang_Show_Tempalte');?></h1>     
                            </div>
                        </div>

                        <div class="panel panel-flat">
                            <div class="panel-body" ng-init="getSmslist(1, 0);">
                                <div class="row" style="margin-top:10px">  
                                    <div class="table-responsive">
                                        <table class="table datatable-show-all table-bordered table-hover datatable-highlight">
                                            <thead>



                                                <tr>
                                                    <th class="head1" colspan="3">
                                                        <select  name="main_status"  ng-change="subStatus();"  class="selectpicker"   data-show-subtext="true" data-live-search="true" required  data-width="100%"   ng-model="filterData.status_name"  >

                                                            <option ng-repeat="st1 in statuslist" value="{{st1.id}}">{{st1.main_status}}</option>
                                                        </select>
                                                        <!--<input type="text" ng-model="filterData.status_name"  class="form-control" placeholder="Search Status" required="required">-->

                                                    </th>
                                                    <th class="head1" colspan="3">
                                                        <input type="submit" name="Search" class="btn btn-primary" value="<?=lang('lang_Search');?>" ng-click="getSmslist(1, 1);">        </th>  

                                                </tr> 


                                            </thead>
                                        </table>
                                        <table class="table datatable-show-all table-bordered table-hover datatable-highlight">

                                            <thead>
                                                <tr>
                                                    <th class="head0"><?=lang('lang_SrNo');?></th>  
                                                    <th class="head1"><?=lang('lang_Status');?></th>                            	
                                                    <th class="head1"><?=lang('lang_Sub_Status');?></th>
                                                    <th class="head1"><?=lang('lang_Arabic_SMS');?></th>
                                                    <th class="head0"><?=lang('lang_Activity_On_Off');?></th>
                                                    <th class="head1"><?=lang('lang_English_Sms');?></th>
                                                    <th class="head0"><?=lang('lang_Activity');?></th>
                                                    <th class="head1"><?=lang('lang_Action');?></th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr ng-repeat="data in SmslistArray">
                                                    <td>{{$index + 1}}</td>
                                                    <td>{{data.main_status}}</td>
                                                    <td>{{data.sub_status}}</td>  
                                                    <td>{{data.arabic_sms}}</td>
                                                    <td style="text-align:center;"><a  style="cursor: pointer" ng-click="ShowactiveStatus(data.id, 'N')" ng-if="data.arabic_status == 'Y'" ng-confirm-click="Do you want to Off?">&nbsp; <span class="badge badge-primary badge-pill mt-15 mr-10">On<span></a> 
                                                                    <a  style="cursor: pointer" ng-click="ShowactiveStatus(data.id, 'Y')" ng-if="data.arabic_status == 'N'" ng-confirm-click="Do you want to On?">&nbsp;<span class="badge badge-danger badge-pill mt-15 mr-10">Off</span></a></td>
                                                                    <td>{{data.english_sms}}</td>  
                                                                    <td style="text-align:center;"><a  style="cursor: pointer" ng-click="UpdateEnglishStatus(data.id, 'N')" ng-if="data.english_status == 'Y'" ng-confirm-click="Do you want to Off?">&nbsp; <span class="badge badge-primary badge-pill mt-15 mr-10">On<span></a> 
                                                                                    <a  style="cursor: pointer" ng-click="UpdateEnglishStatus(data.id, 'Y')" ng-if="data.english_status == 'N'" ng-confirm-click="Do you want to On?">&nbsp;<span class="badge badge-danger badge-pill mt-15 mr-10">Off</span></a></td>


                                                                                    <td>
                                                                                        <div class="btn-group">
<!--                                                                                            <button type="button" class="btn btn-basic" style="background-color:grey !important"><i class="icon-list"></i></button>   
                                                                                            <button type="button" class="btn btn-basic dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:grey !important">
                                                                                                <span class="sr-only">Toggle Dropdown</span>  
                                                                                            </button>-->

                                                                                            <ul class="icons-list">
                                                                                                <li class="dropdown"> 
                                                                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                                                                                        <i class="icon-menu9"></i> 
                                                                                                    </a>
                                                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                                                        <li ><a href="<?= base_url(); ?>edit_template/{{data.id}}" ><i class="icon-eye" ></i> <?=lang('lang_Edit');?></a></li>
                                                                                                        <li ><a href="javascript://" ng-click="GetNotifydelete(data.id)" ng-confirm-click="Do you want to Delete?"><i class="icon-trash" ></i> <?=lang('lang_Delete');?></a></li>


                                                                                                    <!-- <li><a ng-click="updatemanifeststatus_notfound(data.id,data.uniqueid,data.sid,data.qty);"  ><i class="icon-pencil7"></i> Update Not Found</a></li>-->

                                                                                                    </ul>
                                                                                                </li>
                                                                                            </ul>


                                                                                           
                                                                                        </div>
                                                                                    </td>

                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td ng-if="SmslistArray.length == 0" colspan="8" align="center" style="text-align:center;">
                                                                                           <?=lang('No_Record_Found');?>
                                                                                        </td> 

                                                                                    </tr>  

                                                                                    </tbody>
                                                                                    </table>
                                                                                    <button ng-hide="SmslistArray.length == totalCount" class="btn btn-info" ng-click="getSmslist(count = count + 1, 0);" ng-init="count = 1">Load</button>
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
