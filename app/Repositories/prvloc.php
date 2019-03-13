<?php
include_once "functions.php";

$ip= "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];
$refpp = $argv[3];
$pickpt= $argv[4];
$request = $argv[5];
switch ($argv[6]) {
    case "1":
        $dest = "CityArea";
        break;
    case "2":
        $dest = "AirPort";
        break;
    case "3":
        $dest = "''";
        break;
}

$row = getData($callerid,'customerdetail');
$id = $row['id'];

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


if($request=='1'){
    $info = prvLocBooking($ip, $id, $callerid, 'confirm1',$refpp,$pickpt,$dest);
    echo $info['status'];
}
else if($request=="2") {
    $info = booking($ip, $id, $callerid, $refpp, $pickpt, $dest);
    $info['callerid'] = $callerid;
    insertPrvLocBooking($info);
    print_r($info);
    if(isset($info['carplate1']) and !(is_array($info['carplate1']))){
        echo "Booking Successful". PHP_EOL;

    }
    else {
        echo "No Booking Made" . PHP_EOL;

    }
}



function booking($ip,$id, $callerid,$refpp,$pickpt,$dest){

    echo "Please wait your request is processing." .PHP_EOL;
    $info = prvLocBooking($ip,$id, $callerid,'confirm2',$refpp,$pickpt,$dest);
    $info['callerid']=$callerid;
    return $info;


}



function prvLocBooking($ip,$id, $callerid,$request,$refpp,$pickpt,$dest)
{

    $url = "http://$ip/IVRInterface/Service.asmx/previous_location_booking";
    $data = array('id'=>$id,'callerid'=>$callerid,'request'=>$request,'refpp'=>$refpp,'pickpt'=>$pickpt,'destination'=>$dest);
    echo $url;
    return curl($url,$data);

}


?>

