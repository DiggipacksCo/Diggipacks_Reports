<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
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
          <div class="panel-heading">
            <h1><strong><?=lang('lang_Add_Courier_Company');?></strong></h1>
          </div>
          <hr>
          <div class="panel-body">
            <?php if(!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.validation_errors().'</div>';?>
            <?php if($this->session->flashdata('err_msg')!=''){echo '<div class="alert alert-warning" role="alert">  '.$this->session->flashdata('err_msg').'.</div>';}?>
            <form action="<?= base_url('CourierCompany/add');?>" method="post"  name="add_customer" enctype="multipart/form-data">
              <div class="form-group">
                <p style="display:none;">
                  <label><?=lang('lang_Account_No');?></label>
                  <input type="text" name="uniqueid" readonly="1" class="form-control" />
              </div>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Profile_Details');?></legend>
               
               
                <div class="form-group">
                  <label><?=lang('lang_Company_Name');?></label>
                  <input type="text" class="form-control" id="company" name="company" value="<?=set_value('company');?>"/>
                </div>
               
                <div class="form-group">
                  <label><?=lang('lang_City');?></label>       
                  <span id="city"></span>
                  <select name="city_drop" id="city_drop" required class="form-control">
                    <option selected="selected"><?=lang('lang_Please_Select_City');?></option>
                    <?php if(!empty($city_drp))
                                        {foreach($city_drp as $cry){
                                            ?>
                    <option value="<?php echo $cry->id;?>" <?php if(!empty($ctr_id)){ if($cry->id== $ctr_id) {echo "selected=selected";}}?>><?php echo $cry->city?></option>
                    <?php }}?>
                  </select>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Address');?></label>   
                  <input type="text" class="form-control" id="address" name="address" value="<?=set_value('address');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Phone_No');?> 1</label>
                  <input type="text" name="phone1" class="form-control" id="phone1" value="<?=set_value('phone1');?>"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Phone_No');?>2</label>
                  <input type="text"  name="phone2" class="form-control" id="phone2" value="<?=set_value('phone2');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Tracking_Link');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="store_link" id="store_link" value="<?=set_value('store_link');?>" placeholder=" Tracking Link"/>
                </div>
              </fieldset>
              <!--========================================Bank Detail=======================-->
               <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Api_Credentials');?></legend>
                 <div class="form-group">
                  <label> <?=lang('lang_Api_Url');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="api_url" id="api_url" value="<?=set_value('api_url');?>" placeholder=" Api Url"/>
                </div>
                 <div class="form-group">
                  <label><?=lang('lang_Authentication_Token');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="auth_token" id="auth_token" value="<?=set_value('auth_token');?>" placeholder=" Authentication Token"/>
                </div>
                 <div class="form-group">
                  <label> <?=lang('lang_Username');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="user_name" id="user_name" value="<?=set_value('user_name');?>" placeholder=" Username"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Password');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="password" name="password" id="password" value="<?=set_value('password');?>" placeholder=" Password"/>
                </div>
                   <div class="form-group">
                  <label>  <?=lang('lang_Courier_Account');?>#:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="courier_account_no" id="courier_account_no" value="<?=set_value('courier_account_no');?>" placeholder=" Courier Account #"/>
                </div>
                   <div class="form-group">
                  <label><?=lang('lang_Courier_Pin');?> #:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="courier_pin_no" id="courier_pin_no" value="<?=set_value('courier_pin_no');?>" placeholder="Courier Pin #"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Awb_Sequence_Start');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="start_awb_sequence" id="start_awb_sequence" value="<?=set_value('start_awb_sequence');?>" placeholder=" Awb Sequence Start"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Awb_Sequence_End');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="end_awb_sequence" id="end_awb_sequence" value="<?=set_value('end_awb_sequence');?>" placeholder="Awb Sequence End"/>
                </div>
                </fieldset>
            
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Files');?></legend>
                <div class="form-group">
                  <label><?=lang('lang_Upload_CRpdf');?>:</label>
                  <input type="file"  class="form-control" name="upload_cr" id="upload_cr" />
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Upload_IDpdf');?>:</label>
                  <input type="file"  class="form-control" name="upload_id" id="upload_id" />
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Upload_Contractpdf');?>:</label>
                  <input type="file"  class="form-control" name="upload_contact" id="upload_contact" />
                </div>
              </fieldset>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Contract');?></legend>
                <div class="form-group">
                  <label><?=lang('lang_Contract_Date');?> </label>
                  <input class="form-control" type="date" name="entrydate" id="entrydate"  value="<?=set_value('entrydate');?>"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Vat_No');?></label>
                  <input class="form-control" type="text" name="vat_no" id="vat_no"  value="<?=set_value('vat_no');?>"/>
                </div>
              </fieldset>
              
              <!-- <div class="form-group">
                            <strong>C2C Client</strong>&nbsp;&nbsp;<input type="radio"  name="VIP_user" />&nbsp;&nbsp;&nbsp;
                            <strong>B2C Client</strong>&nbsp;&nbsp;<input type="radio" name="VIP_user" checked="checked" />
                            </div>-->
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Login_Details');?></legend>
                <div class="form-group">
                  <label><?=lang('lang_Email');?></label>
                  <input type="text"  class="form-control" id="email" name="email" value="<?=set_value('email');?>"/>
                </div>
               
              </fieldset>
              <input name="id" type="hidden" />
              <button type="submit" class="btn btn-primary" name="submit" value="submit"><?=lang('lang_Add_New_Courier_Company');?></button>
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
