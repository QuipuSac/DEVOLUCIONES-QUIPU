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
				        window.location = "Menu.php";
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
				//die( print_r( sqlsrv_errors(),true));
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
						echo $archivodevolucion;
						if(file_exists($archivo))
						{	
							unlink('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
							
						}
					}
				}?>
			}
		</script>
		<script >
			function Guardar_devolucion(xRUC,xcliente,xdireccion,xpedido,IDCONSOLIDADO,IDVENDEDOR){
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
			                url:   'Guardar_devolucion.php',
			                type:  'post',
			                beforeSend: function () {
			                        $("#guarda1").html("Procesando, espere por favor...");
			                },
			                success:  function (response) {
			                        $("#guarda1").html(response);
			                        //alert(response);
			                        //window.location = "Menu.php";
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
		<script>
			function JalarItem(CODIGO){	
				var parametros={"CODIGO":CODIGO};	
				$.ajax({
				type: "GET",
				url: "buscar_item.php",
				data: parametros,
					success: function(datos){
						//$("#RAZON").html(datos);
					document.getElementById("DESCRIPCIONITEM").value=datos;
					}
				});
			}
		</script>
		<script>
			function JalarItemid(CODIGO){	
				var parametros={"CODIGO":CODIGO};	
				$.ajax({
				type: "GET",
				url: "buscar_iditem.php",
				data: parametros,
					success: function(datos){
						// $("#RAZON").html(datos);
					document.getElementById("idITEM").value=parseInt(datos);
					}
				});
			}
		</script>
		<script>
			function Llenarunidad(CODIGO){	
				var parametros={"CODIGO":CODIGO};	
				$.ajax({
				type: "GET",
				url: "completar_unidad.php",
				data: parametros,
					success: function(datos){
						// $("#RAZON").html(datos);
						 $("#idunidaditem").html(datos);
					//document.getElementById("idITEMx").value= datos;
					}
				});
			}
		</script>
		<script>
			function JalarItemPrecio(id,cantidad,idunidad){	
				var parametros = {	
				"id" : id,
				"cantidad" : cantidad,
				"idunidad" : idunidad
				};
				$.ajax({
				type: "GET",
				url: "buscar_precio.php",
				data: parametros,
					success: function(datos){
						// $("#RAZON").html(datos);
					document.getElementById("PRECIOITEM").value=parseFloat(datos);
					document.getElementById("TOTALITEM").value=parseFloat((document.getElementById("PRECIOITEM").value)*(document.getElementById("CANTIDADITEM").value));
					}
				});
			}
		</script>
		<script>
			function JalarItemid(CODIGO){	
				var parametros={"CODIGO":CODIGO};	
				$.ajax({
				type: "GET",
				url: "buscar_iditem.php",
				data: parametros,
					success: function(datos){
						// $("#RAZON").html(datos);
					document.getElementById("idITEM").value=parseInt(datos);
					}
				});
			}
		</script>
		<script>
			function Jalarunidaditem(CODIGO){	
				var parametros={"CODIGO":CODIGO};	
				$.ajax({
				type: "GET",
				url: "buscar_unidad.php",
				data: parametros,
					success: function(datos){
						// $("#RAZON").html(datos);
					document.getElementById("idunidaditem").value=parseInt(datos);
					}
				});
			}
		</script>
		<script>
			function JalarItemPrecio(id,cantidad,idunidad){	
				var parametros = {	
				"id" : id,
				"cantidad" : cantidad,
				"idunidad" : idunidad
				};
				$.ajax({
				type: "GET",
				url: "buscar_precio.php",
				data: parametros,
					success: function(datos){
						// $("#RAZON").html(datos);
					document.getElementById("PRECIOITEM").value=parseFloat(datos);
					document.getElementById("TOTALITEM").value=parseFloat((document.getElementById("PRECIOITEM").value)*(document.getElementById("CANTIDADITEM").value));
					}
				});
			}
		</script>
		<script>
			function eliminar2 (id,precio_venta,cantidad,unidad){	
					var parametros=
					{
						"id":id,
						"precio_venta":precio_venta,
						"cantidad":cantidad,
						"unidad":unidad 
					};	
					$.ajax({
			        type: "POST",
			        url: "DEVOLVER.php",
			        data: parametros,
							beforeSend: function(objeto){
							$("#resultados").html("Mensaje: Cargando...");
							},
					        success: function(datos){
							$("#resultados").html(datos);

							document.getElementById('idITEM').value='-' ;
							document.getElementById('CODIGO').value='-' ;
							document.getElementById('DESCRIPCIONITEM').value='-' ;
							document.getElementById('idunidaditem').value=1 ;
							document.getElementById('CANTIDADITEM').value=0 ;
							document.getElementById('PRECIOITEM').value=0 ;	
							document.getElementById('TOTALITEM').value=0 ;
							}
					});
			}
		</script>
		<script>
			function agregaritem (id,precio_venta,cantidad,unidad){	
				var parametros=
				{
					"id":id,
					"precio_venta":precio_venta,
					"cantidad":cantidad,
					"unidad":unidad
				};	
				$.ajax({
		        type: "POST",
		        url: "agregar_producto.php",
		        data: parametros,
						beforeSend: function(objeto){
						$("#resultados").html("Mensaje: Cargando...");
						},
				        success: function(datos){
						$("#resultados").html(datos);

						document.getElementById('idITEM').value='-' ;
						document.getElementById('CODIGO').value='-' ;
						document.getElementById('DESCRIPCIONITEM').value='-' ;
						document.getElementById('idunidaditem').value=1 ;
						document.getElementById('CANTIDADITEM').value=0 ;
						document.getElementById('PRECIOITEM').value=0 ;	
						document.getElementById('TOTALITEM').value=0 ;
						}
				});
			}
			function eliminarx (id){
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
		function agregarbonificacionitem (id,precio_venta,cantidad,unidad){	
				var parametros=
				{
					"id":id,
					"precio_venta":precio_venta,
					"cantidad":cantidad,
					"unidad":unidad
				};	
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
		function eliminarbonificacionx (id){
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
			function editar(id,codigo,idunidad,cantidad,precio){ 
				document.getElementById('idITEM').value=id;
				document.getElementById('CODIGO').value=codigo;
				document.getElementById('idunidaditem').value=idunidad;
				document.getElementById('CANTIDADITEM').value=cantidad;
				document.getElementById('PRECIOITEM').value=precio; 
				document.getElementById('CODIGO').focus() ;	
				document.getElementById('CANTIDADITEM').focus() ;
				document.getElementById('agregarproducto').focus() ; 
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
					document.getElementById("cliente").value=datos;
					}
				});
			}
		</script>
		<script>
			function Jalardireccion(Ruc){	
				var parametros={"Roc":Ruc};	
				$.ajax({
				type: "GET",
				url: "buscar_direccion.php",
				data: parametros,
					success: function(datos){
					document.getElementById("direccion").value=datos;
					}
				});
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
				$archivoEXTRA = 'C:\bkp\pedidoEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml';
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
			    	$library->asXML('C:\bkp\pedidoEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
			    	$xml = simplexml_load_file('C:\bkp\pedidoEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
			    
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
				    $xml->save('C:\bkp\pedidoEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml'); 

				    $xml = simplexml_load_file('C:\bkp\pedido'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
				    $xml = simplexml_load_file('C:\bkp\pedidoEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
				    $salida ="";
				}

				$sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
				$paramspropiedades = array('BONIWEB');
				$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
				$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
				//echo  $rowpropiedades['valor']; 
				$_SESSION['BONIWEB'] = $rowpropiedades['valor'];
				
				if ($rowpropiedades['valor']=='S') {
					//VARIABLES 
					$querybonificacion ="SELECT TOP 1 B.iditemboni AS idItem,I.Codigo AS Codigo,B.DESCRIPCION AS Descripcion,
									B.CANTBONI AS CANTIDAD,B.PRECIO AS precioesp,B.iditem as itembonificador,B.CANTIDAD AS FACTOR
									,B.IDUNIDADBONI AS UNIDAD
									FROM tbbonificaciones B INNER JOIN TBITEMS I ON I.idItem=B.iditemboni 
									WHERE B.idItem = ? AND B.cantidad <=? AND 0 < B.cantidad and B.IDUNIDADB=?
									ORDER BY  FACTOR DESC";
					$paramsbonificacion = array(array($id),array($cantidad),array($unidad));
					$sqlbonificacion=sqlsrv_query($conexion,$querybonificacion,$paramsbonificacion);
					while ($rowbonificacion=sqlsrv_fetch_array($sqlbonificacion)){
						$archivobonificacion = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml';
						$archivobonificacionEXTRA = 'C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml';
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

							$library->asXML('C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
							$xml = simplexml_load_file('C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');			    
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
							$xml->save('C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');

							$xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
							$xml = simplexml_load_file('C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($rowidUsuario['idUsuario'], 0).'.xml');
							
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
	
	
	}
	
?>
<body class="container" background="img/login.JPG" onload="LEER(),LEERBONIFICACION(),ocultarbonificacion()" >	
<div >
<center>

	<BR>
		<form method="POST" action="Menu.php">
		<button type="submit" class="btn btn-default" >Menu</button>
		</form>  
	<BR>
</center>
<div class="panel bordepanel">
	<div class="panel-heading" STYLE="background-color:#6E6E6E">
	    	<font color = "FFFFFF"><h3 class="panel-title">Registro de Cantidades Recibidas-Pedido: <?php echo $_POST['xpedido']; ?> </h3></font>
	</div>
	<div class="panel-body">
		<div class="row">
				<div class="col-sm-1"></div>
				<div class="col-sm-3">
					<div class="input-group">
						<span class="input-group-addon" id="valor1">Código:</span>
						<input id="CODIGO" type="text" class="form-control" onClick="this.select()" 
						onblur="JalarItem($('#CODIGO').val()),JalarItemid($('#CODIGO').val()),
						Llenarunidad($('#CODIGO').val()),Jalarunidaditem($('#CODIGO').val())" readonly="readonly">
					</div>
					<input id="idITEM" type="number" value="0" class="form-control" readonly style="display:none;">
					<input id="DESCRIPCIONITEM" type="text" class="form-control" readonly="readonly" >
				</div>
				<div class="col-sm-2">
					<select class="form-control" data-width="auto" id="idunidaditem" onchange="valueselect2()">
							<option value="0" >...</option>	
						<?php 
							$sqlunidades ="SELECT * FROM tbunidades";
							//$paramsunidades = array('');
							$recunidades = sqlsrv_query($conexion,$sqlunidades);
						while ($rowunidades=sqlsrv_fetch_array($recunidades)){
							$idUnidad=$rowunidades['idUnidad'];
							$unidad=$rowunidades['Descripcion'];
						?>
							<option value="<?php echo $idUnidad;?>" ><?php echo $unidad;?></option>
						<?php /*<td ><?php echo $nombre_marca; ?></td>*/
						}
						?>
					</select>
					<div class="input-group">
					<span class="input-group-addon" id="valor1">Cantidad:</span>
					<input id="CANTIDADITEM" type="number" class="form-control"  onClick="this.select()" 
					onblur="JalarItemPrecio($('#idITEM').val(),$('#CANTIDADITEM').val(),$('#idunidaditem').val())" >
					</div>
				</div>
				<div class="col-sm-3" >
					<div class="input-group">
					<span class="input-group-addon" id="valor1">Precio:</span>
					<input id="PRECIOITEM" type="number" class="form-control" value="1"  onClick="this.select()" readonly="readonly" >
					</div> 

					<div class="input-group">
					<span class="input-group-addon" id="valor1">Total:</span>
					<input id="TOTALITEM" type="number" class="form-control"  onClick="this.select()" readonly="readonly" >
					</div>
				</div>
				<div class="col-sm-2">
					<div class="input-group">
						<button type="button" class="buttonpedido" id="agregarproducto"
						onclick="agregaritem($('#idITEM').val(),$('#PRECIOITEM').val(),$('#CANTIDADITEM').val(),$('#idunidaditem').val()),
						agregarbonificacionitem ($('#idITEM').val(),$('#PRECIOITEM').val(),$('#CANTIDADITEM').val(),$('#idunidaditem').val())" >
							<span class="glyphicon glyphicon-arrow-down"></span> Agregar Producto
						</button>
						<button  type="button" class="buttonpedido" data-toggle="modal" data-target="#myModal">
							<span class="glyphicon glyphicon-search"></span>ver
						</button>
					</div>
				</div>	
			</div>
	
		<div id="resultados" class='col-md-12'></div>
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
							    <input id="Ruc" type="text" class="form-control"  placeholder="Ruc" value="<?php echo $_POST['xRUC']; ?>" onClick="this.select()" 
							    onblur="JalarRazSocial($('#Ruc').val()),Jalardireccion($('#Ruc').val())">
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
							<div class="col-sm-8">	
								<div class="pull-right" >
									<button type="button" class="buttonpedido" data-toggle="modal" data-target="#myCliente">
										<span class="glyphicon glyphicon-search"></span> Buscar Cliente
									</button>
								</div>
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
					<br>
					<div class="pull-right" style="display:none" >
					    <button type="submit" class="buttonpedido" value="busqueda" href="javascript:;" 
					    onclick="Actualizar_pedido($('#Ruc').val(), $('#cliente').val(), $('#DOC').val(), <?php echo $IDDOCUMENTO; ?>, <?php echo $_POST['xpedido'];?>, $('#direccion').val(),<?php echo $IDDOCUMENTOBONIFICACION;?>,$('#fpago').val() );return false;" value="Calcula">
					    <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Pedido
					    </button>
					</div>
					<div class="pull-right">
					   	<button type="submit" class="buttonpedido" value="busqueda" href="javascript:;" 
onclick="Guardar_devolucion($('#Ruc').val(),$('#cliente').val(),$('#direccion').val(),'<?php echo $_POST['xpedido'];?>','<?php echo $_POST['IDCONSOLIDADO'];?>','<?php echo $_POST['IDVENDEDOR'];?>') , Actualizar_pedido($('#Ruc').val(), $('#cliente').val(), $('#DOC').val(), <?php echo $IDDOCUMENTO; ?>, <?php echo $_POST['xpedido'];?>, $('#direccion').val(),<?php echo $IDDOCUMENTOBONIFICACION;?>,$('#fpago').val() );return false;" value="Calcula"> <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Devolucion
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
						<button type="button" class="btn btn-default" onclick="load(1)" onload="load(1)"><span class='glyphicon glyphicon-search'></span> Buscar</button>
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
								<input name="valordocumento" id="documento3" type="radio" value="1" onClick="Seleccionadocumento('1')" />
								<font color = "585858"><label id="valordocumento">DNI</label></font>
								<input name="valordocumento" id="documento3" type="radio" value="0" onClick="Seleccionadocumento('0')" checked="checked"  />
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
					  	<input class="form-control" type="text" id="DOC3" value="0" style="visibility:hidden" >
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
						$(document).ready(function(){
							borrarXML();
						});
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
		$(document).ready(function(){
			$('.buttonpedido').on('click', function () {
				$.ajax({
				type: "post",
				url: 'MostrarProductos3.php',
				data: {'paz'},
				dataType: "html",
				success: function (response) {
					$('.outer_div').html(response);
				}
			});
			});
			
		});/*
		function load(page){
			var q= $("#q").val();
			var parametros={"action":"ajax","page":page,"q":q};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'MostrarProductos3.php',
				data: parametros,
				 beforeSend: function(objeto){
				 $('#loader').html('Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html(data);
				}
			})
		}*/
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
			var parametros={"action":"ajax","page":page,"q":q};
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
			if (isNaN(precio_venta))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+id).focus();
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