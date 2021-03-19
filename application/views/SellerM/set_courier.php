<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<title>Set Courier Companies</title>
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
			<div class="content-wrapper">

				<?php $this->load->view('include/page_header'); ?>


				<!-- Content area -->
				<div class="content">
					<div class="panel panel-flat">
						<div class="panel-heading"><h1><strong>Set Courier Companies</strong></h1></div>
						<hr>
						<div class="panel-body">


				<form action="<?= base_url('Seller/updateCourier/'.$seller['id']); ?>" method="post" enctype="multipart/form-data">
					
              
               <fieldset class="scheduler-border">
                <legend class="scheduler-border">Set Courier Companies</legend>
                 <div class="form-group">

                 <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
                <thead>
                  <tr>
				  <th>#</th>
                    <th>Name</th>
					<th>Priority</th>
					<th>Active</th>
                  </tr>
                </thead>
                <tbody>
                
                  <?php if(!empty($fullfilment_drp)): ?>

					<?php $i=0; 
					$totalCount=count($fullfilment_drp);
					foreach($fullfilment_drp as $seller)
					{ $i++; ?>
					 <tr>
					 
                      <td><?=$i;  ?></td>
					  <td><?= $seller['company']; ?></td>
					  <td> 
					  <input type="hidden" name="id[<?=$seller['id'];?>]" value="<?=$seller['id'];?>" >
					  <select name="priority[<?=$seller['id'];?>]" id="<?=$i;?>" class="priority" onChange="Getchekpriority(this.value,<?=$i;?>,<?=$totalCount;?>);">
					  <?php  for($count=1;$count<=$totalCount; $count++){ ?>
					  <?php if($count== $seller['priority']){ ?>
						<option value="<?=$count;?>" selected="selected"><?=$count;?></option>
					 <?php } else { ?>
						<option value="<?=$count;?>"><?=$count;?></option>
					 <?php } }?>
					 
					  </select> </td>
					  <td> 
					  <select name="status[<?=$seller['id'];?>]"  >
					  
					  <?php if($seller['status']==0){ ?>
						<option value="0" selected="selected">Active</option>
						<option value="1" >Inactive</option>
					 <?php } else { ?>
						<option value="0" >Active</option>
						<option value="1" selected="selected">Inactive</option>
					 <?php } ?>
					 
					  </select> 
                      </td>

                    </tr>
					<?php }
                     

                    ?>
                <?php endif; ?>
              </tbody>
            </table>
                </div>
                </fieldset>
              
             <button type="submit" class="btn btn-primary pull-right">Update</button>
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

<script>



$('.priority').on('change', function() {
	//alert(this.value);
	//var nval=$('.priority').find("input[value='"+value+"']").attr('id');
	//console.log(nval);
});
function Getchekpriority(val,id,tcount)
{
	var neid=0;
	var allvalue=[];
	var i;
	 allvalue.push(parseInt(val));
for (i = 1; i <= tcount; i++) {
   var value=parseInt(document.getElementById(i).value);
   if(value==val && i!=id)
   {
	   neid=i;
   }
   else
   {
	   allvalue.push(value);
   }

}

var ii;
var requestid=0;
for (ii = 1; ii <= tcount; ii++) {
	if(allvalue.includes(ii)==false)
	{
		requestid=ii;
		//console.log("false"+ii+"false");
	}
}
document.getElementById(neid).value=requestid;	

// console.log(neid+"//////////"+requestid);
}


</script>