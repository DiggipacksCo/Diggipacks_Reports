<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Add New Template</title>
        <?php $this->load->view('include/file'); ?>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

        <script src="<?= base_url(); ?>assets/js/angular/user.app.js"></script>


    </head>

    <body ng-app="usersApp" ng-controller="PickerSettingsCtlr"  >

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
                            <div class="panel-heading"><h1><strong>Add New Template</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>

                                <form action="<?= base_url('Users/addnewaccessTemplate'); ?>" name="adduser" method="post">
                                   
                                    <div class="form-group">
                                        <label for="d_id"><strong>Type:</strong></label>
                                        <select name="d_id" id="dcat_id" class="form-control"> <option value="">Select Type</option>
                                            <?php
                                            foreach ($typeArr as $d_data) {
                                                if ($d_data['id'] == $editdata['d_id']) {
                                                    echo '<option value="' . $d_data['id'] . '" selected>' . $d_data['designation_name'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $d_data['id'] . '">' . $d_data['designation_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="privilage_array"><strong>Category:</strong>
                                          <input type="checkbox" id="checkbox" > Select All
                                        </label>
                                        <select name="privilage_array[]"  ng-model="filterData.privilage_array" id="main_item" multiple="multiple"  class="form-control js-example-basic-multiple" data-width="100%" ng-change="GetSubCatDatashow();"> 
                                            <?php
                                            $editCatArr = explode(',', $editdata['privilage_array']);

                                            foreach ($CatData as $cat_data) {
                                                if (in_array($cat_data['id'], $editCatArr)) {
                                                    echo '<option value="' . $cat_data['id'] . '" selected>' . $cat_data['privilege_name'] . '</option>';
                                                } else {
                                                    echo '<option value="' . $cat_data['id'] . '">' . $cat_data['privilege_name'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="privilage_array_sub"><strong>Sub Category:</strong>
                                           <input type="checkbox" id="checkbox2" > Select All
                                        </label>
                                        <select name="privilage_array_sub[]" multiple="multiple" id="main_item_sub"  class="form-control js-example-basic-multiple" data-width="100%"> 

                                            <option ng-repeat="data in sub_catArr" value="{{data.id}}">{{data.privilege_name}}</option>

                                            }
                                            ?>
                                        </select>
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
 <script>

            $(".js-example-basic-multiple").select2();
            $(".js-example-basic").select2();
            $("#checkbox").click(function () {
                if ($("#checkbox").is(':checked')) {
                    $("#main_item > option").prop("selected", "selected");
                    $("#main_item").trigger("change");
                } else {
                    $("#main_item > option").removeAttr("selected");
                    $("#main_item").trigger("change");
                }
            });
            
            $("#checkbox2").click(function () {
                if ($("#checkbox").is(':checked')) {
                    $("#main_item_sub > option").prop("selected", "selected");
                    $("#main_item_sub").trigger("change");
                } else {
                    $("#main_item_sub > option").removeAttr("selected");
                    $("#main_item_sub").trigger("change");
                }
            });
            </script>
<script>
    
    
$("#dcat_id").select2({
    placeholder: "Select Type",
    allowClear: true
});
$("#main_catid").select2({
    placeholder: "Select Category",
    allowClear: true
});

$("#sub_catid").select2({
    placeholder: "Select Sub Category",
    allowClear: true
});
</script>

