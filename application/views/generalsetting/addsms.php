<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Add SMS Setting</title>
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
                            <div class="panel-heading"><h1><strong>Add SMS Setting</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>
                                <?php
                                if ($this->session->flashdata('msg') != '') {
                                    echo '<div class="alert alert-success" role="alert">  ' . $this->session->flashdata('msg') . '.</div>';
                                }
                                ?>
                                <?php if(empty($EditData)){ ?>
                                <form action="<?= base_url('Generalsetting/smsconfigrationsave'); ?>" name="adduser" method="post" enctype="multipart/form-data">
<?php }{ ?>
    <form action="<?= base_url('Generalsetting/smsconfigrationsave/'.$EditData['id']); ?>" name="adduser" method="post" enctype="multipart/form-data">
<?php } ?>

                                    <div class="form-group">
                                        <label for="company_name"><strong>Company Name:</strong></label>
                                        <input type="text" class="form-control" name='company_name' id="company_name" placeholder="Company Name" value="<?= $EditData['company_name'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="api_url"><strong>Address:</strong></label>
                                        <input type="text" class="form-control" name='api_url' id="api_url" placeholder="Api Url" value="<?= $EditData['api_url'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="params"><strong>Params:</strong></label>
                                        <input type="text" class="form-control" name='params' id="params" placeholder="params" value="<?= $EditData['params'] ?>">
                                    </div>
<!--                                    <div class="form-group">
                                        <label for="fax"><strong>Fax:</strong></label>
                                        <input type="text" class="form-control" name='fax' id="fax" placeholder="Fax" value="<? //= $EditData['fax'] ?>">
                                    </div>-->
                                   

                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success">Update</button>
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
    password: {
    required: true,
    minlength: 6
    },
    profile_pic: "required",
    },
    messages: {
    usertype: "Please Select User Type",
    username: "Please enter Username",
    password: {
    required: "Please provide a password",
    minlength: "Your password must be at least 6 characters long"
    },
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
