<?php
include_once "functions.php";

$callerid=$argv[1];
if($argv[2]==1)
    $dest= 'CityArea';
elseif($argv[2]==2)
    $dest='ChangiAirport';
elseif($argv[2]==3)
    $dest='-';

$sql = "Insert into destination(callerid,dest) values('$callerid','$dest')";
delete("Delete from destination where callerid='".$info['callerid']."'");
$res = runquery($sql);
