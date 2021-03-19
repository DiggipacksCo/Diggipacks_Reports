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
				<div class="content" >
					<div class="panel panel-flat">
						<div class="panel-heading"><h1><strong>Add Sub Category</strong></h1></div>
						<hr>
						<div class="panel-body" style="padding-bottom:200px;">


							<form id="form" action="<?= base_url('ItemCategory/addSubCategory');?>" method="post">
								
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Name:</strong></label>
									<input type="text" class="form-control" name='name' id="name" placeholder="Name">
								</div>
								

								<div class="form-group">
										<input type="hidden" class="form-control" id="categories_ids" name="categories_ids">
                                  	
                                    <label for="exampleInputEmail1"><strong>Main Category:</strong></label>
										<div>
										 <select  id="dd_category" name="dd_category" class="selectpicker"  data-width="100%" >
                                    	<option value="0">Select Main Category</option>
									    <?php foreach($all_categories as $category):?>
                                            <option value="<?= $category->id;?>"><?= $category->name; ?></option>
                                        <?php endforeach; ?>
										</select>
										</div>
								</div>

							  <!-- <div class="form-group">
                                  	<input type="hidden" class="form-control" id="attributes_ids" name="attributes_ids">
                                  	
                                    <label for="exampleInputEmail1"><strong>Attributes:</strong></label>
                                    <div class="multi-select-full" style="padding-top: 10px;">
                                    <?php// if(!empty($all_attributes)):?>
                                    <select  id="dd_attribute" name="dd_attribute" multiple class="multiselect"   multiple="multiple" style="display: none;">
                                    	 <option value="0">Select Attributes</option>
									    <?php //foreach($all_attributes as $attribute):?>
                                            <option value="<?= $attribute->id;?>"><?= $attribute->name; ?></option>
                                        <?php// endforeach; ?>
									</select>
									
                                    <?php// endif; ?>
                                    </div>
                                </div> -->
								<div style="padding-top: 20px;">
                                <button type="submit" class="btn btn-success" >Submit</button>
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
</body>
</html>
