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
			$idvendedor=$_POST['valorCaja1'];
			$sqlCONSOLIDADO ="SELECT top 1 * FROM TBDOCUMENTOS WHERE IDTIPODOCU=238 AND IDVENDEDOR=? ORDER BY 1 DESC";
			$paramsCONSOLIDADO = array($idvendedor);
			$recCONSOLIDADO = sqlsrv_query($conexion,$sqlCONSOLIDADO,$paramsCONSOLIDADO);
			$rowCONSOLIDADO = sqlsrv_fetch_array( $recCONSOLIDADO, SQLSRV_FETCH_ASSOC) ;
			//echo  $rowpropiedades['valor'];
			if ($rowCONSOLIDADO['Estado']=='R') {
				$numerodoccc=$rowCONSOLIDADO['NumeDocu'];
				echo $numerodoccc;
				
			}else{
				echo "Falta Iniciar Consolidado de Devoluciones.";
			}
	}	
?>