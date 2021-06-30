<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
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
                            <div class="panel-heading" dir="ltr">
                                <h1><strong><?=lang('lang_Add_Gift_Offer');?></strong></h1>
                            </div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                } ?>
                                <form action="<?= base_url('Offers/getaddform'); ?>" method="post" name="itmfrm" >
                                    <div class="form-group">
                                        <label for="name"><strong><?=lang('lang_Sellers');?>:</strong></label>
                                        <select id="seller_id" name="seller_id" class="bootstrap-select form-control" data-show-subtext="true" data-live-search="true" ng-model="fromdata.seller_id" ng-change="getitemdataforsellers($event);"  data-width="100%">
                                            <option value=""><?=lang('lang_SelectSeller');?></option>
                                            <?php
                                            if (!empty($sellersArray)) {
                                                $ii = 0;
                                                foreach ($sellersArray as $sdata) {
                                                    echo'<option value="' . $sdata['id'] . '">' . $sdata['name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="error" ng-show="itmfrm.seller_id.$error.required">  <?=lang('lang_PleaseSelectSeller');?></span> </div>
                                    <div class="form-group">
                                        <label for="main_item"><strong><?=lang('lang_Main_Item');?>:</strong></label>
                                        <select id="main_item" name="main_item[]"  class="js-example-basic-multiple bootstrap-select"  multiple="multiple" ng-change="Getcheckskuqtyfield(fromdata.main_item);" ng-model="fromdata.main_item" data-width="100%">

                                            <option ng-repeat="itemshow in itemdata"  value="{{itemshow.sku}}">{{itemshow.sku}}</option>

                                        </select>
                                        <span class="error" ng-show="itmfrm.main_item.$error.required"> <?=lang('lang_Please_Select_Main_Item');?> </span> </div>
                                    <div class="form-group" ng-if="items != 0" ng-repeat="data5 in qtyfiled">
                                        <label for="itemqty"><strong><?=lang('lang_QTY');?> ({{data5}}):</strong></label>

                                        <input type="number" class="form-control" name="itemqty[data5]" id="itemqty[data5]" placeholder="<?=lang('lang_QTY');?>" step="1" value="1" min="1" ng-model="fromdata.itemqty[data5]" style="width:50%;"  required>
                                        <span class="error" ng-show="itmfrm.itemqty.$error.required"> <?=lang('lang_PleaseEnterQTY');?> </span>   </div>

                                    <!--<div class="form-group">
                                      <label for="offer_item"><strong>Sub Item:</strong></label>
                                      <select id="offer_item" name="offer_item[]" class="js-example-basic-multiple bootstrap-select" ng-model="item.offer_item"  multiple="multiple"  data-width="100%">
                                       <option ng-repeat="itemshow in itemdata"  value="{{itemshow.id}}">{{itemshow.sku}}</option>
                                        
                                      </select>
                                      <span class="error" ng-show="itmfrm.offer_item.$error.required"> Please Select Sub Item </span> </div>-->
                                    <div class="form-group">
                                        <label for="start_date"><strong><?=lang('lang_Start_Date');?>:</strong></label>
                                        <input type="text" class="form-control expity_date" name='start_date' id="start_date" placeholder="<?=lang('lang_Start_Date');?>" ng-model="fromdata.start_date" required>
                                        <span class="error" ng-show="itmfrm.start_date.$error.required"> <?=lang('lang_Please_Select_Start_Date');?> </span> </div>
                                    <div class="form-group">
                                        <label for="expire_date"><strong><?=lang('lang_End_Date');?>:</strong></label>
                                        <input type="text" class="form-control expity_date" name='expire_date' id="expire_date" placeholder="<?=lang('lang_End_Date');?>" ng-model="fromdata.expire_date" required>
                                        <span class="error" ng-show="itmfrm.expire_date.$error.required"> <?=lang('lang_Please_Select_End_Date');?> </span> </div>
                                    <div style="padding-top: 20px;">
                                        <button type="button" class="btn btn-success" ng-disabled="itmfrm.$invalid" ng-click="GetFormDataAdd();" ><?=lang('lang_Submit');?></button>
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
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
        <script>
                                            var app = angular.module('formApp', []);
                                            app.controller('formCtrl', function ($scope, $http, $interval, $window) {
                                                $scope.fromdata = {};
                                                $scope.itemdata = [];
                                                $scope.items = {};
                                                $scope.qtyfiled = {};
                                                $scope.getitemdataforsellers = function ($event)
                                                {
                                                    $http({url: "<?= base_url(); ?>Offers/sellerdropdata", method: "POST", data: $scope.fromdata,
                                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                                                        $scope.itemdata = response.data;
                                                    });
                                                }
                                                $scope.Getcheckskuqtyfield = function (skuarray)
                                                {
                                                    $scope.qtyfiled = $scope.fromdata.main_item;
                                                    ///console.log($scope.qtyfiled);
                                                    //alert(skuarray);
                                                }
                                                $scope.GetFormDataAdd = function ()
                                                {

                                                    $http({url: "<?= base_url(); ?>Offers/GetaddformofferData", method: "POST", data: $scope.fromdata,
                                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                                                        if (response.data.status == 'error')
                                                        {
                                                            alert(response.data.mess);
                                                        } else
                                                        {
                                                            alert("successfylly offer created!");
                                                            $window.location.href = "<?= base_url(); ?>Offers/offerslist";
                                                        }


                                                    });
                                                }

                                            });
        </script> 
        <script>

            $(".js-example-basic-multiple").select2();
            $(".js-example-basic").select2();

            $(document).on('keyup', ".select2-search__field", function (e) {
                //if Ctrl+A pressed
                if (e.keyCode == 65 && e.ctrlKey) {
                    //Select only filtered results
                    $.each($(".select2-results__options").find('li'), function (i, item) {
                        $(".js-example-basic-multiple > option:contains('" + $(item).text() + "')").prop("selected", "selected");
                    });
                    //Remove entered letters and close suggestions
                    $(this).val("").click();
                    //Select with select2
                    $(".js-example-basic-multiple").trigger("change");
                }
            });
            $(function () {
                $(".expity_date").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    minDate: 0,
                });
            });
        </script>
    </body>
</html>
