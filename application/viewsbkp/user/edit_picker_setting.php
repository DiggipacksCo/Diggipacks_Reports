<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Add New User</title>
        <?php $this->load->view('include/file'); ?>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.css" integrity="sha512-MT4B7BDQpIoW1D7HNPZNMhCD2G6CDXia4tjCdgqQLyq2a9uQnLPLgMNbdPY7g6di3hHjAI8NGVqhstenYrzY1Q==" crossorigin="anonymous" />

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


    </head>

    <body>

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
                            <div class="panel-heading"><h1><strong>Picker Assign Setting(<?= $editdata['name']; ?>)</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>

                                <form action="<?= base_url('Users/add_picker_setting'); ?>" name="adduser" method="post">
                                    <input type="hidden" name="user_id" value="<?=$editdata['id'];?>">
                                    <div class="form-group">
                                        <label for="per_day_target"><strong>Capacity:</strong></label>
                                        <input type="number" class="form-control" min="0" step="1" name='per_day_target' id="per_day_target" placeholder="Capacity" value="<?= $editdata['per_day_target']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="batch_no"><strong>Batch Number:</strong></label>
                                        <input type="text" class="form-control" name='batch_no' id="batch_no" placeholder="Batch Number" value="<?= $editdata['batch_no']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="assign_time"><strong>Assigning Time:</strong></label>
                                        <input type="text" class="form-control clockpicker" name='assign_time' id="assign_time" placeholder="Assigning Time" value="<?= $editdata['assign_time']; ?>">
                                    </div>
                                    <div class="form-group">



                                        <label for="day_off"><strong>Day Off:</strong></label>
                                        <select name="day_off[]" multiple="multiple" data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                                            <option value="">Select Day</option>
                                            <?php
                                            
                                              $day_offArr = explode(',', $editdata['day_off']);
                                            $weekOfdays = DaysNames();
                                            foreach ($weekOfdays as $day) {
                                                if(in_array($day,$day_offArr))
                                                {
                                                echo '<option value="' . $day . '" selected>' . $day . '</option>';
                                                }
                                                else
                                                {
                                                    echo '<option value="' . $day . '">' . $day . '</option>'; 
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>







                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success">Submit</button>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js" integrity="sha512-x0qixPCOQbS3xAQw8BL9qjhAh185N7JSw39hzE/ff71BXg7P1fkynTqcLYMlNmwRDtgdoYgURIvos+NJ6g0rNg==" crossorigin="anonymous"></script>
<script type="text/javascript">
    $('.clockpicker').clockpicker({
        placement: 'top',
        align: 'left',
        donetext: 'Done'
    });

    $(document).ready(function () {
        $('.js-example-basic-multiple').select2();
    });
</script>