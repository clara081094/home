<?php
include '/home/confAMI.php';
$conferencia= new Confami();
if($conferencia->conferenciaHay())
{
	//echo "\n Entra aca \n\n\n";
 	//$conferencia= new Confami();
	//$conferencia->log();
	$conferencia->obtenerUsuarios();
	//echo "\n"."TERMINO Y SI HAY\n";
}


?>
