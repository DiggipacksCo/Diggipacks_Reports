<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>
         <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 

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
                       
                       

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?=lang('lang_Performance');?></strong></h1>

                                <div class="heading-elements">
                                    <ul class="icons-list">
                                        <!-- <li><a data-action="collapse"></a></li>
                                        <li><a data-action="reload"></a></li> -->
                                        <!-- <li><a data-action="close"></a></li> -->
                                    </ul>
                                </div>
                                <hr>
                            </div>
                            
                             <div class="row" >
                                <div class="col-lg-12" >

                                    <!-- Marketing campaigns -->
                                    <div class="panel panel-flat" dir="ltr">

                                        <form method="post" action="<?= base_url(); ?>performance">

                                            <div class="panel-body" >
                                                <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                    <?php
                                                      
                                                    // lowest year wanted
                                                      
                                                      if($postData['clfilter']==1)
                                                      {
                                                   $postData=array();
                                                      }
                                                      
                                                      if (empty($postData['to_date']))
                                                    $to_date = 0;
                                                else
                                                     $to_date = $postData['to_date'];
                                                if (empty($postData['from_date']))
                                                    $from_date = 0;
                                                else
                                                     $from_date = $postData['from_date'];
                                                   
                                                    ?>


                                                    <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_company');?>:</strong>

                                                            <select  id="cc_id" name="cc_id"    data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                                <option value=""><?=lang('lang_Select_Company');?></option>
                                                                <?php
                                                                foreach (GetCourierCompanyDrop() as $data) {

                                                                    if ($postData['cc_id'] == $data['id'])
                                                                        echo'  <option value="' . $data['id'] . '" selected>' . $data['company'] . '</option>';
                                                                    else
                                                                        echo'  <option value="' . $data['id'] . '">' . $data['company'] . '</option>';
                                                                }
                                                                ?>

                                                            </select>
                                                        </div> </div>
                                                    
                                                     <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                             <input class="form-control date" placeholder="From" id="from_date" name="from_date" value="<?=$postData['from_date'];?>" >

                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" placeholder="To" id="to_date"name="to_date" value="<?=$postData['to_date'];?>"  > 

                                                    </div></div>
<div class="col-md-4"><div class="form-group" ><strong></strong><br/><button type="submit"  class="btn btn-danger"- ><?= lang('lang_Get_Details'); ?></button>
                                                            <button type="submit" value="1" name="clfilter"  class="btn btn-danger"- ><?=lang('lang_Clear_Filter');?></button>

                                                        </div></div>




                                                </div>
                                            </div>

                                        </form>

                                        <!-- /quick stats boxes -->
                                    </div>
                                </div>
                            </div>

                            <div class="panel-body" >

              <!-- <input type="text" id="search"  placeholder="Search .." class="form-control">
                                -->


                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered bg-*" >
                                        <thead>
                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.</th>
                                                <th><?=lang('lang_Name');?></th>
                                                <th><?=lang('lang_Delivered');?></th>

                                                <th><?=lang('lang_Return');?></th>
                                                <th><?=lang('lang_Running');?></th>
                                                <th><?=lang('lang_Delivery_Performance_Report');?></th>


                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $sr = 1; ?>
                                            <?php
                                            if (!empty($sellers)) {
                                                $totalshow_deliverd = 0;
                                                $totalshow_deliverdnot = 0;
                                                $totalshow_deliverdruning = 0;
                                                if (empty($to_date))
                                                    $to_date = 0;
                                                if (empty($from_date))
                                                    $from_date = 0;
                                                foreach ($sellers as $seller) {

                                                    $delivered = GetdeliveryreportArr($seller->cc_id, '7',$postData);
                                                    $Not_delivered = GetdeliveryreportArr($seller->cc_id, '8',$postData);
                                                    $running = GetdeliveryreportArr($seller->cc_id, 'running',$postData);

                                                    $totalrun = $delivered + $Not_delivered + $running;
                                                    $performance = number_format($delivered / $totalrun * 100, 2);

                                                    $totalshow_deliverd += $delivered;
                                                    $totalshow_deliverdnot += $Not_delivered;
                                                    $totalshow_deliverdruning += $running;
                                                    echo'<tr>
                      <td>' . $sr . '</td>
                      <td>' . $seller->company . ' </td>
                      <td><a href="' . base_url() . 'performance_details/' . $seller->cc_id . '/7/' . $from_date . '/' . $to_date . '" target="_blank"><span class="btn btn-success">' . $delivered . ' </span></a></td>
					  <td><a href="' . base_url() . 'performance_details/' . $seller->cc_id . '/8/' . $from_date . '/' . $to_date . '" target="_blank"><span class="btn btn-danger">' . $Not_delivered . ' </span></a></td>
					  <td><a href="' . base_url() . 'performance_details/' . $seller->cc_id . '/running/' . $from_date . '/' . $to_date . '" target="_blank"><span class="btn btn-warning">' . $running . '</span></a> </td>
					  <td><span class="btn btn-info">' . $performance . '</span> </td>
					 <td class="text-center"><a data-toggle="modal" data-target="#update_location_' . $seller->id . '" title="Graph Details" onclick="chartcheckdetail(' . $seller->id . ',' . $delivered . ',' . $Not_delivered . ',' . $running . ');"><i class="fa fa-signal fa-2x text-green"></i></a></td>
					  
                    
                     
                    </tr>
					
					 <div id="update_location_' . $seller->id . '" class="modal fade" >
    <div class="modal-dialog" >
      <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h6 class="modal-title">Graph Details</h6>
        </div>
      
      
        <script>
		function chartcheckdetail(poid,deli,ndeli,ofd)
			{
				console.log(poid+","+ofd+","+deli+","+ndeli);
				var total=parseInt(deli)+parseInt(ndeli)+parseInt(ofd);
				//var poidpersent=parseInt(poid)*100/total;
				var ofdpersent=parseInt(ofd)*100/total;
				var delipersent=parseInt(deli)*100/total;
				var ndelipersent=parseInt(ndeli)*100/total;
			new Chart(document.getElementById("doughnut-chart"+poid), {
		type: \'doughnut\',
		data: {
		  labels: ["Running %" ,"Delivered %", "Return %"],
		  datasets: [
			{
			  label: "Shipment Details",
			  backgroundColor: ["#FF5722","green","red"],
			  data: [ofdpersent.toFixed(2),delipersent.toFixed(2),ndelipersent.toFixed(2)]
			}
		  ]
		},
		 options: {
			 legend: {
				display: true
			 },
			 tooltips: {
				enabled: true
			 }
		
		}
	});
	}</script>
       
        <canvas id="doughnut-chart' . $seller->id . '" style="width:20%; height:20%;"></canvas>
        
       </div>
    </div>
</div>
					';
                                                    $sr++;
                                                }

                                                echo'<tr><td colspan="2">&nbsp;</td>
		<td><a href="' . base_url() . 'performance_details/0/7/' . $from_date . '/' . $to_date . '"><span class="btn btn-success">' . $totalshow_deliverd . '</span></a></td>
		<td><a href="' . base_url() . 'performance_details/0/8/' . $from_date . '/' . $to_date . '"><span class="btn btn-danger">' . $totalshow_deliverdnot . '</span></a></td>
		<td><a href="' . base_url() . 'performance_details/0/running/' . $from_date . '/' . $to_date . '"><span class="btn btn-warning">' . $totalshow_deliverdruning . '</span></a></td><td colspan="3">&nbsp;</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                                <!--  <div>
                                   <center>
<?php //echo $links;  ?> 
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




        </div>

   <script type="text/javascript">

                                    $('.date').datepicker({

                            format: 'yyyy-mm-dd'

                            });

        </script>
    </body>
</html>
