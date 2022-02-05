<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script> 


        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>

        <script src='<?= base_url(); ?>assets/js/angular/returnbulk.app.js'></script>     
    </head>

    <style>
        input.ng-invalid.ng-touched
        {
            border: 1px solid red !important; 
        }
    </style>


    <body ng-app="returnShipment" ng-controller="scanShipment"  >

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
                    <div class="content"  >
                        <!--style="background-color: red;"-->

                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>


                        <!-- Dashboard content -->
                         <div class="loader logloder" ng-show="loadershow"></div>
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong><?= lang('lang_Return'); ?> Shipment</strong>
                                            <!--
                                                              <a  ng-click="exportExcel();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>
                                                             <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>
                                            -->
                                        </h1>
                                        <div style="display:none">
                                            <audio id="audio" controls>
                                                <source src="<?= base_url('assets/apx_tone_alert_7.mp3'); ?>" type="audio/ogg">
                                            </audio>
                                            <audio id="audioSuccess" controls>
                                                <source src="<?= base_url('assets/filling-your-inbox.mp3'); ?>" type="audio/ogg">
                                            </audio>      
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                              <div class="col-md-12" ng-if="error_slip_succ">
                                                    <div class="alert alert-success" ng-repeat="success_msg in error_slip_succ">{{success_msg}} :Succes</div>
                                                </div>
                                            <div class="col-lg-12" >
                                               
                                             
                                                <div  ng-if="invalidstring.length>0 && invalidstring != null && invalidstring[0].slip_no != ''" >
                                                    <div class="alert alert-danger">Not avilable for return:
                                                        <span ng-repeat="inv in invalidstring">{{inv.slip_no}} <span ng-if="($index + 1) < invalidstring.length">,</span>  </span></div>
                                                                        
                                                </div> 
                                            </div>
                                            <div class="col-lg-6">

                                                <div ng-if="awbArray.length > 200" class="alert alert-danger"><?= lang('lang_Please_Verify_the_Packing_Limit_Exceed'); ?>! </div>
                                                <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                                                <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                                            </div>      
                                        </div>




                                        <form ng-submit="nextpage(2);" method="post" ng-if="step == 1" >
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="8" id="show_awb_no" ng-change="scan_awb();"   ng-model="scan.slip_no" required class="form-control" placeholder="AWB | 3PL"></textarea>

                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <a type="button"  class="btn btn-warning" style=" margin-left: 2%; margin-right: 3%; "><?= lang('lang_Row_Count'); ?> <span class="badge badge badge-pill badge-success" id="count_val">{{totalstep1}}</span>	</a>

                                                </div>
                                            </div>
                                            <div class="col-md-3">	

                                                <div class="form-group">
                                                    <textarea type="text" class="form-control" ng-model="scan.comment" placeholder="Enter Comment"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-4">	

                                                <div class="form-group">
                                                    <button type"submit" ng-disabled="scan.validate == null || scan.validate.length == 0"  role="button" class="btn btn-primary form-control" >Next</button>	



                                                </div>	 </div>	



                                        </form>

                                        <form ng-submit="updateData();" method="post" ng-if="step == 2" >

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <a type="button"  class="btn btn-warning" style=" margin-left: 2%; margin-right: 3%; "><?= lang('lang_Row_Count'); ?> <span class="badge badge badge-pill badge-success" id="count_val">{{totalstep2}}</span>	</a>

                                                </div>
                                            </div>

                                            <div class="col-md-4">	

                                                <div class="form-group">
                                                    <button type"submit" ng-disabled="scan.validate == null || scan.validate.length == 0"  role="button" class="btn btn-primary form-control" >Update</button>	



                                                </div>	 </div>	
                                            <div class="col-md-4">	

                                                <div class="form-group">
                                                    <button type"button"  ng-click="nextpage(1);"  role="button" class="btn btn-danger form-control" >Back</button>	



                                                </div>	 </div>	

                                            <div class="col-md-12">
                                                <table class="table table-striped table-hover table-bordered dataTable" id="example" datatable="ng" >
                                                    <thead>
                                                        <tr>
                                                            <th>Sr.No.</th>
                                                            <th>Awb Number</th>
                                                            <th>SKU</th>
                                                            <th>Qty</th>
                                                            <th>Missing</th>
                                                            <th>Dammage</th>

                                                        </tr>
                                                    </thead>
                                                    <tr  ng-repeat="ss in scan.validate" ng-init="scan.validate[$index].missing = 0; scan.validate[$index].damage = 0">
                                                        <td>{{$index + 1}}</td>
                                                        <td > 
                                                            <span  ng-if="ss.slip_no != scan.validate[$index - 1].slip_no && ss.slip_no == scan.validate[$index + 1].slip_no" style="font-size:20px" > [ </span>   
                                                            <span class="badge badge-warning " ng-if="ss.slip_no == scan.validate[$index - 1].slip_no || ss.slip_no == scan.validate[$index + 1].slip_no" >{{ss.slip_no}} </span> 
                                                            <span  ng-if="ss.slip_no == scan.validate[$index - 1].slip_no && ss.slip_no != scan.validate[$index + 1].slip_no"  style="font-size:20px" > ] </span>  
                                                            <span class="badge badge-info " ng-if="ss.slip_no != scan.validate[$index - 1].slip_no && ss.slip_no != scan.validate[$index + 1].slip_no" >{{ss.slip_no}}  </span>
                                                        </td>
                                                        <td> <span class="badge badge-primary badge-pill">{{ss.sku}}</span></td>
                                                        <td><span class="badge badge-info badge-pill">{{ss.piece}}</span></td>
                                                        <td><input type="number" class="form-control" ng-valid="scan.validate[$index].missing+scan.validate[$index].damage<=scan.validate[$index].piece" ng-change="checkValue($index, 'missing')" ng-model="scan.validate[$index].missing">
                                                            <span style="color:red">{{scan.validate[$index].msgmiss}}</span>
                                                        </td>
                                                        <td><input type="number" class="form-control"  ng-valid="scan.validate[$index].missing+scan.validate[$index].damage<=scan.validate[$index].piece" ng-change="checkValue($index, 'damage')" ng-model="scan.validate[$index].damage" >
                                                            <span style="color:red">{{scan.validate[$index].msgmdam}}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </form>




                                    </div>

                                    <!-- /quick stats boxes -->
                                </div>
                            </div>

                        </div>

                    </div> 

                </div>            
                <!-- /dashboard content -->

                <!-- /basic responsive table -->


            </div>
            <!-- /content area -->
        </div>
        <?php $this->load->view('include/footer'); ?>   


        <!-- /page container -->

    </body>
</html>
