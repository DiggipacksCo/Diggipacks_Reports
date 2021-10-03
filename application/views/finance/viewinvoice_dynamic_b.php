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
			</style>
			<br />
			<table  cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;width: 100%;">
				<tr>
					<!-- <td colspan="5"></td> -->
					<td colspan="13" style="text-align:center;"><strong>Tax Invoice -  الفاتورة الضريبية</strong></td>
				<!-- 	<td colspan="9"></td> -->
				</tr>
				<tr>

					<td colspan="5" style="padding:2%;"> 
						<b>UID Account Number:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'uniqueid');?> - رقم الحساب</b>
						<br/> <b>Customers Name:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'name');?> - اسم العميل</b>
						<br/> <b>Address:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'address');?>  - عنوان</b>
						<br/> <b>Bank Account Number:-<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'account_number');?> - الحساب البنكي</b>
						<br/> <b>Account Manager:-<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'account_manager');?></b>
							<br/> <b>Vat Id No.:-&nbsp;<?=GetalldashboardClientField($invoiceDatainfo[0]['cust_id'], 'vat_no');?>- الرقم الضريبي </b>

						<br/> <b>Currency:-<?= $currency; ?></b> 
					</td>

					<td colspan="3" align="center"> 
						 <img src="<?= SUPERPATH . Getsite_configData_field('logo'); ?>"  height="70px;"/>
						<!-- <img src="https://super.fastcoo-tech.com/assets/331.png.webp" height="70px;" />  -->
					</td>

					<td colspan="5"style="padding:2%;" ><b align="left">Name Of Company -   اسم الشركة -  <?= Getsite_configData_field('ligal_name'); ?> </b>
						
						<br/> <b>Vat Id No.:-&nbsp;<?= Getsite_configData_field('vat'); ?>- الرقم الضريبي </b>
						<br/> <b>IBAN #:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'], 'iban_number');?> </b>
						<br/> <b>Invoice No:-&nbsp;<?=$invoiceDatainfo[0]['invoice_no'];?> - رقم الفاتورة</b>
						<br/> <b>Invoice Date:-&nbsp;<?=$invoiceDatainfo[0]['invoice_date'];?> - تاريخ الفاتورة</b>
						<br/><b>Toll Free no :-<?=Getsite_configData_field( 'tollfree_fm');?></b>
					</td>
				</tr>
				<tr>
					<td colspan="13" align="justify">&nbsp;</td>
				</tr>
				
				</table>
				<table  cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;width: 100%;">
				<thead>
				<tr>
			
					<th>Sr No.  / الرقم التسلسلي </th>					
					<th>AWB no. /  رقم البوليصة </th>
					<th>Weight (Kg) / الوزن (ك.غ)</th>
					<th>Picking Charge / سعر الالتقاط </th>
					<th>Packing Charge / سعر التغليف </th>
					<th>Special Packing / التغليف الخاص  </th>
					<th>Pallet Charge / سعر الطبليه </th>
					
				
					
					<th>Outbound Charge / سعر الشحنات الصادره </th> 
				
					<th>Cancel Charge / سعر الالغاء  </th>
					<th>Box Charge / سعر الكرتون  </th>
		
					<th>Total Without Vat /  المجموع بدون ضرائب  </th>
					<th>Total Vat /   مجموع الضرائب </th>
					<th>Total With Vat / المجموع مع قيمة الضريبة المضافة </th>
				</tr>
				</thead>
				<tbody>
				<?php
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
 						$picking_charge = $rowData['picking_charge'];
 						$packing_charge = $rowData['packing_charge'];
 						// $dispatch_charge = $rowData['dispatch_charge'];
 						$inbound_charge = $rowData['inbound_charge'];
 						$outbound_charge = $rowData['outbound_charge'];
						 $pallet_charge= $rowData['pallet_charge'];
 						$box_charge = $rowData['box_charge'];
 						$shipping_charge = $rowData['shipping_charge'];
 						$sku_barcode_print = $rowData['sku_barcode_print'];
 						$total_without_vat = round(( $pallet_charge+$packing_charge+$box_charge+$picking_charge+$special_packing+$cancel_charge+$outbound_charge),2);
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
						       
								<td align="center">' . $rowData['pallet_charge'] . '</td>
						       
						        <td align="center">' . $rowData['outbound_charge'] . '</td>
						      						
						        <td align="center">' . $rowData['cancel_charge'] . '</td>							
						        <td align="center">' . $rowData['box_charge'] . '</td>							
											
						        <td align="center">' . $total_without_vat . '</td>							
						        <td align="center">' . $totalvat . '</td>							
						        <td align="center">' . $total_with_vat . '</td>									    
						      </tr>';
					}

					?>


					<tr>	
						
					<?php  
					$tot = round(($totalValue['pallet_charge']+$totalValue['picking_charge']+$totalValue['packing_charge']+$totalValue['special_packing']+$totalValue['outbound_charge']+$totalValue['box_charge']),2);  
					$totvat  = round((($tot * 15)/100),2) ;
 					$tot_with_vat  = round(($tot + $totvat),2);
 					$bank_fees = GetalldashboardClientField($invoiceData[0]['cust_id'], 'bank_fees');
					?>	
					<th colspan="3"> Total Charges - التكلفة الإجمالية</th>					
					<th><?=$totalValue['picking_charge'];?></th>
					<th><?=$totalValue['packing_charge'];?></th>
					<th><?=$totalValue['special_packing'];?></th>	
					<th><?=$totalValue['pallet_charge'];?></th>				
				
				
					<th><?=$totalValue['outbound_charge'];?></th>
				
					<th><?=$totalValue['cancel_charge'];?></th>					
					<th><?=$totalValue['box_charge'];?></th>
					<th><?=$tot;?></th>					
					<th><?=$totvat;?></th>					
					<th><?=$tot_with_vat;?></th>					
				</tr>
				
				<tr>
					<td colspan="13" align="justify">&nbsp;</td>
				</tr>
				
					<br>
					<?php 
						$TOTAL =round(($totalValue['pallet_charge']+ $totalValue['pickup_charge']+$totalValue['storage_charge']+$totalValue['onhold_charges']+$totalValue['inventory_charge']+$totalValue['portal_charge']+$totalValue['sku_barcode_print']+$totalValue['packing_charge']+$totalValue['picking_charge']+$totalValue['special_packing']+$totalValue['inbound_charge']+$totalValue['outbound_charge']+$totalValue['box_charge']),2);
						$TOTALvat    =round( (($TOTAL * 15)/100),2) ;
						$TOTAL_with_vat  =round( ($TOTAL + $TOTALvat),2);
					?>

					 <?php 
					/*$TOTAL = round(($tot + $totalValue['pallet_charge']+  $totalValue['pickup_charge']+$totalValue['storage_charge']+$totalValue['onhold_charges']),2);
					$TOTALvat    = round((($TOTAL * 15)/100),2) ;
 					$TOTAL_with_vat  = round(($TOTAL + $TOTALvat),2);*/
					?> 
				
			
				
				</tbody>
				
				


				</table>
				
					<table align="left" style="width:50%;" >
						<tbody>
						<tr>
							<th colspan="2">Summary - ملخص</th>
						</tr>

						<tr>
							<td align="justify"> Total Pickup Charges / مجموع رسوم البيك اب </td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['pickup_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Packing Charges /  مجموع سعر التغليف</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['packing_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Picking Charges / مجموع سعر التقاط المنتجات</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['picking_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Special Packing Charges / مجموع سعر التغليف الخاص</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['special_packing'] ;?> 
								
							</td>
						</tr>
						
						<tr>
							<td align="justify"> Total Inbound Charges / مجموع سعر الوارد </td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['inbound_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Outbound Charges / مجموع سعر الصادر </td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['outbound_charge'];?>
							</td>
						</tr>
					
						<tr>
							<td align="justify"> Total Inventory Charges /مجموع رسوم الجرد</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['inventory_charge'];?>
							</td>
						</tr>
							<tr>
							<td align="justify"> Total Portal Charges / مجموع سعر لوحة تحكم العميل</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['portal_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Sku Barcode Print / مجموع سعر طباعه الباركود </td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['sku_barcode_print'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Box Charges / مجموع سعر الكرتون </td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['box_charge'];?>
							</td>
						</tr>
							<tr>
							<td align="justify"> Total Onhold Charges / مجموع سعر الشحنات المعلقه</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['onhold_charges'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Storage Charges / مجموع سعر التخزين</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['storage_charge'];?>
							</td>
						</tr>
						<tr>
							<td align="justify"> Total Pallet Charges / مجموع سعر الطبليات</td>
							<td align="center"><?= $currency; ?>
								<?=$totalValue['pallet_charge'];?>
							</td>
						</tr>
						
						<tr>
							<td align="justify">Discount / الخصم</td>
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
							<td align="justify">Total After Discount  / إجمالي المبالغ بعد الخصم</td>
							<td align="center"><?= $currency; ?>
								<?=$TOTAL?>
							</td>
						</tr>
						


						
							<tr>
							<td align="justify">Total Vat Fees / مجموع الضريبه المضافه </td>
							<td align="center"><?= $currency; ?>
								<?=$TOTALvat;?>
							</td>
						</tr>
							<tr>
								<td>Total Fees After VAT <?=$invoiceData[0]['vat_percent'];?>% /  إجمالي الرسوم بعد ضريبة القيمة المضافة</td>
								<td align="center"><?= $currency; ?>
									<?=$TOTAL_with_vat;?>
								</td>
							</tr>
						
							<tr>
								<th align="justify">Grand Total /  المجموع الكلي  </th>
								<th> <?= $currency; ?>
									<?=$TOTAL_with_vat;?>
								</th>
							</tr>
								</tbody>
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