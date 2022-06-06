<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
 <script src="<?= base_url(); ?>assets/js/angular/courier_company.js"></script> 
</head>

<body ng-app="CourierAppPage" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="forward_shipment_view" ng-init="loadMore_firwarded(1, 0);" > 
  
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
       
        <div class="loader logloder" ng-show="loadershow"></div>
        <div class="row" >
          <div class="col-lg-12" > 
            
            <!-- Marketing campaigns -->
            <div class="panel panel-flat">
              <div class="panel-heading">
                <h1> <strong>Forwarded to 3PL</strong> </h1>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Dashboard content -->
        
        <form  method="post" action="<?= base_url(); ?>Shipment/GetDeliveryStationClient" >
          <div class="row" >
            <div class="col-lg-12" > 
              
              <!-- Marketing campaigns -->
              <div class="panel panel-flat">
                <div class="panel-heading"> </div>
                
                <!-- href="<? // base_url('Excel_export/shipments');  ?>" --> 
                <!-- href="<? //base_url('Pdf_export/all_report_view');  ?>" --> 
                <!-- Quick stats boxes -->
                <div class="table-responsive " >
                  <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 
                    
                    <!-- Today's revenue --> 
                    
                    <!-- <div class="panel-body" > -->
                    
                    <table class="table table-bordered table-hover" style="width: 100%;">
                      <!-- width="170px;" height="200px;" -->
                      <tbody >
                        <tr style="width: 80%;">
                          <td><div class="form-group" ><strong>Warehouse:</strong>
                              <?php
                                                                    $whData = Getwarehouse_Dropdata();

                                                                    //print_r($destData);
                                                                    ?>
                              <select  id="warehouse" name="warehouse"  ng-model="filterData.warehouse" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                <option value="">Select Warehouse</option>
                                <?php foreach ($whData as $data): ?>
                                <option value="<?= $data['id']; ?>">
                                <?= $data['name']; ?>
                                </option>
                                <?php endforeach; ?>
                              </select>
                            </div></td>
                          <td><div class="form-group" ><strong>Origin:</strong> <br>
                              <?php
                                                                    $destData = getAllDestination();

                                                                    //print_r($destData);
                                                                    ?>
                              <select  id="origin" name="origin"  ng-model="filterData.origin" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                <option value="">Select Origin</option>
                                <?php foreach ($destData as $data): ?>
                                <option value="<?= $data['id']; ?>">
                                <?= $data['city']; ?>
                                </option>
                                <?php endforeach; ?>
                              </select>
                            </div></td>
                          <td><div class="form-group" ><strong>Destination:</strong> <br>
                              <?php
                                                                    $destData = getAllDestination();

                                                                    //print_r($destData);
                                                                    ?>
                              <select  id="destination" name="destination"  ng-model="filterData.destination" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                <option value="">Select Destination</option>
                                <?php foreach ($destData as $data): ?>
                                <option value="<?= $data['id']; ?>">
                                <?= $data['city']; ?>
                                </option>
                                <?php endforeach; ?>
                              </select>
                            </div></td>
                        </tr>
                        <tr style="width: 100%;">
                          <td><div class="form-group" ><strong>AWB value:</strong>
                              <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                            </div></td>
                          <td ><a class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></a></td>
                          <td colspan="2"><a  class="btn btn-danger" ng-click="loadMore_firwarded(1, 1);" >Search</a></td>
                        </tr>
                      </tbody>
                    </table>
                    <br>
                    <div id="today-revenue"></div>
                    <!-- </div> panel-body--> 
                    
                    <!-- /today's revenue --> 
                    
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
              <div class="table-responsive" style="padding-bottom:20px;" > 
                <!--style="background-color: green;"-->
                <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>AWB No.</th>
                      <th>Forwarded AWB No.</th>
                      <th>Forwarded Company</th>
                      <th>Origin</th>
                      <th>Destination</th>
                      <th>Warehouse</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                    
                  </thead>
                  <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                    <td>{{$index + 1}} </td>
                    <td>{{data.slip_no}}</td>
                    <td><a href="{{data.frwd_company_label}}" target="_blank">{{data.frwd_company_awb}}</a></td>
                    <td>{{data.cc_name}}</td>
                    <td>{{data.origin}}</td>
                    <td>{{data.destination}}</td>
                    <td>{{data.wh_id}}</td>
                    <td>{{data.entrydate}}</td>
                    <td><a  class="btn btn-danger" ng-confirm-click="You are sure want cancel order ?" ng-click="GetCanelBplOrder(data.slip_no);">Cancel</a></td>
                  </tr>
                </table>
                <a ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore_firwarded(count = count + 1, 0);" ng-init="count = 1">Load More</a> </div>
              <hr>
            </div>
          </div>
          <!-- /basic responsive table -->
        </form>
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
