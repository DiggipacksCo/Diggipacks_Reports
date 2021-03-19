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
<div class="panel-heading"><h1><strong>Add Shipment</strong></h1></div>
<hr>
<div class="panel-body">


<form action="<?= base_url('Shipment/add');?>" method="post">

<!-- <div class="form-group">
<label for="exampleInputEmail1"><strong>ID#:</strong></label>
<input  type="text"  name='id' value='<?= $item->id; ?>' disabled  class="form-control">
</div> -->
 <div class="form-group">
                  <label for="exampleInputEmail1"><strong>AWB No:</strong></label>
                  <input type="text" class="form-control" name='awb_no' id="exampleInputEmail1" placeholder="AWB No">
</div>

<div class="form-group">
<label for="exampleInputEmail1"><strong>Seller#:</strong></label>
<select required name="seller" id="seller" class="selectpicker"  data-width="100%">
<option value="">Select Seller</option>
<?php foreach($sellers as $seller):?>

<option value="<?= $seller->id ?>"><?= $seller->name ?></option>

<?php endforeach; ?>

</select>
<!-- <input  type="text"  name='sku' value=' disabled  class="form-control"> -->
</div>

<div class="form-group">

<label for="exampleInputEmail1"><strong> Item SKU#:</strong></label>
<select required name="item_sku" id="item_sku" class="selectpicker"  data-width="100%" >
<!-- <option value="">Select Item Sku</option> -->

<!-- <?php //foreach($items as $item):?>

<option value="<?= $item->id ?>">
	<?= $item->sku ?>/<br>
	<?= $item->name ?></option>

<?php //endforeach; ?> -->
</select>
</div>

<!-- <input type="text" name="itemname" id="itemname" value=" "disabled> -->

<!-- <div class="form-group">
<label for="exampleInputEmail1"><strong> Cartoon SKU#:</strong></label>
<select required name="cartoon_sku" id="cartoon_sku" class="form-control">
<option value="">Please select cartoon sku</option>
<?php// foreach($cartoons as $cartoon):?>

<option value="<?= $cartoon->id ?>">
<?= $cartoon->sku ?>/<br>
	<?= $cartoon->name ?></option>

<?php// endforeach; ?>

</select>

</div> -->




<div class="form-group">
<label for="exampleInputEmail1"><strong>Item Quantity:</strong></label>
<input  type="number" class="form-control" name='item_quantity' id='item_quantity' value="1" min="1" >
</div>

<!-- <div class="form-group">
<label for="exampleInputEmail1"><strong>Cartoon Quantity:</strong></label>
<input  type="number" class="form-control" name='cartoon_quantity' value="1" min="1" id='cartoon_quantity'  >
</div> -->

<div class="form-group">
<label for="exampleInputEmail1"><strong>Status</strong></label>
<select name="status" id="status" class="form-control">
<!-- <option value="">Please Select Status</option>
<option value="Packed">Packed</option>
<option value="Dispatched">Dispatched</option> -->
<option value="12">Booked-Pickup Scheduled</option>
<!-- <option value="Packing">Packing</option>
<option value="Completed">Completed</option>
<option value="RTF">RTF</option>
<option value="Shipped">Shipped</option>
<option value="Pending">Pending</option>
<option value="AFulfillment">Awaiting Fulfillment</option>
<option value="AShipment">Awaiting Shipment</option>
<option value="APickup">Awaiting Pickup</option>
<option value="PShipped">Partially Shipped</option>
<option value="Cancelled">Cancelled</option>
<option value="Disputed">Disputed</option>
<option value="Declined">Declined</option>
<option value="Refunded">Refunded</option>
<option value="PRefunded">Partially Refunded</option>
<option value="VRequired">Verification Required</option> -->

</select>

</div>
 
<!-- <div class="form-group">

<label for="exampleInputEmail1"><strong>Quantity:</strong></label>
<input type="number" class="form-control" name='quantity' value="<?= $item->quantity; ?>" id="exampleInputEmail1" placeholder="Name">
</div> -->
 <div class="form-group">

                  <label for="exampleInputEmail1"><strong>Comment:</strong></label>
                  <textarea rows="10" id="comment" name="comment" class="form-control"></textarea>
</div>
<button type="submit" class="btn btn-success">Submit</button>
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
<script> 
	$(document).ready(function(){
    var data_details;
$("#item_sku").change(function(){
	$('#item_quantity').val(1);
	
	var item_id = $('#item_sku').val();
 $.each(data_details,function(index){
      if(item_id==data_details[index]['id'])
        $('#item_quantity').attr({
               "max" : data_details[index]['quantity']
         });
      
  });
   });

  $("#seller").change(function(){
    var seller_id = $("#seller").val();
    $('#item_sku').html("");
    $('#item_quantity').val(1);
$('#item_sku').selectpicker('refresh');
	$.ajax({
            url: "<?= base_url('/ItemInventory/getInventory/');?>",
            method: "POST",
            data:{ seller:seller_id}
        }).done(function (data) {
			if ($.trim(data)){
				data = JSON.parse(data);
            console.log(data);
            data_details=data;
                 $("#item_sku").append('<option value="">Select Item</option>');
                 $.each(data,function(index){

                    $("#item_sku").append('<option value="'+data[index]['id']+'">'+data[index]['sku']+'</option> ');
                    
                    $('#item_sku').selectpicker('refresh');
                  });
        
    
		      }
        }).fail(function () {
            alert("Something Failed!");
        });

  });
});

  


</script>
</body>
</html>
