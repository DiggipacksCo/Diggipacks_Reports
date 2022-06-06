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
            <link rel="stylesheet" href="https://rawgit.com/select2/select2/master/dist/css/select2.min.css">
        <script type="text/javascript" src="https://rawgit.com/select2/select2/master/dist/js/select2.js"></script>
        <script src="<?= base_url(); ?>assets/js/angular/otherpage.app.js"></script>

        <style>
            
            .select2-container--default .select2-selection--single .select2-selection__rendered {
    
    line-height: 13px !important;
}
        </style>
    </head>

    <body >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-app="AppOtherPage" ng-controller="CTLEditshpment" ng-init="Othervalues('<?=$hub_name_o;?>','<?=$hub_name_d;?>','<?=$shipment[0]->origin;;?>','<?=$shipment[0]->destination;?>');">

                                   
            <!-- Page content -->
            <div class="page-content">
                
                

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong>Edit Shipment (<?= $shipment[0]->slip_no; ?>)</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>

                                <form action="<?= base_url('Shipment/edit/' . $shipment[0]->id); ?>" method="post">
                                  
                                    <?php
                                    $destData = countryList();
                                    ?>

                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="sender_name"><strong>Sender Name&nbsp;:</strong><b class="error">*</b></label>
                                            <input type="text" name="sender_name" id="sender_name" class="form-control" value="<?= $shipment[0]->sender_name; ?>"  required>


                                        </div>
                                    </div>
                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="reciever_name"><strong>Receiver Name&nbsp;:</strong><b class="error">*</b></label>
                                            <input type="text" name="reciever_name" id="reciever_name" class="form-control" value="<?= $shipment[0]->reciever_name; ?>"  required>


                                        </div>
                                    </div>
                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="sender_phone"><strong>Sender Phone&nbsp;:</strong><b class="error">*</b></label>
                                            <input type="text" name="sender_phone" id="sender_phone" class="form-control" value="<?= $shipment[0]->sender_phone; ?>"  required>


                                        </div>
                                    </div>

                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="reciever_phone"><strong>Receiver Phone&nbsp;:</strong><b class="error">*</b></label>
                                            <input type="text" name="reciever_phone" id="reciever_phone" class="form-control" value="<?= $shipment[0]->reciever_phone; ?>"  required>


                                        </div>
                                    </div>


                                    <div class="col-md-6"> <div class="form-group" ><strong>Origin Country/HUB:</strong>
                                            <br>

                                            <select   ng-change="showCity();" ng-model="filterData.country_o"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                                                <option value=""><?= lang('lang_Select_Destination'); ?></option>
                                                <?php foreach ($destData as $data) { ?>
                                                    <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div></div>


                                    <div class="col-md-6"> <div class="form-group" ><strong><?= lang('lang_Destination'); ?> Country/HUB:</strong>
                                            <br>

                                            <select   ng-change="showCity2();" ng-model="filterData.country_d"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                                                <option value=""><?= lang('lang_Select_Destination'); ?></option>
                                                <?php foreach ($destData as $data) { ?>
                                                    <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div></div>
                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="origin"><strong>Origin&nbsp;:</strong><b class="error">*</b></label>
                                            
                                            
                                            <select name="origin" id="select_box_origin" class="form-control"  ng-model="filterData.origin_c"  data-width="100%" required>
                                                <option value="" disabled>Select Origin</option>

                                                

                                                <option ng-repeat="data2 in citylist"  value="{{data2.id}}">{{data2.city}}</option>




                                            </select>

                                        </div>  </div>


                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="destination"><strong>Destination&nbsp;:</strong><b class="error">*</b></label>
                                            <select name="destination" id="select_box_destination" class="form-control" ng-model="filterData.destination_d"   data-width="100%" required>
                                                <option value="" disabled>Select Destination</option>
                                                <option ng-repeat="data in citylist2" value="{{data.id}}">{{data.city}}</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="sender_address"><strong>Sender Address&nbsp;:</strong><b class="error">*</b></label>
                                            <textarea  name="sender_address" id="sender_address" class="form-control"   required><?= $shipment[0]->sender_address; ?></textarea>


                                        </div>
                                    </div>

                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="reciever_address"><strong>Receiver Address&nbsp;:</strong><b class="error">*</b></label>
                                            <textarea  name="reciever_address" id="reciever_address" class="form-control"   required><?= $shipment[0]->reciever_address; ?></textarea>


                                        </div>
                                    </div>

                                    <div class="col-md-6"> 
                                        <div class="form-group">

                                            <label for="comment"><strong>Remarks&nbsp;:</strong></label>
                                            <textarea  name="comment" id="comment" class="form-control"   required></textarea>


                                        </div>
                                    </div>

                                    <div class="col-md-12"> 


                                        <button type="submit" name="FrmBtn" class="btn btn-primary pull-right">Update Details</button>

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
        
   
     
        </script> 
    </body>
</html>