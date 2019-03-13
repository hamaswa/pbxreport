<?php
include_once "functions.php";

$ip= "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];
$pickupcode = $argv[3];
$request = $argv[4];
$id = getid($callerid);

if($request=='1'){
    $info = expressCab($ip, $id, $callerid, 'confirm1',$pickupcode);
    echo $info['status']==1?$info['id']:$info['status'];
}
else if($request=="2") {
    $info = expressCab($ip, $id, $callerid, 'confirm2',$pickupcode);
    $info['callerid']=$callerid;
    print_r($info);
    if(isset($info['carplate1']) and !(is_array($info['carplate1']))){
        echo "Booking Successful". PHP_EOL;
        $info['pickuppt1']= getPickuppt($pickupcode,'xpresscode');

    }
    else {
        echo "No Booking Made" . PHP_EOL;

    }
    $info['carplate1'] = formatnum($info['carplate1']);
    $info['postalcode'] = isset($info['postalcode'])?formatnum($info['postalcode']):"";
    insertExpressCab($info);

}


function expressCab($ip,$id, $callerid,$request,$pickupcode){

    $url = "http://$ip/IVRInterface/Service.asmx/express_cab_booking?callerid=$callerid&id=$id&pickupcode=$pickupcode&request=$request";
   // echo $url.PHP_EOL;
    return curlcall($url);

}


?>

