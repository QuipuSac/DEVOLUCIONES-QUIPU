
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
			if (isset($_GET['Roc'])) {					
				$sql="SELECT * FROM tbclieprov WHERE TIPOCLIEPROV='C' and RUC = ? ";
				$params = array($_GET['Roc']);
				$query = sqlsrv_query($conexion,$sql,$params);
					
				$row=sqlsrv_fetch_array($query);
				$cliente=$row['Descripcion'];
				if (isset($cliente)) {
					echo $cliente;
				}else{
					echo"-";
				}
			}
		}	
?>