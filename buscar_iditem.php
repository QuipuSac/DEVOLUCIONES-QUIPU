
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
			$sql="SELECT idItem FROM tbitems WHERE codigo=? ";
			$params = array($_GET['CODIGO']);
			$query = sqlsrv_query($conexion,$sql,$params);
				
			$row=sqlsrv_fetch_array( $query , SQLSRV_FETCH_ASSOC );
			$idItem=$row['idItem'];
			if (is_numeric($idItem)) {
				echo $idItem;
			}else{
				echo 0.00;
			}
		}
	}	
?>