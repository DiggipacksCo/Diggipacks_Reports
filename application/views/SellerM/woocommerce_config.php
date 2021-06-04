<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">

        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
        <style type="text/css">
            .form-group.radiosection {
                display: inline-block;
                width: 24%;
            } 
        </style>
        
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
                            <div class="panel-heading"><h1><strong>WooCommerce Configuration</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <form action="<?= base_url('Seller/updateWoocommerce/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">

                                    <fieldset class="scheduler-border" id="show_salla_details" style="">   
                                        <legend class="scheduler-border">WooCommerce <?= lang('lang_Details'); ?></legend>
                                        <div class="form-group">
                                            <label>Consumer key</label>
                                            <input type="text" class="form-control" name="consumer_key" id="consumer_key" value="<?php echo $customer['wc_consumer_key']; ?>" autocomplete="off">
                                        </div>
                                        
                                         <div class="form-group">
                                            <label>Consumer secret key</label>
                                            <input type="text" class="form-control" name="consumer_secreat_key" id="consumer_secreat_key" value="<?php echo $customer['wc_secreat_key']; ?>" autocomplete="off">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Store Url</label>
                                            <input type="text" class="form-control" name="consumer_store_url" id="consumer_store_url" value="<?php echo $customer['wc_store_url']; ?>" autocomplete="off">
                                        </div>

                                       <div class="form-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="consumer_active" <?php echo ($customer['wc_active'] == "1" ? 'checked' : ''); ?> value="1"><?= lang('lang_active'); ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="consumer_active" <?php echo ($customer['wc_active'] == "0" ? 'checked' : ''); ?> value="0"><?= lang('lang_inactive'); ?>
                                            </label>
                                        </div>
                                        
                                    </fieldset>
                                    <button type="submit" name="updatewoocommerce" value="1" class="btn btn-primary pull-right">Update</button>
                                </form>

                            </div>
                        </div>
                        <hr>
                       
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

<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }
    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
<script type="text/javascript">
    $('form').attr('autocomplete', 'off');
    $('input').attr('autocomplete', 'off');
</script>  
