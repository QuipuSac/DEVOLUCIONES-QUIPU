<?php
function Conexion($basedatos){
	//
	$adm= 'XOSCAR';
	$contraseña = 'QUIPU2846+*';
	$servidor = 'SERVIDOR'; 
	//$basedatos = @$_POST['nempresas'];
	//$usuario = @$_POST['nnombre'];
	//$pass = @$_POST['npassword'];

	$info = array('Database'=>@$basedatos, 'UID'=>@$adm, 'PWD'=>@$contraseña); 
	$conexion = sqlsrv_connect($servidor, $info); 
	
return $conexion;
}
?>
