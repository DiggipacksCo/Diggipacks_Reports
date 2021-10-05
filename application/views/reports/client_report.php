
<?php
/* $number = cal_days_in_month(CAL_GREGORIAN, 10, date('Y')); // 31
  echo "There were {$number} days in August 2003";
  die; */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/reports.app.js"></script>


    </head>

    <body ng-app=AppReports >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="ClientReportCTRL">

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

                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" dir="ltr">
                                    <div class="panel-heading">
                                        <h1> <strong><?= lang('lang_Client_Report'); ?> </strong> 
                                            <a  id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;

                                            <a onclick="printPage();" style="display:none"><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">

                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                <?php
                                                // lowest year wanted
                                                $cutoff = 2020;

                                                // current year
                                                $now = date('Y');

                                                // build years menu
                                                $yearDrop .= '<select name="year" class="form-control" ng-model="filterData.year"><option value="">Year</option>' . PHP_EOL;
                                                for ($y = $now; $y >= $cutoff; $y--) {
                                                    $yearDrop .= '  <option value="' . $y . '">' . $y . '</option>' . PHP_EOL;
                                                }
                                                $yearDrop .= '</select>' . PHP_EOL;

                                                // build months menu
                                                $monthDrop .= '<select name="month" class="form-control" ng-model="filterData.month">'
                                                        . '<option value="">Month</option>' . PHP_EOL;

                                                $monthDrop .= '   <option value="01">January</option>
                                                            <option value="02">February</option>
                                                            <option value="03">March</option>
                                                            <option value="04">April</option>
                                                            <option value="05">May</option>
                                                            <option value="06">June</option>
                                                            <option value="07">July</option>
                                                            <option value="08">August</option>
                                                            <option value="09">September</option>
                                                            <option value="10">October</option>
                                                            <option value="11">November</option>
                                                            <option value="12">December</option>' . PHP_EOL;

                                                $monthDrop .= '</select>' . PHP_EOL;

                                                // build days menu
                                                $daysDrop .= '<select name="day" class="form-control" ng-model="filterData.day"><option value="">Day</option>' . PHP_EOL;
                                                for ($d = 1; $d <= 31; $d++) {
                                                    $daysDrop .= '  <option value="' . $d . '">' . $d . '</option>' . PHP_EOL;
                                                }
                                                $daysDrop .= '</select>' . PHP_EOL;
                                                ?>




                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_Customer');?>:</strong>
                                                        <select class="form-control"  ng-model="filterData.cust_id">
                                                            <option  value=""><?=lang('lang_Select_Customer');?></option> 
                                                            <?php
                                                            $custDrop = Getallsellerdata();
                                                            foreach ($custDrop as $val) {
                                                                echo' <option  value="' . $val['id'] . '">' . $val['name'] . '/' . $val['company'] . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Year');?>:</strong>
                                                        <?= $yearDrop; ?>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Month');?>:</strong>
                                                        <?= $monthDrop; ?>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Day');?>:</strong>
                                                        <?= $daysDrop; ?>
                                                    </div></div>

                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_warehouse');?>:</strong> <br>
                                                        <?php
                                                        $warehouseArr = Getwarehouse_Dropdata();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-model="filterData.wh_id"  class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_Selectwarehousename');?></option>
                                                            <?php foreach ($warehouseArr as $data): ?>
                                                                <option value="<?= $data['id']; ?>">
                                                                    <?= $data['name']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div></div>

                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_AWB_No');?>:</strong>
                                                        <input type="text" id="slip_no" name="slip_no"  ng-model="filterData.slip_no"  class="form-control" placeholder="Enter AWB no.">
                                                        <!--  <?php // if($condition!=null):    ?>
                                                         <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition; ?>" >
                                                        <?php // endif;  ?> -->
                                                    </div></div>

                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_Destination');?>:</strong>
                                                        <br>
                                                        <?php
                                                        $destData = getAllDestination();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-model="filterData.destination" data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Destination');?></option>
                                                            <?php foreach ($destData as $data): ?>
                                                                <option value="<?= $data['id']; ?>"><?= $data['city']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> </div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Ref_No');?>:</strong>
                                                        <input  id="booking_id" name="booking_id"  ng-model="filterData.booking_id" class="form-control" placeholder="Enter Ref no."> 

                                                    </div></div>

                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_company');?>:</strong>

                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Company');?></option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> </div>

                                                <div class="col-md-4"><div class="form-group" ><strong><?=lang('lang_Payment_Mode');?>:</strong><br/>

                                                        <select  id="mode" name="cc_id"  ng-model="filterData.mode"   class="form-control" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Mode');?></option>
                                                            <option value="COD"><?=lang('lang_COD');?></option>
                                                            <option value="CC"><?=lang('lang_CC');?></option>


                                                        </select>
                                                    </div>  </div>
                                                <div class="col-md-4"><div class="form-group" ><strong></strong><br/><button  class="btn btn-danger" ng-click="GetClientOrderReports(1, 1);" ><?= lang('lang_Get_Details'); ?></button>

                                                        <button type="button" class="btn btn-success ml-10" ><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></div></div>




                                            </div>
                                        </div>

                                        <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;"  > 
                                    <!--style="background-color: green;"-->

                                    <table class="table table-striped table-hover table-bordered"   style="width:100%;">

                                        <thead>
                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.
                                                </th>
                                                <th><?=lang('lang_Payment_Mode');?></th>
                                                <th><?=lang('lang_AWB_No');?>.</th>
                                                <th><?=lang('lang_Ref_No');?>.</th>
                                                <th><?=lang('lang_Forwarded_AWB_No');?>.</th>
                                                <th><?=lang('lang_Forwarded_Company');?></th>
                                                <th><?=lang('lang_Destination');?></th>
                                                <th><?=lang('lang_Receiver');?></th>
                                                <th><?=lang('lang_Receiver_Address');?></th>
                                                <th><?=lang('lang_Receiver_Mobile');?></th>
                                                <th><?=lang('lang_Item_Sku_Detail');?>
                                                <th><?=lang('lang_Seller');?></th>
                                                <th><?=lang('lang_warehouse');?></th>
                                                <th> <?=lang('lang_Date');?></th>
                                            </tr>

                                        </thead>

                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}} </td>
                                            <td>{{data.mode}}</td>  
                                            <td>{{data.slip_no}}</td>
                                            <td>{{data.booking_id}}</td>
                                            <td>{{data.frwd_company_awb}}</td> 
                                            <td>{{data.cc_name}}</td>
                                            <td>{{data.destination}}</td>
                                            <td>{{data.reciever_name}}</td>
                                            <td>{{data.reciever_address}}</td>
                                            <td>{{data.reciever_phone}}</td>

                                            <td><a  ng-click="GetInventoryPopup(data.slip_no);"><span class="label label" style="background-color:<?= DEFAULTCOLOR; ?>;"><?= lang('lang_Get_Details'); ?></span></a></td>

                                            <td>{{data.company}}</td>
                                            <td > {{data.wh_id}}</td>
                                            <td>{{data.entrydate}}</td>
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="GetClientOrderReports(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></button>


                                </div>

                                <hr>
                            </div>
                        </div>
                        <!-- /basic responsive table -->
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area -->

                </div>

                <div id="deductQuantityModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-danger" style="background-color:<?= DEFAULTCOLOR; ?>;border-color:<?= DEFAULTCOLOR; ?>">
                                <h6 class="modal-title">Item Sku Detail</h6>
                                <button type="button" class="close" data-dismiss="modal">×</button>

                            </div>

                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                           <th><?=lang('lang_SKU');?> </th>
                                            <th><?=lang('lang_QTY');?></th>
                                            <th><?=lang('lang_Deducted_Shelve_NO');?></th>
                                            <th><?=lang('lang_COD');?> (<?= site_configTable("default_currency"); ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="dataship in shipData1">
                                            <td><span class="label label-primary">{{dataship.sku}}</span></td>
                                            <td><span class="label label-info">{{dataship.piece}}</span></td>
                                            <td><span class="label label-info">{{dataship.deducted_shelve}}</span></td>
                                            <td><span class="label label-danger">{{dataship.cod}}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    
                    
                    <div style="display:none;">
                        
                         <table class="table table-striped table-hover table-bordered" id="printTable"  style="width:100%;">

                                        <thead>
                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.
                                                </th>
                                                <th><?=lang('lang_Payment_Mode');?></th>
                                                <th><?=lang('lang_AWB_No');?>.</th>
                                                <th><?=lang('lang_Ref_No');?>.</th>
                                                <th><?=lang('lang_Forwarded_AWB_No');?>.</th>
                                                <th><?=lang('lang_Forwarded_Company');?></th>
                                                <th><?=lang('lang_Destination');?></th>
                                                <th><?=lang('lang_Receiver');?></th>
                                                <th><?=lang('lang_Receiver_Address');?></th>
                                                <th><?=lang('lang_Receiver_Mobile');?></th>
                                                <th><?=lang('lang_Item_Sku_Detail');?> <table class="table table-striped table-hover table-bordered"><tr><td><?=lang('lang_SKU');?></td><td><?=lang('lang_QTY');?></td><td><?=lang('lang_COD');?></td><td><?=lang('lang_Deducted_Shelve_NO');?></td></tr></table></th>
                                                <th><?=lang('lang_Seller');?></th>
                                                <th><?=lang('lang_warehouse');?></th>
                                                <th> <?=lang('lang_Date');?></th>
                                            </tr>

                                        </thead>

                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}} </td>
                                            <td>{{data.mode}}</td>  
                                            <td>{{data.slip_no}}</td>
                                            <td>{{data.booking_id}}</td>
                                            <td>{{data.frwd_company_awb}}</td> 
                                            <td>{{data.cc_name}}</td>
                                            <td>{{data.destination}}</td>
                                            <td>{{data.reciever_name}}</td>
                                            <td>{{data.reciever_address}}</td>
                                            <td>{{data.reciever_phone}}</td>

                                            <td><table class="table table-striped table-hover table-bordered">
                                                    <tr ng-repeat="data_s in data.deducted_shelve_no"><td>{{data_s.sku}}</td><td>{{data_s.piece}}</td><td>{{data_s.cod}}</td><td>{{data_s.deducted_shelve}}</td></tr>
                                                </table></td>

                                            <td>{{data.name}}</td>
                                            <td > {{data.wh_id}}</td>
                                            <td>{{data.entrydate}}</td>
                                        </tr>
                                    </table>
                        
                    </div>

                </div>
                <!-- /main content -->

            </div>


        </div>

        <!-- /page container -->
        <script>

                    function printPage()
                    {
                    document.getElementById('disdisplay').style.display = "none";
                            var divToPrint = document.getElementById('printTable');
                            var htmlToPrint = '' +
                            '<style type="text/css">' +
                            'table th, table td {' +
                            'border:1px solid #000;' +
                            'width:1200px' +
                            '}' +
                            'table th, table td {' +
                            'border:1px solid #000;' +
                            'padding:8px;' +
                            '}' +
                            'table th {' +
                            'padding-top: 12px;' +
                            'padding-bottom: 12px;' +
                            ' text-align: left;' +
                            'border:1px solid #000;' +
                            'padding:0.5em;' +
                            '}' +
                            '</style>';
                            htmlToPrint += divToPrint.outerHTML;
                            newWin = window.open("");
                            newWin.document.write(htmlToPrint);
                            newWin.print();
                            newWin.close();
                    }
        </script>

                    <script>             var tableToExcel = (function() {
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
var todaysDate = 'Packaging Report '+ new Date();
var blobURL = tableToExcel('printTable', 'invoice');
$(this).attr('download',todaysDate+'.xls')
$(this).attr('href',blobURL);
});




        </script>

        <script>


                                // "order": [[0, "asc" ]]
                                $('#s_type').on('change', function () {
                        if ($('#s_type').val() == "SKU") {
                        $('#s_type_val').attr('placeholder', 'Enter SKU no.');
                        } else if ($('#s_type').val() == "AWB") {
                        $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                        }

                        });


        </script>

    </body>
</html>
