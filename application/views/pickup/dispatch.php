<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<script src='https://code.responsivevoice.org/responsivevoice.js'></script>    
<title><?= lang('lang_Inventory'); ?></title>
<?php $this->load->view('include/file'); ?>


</head>

<body ng-app="fulfill" ng-controller="dispatch"  >

<?php $this->load->view('include/main_navbar'); ?>


<!-- Page container -->
<div class="page-container" >

<!-- Page content -->
<div class="page-content">

<?php $this->load->view('include/main_sidebar'); ?>


<!-- Main content -->
<div class="content-wrapper" >
<!--style="background-color: black;"-->
<?php $this->load->view('include/page_header'); ?>



<!-- Content area -->
<div class="content"  >
<!--style="background-color: red;"-->
   
<?php 
if($this->session->flashdata('msg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

if($this->session->flashdata('something'))
echo '<div class="alert alert-warning">'.$this->session->flashdata('something').": ".$this->session->flashdata('error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
?>


     <!-- Dashboard content -->
          <div class="row" >
            <div class="col-lg-12" >

              <!-- Marketing campaigns -->
              <div class="panel panel-flat">
                <div class="panel-heading">
                 <h1>
                  <strong><?= lang('lang_Dispatch'); ?></strong>
<!--
                  <a  ng-click="exportExcel();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>
                 <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>
-->
               </h1>
                     <div style="display:none">
            <audio id="audio" controls>
            <source src="<?= base_url('assets/apx_tone_alert_7.mp3');?>" type="audio/ogg">
                     </audio>
            <audio id="audioSuccess" controls>
            <source src="<?= base_url('assets/filling-your-inbox.mp3');?>" type="audio/ogg">
                     </audio>      
                  </div>
                </div>
                  <div class="row">
                  <div class="col-lg-12" >
                      
                    
                  <div  ng-if="invalidstring" ><div class="alert alert-danger"><?= lang('lang_list_of_not_packed_Orders'); ?>:{{invalidstring}}</div>
                      <div class="alert alert-warning"> <?= lang('lang_Above_orders_removed_automatically'); ?> !</div>
                  </div> 
                  <div  ng-if="invalidstringpallet" ><div class="alert alert-danger"><?= lang('lang_list_of_not_added_no_of_pallets'); ?>: {{invalidstringpallet}}</div>
                      <div class="alert alert-warning"> <?= lang('lang_Above_orders_removed_automatically'); ?> !</div>
                  </div> 
                  </div>
                 <div class="col-lg-6">
                   
                <div ng-if="awbArray.length>200" class="alert alert-danger"><?= lang('lang_Please_Verify_the_Packing_Limit_Exceed'); ?> ! </div>
                  <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                  <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                </div>      
                  </div>
            
            <!-- href="<?// base_url('Excel_export/shipments');?>" -->
 <!-- href="<?//base_url('Pdf_export/all_report_view');?>" -->
                <!-- Quick stats boxes -->
                <div class="table-responsive " >
                 

                    
                   <form ng-submit="dispatchOrder();" method="post" >
            <div class="col-md-12">
            <div class="form-group">
            <textarea rows="8" placeholder="AWB | 3PL" id="show_awb_no" ng-change="scan_awb();"   ng-model="scan.slip_no" required class="form-control"></textarea>
				
            </div>
            </div>
              <div class="col-md-2">
            <div class="form-group">
          <a type="button"  class="btn btn-warning" style=" margin-left: 2%; margin-right: 3%; "><?= lang('lang_Row_Count'); ?> <span class="badge badge badge-pill badge-success" id="count_val">{{scan.awbArray.length}}</span>	</a>
			
				</div>
                       </div>
         <div class="col-md-3">	
           
         <div class="form-group">
             <select ng-model="type" class="form-control" ng-change="GetCheckvalidbox();">
             <option value="DL" ><?= lang('lang_Last_Mile'); ?></option>
                 <option value="POD" ><?= lang('lang_Deliver'); ?></option>
                  <option value="SFP">Self Pickup</option>
             
             </select>
             </div>
                       </div>
                       
                         <div class="col-md-3" ng-show="BoxInputshow">	

                                                <div class="form-group">
                                                    <textarea type="text" class="form-control" ng-model="comments" placeholder="Enter Comment"></textarea>
                                                </div>
                                            </div>
            <div class="col-md-4">	
           
         <div class="form-group">
              <button type"submit" ng-if="scan.awbArray.length>0"  role="button" class="btn btn-primary form-control" ><?= lang('lang_Dispatch'); ?></button>	
             <button type"submit" ng-if="scan.awbArray==null"  role="button" class="btn btn-info form-control" disabled ><?= lang('lang_Dispatch'); ?> </button>	
           
				 		
            </div>	 </div>	
           
            </form>

                  
                 
                
                  
                </div>
               
                <!-- /quick stats boxes -->
              </div>
            </div>
                
          </div>
          
    </div> 
         
    </div>            
          <!-- /dashboard content -->

<!-- /basic responsive table -->

 
</div>
<!-- /content area -->
    </div>
    
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
   
    
      
        <h5 class="modal-title" id="exampleModalLabel">Add Pallet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form name="myform" novalidate ng-submit="myForm.$valid && dispatchOrder()" enctype="multipart/form-data" >
      <div class="modal-body">
      
    <table class="table table-bordered table-hover" style="width: 100%;">
                    <!-- width="170px;" height="200px;" -->
                    <tbody >
                    
                      <tr ng-repeat="data1 in InputAray" style="width: 80%;"><td><strong>Pallet No.({{data1}}):</strong></td><td> <input type="text"  ng-model="scan.pallet[$index]"  class="form-control" placeholder="Enter Pallet No."></td>
                          </tr>
                          </tbody>
                          </table>
     
      
    
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" ng-click="dispatchOrder();" >Update</button>
      </div>
          </form>          
    </div>
  </div>
</div>
 <?php $this->load->view('include/footer'); ?>   

<script>
    $("#show_awb_no").bind('keyup click mouseleave change', function(e) {
	
	validationCheck();
});
function validationCheck()
	{
	
   
var valid_check=0;		
var text = $("#show_awb_no").val();   
var lines = text.split(/\r|\r\n|\n/);
var count = lines.length;
console.log(count); // Outputs 4
	$("#count_val").html(count);
	if($("#user_name").val()=='')
		valid_check=1;
		else if(valid_check==0) 
		valid_check=0;	
	
if($("#inv_type").val()=='' )
		valid_check=1;
	else if(valid_check==0) 
		valid_check=0;	
if(count>5000)
	{
	document.getElementById('message').innerHTML='<div class="alert alert-danger" id="success-alert">Error! shipment limit Exceed Please Add less then 5001</div>'; 	
	valid_check=1;
		
		$("#count_val").removeClass("badge badge-pill badge-success");
		$("#count_val").addClass("badge badge-pill badge-danger");
	}
	
	else
		{
			
		if(valid_check==0) 
		valid_check=0;	
		document.getElementById('message').innerHTML="";		
		
		$("#count_val").removeClass("badge badge-pill badge-danger");
		$("#count_val").addClass("badge badge-pill badge-success");
		}
	console.log('valid check'+valid_check);
   if(valid_check==0)
	   {
		   $("#btnSubmit").show();
		$("#btnSubmitDS").hide();	
		 
	   }
	 
	else
		{
			
			  $("#btnSubmit").hide();
		$("#btnSubmitDS").show();  
		}
		
	
		
	}
    

</script>
<!-- /page container -->

</body>
</html>
