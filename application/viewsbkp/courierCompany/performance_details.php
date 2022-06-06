<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
  <title>Inventory</title>
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
      <div class="content-wrapper" >
        <!--style="background-color: black;"-->
        <?php $this->load->view('include/page_header'); ?>



        <!-- Content area -->
        <div class="content" >
          <!--style="background-color: red;"-->
       <?php 
if($this->session->flashdata('msg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'?> 

          <!-- Basic responsive table -->
          <div class="panel panel-flat" >
            <!--style="padding-bottom:220px;background-color: lightgray;"-->
            <div class="panel-heading">
              <!-- <h5 class="panel-title">Basic responsive table</h5> -->
              <h1><strong>Performance Details</strong></h1>

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

              <!-- <input type="text" id="search"  placeholder="Search .." class="form-control">
 -->
            

            <div class="table-responsive" style="padding-bottom:20px;" >
              <!--style="background-color: green;"-->
              <table class="table table-striped table-hover table-bordered bg-*" >
                <thead>
                  <tr>
                    <th>Sr. No.</th>
                     <th>Company</th>
                    <th>Awb No.</th>
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
                  <?php $sr=1;?>
                  <?php if(!empty($DetailsArr)){
					
                     foreach($DetailsArr as $rows){ 
                    
                   
					
                     echo'<tr>
                      <td>'.$sr.'</td>
					   <td>'.$rows['company'].' </td>
                      <td>'.$rows['slip_no'].' </td>
					  <td>'.$rows['frwd_company_awb'].' </td>
					  <td>'.$rows['mode'].' </td>
					  <td>'.$rows['sender_name'].' </td>
					    <td>'.$rows['sender_phone'].' </td>
					  <td>'.$rows['reciever_name'].' </td>
					  <td>'.$rows['reciever_phone'].' </td>
					  <td>'.getallmaincatstatus($rows['delivered'],'main_status').' </td>
 
					  
                    
                     
                    </tr>';
					  $sr++;
					 }
					 
					
					}

                 ?>
              </tbody>
            </table>
            
          </div>
           <!--  <div>
              <center>
               <?php //echo $links; ?> 
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


<!-- <script>
 var $rows = $('tbody tr');
 $('#search').keyup(function() {
  var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();   

  $rows.show().filter(function() {
    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
    return !~text.indexOf(val);
  }).hide();
});
</script> -->


</div>

 


<!-- /page container -->

</body>
</html>
