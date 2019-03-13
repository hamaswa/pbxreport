<?php
include_once "functions.php";

$ip = "tftds-websvc.smrt.com.sg:8787"; //$argv[1]==1?"203.126.106.143:8787":$argv[1];
$callerid=$argv[2];
$postal=$argv[3];


$id = getid($callerid);

$info = postalcode($ip,$id, $callerid,$postal);
$info['postalcode'] = formatnum($postal);
$info['callerid']=$callerid;

print_r($info);

insertpostalcode($info);


function postalcode($ip,$id, $callerid,$postal)
{
    $url = "http://$ip/IVRInterface/Service.asmx/postal_code_info?id=$id&postal=$postal&callerid=$callerid";
    return curlcall($url);

}

?>
