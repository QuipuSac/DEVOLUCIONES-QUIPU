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
	function Actualizar_pedido(valorCaja1, valorCaja2,valorCaja3,valorCaja4,valorCaja5){
		var parametros = {
	        "valorCaja1" : valorCaja1,
	        "valorCaja2" : valorCaja2,
	        "valorCaja3" : valorCaja3,
	        "valorCaja4" : valorCaja4,
	        "valorCaja5" : valorCaja5
	    };
	    $.ajax({
		        data:  parametros,
		        url:   'Editar_pedido.php',
		        type:  'post',
		    beforeSend: function () {
		        $("#guarda").html("Procesando, espere por favor...");
		    },
		    success:  function (response) {
		        $("#guarda").html(response);
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
//echo "RAZ SOCIAL" . $_POST['xcliente'];

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
		$sqlidTipoDocuReferencia="SELECT * FROM tbdocumentos WHERE NUMEDOCU = ? and IDDIARIO = 89 ";
		$paramsidTipoDocuReferencia = array($_POST['xpedido']);
		$queryidTipoDocuReferencia = sqlsrv_query($conexion,$sqlidTipoDocuReferencia,$paramsidTipoDocuReferencia);
		$rowidTipoDocuReferencia=sqlsrv_fetch_array($queryidTipoDocuReferencia);
		$idTipoDocuReferencia=$rowidTipoDocuReferencia['idTipoDocuReferencia'];
		/*if (isset($idTipoDocuReferencia)) {
			echo " " . $idTipoDocuReferencia;
		}else{
			echo"***-";
		}*/
		}else{
			echo"<script type=\"text/javascript\">alert('Seleccionar Pedido.');  window.location='BusquedaPedido.php';</script>";
		}


	}
	$sqlidUsuario ="SELECT *	FROM tbusuarios	WHERE cuenta = ? ";
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
	}
	//echo "<br> iddocumento: ". $IDDOCUMENTO;
	$sqldetalle = "SELECT * FROM TBDETCOMERCIAL WHERE IDDOCUMENTO=? " ;
	$paramsdetalle = array($IDDOCUMENTO);
	$querydetalle = sqlsrv_query($conexion,$sqldetalle,$paramsdetalle);
	while ($row=sqlsrv_fetch_array($querydetalle)){
		$idItem=$row['idItem'];
		$Cantidad=$row['Cantidad'];
		$Precio=$row['Precio'];
		$Descripcion=$row['Descripcion'];
		//echo "<br> idItem: ". $idItem;
		//echo "<br> Cantidad: ". $Cantidad;
		//echo "<br> Precio: ". $Precio;
		//echo "<br> Descripcion: ". $Descripcion;

		if (isset($idItem)){$id=$idItem;}
		if (isset($Cantidad)){$cantidad=$Cantidad;}
		if (isset($Precio)){$precio_venta=$Precio;}

		if (!empty($id) and !empty($cantidad) and !empty($precio_venta))
		{				
			$archivo = 'C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml';
			
			    //echo "El fichero $archivo no existe<br/>";
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
		     
		    $xml->formatOutput = true; 

		    $strings_xml = $xml->saveXML(); 

		    $xml->save('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml'); 

		    //echo 'Se acaba de crear por primera vez';
		    //echo "<p><b>Muestra</b></p>";
		    $xml = simplexml_load_file('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
		    $salida ="";
		    /*
		    foreach($xml->session as $item){
		      $salida .=
		        "<b>iditem:</b> " . $item->iditem . "<br/>".
		        "<b>cantidad:</b> " . $item->cantidad . "<br/>".
		        "<b>precio:</b> " . $item->precio_venta . "<br/><hr/>";
		    }
		    echo $salida;*/
			
			/*
			$xml = new DomDocument('1.0', 'UTF-8'); 
		    $root = $xml->createElement('detalle'); 
		    $root = $xml->appendChild($root); 

			$session=$xml->createElement('session'); 
		    $session =$root->appendChild($session); 

			$iditem=$xml->createElement('iditem',$id); 
		    $iditem =$session->appendChild($iditem); 

		    $cant=$xml->createElement('cantidad',$cantidad); 
		    $cant =$session->appendChild($cant); 
		     
		    $precio=$xml->createElement('precio_venta',$precio_venta); 
		    $precio=$session->appendChild($precio); 
		     
		    $xml->formatOutput = true; 

		    $strings_xml = $xml->saveXML(); 

		    $xml->save('detalle.xml'); 

		    echo 'Se añadió correctamente'; 
		*/	
		} 
	}
	
?>
<body class="container" background="img/login.JPG" onload="LEER();" >	
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
	<div class="panel bordepanel">
		  	<div class="panel-body">
		  	<form class="form-horizontal" role="form" id="datos_pedido" >
					<div class="form-group">
						<font color = "585858"><label class="control-label col-sm-2" id="valor1" >Ruc:</label></font>
					    <div class="col-sm-2">
					    	<input class="form-control" type="text" id="Ruc" value="<?php echo $_POST['xRUC']; ?>"  >
					    </div>
					    <font color = "585858"><label class="control-label col-sm-1" id="valor2" >Raz.Social:</label></font>
					  	<div class="col-sm-3">
					  		<input class="form-control" type="text" id="cliente" value="<?php echo $_POST['xcliente']; ?>" >
					  	</div>
					  	<div class="col-sm-4">
						  	<fieldset>		
						  		<font color = "585858"><label class="control-label "  id="valor1">Documento:</label></font>			  		
						  		<input name="documento2" id="documento1" type="radio" value="1" onClick="SeleccionaDocumento(1)"  />
								<font color = "585858"><label  id="valor">Factura:</label></font>
								<input name="documento2" id="documento3" type="radio" value="3" onClick="SeleccionaDocumento(3)" />
								<font color = "585858"><label id="valor">Boleta:</label></font>
							</fieldset>
						</div>
						</div>
					
						<div class="col-md-2">
							<input class="form-control" type="text" id="DOC"  onchange="Selectcombo(<?php echo $idTipoDocuReferencia; ?>)"
							value="<?php echo $idTipoDocuReferencia; ?>" style="visibility:hidden">
						</div>	
					  	<div class="col-md-10">
					       	<div class="pull-right" >
					       		<button type="submit" class="buttonpepdido" value="busqueda" href="javascript:;" 
					       		onclick="Actualizar_pedido($('#Ruc').val(), $('#cliente').val(), $('#DOC').val(), <?php echo $IDDOCUMENTO; ?>,<?php echo $_POST['xpedido']; ?> );return false;" value="Calcula"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar Pedido
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