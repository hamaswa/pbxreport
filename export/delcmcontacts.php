<?php
error_reporting("E_ALL");
class cmContacts
{
   public function __construct()
    {
        $servername = "localhost";
        $dbname="motion2";
        $username = "root";
        $password = "Boy2Cat4$";
        $this->conn = new mysqli($servername, $username, $password,$dbname);
    }

     public function delete($start, $end)
    {
        $sql = "delete from cm_contacts where createdAt < (NOW() - INTERVAL 24 HOUR)";
        $this->conn->query($sql);

    }


}

$cmcontacts = new cmContacts();
$cmcontacts->delete();


