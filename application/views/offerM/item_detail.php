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
						<div class="panel-heading"><h1><strong>Edit Item</strong></h1>
						</div>
						<hr>
						<div class="panel-body">


							<form action="<?= base_url('Item/edit/'.$item->id); ?>" method="post">

								<div class="form-group">

									<label for="exampleInputEmail1"><strong>ID#:</strong></label>
									<input  type="text"  name='id' value='<?= $item->id; ?>' disabled  class="form-control">
								</div>

								<div class="form-group">

									<label for="exampleInputEmail1"><strong>Name:</strong></label>
									<input type="text" class="form-control" name='name' value="<?= $item->name; ?>" id="exampleInputEmail1" placeholder="Name" required>
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Sku#:</strong></label>
									<input type="text" class="form-control" name='sku'  value="<?= $item->sku; ?>" id="exampleInputEmail1" placeholder="Sku#" required>
								</div>
                                
                                 <div class="form-group">
                                    <label for="exampleInputEmail1"><strong>Description:</strong></label>
                                    <textarea rows="5" id="description" name="description" class="form-control" placeholder="Description" required><?= $item->description; ?></textarea>

                                </div>

								<!-- <div class="form-group">
                                        <input type="hidden" class="form-control" id="category_id" name="category_id">
                                    
                                    <label for="exampleInputEmail1"><strong>Main Category:</strong></label>
                                        
                                         <select  id="dd_category" name="dd_category" class="selectpicker"  data-width="100%" >
												<?php //foreach($main_categories as $category):?>

													<?php// if($category->id==$category_details->id):?>
														<option selected value="<?= $category->id;?>"><?= $category->name; ?></option>
													<?php// else:?>
														<option value="<?= $category->id;?>"><?= $category->name; ?></option>
													<?php// endif; ?>
													
												<?php// endforeach; ?>
                                    </select>
                                    <script type="text/javascript">$("#dd_category").selectpicker("render");</script>

                                </div>
								
								<div class="form-group">
                                        <input type="hidden" class="form-control" id="subcategory_id" name="subcategory_id">
                                    
                                    <label for="exampleInputEmail1"><strong>Sub Category:</strong></label>
                                        
                                         <select  id="dd_subcategory" name="dd_subcategory" class="bootstrap-select"  data-width="100%" >
                                         <option value="<?= $sub_category_details->id; ?>"><?= $sub_category_details->name;?></option> 
                                        </select>
                                </div>
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Sub Category:</strong></label>
									<div style="padding-top: 20px;">
										<?php //if(!empty($all_categories)):?>
											<select  id="dd_subcategory" name="dd_subcategory" style="width: 100%;"  class="form-control">
											<option value="<?= $sub_category_details->id; ?>"><?= $sub_category_details->name;?></option>
												<?php //foreach($all_categories as $category):?>

													<?php// if($category->id==$sub_category_details->id):?>
														<option  value="<?= $category->id;?>"><?= $category->name; ?>/<?= $category->id;?></option>
													<?php// else:?>
														<option value="<?= $category->id;?>"><?= $category->name; ?>/<?= $category->id;?></option>
													<?php// endif; ?>
													
												<?php //endforeach; ?>
											</select>
										<?php //endif; ?>
									</div>
								</div>

								<div class="form-group">
                                    <label for="exampleInputEmail1"><strong>Category Attributes:</strong></label>
                                   
                                    <div id="category" name="category" style="padding-top: 10px;">

                                    
                                    <?php// for ($i=0; $i <$total_attributes ; $i++): ?>    
                                   <?php// if($attributes_value[0]==""):?>
                                   <input type="text" id="<?= $i; ?>"  name="<?= $i; ?>" class="values form-control input-md" placeholder="<?= $attributes_name[$i]; ?>" style="padding-top: 10px;" required >
                                   <?php// else:?>
                                    <input type="text" id="<?= $i; ?>"  name="<?= $i; ?>" class="values form-control input-md" value="<?= $attributes_value[$i]; ?>" placeholder="<?= $attributes_name[$i]; ?>" style="padding-top: 10px;" required >
                                  <?php// endif;?>
                                    <?php// endfor;?>
                                   
                                    </div>
                                </div> -->
								

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

<!-- <script> 
$(document).ready(function(){


	var category_id =$('#dd_category').val();
        console.log(category_id);
        if(category_id!=""){
        $.ajax({
                url: " base_url('/ItemCategory/findMain/');",
                method: "POST",
                data:{ category_id : category_id, }
            }).done(function (data) {
                if ($.trim(data)){
                    data = JSON.parse(data);
                   
                
                 $.each(data,function(index){
                 	if ('<?= $sub_category_details->id?>'==data[index]['id']) {
                    $("#dd_subcategory").append('<option selected value="'+data[index]['id']+'">'
                        +data[index]['name']+'</option> ');
                    
                	}
                	else{
                		 $("#dd_subcategory").append('<option value="'+data[index]['id']+'">'
                        +data[index]['name']+'</option> ');

                	}
                	console.log(data[index]['name']);
                    $('#dd_subcategory').selectpicker('refresh');

                 });
             }
            
            }).fail(function () {
                alert("Something Failed!");
            });
        }
        else{
            alert('Please Select Category');
        }  


$("#dd_category").change(function(){
     
       	
        $("#dd_subcategory").html('');
        $("#category").html('');
        $('#dd_subcategory').selectpicker('refresh');
       
        var category_id =$('#dd_category').val();
        
        if(category_id!=""){
        $.ajax({
                url: " base_url('/ItemCategory/findMain/');",
                method: "POST",
                data:{ category_id : category_id, }
            }).done(function (data) {
                if ($.trim(data)){
                    data = JSON.parse(data);
                   
                 $("#dd_subcategory").append('<option value="">Select Option</option>');
                 $.each(data,function(index){
                    $("#dd_subcategory").append('<option value="'+data[index]['id']+'">'
                        +data[index]['name']+'</option> ');
                    //console.log(data[index]['name']);

                    $('#dd_subcategory').selectpicker('refresh');
                    
                        // <input type="text" id="'+data[index]['id']+'" name="'+data[index]['id']+'" class="form-control input-md" placeholder="'+data[index]['name']+'"style="padding-top: 10px;" required>');
                    //append(' <option value="'+data[index]['id']+
                      // '">'+data[index]['name']+'</option>');

                 });
             }
            
            }).fail(function () {
                alert("Something Failed!");
            });
        }
        else{
            alert('Please Select Category');
        }

    });  
   


$("#dd_subcategory").change(function(){
        $("#category").html('');
        var category_id =$('#dd_category').val();
        var sub_category_id=$('#dd_subcategory').val();
        $('#dd_subcategory').selectpicker('refresh');

        $.ajax({
                url: "base_url('/Attribute/findAttributes/');",
                method: "POST",
                data:{ category_id : category_id, sub_category_id : sub_category_id,}
            }).done(function (data) {
                if ($.trim(data)){
                    data = JSON.parse(data);
                    console.log(data[0]['attributes_id']);
                    var attributes=data[0]['attributes_id'].split(",");

               var all_attributes= new Array();
               all_attributes =<?php// echo json_encode($all_attributes); ?>;
               // console.log(all_attributes[0]["name"]);
                for (var i =0; i< attributes.length; i++) {
                    for (var j = 0; j <all_attributes.length; j++) {
                    if(attributes[i]==all_attributes[j]["id"]){
                        $("#category").append('<input type="text" id="'+all_attributes[j]["id"]+'" name="'+all_attributes[j]["id"]+'" class="form-control input-md" placeholder="'+all_attributes[j]["name"]+'"style="padding-top: 10px;" required>');
                    }
                 }
                }
                
             }
            
            }).fail(function () {
                alert("Something Failed!");
            });

    });         
});
// $("#dd_category").change(function(){
 
//     //$(".values").hide();   
//     $("#category").html('');
    

//     var category_id =$('#dd_category').val();
    
//     $.ajax({
//             url: "/inventory/Attribute/findwithcategory/",
//             method: "POST",
//             data:{ category_id : category_id, }
//         }).done(function (data) {
//             if ($.trim(data)){
//                 data = JSON.parse(data);
//                 console.log(data[0]['id']);
           

//              $.each(data,function(index){
//                 $("#category").append('<input type="text" id="'+data[index]['id']+'" name="'+data[index]['id']+'" class="form-control input-md" placeholder="'+data[index]['name']+'"style="padding-top: 10px;" required>');
//                 //append(' <option value="'+data[index]['id']+
//                 //    '">'+data[index]['name']+'</option>');

//              });
//          }
        
//         }).fail(function () {
//             alert("Something Failed!");
//         });

// });         
// });

</script> -->
</body>
</html>