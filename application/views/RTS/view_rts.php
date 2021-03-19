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
<!-- Basic responsive table -->
<div class="panel panel-flat" >
<!--style="padding-bottom:220px;background-color: lightgray;"-->
<div class="panel-heading">
<!-- <h5 class="panel-title">Basic responsive table</h5> -->
<!-- <h1><strong>Shipments Table</strong></h1>

<div class="heading-elements">
<ul class="icons-list">
	<li><a href="<?// base_url('Excel_export/shipments');?>"><i class="icon-file-excel"></i></a></li>

<li><a data-action="collapse"></a></li>
<li><a data-action="reload"></a></li> -->
<!-- <li><a data-action="close"></a></li> 
</ul>
</div> -->
 <h1><strong>Shipments Table</strong><a href="<?= base_url('Excel_export/RTSshipments');?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1>
<hr>
</div>

<div class="panel-body" >




<div class="table-responsive" style="padding-bottom:20px;" >
<!--style="background-color: green;"-->
<table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
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
<?php if(!empty($shipments)): ?>
<?php foreach($shipments as $shipment): ?>
<tr>
<td><?= $sr;?></td>

<td ><a href="<?= base_url('Shipment/report_view/'.$shipment->id);?>"><?= $shipment->slip_no; ?></a></td>

<td><?= $shipment->sku; ?></td>
<td><?= $shipment->main_status; ?></td>


<!--<?php// if(!empty($items)): ?>
	<?php //foreach($items as $item_detail): ?>
		<?php// if($shipment->sku==$item_detail[0]->id):?>
			
			<td><?// $item_detail[0]->sku; ?></td>
 		<?php// endif;?>
	<?php// endforeach;?>
<?php// endif;?>

<?php //if(!empty($status)): ?>
	<?php// foreach($status as $status_detail): ?>
		<?php// if($shipment->delivered==$status_detail->id):?>	
			<td><?= $status_detail->main_status; ?></td>
 		<?php// endif;?>
	<?php// endforeach;?>
<?php// endif;?>
 -->

<td><?= $shipment->piece; ?></td>
<!-- <td><?= $data->Cartoon_Sku; ?></td> -->
<!-- <td><?= $data->cartoon_quantity; ?></td> -->
<!-- <?php //if(!empty($sellers)):?>
	<?php// foreach($sellers as $seller_detail):?>
		<?php// if($shipment->cust_id==$seller_detail[0]->customer):?>
			<td><?// $seller_detail[0]->name; ?></td>
		<?php// endif;?>
	<?php// endforeach;?>	
<?php //endif;?> -->
<td><?= $shipment->name; ?></td>

<td><?= $shipment->entrydate; ?></td>
<?php $sr++;?>

<td class="text-center">
<ul class="icons-list">
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
<i class="icon-menu9"></i>
</a>

<ul class="dropdown-menu dropdown-menu-right">
<li><a href="<?= base_url('Shipment/edit_view/'.$shipment->id);?>"><i class="icon-pencil7"></i> Edit </a></li>
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
$(document).ready(function() {
var table = $('#example').DataTable({});
} );


</script>
<!-- /page container -->

</body>
</html>
