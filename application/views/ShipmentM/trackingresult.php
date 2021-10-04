<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
    </head>

    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        
        <div class="page-container" ng-app="fulfill" ng-controller="ExportCtrl" ng-init="slipnosdetails('<?=implode(',', $traking_awb_no);?>');"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 
                        <!--style="background-color: red;"-->


                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                      <h1> <strong><?=lang('lang_Tracking_Parcel_List');?></strong>  <a ng-click="getExcelDetails(filterData.exportlimit);" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> </h1>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <?php

                                   $lang_Tracking_Result_for_AWB=lang('lang_Tracking_Result_for_AWB');	
                                    if (!empty($traking_awb_no))
                                        echo ''.$lang_Tracking_Result_for_AWB.'#      <b>' . implode(',', $traking_awb_no) . '</b>';
                                    ?>
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><b class="size-2"><?=lang('lang_Date');?></b></th>
                                                <th><b class="size-2">AWB</b></th>
                                                <th><b class="size-2"><?=lang('lang_Origin');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Destination');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Pieces');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Weight');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Status');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Action');?></b></th>
                                            </tr>
                                            <?php
                                              $lang_View_detail=lang('lang_View_detail');	
                                            //print_r($shipmentdata);
                                            if (!empty($shipmentdata)) {
                                                foreach ($shipmentdata as $awbdata) {
                                                    echo '<tr>
                                                        <td>' . $awbdata['entrydate'] . '</td>
                                                             <td>' . $awbdata['slip_no'] . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                                                        <td>' . $awbdata['pieces'] . '</td>

                                                        <td>' . $awbdata['weight'] . 'Kg</td>
                                                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>

                                                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary" target="_black">'.$lang_View_detail.'</a></td>

                                                        </tr>';
                                                }
                                            } else {
                                                echo'<tr><td colspan="6" align="center">record not found</td></tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>
                                </div>
                                <hr>
                            </div>
                        </div>
                        
                           <div id="excelcolumn" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #0B70CD;">
                            <h4 class="modal-title text-white" style="margin-bottom:12px;" ><?= lang('lang_Select_Column_to_download'); ?></h4>
                            
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">             
                                    <label class="container">

                                        <input type="checkbox" id='but_checkall' value='Check all' ng-model="checkall" ng-click='toggleAll()'/>   <?= lang('lang_SelectAll'); ?>
                                        <span class="checkmark"></span>


                                    </label>
                                </div>

                                <div class="col-md-12 row">
                                    <div class="col-sm-4">          
                                        <label class="container">  
                                            <input type="checkbox" name="Date" value="Date"    ng-model="listData2.entrydate"> <?= lang('lang_Date'); ?>
                                            <span class="checkmark"></span>
                                        </label>   
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Reference" value="Reference"   ng-model="listData2.booking_id"><?= lang('lang_Reference'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Shipper_Reference" value="Shipper_Reference"   ng-model="listData2.shippers_ref_no"> <?= lang('lang_shipper_Refrence'); ?> #
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="AWB" value="AWB"   ng-model="listData2.slip_no"> <?= lang('lang_AWB_No'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Origin" value="Origin"  ng-model="listData2.origin"> <?= lang('lang_Origin'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Destination" value="Destination"  ng-model="listData2.destination"> <?= lang('lang_Destination'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender" value="Sender"  ng-model="listData2.sender_name"><?= lang('lang_Sender'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender_Address" value="Sender_Address"   ng-model="listData2.sender_address"> <?= lang('lang_Sender_Address'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender_Phone" value="Sender_Phone"   ng-model="listData2.sender_phone"> <?= lang('lang_Sender_Phone'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Receiver" value="Receiver"   ng-model="listData2.reciever_name"> <?= lang('lang_Receiver_Name'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Recevier_Address" value="Recevier_Address"   ng-model="listData2.reciever_address"> <?= lang('lang_Receiver_Address'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Receiver_Phone" value="Receiver_Phone"   ng-model="listData2.reciever_phone"><?= lang('lang_Receiver_Mobile'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Mode" value="Mode"  ng-model="listData2.mode"> <?= lang('lang_Mode'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Status" value="Status"  ng-model="listData2.delivered"> <?= lang('lang_Status'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="COD_Amount" value="COD_Amount"   ng-model="listData2.total_cod_amt"> <?= lang('lang_COD_Amount'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>




                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="UID_Account" value="UID_Account"  ng-model="listData2.cust_id"> <?= lang('lang_UID_Account'); ?>
                                            <span class="checkmark"></span> 
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Pieces" value="Pieces"  ng-model="listData2.pieces" > <?= lang('lang_Pieces'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Weight" value="Weight"  ng-model="listData2.weight" > <?= lang('lang_Weight'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Description" value="Description"  ng-model="listData2.status_describtion" > <?= lang('lang_Description'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <!-- <div class="col-sm-4">
                                         <label class="container">
                                             <input type="checkbox" name="Forward_through" value="Forward_through"  ng-model="listData2.frwd_throw" > Forward through
                                             <span class="checkmark"></span> 
                                         </label>
                                     </div> -->
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="Forward_awb" value="Forward_awb"  ng-model="listData2.frwd_awb_no"> <?= lang('lang_Forwarded_AWB_No'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>  
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="transaction_no" value="transaction_no"  ng-model="listData2.transaction_no"> <?= lang('lang_Transaction_Number'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="close_date" value="close_date"  ng-model="listData2.close_date"> <?= lang('lang_close_date'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    
                                      <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="last_status_n" value="last_status_n"  ng-model="listData2.last_status_n"> Last Status
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>



                                </div>
                                <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                    <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShiptracking(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
                                </div>

                            </div>

                        </div>
                    </div>
                </div>  


            </div>   
                        <!-- /basic responsive table -->
<?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area --> 

                </div>
                <!-- /main content --> 

            </div>
            <!-- /page content --> 

        </div>
        
        <div style="display:none;">
            
              <table  id="downloadtable">
                                        <thead>
                                            <tr>
                                                <th><b class="size-2"><?=lang('lang_Date');?></b></th>
                                                 <th><b class="size-2"><?=lang('lang_AWB_No');?>.</b></th>
                                                <th><b class="size-2"><?=lang('lang_Origin');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Destination');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Pieces');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Weight');?></b></th>
                                                <th><b class="size-2"><?=lang('lang_Status');?></b></th>
                                               
                                            </tr>
                                            <?php
                                            //print_r($shipmentdata);
                                            if (!empty($shipmentdata)) {
                                                foreach ($shipmentdata as $awbdata) {
                                                    echo '<tr>
                                                        <td>' . $awbdata['entrydate'] . '</td>
                                                             <td>' . $awbdata['slip_no'] . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                                                        <td>' . $awbdata['pieces'] . '</td>

                                                        <td>' . $awbdata['weight'] . 'Kg</td>
                                                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>

                                                       

                                                        </tr>';
                                                }
                                            } else {
                                                echo'<tr><td colspan="6" align="center">record not found</td></tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>
        </div>


       
    </body>
</html>
