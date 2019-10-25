<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Buscar Producto</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
	function buscaritem(valorCaja1, valorCaja2,valorCaja3){
	        var parametros = {
	                "valorCaja1" : valorCaja1,
	                "valorCaja2" : valorCaja2,
	                "valorCaja3" : valorCaja3
	        };
	        $.ajax({
	                data:  parametros,
	                url:   'ListarProductos.php',
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
		function Guardar_item(iditem, codigo,descripcion,precio,idunidad,CODIGODIS){
			//INCLUYE BONIFICACION
			var parametros = {
		                "iditem" : iditem,
		                "codigo" : codigo,
		                "descripcion" : descripcion,
		                "precio" : precio,
		                "idunidad" : idunidad,
		                "CODIGODIS" : CODIGODIS
		        };
		        $.ajax({
		                data:  parametros,
		                url:   'Guardar_item.php',
		                type:  'post',
		                beforeSend: function () {
		                        $("#guarda1").html("Procesando, espere por favor...");
		                },
		                success:  function (response) {
		                        $("#guarda1").html(response);
		                        //alert("maincra");
		                 //       window.location = "BusquedaProducto.php";
		                }
		        });		
		}
	</script>
	<script>
	function limpiar(){
		document.getElementById("IDITEM").value='' ;	
		document.getElementById("CODIGO").value='' ;
		document.getElementById("CODIGODIS").value='' ;
		document.getElementById("DESCRIPCION").value='' ;
		document.getElementById("PRECIO").value=0.00 ;	
		document.getElementById("IDUNIDAD").value=1 ;
	}
	</script>
	<script>
	function subirproducto(){
		$(document).ready(function() {
			$("a.l1s").click(function(){
				IDITEM = $(this).parents("tr").find("td").eq(0).text();
				CODIGO = $(this).parents("tr").find("td").eq(1).text();
				CODIGODIS = $(this).parents("tr").find("td").eq(2).text();
				DESCRIPCION = $(this).parents("tr").find("td").eq(3).text();
				PRECIO = $(this).parents("tr").find("td").eq(4).text();
				IDUNIDAD = $(this).parents("tr").find("td").eq(7).text();
				document.getElementById("IDITEM").value=IDITEM;	
				document.getElementById("CODIGO").value=CODIGO;
				document.getElementById("CODIGODIS").value=CODIGODIS;
				document.getElementById("DESCRIPCION").value=DESCRIPCION;
				document.getElementById("PRECIO").value=PRECIO;	
				document.getElementById("IDUNIDAD").value=IDUNIDAD;
				document.getElementById("CODIGO").focus();
				
			});
		});
	}
	</script>
	<script>
		function subirproducto2(){ 
			$(document).ready(function() {
				$("button.buttondetallepedido").click(function(){
					IDITEM = $(this).parents("tr").find("td").eq(0).text();
					CODIGO = $(this).parents("tr").find("td").eq(2).text();
					CODIGODIS = $(this).parents("tr").find("td").eq(3).text();
					DESCRIPCION = $(this).parents("tr").find("td").eq(4).text();
					PRECIO = $(this).parents("tr").find("td").eq(5).text();
					IDUNIDAD = $(this).parents("tr").find("td").eq(8).text();
					document.getElementById("IDITEM").value=IDITEM;	
					document.getElementById("CODIGO").value=CODIGO;
					document.getElementById("CODIGODIS").value=CODIGODIS;
					document.getElementById("DESCRIPCION").value=DESCRIPCION;
					document.getElementById("PRECIO").value=PRECIO;	
					document.getElementById("IDUNIDAD").value=IDUNIDAD;
					document.getElementById("CODIGO").focus();
					
				});
			});
		}
	</script>
	<script>
		function valueselect(){
			var i = document.getElementById('IDUNIDAD');
			var p = i.options[i.selectedIndex].value;
			document.getElementById('IDUNIDADINPUT').value=p;//ya no se usa
		}
	</script>
</head>
<body class="container" background="img/login.JPG" >
<div class="container">
<center>
	<BR>
		<form method="POST" action="Menu.php">
		<button type="submit" class="btn btn-default">Menú</button>
		</form>
	<BR>
</center>	
	<div class="panel bordepanel">
		<div class="panel-heading" STYLE="background-color:#6E6E6E">
	    	<font color = "FFFFFF"><h3 class="panel-title">Buscador</h3></font>
	  	</div>
	  	<div class="panel-body">
	  	<form class="form-horizontal" role="form">
	  		<form role="form" method="post" >
				<div class="row" style="display:none" >
						<div class="col-sm-1"></div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="labeliditem">IDITEM:</span>
							    <input id="IDITEM" type="text" class="form-control"  placeholder="IDITEM" value="-" 
							    onClick="this.select()" >

						</div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="labelIDUNIDAD">IDUNIDAD:</span>
							    <input id="IDUNIDADINPUT" type="text" class="form-control"  placeholder="IDUNIDAD" value="-" 
							    onClick="this.select()" >
							    
						</div>
						
				</div>
				<div class="row">
					<div class="col-sm-1"> </div>
				</div>
				<div class="row"> 
					<div class="col-sm-2">
				    	<span  class="input-group-addon" id="labelcodigo">Código:</span>
				    	<input class="form-control" type="text" onClick="this.select()" id="CODIGO" placeholder="Código" name="ncodigo"  >
				    </div>
				    <div class="col-sm-2">
				    	<span  class="input-group-addon" id="labelcodigo">Código Dis:</span>
				    	<input class="form-control" type="text" onClick="this.select()" id="CODIGODIS" placeholder="Código" name="ncodigodis"  >
				    </div>
				    <div class="col-sm-4">
				    	<span  class="input-group-addon" id="labeldescripcion">Descripción:</span>
				    	<input class="form-control" type="text" id="DESCRIPCION"   placeholder="Descripción" name="ndescripcion"  >
				    </div>

					<div class="col-sm-2">
				    	<span  class="input-group-addon" id="labelprecio">Precio:</span>
				    	<input class="form-control" type="number" id="PRECIO" onClick="this.select()" placeholder="Precio" name="nPRECIO">
				    </div> 
				    <div class="col-sm-2">
				    	<span  class="input-group-addon" id="labelUNIDAD">Unidad:</span>
						<div>	
							<div >
							  	<select class="form-control" data-width="auto" id="IDUNIDAD" onchange="valueselect()">
							  		 
									<?php
										require_once("conexion.php");
										session_start();
										$basedatos =@$_SESSION['basedatos'];
										$conexion=Conexion($basedatos);
										$usuario = @$_SESSION['usuario'];
										$pass = @$_SESSION['pass'];

											$sqlfpago ="SELECT * FROM TBUNIDADES ";
											$paramsfpago = array('CONPAG');
											$recfpago = sqlsrv_query($conexion,$sqlfpago,$paramsfpago);
											while ($rowfpago=sqlsrv_fetch_array($recfpago)){
												$IDUNIDAD=$rowfpago['idUnidad'];
												$UNIDAD=$rowfpago['Descripcion'];
									?>
										<option value="<?php echo $IDUNIDAD;?>" ><?php echo $UNIDAD;?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>	
				</div>	
			  	<div class="row">
			  		<div class="col-sm-3"></div>
			  		<div class="col-sm-6">	
			       		<div class="input-group">
							<button type="submit" class="buttonpepdido" value="limpiar" href="javascript:;" onclick="limpiar();return false;" value="Calcula"><span class="glyphicon glyphicon-file"></span> Nuevo</button>
							<button type="submit" class="buttonpepdido" value="busqueda" href="javascript:;" onclick="buscaritem($('#CODIGO').val(), $('#DESCRIPCION').val(),$('#CODIGODIS').val());return false;" value="Calcula"><span class="glyphicon glyphicon-search"></span> Buscar</button>
							<button type="submit" class="buttonpepdido" value="busqueda" href="javascript:;" 
			       		onclick="Guardar_item($('#IDITEM').val(), $('#CODIGO').val(), $('#DESCRIPCION').val(), $('#PRECIO').val(), $('#IDUNIDAD').val(),$('#CODIGODIS').val());return false;" value="Calcula"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
						</div> 
			       	</div>
		    	</div> 
		    </form>	
	  	</form>
	  	</div>
	</div>
</div>
<span id="resultado" onclick="subirproducto();"></span>
<span id="guarda1"></span>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<script src="js/responsive.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>