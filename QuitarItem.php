<?php
$codigo=@$_POST['codigo'];
echo $codigo;
if(!@$codigo){
	//die( print_r( sqlsrv_errors(), true));
	echo "No hay conexion";
	
}
echo $codigo;

?>