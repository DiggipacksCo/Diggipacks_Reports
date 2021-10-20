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

<body  ng-app="fulfill">

	<?php $this->load->view('include/main_navbar'); ?>


	<!-- Page container -->
	<div class="page-container" ng-controller="shipment_view" >

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
								<div class="col-md-3"> <div class="form-group" ><strong><?= lang('lang_Destination'); ?> Country/HUB:</strong>
                                                        <br>
                                                        <?php
                                                        $destData = countryList();

                                                        //print_r($destData);
                                                        ?>
                                                        <select   ng-change="showCity();" ng-model="filterData.country"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                                                            <option value=""><?= lang('lang_Select_Destination'); ?></option>
                                                            <?php foreach ($destData as $data) { ?>
                                                                <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div></div>
                                                 <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_Destination');?> City:</strong>
                                                        <br>
                                                       
                                                        <select   id="destination" name="destination"   data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" ng-model="filterData.destination"   >
                       
                     <option ng-repeat="cData in citylist"  data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                                                        </select>
                                                    </div></div>
                                            
								


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