<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
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
                        <div class="panel-heading"><h1><strong>Bulk Upload</strong><a href="<?= base_url('Excel_export/item_bulk_format');?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1></div>
                        <div class="alert alert-danger"><strong>Note </strong><br>&nbsp1. To add bulk of items use this import feature. Below are the columns you must have according to serial number in the excel csv file.<br>&nbsp2. All fields are required.<br>&nbsp2. Click above excel icon to get excel file for an idea.</div>
                        <hr>

                        <div class="panel-body">
               
                <br> 
                <table class="table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <td>(1) Name <span style="color:#F00"><strong>*</strong></td>
                        <td>(2) Sku <span style="color:#F00"><strong>*</strong></td>
                        <td>(3) Description <span style="color:#F00"><strong>*</strong></span></td>
                    </tr>
                    
                </tbody>
                </table>
                <br>
                <form class="stdform" method="post" action="<?= base_url('Excel_export/add_item_bulk');?>" name="add_ship" enctype="multipart/form-data">
                <label><strong class="alert-danger">Import File</strong></label>
                <span class="field">
                    <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                    <!-- <span id="weight" class="alert"></span> -->
                </span><br> 
                <button type="submit"  class="btn btn-success pull-left">Add Shipment</button> 
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
