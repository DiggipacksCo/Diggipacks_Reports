<?php

$conn = new mysqli("ajouldb-db-instance-1.ctikm53hr4st.us-east-1.rds.amazonaws.com", "ajoulMaster", "Ajouldb118", "diggipacks_db");

// Check connection
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}
$sql = "select c.id,c.*,cn.city as city_name from customer c left join country cn on c.city = cn.id where c.deleted='N' and c.is_shopify_active ='1' and c.shopify_url !='' ";
$query = $conn->query($sql);

if ($query->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($query)) {

        //echo "<pre>";print_r($row);die;
     echo '<br>'.   $url = $row['shopify_url'];
       // if (!empty($row['shopify_tag'])) {
            //$url .= "?tags=" . $row['shopify_tag'];

            getOrders($url, $row);
            
        //}
        
    }
}

    function getOrders($url, $customer) {
    
        $orders = file_get_contents($url);
        $orders = json_decode($orders)->orders;
        $location_id = $customer['location_id'];

        if ($orders) {
            $params = array();

            $origin_city = "";
            foreach ($orders as $order) {
                sleep(1);
                if ($order->line_items) {
                    $product_arr = array();
                    foreach ($order->line_items as $items) {

                        $product_arr[] = array(
                            'sku' => $items->sku,
                            'description' => $items->variant_title,
                            'cod' => $items->price,
                            'piece' => $items->quantity,
                            'weight' => $items->grams / 1000
                        );

                        //$origin_city = $items->origin_location->city;
                    }
                }

                $payment_mod = "CC";
                if (trim($order->gateway) == "Cash on Delivery (COD)") {
                    $payment_mod = "COD";
                }

                $receiver_address = " ";
                if (!empty($order->shipping_address->address1)) {
                    $receiver_address .= $order->shipping_address->address1 . "  " . $order->shipping_address->address2 . " ";
                } else if (!empty($order->billing_address->address1)) {
                    $receiver_address .= $order->billing_address->address1 . "  " . $order->billing_address->address2 . " ";
                } else {
                    $receiver_address .= $order->shipping_address->city . "  " . $order->shipping_address->country . " ";
                }


                $receiver_phone = "";
                if (!empty($order->shipping_address->phone)) {
                    $receiver_phone = $order->shipping_address->phone;
                } else {
                    $receiver_phone = $order->billing_address->phone;
                }

                $destination = "";
                if (!empty($order->shipping_address->city)) {
                    $destination = $order->shipping_address->city;
                } else {
                    $destination = $order->billing_address->city;
                }

                $receiver_name = "";
                if (!empty($order->shipping_address->first_name)) {
                    $receiver_name = $order->shipping_address->first_name . " " . $order->shipping_address->last_name;
                } else {
                    $receiver_name = $order->billing_address->first_name . " " . $order->billing_address->last_name;
                }

                $params = array(
                    'customerId' => $customer['uniqueid'],
                    'sender_name' => $customer['name'],
                    'sender_email' => $customer['email'],
                    'origin' => $customer['city_name'],
                    'sender_phone' => $customer['phone'],
                    'sender_address' => $customer['address'],
                    'receiver_name' => $receiver_name,
                    'receiver_phone' => $receiver_phone,
                    'destination' => $destination,
                    'BookingMode' => $payment_mod,
                    'receiver_address' => $receiver_address,
                    'reference_id' => $order->order_number,
                    'codValue' => $order->total_price,
                    'productType' => 'parcel',
                    'service' => 12,
                    'skudetails' => $product_arr,
                    'shopify_order_id' => $order->id
                );

                 
                $response = requestSend($url, $params);
                $response = json_decode($response);
                echo '<br>'.$order->order_number;
                if ($response->status == 200 && $customer['shopify_fulfill'] == 1) {
                    //fulfillment($url, $response->awb_no, $order->id,$location_id); its now at dis[atch to fm its working not by mistake]
                }
                if ($response->status == 140 && $customer['shopify_fulfill'] == 1) {
                   // fulfillment($url, $response->awb_no, $order->id,$location_id);
                }
            }
        }
    }

    function requestSend($url, $data) {
        $api_url = "https://api.diggipacks.com/Shopify/createOrder";
        $headers = array(
            "Content-type: application/json;  charset=utf-8"
        );
        $request = json_encode($data, JSON_UNESCAPED_UNICODE);


        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

       echo $result = curl_exec($ch);
        return $result;
    }

    function fulfillment($url, $awb, $ref_id,$location_id) {

        $url = explode('orders.json', $url);
        $url = $url[0];
        $f_arr = array(
            "fulfillment" => array(
                "tracking_number" => $awb,
                "tracking_url" => "https://track.diggipacks.com/result_detailfm/" . $awb,
                "tracking_company" => "Diggipacks",
                "location_id" => $location_id
            )
        );

        $furl = $url . "orders/$ref_id/fulfillments.json";
         $data_string = json_encode($f_arr);

        $cSession = curl_init();

        $requestHeaders = array();
        $requestHeaders[] = 'Content-Type:application/json';
        curl_setopt($cSession, CURLOPT_URL, $furl);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_VERBOSE, 0);
        curl_setopt($cSession, CURLOPT_HEADER, true);
        curl_setopt($cSession, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($cSession, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($cSession, CURLOPT_SSL_VERIFYPEER, false);

        $result = curl_exec($cSession);

        if ($result) {
            $httpCode = curl_getinfo($cSession, CURLINFO_HTTP_CODE);
            $aHeaderInfo = curl_getinfo($cSession);
            $curlHeaderSize = $aHeaderInfo['header_size'];
            $sBody = trim(mb_substr($result, $curlHeaderSize));

            $ResponseHeader = explode("\n", trim(mb_substr($result, 0, $curlHeaderSize)));
            $responseArray = json_decode($sBody, true);
            echo "<pre>";
            print_r($responseArray);
            curl_close($cSession);
        }
    }
    