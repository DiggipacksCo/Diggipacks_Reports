<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js"></script>






    </head>

    <body ng-app='Appiteminventory'>

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="IteminventoryAdd">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong><?=lang('lang_Add_Item_Inventory');?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if ($this->session->flashdata('msg')): ?>
                                    <?= '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?> 
                                <?php elseif ($this->session->flashdata('error')): ?>
                                    <?= '<div class="alert alert-danger">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                                <?php endif; ?>

                                <form action="<?= base_url('ItemInventory/add'); ?>" name="myform" method="post">

                                    <!-- <div class="form-group">
                                    <label for="exampleInputEmail1"><strong>ID#:</strong></label>
                                    <input  type="text"  name='id' value='<?= $item->id; ?>' disabled  class="form-control">
                                    </div> -->

                                    <div class="form-group">
                                        <label for="wh_id"><strong><?=lang('lang_Warehouse');?>:</strong></label>
                                        <?= GetwherehouseDropShow(set_value('wh_id')); ?>     
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><strong><?=lang('lang_Item_SKU');?>:</strong></label>
                                        <select name="sku" id="sku" class="selectpicker" data-show-subtext="true" data-live-search="true" ng-model="filterData.sku"  data-width="100%">
                                            <option value=""><?=lang('lang_Please_select_item_sku');?></option>
                                            <?php foreach ($items as $item): ?>

                                                <option value="<?= $item->id ?>">
                                                    <?= $item->sku ?>/<br>
                                                <?= $item->name ?></option>

                                            <?php endforeach; ?>

                                        </select>

                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><strong><?=lang('lang_Seller');?>#: </strong></label>
                                        <select name="seller" id="seller" class="selectpicker" data-show-subtext="true" data-live-search="true" ng-model="filterData.seller_id"  ng-change="loadMore();"   data-width="100%">
                                            <option value=""><?=lang('lang_Please_select_seller');?></option>
                                            <?php
                                            // print_r($sellers);
                                            foreach ($sellers as $seller):
                                                ?>

                                                <option value="<?= $seller->id ?>"><?= $seller->name ?></option>

<?php endforeach; ?>

                                        </select>
                                        <!-- <input  type="text"  name='sku' value=' disabled  class="form-control"> -->
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="qtycout" id="qtycout" ng-model="showform.qtycout">
                                        <label for="exampleInputEmail1"><strong><?=lang('lang_Quantity');?>:</strong></label>
                                        <input  type="number" class="form-control" name='quantity' required ng-model="filterData.quantity" value="1" min="1" id="exampleInputEmail1" ng-blur="loadMore()" >
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail15"><strong><?=lang('lang_Stock_Location');?>:</strong></label>
                                        <select name="stock_location[]" id="exampleInputEmail15" ng-model="filterData.stock_location"  class="js-example-basic-multiple bootstrap-select form-control" multiple data-width="100%" ng-change="GetcheckButton();"> 

                                            <option ng-repeat="data in shipData" value="{{data.stock_location}}">{{data.stock_location}}</option>

                                        </select>
                                        <span class="error">{{CountLocation}}</span>
                                        <span class="error"><br>{{CountLocation2}}</span>
                                        
                                    </div>    




                                    <!-- <div class="form-group">
                                    
                                    <label for="exampleInputEmail1"><strong>Quantity:</strong></label>
                                    <input type="number" class="form-control" name='quantity' value="<?= $item->quantity; ?>" id="exampleInputEmail1" placeholder="Name">
                                    </div> -->

                                    <div class="form-group">
                                        <label for="expity_date"><strong><?=lang('lang_Expire_Date');?>:</strong></label>
                                        <input  type="text" class="form-control" name='expity_date' ng-model="filterData.expity_date"  id="expity_date"   >
                                    </div>

                                    <button type="button" ng-show="buttonhide" class="btn btn-success" ng-click="GetaddconfirmDatashow()" ><?=lang('lang_Submit');?></button>

                                    <div class="modal fade" id="showskuformviewid2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">



                                                    <h5 class="modal-title" id="exampleModalLabel"><?=lang('lang_Confirm_Inventory_Order');?></h5>
                                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                     </button>-->
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered table-hover" style="width: 100%;">
                                                        <!-- width="170px;" height="200px;" -->
                                                        <tbody >
                                                            <tr style="width: 80%;">
                                                                <td><strong><?=lang('lang_item_sku');?>:</strong></td>
                                                                <td>{{filterData.skuname}}</td>

                                                            </tr>
                                                            <tr style="width: 80%;">
                                                                <td><strong><?=lang('lang_Seller');?>:</strong></td>
                                                                <td>{{filterData.seller_name}}</td>

                                                            </tr>
                                                            <tr style="width: 80%;">
                                                                <td><strong><?=lang('lang_Quantity');?>:</strong></td>
                                                                <td>{{filterData.quantity}}</td>

                                                            </tr>

                                                            <tr style="width: 80%;">
                                                                <td><strong><?=lang('lang_Stock_Location');?>:</strong></td>
                                                                <td><span ng-repeat="data in filterData.stock_location">{{data}}<br></span></td>

                                                            </tr>
                                                            <tr style="width: 80%;">
                                                                <td><strong><?=lang('lang_Expire_Date');?>:</strong></td>
                                                                <td>{{filterData.expity_date}}</td>

                                                            </tr>

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?=lang('lang_Cancel');?></button>
                                                    <button type="submit" class="btn btn-primary"  ><?=lang('lang_Confirm_Order');?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

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

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
                                                                    $(".js-example-basic-multiple").select2();
                                                                    $(function () {
                                                                        $("#expity_date").datepicker({
                                                                            changeMonth: true,
                                                                            changeYear: true,
                                                                            dateFormat: 'yy-mm-dd',
                                                                            minDate: 0
                                                                        });
                                                                    });
        </script>

    </body>
</html>
