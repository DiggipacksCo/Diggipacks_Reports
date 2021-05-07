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
        <div class="page-container" ng-controller="Ctrmanifest" ng-init="loadMore(1, 0,'no');staffpage='yes'">

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
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong><?= lang('lang_Manifest_List'); ?></strong>
                          <!--                  <a  ng-click="exportmanifestlist();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>-->
                                          <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>-->
                                        </h1>
                                    </div>
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments');     ?>" -->
                         <!-- href="<? //base_url('Pdf_export/all_report_view');     ?>" -->
                                        <!-- Quick stats boxes -->
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
                                                             <td>
                                                                <div class="form-group mt-10" >
                                                        <select class="form-control"  ng-model="filterData.sort_list" ng-change="loadMore(1, 1,'no');">

                                                            <option value="">Short List</option>


                                                            <option value="NO">Newest Order</option>
                                                            <option value="OLD">Oldest Order</option>
                                                          
                                                            

                                                        </select>

                                                   </div>
                                                            </td>


                                                            <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                            <td><button  class="btn btn-danger" ng-click="loadMore(1, 1,'no');" ><?= lang('lang_Search'); ?></button></td>


                                                        </tr>


                                                    </tbody>
                                                </table>
                                                <br>



                                                <div id="today-revenue"></div>
                                                <!-- </div> panel-body-->

                                                <!-- /today's revenue -->

                                            </div>



                                        </div>

                                        <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>


                        <div class="panel panel-flat" >

                            <div class="panel-body" >


                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_Manifest_ID'); ?> </th>
                                                <!--<th>SKU</th>-->
                                                <th><?= lang('lang_Total_Qty'); ?></th>  
                                                <th><?= lang('lang_Completed_Qty'); ?></th>  
                                                <th><?= lang('lang_Pending_Qty'); ?></th>  
                                                <th>Driver</th>   
                                                <th>3PL Company</th>  
                                                <th>3PL AWB</th> 
                                                <th>City</th> 
                                                <th>Address</th> 
                                            <!--<th>Status</th>
                                            <th>Code</th>-->
                                                 <th>Vehicle Type</th>
                                                <th><?= lang('lang_Seller'); ?></th>
                                                <th><?= lang('lang_On_Hold'); ?></th>
                                                <th><?= lang('lang_Inventory_Updated'); ?></th>
                                                <th><?= lang('lang_Request_Date'); ?></th>




                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}}  </td>
                                            <td>{{data.uniqueid}}</td>
                                           <!-- <td><span class="badge badge-info">{{data.sku}}</span></td>-->
                                            <td width="200"><span class="badge badge-success" title="Total">{{data.qtyall}}</span> <!--&nbsp;<span class="badge badge-danger">2</span>--></td>
                                             <td width="200"> <span class="badge badge-warning" title="Completed">{{data.complatedqty}}</span> <!--&nbsp;<span class="badge badge-danger">2</span>--></td>
                                              <td width="200"> <span class="badge badge-danger" title="Pending">{{data.totalqtycount}}</span><!--&nbsp;<span class="badge badge-danger">2</span>--></td>
                                            <td >{{data.assign_to}}</td>

                                            <td >{{data.company_name}}</td>
                                            <td ><a  href="{{data.company_label}}" ng-if="data.company_label != ''"  target="_blank">{{data.company_awb}}</a>
                                                <a ng-if="data.company_label == ''">{{data.company_awb}}</a>
                                            </td>
                                            <td >{{data.city}}</td>
                                            <td >{{data.address}}</td>
                                            
                                              <td><img ng-if="data.vehicle_type != ''" src="<?= base_url(); ?>{{data.vehicle_type}}" width="65">
                                                <img ng-if="data.vehicle_type == ''" src="<?= base_url(); ?>assets/nfd.png" width="65"></td>
                                            <td > {{data.seller_id}}</td>
                                   <!--          <td ><img src="{{data.pickimg}}"/> </td>-->
                                             <!-- <td > {{data.code}}</td>-->

                                            <td ng-if="data.on_hold == 'N'"><span class="badge badge-danger"> <?= lang('lang_NO'); ?></span></td>
                                            <td ng-if="data.on_hold == 'Y'"><span class="badge badge-success"> <?= lang('lang_YES'); ?></span></td>
                                            <td ng-if="data.confirmO == 'N'"><span class="badge badge-danger"> <?= lang('lang_NO'); ?></span></td>
                                            <td ng-if="data.confirmO == 'Y'"><span class="badge badge-success"> <?= lang('lang_YES'); ?></span></td>



                                            <td > {{data.req_date}}</td>




                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li ><a href="manifestview/{{data.uniqueid}}/PS"><i class="icon-eye" ></i>Pending Sku</a></li>
                                                            <li ><a href="manifestview/{{data.uniqueid}}/RS"><i class="icon-eye" ></i> Received Sku</a></li>
                                                            <li ><a href="manifestview/{{data.uniqueid}}/DM"><i class="icon-eye" ></i>Damage Or Missing Sku</a></li>

                                                            <li ><a ng-click="Getpickupimgview(data.pickimg);"><i class="icon-eye" ></i>Proof Of Pickup</a></li>

                                                            <li ng-if="data.qtyall > data.complatedqty && data.confirmO == 'N'"><a ng-click="updatemanifeststatus(data.id, data.uniqueid, data.sid, data.qtyall, data.complatedqty, data.totalqtycount);"  ><i class="icon-pencil7"></i>Update Stock</a></li> 
                                                            <li ng-if="data.confirmO == 'N' && data.on_hold == 'N' && data.totalqtycount > 0"><a ng-confirm-click="Are you sure you want to Update On Hold?"  confirmed-click="showonholdorder_pop(data.uniqueid,data.sid);" ><i class="icon-pencil7"></i> <?= lang('lang_Update_On_Hold'); ?></a></li>
                                                            <li ng-if="data.confirmO == 'N' && data.addBtnI == 'N' && data.complatedqty == data.qtyall && data.error == '0' && data.staff_id==0"><a  ng-click="GetpopAssignStafflist(data.uniqueid);"><i class="icon-user"></i>Assign Staff</a></li>
                                                            <li ng-if="data.addBtnI == 'Y' && (data.complatedqty == data.qtyall || itemupdated == 'Y')"><a ng-confirm-click="are you sure want to Add Sku?" confirmed-click="addskufielddata_pop(data.sku,data.uniqueid);"><i class="icon-pencil7"></i><?= lang('lang_Update_Sku'); ?></a></li>
                                                          <!-- <li><a ng-click="updatemanifeststatus_notfound(data.id,data.uniqueid,data.sid,data.qty);"  ><i class="icon-pencil7"></i> Update Not Found</a></li>-->





                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td> 
                                        </tr>

                                    </table>

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0,'no');" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
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


            <div class="modal fade" id="PopidreturnShowitem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">



                            <h5 class="modal-title" id="exampleModalLabel">Return Order{{uniqueid}} </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-warning" ng-if="message.error" id="errhide">{{message.error}}</div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                            <div class="col-md-12" ng-if="invalidSslip_no">
                                <div class="alert alert-warning" ng-if="invalidSslip_no" ng-repeat="in_data in invalidSslip_no">Invalid slip no. "{{in_data}}"</div>
                            </div>
                            <div class="col-md-12" ng-if="Success_msg">
                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : Shipment Forwarded</div>
                            </div>
                            <div class="col-md-12" ng-if="Error_msg">
                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                            </div>




                            <div class="col-md-12" ng-if="mainstatusEmpty">
                                <div class="alert alert-danger" ng-if="mainstatusEmpty">{{mainstatusEmpty}}</div>
                            </div>
                            <div class="col-md-12" ng-if="messArray1 != 0">
                                <div class="alert alert-danger" ng-repeat="mdata in messArray1">Wrong Awb {{mdata}}</div>    
                            </div>
                            <form novalidate ng-submit="myForm.$valid && createUser()" >
                                <input type="radio" name="assign_type" ng-model="returnUpdate.assign_type" ng-click="GetChangeAssignType('D');" value="D"  > Driver <input type="radio" name="assign_type" value="CC" ng-model="AssignData.assign_type" ng-click="GetChangeAssignType('CC');"  > Coourier Company
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" ng-if="returnUpdate.assign_type == 'D'" class="btn btn-primary" ng-click="saveassigntodriver();" >Update Driver</button>
                            <button type="button" ng-if="returnUpdate.assign_type == 'CC'" class="btn btn-primary" ng-click="saveassigntodriver();" >Send Curier</button>
                        </div>
                        </form>          
                    </div>
                </div>
            </div>
            <!-- /page content -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">



                            <h5 class="modal-title" id="exampleModalLabel"><?= lang('lang_Update_Manifest'); ?> ({{MupdateData.mid}}) </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
<!--                            <span class="badge badge-success" title="Total">({{ MupdateData.tqty}})</span>
                            <span class="badge badge-danger" title="Pending">({{ MupdateData.pendingqty ? MupdateData.pendingqty : MupdateData.ptqy}})</span>-->

                        </div>
                        

                        <div class="modal-body">


                           <div class="alert alert-warning" ng-if="messageshow_wl">{{messageshow_wl}}</div>
                            <div class="alert alert-success" ng-if="messageshow_al">{{messageshow_al}}</div>
                            <div class="alert alert-warning" ng-if="message.error" id="errhide">{{message.error}}</div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                            <form novalidate ng-submit="myForm.$valid && createUser()" >
                                <input type="text"  ng-model="MupdateData.sku" name="sku" id="sku" class="form-control" my-enter="getsavemanifestrecevedata();" required placeholder="SKU" style="width: 50%;float: left;">&nbsp;&nbsp;&nbsp;
                                <button ng-show="ConfrmBtnDis" class="btn btn-warning" ng-confirm-click="are you sure want to update Status?" confirmed-click="GetConfirmUpdateStatusData();">Confirm</button>
                                <br>
                                <br>
                                <table class="" style="width: 100%; margin-top: 25px;" >
                                    <tr><td>SKU</td><td>Qty</td><td>Scan</td><td>Item Image</td><td>Status</td></tr>
                                    <tr ng-repeat="im_data in SeachSkuList"><td>{{im_data.sku}}</td>
                                        <td> <span class="badge badge-success">{{im_data.qty}}</span></td>
                                        <td><span class="badge badge-warning">{{im_data.scan}}</span></td> 
                                        <td><img ng-if="im_data.item_path != ''" src="<?= base_url(); ?>{{im_data.item_path}}" width="80">
                                            <img ng-if="im_data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="80">
                                        </td>
                                     <td><span class="badge badge-warning" ng-if="im_data.status=='pending'">Pending</span>
                                     <span class="badge badge-success" ng-if="im_data.status=='Compeleted'">Completed</span></td> 
                                    </tr></table>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" ng-click="Closewidowprces();" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-primary" ng-click="getsavemanifestrecevedata();" >Update</button>-->
                        </div>
                        </form>          
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ConfirmPOPid" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">


                            <h5 class="modal-title" id="exampleModalLabel"><?= lang('lang_Update_Confirm_Order'); ?> {{uniqueid}} </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>


                        </div>


                        <div class="modal-body">
                            <div class="alert alert-warning" ng-show="countboxvalmess"><?= lang('lang_Please_Add_More_Location_after_process'); ?></div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                            <form novalidate ng-submit="myForm.$valid && getsaveconfirmOrders()" name="myForm" >
                                <div class="alert alert-warning" ng-if="errorinvalidpallet" ng-repeat="edata in errorinvalidpallet"> this 
                                    {{edata}} shelve no is invalid
                                </div>
                                <div class="alert alert-warning" ng-if="erroralreadypallett" ng-repeat="edata in erroralreadypallett"> this 
                                    {{edata}} Shelve no is already use another user
                                </div>
                                <div class="alert alert-warning" ng-if="erroremptypallet" ng-repeat="edata in erroremptypallet"> this sku
                                    {{edata}}  shelve no is empty
                                </div>

                                <div ng-repeat="data in Updateqtyconf.result">
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" style="width:100%;"><tr>
                                            <td><?= lang('lang_SKU'); ?></td><td>{{data.sku}}</td></tr>
                                        <tr>
                                            <td><?= lang('lang_Boxes'); ?></td><td>{{data.boxes}}</td></tr>
                                        <tr>
                                            <td><?= lang('lang_Shelve_No'); ?></td><td><input class="form-control" type="text" ng-model="data.shelveNo"></td></tr>
                                        <tr>
                                            <td><?= lang('lang_warehouse'); ?></td><td>
                                                <select class="form-control" ng-model="data.wh_id" required="">
                                                    <option value=""><?= lang('lang_Please_Select'); ?> <?= lang('lang_warehouse'); ?></option>
                                                    <option ng-repeat="wdata in warehouseArr" value="{{wdata.id}}">{{wdata.name}}</option>
                                                </select>
                                                <!--                                                            {{data.warehouse_name}}-->
                                            </td></tr>



                                        <tr>
                                            <td><?= lang('lang_Locations'); ?></td><td><div ng-repeat="data2 in data.stockLocation">{{data2.stock_location}}</div></td></tr>


                                    </table>


                                </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" ng-show="countboxval" ng-click="myForm.$valid && getsaveconfirmOrders()" ><?= lang('lang_Confrim'); ?></button>
                        </div>
                        </form>          
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addSkudetailspop" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">


                            <h5 class="modal-title" id="addSkudetailspop"><?= lang('lang_Update_Sku'); ?> </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>


                        </div>


                        <div class="modal-body">


                            <div class="alert alert-warning" ><?= lang('lang_Please_Update_below_sku_details_for_following_link'); ?> <a href="<?= base_url(); ?>Item/add_view" target="_blank">Add Sku</a></div>
                            <form novalidate  >


                                <table class="table table-striped  table-bordered dataTable" style="width:100%;">
                                    <tr>
                                        <td><?= lang('lang_SKU'); ?></td>
                                        <td><?= lang('lang_Action'); ?></td>
                                    </tr>
                                    <tr ng-repeat="data8 in skuArraydetails">
                                        <td>{{data8.sku}}</td>
                                        <td><i class="fa fa-times"></i></td>
                                    </tr>




                                </table>



                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('lang_Close'); ?></button>

                        </div>
                        </form>          
                    </div>
                </div>
            </div>
            
              <div class="modal fade" id="showAssignStaffPOP_id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">


                            <h5 class="modal-title" id="addSkudetailspop">Assign Staff </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>


                        </div>


                        <div class="modal-body">


                             <select class="form-control" ng-model="staffUpdateAssignArr.staff_id" required="">
                                                    <option value=""><?= lang('lang_Please_Select'); ?> Staff</option>
                                                    <option ng-repeat="wdata in StaffListArr" value="{{wdata.id}}">{{wdata.name}}</option>
                                                </select>
                        </div>
                        <div class="modal-footer">
                            
                            
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('lang_Close'); ?></button>
                            <button type="button" class="btn btn-primary" ng-if="staffUpdateAssignArr.staff_id>0" ng-click="GetUpdateStaffAssign();" >Assign</button>

                        </div>
                                
                    </div>
                </div>
            </div>
            
            
            


            <div class="modal fade bd-example-modal-lg" tabindex="-1" id="Shopickimgmodel"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">


                            <h5 class="modal-title" id="exampleModalLabel"><?= lang('lang_View_Pickup_Upload'); ?> </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>


                        </div>


                        <div class="modal-body">
                            <img src="{{MupdateData.imgpath}}" width="300">

                        </div>
                    </div>
                </div>
            </div>



    </body>
</html>
