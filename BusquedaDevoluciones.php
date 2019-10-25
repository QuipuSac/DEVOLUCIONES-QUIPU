<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Buscar Pedido</title>
	<style>
        input {text-transform: uppercase;}
    </style>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
	function buscarPedido(ruc, cliente,sericonsolidado,numconsolidado,Pedido){
	        var parametros = {
	                "ruc" : ruc,
	                "cliente" : cliente,
	                "sericonsolidado" : sericonsolidado,
	                "numconsolidado" : numconsolidado,
	                "Pedido" : Pedido
	        };
	        $.ajax({
	                data:  parametros,
	                url:   'ListarDevoluciones.php',
	                type:  'post',
	                beforeSend: function () {
	                        $("#resultado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) {
	                        document.getElementById("botonModificar").style.display = "none";
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
				$archivobonificacion = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
				if(file_exists($archivo))
				{	
					unlink('C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
					unlink('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
				}
			}

			}?>
		}
	</script>
	<script>
	function modificarPedido( ruc,cliente,pedido){
	        var parametros = {
		        	"pedido" : pedido,
		        	"ruc" : ruc,
		        	"cliente" : cliente,
	        };
	        $.ajax({
	                data:  parametros,

	                url:  'ModificarPedido2.php',
	                type:  'post',
	                beforeSend: function () {
	                        $("#resultado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) {
	                        $("#resultado").html(response);
	                        window.location = "ModificarPedido2.php";
	                }
	        });
	}
	</script>
	<script >
	function ObtenerFila(){
		$(document).ready(function() {
			$("button.buttontabla").click(function(){
				pedido = $(this).parents("tr").find("td").eq(6).text();
				ruc = $(this).parents("tr").find("td").eq(2).text();
				cliente = $(this).parents("tr").find("td").eq(3).text();
				direccion = $(this).parents("tr").find("td").eq(5).text();
				document.getElementById("Pedido").value=pedido;	
				document.getElementById("cliente").value=cliente;
				document.getElementById("ruc").value=ruc;
				document.getElementById("Pedido").focus();
				document.getElementById("xpedido").value=pedido;	
				document.getElementById("xcliente").value=cliente;
				document.getElementById("xRUC").value=ruc;
				document.getElementById("xdireccion").value=direccion;
				document.getElementById("botonModificar").style.display = "block"; 
			//document.getElementById("botonModificar").style.display = "none";
				
			});
		});
	}
	</script> 
</head>
<body class="container" background="img/login.JPG" onload="borrarXML()">
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
	    	<font color = "FFFFFF"><h3 class="panel-title">Buscador de Devoluciones</h3></font>
	  	</div>
	  	<div class="panel-body">
	  	<form class="form-horizontal" role="form" method="post" > 
	  		<form role="form" method="post" >
				<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="valor1">Clave/Serie:</span>
							    <input id="sericonsolidado" type="number" class="form-control"  
							    placeholder="Clave/Serie"  readonly="true" value=<?php echo date("Y").$_POST['idvendedorpedido']  ?> >
						</div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="valor2">Número:</span>
							    <input id="numconsolidado" type="number" class="form-control"  
							    placeholder="Consolidado pedido"  onClick="this.select()" >
						</div>
						<div class="col-sm-2">
							    <span class="input-group-addon" >Nro.Pedido:</span>
							    <input id="Pedido" type="number" class="form-control"  
							    placeholder="Nro.pedido"  onClick="this.select()" >
						</div>
						<div class="col-sm-5"></div>
				</div><br>
				<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-4">
							    <span class="input-group-addon" id="valor4">Doc.Cliente:</span>
							    <input id="ruc" type="text" class="form-control"  
							    placeholder="Doc.Cliente" onClick="this.select()" >
						</div>
						<div class="col-sm-4">
							    <span class="input-group-addon" id="valor5">Cliente:</span>
							    <input id="cliente" type="text" class="form-control"  
							    placeholder="Raz.Social" onClick="this.select()" >
						</div> 
				</div><br> 
			</form> 
			  	<div class="col-sm-offset-2 col-sm-10">
				  	<div>	
			       		<button type="submit" class="buttonpepdido" 
			       		onclick="buscarPedido($('#ruc').val(), $('#cliente').val(), $('#sericonsolidado').val(), $('#numconsolidado').val(), $('#Pedido').val());return false;" value="Calcula" >
			       		<span class="glyphicon glyphicon-search"></span> Buscar</button>
			       	</div> 
			       			<?php 
				                $sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
								$paramspropiedades = array('vpedido');
								$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
								$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
								//echo  $rowpropiedades['valor'];

								if ($rowpropiedades['valor']==1) {
									$ventanpedido='ModificarPedido1.php';//primera version
								}else{
									$ventanpedido='ModificarPedido2.php';//segunda version con maestroclientes
								}
								$PERFILUSUARIO=$_POST['PERFILEDITAR'];
								//echo $PERFILUSUARIO;
							?> 
			       	<form method="POST" action=<?php echo $ventanpedido; ?> >
					    	<button type="submit" class="buttonpepdido" id="botonModificar" style="display: none">
							<span class="glyphicon glyphicon-pencil"></span> Modificar</button>
							<div class="col-xs-2">
						    	<input class="form-control" type="text" id="xRUC" placeholder="RUC" name="xRUC" style="visibility:hidden">
						    </div>
						    <div class="col-xs-2">
						    	<input class="form-control" type="text" id="xcliente" placeholder="razsocial" name="xcliente" style="visibility:hidden">
						    </div>
						    <div class="col-xs-2">
						    	<input class="form-control" type="text" id="xpedido" placeholder="nropedido" name="xpedido" style="visibility:hidden">
						    </div>
						    <div class="col-xs-2">
						    	<input class="form-control" type="text" id="xdireccion" placeholder="direccion" name="xdireccion" style="visibility:hidden">
						    </div> 
						    <div class="col-xs-2">
						    	<input class="form-control" type="text" id="PERFILUSUARIO" placeholder="PERFILUSUARIO" name="PERFILUSUARIO" 
						    	style="visibility:hidden" value="<?php echo $PERFILUSUARIO; ?>">
						    </div>
					</form> 
		    	</div>
	  	</form>
	  	</div>
	</div>			
</div>
<div class="container">
	<span id="guarda" onclick="ObtenerFila();"></span>
	<span id="resultado" onclick="ObtenerFila();"></span>
</div>
<!-- items -->
<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Detalle del Pedido</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <div class="form-group" style="display:none">
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="q" value ="443" placeholder="Buscar productos" >
						</div>
							<button type="button" class="btn btn-default" onload="load(1)" onclick="load(1)">
							<span class='glyphicon glyphicon-search'></span> Buscar</button>
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
<!-- items -->
<script>
		$(document).ready(function(){
			load(1);
		});
		function load(page){
			var q= $("#q").val();
			var parametros={"action":"ajax","page":page,"q":page};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'detallepedido.php',
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
</body>
</html>