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
              <div class="panel panel-flat" >
                <div class="panel-heading">
                  <h6 class="panel-title"><br><div>&nbsp&nbsp&nbspDetails for AWB No: <?= $data['shipment'][0]->slip_no;?>
                      </div></h6>
                </div>
                <hr>
        

                

                <!-- Quick stats boxes -->
                <div class="row" style="padding-top: 20px;">
                 <div class="col-lg-6" style="padding-left: 20px; padding-right: 20px;">

                    <!-- Today's revenue -->
                    
                    <div class="panel">
                      <div class="panel-body">
                        
                        <div><strong>Booking Date:</strong> <?= $data['shipment'][0]->entrydate;?></div><br>
                        <div><strong>Status:</strong> <?= $data['status'][0]->main_status;?></div><br>
                        <?php if($sku==Null):?>
                          <div><strong>Item Sku:</strong> </div><br>
                        <?php else:?>
                          <?php $skus="";?>
                          <?php for($i=0;$i<count($sku);$i++):?>
                            
                           <?php  if($i<count($sku)-1):?>
                            <?php $skus=$skus.$sku[$i].' ('. $piece[$i]. ') , ';?>
                          <?php else:?>
                             <?php $skus=$skus.$sku[$i].' ('. $piece[$i]. ') ';?>
                           <?php endif;?>
                          <?php endfor;?>
                          
                        <div><strong>Item Sku:</strong> <?= $skus;?></div><br>

                        <?php endif;?>
                        <div><strong>Weight:</strong> <?= $data['shipment'][0]->weight;?> (KG)</div><br>
                       
                      </div>
                      
                      <div id="today-revenue"></div>
                    </div>
                  
                    <!-- /today's revenue -->

                  </div>

                  <div class="col-lg-6" style="padding-left: 20px; padding-right: 20px;">

                    <!-- Today's revenue -->
                    
                    <div class="panel">
                      <div class="panel-body">
                        
                        <div><strong>Quantity:</strong> <?= $data['shipment'][0]->pieces;?> (PCS)</div><br>
                          <div><strong>Seller:</strong> <?= $data['seller'][0]->name;?></div><br>
                          <div><strong>Seller Contact:</strong> <?= $data['seller'][0]->phone;?></div><br>
                        
                        
                        <div><strong>Net Amount:</strong> <?= $data['shipment'][0]->total_cod_amt;?> (SR)</div><br>
                        
                       
                      </div>
                      
                      <div id="today-revenue"></div>
                    </div>
                  
                    <!-- /today's revenue -->

                  </div>


                  
                  
                 
                </div>
                <!-- /quick stats boxes -->
              </div>
            </div>
          </div>
          <!-- /dashboard content -->




          
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
<!-- footer limited  check it-->
