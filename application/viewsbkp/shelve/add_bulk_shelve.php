<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
    <title><?=lang('lang_Inventory');?></title>
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
            <div class="content-wrapper">

                <?php $this->load->view('include/page_header'); ?>


                <!-- Content area -->
                <div class="content">
            
                    <div class="panel panel-flat">
                        <div class="panel-heading" dir="ltr"><h1><strong><?=lang('lang_Bulk_Upload');?> <?=lang('lang_Shelve');?></strong><a href="<?= base_url('Excel_export/bulk_format_shelve');?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1></div>
                        <div class="alert alert-danger"><strong><?=lang('lang_Note');?> </strong><br>&nbsp1. <?=lang('lang_To_add_bulk_of_Shelve_use_this_import_feature');?>.<br>&nbsp2.  <?=lang('lang_All_fields_are_required');?>.<br>&nbsp3. <?=lang('lang_Click_above_excel_icon_excel_file_idea');?>.</div>
                        <hr>
 <?php if(!empty($this->session->flashdata('dupmsg'))){echo '<div class="alert alert-warning" role="alert"> Duplicate Shelve Nos  '.$this->session->flashdata('dupmsg').'.</div>';}?>
                        <?php if(!empty($this->session->flashdata('emptyrow'))){echo '<div class="alert alert-warning" role="alert"> empty rows '.$this->session->flashdata('emptyrow').'.</div>';}?>
                          <?php if(!empty($this->session->flashdata('successRow'))){echo '<div class="alert alert-success" role="alert"> Added Rows '.$this->session->flashdata('successRow').'.</div>';}?>
                        
                        

                        <div class="panel-body">
               
                <br> 
                <table class="table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <td>(1) <?=lang('lang_Shelve');?> <span style="color:#F00"><strong>*</strong></td>
                        <td>(2) <?=lang('lang_City');?> <span style="color:#F00"><strong>*</strong></td>
                    </tr>
                    
                </tbody>
                </table>
                <br>
                <form class="stdform" method="post" action="<?= base_url('Excel_export/add_shelve_bulk');?>" name="add_ship" enctype="multipart/form-data">
                <label><strong class="alert-danger"><?=lang('lang_Import_File');?></strong></label>
                <span class="field">
                    <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                    <!-- <span id="weight" class="alert"></span> -->
                </span><br> 
                <button type="submit"  class="btn btn-success pull-left"><?=lang('lang_Add_Shelve');?></button> 
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
