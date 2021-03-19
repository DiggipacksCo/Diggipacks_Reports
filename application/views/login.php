<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/dgpk.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<style>
    
    body {


        background-image: url("https://lm.diggipacks.com//assets/images/bg.jpg");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    
</style>
</head>

<body class="login-container " > <!--login-cover style="background-color:#0F3BA7;"-->

<!-- Page container -->
<div class="page-container"> 
  
  <!-- Page content -->
  <div class="page-content"> 
    
    <!-- Main content -->
    <div class="content-wrapper"> 
      
      <!-- Content area -->
      <div class="content pb-20"> 
        
        <!-- Form with validation -->
        <form action="Login/auth_user" class="form-validate" method="post" >
          <!-- <form action="<?php //echo base_url("Login/auth_user") ?>" class="form-validate" method="post" enctype="multipart/form-data" > --> 
          <!-- <?php //echo form_open('Login.php/auth_user'); ?> -->
          <div class="panel panel-body login-form">
            <div class="text-center">
                <img class="img-fluid" src="<?=$this->site_data->newlogo?>" width="150">
              
              <h5 class="content-group">Login to your account <small class="display-block">Your credentials</small></h5>
            </div>
            <?php 
if($this->session->flashdata('Error'))
echo '<div class="alert alert-success">'.$this->session->flashdata('Error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'?>
            <div class="form-group has-feedback has-feedback-left">
              <input type="text" class="form-control" placeholder="Username or Email" name="username" required="required">
              <div class="form-control-feedback"> <i class="icon-user text-muted"></i> </div>
            </div>
            <div class="form-group has-feedback has-feedback-left">
              <input type="password" class="form-control" placeholder="Password" name="password" required="required">
              <div class="form-control-feedback"> <i class="icon-lock2 text-muted"></i> </div>
            </div>
            <div class="form-group login-options">
              <div class="row"> 
                <!-- <div class="col-sm-6">
                    <label class="checkbox-inline">
                      <input type="checkbox" class="styled" checked="checked">
                      Remember
                    </label>
                  </div> --> 
                
                <!-- <div class="col-sm-6 text-right">
                    <a href="login_password_recover.html">Forgot password?</a>
                  </div> --> 
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn bg-pink-400 btn-block" style="background-color:#0F3BA7;">Login <i class="icon-arrow-right14 position-right"></i></button>
            </div>
            
            <!-- <div class="content-divider text-muted form-group"><span>or sign in with</span></div>
              <ul class="list-inline form-group list-inline-condensed text-center">
                <li><a href="#" class="btn border-indigo text-indigo btn-flat btn-icon btn-rounded"><i class="icon-facebook"></i></a></li>
                <li><a href="#" class="btn border-pink-300 text-pink-300 btn-flat btn-icon btn-rounded"><i class="icon-dribbble3"></i></a></li>
                <li><a href="#" class="btn border-slate-600 text-slate-600 btn-flat btn-icon btn-rounded"><i class="icon-github"></i></a></li>
                <li><a href="#" class="btn border-info text-info btn-flat btn-icon btn-rounded"><i class="icon-twitter"></i></a></li>
              </ul> --> 
            
            <!--  <div class="content-divider text-muted form-group"><span>Don't have an account?</span></div>
              <a href="base_url('Login/sign_up')" class="btn btn-default btn-block content-group">Sign up</a>
              <span class="help-block text-center no-margin">By continuing, you're confirming that you've read our <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span> --> 
          </div>
        </form>
        <!-- /form with validation --> 
        
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