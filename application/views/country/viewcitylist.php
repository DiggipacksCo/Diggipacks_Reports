<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_All_Users');?></title>
        <?php $this->load->view('include/file'); ?>
    </head>

    <body>
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
                                <h1><strong><?=lang('lang_City');?> <?=lang('lang_List');?></strong> <a class="btn btn-primary mb-10 pull-right" href="<?=base_url('Country/Hubaddform');?>" style="margin-left: 10px;"><?=lang('lang_add');?><?=lang('lang_Hub');?></a> <a href="<?=base_url('Country/CityAddForm');?>" class="btn btn-primary mb-10 pull-right" ><?=lang('lang_add');?><?=lang('lang_City');?></a></h1>
                               
                                
                            </div>
                            <div class="panel-body" > 


                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                 <th><?=lang('lang_SrNo');?>.</th>
                                                <th><?=lang('lang_Country');?></th>
                                                <th><?=lang('lang_Hub');?> <?=lang('lang_Name');?></th>
                                                 <th><?=lang('lang_City');?></th>
                                                  <th><?=lang('lang_City_Code');?></th>
                                                  <th><?=lang('lang_Arabic_City_Name');?></th>

                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 1; ?>
                                            <?php if (!empty($ListArr)): ?>
                                                <?php foreach ($ListArr as $rows):
                                                    ?>
                                                    <tr>
                                                        <td><?= $sr; ?></td>
                                                        <td><a> <?= $rows['country']; ?> </a></td>
                                                        <td> <?= $rows['state']; ?></td>
                                                          <td> <?= $rows['city']; ?></td>
                                                           <td> <?= $rows['city_code']; ?></td>
                                                           <td> <?= $rows['title']; ?></td>
                                                        <?php $sr++; ?>
                                                        <td class="text-center"><ul class="icons-list">
                                                                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                        <li><a href="<?= site_url('Country/CityAddForm/' . $rows['id']); ?>"><i class="icon-pencil7"></i>  <?=lang('lang_Edit');?></a></li>
                                                                    </ul>

                                                                </li>
                                                            </ul></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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


        <!-- /page container -->

    </body>
</html>
