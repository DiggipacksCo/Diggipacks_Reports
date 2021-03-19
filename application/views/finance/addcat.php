<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
	<title>Finance</title>
	<?php $this->load->view('include/file'); ?>


</head>

<body>

	<?php $this->load->view('include/main_navbar'); ?>


	<!-- Page container -->
	<div class="page-container" >

		<!-- Page content -->
		<div class="page-content">

			<?php $this->load->view('include/main_sidebar'); ?>


			<!-- Main content -->
			<div class="content-wrapper">

				<?php $this->load->view('include/page_header'); ?>


				<!-- Content area -->
				<div class="content">
					<div class="panel panel-flat">
						<div class="panel-heading"><h1><strong>Add Category</strong></h1></div>
						<hr>
						<div class="panel-body">
                  <?php if(!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.validation_errors().'</div>';?>
                  <?php if($this->session->flashdata('err_msg')!=''){echo '<div class="alert alert-warning" role="alert">  '.$this->session->flashdata('err_msg').'.</div>';}?>
  
							<form action="<?= base_url('Finance/addfinCat');?>" method="post" name="storfrm">
                            <input type="hidden" name="editid" value="<?php if(!empty($editid)) echo $editid;?>">
								
                                
                                <?php
								if(!empty($editdata))
								{
								if($editdata['type']=='Basic')
								  $sel1="selected";
								  else if($editdata['type']=='Advance')
								  $sel2="selected";
								}
								else
								{
									$sel1="";
									$sel2="";
								}
								  
								?>
                                <div class="form-group">
									<label for="type"><strong>Type:</strong></label>
						     <select name="type" id="type"  class="form-control" required>
                             <option value=""> Select Type</option>
                             <option value="Basic" <?=$sel1?>>Basic</option>
                             <option value="Advance" <?=$sel2?>>Advance</option>
                             
                             
                             </select>
                                   
								</div>
								<div class="form-group">
									<label for="name"><strong>Category Name:</strong></label>
						<input type="text"  class="form-control" name='name' id="name"  placeholder="Name" value="<?php if(!empty($editdata))echo $editdata['name']; ?>" required >
                                   
								</div>
                                <div class="form-group">
									<label for="name"><strong>Description:</strong></label>
						<textarea   class="form-control" name='description' id="description"  placeholder="Description"  required ><?php if(!empty($editdata))echo $editdata['description']; ?></textarea>
                                   
								</div>
                               
								

								<div style="padding-top: 20px;">
                                <button type="submit"  class="btn btn-success">Submit</button>
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

</body>
</html>
<script type="application/javascript">

$(function() {
  $("form[name='storfrm']").validate({
    rules: {
      type: "required",
	  name: "required",
    },
    messages: {
      type: "Please select type",
	   name: "Please enter Name",
     
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
});

</script>
