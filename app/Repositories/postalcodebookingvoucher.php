<?php
include_once "functions.php";

//$ip = "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[1];
$request = $argv[2];
$voucher = $argv[3];

$rowdist = getData($callerid,'dest');

$dest = $rowdist['dest'];
$row = getData($callerid,'postalcode');
$postal=str_replace(' ','',$row['postalcode']);
echo " Booking for pickup point " . $row['pickuppt1']. " Postal code ". $postal . PHP_EOL;

$id = getid($callerid);

if($request=='1'){
    $info = postalcodebooking($ip, $id, $callerid, $postal, 'confirm1',$dest,$voucher);
    echo $info['status'];
}
else if($request=="2"){
    $info = booking($ip, $id, $callerid, $postal, $dest,$voucher);
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

    
    $sql = insertquery($info,'postalbooking');

    delete("Delete from postalbooking where callerid='".$info['callerid']."'");
    $res = runquery($sql);

}




function booking($ip,$id, $callerid,$postal,$dest,$voucher){

    $info = postalcodebooking($ip,$id, $callerid,$postal,'confirm2',$dest,$voucher);
    $info['callerid']=$callerid;
    $info['postalcode']=$postal;
    return $info;

}



function postalcodebooking($ip,$id, $callerid,$postal,$request,$dest,$voucher)
{

    $url = "http://203.126.106.154:8787/IVRInterface/Service.asmx/postal_code_booking_voucher?id=$id&postal=$postal&callerid=$callerid&request=$request&destination=$dest&voucher=$voucher";
   return curlcall($url);

}



?>

