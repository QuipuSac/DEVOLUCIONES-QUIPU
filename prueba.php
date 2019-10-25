<?php

	require_once("conexion.php");
	session_start();
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
		//$Codigo = @$_POST['ncodigo'];
		//$Descripcion = @$_POST['ndescripcion'];
	echo $_POST['valorCaja1'];
	echo $_POST['valorCaja2'];
	if(!@$conexion){
	//die( print_r( sqlsrv_errors(), true));
	echo "No hay conexion";
	}
	else{
	$sql ="SELECT Codigo,Descripcion,PrecioVenta FROM TBITEMS 
	WHERE Codigo LIKE '%' + ? + '%' AND Descripcion LIKE '%' + ? + '%'";
	$params = array($_POST['valorCaja1'], $_POST['valorCaja2']);
	//$params = array($Codigo, $Descripcion);
	$rec = sqlsrv_query($conexion,$sql,$params);
	if (!$rec) {
	  	echo 'No se pudo ejecutar la consulta: ' ;
	   	if( ($errors = sqlsrv_errors() ) != null) {
	      	foreach( $errors as $error ) {
	           echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
	           echo "code: ".$error[ 'code']."<br />";
	           echo "message: ".$error[ 'message']."<br />";
	        	}
	   	}
	}else{
    echo "<div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
	echo "<tr class='active'><td>Código</td><td>Descripción</td><td>Precio</td></tr> \n";
	while( $obj = sqlsrv_fetch_object( $rec)) {
	    	echo "<tr class='info'><td>".$obj->Codigo."</td><td>".$obj->Descripcion."</td><td>".$obj->PrecioVenta."</td></tr> \n"; 
		} 
			echo "</table></div>\n";
		/*
		echo $obj->Codigo. ", ".$obj->Descripcion. ", ".$obj->PrecioVenta."<br />";
			}
			*/
			}
		}
	?>