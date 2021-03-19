<?php
echo "ssss";
ini_set('display_errors', 1);
curl_request('https://fm.fastcoo-tech.com/InvocieCron/Getportelrentelrun');
 curl_request('https://fm.fastcoo-tech.com/InvocieCron/getruninvocie');
function curl_request($url)
{
$handle = curl_init();
$url = $url;

// Set the url
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
 
$output = curl_exec($handle);
 
curl_close($handle);
 
echo $output; 
}

?>


