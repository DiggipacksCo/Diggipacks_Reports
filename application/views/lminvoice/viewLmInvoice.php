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
                                    <div class="panel-heading">
                                        <h1> <strong>LastMile Invoice View</strong> </h1>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row"> </div>
                                       	
									<div class="row" style="margin-top:20px">  
                                       <div class ="table-responsive">
										  <table class="table ticket-list table-lg dataTable no-footer">
											
											<tbody>
											  <tr style="width:100%">
												<th style="width:35%">Seller :
												  <select class="select2 select2-multiple form-control" style="word-wrap: break-word;" ng-model="SearArr.cust_id"  multiple="multiple" data-placeholder="Choose">
												 
													<option ng-repeat="cdata in CustomerDropdata" value="{{cdata.id}}">{{cdata.company}}({{cdata.uniqueid}})</option>
												  </select>  
												  <br>
												Created By :
												  <select class="select2 select2-multiple form-control" ng-model="SearArr.created" multiple="multiple" data-placeholder="Choose">
													<option ng-repeat="sdata in staffDropdata" value="{{sdata.id}}">{{sdata.name}}</option>
												  </select>
												  <br>
												 Cod Pay By :
												  <select class="select2 select2-multiple form-control" ng-model="SearArr.paid" multiple="multiple" data-placeholder="Choose">
													<option ng-repeat="sdata in staffDropdata" value="{{sdata.id}}">{{sdata.name}}</option>
												  </select>
												  <br>  
												 Payment Received By :
												  <select class="select2 select2-multiple form-control" ng-model="SearArr.received" multiple="multiple" data-placeholder="Choose">
													<option ng-repeat="sdata in staffDropdata" value="{{sdata.id}}">{{sdata.name}}</option>
												  </select>
												<th width="30%">Invoice No.: 
												  <select  class="form-control custom-select" >
												  <option value="">Select</option>
												  </select>
												   <br>
												Payement Mode:
												  <select class="form-control custom-select  mt-15" ng-model="SearArr.mode">
													<option value=""> Select </option>
													<option value="CC">Paid</option>
													<option value="COD">COD</option>
												  </select>
												  <br>
												Status :
												  <select class="form-control custom-select  mt-15" ng-model="SearArr.status">
													<option value=""> Select </option>
													<option value="Delivered">Delivered</option>
													<option value="Return to shiper">Return to shiper</option>
												  </select>
												</th>
												<th style="width:30%"> Create Date<br>
												From Date :
												  <input type="date" name="c_date1"  ng-model="SearArr.c_date1" id="datepicker1" class="form-control" placeholder="dd-mm-yy" >
												  <br>
												 To Date:
												  <input type="date" name="c_date2" ng-model="SearArr.c_date2" id="datepicker2" class="form-control" placeholder="dd-mm-yy">
												</th> 
											  </tr>
											  <tr>
											  
												<th width="10px"> Payment Date <br>
												  From Date  :
												  <input type="date" name="p_date1" ng-model="SearArr.p_date1" id="datepicker3" class="form-control" placeholder="dd-mm-yy" >
												  <br>
												 To date  :
												  <input type="date" name="p_date2" ng-model="SearArr.p_date2" id="datepicker4" class="form-control" placeholder="dd-mm-yy" >
												</th>
												<th width="10px"> <button type="button" class="btn btn-info btn btn-primary" ng-click="getPayableCODlist(1,1);" style="margin-top:80px">{{'lang_search'|translate}} </button>
												</th>
											  </tr>
											</tbody>      
										  </table>
										</div>   
										<div class ="table-responsive">
										<table class="table ticket-list table-lg dataTable no-footer">
										  <thead>
											<tr>
											  <th>Sr No. </th>
												<th>Account No.</th>
												<th>Company Name</th>
												 <th>Invoice#</th>
												<th>Summery </th>
												<th>Craeted By </th>
												 <th>Create date  </th>
												<th>Received By </th>
												<th>Received Date  </th>
												<th> Pay by</th>
												<th>Pay date</th>   
												<th> Receive </th>   
												<th> Pay </th>
												<th >Action </th>
											</tr>
										  </thead>
										  <tbody>
										  
											  <tr ng-repeat="data in payableinvoicelistArray"> 
											<td>{{$index+1}}</td>
											<td>{{data.uniqueid}}</td>
											<td>{{data.company}}</td>
											<td>{{data.invoice_no}}</td>
											<td><strong>Shipemnt</strong>:{{data.invoiceCount}}<br>
												<strong> COD Charges</strong>:{{data.cod_charge_sum}}<br> <strong> Return Charges </strong>:{{data.return_charge_sum}}<br><strong>Service Charge</strong>:{{data.service_charge_sum}}
												<br><strong>Total Vat</strong>:{{data.vat_sum}}
												<hr><strong>COD Amount</strong>:{{data.cod_amount_sum}}
											</td>
											<td>{{data.invoice_created_by}}</td>
											<td>{{data.invoice_created_date}}</td>  
											<td>{{data.receivable_paid_by}}</td>
											<td> {{data.receivable_paid_date}} </td>
											<td> {{data.cod_paid_by}}</td>
											<td> {{data.cod_paid_date}} </td>  
											<td>
											
											<a ng-if="data.receivable_pay_status=='N'" data-toggle="modal" class="btn btn-danger text-white" data-target="#updateLinehoulC51201961561904494" title="Received" ng-click="Getpopoprncustdetais(data.pid,'#payable_invoice','one');"> Receive</a>
											  <a ng-if="data.receivable_pay_status=='Y'"  class="btn btn-primary text-white"  title="Received"  ng-click="Getpopoprncustdetais(data.pid,'#payable_invoice_list','one');" > Received </a>  
											  </td><td>
											 <a ng-if="data.cod_pay_status=='N'" data-toggle="modal" class="btn btn-danger text-white" data-target="#updateLinehoulC51201961561904494" title="PAY" ng-click="Getpopoprncustdetais(data.pid,'#account_detail','one');"> Pay</a>	
											  <a ng-if="data.cod_pay_status!='N'" class="btn btn-primary text-white" title="PAY" ng-click="Getpopoprncustdetais(data.pid,'#payable_invoice_list1','one');"> Paid</a>	  
											   </td><td>
											<a href="codreceivablePrint/{{data.invoice_no}}" target="_blank" class="btn btn-primary">View</a></td>

										 
										   </tr>
										  </tbody>
										</table>
									  </div>
                                    </div>


<div class="modal" id="payable_invoice_list" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">   
        <div class="modal-content"> 
<div class="modal-header">
                <h5 class="modal-title">Show Transaction Proof Invoice #({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
            </div>
		
       
      
        
        <div class="modal-body">
				<div class="col-md-4">
					<div class="form-group">
						<label>Transaction No. : #({{editcodlistArray.rec_voucher}}) </label>
					</div>
				</div>
                     
				<br>  
               <button style="margin-top: 3px;" type="submit" class="btn btn-info pull-right"  name="update_linehoul" ng-click="modelClose('payable_invoice_list')">close </button>     
		</div> 
        
       </div>
    </div>
</div>

<div class="modal" id="payable_invoice_list1" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">   
        <div class="modal-content"> 
<div class="modal-header">
                <h5 class="modal-title">Show Transaction Proof Invoice  #({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
            </div>
		
       <div class="modal-body">
   		
				
					
					<div class="col-md-4">
                 <div class="form-group">  
                    <label>   <a href="{{editcodlistArray.pay_voucher}}" target="_blank">Invoice Copy</a> </label>   
                
                    </div></div>
                     
				<br>
               
			      <button style="margin-top: 3px;" type="submit" class="btn btn-info pull-right" name="update_linehoul" ng-click="modelClose('payable_invoice_list1')">close </button>         

					
			    
        </div>
      
        
       </div>
    </div>
</div>



<div class="modal" id="payable_invoice" tabindex="-1" role="dialog" aria-labelledby="payable_invoice" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prouf of Payment #({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
			<div class="modal-body">
   					<div class="col-md-8">
                 		<div class="form-group">
                   		 <label>Transaction No.  </label>
                    	<input type="text" name="invoice_no" class="form-control" ng-model="editcodlistArray.rec_voucher" required="">
                    	</div>
                    </div>
             
               
				<button style="margin-top: 3px;" type="submit" class="btn btn-info" name="update_linehoul" ng-click="payableInvoice_update(editcodlistArray);">Update </button>  
					
			   </div>
           
        </div>
    </div>
</div>

<div class="modal" id="account_detail" tabindex="-1" role="dialog" aria-labelledby="account_detail" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Proof #({{editcodlistArray.invoice_no}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
			<div class="modal-body">
   					<div class="col-md-8">
                 		<div class="form-group">
                   		 <label>Transaction</label>
                    		 <input type="file" name="pro_image" ng-files="file" accept="image/*" class="form-control" >
                    	</div>
                    </div>
             
               
				<button style="margin-top: 3px;" type="submit" class="btn btn-info" name="update_linehoul" ng-click="GetupdatePayment(editcodlistArray)">Update</button>  
					
			   </div>
           
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
