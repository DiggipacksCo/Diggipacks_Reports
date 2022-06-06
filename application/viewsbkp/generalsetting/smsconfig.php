<?php defined('BASEPATH') OR exit('No direct script access allowed');
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
    <title><?= lang('lang_Set_SMS_Config'); ?></title>
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
                        <div class="panel-heading"><h1><strong><?= lang('lang_SMS_Configuration'); ?></strong></h1></div>
                        <hr>
                        <div class="panel-body">

                    
              
               <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?= lang('lang_SMS_Configuration'); ?></legend>
                
                 <?php if(empty($res_data)) {?>
                <form action="<?= base_url('Generalsetting/addsmssetting')?>" method="post" enctype="multipart/form-data">
                <button type="submit" class="btn btn-primary pull-right"><?= lang('lang_ADD'); ?></button> 
            </form>  
                        <?php } ?>
                 <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
                <thead>
                  <tr>
                  <th>#</th>
                    <th><?= lang('lang_Company_name'); ?></th>
                    <th><?= lang('lang_Param'); ?></th>
                    <th><?= lang('lang_ApiUrl'); ?></th>
                    <th><?= lang('lang_Action'); ?></th>
                  </tr>
                </thead>
                <tbody>

                  
                  <?php 
                if(!empty($res_data))
                {
                    $i=1; 
                    $totalCount=count($res_data);
                     
                   $seller= $res_data
                    ?>
                      <tr >
                      <td><?=$i;  ?></td>
                      <td><?= $seller['company_name']; ?></td>   
                      <td><?= $seller['api_url']; ?></td>
                      <td><?= $seller['params']; ?></td>                   
                      
                      <td class="text-center">
                                                <ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                           
                                                        
                                                           <li ><a href="<?=base_url('Generalsetting/addsmssetting/'.$seller['super_id']);?>"  ><i class="icon-pencil7"></i><?= lang('lang_Edit'); ?></a></li>
                                                           





                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td> 

                    </tr>

                <?php }?>
              
              </tbody>
            </table>
                </div>
                </fieldset>
              
          

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