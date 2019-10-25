<?php
$session_id= session_id();

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
		if (is_numeric($_GET['id']) and isset($_GET['cantidad']) and isset($_GET['idunidad']))
			{	
				$sql ="SELECT TOP 1 D.PRECIO AS Precio	FROM tbdetprecio D 
				WHERE D.idItem = ? and D.Factor <= ? and 0 < D.Factor and D.IdUnidad= ? ORDER BY 1 ASC" ;
				$params = array($_GET['id'],$_GET['cantidad'],$_GET['idunidad']);
				$query = sqlsrv_query($conexion,$sql,$params);
				$row=sqlsrv_fetch_array( $query , SQLSRV_FETCH_ASSOC );
					$precio=$row['Precio']; 
				if (isset($precio)) {
					echo $precio;
				}else{
					echo"0.00";
				}
			}
		else{
			echo "string";
		}
	}
?>