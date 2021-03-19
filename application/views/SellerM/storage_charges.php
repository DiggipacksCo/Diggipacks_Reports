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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<title>Set Courier Companies</title>
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
						<div class="panel-heading"><h1><strong>Set Storage Charges</strong></h1></div>
						<hr>
						<div class="panel-body">


			
					
              
               <fieldset class="scheduler-border">
                <legend class="scheduler-border">Set Storage Charges</legend>
                 <div class="form-group">

                 <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
                <thead>
				<tr>
					<th>Sr.No.</th>
					<th>Storage Type</th>
					<th>Price</th>
					<th class="text-center" ><i class="icon-database-edit2"></i></th>
					
				</tr>
                </thead>
                <tbody>
                
                  <?php if(!empty($fullfilment_drp)): ?>

					<?php $i=0; 
					$totalCount = count($fullfilment_drp);
					foreach($fullfilment_drp['result'] as $seller)
					// echo "<pre>";print_r($fullfilment_drp);
					{ $i++; ?>
					 <tr>
					 
                      <td><?=$i;  ?></td>
					  <td><?= $seller['storage_type']; ?></td>
					  <td> 
					  <input type="hidden" name="id[<?=$seller['id'];?>]" value="<?=$seller['id'];?>" ><?= $seller['rate']; ?>
					   </td>
					   <td class="text-center">
					   <a href="<?=base_url();?>editviewstorage/<?=$seller['id'];?>" ><i class="icon-pencil" ></i></a>
					   </td>
                    </tr>
					<?php }
                     

                    ?>
                <?php endif; ?>
              </tbody>
            </table>
                </div>
                </fieldset>
              
             
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
