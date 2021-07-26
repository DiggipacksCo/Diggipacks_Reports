<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />  

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
                            <div class="panel-heading">
                                <?php if (empty($editdata)) { ?>
                                    <h1><strong><?= lang('lang_Add_Warehouse'); ?></strong></h1>
                                <?php } else { ?>
                                    <h1><strong><?= lang('lang_Update_Warehouse'); ?></strong></h1>
                                <?php } ?>  
                            </div>
                            <hr>  
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                } ?>
                                    <?php if (empty($editdata)) { ?>
                                    <form action="<?= base_url('Warehouse/add'); ?>" method="post"  name="add_customer" enctype="multipart/form-data">  
                                        <?php } else { ?>
                                        <form action="<?= base_url('Warehouse/edit/' . $id); ?>" method="post"  name="add_customer">
<?php } ?>

                                        <input type="hidden" class="form-control" id="id" name="id" value="<?php if (!empty($editdata)) echo $editdata['id']; ?>" required/>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border"><?= lang('lang_Warehouse_Details'); ?></legend>   
                                            <input type="hidden" name="editid" value="<?php if (!empty($editid)) echo $editid; ?>">

                                            <div class="form-group">
                                                <label><?= lang('lang_Warehouse_Name'); ?></label>
                                                <input type="text" class="form-control" id="company" name="name" value="<?php if (!empty($editdata)) echo $editdata['name']; ?>" required/>
                                            </div>
                                            <?php
                                            $city_ids=array();
                                            $weekendArr = array();
                                            if(!empty($editdata['city_id']))
                                            {
                                            $weekendArr[] = $editdata['city_id'];

                                            $weekendArr = explode(",", $weekendArr[0]);

                                            $city_ids = json_decode($editdata['city_id'], true);
                                            }
                                            // print_r($city_ids);
                                            ?>
                                            <div class="form-group ">
                                                <label><?= lang('lang_City'); ?></label>  
                                                <span id="city"></span>
                                                <select name="city_id[]" class="selectpicker" multiple  data-show-subtext="true" data-live-search="true" required  data-width="100%"> 

                                                    <?php
                                                    if (!empty($city_drp)) {
                                                        foreach ($city_drp as $cry) {
                                                           
                                                    // if (in_array($cry->id, $city_ids)) {
                                                               // echo "selected=selected";
                                                          //  } 
                                                    if (in_array($cry->id, $city_ids)) {
                                                           echo' <option  value="'.$cry->id.'" selected="selected" >'. $cry->city.'</option> ';
                                                    }
                                                    else
                                                    {
                                                         echo' <option  value="'.$cry->id.'" >'. $cry->city.'</option> '; 
                                                    }
                                                              }} ?>
                                     </select> 
                                              

                                            </div>



<?php if (empty($editdata)) { ?>
                                                <button type="submit" class="btn btn-primary" name="submit" value="submit"><?= lang('lang_Add_New_Warehouse'); ?></button>
<?php } else { ?>
                                                <button type="submit" class="btn btn-primary" name="submit" value="submit"><?= lang('lang_Update_Warehouse'); ?></button>   
                        <?php } ?> 


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



