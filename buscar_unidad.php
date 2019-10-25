<?php

	require_once("conexion.php");
	session_start();
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	$usuario = @$_SESSION['usuario'];
	$pass = @$_SESSION['pass'];

	if(!@$conexion){
	
	echo "No hay conexion";
	}
	else{
		if (isset($_GET['CODIGO'])) {					
			$sql="SELECT idUnidad FROM tbitems WHERE codigo=? OR CodigoDistribuidor=? ";
			$params = array($_GET['CODIGO'],$_GET['CODIGO']);
			$query = sqlsrv_query($conexion,$sql,$params);
				
			$row=sqlsrv_fetch_array( $query , SQLSRV_FETCH_ASSOC );
			$idUnidad=$row['idUnidad'];
			if (is_numeric($idUnidad)) {
				echo $idUnidad;
			}else{
				echo 0.00;
			}
		}
	}	
?>