<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
       
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/lminvoice.app.js"></script>
    </head>

    <body ng-app="lmInvoice">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="createInvoice" > 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  > 
                        <!--style="background-color: red;"-->

                      
                            <div ng-if="message!=undefined" class="alert alert-success">{{message}}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>'

                      
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong><?=lang('lang_Bulk_Create_Invoice');?></strong> </h1>
                                    </div>

                                    <div class="panel-body" ng-if="returnData">
                                    <div  ng-if="returnData.Available.length>0" class="alert alert-success">Available to Create Invoice <button class="float-right" ng-click="downloadexl(returnData.Available,'Available to Create Invoice ')">Download</button></div>

                                    <div ng-if="returnData.destinationIssue.length>0" class="alert alert-info"> Destination Missing for these sellers  
                                    <button class="float-right" ng-click="downloadexl(returnData.destinationIssue,'Destination Missing for these sellers')">><?=lang('lang_Download');?><</button>
                                    </div>

                                    <div ng-if="returnData.belongToOther.length>0"  class="alert alert-waring"> Shipment Belongs to other seller
                                    
                                    <button class="float-right" class="float-right" ng-click="downloadexl(returnData.belongToOther,'Shipment Belongs to other seller')">><?=lang('lang_Download');?><</button>
                                    </div>

                                    <div  ng-if="returnData.statusNotcorrect.length>0" class="alert alert-danger">
                                    Status Incorrect 
                                    <button class="float-right" ng-click="downloadexl(returnData.statusNotcorrect,' Status Incorrect ')">><?=lang('lang_Download');?><</button>
                                    </div>

                                    <div  ng-if="returnData.areadyExit.length>0 && returnData.price_zero.length==0" class="alert alert-danger">
                                    Invoice Already Created
                                    <button class="float-right" ng-click="downloadexl(returnData.areadyExit,'Invoice Already Created')">><?=lang('lang_Download');?><</button>
                                    </div>
                                    <div  ng-if="returnData.price_zero.length>0" class="alert alert-danger">
                                   Shipment Price or zone not set (Shipment Price Zero)
                                    <button class="float-right" ng-click="downloadexl(returnData.price_zero,'Shipment Price or zone not set (Shipment Price Zero)')">><?=lang('lang_Download');?><</button>
                                    </div>

                                    
                                    <input type="submit" name="track_ready" class="btn btn-primary form-control "  ng-click="createInvoice()" value="<?=lang('lang_Create_Invoice');?>">	
                                    </div>
                                    <div class="panel-body" ng-if="returnData==undefined">
                                        <div class="row"> </div>
                                        <!-- <div class="alert alert-danger"><?=lang('lang_Note');?> Note Order Limit is 200</div> -->
                                        <!-- <form  method="post" action="<?= base_url(); ?>lastmile/checkInvoice"  > -->
                                            
                                        <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong> <br>
                                                        <select  id="seller" name="seller"  ng-model="filterData.seller"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_SelectSeller');?></option>
                                                            <?php foreach ($sellers as $seller_detail): ?>
                                                                <option value="<?= $seller_detail->id; ?>">
                                                                    <?= $seller_detail->company; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div></div>
                                            <div class="col-md-12" ng-if="filterData.seller">
                                                <div class="form-group">
                                                    <textarea rows="8" id="show_awb_no" name="show_awb_no"   ng-change="countData()" ng-model="filterData.slip_no"  placeholder="<?=lang('lang_Please_Enter_Your_AWB_Number');?>" required class="form-control"></textarea>
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-1"  ng-if="filterData.seller">
                                                <div class="form-group">

                                                    <span  id="rowcount" class="btn btn-danger  btn-sm" >{{lines}}</span>

                                                </div> 
                                            </div> 
                                           
                                            <div class="col-md-2"  ng-if="filterData.seller">
                                            <div class="form-group">

                                            <input type="button" ng-click="checkIncvoice()"  name="track_ready" class="btn btn-primary form-control" value="<?=lang('lang_Check_Invoice');?>">	

                                            </div> 
                                            </div>
                                            
                                            
                                          
                                        <!-- </form> -->
                                    </div>


                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /dashboard content --> 

                <!-- /basic responsive table --> 

            </div>
            <!-- /content area --> 
        </div>
        <?php $this->load->view('include/footer'); ?>
        
        <script type="text/javascript">
             $(".checkdisable").attr("disabled", true);
 $("#show_awb_no").on("change input paste keyup", function() {
	// alert("ssssss");
   //$("#res").html(jQuery(this).val());
 var value = $("#show_awb_no").val();   
    var items = value.split('\n');
    var lines = 0;
    for(var no=0;no<items.length;no++){
        lines += Math.ceil(items[no].length/40);    }
        document.getElementById('rowcount').innerHTML =lines;
    $(".checkdisable").attr("disabled", false);
if(parseInt(lines)>200)
{
  
}
else
{
  //$(".checkdisable").attr("disabled", false);
}
});


</script>

        <!-- /page container -->

    </body>
</html>
