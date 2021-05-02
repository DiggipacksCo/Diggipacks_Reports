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
                                <h1><strong>Zid Product List</strong></h1>

                            </div>

                            <div class="panel-body" >




                                <div class="table-responsive" style="padding-bottom:20px;" >
                                <?php  
                                // $warehouse = Getwarehouse_Dropdata();
                                // $storageArr = Getallstorage_drop();
                                // echo "<pre>"; print_r($storageArr); ?> 
                                    <!--style="background-color: green;"-->
                                    <form method="post" action="<?php echo base_url(); ?>Seller/SaveZidProducts">
                                        <table class="table table-striped table-hover table-bordered dataTable bg-*" id="">
                                            <thead>
                                                <tr>
                                                    <th>Select Product</th>
                                                    <th>SKU</th>
                                                    <th>Zid ID</th>
                                                    <th>Name</th>
                                                    <th>Warehouse</th>                    
                                                    <th>Storage</th>
                                                    <th>Capacity</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $sr = 1; ?> 

                                                <?php
                                                
                                                 if (!empty($products)): ?>
                                                    <?php
                                                        $warehouse = Getwarehouse_Dropdata();
                                                        $storageArr = Getallstorage_drop();
                                                    ?>
                                                    <?php foreach ($products as $product): ?>

                                                        <tr>
                                                            <?php
                                                            $is_exist = checkZidSkuExist($product['sku'], $product['id']);
                                                            
                                                            ?>
                                                            <td><input type="checkbox" <?php echo ($is_exist)? 'checked disabled': ''?> name="selsku[]" value="<?php echo $product['sku']; ?>"></td>
                                                            <td><input type="hidden" name="sku[]" value="<?= $product['sku']; ?>"><?php echo $product['sku']; ?></td>
                                                            <td><input type="hidden" name="pid[]" value="<?= $product['id']; ?>"><?php echo $product['id']; ?></td>
                                                            <td><input type="hidden" name="skuname[]" value="<?= $product['name']; ?>"><?php echo $product['name']; ?></td>
                                                            <td>
                                                                <select class="form-control" name="warehouseid[]">
                                                                    <option value="NULL">Select Warehouse</option>
                                                                    <?php
                                                                    foreach ($warehouse as $warehose1) {
                                                                        echo '<option value="' . $warehose1['id'] . '">' . $warehose1['name'] . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td>

                                                                <select class="form-control" name="storageid[]">
                                                                    <option value="NULL">Select Storage</option>
                                                                    <?php
                                                                    foreach ($storageArr as $storage) {
                                                                        echo '<option value="' . $storage['id'] . '">' . $storage['storage_type'] . '</option>';
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" name="sku_size[]" value="10"></td>
                                                        </tr>

                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                        <input type="submit" value="Save SKU">
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


        </script>

        <!-- /page container -->

    </body>
</html>
