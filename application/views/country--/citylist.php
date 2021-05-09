<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_All_Users'); ?></title>
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
                                <h1><strong><?= lang('lang_City'); ?> <?= lang('lang_List'); ?></strong> </h1>
                            </div>
<!--                            <form id="search_form" name="search_form" method="post" action="<?php //echo base_url('Country/cityList');   ?>">
                                <input type="hidden" id="search_params" name="search_params" value="<?php //echo $search_val;   ?>" />
                            </form>-->
                            <div class="table-responsive">
                                <div class="loader logloder" id="loadershow" style="display:none;"></div>
                                <table class="table datatable-show-all table-bordered table-hover datatable-highlight" id="city_list_paging">
                                    <thead>
                                        <tr> 

                                            <th class="head1" colspan="6">
                                                <input type="text" id="search_val" name="search_val" value="<?php echo isset($_REQUEST['filter_by']) ? $_REQUEST['filter_by'] : ''; ?>" placeholder="Searching By City Name" class="form-control" onfocus="this.value = ''" />
                                            </th>

                                            <th class="head1" colspan="6">
                                                <input type="button" name="Search" class="btn btn-info" value="Search" onclick="return Valid_search();" /> 
                                                <input type="button" name="clear_filter" class="btn btn-success" value="Clear Filter" onclick="clearFilter(); return;" /> 
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="head0">Sr.No.</th>
                                            <th class="head1">City</th>
                                            <?php if (GetCourierCompanyStausActive('SMSA') == 'Y') { ?>
                                                <th class="head1">SMSA </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('UBREEM') == 'Y') { ?>
                                                <th class="head1">UBREEM</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('ARAMEX') == 'Y') { ?>
                                                <th class="head1">ARAMEX </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('DOTS') == 'Y') { ?>
                                                <th class="head1">DOTS </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('IMILE') == 'Y') { ?>
                                                <th class="head1">IMILE </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('NAQEL') == 'Y') { ?>
                                                <th class="head1">NAQEL </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Esnad') == 'Y') { ?>
                                                <th class="head1">ESNAD</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Esnad') == 'Y') { ?>
                                                <th class="head1">ESNAD CITY CODE</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('SAMANA') == 'Y') { ?>
                                                <th class="head1">SAMANA</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AGILITY') == 'Y') { ?>
                                                <th class="head1">AGILITY</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('DESCEN') == 'Y') { ?>
                                                <th class="head1">DESCEN</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AYMAKAN') == 'Y') { ?>
                                                <th class="head1">AYMAKAN</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('ZAJIL') == 'Y') { ?>
                                                <th class="head1">ZAJIL</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('CLEX') == 'Y') { ?>
                                                <th class="head1">CLEX</th>
                                                <?php if (GetCourierCompanyStausActive('RABEL') == 'Y') { ?>
                                                    <th class="head1">RABEL</th>
                                                <?php } ?>
                                                <?php if (GetCourierCompanyStausActive('SPEEDZI') == 'Y') { ?>
                                                    <th class="head1">SPEEDZI</th>
                                                <?php } ?>
                                                <?php if (GetCourierCompanyStausActive('Barqfleet') == 'Y') { ?>
                                                    <th class="head1">BARQ</th>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Labaih') == 'Y') { ?>
                                                <th class="head1">LABAIH</th>
                                            <?php } ?>

                                            <?php if (GetCourierCompanyStausActive('MAKHDOOM') == 'Y') { ?>
                                                <th class="head1">MAKHDOOM </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Saee') == 'Y') { ?>
                                                <th class="head1">SAEE</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('AJEEK') == 'Y') { ?>
                                                <th class="head1">AJEEK</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('EMDAD') == 'Y') { ?>
                                                <th class="head1">EMDAD </th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('SHIPSY') == 'Y') { ?>
                                                <th class="head1">SHIPSY</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Shipadelivery') == 'Y') { ?>
                                                <th class="head1">SHIPSA</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('TAMEX') == 'Y') { ?>
                                                <th class="head1">TAMEX</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('ZID CITY') == 'Y') { ?>
                                                <th class="head1">ZID CITY</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('SALLA CITY') == 'Y') { ?>
                                                <th class="head1">SALLA CITY</th>
                                            <?php } ?>
                                            <?php if (GetCourierCompanyStausActive('Alamalkon') == 'Y') { ?>
                                                <th class="head1">Alamalkon </th>
                                            <?php } ?>
                                                   <?php if (GetCourierCompanyStausActive('Saudi Post') == 'Y') { ?>
                                                <th class="head1">Saudi Post Id </th>
                                            <?php } ?>
                                            <th class="head1">LAT</th>
                                            <th class="head1">LNG</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        //print "<pre>"; print_r($result);die;
                                        if (is_array($result) && count($result) > 0) {
                                            $cnt = 1;
                                            foreach ($result as $listdata) {
                                                ?>    
                                                <tr>
                                                    <td><?php echo $cnt++; ?></td>
                                                    <td><?php echo $listdata['city']; ?></td>
                                                     <?php if(GetCourierCompanyStausActive('SMSA')=='Y'){ ?>
                                                    <td>
                                                        <input type="text" name="samsa_city_name" id="samsa_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['samsa_city']; ?>" class="form-control">
                                                        <br>          
                                                        <a class="remove-photo btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'samsa_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('UBREEM') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="ubreem_city_name" id="ubreem_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['ubreem_city']; ?>" class="form-control">
                                                        <br>          
                                                        <a class="remove-photo btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'ubreem_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                      <?php } ?>
                                                       <?php if (GetCourierCompanyStausActive('ARAMEX') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="aramex_city_name" id="aramex_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['aramex_city']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'aramex_city');" value="<?php echo $listdata['id']; ?>">Update</a>  
                                                    </td>
                                                      <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('DOTS') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="dots_city_name" id="dots_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['dots_city']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'dots_city');" value="<?php echo $listdata['id']; ?>">Update</a>  
                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('IMILE') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="imile_city_name" id="imile_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['imile_city']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'imile_city');" value="<?php echo $listdata['id']; ?>">Update</a>  
                                                    </td>
                                                      <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('NAQEL') == 'Y') { ?>
                                                    <td>
                                                        <input type="text" name="naqel_city_name" id="naqel_city_code<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['naqel_city_code']; ?>" class="form-control">
                                                        <br>    
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'naqel_city_code');" value="<?php echo $listdata['id']; ?>">Update</a>  
                                                    </td>
                                                      <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Esnad') == 'Y') { ?>
                                                    <td><input type="text" name="esnad_city_name" id="esnad_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['esnad_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'esnad_city');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td> 
                                                    <td><input type="text" name="esnad_city_code" id="esnad_city_code<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['esnad_city_code']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'esnad_city_code');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td> <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('SAMANA') == 'Y') { ?>
                                                    <td><input type="text" name="samana_city" id="samana_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['samana_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'samana_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                    <?php } ?>
                                                       <?php if (GetCourierCompanyStausActive('AGILITY') == 'Y') { ?>
                                                    <td><input type="text" name="agility_city" id="agility_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['agility_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'agility_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('DESCEN') == 'Y') { ?>
                                                    <td><input type="text" name="descen" id="descen<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['descen']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'descen');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('AYMAKAN') == 'Y') { ?>
                                                    <td><input type="text" name="aymakan" id="aymakan<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['aymakan']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'aymakan');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                      <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('ZAJIL') == 'Y') { ?>
                                                    <td><input type="text" name="zajil" id="zajil<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['zajil']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'zajil');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('CLEX') == 'Y') { ?>
                                                    <td><input type="text" name="clex" id="clex<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['clex']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'clex');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                      <?php } ?>
                                                       <?php if (GetCourierCompanyStausActive('RABEL') == 'Y') { ?>
                                                    <td><input type="text" name="rabel_city" id="rabel_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['rabel_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'rabel_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                      <?php } ?>
                                                      <?php if (GetCourierCompanyStausActive('SPEEDZI') == 'Y') { ?>
                                                    <td><input type="text" name="speedzi_city" id="speedzi_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['speedzi_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'speedzi_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Barqfleet') == 'Y') { ?>
                                                    <td><input type="text" name="barq_city" id="barq_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['barq_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'barq_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Labaih') == 'Y') { ?>

                                                    <td><input type="text" name="labaih" id="labaih<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['labaih']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'labaih');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('MAKHDOOM') == 'Y') { ?>
                                                    <td><input type="text" name="makhdoom_city_name" id="makhdoom<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['makhdoom']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'makhdoom');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('Saee') == 'Y') { ?>
                                                    <td><input type="text" name="saee_city_name" id="saee_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['saee_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'saee_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('AJEEK') == 'Y') { ?>
                                                    <td><input type="text" name="ajeek_city_name" id="ajeek_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['ajeek_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'ajeek_city');" value="<?php echo $listdata['id']; ?>">Update</a>
                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('EMDAD') == 'Y') { ?>
                                                    <td><input type="text" name="emdad_city" id="emdad_city<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['emdad_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'emdad_city');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                    <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('SHIPSY') == 'Y') { ?>
                                                    <td><input type="text" name="shipsy_city" id="shipsy_city_name<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['shipsy_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'shipsy_city');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Shipadelivery') == 'Y') { ?>
                                                    <td><input type="text" name="shipsa_city" id="shipsa_city_name<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['shipsa_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'shipsa_city');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                     <?php } ?>
                                                    <?php if (GetCourierCompanyStausActive('TAMEX') == 'Y') { ?>
                                                    <td><input type="text" name="tamex_city" id="tamex_city_name<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['tamex_city']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'tamex_city');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('ZID CITY') == 'Y') { ?>


                                                    <td><input type="text" name="zid" id="zid<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['zid']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'zid');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                     <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('SALLA CITY') == 'Y') { ?>

                                                    <td><input type="text" name="sala" id="sala<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['sala']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'sala');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                     <?php } ?>

                                                    <?php if (GetCourierCompanyStausActive('Alamalkon') == 'Y') { ?>
                                                    <td><input type="text" name="alamalkon_city" id="alamalkon_city_name<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['alamalkon']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'alamalkon');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                      <?php } ?>
                                                     <?php if (GetCourierCompanyStausActive('Saudi Post') == 'Y') { ?>
                                                    <td><input type="text" name="saudipost_id" id="saudipost_id<?php echo $listdata['id']; ?>" placeholder="Saudi Post Id" value="<?php echo $listdata['saudipost_id']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'saudipost_id');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                      <?php } ?>
                                                    
                                                    
                                                    <td><input type="text" name="latitute" id="latitute<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['latitute']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'latitute');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                    <td><input type="text" name="longitute" id="longitute<?php echo $listdata['id']; ?>" placeholder="Add city" value="<?php echo $listdata['longitute']; ?>" class="form-control">
                                                        <br>
                                                        <a class="btn btn-info" style="" onclick="updateCityListData('<?php echo $listdata['id']; ?>', 'longitute');" value="<?php echo $listdata['id']; ?>">Update</a>

                                                    </td>
                                                </tr>

                                                <?php
                                            } //endforeach
                                        } else {
                                            ?>
                                            <tr><td colspan="27" class="text-center">No Data Found</td></tr>
                                        <?php } ?>              
                                    </tbody>

                                </table>
                            </div>  
                            <?php if ($totalpages > $startcounter) { ?>
                                <div class="mt-2 mb-4">
                                    <button  class="btn btn-danger" id="load_more_btn" onclick="loadMoreCityData(); return;">Load More</button>
                                </div>
                            <?php } ?>

                        </div>

                        <!-- /basic responsive table -->
                        <input type="hidden" name="total_pages" id="total_pages" value="<?php echo $totalpages ?>" />
                        <input type="hidden" name="perPageResult" id="perPageResult" value="<?php echo $per_page_records ?>" />
                        <input type="hidden" name="startcounter" id="startcounter" value="1" />
                        <input type="hidden" name="filter_city" id="filter_city" value="" />
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area --> 

                </div>
                <!-- /main content --> 

            </div>
            <!-- /page content --> 

        </div>


        <!-- /page container -->
        <script type="text/javascript">
            function loadMoreCityData() {
                var startcounter = parseInt($("#startcounter").val());
                var perPageResult = parseInt($("#perPageResult").val());
                var total_pages = parseInt($("#total_pages").val());
                var search_val = $("#search_val").val();
                if (startcounter > 0) {
                    start = perPageResult * startcounter;
                }

                $.ajax({
                    url: '<?php echo base_url('Country/filter_city'); ?>',
                    data: {offset: start, limit: perPageResult, filter_by: search_val},
                    error: function () { },
                    dataType: 'html',
                    type: 'POST',
                    beforeSend: function () {
                        $("#loadershow").show();
                    },
                    complete: function () {
                        $("#loadershow").hide();
                    },
                    success: function (data) {
                        $("#startcounter").val(startcounter + 1);
                        $('#city_list_paging > tbody:last-child').append(data);
                        if (startcounter >= (total_pages - 1)) {
                            $("#load_more_btn").hide();
                        }
                    }

                });
            }

            function updateCityListData(select_id, type) {
                var columnVal = $("#" + type + select_id).val();
                var city_id = select_id;
                var column_name = type;

                $.ajax({
                    url: '<?php echo base_url('Country/UpdateCityList'); ?>',
                    data: {city_id: city_id, columnVal: columnVal, column_name: column_name},
                    error: function () { },
                    dataType: 'html',
                    type: 'POST',
                    success: function (data) {
                        var jsonData = $.parseJSON(data);
                        if (jsonData.success) {
                            alert('Data updated successfully');
                        }
                    },

                });

            }
            function Valid_search() {
                var searchVal = $("#search_val").val();

                if (searchVal == '') {
                    alert("Please enter city name to search");
                    return false;
                }
                const url = window.location.href.split('?')[0];
                window.location.href = url + '?filter_by=' + searchVal;

            }
            function clearFilter() {
                const url = window.location.href.split('?')[0];
                window.location.href = url;
            }
        </script>
    </body>
</html>
