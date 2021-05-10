<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_All_Users');?></title>
        <?php $this->load->view('include/file'); ?>
    </head>
<style>
.subject-info-box-1,
.subject-info-box-2 {
    float: left;
    width: 45%;
    
    select[multiple],
select[size] {
 

        height: 400px !important;
        padding: 0;

        option {
            padding: 4px 10px 4px 10px;
        }

        option:hover {
            background: #EEEEEE;
        }
    }
}

.subject-info-arrows {
    float: left;
    width: 10%;

    input {
        width: 70%;
        margin-bottom: 5px;
    }
}
</style>
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
                        if ($this->session->flashdata('succmsg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('succmsg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        if ($this->session->flashdata('errormess'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('errormess') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Import Cities from Master</strong> 
                                    
                            </div>
                            <div class="panel-body" > 
                            <form action="<?= base_url('Country/addmaster');?>" method="post" enctype="multipart/form-data" >
                      
                                <div class="subject-info-box-1">
                                    <select multiple="multiple" id='lstBox1'  class="form-control">
                                    <?php if (!empty($ListArr)): ?>
                                                <?php foreach ($ListArr as $rows):
                                                    ?>
<option value="<?= $rows['id']; ?>"> <?= $rows['city']; ?> </option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                    </select>
                                    </div>
                                    <div class="subject-info-arrows text-center">
                                    <input type='button' style="margin-top:5px;" id='btnAllRight' value='>>' class="btn btn-info" /><br />
                                    <input type='button' style="margin-top:5px;"id='btnRight' value='>' class="btn btn-info" /><br />
                                    <input type='button' style="margin-top:5px;" id='btnLeft' value='<' class="btn btn-info" /><br />
                                    <input type='button' style="margin-top:5px;" id='btnAllLeft' value='<<' class="btn btn-info" />
                                    </div>
                                    <div class="subject-info-box-2">
                                    <select multiple="multiple" name="master_id[]" id='lstBox2' class="form-control">
                                    <?php if (!empty($pre)): ?>
                                                <?php foreach ($pre as $rows):
                                                    ?>
<option value="<?= $rows['id']; ?>" > <?= $rows['city']; ?> </option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>  
                                    </select>
                                    </div>
                                    <div class="clearfix"></div>
                                    <button class="btn btn-warning" type="button"  id="selectAll">Confirm</button>
                                    <button class="btn btn-info" disabled type="submit" id="subButton" value="sumbit" name="Add in Master">Add in Master</button>
                                    
                                </div>
                                </form>
                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table -->
                        <?php $this->load->view('include/footer'); ?>
                        <Script>
        $(document).ready(function(){
        $('#selectAll').click(function(){
           
            $('#lstBox2 option').prop('selected', true);
            $('#subButton').prop('disabled', false);
        });
        });


                        (function () {

    



  $("#btnRight").click(function (e) {
    var selectedOpts = $("#lstBox1 option:selected");
    if (selectedOpts.length == 0) {
      alert("Nothing to move.");
      e.preventDefault();
    }

    $("#lstBox2").append($(selectedOpts).clone());
    $(selectedOpts).remove();
    $('#subButton').prop('disabled', true);
    e.preventDefault();
  });

  $("#btnAllRight").click(function (e) {
   var isconfirm= confirm("Do you really want to add cities! its huge...");
   if(isconfirm)
{


    var selectedOpts = $("#lstBox1 option");
    if (selectedOpts.length == 0) {
      alert("Nothing to move.");
      e.preventDefault();
    }

    $("#lstBox2").append($(selectedOpts).clone());
    $(selectedOpts).remove();
    $('#subButton').prop('disabled', true);
    e.preventDefault();
}
  });

  $("#btnLeft").click(function (e) {
    var selectedOpts = $("#lstBox2 option:selected");
    if (selectedOpts.length == 0) {
      alert("Nothing to move.");
      e.preventDefault();
    }

    $("#lstBox1").append($(selectedOpts).clone());
    $(selectedOpts).remove();
    $('#subButton').prop('disabled', true);
    e.preventDefault();
  });

  $("#btnAllLeft").click(function (e) {
    var selectedOpts = $("#lstBox2 option");
    if (selectedOpts.length == 0) {
      alert("Nothing to move.");
      e.preventDefault();
    }

    $("#lstBox1").append($(selectedOpts).clone());
    $(selectedOpts).remove();
    $('#subButton').prop('disabled', true);
    e.preventDefault();
  });
})(jQuery);
                        </Script>
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
