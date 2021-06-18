<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Update_User_Privilege');?></title>
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
<!--                            <div class="panel-heading"><h1><strong>Add Privilege</strong></h1><span class="badge badge-danger badge-pill float-right">&nbsp;&nbsp;</span><span class="float-right"> &nbsp;&nbsp;Inactive&nbsp;</span>&nbsp;&nbsp;<span class="badge badge-success badge-pill float-right">&nbsp;&nbsp;</span> <span class="float-right"> Active&nbsp;</span></div>-->
                            <hr>
                            <div class="panel-body">

                             
                                       
                                        <?php
                                         $SrNo=lang('lang_SrNo');	
                                         $lang_Privilege_Name=lang('lang_Privilege_Name');	
                                         $lang_Action=lang('lang_Action');	
                                        echo'<div id="privilage_table">';
                                        // if(!empty($privikegeData))
                                        //  {



                                        echo'<table class="table table-striped table-bordered table-hover">';

                                        echo'<thead>
                <tr>
                <td>'.$SrNo.'</td>
                <td>'.$lang_Privilege_Name.'</td>
                <td>'.$lang_Action.'</td>
                </tr>
                </thead>
                <tbody>
                <div class="panel-body">
                <div class="col-md-6">
                <div class="content-group">
                <div class="row">
                <div class="col-sm-6">';
                                        //print_r($privikegeData[0]); die;
                                        foreach ($privikegeData as $key => $val) {
                                            
                                            if($val['deleted']=='Y')
                                            {
                                            $linkactive='';//'<span class="badge badge-danger badge-pill float-right">&nbsp;&nbsp;</span>';
                                            }
                                            else
                                            {
                                               $linkactive='';'<span class="badge badge-success badge-pill float-right">&nbsp;&nbsp;</span>';  
                                            }
                                            $sr_no = $key + 1;
                                            // echo checkPrivilageExitsForCustomer($userid,$privikegeData[$key]['id']);

                                            $SubmenuArray = getallprivilegedata_submenu($privikegeData[$key]['id']);
                                            $submenutable = '';
                                            $kk = 0;

                                            foreach ($SubmenuArray as $key2 => $val) {
                                                if($val['deleted']=='Y')
                                            {
                                            $linkactive_sub='';//'<span class="badge badge-danger badge-pill float-right">&nbsp;&nbsp;</span>';
                                            }
                                            else
                                            {
                                               $linkactive_sub='';//'<span class="badge badge-success badge-pill float-right">&nbsp;&nbsp;</span>';  
                                            }
                                                $alphabet = range('A', 'Z');
                                                $sr_no2 = $kk;

                                                if (checkPrivilageExitsForCustomer($userid, $SubmenuArray[$key2]['id']) == 'Y') {
                                                    $submenutable .= '<table class="table table-striped table-bordered table-hover">
						<tr><td width="150">' . $alphabet[$kk] .$linkactive_sub. '</td><td>' . $SubmenuArray[$key2]['privilege_name'] . '</td><td align="center" width="200"><div class="checkbox checkbox-switch">
					<label>
					<input type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-toggle="toggle" data-onstyle="success" data-offstyle="warning" data-on-color="default" data-off-color="danger" checked name="onoff_check_box_' . $SubmenuArray[$key2]['id'] . '" id="onoff_check_box_' . $SubmenuArray[$key2]['id'] . '"  onchange="setUserPrivilageOnOff(' . $SubmenuArray[$key2]['id'] . ');" value="' . $SubmenuArray[$key2]['id'] . '" >
					</label>
					
					
					<span id="alert_customer"></span>
					</div></td></tr></table>';
                                                } else {
                                                    $submenutable .= '<table class="table table-striped table-bordered table-hover">
						<tr><td width="150">' . $alphabet[$kk] .$linkactive_sub. '</td><td>' . $SubmenuArray[$key2]['privilege_name'] . '</td><td align="center" width="200"><div class="checkbox checkbox-switch">
					<label>
					<input type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-toggle="toggle" data-onstyle="success" data-offstyle="warning" data-on-color="default" data-off-color="danger"  name="onoff_check_box_' . $SubmenuArray[$key2]['id'] . '" id="onoff_check_box_' . $SubmenuArray[$key2]['id'] . '"  onchange="setUserPrivilageOnOff(' . $SubmenuArray[$key2]['id'] . ');" value="' . $SubmenuArray[$key2]['id'] . '" >
					</label>
					
					
					<span id="alert_customer"></span>
					</div></td></tr></table>';
                                                }

                                                $kk++;
                                            }

                                            if (checkPrivilageExitsForCustomer($userid, $privikegeData[$key]['id']) == 'Y') {

                                                echo'<tr>
                <td>' . $sr_no .$linkactive. '</td>
                <td>' . $privikegeData[$key]['privilege_name'];
                                                echo $submenutable;
                                                echo'</td>
                <td align="center">';
                                                echo '<div class="checkbox checkbox-switch">
                <label>
                <input type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-toggle="toggle" data-onstyle="success" data-offstyle="warning" data-on-color="default" data-off-color="danger" checked name="onoff_check_box_' . $privikegeData[$key]['id'] . '" id="onoff_check_box_' . $privikegeData[$key]['id'] . '"  onchange="setUserPrivilageOnOff(' . $privikegeData[$key]['id'] . ');" value="' . $privikegeData[$key]['id'] . '" >
                </label>
				
                
                <span id="alert_customer"></span>
                </div>';
                                                echo '</td></tr>';
                                            } else {
                                                //print_r();
                                                //. if(in_array($privikegeData[$key]['id'],$SubmenuArray))	




                                                echo'<tr><td>' . $sr_no .$linkactive. '</td><td>' . $privikegeData[$key]['privilege_name'];
                                                echo $submenutable;

                                                echo'</td><td  align="center">
                
                <div class="checkbox checkbox-switch">
                <label>
                <input type="checkbox" class="switch" data-on-text="On" data-off-text="Off" data-toggle="toggle" data-onstyle="success" data-offstyle="warning" data-on-color="default" data-off-color="danger" name="onoff_check_box_' . $privikegeData[$key]['id'] . '" id="onoff_check_box_' . $privikegeData[$key]['id'] . '"  onchange="setUserPrivilageOnOff(' . $privikegeData[$key]['id'] . ');" value="' . $privikegeData[$key]['id'] . '" >
                </label>
                </div>
                </td></tr>';
                                            }
                                        }
                                        echo'</div></div></div></div> </div></tbody></table>';



                                        echo'</div>';
                                        ?> 


                                   
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

    $.post("<?= base_url(); ?>Users/setCustomerPrivilage?privilage_id="+privilage_id+"&customer_id="+<?= $userid ?>+"&onoff_true_false="+onoff_true_false, function(data, status){
    //alert(");

    });


    }


    //

</script>
