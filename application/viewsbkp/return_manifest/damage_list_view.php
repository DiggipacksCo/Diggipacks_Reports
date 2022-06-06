<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js"></script>

       


       
    </head>

    <body ng-app="Appiteminventory">
        <?php $this->load->view('include/main_navbar');
        
        ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CtritemInvontaryview" >  

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
                                <div class="panel panel-flat" >
                                    <div class="panel-heading">
                                        <h1><strong><?= lang('lang_Show_Damage_Item'); ?></strong>
                                            <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 
                                             
                                          
                                          
                                        </h1>

<!-- <i class="icon-file-excel pull-right" style="font-size: 35px;"></i> --> 
                                    </div>
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 

                                            <!-- Today's revenue --> 

                                            <!-- <div class="panel-body" style="background-color: pink;"> -->

                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >

                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong><?= lang('lang_SKU'); ?>:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                            </div></td>
                                                            
                                                               <td ><div class="form-group" ><strong><?= lang('lang_Sellers'); ?>:</strong> <br>
                                                                <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                                                                    <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                                                    <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                            <?= $seller_detail->company; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>
                                                            
                                                                <td colspan="1"><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                        <td colspan="1"> <button  class="btn btn-danger" ng-click="loadMore_damage(1, 1);" style="margin-left: 7%" ><?= lang('lang_Search'); ?></button></td>
                                                        
                                                        <td colspan="1" ng-if="Items.length>0"> <button  class="btn btn-danger" ng-click="GetCheckReturnOrderProcesspop();" style="margin-left: 7%" ><?= lang('lang_Create_Order_Return'); ?></button></td>

                                                     
                                                        
                                                      
                                                    </tr>
                                                 </tbody>
                                            </table>
                                            <br>
                                          

                                        </div>
                                    </div>

                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                           

                            <div class="panel-body" > 
                              <!-- <input type="text" value="{{data1.sku}}" id="check" style="display: none;" name="check" />
                                -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable" id="printTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.
                                                    <br> <input type="checkbox" ng-model="selectedAll"  ng-change="selectAll();" />
                                                </th>
                                                 <th><?= lang('lang_Return_Type'); ?></th>
                                                <th><?= lang('lang_Name'); ?></th>
                                              
                                                <th><?= lang('lang_Item_Image'); ?></th>
                                              
                                                <th><?= lang('lang_ItemSku'); ?></th>
                                               
                                              

                                                <th><?= lang('lang_Quantity'); ?></th>
                                                <th><?= lang('lang_Sellers'); ?></th>
                                             
                                               
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="">

                                            

                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}}   <input ng-if="data.return_update=='N'" type="checkbox" value="{{data.id}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" />
                                                
                                                    <input ng-if="data.return_update=='Y'" title="Already Returned"type="checkbox" disabled="" /></td>
                                                
                                                 <td>{{data.order_type}}</td>
                                                <td>{{data.name}}</td>
                                              
                                                <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="70">
                                                    <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="70">
                                                </td>
<td>{{data.sku}}</td>
                                              <td ><span class="badge badge-success">{{data.quantity}}</span></td>
                                                <td>{{data.seller_name}}</td>
</tr>
                                        </tbody>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore_damage(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
                                </div>
                                
                                
                                <hr>
                                
                                
                                  <div class="modal fade" id="PopidreturnShowitem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">



                            <h5 class="modal-title" id="exampleModalLabel"><?= lang('lang_Return_Order'); ?> {{uniqueid}} </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-warning" ng-if="message.error" id="errhide">{{message.error}}</div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                            <div class="col-md-12" ng-if="invalidSslip_no">
                                <div class="alert alert-warning" ng-if="invalidSslip_no" ng-repeat="in_data in invalidSslip_no"><?= lang('lang_Invalid_slip_no'); ?>. "{{in_data}}"</div>
                            </div>
                            <div class="col-md-12" ng-if="Success_msg">
                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?= lang('lang_Shipment_Forwarded'); ?></div>
                            </div>
                            <div class="col-md-12" ng-if="Error_msg">
                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                            </div>
                            <div class="col-md-12" ng-if="responseError">
                                <div class="alert alert-danger" ng-repeat="error_response in responseError">{{error_response}}</div>
                            </div>




                            <div class="col-md-12" ng-if="mainstatusEmpty">
                                <div class="alert alert-danger" ng-if="mainstatusEmpty">{{mainstatusEmpty}}</div>
                            </div>
                            <div class="col-md-12" ng-if="messArray1 != 0">
                                <div class="alert alert-danger" ng-repeat="mdata in messArray1"> <?= lang('lang_wrong_AWB_no'); ?> {{mdata}}</div>    
                            </div>
                            <form novalidate ng-submit="myForm.$valid && createUser()" >
                                <input type="radio" name="assign_type" ng-model="returnUpdate.assign_type" ng-click="GetChangeAssignType('D');" value="D"  > <?= lang('lang_Drivers'); ?> <input type="radio" name="assign_type" value="CC" ng-model="AssignData.assign_type" ng-click="GetChangeAssignType('CC');"  > <?= lang('lang_Courier_Company'); ?>
                                <div ng-show="driverbtn">
                                    <select type="text"  ng-model="returnUpdate.assignid" class="form-control" required>
                                        <option ng-repeat="x in assigndata"  value="{{x.id}}">{{x.username}}</option>
                                    </select>
                                </div>
                                <div ng-show="crourierbtn">

                                    <select type="text"  ng-model="returnUpdate.cc_id" class="form-control" required>
                                        <option ng-repeat="cx in courierData"  value="{{cx.cc_id}}">{{cx.company}}</option>
                                    </select>
                                </div>
                                <br>
                                  <div>

                                      <input type="radio"  ng-model="returnUpdate.pack_type" value="P" > Pallet <input type="radio" ng-model="returnUpdate.pack_type" value="B" > Box 
                                      
                                     
                                </div>
                                <div> <input type="text" ng-model="returnUpdate.boxes" class="form-control" placeholder="Enter Qty" required></div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('lang_Close'); ?></button>
                            <button type="button" ng-if="returnUpdate.assign_type == 'D'" class="btn btn-primary" ng-click="saveassigntodriver();" ><?= lang('lang_Update_Driver'); ?></button>
                            <button type="button" ng-if="returnUpdate.assign_type == 'CC'" class="btn btn-primary" ng-click="saveassigntodriver();" ><?= lang('lang_Send_Courier'); ?></button>
                        </div>
                        </form>          
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
          
          


        </div>


        <!-- /page container --> 
        <script type="text/javascript">
            setTimeout(function() {
                 $('.alert-danger').fadeOut();
                }, 10000 );
            function printPage()
            {
                var divToPrint = document.getElementById('printTable');
                var htmlToPrint = '' +
                        '<style type="text/css">' +
                        'table th, table td {' +
                        'border:1px solid #000;' +
                        'width:1200px' +
                        '}' +
                        'table th, table td {' +
                        'border:1px solid #000;' +
                        'padding:8px;' +
                        '}' +
                        'table th {' +
                        'padding-top: 12px;' +
                        'padding-bottom: 12px;' +
                        ' text-align: left;' +
                        'border:1px solid #000;' +
                        'padding:0.5em;' +
                        '}' +
                        '</style>';
                htmlToPrint += divToPrint.outerHTML;
                newWin = window.open("");
                newWin.document.write(htmlToPrint);
                newWin.print();
                newWin.close();
            }
        </script>
    

    </body>
</html>
