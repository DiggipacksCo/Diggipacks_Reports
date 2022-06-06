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
<div class="panel-heading"><h1><strong>Edit Item Inventory</strong></h1></div>
<hr>
<div class="panel-body">


<form action="<?= base_url('ItemInventory/edit/'.$item->id);?>" method="post">

<div class="form-group">
<label for="exampleInputEmail1"><strong>ID#:</strong></label>
<input  type="text"  id='id' name='id' value='<?= $item->id; ?>' disabled  class="form-control">
</div>

<div class="form-group">
<label for="exampleInputEmail1"><strong>Sku#:</strong></label>
<input  type="text"  id='sku' name='sku' value='<?= $item->sku; ?>' disabled  class="form-control">
</div>

<div class="form-group">
<label for="exampleInputEmail1"><strong>Name:</strong></label>
<input disabled type="text"  id="name" class="form-control" name='name' value="<?= $item->name; ?>" placeholder="Name">
</div>

 
<div class="form-group">

<label for="exampleInputEmail1"><strong>Quantity:</strong></label>
<input type="number" class="form-control" id="quantity" name='quantity' value="<?= $item->quantity; ?>"  placeholder="Name" >
<!-- min="<?= $item->quantity; ?>" -->
</div>

<button type="submit" class="btn btn-primary pull-right">Edit</button>
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
