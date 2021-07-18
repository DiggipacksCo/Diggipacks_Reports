<!-- Main sidebar -->
<?php $color = Getsite_configData_field('theme_color_fm'); ?>
<style>
    .sidebar-main{
        background-color:<?php echo $color; ?> !important;
    }
</style>
<div class="sidebar sidebar-main" style="background-color:#0B70CD;">
    <div class="sidebar-content"> 



        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Main -->
                    <li class="navigation-header"><span><?= lang('lang_Main'); ?>    
                        </span> <i class="icon-menu" title="Main pages"></i></li>
                    <li  <?php if ($this->uri->segment(1) == 'Home' || $this->uri->segment(1) == '') echo 'class="active"'; ?>>
                        <a href="<?= base_url('Home'); ?>"><i class="icon-home4"></i> <span><?= lang('lang_Home'); ?> </span></a>
                    </li>
                    <?php if (menuIdExitsInPrivilageArray(68) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'CompanyDetails') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-cogs"></i> <span><?= lang('lang_General_Setting'); ?></span></a>
                            <ul>
                                <li><a href="<?= base_url('CompanyDetails');?>"><?= lang('lang_company_details'); ?></a></li>
                                <li><a href="<?= base_url('defaultlist_view');?>"><?= lang('lang_Default_courier_company'); ?></a></li>
                                <li><a href="<?= base_url('smsconfigration');?>"><?= lang('lang_SMS_Configuration'); ?></a></li>
                               
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray(1) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Shipment' || $this->uri->segment(1) == 'bulk_update_view' || $this->uri->segment(1) == 'TrackingResult' || $this->uri->segment(1) == 'TrackingDetails' || $this->uri->segment(1) == 'Forward_Delivery_Station' || $this->uri->segment(1) == 'bulkprint' || $this->uri->segment(1) == 'forwardshipments' || $this->uri->segment(1) == 'forwardedshipments' || $this->uri->segment(1) == 'bulk_tracking') echo 'class="active"'; ?>> <a href="#"><i class="icon-bus"></i> <span><?= lang('lang_Shipment_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(29) == 'Y') { ?>

                                    <li><a href="<?= base_url('Shipment'); ?>"><?= lang('lang_All_Orders'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(81) == 'Y') { ?>
                                    <li><a href="<?= base_url('Forward_Delivery_Station'); ?>"><?= lang('lang_Bulk_Forward3PL'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(101) == 'Y') { ?>
                                    <li><a href="<?= base_url('forwardshipments'); ?>"><?= lang('lang_Manual_Forward3PL'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(141) == 'Y') { ?>
                                    <li><a href="<?= base_url('cancelOrder'); ?>"><?= lang('lang_Cancelling_orders3PL'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(82) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulkprint'); ?>"><?= lang('lang_Bulk_Print'); ?></a></li>
                                <?php } ?>
                                     <?php if (menuIdExitsInPrivilageArray(137) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulk_tracking'); ?>"><?= lang('lang_bulk_track'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(137) == 'Y') { ?>
                                    <li><a href="<?= base_url('remove_forward'); ?>"><?= lang('lang_remove_forwarding'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray(81) == 'Y') { ?>
                                    <li><a href="<?= base_url('Reverse_Delivery_Station'); ?>"><?= lang('lang_Reverse_Shipment'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(81) == 'Y') { ?>
                                    <li><a href="<?= base_url('Reverse_Shipment'); ?>"><?= lang('lang_View_Reverse_Shipment'); ?></a></li>
                                <?php } ?>
                                    <li><a href="<?= base_url('shipment_mapping'); ?>">Shipment Mapping</a></li>
                                <?php if (menuIdExitsInPrivilageArray(30) == 'Y') { ?>

                                    <li class="navigation-divider"></li>
                                    <li><a href="<?= base_url('bulk_update_view'); ?>"><?= lang('lang_Bulk_Update'); ?><span class="label bg-warning-400"><?= lang('lang_Update'); ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>


                    <?php if (menuIdExitsInPrivilageArray(2) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'ItemInventory'  || $this->uri->segment(1) == 'add_shelve' || $this->uri->segment(1) == 'view_shelve' || $this->uri->segment(1) == 'shelve_sku' || $this->uri->segment(1) == 'historyview' || $this->uri->segment(1) == 'skuTransfer' || $this->uri->segment(1) == 'skuTransferedList' || $this->uri->segment(1) == 'inventory_check' ) echo 'class="active"'; ?>> <a href="#"><i class="icon-copy"></i> <span><?= lang('lang_Inventory_Management'); ?></span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray(31) == 'Y') { ?>
                                    <li><a href="<?= base_url('ItemInventory/add_view'); ?>"> <?= lang('lang_Add_Item_Inventory'); ?></a></li>
                                <?php } ?>
                              
                                <?php if (menuIdExitsInPrivilageArray(33) == 'Y') { ?>
                                    <li><a href="<?= base_url('ItemInventory'); ?>"><?= lang('lang_View_Item_Inventory'); ?></a></li>
                                <?php } ?>
                               

                                <?php if (menuIdExitsInPrivilageArray(34) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_shelve'); ?>"><?= lang('lang_Add_Shelve'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(35) == 'Y') { ?>
                                    <li><a href="<?= base_url('view_shelve'); ?>"><?= lang('lang_View_Pallet'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(36) == 'Y') { ?>
                                    <li><a href="<?= base_url('shelve_sku'); ?>"><?= lang('lang_Update_Pallet'); ?></a></li>
                                <?php } ?>
                               
                                <?php if (menuIdExitsInPrivilageArray(74) == 'Y') { ?>
        <!--                                <li><a href="<?= base_url('skuTransfer'); ?>">Stock Transfer</a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray(74) == 'Y') { ?>
                                    <li><a href="<?= base_url('skuTransferedList'); ?>">Stock Tranfer List</a></li>-->
                                <?php } ?>
                                
                                     <?php if (menuIdExitsInPrivilageArray(119) == 'Y') { ?>
                                    <li><a href="<?= base_url('inventory_check'); ?>"><?= lang('lang_Inventory_Per_Client'); ?></a></li>
                                     <?php } ?>
                                   
                                <?php if (menuIdExitsInPrivilageArray(40) == 'Y') { ?>
                                    <li class="navigation-divider"></li>
                                    <li><a href="<?= base_url('ItemInventory/add_bulk_view'); ?>"><?= lang('lang_Upload_Inventory'); ?><span class="label bg-warning-400"><?= lang('lang_Add'); ?> </span></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray(2) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'AddofferGift' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(1) == 'generateStockLocation' || $this->uri->segment(1) == 'showStock' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'edit_offer_gift' ) echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span> <?= lang('lang_Stock_Location_management'); ?> </span></a>
                            <ul>

                            <?php if (menuIdExitsInPrivilageArray(37) == 'Y') { ?>
                                    <li><a href="<?= base_url('generateStockLocation'); ?>"><?= lang('lang_Generate_Stock_Location'); ?></a></li>
                                <?php } ?>
                                    
                                    
                                    
                                    
                                    
                                <?php if (menuIdExitsInPrivilageArray(38) == 'Y') { ?>
                                    <li><a href="<?= base_url('showStock'); ?>"> <?= lang('lang_All_Stock_Location'); ?></a></li>
                                <?php } ?>
                                    <?php if (menuIdExitsInPrivilageArray(38) == 'Y') { ?>
                                    <li><a href="<?= base_url('showStock/AS'); ?>"> <?= lang('lang_Assigned_Stock_Location'); ?></a></li>
                                <?php } ?>
                                    <?php if (menuIdExitsInPrivilageArray(38) == 'Y') { ?>
                                    <li><a href="<?= base_url('showStock/UN'); ?>"> <?= lang('lang_Unassigned_Stock_Location'); ?></a></li>
                                <?php } ?>


                            </ul>
                        </li>
                    <?php /**/
                    } ?>



                  
                        <?php if (menuIdExitsInPrivilageArray(3) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Item') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Item_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(41) == 'Y') { ?>
                                    <li><a href="<?= base_url('Item/add_view'); ?>"><?= lang('lang_Add_Item'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(42) == 'Y') { ?>
                                    <li><a href="<?= base_url('Item'); ?>"><?= lang('lang_View_All_Items'); ?></a></li>
                                <?php } ?>
                                    
                                       <?php if (menuIdExitsInPrivilageArray(42) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulk_print_barcode'); ?>"> <?= lang('lang_Bulk_sku_print'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(43) == 'Y') { ?>
                                    <li class="navigation-divider"></li>
                                    <li><a href="<?= base_url('Item/add_bulk_view'); ?>"><?= lang('lang_Import_Items'); ?><span class="label bg-warning-400"><?= lang('lang_Add'); ?> </span></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(43) == 'Y') { ?>
                                    <li class="navigation-divider"></li>
                                    <li><a href="<?= base_url('Item/add_bulk_weight_view'); ?>"><?= lang('lang_Import_bulk_weight'); ?> <span class="label bg-warning-400">  <?= lang('lang_NEW'); ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                        
                         <?php if (menuIdExitsInPrivilageArray(134) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'add_vehicle' || $this->uri->segment(1) == 'vehicle_list') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Vehicle_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(135) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_vehicle'); ?>"><?= lang('lang_Add_Vehicle'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(136) == 'Y') { ?>
                                    <li><a href="<?= base_url('vehicle_list'); ?>"><?= lang('lang_Vehicle_List'); ?></a></li>
                                <?php } ?>
                                    
                               
                            </ul>
                        </li>
                    <?php } ?>
                        
                          <!-- <?php if (menuIdExitsInPrivilageArray(125) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'showtods' || $this->uri->segment(1) == 'GenerateTods') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span>Tod Management</span></a>
                            <ul>
                               
                                     <?php if (menuIdExitsInPrivilageArray(127) == 'Y') { ?>
                                    <li><a href="<?= base_url('showtods'); ?>">Show Tod</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?> -->
                        
                         <?php if (menuIdExitsInPrivilageArray(128) == 'Y') 
                         { ?>
                        
                        <li  <?php if ($this->uri->segment(1) == 'damage_list' || $this->uri->segment(1) == 'return_manifest') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-truck"></i> <span><?= lang('lang_Return_Manifest'); ?></span></a>   
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(129) == 'Y')
                                { ?>

                                    <li><a href="<?= base_url('damage_list'); ?>"><?= lang('lang_Show_Damage_Item'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(130) == 'Y')
                                { ?>
                                    <li><a href="<?= base_url('return_manifest'); ?>"><?= lang('lang_Return_Order_List'); ?></a></li>
                                <?php } ?>
                            </ul> 
                        </li>
                    <?php } ?>
                        
                    <?php if (menuIdExitsInPrivilageArray(77) == 'Y') { ?>

                        <li <?php if ($this->uri->segment(1) == 'GenerateTods' || $this->uri->segment(1) == 'showtods' || $this->uri->segment(1) == 'addWarehouse'|| $this->uri->segment(1) =='viewWarehouse') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Warehouse_Management'); ?></span></a>
                        
                            <ul>
                             <?php if (menuIdExitsInPrivilageArray(126) == 'Y') { ?>
                                    <li><a href="<?= base_url('GenerateTods'); ?>"><?= lang('lang_Generate_Tods'); ?> </a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(127) == 'Y') { ?>
                                    <li><a href="<?= base_url('showtods'); ?>"><?= lang('lang_Show_Tod'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(84) == 'Y') { ?>

                                    <li><a href="<?= base_url('addWarehouse'); ?>"><?= lang('lang_Add_Warehouse'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(85) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewWarehouse'); ?>"><?= lang('lang_View_Warehouse'); ?></a></li>
                                <?php } ?>
                            </ul> 
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray(17) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'shownewmanifestRequest' || $this->uri->segment(1) == 'showpickuplist' || $this->uri->segment(1) == 'showmenifest' || $this->uri->segment(1) == 'manifestview' || $this->uri->segment(1) == 'showTicket' || $this->uri->segment(1) =='show_assignedlist') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Manifest_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(49) == 'Y') { ?>
                                    <li><a href="<?= base_url('shownewmanifestRequest'); ?>"><?= lang('lang_New_Manifest'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(50) == 'Y') { ?>
                                    <li><a href="<?= base_url('showpickuplist'); ?>"><?= lang('lang_Pickup_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(51) == 'Y') { ?>
                                    <li><a href="<?= base_url('showmenifest'); ?>"><?= lang('lang_Manifest_List'); ?></a></li>
                                <?php } ?>
                                    
                                       <?php if (menuIdExitsInPrivilageArray(51) == 'Y') { ?>
                                    <li><a href="<?= base_url('show_assignedlist'); ?>"><?= lang('lang_Show_Assigned_List'); ?></a></li>
                                <?php } ?>
                                    
                                    
                                <!-- <?php if (menuIdExitsInPrivilageArray(52) == 'Y') { ?>
                                    <li><a href="<?= base_url('showTicket'); ?>">Manifest Ticket</a></li>
                                <?php } ?> -->
                            </ul>
                        </li>
                        <?php
                        /**/
                    }
                    ?>
<?php if (menuIdExitsInPrivilageArray(18) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'showTicketview') echo 'class="active"'; ?>> <a href="#"><i class="icon-ticket"></i> <span><?= lang('lang_Ticket_Management'); ?></span></a>
                            <ul>
                                <li><a href="<?= base_url('showTicketview'); ?>"><?= lang('lang_All_List'); ?></a></li>
                                <li><a href="<?= base_url('showTicket'); ?>"><?= lang('lang_Manifest_Ticket'); ?></a></li>
                            </ul>
                        </li>
                    <?php } ?>
<?php if (menuIdExitsInPrivilageArray(19) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'offerslist' || $this->uri->segment(2) == 'OfferOrders' || $this->uri->segment(2) == 'Addoffers' || $this->uri->segment(1) == 'OfferOrders') echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span> <?= lang('lang_Offer_Management'); ?> </span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray(86) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/offerslist'); ?>"><?= lang('lang_Bundel_Offers_List'); ?></a></li>
                                <?php } ?>  
                                <?php if (menuIdExitsInPrivilageArray(87) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/Addoffers'); ?>"><?= lang('lang_Add_New_Bundle_Offers'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(88) == 'Y') { ?>
                                    <li><a href="<?= base_url('OfferOrders_gift'); ?>"><?= lang('lang_Bundle_Orders_List'); ?></a></li>
    <?php } ?>

                           <?php if (menuIdExitsInPrivilageArray(86) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/giftOffersList'); ?>"><?= lang('lang_Gift_Offers_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(87) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/AddofferGift'); ?>"><?= lang('lang_Add_New_Gift_Offers'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(88) == 'Y') { ?>
                                    <li><a href="<?= base_url('OfferOrders'); ?>"><?= lang('lang_Gift_Order_List'); ?></a></li>
                            <?php } ?>



                            </ul>
                        </li>
                    <?php /**/
                    } ?>
                        
                        <!-- <?php if (menuIdExitsInPrivilageArray(19) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'AddofferGift' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'edit_offer_gift' ) echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span> Gift Offer Management </span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray(86) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/giftOffersList'); ?>"><?= lang('lang_Offers_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(87) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/AddofferGift'); ?>"><?= lang('lang_Add_New_Offers'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(88) == 'Y') { ?>
                                    <li><a href="<?= base_url('OfferOrders'); ?>"><?= lang('lang_Orders_List'); ?></a></li>
    <?php } ?>


                            </ul>
                        </li>
                    <?php /**/
                    } ?> -->

<?php if (menuIdExitsInPrivilageArray(138) == 'Y') { ?>
                    
                        <li <?php if ($this->uri->segment(2) == 'AddofferGift' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'edit_offer_gift'|| $this->uri->segment(1) == 'picker_setting' ) echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span>   <?= lang('lang_Pickers_Management'); ?></span></a>
                            <ul>

                            <?php if (menuIdExitsInPrivilageArray(98) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'pickedSingleView') echo 'class="active"'; ?>> <a href="<?= base_url('pickedSingleView'); ?>"></i> <span> <?= lang('lang_Single_Pickup_List'); ?></span></a> </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray(99) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'pickedBatchView') echo 'class="active"'; ?>> <a href="<?= base_url('pickedBatchView'); ?>"></i> <span><?= lang('lang_Batch_Pickup_List'); ?></span></a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray(100) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'pickedcompletedView') echo 'class="active"'; ?>> <a href="<?= base_url('pickedcompletedView'); ?>"></i> <span><?= lang('lang_pickup_completed_list'); ?></span></a> </li>
                    <?php } ?> 

                    <?php if (menuIdExitsInPrivilageArray(139) == 'Y') { ?>
                                    <!-- <li><a href="<?= base_url(); ?>users" id="layout2"><?= lang('lang_View_All_Users'); ?></a></li> -->
                                     <li><a href="<?= base_url(); ?>picker_setting" id="layout2"><?= lang('lang_Picker_Settings'); ?></a></li>
                        <?php } ?>


                            </ul>
                        </li>
                    <?php /**/
                    } ?>

                      <?php if (menuIdExitsInPrivilageArray(9) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'AddofferGift' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'edit_offer_gift' || $this->uri->segment(2) == 'packing_b2b' ) echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span> <?= lang('lang_Packaging_Management'); ?> </span></a>
                            <ul>

                            <!-- <?php if (menuIdExitsInPrivilageArray(9) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'packing_report') echo 'class="active"'; ?>> <a href="<?= base_url('packing_report'); ?>"></i> <span><?= lang('lang_Packaging_Report'); ?></span></a> </li>
                    <?php } ?> -->
                        
                         <?php if (menuIdExitsInPrivilageArray(9) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'packing') echo 'class="active"'; ?>> <a href="<?= base_url('packing'); ?>"></i> <span><?= lang('lang_Packaging'); ?></span></a> </li>
                    <?php } ?>
                        
                         <?php if (menuIdExitsInPrivilageArray(9) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'packing_b2b') echo 'class="active"'; ?>> <a href="<?= base_url('packing_b2b'); ?>"></i> <span><?= lang('lang_Packaging'); ?> B2B</span></a> </li>
                    <?php } ?>
                        
                         <?php if (menuIdExitsInPrivilageArray(9) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'packing_tod') echo 'class="active"'; ?>> <a href="<?= base_url('packing_tod'); ?>"></i> <span><?= lang('lang_Packaging_With_Tod'); ?></span></a> </li>
                    <?php } ?>

                            </ul>
                        </li>
                    <?php /**/
                    } ?>


                 <?php if (menuIdExitsInPrivilageArray(10) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'AddofferGift' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'edit_offer_gift' ) echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span>  <?= lang('lang_Dispatching_Management'); ?></span></a>
                            <ul>

                            <?php if (menuIdExitsInPrivilageArray(10) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'dispatch') echo 'class="active"'; ?>> <a href="<?= base_url('dispatch'); ?>"></i> <span><?= lang('lang_Dispatching'); ?></span></a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray(10) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'dispatch_b2b') echo 'class="active"'; ?>> <a href="<?= base_url('dispatch_b2b'); ?>"></i> <span><?= lang('lang_Dispatching'); ?> B2B</span></a> </li>
                    <?php } ?>
                         <?php if (menuIdExitsInPrivilageArray(10) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'dispatch3pl') echo 'class="active"'; ?>> <a href="<?= base_url('dispatch3pl'); ?>"></i> <span> <?= lang('lang_Dispatch_To_tpl'); ?></span></a> </li>
                    <?php } ?>
                        


                            </ul>
                        </li>
                    <?php /**/
                    } ?>
                   

                    
                  
                        

                    <?php if (menuIdExitsInPrivilageArray(78) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'returnLM') echo 'class="active"'; ?>> <a href="<?= base_url('returnLM'); ?>"><i class="fa fa-arrow-left"></i> <span>RTF</span></a> </li>
                    <?php } ?>


                            <?php if (menuIdExitsInPrivilageArray(4) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Seller') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-industry"></i> <span><?= lang('lang_Seller_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(44) == 'Y') { ?>
                                    <li><a href="<?= base_url('Seller/add_view'); ?>" id="layout1"><?= lang('lang_Add_Seller'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(45) == 'Y') { ?>
                                    <li><a href="<?= base_url('Seller'); ?>" id="layout2"><?= lang('lang_view_All_sellers'); ?></a></li>
                        <?php } ?>
                        <li><a href="<?= base_url('addZoneCustomer'); ?>" id="layout2"><?= lang('lang_Add_Seller_Zone'); ?> </a></li>
                        <li><a href="<?= base_url('viewZoneCustomer'); ?>" id="layout2"><?= lang('lang_View_Seller_Zone'); ?></a></li>
                            </ul>
                        </li>
<?php } ?>
                            <?php if (menuIdExitsInPrivilageArray(5) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'add-new-user' || $this->uri->segment(1) == 'users' || $this->uri->segment(1) == 'update-user' || $this->uri->segment(1) == 'user-privilege' || $this->uri->segment(1) == 'user-details' ) echo 'class="active"'; ?>> <a href="#"><i class="icon-users"></i> <span><?= lang('lang_Users_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(47) == 'Y') { ?>
                                    <li><a href="<?= base_url(); ?>users" id="layout2"><?= lang('lang_View_All_Users'); ?></a></li>
                                    <li><a href="<?= base_url(); ?>add-new-user" id="layout1"><?= lang('lang_Add_User'); ?></a></li>
                                <?php } ?>
                               
                                    
                                    
                            </ul>
                        </li>
                    <?php } ?>

                            <!-- <?php if (menuIdExitsInPrivilageArray(20) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'add_storage' || $this->uri->segment(1) == 'view_storage' || $this->uri->segment(1) == 'setStorageRate') echo 'class="active"'; ?>> <a href="#"><i class="icon-copy"></i> <span><?= lang('lang_Storage_Management'); ?></span></a>
                            <ul> -->
                               <!--  <?php if (menuIdExitsInPrivilageArray(53) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_storage'); ?>"><?= lang('lang_Add_Storage_Type'); ?></a></li>
                                <?php } ?> -->
                               
                               <!--  <?php if (menuIdExitsInPrivilageArray(55) == 'Y') { ?>
                                    <li><a href="<?= base_url('setStorageRate'); ?>"><?= lang('lang_SetStorage_Rate'); ?></a></li>
                        <?php } ?> -->
                            <!-- </ul>
                        </li>
<?php } ?> -->
                            <?php if (menuIdExitsInPrivilageArray(21) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'addfinancecategory' || $this->uri->segment(1) == 'viewfinancecategory' || $this->uri->segment(1) == 'viewfixrateCharges' || $this->uri->segment(1) == 'sellerCharges' || $this->uri->segment(1) == 'storageInvoices' || $this->uri->segment(1) == 'PickupchargesInvocie' || $this->uri->segment(1) == 'ordersinvoiceView' || $this->uri->segment(1) == 'transaction_report' || $this->uri->segment(1) == 'editcatview' || $this->uri->segment(1) == 'invoices_dynamic' || $this->uri->segment(1) == 'newinvoicesView') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-money"></i> <span><?= lang('lang_Finance_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(56) == 'Y') { ?>
                                  <!-- <li><a href="<?= base_url('addfinancecategory'); ?>">Add Category</a></li>-->
                                <?php } ?>

                                 <?php if (menuIdExitsInPrivilageArray(57) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewfixrateCharges'); ?>"><?= lang('lang_Set_Fixed_Rate_Charges'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(58) == 'Y') { ?>
                                    <li><a href="<?= base_url('sellerCharges'); ?>"><?= lang('lang_Set_Dynamic_Rate_Charges'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray(57) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewfinancecategory'); ?>"><?= lang('lang_All_Category'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(59) == 'Y') { ?>
                                    <li><a href="<?= base_url('storageInvoices'); ?>"><?= lang('lang_StorageChargesInvoices'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(60) == 'Y') { ?>
                                    <!-- <li><a href="<?= base_url('PickupchargesInvocie'); ?>">Pickup Charges Invoice</a></li>-->
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(60) == 'Y') { ?>
                                    <li><a href="<?= base_url('ordersinvoiceView'); ?>"><?= lang('lang_All_Invoices'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(61) == 'Y') { ?>
                                    <li><a href="<?= base_url('transaction_report'); ?>"><?= lang('lang_Transaction_Report'); ?></a></li> 
                                <?php } ?>
                                 <?php if (menuIdExitsInPrivilageArray(61) == 'Y') { ?>
                                    <li><a href="<?= base_url('newinvoicesView'); ?>"><?= lang('lang_Fix_Rate_Invoice'); ?></a></li>
                                <?php } ?> 

                                <?php if (menuIdExitsInPrivilageArray(61) == 'Y') { ?>
                                    <li><a href="<?= base_url('invoices_dynamic'); ?>"><?= lang('lang_Dynamic_Invoice'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(54) == 'Y') { ?>
                                    <li><a href="<?= base_url('view_storage'); ?>"><?= lang('lang_All_Storage_Types'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(141) == 'Y')
                                    { ?>
                                    <li><a href="<?= base_url('cancelOrder'); ?>"><?= lang('lang_Cancel_Order'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray(54) == 'Y') { ?>
                                    <li><a href="<?= base_url('createInvoice'); ?>"><?= lang('lang_Create_Lm_Invoice'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(54) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewLmInvoice'); ?>"><?= lang('lang_View_Lm_Invoice'); ?></a></li>
                                <?php } ?>

                            </ul>
                        </li>
<?php } ?>

                   
                          
                        
                        <?php if (menuIdExitsInPrivilageArray(131) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'show_route' || $this->uri->segment(1) == 'add_route') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-map"></i> <span><?= lang('lang_Route_Management'); ?> </span></a>
                            <ul>

                              <?php if (menuIdExitsInPrivilageArray(132) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_route'); ?>"><?= lang('lang_Add_Route'); ?></a></li>
                                    <?php } ?>
                              
    <?php if (menuIdExitsInPrivilageArray(133) == 'Y') { ?>
                                    <li><a href="<?= base_url('show_route'); ?>"><?= lang('lang_Show_Route'); ?></a></li>
                                    <?php } ?>
   

                      
                                   
                      


                            </ul>
                        </li>
                        <?php } ?>
                        <?php if (menuIdExitsInPrivilageArray(23) == 'Y') { ?>
                        <li <?php
                    if ($this->uri->segment(1) == "Courier" || $this->uri->segment(1) == "addZone" || $this->uri->segment(1) == "viewZone") {
                        echo 'class="active"';
                    }
                    ?>> 
                            <a href="#"><i class="fa fa-truck"></i> <span><?= lang('lang_Courier_Service_Management'); ?></span></a>
                            <ul>
                                <li <?php
                    if ($this->uri->segment(1) == "viewCourierCompany") {
                        echo 'class="active"';
                    }
                        ?>>
                                    <a href="<?= base_url('viewCourierCompany'); ?>" ><?= lang('lang_View_Courier_Company'); ?></a></li>
                                
                                
                                <li>  <a href="<?= base_url('addZone'); ?>" ><?= lang('lang_Add_Zone'); ?></a></li>
                                <li> <a href="<?= base_url('viewZone'); ?>" ><?= lang('lang_View_Zone'); ?></a></li>
                                <li><a href="<?= base_url('ShipmentLogview');?>"><?= lang('lang_Shipment_Log'); ?></a></li>
                                

                            </ul>
                        </li>
                            <?php } ?>
                        <?php if (menuIdExitsInPrivilageArray(79) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Country') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-map"></i> <span><?= lang('lang_Location'); ?></span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray(89) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/ViewCountrylist'); ?>"><?= lang('lang_Location_List'); ?></a></li>
                                <?php } ?>
    <?php if (menuIdExitsInPrivilageArray(90) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/Importlocations'); ?>"><?= lang('lang_import_location'); ?></a></li>
    <?php } ?>

                        <?php if (menuIdExitsInPrivilageArray(91) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/Importdeliverycity'); ?>"><?= lang('lang_import_delivery_City'); ?></a></li>
                        <?php } ?>
                                    
                                    <?php if (menuIdExitsInPrivilageArray(91) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/Delivery_city_list'); ?>"><?= lang('lang_Delivery_Company_List'); ?></a></li>
                        <?php } ?>
                        <?php if (menuIdExitsInPrivilageArray(91) == 'Y') { ?>
                                    <li><a href="<?= base_url('import_from_master'); ?>"><?= lang('lang_add_from_Master'); ?></a></li>
                        <?php } ?>


                            </ul>
                        </li>
                    <?php } ?>

                      <?php if (menuIdExitsInPrivilageArray(108) == 'Y') { ?>
                        <li> <a href="#"><i class="icon-users"></i> <span><?= lang('lang_FAQ'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(111) == 'Y') { ?>
                                <li><a href="<?= base_url(); ?>add_faq" id="layout1"><?= lang('lang_Add_FAQ'); ?></a></li>
                                 <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(112) == 'Y') { ?>
                                <li><a href="<?= base_url(); ?>show_faq" id="layout2"><?= lang('lang_Show_FAQ'); ?></a></li>  
                                 <?php } ?>
                            </ul>
                        </li>  
                <?php } ?>
                        
                        <?php if (menuIdExitsInPrivilageArray(24) == 'Y') { ?>

                        <li>
                            <a href="javascript: void(0);">
                                <i class="fa fa-envelope-open"></i>
                                <span><?= lang('lang_Sms_Management'); ?></span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">

                                <?php if (menuIdExitsInPrivilageArray(100) == 'Y') { ?>
                                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('add_template'); ?>"><?= lang('lang_Add_Template'); ?></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(100) == 'Y') { ?>
                                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('show_template'); ?>"><?= lang('lang_Show_Template'); ?></a> </li>
                                    <?php } ?>


                            </ul>
                        </li>
                    <?php } ?>
                         <?php if (menuIdExitsInPrivilageArray(109) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'shelve_report' || $this->uri->segment(1) == 'topdispatchproduct' || $this->uri->segment(1) == 'performance' || $this->uri->segment(1) == 'client_report' || $this->uri->segment(1) == 'dispatching_report'|| $this->uri->segment(1) == 'InboundRecord' || $this->uri->segment(1) == 'report_3pl') echo 'class="active"'; ?>>
                            <a href="javascript: void(0);">
                                <i class="icon-copy"></i>
                                <span><?= lang('lang_Reports_Management'); ?></span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">

                              <?php if (menuIdExitsInPrivilageArray(113) == 'Y') { ?>
                              
                                     <li><a href="<?= base_url('shelve_report'); ?>"><?= lang('lang_Shelve_Report'); ?></a></li>
                                      <?php } ?>
                                     <?php if (menuIdExitsInPrivilageArray(114) == 'Y') { ?>
                                      <li><a href="<?= base_url('topdispatchproduct'); ?>"><?= lang('lang_Top_Product_Dispatch'); ?></a></li>
                                       <?php } ?>
                                      <?php if (menuIdExitsInPrivilageArray(115) == 'Y') { ?>
                                      <li> <a href="<?= base_url('performance'); ?>" ><?= lang('lang_OFD_Report'); ?></a></li>
                                       <?php } ?>
                                      <?php if (menuIdExitsInPrivilageArray(116) == 'Y') { ?>
                                      <li> <a href="<?= base_url('Staff_report'); ?>" ><?= lang('lang_Staff_performance'); ?></a></li>
                                       <?php } ?>
                                      <?php if (menuIdExitsInPrivilageArray(117) == 'Y') { ?>
                                      <li> <a href="<?= base_url('dispatching_report'); ?>" > <?= lang('lang_Dispatching_Report'); ?></a></li>
                                       <?php } ?>
                                      <?php if (menuIdExitsInPrivilageArray(118) == 'Y') { ?>
                                      <li> <a href="<?= base_url('report_3pl'); ?>" ><?= lang('lang_TPL_Report'); ?></a></li>
                                       <?php } ?>
                                       <?php if (menuIdExitsInPrivilageArray(9) == 'Y') { ?>
                                       <li <?php if ($this->uri->segment(1) == 'packing_report') echo 'class="active"'; ?>> <a href="<?= base_url('packing_report'); ?>"></i> <span><?= lang('lang_Packaging_Report'); ?></span></a> </li>
                                        <?php } ?>

                                       <?php if (menuIdExitsInPrivilageArray(83) == 'Y') { ?>
                                    <li><a href="<?= base_url('ItemInventory/ViewTotalInventory'); ?>"><?= lang('lang_Item_Inventory_total'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray(32) == 'Y') { ?>
                                    <li><a href="<?= base_url('InboundRecord'); ?>"><?= lang('lang_Inbound_Record'); ?></a></li>
                                <?php } ?>
                                 <?php if (menuIdExitsInPrivilageArray(76) == 'Y') { ?>
                                    <li><a href="<?= base_url('ItemInventory/historyview'); ?>"><?= lang('lang_Inventory_History'); ?></a></li>
                                <?php } ?>
                                      


                            </ul>
                        </li>
                         <?php } ?>
                         <?php if (menuIdExitsInPrivilageArray(110) == 'Y') { ?>
                         <li  <?php if ($this->uri->segment(1) == 'add_access_template' || $this->uri->segment(1) == 'show_access_template' ) echo 'class="active"'; ?>>
                            <a href="javascript: void(0);">
                               <i class="fa fa-star"></i>
                                <span> <?= lang('lang_Access_Management'); ?></span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">

                               <?php if (menuIdExitsInPrivilageArray(120) == 'Y') { ?>
                                     <li><a href="<?= base_url('add_access_template'); ?>"><?= lang('lang_New_Template'); ?></a></li>
                                      <?php } ?>
                                      <?php if (menuIdExitsInPrivilageArray(121) == 'Y') { ?>
                                      <li><a href="<?= base_url('show_access_template'); ?>"><?= lang('lang_Show_Template'); ?></a></li>
                                       <?php } ?>
                                    


                            </ul>
                        </li>
                         <?php } ?>
                         <li><a href="<?= base_url('Country/cityList'); ?>"><i class="fa fa-arrow-left"></i> <span><?= lang('lang_City_List'); ?></span></a>    </li>
                        
                        
                    

                    <?php if (menuIdExitsInPrivilageArray(80) == 'Y') { ?>
                        <li > <a type="button" href="<?= base_url(); ?>ordergenerated" class="btn btn-warning" style="margin-left: 2%; margin-right: 3%"><?= lang('lang_OrderGenerated'); ?> <span class="badge"><?php echo statusCount(11); ?></span></a> </li>


                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray(13) == 'Y') { ?>
                        <li > <a type="button" href="<?= base_url(); ?>orderCreated" class="btn btn-primary" style="margin-left: 2%; margin-right: 3%"><?= lang('lang_OrderCreated'); ?> <span class="badge"><?php echo statusCount(1); ?></span></a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray(14) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>pickupList" class="btn btn-warning" style="margin-left: 2%; margin-right: 3%"><?= lang('lang_Pick_List'); ?> <span class="badge"><?php echo statusCount(2); ?></span></a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray(15) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>packed" class="btn btn-info" style="margin-left: 2%; margin-right: 3%"><?= lang('lang_Packed'); ?> <span class="badge"><?php echo statusCount(4); ?></span></a> </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray(16) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>dispatched" class="btn btn-success" style=" margin-left: 2%; margin-right: 3%; "><?= lang('lang_Dispatched'); ?> <span class="badge"><?php echo statusCount(5); ?></span></a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray(103) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>delivery_manifest" class="btn btn-warning" style=" margin-left: 2%; margin-right: 3%; "><?= lang('lang_delivery_Manifest'); ?> <span class="badge"><?php echo ManifeststatusCount(); ?></span></a> </li>
                            <?php } ?>
<?php if (menuIdExitsInPrivilageArray(104) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>delivered" class="btn btn-success" style=" margin-left: 2%; margin-right: 3%; "><?= lang('lang_Delivered'); ?> <span class="badge"><?php echo statusCount(7); ?></span></a> </li>
<?php } ?>
<?php if (menuIdExitsInPrivilageArray(105) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>returned" class="btn btn-danger" style=" margin-left: 2%; margin-right: 3%; "><?= lang('lang_Returned'); ?> <span class="badge"><?php echo statusCount(8); ?></span></a> </li>
<?php } ?>



                </ul>
            </div>
        </div>
        <!-- /main navigation --> 

    </div>
</div>
<!-- /main sidebar -->