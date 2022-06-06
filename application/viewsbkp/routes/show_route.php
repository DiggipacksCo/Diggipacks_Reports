<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_All_Users');?></title>
        <?php $this->load->view('include/file'); ?>
       <script src="<?=base_url();?>assets/js/angular/routemanagementCtrl.js"></script>
    </head>

    <body ng-app="routeApp" ng-controller="routemanagementCtrl" ng-init="getRoutelist(1,0)">
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
                        <?php
                        if ($this->session->flashdata('succmsg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('succmsg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        if ($this->session->flashdata('errormess'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('errormess') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?= lang('lang_Show_Route'); ?></strong> <a class="btn btn-primary mb-10 pull-right" href="<?=base_url('add_route');?>" style="margin-left: 10px;"><?= lang('lang_Add_Route'); ?></a> </h1>
                               
                                
                            </div>
                            <div class="panel-body" > 


                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.</th>
                                                <th><?=lang('lang_Route_Code');?></th>
                                                <th><?=lang('lang_Route');?></th>
                                                <th><?=lang('lang_Arabic_Keyword');?></th>
                                                <th><?=lang('lang_latitude_longitude');?></th>

                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <tr ng-repeat="data in RoutelistArray">
                                                <td>{{$index+1}}</td>
                                                <td>{{data.routecode}}</td>
                                                 <td>{{data.route}}</td>
                                                  <td>{{data.keyword}}</td>
                                                   <td>{{data.latlang}}</td>
                                                  <td class="text-center"><ul class="icons-list">
                                                                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                        <li><a href="<?= site_url()?>edit_route/{{data.id}}">  <?=lang('lang_Edit');?></a></li>
                                                                        <li><a href="<?= site_url()?>delete_route/{{data.id}}"> <?=lang('lang_Delete');?> </a></li>
                                                                    </ul>

                                                                </li>
                                                            </ul></td>
                                            </tr>
                                           
                                        </tbody>
                                    </table>
                                    
                                     <button ng-hide="RoutelistArray.length==totalCount" class="btn btn-info" ng-click="getRoutelist(count=count+1,0);" ng-init="count=1"><?=lang('lang_Load_More');?></button>
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


        <!-- /page container -->

    </body>
</html>
