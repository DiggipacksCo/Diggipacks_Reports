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
    <div class="content-wrapper">
      <?php $this->load->view('include/page_header'); ?>
      
      <!-- Content area -->
      <div class="content">
        <div class="panel panel-flat">
          <div class="panel-heading">
            <h1><strong>Generate TOD</strong></h1>
          </div>
          <hr>
          <div class="panel-body"> <br>
            <br>
             <?php if(!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.validation_errors().'</div>';?>
            <form class="stdform" method="post" action="<?= base_url('generatetodsfrm');?>">
             
              
              <div  class="col-md-3">
                <div class="form-group" ><strong>Start Letters:</strong> <br>
                   <input type="text" min="1" maxlength="5" required name="charname" class="form-control" required/>
                </div>
              </div>
              
              <div  class="col-md-4">
                <div class="form-group" ><strong>Number Of TOD:</strong> <br>
                  <input type="number" min="1" required name="stockCount" class="form-control" required/>
                </div>
              </div>
              <div  class="col-md-2">
                <div class="form-group" > <br>
                  <button type="submit"  class="btn btn-success pull-left">Generate</button>
                </div>
              </div>
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
