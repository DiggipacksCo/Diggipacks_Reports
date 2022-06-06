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
<!--style="background-color: black;"-->
<?php $this->load->view('include/page_header'); ?>



<!-- Content area -->
<div class="content" >
<!--style="background-color: red;"-->
<?php 
if($this->session->flashdata('msg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'?> 
     <!-- Dashboard content -->
          <div class="row" >
            <div class="col-lg-12" >

              <!-- Marketing campaigns -->
              <div class="panel panel-flat">
                <div class="panel-heading">
                 <h1>
                  <strong>Shipments Table</strong>
               </h1>
                </div>
            
            


              </div>
            </div>
          </div>
          <!-- /dashboard content -->
<!-- Basic responsive table -->
<div class="panel panel-flat" >


<div class="panel-body" >


<div class="table-responsive" style="padding-bottom:20px;" >

<table id="example" class="table table-striped  table-bordered dataTable bg-*" style="width:100%">
        <thead>
            <tr>
                <th>id</th>
                <th>AWB no</th>
                <th>sku</th>
                <th>status</th>
                <th>pieces</th>
                <th>name</th>
                <th>entry date</th>
            </tr>
        </thead>
      <tbody>
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
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "ajax": "<?= base_url('Shipment/filter');?>",
        "order": [[2, "desc" ]]
     });
    });


  

</script>
<!-- /page container -->

</body>
</html>
