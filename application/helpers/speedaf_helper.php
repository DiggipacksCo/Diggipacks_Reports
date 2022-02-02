<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

    function decrypt_data($data_to_decrypt, $secret_key,$encryption_algorithm, $initilization_vector ) 
	{
		
        $decrypted_data = openssl_decrypt($data_to_decrypt['data'], $encryption_algorithm,  $secret_key, 0, $initilization_vector);
		$decrypted_data = json_decode($decrypted_data, true);
		
		//data can be of mixed type
		return $decrypted_data;
    }
    
    function encrypt_data($data_to_encrypt, $timestamp, $secret_key,$encryption_algorithm, $initilization_vector)
    {
		if(empty($data_to_encrypt)){
			throw new \Exception("Kindly provide the data to be enctrypted");
		}
		
		if(empty($timestamp)){
			throw new \Exception("timestamp is required");
		}
		
		
		//incoming data are the query parameters. they are in array form.
		//we need to transform them into json_format 
		$data_in_json_format =  json_encode($data_to_encrypt);
		
		$data = array(
			"data" => $data_in_json_format,
			"sign" => md5($timestamp . $secret_key . $data_in_json_format, false)
		);
		
		//turn whole data into json b4 ecryption
		$data = json_encode($data); 
                $encrypted_data = openssl_encrypt($data, $encryption_algorithm, $secret_key, 0, $initilization_vector);
		
		return $encrypted_data; //based_64_encoded string
    }
    
    function getCurrentTimestamp()
    {
        list($msec, $sec) = explode(' ', microtime());
        $timestamp = ceil((floatval($msec) + floatval($sec)) * 1000);
                    return $timestamp;
    }



