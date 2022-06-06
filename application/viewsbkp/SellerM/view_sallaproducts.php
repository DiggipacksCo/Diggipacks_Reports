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
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                        <!--style="background-color: red;"-->
                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            ?> 

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Salla Product List</strong></h1>

                            </div>
                            <?php
                                                    $warehouse = Getwarehouse_Dropdata();
                                                    $storageArr = Getallstorage_drop();
                                                    ?>
                            <div class="panel-body" >
  <form method="post" action="<?php echo base_url(); ?>Seller/SaveZidProducts">
  <div class="col-md-3">
                                        <div class="form-group ">
                                        
                                            <label>Select Warehouse</label>
                                           
                           
                                                    <select  class="form-control" name="warehouseid" required>
                                                       
                                                        <?php
                                                        foreach ($warehouse as $warehose1) {
                                                            echo '<option value="' . $warehose1['id'] . '">' . $warehose1['name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <div class="form-group ">
                                        
                                        <label>Select Storage</label>

                                                    <select  class="form-control" name="storageid" required>
                                                       
                                                        <?php
                                                        foreach ($storageArr as $storage) {
                                                            echo '<option value="' . $storage['id'] . '">' . $storage['storage_type'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                    </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                    <div class="form-group ">
                                        
                                        <label>Select Sku Capacity</label>
                                        <input type="number" class="form-control"  required name="sku_size" value="10" required>

                                        </div>
                                        </div>
                                                    <div class="col-md-3">
                                        <div class="form-group ">
                                        
                                       
   <input type="submit" class="btn btn-primary pull-right" value="Save SKU" style="margin-top:27px" /> </div>

</div>
</div>
                                                   

                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                  
                                        <table class="table table-striped table-hover table-bordered dataTable bg-*" id="">
                                            <thead>
                                                <tr>
                                                    <th> <input type="checkbox" id="checkAll" /> &nbsp;Select Product</th>
                                                    <th>SKU</th>
                                                    <th>Salla ID</th>
                                                    <th>Name</th>
                                                   
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $sr = 0; ?>

                                                <?php if (!empty($products)): ?>
                                                   
                                                    <?php foreach ($products as $key=>$product): ?>

                                                        <tr>
                                                            <?php
                                                            $is_exist = exist_zidsku_id($product['sku'], $this->session->userdata('user_details')['super_id']);
                                                           if(empty($is_exist))
                                                           {
                                                            
                                                            ?>
                                                            <td><input type="checkbox"  name="selsku[]" value="<?php echo $sr; ?>"></td>
                                                            <td><input type="hidden" name="sku[]" value="<?= $product['sku']; ?>"><?php echo $product['sku']; ?></td>
                                                            <td><input type="hidden" name="pid[]" value="<?= $product['id']; ?>"><?php echo $product['id']; ?></td>
                                                            <td><input type="hidden" name="skuname[]" value="<?= $product['name']; ?>"><?php echo $product['name']; ?></td>
                                                            
                                                           <?php  $sr++; } ?>
                                                        </tr>

                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                     
                                    </form>

                                </div>
                                <!--  <div>
                                   <center>
                                <?php //echo $links;  ?> 
                                  </center>
                                </div> -->
                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table --> 
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->


                </div>
                <!-- /main content -->


            </div>
            <!-- /page content -->





        </div>
        <script>
            $(document).ready(function () {
                var table = $('#example').DataTable({});

            });

            $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });

        </script>

        <!-- /page container -->

    </body>
</html>
