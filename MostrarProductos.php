<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Buscar Producto</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 
</head>
<body class="container">
<div class="container">
		<?php
					require_once("conexion.php");
					session_start();
					$basedatos =@$_SESSION['basedatos'];
					$conexion=Conexion($basedatos);
					//$Codigo = @$_POST['ncodigo'];
					//$Descripcion = @$_POST['ndescripcion'];
					if(!@$conexion){
						//die( print_r( sqlsrv_errors(), true));
						echo "No hay conexion";
					}
					else{
						$sql ="SELECT A.Codigo as Codigo,A.Descripcion as Item ,A.PrecioVenta as PrecioVenta,B.Descripcion as Unidad
						FROM TBITEMS A inner join tbunidades B  on B.idunidad=A.idunidad
						WHERE A.Codigo LIKE '%' + ? + '%' AND A.Descripcion LIKE '%' + ? + '%'";
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
								echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
						      	echo "<tr class='active'><td>Código</td><td>Descripción</td><td>Precio</td><td>Unidad</td></tr> \n";
						      	while( $obj = sqlsrv_fetch_object( $rec)) {
						      	echo "<tr class='info'><td id='codigo'><a class='l1s'</a>".$obj->Codigo."</td><td>".$obj->Item."</td><td>".$obj->PrecioVenta."</td><td>".$obj->Unidad."</td></tr> \n";
								} 
								echo "</table></div></div>\n";
					/*
					echo $obj->Codigo. ", ".$obj->Descripcion. ", ".$obj->PrecioVenta."<br />";
					}
					*/
							}
					}
				?>
</div>
<script src="js/responsive.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

