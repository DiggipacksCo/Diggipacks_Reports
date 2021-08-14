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
            
        .subject-info-box-1,
        .subject-info-box-2 {
            float: left;
            width: 45%;
            
            select[multiple],
        select[size] {
        

            height: 400px !important;
            padding: 0;

            option {
                padding: 4px 10px 4px 10px;
            }

            option:hover {
                background: #EEEEEE;
            }
        }
    }

.subject-info-arrows {
    float: left;
    width: 10%;

    input {
        width: 70%;
        margin-bottom: 5px;
    }
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
                           



                            <hr>
                            <?php if ($customer['zid_webhook_subscribed'] == 'Y') { ?>
                            <div class="panel-body">
                                <form action="<?= base_url('Seller/zidWebhookSubscribe/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">
                                    <fieldset class="scheduler-border" id="show_zid_details">   
                                        <legend class="scheduler-border">Check Webhook List</legend>
                                        <div div class="form-group">
                                      
                                           
                                        
                                                <a href="javascript://" class="btn btn-primary" onclick="checkWebook('<?php echo $customer['id']; ?>')">Check Webhook List</a>
<!--                                                <button type="submit" name="zid_webhook_subscribed" value="N" class="btn btn-danger">UnSubscribe Webhook</button> -->

                                        </div>
                                        <div div class="form-group" id="webhook_id" style="display: none">

                                        </div>

                                    </fieldset>  


                                </form>
                            </div>
                           
                            <?php }   ?>
  <div class="panel-body">
                                <form action="<?= base_url('Seller/zidWebhookSubscribe/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">
                                    <fieldset class="scheduler-border" id="show_zid_details">   
                                        <legend class="scheduler-border">Zid Webhook UnSubscribe</legend>
                                        <div div class="form-group">
                                        <div class="subject-info-box-1">
                                          
                                    <select  id='zid_delivery_name11'  name="zid_delivery_name" class="form-control">
                                    <?php if (!empty($delivery_options)): ?>
                                                <?php foreach ($delivery_options as $rows):
                                                   if($rows['subscribed']=='Y') { ?>
                                    <option value="<?= $rows['id']; ?>"> <?= $rows['zid_delivery_name']; ?> </option>
                                            <?php } endforeach; ?>
                                            <?php endif; ?>
                                    </select>
                                    </div>
                                           
                                        
                                                <button type="submit" name="zid_webhook_subscribed" value="N" class="btn btn-danger pull-right" >UnSubscribe Webhook</button> 
                                          

                                        </div>
                                        <div div class="form-group" id="webhook_id" style="display: none">

                                        </div>

                                    </fieldset>  


                                </form>
                            </div>
                                <div class="panel-body">
                                <form action="<?= base_url('Seller/zidWebhookSubscribe/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">
                                    <fieldset class="scheduler-border" id="show_zid_details">   
                                        <legend class="scheduler-border">Zid Webhook Subscribe</legend>
                                        <div div class="form-group">
                                        <div class="subject-info-box-1">
                                          
                                    <select  id='zid_delivery_name11'  name="zid_delivery_name" class="form-control">
                                    <?php if (!empty($delivery_options)): ?>
                                                <?php foreach ($delivery_options as $rows):
                                                   if($rows['subscribed']=='N') { ?>
                                    <option value="<?= $rows['id']; ?>"> <?= $rows['zid_delivery_name']; ?> </option>
                                            <?php } endforeach; ?>
                                            <?php endif; ?>
                                    </select>
                                    </div>
                                           
                                        
                                                <button type="submit" name="zid_webhook_subscribed" value="Y" class="btn btn-primary pull-right" submit="return confirm('Are you sure you want to delete this Webook?');">Subscribe Webhook</button> 
                                          

                                        </div>
                                        <div div class="form-group" id="webhook_id" style="display: none">

                                        </div>

                                    </fieldset>  


                                </form>
                            </div>
                         
                            <div class="panel-body">
                                   
                                    <fieldset class="scheduler-border" id="show_zid_details">   
                                        <legend class="scheduler-border">Zid Delivery Option List</legend>
                                        
                                          
                                 
                                    <?php if (!empty($delivery_options)): ?>
                                              
                                            <table class="table table-striped table-hover table-bordered dataTable bg-* "  id="">
                                            <thead>
                                                <tr>
                                                <th>Sr No.</th>
                                                    <th>Name</th>
                                                    <th>Id</th>
                                                    <th>Subscribed</th>
                                                    <th>Delivery Cost</th>
                                                    <th>COD Enable</th>
                                                    <th> Estimated Delivery Time </th>
                                                    <th>Edit </th>
                                                   
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $sr = 1; ?>

                                                <?php if (!empty($delivery_options)): ?>
                                                   
                                                    <?php foreach ($delivery_options as $key=>$product): ?>

                                                        <tr>
                                                        <td><?php echo $key+1;  ?></td>
                                                      
                                                            <td><?php echo $product['zid_delivery_name']; ?></td>
                                                            <td><?php echo $product['delivery_id']; ?></td>
                                                            <td><?php if($product['subscribed']=='Y'){ echo 'Yes';}else{ echo "NO";} ?></td>
                                                            
                                                          
                                                            <td><?php echo $product['zid_delivery_cost']; ?></td>
                                                            <td><?php echo $product['zid_cod_enabled']; ?></td>
                                                            <td><?php echo $product['delivery_estimated_time_en']; ?></td>

                                                            <td class="text-center">
                        <ul class="icons-list">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="icon-menu9"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">  
                              <li><a href="<?= base_url('Seller/updateZidConfig/' . $customer['id'].'/'.$product['id']);  ?>"><i class="icon-pencil7"></i> <?= lang('lang_Edit'); ?> </a></li>
                               <li><a href="<?= base_url('Seller/deleteDeliveryOption/' . $customer['id'].'/'.$product['id']);?>"  onclick="return confirm('Are you sure?')"><i class="icon-trash"></i>Delete</a></li>
                            </ul>
                          </li>
                        </ul>
                      </td>
                                                           
                                                        </tr>

                                                    <?php endforeach; ?>
                                               
                                                    <?php endif; ?>
                                            </tbody>
                                        </table>          
                                  
                                 
                                         
                                    </fieldset>  
                                    <?php endif; ?>

                               
                            </div>

                            <div class="panel-body">
                                <form action="<?= base_url('Seller/zidDeliveryOptionAdd/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">
                                    <fieldset class="scheduler-border" id="show_zid_details">   
                                        <legend class="scheduler-border">Delivery Option</legend>
                                        <div class="form-group">
                                            <label>Name</label>
                                            
                                            <input type="text" required="" class="form-control" name="zid_delivery_name" id="zid_delivery_name" value="<?php echo isset($delivery_option_edit['zid_delivery_name']) ? $delivery_option_edit['zid_delivery_name'] : ''; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Cost</label>
                                            <input type="text" required="" class="form-control" name="zid_delivery_cost" id="zid_delivery_cost" value="<?php echo isset($delivery_option_edit['zid_delivery_cost']) ? $delivery_option_edit['zid_delivery_cost'] : ''; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Cod Enabled</label>
                                            <select name="zid_cod_enabled" id="zid_cod_enabled" required class="form-control">
                                                <option value="" >Select Cod Status</option>
                                                <option <?php echo ($delivery_option_edit['zid_cod_enabled'] == "1" ? 'selected' : ''); ?> value="1" >Enabled</option>  
                                                <option <?php echo ($delivery_option_edit['zid_cod_enabled'] == "0" ? 'selected' : ''); ?> value="0" >Disabled</option>  
                                            </select>
                                            <!--<input type="text" required="" class="form-control" name="zid_code_enabled" id="zid_code_enabled" value="<?php echo isset($delivery_option_edit['zid_code_enabled']) ? $delivery_option_edit['zid_code_enabled'] : ''; ?>"/>-->
                                        </div>

                                        <div class="form-group">
                                            <label>Cod Fee</label>
                                            <input type="text" required="" class="form-control" name="zid_cod_fee" id="zid_cod_fee" value="<?php echo isset($delivery_option_edit['zid_cod_fee']) ? $delivery_option_edit['zid_cod_fee'] : ''; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Delivery Estimated Time ar</label>
                                            <input type="text" required="" class="form-control" name="delivery_estimated_time_ar" id="delivery_estimated_time_ar" value="<?php echo $delivery_option_edit['delivery_estimated_time_ar']; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Delivery Estimated Time en</label>
                                            <input type="text" required="" class="form-control" name="delivery_estimated_time_en" id="delivery_estimated_time_en" value="<?php echo $delivery_option_edit['delivery_estimated_time_en']; ?>"/>
                                        </div>

                                        <div class="form-group">
                                        <!-- djhfjdhjdhfjdhfjd -->

                                        <div class="subject-info-box-1">
                                    <select multiple="multiple" id='lstBox1'  class="form-control">
                                    <?php if (!empty($ListArr)): ?>
                                                <?php foreach ($ListArr as $rows):
                                                    ?>
                                    <option value="<?= $rows['id']; ?>"> <?= $rows['en_name']; ?> </option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                    </select>
                                    </div>
                                    <div class="subject-info-arrows text-center">
                                    <input type='button' style="margin-top:5px;" id='btnAllRight' value='>>' class="btn btn-info" /><br />
                                    <input type='button' style="margin-top:5px;"id='btnRight' value='>' class="btn btn-info" /><br />
                                    <input type='button' style="margin-top:5px;" id='btnLeft' value='<' class="btn btn-info" /><br />
                                    <input type='button' style="margin-top:5px;" id='btnAllLeft' value='<<' class="btn btn-info" />
                                    </div>
                                    <div class="subject-info-box-2">
                                    <select multiple="multiple" name="zid_city[]" id='lstBox2' class="form-control">
                                    <?php if (!empty($pre)): 
                                        ?>
                                        <?php foreach ($pre as $rows): ?>
                                    <option selected  value="<?= $rows['id']; ?>" > <?= $rows['en_name']; ?> </option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>  
                                    </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <br/><br/>
                                    <button class="btn btn-warning  " type="button"  id="selectAll">Confirm</button>
                                    <button type="submit" name="deliver_option" value="1" class="btn btn-primary">Update</button> 
                                   
                                    
                                </div>


                                        <!-- dklfjflkjdfkjskdjkd -->
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

    $('#all').change(function (e) {
        console.log(e.currentTarget.checked);
        if (e.currentTarget.checked) {
            $('.citycheck').prop('checked', true);
        } else {
            $('.citycheck').prop('checked', false);
        }
    });

</script>
<Script>
        $(document).ready(function(){
        $('#selectAll').click(function(){
           
            $('#lstBox2 option').prop('selected', true);
            $('#subButton').prop('disabled', false);
        });
        });


        (function () {
        $("#btnRight").click(function (e) {
            var selectedOpts = $("#lstBox1 option:selected");
            if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
            }

            $("#lstBox2").append($(selectedOpts).clone());
            $(selectedOpts).remove();
            $('#subButton').prop('disabled', true);
            e.preventDefault();
        });

        $("#btnAllRight").click(function (e) {
        var isconfirm= confirm("Do you really want to add cities! its huge...");
        if(isconfirm)
        {


        var selectedOpts = $("#lstBox1 option");
            if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
            }

            $("#lstBox2").append($(selectedOpts).clone());
            $(selectedOpts).remove();
            $('#subButton').prop('disabled', true);
            e.preventDefault();
        }
        });

        $("#btnLeft").click(function (e) {
            var selectedOpts = $("#lstBox2 option:selected");
            if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
            }

            $("#lstBox1").append($(selectedOpts).clone());
            $(selectedOpts).remove();
            $('#subButton').prop('disabled', true);
            e.preventDefault();
        });

        $("#btnAllLeft").click(function (e) {
            var selectedOpts = $("#lstBox2 option");
            if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
            }

            $("#lstBox1").append($(selectedOpts).clone());
            $(selectedOpts).remove();
            $('#subButton').prop('disabled', true);
            e.preventDefault();
        });
        })(jQuery);
</Script>