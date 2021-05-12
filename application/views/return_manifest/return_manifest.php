<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>

        <?php $this->load->view('include/file'); ?>



        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/manifest.app.js"></script>


    </head>

    <body ng-app="AppManifest" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="Ctrmanifest" ng-init="loadMore_return(1, 0);">

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

                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong>Return <?= lang('lang_Manifest_List'); ?></strong>

                                        </h1>
                                    </div>
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <form ng-submit="dataFilter();">

                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >
                                                        <tr style="width: 80%;">

                                                            <td>
                                                                <div class="form-group" ><strong><?= lang('lang_Sellers'); ?>:</strong>
                                                                    <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control"> 
                                                                        <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                                                        <option ng-repeat="sdata in sellers"  value="{{sdata.id}}">{{sdata.name}}</option>
                                                                    </select>

                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?= lang('lang_Drivers'); ?>:</strong>
                                                                    <select id="driverid" name="driverid" ng-model="filterData.driverid" class="form-control"> 
                                                                        <option value=""><?= lang('lang_Select_Driver'); ?></option>
                                                                        <option ng-repeat="x in assigndata"  value="{{x.id}}">{{x.username}}</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?= lang('lang_Manifest_ID'); ?>:</strong>
                                                                    <input type="text" id="manifestid" name="manifestid" ng-model="filterData.manifestid" class="form-control"> 
                                                                </div>
                                                            </td>
                                                            <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                            <td><button  class="btn btn-danger" ng-click="loadMore_return(1, 1);" ><?= lang('lang_Search'); ?></button></td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                        <thead>
                                            <tr><th><?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_Manifest_ID'); ?> </th>
                                                <th> <?= lang('lang_SKU'); ?></th>
                                                <th><?= lang('lang_Total_Qty'); ?></th>  
                                              
                                                <th><?= lang('lang_Drivers'); ?></th>   
                                                <th><?= lang('lang_TPL_company'); ?></th>  
                                                <th><?= lang('lang_TPL_AWB'); ?></th> 
                                                <th><?= lang('lang_City'); ?></th> 
                                                <th><?= lang('lang_Address'); ?></th> 
                                                <th><?= lang('lang_Seller'); ?></th>
                                             <th><?= lang('lang_Request_Date'); ?></th>
<!--                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>-->
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}}  </td>
                                            <td>{{data.uniqueid}}</td>
                                             <td>{{data.sku}}</td>
                                           <td width="200"><span class="badge badge-success" title="Total">{{data.qtyall}}</span> <!--&nbsp;<span class="badge badge-danger">2</span>--></td>
                                            
                                            <td >{{data.assign_to}}</td>
                                            <td >{{data.r_3pl_name}}</td>
                                            <td ><a  href="{{data.r_3pl_label}}" ng-if="data.r_3pl_label != ''"  target="_blank">{{data.r_3pl_awb}}</a>
                                                <a ng-if="data.r_3pl_label == ''">{{data.r_3pl_awb}}</a>
                                            </td>
                                            <td >{{data.city}}</td>
                                            <td >{{data.address}}</td>
                                            <td > {{data.seller_id}}</td>
                                         
                                            <td>{{data.req_date}}</td>
<!--                                            <td class="text-center"><ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li ><a href="manifestview/{{data.uniqueid}}/PS"><i class="icon-eye" ></i>Pending Sku</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td> -->
                                        </tr>
                                    </table>

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore_return(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="PopidreturnShowitem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">



                            <h5 class="modal-title" id="exampleModalLabel"><?= lang('lang_Return_Order'); ?> {{uniqueid}} </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-warning" ng-if="message.error" id="errhide">{{message.error}}</div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                            <div class="col-md-12" ng-if="invalidSslip_no">
                                <div class="alert alert-warning" ng-if="invalidSslip_no" ng-repeat="in_data in invalidSslip_no"><?= lang('lang_Invalid_slip_no'); ?>. "{{in_data}}"</div>
                            </div>
                            <div class="col-md-12" ng-if="Success_msg">
                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?= lang('lang_Shipment_Forwarded'); ?></div>
                            </div>
                            <div class="col-md-12" ng-if="Error_msg">
                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                            </div>




                            <div class="col-md-12" ng-if="mainstatusEmpty">
                                <div class="alert alert-danger" ng-if="mainstatusEmpty">{{mainstatusEmpty}}</div>
                            </div>
                            <div class="col-md-12" ng-if="messArray1 != 0">
                                <div class="alert alert-danger" ng-repeat="mdata in messArray1"> <?= lang('lang_wrong_AWB_no'); ?> {{mdata}}</div>    
                            </div>
                            <form novalidate ng-submit="myForm.$valid && createUser()" >
                                <input type="radio" name="assign_type" ng-model="returnUpdate.assign_type" ng-click="GetChangeAssignType('D');" value="D"  > <?= lang('lang_Drivers'); ?> <input type="radio" name="assign_type" value="CC" ng-model="AssignData.assign_type" ng-click="GetChangeAssignType('CC');"  > <?= lang('lang_Courier_Company'); ?>
                                <div ng-show="driverbtn">
                                    <select type="text"  ng-model="returnUpdate.assignid" class="form-control" required>
                                        <option ng-repeat="x in assigndata"  value="{{x.id}}">{{x.username}}</option>
                                    </select>
                                </div>
                                <div ng-show="crourierbtn">

                                    <select type="text"  ng-model="returnUpdate.cc_id" class="form-control" required>
                                        <option ng-repeat="cx in courierData"  value="{{cx.id}}">{{cx.company}}</option>
                                    </select>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('lang_Close'); ?></button>
                            <button type="button" ng-if="returnUpdate.assign_type == 'D'" class="btn btn-primary" ng-click="saveassigntodriver();" ><?= lang('lang_Update_Driver'); ?></button>
                            <button type="button" ng-if="returnUpdate.assign_type == 'CC'" class="btn btn-primary" ng-click="saveassigntodriver();" ><?= lang('lang_Send_Courier'); ?></button>
                        </div>
                        </form>          
                    </div>
                </div>
            </div>
    </body>
</html>
