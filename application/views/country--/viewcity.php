<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>All Users</title>
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
                            <div class="panel-heading"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Country List</strong></h1>
                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <!-- <li><a data-action="collapse"></a></li>
                                          <li><a data-action="reload"></a></li> --> 
                                        <!-- <li><a data-action="close"></a></li> -->
                                    </ul>
                                </div>
                                <hr>
                            </div>
                            <div class="panel-body" > 

<!-- <input type="text" id="search"  placeholder="Search .." class="form-control">
                                -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
                                        <thead>
                                            <tr>
                                                <th>Sr.No.</th>
                                                <th>Country</th>

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
                                                        <td><a href="<?= site_url('Country/CountraddFrom/' . $rows['id']); ?>"> <?= $rows['country']; ?> </a></td>

        <?php $sr++; ?>
                                                        <td class="text-center"><ul class="icons-list">
                                                                <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                                                                    <ul class="dropdown-menu dropdown-menu-right">




                                                                        <li><a href="<?= site_url('Country/CountraddFrom/' . $user['id']); ?>"><i class="icon-pencil7"></i> Edit </a></li>


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
<?php //echo $links;  ?> 
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
