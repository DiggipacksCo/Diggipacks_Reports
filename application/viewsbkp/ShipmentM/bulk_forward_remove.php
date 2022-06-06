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

                        if ($this->session->flashdata('error'))
                            echo '<div class="alert alert-warning">'.$this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>';
                        ?>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong><?=lang('lang_Forward_Remove');?></strong> </h1>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row"> </div>
                                        <div class="alert alert-danger"><?=lang('lang_Note');?> <?=lang('lang_Order_Limit_is_two_Hundred');?></div>
                                        <form  method="post" action="<?= base_url(); ?>Shipment/bulk_forward_remove"  >
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="8" id="show_awb_no" name="tracking_numbers"  placeholder="<?=lang('lang_Please_Enter_Your_AWB_Number');?>" required class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-1" >
                                                <div class="form-group">

                                                    <span  id="rowcount" class="btn btn-danger  btn-sm" >0</span>

                                                </div> 
                                            </div> 
                                            
                                            <div class="col-md-2" >
                                            <div class="form-group">

                                            <input type="submit" name="track_ready" class="btn btn-primary form-control checkdisable" value="<?=lang('lang_Remove');?>">	

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
 $("#show_awb_no").on("change input paste keyup", function() {
	// alert("ssssss");
   //$("#res").html(jQuery(this).val());
 var value = $("#show_awb_no").val();   
    var items = value.split('\n');
    var lines = 0;
    for(var no=0;no<items.length;no++){
        lines += Math.ceil(items[no].length/40);    }
        document.getElementById('rowcount').innerHTML =lines;<!---->
if(parseInt(lines)>200)
{
  $(".checkdisable").attr("disabled", true);
}
else
{
  $(".checkdisable").attr("disabled", false);
}
});


</script>

        <!-- /page container -->

    </body>
</html>
