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
        <div class="page-container"  ng-controller="shipment_mapping">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>
                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>
                    

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong>Add New Mapping</strong><button  class="btn btn-danger" ng-click="viewAlMapping();"   style="margin-left:7%;float:right;">View All Mapping</button></h1>
                                
                            </div>
                            <hr>
                            <div class="panel-body">
                               
                            <div class="col-md-12" ng-if="Success_msg">
                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?= lang('lang_Shipment_Forwarded'); ?></div>
                            </div>
                            <div class="col-md-12" ng-if="responseError">
                                <div class="alert alert-danger" ng-repeat="error_response in responseError">{{error_response}}</div>
                            </div>
                                <form action="#" name="add_mapping">
                                    <div class="form-group">
                                        <strong><?=lang('lang_company');?>:</strong>
                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id"  data-live-search="true" class="selectpicker" data-width="100%" >

                                            <option value=""><?=lang('lang_Select_Company');?></option>
                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                <option value="<?= $data['cc_id']; ?>" <?php if($mapdata['cc_id'] == $data['cc_id'] ){ echo 'selected="selected"'; } ?>><?= $data['company']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="map_data"><strong>Mapping Data:</strong></label>
                                        <textarea id="map_data" name="map_data"  ng-model="filterData.map_data"  rows="15" class="form-control" sytle="border:1px solid;" ><?=$mapdata['map_data'] ?></textarea>
                                        <input type="hidden" name="id" value="<?=$mapdata['id'] ?>" />
                                    </div>
                                    <div style="padding-top: 20px;">
                                        <button type="button"  ng-click="saveMappingData()" class="btn btn-success"><?=lang('lang_Save');?></button>
                                    </div>
                                </form>

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

    </body>
</html>