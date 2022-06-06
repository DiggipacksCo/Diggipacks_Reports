<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
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
                          
                          

                            <div class="panel-body">
                                  <div class="panel-heading"><h1><strong>Import Bulk Location</strong><a href="<?= base_url('Excel_export/Importlocation'); ?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1></div>
                                  
                                  
                               <?php
		 
		 
		  if(!empty($this->session->flashdata('errorA')))
		  {
			 // print_r($this->session->flashdata('errorA'));
			  foreach($this->session->flashdata('errorA')['validrowtate'] as $validAlertState)
			  {
				 echo '<div class="alert alert-success">Row '.$validAlertState.' Successfulyy Added Hub Name  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			  }
                          foreach($this->session->flashdata('errorA')['validrowcity'] as $validAlertcity)
			  {
				 echo '<div class="alert alert-success">Row '.$validAlertcity.' Successfulyy Added city  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			  }
                          foreach($this->session->flashdata('errorA')['duplicate_city'] as $duplicate_cty)
			  {
				 echo '<div class="alert alert-danger">Row '.$duplicate_cty.' city already exists   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			  }
                          foreach($this->session->flashdata('errorA')['duplicatefile_city'] as $duplicate_filecty)
			  {
				 echo '<div class="alert alert-danger">Row '.$duplicate_filecty.' city duplicate in file   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			  }
                           foreach($this->session->flashdata('errorA')['state_empty'] as $duplicate_filecty)
			  {
				 echo '<div class="alert alert-warning">Row '.$duplicate_filecty.' Hub Name is empty   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			  }
                           foreach($this->session->flashdata('errorA')['city_empty'] as $duplicate_filecty)
			  {
				 echo '<div class="alert alert-warning">Row '.$duplicate_filecty.' city Name is empty    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			  }
                           foreach($this->session->flashdata('errorA')['citycode_empty'] as $duplicate_filecty)
			  {
				 echo '<div class="alert alert-warning">Row '.$duplicate_filecty.' city code is empty   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
			  }
			  
			  
			  
			  
			 
		  }
		  ?>
<?php if($this->session->flashdata('msg')):?>
<?= '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?> 
<?php elseif($this->session->flashdata('error')):?>
<?= '<div class="alert alert-danger">'.$this->session->flashdata('error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?>
<?php endif;?>   
                            <div class="alert alert-danger"><strong>Note </strong><br>&nbsp1. To add bulk of items use this import feature. Below are the columns you must have according to serial number in the excel csv file.<br>&nbsp2. All fields are required.<br>&nbsp2. Click above excel icon to get excel file for an idea.</div>  <hr>

                                <br> 
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                        <tr>

                                         
                                            <td>(1) Hub <span style="color:#F00"><strong>*</strong></span></td>
                                             <td>(2) City <span style="color:#F00"><strong>*</strong></span></td>
                                              <td>(3) City Code <span style="color:#F00"><strong>*</strong></span></td>

                                        </tr>
                                        <tr>
                                           

                                           

                                          
                                        </tr>

                                    </tbody>
                                </table>
                                <br>
                                <form class="stdform" method="post" action="<?= base_url('Excel_export/addImportCity'); ?>" name="add_ship" enctype="multipart/form-data">
                                    <label><strong class="alert-danger">Import File</strong></label>
                                    <span class="field">
                                        <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                                        <!-- <span id="weight" class="alert"></span> -->
                                    </span><br> 
                                    <button type="submit"  class="btn btn-success pull-left">Add Location</button> 
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
