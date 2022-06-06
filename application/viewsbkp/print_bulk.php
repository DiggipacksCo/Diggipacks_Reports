 <?php
 
echo'<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>AWB print of  </title> 

        <style>


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
      
        foreach ($status_update_data as $key => $val) {

//echo $status_update_data[$key]['origin']; die;
            $destination = getdestinationfieldshow($status_update_data[$key]['destination'], 'city');
             $origin = getdestinationfieldshow($status_update_data[$key]['origin'], 'city');
            $country = getdestinationfieldshow($status_update_data[$key]['origin'], 'country'); 
            $dcode = getCityCode($status_update_data[$key]['destination']);
            $oringincode = getCityCode($status_update_data[$key]['origin']);
             $account_no = Get_cust_uid($status_update_data[$key]['cust_id']);
             $email_sender = GetcustomerTablefield($status_update_data[$key]['cust_id'], 'email');
             $limit = $status_update_data[$key]['pieces'];
            for ($i = 1; $i <= $limit; $i++) {
              if ($status_update_data[$key]['weight'] > $status_update_data[$key]['volumetric_weight'])
                    $weight = $status_update_data[$key]['weight'];
                else
                    $weight = $status_update_data[$key]['volumetric_weight'];  

              //echo $this->config->item('base_url_super') . site_configTable('logo'); die;

               echo '<div class="invoice-box">
							<table cellpadding="0" cellspacing="0"  border="1">
								<tr >
									
											<tr>
												<td class="" style="width:34%; ">
													<img src="https://super.fastcoo-tech.com/assets/clientlogo/1607498465.png"  style="height:17% !important; width:100px !important">
												</td>
												
												
												<td style=" text-align:center" class="center" > 
												<img src="' . $this->config->item('base_url_admin') . 'application/third_party/qrcodegen.php?data=' . $status_update_data[$key]['slip_no'] . '"  style="height:17% !important; width:17% !important;" /> 
												</td>
												<td align="center" style="width:33%"> 
											<span style="font-size:28px;">' . $dcode . '</span>
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
										<strong>Address</strong>:' . $status_update_data[$key]['sender_address'] . '
										 <br /><strong>Mobile</strong>: ' . $status_update_data[$key]['sender_phone'] . ' <br /><strong>Email:</strong>: ' . $email_sender . '
										 <br /><strong>City</strong>: ' . $origin . '
                                                                                     <br /><strong>Country</strong>: ' . $country . '
									</td>
									
										
									<td class="center "  style="font-size:10px;">
										 <strong> To</strong>  :  <br />
										<strong>' . $status_update_data[$key]['reciever_name'] . ' </strong> <br />
										<strong>Address</strong>:' . $status_update_data[$key]['reciever_address'] . '
										 <br /><strong>Mobile</strong>: ' . $status_update_data[$key]['reciever_phone'] . '
                                                                                     <br /><strong>Email</strong>: ' . $status_update_data[$key]['reciever_email'] . '
                                                                                         
										 <br /><strong>City</strong>: ' . $destination . '
                                                                                      <br /><strong>Country</strong>: ' . $country . '
									</td>
									
								</tr> 
										
									
								</tr> 
							</table>
							<table cellpadding="0" cellspacing="0" border="1">	   
							<tr> <td colspan="2"  style="font-size:12px; align:center" align="center" >
										  
										' . barcodeRuntime_new($status_update_data[$key]['slip_no']) . '</td>
											</tr> <tr> 
										<td colspan="2" style="font-size:14px; align:center" align="center" ><strong>' . $status_update_data[$key]['slip_no'] . '  ' . $i . '/' . $status_update_data[$key]['pieces'] . '</strong>
									</td>
									
								</tr>
								<tr>
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
										<strong> Pieces </strong>: ' . $status_update_data[$key]['pieces'] . '
									</td>  
									
								</tr>
								<!--<tr>
									<td class=" " style="font-size:12px;">
										<strong> Reference number </strong>: ' . $status_update_data[$key]['booking_id'] . '
									</td> 
								</tr>-->  ';


                if ($status_update_data[$key]['pod'] == 'Y') {
                   echo '<tr>
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
                       echo '<tr> 
											<td class=" " colspan="2" align="center">
												<strong> COD </strong>: ' . $status_update_data[$key]['total_cod_amt'] . '
											</td>  
										</tr>';
                    } else {
                       echo '<tr>
											<td class=" "colspan="2" align="center">
												<strong> COD </strong>: ' . $Total_amount . ' '.site_configTable("default_currency").'
											</td>  
										</tr>';
                    }
                }

                if (!empty($status_update_data[$key]['booking_id'])) {
                   echo '  
							
								<tr align="center">
									<td colspan="2"  style="font-size:12px; align:center" align="center">
										
										' . barcodeRuntime_new($status_update_data[$key]['booking_id']) . '
										</td>
										</tr> <tr> 
										<td colspan="2" style="font-size:12px; align:center" align="center" ><strong>' . $status_update_data[$key]['booking_id'] . '</strong>
									</td></tr> ';
                }

               echo '   <tr><td colspan="2" style="font-size:8px;" >
										<strong> Description </strong>:' . $status_update_data[$key]['status_describtion'] . '
									</td></tr></table>
						</div> ';
            }
        }
      ?>
        </body></html>