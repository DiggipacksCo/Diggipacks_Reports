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
		
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

    </head>

    <body ng-app="lmInvoice">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"  ng-controller="bulkmanagementCtrl"  > 

            <!-- Page content -->
            <div class="page-content"  ng-init="getPayableCODlist(1,0);GetcustomerData();GetstaffDropData();">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  > 
                        <!--style="background-color: red;"-->

                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>';

                        if ($this->session->flashdata('error'))
                            echo '<div class="alert alert-warning">'.$this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>';
                        ?>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading"dir="ltr">
                                        <h1> <strong><?=lang('lang_LastMile_Invoice_View');?></strong> </h1>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row"> </div>
                                       	
									<div class="row" style="margin-top:20px">  
                                       <div class ="table-responsive">
										  <table class="table ticket-list table-lg dataTable no-footer">
											
											<tbody>
											  <tr style="width:100%">
												<th style="width:35%"> <?=lang('lang_Seller');?>:
												  <select class="select2 select2-multiple form-control" style="word-wrap: break-word;" ng-model="SearArr.cust_id"  multiple="multiple" data-placeholder="Choose">
												 
													<option ng-repeat="cdata in CustomerDropdata" value="{{cdata.id}}">{{cdata.company}}({{cdata.uniqueid}})</option>
												  </select>  
												  <br>
												 <?=lang('lang_Created_By');?>:
												  <select class="select2 select2-multiple form-control" ng-model="SearArr.created" multiple="multiple" data-placeholder="Choose">
													<option ng-repeat="sdata in staffDropdata" value="{{sdata.id}}">{{sdata.name}}</option>
												  </select>
												  <br>
												<?=lang('lang_Cod_Pay_By');?>:
												  <select class="select2 select2-multiple form-control" ng-model="SearArr.paid" multiple="multiple" data-placeholder="Choose">
													<option ng-repeat="sdata in staffDropdata" value="{{sdata.id}}">{{sdata.name}}</option>
												  </select>
												  <br>  
												  <?=lang('lang_Payment_Received_By');?>:
												  <select class="select2 select2-multiple form-control" ng-model="SearArr.received" multiple="multiple" data-placeholder="Choose">
													<option ng-repeat="sdata in staffDropdata" value="{{sdata.id}}">{{sdata.name}}</option>
												  </select>
												<th width="30%"> <?=lang('lang_Invoice_no');?>.: 
												  <input type="text"  ng-model="SearArr.invoices" class="form-control " >
												  
												   <br>
												<?=lang('lang_Payment_Mode');?>:
												  <select class="form-control custom-select  mt-15" ng-model="SearArr.mode">
													<option value=""> <?=lang('lang_select');?> </option>
													<option value="CC"><?=lang('lang_Paid');?></option>
													<option value="COD"><?=lang('lang_COD');?></option>
												  </select>
												  <br>
												 <?=lang('lang_Status');?>:
												  <select class="form-control custom-select  mt-15" ng-model="SearArr.status">
													<option value="">  <?=lang('lang_select');?></option>
													<option value="Delivered"><?=lang('lang_Delivered');?></option>
													<option value="Return to shiper"><?=lang('lang_Return_to_shipper');?></option>
												  </select>
												</th>
												<th style="width:30%"> <?=lang('lang_Created_Date');?><br>
												<?=lang('lang_From_Date');?>
												  <input type="date" name="c_date1"  ng-model="SearArr.c_date1" id="datepicker1" class="form-control" placeholder="dd-mm-yy" >
												  <br>
												  <?=lang('lang_To_Date');?>
												  <input type="date" name="c_date2" ng-model="SearArr.c_date2" id="datepicker2" class="form-control" placeholder="dd-mm-yy">
												</th> 
											  </tr>
											  <tr>
											  
												<th width="10px"> <?=lang('lang_Payment_Date');?><br>
												   <?=lang('lang_From_Date');?>:
												  <input type="date" name="p_date1" ng-model="SearArr.p_date1" id="datepicker3" class="form-control" placeholder="dd-mm-yy" >
</th>
<th>

												   <?=lang('lang_To_Date');?>:
												  <input type="date" name="p_date2" ng-model="SearArr.p_date2" id="datepicker4" class="form-control" placeholder="dd-mm-yy" >
												</th>
												<th width="10px"> <button type="button" class="btn btn-info btn btn-primary" ng-click="getPayableCODlist(1,1);" style="margin-top:80px">Search </button>
												</th>
											  </tr>
											</tbody>      
										  </table>
										</div>   
										<div class ="table-responsive">
										<table class="table ticket-list table-lg dataTable no-footer">
										  <thead>
											<tr>
											  <th><?=lang('lang_Sr_No');?>. </th>
												<th><?=lang('lang_Account_No');?>.</th>
												<th><?=lang('lang_Company_name');?></th>
												 <th> <?=lang('lang_Invoice');?></th>
												<th><?=lang('lang_Summery');?> </th>
												<th> <?=lang('lang_Created_By');?></th>
												 <th> <?=lang('lang_Created_Date');?> </th>
												<th><?=lang('lang_Received_By');?> </th>
												<th> <?=lang('lang_Received');?> <?=lang('lang_Date');?></th>
												<th> <?=lang('lang_Pay_By');?></th>
												<th><?=lang('lang_Pay_Date');?></th>   
												<th>  <?=lang('lang_Receive');?></th>   
												<th>  <?=lang('lang_Pay');?></th>
												<th > <?=lang('lang_Action');?></th>
											</tr>
										  </thead>
										  <tbody>
										  
											  <tr ng-repeat="data in payableinvoicelistArray"> 
											<td>{{$index+1}}</td>
											<td>{{data.uniqueid}}</td>
											<td>{{data.company}}</td>
											<td>{{data.invoice_no}}</td>
											<td><strong><?=lang('lang_Shipment');?></strong>:{{data.invoiceCount}}<br>
												<strong> <?=lang('lang_COD_Charges');?></strong>:{{data.cod_charge_sum}}<br> <strong> <?=lang('lang_Return_Charges');?></strong>:{{data.return_charge_sum}}<br><strong><?=lang('lang_Service_Charge');?></strong>:{{data.service_charge_sum}}
												<br><strong><?=lang('lang_Total_Vat');?></strong>:{{data.vat_sum}}
												<hr><strong><?=lang('lang_COD_Amount');?></strong>:{{data.cod_amount_sum}}
											</td>
											<td>{{data.invoice_created_by}}</td>
											<td>{{data.invoice_created_date}}</td>  
											<td>{{data.receivable_paid_by}}</td>
											<td> {{data.receivable_paid_date}} </td>
											<td> {{data.cod_paid_by}}</td>
											<td> {{data.cod_paid_date}} </td>  
											<td>
											
											<a ng-if="data.receivable_pay_status=='N'" data-toggle="modal" class="btn btn-danger text-white" >  <?=lang('lang_Receive');?></a>	
											  <a ng-if="data.cod_pay_status!='N'" class="btn btn-primary text-white" title="PAY" > <?=lang('lang_Received2129');?></a>	  
											  </td><td>
											 <a ng-if="data.receivable_pay_status=='N'" data-toggle="modal" class="btn btn-danger text-white" >  <?=lang('lang_Pay');?></a>	
											  <a ng-if="data.cod_pay_status!='N'" class="btn btn-primary text-white" title="PAY" > <?=lang('lang_Paid');?></a>	  
											   </td><td>
										

											<ul class="icons-list">
                                                    <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>    

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li><a href="<?= base_url(); ?>codreceivablePrint/{{data.invoice_no}}" target="_blank" ><i class="icon-eye"></i> <?=lang('lang_View');?> </a>
															
															</li>   
                                                            <li>
															<a ng-if="data.receivable_pay_status=='N'" data-toggle="modal" data-target="#updateLinehoulC51201961561904494" title="Received" ng-click="Getpopoprncustdetais(data.pid,'#payable_invoice','one');"><i class="fa fa-money"></i>  <?=lang('lang_Receive');?></a>
											  <a ng-if="data.receivable_pay_status=='Y'"  title="Received"  ng-click="Getpopoprncustdetais(data.pid,'#payable_invoice_list','one');" > <i class="fa fa-money"></i>  <?=lang('lang_Received');?> </a>  
															</li>     
                                                            <li>
															<a ng-if="data.cod_pay_status=='N'" data-toggle="modal" data-target="#updateLinehoulC51201961561904494" title="PAY" ng-click="Getpopoprncustdetais(data.pid,'#account_detail','one');"><i class="fa fa-file"></i>  <?=lang('lang_Pay');?></a>	
											  <a ng-if="data.cod_pay_status!='N'" title="PAY" ng-click="Getpopoprncustdetais(data.pid,'#payable_invoice_list1','one');"> <i class="fa fa-file"></i> <?=lang('lang_Paid');?></a>	
															 </li>  

															 <li>
															<a ng-if="data.discount>0" data-toggle="modal" data-target="#updateLinehoulC51201961561904494" title="DISCOUNT" ng-click="Getpopoprncustdetais(data.pid,'#discounted','one');"><i class="fa fa-tag"></i> Discount</a>	
											  <a ng-if="data.discount<=0" title="DISCOUNT" ng-click="Getpopoprncustdetais(data.pid,'#discount','one');"> <i class="fa fa-tag"></i> Discount</a>	
															 </li> 
															 
															 <li>
															<a ng-if="data.receivable_pay_status=='N'" data-toggle="modal" data-target="#updateLinehoulC51201961561904494" title="Edit Discount" ng-click="Getpopoprncustdetais(data.pid,'#discounted','one');"><i class="fa fa-tag"></i> Edit Discount</a>	
															 </li>   
															 <li>
															 <a ng-if="data.receivable_pay_status=='N'" data-toggle="modal" data-target="#updateLinehoulC51201961561904494" title="Edit Discount" ng-click="Getpopoprncustdetais(data.pid,'#bank_fees_edit','one');"><i class="fa fa-tag"></i> Edit Bank Fees</a>	
															 </li> 
                                                             


                                                        </ul>
                                                    </li>
                                                </ul>
												</td>
										   </tr>
										  </tbody> 
										</table>
									  </div>
                                    </div>


<div class="modal" id="payable_invoice_list" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">   
        <div class="modal-content"> 
<div class="modal-header">
                <h5 class="modal-title"><?=lang('lang_Show_Transaction_Proof_Invoice');?> #({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
            </div>
		
       
      
        
        <div class="modal-body">
				<div class="col-md-4">
					<div class="form-group">
						<label> <?=lang('lang_Transaction_Number');?>. : #({{editcodlistArray.rec_voucher}}) </label>
					</div>
				</div>
                     
				<br>  
               <button style="margin-top: -30px;" type="submit" class="btn btn-info pull-right"  name="update_linehoul" ng-click="modelClose('payable_invoice_list')"><?=lang('lang_Close');?> </button>     
		</div> 
        
       </div>
    </div>
</div>

<div class="modal" id="payable_invoice_list1" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">   
        <div class="modal-content"> 
<div class="modal-header">
                <h5 class="modal-title"  dir="ltr"> <?=lang('lang_Show_Transaction_Proof_Invoice');?> #({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
            </div>
		
       <div class="modal-body">
   		
				
					
					<div class="col-md-4">
                 <div class="form-group">  
                    <label>   <a href="{{editcodlistArray.pay_voucher}}" target="_blank"><?=lang('lang_Invoice_Copy');?></a> </label>   
                
                    </div></div>
                     
				<br>
               
			      <button style="margin-top: -30px;" type="submit" class="btn btn-info pull-right" name="update_linehoul" ng-click="modelClose('payable_invoice_list1')"><?=lang('lang_Close');?> </button>         

					
			    
        </div>
      
        
       </div>
    </div>
</div>

<div class="modal" id="discounted" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">   
        <div class="modal-content"> 
<div class="modal-header">
                <h5 class="modal-title"  dir="ltr"> <?=lang('lang_Discount');?> #({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
            </div>
		
       <div class="modal-body">
   		
				
					
					<div class="col-md-4">
                 <div class="form-group">  
                    <label>   {{editcodlistArray.discount}}</label>   
                
                    </div></div>
                     
				<br>
               
			      <button style="margin-top: -30px;" type="submit" class="btn btn-info pull-right" name="update_linehoul" ng-click="modelClose('discounted')"><?=lang('lang_Close');?> </button>         

					
			    
        </div>
      
        
       </div>
    </div>
</div>



<div class="modal" id="payable_invoice" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"  dir="ltr"> <?=lang('lang_Proof_of_Payment');?>#({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
			<div class="modal-body">
   					<div class="col-md-8">
                 		<div class="form-group">
                   		 <label><?=lang('lang_Transaction_Number');?>.  </label>
                    	<input type="text" name="invoice_no" class="form-control" ng-model="editcodlistArray.rec_voucher" required="">
                    	</div>
                    </div>
             
               
				<button style="margin-top: 30px;" type="submit" class="btn btn-info" name="update_linehoul" ng-click="payableInvoice_update(editcodlistArray);"> <?=lang('lang_Update');?></button>  
					
			   </div>
           
        </div>
    </div>
</div>



<div class="modal" id="discount" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"  dir="ltr"> <?=lang('lang_Discount');?>#({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
			<div class="modal-body">
   					<div class="col-md-8">
					   <form action="<?=base_url();?>LastmileInvoice/discountUpdate" method="post" enctype= 'multipart/form-data'>
                 		<div class="form-group">
						 <input type="hidden" name="invoice_no" value="{{editcodlistArray.invoice_no}}">
							 <input type="hidden" name="cust_id" value="{{editcodlistArray.cust_id}}">
                   		 <label><?=lang('lang_Discount');?>  </label>
                    	<input type="text" name="discount" class="form-control" ng-model="editcodlistArray.discount" required="">
                    	</div>
                    </div>
             
               
				<button style="margin-top: 30px;" type="submit" class="btn btn-info" name="update_linehoul" > <?=lang('lang_Update');?></button>  
					
			   </div>
           </form>
        </div>
    </div>
</div>
<div class="modal" id="bank_fees_edit" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"  dir="ltr">Bank Fees Edit#({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
			<div class="modal-body">
   					<div class="col-md-8">
					   <form action="<?=base_url();?>LastmileInvoice/bankFeesUpdate" method="post" enctype= 'multipart/form-data'>
                 		<div class="form-group">
						 <input type="hidden" name="invoice_no" value="{{editcodlistArray.invoice_no}}">
							 <input type="hidden" name="cust_id" value="{{editcodlistArray.cust_id}}">
                   		 <label>Bank Fees Edit </label>
                    	<input type="text" name="bank_fees" class="form-control" ng-model="editcodlistArray.bank_fees" required="">
                    	</div>
                    </div>
             
               
				<button style="margin-top: 30px;" type="submit" class="btn btn-info" name="update_linehoul" > <?=lang('lang_Update');?></button>  
					
			   </div>
           </form>
        </div>
    </div>
</div>

<div class="modal" id="account_detail" tabindex="-1" role="dialog" aria-labelledby="account_detail" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" dir="ltr">  <?=lang('lang_Proof');?>#({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          <form action="<?=base_url();?>LastmileInvoice/PaymentConfirmUpdaye" method="post" enctype= 'multipart/form-data'>
			<div class="modal-body">
   					<div class="col-md-8">
                 		<div class="form-group">
                   		 <label><?=lang('lang_Transaction');?></label>
                    		 <input type="file" name="pro_image" ng-files="file" accept="image/*" class="form-control" >
							 <input type="hidden" name="invoice_no" value="{{editcodlistArray.invoice_no}}">
							 <input type="hidden" name="cust_id" value="{{editcodlistArray.cust_id}}">
                    	</div>
                    </div>
             
               
				<button style="margin-top: 40px;" type="submit" class="btn btn-info" name="update_linehoul" ><?=lang('lang_Update');?></button>  
					
			   </div>
           </form>
        </div>
    </div>
</div>
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
        
      

        <!-- /page container -->

    </body>
</html>
