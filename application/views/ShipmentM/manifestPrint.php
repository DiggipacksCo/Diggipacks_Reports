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
            .fontsizec2{ font-size:10px;}
        </style>



    </head>

    <body>


        <?php
        // print_r($shipment);die;
        if (!empty($shipment)) {
            $totalPice = 0;
            //echo '<pre>';
            // print_r($shipment); die;



            echo'<div class="invoice-box"  >  <table class="table table-lg text-nowrap"  >
                  <tr><td align="center" style="text-align:center;"><img src="' . SUPERPATH . Getsite_configData_field('logo') . '" width="150"></td></tr>
                         <tr><td align="center" style="text-align:center;"><b>Manfest Print</b></td></tr>
                      
                  
                </table>
                <table class="table table-lg text-nowrap" cellpadding="0" cellspacing="0"  >
                  <tr ><td style="width:50%; " class="fontsizecl " >Manifest ID : ' . $shipment[0]['m_id'] . '</strong></td>'
            . '<td style="width:50%;" class="fontsizecl">Date : <strong>' . date('Y-m-d', strtotime($shipment[0]['entry_date'])) . '</strong>';
            if(GetCourCompanynameId($shipment[0]['fwd_company'], 'company')!='')
            {
            echo'<br><br>
                
Courier Company : <strong>' .GetCourCompanynameId($shipment[0]['fwd_company'], 'company'). '</strong>';
            }
            echo'</td>
                            </tr>
                    
                    
                   
                </table>
				   
                <table class="" cellpadding="0" cellspacing="0"  border="1" style="margin-top:10px;" >
                <thead>
                <tr style="text-align:center;">
                <th style="text-align:center;" class="fontsizec2">#</th>
                    <th style="text-align:center;" class="fontsizec2">Order#</th>
                    <th style="text-align:center;" class="fontsizec2">Reference#</th>';
            
            if ($shipment[0]['fwd_company'] > 0) {
                echo'<th style="text-align:center;" class="fontsizec2">3PL#</th>';
                     
            }

            echo' 
                    <th style="text-align:center;" class="fontsizec2">Destination City</th>
                   <!-- <th style="text-align:center;" class="fontsizec2">Mode</th>
                    <th style="text-align:center;" class="fontsizec2">Piece</th>-->
                    </tr>
                    </thead>';
//<img src="' . barcodeRuntime($ship['slip_no']) . '">
            foreach ($shipment as $key=>$ship) {
                $counter=$key+1;
                echo'<tbody>
                        <tr style="text-align:center;" >
                        <td style="text-align:center;" class="fontsizec2"><strong><br>' . $counter . '</strong></td>
                        <td style="text-align:center;" class="fontsizec2"><strong><br>' . $ship['slip_no'] . '</strong></td><td style="text-align:center;" class="fontsizec2"><strong><br>' . GetshpmentDataByawb($ship['slip_no'], 'booking_id') . '</strong></td>';
                if ($ship['fwd_company'] > 0) {
                    echo'  <td style="text-align:center;" class="fontsizec2"><strong><br>' . $ship['fwd_awb'] . '</strong></td>
                      <!-- <td style="text-align:center;" class="fontsizec2">' . GetCourCompanynameId($ship['fwd_company'], 'company') . '</td>-->';
                }
                echo'
                       <td style="text-align:center;" class="fontsizec2">' . getdestinationfieldshow($ship['destination'], 'city') . '</td>
                        <!--<td style="text-align:center;" class="fontsizec2">' . GetshpmentDataByawb($ship['slip_no'], 'mode') . '</td>
                        <td style="text-align:center;" class="fontsizec2">' . GetshpmentDataByawb($ship['slip_no'], 'pieces') . '</td>-->
                        </tr>
                </tbody>';
            }
            
            echo'</table><br> <table class="table table-lg text-nowrap" cellpadding="0" cellspacing="0"  >
                <tr ><td colspan="2" class="fontsizecl " >Total Orders : '.count($shipment).' </td></tr>   
                  <tr ><td style="width:50%; " class="fontsizecl " >Dispatcher Name : </td><td style="width:50%; " class="fontsizecl " >3PL Receiver Name & Signature : </td></tr>
           
</table>
              </div>';
        }
        ?>






    </body>
</html>
<!-- footer limited  check it-->