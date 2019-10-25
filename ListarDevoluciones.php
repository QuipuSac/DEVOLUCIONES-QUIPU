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
			$usuario = @$_SESSION['usuario'];
			$conexion=Conexion($basedatos);
					//$Codigo = @$_POST['ncodigo'];
					//$Descripcion = @$_POST['ndescripcion'];
			if(!@$conexion){
						//die( print_r( sqlsrv_errors(), true));
				echo "No hay conexion";
			}
			else{
				$sqlCUENTA ="SELECT *	FROM tbusuarios	WHERE cuenta = ? ";
				$paramsCUENTA = array($usuario);
				$recCUENTA = sqlsrv_query($conexion,$sqlCUENTA,$paramsCUENTA);
				$row = sqlsrv_fetch_array( $recCUENTA);
				$row['idUsuario'];

				$vendedor = $row['Nombres']." ".$row['Apellidos'];
		
				$sqlvendedor ="SELECT *	FROM tbclieprov	WHERE Descripcion = ? ";
				$paramsvendedor = array($vendedor);				
				$recvendedor = sqlsrv_query($conexion,$sqlvendedor,$paramsvendedor);
				$filavendedor = sqlsrv_fetch_array( $recvendedor, SQLSRV_FETCH_ASSOC);
				$idUsuario2=$filavendedor['idClieProv'];

					$sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
					$paramspropiedades = array('CONSP');
					$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
					$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
				
					if ($rowpropiedades['valor']=='S') {
						$sql ="SELECT DOC.IDDOCUMENTO as IDDOCUMENTO,DOC.NUMEDOCU as PEDIDO,CONVERT(VARCHAR(10),DOC.FECHAEMISION,103) as FECHAEMISION
						,DOC.RUC as RUC,DOC.NOMBRECLIEPROV as NOMBRECLIEPROV,DOC.TOTAL as TOTAL,
						DOC.DireccionClieProv as DIRECCION, DOC.NUMEDOCUREFERENCIA AS REFE  FROM TBDOCUMENTOS
						INNER JOIN TBCAJAS ON TBCAJAS.IDDOCUMENTOCAJA=TBDOCUMENTOS.IDDOCUMENTO
						INNER JOIN TBDOCUMENTOS DOC ON DOC.IDDOCUMENTO=TBCAJAS.IDDOCUMENTOOPERACION
						WHERE DOC.RUC LIKE '%' + ? + '%' AND DOC.NOMBRECLIEPROV LIKE '%' + ? + '%' 
						AND TBDOCUMENTOS.SERIDOCU= ? AND TBDOCUMENTOS.NUMEDOCU= ? AND DOC.NUMEDOCU LIKE '%' + ? + '%' AND TBDOCUMENTOS.IDDIARIO=89 AND DOC.ESTADO='R' AND TBDOCUMENTOS.DESCRIPCION ='CONSOLIDADO DEVOLUCIONES' AND DOC.IDTIPODOCU=236 order by 1 desc";
					}
					else{
						$sql ="SELECT iddocumento as IDDOCUMENTO,NUMEDOCU as PEDIDO,CONVERT(VARCHAR(10),FECHAEMISION,103) as FECHAEMISION,RUC,NOMBRECLIEPROV,TOTAL,
						DireccionClieProv as DIRECCION FROM TBDOCUMENTOS
						WHERE RUC LIKE '%' + ? + '%' AND NOMBRECLIEPROV LIKE '%' + ? + '%' AND IDDIARIO=89 and IDTIPODOCU=236 
						and CONVERT(VARCHAR(10),FECHAEMISION,10)=CONVERT(VARCHAR(10),GETDATE(),10) AND ESTADO='R' AND DESCRIPCION ='PROFORMAS' order by 1 desc ";
					}				
				
				$params = array($_POST['ruc'], $_POST['cliente'],$_POST['sericonsolidado'],$_POST['numconsolidado'],$_POST['Pedido']);
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
						      	echo "<tr class='active'><th>Devolución</th><th>F.Emisión</th><th>RUC</th><th>Cliente</th><th>Pedido</th><th>TOTAL</th><th ></th></tr> \n";
						      	while( $obj = sqlsrv_fetch_object( $rec)) {
						      	echo "<tr class='info'><th>".$obj->PEDIDO."</button></th><td>".$obj->FECHAEMISION."</td><td>".$obj->RUC."</td><td>".$obj->NOMBRECLIEPROV."</td><td>".$obj->REFE."</td><td>".number_format($obj->TOTAL, 2, '.', '')."</td><td style='display:none'>".$obj->DIRECCION."</td>
								<td style='display:none'>".$obj->PEDIDO."</td>
								<th><button type='button' class='buttontabla2' id='detalle' 
								data-toggle='modal' data-target='#myModal' onclick='load(".$obj->IDDOCUMENTO.")''><span class='glyphicon glyphicon-list-alt'></span></th></tr> \n"; 
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