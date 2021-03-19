<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Picker Settings</title>
        <?php $this->load->view('include/file'); ?>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

        <script src="<?= base_url(); ?>assets/js/angular/user.app.js"></script>
    </head>

    <body ng-app="usersApp" ng-controller="PickerSettingsCtlr" ng-init="loadMore();" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"> 

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

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Picker Settings</strong></h1>
                                <div class="heading-elements">
                                    <ul class="icons-list">

                                    </ul>
                                </div>
                                <hr>
                            </div>
                            <div class="panel-body" > 
                                
                                  <?php
                                if ($this->session->flashdata('msg') != '') {
                                    echo '<div class="alert alert-success" role="alert">  ' . $this->session->flashdata('msg') . '.</div>';
                                }
                                ?>
                                  <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>


                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sr.No.</th>
                                                <th>Picker Name</th>
                                                <th>Capacity</th>
                                                <th>Batch Number</th>
                                                <th>Assigning Time</th>
                                              
                                                <th>Days off</th>
                                                <th>Assign Status</th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr ng-repeat="data in UserArr">

                                                <td> {{$index + 1}}</td> 
                                                <td> {{data.name}}</td> 
                                                <td> <span class="label label-primary">{{data.per_day_target}}</span></td> 
                                                <td><span class="label label-primary"> {{data.batch_no}}</span></td> 
                                                <td> <span class="label label-primary">{{data.assign_time}}</span>
                                                </td> 
                                              
                                                <td> {{data.day_off}}</td> 
                                                <td> <span ng-if="data.auto_status=='Y'" class="label label-success">Active</span>
                                                    <span ng-if="data.auto_status=='N'" class="label label-warning">Inactive</span>
                                                </td> 
                                                <td class="text-center"><ul class="icons-list">
                                                        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a><ul class="dropdown-menu dropdown-menu-right">
                                                                <li><a href="<?= site_url();?>edit_picker_setting/{{data.id}}"> Edit </a></li>
                                                         <li><a ng-if="data.auto_status=='N'" href="<?= site_url(); ?>auto_assign_active/Y/{{data.id}}"> Actve </a>
                                                         
                                                         <a ng-if="data.auto_status=='Y'" href="<?= site_url(); ?>auto_assign_active/N/{{data.id}}"> Inactive </a></li>
                                                            </ul>

                                                        </li>
                                                    </ul></td>

                                            </tr>

                                        </tbody>
                                    </table>
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

        <script>

                    $('.timepicker').val('13:24:00');
        </script>

    </body>
</html>
