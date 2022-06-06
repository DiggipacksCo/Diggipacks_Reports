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
  <title>User Details</title>
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
                  <h1 class="panel-title"><strong>User Details</strong></h1>
                  
                </div>
                <hr>
                

                <!-- Quick stats boxes -->
                <div class="row" style="padding-top: 10px;">
                  <div class="col-lg-12" style="padding-left: 60px;padding-right: 60px; ">

                    <!-- Today's revenue -->
                   
                    <div class="panel bg-teal-400">

                      <div class="panel-body" style="height:300px">
                        <div class="icon-user" style="padding-bottom: 10px;"> <?= $userdata->username;?></div><br>
                        <div class="icon-mail-read" style="padding-bottom: 10px;"> <?= $userdata->email;?></div><br>
                       
                        <div class="icon-phone2" style="padding-bottom: 10px;"> <?= $userdata->mobile_no;?></div><br>
                        <div class="icon-gallery" style="padding-bottom: 10px;"> <img src="<?= base_url().$userdata->profile_pic;?>" width="150"></div> 
                        <br>
                          
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


                    <!-- Basic responsive table -->
          

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

