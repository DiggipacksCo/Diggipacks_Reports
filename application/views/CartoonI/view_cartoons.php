<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

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
<?php 
if($this->session->flashdata('msg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'?> 


<!-- Basic responsive table -->
<div class="panel panel-flat">
<div class="panel-heading">
<!-- <h5 class="panel-title">Basic responsive table</h5> -->
<h1><strong>Cartoons Table</strong></h1>

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
 
<!-- <input type="text" id="search"  placeholder="Search .." class="form-control" autofocus>
  -->


<div class="table-responsive" style="padding-bottom:20px">
<table class="table table-striped  table-bordered dataTable bg-*" id="example">
<thead>
<tr>
<th>Id#</th>
<th>Name</th>
<th>Sku#</th>
<th>Size</th>
<th>Quantity</th>
<th>Update date</th>
<th class="text-center" style="width: 30px;"><i class="icon-menu-open2"></i></th>
</tr>
</thead>
<tbody>
<?php if(!empty($cartoons)): ?>
<?php foreach($cartoons as $cartoon): ?>
<tr> 
<td><?= $cartoon->id; ?></td>
<td><a href="<?= site_url('Cartoon/edit_view/'.$cartoon->id);?>"><?= $cartoon->name; ?></a></td>
<td><?= $cartoon->sku; ?></td>
<td><?= $cartoon->size; ?></td>
<td><?= $cartoon->quantity; ?></td>
<td><?= $cartoon->update_date; ?></td>
<td class="text-center">
<ul class="icons-list">
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
<i class="icon-menu9"></i>
</a>

<ul class="dropdown-menu dropdown-menu-right">
<li><a href="<?= site_url('Cartoon/edit_view/'.$cartoon->id);?>"><i class="icon-database-edit2"></i> Edit </a></li>
<li><a href="#"><i class="icon-file-pdf"></i> Export to .pdf</a></li>
<li><a href="#"><i class="icon-file-excel"></i> Export to .csv</a></li>
<li><a href="#"><i class="icon-file-word"></i> Export to .doc</a></li>
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
<script>
$(document).ready(function() {
    var table = $('#example').DataTable({});
} );
  
 
</script>
<!-- /page container -->

</body>
</html>
