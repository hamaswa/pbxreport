<?php
error_reporting("E_ALL");
class downloadController
{
    public  $content;
    public  $filename;
    private  $my_path;
    private $my_name;
    private $my_mail;
    private $my_replyto;
    private $my_subject;
    private $my_message;

    public function __construct()
    {
        $servername = "localhost";
        $dbname="motion2";
        $username = "root";
        $password = "Boy2Cat4$";
        $this->content="";
        $this->my_path = dirname(__FILE__);
        $this->my_name = "";
        $this->my_mail = "my@mail.com";
        $this->my_replyto = "noReply@mail.net";
        $this->my_subject = "This is a mail with attachment.";
        $this->my_message = "Body Message";
        $this->conn = new mysqli($servername, $username, $password,$dbname);
    }

    public function weeklydownload()
    {
        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last monday midnight", $previous_week);
        $end_week = strtotime("next sunday", $start_week);
        $start = date("Y-m-d", $start_week);
        $end = date("Y-m-d", $end_week);
        $this->filename = $start."_to_".$end."_singtel_traffic_report.xslx";
        $today = date('d/m/Y');
        while (strtotime($start) <= strtotime($end)) {
            $this->content[]= array("Generate Date:" . $today );
            $this->content[] = array("Daily 24 Hour Measurement of PBX Traffic Report");
            $this->download(date('Y-m-d',strtotime($start))." 00:00:00", date('Y-m-d',strtotime($start))." 23:59:59");
            $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));

        }
        $this->content[]=array("","","","","","-----------End of Report-----------","","");

        $fp = fopen($this->filename, 'w');

        foreach ($this->content as $fields) {
            fputcsv($fp, $fields);
        }


    }

    public function rangedaysdownload($start,$end)
    {
        $this->filename = $start."_to_".$end."__singtel_traffic_report.csv";
        $today = date('d/m/Y');

        while (strtotime($start) <= strtotime($end)) {
            $this->content[]= array("Generate Date:" . $today );
            $this->content[] = array("Daily 24 Hour Measurement of PBX Traffic Report");
            $this->download(date('Y-m-d',strtotime($start))." 00:00:00", date('Y-m-d',strtotime($start))." 23:59:59");
            $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));

        }


        $this->content[]=array("","","","","","-----------End of Report-----------","","");

        $fp = fopen($this->filename, 'w');

        foreach ($this->content as $fields) {
            fputcsv($fp, $fields);
        }



    }
    /**/
    private function download($start, $end)
    {
        $sql = "SELECT Hour(createdAt)+1 as hour, 
                COUNT(DISTINCT report_square.uniqueid) AS Attempt,
                /*SEC_TO_TIME(SUM(UNIX_TIMESTAMP(leaveAt)-UNIX_TIMESTAMP(joinAt))) AS Tot,*/
                  ROUND(AVG(UNIX_TIMESTAMP(leaveAt)-UNIX_TIMESTAMP(joinAt))) AS Average
                  FROM report_square 
                  WHERE project_name='SMRT_TFDS_1' and createdAt BETWEEN '$start' and '$end'
                  GROUP BY hour";
       // echo $sql. PHP_EOL;
        $data = array();

        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['incomming'][] = $row;
            }
        }
        else {
        echo $this->content[] = array("No data available");
        }

        $data['incomming'] = json_decode(json_encode($data['incomming']), True);

        $data1 = array();
        $max = null;
        $index = null;
        for ($i = 0; $i < count($data['incomming']); $i++) {
            $data1['incomming'][$data['incomming'][$i]['hour']] = $data['incomming'][$i];
            if($data['incomming'][$i]['Attempt']>$max || !isset($index)) {
                $max = (!isset($max) ? $data['incomming'][$i]['Attempt'] : ($data['incomming'][$i]['Attempt'] > $max ? $data['incomming'][$i]['Attempt'] : $max));
                $index = $data['incomming'][$i]['hour'];
            }

        }

        unset($data);
        $data = $data1;

        $this->content[] = array("Tel No	:","69586888","","","Name	: SMRT Taxis Pte Ltd");
        $this->content[] = array("Date 		: ", date("d/m/Y",strtotime($start)),"","","","Lines	: 90");


        $this->content[] = array("","Incoming Calls","","","","Outgoing Calls","","","","Total Calls");

        $this->content[] = array("Hour","BHInd", "Attempt","Answer","Duration","BHInd","Attempt","Answer","Duration","BHInd","Attempt","Answer","Duration");


        for ($i = 1; $i <= 24; $i++) {
            $array = array();
            unset($data['incomming'][$i]['hour']);
            $array[] = $i;
            for($j=1;$j<4;$j++) {
                switch ($j){
                    case 1:
                        $array[] = ($i == $index ? "*" : "");
                        if (isset($data['incomming'][$i])) {
                            $array[] = $data['incomming'][$i]['Attempt'];
                            $array[] = $data['incomming'][$i]['Attempt'];
                            $array[] = $data['incomming'][$i]['Average'];
                        } else {
                             $array[] = "0";
                            $array[] = "0";
                            $array[] = "0";
                        }
                        break;
                    case 2:
                        $array[] = ($i == $index ? "+" : "");
                        $array[] = "0";
                        $array[] = "0";
                        $array[] = "0";

                        break;
                    case 3:
                        $array[] = ($i == $index ? "#" : "");
                        if (isset($data['incomming'][$i])) {
                            $array[] = $data['incomming'][$i]['Attempt'];
                            $array[] = $data['incomming'][$i]['Attempt'];
                            $array[] = $data['incomming'][$i]['Average'];
                        } else {
                            $array[] = "0";
                            $array[] = "0";
                            $array[] = "0";
                        }
                        break;
                }


             }

            $this->content[]=$array;
        }

        $this->excelformulas(count($this->content));

        $this->content[]= array("","","");
        $this->content[]= array("Abbreviations:","","","","");
        $this->content[]= array("Date           ",":The date of measurement.","","","","Hour"," :The end hour of measurement.");
        $this->content[]= array("Incoming Calls ",":Calls terminated  at subscriber.","","","","Attempt ",": Number of call attempts made.");
        $this->content[]= array("Outgoing Calls ",":Calls originated from subscriber.","","","","Answer ",": Number of answered calls.");
        $this->content[]= array("BHInd.   *     ",":Busy hr (Incoming Calls)","","","","Duration ",":The average conversation time in secs calculated using Traffic");

        $this->content[] = array("+ : BHInd(Outgoing Calls)");
        $this->content[] = array("+ : BHInd(Total Calls)");


        //$this->sendMail($this->filename, $this->my_path, "hkhan.swa@gmail.com", $this->my_mail, $this->my_name, $this->my_replyto, $this->my_subject, $this->my_message);
        //file_put_contents($this->filename, $this->content);


        //$this->downloadCallReport('csv', array());


    }

    private function excelformulas($i)
    {
        $array = array("Total", "", $this->sumcells('C',$i), $this->sumcells('D',$i),
            "=Round(SUMPRODUCT(D".($i-23).":D".$i.",E".($i-23).":E".$i.")/D".($i+1).")", "",
            $this->sumcells('G',$i), $this->sumcells('H',$i), $this->sumcells('I',$i), "",
            $this->sumcells('K',$i), $this->sumcells('L',$i),
            "=Round(SUMPRODUCT(L".($i-23).":L".$i.",M".($i-23).":M".$i.")/L".($i+1).")");

        $this->content[] = $array;
    }

    private function sumcells($cell,$i)
    {
        return "=sum(" . $cell . ($i-23) . ":" . $cell . $i . ")";
    }


    private function sendMail($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
        $file = $path.$filename;
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $header = "From: ".$from_name." <".$from_mail.">\r\n";
        $header .= "Reply-To: ".$replyto."\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $header .= "This is a multi-part message in MIME format.\r\n";
        $header .= "--".$uid."\r\n";
        $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
        $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $header .= $message."\r\n\r\n";
        $header .= "--".$uid."\r\n";
        $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
        $header .= $content."\r\n\r\n";
        $header .= "--".$uid."--";
        if (mail($mailto, $subject, "", $header)) {
            echo "mail send ... OK"; // or use booleans here
        } else {
            echo "mail send ... ERROR!";
        }
    }


}


$download = new downloadController();
if($_REQUEST['type']=='daily'){
    $download->dailydownload();
}
elseif($_REQUEST['type']=='weekly'){
    $download->weeklydownload();
}
elseif($_REQUEST['type']=="sevendays"){
    $download->sevendaysdownload();
}
elseif($_REQUEST['type']=='range'){
    $start=$_REQUEST['start'];
    $end=$_REQUEST['end'];
    $download->rangedaysdownload($start,$end);
}
