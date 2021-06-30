<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_All_Users'); ?></title>
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
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        if ($this->session->flashdata('err_msg'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('err_msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?= lang('lang_Users_Table'); ?></strong></h1>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <!-- <li><a data-action="collapse"></a></li>
                                          <li><a data-action="reload"></a></li> --> 
                                        <!-- <li><a data-action="close"></a></li> -->
                                    </ul>
                                </div>
                                <hr>
                            </div>
                            <div class="panel-body"> 

<!-- <input type="text" id="search"  placeholder="Search .." class="form-control">
                                -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
                                        <thead>
                                            <tr>
                                                 <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_Username'); ?></th>
                                                <th><?= lang('lang_User_Type'); ?></th>
                                                <th><?= lang('lang_Email'); ?></th>
                                                <th><?= lang('lang_Mobile_No'); ?>.</th>
                                                <th><?= lang('lang_User_Logo'); ?></th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 1; ?>
                                            <?php if (!empty($usersrows)): ?>
                                                <?php foreach ($usersrows as $user):
                                                    ?>
                                                    <tr>
                                                        <td><?= $sr; ?></td>
                                                        <td><a href="<?= site_url('user-details/' . $user->id); ?>">
                                                                <?= $user->username ?>
                                                            </a></td>
                                                        <td style="text-transform: uppercase;"><?= getusertypenameshow($user->user_type); ?></td>
                                                        <td><?= $user->email; ?></td>
                                                        <td><?= $user->phone; ?></td>
                                                        <?php if (file_exists('../fs_files/' . $user->logopath) && !empty($user->logopath)) { ?>
                                                            <td><img src="<?= FBASEURL . $user->logopath; ?>" width="100"></td>
                                                        <?php } else { ?>
                                                            <td><img src="<?= base_url() ?>assets/images/noimg.png" width="100"></td>
                                                        <?php } ?>
                                                        <?php $sr++; ?>
                                                        <td class="text-center"><ul class="icons-list">
                                                                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                                        <?php if ($this->session->userdata('user_details')['user_type'] == 1) { ?>
                                                                            <li><a href="<?= site_url('user-privilege/' . $user->id); ?>"><i class="fa fa-star"></i> <?= lang('lang_Privilege_Access'); ?></a></li>
                                                                        <?php } ?>


                                                      
                                                        <li><a href="<?= site_url('update-user/' . $user->id); ?>"><i class="icon-pencil7"></i>  <?= lang('lang_Edit'); ?> </a></li>

                                                        <li><a href="<?= site_url('delete-user/' . $user->id); ?>"><i class="icon-bin"></i> <?= lang('lang_Delete'); ?> </a></li>

                                                                    </ul>

                                                                </li>
                                                            </ul></td>
                                                    </tr>
    <?php endforeach; ?>
<?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!--  <div>
                                  <center>
<?php //echo $links;   ?> 
                                 </center>
                               </div> -->
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
            $(document).ready(function () {
                var table = $('#example').DataTable({});

            });


        </script> 

        <!-- /page container -->

    </body>
</html>
