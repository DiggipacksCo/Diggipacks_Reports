<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>


        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>

    <body ng-app="fulfill" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_mapping" ng-init="loadMore(1, 0);" >

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
                                            <strong>View All Mapping</strong>
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments');    ?>" -->
                         <!-- href="<? //base_url('Pdf_export/all_report_view');    ?>" -->
                                        <!-- Quick stats boxes -->
                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_company');?>:</strong>
                                                        <br>
                                                        <?php
                                                        //$destData = getAllDestination();
                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Company');?></option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['cc_id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-2" style="margin-top: 20px;"><div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.sort_limit" ng-change="loadMore(1, 1);">
                                                            <option value=""><?=lang('lang_Short');?></option>
                                                            <option ng-repeat="(key,value) in dropshort" value="{{key}}-{{value}}">{{value}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group" >
                                                        <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button>
                                                        <button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                        <button  class="btn btn-danger" ng-click="addNewMapping();"   style="margin-left: 7%">Add New Mapping</button>
                                                    </div>
                                                </div>
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
                                                <th><?=lang('lang_SrNo');?></th>
                                                 <th><?=lang('lang_Forwarded_Company');?></th>
                                                 <th>Mapping</th>
                                                 <th><?=lang('lang_Status');?></th>
                                                 <th><?=lang('lang_Date');?></th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>  
                                        </thead>   
                                        <tr ng-if='mappingData != 0' ng-repeat="data in mappingData"> 

                                            <td>{{$index + 1}}</td>    
                                            <td>{{data.cc_name}}</td>
                                            <td>{{data.map_data}}</td>
                                            <td>{{data.status}}</td>
                                            <td>{{data.updated_at}}</td>
                                            <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>   

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li><a href="<?= base_url(); ?>Shipment/edit_mapping_view/{{data.id}}"><i class="icon-pencil7"></i>Edit</a></li>
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
        </div>
     <script type="text/javascript">
         
//         $(document).ready(function(){
//             $("#status").change(function(){
//                 var selectedCode = $(this).val();
//                     
//                 if(selectedCode == '3PL'){
//                     $(".other_status").removeClass("hidden");
//                                    
////                     var selectedTxt = '3PL Updates';
////                     
////                     $.ajax({
////                            url: '<?php //echo base_url('Shipment/get_pl_status'); ?>',
////                            data: {status_name:selectedTxt},
////                            error: function () {},
////                            dataType: 'html',
////                            type: 'POST',
////                            beforeSend:function(){$("#other_status").addClass("hidden");},
////                            success: function (data) {
////                                //alert(data.length)    
////                                var jsonData = $.parseJSON(data);
////                                if(jsonData.length >0){
////                                    var option = "<option value='' >-select-</option>";
////                                    for(i=0;i<jsonData.length;i++){
////                                        option += "<option value='"+jsonData[i].code+"' >"+jsonData[i].sub_status+"</option>";
////                                    }
////                                    
////                                    $(".other_status").removeClass("hidden");
////                                    $("#status_o").html(option);
////                                }
//////                                if (jsonData.success) {
//////                                    alert('Data updated successfully');
//////                                }
////                            }
////
////                        });
//
//                 }else{
//                     $(".other_status").addClass("hidden");
//                 } 
//                
//                
//             });
//             
//         });
                     
                
      

        </script>
        <!-- /page container -->
    </body>
</html>
