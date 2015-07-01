<?php
//header('Content-Type: application/text+xml');
$mac_address_raw = @$_GET['mac'];
$location_server = "http://10.47.128.156:8080/index2.php";

if($mac_address_raw == ""){
        echo "You need to provide a mac address!";
        exit;
    }
    
    if (!preg_match('/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/', $mac_address_raw)){
       echo "Mac address is invalid";
       exit;
    }
$mac = $mac_address_raw;
//Pull from servers
exec("curl ".$location_server."/?mac=".$mac,$content);
//var_dump($content);
//Parse the $content json into array format
$location_array = json_decode($content[0]);
$newContent = json_encode($location_array,JSON_FORCE_OBJECT);
//var_dump($newContent);
exec("node example.js ".$newContent, $output);
echo $output[0];
//var_dump($output);
//echo $location_array
exit;
?>