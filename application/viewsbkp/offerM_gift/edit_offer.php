<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    </head>

    <body >
        <?php $this->load->view('include/main_navbar'); ?>

        
        
        <!-- Page container -->
        <div class="page-container" ng-app="formApp" ng-controller="formCtrl" ng-init="getitemdataforsellers(<?=$edit_id;?>);"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper">
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h1><strong>Edit Offer</strong></h1>
                            </div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>
                                <form action="<?= base_url('Offers/getaddform'); ?>" method="post" name="itmfrm" >
                                    <div class="form-group">
                                        <label for="name"><strong>Seller:</strong></label><br>
                                       <?=$seller_name?>
                                         </div>
                                    <div class="form-group">
                                        <label for="main_item"><strong>Main Item:</strong></label>
                                        <input type="checkbox" id="checkbox" > Select All
                                        <select id="main_item" name="main_item[]"  class="js-example-basic-multiple bootstrap-select"  multiple="multiple"  ng-model="fromdata.main_item" data-width="100%">

                                            <option ng-repeat="itemshow in itemdata"  value="{{itemshow.sku}}">{{itemshow.sku}}</option>

                                        </select>
                                        <span class="error" ng-show="itmfrm.main_item.$error.required"> Please Select Main Item </span> </div>





                                    <div class="form-group">
                                        <label for="gift_item"><strong>Gift Item:</strong></label>
                                        <select id="gift_item" name="gift_item[]"  class="js-example-basic-multiple bootstrap-select"  multiple="multiple"  ng-model="fromdata.gift_item" data-width="100%">

                                            <option ng-repeat="itemsG in itemdata_gift"  value="{{itemsG.sku}}">{{itemsG.sku}}</option>

                                        </select>               <span class="error" ng-show="itmfrm.gift_item.$error.required"> Please Select Gift Item </span> </div>


                                    <div class="form-group">
                                        <label for="gift_item"><strong>Offer On/Off:</strong></label><br>
                                        <label class="switch">
                                            <input type="checkbox" name="status" ng-model="fromdata.status" id="status" value="Y" checked >
                                            <span class="slider round"></span>
                                        </label>
                                         </div>







                                    <div style="padding-top: 20px;">
                                        <button type="button" class="btn btn-success" ng-disabled="itmfrm.$invalid" ng-click="GetFormDataAdd();" >Submit</button>
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
                                                $scope.itemdata = {};
                                                $scope.itemdata_gift = {};
                                                $scope.items = {};
                                                $scope.qtyfiled = {};
                                              
                                               
                                                 $scope.fromdata.main_item = <?=$main_itemsArr;?>;
                                                 $scope.fromdata.gift_item = <?=$offer_itemsArr;?>;
                                                  $scope.fromdata.seller_id='<?=$editData['seller_id'];?>';
                                                  
                                                  $scope.fromdata.status_new='<?=$editData['status'];?>';
                                                  $scope.fromdata.promocode='<?=$editData['promocode'];?>';
                                                  if($scope.fromdata.status_new=='Y')
                                                  {
                                                      $scope.fromdata.status=true;
                                                  }
                                                  else
                                                  {
                                                       $scope.fromdata.status=false;
                                                  }
                                                  
                                                  
                                             
                                                $scope.getitemdataforsellers = function ($event)
                                                {
                                                    $http({url: "<?= base_url(); ?>Offers/sellerdropdata", method: "POST", data: $scope.fromdata,
                                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                                                        $scope.itemdata = response.data;
                                                        $scope.itemdata_gift = response.data;

                                                        console.log($scope.itemdata_gift);

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

                                                    $http({url: "<?= base_url(); ?>Offers/GetaddformofferData_gift_edit", method: "POST", data: $scope.fromdata,
                                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}}).then(function (response) {
                                                        if (response.data.status == 'error')
                                                        {
                                                            alerst(response.data.mess);
                                                        } else
                                                        {
                                                           alert("successfylly offer Updated!");
                                                          $window.location.href = "<?= base_url(); ?>Offers/giftOffersList";
                                                        }


                                                    });
                                                }

                                            })
                                            .directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return val != null ? parseInt(val, 10) : null;
      });
      ngModel.$formatters.push(function(val) {
        return val != null ? '' + val : null;
      });
    }
  };
});;
        </script> 
        <script>

            $(".js-example-basic-multiple").select2();
            $(".js-example-basic").select2();
            $("#checkbox").click(function () {
                if ($("#checkbox").is(':checked')) {
                    $("#main_item > option").prop("selected", "selected");
                    $("#main_item").trigger("change");
                } else {
                    $("#main_item > option").removeAttr("selected");
                    $("#main_item").trigger("change");
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

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>    
