<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
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
                                <div class="panel-heading" dir="ltr"><h1><strong><?= lang('lang_Bulk_Upload'); ?></strong><a href="<?= base_url('Excel_export/promocode_bulk_format'); ?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1></div>
                                <div class="alert alert-danger"><strong><?= lang('lang_Note'); ?> : </strong><br>&nbsp1.  <?= lang('lang_Toexcel_csvv_file_get_excel_idea'); ?>.<br>&nbsp2. <?= lang('lang_All_fields_are_required'); ?>.<br>&nbsp3. <?= lang('lang_Click_above_excel_icon_excel_file_idea'); ?>.</div>
                                <hr>

                                <br> 
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>

                                        <tr>

                                            <td>(1) Seller Account No. <span style="color:#F00"><strong>*</strong></span></td>
                                            <td>(2) Offer Code <span style="color:#F00"><strong>*</strong></span></td>
                                            <td>(3) Main Item <span style="color:#F00"><strong>*</strong></span></td>



                                        </tr>
                                        <tr>

                                            <td>(4) Qty <span style="color:#F00"><strong>*</strong></span></td>
                                            <td>(5) Start Date <span style="color:#F00"><strong>*</strong></span></td> 

                                            <td>(6) End Date <span style="color:#F00"><strong>*</strong></span></td>




                                        </tr>




                                    </tbody>
                                </table>
                                <br>
                                <form class="stdform" method="post" action="<?= base_url('Excel_export/add_bulk_promo_offers'); ?>" id="AddnventoryID" name="AddnventoryID" enctype="multipart/form-data" onsubmit="document.getElementById('Newaddfrm').disabled = true; processFormData();">


                                    <label><strong class="alert-danger"><?= lang('lang_Import_Excel_File'); ?></strong></label>
                                    <span class="field">
                                        <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                                        <!-- <span id="weight" class="alert"></span> -->
                                    </span><br> 
                                    <button type="submit" id="Newaddfrm"  class="btn btn-success pull-left">Add Offer</button> 
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

<link href="<?= base_url(); ?>assets/colorpicker/jquery.colorpicker.bygiro.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/colorpicker/jquery.colorpicker.bygiro.min.js"></script>

<script>
                                    $('.myColorPicker').colorPickerByGiro({
                                        preview: '.myColorPicker-preview',
                                        showPicker: true,
                                        format: 'hex',
                                        sliderGap: 6,

                                        cursorGap: 6,
                                        text: {

                                            close: 'Close',

                                            none: 'None'

                                        }




                                    });
</script>

<script>
    processFormData = function (event) {
//alert("ssssss");
// For this example, don't actually submit the form
        event.preventDefault();


        var Elem = event.target;
        if (Elem.nodeName == 'td') {
            $("#AddnventoryID").submit()
        }






    };


</script>
