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
                                    <h1><strong>Storage Setting(<?=$wareArr['name'];?>)</strong></h1>
                                <?php } else { ?>
                                    <h1><strong>Storage Setting(<?=$wareArr['name'];?>)</strong></h1>
                                <?php } ?>  
                            </div>
                             <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">'.$this->session->flashdata('err_msg') . '.</div>';
                                } ?>
                                   
                                        <form action="<?= base_url('Warehouse/storage_processs/' . $id); ?>" method="post"  name="add_customer">


                                        <input type="hidden" class="form-control" id="id" name="id" value="<?php if (!empty($editdata)) echo $editdata['id']; ?>" required/>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border">Storage Capacity</legend>   
                                            <input type="hidden" name="editid" value="<?php if (!empty($id)) echo $id; ?>">
                                            
                                            <?php 
                                            foreach($storageArr as $val)
                                            {
                                                $size=GetStorageData($id,$val['id']);
                                            echo'<div class="form-group">
                                                <label>'.$val['storage_type'].'</label>
                                                <input type="number" min="0" class="form-control" placeholder="Enter Capacity" name="capacity['.$val['id'].']" value="'.$size.'" required/>
                                            </div>';
                                            }
                                            
                                            ?>
                                         <button type="submit" class="btn btn-primary" name="submit" value="submit">Update</button>   
                        


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



