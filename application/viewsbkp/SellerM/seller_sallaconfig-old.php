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
                            <div class="panel-heading"><h1><strong><?= lang('lang_Salla_Configuration'); ?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <form action="<?= base_url('Seller/updateSallaConfig/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">

                                    <fieldset class="scheduler-border" id="show_salla_details" style="">   
                                        <legend class="scheduler-border">Salla <?= lang('lang_Details'); ?></legend>
                                        <div class="form-group">
                                            <label>Salla <?= lang('lang_Authentication_Token'); ?></label>
                                            <input type="text" class="form-control" name="salla_manager_token" id="salla_manager_token" value="<?php echo $customer['salla_athentication']; ?>" autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <select name="salla_status" id="salla_status" required class="form-control">
                                                <option value="" >Select Add Status</option>
                                                <option <?php echo ($customer['salla_status'] == "created" ? 'selected' : ''); ?> value="created" >Created</option>  
                                                <option <?php echo ($customer['salla_status'] == "updated" ? 'selected' : ''); ?> value="updated" >Updated</option>  
                                            </select>

                                        </div>
                                                                   <div class="form-group">

                                            <label class="radio-inline">
                                                <input type="radio" name="salla_active" <?php echo ($customer['salla_active'] == "Y" ? 'checked' : ''); ?> value="Y"><?= lang('lang_active'); ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="salla_active" <?php echo ($customer['salla_active'] == "N" ? 'checked' : ''); ?> value="N"><?= lang('lang_inactive'); ?>
                                            </label>
                                        </div>
                                    </fieldset>
                                    <button type="submit" name="updatesalla" value="1" class="btn btn-primary pull-right"><?= lang('lang_Update'); ?></button>
                                </form>

                            </div>
                        </div>
                        <hr>
                        <div class="panel-body">
                                <form action="<?= base_url('Seller/sallaWebhookSubscribe/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">
                                    <fieldset class="scheduler-border" id="show_salla_details">   
                                        <legend class="scheduler-border"><?= lang('lang_Salla_Webhook_Subscription'); ?></legend>
                                        <div div class="form-group">
                                            <?php //echo "<pre>";print_r($customer);die;?>
                                            <?php if ($customer['salla_webhook_subscribed'] == 'Y') { ?>
                                                <a href="javascript://" class="btn btn-primary" onclick="checkWebook('<?php echo $customer['id']; ?>')"><?= lang('lang_Check_Webhook_List'); ?></a>
                                                <button type="submit" name="salla_webhook_subscribed" value="N" class="btn btn-danger"><?= lang('lang_UnSubscribe_Webhook'); ?></button> 
                                            <?php } else { ?>
                                                <button type="submit" name="salla_webhook_subscribed" value="Y" class="btn btn-primary pull-right" submit="return confirm('Are you sure you want to delete this Webook?');"><?= lang('lang_Subscribe_Webhook'); ?></button> 
                                            <?php } ?>

                                        </div>
                                        <div div class="form-group" id="webhook_id" style="display: none">

                                        </div>

                                    </fieldset>  


                                </form>
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
<script type="text/javascript">

    $('.datepppp').datepicker({
        format: 'yyyy-mm-dd'
    });

</script>

<script type="text/javascript">
    $('form').attr('autocomplete', 'off');
    $('input').attr('autocomplete', 'off');
    function checkWebook(customer_id) {
        $.ajax({
            url: '<?php echo base_url() ?>Seller/getsallaWebHooks',
            data: 'cust_id=' + customer_id,
            method: 'POST',
            beforeSend: function () {

            },
            success: function (resp) {
                $('#webhook_id').show().html(resp);
            }
        });
    }

</script> 