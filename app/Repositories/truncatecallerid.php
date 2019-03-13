<?php
$callerid=$argv[1];
echo truncateCallerid($callerid);
function truncateCallerid($callerid){
$rmpart = '+65';

if (substr($callerid, 0, strlen($rmpart)) == $rmpart)
{
 $num = substr($callerid, strlen($rmpart));
 return $num;
}
return $callerid;

}


?>

