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
            <h1><strong>Weight Range Setup</strong></h1>
          </div>
          <hr>
          <div class="panel-body">
            <?php if(!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.validation_errors().'</div>';?>
            <?php if($this->session->flashdata('err_msg')!=''){echo '<div class="alert alert-warning" role="alert">  '.$this->session->flashdata('err_msg').'.</div>';}?>
            <form action="<?= base_url('CourierCompany/GetaddweightRange/'.$company_id);?>" method="post"  name="add_product_type" enctype="multipart/form-data">
              
             
               
                   <input type="hidden" value="1" id="count" name="count_value" />
                            <div id="add_range_style">
                           
                            <label>&nbsp;</label>
                            
                        
                          <table class="table table-striped table-hover table-bordered dataTable bg-*" >
                            <thead>
                                <tr>
                                <th>Sr.No.</th>
                                <th>Start Range</th>
                                <th>End Range</th>
                                <th></th>
                                </tr>
                            </thead>
                            
                            <tbody>
                            <tr>
                            <td> <strong>#1</strong></td>
                            <td><input type="text" class="form-control" name="start_range" id="start_range" value="<?=$WeightArr['start_range'];?>" required /></td>
                            <td><input type="text" class="form-control" name="end_range" id="end_range" onKeyUp="Getweightcheck(this.value);" value="<?=$WeightArr['end_range'];?>" required /></td>
                            
                         
                            </tr>
                             <tr>
                            <td> <strong>#2</strong></td>
                            <td><input type="text" class="form-control" name="start_range1" id="start_range1" value="<?=$WeightArr['end_range'];?>" required /></td>
                            <td><input type="text" class="form-control" name="end_range1" id="end_range1" value="Flat" required /></td>
                            
                         
                            </tr>
                            
                            <tr>
                            <td></td>
         
                            <td></td>
                            </tr>
                            </tbody>
                            </table>        
           				
         					</div> 
                
                
                
             
            
               
            
              
              
     
              
       
              <button type="submit" class="btn btn-primary"  name="submit" value="submit">Submit</button>
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
<script>

function Getweightcheck(val)
{
	var checkval=$('#end_range').val();
	$('#start_range1').val(checkval);
}
</script>
<style>
fieldset.scheduler-border {
	border: 1px groove #ddd !important;
	padding: 0 1.4em 1.4em 1.4em !important;
	margin: 0 0 1.5em 0 !important;
	-webkit-box-shadow:  0px 0px 0px 0px #000;
	box-shadow:  0px 0px 0px 0px #000;
}
legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width:auto;
	padding:0 10px;
	border-bottom:none;
}
</style>
