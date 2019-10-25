<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Modificar Pedido</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<?php
		require_once("conexion.php");
		session_start();
		$basedatos =@$_SESSION['basedatos'];
		$conexion=Conexion($basedatos);
		$usuario = @$_SESSION['usuario'];
		$pass = @$_SESSION['pass'];
	?>
	<script>
	function realizaProceso(valorCaja1, valorCaja2){
	    var parametros = {
	        "valorCaja1" : valorCaja1,
	        "valorCaja2" : valorCaja2
	    };
	    $.ajax({
	        data:  parametros,
	        url:   'MostrarProductos.php',
	        type:  'post',
	        beforeSend: function () {
	            $("#resultado").html("Procesando, espere por favor...");
	        },
	        success:  function (response) {
	            $("#resultado").html(response);
	        }
	    });
	}
	</script>
<script >
	function Actualizar_pedido(valorCaja1, valorCaja2,valorCaja3,valorCaja4,valorCaja5,valorCaja6,valorCaja7,valorCaja8){
		var parametros = {
	        "valorCaja1" : valorCaja1,
	        "valorCaja2" : valorCaja2,
	        "valorCaja3" : valorCaja3,
	        "valorCaja4" : valorCaja4,
	        "valorCaja5" : valorCaja5,
	        "valorCaja6" : valorCaja6,
	        "valorCaja7" : valorCaja7,
	        "valorCaja8" : valorCaja8
	    };
	    $.ajax({
		        data:  parametros,
		        url:   'Editar_pedido.php',
		        type:  'post',
		    beforeSend: function () {
		        $("#guarda1").html("Procesando, espere por favor...");
		    },
		    success:  function (response) {
		        $("#guarda1").html(response);
		        alert(response);
		        window.location = "BusquedaPedido.php";
		    }
	    });		
	}
</script>
	<script>
		function SeleccionaDocumento(DOCU){
		var DOCUMENTO = DOCU;
		document.getElementById("DOC").value=DOCUMENTO;
		}
	</script>
	<script>
		function Selectcombo(idtipodocu){	
			var idTipoDocuReferencia=idtipodocu;
			if (idTipoDocuReferencia=3) {
			//document.getElementById("Ruc").value=idTipoDocuReferencia;
			document.getElementsByTagName('documento3').checked ==true;
			}else{
			document.getElementsByTagName('documento1').checked ==true;
			};
				
		}
	</script>
</head>

<?php
//echo "NRO PEDIDO " . $_POST['xpedido'] . "<br>";
//echo "RUC " . $_POST['xRUC'] . "<br>";
//echo "xdireccion " . $_POST['xdireccion'];
//date_default_timezone_set("America/Phoenix"); 
//echo $FechaEmision=date("d") . "/" . date("m") . "/" . date("Y");

	require_once("conexion.php");
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	$usuario = @$_SESSION['usuario'];
	$pass = @$_SESSION['pass'];

	if(!@$conexion){
	echo "No hay conexion";
	}
	else{
		if ($_POST['xpedido']){				
		$sqlidTipoDocuReferencia="SELECT * FROM tbdocumentos WHERE NUMEDOCU = ? and IDDIARIO = 89 and idtipodocu=215";
		$paramsidTipoDocuReferencia = array($_POST['xpedido']);
		$queryidTipoDocuReferencia = sqlsrv_query($conexion,$sqlidTipoDocuReferencia,$paramsidTipoDocuReferencia);
		$rowidTipoDocuReferencia=sqlsrv_fetch_array($queryidTipoDocuReferencia);
		$idTipoDocuReferencia=$rowidTipoDocuReferencia['idTipoDocuReferencia'];
		$formapago=$rowidTipoDocuReferencia['idFormaPago'];
		/*if (isset($idTipoDocuReferencia)) {
			echo " " . $idTipoDocuReferencia;
		}else{
			echo"***-";
		}*/
		}else{//window.location='BusquedaPedido.php';
			echo"<script type=\"text/javascript\">alert('Seleccionar Pedido.');  </script>";
		}


	}
	$sqlidUsuario ="SELECT * FROM tbusuarios WHERE cuenta = ? ";
	$paramsidUsuario = array($usuario);
						
	$rec = sqlsrv_query($conexion,$sqlidUsuario,$paramsidUsuario);
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
		$rowidUsuario = sqlsrv_fetch_array( $rec);
	}

	$sqliddocumento = "SELECT IDDOCUMENTO FROM TBDOCUMENTOS WHERE IDDIARIO=89 AND IDTIPODOCU=215 AND NUMEDOCU=? " ;
	$paramsiddocumento = array($_POST['xpedido']);
	$queryiddocumento = sqlsrv_query($conexion,$sqliddocumento,$paramsiddocumento);
	while ($row=sqlsrv_fetch_array($queryiddocumento)){
	$IDDOCUMENTO=$row['IDDOCUMENTO'];
	$sqldetalle = "SELECT * FROM TBDETCOMERCIAL WHERE IDDOCUMENTO=? " ;
	$paramsdetalle = array($IDDOCUMENTO);
	$querydetalle = sqlsrv_query($conexion,$sqldetalle,$paramsdetalle);

		while ($row=sqlsrv_fetch_array($querydetalle)){
		$idItem=$row['idItem'];
		$Cantidad=$row['Cantidad'];
		$Precio=$row['Precio'];
		$Descripcion=$row['Descripcion'];
		$unidad=$row['idUnidad'];
			//echo "<br> idItem: ". $idItem;
			//echo "<br> Cantidad: ". $Cantidad;
			//echo "<br> Precio: ". $Precio;
			//echo "<br> Descripcion: ". $Descripcion;

			if (isset($idItem)){$id=$idItem;}
			if (isset($Cantidad)){$cantidad=$Cantidad;}
			if (isset($Precio)){$precio_venta=$Precio;}

			if (!empty($id) and !empty($cantidad) and !empty($precio_venta) and !empty($unidad))
			{				
				$archivo = 'C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml';
				if(file_exists($archivo)){
					$library = new SimpleXMLElement('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml', null, true);
				    $session = $library->addChild('session', $id);
				    $session->addAttribute('iditem', $id);
					$session->addChild('iditem',$id);
					$session->addChild('cantidad',$cantidad);
					$session->addChild('precio_venta',$precio_venta );
					$session->addChild('unidad',$unidad);
			    
			    	$library->asXML('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
			    	$xml = simplexml_load_file('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
			    
				}
				else{
					$xml = new DomDocument('1.0', 'UTF-8'); 
				    $root = $xml->createElement('detalle'); 
				    $root = $xml->appendChild($root); 

					$session=$xml->createElement('session',$id);
				    $session =$root->appendChild($session); 
					$session->setAttribute('iditem',$id);

					$iditem=$xml->createElement('iditem',$id); 
				    $iditem =$session->appendChild($iditem); 

				    $cant=$xml->createElement('cantidad',$cantidad); 
				    $cant =$session->appendChild($cant); 
				     
				    $precio=$xml->createElement('precio_venta',$precio_venta); 
				    $precio=$session->appendChild($precio); 
				     						     
					$und=$xml->createElement('unidad',$unidad); 
					$und=$session->appendChild($und); 
							     
				    $xml->formatOutput = true; 

				    $strings_xml = $xml->saveXML(); 

				    $xml->save('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml'); 

				    //echo 'Se acaba de crear por primera vez';
				    //echo "<p><b>Muestra</b></p>";
				    $xml = simplexml_load_file('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
				    $salida ="";
				}

				$sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
				$paramspropiedades = array('BONIWEB');
				$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
				$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
				//echo  $rowpropiedades['valor'];

				if ($rowpropiedades['valor']=='S') {
					//VARIABLES 
					$querybonificacion ="SELECT TOP 1 B.iditemboni AS idItem,I.Codigo AS Codigo,B.DESCRIPCION AS Descripcion,
									B.CANTBONI AS CANTIDAD,B.PRECIO AS precioesp,B.iditem as itembonificador,B.CANTIDAD AS FACTOR
									,B.IDUNIDADBONI AS UNIDAD
									FROM tbbonificaciones B INNER JOIN TBITEMS I ON I.idItem=B.iditemboni 
									WHERE B.idItem = ? AND B.cantidad <=? AND 0 < B.cantidad
									ORDER BY  FACTOR DESC";
					$paramsbonificacion = array(array($id),array($cantidad));
					$sqlbonificacion=sqlsrv_query($conexion,$querybonificacion,$paramsbonificacion);
					while ($rowbonificacion=sqlsrv_fetch_array($sqlbonificacion)){
						$archivobonificacion = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml';
						if(file_exists($archivobonificacion)){

							$library = new SimpleXMLElement('C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml', null, true);
							$session = $library->addChild('session', $rowbonificacion['itembonificador']);
							$session->addAttribute('itembonificador', $rowbonificacion['itembonificador']);
							$session->addChild('iditem',$rowbonificacion['idItem']);
							$session->addChild('codigo',$rowbonificacion['Codigo']);
							$session->addChild('descripcion',$rowbonificacion['Descripcion']);
							$session->addChild('cantidad',$rowbonificacion['CANTIDAD']);
							$session->addChild('precio_venta',$rowbonificacion['precioesp']);
							$session->addChild('itembonificador',$rowbonificacion['itembonificador']);
							$session->addChild('unidad',$rowbonificacion['UNIDAD']);
											    
							$library->asXML('C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
							$xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');			    
						} else {
							$xml = new DomDocument('1.0', 'UTF-8'); 
						    $root = $xml->createElement('detalle'); 
						    $root = $xml->appendChild($root); 

							$session=$xml->createElement('session',$rowbonificacion['itembonificador']);
						    $session =$root->appendChild($session); 
							$session->setAttribute('itembonificador',$rowbonificacion['itembonificador']);

							$iditem=$xml->createElement('iditem',$rowbonificacion['idItem']); 
							$iditem =$session->appendChild($iditem); 

							$codigo=$xml->createElement('codigo',$rowbonificacion['Codigo']); 
							$codigo =$session->appendChild($codigo); 

							$descripcion=$xml->createElement('descripcion',$rowbonificacion['Descripcion']); 
							$descripcion =$session->appendChild($descripcion); 

							$cant=$xml->createElement('cantidad',$rowbonificacion['CANTIDAD']); 
							$cant =$session->appendChild($cant); 

							$precio=$xml->createElement('precio_venta',$rowbonificacion['precioesp']); 
							$precio=$session->appendChild($precio); 

							$itembonificador=$xml->createElement('itembonificador',$rowbonificacion['itembonificador']); 
							$itembonificador=$session->appendChild($itembonificador); 

							$IDUND=$xml->createElement('unidad',$rowbonificacion['UNIDAD']); 
							$IDUND=$session->appendChild($IDUND); 

							$xml->formatOutput = true; 

							$strings_xml = $xml->saveXML(); 

							$xml->save('C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml'); 
												    
							$xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
							$salida ="";
						}
					}
						
				}
			} 
		}
		$sqlIDBONIFICACION = "SELECT IDDOCUMENTO FROM TBDOCUMENTOS WHERE IDDIARIO=89 AND IDTIPODOCU=234 AND NumeDocuReferencia=? " ;
		$paramsIDBONIFICACION = array($_POST['xpedido']);
		$queryIDBONIFICACION = sqlsrv_query($conexion,$sqlIDBONIFICACION,$paramsIDBONIFICACION);
		$rowBONIFICACION=sqlsrv_fetch_array($queryIDBONIFICACION);
		$IDDOCUMENTOBONIFICACION=$rowBONIFICACION['IDDOCUMENTO'];
		if (!@$IDDOCUMENTOBONIFICACION) {
			$IDDOCUMENTOBONIFICACION=0;
			//echo"<script type=\"text/javascript\">alert('IDBONIFICACION: $IDDOCUMENTOBONIFICACION');</script>";
		}else{
			//echo"<script type=\"text/javascript\">alert('IDBONIFICACION: $IDDOCUMENTOBONIFICACION');</script>";
		}
	//echo "<br> IDDOCUMENTOBONIFICACION: ". $rowBONIFICACION['IDDOCUMENTO'];
	
	//echo "<br> iddocumento: ". $IDDOCUMENTO;
	
	//echo "<br> NUMERO PEDIDO: ". $_POST['xpedido'];
	
	
	}
	
?>
<body class="container" background="img/login.JPG" onload="LEER(),LEERBONIFICACION(),ocultarbonificacion()" >	
<div >
<center>

	<BR>
		<form method="POST" action="BusquedaPedido.php">
		<button type="submit" class="btn btn-default" >Regresar</button>
		</form>
	<BR>
</center>
<div class="panel bordepanel">
	<div class="panel-heading" STYLE="background-color:#6E6E6E">
	    	<font color = "FFFFFF"><h3 class="panel-title">Pedido: <?php echo $_POST['xpedido']; ?></h3></font>
	</div>
	<div class="panel-body">
<div id="resultados" class='col-md-12'></div>
	<div class="pull-right">
		<button type="button" class="buttonpepdido" data-toggle="modal" data-target="#myModal">
			<span class="glyphicon glyphicon-plus"></span> Agregar Productos
		</button>
	</div>	
</div><!-- Carga los datos ajax -->
</div>
<script>
	function ocultarbonificacion(){
	<?php
		require_once("conexion.php");
		session_start();
		$basedatos =@$_SESSION['basedatos'];
		$conexion=Conexion($basedatos);
		$usuario = @$_SESSION['usuario'];
		$pass = @$_SESSION['pass'];

		if(!@$conexion){
		//die( print_r( sqlsrv_errors(), true));
		echo "No hay conexion";
		}
		else{
				$sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
				$paramspropiedades = array('BONIWEB');
				$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
				$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
				//echo  $rowpropiedades['valor'];
				if ($rowpropiedades['valor']=='S') {
					$varvisible='block';
				}
				else{
					$varvisible='none';
				}
		}?>
	}
	</script>

	<div id='bonificacion' class="panel bordepanel" style="display:<?PHP echo $varvisible?>;">
		<div class="panel-heading" STYLE="background-color:#6E6E6E">
		    	<font color = "FFFFFF"><h3 class="panel-title">Bonificación</h3></font>
		</div>
		<div class="panel-body">
			<div id="resultadosbonificacion" class='col-md-12'></div>
		</div><!-- Carga los datos ajax -->
	</div>
	<div class="panel bordepanel">
			<div class="panel-heading" STYLE="background-color:#6E6E6E">
	    		<font color = "FFFFFF"><h3 class="panel-title">Cliente</h3></font>
	 		</div>
		  	<div class="panel-body">
		  	<form class="form-horizontal" role="form" id="datos_pedido" >
					<input class="form-control" type="text" id="DOC" onchange="Selectcombo(<?php echo $idTipoDocuReferencia; ?>)" value="<?php echo $idTipoDocuReferencia; ?>" style="visibility:hidden">

					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="valor1">Ruc:</span>
							    <input id="Ruc" type="text" class="form-control"  placeholder="Ruc" value="<?php echo $_POST['xRUC']; ?>" 
							    onClick="this.select()" onchange="JalarRazSocial($('#Ruc').val())">
						</div>
						<div class="col-sm-5">
							    <span class="input-group-addon" id="valor1">Raz.Social:</span>
							    <input class="form-control" type="text" id="cliente" value="<?php echo $_POST['xcliente']; ?>" placeholder="Raz.Social" 
						  		onClick="this.select()">
						</div>
						
					</div>
					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-8">
							    <span class="input-group-addon" id="valor1">Dirección:</span>
							    <input class="form-control" type="text" id="direccion" value="<?php echo $_POST['xdireccion']; ?>" placeholder="Dirección" onClick="this.select()" >
						</div>
					</div>
					<div class="row">
							<div class="col-sm-2"></div>
							<div class="col-sm-4"></div>
							<div class="col-sm-4">
								<div class="pull-right" >
								  	<font color = "585858"><label  id="valor1">Documento:</label></font>			  		
								  	<input name="documento2" id="documento1" type="radio" value="1" onClick="SeleccionaDocumento(1)" 
								  	<?php if((int)$idTipoDocuReferencia==1){echo "checked";} ?> />
									<font color = "585858"><label  id="valorticket">Factura</label></font>
									<input name="documento2" id="documento3" type="radio" value="3" onClick="SeleccionaDocumento(3)"
									<?php if((int)$idTipoDocuReferencia==3){echo "checked";} ?> />
									<font color = "585858"><label id="valorticket">Boleta</label></font>
							  	</div>
								
							</div>
					</div>
					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-5">	
							<div class="pull-right" >
							  	<select class="form-control" data-width="auto" id="fpago" >
							  			
									<?php 
										$sqlfpago ="SELECT * FROM TBPROPIEDADES where tipo= ? ";
										$paramsfpago = array('CONPAG');
										$recfpago = sqlsrv_query($conexion,$sqlfpago,$paramsfpago);
										while ($rowfpago=sqlsrv_fetch_array($recfpago)){
											$clave=$rowfpago['Clave'];
											$valor=$rowfpago['Valor'];

									?>
										<option value="<?php echo $clave;?>" <?php if((int)$formapago==(int)$clave){echo "selected";} ?>><?php echo $valor;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>	
					</div>
					</div>
					
						<div class="col-md-2">
							<input class="form-control" type="text" id="DOC"  onchange="Selectcombo(<?php echo $idTipoDocuReferencia; ?>)"
							value="<?php echo $idTipoDocuReferencia; ?>" style="visibility:hidden">
						</div>	
					  	<div class="col-md-10">
					       	<div class="pull-right" >
					       		<button type="submit" class="buttonpepdido" value="busqueda" href="javascript:;" 
					       		onclick="Actualizar_pedido($('#Ruc').val(), $('#cliente').val(), $('#DOC').val(), <?php echo $IDDOCUMENTO; ?>,<?php echo $_POST['xpedido']; ?>,'-',<?php echo $IDDOCUMENTOBONIFICACION;?>,$('#fpago').val() );return false;" value="Calcula"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Pedido
					       		</button>
					       	</div>
						</div>
		  	</form>
		  	</div>
	</div>
	<div id="guarda" class='col-md-12'></div>
</div>
<!-- nuevo -->
<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Buscar productos</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <div class="form-group">
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="q" placeholder="Buscar productos" onkeyup="load(1)">
						</div>
						<button type="button" class="btn btn-default" onclick="load(1)"><span class='glyphicon glyphicon-search'></span> Buscar</button>
					  </div>
					</form>
					<div id="loader" style="position: absolute;	text-align: center;	top: 55px;	width: 100%;display:none;"></div><!-- Carga gif animado -->
					<div class="outer_div" ></div><!-- Datos ajax Final -->
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				  </div>
				</div>
			  </div>
			</div>
<!-- nuevo -->
<script src="js/responsive.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- script nuevo-->
<script>
		$(document).ready(function(){
			load(1);
		});
		function load(page){
			var q= $("#q").val();
			var parametros={"action":"ajax","page":page,"q":q};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'MostrarProductos2.php',
				data: parametros,
				 beforeSend: function(objeto){
				 $('#loader').html(' Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
				}
			})
		}
	</script>
	<script>
		function LEER()
		{
			$.ajax({

	        type: "GET",
	        url: "LEERXML.PHP",
					beforeSend: function(){
					$("#resultados").html("Mensaje: Cargando Lista ...");
					},
			        success: function(datos){
					$("#resultados").html(datos);
					}
			});
		}
	</script>
	<script>
		function LEERBONIFICACION()
		{
			$.ajax({

	        type: "GET",
	        url: "LEERXMLBONIFICACION.PHP",
					beforeSend: function(){
					$("#resultadosbonificacion").html("Mensaje: Cargando Lista ...");
					},
			        success: function(datos){
					$("#resultadosbonificacion").html(datos);
					}
			});
		}
	</script>
	<script>
	function agregar (id)
		{	
			var precio_venta=$('#precio_venta_'+id).val();
			var cantidad=$('#cantidad_'+id).val();
			//Inicia validacion
			if (isNaN(cantidad))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+id).focus();
			return false;
			}
			if (isNaN(precio_venta))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+id).focus();
			return false;
			}
			//Fin validacion
			var parametros={"id":id,"precio_venta":precio_venta,"cantidad":cantidad};	
			$.ajax({
	        type: "POST",
	        url: "agregar_producto.php",
	        data: parametros,
					beforeSend: function(objeto){
					$("#resultados").html("Mensaje: Cargando...");
					},
			        success: function(datos){
					$("#resultados").html(datos);
					}
			});
		}
	function eliminar (id)
		{
			$.ajax({
        type: "GET",
        url: "agregar_producto.php",
        data: "id="+id,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});

		}
		$("#datos_pedido").submit(function(){
		  var proveedor = $("#Ruc").val();
		  var transporte = $("#cliente").val();
		  var condiciones = $("#numedocu").val();
		  var comentarios = $("#comentarios").val();
		  
			VentanaCentrada('./pdf/documentos/pedido_pdf.php?proveedor='+proveedor+'&transporte='+transporte+'&condiciones='+condiciones,'Pedido','','1024','768','true');	
		});
	</script>
</body>
</html>