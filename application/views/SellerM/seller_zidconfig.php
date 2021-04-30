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


        <title>Inventory</title>
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
                            <div class="panel-heading"><h1><strong> Zid Configuration</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <form action="<?= base_url('Seller/updateZidConfig/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">
                                    <div class="form-group ">
                                        <p style="display:none;">
                                            <label>Account No</label>
                                            <input type="text" name="uniqueid" readonly="1" class="form-control" />
                                    </div>

                                    <fieldset class="scheduler-border" id="show_zid_details">   
                                        <legend class="scheduler-border">Zid Details</legend>
                                        <div class="form-group">
                                            <label>X-MANAGER-TOKEN</label>
                                            <input type="text" class="form-control" name="manager_token" id="manager_token" value="<?php echo $customer['manager_token']; ?>"/>


                                        </div>

                                        <div class="form-group">
                                            <label>Zid Store ID</label>
                                            <input type="text"  class="form-control" name="zid_sid" id="zid_sid" value="<?php echo $customer['zid_sid']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <select name="zid_status" id="zid_status" required class="form-control">
                                                <option value="" >Select Zid Status</option>
                                                <option <?php echo ($customer['zid_status'] == "new" ? 'selected' : ''); ?> value="new" >New</option>  
                                                <option <?php echo ($customer['zid_status'] == "ready" ? 'selected' : ''); ?> value="ready" >Ready</option>  
                                            </select>
                                        </div>

                                        <div class="form-group">

                                            <label class="radio-inline">
                                                <input type="radio" name="zid_active" <?php echo ($customer['zid_active'] == "Y" ? 'checked' : ''); ?> value="Y">Active
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="zid_active" <?php echo ($customer['zid_active'] == "N" ? 'checked' : ''); ?> value="N">Inactive
                                            </label>
                                        </div>
                                    </fieldset>  

                                    <button type="submit" name="updatezid" value="1" class="btn btn-primary pull-right">Update</button> 
                                </form>

                            </div>
                            <hr>
                            <div class="panel-body">
                                <form action="<?= base_url('Seller/zidWebhookSubscribe/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">
                                    <fieldset class="scheduler-border" id="show_zid_details">   
                                        <legend class="scheduler-border">Zid Webhook Subscription</legend>
                                        <div div class="form-group">
                                            <?php //echo "<pre>";print_r($customer);die;?>
                                            <?php if ($customer['zid_webhook_subscribed'] == 'Y') { ?>
                                                <a href="javascript://" class="btn btn-primary" onclick="checkWebook('<?php echo $customer['id']; ?>')">Check Webhook List</a>
                                                <button type="submit" name="zid_webhook_subscribed" value="N" class="btn btn-danger">UnSubscribe Webhook</button> 
                                            <?php } else { ?>
                                                <button type="submit" name="zid_webhook_subscribed" value="Y" class="btn btn-primary pull-right" submit="return confirm('Are you sure you want to delete this Webook?');">Subscribe Webhook</button> 
                                            <?php } ?>

                                        </div>
                                        <div div class="form-group" id="webhook_id" style="display: none">

                                        </div>

                                    </fieldset>  


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
    function checkWebook(customer_id) {
        $.ajax({
            url: '<?php echo base_url() ?>Seller/getZidWebHooks',
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
<script type="text/javascript">

    $('.datepppp').datepicker({

        format: 'yyyy-mm-dd'

    });

</script>