<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>

        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/reports.app.js"></script>


    </head>


    <body ng-app="AppReports">
        <?php $this->load->view('include/main_navbar');
        ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CtritemInvontaryview" ng-init="loadMore(1, 0);">  

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 

                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading" dir="ltr">
                                        <h1><strong>Missing and Damage Inventory</strong><a href="<?= base_url('Excel_export/shipments'); ?>"></a>
                                            <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a> 

                                            
                                          
                                            <a  ng-click="getExcelDetails();" >
                                                <i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected><?= lang('lang_select_export_limit'); ?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.k}}" >{{exdata.j}}-{{exdata.k}}</option>  

                                            </select> 
                                        </h1>


                                    </div>
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 

                                            <!-- Today's revenue --> 

                                            <!-- <div class="panel-body" style="background-color: pink;"> -->

                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >

                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong><?= lang('lang_SKU'); ?>:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                            </div></td>

                                                        <td ><div class="form-group" ><strong>AWB No.:</strong>
                                                                <input type="text"   id="order_no" name="order_no"  ng-model="filterData.order_no" class="form-control" placeholder="Enter AWB No.">
                                                            </div></td>
                                                              <td ><div class="form-group" ><strong><?= lang('lang_Quantity'); ?>:</strong>
                                                                <input type="number" min='0'  id="quantity"name="quantity"  ng-model="filterData.quantity" class="form-control" placeholder="Enter Quantity">
                                                            </div></td>

                                                        <td ><div class="form-group" ><strong><?= lang('lang_Seller'); ?>:</strong> <br>
                                                                <select  id="seller" name="seller" ng-model="filterData.seller" class="selectpicker" data-width="100%" >
                                                                    <option value=""><?= lang('lang_Select_Seller'); ?></option>
                                                                    <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                            <?= $seller_detail->name; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>


                                                    </tr>


                                                    <tr style="width: 100%;">	





                                                        <td colspan="1"><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                        <td colspan="1"> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" style="margin-left: 7%" ><?= lang('lang_Search'); ?></button></td>

                                                    </tr>


                                                </tbody>
                                            </table>
                                            <br>



                                        </div>
                                    </div>

                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-flat" > 


                            <div class="panel-body" > 


                                <div class="table-responsive" style="padding-bottom:20px;" > 

                                    <table class="table table-striped table-hover table-bordered dataTable" id="printTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                 <th>AWB No.</th>
                                                <th><?= lang('lang_Name'); ?></th>
                                              
                                                <th><?= lang('lang_ItemSku'); ?></th>



                                                <th>Total <?= lang('lang_Quantity'); ?></th>
                                                <th>Damage <?= lang('lang_Quantity'); ?></th>
                                                <th>Missing <?= lang('lang_Quantity'); ?></th>
                                                <th><?= lang('lang_Seller'); ?></th>
                                                <th><?= lang('lang_Description'); ?></th>
                                                <th>Update On</th>


                                            </tr>
                                        </thead>
                                        <tbody>


                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}} </td>
                                                 <td>{{data.order_no}}</td>
                                                <td>{{data.name}}</td>
                                               

                                                <td>{{data.sku}}</a></td>

                                                <td><span class="badge badge-success">{{data.quantity}}</span></td>
                                                <td><span class="badge badge-danger">{{data.d_qty}}</span></td>
                                                <td><span class="badge badge-warning">{{data.m_qty}}</span></td>


                                                <td>{{data.seller_name}}</td>

                                                <td>{{data.description}}</td>
                                                <td>{{data.update_date}}</td>

                                            </tr>
                                        </tbody>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
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



            <!-- Excel --->


            <div id="excelcolumn" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f3f5f6;">
                            <center>   <h4 class="modal-title" style="color:#000"><?= lang('lang_Select_Column_to_download'); ?></h4></center>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">             
                                    <label class="container">

                                        <input type="checkbox" id='but_checkall' value='checkall' ng-model="checkall" ng-click='toggleAll()'/>     <?= lang('lang_SelectAll'); ?>
                                        <span class="checkmark"></span>


                                    </label>
                                </div>

                                <div class="col-md-12 row">
                                    
                                      <div class="col-sm-4">          
                                        <label class="container">  
                                            <input type="checkbox" name="order_no" value="order_no"   ng-model="listData2.order_no"> AWB No.
                                            <span class="checkmark"></span>
                                        </label>   
                                    </div>
                                    <div class="col-sm-4">          
                                        <label class="container">  
                                            <input type="checkbox" name="name" value="name"   ng-model="listData2.name"> <?= lang('lang_Name'); ?>
                                            <span class="checkmark"></span>
                                        </label>   
                                    </div>

                                   

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="sku" value="sku"  ng-model="listData2.sku"> <?= lang('lang_Item_SKU'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                  <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="quantity" value="quantity"  ng-model="listData2.quantity"> Total <?= lang('lang_Quantity'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                     <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="quantity" value="d_qty"  ng-model="listData2.d_qty"> Damage <?= lang('lang_Quantity'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    
                                    
                                     <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="quantity" value="m_qty"  ng-model="listData2.m_qty">  Missing <?= lang('lang_Quantity'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="seller_name" value="seller_name"  ng-model="listData2.seller_name"> <?= lang('lang_Seller'); ?>   
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="item_description" value="item_description"  ng-model="listData2.item_description"> <?= lang('lang_Description'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="update_date" value="update_date"  ng-model="listData2.update_date">  <?= lang('lang_Update_Date'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>




                                </div>
                                <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                    <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="ItemInventoryExport(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
                                </div>

                            </div>

                        </div>
                    </div>
                </div>  


            </div>   



        </div>



 <script>
            function printPage()
            {
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

    </body>
</html>
