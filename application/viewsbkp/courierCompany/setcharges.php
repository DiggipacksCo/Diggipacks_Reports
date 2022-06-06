<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>
<script src="<?=base_url();?>assets/js/angular/rates.app.js"></script>
</head>

<body ng-app="ZoneRateApp" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CTR_zonerate"> 
  <!--getallseller--> 
  
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
if($this->session->flashdata('succmsg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('succmsg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

if($this->session->flashdata('errormess'))
echo '<div class="alert alert-warning">'.$this->session->flashdata('errormess').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
?>
        <div class="row" >
          <div class="col-lg-12" > 
            
            <!-- Marketing campaigns -->
            <div class="panel panel-flat">
              <div class="panel-heading">
                <h1> <strong>Add Zone Rate</strong> 
                  <!--  <a  ng-click="exportmanifestlist();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>--> 
                  <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>--> 
                </h1>
              </div>
              
              <!-- href="<?// base_url('Excel_export/shipments');?>" --> 
              <!-- href="<?//base_url('Pdf_export/all_report_view');?>" --> 
              <!-- Quick stats boxes -->
              <div class="table-responsive " >
                <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 
                  
                  <!-- Today's revenue --> 
                  
                  <!-- <div class="panel-body" > --> 
                  <!-- width="170px;" height="200px;" -->
                  <form  method="post">
                    <table class="table table-bordered table-hover" style="width: 100%;">
                      <tbody >
                        <tr style="width: 80%;">
                          <td><div class="form-group" ><strong>Zones:</strong>
                              <select id="zone_id_form" name="zone_id_form" ng-model="zone_id"  class="form-control">
                                <option value="">Select Zone</option>
                                <?php
			   foreach($zonesList as $value)
			   {
			    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
			   }
			   ?>
                              </select>
                            </div></td>
                          <!--<td><div class="form-group" ><strong>Service:</strong>
              <select id="service_id" name="service_id" ng-model="ratesArr.service_id" class="form-control">
                <option value="">Select  Service</option>
                <option ng-repeat="Nsdata in servicesArr"  value="{{Nsdata.id}}">{{Nsdata.services_name}}</option>
              </select>
            </div></td>-->
                          <td><input type="button"  class="btn btn-danger" value="Get Rates" ng-click="GetrateBycompanyZones(zone_id,<?=$company_id;?>);"></td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
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
        <div class="panel panel-flat" >
          <div class="panel-body" >
            <div class="table-responsive" style="padding-bottom:20px;" > 
              <!--style="background-color: green;"-->
              <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;" ng-show="tableshow">
                <thead>
                  <tr>
                    <th>WEIGHT RANGE</th>
                     <td><label> {{showweightArray.start_range}}<strong> TO </strong>{{showweightArray.end_range}}</label></td>
                  
                   
                   
                
                  </tr>
                </thead>
                <tr>
                   <th>Charge</th>
                  <td><input type="text" ng-model="showRatesArray.price"  placeholder="Charge per kg"  class="form-control"  /></td>
                  
                 
                </tr>
                <tr> <th>Additional Charges</th><td><input type="text" ng-model="showRatesArray.extra_charge"  placeholder="Additional Charges"  class="form-control"  /></td></tr>
                  <tr> <th>Return Charge</th> <td><input type="text" ng-model="showRatesArray.return_fees"  placeholder="Return Charge"  class="form-control"  /></td></tr>
                <tr>
                  <td colspan="4" align="right"><button  class="btn btn-info" ng-show = "IsVisible" ng-click="getUpdateratesdata();">Update</button></td>
                </tr>
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
</body>
</html>
