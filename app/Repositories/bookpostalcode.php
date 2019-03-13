<?php
include_once "functions.php";

$ip = "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];
$request = $argv[3];
$dest = $argv[4];

$row = getData($callerid,'postalcode');
$postal=str_replace(' ','',$row['postalcode']);
echo " Booking for pickup point " . $row['pickuppt1']. " Postal code ". $postal . PHP_EOL;

$id = getid($callerid);

if($request=='1'){
    $info = postalcodebooking($ip, $id, $callerid, $postal, 'confirm1',$dest);
    echo $info['status'];
}
else if($request=="2"){
    $info = booking($ip, $id, $callerid, $postal, $dest);
    $info['callerid'] = $callerid;
    if(isset($info['carplate1']) and !(is_array($info['carplate1']))){
        echo $info['carplate1'];
        echo "Booking Successful";
    }
    else {
        echo "No Booking Made" . PHP_EOL;
    }
    $info['carplate1'] = formatnum($info['carplate1']);
    $info['postalcode'] = formatnum($postal);
    $info['pickuppt1'] = str_replace($postal,$info['postalcode'],$info['pickuppt1']);
    print_r($info);

    insertpostalbooking($info);

}




function booking($ip,$id, $callerid,$postal,$dest){

    $info = postalcodebooking($ip,$id, $callerid,$postal,'confirm2',$dest);
    $info['callerid']=$callerid;
    $info['postalcode']=$postal;
    return $info;

}



function postalcodebooking($ip,$id, $callerid,$postal,$request,$dest)
{

    $url = "http://$ip/IVRInterface/Service.asmx/postal_code_booking?id=$id&postal=$postal&callerid=$callerid&request=$request&destination=$dest";
   return curlcall($url);

}



?>

