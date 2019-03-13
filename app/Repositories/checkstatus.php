<?php
include_once "functions.php";

$ip= "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];


$status = checkStatus($ip);
echo $status['services'].PHP_EOL;
if($status['services']>0){
    $callerid = $argv[2];
    $id = generateRandomString(10);
    $sql = "Delete from `callersession` where callerid='$callerid'";
    delete($sql);
    $sql = "INSERT INTO `callersession` (`id`, `callerid`) VALUES ('$id', '$callerid')";
    runquery($sql);
}

function checkStatus($ip)
{
    $url = "http://" . $ip . "/IVRInterface/Service.asmx/check_tftds";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// execute!
    $response = curl_exec($ch);


// close the connection, release resources used
    curl_close($ch);
    $xml = simplexml_load_string($response);
    $json = json_encode($xml);
   return json_decode($json, TRUE);

}
?>