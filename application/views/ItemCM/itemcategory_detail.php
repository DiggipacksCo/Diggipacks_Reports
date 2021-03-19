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
						<div class="panel-heading"><h1><strong>Edit Item Category</strong></h1></div>
						<hr>
						<div class="panel-body">


							<form id="form" action="<?= base_url('ItemCategory/edit/'.$itemcategory->id); ?>" method="post">

								<div class="form-group">

									<label for="exampleInputEmail1"><strong>ID#:</strong></label>
									<input  type="text"  name='id' value='<?= $itemcategory->id; ?>' disabled  class="form-control">
								</div>

								<div class="form-group">

									<label for="exampleInputEmail1"><strong>Name:</strong></label>
									<input type="text" class="form-control" name='name' value="<?= $itemcategory->name; ?>" id="exampleInputEmail1" placeholder="Name">
								</div>

							<!-- 	 <div class="form-group">
                                  	<input type="hidden" class="form-control" id="attributes_ids" name="attributes_ids">
                                  	
                                    <label for="exampleInputEmail1"><strong>Main Category:</strong></label>
                             		
                                    <div class="multi-select-full" style="padding-top: 10px;">

                                    <?php// if(!empty($all_attributes)):?>
                                    <select  id="dd_attribute" name="dd_attribute" multiple class="multiselect"   multiple="multiple" style="display: none;">

                                    	  <?php $attributes_id//=explode(",",$itemcategory->attributes_id);?> 
									    <?php// foreach($all_attributes as $attribute):?>
									    	<?php// if (in_array($attribute->id,$attributes_id)==true): ?>
                                            <option selected value="<?= $attribute->id;?>"><?= $attribute->name; ?></option>
                                            <?php //elseif(in_array($attribute->id,$attributes_id)==false):?> 
                                            <option value="<?= $attribute->id;?>"><?= $attribute->name; ?></option>
                                         <?php //endif;?> 
                                        <?php //endforeach; ?>
									</select>
									 <div class="btn-group">
										<button type="button" class="multiselect dropdown-toggle btn btn-default" data-toggle="dropdown" title="None selected"><span class="multiselect-selected-text">None selected</span> <b class="caret"></b></button>
										<ul class="multiselect-container dropdown-menu">
											<?php //foreach($all_attributes as $attribute):?>
											<li>
												<?php// $i=0;?>
											<a tabindex="<?=$i;?>"><label class="checkbox">
												<div class="checker"><span><input type="checkbox" value="<?= $attribute->id;?>"></span></div><?= $attribute->name; ?></label></a>
												<?php// $i++;?>
											</li>
											<?php //endforeach; ?>
										</ul>
									</div> 
                                    <?php //endif; ?>
                                    </div>
                                </div> -->
								<div class="form-group">
										<input type="hidden" class="form-control" id="categories_ids" name="categories_ids">
                                  	
                                    <label for="exampleInputEmail1"><strong>Main Category:</strong></label>
										<div>
										 <select  id="dd_category" name="dd_category" class="selectpicker"  data-width="100%" >
                                    	<option value="0">Select Attributes</option>
                                    	<?php if($itemcategory->main_id!=0):?>
									    <?php foreach($all_categories as $category):?>
                                        <?php if($category->id==$itemcategory->main_id):?>
                                            <option selected value="<?= $category->id;?>"><?= $category->name; ?></option>
                                        <?php else:?>
                                        	<option value="<?= $category->id;?>"><?= $category->name; ?></option>
										<?php endif;?>
                                        <?php endforeach; ?>
                                    	<?php endif;?>
										</select>
										</div>
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


<script type="text/javascript">

	$("#dd_category").selectpicker("render");

$('#form').submit(function() {
	var hexvalues = [];
var labelvalues = [];

 //document.getElementById("categories_ids").value=$('#dd_category :selected').val();

$('#dd_category :selected').each(function(i, selectedElement) {
 hexvalues[i] = $(selectedElement).val();
 labelvalues[i] = $(selectedElement).text();
});
	document.getElementById("categories_ids").value=hexvalues;
	console.log(hexvalues);
	console.log(labelvalues);

});


</script>
	<!-- /page container -->
<!-- <script type="text/javascript">

	

$('#form').submit(function() {
	var hexvalues = [];
var labelvalues = [];
	
$('#dd_attribute :selected').each(function(i, selectedElement) {
 hexvalues[i] = $(selectedElement).val();
 labelvalues[i] = $(selectedElement).text();
});
	document.getElementById("attributes_ids").value=hexvalues;
	console.log(hexvalues);
	console.log(labelvalues);

});

</script> -->
</body>
</html>