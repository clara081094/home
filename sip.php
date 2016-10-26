<?php
$errno = "admin";
$errstr = "amp111";
$timeout = "30";

$socket = fsockopen("192.168.1.50","5038", $errno, $errstr, $timeout);
//echo "socket: ".$socket;
if ($socket!=null){
fputs($socket, "Action: Login\r\n");
fputs($socket, "UserName: admin\r\n");
fputs($socket, "Secret: amp111\r\n\r\n");
fputs($socket, "Action: SIPpeers\r\n\r\n");
fputs($socket, "Action: Logoff\r\n\r\n");

 while (!feof($socket)) {
    $wrets = fread($socket, 4096);
    $lines=explode("\n", $wrets);
    for($i=0;$i<sizeof($lines);$i++)
    {
	$vari=substr_replace($lines[$i],"", -1);
                $varid="Channeltype: SIP";
                //echo $vars."\n";      
                if(strcmp($vari,$varid)==0)
                {
                     $i++;
                     //$user=substr((string)substr_replace($lines[$i],"",0,11),0,-1);
                     $user=substr((string)substr_replace($lines[$i],"",0,12),0,-1);
		     echo $user;
                     //return true;
                     //echo "\n------>Este es el numero: ".$participant->getNumero()." \n chanel: ".$participant->getChannel()."\n muted:$
                }
    }
 }

fclose($socket);
}

?>
