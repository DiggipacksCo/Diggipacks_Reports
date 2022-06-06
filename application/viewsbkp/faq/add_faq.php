<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Add_FAQ');?></title>
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
                            <div class="panel-heading"><h1><strong> <?php if(!empty($edit_id)){ echo ''.lang('lang_Edit').'';} else { echo ''.lang('lang_Add').' ';}?> <?=lang('lang_FAQ');?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>
<form method="post" action="<?=base_url();?>faq/add" >
                  <input type="hidden" name="edit_id" value="<?=$edit_id;?>">  
                  
                  
                  <div class="form-group">
                    <label for="question"><?=lang('lang_Question');?></label>
                    <input type="text" id="question" name="question" class="form-control" placeholder="Enter Question" value="<?php if(!empty($EditData['question'])) echo $EditData['question'];else  echo set_value('question');?>" required>
                  </div>
                  <div class="form-group">
                   <label for="answer"><?=lang('lang_Answer');?></label>
                    <textarea id="snow-editor"  rows="10" cols="100" class="form-control" name="answer" required><?php if(!empty($EditData['answer'])) echo $EditData['answer'];else  echo set_value('answer');?></textarea>   
                  </div>
                  <!-- <div class="form-group">
                    <label>Expire Date</label>
                    <input type="date" class="form-control" value="<?php if(!empty($EditData['expiry_date'])) echo $EditData['expiry_date'];else  echo set_value('expiry_date');?>" name="expiry_date" required>
                  </div> 
                  <div class="form-group">
                    <label for="video_link">Video Link Upload</label>
                    <input type="text" id="video_link" name="video_link" class="form-control" placeholder="Enter Video Link" value="<?php if(!empty($EditData['video_link'])) echo $EditData['video_link'];else  echo set_value('video_link');?>" required>
                  </div>-->
                  <br>
                  <div class="form-group mb-0">
                    <label for="example-helping">&nbsp;</label>
                    <input type="submit" class="btn btn-primary waves-effect waves-light" value=" <?=lang('lang_Submit');?>">
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
