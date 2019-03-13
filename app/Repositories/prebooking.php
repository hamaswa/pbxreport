<?php
include_once "functions.php";

$ip= "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];

$id = getid($callerid);

$info = prebooking($ip,$id,$callerid,'getjob');
$info['callerid'] = $callerid;
$info['carplate1'] = formatnum($info['carplate1']);
$info['carplate2'] = formatnum($info['carplate2']);
$info['carplate3'] = formatnum($info['carplate3']);


if(gettype($info['jobid1'])=="array") {
    echo "No Booking Available". PHP_EOL;
}
else
    echo $info['job']. PHP_EOL;

insertprebooking($info);

function prebooking($ip,$id, $callerid,$request)
{
    $url = "http://$ip/IVRInterface/Service.asmx/pre_booking_info?id=$id&request=$request&callerid=$callerid";
    echo $url.PHP_EOL;
    $info = curlcall($url);
    return $info;

}



?>

