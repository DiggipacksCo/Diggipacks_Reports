<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/dgpk.png'); ?>" type="image/x-icon">
    
	
        <link rel='stylesheet' href='https://dg-admin.fastcoo.net/assets/bootstrap4.min.css'>
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>

        <style>
            .canvasjs-chart-credit{
                display: none;
            }
            .weight-500 {
                font-weight: 400!important;
            }

            .font-20 {
                font-size: 18px!important;
                line-height: 1.5em;
            }
            .icon-2x{
                font-size: 40px!important; 
            }
            
           .newbgCL{ background: #f5f5f5;}

	html {
	-webkit-text-size-adjust: none; /* Prevent font scaling in landscape */
	width: 100%;
	height: 100%;
	-webkit-font-smoothing: antialiased;
	-moz-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}
input[type="submit"] {
	-webkit-appearance: none;
}
*, *:after, *:before {
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}
body {
	margin: 0;
	padding: 0;
	-webkit-font-smoothing: antialiased;
	font-family: 'Muli', sans-serif;
	font-weight: 400;
	width: 100%;
	min-height: 100%;
	color: #333333;
	width: 100%;
	height: 100%;
	background: #ecf0f4;
}
a {
	outline: none;
	text-decoration: none;
	color: #555;
}
a:hover, a:focus {
	outline: none;
	text-decoration: none;
}
img {
	border: 0;
}
input, textarea, select {
	outline: none;
	resize: none;
	font-family: 'Muli', sans-serif;
}
a, input, button {
	outline: none !important;
}
 button::-moz-focus-inner {
 border: 0;
}
h1, h2, h3, h4, h5, h6 {
	margin: 0;
	padding: 0;
	font-weight: 700;
	color: #202342;
	font-family: 'Muli', sans-serif;
}
img {
	border: 0;
	vertical-align: top;
	max-width: 100%;
	height: auto;
}
ul, ol {
	margin: 0;
	padding: 0;
	list-style: none;
}
p {
	margin: 0 0 15px 0;
	padding: 0;
}

.container-fluid{
	max-width: 1900px;
}

/* Common Class */
.pd-5{padding: 5px;}
.pd-10{padding: 10px;}
.pd-20{padding: 20px;}
.pd-30{padding: 30px;}
.pb-10{padding-bottom: 10px;}
.pb-20{padding-bottom: 20px;}
.pb-30{padding-bottom: 30px;}
.pt-10{padding-top: 10px;}
.pt-20{padding-top: 20px;}
.pt-30{padding-top: 30px;}
.pr-10{padding-right: 10px;}
.pr-20{padding-right: 20px;}
.pr-30{padding-right: 30px;}
.pl-10{padding-left: 10px;}
.pl-20{padding-left: 20px;}
.pl-30{padding-left: 30px;}
.px-30{padding-left: 30px; padding-right: 30px;}
.px-20{padding-left: 20px; padding-right: 20px;}
.py-30{padding-top: 30px; padding-bottom: 30px;}
.py-20{padding-top: 20px; padding-bottom: 20px;}
.mb-30{margin-bottom: 30px;}
.mb-50{margin-bottom: 50px;}

.font-30{font-size: 30px; line-height: 1.46em;}
.font-24{font-size: 24px; line-height: 1.5em;}
.font-20{font-size: 20px; line-height: 1.5em;}
.font-18{font-size: 18px; line-height: 1.6em;}
.font-16{font-size: 16px; line-height: 1.75em;}
.font-14{font-size: 14px; line-height: 1.85em;}
.font-12{font-size: 12px; line-height: 2em;}

.weight-300{font-weight: 300;}
.weight-400{font-weight: 400;}
.weight-500{font-weight: 500;}
.weight-600{font-weight: 600;}
.weight-700{font-weight: 700;}
.weight-800{font-weight: 800;}

.text-blue{color: #1b00ff;}
.text-dark{color: #000000;}
.text-white{color: #ffffff;}
.height-100-p{height: 100%;}
.bg-white{background: #ffffff;}
.border-radius-10{
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}
.border-radius-100{
	-webkit-border-radius: 100%;
	-moz-border-radius: 100%;
	border-radius: 100%;
}
.box-shadow{
	-webkit-box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
	-moz-box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
	box-shadow: 0px 0px 28px rgba(0, 0, 0, .08);
}

.gradient-style1{
	background-image: linear-gradient( 135deg, #43CBFF 10%, #9708CC 100%);
}
.gradient-style2{
	background-image: linear-gradient( 135deg, #72EDF2 10%, #5151E5 100%);
}
.gradient-style3{
	background-image: radial-gradient( circle 732px at 96.2% 89.9%,  rgba(70,66,159,1) 0%, rgba(187,43,107,1) 92% );
}
.gradient-style4{
	background-image: linear-gradient( 135deg, #FF9D6C 10%, #BB4E75 100%);
}

/* widget style 1 */

.widget-style1{
	padding: 20px 10px;
}
.widget-style1 .circle-icon{
	width: 60px;
}
.widget-style1 .circle-icon .icon{
	width: 60px;
	height: 60px;
	background: #ecf0f4;
	display: flex;
	align-items: center;
	justify-content: center;
}
.widget-style1 .widget-data{
	width: calc(100% - 150px);
	padding: 0 15px;
}
.widget-style1 .progress-data{
	width: 90px;
}
.widget-style1 .progress-data .apexcharts-canvas{
	margin: 0 auto;
}

.widget-style2 .widget-data{
	padding: 20px;
}

.widget-style3{
	padding: 30px 20px;
}
.widget-style3 .widget-data{
	width: calc(100% - 60px);
}
.widget-style3 .widget-icon{
	width: 60px;
	font-size: 45px;
	line-height: 1;
}

.apexcharts-legend-marker{
	margin-right: 6px !important;
}
        </style>
    </head>

    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading"dir="ltr">
					                  
                                        <h6 class="panel-title"><?=lang('lang_Dashboard');?></h6>
                                        <div class="heading-elements"> <span class="label bg-success heading-text"><?=lang('lang_DETAILS');?></span> 

                                        </div>


                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-lg text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <td class="col-md-5"><div class="media-left">
                                                            <div id="campaigns-donut"></div>
                                                        </div>
                                                        <div class="media-left"> 

                                                        </div></td>
                                                    <td class="col-md-5"><div class="media-left">
                                                            <div id="campaign-status-pie"></div>
                                                        </div>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Quick stats boxes  style="background-color: #263238"-->

                                    <?php if (menuIdExitsInPrivilageArray(93) == 'Y' ) { ?>
                                        <div class="row" >


                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list ">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Total_Shipments ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Shipments');?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>





                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <!--<a href="<?= base_url('RTS'); ?>">-->
                                                <div class="panel newbgCL">
                                                    <div class="panel-body">
                                                        <div class="heading-elements">
                                                            <ul class="icons-list">
                                                                <li><i class="icon-office icon-2x"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="no-margin">
                                                            <?= GettotalpalletsCount(); ?>
                                                        </h3>
                                                        <?=lang('lang_Total_Used_Pallets');?>
                                                        <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                    </div>
                                                    <div id="today-revenue"></div>
                                                </div>
                                                <!--  </a> -->
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('ItemInventory'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-copy icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <?php if ($Item_Inventory != 0): ?>
                                                                <h3 class="no-margin">
                                                                    <?= $Item_Inventory ?>
                                                                </h3>
                                                            <?php else: ?>
                                                                <h3 class="no-margin">0</h3>
                                                            <?php endif; ?>
                                                            <?=lang('lang_Total_Items_in_mInventory');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Item'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-stack2 icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Total_Items; ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Items');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>

                                            <!-- /today's revenue -->
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Seller'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                  <li><!-- <span class="label bg-warning-400">CHECK </span> --> 
                                                                        <i class="icon-users icon-2x"></i> </li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Total_Sellers; ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Sellers');?> 

                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="#">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-copy icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Item_Inventory_expire; ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Items_in_Inventory_Expired');?> 

                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (menuIdExitsInPrivilageArray(94) == 'Y' ) { ?>
                                        <div class="row">
                                            <div class="panel-heading">
                                                <h6 class="panel-title"><?=lang('lang_TodayDashboard');?></h6>

                                            </div>
                                              <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-list icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current(11); ?>
                                                            </h3>
                                                            <?=lang('lang_OrderGenerated');?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current(1); ?>
                                                            </h3>
                                                            <?=lang('lang_OrderCreated');?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>

                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 


                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-copy icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current(2); ?>
                                                            </h3>
                                                            <?=lang('lang_PicklistGenerated');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>

                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-user icon-2x"></i></li>
                                                                </ul>
                                                            </div>

                                                            <h3 class="no-margin">
                                                                <?= get_total_current(3); ?>
                                                            </h3>
                                                            <?=lang('lang_AssigningToPicker');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-box icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current(4); ?>
                                                            </h3>
                                                            <?=lang('lang_Packed');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <?php //echo get_total_current(5); die;?>
                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current(5); ?>
                                                            </h3>
                                                            <?=lang('lang_DispatchedtoLM');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-server icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current(7); ?>
                                                            </h3>
                                                            <?=lang('lang_Delivered');?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list ">
                                                                    <li><i class="fa fa-arrow-left fa-2x icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current(8); ?>
                                                            </h3>
                                                            <?=lang('lang_Return');?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>

                                        </div>
                                    <?php } ?>

                                             <!-- Manish Verma Code-->
                                    <div class="container-fluid pt-5 mt-5 pb-5">
                                         <div class="row">
                                                <div class="col-xl-3 mb-50">
                                                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style3">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="widget-data">
                                                                <div class="weight-500 font-20">Back Order</div>
                                                                <div class="weight-500 font-30 text-dark"><?php echo statusCount_back(); ?></div>
                                                            </div>
                                                            <div class="widget-icon">
                                                                <div class="icon"><i class="fa fa-arrow-left icon-2x" aria-hidden="true"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>   
                                                <div class="col-xl-3 mb-50">
                                                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style3">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="widget-data">
                                                                <div class="weight-500 font-20"> Order Generated</div>
                                                                <div class="weight-500 font-30 text-dark"><?php echo statusCount(11); ?></div>
                                                            </div>
                                                            <div class="widget-icon">
                                                                <div class="icon"><i class="icon-list  icon-2x" aria-hidden="true"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 mb-50">
                                                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style3">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="widget-data">
                                                                <div class="weight-500 font-20">Order Created</div>
                                                                <div class="weight-500 font-30 text-dark"><?php echo statusCount(1); ?></div>
                                                            </div>
                                                            <div class="widget-icon">
                                                                <div class="icon"><i class="icon-bus icon-2x"></i></div>   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 mb-50">
                                                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style3">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="widget-data">
                                                                <div class="weight-500 font-20">Picklist</div>
                                                               
                                                                <div class="weight-500 font-30 text-dark"><?php echo statusCount(2); ?></div>
                                                            </div>
                                                            <div class="widget-icon">
                                                                <div class="icon"><i class="icon-copy icon-2x" aria-hidden="true"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-3 mb-50">
                                                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style3">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="widget-data">
                                                                <div class="weight-500 font-20">Picked</div>
                                                                <div class="weight-500 font-30 text-dark"><?php echo statusCount(4); ?></div>
                                                            </div>
                                                            <div class="widget-icon">
                                                                <div class="icon"><i class="icon-box icon-2x" aria-hidden="true"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div class="col-xl-3 mb-50">
                                                    <div class="bg-white box-shadow border-radius-10 height-100-p widget-style3">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            <div class="widget-data">
                                                                <div class="weight-500 font-20">Dispatch</div>
                                                                <div class="weight-500 font-30 text-dark"><?php echo statusCount(5); ?></div>
                                                            </div>
                                                            <div class="widget-icon">
                                                                <div class="icon"><i class="icon-bus icon-2x" aria-hidden="true"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                               
                                            </div>
                                            
                                        <!-- Widget Style 1 End -->
                                            
                                        <?php
 
                                            $dataPoints = array(
                                                array("label"=> "Back Order", "y"=> monthstatusCount_back()),
                                                array("label"=> "Order Generated", "y"=> monthstatusCount(11)),
                                                array("label"=> "Order Created", "y"=> monthstatusCount(1)),
                                                array("label"=> "Picklist", "y"=> monthstatusCount(2)),
                                                array("label"=> "Picked", "y"=> monthstatusCount(4)),
                                                array("label"=> "Dispatch", "y"=> monthstatusCount(5)),
                                               
                                            );
                                                
                                            ?>
                                            
                                            <!-- <div class="row" style="padding:10x">
                                            <div id="chartContainer123" style="height: 550px; width: 100%;"></div>
                                            </div>  -->
                                           
                                        
                                        </div>
                                    <!-- End Dashboard Block -->
                                    
                                    <!-- /quick stats boxes --> 
                                </div>
                            
                            </div>
                            
                        </div>
                        <!-- /dashboard content --> 
                       
                        <!-- Main charts -->
                        <div class="row">
                            <div class="col-lg-12"> 
                                <!-- Traffic sources --> 

                                <div class="panel panel-flat">

                                    <?php if (menuIdExitsInPrivilageArray(95) == 'Y' ) { ?>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <!--	<div id="container5"></div>-->
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <div id="container3"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (menuIdExitsInPrivilageArray(96) == 'Y' || 1==1) { ?>    
                                        <div class="panel-heading">
                                            <h6 class="panel-title"><?=lang('lang_TodayDashboard');?></h6>
                                            <div class="heading-elements">

                                            </div>
                                        </div>
                                        <?php
                                        //print_r(json_encode($totalorderschart));die;
                                        ?>
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <div id="container"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    <?php } ?>

                                    <?php if (menuIdExitsInPrivilageArray(97) == 'Y' || 1==1) { ?>   
                                    
                                    <?php
                                      // lowest year wanted
                                                                $cutoff = 2020;

                                                                // current year
                                                                $now = date('Y');

                                                                // build years menu
                                                                $yearDrop .= '<form action="'.base_url().'Home"  method="post" style="float:right;"><select name="year" class=""  onchange="this.form.submit()"><option value="">Year</option>' . PHP_EOL;
                                                                for ($y = $now; $y >= $cutoff; $y--) {
                                                                    if(set_value('year')==$y)
                                                                    $yearDrop .= '  <option value="' . $y . '" seleted>' . $y . '</option>' . PHP_EOL;
                                                                    else
                                                                     $yearDrop .= '  <option value="' . $y . '">' . $y . '</option>' . PHP_EOL;
                                                                        
                                                                }
                                                                $yearDrop .= '</select></form>' . PHP_EOL;
                                    
                                    ?>
                                    <div class="panel-heading" style="width:90%;">
                                            <h6 class="panel-title"><?=lang('lang_MonthlyShipmentDetails');?>   <?=$yearDrop;?></h6>
                                            <div class="heading-elements">

                                            </div>
                                        </div>

                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-lg-12">


                                                    <div class="col-lg-10 col-lg-offset-1">
                                                        <div id="container2"></div>

                                                    </div>
                                                </div>





                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="position-relative" id="traffic-sources"></div>
                                </div>  
                                <!-- /traffic sources --> 

                            </div>
                            <div class="col-lg-5"> 


                                <!-- /sales stats --> 
                            </div>
                        </div>
                        <!-- /main charts -->

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

    <script src="<?php echo base_url(); ?>assets/js/stock/highcharts-more.js"></script> 
    <script src="https://code.highcharts.com/stock/highstock.js"></script>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	exportEnabled: true,
	title:{
		text: ""
	},
	subtitles: [{
		text: "Monthly Order"
	}],
	data: [{
		//type: "pie",
		//showInLegend: "true",
		legendText: "{label}",
		indexLabelFontSize: 16,
		indexLabel: "{y} ",
		yValueFormatString: "#,##0",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>


    <script>
        runtimechart('rendom');
        function runtimechart(start)
        {
            
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url(); ?>Shipment/getlivechartallshipment",
                dataType: "json",
                data: {id: 1},
                success: function (data) {
                    //alert(JSON.stringify(data)
                    console.log(data);
                    var products = data;
                    var dataseries = [];
                    var dataitems = [];
                    for (var key in products['All']) {

                        ///alert(products['All'][key][0]);

                        //alert(products['All'][key]['yy']+","+products['All'][key]['mm']+","+products['All'][key]['dd']+","+products['All'][key]['hh']+","+products['All'][key]['ii']+","+products['All'][key]['ss']);
                        dataitems.push({"x": Date.UTC(products['All'][key]['yy'], products['All'][key]['mm'], products['All'][key]['dd'], products['All'][key]['hh'], products['All'][key]['ii'], products['All'][key]['ss']), "y": products['All'][key][1], "color": products['All'][key]['color']});
                    }
                    dataseries.push({name: 'Performance', type: 'column', data: dataitems, threshold: null, visible: true, step: false, dataGrouping: {enabled: false}, shadow: false, tooltip: {pointFormat: "performance: {point.y:,.2f} s"}, });
                    Highcharts.chart({
                        chart: {backgroundColor: null, plotBackgroundColor: "rgba(255, 255, 255, 1)", plotBorderWidth: 1, plotBorderColor: "#d2d2d2", renderTo: 'container5'},
                        yAxis: {
                            labels: {
                                formatter: function () {
                                    return this.value + "s";
                                },
                                style: {color: "#000", textShadow: "0 1px 2px #EEEEEE", }
                            }, min: -0
                        },

                        navigator: {enabled: true, height: 60, adaptToUpdatedData: false},
                        scrollbar: {liveRedraw: false},
                        legend: {layout: 'vertical', align: 'right', verticalAlign: 'middle', borderWidth: 0, enabled: false},
                        rangeSelector: {
                            selected: 2, enabled: true,
                            buttons: [
                                {type: 'hour', count: 1, text: '1h'},
                                {type: 'hour', count: 12, text: '12h'},
                                {type: 'day', count: 1, text: '1d'},
                                {type: 'week', count: 1, text: '1w'},
                                {type: 'month', count: 1, text: '1m'},
                                {type: 'all', text: 'All'}
                            ]
                        },

                        xAxis: {events: {}, minRange: 3600},
                        credits: {enabled: false},
                        title: {text: ''},
                        plotOptions: {series: {turboThreshold: 500000}},
                        series: dataseries

                    });


                }
            });
        }
        // Create the chart
        $(".highcharts-credits").empty();
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: "<?=lang('lang_TodayDashboard');?>"
            },
            /*  subtitle: {
             text: ''
             },*/
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: '<?=lang('lang_Total_Shipments');?>'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                },

            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
            },

            series: [
                {
                    name: "Shipment",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Order Created",
                            y: <?= get_total_current(1); ?>,
                            drilldown: "Order Created"
                        },
                        {
                            name: "Picklist Generated",
                            y: <?= get_total_current(2); ?>,
                            drilldown: "Picklist Generated"
                        },
                        {
                            name: "Assigning To Picker",
                            y: <?= get_total_current(3); ?>,
                            drilldown: "Assigning To Picker"
                        },
                        {
                            name: "Packed",
                            y: <?= get_total_current(4); ?>,
                            drilldown: "Packed"
                        },
                        {
                            name: "Dispatched to LM ",
                            y: <?= get_total_current(5); ?>,
                            drilldown: "Dispatched to LM "
                        },
                        {
                            name: "Delivered",
                            color: 'green',
                            y: <?= get_total_current(7); ?>,
                            drilldown: "Delivered"
                        },
                        {
                            name: "Return",
                            y: <?= get_total_current(8); ?>,
                            drilldown: 'Return',
                            color: '#AD1457'
                        }
                    ]
                }
            ],

        });
        $(".highcharts-credits").empty();

        Highcharts.chart('container2', {
            chart: {
                type: 'column'
            },
            title: {
                text: "<?=lang('lang_MonthlyShipmentDetails');?>"
            },
            /*  subtitle: {
             text: ''
             },*/
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: '<?=lang('lang_Total_Shipments');?>'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                },
                column: {pointPadding: 0.2, borderWidth: 0}

            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>',
                shared: true,
                useHTML: true
            },
            labels: {
                items: [{

                        style: {
                            left: '50px',
                            top: '18px',
                            color: (// theme
                                    Highcharts.defaultOptions.title.style &&
                                    Highcharts.defaultOptions.title.style.color
                                    ) || 'black'
                        }
                    }]
            },
            series: [
                {
                    name: "Fulfillment",
                    colorByPoint: true,
                    // data: [{name: "Order Created",y: <?= get_total_current(1); ?>,drilldown: "Order Created"},]
                    data: <?= json_encode($totalorderschart, JSON_NUMERIC_CHECK) ?>
                    //JSON_NUMERIC_CHECK

                }
            ],

        });
        $(".highcharts-credits").empty();


        /*Highcharts.setOptions({
         colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
         return {
         radialGradient: {
         cx: 0.5,
         cy: 0.3,
         r: 0.7
         },
         stops: [
         [0, color],
         [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
         ]
         };
         })
         });
         */
        // Build the chart
        Highcharts.chart('container3', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: "<?=lang('lang_Shipments_Totals');?> "
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}',
                        connectorColor: 'silver'
                    }
                }
            },
            series: [{
                    name: '',
                    data: [
                        {name: 'Total Shipments', y:<?= $Total_Shipments ?>, color: '#26A69A'},
                        {name: 'Total RTF', y: <?= $Total_Rts; ?>, color: '#00695C'},
                        {name: 'Total Items in Inventory', y:<?php if ($Item_Inventory != 0): ?><?= $Item_Inventory ?><?php else: ?>0<?php endif; ?>, color: '#29B6F6'},
                                            {name: 'Total Items', y: <?= $Total_Items; ?>, color: '#039BE5'},
                                            {name: 'Total Sellers', y: <?= $Total_Sellers; ?>, color: '#0277BD'}

                                        ]
                                    }]
                            });
                            $(".highcharts-credits").empty();


    </script>

</html>
<!-- footer limited  check it-->