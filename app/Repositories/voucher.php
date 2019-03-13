<?php
include_once "functions.php";

//$ip = "203.126.106.143:8787";  //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[1];
$voucher=$argv[2];

$id = getid($callerid);
checkvoucher($id,$callerid,$voucher);

function checkvoucher($id, $callerid,$voucher)
{
    $url = "http://203.126.106.154:8787/IVRInterface/Service.asmx/voucher_info?id=$id&callerid=$callerid&voucher=$voucher";
    $info = curlcall($url);
    echo $info['error'];
}

?>

