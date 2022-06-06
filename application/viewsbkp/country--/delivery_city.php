<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Delivery Company City</title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>/assets/js/angular/country.app.js"></script>
    </head>

    <body ng-app="AppCountry" ng-controller="DeliveryCItylistCtrl" ng-init="loadMore(1,0);">
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


                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Delivery Company City</strong> 

                                </h1>

                            </div>
                            <div class="panel-body" > 


                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered " id="example">
                                        <thead>
                                            <tr>
                                                <th>Sr.No.</th>
                                                <th>Courier Company</th>
                                                <th >
                                                   City List
                                                   <table class="table table-striped table-hover table-bordered">
                                                           
                                                           <tr><td>City Name</td><td>Courier City</td></tr>
                                                       
                                                       </table>
                                                </th>
                                                
                                              


                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr ng-repeat="hederdata in shipData">
                                               <td>{{$index+1}}</td>  
                                                  <td>{{hederdata.cc_name}}</td>  
                                               
                                                   <td>
                                                      <table class="table table-striped table-hover table-bordered">
                                                           
                                                          
                                                        <tr ng-repeat="data in hederdata.city_name"><td>{{data.city_id}}</td><td>{{data.city_name}}</td></tr>
                                                       </table>
                                                   
                                                      </td> 



                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!--  <div>
                                  <center>
                                <?php //echo $links;  ?> 
                                 </center>
                               </div> -->
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

        <!-- /page container -->

    </body>
</html>
