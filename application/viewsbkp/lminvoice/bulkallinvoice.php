<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/jszip.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/jszip-utils.js"></script>
<script type="text/javascript" src="<?=base_url();?>assets/FileSaver.js"></script>
<div class="centercontent tables">
  <div id="contentwrapper" class="contentwrapper">
    <div class="contenttitle2">
      <h3>
     
     Invoice detail( <?=$invoiceData[0]['invoice_no'];?>)
     <?php
$logo = Getsite_configData_field('logo'); //$this->site_data->newlogo;
?>
      </h3>
      
      <br />
    </div>
 
   
		<a onclick="javascript:printDiv('printme')" style="cursor:pointer;" >
		<button style="float:right;" class="btn btn-danger">Print</button>
		</a>
        <input class="btn-primary" type="button" onclick="create_zip();"  value="Export to Excel" style="float:;">
 <br />
	
 <style>
    #header {
  display: table-header-group;
  
}
table.report-container {
    page-break-after:always;
}
thead.report-header {
    display:table-header-group;
}

#mainC {
  display: table-row-group;
}

#footer {
  display: table-footer-group;
}</style>
    <style type="text\css" media="print">
	@media #print
	{
	  display: none;
	}

	</style>

      
	<style>
    table, th, td
	{
    border: 1px solid black;
    padding:2px;
    }
    th 
	{
    background-color: #CCC;
    color: #000;
    width:10%;
    }
    </style>
    
   
    <div id="printme" style="100%;" > 
    
    
    <!-- This code is for print button -->
     <script language="javascript">
            function printDiv(divName)
			 {

                  var printContents = document.getElementById(divName).innerHTML;
                  var originalContents = document.body.innerHTML;
 
                  document.body.innerHTML = printContents;
                  window.print();
                  document.body.innerHTML = originalContents;
            }
     </script>
     
    <!-- Export To Excel -->
   
      <!-- Export To Excel -->
     
   
     <br />
     
        <table id="print" cellpadding="0"  cellspacing="0" border="1" class="display nowrap" style="margin:0 auto;"  >
  <thead>
<tr><td colspan="6"></td><td colspan="2" style="text-align:center;"><strong><?php if($this->session->userdata('user_details')['super_id']!=20) { ?>  Draft Invoice -  الفاتورة المبدئية<?php } else { ?>  Invoice -  الفاتورة   <?php }?></strong></td><td colspan="10"></td></tr>
        <tr  >
      <td colspan="6" align="left" >
			<b>UID Account Number:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'],'uniqueid');?> - رقم الحساب</b><br/> 
			
			<b>Customers Name:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'],'name');?> - اسم العميل</b> <br/>
			
			<b>Address:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'],'address');?>,<?=getdestinationfieldshow(GetalldashboardClientField($invoiceData[0]['cust_id'],'city'),'city');?> , <?=getdestinationfieldshow(GetalldashboardClientField($invoiceData[0]['cust_id'],'city'),'country');?>  - عنوان</b><br/>
			
			 <b>Bank Account Number:-<?=GetalldashboardClientField($invoiceData[0]['cust_id'],'account_number');?> - الحساب البنكي</b><br/>
		  
		  	<b>Account Manager:-<?=GetalldashboardClientField($invoiceData[0]['cust_id'],'account_manager');?></b>
		  <br/>
		  <b>Vat Id No.:-&nbsp;<?=GetalldashboardClientField($invoiceData[0]['cust_id'],'vat_no');?>- الرقم الضريبي </b>
		  <br/>
		   <b>Currency:-SAR</b>
			</td>
           <td colspan="2" align="center">
    <img src="<?= SUPERPATH . Getsite_configData_field('logo'); ?>" height="50px;"/></td>
    <td colspan="10" align="left">
      <b> <?=Getsite_configData_field('ligal_name');?>  – <?=Getsite_configData_field('company_address');?>
 </b><br/>
    <b>Vat Id No.:-&nbsp;<?=Getsite_configData_field('vat');?> - الرقم الضريبي </b><br/>  
    <b>Invoice No:-&nbsp;<?=$invoiceData[0]['invoice_no'];?> - رقم الفاتورة</b><br/>
    <b>Invoice Date:-&nbsp;<?=$invoiceData[0]['invoice_date'];?> - تاريخ الفاتورة</b><br/>
	<b>Support Email:-&nbsp;<?=Getsite_configData_field('support_email');?> </b>
	
<b>    </td>
        </tr>
        <tr>
          <td colspan="18" align="justify">&nbsp;</td>
        </tr>
        <tr>
          <th >Sr.No.</th>
         
           <th>Ref No. / الرقم المرجعي</th>
			 <th>Awb No / رقم البوليصة</th>
       <th>Status / الحالة</th>
         
        
          <th>Close Date / تاريخ التوصيل</th>
		<th>Delivery Attempts / محاولات التسليم</th>	
         
          <th>Origin / المصدر</th>
          <th>Destination / الوجهة</th>
          <th>Weight (Kg) / الوزن</th>
			   <th>No. of Pieces / عدد القطع</th>
          <th>Service Type / نوع الخدمة</th>        
           <th>COD Amount / قيمة التحصيل</th>
          <th>COD fees / رسوم التحصيل</th>
           <th>Return Charge / قيمة الإرجاع</th>     
          <th>Shipping Service / المجموع الصافي</th>
			 <th>Total / المجموع</th>
			 <th>VAT / الضريبة</th> 
			<th>Grand Total / المجموع الكلي</th>
       
        </tr>
		  </thead>

     
	
		<?php

$bankFees=$invoiceData[0]['bank'];
$discount=$invoiceData[0]['discount'];
		$total_cod_amount=0;
		$total_collect_add=0;
		$total_service_add=0;
		$returnCharges=0;
		$totalchargeShow=0;
		$totalvatShow=0;
    $totalgrandShow=0;
    $totalamount=0;
    $vatpercentage = Getsite_configData_field('default_service_tax')  ; 
		foreach($invoiceData as $key=>$rowData)
		{	
		$cod_charge=$rowData['cod_charge'];
		 $return_charge=$rowData['return_charge'];
		$service_charge=$rowData['service_charge'];
   
		$totalamount=	$cod_charge+$return_charge+$service_charge;
		$totalvat=$vatpercentage/100*$totalamount;
		 $grandTotal=$totalamount+$totalvat;
		
		$total_cod_amount+=$rowData['cod_amount'];
		$total_collect_add+=$rowData['cod_charge'];
		$total_service_add+=$rowData['service_charge'];
		$returnCharges+=$rowData['return_charge'];
		
		$totalchargeShow+=$totalamount;
		$totalvatShow+=$totalvat;
		$totalgrandShow+=$grandTotal;
		$counter=$key+1;
     echo' <tr>
        <td align="center">'.$counter.'</td>
		   <td align="center">'.$rowData['refrence_no'].'</td>
        <td align="center">'.$rowData['awb_no'].'</td>
        <td align="center">'.$rowData['status'].'</td>
        
        
       
        <th>'.$rowData['close_date'].'</th>
        <td align="center">'.$rowData['d_attempt'].'</td>
        <td align="center">'.getdestinationfieldshow($rowData['origin'],'city').'</td>
        <td align="center">'.getdestinationfieldshow($rowData['destination'],'city').'</td>
		
       <td style="background-color:#AEFFAE;">
		'.$rowData['weight'].' Kg
       </td>
		   <td align="center">'.$rowData['qty'].'</td>
		<td align="center">'.$rowData['mode'].'</td>
		<td align="center">'.$rowData['cod_amount'].'</td>
		<td align="center">'.$rowData['cod_charge'].'</td>
        <td align="center">'.$rowData['return_charge'].'</td>
		<td align="center">'.$rowData['service_charge'].'</td>
		<td align="center">'.$totalamount.'</td>
		  <td align="center">'.$totalvat.'</td>
		  <td align="center"> '.$grandTotal.'</td>
		  
		  
    <form action="" name="">
    <input type="hidden" name="invoic_no_b" id="invoic_no_b" value="'.$rowData['invoice_no'].'" />
    </form>
      </tr>'; 
		}
		?>
       <tr> 
        <td colspan="10" style="text-align:center; ">&nbsp;</td>
   		 <td style="text-align:right; font-size:18px;"><b> Total (SAR)</b>  </td>	
		
        </td>
        
          <td style="text-align:right; font-size:18px;"><b><?=$total_cod_amount;?> </b></td>
        <td style="text-align:right; font-size:18px;"><b><?=$total_collect_add;?> </b></td>
        <td style="text-align:right; font-size:18px;"><b><?=$returnCharges?> </b></td> 
        
       
        </td>
         <td style="text-align:right; font-size:18px;"><b> <?=$total_service_add;?>
      </b>
        </td>
		 <td align="center"><strong> <?=$totalchargeShow;?></strong></td>
		 <td align="center"><strong><?=$totalvatShow;?></strong></td>
		 <td align="center"><strong> <?=$totalgrandShow;?></strong></td>
        </tr> 
      </tbody>
   <tr> 
   <?php 
   $totalchargeShow=$totalchargeShow-$discount;

   $totalvatShow=$totalchargeShow*$vatpercentage/100;
   $totalgrandShow= $totalchargeShow+$totalvatShow;
   

   ?>
<td colspan="17">
   <table align="left" width="30%" border="1">
    
    <tr> <th colspan="2" >Summary - ملخص</th> </tr>
   <tr>
    <th>Total Customer Collection – اجمالي تحصيلات العميل</th><th>SAR <?=$total_cod_amount;?></th>
    </tr>
    <tr><td align="justify">Less: COD Fees - رسوم التحصيل</td><td align="center">SAR -<?=$total_collect_add;?></td></tr>

    <tr><td align="justify">Less: Shipment Charges - تكلفة توصيل الشحنات</td><td align="center">SAR -<?=$total_service_add;?></td></tr>
     <tr><td align="justify">Less: Return Charge – رسوم الإرجاع</td><td align="center">SAR -<?=$returnCharges;?></td></tr>

    
    <tr><td align="justify"> Discount - الخصم</td><td align="center">SAR <?=$discount;?></td></tr>

    <tr>
    <th align="justify">Total Invoice before VAT – اجمالي الفاتورة قبل الضريبة</th><th >SAR -<?=$totalchargeShow;?></th></tr>
   <!-- <td>{$vat}</td>-->
    </tr>
    <tr>
    <td>VAT Percentage  - نسبة الضريبة</td> <td align="center"><?=$vatpercentage; ?> % </td> 
    </tr>
    <tr>
    <td>VAT Amount - قيمة الضريبة</td> <td align="center">SAR -<?=$totalvatShow;?></td>
    </tr>
    <tr>
    <th align="justify">Total Invoice after VAT - اجمالي الفاتورة  بعد الضريبة</th><th >- SAR <?=$totalgrandShow;?></th></tr>
    <tr><td align="justify">Less: Bank fees – رسوم التحويل البنكي</td><td align="center">- SAR <?=$invoiceData[0]['bank_fees'];?></td></tr>
   <th align="justify">Amount To be Transfered - اجمالي المبلغ الواجب تحويله</th><th >SAR <?php echo $total_cod_amount-$totalgrandShow-$invoiceData[0]['bank_fees']; ?></th></tr> 
    </table>
    </td>
    </tr>  
    </table>
	
	
	</div>
	</div>
	</div>

      <!-- Export To Excel -->
      <script type="text/javascript" src="<?= base_url(); ?>assets/zip_request.js"></script>


 <form action='' id='new_form' method='POST'>
<input type='hidden' id='new_id' name='exceldata'>
 </form>

    
    

