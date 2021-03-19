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
              <h1><strong>Sellers Table</strong></h1>

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
              <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
                <thead>
                  <tr>
                    <th>Sr.No.</th>
					<th>Secret Key</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Account No#</th>
                    <th>Location</th>
                    <th>Phone #1</th>
                    <th>Invoice Type</th>
                    <th class="text-center" ><i class="icon-database-edit2"></i></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $sr=1;?>
                  <?php if(!empty($sellers)): ?>
                    <?php foreach($sellers as $seller): ?>
                      <tr>
                      <td> <?= $sr;?></td>
					            <td> <?= $seller->secret_key; ?></td>    
                      <td><a href="<?= site_url('Seller/report_view/'.$seller->id);?>"><?= $seller->name; ?></a></td>
                      
                      
                       <td><?= $seller->company; ?></td>
                      <td><?= $seller->email; ?></td>
                      <td><?= $seller->uniqueid; ?></td>
                      <td><?= $seller->address; ?></td>
                      <td><?= $seller->phone; ?></td>
                      <td><?= $seller->invoice_type; ?></td>
                      <?php $sr++;?>
                      <td class="text-center">
                        <ul class="icons-list">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="icon-menu9"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">  
                              <li><a href="<?= site_url('Seller/edit_view/'.$seller->id);?>"><i class="icon-pencil7"></i> Edit </a></li>
                               <li><a href="<?= site_url('Seller/set_courier/'.$seller->id);?>"><i class="icon-pencil7"></i> Set Courier Companies</a></li>
                               <li><a href="<?= site_url('Seller/storage_charges/'.$seller->id);?>"><i class="icon-pencil7"></i> Set Storages Charges</a></li>
                          
                            </ul>
                          </li>
                        </ul>
                      </td>
                    </tr>

                  <?php endforeach; ?>
                <?php endif; ?>
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
<script>
$(document).ready(function() {
    var table = $('#example').DataTable({});
    
});
  
 
</script>

<!-- /page container -->

</body>
</html>
