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
						<div class="panel-heading"><h1><strong>Edit Attribute</strong></h1></div>
						<hr>
						<div class="panel-body">


							<form id="form" action="<?= base_url('Attribute/edit/'.$attribute->id); ?>" method="post">

								<div class="form-group">

									<label for="exampleInputEmail1"><strong>ID#:</strong></label>
									<input  type="text"  name='id' value='<?= $attribute->id; ?>' disabled  class="form-control">
								</div>

								<!-- <div class="form-group">

									<label for="exampleInputEmail1"><strong>Name:</strong></label>
									<input type="text" class="form-control" name='name' value="<?= $attribute->name; ?>" id="exampleInputEmail1" placeholder="Name">
								</div> -->
								<!-- <div class="form-group">
									<label for="exampleInputEmail1"><strong>Item Category:</strong></label>
									<div style="padding-top: 20px;">
										<?php// if(!empty($all_categories)):?>
											<select name="dd_category" style="width: 100%;"  class="form-control">
											<option value="<?= $category->id; ?>"><?= $category->name;?></option>
												<?php //foreach($all_categories as $category):?>

													<?php //if($category->id==$attribute->category_id):?>
														<option  value="<?= $category->id;?>"><?= $category->name; ?></option>
													<?php //else:?>
														<option value="<?= $category->id;?>"><?= $category->name; ?></option>
													<?php //endif; ?>
													
												<?php //endforeach; ?>
											</select>
										<?php// endif; ?>
									</div>
								</div> -->
								      <div class="form-group">
                                        <input type="hidden" class="form-control" id="category_id" name="category_id">
                                    
                                    <label for="exampleInputEmail1"><strong>Category:</strong></label>
                                        
                                         <select  id="dd_category" name="dd_category" class="selectpicker"  data-width="100%" >

                                            <option value="<?= $category[0]->id;?>"><?= $category[0]->name; ?></option>   
                                    	</select>
                                    <script type="text/javascript">$("#dd_category").selectpicker("render");</script>

                                </div>

                                <div class="form-group">
                                        <input type="hidden" class="form-control" id="subcategory_id" name="subcategory_id">
                                    
                                    <label for="exampleInputEmail1"><strong>Sub Category:</strong></label>
                                        
                                         <select  id="dd_subcategory" name="dd_subcategory" class="selectpicker"  data-width="100%" >
                                         	<option value="<?= $sub_category[0]->id;?>"><?= $sub_category[0]->name; ?></option>
                                        </select>
                                    <script type="text/javascript">$("#dd_subcategory").selectpicker("render");</script>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="attributes_ids" name="attributes_ids">
                                    
                                    <label for="exampleInputEmail1"><strong>Attributes:</strong></label>
                                    <div class="multi-select-full">
                                    <?php if(!empty($all_attributes)):?>

                                    <select  id="dd_attribute" name="dd_attribute" multiple class="multiselect" multiple="multiple" style="display: none;">

                                        <?php $i=0;?>
                                    	<?php foreach($all_attributes as $attribute):?>
                                       	<?php if($attributes[$i]==$attribute->id):?>
                                            <option selected value="<?= $attribute->id;?>"><?= $attribute->name; ?></option>
											<?php if($i<count($attributes)):?>
                                            <?php $i++; ?> 
                                        	<?php endif;?>
                                        <?php else:?>
                                        	<option value="<?= $attribute->id;?>"><?= $attribute->name; ?></option>
                                        <?php endif;?>
                                       
                                    	<?php endforeach; ?>
                                        
                                    </select>
                                    <?php endif; ?>
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
	<!-- /page container -->
<script type="text/javascript">
	// $(document).ready(function(){
	// 	$('#dd_attribute').multiselect('checked', ['1']);
	// });
 $('#form').submit(function() {
        var hexvalues = [];
    var labelvalues = [];

    
     // document.getElementById("category_id").value=$('#dd_category :selected').val();
     // document.getElementById("subcategory_id").value=$('#dd_subcategory :selected').val();

     $('#dd_attribute :selected').each(function(i, selectedElement) {
     hexvalues[i] = $(selectedElement).val();
     labelvalues[i] = $(selectedElement).text();
    });
        document.getElementById("attributes_ids").value=hexvalues;
        console.log(hexvalues);
        console.log(labelvalues);

    });

</script>
</body>
</html>