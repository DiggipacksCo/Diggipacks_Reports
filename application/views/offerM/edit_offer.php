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

<body >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" > 
  
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
            <h1><strong>Edit Offer</strong></h1>
          </div>
          <hr>
          <div class="panel-body">
           <?php if(!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.validation_errors().'</div>';?>
                  <?php if($this->session->flashdata('err_msg')!=''){echo '<div class="alert alert-warning" role="alert">  '.$this->session->flashdata('err_msg').'.</div>';}?>
            <form action="<?= base_url('Offers/Updateform/'.$editData['id'].'');?>" method="post" name="itmfrm">
              
              
              
              <div class="form-group">
                <label for="offer_item"><strong>Promo Code:</strong></label><br>
                <?=$editData['promocode'];?>
               </div>
              <div class="form-group">
                <label for="start_date"><strong>Start Date:</strong></label>
                <input type="text" class="form-control expity_date" name='start_date' id="start_date" placeholder="Start Date" value="<?=$editData['start_date'];?>" required>
                 </div>
              <div class="form-group">
                <label for="expire_date"><strong>End Date:</strong></label>
                <input type="text" class="form-control expity_date" name='expire_date' id="expire_date" placeholder="End Date" value="<?=$editData['expire_date'];?>"  required>
               </div>
              <div style="padding-top: 20px;">
                <button type="submit" class="btn btn-success">Update</button>
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
<!--/script> --> 
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 

<script> 


$( function() {
    $( ".expity_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
	dateFormat: 'yy-mm-dd',
	minDate: 0,
    });
  } );
</script>
</body>
</html>
