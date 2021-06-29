<?php
if($this->session->userdata('langCheck')=='AR')
echo '<html dir="RTL" lang="ar">';
else
echo '<html dir="LTR" lang="en">';
?>

<!-- Global stylesheets -->

<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css/icons/icomoon/styles.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css/bootstrap.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css/core.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css/components.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css/colors.css'); ?>" rel="stylesheet" type="text/css">
<link href="<?= base_url('assets/css/validation.css'); ?>" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"> 
<link href="<?= base_url('assets/js/core/daterangepicker.css'); ?>" rel="stylesheet" type="text/css" />
<?php
	if($this->session->userdata('langCheck')=='AR'){
		?>
			<link href="<?= base_url('assets/css/ar_bootstrap.css');?>" rel="stylesheet" type="text/css">
			<?php

	}else{
			?>
			<link href="<?= base_url('assets/css/bootstrap.css');?>" rel="stylesheet" type="text/css">
			<?php
	}

	?>
    <?php
	if($this->session->userdata('langCheck')=='AR'){
		?>
			<link href="<?= base_url('assets/css/ar_core.css');?>" rel="stylesheet" type="text/css">
			<?php

	}else{
			?>
			<link href="<?= base_url('assets/css/core.css');?>" rel="stylesheet" type="text/css">
			<?php
	}

	?>

<?php
	if($this->session->userdata('langCheck')=='AR'){
		?>
				<link href="<?= base_url('assets/css/ar_components.css');?>" rel="stylesheet" type="text/css">
			<?php

	}else{
			?>
				<link href="<?= base_url('assets/css/components.css');?>" rel="stylesheet" type="text/css">
			<?php
	}

	?>

<!-- global stylesheets -->
<script>var SITEAPP_PATH = "<?= base_url(); ?>";</script>
<script>var URLBASE = "<?= base_url(); ?>";</script>
 
<!-- Core JS files -->
<script type="text/javascript" src="<?= base_url('assets/js/core/moment.min.js'); ?>"></script>      
<script type="text/javascript" src="<?= base_url('assets/js/plugins/loaders/pace.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/libraries/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/loaders/blockui.min.js'); ?>"></script>

<!-- core JS files -->
<!-- angular JS files -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js"></script>

<script type="text/javascript" src="<?= base_url('assets/js/timer.js'); ?>"></script>

<link rel="stylesheet"; href="https://unpkg.com/ng-table@2.0.2/bundles/ng-table.min.css">
<script src="https://unpkg.com/ng-table@2.0.2/bundles/ng-table.min.js"></script>
<script>
    function disableScreen(val) {
        if (val == 1)
        {
            var div = document.createElement("div");
            div.className += "overlay";
            document.body.appendChild(div);
        } else
            $("div").removeClass("overlay");

      console.log("ss");
    }
</script>

<script type="text/javascript" src="<?= base_url('assets/js/angular/app.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/angular/shelve.app.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/angular/templatesCtrl.js'); ?>"></script>

<!-- /angular JS files -->


<!-- Theme JS files -->

<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/styling/uniform.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/notifications/pnotify.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/selects/bootstrap_select.min.js'); ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/selects/bootstrap_multiselect.js'); ?>"></script>

<script type="text/javascript" src="<?= base_url('assets/js/core/daterangepicker.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/core/daterangepicker-data.js'); ?>"></script>


<script type="text/javascript" src="<?= base_url('assets/js/core/app.js'); ?>"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="<?= base_url('assets/js/pages/form_multiselect.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/pages/form_bootstrap_select.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/validation/validation.min.js'); ?>"></script>

<style>
    .overlay {
        background-color:#FFFFFF;
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: 9999999999 !important;
        top: 0px;
        left: 0px;
        opacity: .5; /* in FireFox */ 
        filter: alpha(opacity=50); /* in IE */
    }
    .loader {
        position:fixed;
        top:34%;
        border: 16px solid #FFFFFF;
        border-radius: 50%;
        border-top: 16px solid #82CF8A;
        border-bottom: 16px solid #82CF8A;
        width: 200px;

        height: 200px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
        text-align:center;
        margin-left: 30%;
        z-index: 9999999999 !important;
        opacity: .5; /* in FireFox */ 
        filter: alpha(opacity=50); /* in IE */
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>

<!-- /theme JS files -->