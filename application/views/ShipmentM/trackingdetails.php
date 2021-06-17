<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
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
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 
                        <!--style="background-color: red;"-->
                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 
                                <?php 
                                    $awb_label = ''; $trackFlag = FALSE; $track_awb= '';
                                    if(!empty($Shipmentinfo['frwd_company_awb'])){
                                        $track_url = GetCourCompanynameId($Shipmentinfo['frwd_company_id'], 'company_url');
                                        if(!empty($track_url)){
                                            $trackFlag = TRUE;
                                            $track_awb = $track_url.$Shipmentinfo['frwd_company_awb'];
                                        }else{
                                            $track_awb = '#';
                                        }
                                        $awb_label = ' / ( 3pl-Label : <a href="'.$Shipmentinfo['frwd_company_label'].'" target="_blank" >'.$Shipmentinfo['frwd_company_awb'].' </a> )';
                                    }
                                ?>
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        
                                        <h1> <strong>Detail - (Tracking No. :
                                                <?= $Shipmentinfo['slip_no']; ?>
                                                ) / (Reference No. : <?php echo $Shipmentinfo['booking_id'] ?>) <?php echo $awb_label; ?></strong> 
                                        <?php if($trackFlag){ ?>
                                            <a class="btn btn-danger" target="_blank" href="<?php echo $track_awb; ?>" >Track</a>
                                        <?php } ?>
                                        </h1>
                                        
                                                
                                    </div>
                                    


<!-- href="<? // base_url('Excel_export/shipments');  ?>" --> 
<!-- href="<? //base_url('Pdf_export/all_report_view');  ?>" --> 
                                    <!-- Quick stats boxes --> 

                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                            
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <div class="panel-heading">
                                        <h1><strong>Shipment Info</strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                            if ($Shipmentinfo['booking_id'] != '')
                                                echo' <tr><th><b class="size-2">Reference No.</b></th><td>' . $Shipmentinfo['booking_id'] . '</td></tr>';

                                            echo'<tr><th><b class="size-2">Shipper Reference No.</b></th>';
                                            if ($Shipmentinfo['shippers_ref_no'] != '')
                                                echo'<td>' . $Shipmentinfo['shippers_ref_no'] . '</td>';
                                            else
                                                echo'<td>--</td>';
                                            echo'</tr>';
                                            echo' <tr><th><b class="size-2">Entry Date</b></th><td>' . date('d-m-Y', strtotime($Shipmentinfo['entrydate'])) . '</td></tr>
                          <tr><th><b class="size-2">Origin</b></th><td>' . getdestinationfieldshow($Shipmentinfo['origin'], 'city') . '</td></tr>
                          <tr><th><b class="size-2">Destination</b></th><td>' . getdestinationfieldshow($Shipmentinfo['destination'], 'city') . '</td></tr>';
                                            if ($Shipmentinfo['total_amt'])
                                                echo'<tr><th><b class="size-2">Net Price</b></th><td>' . $Shipmentinfo['total_amt'] . '</td></tr>';
                                            echo'<tr><th><b class="size-2">No. of Pieces</b></th><td>' . $Shipmentinfo['pieces'] . '</td></tr>';
                                            if ($Shipmentinfo['mode'] == 'COD')
                                                echo' <tr><th><b class="size-2">Payment Mode</b></th><td>' . $Shipmentinfo['mode'] . ' ' . $Shipmentinfo['total_cod_amt'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Payment Mode</b></th><td>' . $Shipmentinfo['mode'] . '</td></tr>';
                                            echo'<tr><th><b class="size-2">Schedule Chanel</b></th>';
                                            if ($Shipmentinfo['schedule_type'])
                                                echo'<td><span class="label label-success">' . $Shipmentinfo['schedule_type'] . '</span></td>';
                                            else
                                                echo'<td><span class="label label-danger">N/A</span></td>';
                                            echo'</tr>';
                                            echo '<tr><th><b class="size-2">Payment Date</b></th><td>--</td></tr>';
                                            if ($Shipmentinfo['shipping_zone'])
                                                echo'<tr><th><b class="size-2">Shipping Zone</b></th><td>' . $Shipmentinfo['shipping_zone'] . '</td></tr>';

                                            echo'<tr><th><b class="size-2">Scheduled</b></th>';
                                            if ($Shipmentinfo['schedule_status'] == 'Y')
                                                echo'<td>' . $Shipmentinfo['schedule_date'] . ' | ' . $Shipmentinfo['time_slot'] . '</td>';
                                            else
                                                echo'<td>NO</td>';
                                            echo'</tr>';
                                            if ($Shipmentinfo['shelv_no'])
                                                echo'<tr><th><b class="size-2">Shelve No.</b></th><td>' . $Shipmentinfo['shelv_no'] . '</td></tr>';
                                            if ($Shipmentinfo['shelv_no'])
                                                echo'<tr><th><b class="size-2">Shelve Location.</b></th><td>' . $Shipmentinfo['shelv_no'] . '</td></tr>';
                                            if ($Shipmentinfo['refused'] == 'YES')
                                                echo' <tr><th><b class="size-2">On Hold</b></th><td>YES</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">On Hold</b></th><td>No</td></tr>';

                                            if ($Shipmentinfo['mode'] == 'COD')
                                                $colorclass = 'style="background-color:#AEFFAE;"';
                                            if ($Shipmentinfo['booking_mode'] == 'Pay at pickup' && $Shipmentinfo['total_cod_amt'] != 0)
                                                $colorclass2 = 'style="background-color:#AEFFAE;"';
                                            if ($Shipmentinfo['amount_collected'] == 'N')
                                                echo' <tr><th><b class="size-2">Ammount Collected</b></th><td>No</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Ammount Collected</b></th><td>Yes</td></tr>';
                                            echo' <tr><th><b class="size-2">Weight</b></th><td>' . $Shipmentinfo['weight'] . 'Kg</td></tr>
                          <!--<tr><th><b class="size-2" >Status </b></th><td ' . $colorclass . ' ' . $colorclass2 . '>' . getallmaincatstatus($Shipmentinfo['delivered'], 'main_status') . '</td></tr>-->
                          <tr><th><b class="size-2" >Status </b></th><td ' . $colorclass . ' ' . $colorclass2 . '>' . getStatusByCode_fm($Shipmentinfo['code'] ) . '</td></tr>
                         <!-- <tr><th><b class="size-2">Store Link</b></th><td>' . $Shipmentinfo['cust_id'] . '</td></tr>
                          <tr><th><b class="size-2">User Type</b></th><td>' . $Shipmentinfo['cust_id'] . '</td></tr>-->
                          <tr><th><b class="size-2">Product Type</b></th><td>' . $Shipmentinfo['nrd'] . '</td></tr>
                          <tr><th><b class="size-2">No. Of Attempt</b></th><td>' . $Shipmentinfo['no_of_attempt'] . '</td></tr>                              
                          <tr><th><b class="size-2">3pl Pickup Date</b></th><td>' . $Shipmentinfo['pl3_pickup_date'] . '</td></tr>
                          <tr><th><b class="size-2">3pl Closed Date</b></th><td>' . $Shipmentinfo['pl3_closed_date'] . '</td></tr>
                          <tr><th><b class="size-2">Transaction Date</b></th><td>' . $Shipmentinfo['transaction_date'] . '</td></tr>
                          <tr><th><b class="size-2">Product Description</b></th><td>' . $Shipmentinfo['status_describtion'] . '</td></tr>';
                                            ?>
                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">View Details</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>

                                     <div class="panel-heading">
                                        <h1><strong>Sender Info</strong></h1>
                                    </div>
                                  
                                      <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                            if ($Shipmentinfo['sender_name '] != '')
                                                echo' <tr><th><b class="size-2">Sender</b></th><td>' . $Shipmentinfo['sender_name '] . '</td></tr>';
                                            else
                                                 echo' <tr><th><b class="size-2">Sender</b></th><td style="color:grey;">No Info Found</td></tr>';

                                           
                                            if ($Shipmentinfo['sender_address'] != '')
                                                echo' <tr><th><b class="size-2">Sender Address</b></th><td>' . $Shipmentinfo['sender_address'] . '</td></tr>';
                                            else
                                                 echo' <tr><th><b class="size-2">Sender Address</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            if ($Shipmentinfo['sender_phone'] != '')
                                            echo'<tr><th><b class="size-2">Sender Mobile</b></th><td>' . ($Shipmentinfo['sender_phone']) . '</td></tr>';
                                               else
                                                 echo' <tr><th><b class="size-2">Sender Mobile</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            if ($Shipmentinfo['sender_email '] != '')
                                            echo'<tr><th><b class="size-2">Sender Email</b></th><td>' . ($Shipmentinfo['sender_email  ']) . '</td></tr>';
                                                 else
                                                 echo' <tr><th><b class="size-2">Sender Email</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            ?>
                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">View Details</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>

                                    <div class="panel-heading">
                                        <h1><strong>Receiver Info</strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                            if ($Shipmentinfo['reciever_name'] != '')
                                                echo' <tr><th><b class="size-2">Receiver</b></th><td>' . $Shipmentinfo['reciever_name'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Sender</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            if ($Shipmentinfo['reciever_address'] != '')
                                                echo'<tr><th><b class="size-2">Receiver Address</b></th><td>' . $Shipmentinfo['reciever_address'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Receiver Address</b></th><td style="color:grey;">No Info Found</td></tr>';
                                           
                                            if ($Shipmentinfo['reciever_phone'] != '')
                                                echo'<tr><th><b class="size-2">Receiver Mobile</b></th><td>' . ($Shipmentinfo['reciever_phone']) . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Receiver Mobile</b></th><td style="color:grey;">No Info Found</td></tr>';


                                           
                                            if ($Shipmentinfo['reciever_email'] != '')
                                                echo' <tr><th><b class="size-2">Receiver Email</b></th><td>' . ($Shipmentinfo['reciever_email']) . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Receiver Email</b></th><td style="color:grey;">No Info Found</td></tr>';
                         
                                            ?>
                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">View Details</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>

                                    <div class="panel-heading">
                                    <h1><strong>Forwarded Info</strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                            if ($Shipmentinfo['frwd_date'] != '')
                                                echo' <tr><th><b class="size-2">Forwarded Date</b></th><td>' . $Shipmentinfo['frwd_date'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Forwarded Date</b></th><td style="color:grey;">No Info Found</td></tr>';

                                           
                                            if ($Shipmentinfo['frwd_company_id'] != '0')
                                                echo   '<tr><th><b class="size-2">Forwarded Company</b></th><td>' . GetCourCompanynameId($Shipmentinfo['frwd_company_id'], 'company') . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Forwarded Company </b></th><td style="color:grey;">No Info Found</td></tr>';
                                           
                                           
                                            if ($Shipmentinfo['frwd_company_awb'] != '')
                                                echo'<tr><th><b class="size-2">Forwarded AWB No.</b></th><td>' . ($Shipmentinfo['frwd_company_awb']) . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Forwarded AWB No.</b></th><td style="color:grey;">No Info Found</td></tr>';


                                           
                                            if ($Shipmentinfo['forwarded'] != '0')
                                                echo' <tr><th><b class="size-2">Forwarded </b></th><td> Yes </td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Forwarded </b></th><td style="color:grey;">No</td></tr>';
                         
                                          
                                            ?>
                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">View Details</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>


                                    <div class="panel-heading">
                                        <h1><strong>Dimension Details</strong></h1>
                                    </div>
                                    <?php
                                    $skuArr = Getallskudatadetails_tracking($Shipmentinfo['slip_no']);
                                    echo ' <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
              <thead>
                  <tr>
                    <th width="20">Sr.No</th><th>SKU</th><th>Weight</th><th>Pieces</th><th>Description</th></thead>
              ';
                                    foreach ($skuArr as $key => $skuval) {
                                        $counter2 = $key + 1;
                                        echo '<tr><td>' . $counter2 . '</td><td>' . $skuval['sku'] . '</td><td>' . $skuval['wieght'] . ' KG</td><td>' . $skuval['piece'] . '</td><td>' . $skuval['description'] . '</td></tr>';
                                    }
                                    echo '</table>';
                                    ?>

                                    <div class="panel-heading">
                                        <h1><strong>Latest Status (Travel History)</strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th width="20">Sr.No</th>
                                                <th width="110">Date</th>
                                                <th width="150">Activities</th>
<!--                                                <th>Location</th>
                                                <th>City Code</th>-->
                                                <th>Code</th>
                                                <th>Details</th>
                                                <th>User Name</th>
                                                <th>User Type</th>
                                                <th>Comment</th>
                                            </tr>
                                            <?php
                                            $counter = 0;
//print_r($THData);
                                            
                                            foreach ($THData as $historydata) {
                                                $counter1 = $counter + 1;
                                                echo'<tr>
                                                <td>' . $counter1 . '</td>
                                                <td>' . date("Y-m-d H:i:s", strtotime($historydata['entry_date'])) . '</td>
                                                <td>' . $historydata['Activites'] . '</td>';
                                //    if ($historydata['new_location'] > 0)
                                //        echo'<td>' . getdestinationfieldshow($historydata['new_location'], 'city') . '</td>';
                                //    else
                                //        echo'<td>--</td>';
                                //    if ($historydata['new_location'] > 0)
                                //        echo'<td>' . getdestinationfieldshow($historydata['new_location'], 'city_code') . '</td>';
                                //    else
                                //        echo'<td>--</td>';
                                                echo'<td>' . $historydata['code'] . '</td>
                <td>' . $historydata['Details'] . '</td>
                <td>' . getUserNameByIdType($historydata['user_id'], $historydata['user_type'], $Shipmentinfo['Api_Integration']) . '</td>
                <td>' . $historydata['user_type'] . '</td>';
                                                if ($historydata['comment'])
                                                    echo'<td>' . $historydata['comment'] . '</td>';
                                                else
                                                    echo'<td>--</td>';
                                                echo'</tr>';
                                                $counter++;
                                            }
                                            ?>
                                        </thead>
                                    </table>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table -->
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area --> 

                </div>
                <!-- /main content --> 

            </div>
            </div>
           </div>
            <!-- /page content --> 



        </div>

        <!-- /page container -->

    </body>
</html>
