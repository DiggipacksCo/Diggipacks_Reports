<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        

        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
<style type="text/css">
.form-group.radiosection {
    display: inline-block;
    width: 24%;
}
</style>
    </head>

    <body >

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
                            <div class="panel-heading"><h1><strong>Edit Seller</strong></h1></div>
                            <hr>
                            <div class="panel-body">


                                <form action="<?= base_url('Seller/edit/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">

                                    <div class="form-group ">
                                        <p style="display:none;">
                                            <label>Account No</label>
                                            <input type="text" name="uniqueid" readonly="1" class="form-control" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Profile Details</legend>


                                        <?php
                                        if ($customer['access_lm'] == 'Y') {
                                            $check1 = 'checked="checked"';
                                        } else {
                                            $check2 = 'checked="checked"';
                                        }
                                        
                                        if ($customer['auto_forward'] == 'Y') {
                                            $checkauto1 = 'checked="checked"';
                                        } else {
                                            $checkauto2 = 'checked="checked"';
                                        } 

                                        if ($customer['invoice_type'] == 'Fix Rate') {
                                            $checkfixrate = 'checked="checked"';
                                        }
                                        else {
                                            $checkdynamicrate = 'checked="checked"';
                                        } 
                                        
                                         if ($customer['first_out'] == 'Y') {
                                            $checkfirst_out = 'checked="checked"';
                                        }
                                        else {
                                            $checkfirst_out1 = 'checked="checked"';
                                        } 
                                        
                                        
                                       
                                        ?>
                                        
                                        <div class="form-group radiosection">
                                        <label for="auto_forward"><strong>Auto Forwarding :</strong></label><br>
                                        
                                        <input type="radio" name="auto_forward" id="auto_forward"  <?= $checkauto1; ?>  value="Y">  Yes <input type="radio" name="auto_forward" id="auto_forward"  <?= $checkauto2; ?>  value="N">  No
                                        </div>
                                        <div class="form-group radiosection">
                                            <label for="access_lm"><strong>Access LM:</strong></label><br>

                                            <input type="radio" name="access_lm" id="system_access" <?= $check1; ?>  value="Y">  Yes <input type="radio" name="access_lm" id="system_access1" <?= $check2; ?>  value="N">  No
                                        </div>
                                        <!-- <div class="form-group radiosection">
                                            <label for="access_lm"><strong>Invoice Type:</strong></label><br>

                                           <input type="radio" name="invoice_type" id="invoice_type" <?= $checkfixrate; ?>  value="Fix Rate">  Fix Rate <input type="radio" name="invoice_type" id="invoice_type1" <?= $checkdynamicrate; ?> value="Dynamic">  Dynamic
                                        </div> -->
                                        
                                          <div class="form-group radiosection">
                                            <label for="access_lm"><strong>Inventory First In First Out:</strong></label><br>

                                            <input type="radio" name="first_out" id="first_out" <?= $checkfirst_out; ?>  value="Y">  Yes <input type="radio" name="first_out" id="invoice_type1" <?= $checkfirst_out1; ?> value="N"> No
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text"  class="form-control" id="name" name="name" value="<?= $customer['name']; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>Company Name</label>
                                            <input type="text" class="form-control" id="company" name="company" value="<?= $customer['company']; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label>City</label>
                                            <span id="city"></span>
                                            <select name="city_drop" id="city_drop" required class="form-control">
                                                <option  selected="selected">Please Select City<?= $customer['city']; ?></option>
                                            <?php
                                            if (!empty($city_drp)) {
                                                foreach ($city_drp as $cry) {
                                                    ?>
                                                        <option value="<?php echo $cry->id; ?>" <?php
                                                        if ($cry->id == $customer['city']) {
                                                            echo "selected=selected";
                                                        }
                                                        ?>><?php echo $cry->city ?></option>
                                                            <?php }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" class="form-control" id="address" name="address" value="<?= $customer['address']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone No 1</label>
                                            <input type="text" name="phone1" class="form-control" id="phone1" value="<?= $customer['phone']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone No 2</label>
                                            <input type="text"  name="phone2" class="form-control" id="phone2" value="<?= $customer['fax']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label> Store Link:<span class="bold_alert">*</span></label>
                                            <input class="form-control" type="text" name="store_link" id="store_link" value="<?= $customer['store_link']; ?>" placeholder=" Store Link"/>
                                        </div>
                                    </fieldset>
                                    <!--========================================Bank Detail=======================-->

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Bank Details</legend>
                                        <div class="form-group">
                                            <label>Bank Name</label>
                                            <input type="text"  class="form-control" name="bank_name" id="bank_name" value="<?= $customer['bank_name']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Account Number</label>
                                            <input type="text"  class="form-control" name="account_number" id="account_number" value="<?= $customer['account_number']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Iban Number</label>
                                            <input type="text"  class="form-control" name="iban_number" id="iban_number" value="<?= $customer['iban_number']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Account Manager</label>
                                            <input type="text" class="form-control" name="account_manager" id="account_manager" value="<?= $customer['account_manager']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Manager Email</label>
                                            <input type="text" class="form-control" name="managerEmail" id="managerEmail" value="<?= $customer['managerEmail']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Manager Number</label>
                                            <input type="text" class="form-control" name="managerMobileNo" id="managerMobileNo" value="<?= $customer['managerMobileNo']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>Bank Fee:</label>
                                            <input type="num"  class="form-control" name="bankfee" id="bankfee" value="<?= $customer['bank_fees']; ?>"/>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Files</legend>
                                        <div class="form-group">
                                            <label>Upload CR (pdf):</label>
                                            <input type="file"  class="form-control" name="upload_cr" id="upload_cr" />
                                            <input type="hidden"   name="upload_cr_old"  value="<?= $customer['upload_cr']; ?>" />
                                        </div>
                                        <?php
                                        if ($customer['upload_cr'] != '')
                                        {
                                            $imageArr= explode('.',$customer['upload_cr']);
                                            if($imageArr[1]=='pdf')
                                            {
                                             echo'  <div class="form-group"><a href="'.FBASEURL .$customer['upload_cr'].'" target="_blank"><i class="icon-file-pdf" style="font-size:40px;"></i></a></div>';   
                                            }
                                            else
                                            {
                                              echo'  <div class="form-group"><img src="' . FBASEURL . $customer['upload_cr'] . '" width="150"></div>';   
                                            }
                                           
                                        }
                                        ?>
                                        <div class="form-group">
                                            <label>Upload ID (pdf):</label>
                                            <input type="file"  class="form-control" name="upload_id" id="upload_id" />
                                            <input type="hidden"   name="upload_id_old"  value="<?= $customer['upload_id']; ?>" />
                                        </div>
                                        <?php
                                        if ($customer['upload_id'] != '')
                                        {
                                           $imageArr= explode('.',$customer['upload_id']);
                                            if($imageArr[1]=='pdf')
                                            {
                                             echo'  <div class="form-group"><a href="'.FBASEURL .$customer['upload_id'].'" target="_blank"><i class="icon-file-pdf" style="font-size:40px;"></i></a></div>';     
                                            }
                                            else
                                            {
                                              echo'  <div class="form-group"><img src="' . FBASEURL . $customer['upload_id'] . '" width="150"></div>';   
                                            }
                                        }
                                        ?>
                                        <div class="form-group">
                                            <label>Upload Contract (pdf):</label>
                                            <input type="file"  class="form-control" name="upload_contact" id="upload_contact" />
                                            <input type="hidden"   name="upload_contact_old"  value="<?= $customer['upload_contact']; ?>" />
                                        </div>
                                        <?php
                                        if ($customer['upload_contact'] != '')
                                        {
                                           $imageArr= explode('.',$customer['upload_contact']);
                                            if($imageArr[1]=='pdf')
                                            {
                                             echo'  <div class="form-group"><a href="'.FBASEURL .$customer['upload_contact'].'" target="_blank"><i class="icon-file-pdf" style="font-size:40px;"></i></a></div>';      
                                            }
                                            else
                                            {
                                              echo'  <div class="form-group"><img src="' . FBASEURL . $customer['upload_contact'] . '" width="150"></div>';   
                                            }
                                            
                                        }
                                        ?>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Contract</legend>
                                        <div class="form-group">
                                            <label> Contract Date </label>
                                            <input class="form-control" type="date" name="entrydate" id="entrydate"  value="<?= $customer['entrydate']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label> Vat No</label>
                                            <input class="form-control" type="text" name="vat_no" id="vat_no"  value="<?= $customer['vat_no']; ?>"/>
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
                                            <input type="text"  class="form-control" id="email" name="email" value="<?= $customer['email']; ?>" disabled/>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" value="" name="password"  id="alert_password" autocorrect="off" spellcheck="false" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
                                        </div>

                                    </fieldset>
                                    
                                    <div class="form-group">
                                        <label> &nbsp;</label>
                                        <input class="custom-control-input" type="checkbox" name="zid_active" id="zid_active"  value="Y" <?=($customer['zid_active']=='Y'?'checked':'')?> /> Zid <?=lang('lang_Account');?>
                                    </div>
                                    
                                 
                                    <fieldset class="scheduler-border" id="show_zid_details" <?php echo ($customer['zid_active'] == 'Y' ? 'style="display:block"' : 'style="display:none"'); ?>>   
                                        <legend class="scheduler-border">Zid Details</legend>
                                        <div class="form-group">
                                            <label>X-MANAGER-TOKEN</label>
                                            <input type="text" class="form-control" name="manager_token" id="manager_token" value="<?= $customer['manager_token']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label>User-Agent</label>
                                            <input type="text"  class="form-control" name="user_Agent" id="user_Agent" value="<?= $customer['user_Agent']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <labelel>Zid Store ID</label>
                                            <input type="text"  class="form-control" name="zid_sid" id="zid_sid" value="<?=$customer['zid_sid'];?>"/>
                                        </div>
                                    </fieldset>
                                     <?php if($customer['salla_active']=='N'){ ?>
                                     <div class="form-group">
                  <label> &nbsp;</label>
                  <input class="custom-control-input" type="checkbox" name="salla_active" id="salla_active"  value="Y" <?=($customer['salla_active']=='Y'?'checked':'')?>/> Salla <?=lang('lang_Account');?>   
               </div>

                                    <?php  } ?>
									 <fieldset class="scheduler-border" id="show_salla_details" <?php echo ($customer['salla_active'] == 'Y' ? 'style="display:block"' : 'style="display:none"'); ?>>   
                                        <legend class="scheduler-border">Salla Details</legend>
                                        <div class="form-group">
                                            <label>Salla Auth Token</label>
                                            <input type="text" class="form-control" name="salla_manager_token" id="salla_manager_token" value="<?= $customer['salla_athentication']; ?>"/>
                                        </div>
                                          <div class="form-group" ><strong>From Date:</strong>
                                                        <input type="text" class="form-control datepppp"  id="from" name="from" ng-model="filterData.from">

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
<script type="text/javascript">
    
    
    $('form').attr('autocomplete', 'off');
$('input').attr('autocomplete', 'off');

	$(document).ready(function() {
		
		$('#zid_active').change(function() {
			if(this.checked) {
				$("#show_zid_details").show();    
			}else{    
				$("#show_zid_details").hide();
			}
		});
		
		$('#salla_active').change(function() {
                    //alert("sssssss");
			if(this.checked) {
				$("#show_salla_details").show();    
			}else{    
				$("#show_salla_details").hide();
			}
		});
		
	});    
</script>  
<script type="text/javascript">

    $('.datepppp').datepicker({

format: 'yyyy-mm-dd'

});

        </script>