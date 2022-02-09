<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mylerzClass {

    public $param;
    

    public function __construct($param = "")
    { 
        // $this->param =$param;
        // $this->pdf = new mPDF('utf-8',array(101,152),0, '', 0, 0, 0, 0, 0, 0);
        
    }


    function getToken($username='',$password='',$api_url=''){
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url.'/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=password&username='.$username.'&password='.$password,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: .AspNet.Cookies=oULfDaCQI0rE81rS2OztApqfljQYkOQTRvh-IKtdmmHBu6DjOOy1J-vp0kiQJmwTk_3bjkvHmobrkCHTL1gQt2gnHGwk2zy3MMeYsGO1UPzW6yjKV2ipAd9Ls9LbctisKrEHsGCetHF85A7pdGeI6Y2L68sE0e5b2Lt6ZRDAUPREKdi5Jl9UONIb_zahiWHI0DbmYByDpLWhoXDFhDoaMvRz7SNYvTEYpRCJ_LyCJ54tkHqOSdCliL_ZwrXIMCAxNhWckJnR9fDGS_gLJjeF1vH3kvBZ8hlO_86rqLbVMtvnciapu_WIj9AnGRH75Nou88LeyOAJQg-g8mDsds9g_Yuac7M9WtFl7K9eP2GFq2J6Dp9gezBIWsTKHKIyNLu6J3T2GPPDTFoaHqpQXYGWVbcCrKSBuXjRjLuOTTLE8GtDkzWfGBP1pAJQNmJKYMH0YrABl7WlNOPlkMuFSRgEoQ'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $responseData = json_decode($response,true);
        return $responseData;

    }

    function forwardShipment($sellername= null,$ShipArr=array(), $counrierArr=array(), $token= null,$complete_sku= null,$c_id= null,$box_pieces1= null,$super_id= null){

        $wharehouse = $sellername." ".$counrierArr['company'];
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'mylerz_city', $super_id);
        
        $sender_country = getdestinationfieldshow($ShipArr['origin'], 'country', $super_id);
        

        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'mylerz_city', $super_id);
        $receiver_country = getdestinationfieldshow($ShipArr['destination'], 'country' , $super_id);
        
        $store_address = $ShipArr['sender_address'];
        $senderemail = $ShipArr['sender_email'];
        $senderphone = $ShipArr['sender_phone'];

        $API_URL = $counrierArr['api_url'] . "/api/Orders/AddOrders";


        if (empty($box_pieces1)) {
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }

        if ($ShipArr['weight'] == 0) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }

        $payMode = $ShipArr['mode'];
        if($ShipArr['mode'] == "COD"){
            $cod_amount = $ShipArr['total_cod_amt'];

        }
        elseif ($ShipArr['mode'] == 'CC'){
            $cod_amount = 0;
        }
        $piecec_array = array();
        for($i=1;$i<=$box_pieces;$i++){
            $piecec_array[] = array(
                "PieceNo" => $i,
                "weight"=>$weight,
                "ItemCategory"=> "Goods",
                "Dimensions"=> "",
                "Special_Notes"=> $complete_sku
               );
           }

        $date = date('Y-m-d');
        $time = date('h:i:s');
        $PickupDueDate  = $date."T".$time;
        //echo $PickupDueDate;die;
        $array_data = array(
                array(
                "WarehouseName" => $wharehouse,
                "PickupDueDate"  => $PickupDueDate,
                "Package_Serial" => 1,
                "Service" => "SD",
                "Reference" => $ShipArr['slip_no'],
                "Description" => $complete_sku,
                "Total_Weight" => $weight,
                "Service_Type" => "DTD",
                "Service_Category" => "DELIVERY",
                "Payment_Type" => $payMode,
                "COD_Value"  => $cod_amount,
                "Customer_Name" => $ShipArr['reciever_name'],
                "Mobile_No" => $ShipArr['reciever_phone'],
                "Building_No" => "",
                "Street" => $ShipArr['reciever_address'],
                "Floor_No" => "",
                "Apartment_No" => "",
                "Country" => $receiver_country,
                "City" => $receiver_city,
                "Neighborhood" => 'Maadi',
                "District" => "",
                "GeoLocation" => "",
                "Address_Category" =>  "H",
                "CustVal" =>  "",
                "Currency" =>  "SAR",
                "Pieces"=> $piecec_array
            )
                
        );

        $json_string = json_encode($array_data);
        //echo $json_string;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json_string,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $responseData = json_decode($response,true);
        
        $CI =& get_instance();
        $CI->load->model('Ccompany_model');

        if($responseData['IsErrorState'] === false){
            $successstatus  = "Success";
        }else{
            $successstatus  = "Fail";
        }
        $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        return $responseData;
    }

    function getLabel($client_awb = null,$token = null, $api_url=  null,$slipNo=null){

        $curl = curl_init();

        $labelUrl = $api_url.'/api/Packages/GetAWB';
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $labelUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
        "Barcode":"'.$client_awb.'",
        "ReferenceNumber":"'.$slipNo.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $responseData = json_decode($response,true);
        
        return $responseData;


    }

    


}	