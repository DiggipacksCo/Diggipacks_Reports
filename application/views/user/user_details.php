<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Edit_User'); ?></title>
        <?php $this->load->view('include/file'); ?>


    </head>

    <body  ng-app="fulfill">

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
                            <div class="panel-heading"><h1><strong><?= lang('lang_Edit_User'); ?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>

                                <form action="<?= base_url('Users/edit'); ?>" name="adduser" method="post" enctype="multipart/form-data" autocomplete="off">


                                    <?php
                                    if ($editdata['system_access'] == 'Y') {
                                        $check1 = 'checked="checked"';
                                    } else {
                                        $check2 = 'checked="checked"';
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label for="usertype"><strong><?= lang('lang_Access_LM'); ?>:</strong></label><br>

                                        <input type="radio" name="system_access" id="system_access" <?= $check1; ?>  value="Y">  Yes <input type="radio" name="system_access" id="system_access1" <?= $check2; ?>  value="N">  No
                                    </div>
                                    
                                     <div class="form-group">
                                        <label for="usertype"><strong> <?= lang('lang_Update_Group_Privilege'); ?>:</strong></label><br>

                                        <input type="radio" name="g_privilage" id="g_privilage"   value="Y">  Yes <input type="radio" name="g_privilage" id="g_privilage" checked="checked"  value="N">  No
                                    </div>
                                    <input type="hidden" id="uid" name="uid" value="<?= $editdata['id']; ?>">

                                    <div class="form-group">
                                        <label for="usertype"><strong><?= lang('lang_warehouse'); ?>:</strong></label>   
                                        <?= GetwherehouseDropShow($editdata['wh_id']); ?>
                                    </div> 
                                    <div class="form-group">
                                        <label for="usertype"><strong><?= lang('lang_User_Type'); ?>:</strong></label>
                                        <?= getusertypedropdown($editdata['user_type']); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="username"><strong><?= lang('lang_User_Name'); ?>:</strong></label>
                                        <input type="text" class="form-control" name='username' id="username" placeholder="User Name" value="<?= $editdata['username']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email"><strong><?= lang('lang_Email_Address'); ?>:</strong></label>
                                        <input type="text" class="form-control" name='email' id="email" placeholder="Email Address" value="<?= $editdata['email']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_no"><strong><?= lang('lang_Mobile_No'); ?>.:</strong></label>
                                        <input type="text" class="form-control" name='mobile_no' id="mobile_no" placeholder="Mobile No." value="<?= $editdata['phone']; ?>">
                                    </div>
                                    
                                  

                                    <div class="form-group" >
                <strong> Country/HUB:</strong>

                <?php
                $destData = countryList($editdata['branch_location']);

                //print_r($destData);
                ?>
                <select   ng-change="showCity();" ng-model="filterData.country" ng-init="filterData.country='<?=$destData[0]['country'];?>' " data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                
                  <?php foreach ($destData as $data) { ?>
                  <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                  <?php } ?>

                </select>
                </div>
                <div class="form-group" ng-init="showCity()" ><strong>City:</strong>
                        
                        
                        <select  name="branch_location" id="branch_location" required  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%"  ng-init="filterData.destination='<?=$editdata['branch_location'];?>'" ng-model="filterData.destination" >

                <option ng-repeat="cData in citylist"  ng-if="cData.id=='<?=$editdata['branch_location'];?>'" seleted data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                <option ng-repeat="cData in citylist"  ng-if="cData.id!='<?=$editdata['branch_location'];?>'"  data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                        </select>
                       
                    </div>
                                    <div class="form-group">
                                        <label for="address"><strong><?= lang('lang_Address'); ?>:</strong></label>
                                        <input type="text" class="form-control" name='address' id="address" placeholder="Address" value="<?= $editdata['address']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="password"><strong><?= lang('lang_Password'); ?>:</strong></label>
                                        <input type="password" class="form-control" name='password' id="password" placeholder="Password" autocorrect="off" spellcheck="false" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                    </div>
                                    <?php if($editdata['user_type']==4){?>
                                     <div class="form-group">
                                        <label for="per_day_target"><strong><?= lang('lang_Per_Day_Target'); ?>:</strong></label>
                                        <input type="number" min="0"  class="form-control" name='per_day_target' id="password" placeholder="Per Day Target">
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="form-group">
                                        <label for="logo_path"><strong><?= lang('lang_User_Logo'); ?>:</strong></label>
                                        <input type="file" class="form-control" name='logo_path' id="logo_path">
                                        <input type="hidden" class="form-control" name='logo_path_old' id="logo_path_old" value="<?= $editdata['logopath']; ?>">
                                    </div>
                                    <?php
                                    if (!empty($editdata['logopath']))
                                        echo'<div class="form-group">
									<label for="logo_path"><strong>Show User Logo:</strong></label><br>
									<img src="' . FBASEURL . $editdata['logopath'] . '" width="200">
								</div>';
                                    ?>



                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success"><?= lang('lang_Submit'); ?></button>
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

 
    $('form').attr('autocomplete', 'off');
$('input').attr('autocomplete', 'off');
    $(function() {
    $("form[name='adduser']").validate({
    rules: {
    usertype: "required",
    username: "required",

    email: {
    required: true,
    email: true
    },
    mobile_no: {
    required: true,
    number: true,
    minlength: 10,
    maxlength: 10
    },


    },
    messages: {
    usertype: "Please Select User Type",
    username: "Please enter Username",

    email: {
    required: "Please Email Address",
    email: "Please enter a valid Email address"
    },
    mobile_no: {
    required: "Please enter Mobile No.",
    number: "Please enter a valid number.",
    minlength: "Please Enter Valid Mobile No."
    },

    },
    submitHandler: function(form) {
    form.submit();
    }
    });
    });

</script>
