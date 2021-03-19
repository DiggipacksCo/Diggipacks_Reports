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
						<div class="panel-heading"><h1><strong>Add Seller</strong></h1></div>
						<hr>
						<div class="panel-body">


							<form action="<?= base_url('Seller/add');?>" method="post">
								
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Name:</strong></label>
									<input type="text" class="form-control" name='name' id="exampleInputEmail1" placeholder="Name">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Email:</strong></label>
									<input type="email" class="form-control" name='email'  id="exampleInputEmail1" placeholder="Email">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Account No#:</strong></label>
									<input type="text" class="form-control" name='account_no'  id="exampleInputEmail1" placeholder="Account No#">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Location:</strong></label>
									<input type="text" class="form-control" name='location'  id="exampleInputEmail1" placeholder="Location">
								</div>
								<div class="form-group">
									<label for="exampleInputEmail1"><strong>Phone #1:</strong></label>
									<input type="text" class="form-control" name='phone1' id="exampleInputEmail1" placeholder="Phone N.O.">
								</div>
								<div class="form-group">
									<label for="phone2"><strong>Phone #2:</strong></label>
									<input type="text" class="form-control" name='phone2'  id="phone2" placeholder="Phone N.O.">
								</div>

								  <div class="form-group">
                                        <input type="hidden" class="form-control" id="subcategory_id" name="subcategory_id">
                                    
                                    <label for="dd_customer"><strong>Customer:</strong></label>
                                        
                                         <select  id="dd_customer" name="dd_customer" class="selectpicker"  data-width="100%" >
                                         	<option value="">Select Customer</option>
                                        <?php foreach($customers as $customer):?>
                                            <option value="<?= $customer->id;?>"><?= $customer->company; ?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    <script type="text/javascript">$("#dd_subcategory").selectpicker("render");</script>
                                </div>

                                <div class="form-group">
									<label for="warehousing_charge"><strong>Warehousing Charges:</strong></label>
									<input type="text" class="form-control" name='warehousing_charge' id="warehousing_charge" placeholder="Warehousing Charges">
								</div>
								<div class="form-group">
									<label for="fulfillment_charge"><strong>Fulfillment Charges:</strong></label>
									<input type="text" class="form-control" name='fulfillment_charge'  id="fulfillment_charge" placeholder="Fulfillment Charges">
								</div>
								 <div class="form-group">
									<label for="cbm_no"><strong>CBM No:</strong></label>
									<input type="text" class="form-control"  name='cbm_no' id="cbm_no" placeholder="CBM No">
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

</body>
</html>
