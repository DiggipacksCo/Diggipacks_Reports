<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
  <title><?= lang('lang_Inventory'); ?></title>
  <?php $this->load->view('include/file'); ?>
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/iteminventory.app.js"></script>

</head>

<body ng-app="Appiteminventory" ng-controller="CTR_vehicleList">

  <?php $this->load->view('include/main_navbar'); ?>


  <!-- Page container -->
  <div class="page-container" >

    <!-- Page content -->
    <div class="page-content" ng-init="loadMore(1,0)">

      <?php $this->load->view('include/main_sidebar'); ?>


      <!-- Main content -->
      <div class="content-wrapper" >
        <!--style="background-color: black;"-->
        <?php $this->load->view('include/page_header'); ?>



        <!-- Content area -->
        <div class="content" >
          <!--style="background-color: red;"-->
          
          
         
<?php if($this->session->flashdata('msg')):?>
<?= '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?> 
<?php elseif($this->session->flashdata('error')):?>
<?= '<div class="alert alert-danger">'.$this->session->flashdata('error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?>
<?php endif;?>


          <!-- Basic responsive table -->
          <div class="panel panel-flat"  >
            <!--style="padding-bottom:220px;background-color: lightgray;"-->
            <div class="panel-heading"dir="ltr"
              <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?= lang('lang_Vehicle_Table'); ?></strong>
              
            
              </h1>

              <hr>

            </div>

            <div class="panel-body" >

            <table class="table table-bordered table-hover" style="width: 100%;">
                    <!-- width="170px;" height="200px;" -->
                    <tbody >
                    
                      <tr style="width: 80%;">
                      
                                            <td ><div class="form-group" ><strong><?= lang('lang_Name'); ?>:</strong>
                            <input type="text"  id="name" name="name"  ng-model="filterData.name" class="form-control" placeholder="Enter Name">
                          </div></td>
                          
                          
                                            <td ><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?= lang('lang_Search'); ?></button></td>
                        
                      </tr>
                      
                    </tbody>
                  </table>

            <div class="table-responsive" style="padding-bottom:20px;" >
              <!--style="background-color: green;"-->
              <table class="table table-striped table-hover table-bordered" id="example">
                <thead>
                  <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                  
                                                <th><?= lang('lang_Name'); ?></th>
                   
                                                <th><?= lang('lang_Icon'); ?></th>
                   
                    <!-- <th>Category</th> -->
                    <th class="text-center" ><i class="icon-database-edit2"></i></th>
                  </tr>
                </thead>
                <tbody>
                  
                      <tr ng-if="shipData!=0" ng-repeat="data in shipData">
                      <td>{{$index+1}}</td>
                        <td><a href="<?=base_url();?>edit_vehicle/{{data.id}}">{{data.name}}</a></td>
                       <td><img ng-if="data.icon_path!=''" src="<?=base_url();?>{{data.icon_path}}" width="65">
                      <img ng-if="data.icon_path==''" src="<?=base_url();?>assets/nfd.png" width="65"></td>
                      
                   
                      <td class="text-center">
                        <ul class="icons-list">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="icon-menu9"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                                                <li><a href="<?= base_url(); ?>edit_vehicle/{{data.id}}"><i class="icon-pencil7"></i> <?= lang('lang_Edit'); ?> </a></li>
                                                                <li><a href="<?= base_url(); ?>deleteVehicle/{{data.id}}"><i class="icon-trash-alt"></i> <?= lang('lang_Delete'); ?></a></li>
                             
                              
                            </ul>
                          </li>
                        </ul>
                      </td>
                    </tr>

              </tbody>
            </table>
            
           
           </div>
           <button ng-hide="shipData.length<100 || shipData.length==totalCount || shipData==0" class="btn btn-info" ng-click="loadMore(count=count+1,0);" ng-init="count=1"><?= lang('lang_Load_More'); ?></button>
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