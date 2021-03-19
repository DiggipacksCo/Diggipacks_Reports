<?php
# Fill our vars and run on cli
# $ php -f db-connect-test.php
//error_reporting(-1);
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set("Asia/Riyadh");

$db_host = 'ajouldb-db-instance-1.ctikm53hr4st.us-east-1.rds.amazonaws.com';
$db_user = 'ajoulMaster';
$db_password = "Ajouldb118";
//$db_name = 'khaliji_fulfill_db';  
$db_name = 'fastcoo_online_db_v4';
$mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

$result = $mysqli->query("select frwd_company_awb ,slip_no,super_id,frwd_company_id from shipment_fm where delivered NOT IN ('11','6') and deleted='N' ");


echo '<pre>';


while($row =$result->fetch_array() )
{       
    //$row['frwd_company_awb'] = "44116357526";
    //$rows[] = $row;
   // echo "here we are".$row['frwd_company_awb']."ourslipno".$row['slip_no']."<br><br>";
    $qry = $mysqli->query("select * from courier_company where super_id='".$row['super_id']."' and id='".$row['frwd_company_id']."'");
    $row1 =$qry->fetch_array();
    $api_url=$row1['api_url'];
    $user_name=$row1['user_name'];
    $password=$row1['password'];
    $courier_pin_no=$row1['courier_pin_no'];
    $AccountNumber=$row1['courier_account_no'];
    $AccountEntity='RH';
    if($row1['company']=='Aramex')
    {
            $data['ClientInfo']=array(
            'UserName' => $row1['user_name'],
            'Password' => $row1['password'],
            'Version' => 'v1',
            'AccountNumber' => $row1['courier_account_no'],
            'AccountPin' =>$row1['courier_pin_no'],
            'AccountEntity' => 'RUH',
            'AccountCountryCode' => 'SA'
        );
    
        ARAMEX_Update($row['frwd_company_awb'],$row['slip_no'],$data);
    }
    
    
}




function ARAMEX_Update($client_awb=null, $awb=null,$ClientInfo=array()) {
    $db_host = 'ajouldb-db-instance-1.ctikm53hr4st.us-east-1.rds.amazonaws.com';
    $db_user = 'ajoulMaster';
    $db_password = "Ajouldb118";
    $db_name = 'fastcoo_online_db_v4';
    $mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') '
                . $mysqli->connect_error);
    }
    echo $awb = trim($awb);
    echo "</br>";
    echo $client_awb = trim($client_awb); 

    //print_r($ClientInfo); exit;

    $params = array(
        'ClientInfo'=>$ClientInfo['ClientInfo'],
        'GetLastTrackingUpdateOnly' => false,
        'Shipments' => array($client_awb),
        'Transaction' =>
        array(
            'Reference1' => '',
            'Reference2' => '',
            'Reference3' => '',
            'Reference4' => '',
            'Reference5' => '',
        )
    );
    $dataJson = json_encode($params);
    // echo "<pre> dataJson = ";
    // print_r($dataJson);echo "</pre>";
    // exit;

    $headers = array(
        "Content-type:application/json",
        "Accept:application/json");
    $url = "https://ws.aramex.net/ShippingAPI.V2/Tracking/Service_1_0.svc/json/TrackShipments";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
    $response = curl_exec($ch);
    curl_close($ch);
    // $xml2 = new SimpleXMLElement($response);	
    //echo $response; exit; 

    $awb_array = json_decode($response);
    $all_array = json_decode(json_encode($awb_array), TRUE);

    $fixed_array = $all_array['TrackingResults'][0]['Value'];
    $final_array = array_reverse($fixed_array, true);

    echo "<pre>";
    print_r($final_array);
    echo "<br>";
    echo "<br>";
    echo "<br>"; //die; 

    if (!empty($final_array)) {
        foreach ($final_array as $allData) {
          // print_r($dataArray);exit;
            $arrayData = ARAMEX_status($allData['UpdateCode']);
            if (!empty($arrayData)) {
                // print_r($allData);exit;
                $date_time = str_replace('/', '', $allData['UpdateDateTime']);
                $date_time = str_replace('Date(', '', $date_time);
                $epoch = str_replace(')', '', $date_time);
                $date_in_formate = strstr($epoch, '+', true);
                $EPOCH_DATE = (int) $date_in_formate / 1000;
                $CURRENT_DATE_TIME = date('Y-m-d H:i:s', $EPOCH_DATE);
                $CURRENT_DATE = date('Y-m-d', $EPOCH_DATE);
                $CURRENT_TIME = date('H:i:s', $EPOCH_DATE);
                $details = $allData['UpdateDescription'];
                $activity = $arrayData['FASTCOO'];
                $checkdelivered = " select id,super_id,user_id from shipment_fm where  slip_no='" . $awb . "' and deleted='N' and delivered NOT IN('11','6')";
                $final_result = $mysqli->query($checkdelivered);
                if ($final_result->num_rows > 0) {

                    echo $checkData = " select id from status_fm where  slip_no='" . $awb . "' and  entry_date='" . $CURRENT_DATE_TIME . "' and new_status='" . $arrayData['main_d'] . "' and deleted='N' ";
                    echo "</br>";
                   $result = $mysqli->query($checkData);

                    $rowcount = $result->num_rows;
                    if ($rowcount == 0) {
                        $sresult =$final_result->fetch_array();
                        if ($arrayData['code'] == 'POD')
                            $deliverdate = " , delever_date='" . $CURRENT_DATE_TIME . "'";
                        else
                            $deliverdate = "";
                        echo $shQry = "update shipment_fm set update_date='" . $CURRENT_DATE_TIME . "', code='" . $arrayData['code'] . "',delivered='" . $arrayData['main_d'] . "' " . $deliverdate . " where slip_no='" . $awb . "' and frwd_company_awb='" . $client_awb . "' and deleted='N'";
                        echo "</br>";
                       //$mysqli->query($shQry);

                        echo $statusQry = "insert into status_fm(slip_no,new_status,pickup_time,pickup_date,Activites,Details,entry_date,user_id,user_type,code,comment,super_id) values ('" . $awb . "','" . $arrayData['main_d'] . "','" . $CURRENT_TIME . "','" . $CURRENT_DATE_TIME . "','" . $activity . "','" . $details . "','" . $CURRENT_DATE_TIME . "','".$sresult['user_id']."','user','" . $arrayData['code'] . "','Updated By ARAMEX','".$sresult['super_id']."')";
                        echo "</br>";
                        //$mysqli->query($statusQry);
                    }
                }
            }
        }
    } else {
        print_r($response);
    }
}

function ARAMEX_status($status) {
    $ARAMEX_Array = array(
        0 => array('ARAMEX' => 'SH003', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        1 => array('ARAMEX' => 'SH004', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        2 => array('ARAMEX' => 'SH005', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
        3 => array('ARAMEX' => 'SH006', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
        4 => array('ARAMEX' => 'SH007', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
        5 => array('ARAMEX' => 'SH008', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        6 => array('ARAMEX' => 'SH012', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        7 => array('ARAMEX' => 'SH570', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        8 => array('ARAMEX' => 'SH022', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        9 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        10 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        11 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        12 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        13 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        14 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        15 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        16 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        17 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        18 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        19 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        20 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        21 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        22 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        23 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        24 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        25 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        26 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        27 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        28 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        29 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        30 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        31 => array('ARAMEX' => 'SH033', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        32 => array('ARAMEX' => 'SH034', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        33 => array('ARAMEX' => 'SH035', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        34 => array('ARAMEX' => 'SH041', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        35 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        36 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        37 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        38 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        39 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        40 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        41 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        42 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        43 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        44 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        45 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        46 => array('ARAMEX' => 'SH043', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        47 => array('ARAMEX' => 'SH044', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        48 => array('ARAMEX' => 'SH044', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        49 => array('ARAMEX' => 'SH044', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        50 => array('ARAMEX' => 'SH047', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        51 => array('ARAMEX' => 'SH069', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '26'),
        52 => array('ARAMEX' => 'SH070', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        53 => array('ARAMEX' => 'SH071', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        54 => array('ARAMEX' => 'SH073', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        55 => array('ARAMEX' => 'SH074', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        56 => array('ARAMEX' => 'SH074', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        57 => array('ARAMEX' => 'SH076', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        58 => array('ARAMEX' => 'SH076', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        59 => array('ARAMEX' => 'SH076', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        60 => array('ARAMEX' => 'SH076', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        61 => array('ARAMEX' => 'SH077', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        62 => array('ARAMEX' => 'SH110', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        63 => array('ARAMEX' => 'SH154', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
        64 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        65 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        66 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        67 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        68 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        69 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        70 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        71 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        72 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        73 => array('ARAMEX' => 'SH001', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        74 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        75 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        76 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        77 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        78 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        79 => array('ARAMEX' => 'SH156', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        80 => array('ARAMEX' => 'SH157', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        81 => array('ARAMEX' => 'SH157', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        82 => array('ARAMEX' => 'SH157', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        83 => array('ARAMEX' => 'SH158', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        84 => array('ARAMEX' => 'SH160', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        85 => array('ARAMEX' => 'SH160', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        86 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        87 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        88 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        89 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        90 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        91 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        92 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        93 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        94 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        95 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        96 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        97 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        98 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        99 => array('ARAMEX' => 'SH162', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        100 => array('ARAMEX' => 'SH163', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        101 => array('ARAMEX' => 'SH164', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        102 => array('ARAMEX' => 'SH203', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        103 > array('ARAMEX' => 'SH222', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        104 => array('ARAMEX' => 'SH228', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        105 => array('ARAMEX' => 'SH230', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        106 => array('ARAMEX' => 'SH234', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
        107 => array('ARAMEX' => 'SH236', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        108 => array('ARAMEX' => 'SH237', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        109 => array('ARAMEX' => 'SH247', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        110 => array('ARAMEX' => 'SH249', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        111 => array('ARAMEX' => 'SH250', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        112 => array('ARAMEX' => 'SH251', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        113 => array('ARAMEX' => 'SH252', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        114 => array('ARAMEX' => 'SH257', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        115 => array('ARAMEX' => 'SH259', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        116 => array('ARAMEX' => 'SH260', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        117 => array('ARAMEX' => 'SH261', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        118 => array('ARAMEX' => 'SH270', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        119 => array('ARAMEX' => 'SH271', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        120 => array('ARAMEX' => 'SH272', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        121 => array('ARAMEX' => 'SH273', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        122 => array('ARAMEX' => 'SH275', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        123 => array('ARAMEX' => 'SH278', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        124 => array('ARAMEX' => 'SH279', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        125 => array('ARAMEX' => 'SH280', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        126 => array('ARAMEX' => 'SH281', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        127 => array('ARAMEX' => 'SH294', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        128 => array('ARAMEX' => 'SH294', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        129 => array('ARAMEX' => 'SH294', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        130 => array('ARAMEX' => 'SH294', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        131 => array('ARAMEX' => 'SH294', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        132 => array('ARAMEX' => 'SH294', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        133 => array('ARAMEX' => 'SH294', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        134 => array('ARAMEX' => 'SH295', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        135 => array('ARAMEX' => 'SH296', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        136 => array('ARAMEX' => 'SH299', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        137 => array('ARAMEX' => 'SH308', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        138 => array('ARAMEX' => 'SH312', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        139 => array('ARAMEX' => 'SH313', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        140 => array('ARAMEX' => 'SH314', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        141 => array('ARAMEX' => 'SH369', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        142 => array('ARAMEX' => 'SH375', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        143 => array('ARAMEX' => 'SH376', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        144 => array('ARAMEX' => 'SH381', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        145 => array('ARAMEX' => 'SH382', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        146 => array('ARAMEX' => 'SH383', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        147 => array('ARAMEX' => 'SH406', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        148 => array('ARAMEX' => 'SH407', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        149 => array('ARAMEX' => 'SH408', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        150 => array('ARAMEX' => 'SH410', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        160 => array('ARAMEX' => 'SH434', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        161 => array('ARAMEX' => 'SH438', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        162 => array('ARAMEX' => 'SH442', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        163 => array('ARAMEX' => 'SH443', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        164 => array('ARAMEX' => 'SH444', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        165 => array('ARAMEX' => 'SH445', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        166 > array('ARAMEX' => 'SH446', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        167 => array('ARAMEX' => 'SH447', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        168 => array('ARAMEX' => 'SH448', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        169 => array('ARAMEX' => 'SH449', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        170 => array('ARAMEX' => 'SH450', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        171 => array('ARAMEX' => 'SH451', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        172 => array('ARAMEX' => 'SH452', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        173 => array('ARAMEX' => 'SH453', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        174 => array('ARAMEX' => 'SH454', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        175 => array('ARAMEX' => 'SH455', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        176 => array('ARAMEX' => 'SH456', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        177 => array('ARAMEX' => 'SH457', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        178 => array('ARAMEX' => 'SH458', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        179 => array('ARAMEX' => 'SH459', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        180 => array('ARAMEX' => 'SH460', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        181 => array('ARAMEX' => 'SH461', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        182 => array('ARAMEX' => 'SH462', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        183 => array('ARAMEX' => 'SH463', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        184 => array('ARAMEX' => 'SH464', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        185 => array('ARAMEX' => 'SH465', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        186 => array('ARAMEX' => 'SH466', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        187 => array('ARAMEX' => 'SH467', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        188 => array('ARAMEX' => 'SH468', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        189 => array('ARAMEX' => 'SH469', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        190 => array('ARAMEX' => 'SH470', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        191 => array('ARAMEX' => 'SH471', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        192 => array('ARAMEX' => 'SH472', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        193 => array('ARAMEX' => 'SH473', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        194 => array('ARAMEX' => 'SH474', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        195 => array('ARAMEX' => 'SH475', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        196 => array('ARAMEX' => 'SH479', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        197 => array('ARAMEX' => 'SH480', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '13'),
        198 => array('ARAMEX' => 'SH484', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        199 => array('ARAMEX' => 'SH491', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        200 => array('ARAMEX' => 'SH492', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        201 => array('ARAMEX' => 'SH493', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        202 => array('ARAMEX' => 'SH494', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        203 => array('ARAMEX' => 'SH495', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        204 => array('ARAMEX' => 'SH496', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
        205 => array('ARAMEX' => 'SH498', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        206 => array('ARAMEX' => 'SH499', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        207 => array('ARAMEX' => 'SH504', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        208 => array('ARAMEX' => 'SH505', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        209 => array('ARAMEX' => 'SH513', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        210 => array('ARAMEX' => 'SH515', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        211 => array('ARAMEX' => 'SH515', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        212 => array('ARAMEX' => 'SH515', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        213 => array('ARAMEX' => 'SH515', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        214 => array('ARAMEX' => 'SH516', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        215 => array('ARAMEX' => 'SH517', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        216 => array('ARAMEX' => 'SH518', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        217 => array('ARAMEX' => 'SH519', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        218 => array('ARAMEX' => 'SH521', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        219 => array('ARAMEX' => 'SH521', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        220 => array('ARAMEX' => 'SH529', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        221 => array('ARAMEX' => 'SH530', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        222 => array('ARAMEX' => 'SH531', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        223 => array('ARAMEX' => 'SH532', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        224 => array('ARAMEX' => 'SH533', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        225 => array('ARAMEX' => 'SH534', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        226 => array('ARAMEX' => 'SH537', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        227 => array('ARAMEX' => 'SH538', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        228 => array('ARAMEX' => 'SH539', 'FASTCOO' => 'In Transit', 'code' => 'RR', 'main_d' => '12'),
        229 => array('ARAMEX' => 'SH540', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        230 => array('ARAMEX' => 'SH542', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        231 => array('ARAMEX' => 'SH543', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        232 => array('ARAMEX' => 'SH544', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        233 => array('ARAMEX' => 'SH546', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        234 => array('ARAMEX' => 'SH547', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        235 => array('ARAMEX' => 'SH548', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        236 => array('ARAMEX' => 'SH549', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        237 => array('ARAMEX' => 'SH556', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        238 => array('ARAMEX' => 'SH559', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        239 => array('ARAMEX' => 'SH560', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        240 => array('ARAMEX' => 'SH562', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12'),
        241 => array('ARAMEX' => 'SH563', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '12')
    );
    foreach ($ARAMEX_Array as $key => $val) {
        if ($ARAMEX_Array[$key]['ARAMEX'] == trim($status)) {
            // print_r($ARAMEX_Array[$key]);exit;
            return $ARAMEX_Array[$key];
        }
    }
}
?>