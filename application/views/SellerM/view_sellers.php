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
                            <div class="panel-heading"dir="ltr">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?= lang('lang_Sellers_Table'); ?></strong></h1>

                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <!-- <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li> -->
                                        <!-- <li><a data-action="close"></a></li> -->
                                    </ul>
                                </div>
                                <hr>
                            </div>

                            <div class="panel-body" >

              <!-- <input type="text" id="search"  placeholder="Search .." class="form-control">
                                -->


                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example">
                                        <thead>
                                            <tr>

                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th>User Type</th>
                                                <th><?= lang('lang_secret_key'); ?></th>
                                                <th><?= lang('lang_Name'); ?></th>
                                                <th><?= lang('lang_Company_name'); ?></th>
                                                <th><?= lang('lang_Email'); ?></th>
                                                <th><?= lang('lang_Account_No'); ?>#</th>
                                                <th><?= lang('lang_Location'); ?></th>
                                                <th> <?= lang('lang_Phone'); ?>#1</th>
                                                <th><?= lang('lang_Invoice_Type'); ?></th>
                                                 <th>Status</th>

                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 1; ?>
                                            <?php if (!empty($sellers)): ?>
                                                <?php foreach ($sellers as $seller): ?>
                                                    <tr>
                                                        <td> <?= $sr; ?></td>
                                                        <td><?= $seller->u_type; ?></td>
                                                        <td> <?= $seller->secret_key; ?></td>    
                                                        <td><a href="<?= site_url('Seller/report_view/' . $seller->id); ?>"><?= $seller->name; ?></a></td>


                                                        <td><?= $seller->company; ?></td>
                                                        <td><?= $seller->email; ?></td>
                                                        <td><?= $seller->uniqueid; ?></td>
                                                        <td><?= $seller->address; ?></td>
                                                        <td><?= $seller->phone; ?></td>
                                                        <td><?= $seller->invoice_type; ?></td>
                                                         <?php if ($seller->status == 'N') { ?>
                                                        <td><span class="label bg-warning">Inactive</span></td>
                                                         <?php } else { ?>
                                                         <td><span class="label bg-success">Active</span></td>
                                                        <?php } ?>
                                                        <?php $sr++; ?>
                                                        <td class="text-center">
                                                            <ul class="icons-list">
                                                                <li class="dropdown">
                                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                        <i class="icon-menu9"></i>
                                                                    </a>

                                                                    <ul class="dropdown-menu dropdown-menu-right">  
                                                                        <li><a href="<?= site_url('Seller/edit_view/' . $seller->id); ?>"><i class="icon-pencil7"></i> <?= lang('lang_Edit'); ?> </a></li>
                                                                        <?php if ($seller->status == 'N') { ?>
                                                                            <li><a href="<?= site_url('active_seller/' . $seller->id . '/Y'); ?>"><i class="icon-pencil7"></i> Active  </a></li>
                                                                        <?php } else { ?>
                                                                            <li><a href="<?= site_url('active_seller/' . $seller->id . '/N'); ?>"><i class="icon-pencil7"></i> Inactive  </a></li>
                                                                        <?php } ?>


                                                                        <li><a href="<?= site_url('Seller/set_courier/' . $seller->id); ?>"><i class="icon-pencil7"></i><?= lang('lang_Set_Courier_Companies'); ?></a></li>
                                                                        <li><a href="<?= site_url('Seller/storage_charges/' . $seller->id); ?>"><i class="icon-pencil7"></i> <?= lang('lang_SetStorage_Charges'); ?></a></li>
                                                                        <li><a href="<?= site_url('Seller/add_courier_company/' . $seller->id); ?>"><i class="icon-pencil7"></i> <?= lang('lang_AddCourierCompany'); ?> </a></li>
                                                                        <?php if (menuIdExitsInPrivilageArray(107) == 'Y') { ?>
                                                                            <li><a href="<?= site_url('Seller/updateZidConfig/' . $seller->id); ?>"><i class="icon-pencil7"></i> <?= lang('lang_Zid_Configuration'); ?></a></li>
                                                                            <?php echo (($seller->manager_token != '' && $seller->zid_active == 'Y') ? '<li><a href="' . site_url("Seller/ZidProducts/" . $seller->id) . '"><i class="icon-pencil7"></i>Zid Product List</a></li>' : '') ?>
                                                                        <?php } ?>
                                                                        <?php if (menuIdExitsInPrivilageArray(106) == 'Y') { ?>
                                                                            <li><a href="<?= site_url('Seller/updateSallaConfig/' . $seller->id); ?>"><i class="icon-pencil7"></i> <?= lang('lang_Salla_Configuration'); ?></a></li>
                                                                            <?php echo (($seller->salla_athentication != '' && $seller->salla_active == 'Y') ? '<li><a href="' . site_url("Seller/SallaProducts/" . $seller->id) . '"><i class="icon-pencil7"></i>Salla Product List</a></li>' : '') ?>
                                                                        <?php } ?>

                                                                        <li><a href="<?= site_url('Seller/updateShopify/' . $seller->id); ?>"><i class="fa fa-balance-scale"></i><?= lang('lang_Shopify_Config'); ?></a></li>



                                                                        <li><a href="<?= site_url('Seller/updateWoocommerce/' . $seller->id); ?>"><i class="icon-pencil7"></i> <?= lang('lang_Woocommerce_Configuration'); ?></a></li>




                                                                        <!-- <?php //echo (($seller->manager_token != '' && $seller->zid_active == 'Y') ? '<li><a href="' . site_url("Item/updateZid_product/" . $seller->id) . '"><i class="icon-pencil7"></i>Zid Stock Update </a></li>' : '')   ?> -->


                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>

                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

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


<!-- <script>
 var $rows = $('tbody tr');
 $('#search').keyup(function() {
  var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

  $rows.show().filter(function() {
    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
    return !~text.indexOf(val);
  }).hide();
});
</script> -->


        </div>
        <script>
            $(document).ready(function () {
                var table = $('#example').DataTable({});

            });


        </script>

        <!-- /page container -->

    </body>
</html>
