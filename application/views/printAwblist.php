<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$html .= '
<!doctype html>
<html><head>
<meta charset="utf-8">';
$html .= '<title>AWB print of ' . $awb . ' </title> ';
$html .= '<style>


img {
    -webkit-filter: grayscale(100%); /* Safari 6.0 - 9.0 */
    filter: grayscale(100%);
}
.invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 10px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 12px;
        line-height: 24px;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #555;
        height:850px
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
        text-align: left;
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
}

td, th {

        text-align: left;
        padding: 0px;
} 
</style>
</head> 
<body>';
//print_r($status_update_data); die;
foreach ($status_update_data as $key => $val) {


    $destination = getdestinationfieldshow($status_update_data[$key]['destination'], 'city');
    $origin = getdestinationfieldshow($status_update_data[$key]['origin'], 'city');
    $dcode = getdestinationfieldshow($status_update_data[$key]['destination'], 'city_code');
    $oringincode = getdestinationfieldshow($status_update_data[$key]['origin'], 'city_code');
    $account_no = Getuniqueidbycustid($status_update_data[$key]['cust_id']);
    $limit = $status_update_data[$key]['pieces'];
    //	print_r($status_update_data[$key]['destination']); die();
    //for ($i = 1; $i <= $limit; $i++)
    {



        if ($status_update_data[$key]['weight'] > $status_update_data[$key]['volumetric_weight'])
            $weight = $status_update_data[$key]['weight'];
        else
            $weight = $status_update_data[$key]['volumetric_weight'];


        $style = " width:100px;margin-top:13px;";
        $style2 = "font-size:28px;margin-top:20px;";
        $style3 = "height:17%; width:17%;";
        if (isset($type) && $type == 'A4') {
            $style = " width:230px;margin-top:35px;";
            $style2 = "font-size:50px;margin-top: 60px;text-align:center";
             $style3 = "width:19%;";
        }


        /* 	$listingQry1="select * from status where deleted='N' and slip_no='".$status_update_data[$key]['slip_no']."'";
          $this->dbh_read->Query($listingQry1);
          if($this->dbh_read->num_rows)
          {
          $status_update_data1=$this->dbh_read->FetchAllResults($listingQry1);
          //$objSmarty->assign("status_update_data1", $status_update_data1);
          } */

        //site_configTable('logo')
        //$destination_city=$functions->Get_city_idd($status_update_data[$key]['country_city']);
        //echo 'application/third_party/qrcodegen.php?data='.$status_update_data[$key]['slip_no']; die;
        $html .= '<div class="invoice-box">
    <table cellpadding="0" cellspacing="0"  border="1">
            <tr >

    <tr>
            <td class="" style="width:34%; text-align:center;">
                    <img src="' . SUPERPATH . Getsite_configData_field('logo') . '"  style="' . $style . '">           
            </td>



            <td style=" text-align:center" class="center" > 
            <img src="https://lm.fastcoo-tech.com/application/third_party/qrcodegen.php?data=' . $status_update_data[$key]['slip_no'] . '"  style="'.$style3.'" />    
            </td>
            <td style="text-align:center" > 
    <p style="' . $style2 . '">' . $dcode . '</p>
            </td>
    </tr>
    <tr> 


            </tr>
            </table>
<table border="1">

                        <tr  align="center">
        <td style="font-size:12px; width:50%">
                 <strong> From</strong>  :  <br />
                <strong>' . $status_update_data[$key]['sender_name'] . '  </strong><br />
                ' . $status_update_data[$key]['sender_address'] . '
                 <br /><strong>Mobile</strong>: ' . $status_update_data[$key]['sender_phone'] . '
                 <br /><strong>City</strong>: ' . $origin . '
        </td>


        <td class="center "  style="font-size:10px;">
                 <strong> To</strong>  :  <br />
                <strong>' . $status_update_data[$key]['reciever_name'] . ' </strong> <br />
                ' . $status_update_data[$key]['reciever_address'] . '
                 <br /><strong>Mobile</strong>: ' . $status_update_data[$key]['reciever_phone'] . '
                 <br /><strong>City</strong>: ' . $destination . '
        </td>

</tr> 
										

</tr> 
</table>
<table cellpadding="0" cellspacing="0" border="1">	   
<tr> <td colspan="2"  style="font-size:12px; align:center" align="center" >

                <img src="' . barcodeRuntime($status_update_data[$key]['slip_no']) . '">    </td>
                        </tr> <tr> 
                <td colspan="2" style="font-size:14px; align:center" align="center" ><strong>' . $status_update_data[$key]['slip_no'] . '    </strong>
        </td>

</tr>';
        if ($status_update_data[$key]['frwd_company_label'] == 'SP') {
            $html .= '<tr> <td colspan="2"  style="font-size:12px; align:center" align="center" >

                <img src="' . barcodeRuntime($status_update_data[$key]['frwd_company_awb']) . '">    </td>
                        </tr> <tr> 
                <td colspan="2" style="font-size:14px; align:center" align="center" ><strong>' . $status_update_data[$key]['frwd_company_awb'] . '    </strong>
        </td>

</tr>';
        }
        $html .= '<tr>
        <td class=" " style="font-size:8px;">
                <strong> Account number </strong>: ' . $account_no . '
        </td>

        <td class=" " style="font-size:8px;">
                <strong> Date </strong> : ' . date("d-m-Y H:i:s", strtotime($status_update_data[$key]['entrydate'])) . '
        </td>  
</tr>
<tr>
        <td class=" " style="font-size:12px;">
                <strong> Weight</strong> : ' . $weight . 'Kg' . '
        </td>  

        <td class=" "style="font-size:12px;">
                <strong>Sku Qty </strong>: ' . $status_update_data[$key]['pieces'] . '
        </td>  


</tr>
    <!--<tr>
            <td class=" " style="font-size:12px;">
                    <strong> Reference number </strong>: ' . $status_update_data[$key]['booking_id'] . '
            </td> 
    </tr>-->  ';


        if ($status_update_data[$key]['pod'] == 'Y') {
            $html .= '<tr>
                    <td class=" " style="font-size:12px;">
                            <strong> POD fees </strong>: ' . $status_update_data[$key]['pod_fees'] . '
                    </td> 
            </tr>';
        }
        if ($status_update_data[$key]['pod'] == 'Y') {
            $pod_services = 'Yes';
        } else {
            $pod_services = 'No';
        }


        $Total_amount = $status_update_data[$key]['cod_fees'] + $status_update_data[$key]['service_charge'] + $status_update_data[$key]['total_cod_amt'];

        if ($status_update_data[$key]['total_cod_amt'] != '' && $status_update_data[$key]['total_cod_amt'] != '0') {
            if ($status_update_data[$key]['client_type'] == 'B2C') {
                $html .= '<tr> 
    <td class=" " colspan="2" align="center">
            <strong> COD </strong>: ' . $status_update_data[$key]['total_cod_amt'] . '
    </td>  
    </tr>';
            } else {
                $html .= '<tr>
    <td class=" "colspan="2" align="center">
            <strong> ' . $status_update_data[$key]['mode'] . ' </strong>: ' . $Total_amount . ' SAR
    </td>  
    </tr>';
            }
        }

        if (!empty($status_update_data[$key]['booking_id'])) {
            $html .= '  
							
    <tr align="center">
            <td colspan="2"  style="font-size:12px; align:center" align="center">

                    <img src=" ' . barcodeRuntime($status_update_data[$key]['booking_id']) . '">
                    </td>
                    </tr> <tr> 
                    <td colspan="2" style="font-size:12px; align:center" align="center" ><strong>' . $status_update_data[$key]['booking_id'] . '</strong>
            </td></tr> ';
        }

        $html .= '   <tr><td colspan="2" style="font-size:8px;" >
                    <strong> Description </strong>:' . $status_update_data[$key]['status_describtion'] . '
            </td></tr></table>
</div> ';
    }
}
$html .= '

</body>
</html> ';
echo $html;
