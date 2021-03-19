<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/iteminventory.app.js"></script>
</head>

<body ng-app="Appiteminventory">
<?php


 $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CtritemInvontaryview" ng-init="loadMore(1,0);"> 
  
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
     
        <!-- Dashboard content -->
        <div class="row" >
          <div class="col-lg-12" > 
            
            <!-- Marketing campaigns -->
            <div class="panel panel-flat" >
              <div class="panel-heading">
                <h1><strong>Items Inventory Order Confimr</strong> </h1>
                
                <!-- <i class="icon-file-excel pull-right" style="font-size: 35px;"></i> --> 
              </div>
              
              <!-- Quick stats boxes -->
              <div class="panel-body">
                <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 
                  
                  <!-- Today's revenue --> 
                  
                  <!-- <div class="panel-body" style="background-color: pink;"> -->
                  
                  <table class="table table-bordered table-hover" style="width: 100%;">
                    <!-- width="170px;" height="200px;" -->
                    <tbody >
                      <tr style="width: 80%;">
                        <td><strong>item sku:</strong></td>
                        <td><?=$IData['sku'];?></td>
                        
                      </tr>
                       <tr style="width: 80%;">
                        <td><strong>Seller:</strong></td>
                         <td><?=$IData['sku'];?></td>
                        
                      </tr>
                       <tr style="width: 80%;">
                        <td><strong>Quantity:</strong></td>
                        <td><?=$IData['sku'];?></td>
                        
                      </tr>
                       <tr style="width: 80%;">
                        <td><strong>Stock Location:</strong></td>
                        <td><?=$IData['sku'];?></td>
                        
                      </tr>
                       <tr style="width: 80%;">
                        <td><strong>Expire Date:</strong></td>
                        <td><?=$IData['sku'];?></td>
                        
                      </tr>
                    
                    </tbody>
                  </table>
                  <br>
                  
                  <!-- </div> panel-body--> 
                  
                  <!-- /today's revenue --> 
                  
                </div>
              </div>
              
              <!-- /quick stats boxes --> 
            </div>
          </div>
        </div>
        <!-- /dashboard content --> 
        <!-- Basic responsive table -->
        
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

</body>
</html>
