<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    </head>
    <style>
        .bigdrop {
            width: 100% !important;
        }
        .select2-container {
            min-width: 400px;
        }
        .select2-results { background-color: #f5f5f5; }

        .select2-selection__choice{
            color:#000!important;
        }
        .select2-container--default .select2-results__option[aria-selected=true]{
            background:#29aba3!important;
        }
        .select2-results__option {
            padding-right: 20px;
            vertical-align: middle;
        }
        .select2-results__option:before {
            content: "";
            display: inline-block;
            position: relative;
            height: 20px;
            width: 20px;
            border: 2px solid #e9e9e9;
            border-radius: 4px;
            background-color: #fff;
            margin-right: 20px;
            vertical-align: middle;
        }
        .select2-results__option[aria-selected=true]:before {
            font-family:fontAwesome;
            content: "\f00c";
            color: #fff;
            background-color: #f77750;
            border: 0;
            display: inline-block;
            padding-left: 3px;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #fff;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #eaeaeb;
            color: #272727;
        }
        .select2-container--default .select2-selection--multiple {
            margin-bottom: 10px;
        }
        .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
            border-radius: 4px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #f77750;
            border-width: 2px;
        }
        .select2-container--default .select2-selection--multiple {
            border-width: 2px;
        }
        .select2-container--open .select2-dropdown--below {

            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);

        }
        .select2-selection .select2-selection--multiple:after {
            content: 'hhghgh';
        }
        /* select with icons badges single*/
        .select-icon .select2-selection__placeholder .badge {
            display: none;
        }
        .select-icon .placeholder {
            display: none;
        }
        .select-icon .select2-results__option:before,
        .select-icon .select2-results__option[aria-selected=true]:before {
            display: none !important;
            /* content: "" !important; */
        }
        .select-icon  .select2-search--dropdown {
            display: none;
        }
        .container {
            display: block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 15px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .container .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
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
                <div class="content-wrapper">
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h1><strong><?= lang('lang_Add_Zone'); ?></strong></h1>
                            </div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                } ?>
                                    <?php if (empty($EditData)) { ?>
                                    <form action="<?= base_url('Zone/add'); ?>" method="post"  name="add_customer" enctype="multipart/form-data">
                                        <?php } else { ?>
                                        <form action="<?= base_url('Zone/editZoneUpdate/' . $id); ?>" method="post"  name="add_customer" enctype="multipart/form-data">
<?php } ?>

                                        <fieldset class="scheduler-border">
                                            <legend class="scheduler-border"><?= lang('lang_Zone_Details'); ?></legend>


                                            <div class="form-group">
                                                <label><?= lang('lang_Zone_Name'); ?></label>
                                                <input type="text" class="form-control" id="company" name="name" value="<?php if (!empty($EditData)) echo $EditData[0]->name;
else echo set_value('name'); ?>" required/>
                                            </div>

                                            <div class="form-group">
                                                <label><?= lang('lang_Courier_Company'); ?></label>
                                                <span id="c_id"></span>
                                                <select name="c_id" id="" required class="js-select4 bigdrop"  required > 
                                                <option value="0" <?php if ($cmpy->id == 0) {
                                                                echo "selected=selected";} ?>>Last Mile </option>
                                                    <?php
                                                    if (!empty($company)) {
                                                        foreach ($company as $cmpy) {
                                                            ?>
                                                            <option value="<?php echo $cmpy->id; ?>" <?php if ($cmpy->id == $EditData[0]->cc_id) {
                                                                echo "selected=selected";
                                                            } ?>><?php echo $cmpy->company ?></option>
                                                <?php }
                                            } ?>
                                                </select>
                                            </div>

<?php
$alreayCityIds = json_decode($EditData[0]->city_id);
// print_r($alreayCityIds);
?>
                                            <div class="form-group ">
                                                <label>City</label>
                                                <span id="city"></span>
                                                <select name="city_id[]" class="selectpicker"   data-show-subtext="true" data-live-search="true"   data-width="100%" multiple> 

                                                <?php
                                                if (!empty($city_drp)) {
                                                    foreach ($city_drp as $cry) {
                                                        ?>
                                                            <option value="<?php echo $cry->id; ?>" <?php if (in_array($cry->id, $alreayCityIds)) {
                                                            echo "selected=selected";
                                                        } ?>><?php echo $cry->city ?></option>
                                                    <?php }
                                                    } ?>
                                                  </select>
                                            </div>

                                               <div class="form-group ">
                                                <label>Capcity</label>
                                                <span id="capacity"></span>
                                               <input type="number" name="capacity" class="form-control" min="0"  onChange="updateTextInput(this.value);" value="<?= $EditData[0]->capacity; ?>" required>
                                              </div>  

                                              <div class="form-group ">
                                                <label>Price</label>
                                                <span id="price"></span>
                                               <input type="number" name="price" class="form-control" value="<?= $EditData[0]->price; ?>" required>
                                              </div>


                                            <button type="submit" class="btn btn-primary" name="submit" value="submit">Add New Zone</button>
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
<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }
    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>

<script type="text/javascript">
    function updateTextInput(dta)
    {

        $('#capacity').html(':<strong>' + dta + '</strong>');
    }

    $(".js-select4").select2({
        closeOnSelect: false,
        placeholder: "Select",
        theme: "bootstrap",
        allowHtml: true,
        allowClear: true,
        tags: true // �?оздает новые опции на лету
    });

    $(document).ready(function () {
        $("#e1").select2({dropdownCssClass: 'bigdrop'});
    });
</script>  

