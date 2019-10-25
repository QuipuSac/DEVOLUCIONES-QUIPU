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
			$sql="SELECT DISTINCT TBDETPRECIO.IDITEM ,TBDETPRECIO.IDUNIDAD AS IDUNIDAD, TBUNIDADES.DESCRIPCION AS UNIDAD FROM TBDETPRECIO 
				 INNER JOIN TBUNIDADES ON TBUNIDADES.IDUNIDAD=TBDETPRECIO.IDUNIDAD
				 INNER JOIN TBITEMS ON TBITEMS.IDITEM=TBDETPRECIO.IDITEM
				 where TBITEMS.CODIGO= ? OR TBITEMS.CodigoDistribuidor= ? ";
			$params = array($_GET['CODIGO'],$_GET['CODIGO']);
			$query = sqlsrv_query($conexion,$sql,$params);
				
			while ($row=sqlsrv_fetch_array($query)) {//while ($row=sqlsrv_fetch_array($sql))
				//echo $row['UNIDAD'];
				echo "<option value='".$row['IDUNIDAD']."'> ".$row['UNIDAD']."</option>";
			}
			/*
			if (is_numeric($idUnidad)) {
				echo $idUnidad;
			}else{
				echo 0.00;
			}
			*/
		}
	}	
?>