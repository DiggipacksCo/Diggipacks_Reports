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
        

        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
<style type="text/css">
.form-group.radiosection {
    display: inline-block;
    width: 23%;
}
</style>
    </head>

    <body ng-app="fulfill">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_view">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong><?= lang('lang_Edit_Seller'); ?></strong></h1></div>
                            <hr>
                            <div class="panel-body">


                                <form action="<?= base_url('Seller/edit/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">

                                    <div class="form-group ">
                                        <p style="display:none;">
                                            <label><?= lang('lang_Account_No'); ?></label>
                                            <input type="text" name="uniqueid" readonly="1" class="form-control" />
                                    </div>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border"><?= lang('lang_Profile_Details'); ?></legend>


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
                                       // echo '//'.$customer['u_type'].'//';
                                        if ($customer['u_type'] == 'B2B') {
                                            $checku_type = 'checked="checked"';
                                        }
                                        else {
                                            $checku_type1 = 'checked="checked"';
                                        } 
                                        if ($customer['discount'] == 1) {
                                            $checku_discount = 'checked="checked"';
                                        }
                                        else {
                                            $checku_discount1 = 'checked="checked"';
                                        } 
                                        
                                        
                                       
                                        
                                        ?>
                                         <div class="form-group radiosection">
                      <label for="u_type"><strong>User Type B2B:</strong></label><br>
                      <input type="radio" name="u_type" id="u_type" value="B2B" <?=$checku_type;?>> Yes <input type="radio" name="u_type" id="u_type"  value="B2C" <?=$checku_type1;?>> No      
                  </div>
                                        <div class="form-group radiosection">
                                            <label for="auto_forward"><strong> <?= lang('lang_Auto_Forwarding'); ?>:</strong></label><br>
                                        
                                        <input type="radio" name="auto_forward" id="auto_forward"  <?= $checkauto1; ?>  value="Y">  <?= lang('lang_Yes'); ?> <input type="radio" name="auto_forward" id="auto_forward"  <?= $checkauto2; ?>  value="N">  <?= lang('lang_No'); ?>
                                        </div>
                                        <div class="form-group radiosection">
                                            <label for="access_lm"><strong><?= lang('lang_Access_LM'); ?>:</strong></label><br>

                                            <input type="radio" name="access_lm" id="system_access" <?= $check1; ?>  value="Y">  <?= lang('lang_Yes'); ?> <input type="radio" name="access_lm" id="system_access1" <?= $check2; ?>  value="N">  <?= lang('lang_No'); ?>
                                        </div>
                                        <!-- <div class="form-group radiosection">
                                            <label for="access_lm"><strong>Invoice Type:</strong></label><br>

                                           <input type="radio" name="invoice_type" id="invoice_type" <?= $checkfixrate; ?>  value="Fix Rate">  Fix Rate <input type="radio" name="invoice_type" id="invoice_type1" <?= $checkdynamicrate; ?> value="Dynamic">  Dynamic
                                        </div> -->
                                        
                                          <div class="form-group radiosection">
                                            <label for="access_lm"><strong><?= lang('lang_Inventory_First_In_First_Out'); ?>:</strong></label><br>

                                            <input type="radio" name="first_out" id="first_out" <?= $checkfirst_out; ?>  value="Y">  <?= lang('lang_Yes'); ?> <input type="radio" name="first_out" id="invoice_type1" <?= $checkfirst_out1; ?> value="N"> <?= lang('lang_No'); ?>
                                        </div>
                                        
                                          
                                        <div class="form-group">
                                            <label><?= lang('lang_Name'); ?></label>
                                            <input type="text"  class="form-control" id="name" name="name" value="<?= $customer['name']; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label><?= lang('lang_Company_name'); ?></label>
                                            <input type="text" class="form-control" id="company" name="company" value="<?= $customer['company']; ?>"/>
                                        </div>

                                   


                                        <div class="form-group" >
                <strong> Country/HUB:</strong>

                <?php
                $destData = countryList($customer['city']);

                //print_r($destData);
                ?>
                <select   ng-change="showCity();" ng-model="filterData.country" ng-init="filterData.country='<?=$destData[0]['country'];?>' " data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                
                  <?php foreach ($destData as $data) { ?>
                  <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                  <?php } ?>

                </select>
                </div>
                <div class="form-group" ng-init="showCity()" ><strong>City:</strong>
                        
                        
                        <select  name="city_drop" id="city_drop" required  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%"  ng-init="filterData.destination='<?=$customer['city'];?>'" ng-model="filterData.destination" >

                <option ng-repeat="cData in citylist"  ng-if="cData.id=='<?=$customer['city'];?>'" seleted data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                <option ng-repeat="cData in citylist"  ng-if="cData.id!='<?=$customer['city'];?>'"  data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                        </select>
                       
                    </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Address'); ?></label>
                                            <input type="text" class="form-control" id="address" name="address" value="<?= $customer['address']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label> <?= lang('lang_PhoneNo'); ?>1</label>
                                            <input type="text" name="phone1" class="form-control" id="phone1" value="<?= $customer['phone']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_PhoneNo'); ?> 2</label>
                                            <input type="text"  name="phone2" class="form-control" id="phone2" value="<?= $customer['fax']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label> <?= lang('lang_Store_Link'); ?>:<span class="bold_alert">*</span></label>
                                            <input class="form-control" type="text" name="store_link" id="store_link" value="<?= $customer['store_link']; ?>" placeholder=" Store Link"/>
                                        </div>
                                    </fieldset>
                                    <!--========================================Bank Detail=======================-->

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border"><?= lang('lang_Bank_Detail'); ?> </legend>
                                        <div class="form-group">
                                            <label><?= lang('lang_Bank_Name'); ?> </label>
                                            <input type="text"  class="form-control" name="bank_name" id="bank_name" value="<?= $customer['bank_name']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Account_Number'); ?> </label>
                                            <input type="text"  class="form-control" name="account_number" id="account_number" value="<?= $customer['account_number']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Iban_Number'); ?> </label>
                                            <input type="text"  class="form-control" name="iban_number" id="iban_number" value="<?= $customer['iban_number']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Account_Manager'); ?> </label>
                                            <input type="text" class="form-control" name="account_manager" id="account_manager" value="<?= $customer['account_manager']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Manager_Email'); ?> </label>
                                            <input type="text" class="form-control" name="managerEmail" id="managerEmail" value="<?= $customer['managerEmail']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Manager_Number'); ?> </label>
                                            <input type="text" class="form-control" name="managerMobileNo" id="managerMobileNo" value="<?= $customer['managerMobileNo']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Bank_Fee'); ?> :</label>
                                            <input type="num"  class="form-control" name="bankfee" id="bankfee" value="<?= $customer['bank_fees']; ?>"/>
                                        </div>
                                    </fieldset>
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border"><?= lang('lang_Files'); ?></legend>
                                        <div class="form-group">
                                            <label><?= lang('lang_UploadCRpdf'); ?> :</label>
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
                                            <label><?= lang('lang_UploadIDpdf'); ?> :</label>
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
                                            <label><?= lang('lang_UploadContractpdf'); ?> :</label>
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
                                        <legend class="scheduler-border"><?= lang('lang_Contract'); ?> </legend>
                                        <div class="form-group">
                                            <label> <?= lang('lang_Contract_Date'); ?>  </label>
                                            <input class="form-control" type="date" name="entrydate" id="entrydate"  value="<?= $customer['entrydate']; ?>"/>
                                        </div>
                                        <div class="form-group">
                                            <label> <?= lang('lang_Vat_No'); ?> </label>
                                            <input class="form-control" type="text" name="vat_no" id="vat_no"  value="<?= $customer['vat_no']; ?>"/>
                                        </div>
                                    </fieldset>
                                    
                                     <fieldset class="scheduler-border">
                <legend class="scheduler-border">Discount</legend>
               <div class="form-group">
                      <label ><strong>Discount  :</strong></label><br>
                      
                      <input type="radio" name="discount" id="discount1" value="1" <?=$checku_discount;?>>  <?= lang('lang_Yes'); ?> &nbsp;&nbsp; <input type="radio" name="discount" id="discount2"  value="0" <?=$checku_discount1;?>>  <?= lang('lang_No'); ?>
                  </div>
                <div class="form-group radiosection">
                  <label> From</label>
                  <input class="form-control datepppp" type="text" placeholder="From" name="discount_f" id="discount_f"  value="<?= $customer['discount_f']; ?>"/>
                </div>  
                <div class="form-group radiosection">&nbsp;</div>
                <div class="form-group radiosection">
                  <label> To</label>
                  <input class="form-control datepppp" type="text" placeholder="To" name="discount_to" id="discount_to"  value="<?= $customer['discount_to']; ?>"/>
                </div>
              </fieldset>

                                    <!-- <div class="form-group">
                                                  <strong>C2C Client</strong>&nbsp;&nbsp;<input type="radio"  name="VIP_user" />&nbsp;&nbsp;&nbsp;
                                                  <strong>B2C Client</strong>&nbsp;&nbsp;<input type="radio" name="VIP_user" checked="checked" />
                                                  </div>-->
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border"><?= lang('lang_Login_Details'); ?> </legend>
                                        <div class="form-group">
                                            <label><?= lang('lang_Email'); ?> </label>
                                            <input type="text"  class="form-control" id="email" name="email" value="<?= $customer['email']; ?>" disabled/>
                                        </div>
                                        <div class="form-group">
                                            <label><?= lang('lang_Password'); ?> </label>
                                            <input type="password" class="form-control" value="" name="password"  id="alert_password" autocorrect="off" spellcheck="false" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
                                        </div>

                                    </fieldset>
                                  
                                 
                                   
                                  
								


                                    <button type="submit" class="btn btn-primary pull-right"><?= lang('lang_Edit'); ?></button>
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

	
</script>  
<script type="text/javascript">

    $('.datepppp').datepicker({

format: 'yyyy-mm-dd'

});

        </script>