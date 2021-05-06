<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/manifest.app.js"></script>




    </head>

    <body ng-app="AppManifest" ng-controller="CTR_newmanifestrequest" ng-init="loadMore(1, 0);">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" >

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >

                        <div class="loader logloder" ng-show="loadershow"></div>

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
                                            <strong>New Manifest Request List </strong>
                                            <a  ng-click="exportmanifestlistnew();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>
                                          <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>-->
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments');   ?>" -->
                         <!-- href="<? //base_url('Pdf_export/all_report_view');   ?>" -->
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
                                                                <div class="form-group" ><strong>Sellers:</strong>
                                                                    <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control"> 
                                                                        <option value="">Select Seller</option>
                                                                        <option ng-repeat="sdata in sellers"  value="{{sdata.id}}">{{sdata.name}}</option>
                                                                    </select>

                                                                </div>
                                                            </td>



                                                            <td>
                                                                <div class="form-group" ><strong>Manifest ID:</strong>
                                                                    <input type="text" id="manifestid" name="manifestid" ng-model="filterData.manifestid" class="form-control"> 

                                                                </div>
                                                            </td>
                                                            
                                                             <td>
                                                                <div class="form-group mt-10" >
                                                        <select class="form-control"  ng-model="filterData.sort_list" ng-change="loadMore(1, 1);">

                                                            <option value="">Short List</option>


                                                            <option value="NO">Newest Order</option>
                                                            <option value="OLD">Oldest Order</option>
                                                          
                                                            

                                                        </select>

                                                   </div>
                                                            </td>

                                                            <td><button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                            <td><button  class="btn btn-danger" ng-click="loadMore(1, 1);" >Search</button></td>


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
                                                <th>Sr.No.</th>
                                                <th>Manifest ID</th>
                                                <!--<th>SKU</th>-->
                                                <th>QTY</th>  
                                             <!-- <th>Assign To</th> -->  
                                             <!--<th>Status</th>
                                             <th>Code</th>-->
                                                <th>Vehicle Type</th>
                                                <th>Seller</th>
                                                <th>Schedule Date</th>
                                                <th>Request Date</th>



                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}}  </td>
                                            <td>{{data.uniqueid}}</td>
                                           <!-- <td><span class="badge badge-info">{{data.sku}}</span></td>-->
                                            <td width="200"><span class="badge badge-success">{{data.qtyall}}</span><!--&nbsp;<span class="badge badge-danger">2</span>--></td>
                                            <!-- <td >{{data.assign_to}}</td>-->

 <!-- <td > {{data.pstatus}}</td>
   <td > {{data.code}}</td>-->
                                            <td><img ng-if="data.vehicle_type != ''" src="<?= base_url(); ?>{{data.vehicle_type}}" width="65">
                                                <img ng-if="data.vehicle_type == ''" src="<?= base_url(); ?>assets/nfd.png" width="65"></td>
                                            <td > {{data.seller_id}}</td>
                                            <td ><span ng-if="data.schedule_date != ''">{{data.schedule_date}}</span><span ng-if="data.schedule_date == ''">--</span></td>

                                            <td > {{data.req_date}}</td>




                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <ul class="dropdown-menu dropdown-menu-right">

                                                            <li><a ng-click="updatemanifeststatus($index, data.uniqueid);"  ><i class="icon-pencil7"></i> Assign To</a></li>





                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td> 
                                        </tr>

                                    </table>

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1">Load More</button>
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
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">



                            <h5 class="modal-title" id="exampleModalLabel">Assign TO {{uniqueid}} </h5>
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

                            <div class="loader logloder" ng-show="loadershow"></div>




                            <div class="col-md-12" ng-if="mainstatusEmpty">
                                <div class="alert alert-danger" ng-if="mainstatusEmpty">{{mainstatusEmpty}}</div>
                            </div>
                            <div class="col-md-12" ng-if="messArray1 != 0">
                                <div class="alert alert-danger" ng-repeat="mdata in messArray1">Wrong Awb {{mdata}}</div>    
                            </div>
                            <form novalidate ng-submit="myForm.$valid && createUser()" >
                                <input type="radio" name="assign_type" ng-model="AssignData.assign_type" ng-click="GetChangeAssignType('D');" value="D"  > Driver <input type="radio" name="assign_type" value="CC" ng-model="AssignData.assign_type" ng-click="GetChangeAssignType('CC');"  > Courier Company
                                <div ng-show="driverbtn">
                                    <select type="text"  ng-model="AssignData.assignid" class="form-control" required>
                                        <option ng-repeat="x in assigndata"  value="{{x.id}}">{{x.username}}</option>
                                    </select>
                                </div>
                                <div ng-show="crourierbtn">

                                    <select type="text"  ng-model="AssignData.cc_id" class="form-control" required>
                                        <option ng-repeat="cx in courierData"  value="{{cx.cc_id}}">{{cx.company}}</option>
                                    </select>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" ng-if="AssignData.assign_type == 'D'" class="btn btn-primary" ng-click="saveassigntodriver();" >Update Driver</button>
                            <button type="button" ng-if="AssignData.assign_type == 'CC'" class="btn btn-primary" ng-click="saveassigntodriver();" >Send Courier</button>
                        </div>
                        </form>          
                    </div>
                </div>
            </div>




        </div>

        <!-- /page container -->

    </body>
</html>
