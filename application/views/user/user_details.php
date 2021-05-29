<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Edit User</title>
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
                            <div class="panel-heading"><h1><strong>Edit User</strong></h1></div>
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
                                        <label for="usertype"><strong>Access LM:</strong></label><br>

                                        <input type="radio" name="system_access" id="system_access" <?= $check1; ?>  value="Y">  Yes <input type="radio" name="system_access" id="system_access1" <?= $check2; ?>  value="N">  No
                                    </div>
                                    
                                     <div class="form-group">
                                        <label for="usertype"><strong>Update Group Privilege :</strong></label><br>

                                        <input type="radio" name="g_privilage" id="g_privilage"   value="Y">  Yes <input type="radio" name="g_privilage" id="g_privilage" checked="checked"  value="N">  No
                                    </div>
                                    <input type="hidden" id="uid" name="uid" value="<?= $editdata['id']; ?>">

                                    <div class="form-group">
                                        <label for="usertype"><strong>warehouse:</strong></label>   
                                        <?= GetwherehouseDropShow($editdata['wh_id']); ?>
                                    </div> 
                                    <div class="form-group">
                                        <label for="usertype"><strong>User Type:</strong></label>
                                        <?= getusertypedropdown($editdata['user_type']); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="username"><strong>User Name:</strong></label>
                                        <input type="text" class="form-control" name='username' id="username" placeholder="User Name" value="<?= $editdata['username']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email"><strong>Email Address:</strong></label>
                                        <input type="text" class="form-control" name='email' id="email" placeholder="Email Address" value="<?= $editdata['email']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_no"><strong>Mobile No.:</strong></label>
                                        <input type="text" class="form-control" name='mobile_no' id="mobile_no" placeholder="Mobile No." value="<?= $editdata['phone']; ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="branch_location"><strong>Branch Location:</strong></label>
                                        <select name="branch_location" class="form-control" id="branch_location">
                                 <?php 
                                 
                                   echo' <option value="">Select Branch Location</option>';  
                                 
                                 foreach(getAllDestination() as $citydata)
                                 {
                                     if($citydata['id']== $editdata['branch_location'])
                                     echo' <option value="'.$citydata['id'].'" selected>'.$citydata['city'].'</option>';  
                                     else
                                          echo' <option value="'.$citydata['id'].'">'.$citydata['city'].'</option>';  
                                 }
                                 ?>
                                            </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="address"><strong>Address:</strong></label>
                                        <input type="text" class="form-control" name='address' id="address" placeholder="Address" value="<?= $editdata['address']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="password"><strong>Password:</strong></label>
                                        <input type="password" class="form-control" name='password' id="password" placeholder="Password" autocorrect="off" spellcheck="false" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');">
                                    </div>
                                    <?php if($editdata['user_type']==4){?>
                                     <div class="form-group">
                                        <label for="per_day_target"><strong>Per Day Target:</strong></label>
                                        <input type="number" min="0"  class="form-control" name='per_day_target' id="password" placeholder="Per Day Target">
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="form-group">
                                        <label for="logo_path"><strong>User Logo:</strong></label>
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
