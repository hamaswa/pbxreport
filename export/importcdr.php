<?php

$db_host = 'localhost';
$db_name = 'asteriskcdrdb';
$db_login = 'root';
$db_pass = '';

// connect to db
$mysqli = new mysqli($db_host, $db_login, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    die("Could not connect : " . $mysqli->connect_error());
}
//** 1) Find records in the asterisk log file. **
$rows = 0;
$handle = fopen("/root/cdr.csv", "r");
$count= 0;
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    // NOTE: the fields in Master.csv can vary. This should work by default on all installations but you may have to edit the next line to match your configuration
    list($accountcode, $src, $dst, $dcontext, $clid, $channel, $dstchannel, $lastapp,
        $lastdata, $start, $answer, $end, $duration, $billsec, $disposition, $amaflags, $uniqueid, $userfield ) = $data;
    /** 2) Test to see if the entry is unique **/
    $sql = "SELECT calldate, src, duration".
        " FROM cdr".
        " WHERE calldate='$start'".
        " AND src='$src'".
        " AND duration='$duration'".
        " LIMIT 1";
    if(!($result = $mysqli->query($sql))) {
        echo ("Invalid query: " . mysqli_error()."\n");
        //echo ("SQL: $sql\n");

    } else {
        if ($result->num_rows === 0) { // we found a new record so add it to the DB
            // 3) insert each row in the database
            if ($answer === '') $answer = '0000-00-00 00:00:00';  // replace empty date with default value

            if ($count === 0) {
                $query = "INSERT INTO cdr (calldate,
                                 clid,
                                 src,
                                 dst,
                                 dcontext,
                                 channel,
                                 dstchannel,
                                 lastapp,
                                 lastdata,
                                 duration,
                                 billsec,
                                 disposition,
                                 amaflags,
                                 accountcode,
                                 uniqueid,
                                 userfield) 
            VALUES('$start',
                '" . $mysqli->escape_string($clid) . "',
                '$src',
                '$dst',
                '$dcontext',
                '$channel',
                '$dstchannel',
                '$lastapp',
                '$lastdata',
                '$duration',
                '$billsec',
                '$disposition',
                '$amaflags',
                '$accountcode',
                '$uniqueid', 
                '$userfield')";
            } else {
                $query .= ",('$start',
                '" . $mysqli->escape_string($clid) . "',
                '$src',
                '$dst',
                '$dcontext',
                '$channel',
                '$dstchannel',
                '$lastapp',
                '$lastdata',
                '$duration',
                '$billsec',
                '$disposition',
                '$amaflags',
                '$accountcode',
                '$uniqueid', 
                '$userfield')";
            }
            $count++;
            if ($count === 1500) {
                $count = 0;
                if (!($result2 = $mysqli->query($query))) {
                    echo("Invalid query: " . $mysqli->error . "\n");
                    continue; // skip invalid record or you can die() here
                }
                print("$rows Records Inserted\n");

            }
            $rows++;
        } else {
            print("Not unique: $end $src $duration\n");
        }
    }
}
$result->free();
$result2->free();
$mysqli->close();
fclose($handle);
print("$rows imported\n");
?>