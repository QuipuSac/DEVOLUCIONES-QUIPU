
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
				$sql="SELECT * FROM tbitems WHERE codigo=? ";
				$params = array($_GET['CODIGO']);
				$query = sqlsrv_query($conexion,$sql,$params);
					
				$row=sqlsrv_fetch_array($query);
				$Descripcion=$row['Descripcion'];
				if (isset($Descripcion)) {
					echo $Descripcion;
				}else{
					echo"-";
				}
			}
		}	
?>