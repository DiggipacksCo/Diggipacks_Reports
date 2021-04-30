
    <?php
        //print "<pre>"; print_r($result);die;
        if(is_array($result) && count($result) >0){
            $cnt = $counter;
            foreach($result as $listdata){

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


    }else{ ?>
    <tr><td colspan="27" class="text-center">No Data Found</td></tr>
<?php    }      ?>              
                                   