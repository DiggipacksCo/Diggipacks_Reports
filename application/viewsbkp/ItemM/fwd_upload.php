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
            <div class="content-wrapper">

                <?php $this->load->view('include/page_header'); ?>



                <!-- Content area -->
                <div class="content">
            
                    <div class="panel panel-flat">
                     <hr>

                        <div class="panel-body">
               
                <br> 
                <table class="table table-striped table-bordered table-hover">
                <tbody>
                    
                    <tr>
                    
                        <td>(1) Awb <span style="color:#F00"><strong>*</strong></span></td>
                        <td>(2) Forward  <span style="color:#F00"><strong>*</strong></span></td>
                        <td>(3) Company <span style="color:#F00"><strong>*</strong></span></td>
                     
                    </tr>
                    
                    
                </tbody>
                </table>
                <br>
                <form class="stdform" method="post" action="<?= base_url('Excel_export/importfwd');?>" id="AddnventoryID" name="AddnventoryID" enctype="multipart/form-data" onsubmit="document.getElementById('Newaddfrm').disabled=true; processFormData();">
                    
                    
                <label><strong class="alert-danger">Import Excel File</strong></label>
                <span class="field">
                    <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                    <!-- <span id="weight" class="alert"></span> -->
                </span><br> 
                <button type="submit" id="Newaddfrm"  class="btn btn-success pull-left">Update</button> 
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

 <link href="<?= base_url(); ?>assets/colorpicker/jquery.colorpicker.bygiro.min.css" rel="stylesheet">
        <script src="<?= base_url(); ?>assets/colorpicker/jquery.colorpicker.bygiro.min.js"></script>

        <script>
                                            $('.myColorPicker').colorPickerByGiro({
                                                preview: '.myColorPicker-preview',
                                                showPicker: true,
                                                format: 'hex',
                                                sliderGap: 6,

                                                cursorGap: 6,
                                                text: {

                                                    close: 'Close',

                                                    none: 'None'

                                                }




                                            });
        </script>
        
        <script>
  processFormData = function(event) {
  //alert("ssssss");
   // For this example, don't actually submit the form
   event.preventDefault();

    
    var Elem = event.target;
       if (Elem.nodeName=='td'){
          $("#AddnventoryID").submit()
       }
       
       
  
  
   

  };
  

    </script>
