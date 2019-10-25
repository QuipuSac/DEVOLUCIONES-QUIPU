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
					if(!@$conexion){
						echo "No hay conexion";
					}
					else{
						/*$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD
						FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD
						WHERE I.Codigo = ?   AND I.Descripcion LIKE '%' + ? + '%' 
							 OR I.CodigoDistribuidor = ? ";
						$params = array($_POST['valorCaja1'], $_POST['valorCaja2'], $_POST['valorCaja1']);
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
						      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
						      	while( $obj = sqlsrv_fetch_object( $rec)) {
						      	echo "<tr class='info'><td style='display:none'>".$obj->IDITEM."</td>
						      		<td >
										<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
											<span class='glyphicon glyphicon-arrow-up'></span>
										</button>
									</td>
						      		<td>".$obj->CODIGO."</td><td>".$obj->CODIGODIS."</td><td>".$obj->DESCRIPCION."</td><td>".$obj->PRECIO."</td><td>".$obj->UNIDAD."</td><td>".number_format($obj->FACTOR,0)."</td><td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td></tr> \n"; 
								} 
								echo "</table></div></div>\n";
							}*/
							if ($_POST['valorCaja1']=='' and $_POST['valorCaja2']=='' and $_POST['valorCaja3']=='')
							{	//echo "muestra todo";
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.Codigo LIKE '%' + ? + '%' AND I.Descripcion LIKE '%' + ? + '%' AND I.CodigoDistribuidor LIKE '%' + ? + '%' AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja1'], $_POST['valorCaja2'], $_POST['valorCaja3']);
								$rec = sqlsrv_query($conexion,$sql,$params);
								//
								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD WHERE I.Codigo LIKE '%' + ? + '%' AND I.Descripcion LIKE '%' + ? + '%' AND I.CodigoDistribuidor LIKE '%' + ? + '%' AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja1'], $_POST['valorCaja2'], $_POST['valorCaja3']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];

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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
							if ($_POST['valorCaja1']<>'' and $_POST['valorCaja2']=='' and $_POST['valorCaja3']=='')
							{	echo "busca por codigo";
								
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.Codigo = ?  AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja1']);
								$rec = sqlsrv_query($conexion,$sql,$params);

								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD WHERE I.Codigo = ?  AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja1']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];
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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
							if ($_POST['valorCaja1']=='' and $_POST['valorCaja2']<>'' and $_POST['valorCaja3']=='')
							{	echo "busca por Descripcion";
								
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.Descripcion LIKE '%' + ? + '%'  AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja2']);
								$rec = sqlsrv_query($conexion,$sql,$params);
								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD  WHERE I.Descripcion LIKE '%' + ? + '%'  AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja2']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];
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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
							if ($_POST['valorCaja1']=='' and $_POST['valorCaja2']=='' and $_POST['valorCaja3']<>'')
							{	echo "busca por cod.dis";
								
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.CodigoDistribuidor = ? AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja3']);
								$rec = sqlsrv_query($conexion,$sql,$params);
								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD WHERE I.CodigoDistribuidor = ? AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja3']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];
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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
							if ($_POST['valorCaja1']<>'' and $_POST['valorCaja2']<>'' and $_POST['valorCaja3']<>'')
							{	echo "busca por codigo,cod.dis,Descripcion";
								
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.Codigo LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND I.CodigoDistribuidor LIKE '%' + ? + '%' AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja1'], $_POST['valorCaja2'], $_POST['valorCaja3']);
								$rec = sqlsrv_query($conexion,$sql,$params);
								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD  WHERE I.Codigo LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND I.CodigoDistribuidor LIKE '%' + ? + '%' AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja1'], $_POST['valorCaja2'], $_POST['valorCaja3']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];
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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
							if ($_POST['valorCaja1']<>'' and $_POST['valorCaja2']<>'' and $_POST['valorCaja3']=='')
							{	echo "busca por codigo,cod.dis";
								
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.Codigo LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja1'], $_POST['valorCaja2']);
								$rec = sqlsrv_query($conexion,$sql,$params);
								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD WHERE I.Codigo LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja1'], $_POST['valorCaja2']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];
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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
							if ($_POST['valorCaja1']=='' and $_POST['valorCaja2']<>'' and $_POST['valorCaja3']<>'')
							{	echo "busca por cod.dis,Descripcion";
								
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.CodigoDistribuidor LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja3'], $_POST['valorCaja2']);
								$rec = sqlsrv_query($conexion,$sql,$params);
								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD WHERE I.CodigoDistribuidor LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja3'], $_POST['valorCaja2']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];
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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
							if ($_POST['valorCaja1']<>'' and $_POST['valorCaja2']=='' and $_POST['valorCaja3']<>'')
							{	echo "busca por codigo,Descripcion";
								
								$sql ="SELECT I.idItem AS IDITEM,I.Codigo AS CODIGO,I.CodigoDistribuidor AS CODIGODIS,I.Descripcion AS DESCRIPCION,D.Precio AS PRECIO,U.DESCRIPCION AS UNIDAD,D.FACTOR AS FACTOR, D.IDUNIDAD AS IDUNIDAD 
									FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD 
									WHERE I.Codigo LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND D.FACTOR=1 ORDER BY I.CODIGO ASC";
								$params = array($_POST['valorCaja1'], $_POST['valorCaja2']);
								$rec = sqlsrv_query($conexion,$sql,$params);
								$sqlcount="SELECT count(*) as NUMERO FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem INNER JOIN TBUNIDADES U ON U.IDUNIDAD=D.IDUNIDAD WHERE I.Codigo LIKE '%' + ? + '%'  AND I.Descripcion LIKE '%' + ? + '%' AND D.FACTOR=1";
								$paramscount = array($_POST['valorCaja1'], $_POST['valorCaja2']);
								$querycount = sqlsrv_query($conexion,$sqlcount,$paramscount);
									
								$row=sqlsrv_fetch_array( $querycount , SQLSRV_FETCH_ASSOC );
								$FILAS=$row['NUMERO'];
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
									echo "NRO.RESULTADOS: ".$FILAS;
									echo "<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' > \n";
							      	echo "<tr class='active'><td style='display:none'>IDITEM</td><td> </td><td>Código</td><td>Código Dis.</td><td>Descripción</td><td>Precio</td><td>Unidad</td><td>Factor</td><td style='display:none'>IDUNIDAD</td></tr> \n";
							      	while( $obj = sqlsrv_fetch_object( $rec)) {
							      		echo "
							      			<tr class='info'>
								      			<td style='display:none'>".$obj->IDITEM."</td>
									      		<td>
													<button type='button' class='buttondetallepedido' onclick='subirproducto2()'>
														<span class='glyphicon glyphicon-arrow-up'></span>
													</button>
												</td>
								      			<td>".$obj->CODIGO."</td>
								      			<td>".$obj->CODIGODIS."</td>
								      			<td>".$obj->DESCRIPCION."</td>
								      			<td>".$obj->PRECIO."</td>
								      			<td>".$obj->UNIDAD."</td>
								      			<td>".number_format($obj->FACTOR,0)."</td>
								      			<td style='display:none'>".number_format($obj->IDUNIDAD,0)."</td>
							      			</tr> \n";
									} 
									echo "</table></div></div>\n";
								}
							}
					}
				?>
</div>
<script src="js/responsive.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>