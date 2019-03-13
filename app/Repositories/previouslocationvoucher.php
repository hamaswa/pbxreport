<?php
include_once "functions.php";

$ip= "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[1];
$refpp = $argv[2];
$pickpt= $argv[3];
$request = $argv[4];
$voucher = $argv[6];
$dest = isset($argv[5])?$argv[5]:"confirm1";

$id = getid($callerid);


if($request=='1'){
    $info = prvLocBooking($id, $callerid, 'confirm1',$refpp,$pickpt,$dest,$voucher);
    echo $info['status'];
}
else if($request=="2") {
    $info = booking($ip, $id, $callerid, $refpp, $pickpt, $dest,$voucher);
    $info['callerid'] = $callerid;

    if(isset($info['carplate1']) and !(is_array($info['carplate1']))){
        echo "Booking Successful". PHP_EOL;
    }
    else {
        echo "No Booking Made" . PHP_EOL;

    }
    $info['carplate1'] = formatnum($info['carplate1']);
    $row = getData($callerid,'customerdetail');
    switch ($refpp){
        case $row['ref1'];
            $pickpt= $row['loc1'];
            break;
        case $row['ref2'];
            $pickpt= $row['loc2'];
            break;
        case $row['ref3'];
            $pickpt= $row['loc3'];
            break;
    }

    $info['pickuppt1']= $pickpt;
    $sql = insertquery($info,'previouslocationvoucher');
    delete("Delete from previouslocationvoucher where callerid='".$info['callerid']."'");
    $res = runquery($sql);
}



function booking($ip,$id, $callerid,$refpp,$pickpt,$dest,$voucher){

    echo "Please wait your request is processing." .PHP_EOL;
    $info = prvLocBooking($id, $callerid,'confirm2',$refpp,$pickpt,$dest,$voucher);
    $info['callerid']=$callerid;
    return $info;


}



function prvLocBooking($id, $callerid,$request,$refpp,$pickpt,$dest,$voucher)
{

    $url = "http://203.126.106.143:8787/IVRInterface/Service.asmx/previous_location_booking?id=$id&callerid=$callerid&request=$request&refpp=$refpp&pickpt=$pickpt&destination=$dest&voucher=$voucher";
    return curlcall($url);

}


?>

