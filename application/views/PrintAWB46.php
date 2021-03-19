<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>AWB Print</title>
        <style>

            .invoice-box {
                max-width: 1200px !important;
                margin: auto;
                padding: 10px;

                font-size: 13px;
                line-height: 70px;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                color: #555;

            }


            .invoice-box table {
                width: 100%;
                line-height: inherit;
                text-align: left;
            }

            .invoice-box table td {
                padding: 5px;
                vertical-align: top;
            }

            .invoice-box table tr td:nth-child(2) {
                text-align: right;
            }

            .invoice-box table tr.top table td {
                padding-bottom: 10px;
            }

            .invoice-box table tr.top table td.title {
                font-size: 45px;
                line-height: 45px;
                color: #333;
            }
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
                line-height:20px;
            }

            td, th {

                text-align: left;
                padding: 0px;
            } 
            .lineheaig1 { line-height:20px;}
            .cod_box{
                background:#CCC !important;
                border-radius:10px;
                padding:10px;
                text-align:center;
                -webkit-print-color-adjust: exact; 
                font-size:18px;

            } 
            .fontsizecl{ font-size:12px;}

            .imglogo {
                margin-left: auto;
                margin-right: auto;
                display: block;
            }
        </style>



    </head>

    <body>


        <?php
        if (!empty($shipment)) {
            $totalPice = 0;
            $pt = count($shipment) -1;
            $pc = 0;
            for ($i = 0; $i < count($shipment); $i++) {

                 $pc++;
                $break = "page-break-after:always;";
                if($pc > $pt){
                    $break = "page-break-after:auto;";
                }
                //GetallCutomerBysellerId($shipment[$i]['slip_no']
                if ($shipment[$i]['mode'] == 'COD')
                    $codamt = $shipment[$i]['total_cod_amt'];
                else
                    $codamt = 0;

                $dcode = getdestinationfieldshow($shipment[$i]['dest_cty'], 'city_code');
                $account_no = Getuniqueidbycustid($shipment[$i]['cust_id']);

                echo'<div class="invoice-box" width="1000" height="1200" style="'.$break.'">  
                <table class="table table-lg text-nowrap" cellpadding="0" cellspacing="0"  border="1">
                

                  <tr >
<td colspan="2"><table class="table table-lg text-nowrap" cellpadding="2" cellspacing="2"  border="1">
                  <tr><td align="center" style="text-align:center;width: 35%;" ><img src="' . SUPERPATH . Getsite_configData_field('logo') . '" class="imglogo" width="100" style="margin-top: 23px;"> </td>
                      
<td align="center" style="text-align:center;">	<img src="' . base_url() . 'assets/qrcodegen.php?data=' . $shipment[$i]['slip_no'] . '"   /></td><td align="center" style="text-align:center;"><b style="margin-top: 23px;font-size: 35px;">' . $dcode . '</b></td></tr>
                  
                </table></td> 
<tr >
<td style="width:50%;" class="fontsizecl" ><strong>SENDER INFO</strong></td><td style="width:50%; text-align:left;" class="fontsizecl"><strong>RECEIVER INFO</strong></td></tr>
                    <tr ><td class="fontsizecl"><b>Name:</b>' . GetallCutomerBysellerId(GetshpmentDataByawb($shipment[$i]['slip_no'], 'cust_id'), 'company') . '<br>
                    <b>Contact:</b>' . $shipment[$i]['sender_phone'] . '<br>
                    <b>Address:</b>' . $shipment[$i]['sender_address'] . '<br>
                    <b>City:</b>' . $shipment[$i]['origin'] . '<br></td>
                        
					<td class="fontsizecl" style="text-align:left;"><b>Name:</b>' . $shipment[$i]['reciever_name'] . '<br>
					<b>Contact:</b>' . $shipment[$i]['reciever_phone'] . '<br>
					<b>Address:</b>' . $shipment[$i]['reciever_address'] . '<br>
					<b>City:</b>' . $shipment[$i]['destination'] . '<br></td></tr>
                    
                    <tr><td colspan="2" align="center" style="text-align:center;"><strong><img src="' . barcodeRuntime($shipment[$i]['slip_no']) . '"><br>' . $shipment[$i]['slip_no'] . '</strong></td></tr>
                    <tr><td colspan="2" align="center" style="text-align:center;" class="fontsizecl"><strong>COD AMOUNT:' . $codamt . ' (SR)</strong></td></tr>
                    <tr><td class="fontsizecl"><b style="font-size:13px;">Weight:</b> ' . $shipment[$i]['weight'] . ' KG</td><td class="fontsizecl" style="text-align:left;"><b style="font-size:13px;">Sku Qty:</b> 1</td></tr>
                          <tr><td class="fontsizecl"><b style="font-size:13px;">Account No:</b> ' . $account_no . '</td><td class="fontsizecl" style="text-align:left;"><b style="font-size:13px;">Date:</b> ' . date("Y-m-d", strtotime($shipment[$i]['ship_entrydate'])) . '</td></tr>
                </table>
				   <table class="table table-lg text-nowrap" cellpadding="0" cellspacing="0"  border="1">
                 <tr><td colspan="2" align="center"  style="text-align:center;"><strong><img src="' . barcodeRuntime(GetshpmentDataByawb($shipment[$i]['slip_no'], 'booking_id')) . '"><br>' . GetshpmentDataByawb($shipment[$i]['slip_no'], 'booking_id') . '</strong></td></tr>
                     
 <tr><td class="fontsizecl" colspan="2" align="center"  style="text-align:center;"><strong>Description : </strong> ' . $shipment[$i]['status_describtion'] . '</td></tr>
                 </table> ';


                echo'
              </div>';
            }
        }
        ?>






    </body>
</html>
<!-- footer limited  check it-->