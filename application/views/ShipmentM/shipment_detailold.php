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
						<div class="panel-heading"><h1><strong>Edit Shipment</strong></h1></div>
						<hr>
						<div class="panel-body">


							<form action="<?= base_url('Shipment/edit/'.$shipment[0]->id); ?>" method="post">

								<div class="form-group">

									<label for="exampleInputEmail1"><strong>id:</strong></label>
									<input  type="text"  name='id' value='<?= $shipment[0]->id; ?>' disabled  class="form-control">
								</div>

								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Seller:</strong></label>
										<select name="seller" id="seller" class="selectpicker"  data-width="100%" required>
										<option value="" disabled>Select Seller</option>
                                        <?php foreach($sellers as $seller_detail):?>
                                        <?php if($seller_detail->customer!=0):?>
                                        <?php if($seller[0]->id==$seller_detail->id):?>
											<option value="<?= $seller_detail->id;?>" selected><?= $seller_detail->name; ?></option>
										<?php else:?>
											<option disabled value="<?= $seller_detail->id;?>"><?= $seller_detail->name; ?></option>
										<?php endif;?>
                                        <?php endif;?>
                                        <?php endforeach; ?>	
									</select>
								</div>

								<div class="form-group">

									<label for="exampleInputEmail1"><strong>AWB No:</strong></label>
									<input type="text" class="form-control" name='slip_no' value="<?= $shipment[0]->slip_no; ?>" id="slip_no" placeholder="AWB No" readonly>
								</div>
								<?php $max_for_current_item;?>
								<div class="form-group">
									
									<label for="exampleInputEmail1"><strong>Item Sku:</strong></label>
									<select name="item_sku" id="item_sku" class="selectpicker"  data-width="100%" required>
										<!-- <option value="">Select Item</option> -->
                                        <?php foreach($items as $item):?>
                                        <?php foreach($seller_inventory as $inventory):?>	
                                        <?php if($inventory->item_sku==$item->id):?>
                                        <?php if($shipment[0]->sku==$item->id):?>
											<?php $max_for_current_item=$inventory->quantity;?>
											<option value="<?= $item->id;?>" selected>
												<?= $item->sku;?>/ <?= $item->name; ?></option>
										<?php else:?>
										 	<option disabled value="<?= $item->id;?>"><?= $item->sku;?>/ <?= $item->name; ?></option>
										<?php endif;?>
										<?php endif;?>
                                        <?php endforeach;?>
                                        <?php endforeach; ?>	
									</select>

								</div>
								
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Status</strong></label>
									<select name="status" id="status" class="selectpicker"  data-width="100%">
										
										<?php if($shipment[0]->delivered==12):?>
											<option value="12" selected>Booked-Pickup Scheduled</option>
											<option value="17">Dispatched</option>
											<option value="18">Packed</option>
											<option value="19">RTF</option>
										<?php elseif($shipment[0]->delivered==17):?>
											<option value="12">Booked-Pickup Scheduled</option>	
											<option value="17" selected>Dispatched</option>
											<option value="18">Packed</option>
											<option value="19">RTF</option>
										<?php elseif($shipment[0]->delivered==18):?>
											<option value="12">Booked-Pickup Scheduled</option>	
											<option value="17">Dispatched</option>
											<option value="18" selected>Packed</option>
											<option value="19">RTF</option>
										<?php elseif($shipment[0]->delivered==19):?>
											<option value="12">Booked-Pickup Scheduled</option>	
											<option value="17">Dispatched</option>
											<option value="18">Packed</option>
											<option value="19" selected>RTF</option>
										<?php endif;?>
							
		
									</select>

								</div>

								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Quantity:</strong></label>
									<input type="number" class="form-control" name="item_quantity" value="<?= $shipment[0]->pieces; ?>"  id="item_quantity" placeholder="Quantity" readonly>
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
<script> 
$(document).ready(function(){
$('#item_quantity').attr({

			               "max" : <?=$max_for_current_item;?>
			    });
			    	
$("#item_sku").change(function(){
    var seller = $("#seller").val();
    var item_sku = $("#item_sku").val();

    $('#item_quantity').val(1);

	$.ajax({
            url: "<?= base_url('/ItemInventory/getInventory/');?>",
            method: "POST",
            data:{item_sku:item_sku,seller:seller,}
        }).done(function (data) {
			 if ($.trim(data)){
				data = JSON.parse(data);
            console.log(data);
        
                 $.each(data,function(index){

                   if(item_sku==data[index]['item_sku']){
			        $('#item_quantity').attr({
			               "max" : data[index]['quantity']
			         });
			    	}
                  });
        
    
		      }
        }).fail(function () {
            alert("Something Failed!");
        });

  });

  $("#seller").change(function(){
    var seller_id = $("#seller").val();
    $('#item_sku').html("");
    $('#item_quantity').val(1);
	$('#item_sku').selectpicker('refresh');
	$.ajax({
            url: "<?= base_url('/ItemInventory/getInventory2/');?>",
            method: "POST",
            data:{ seller:seller_id}
        }).done(function (data) {
			if ($.trim(data)){
				data = JSON.parse(data);
            console.log(data);
            data_details=data;
                 $("#item_sku").append('<option value="">Select Item</option>');
                 $.each(data,function(index){
                 	
         
              	$("#item_sku").append('<option value="'+data[index]['item_sku']+'">'+data[index]['sku']+'/ '+data[index]['name']+'</option> ');
    
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