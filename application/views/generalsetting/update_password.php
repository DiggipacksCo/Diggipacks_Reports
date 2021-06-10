<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Update_Password'); ?></title>
        <?php $this->load->view('include/file'); ?>


    </head>

    <body  ng-app="PasswordApp" ng-controller="CtrlPass">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong><?= lang('lang_Update_Password'); ?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>
                                <?php
                                if ($this->session->flashdata('msg') != '') {
                                    echo '<div class="alert alert-success" role="alert">  ' . $this->session->flashdata('msg') . '.</div>';
                                }
                                ?>

                                <form>


                                    <div class="form-group">
                                        <label for="old_pass"><strong><?= lang('lang_Old_Password'); ?>:</strong></label>
                                        <input type="password" class="form-control" ng-blur="GetcheckOldPassword(UpdateArray.old_pass);" name='old_pass' ng-model="UpdateArray.old_pass" id="old_pass" placeholder="Old Password" required="required">
                                        <span class="error" ng-if="UpdateArray.old_pass==''"><?= lang('lang_Please_Enter_Old_Password'); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_pass"><strong><?= lang('lang_New_password'); ?>:</strong></label>
                                        <input type="password" class="form-control" name='new_pass' ng-model="UpdateArray.new_pass" id="new_pass" placeholder="New password" required="required">
                                         <span class="error" ng-if="UpdateArray.new_pass==''"> <?= lang('lang_Please_Enter_New_Password'); ?> </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="confrim_pass"><strong><?= lang('lang_Confirm_Password'); ?>:</strong></label>
                                        <input type="password" class="form-control" name='confrim_pass'  ng-model="UpdateArray.confrim_pass" id="confrim_pass" placeholder="Confirm password" required="required">
                                         <span class="error" ng-if="UpdateArray.confrim_pass==''"> <?= lang('lang_Please_Enter_Confirm_Password'); ?> </span>
                                          <span class="error" ng-if="UpdateArray.confrim_pass!=UpdateArray.new_pass"> <?= lang('lang_password_dont_match'); ?> </span>
                                    </div>
                                    
                                  <div style="padding-top: 20px;">
                                      <button type="button" ng-hide="UpdateArray.old_pass==''" ng-if="UpdateArray.new_pass==UpdateArray.confrim_pass" class="btn btn-success" ng-click="GetUpdatePassword();"><?= lang('lang_Update'); ?></button>
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

<script>
    var app = angular.module('PasswordApp', [])

 .controller('CtrlPass', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
    
    
       $scope.UpdateArray={};
        $scope.UpdateArray.old_pass="";
        $scope.UpdateArray.new_pass="";
        $scope.UpdateArray.confrim_pass="";
       
       $scope.GetcheckOldPassword=function(pass)
       {
               $http({
                   
                    url: "Generalsetting/check_old",
                    method: "POST",
                    data: {password:pass},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                   // console.log(results);
                  ///  alert(results.data);
                    if(results.data=='false')
                    {
                        alert("Please Enter Valid Password");
                       $scope.UpdateArray.old_pass="";   
                    }
                    
                });
           
       };
       $scope.GetUpdatePassword=function()
       {
           
            $http({
                   
                    url: "Generalsetting/UpdatePasswordFrm",
                    method: "POST",
                    data: $scope.UpdateArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                   // console.log(results);
                  ///  alert(results.data);
                  
                  if(results.data.status=='succ')
                  {
                     alert(results.data.mess); 
                     window.location.href='<?=base_url();?>Home/logout';
                     
                  }
                  else
                  {
                       alert(results.data.mess); 
                  }
                   
                    
                });
           
       }
       
       
        });
</script>

