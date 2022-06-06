<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
    </head>

    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" > 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  > 
                        <!--style="background-color: red;"-->

                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something').'  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>';
                        ?>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong><?=lang('lang_PrintSKUBarcode');?></strong> </h1>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row"> </div>
                                        <div class="alert alert-danger"><?=lang('lang_Note');?>: <?=lang('lang_SKU_Limit_is_fifty');?></div>
                                        <form  method="post" action="<?= base_url(); ?>Item/BulkPrintSKU" target="_blank"  >
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="8" id="sku_barcode" name="sku_barcode"  placeholder="Please Enter Your SKU Number" required class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-1" >
                                                <div class="form-group">

                                                    <span  id="rowcount" class="btn btn-danger  btn-sm" >0</span>

                                                </div> 
                                            </div> 
                                            <div class="col-md-2" >
                                                <div class="form-group">
                                                    <select name="seller_id" class="form-control" id="seller_id" required="">
                                                        <option value=""><?=lang('lang_Select_Seller');?></option>
                                                        <?php
                                                        $seller_arr = Getallsellerdata();
                                                        foreach ($seller_arr as $val)
                                                        {
                                                        echo '<option value="'.$val['id'].'"> '.$val['name'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    	

                                                </div> 
                                            </div> 
                                            <div class="col-md-2" >
                                                <div class="form-group">

                                                    <input type="number" min="1"  name="sku_qty" class="form-control" required="">	

                                                </div> 
                                            </div> 
                                            <div class="col-md-2" >
                                                <div class="form-group">

                                                    <input type="submit" name="print_ready" class="btn btn-primary form-control checkdisable" value="<?=lang('lang_Print_Barcode');?>">	

                                                </div> 
                                            </div> 


                                        </form>
                                    </div>


                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /dashboard content --> 

                <!-- /basic responsive table --> 

            </div>
            <!-- /content area --> 
        </div>
        <?php $this->load->view('include/footer'); ?>

        <script type="text/javascript">
            $(".checkdisable").attr("disabled", true);
            $("#sku_barcode").on("change input paste keyup", function () {
                // alert("ssssss");
                //$("#res").html(jQuery(this).val());
                var value = $("#sku_barcode").val();
                var items = value.split('\n');
                var lines = 0;
                for (var no = 0; no < items.length; no++) {
                    lines += Math.ceil(items[no].length / 40);
                }
                document.getElementById('rowcount').innerHTML = lines;<!---->
                if (parseInt(lines) > 50)
                {
                    $(".checkdisable").attr("disabled", true);
                } else
                {
                    $(".checkdisable").attr("disabled", false);
                }
            });


        </script>

        <!-- /page container -->

    </body>
</html>
