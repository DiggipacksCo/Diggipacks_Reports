<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript" src="js/jszip.js"></script>
<script type="text/javascript" src="assets/js/jszip-utils.js"></script>
<script type="text/javascript" src="assets/js/FileSaver.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"  />
<div class="centercontent tables">
	<div id="contentwrapper" class="contentwrapper">
		<div class="contenttitle2">
			<h3>

     Invoice detail( <?=$invoiceDatainfo[0]['invoice_no'];?>)
     <?php  $currency = site_configTable("default_currency"); // get default currency    ?>
    
      </h3>
			<br /> </div>
		<a onclick="javascript:printDiv('printme')" style="cursor:pointer;">
			<button style="float:right;" class="btn btn-danger"><i class="fa fa-print fa-2x"></i></button>
		</a>
		<button class="btn-primary" type="button" onclick="create_zip();" value="Export to Excel" style="float:;"><i class="fa fa-file-excel fa-2x"></i></button>
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
			#print_1 {
  display: table-row-group;
}
#print_2 {
  display: table-row-group;
}
			</style>
			<br />
			<table  cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;width: 100%;">
				<tr>
					<!-- <td colspan="5"></td> -->
					<td colspan="11" style="text-align:center;"><strong>Tax Invoice - الفاتورة الضريبية</strong></td>
				<!-- 	<td colspan="9"></td> -->
				</tr>
				<tr>

					<td colspan="4" style="padding:2%;"> 
						<b>UID Account Number:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'uniqueid');?> - رقم الحساب</b>
						<br/> <b>Customers Name:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'name');?> - اسم العميل</b>
						<br/> <b>Address:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'address');?>  - عنوان</b>
						<br/> <b>Bank Account Number:-<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'account_number');?> - الحساب البنكي</b>
						<br/> <b>Account Manager:-<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'account_manager');?></b>
						<br/> <b>Vat Id No.:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'vat_no');?>- الرقم الضريبي </b>
						
						<br/> <b>Currency:-<?= $currency; ?></b> 
					</td>

					<td colspan="2" align="center"> 
						<img src="<?= SUPERPATH . Getsite_configData_field('logo'); ?>"  height="70px;"/>
					
					</td>

					<td colspan="4"style="padding:2%;" ><b align="left">Name Of Company -  اسم الشركة -  <?= Getsite_configData_field('ligal_name'); ?></b>


						<br/> <b>Vat Id No.:-&nbsp;<?= Getsite_configData_field('vat'); ?>- الرقم الضريبي </b>


					
						<!-- <br/> <b>IBAN #:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'iban_number');?> </b> -->
						<br/> <b>Invoice No:-&nbsp;<?=$invoiceDatainfo[0]['invoice_no'];?> - رقم الفاتورة</b>
						<br/> <b>Invoice Date:-&nbsp;<?=$invoiceDatainfo[0]['invoice_date'];?> - تاريخ الفاتورة</b>
						<br/><b>Toll Free no :-<?=Getsite_configData_field( 'tollfree_fm');?></b>
						
					</td>
				</tr>
				<tr>
					<td colspan="11" align="justify">&nbsp;</td>
				</tr>
				
				</table>
				<table  cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;width: 100%;">
				
				<thead>
				<tr>	
					<th>Sr No. </th>					
					<th>AWB no. </th>
					<th>Weight (Kg)</th>
					<th>Handling Fees</th>
					<th>Special Packing </th>
					<th>Return Charges </th>
					
					<th>Cancel Charge </th>
					<th>Total Without Vat  </th>
					<th>Total Vat </th>
					<th>Total With Vat  </th>	
				</tr>
				
				</thead>
				<tbody>

					<?php
					// echo '<pre>';
					// print_r($invoiceData);
					$discount=$invoiceDatainfo[0]['discount'];
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
 						$handline_fees = $rowData['handline_fees'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$total_without_vat = round(($handline_fees+$special_packing+$cancel_charge+$return_charge),2);
 						$totalvat    = round((($total_without_vat * 15)/100),2) ;
 						$total_with_vat  = round(($total_without_vat + $totalvat),2);
 						 $counter = $key + 1;
 						echo ' <tr>
								<td align="center">' . $counter. '</td>
								<td align="center">' . $rowData['slip_no'] . '</td>	
								<td align="center">' . $rowData['weight'] . '</td>								
						        <td align="center">' . $rowData['handline_fees'] . '</td>
						        <td align="center">' . $special_packing . '</td>
						        <td align="center">' . $return_charge . '</td>							
						        <td align="center">' . $rowData['cancel_charge'] . '</td>							
						        <td align="center">' . $total_without_vat . '</td>							
						        <td align="center">' . $totalvat . '</td>							
						        <td align="center">' . $total_with_vat . '</td>									    
						      </tr>';
					}

					?>


					<tr>	
					<?php  
					$tot = ($totalValue['handline_fees']+$totalValue['special_packing']+$totalValue['return_charge']); 
					 $totvat    = round((($tot * 15)/100),2) ;
 					$tot_with_vat  = round(($tot + $totvat),2);
 					$bank_fees = GetalldashboardClientField($invoiceData[0]['cust_id'], 'bank_fees');
					?>	
					<th colspan="3"> Total Charges</th>					
					<th><?=$totalValue['handline_fees'];?></th>
					<th><?=$totalValue['special_packing'];?></th>
					<th><?=$totalValue['return_charge'];?></th>
									
					<th><?=$totalValue['cancel_charge'];?></th>					
					<th><?=$tot;?></th>					
					<th><?=$totvat;?></th>					
					<th><?=$tot_with_vat;?></th>					
				</tr>
				</tbody>

				
				
					<?php 
					$TOTAL = round(($tot + $totalValue['pickup_charge']+$totalValue['storage_charges']+$totalValue['onhold_charges']),2);
					$TOTALvat    = round((($TOTAL * 15)/100),2) ;
 					$TOTAL_with_vat  = round(($TOTAL + $TOTALvat),2);
					?>
					<br>
					
				
				
	


				</table>
				<table align="left" width="50%">
				<tbody>
						<tr>
							<th colspan="2">Summary - ملخص</th>
						</tr>
						<tr>
							<td align="justify"> Total Pickup Charges</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['pickup_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Handling Fees - إجمالي رسوم التحضير </td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['handline_fees'];?>
							</td>
						</tr>
						
						<tr>
							<td align="justify">Total Onhold Charges</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['onhold_charges'];?>
							</td>
						</tr>
						<tr>
							<td align="justify">Total Storage Charges</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['storage_charges'];?>
							</td>
						</tr>
						<tr>
							<td align="justify">Total Return Charges</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['return_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify">Total Special Packages Fees - إجمالي التغليف الخاص للعميل</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['special_packing'];?>
							</td>
						</tr>
						
							<tr>
							<td align="justify">Total Fees before VAT   - إجمالي الرسوم قبل ضريبة القيمة المضافة </td>
							<td align="center"><?= $currency; ?>
								<?=$TOTAL?>
							</td>
						</tr>
						<tr>
							<td align="justify">Discount - الخصم</td>
							<td align="center"><?= $currency; ?>
							-	<?=$discount;?>
							</td>
						</tr>
						
						<?php
						 $TOTAL= $TOTAL-$discount;
						 $TOTALvat= round((($TOTAL * 15)/100),2) ;
						 $TOTAL_with_vat=$TOTAL+ $TOTALvat;
						?>
						
							<tr>
							<td align="justify">Total After Discount  - إجمالي المبالغ بعد الخصم</td>
							<td align="center"><?= $currency; ?>
								<?=$TOTAL?>
							</td>
						</tr>
						
						
							<tr>
							<td align="justify">Total Vat Fees </td>
							<td align="center"><?= $currency; ?>
								<?=$TOTALvat;?>
							</td>
						</tr>
							<tr>
								<td>Total Fees After VAT <?=$invoiceData[0]['vat_percent'];?>% -  إجمالي الرسوم بعد ضريبة القيمة المضافة</td>
								<td align="center"><?= $currency; ?>
									<?=$TOTAL_with_vat;?>
								</td>
							</tr>
							<!-- <tr>
							<td align="justify">Transfer Fees </td>
							<td align="center"><?= $currency; ?> 	<?=$bank_fees;?></td>
						</tr> -->
							<tr>
								<th align="justify">Grand Total </th>
								<th> <?= $currency; ?>
									<?=$TOTAL_with_vat;?>
								</th>
							</tr>
							
							<tbody>
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
					tab_text = tab_text + $('#printme').html();
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