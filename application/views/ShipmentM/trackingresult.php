<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
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


                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                      <h1> <strong>Tracking Parcel List</strong>  <a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> </h1>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <?php
                                    if (!empty($traking_awb_no))
                                        echo 'Tracking Result for AWB#<b>' . implode(',', $traking_awb_no) . '</b>';
                                    ?>
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><b class="size-2">Date</b></th>
                                                <th><b class="size-2">Origin</b></th>
                                                <th><b class="size-2">Destination</b></th>
                                                <th><b class="size-2">Pieces</b></th>
                                                <th><b class="size-2">Weight</b></th>
                                                <th><b class="size-2">Status</b></th>
                                                <th><b class="size-2">Action</b></th>
                                            </tr>
                                            <?php
                                            //print_r($shipmentdata);
                                            if (!empty($shipmentdata)) {
                                                foreach ($shipmentdata as $awbdata) {
                                                    echo '<tr>
                                                        <td>' . $awbdata['entrydate'] . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                                                        <td>' . $awbdata['pieces'] . '</td>

                                                        <td>' . $awbdata['weight'] . 'Kg</td>
                                                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>

                                                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary" target="_black">View Details</a></td>

                                                        </tr>';
                                                }
                                            } else {
                                                echo'<tr><td colspan="6" align="center">record not found</td></tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>
                                </div>
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
        
        <div style="display:none;">
            
              <table  id="downloadtable">
                                        <thead>
                                            <tr>
                                                <th><b class="size-2">Date</b></th>
                                                 <th><b class="size-2">AWB No.</b></th>
                                                <th><b class="size-2">Origin</b></th>
                                                <th><b class="size-2">Destination</b></th>
                                                <th><b class="size-2">Pieces</b></th>
                                                <th><b class="size-2">Weight</b></th>
                                                <th><b class="size-2">Status</b></th>
                                               
                                            </tr>
                                            <?php
                                            //print_r($shipmentdata);
                                            if (!empty($shipmentdata)) {
                                                foreach ($shipmentdata as $awbdata) {
                                                    echo '<tr>
                                                        <td>' . $awbdata['entrydate'] . '</td>
                                                             <td>' . $awbdata['slip_no'] . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                                                        <td>' . $awbdata['pieces'] . '</td>

                                                        <td>' . $awbdata['weight'] . 'Kg</td>
                                                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>

                                                       

                                                        </tr>';
                                                }
                                            } else {
                                                echo'<tr><td colspan="6" align="center">record not found</td></tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>
        </div>


        <script>
        
        var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    var blob = new Blob([format(template, ctx)]);
  var blobURL = window.URL.createObjectURL(blob);
    return blobURL;
  }
})()

$("#btnExport").click(function () {
    var todaysDate = 'Trackiing Details '+ new Date();
    var blobURL = tableToExcel('downloadtable', 'test_table');
    $(this).attr('download',todaysDate+'.xls')
    $(this).attr('href',blobURL);
});</script>
    </body>
</html>
