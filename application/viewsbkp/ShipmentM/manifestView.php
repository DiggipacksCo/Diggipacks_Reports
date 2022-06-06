<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/pickup.app.js"></script>
    </head>

    <body ng-app="appPickup" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="manifestView" ng-init="loadMore(1, 0);">

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" ng-init="filterData.m_id = '<?= $m_id ?>'" >
                        <!--style="background-color: red;"-->

                       


                        <div class="row" >
                            <div class="col-lg-12" >

                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1> <strong><?=lang('lang_Delivery_Manifest_list_Details');?>
                                                <?= $m_id ?>
                                            </strong> <a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>  </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">

                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                <!-- Today's revenue --> 

                                                <!-- <div class="panel-body" > -->

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >
                                                        <tr style="width: 80%;">
                                                           
                                                            
                                                            
                                                            <td><div class="form-group" ><strong><?=lang('lang_AWB_value');?>:</strong>
                                                                    <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                                                                    <!--  <?php // if($condition!=null):          ?>
                                                                                  <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition; ?>" >
                                                                    <?php // endif; ?> --> 
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group" ><strong><?=lang('lang_Ref_No');?>:</strong>
                                                                    <input  id="booking_id" name="booking_id"  ng-model="filterData.booking_id" class="form-control" placeholder="Enter Ref no."> 

                                                                </div>
                                                            </td>
                                                            <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?>  <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                            <td><button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div id="today-revenue"></div>
                                                <!-- </div> panel-body--> 

                                                <!-- /today's revenue --> 

                                            </div>
                                        </div>
                                    </form>
                                    <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="downloadtable" style="width:100%;">
                                        <thead>
                                            <tr>
                                                  <th><?=lang('lang_SrNo');?>.</th>
<th><?=lang('lang_AWB_No');?>.</th>
<th><?=lang('lang_Ref_No');?></th>
   <th><?=lang('lang_Origin');?></th>  
   <th><?=lang('lang_Destination');?></th>
    <th><?=lang('lang_Receiver');?></th>
    <th><?=lang('lang_Receiver_Address');?></th>
     <th><?=lang('lang_Receiver_Mobile');?></th>
                                                <th><?=lang('lang_Item_Sku_Detail');?>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                               <th><?=lang('lang_SKU');?></th>
                                                    <th><?=lang('lang_QTY');?></th>
                                                                <th><?=lang('lang_COD');?> (<?= site_configTable("default_currency"); ?>)</th>
                                                            </tr>
                                                        </thead>
                                                    </table></th>
                                               
                                                
                                                
                                                    <th><?=lang('lang_Seller');?></th>
                                                
                                                    <th><?=lang('lang_Date');?></th>
                                               
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}}</td>
                                            <td>{{data.slip_no}}</td>
                                            <td>{{data.booking_id}}</td>
                                            <td>{{data.origin}}</td>
                                            <td>{{data.destination}}</td>
                                            <td>{{data.reciever_name}}</td>
                                            <td>{{data.reciever_address}}</td>
                                            <td>{{data.reciever_phone}}</td>
                                            <td><table class="table table-striped table-hover table-bordered dataTable bg-*">
                                                    <tbody>
                                                        <tr ng-repeat="data1 in data.sku">
                                                            <td ><span class="label label-primary">{{data1.sku}}</span></td>
                                                            <td><span class="label label-info">{{data1.piece}}</span></td>
                                                            <td><span class="label label-danger">{{data1.cod}}</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table></td>
                                             
                                           <td>{{data.sender_name}}</td>
                                            
                                            <td>{{data.entrydate}}</td>
                                            
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_LoadMore');?></button>
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

<!-- <script>
var $rows = $('tbody tr');
$('#search').keyup(function() {
var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

$rows.show().filter(function() {
var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
return !~text.indexOf(val);
}).hide();
});
</script> -->

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
                    var blob = new Blob([format(template, ctx)]);                                 var blobURL = window.URL.createObjectURL(blob);                                return blobURL;
}
})()

$("#btnExport").click(function () {
var  todaysDate  =  'Delivery Manifest list Details <?= $m_id ?>' + new Date();
var blobURL = tableToExcel('downloadtable', 'test_table');
$(this).attr('download',todaysDate+'.xls')
$(this).attr('href',blobURL);
});


// "order": [[0, "asc" ]]
$('#s_type').on('change',function(){
if($('#s_type').val()=="SKU"){
$('#s_type_val').attr('placeholder','Enter SKU no.');
}else if($('#s_type').val()=="AWB"){
$('#s_type_val').attr('placeholder','Enter AWB no.');
}

});

     
        </script> 
        <script>


                                                                                        // "order": [[0, "asc" ]]
                                                                                        $('#s_type').on('change', function(){
                                                                                if ($('#s_type').val() == "SKU"){
                                                                                $('#s_type_val').attr('placeholder', 'Enter SKU no.');
                                                                                } else if ($('#s_type').val() == "AWB"){
                                                                                $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                                                                                }

                                                                                });
                                                                                        $timeout(function(){
                                                                                        $('.selectpicker').selectpicker('refresh'); //put it in timeout for run digest cycle
                                                                                        }, 1)


        </script> 
        <!-- /page container -->

    </body>
</html>
