<?php
include_once ('/var/www/html/participante.php');

class Confami{

 private $errno = "admin";
 private $errstr = "amp111";
 private $timeout = "30";
 //private $ip=str_replace("\n","",shell_exec("ifconfig eth0 | grep 'inet addr' | awk -F':' {'print $2'} | awk -F' ' {'print $1'}"));
 private $ip;
 private $socket;

function iConfami()
{
	$this->ip = str_replace("\n","",shell_exec("ifconfig eth0 | grep 'inet addr' | awk -F':' {'print $2'} | awk -F' ' {'print $1'}")); 
	$this->socket = fsockopen($this->ip,"5038", $this->errno, $this->errstr, $this->timeout);
	fputs($this->socket, "Action: Login\r\n");
        fputs($this->socket, "UserName: admin\r\n");
        fputs($this->socket, "Secret: amp111\r\n\r\n");
}

function conferenciaHay()
{
	$this->iConfami();
	fputs($this->socket, "Action: ConfbridgeListRooms\r\n\r\n");
        fputs($this->socket, "Action: Logoff\r\n\r\n");
        while (!feof($this->socket)) {
	$wrets=fread($this->socket, 4096);
	$lines=explode("\n", $wrets);
    	for($i=0;$i<sizeof($lines);$i++)
    	{
        	$vari=substr_replace($lines[$i], "", -1);
        	$varid="Message: Confbridge conferences will follow";

        	//echo $vari."\n";
        	if(strcmp($vari,$varid)==0)
		{
                     return true;
		}
        }}
	return false;
}

function obtenerUsuarios()
{
	$this->iConfami();
	$usuarios=array();
	fputs($this->socket, "Action: ConfbridgeList\r\n");
	fputs($this->socket, "Conference: 1\r\n\r\n");
        fputs($this->socket, "Action: Logoff\r\n\r\n");

        while (!feof($this->socket)) {
        $wrets=fread($this->socket, 4096);
        $lines=explode("\n", $wrets);
        for($i=0;$i<sizeof($lines);$i++)
        {
                $vari=substr(substr_replace($lines[$i],"", -1),0,11);
                $varid="CallerIDNum";
		//echo $vari."\n";	
                if(strcmp($vari,$varid)==0)
                {
		     $participant=new Participante();
		     //echo "Cadenaaaaaa: "."--------->".substr_replace($lines[$i],"",0,13);
		     $participant->setNumero(substr((string)substr_replace($lines[$i],"",0,13),0,-1));
		     $i=$i+2;
		     $participant->setChannel(substr((string)substr_replace($lines[$i],"",0,9),0,-1));
		     $i=$i+3;
                     $participant->setMuted(substr((string)substr_replace($lines[$i],"",0,7),0,-1));
		     $i++;
		     array_push($usuarios,$participant);
		     //return true;
		     //echo "\n------>Este es el numero: ".$participant->getNumero()." \n chanel: ".$participant->getChannel()."\n muted: ".$participant->getMuted();
                }
        }}
        return $usuarios;
}

function kick($channel)
{
	$comando="Channel: ".$channel."\r\n\r\n";
	$this->iConfami();
        fputs($this->socket, "Action: ConfbridgeKick\r\n");
        fputs($this->socket, "Conference: 1\r\n");
        fputs($this->socket, $comando);
        fputs($this->socket, "Action: Logoff\r\n\r\n");
}

function mute($channel)
{
	$comando="Channel: ".$channel."\r\n\r\n";
	$this->iConfami();
        fputs($this->socket, "Action: ConfbridgeMute\r\n");
	fputs($this->socket, "Conference: 1\r\n");
        fputs($this->socket, $comando);
        fputs($this->socket, "Action: Logoff\r\n\r\n");
}

function unmute($channel)
{
	$comando="Channel: ".$channel."\r\n\r\n";
	$this->iConfami();
	fputs($this->socket, "Action: ConfbridgeUnmute\r\n");
        fputs($this->socket, "Conference: 1\r\n");
        fputs($this->socket, $comando);
        fputs($this->socket, "Action: Logoff\r\n\r\n");
}

function userSip()
{
	$this->iConfami();
	$usuarios=array();
	fputs($this->socket, "Action: SIPpeers\r\n\r\n");
        fputs($this->socket, "Action: Logoff\r\n\r\n");
        //while (!feof($this->socket)) {
	while (!feof($this->socket)) {
        $wrets=fread($this->socket, 4096);
        $lines=explode("\n", $wrets);
        for($i=0;$i<sizeof($lines);$i++)
        {
                $vari=substr_replace($lines[$i],"", -1);
                $varid="Channeltype: SIP";
                if(strcmp($vari,$varid)==0)
                {
		     $i++;
	 	     $user=substr((string)substr_replace($lines[$i],"",0,12),0,-1);
		     array_push($usuarios,$user);
		     //return true;
		     //echo "\n------>Este es el numero: ".$participant->getNumero()." \n chanel: ".$participant->getChannel()."\n muted: ".$participant->getMuted();
                }
        }}
	//fclose($socket);
        return $usuarios;
}

function reloadSip()
{
	$this->iConfami();
        fputs($this->socket, "Action: Command\r\n");
        fputs($this->socket, "Command: sip Reload\r\n\r\n");
	fputs($this->socket, "Action: Logoff\r\n\r\n");
}

}
?>

