#!/usr/bin/php -q
<?php
sleep(4);
$ip = str_replace("\n","",shell_exec("ifconfig eth0 | grep 'inet addr' | awk -F':' {'print $2'} | awk -F' ' {'print $1'}"));
echo shell_exec("sleep 2");
//$ip="192.168.1.50";
echo shell_exec("sudo linphonecsh init");
echo shell_exec("sleep 2");
echo shell_exec("sudo linphonecsh generic 'ports sip 5061'");
echo shell_exec("sudo linphonecsh generic 'autoanswer enable'");
echo shell_exec("sudo linphonecsh register --host ".$ip." --username 11 --password password11");
echo shell_exec("sudo linphonecsh soundcard playback 3");
echo shell_exec("sudo linphonecsh soundcard capture 3");
$errno = "admin";
$errstr = "amp111";
$timeout = "30";
$rptan=0;

while(true){
$rptac=0;
$socket = fsockopen($ip,"5038", $errno, $errstr, $timeout);
echo "socket: ".$socket;
if ($socket!=null){
fputs($socket, "Action: Login\r\n");
fputs($socket, "UserName: admin\r\n");
fputs($socket, "Secret: amp111\r\n\r\n");
fputs($socket, "Action: ConfbridgeListRooms\r\n\r\n");
fputs($socket, "Action: Logoff\r\n\r\n");

 while (!feof($socket)) {
    $wrets = fread($socket, 4096);
    $lines=explode("\n", $wrets);
    for($i=0;$i<sizeof($lines);$i++)
    {
        $vari=substr_replace($lines[$i], "", -1);
        $varid="Message: Confbridge conferences will follow";

        echo $vari."\n";
        if(strcmp($vari,$varid)==0)
        {
         $rptac=1;
         if(($rptan==0) AND ($rptac==1))
	 {
                echo "Lo encontrooooooooooooooooooooooooooooooooooooooooooooo"."\n";
                echo shell_exec("sudo linphonecsh dial 69@".$ip);
		echo "corrio";
	 }
        }
        $vari="";
        $varid="";
    }
 }

fclose($socket);
$rptan=$rptac;
echo "rptan: ".$rptan."rptac: ".$rptac."\n";
}
sleep(2);
}

?>
