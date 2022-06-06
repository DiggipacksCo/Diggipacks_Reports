<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/manifest.app.js?token=<?=time();?>"></script>
    </head>

    <body ng-app="AppManifest" > 

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="manifestView" ng-init="loadMore(1, 0, '<?= $manifest_id; ?>', '<?= $type; ?>');">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>


                    <div class="content" ng-init="filterData.pickupId = '<?= $manifest_id; ?>'">
                        <!-- Content area -->

                        <!--style="background-color: red;"-->

                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>


               

                        <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>


                                            <strong>Manifest Details <?= $manifest_id ?></strong>
                          <!--                  <a  ng-click="manifestexport();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>-->
                                            <a onclick="printPage('block1');"><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>
                                        </h1>
                                    </div>
                                    <input type="hidden" name="manifest_id" ng-model="filterData.manifest_id" value="<?= $manifest_id ?>">
                                    <form ng-submit="dataFilter();">


                                        <div class="table-responsive " >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->

                                                <table class="table table-bordered table-hover" style="width: 100%;">
                                                    <!-- width="170px;" height="200px;" -->
                                                    <tbody >

                                                        <tr style="width: 80%;">





                                                            <td>
                                                                <div class="form-group" ><strong>SKU:</strong>
                                                                    <input type="text" id="sku" name="sku" ng-model="filterData.sku" class="form-control" placeholder="Enter Sku"> 

                                                                </div>
                                                            </td>


                                                            <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                            <td><button  class="btn btn-danger" ng-click="loadMore(1, 1, '<?= $manifest_id; ?>', '<?= $type; ?>');" ><?= lang('lang_Search'); ?></button></td>
                                                            
                                                            <td ng-if="Items.length>0"><button  class="btn btn-danger" ng-click="GetUpdateMissingdamageAll('M');"  >Missing</button></td>
                                                                <td ng-if="Items.length>0"><button  class="btn btn-danger" ng-click="GetUpdateMissingdamageAll('D');"  >Damage</button></td>


                                                        </tr>


                                                    </tbody>
                                                </table>
                                                <br>




                                            </div>



                                        </div>

                                        <!-- /quick stats boxes -->
                                </div>
                        <div class="panel panel-flat" >

                            <div class="panel-body" >


                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>Sr.No.<br>
<!--                                                    <input type="checkbox" ng-model="selectedAll"  ng-change="selectAll();" />-->
                                                </th>
                                                <th>Manifest ID</th>
                                                <th>Item Image</th>
                                               
                                                <th>SKU</th>  
                                                <th>QTY</th>  
                                                <th>Damage Qty</th>
                                                <th>Missing Qty</th>
                                                 <th>Received</th>
                                                <th>Expire Date</th>
                                                <th>Status</th>
                                                <th>Code</th>

                                                <th>Seller</th>
                                                <th>Assign TO</th>
                                                <th>On Hold</th>
                                                <th>Order Confirm</th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>

                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}}<br>
<!--                                                    <input ng-if="data.code=='PU'" type="checkbox" value="{{data.id}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" />
                                                    <input ng-if="data.code!='PU'" type="checkbox" disabled="disabled" />-->
                                            </td>
                                            <td>{{data.uniqueid}}</td>
                                            <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="100">
                                                <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="100">
                                            </td>
                                            <td>{{data.sku}}</td>
                                            <td><span class="badge badge-primary">{{data.qty}}</span></td>
                                            <td><span class="badge badge-danger"  ng-if="data.editdamage==0"  ng-click="shipData[$index].editdamage=1">{{data.damage_qty}}</span><input type="number"  ng-if="data.editdamage==1" ng-model="shipData[$index].damage_qty" ng-blur="shipData[$index].editdamage=0" string-to-number max="{{data.qty}}"></td>
                                            <td><span class="badge badge-warning"  ng-if="data.editmissing==0"  ng-click="shipData[$index].editmissing=1"  >{{data.missing_qty}}</span> <input type="number" ng-if="data.editmissing==1" value="shipData[$index].missing_qty" ng-model="shipData[$index].missing_qty" ng-blur="shipData[$index].editmissing=0" string-to-number max="{{data.qty}}"> </td>
                                             <td><span class="badge badge-success"  ng-if="data.editreceived==0 || data.editreceived==null"  ng-click="shipData[$index].editreceived=1"  > {{data.received_qty}}</span> <input type="number" ng-if="data.editreceived==1" value="shipData[$index].received_qty" ng-model="shipData[$index].received_qty" ng-blur="shipData[$index].editreceived=0" string-to-number max="{{data.qty}}"> 
                                            
                                           
                                            </td>
                                            
                                           
                                          
                                            <td>{{data.expire_date}}</td>
                                            <td>{{data.pstatus}}</td>
                                            <td>{{data.code}}</td>

                                            <td>{{data.seller_id}}</td>
                                            <td>{{data.assign_to}}</td>
                                            <td ng-if="data.on_hold == 'N'"><span class="badge badge-danger"> NO</span></td>
                                            <td ng-if="data.on_hold == 'Y'"><span class="badge badge-success"> Yes</span></td>
                                            <td ng-if="data.itemupdated == 'N'"><span class="badge badge-danger"> NO</span></td>
                                            <td ng-if="data.itemupdated == 'Y'"><span class="badge badge-success"> Yes</span></td>

                                            <td ng-if="data.save_button=='Y'" > <button  class="btn btn-success" ng-click="savedata($index)" >Save</button></td>
                                            <td  ng-if="data.save_button=='N'" > <button  class="btn btn-danger"  >Saved</button></td>

                                            <!-- <td ng-if="data.code == 'PU'"><select name="notfoundstatus" class="form-control" ng-model="UpdateData.upstatus" ng-change="getUpdatenotfoundStatus(data.id);"><option value="">Select Status</option><option value="MSI">Missing Item</option><option value="DI">Damage Item</option></select></td>
                                            <td ng-if="data.code != 'PU'">--</td> -->
                                        </tr>

                                    </table>
                                    <div style="display:none;">
                                        <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No.</th>
                                                    <th>Manifest ID</th>
                                                    <th>SKU</th> 
                                                     <th>QTY</th>  
                                                <th>Damage Qty</th>
                                                <th>Missing Qty</th>
                                                 <th>Received</th>
                                                    <th>Expire Date</th>
                                                    <th>Status</th>
                                                    <th>Code</th>

                                                    <th>Seller</th>
                                                    <th>Assign TO</th>
                                                    <th>On Hold</th>
                                                    <th>Order Confirm</th>


                                                </tr>
                                            </thead>
                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                                <td>{{$index + 1}}</td>
                                                <td>{{data.uniqueid}}</td>
                                                <td>{{data.sku}}</td>
                                                <td><span class="badge badge-primary">{{data.qty}}</span></td>
                                            <td><span class="badge badge-danger" >{{data.damage_qty}}</span></td>
                                            <td><span class="badge badge-warning"   >{{data.missing_qty}}</span>  </td>
                                            <td><span class="badge badge-success">{{data.received_qty}}</span></td>
                                                <td>{{data.expire_date}}</td>
                                                <td>{{data.pstatus}}</td>
                                                <td>{{data.code}}</td>

                                                <td>{{data.seller_id}}</td>
                                                <td>{{data.assign_to}}</td>
                                                <td ng-if="data.on_hold == 'N'"><span class="badge badge-danger"> NO</span></td>
                                                <td ng-if="data.on_hold == 'Y'"><span class="badge badge-success"> Yes</span></td>
                                                <td ng-if="data.itemupdated == 'N'"><span class="badge badge-danger"> NO</span></td>
                                                <td ng-if="data.itemupdated == 'Y'"><span class="badge badge-success"> Yes</span></td>

                                            </tr>

                                        </table>
                                    </div>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0, '<?= $manifest_id ?>', '<?= $type; ?>');" ng-init="count = 1">Load More</button>
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
        <!-- /page container -->

    </body>
</html>
