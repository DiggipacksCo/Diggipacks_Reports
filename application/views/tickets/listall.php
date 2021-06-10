<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/tickets.app.js"></script>
    </head>

    <body ng-app="AppTickets" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CTR_ticketlist" ng-init="loadMore(1, 0);">

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
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong><?=lang('lang_Manifest_Ticket_List');?> </strong> 
                                          <!--  <a  ng-click="exportmanifestlist();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>--> 
                                          <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>--> 
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments'); ?>" --> 
                                    <!-- href="<? //base_url('Pdf_export/all_report_view'); ?>" --> 
                                        <!-- Quick stats boxes -->
                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                <!-- Today's revenue --> 

                                                <!-- <div class="panel-body" > -->

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >
                                                        <tr style="width: 80%;">
                                                            <td><div class="form-group" ><strong><?=lang('lang_Sellers');?>:</strong>
                                                                    <select id="seller_id"name="seller_id" ng-model="filterData.seller_id" class="form-control">
                                                                        <option value=""><?=lang('lang_SelectSeller');?></option>
                                                                        <option ng-repeat="sdata in sellers"  value="{{sdata.id}}">{{sdata.name}}</option>
                                                                    </select>
                                                                </div></td>
                                                            <td><div class="form-group" ><strong><?=lang('lang_Status');?>:</strong>
                                                                    <select id="searchstatus" name="searchstatus" ng-model="filterData.searchstatus" class="form-control">
                                                                        <option value=""><?=lang('lang_Select_Status');?></option>
                                                                        <option value="pending"><?=lang('lang_Pending');?></option>
                                                                        <option value="process"><?=lang('lang_Process');?></option>
                                                                        <option value="complated"><?=lang('lang_Completed');?></option>
                                                                    </select>
                                                                </div></td>
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

                                        <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                            <th><?=lang('lang_SrNo');?>.</th>
                                         <th><?=lang('lang_Ticket_ID');?></th>
                                                <th><?=lang('lang_Manifest_ID');?></th>
                                                <th><?=lang('lang_Seller');?></th>
                                                <th><?=lang('lang_Subjet');?></th>
                                                <th><?=lang('lang_Message');?></th>
                                                <th><?=lang('lang_Status');?></th>
                                                <th><?=lang('lang_Created_Date');?></th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}} </td>
                                            <td>{{data.ticket_id}}</td>
                                            <td ><a href="<?= base_url(); ?>ticketdetails_view/{{data.ticket_id}}" >{{data.mid_id}}</a></td><!-- comment -->
                                              <td > {{data.seller_id}}</td>
          <td > {{data.subject}}</td>
          <td >{{data.message}}</td>
                                            <td width="150" ><span class="badge badge-danger" ng-if="data.status == 'pending'"><?=lang('lang_Pending');?></span><span class="badge badge-warning" ng-if="data.status == 'process'"><?=lang('lang_Process');?></span><span class="badge badge-success" ng-if="data.status == 'complated'"><?=lang('lang_Completed');?></span></td>
                                            <td > {{data.entrydate}}</td>
                                            <td class="text-center"><ul class="icons-list">
                                                    <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li ><a href="<?= base_url(); ?>ticketdetails_view/{{data.ticket_id}}" ><i class="icon-eye" ></i> <?=lang('lang_View');?></a></li>
                                                            <li ng-if="data.status != 'complated'"><a ng-click="GetUpdateTicketData(data.id);"  ><i class="icon-pencil7"></i> <?=lang('lang_Ticket_Update');?></a></li> 

<!-- <li><a ng-click="updatemanifeststatus_notfound(data.id,data.uniqueid,data.sid,data.qty);"  ><i class="icon-pencil7"></i> Update Not Found</a></li>-->

                                                        </ul>
                                                    </li>
                                                </ul></td>
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></button>
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


            // "order": [[0, "asc" ]]
            $('#s_type').on('change', function () {
                if ($('#s_type').val() == "SKU") {
                    $('#s_type_val').attr('placeholder', 'Enter SKU no.');
                } else if ($('#s_type').val() == "AWB") {
                    $('#s_type_val').attr('placeholder', 'Enter AWB no.');
                }

            });


        </script> 
        <!-- /page container -->

    </body>
</html>
