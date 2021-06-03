<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/manifest.app.js"></script>


    </head>

    <body ng-app="AppManifest" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="CTR_picluplist" ng-init="loadMore(1, 0);">

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
                                    <div class="panel-heading" dir="ltr">
                                        <h1>

                                            <strong><?=lang('lang_Pickup_List');?> </strong>
                          <!--                  <a  ng-click="exportmanifestlistnew();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>-->
                                          <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>-->
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">

                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">



                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >

                                                        <tr>

                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Sellers');?>:</strong>
                                                                    <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control"> 
                                                                        <option value=""><?=lang('lang_SelectSeller');?></option>
                                                                        <option ng-repeat="sdata in sellers"  value="{{sdata.id}}">{{sdata.name}}</option>
                                                                    </select>

                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Drivers');?>:</strong>

                                                                    <select id="driverid" name="driverid" ng-model="filterData.driverid" class="form-control"> 
                                                                        <option value=""><?=lang('lang_Select_Driver');?></option>
                                                                        <option ng-repeat="x in assigndata"  value="{{x.id}}">{{x.username}}</option>
                                                                    </select>

                                                                </div>
                                                            </td>


                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Manifest_ID');?>:</strong>
                                                                    <input type="text" id="manifestid" name="manifestid" ng-model="filterData.manifestid" class="form-control"> 

                                                                </div>
                                                            </td>
                                                            
                                                             <td>
                                                                <div class="form-group mt-10" >
                                                                    <select class="form-control"  ng-model="filterData.sort_list" ng-change="loadMore(1, 1);">
                                                                        <option value=""><?=lang('lang_Short_List');?></option>
                                                                        <option value="NO"><?=lang('lang_Newest_Order');?></option>
                                                                        <option value="OLD"><?=lang('lang_Oldest_Order');?></option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                            <td><button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button></td>
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
                                    <table class="table table-striped table-hover table-bordered" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                            <th><?=lang('lang_SrNo');?>.</th>
                                            <th><?=lang('lang_Manifest_ID');?> </th>
                                                <!--<th>SKU</th>-->
                                                <th><?=lang('lang_QTY');?></th>  
                                                <th><?=lang('lang_Driver_Name');?></th>  
                                                <th><?=lang('lang_TPL_company');?></th>  
                                                <th><?=lang('lang_TPL_AWB');?></th> 
                                                <th><?=lang('lang_City');?></th> 
                                                <th><?=lang('lang_Address');?></th> 
                                                <th><?=lang('lang_Status');?></th>
                                                <th><?=lang('lang_Type');?></th>
                                                <th><?=lang('lang_Seller');?></th>
                                                <th><?=lang('lang_Request_Date');?></th>





                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}}  </td>
                                            <td>{{data.uniqueid}}</td>

                                            
                                            <td ><span class="badge badge-success">{{data.qtyall}}</span></td>
                                            <td >{{data.assign_to}}</td>
                                            <td >{{data.company_name}}</td>
                                            <td ><a  href="{{data.company_label}}" ng-if="data.company_label!=''"  target="_blank">{{data.company_awb}}</a>
                                            <a ng-if="data.company_label==''">{{data.company_awb}}</a>
                                            </td>
                                            <td >{{data.city}}</td>
                                            <td >{{data.address}}</td>

                                            <td > {{data.pstatus}}</td>
                                            <td > <span ng-if="data.manifest_type=='D'">Drop Off</span> <span ng-if="data.manifest_type=='P'">Pickup</span></td>
                                            <td > {{data.seller_id}}</td>
                                            <td > {{data.req_date}}</td>





                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <ul class="dropdown-menu dropdown-menu-right">

                                                            <li><a ng-click="updatemanifeststatus($index, data.uniqueid);"  ><i class="icon-pencil7"></i> <?=lang('lang_Update');?></a></li>




                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td> 
                                        </tr>

                                    </table>

                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></button>
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




                            <h5 class="modal-title" id="exampleModalLabel" dir="ltr"><?=lang('lang_Assign_TO');?> {{mid}} </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-warning" ng-if="message.error" id="errhide">{{message.error}}</div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                            <form name="myform" novalidate ng-submit="myForm.$valid && createUser()" enctype="multipart/form-data" >
                                <input type="hidden" name="menifestid" id="menifestid" value="{{mid}}">

                                <input ng-model="form.image" type="file" accept="image/*" onchange="angular.element(this).scope().uploadedFile(this)" class="form-control" required >

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('lang_Close');?></button>
                                    <button type="button" class="btn btn-primary" ng-click="saveassigntodriver();" ><?=lang('lang_Update');?></button>
                                </div>
                            </form>          
                        </div>
                    </div>
                </div>

<!-- <script>
var $rows = $('tbody tr');
$('#search').keyup(function() {
var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

$rows.show().filter(function() {
var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
return !~text.indexOf(val);
}).hide();
});
</script> -->


            </div>
            <script>


                // "order": [[0, "asc" ]]
                $('#s_type').on('change', function () {
                    if ($('#s_type').val() == "SKU") {
                        $('#s_type_val').attr('placeholder', 'Enter SKU no.');
                    } else if ($('#s_type').val() == "AWB") {
                        $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                    }

                });


            </script>
            <!-- /page container -->

    </body>
</html>
