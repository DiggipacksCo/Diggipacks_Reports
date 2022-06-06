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
        <div class="page-container" ng-app="formApp" ng-controller="formCtrl">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong><?=lang('lang_Add_Item');?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>

                                <form action="<?= base_url('Item/add'); ?>" method="post" name="itmfrm" enctype="multipart/form-data">


                                    <!-- <div class="form-group" style="display:none;">
                                         <label for="name"><strong>Item Type:</strong></label>
                                       <select id="type" name="type" class="bootstrap-select" ng-model="item.type"  data-width="100%" required>
                                             <option value="">Select Type</option>
                                             
                                                                                              <option value="B2C">B2C</option>
                                                   <option value="B2B">B2B</option>
                                                                                      
                                             </select>
                                             
                                          <span class="error" ng-show="itmfrm.type.$error.required"> Please Select Type </span>
                                     </div>-->

                                    <div class="form-group" >
                                        <label for="name"><strong><?=lang('lang_Expire_Block');?>:</strong></label>
                                        <select id="expire_block" name="expire_block" class="form-control" data-width="100%" required>


                                            <option value="Y"><?=lang('lang_Yes');?></option>
                                            <option value="N" selected="selected"><?=lang('lang_No');?></option>

                                        </select>


                                    </div>


                                    <div class="form-group">
                                        <label for="wh_id"><strong><?=lang('lang_warehouse');?>:</strong></label>
                                        <?= GetwherehouseDropShow(set_value('wh_id')); ?>     
                                    </div>

                                    <div class="form-group">
                                        <label for="name"><strong><?=lang('lang_StorageType');?>:</strong></label>
                                        <select id="storage_id" name="storage_id" class="bootstrap-select" ng-model="item.storage_id"  data-width="100%" required>
                                            <option value=""><?=lang('lang_SelectStorageType');?></option>
                                            <?php
                                            if (!empty($StorageArray)) {
                                                foreach ($StorageArray as $rdata) {
                                                    // echo $rdata->id;
                                                    echo '<option value="' . $rdata->id . '">' . $rdata->storage_type . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="error" ng-show="itmfrm.storage_id.$error.required"> <?=lang('lang_Please_Select_Storage_Type');?> </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name"><strong><?=lang('lang_Name');?>:</strong></label>
                                        <input type="text" class="form-control" name='name' id="name" placeholder="<?=lang('lang_Name');?>" ng-model="item.name" required>
                                        <span class="error" ng-show="itmfrm.name.$error.required"> <?=lang('lang_PleaseEnterName');?> </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="sku"><strong><?=lang('lang_Sku');?> #:</strong></label>
                                        <input type="text" class="form-control" name='sku'  id="sku" placeholder="<?=lang('lang_Sku');?> #" ng-model="item.sku" required>
                                        <span class="error" ng-show="itmfrm.sku.$error.required"><?=lang('lang_Please_Enter_Sku');?> </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="sku"><strong><?=lang('lang_Capacity');?>:</strong></label>
                                        <input type="number" class="form-control" name='sku_size' min="1"  id="sku_size" placeholder="<?=lang('lang_Capacity');?>" ng-model="item.sku_size" required>
                                        <span class="error" ng-show="itmfrm.sku_size.$error.required"><?=lang('lang_Please_Enter_Capacity');?> </span>
                                    </div>
                                    
                                     <div class="form-group">
                                    <label for="less_qty"><strong><?=lang('lang_Less_Quantity');?>:</strong></label>
                                    <input type="number" class="form-control" name='less_qty' id="less_qty" placeholder="<?=lang('lang_Less_Quantity');?>" ng-model="item.less_qty" required>
                                    <span class="error" ng-show="itmfrm.name.$error.required"> <?=lang('lang_Please_Enter_Less_Quantity');?> </span>
                                </div>
                                 <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Expiry_Days');?>:</strong></label>
                                    <input type="number" class="form-control" name='alert_day' id="alert_day" placeholder="<?=lang('lang_Expiry_Days');?>" ng-model="item.alert_day" >
<!--                                    <span class="error" ng-show="itmfrm.alert_day.$error.required"> Please Enter Expiry days </span>-->
                                </div>
                                
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Color');?>:</strong></label>
                                    <div class="input-group myColorPicker">
					  <span class="input-group-addon myColorPicker-preview">&nbsp;</span>
					  <input type="text" class="form-control" name="color">
					</div>
                                   
                                </div>
                                
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Length');?>:</strong></label>
                                    <input type="number" class="form-control" name='length' id="length" placeholder="<?=lang('lang_Length');?>" ng-model="item.length" >
                                   
                                </div>
                                
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Width');?>:</strong></label>
                                    <input type="number" class="form-control" name='width' id="width" placeholder="<?=lang('lang_Width');?>" ng-model="item.width" >
                                   
                                </div>
                                
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Height');?>:</strong></label>
                                    <input type="number" class="form-control" name='height' id="height" placeholder="<?=lang('lang_Height');?>" ng-model="item.height" >
                                   
                                </div>

                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Weight');?>:</strong></label>
                                    <input type="float" class="form-control" name='weight' id="weight" placeholder="<?=lang('lang_Weight');?>" ng-model="item.weight" >
                                   
                                </div>

                                    <div class="form-group">
                                        <label for="description"><strong><?=lang('lang_Description');?>:</strong></label>
                                        <textarea rows="5" id="description" name="description" class="form-control" placeholder="<?=lang('lang_Description');?>" ng-model="item.description" required></textarea><span class="error" ng-show="itmfrm.description.$error.required"> Please Enter Description </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="description"><strong><?=lang('lang_Item_Image');?>:</strong></label>
                                        <input type="file" id="item_path" name="item_path"  class="form-control" ng-model="item.item_path">

                                    </div>




                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success" ng-disabled="itmfrm.$invalid"><?=lang('lang_Submit');?></button>
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
        <!--/script> -->
        
          <link href="<?=base_url();?>assets/colorpicker/jquery.colorpicker.bygiro.min.css" rel="stylesheet">
    <script src="<?=base_url();?>assets/colorpicker/jquery.colorpicker.bygiro.min.js"></script>

	<script>
		$('.myColorPicker').colorPickerByGiro({
			preview: '.myColorPicker-preview',
            showPicker:true,
            format:'hex',
            sliderGap: 6,

  cursorGap: 6,
            text: {

    close:'Close',

    none:'None'

  }




		});
	</script>
        <script>
            var app = angular.module('formApp', []);
            app.controller('formCtrl', function ($scope) {

            });
        </script>
   
    </body>
</html>

