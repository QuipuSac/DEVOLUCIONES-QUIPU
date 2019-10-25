<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nuevo Pedido</title>
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
		function Calculo(){
		var precio = document.getElementById("valor5").value;
		var cantidad= document.getElementById("valor4").value;
		var total=(Math.round((precio*cantidad) * 100) / 100)
		document.getElementById("valor6").value=total;
		}
	</script>
	<script>
		function Agregar2(valorCaja1, valorCaja2){
	        	var descripcion= document.getElementById("valor2").value;
				var unidad= document.getElementById("valor3").value;
				var cantidad= document.getElementById("valor4").value;
				var precio = document.getElementById("valor5").value;
				var total= document.getElementById("valor6").value;
				document.getElementById("valor1").value="";
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
					$archivodevolucion = 'C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
					$archivobonificacion = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
					if(file_exists($archivo))
					{	
						unlink('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
						unlink('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
					}
				}
			}?>
		}
	</script>
	<script >
		function Guardar_devolucion(xRUC, xcliente,xdireccion,xpedido,IDCONSOLIDADO,IDVENDEDOR){
			//INCLUYE BONIFICACION
			var parametros = {
		                "xRUC" : xRUC,//$_POST['xRUC']
		                "xcliente" : xcliente,//$_POST['xcliente']
		                "xdireccion" : xdireccion,//$_POST['xdireccion']
		                "xpedido" : xpedido,//$_POST['NUMEROPEDIDO']
		                "IDCONSOLIDADO" : IDCONSOLIDADO,//$_POST['IDCONSOLIDADO']
		                "IDVENDEDOR": IDVENDEDOR//$_POST['IDVENDEDOR']
		        };
		        $.ajax({
		                data:  parametros,
		                url:   'Guardar_pedido.php',
		                type:  'post',
		                beforeSend: function () {
		                        $("#guarda1").html("Procesando, espere por favor...");
		                },
		                success:  function (response) {
		                        $("#guarda1").html(response);
		                        //alert(response);
		                        window.location = "Menu.php";
		                }
		        });		
		}
	</script>
	<script >
		function Guardar_bonificacion(valorCaja1, valorCaja2,valorCaja3,valorCaja4){
		//NO SE USA
			var parametros = {
			"valorCaja1" : valorCaja1,
			"valorCaja2" : valorCaja2,
			"valorCaja3" : valorCaja3,
			"valorCaja4" : valorCaja4
			};
			$.ajax({
				data:  parametros,
				url:   'Guardar_bonificacion.php',
				type:  'post',
				beforeSend: function () {
					$("#guarda2").html("Procesando, espere por favor...");
				},
				success:  function (response) {
					$("#guarda2").html(response);
					//alert(response);
					//window.location = "Menu.php";
				}
			});		
		}
	</script>
	<script>
		function SeleccionaDocumento(DOC){
		var DOCUMENTO = DOC;
		document.getElementById("DOC").value=DOCUMENTO;
		}
	</script>
	<script>
		function JalarRazSocial(Ruc){	
			var parametros={"Roc":Ruc};	
			$.ajax({
			type: "GET",
			url: "buscar_cliente.php",
			data: parametros,
				success: function(datos){
					// $("#RAZON").html(datos);
				document.getElementById("cliente").value=datos;
				}
			});
		}
	</script>
	<script>
		function valueselect(){
			var i = document.getElementById('fpago');
			var p = i.options[i.selectedIndex].value;
			document.getElementById('pago').value=p;//ya no se usa
		}
	</script> 
</head>
<body class="container" background="img/login.JPG" onload="borrarXML(),ocultarbonificacion()">	

<div>
<center>

	<BR>
		<form method="POST" action="Menu.php">
		<button type="submit" class="btn btn-default">Menú</button>
		</form>
	<BR>
</center>
	<div class="panel bordepanel">
		<div class="panel-heading" STYLE="background-color:#6E6E6E">
		    	<font color = "FFFFFF"><h3 class="panel-title">Devolución</h3></font>
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
			<div class="panel-heading" STYLE="background-color:#6E6E6E">
	    		<font color = "FFFFFF"><h3 class="panel-title">Cliente</h3></font>
	 		</div>
		  	<div class="panel-body">
		  	<form class="form-horizontal" role="form" id="datos_pedido" >
					<input class="form-control" type="text" id="DOC" value="3" style="display:none">
					<input class="form-control" type="text" id="pago" value="0" style="display:none">

					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="valor1">Ruc:</span>
							    <input id="Ruc" type="text" class="form-control"  placeholder="Ruc" 
							    value=<?php echo $_POST['xRUC']; ?> onClick="this.select()" onchange="JalarRazSocial($('#Ruc').val())">
						</div>
						<div class="col-sm-5">
							    <span class="input-group-addon" id="valor1">Raz.Social:</span>
							    <input class="form-control" value=<?php echo $_POST['xcliente']; ?> type="text" 
							    id="cliente"  placeholder="Raz.Social" onClick="this.select()">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-8">
							    <span class="input-group-addon" id="valor1">Dirección:</span>
							    <input class="form-control" type="text" id="direccion" 
							    value=<?php echo $_POST['xdireccion']; ?> placeholder="Dirección" 
							    onClick="this.select()" >
						</div>
					</div>
					<div class="row">
							<div class="col-sm-2"></div>
							<div class="col-sm-8">	
								<div class="pull-right" >
									<button type="button" class="buttonpepdido" data-toggle="modal" data-target="#myCliente">
										<span class="glyphicon glyphicon-search"></span> Buscar Cliente
									</button>
								</div>
							</div>
					</div>
					<div class="row" style="display:none;">
							<div class="col-sm-2"></div>
							<div class="col-sm-4"></div>
							<div class="col-sm-4">
								<div class="pull-right" >
								  	<font color = "585858"><label  id="valor1">Documento:</label></font>			  		
								  	<input name="documento2" id="documento1" type="radio" value="1" onClick="SeleccionaDocumento(1)" />
									<font color = "585858"><label  id="valorticket">Factura</label></font>
									<input name="documento2" id="documento3" type="radio" value="3" onClick="SeleccionaDocumento(3)" checked="checked"  />
									<font color = "585858"><label id="valorticket">Boleta</label></font>
							  	</div>
							</div>					
					</div>
					<div class="row" style="display:none;">
						<div class="col-sm-2"></div>
						<div class="col-sm-5">	
							<div class="pull-right" >
							  	<select class="form-control" data-width="auto" id="fpago" onchange="valueselect()">
							  			
									<?php 
										$sqlfpago ="SELECT * FROM TBPROPIEDADES where tipo= ? ";
										$paramsfpago = array('CONPAG');
										$recfpago = sqlsrv_query($conexion,$sqlfpago,$paramsfpago);
										while ($rowfpago=sqlsrv_fetch_array($recfpago)){
											$clave=$rowfpago['Clave'];
											$valor=$rowfpago['Valor'];
									?>
										<option value="<?php echo $clave;?>" ><?php echo $valor;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>	
					</div>		
					<br>
					<div class="pull-right">
					   	<button type="submit" class="buttonpepdido" value="busqueda" href="javascript:;" 
onclick="Guardar_devolucion('<?php echo $_POST['xRUC'];?>','<?php echo $_POST['xcliente'];?>','<?php echo $_POST['xdireccion'];?>','<?php echo $_POST['xpedido'];?>','<?php echo $_POST['IDCONSOLIDADO'];?>','<?php echo $_POST['IDVENDEDOR'];?>');return false;" value="Calcula"> <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Devolucion
						</button>
					</div> 
					<div id="RAZON" class='col-md-12'></div>
					<div style="display:none;" ><?php echo $_POST['xRUC'];?></div>
					<div style="display:none;"><?php echo $_POST['xcliente'];?></div>
					<div style="display:none;"><?php echo $_POST['xdireccion'];?></div>
					<div style="display:none;"><?php echo $_POST['xpedido'];?></div>
					<div style="display:none;" ><?php echo $_POST['IDCONSOLIDADO'];?></div>
					<div style="display:none;"><?php echo $_POST['IDVENDEDOR'];?></div>
		  	</form>
		  	</div>
	</div>
	<div id="guarda1" class='col-md-12'></div>
	<div id="guarda2" class='col-md-12'></div>
</div>
<!-- items -->
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
						  <input type="text" class="form-control" id="q" placeholder="Buscar productos" >
						</div>
						<button type="button" class="btn btn-default" onload="load(1)" onclick="load(1)"><span class='glyphicon glyphicon-search'></span> Buscar</button>
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
<!-- items -->
<!-- clientes -->
<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="myCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"> Buscar clientes </h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <div class="form-group">
					  	<div class="col-sm-6">
						  <input type="text" class="form-control" id="q1" placeholder=" Documento de identidad " >
						</div>
						<div class="col-sm-5" >                                                                                                                                                                                                                                                                                     
						  		<font color = "585858"><label  id="valor1">Tipo Documento:</label></font>			  		
						  		<input name="valordocumento" id="documento1" type="radio" value="6" onClick="Seleccionadocumento('6')" />
								<font color = "585858"><label  id="valordocumento">RUC</label></font>
								<input name="valordocumento" id="documento3" type="radio" value="1" onClick="Seleccionadocumento('1')" checked="checked" />
								<font color = "585858"><label id="valordocumento">DNI</label></font>
								<input name="valordocumento" id="documento3" type="radio" value="0" onClick="Seleccionadocumento('0')"   />
								<font color = "585858"><label id="valordocumento">Otros</label></font>
								<script>
									function Seleccionadocumento(DOC3){
									var DOCUMENTO = DOC3;
									document.getElementById("DOC3").value=DOCUMENTO;
									}
								</script>
					  	</div>
						<div class="col-sm-10"></div>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="q2" placeholder=" Cliente " >
						</div>
						<div class="col-sm-10"></div>
						<div class="col-sm-10"></div>
						<div class="col-sm-10">
						  <input type="text" class="form-control" id="q3" placeholder=" Dirección " >
						</div>
						<div class="col-sm-10"></div>
						<div class="col-sm-6">
							<button type="button" class="btn btn-default" onload="loadcliente(1)" onclick="loadcliente(1)">
							<span class='glyphicon glyphicon-search'></span> Buscar</button>
							<button type="button" class="btn btn-default" 
							onclick="insertcliente($('#q1').val(), $('#q2').val(), $('#q3').val(),$('#DOC2').val(),$('#DOC3').val());loadcliente(1)">
							<span class='glyphicon glyphicon-user'></span> Registrar</button>
						</div>
						<div class="col-sm-4" >
						  		<font color = "585858"><label  id="valor1">Persona:</label></font>			  		
						  		<input name="valorpersona" id="documento1" type="radio" value="J" onClick="SeleccionaPersona('J')" />
								<font color = "585858"><label  id="valorpersona">Jurídica</label></font>
								<input name="valorpersona" id="documento3" type="radio" value="N" onClick="SeleccionaPersona('N')" checked="checked"  />
								<font color = "585858"><label id="valorpersona">Natural</label></font>
								<script>
									function SeleccionaPersona(DOC2){
									var DOCUMENTO = DOC2;
									document.getElementById("DOC2").value=DOCUMENTO;
									}
								</script>
					  	</div>
					  </div>
					</form>
					
					<div id="loader2" style="position: absolute;	text-align: center;	top: 55px;	width: 100%;display:none;"></div><!-- Carga gif animado -->
					<div class="outer_div2" ></div><!-- Datos ajax Final -->

				  </div>

				  <div class="modal-footer">
				  	 <div id="guarda2" style="	text-align: center;	top: 55px;	width: 100%;display:none;"></div>
				  	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				  	<div class="col-sm-10">
				  	
					  	<input class="form-control" type="text" id="DOC2" value="N" style="visibility:hidden">
					  	<input class="form-control" type="text" id="DOC3" value="1" style="visibility:hidden" >
				  </div>
				  </div>
				</div>
			  </div>
			</div>
<!-- clientes -->
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
				 $('#loader').html('Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
				}
			})
		}
</script>
<script>
		function insertcliente(valor1, valor2,valor3,valor4,valor5){
			var parametros = {
	                "valor1" : valor1,
	                "valor2" : valor2,
	                "valor3" : valor3,
	                "valor4" : valor4,
	                "valor5" : valor5
	        };
	        $.ajax({
	                data:  parametros,
	                url:   'Guardar_cliente.php',
	                type:  'post',
	                beforeSend: function (objeto) {
	                        $("#guarda2").html("Procesando, espere por favor...");
	                },
	                success:  function (data) {
	                	$(".outer_div2").html(data).fadeIn('slow');
	                        $("#guarda2").html();
	                        //alert(response);
	                       // window.location = "Menu.php";
	                }
	        });		
	}
</script>
<script>
		$(document).ready(function(){
			loadcliente(1);
		});
		function loadcliente(page){
			var q= $("#q2").val();
			var dir= $("#q3").val();
			var parametros={"action":"ajax","page":page,"q":q,"dir":dir};
			$("#loader2").fadeIn('slow');
			$.ajax({
				url:'MostrarClientes.php',
				data: parametros,
				 beforeSend: function(objeto){
				 $('#loader2').html('Cargando...');
			  },
				success:function(data){
					$(".outer_div2").html(data).fadeIn('slow');
					$('#loader2').html('');
				}
			})
		}
	</script>
	<script>
	function agregar (id)
		{	
			var precio_venta=$('#precio_venta_'+id).val();
			var cantidad=$('#cantidad_'+id).val();
			var unidad=$('#unidad_'+id).val();
			//Inicia validacion
			if (isNaN(cantidad))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+id).focus();
			return false;
			}
			if (isNaN(unidad))
			{
			alert('Esto no es un numero');
			document.getElementById('unidad'+id).focus();
			return false;
			}
			//Fin validacion
			var parametros={"id":id,"precio_venta":precio_venta,"cantidad":cantidad,"unidad":unidad};	
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
	<script>
	function agregarbonificacion (id)
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
	        url: "agregar_bonificacion.php",
	        data: parametros,
					beforeSend: function(objeto){
					$("#resultadosbonificacion").html("Mensaje: Cargando...");
					},
			        success: function(datos){
					$("#resultadosbonificacion").html(datos);
					}
			});
		}
	function eliminarbonificacion (id)
		{
			$.ajax({
        type: "GET",
        url: "agregar_bonificacion.php",
        data: "id="+id,
		 beforeSend: function(objeto){
			$("#resultadosbonificacion").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultadosbonificacion").html(datos);
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
	<script>
	function agregarcliente (RUC,Descripcion,Direccion)
		{	
			var RUC=RUC;
			var descripcion=Descripcion;
			var direccion=Direccion;
			document.getElementById("Ruc").value=RUC;
			document.getElementById("cliente").value=descripcion;
			document.getElementById("direccion").value=direccion;

		}
	</script>
</body>
</html>