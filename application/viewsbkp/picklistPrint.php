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
        <title>Picklist</title>
        <style>
            /*
            @page {
                  size: 21cm 29.7cm;
                  margin: 0; 
              }
            
              body  
              {
                  
                  margin: 0px;  
              } 
              .row1{
                margin-right: -12px;
                margin-left: 0px;
              }
              .products{
                width: 100%;
                border-collapse: collapse;
              }
              h2,h3,h4,h5{
                margin: 0;
                padding:0;
              }
              hr{
                margin-bottom: 5px;
                margin-top: 5px;
              }
              .border{
                border: 1px solid black;
                padding:5px;
              }
              table.products{
                border: 1px solid black;
                margin-left: -6px;
                margin-right: -6px;
                margin-bottom: -6px;
              }
              .products tr td{
                padding:3px;
                border:1px solid #333;
              }
              .products tr th{
                padding:3px;
                border:1px solid #333;
              }
              .pull-right{
                float: right;
              }
              .no-margin{
                margin: 0;
              }
              .no-b{
                border-top:0px !important;
                border-bottom: 0px !important;
              }
              .logo{
                position: absolute;
                width: 90px;
                left: 20px;
                top: 50px;
              }
            */
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
            .mylastpage {
              
                page-break-after:auto;
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

                echo'<div class="invoice-box" width="1000" height="1200" style="'.$break.'">  <table class="table table-lg text-nowrap" cellpadding="2" cellspacing="2"  border="1">
                  <tr><td align="center" style="text-align:center;"><img src="' . SUPERPATH . Getsite_configData_field('logo') . '" width="87" style="vertical-align: bottom; margin-top:20px;"></td><td style="text-align:center;">
<img src="https://lm.fastcoo-tech.com/application/third_party/qrcodegen.php?data=' . $shipment[$i]['slip_no'] . '"   />    </td>                   
</tr>
                  
                </table>
                <table class="table table-lg text-nowrap" cellpadding="0" cellspacing="0"  border="1">
                  <tr ><td style="width:50%;" class="fontsizecl" ><strong>SENDER INFO</strong></td><td style="width:50%; text-align:left;" class="fontsizecl"><strong>RECEIVER INFO</strong></td></tr>
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
                    <!--<tr><td class="fontsizecl"><b>Weight:</b> ' . $shipment[$i]['weight'] . ' KG</td><td ><b>Pieces:</b> 1</td></tr>-->
                </table>
				   <table class="table table-lg text-nowrap" cellpadding="0" cellspacing="0"  border="1">
                 <tr><td colspan="2" align="center"  style="text-align:center;"><strong><img src="' . barcodeRuntime(GetshpmentDataByawb($shipment[$i]['slip_no'], 'booking_id')) . '"><br>' . GetshpmentDataByawb($shipment[$i]['slip_no'], 'booking_id') . '</strong></td></tr>
                 </table> 
                <table class="table table-lg text-nowrap" cellpadding="0" cellspacing="0"  border="1" >
                <thead><tr style="text-align:center;"><th style="text-align:center;">SKU</th><th>Item Icon</th><th style="text-align:center;">Shelve#</th><th style="text-align:center;">Stock Location</th><th style="text-align:center;">Quantity</th></tr></thead>';

                for ($j = 0; $j < count($sku_per_shipment[$i]); $j++) {

                    $locationArr = GetSkuStockLocation($shipment[$i]['slip_no'], $sku_per_shipment[$i][$j]->sku);

                    foreach ($locationArr as $key89 => $val2) {
                        if ($key89 == 0) {
                            $stockLocation = $val2['stock_location'];
                            $shelve_no_show = $val2['shelve_no'];
                        } else {
                            $stockLocation .= ',' . $val2['stock_location'];
                            $shelve_no_show .= ',' . $val2['shelve_no'];
                        }
                    }
                    $item_path = getalldataitemtablesSKU($sku_per_shipment[$i][$j]->sku, 'item_path');
                    if (!empty($item_path)) {
                        $item_path = base_url() . $item_path;
                    } else {
                        $item_path = base_url() . 'assets/nfd.png';
                    }

                    echo'<tbody><tr style="text-align:center;">
                <td style="text-align:center;">' . $sku_per_shipment[$i][$j]->sku . '</td>
                    <td style="text-align:center;"><img src="' . $item_path . '" width="40"></td>
                <td style="text-align:center;">' . $sku_per_shipment[$i][$j]->deducted_shelve . '</td>
                     <td style="text-align:center;">' . $stockLocation . '</td><td style="text-align:center;">' . $sku_per_shipment[$i][$j]->piece . '</td>
                </tr></tbody>';
                }
                echo'</table>
              </div>';
            }
        }
        ?>






    </body>
</html>
<!-- footer limited  check it-->
