<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>    
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>


    </head>

    <body ng-app="fulfill" ng-controller="bulkUpdate"  >

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
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong><?=lang('lang_Bulk_Update');?></strong>
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
                                            <div class="col-lg-12" >


                                                <div  ng-if="invalidstring" ><div class="alert alert-danger"><?=lang('lang_Not_avaible_selected_Update_Orders_removed_automatically');?>:{{invalidstring}}</div>

                                                </div> 
                                            </div>
                                            <div class="col-lg-6">

                                                <div ng-if="awbArray.length > 200" class="alert alert-danger"><?=lang('lang_Please_Verify_the_Packing_Limit_Exceed');?>! </div>
                                                <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                                                <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                                            </div>      
                                        </div>

<!-- href="<? // base_url('Excel_export/shipments'); ?>" -->
<!-- href="<? //base_url('Pdf_export/all_report_view'); ?>" -->
                                        <!-- Quick stats boxes -->



                                        <form ng-submit="updateData();" method="post" >
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="8" id="show_awb_no" ng-change="scan_awb();"   ng-model="scan.slip_no" required class="form-control"></textarea>

                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <a type="button"  class="btn btn-warning" style=" margin-left: 2%; margin-right: 3%; ">><?=lang('lang_Row_Count');?> <span class="badge badge badge-pill badge-success" id="count_val">{{scan.awbArray.length}}</span>	</a>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select  id="status" name="status" ng-model="scan.status" ng-change="scan_awb();" class="selectpicker"  data-width="100%" >

                                                        <option value=""><?=lang('lang_Select_Status');?></option>
                                                        <?php foreach ($status as $status_detail): ?>
                                                            <?php if ($status_detail->id != '2'): ?>  <?php endif; ?>
                                                            <option value="<?= $status_detail->id; ?>"><?= $status_detail->main_status; ?></option>

                                                        <?php endforeach; ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-4">	

                                                <div class="form-group">
                                                    <button type"submit" ng-if="scan.validate"  role="button" class="btn btn-primary form-control" ><?=lang('lang_Update');?></button>	
                                                    <button type"submit" ng-if="scan.validate == null"  role="button" class="btn btn-danger form-control" disabled ><?=lang('lang_Update');?></button>	


                                                </div>	 </div>	

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
