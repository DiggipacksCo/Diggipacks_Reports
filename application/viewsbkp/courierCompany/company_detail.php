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
						<div class="panel-heading"><h1><strong>Edit Courier Company</strong></h1></div>
						<hr>
						<div class="panel-body">


							<form action="<?= base_url('CourierCompany/edit/'.$seller['id']); ?>" method="post" enctype="multipart/form-data">

								 <div class="form-group">
               
              <fieldset class="scheduler-border">
                <legend class="scheduler-border">Profile Details</legend>
               
                
                <div class="form-group">
                  <label>Company Name</label>
                  <input type="text" class="form-control" id="company" name="company" value="<?=$customer['company'];?>"/>
                </div>
                
                <div class="form-group">
                  <label>City</label>
                  <span id="city"></span>
                  <select name="city_drop" id="city_drop" required class="form-control">
                    <option  selected="selected">Please Select City<?=$customer['city_id'];?></option>
                    <?php if(!empty($city_drp))
                          {foreach($city_drp as $cry){?>
                    <option value="<?php echo $cry->id;?>" <?php if($cry->id==$customer['city_id']) {echo "selected=selected";}?>><?php echo $cry->city?></option>
                    <?php }}?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Address</label>
                  <input type="text" class="form-control" id="address" name="address" value="<?=$customer['address'];?>"/>
                </div>
                <div class="form-group">
                  <label>Phone No 1</label>
                  <input type="text" name="phone1" class="form-control" id="phone1" value="<?=$customer['phone'];?>"/>
                </div>
                <div class="form-group">
                  <label>Tracking Link:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="store_link" id="store_link" value="<?=$customer['store_link'];?>" placeholder=" Tracking Link"/>
                </div>
              </fieldset>
              
             <fieldset class="scheduler-border">
                <legend class="scheduler-border">Api Credentials</legend>
                 <div class="form-group">
                  <label> Api Url:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="api_url" id="api_url" value="<?=$customer['api_url'];?>" placeholder=" Api Url"/>
                </div>
                 <div class="form-group">
                  <label>Authentication Token:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="auth_token" id="auth_token" value="<?=$customer['auth_token'];?>" placeholder=" Authentication token"/>
                </div>
                 <div class="form-group">
                  <label> Username:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="user_name" id="user_name" value="<?=$customer['user_name'];?>" placeholder=" Username"/>
                </div>
                <div class="form-group">
                  <label> Password:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="password" name="password" id="password" value="<?=$customer['password'];?>" placeholder=" Password"/>
                </div>

                   <div class="form-group">
                  <label> Courier Account #:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="courier_account_no" id="courier_account_no" value="<?=$customer['courier_account_no'];?>" placeholder=" Courier Account #"/>
                </div>
                   <div class="form-group">
                  <label> Courier Pin #:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="courier_pin_no" id="courier_pin_no" value="<?=$customer['courier_pin_no'];?>" placeholder="Courier Pin #"/>
                </div>





                <div class="form-group">
                  <label> Awb Sequence Start:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="start_awb_sequence" id="start_awb_sequence" value="<?=$customer['start_awb_sequence'];?>" placeholder=" Awb Sequence Start"/>
                </div>
                <div class="form-group">
                  <label> Awb Sequence End:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="end_awb_sequence" id="end_awb_sequence" value="<?=$customer['end_awb_sequence'];?>" placeholder="Awb Sequence End"/>
                </div>




                
                </fieldset>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border">Files</legend>
                <div class="form-group">
                  <label>Upload CR (pdf):</label>
                  <input type="file"  class="form-control" name="upload_cr" id="upload_cr" />
                   <input type="hidden"   name="upload_cr_old"  value="<?=$customer['upload_cr'];?>" />
                </div>
                <?php
				if($customer['upload_cr']!='')
				echo'  <div class="form-group"><img src="'.base_url().$customer['upload_cr'].'" width="200"></div>'; 
				?>
                <div class="form-group">
                  <label>Upload ID (pdf):</label>
                  <input type="file"  class="form-control" name="upload_id" id="upload_id" />
                   <input type="hidden"   name="upload_id_old"  value="<?=$customer['upload_id'];?>" />
                </div>
                 <?php
				if($customer['upload_id']!='')
				echo'  <div class="form-group"><img src="'.base_url().$customer['upload_id'].'" width="200"></div>'; 
				?>
                <div class="form-group">
                  <label>Upload Contract (pdf):</label>
                  <input type="file"  class="form-control" name="upload_contact" id="upload_contact" />
                    <input type="hidden"   name="upload_contact_old"  value="<?=$customer['upload_contact'];?>" />
                </div>
                 <?php
				if($customer['upload_contact']!='')
				echo'  <div class="form-group"><img src="'.base_url().$customer['upload_contact'].'" width="200"></div>'; 
				?>
              </fieldset>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border">Contract</legend>
                <div class="form-group">
                  <label> Contract Date </label>
                  <input class="form-control" type="date" name="entrydate" id="entrydate"  value="<?=$customer['entrydate'];?>"/>
                </div>
                <div class="form-group">
                  <label> Vat No</label>
                  <input class="form-control" type="text" name="vat_no" id="vat_no"  value="<?=$customer['vat_no'];?>"/>
                </div>
              </fieldset>
              
              <!-- <div class="form-group">
                            <strong>C2C Client</strong>&nbsp;&nbsp;<input type="radio"  name="VIP_user" />&nbsp;&nbsp;&nbsp;
                            <strong>B2C Client</strong>&nbsp;&nbsp;<input type="radio" name="VIP_user" checked="checked" />
                            </div>-->
              <fieldset class="scheduler-border">
                <legend class="scheduler-border">Login Details</legend>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text"  class="form-control" id="email" name="email" value="<?=$customer['email'];?>" disabled/>
                </div>
               
                
              </fieldset>


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

</body>
</html>

<style>
fieldset.scheduler-border {
	border: 1px groove #ddd !important;
	padding: 0 1.4em 1.4em 1.4em !important;
	margin: 0 0 1.5em 0 !important;
	-webkit-box-shadow:  0px 0px 0px 0px #000;
	box-shadow:  0px 0px 0px 0px #000;
}
legend.scheduler-border {
	font-size: 1.2em !important;
	font-weight: bold !important;
	text-align: left !important;
	width:auto;
	padding:0 10px;
	border-bottom:none;
}
</style>