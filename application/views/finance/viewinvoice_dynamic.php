<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript" src="js/jszip.js"></script>
<script type="text/javascript" src="assets/js/jszip-utils.js"></script>
<script type="text/javascript" src="assets/js/FileSaver.js"></script>
<div class="centercontent tables">
	<div id="contentwrapper" class="contentwrapper">
		<div class="contenttitle2">
			<h3>
		    Invoice detail( <?=$invoiceData[0]['invoice_no'];?>)
  
      </h3>
			<br /> </div>
		<a onclick="javascript:printDiv('printme')" style="cursor:pointer;">
			<button style="float:right;" class="btn btn-danger">Print</button>
		</a>
		<input class="btn-primary" type="button" onclick="create_zip();" value="Export to Excel" style="float:;">
		<br />
		<div id="printme" style="100%;">
			<!-- This code is for print button -->
			<script language="javascript">
			function printDiv(divName) {
				var printContents = document.getElementById(divName).innerHTML;
				var originalContents = document.body.innerHTML;
				document.body.innerHTML = printContents;
				window.print();
				document.body.innerHTML = originalContents;
			}
			</script>
			<!-- Export To Excel -->
			<!-- Export To Excel -->
			<style type="text\css" media="print"> @media #print { display: none; } </style>
			<style>
			table,
			th,
			td {
				border: 1px solid black;
				padding: 2px;
			}
			
			th {
				background-color: #CCC;
				color: #000;
				width: 10%;
			}
			</style>
			<br />
			<table id="print" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;"width: 100%;>
				<tr>
					<!-- <td colspan="5"></td> -->
					<td colspan="15" style="text-align:center;"><strong>Tax Invoice - فاتورة ضريبية</strong></td>
				<!-- 	<td colspan="9"></td> -->
				</tr>
				<tr>

					<td colspan="4" style="padding:2%;"> 
						<b>UID Account Number:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'uniqueid');?> - رقم الحساب</b>
						<br/> <b>Customers Name:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'company');?> - اسم العميل</b>
						<br/> <b>Address:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'address');?>  - عنوان</b>
						<br/> <b>Bank Account Number:-<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'account_number');?> - الحساب البنكي</b>
						<br/> <b>Account Manager:-<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'account_manager');?></b>
							<br/> <b>Vat Id No.:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'vat_no');?>- الرقم الضريبي </b>

						<br/> <b>Currency:-SAR</b> 
					</td>

					<td colspan="3" align="center"> 
						 <img src="<?= SUPERPATH . Getsite_configData_field('logo'); ?>"  height="70px;"/>
						<!-- <img src="https://super.fastcoo-tech.com/assets/331.png.webp" height="70px;" />  -->
					</td>

					<td colspan="8"style="padding:2%;" ><b align="left">Name Of Company - Fastcoo - اسم الشركة </b>
						
						<br/> <b>Vat Id No.:-&nbsp;<?= Getsite_configData_field('vat'); ?>- الرقم الضريبي </b>
						<br/> <b>IBAN #:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'iban_number');?> </b>
						<br/> <b>Invoice No:-&nbsp;<?=$invoiceData[0]['invoice_no'];?> - رقم الفاتورة</b>
						<br/> <b>Invoice Date:-&nbsp;<?=$invoiceData[0]['invoice_date'];?> - تاريخ الفاتورة</b>
						<br/><b>Toll Free no :-<?=site_config_detaiil($invoiceData[0]['super_id'], 'tollfree_fm');?></b>
						
					</td>
				</tr>
				<tr>
					<td colspan="17" align="justify">&nbsp;</td>
				</tr>
				<tr>
					<th>Sr No. </th>					
					<th>AWB no. </th>
					<th>Weight (Kg)</th>
					<th>Picking Charge</th>
					<th>Packing Charge</th>
					<th>Special Packing</th>
					
					<th>Return Charge</th>
					
					<th>Outbound Charge</th>
					<th>Shipping Charge </th>
					<th>Cancel Charge </th>
					<th>Box Charge </th>
		
					<th>Total Without Vat  </th>
					<th>Total Vat </th>
					<th>Total Wth Vat  </th>
				</tr>
				<?php
					
					foreach ($invoiceData as $key => $rowData) {
					  //	$pickup_charge += $rowData['pickup_charge'];
 						$invoice_no = $rowData['invoice_no'];
 						$slip_no = $rowData['slip_no'];
 						$cancel_charge = $rowData['cancel_charge'];
 						$special_packing_seller = $rowData['special_packing_seller'];
 						$special_packing_warehouse = $rowData['special_packing_warehouse'];
	 						if($special_packing_seller > 0)
	 						{
	 							$special_packing = $special_packing_seller;
	 						}
	 						else { 
	 							$special_packing = $special_packing_warehouse;
	 						}
 						$return_charge = $rowData['return_charge'];
 						$picking_charge = $rowData['picking_charge'];
 						$packing_charge = $rowData['packing_charge'];
 						// $dispatch_charge = $rowData['dispatch_charge'];
 						$inbound_charge = $rowData['inbound_charge'];
 						$outbound_charge = $rowData['outbound_charge'];
 						$box_charge = $rowData['box_charge'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$sku_barcode_print = $rowData['sku_barcode_print'];
 						$total_without_vat = round(($packing_charge+$box_charge+$picking_charge+$special_packing+$return_charge+$shipping_charge+$cancel_charge+$outbound_charge),2);
 						$totalvat    =round( (($total_without_vat * 15)/100),2) ;
 						$total_with_vat  =round( ($total_without_vat + $totalvat),2);
 						 $counter = $key + 1;
 						echo ' <tr>
								<td align="center">' . $counter. '</td>
								<td align="center">' . $rowData['slip_no'] . '</td>	
								<td align="center">' . $rowData['weight'] . '</td>							
						        <td align="center">' . $rowData['picking_charge'] . '</td>
						        <td align="center">' . $rowData['packing_charge'] . '</td>
						        <td align="center">' . $special_packing . '</td>
						       
						        <td align="center">' . $rowData['return_charge'] . '</td>
						       
						        <td align="center">' . $rowData['outbound_charge'] . '</td>
						        <td align="center">' . $rowData['shipping_charge'] . '</td>							
						        <td align="center">' . $rowData['cancel_charge'] . '</td>							
						        <td align="center">' . $rowData['box_charge'] . '</td>							
											
						        <td align="center">' . $total_without_vat . '</td>							
						        <td align="center">' . $totalvat . '</td>							
						        <td align="center">' . $total_with_vat . '</td>									    
						      </tr>';
					}

					?>


					<tr>	
						<tr>	
					<?php  
					$tot = round(($totalValue['picking_charge']+$totalValue['packing_charge']+$totalValue['dispatch_charge']+$totalValue['shipping_charge']+$totalValue['special_packing']+$totalValue['inbound_charge']+$totalValue['outbound_charge']+$totalValue['box_charge']+$totalValue['return_charge']),2); 
					$totvat  = round((($tot * 15)/100),2) ;
 					$tot_with_vat  = round(($tot + $totvat),2);
 					$bank_fees = GetalldashboardClientField($invoiceData[0]['cust_id'], 'bank_fees');
					?>	
					<th colspan="3"> Total Charges</th>					
					<th><?=$totalValue['picking_charge'];?></th>
					<th><?=$totalValue['packing_charge'];?></th>
					<th><?=$totalValue['special_packing'];?></th>					
					<th><?=$totalValue['return_charge'];?></th>
				
					<th><?=$totalValue['outbound_charge'];?></th>
					<th><?=$totalValue['shipping_charge'];?></th>					
					<th><?=$totalValue['cancel_charge'];?></th>					
					<th><?=$totalValue['box_charge'];?></th>
					<th><?=$tot;?></th>					
					<th><?=$totvat;?></th>					
					<th><?=$tot_with_vat;?></th>					
				</tr>
				<tr>
					 
				<td colspan="16">
					<br>
					<?php 
						$TOTAL =round(($tot + $totalValue['pickup_charge']+$totalValue['storage_charges']+$totalValue['onhold_charges']+$totalValue['inventory_charge']+$totalValue['portal_charge']+$totalValue['sku_barcode_print']),2);
						$TOTALvat    =round( (($TOTAL * 15)/100),2) ;
						$TOTAL_with_vat  =round( ($TOTAL + $TOTALvat),2);
					?>
					<table align="left" width="50%">
						<tr>
							<th colspan="2">Summary - ملخص</th>
						</tr>
						<tr>
							<td align="justify"> Total Pickup Charges</td>
							<td align="center">SAR
								<?=$totalValue['pickup_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Packing Charges</td>
							<td align="center">SAR
								<?=$totalValue['packing_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Picking Charges</td>
							<td align="center">SAR
								<?=$totalValue['picking_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Special Packing Charges</td>
							<td align="center">SAR
								<?=$totalValue['special_packing'] ;?> 
								
							</td>
						</tr>
						
						<tr>
							<td align="justify"> Total Return Charges</td>
							<td align="center">SAR
								<?=$totalValue['return_charge'];?>
							</td>
						</tr>

						<tr>
							<td align="justify"> Total Inbound Charges</td>
							<td align="center">SAR
								<?=$totalValue['inbound_charge'];?>
							</td>
						</tr>

						<tr>
							<td align="justify"> Total Outbound Charges</td>
							<td align="center">SAR
								<?=$totalValue['outbound_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Shipping Charges</td>
							<td align="center">SAR
								<?=$totalValue['shipping_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Inventory Charges</td>
							<td align="center">SAR
								<?=$totalValue['inventory_charge'];?>
							</td>
						</tr>
							<tr>
							<td align="justify"> Total Portal Charges</td>
							<td align="center">SAR
								<?=$totalValue['portal_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Sku Barcode Print</td>
							<td align="center">SAR
								<?=$totalValue['sku_barcode_print'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Box Charges</td>
							<td align="center">SAR
								<?=$totalValue['box_charge'];?>
							</td>
						</tr>
							<tr>
							<td align="justify"> Total Onhold Charges</td>
							<td align="center">SAR
								<?=$totalValue['onhold_charges'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Storage Charges</td>
							<td align="center">SAR
								<?=$totalValue['storage_charge'];?>
							</td>
						</tr>
						<tr>
							<tr>
							<td align="justify">Total Fees before VAT 15%  - إجمالي الرسوم قبل ضريبة القيمة المضافة </td>
							<td align="center">SAR
								<?=$TOTAL;?>
							</td>
						</tr>
						<tr>
							<tr>
							<td align="justify">Total Vat Fees </td>
							<td align="center">SAR
								<?=$TOTALvat;?>
							</td>
						</tr>
							<tr>
								<td>Total Fees After VAT <?=$invoiceData[0][0]['vat_percent'];?>% -  إجمالي الرسوم بعد ضريبة القيمة المضافة</td>
								<td align="center">SAR
									<?=$TOTAL_with_vat;?>
								</td>
							</tr>
							<tr>
							<td align="justify">Transfer Fees </td>
							<td align="center">SAR 	<?=$bank_fees;?></td>
						</tr>
							<tr>
								<th align="justify">Grand Total </th>
								<th> SAR
									<?=$bank_fees + $TOTAL_with_vat;?>
								</th>
							</tr>
						<table>
				</td>
				
				


				</table>
				<!-- Export To Excel -->
				<SCRIPT>
				function create_zip() {
					var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel"><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>';
					tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
					tab_text = tab_text + '<x:Name>Test Sheet</x:Name>';
					tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
					tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"></head><body>';
					tab_text = tab_text + "<table border='1px'>";
					//get table HTML code
					tab_text = tab_text + $('#print').html();
					tab_text = tab_text + '</table></body></html>';
					var zip = new JSZip();
					zip.file(Date() + " Invoice.xls", tab_text);
					zip.generateAsync({
						type: "blob"
					}).then(function(content) {
						saveAs(content, Date() + "invoice.zip");
					});
				}
				</SCRIPT>
				<form action='' id='new_form' method='POST'>
					<input type='hidden' id='new_id' name='exceldata'> </form>