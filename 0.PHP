<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nuevo Pedidos</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
	<script>
	function borrarXML(){
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
		$sql ="SELECT *	FROM tbusuarios	WHERE cuenta = ? ";
		$params = array($usuario);
							
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
			$row = sqlsrv_fetch_array( $rec);
			$archivo = 'C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
			if(file_exists($archivo))
			{	
				unlink('C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
			}
		}
		}?>
	}
</script>
<script>
		function CargaXML(DOCU){
		var DOCUMENTO = DOCU;
		document.getElementById("DOC").value=DOCUMENTO;
		}
	</script>
<script >
	function Actualizar_pedido(valorCaja1, valorCaja2,valorCaja3){
		var parametros = {
	        "valorCaja1" : valorCaja1,
	        "valorCaja2" : valorCaja2,
	        "valorCaja3" : valorCaja3
	    };
	    $.ajax({
		        data:  parametros,
		        url:   'Guardar_pedido.php',
		        type:  'post',
		    beforeSend: function () {
		        $("#guarda").html("Procesando, espere por favor...");
		    },
		    success:  function (response) {
		        $("#guarda").html(response);
		        alert(response);
		        window.location = "Menu.php";
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
			document.getElementById("Ruc").value=idTipoDocuReferencia;
			document.getElementsByTagName('documento3').checked ==true;
			}else{
			document.getElementsByTagName('documento1').checked ==true;
			};
				
		}
	</script>
</head>

<?php
//echo "NRO PEDIDO" . $_POST['pedido'] . "<br>";
//echo "RUC" . $_POST['ruc'] . "<br>";
//echo "RAZ SOCIAL" . $_POST['cliente'];

	require_once("conexion.php");
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	$usuario = @$_SESSION['usuario'];
	$pass = @$_SESSION['pass'];

	if(!@$conexion){
	echo "No hay conexion";
	}
	else{
		if (isset($_POST['pedido']))
			{				
				$sql="SELECT * FROM tbdocumentos WHERE NUMEDOCU = ? and IDDIARIO = 89 ";
				$params = array($_POST['pedido']);
				$query = sqlsrv_query($conexion,$sql,$params);

				$row=sqlsrv_fetch_array($query);
				$idTipoDocuReferencia=$row['idTipoDocuReferencia'];
				if (isset($idTipoDocuReferencia)) {
					echo " " . $idTipoDocuReferencia;
				}else{
					echo"***-";
				}
			}
		}	
?>
<body class="container" background="img/login.JPG" onload="borrarXML()">	
<div >
<center>

	<BR>
		<form method="POST" action="Menu.php">
		<button type="submit" class="btn btn-default">Menú</button>
		</form>
	<BR>
</center>
<div class="panel bordepanel">
	<div class="panel-heading" STYLE="background-color:#6E6E6E">
	    	<font color = "FFFFFF"><h3 class="panel-title">Pedido</h3></font>
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
					    	<input class="form-control" type="text" id="Ruc" value="<?php echo $_POST['ruc']; ?>" readonly="readonly" >
					    </div>
					    <font color = "585858"><label class="control-label col-sm-1" id="valor2" >Raz.Social:</label></font>
					  	<div class="col-sm-3">
					  		<input class="form-control" type="text" id="cliente" value="<?php echo $_POST['cliente']; ?>" readonly="readonly">
					  	</div>
					  	<fieldset>		
					  		<font color = "585858"><label class="control-label "  id="valor1">Documento:</label></font>			  		
					  		<input name="documento2" id="documento1" type="radio" value="1" onClick="SeleccionaDocumento(1)"  />
							<font color = "585858"><label  id="valor">Factura:</label></font>
							<input name="documento2" id="documento3" type="radio" value="3" onClick="SeleccionaDocumento(3)" />
							<font color = "585858"><label id="valor">Boleta:</label></font>
						</fieldset>		
						<input class="form-control" type="text" id="DOC"  onchange="Selectcombo(<?php echo $idTipoDocuReferencia; ?>)"
						value="<?php echo $idTipoDocuReferencia; ?>" style="visibility:hidden" >
						<div id="RAZON" class='col-md-12'></div>
					</div>
				  	<div class="col-md-12">
				       	<div class="pull-right" >
				       		<button type="submit" class="buttonpepdido" value="busqueda" href="javascript:;" 
				       		onclick="Guardar_pedido($('#Ruc').val(), $('#cliente').val(), $('#DOC').val());return false;" value="Calcula"><span class="glyphicon glyphicon-floppy-disk"></span> Enviar Pedido
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
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
				}
			})
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