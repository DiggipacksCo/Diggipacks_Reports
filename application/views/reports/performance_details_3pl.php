<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
  <title><?= lang('lang_Inventory'); ?></title>
  <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/reports.app.js"></script>


</head>

<body>

  <?php $this->load->view('include/main_navbar'); ?>


  <!-- Page container -->
  <div class="page-container" ng-app="AppReports">

    <!-- Page content -->
    <div class="page-content"  ng-controller="ClientReportCTRL" ng-init="Get3plDetails(1, 0,'<?= $Urldata['frwd_throw'] ?>','<?= $Urldata['status'] ?>','<?= $Urldata['from'] ?>','<?= $Urldata['to'] ?>')">

      <?php $this->load->view('include/main_sidebar'); ?>


      <!-- Main content -->
      <div class="content-wrapper" >
        <!--style="background-color: black;"-->
        <?php $this->load->view('include/page_header'); ?>



        <!-- Content area -->
        <div class="content" >
          <!--style="background-color: red;"-->

          <!-- Basic responsive table -->
          <div class="panel panel-flat" >
            <!--style="padding-bottom:220px;background-color: lightgray;"-->
            <div class="panel-heading">
              <!-- <h5 class="panel-title">Basic responsive table</h5> -->
              <h1><strong>Report Details</strong></h1>

              <div class="heading-elements">
                <ul class="icons-list">
                  <!-- <li><a data-action="collapse"></a></li>
                  <li><a data-action="reload"></a></li> -->
                  <!-- <li><a data-action="close"></a></li> -->
                </ul>
              </div>
              <hr>
            </div>

            <div class="panel-body" >

          <div class="row" >
                                    <div class="col-lg-12" >

                                        <!-- Marketing campaigns -->
                                        <div class="panel panel-flat">



                                            <div class="panel-body" >
                                                <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                    <div class="col-md-5">
                                                        <div class="form-group" ><strong>Search :</strong>
                                                            <input   ng-model="filterData.searchval" class="form-control" placeholder="Enter AWB No. || 3PL AWB"> 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5" style="margin-top: 20px; ">
                                                        <div class="form-group" >
                                                            <button  class="btn btn-danger" ng-click="Get3plDetails(1, 1,'<?= $Urldata['frwd_throw'] ?>','<?= $Urldata['status'] ?>','<?= $Urldata['from'] ?>','<?= $Urldata['to'] ?>');" >Search</button>
                                                            <button type="button" class="btn btn-success" style="margin-left: 7%">Total <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                        </div>
                                                    </div>


                                                </div>



                                            </div>

                                            <!-- /quick stats boxes -->
                                        </div>
                                    </div>
                                </div>
            

          <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered" >
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Company</th>
                                                <th>AWB No.</th>
                                                <th>3PL AWB</th>
                                                <th>Shipment Type</th>
                                                <th>Sender Name</th>
                                                <th>Sender Mobile</th>
                                                <th>Receiver Name</th>
                                                <th>Receiver Mobile</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            
                                            <tr ng-repeat="data in shipData">

                                                <td>{{$index + 1}}</td> 
                                                <td>{{data.company}}</td> 
                                                <td>{{data.slip_no}}</td> 
                                                <td>{{data.frwd_company_awb}}</td> 
                                                <td>{{data.mode}}</td> 
                                                <td>{{data.sender_name}}</td> 
                                                <td>{{data.sender_phone}}</td> 
                                                <td>{{data.reciever_name}}</td> 
                                                <td>{{data.reciever_phone}}</td> 
                                                <td>{{data.main_status}}</td> 


                                            </tr>

                                        </tbody>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="GetofdDetails(count = count + 1, 0,'<?= $Urldata['frwd_throw'] ?>','<?= $Urldata['status'] ?>','<?= $Urldata['from'] ?>','<?= $Urldata['to'] ?>');" ng-init="count = 1">Load More</button>

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
 <!-- /page content -->




</div>

</body>
</html>
