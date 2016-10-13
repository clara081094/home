#!/usr/bin/php -q
<?php
function kick($sock,$canal)
{
	fputs($sock, "Action: ConfbridgeKick\r\n");
	fputs($sock, "Conference: 1\r\n");
	fputs($sock, "Channel: "."canal"."\r\n\r\n");
}

function mute($sock,$canal)
{
	fputs($sock, "Action: ConfbridgeMute\r\n");
        fputs($sock, "Conference: 1\r\n");
        fputs($sock, "Channel: "."canal"."\r\n\r\n");
}

$errno = "admin";
$errstr = "amp111";
$timeout = "30";

//Llenar los vectores
$canales=array("","","","");
$valores=array(0,0,0,0);

//Conectar al AMI
$socket = fsockopen("192.168.1.50","5038", $errno, $errstr, $timeout);
echo "socket: ".$socket;
if ($socket!=null){
fputs($socket, "Action: Login\r\n");
fputs($socket, "UserName: admin\r\n");
fputs($socket, "Secret: amp111\r\n\r\n");
fputs($socket, "Action: ConfbridgeList\r\n");
fputs($socket, "Conference: 1\r\n\r\n");
fputs($socket, "Action: Logoff\r\n\r\n");
 $x=0;
 //Ultima linea?
 while (!feof($socket)) {
    $wrets = fread($socket, 4096);
    $lines=explode("\n", $wrets);
    for($i=0;$i<sizeof($lines);$i++)
    {
	$oracion=$lines[$i];
	$sub="Channel:";
        //$vari=substr_replace($lines[$i], "", -1);
        //echo $vari."\n";
	echo $lines[$i]."\n"."-----------"."\n";
	//es la linea con informacion del channel?
	if(strpos($oracion,$sub)!==false)
	{
	  //no es la consola?
	  if(intval(substr($oracion,-12,2))!==11)
	  {
		$valor=substr($oracion,-9);
	  	$canales[$x]=substr($oracion,-16);
	  	$valores[$x]=intval($valor); 
	  	echo "Este es el canal: ".$canales[$x]." con su valor: ".$valores[$x]."\n";
	  }	$x++;
	}
    }
 }

 //ordenar burbuja
 if(x>0)
 {
    for($i=1;$i<count($valores);$i++)
    {
        for($j=0;$j<count($valores)-$i;$j++)
        {
            if($valores[$j]>$valores[$j+1])
            {
                $k=$valores[$j+1];
                $temp=$canales[$j+1];

                $valores[$j+1]=$valores[$j];
		$canales[$j+1]=$canales[$j];

                $valores[$j]=$k;
		$canales[$j]=$temp;
            }
        }
    }

    switch ($opcion) {
     case "81":
        echo "Mute primero";
	mute($socket,$canales[0]);
        break;
     case "82":
        echo "Mute segundo";
	mute($socket,$canales[1]);
        break;
     case "83":
        echo "Mute penultimo";
	mute($socket,$canales[2]);
        break;
     case "84":
        echo "Mute ultimo"
	mute($socket,$canales[3]);
	break:
     case "71":
        echo "Kick primero";
	kick($socket,$canales[0]);
        break;
     case "72":
        echo "Kick segundo";
	kick($socket,$canales[1]);
        break;
     case "73":
        echo "Kick penultimo";
	kick($socket,$canales[2]);
        break;
     case "74":
        echo "Kick ultimo"
	kick($socket,$canales[3]);
        break:
    }
 }


fclose($socket);
}

?>
