<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Add Route</title>
        <?php $this->load->view('include/file'); ?>


    </head>
<?php  if(!empty($EditData)){
$countyname="'".$EditData['country_id']."'";
}
?>
    <body ng-app="CountryApp" ng-controller="CountrypageCrl" ng-init="<?php  if(!empty($EditData)){echo'getStatelist('.$countyname.');'; }?>">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong> <?php if(!empty($EditData)){ echo 'Edit';} else { echo 'Add';}?> Route</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('errormess') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('errormess') . '.</div>';
                                }
                                ?>

                                <form action="<?= base_url('RoutsManagement/addRouteFormSubmit'); ?>" name="adduser" method="post">

                                    <input type="hidden" id="edit_id" name="edit_id" value="<?=$EditData['id'];?>">
                                    
                                     <div class="form-group">
                                        <label for="country" ><strong>Country:</strong></label>
                                        <select name="country" class="form-control" id="country" required ng-model="country" ng-change="getStatelist(country);">
                                            
                                            <option value="">Select</option>
                                            <?php 
                                            
                                            foreach($Countrylist as $val)
                                            {
                                                if($EditData['country_id']==$val['country'])
                                                {
                                                   echo '<option value="'.$val['country'].'" selected="selected">'.$val['country'].'</option>'; 
                                                }
                                                else
                                                {
                                             echo '<option value="'.$val['country'].'">'.$val['country'].'</option>';
                                                }
                                            }
                                                     ?>
                                        </select>
                                    </div> 
                                    
                                     <div class="form-group">
                                        <label for="city_id" ><strong>Select City:</strong></label>
                                        <select name="city_id" class="form-control" id="city_id" required ng-model="city_id" >
                                            
                                            <option value="">Select City</option>
                                            <option ng-repeat="cdata in stateListArr" value="{{cdata.city}}">{{cdata.city}}</option>
                                          
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <label for="route"><strong>Route:</strong></label>
                                       <input type="text" class="form-control" name='route' id="country" required placeholder="Enter Route" value="<?=$EditData['route'];?>">
                                    </div> 
                                      <div class="form-group">
                                        <label for="routecode"><strong>Route Code:</strong></label>
                                        <input type="text" class="form-control" name='routecode' id="country" required placeholder="Enter Route Code" value="<?=$EditData['routecode'];?>">
                                    </div> 
                                    
                                    <div class="form-group">
                                        <label for="keyword"><strong>Arabic Keyword:</strong></label>
                                       <input type="text" class="form-control" name='keyword' id="keyword" required placeholder="Enter Arabic Keyword" value="<?=$EditData['keyword'];?>">
                                    </div> 
                                    <div class="form-group">
                                        <label for="latlang"><strong>latitude,longitude:</strong></label>
                                       <input type="text" class="form-control" name='latlang' id="latlang" required placeholder="latitude,longitude" value="<?=$EditData['latlang'];?>">
                                    </div> 
                                   
                             
                                    
                                   


                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success">Submit</button>
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

    </body>
</html>
<script type="application/javascript">
var app = angular.module('CountryApp', [])
.controller('CountrypageCrl', function($scope,$http,$window,$location) {
    $scope.filterData={};
     $scope.stateListArr={};
   
     $scope.country="<?=$EditData['country_id']?>";
      $scope.city_id="<?=$EditData['city_id']?>";
    $scope.getStatelist=function(country)
    {
        
        
        $scope.filterData.country=$scope.country;
         $http({
		url: "<?=base_url()?>RoutsManagement/RouteCityDrop",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           $scope.stateListArr=response.data;
        });
    }
 
});
   

</script>
