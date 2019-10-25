<?php
function Buscador($basedatos){

require_once("conexion.php");

session_start();
$basedatos =@$_SESSION['basedatos'];
$conexion=Conexion($basedatos);
//$usuario = @$_SESSION['usuario'];
//$pass = @$_SESSION['pass'];
$codigo=@$_POST['codigo'];
if(!@$conexion){
	//die( print_r( sqlsrv_errors(), true));
	echo "No hay conexion";
	echo $usuario;
	echo $basedatos;
	echo $pass;
}else{	
		$sql ="SELECT Descripcion FROM TBITEMS WHERE CODIGO=?";
		$params = array($codigo);
		$resultado = sqlsrv_query($conexion,$sql,$params);
		while ($row = sqlsrv_fetch_array($resultado)) {
				$row['Descripcion'];
		}
	}
	return $row['Descripcion'];
}
?>

