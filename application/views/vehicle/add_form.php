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
                            <div class="panel-heading"><h1><strong><?=lang('lang_Add_Vehicle');?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>

                                <form action="<?= base_url('Vehicle/add'); ?>" method="post" name="itmfrm" enctype="multipart/form-data">
                                    
                                    <input type="hidden" value="<?=$Edit_data['id'];?>" name="edit_id">
                                    <div class="form-group">
                                        <label for="name"><strong><?=lang('lang_Name');?>:</strong></label>
                                        <input type="text" class="form-control" name='name' id="name" placeholder="Name" value="<?=$Edit_data['name'];?>" required>

                                    </div>


                                    <div class="form-group">
                                        <label for="icon_path"><strong><?=lang('lang_Icon');?>:</strong></label>
                                          <input type="hidden" value="<?=$Edit_data['icon_path'];?>" name="old_icon_path">
                                        <input type="file" id="icon_path" name="icon_path"  class="form-control"  >

                                    </div>
                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success"><?=lang('lang_Submit');?></button>
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
    </body>
</html>

