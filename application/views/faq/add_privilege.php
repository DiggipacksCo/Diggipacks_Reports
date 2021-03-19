<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
	<title>Update User Privilege</title>
	<?php $this->load->view('include/file'); ?>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</head>

<body>

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
						<div class="panel-heading"><h1><strong>Add Privilege</strong></h1></div>
						<hr>
						<div class="panel-body">
               
                    <?php
							echo'<div id="privilage_table">';
              // if(!empty($privikegeData))
			 //  {
               
                
                
              echo'<table class="table table-striped table-bordered table-hover">';
                
               echo'<thead>
                <tr>
                <td>Sr.No.</td>
                <td>Privilege Name</td>
                <td>Action</td>
                </tr>
                </thead>
                <tbody>
                <div class="panel-body">
                <div class="col-md-6">
                <div class="content-group">
                <div class="row">
                <div class="col-sm-6">';
				//print_r($privikegeData[0]); die;
                foreach($privikegeData as $key=>$val)
                {
					  $sr_no=$key+1;
					 // echo checkPrivilageExitsForCustomer($userid,$privikegeData[$key]['id']);
                if(checkPrivilageExitsForCustomer($userid,$privikegeData[$key]['id'])=='Y')
                {
					
               
			   
                echo'<tr>
                <td>'.$sr_no.'</td>
                <td>'.$privikegeData[$key]['privilege_name'].'</td>
                <td align="center">';
               echo '<div class="checkbox checkbox-switch">
                <label>
                <input type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-toggle="toggle" data-onstyle="success" data-offstyle="warning" data-on-color="default" data-off-color="danger" checked name="onoff_check_box_'.$privikegeData[$key]['id'].'" id="onoff_check_box_'.$privikegeData[$key]['id'].'"  onchange="setUserPrivilageOnOff('.$privikegeData[$key]['id'].');" value="'.$privikegeData[$key]['id'].'" >
                </label>
                
                <span id="alert_customer"></span>
                </div>';
               echo '</td></tr>';
                }
                else
                {
					
                echo'<tr><td>'.$sr_no.'</td><td>'.$privikegeData[$key]['privilege_name'].'</td><td  align="center">
                
                <div class="checkbox checkbox-switch">
                <label>
                <input type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-toggle="toggle" data-onstyle="success" data-offstyle="warning" data-on-color="default" data-off-color="danger" name="onoff_check_box_'.$privikegeData[$key]['id'].'" id="onoff_check_box_'.$privikegeData[$key]['id'].'"  onchange="setUserPrivilageOnOff('.$privikegeData[$key]['id'].');" value="'.$privikegeData[$key]['id'].'" >
                </label>
                </div>
                </td></tr>';
                }
				}
                 echo'</div></div></div></div> </div></tbody></table>';
                
               
             
              echo'</div>';?> 
               </div></div>
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

function setUserPrivilageOnOff(select_id)
{
	
	//alert(select_id);
		var onoff_true_false=document.getElementById('onoff_check_box_'+select_id).checked;
		var privilage_id=select_id;
		
$.post("<?=base_url();?>Users/setCustomerPrivilage?privilage_id="+privilage_id+"&customer_id="+<?=$userid?>+"&onoff_true_false="+onoff_true_false, function(data, status){
	//alert(");
			 
    });
		
	
}


//

</script>
