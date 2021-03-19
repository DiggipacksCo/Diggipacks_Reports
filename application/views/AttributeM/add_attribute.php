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
                        <div class="panel-heading"><h1><strong>Add Attribute</strong></h1></div>
                        <hr>
                        <div class="panel-body">


                            <form id="form" action="<?= base_url('Attribute/add');?>" method="post">
                                
                                <!-- <div class="form-group">
                                    <label for="exampleInputEmail1"><strong>Name:</strong></label>
                                    <input type="text" class="form-control" name='name' id="exampleInputEmail1" placeholder="Name">
                                </div> -->
                                <!-- <div class="form-group">
                                    <label for="exampleInputEmail1"><strong>Item Category:</strong></label>
                                    
                                    <div style="padding-top: 10px;">
                                    <?php //if(!empty($all_categories)):?>
                                    <select name="dd_category" class="form-control input-md"style="width: 100%;">
									    <?php// foreach($all_categories as $category):?>
                                            <option value="<?= $category->id;?>"><?= $category->name; ?></option>
                                        <?php// endforeach; ?>
									</select>
                                    <?php// endif; ?>
                                    </div>
                                </div> -->
                                <div class="form-group">
                                        <input type="hidden" class="form-control" id="category_id" name="category_id">
                                    
                                    <label for="exampleInputEmail1"><strong>Category:</strong></label>
                                        
                                         <select  id="dd_category" name="dd_category" class="selectpicker"  data-width="100%" >
                                             <option value="">Select Category</option>
                                        <?php foreach($main_categories as $Mcategory):?>
                                            <option value="<?= $Mcategory->id;?>"><?= $Mcategory->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <script type="text/javascript">$("#dd_category").selectpicker("render");</script>

                                </div>

                                <div class="form-group">
                                        <input type="hidden" class="form-control" id="subcategory_id" name="subcategory_id">
                                    
                                    <label for="exampleInputEmail1"><strong>Sub Category:</strong></label>
                                        
                                         <select  id="dd_subcategory" name="dd_subcategory" class="selectpicker"  data-width="100%" >
                                        </select>
                                    <script type="text/javascript">$("#dd_subcategory").selectpicker("render");</script>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" class="form-control" id="attributes_ids" name="attributes_ids">
                                    
                                    <label for="exampleInputEmail1"><strong>Attributes:</strong></label>
                                    <div class="multi-select-full">
                                    <?php if(!empty($all_attributes)):?>

                                    <select  id="dd_attribute" name="dd_attribute" multiple class="multiselect"   multiple="multiple" style="display: none;">
                                        
                                        <?php foreach($all_attributes as $attribute):?>
                                            <option value="<?= $attribute->id;?>"><?= $attribute->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div style="padding-top: 20px;">
                                <button type="submit" class="btn btn-success">Submit</button>
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

    
    
    
   
    $("#dd_category").change(function(){
     
       
        $("#dd_subcategory").html('');
        $('#dd_subcategory').selectpicker('refresh');

        var category_id =$('#dd_category').val();

        if(category_id!=""){
        $.ajax({
                url: "<?= base_url('/ItemCategory/findMain/');?>",
                method: "POST",
                data:{ category_id : category_id, }
            }).done(function (data) {
                if ($.trim(data)){
                    data = JSON.parse(data);
                   
                 $("#dd_subcategory").append('<option value="">Select Sub Category</option> ');
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
         
   
        

   $('#form').submit(function() {
        var hexvalues = [];
    var labelvalues = [];

    
     document.getElementById("category_id").value=$('#dd_category :selected').val();
     document.getElementById("subcategory_id").value=$('#dd_subcategory :selected').val();

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
