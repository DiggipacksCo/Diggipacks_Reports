<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

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
      <div class="content-wrapper" >

        <?php $this->load->view('include/page_header'); ?>

        
        <!-- Content area -->
        <div class="content" >

          <!-- Dashboard content -->
          <div class="row" >
            <div class="col-lg-12" >

              <!-- Marketing campaigns -->
              <div class="panel panel-flat">
                <div class="panel-heading">
                  <h1 class="panel-title"><strong>Seller Report</strong></h1>
                  
                </div>
                <hr>
                

                <!-- Quick stats boxes -->
                <div class="row" style="padding-top: 10px;">
                  <div class="col-lg-6" style="padding-left: 60px;padding-right: 60px; ">

                    <!-- Today's revenue -->
                   
                    <div class="panel bg-teal-400">

                      <div class="panel-body" style="height:300px">
                        <div class="icon-user" style="padding-bottom: 10px;"> <?= $seller_info->name;?></div><br>
                        <div class="icon-mail-read" style="padding-bottom: 10px;"> <?= $seller_info->email;?></div><br>
                        <div class="icon-hash" style="padding-bottom: 10px;"> <?= $seller_info->account_no;?></div><br>
                        <div class="icon-phone2" style="padding-bottom: 10px;"> <?= $seller_info->phone;?></div><br>
                        <div class="icon-pin-alt" style="padding-bottom: 10px;"> <?= $customer_info->address;?></div><br> 
                        
                         <div class="icon-file-pdf" style="padding-bottom: 10px; "> 
                         <a href="<?=base_url();?><?= $customer_info->upload_cr;?>" class="btn btn-success" style="color:white;" target="_blank">Uploaded CR</a> 
                         </div><br>   
                         <div class="icon-file-pdf" style="padding-bottom: 10px; "> 
                        
                         <a href="<?=base_url();?><?= $customer_info->upload_id;?>" class="btn btn-success" style="color:white;" target="_blank">Uploaded ID</a> 
                        </div><br>   
                         <div class="icon-file-pdf" style="padding-bottom: 10px; "> 
                        
                         <a href="<?=base_url();?><?= $customer_info->upload_contact;?>" class="btn btn-success" style="color:white;" target="_blank">Uploaded Contract	</a></div><br>   
                        
                      </div>
                      
                      <div id="today-revenue"></div>
                    </div>
                    
                    <!-- /today's revenue -->

                  </div>
                  <div class="col-lg-6" style="padding-left: 60px; padding-right: 60px;">
                     <a id="shipment">
                    <!-- Today's revenue -->
                    <div class="panel bg-teal-400">

                      <div class="panel-body">
                        <div class="heading-elements">
                          <ul class="icons-list">
                            <li></li>
                              
                          </ul>
                        </div>
                        
                        <h3 class="no-margin"><?= count($seller_shipments);?></h3>
                        Total Shipments
                        <!-- <div class="text-muted text-size-small">34.6% avg</div> -->
                      </div>
                      
                      <div id="today-revenue"></div>
                    </div>
                    </a>
                    <!-- /today's revenue -->

                  </div>
                  <div class="col-lg-6" style="padding-left: 60px; padding-right: 60px;">
                  <a id="inventory">
                    <!-- Item Inventory Count -->
                    <div class="panel bg-teal-400">

                      <div class="panel-body">
                        <div class="heading-elements">
                          <ul class="icons-list">
                            <li></li>
                              
                          </ul>
                        </div>
                        
                        <h3 class="no-margin"><?= $total_inventory_items;?></h3>
                        Total Items in Inventory
                        <!-- <div class="text-muted text-size-small">34.6% avg</div> -->
                      </div>
                      
                      <div id="today-revenue"></div>
                    </div>
                    </a>
                    <!-- /Item Inventory Count -->

                  </div>
                  
                </div>
                <!-- /quick stats boxes -->
              </div>
            </div>
          </div>
          <!-- /dashboard content -->


                    <!-- Basic responsive table -->
          <div class="panel panel-flat"  id="table1" style="display: none;">
            <!--style="padding-bottom:220px;background-color: lightgray;"-->

            <div class="panel-heading">
              <!-- <h5 class="panel-title">Basic responsive table</h5> -->
              <h3><strong>Seller Shipments</strong></h3>

              <div class="heading-elements">
                <ul class="icons-list">
                  <!-- <li><a data-action="collapse"></a></li>
                  <li><a data-action="reload"></a></li> -->
                  <!-- <li><a data-action="close"></a></li> -->
                </ul>
              </div>
              <hr>
            </div>

            <div class="panel-body">

              <!-- <input type="text" id="search"  placeholder="Search .." class="form-control">
 -->
            

            <div class="table-responsive" style="padding-bottom:20px;">
              <!--style="background-color: green;"-->
           <table class="table table-striped  table-bordered dataTable bg-*" id="example1">
            <thead>
            <tr>
            <th>Sr.No.</th>
            <th>AWB No.</th>
            <th>Item Sku</th>
            <!-- <th>Cartoon Sku#</th> -->
            <th>Status</th>
            <th>Quantity</th>
            <!-- <th>Cartoon Quantity</th> -->
            <th>Seller</th>
            <th>Date</th>
            <th class="text-center" ><i class="icon-database-edit2"></i></th>
            </tr>
            </thead>
            <tbody>
            <?php $sr=1;?>
            <?php if(!empty($seller_shipments)): ?>
            <?php foreach($seller_shipments as $shipment): ?>
            
            <tr>
            <td><?= $sr;?></td>
            <td ><a href="<?= site_url('Shipment/report_view/'.$shipment->id);?>"><?= $shipment->slip_no; ?></a></td>
          <!--   <?php //for($i=0;$i<count($seller_shipments);$i++):?>
            <?php //if($shipment->sku==$item_inventory[$i][0]->item_sku):?>
            <td><?// $item_inventory[$i][0]->sku; ?></td>
            <?php //endif;?>
            <?php //endfor;?> -->
            <td><?= $shipment->sku; ?></td>
           <!--  <?php //if(!empty($data['status'])): ?>
            <?php //foreach($data['status'] as $status_detail): ?>
            <?php// if($shipment->delivered==$status_detail->id):?>
              
              <td><?= $status_detail->main_status; ?></td>
            <?php// endif;?>
            <?php// endforeach;?>
            <?php// endif;?> -->
            <td><?= $shipment->main_status; ?></td>
            <td><?= $shipment->piece; ?></td>
            <td><?= $shipment->name; ?></td>
            <!-- <?php //if(!empty($data['seller_info'])): ?>
            <?php// if($shipment->cust_id==$data['seller_info']->customer):?>
            <td><?// $data['seller_info']->name ?></td>
            <?php// endif;?>
           <?php //endif;?> -->
            
            <td><?= $shipment->entrydate; ?></td>
            
            <?php $sr++;?>
            <td class="text-center">
            <ul class="icons-list">
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-menu9"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="<?= site_url('Shipment/edit_view/'.$shipment->id);?>"><i class="icon-pencil7"></i> Edit </a></li>
            <li><a href="#"><i class="icon-file-pdf"></i> Export to .pdf</a></li>
            <!-- <li><a href="#"><i class="icon-file-excel"></i> Export to .csv</a></li>
            <li><a href="#"><i class="icon-file-word"></i> Export to .doc</a></li> -->
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
               <?php //echo $links; ?> 
             </center>
           </div> -->
           <hr>
         </div>

       </div>

              <!-- Basic responsive table -->
            <div class="panel panel-flat" id="table2" style="display: none;">
                      <!--style="padding-bottom:220px;background-color: lightgray;"-->
                      <div class="panel-heading">
                      <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                      <h3><strong>Seller Items Inventory</strong></h3>

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
                     <table class="table table-striped  table-bordered dataTable bg-*" id="example2">
                      <thead>
                      <tr>
                      <th>Sr.No.</th>
                      <th>Name</th>
                      <th>Item Sku</th>
                      <th>Quantity</th>
                      <th>Seller</th>
                      <th>Description</th>
                      <th>Update date</th>
                      <th class="text-center" ><i class="icon-database-edit2"></i></th>
                      </tr>
                      </thead>
                      <tbody>

                      <?php $sr=1; ?>
                      <?php if(!empty($item_inventory)): ?>
                      <?php foreach($item_inventory as $item_int): ?>
                      <tr>
                      <td><?= $sr;?></td>
                      <td><?= $item_int->name; ?></td>
                      <td><?= $item_int->sku; ?></td>
                      <td><?= $item_int->quantity; ?></td>
                      <td><?= $item_int->seller_name; ?></td>
                      <!-- <?php// foreach($data['items'] as $item_detail):?>
                      <?php// if($item_int->sku==$item_detail->sku):?>
                        <td><? $item_detail->description;?></td>
                      <?php// endif;?>
                      <?php// endforeach;?> -->
                      <td><?= $item_int->item_description; ?></td>
                      <td><?= $item_int->update_date; ?></td>
                      <?php $sr++;?>
                      <td class="text-center">
                      <ul class="icons-list">
                      <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="icon-menu9"></i>
                      </a>

                      <ul class="dropdown-menu dropdown-menu-right">
                      <li><a href="<?= site_url('ItemInventory/edit_view/'.$item->id);?>"><i class="icon-pencil7"></i> Edit </a></li>
                      <!-- <li><a href="#"><i class="icon-file-pdf"></i> Export to .pdf</a></li>
                      <li><a href="#"><i class="icon-file-excel"></i> Export to .csv</a></li>
                      <li><a href="#"><i class="icon-file-word"></i> Export to .doc</a></li> -->
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
  <!-- /page container -->

  <script>
$(document).ready(function() {
    var table = $('#example1').DataTable({

    });

    var table2 = $('#example2').DataTable({

    });
    
} );
  
  $("#shipment").click(function(){
    if($('#table1').is(':hidden')){
        $('#table1').show();
    }
    else if($('#table1').is(':visible'))
    {
        $('#table1').hide();
    }
  
  });

  $("#inventory").click(function(){
    if($('#table2').is(':hidden')){
        $('#table2').show();
    }
    else if($('#table2').is(':visible'))
    {
        $('#table2').hide();
    }
  
  });
 
</script>
</body>
</html>

