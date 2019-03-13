<?php
include_once "functions.php";

$ip= "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];
$jobid=$argv[3];
$request= $argv[4];

$id = getid($callerid);

if($request=='cancel' or $request='00') {
    cancelbooking($ip, $id, $callerid, $jobid, $request);
}

function cancelbooking($ip,$id, $callerid,$jobid,$request){
    $url = "http://$ip/IVRInterface/Service.asmx/cancel_booking?id=$id&callerid=$callerid&jobid=$jobid&request=$request";
    $info = curlcall($url);
    echo $info['status'];
}
?>

