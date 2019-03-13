<?php
include_once "functions.php";

$ip= "203.126.106.143:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];
$stand=$argv[3];

$id = getid($callerid);

$info = taxistandbooking($ip,$id,$callerid,$stand,'confirm1');
print_r($info);
if($info['validtaxistand']===1) {
    sleep(10);
    $info = taxistandbooking($ip, $id, $callerid, $stand, 'confirm2');
    $info['callerid'] = $callerid;
    $info['carplate1'] = formatnum($info['carplate1']);

    inserttaxistand($info);
} else {
    echo "Invalid Taxi Stand No" . $stand;
}


function taxistandbooking($ip,$id, $callerid,$stand,$request)
{
    $url = "http://$ip/IVRInterface/Service.asmx/taxi_stand_booking?id=$id&request=$request&stand=$stand&callerid=$callerid";
    $info = curlcall($url);
    return $info;

}

function inserttaxistand($val){
    $sql = insertquery($val,'taxistand');

    delete("delete from taxistand where callerid=".$val['callerid']);
    $res = runquery($sql);
    if(gettype($val[carplate1]) != 'array') {
        echo "Booking Made Successfull";
    }
    else {
        echo "No Booking Made";
    }

}

?>

