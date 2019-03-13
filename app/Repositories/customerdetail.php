<?php
include_once "functions.php";

$ip= "tftds-websvc.smrt.com.sg:8787";  //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];
$id = getid($callerid);

customerdetail($ip,$id,$callerid);

function customerdetail($ip,$id, $callerid){
    $url = "http://$ip/IVRInterface/Service.asmx/customer_details?callerid=$callerid&id=$id";
    $info = curlcall($url);
    $info['callerid'] = $callerid;
    delete("delete from customerdetail where callerid = $callerid");
    insertcustomer($info);
}



?>

