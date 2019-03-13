<?php
error_reporting(0);

function generateRandomString($length){
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $charsLength = strlen($characters) -1;
    $string = "";
    for($i=0; $i<$length; $i++){
        $randNum = mt_rand(0, $charsLength);
        $string .= $characters[$randNum];
    }
    return $string;
}

function getid($callerid){

    $row = getData($callerid,'callersession');
    return  (isset($row['id']) and $row['id']!="")?$row['id']:generateRandomString(10);

}

function connectdb()
{

    $servername = "localhost";
    $username = "root";
    $password = "Boy2Cat4$";
    $dbname = "queuemetrics";
// Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else
        return $conn;
}
function getPickuppt($pickupcode,$table){
    $conn = connectdb();
    $sql = "SELECT * from $table where XpressCodeNo='$pickupcode'";
    $result = $conn->query($sql);
    $row = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }

    $conn->close();
    return $row['XpressAccName'];
}

function getData($callerid,$table)
{
    $conn = connectdb();
    $sql = "SELECT * from $table where callerid='$callerid'";
    $result = $conn->query($sql);
    $row = array();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }

    $conn->close();
    return $row;

}

function clearcaller($callerid){

    $del = delete("Delete from postalcode where callerid=$callerid");
    if($del=='ture')
        echo "Caller $callerid Successfully deleted";
    else
        echo $del;

}

function delete($sql){
    $conn = connectdb();
    if ($conn->query($sql) === TRUE) {
        return "true";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

function curlcall($url)
{
    //init curl
    $ch = curl_init("$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // execute!
    $response = curl_exec($ch);
    if(curl_errno($ch)){
        throw new Exception(curl_error($ch));
    }
    // close the connection, release resources used
    curl_close($ch);
    $xml = simplexml_load_string($response);

    $json = json_encode($xml);

    return json_decode($json, TRUE);
}

function curl($url,$data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);

    echo $response;
}

function insertExpressCab($val){
    $sql = insertquery($val,'expresscab');
    delete("Delete from expresscab where callerid='".$val['callerid']."'");
    $res = runquery($sql);
}

function insertPrvLocBooking($val){
    $sql = insertquery($val,'previouslocation');
    delete("Delete from previouslocation where callerid='".$val['callerid']."'");
    $res = runquery($sql);
}

function insertprebooking($val){
    $sql = insertquery($val,'prebooking');
    delete("Delete from prebooking where callerid='".$val['callerid']."'");
    $res = runquery($sql);
}

function insertcustomer($val) {
    $sql = insertquery($val,'customerdetail');
    delete("Delete from customerdetail where callerid=".$val['callerid']);
    $res = runquery($sql);
}

function insertpostalbooking($val){
    $sql = insertquery($val,'postalbooking');

    delete("Delete from postalbooking where callerid='".$val['callerid']."'");
    $res = runquery($sql);
}

function insertquery($val,$table){

    $fields = ""; $values = "";

    foreach ($val as $k=>$v){
        $fields .= $fields!=""?", `". $k ."`" : "`". $k ."`";
        $v = (gettype($v)=='array')?implode(',',$v): $v;
        $values .= $values!=""?", '". $v ."'" : "'". $v ."'";
    }
    return "INSERT INTO `$table` ($fields) values($values)";
}

function insertpostalcode($val){
    $sql = insertquery($val, 'postalcode');
    delete("Delete from postalcode where callerid='".$val['callerid']."'");
    runquery($sql);
    if($val['validpostal']!=0){
        echo "Postalcode Verified".PHP_EOL;
    }
    else
        echo "Postalcode Not verified".PHP_EOL;

}

function formatnum($num){
    return implode(' ' , str_split($num));
}

function runquery($sql){
    $conn = connectdb();
    if ($conn->query($sql) === TRUE) {
        return "OK";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return "Error";
    }

    $conn->close();

}