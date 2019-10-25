<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Buscar Pedido</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
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
						unlink('C:\bkp\pedidoEXTRA'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
						unlink('C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
						unlink('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
					}
				}
			}
			?>
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
	<?php
	$sqlconsolidado ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
	$paramsconsolidado = array('CONSP');
	$recconsolidado = sqlsrv_query($conexion,$sqlconsolidado,$paramsconsolidado);
	$rowconsolidado = sqlsrv_fetch_array( $recconsolidado, SQLSRV_FETCH_ASSOC) ;
	//echo  $rowpropiedades['valor'];
		if ($rowconsolidado['valor']=='S') {
			$v1= $_POST['numeroconsolidado'];
			if(is_numeric($v1)){
		     	$idvendedor=$_POST['idvendedor']; 
				$sqlidCONSOLIDADO ="SELECT * FROM TBDOCUMENTOS WHERE IDTIPODOCU=238 AND NumeDocu=? ORDER BY 1 DESC";
				$paramsidCONSOLIDADO = array($v1);
				$recidCONSOLIDADO = sqlsrv_query($conexion,$sqlidCONSOLIDADO,$paramsidCONSOLIDADO);
				$rowidCONSOLIDADO = sqlsrv_fetch_array( $recidCONSOLIDADO, SQLSRV_FETCH_ASSOC) ;			

		     	$v2=$rowidCONSOLIDADO['idDocumento'];

			}else{
				echo"<script type=\"text/javascript\">alert('Faltó Iniciar Consolidado de Devolución');window.location='Menu.php'; </script>"; 
			}
		}
		else{
			$v2="0";
		}
	?>
	<script>
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
				document.getElementById("IDCONSOLIDADO").value=<?php echo $v2; ?>;	
				document.getElementById("IDVENDEDOR").value=<?php echo $_POST['idvendedor'];  ?>;
				document.getElementById("envio").style.display = "block";
				//window.location.href = 'NuevaDevolucion2.php?var1=1';


			});
		});
	}
	</script>
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
	                url:   'ListarPedidos.php',
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
</head>
<body class="container" background="img/login.JPG" onload="borrarXML()">
<?php
	$sqlconsolidado ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
	$paramsconsolidado = array('CONSP');
	$recconsolidado = sqlsrv_query($conexion,$sqlconsolidado,$paramsconsolidado);
	$rowconsolidado = sqlsrv_fetch_array( $recconsolidado, SQLSRV_FETCH_ASSOC) ;
	//echo  $rowpropiedades['valor'];
	if ($rowconsolidado['valor']=='S') {
		$v1= $_POST['numeroconsolidado'];
		if(is_numeric($v1)){
	     	$idvendedor=$_POST['idvendedor']; 
			$sqlidCONSOLIDADO ="SELECT * FROM TBDOCUMENTOS WHERE IDTIPODOCU=238 AND NumeDocu=? ORDER BY 1 DESC";
			$paramsidCONSOLIDADO = array($v1);
			$recidCONSOLIDADO = sqlsrv_query($conexion,$sqlidCONSOLIDADO,$paramsidCONSOLIDADO);
			$rowidCONSOLIDADO = sqlsrv_fetch_array( $recidCONSOLIDADO, SQLSRV_FETCH_ASSOC) ;			

	     	$v2=$rowidCONSOLIDADO['idDocumento'];

		}else{
			echo"<script type=\"text/javascript\">alert('Faltó Iniciar Consolidado de Devolución');window.location='Menu.php'; </script>"; 
		}
	}
	else{
		$v2="0";
	}
?>

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
	    	<font color = "FFFFFF"><h3 class="panel-title">Buscador de Pedidos</h3></font>
	  	</div>
	  	<div class="panel-body">
	  	<form class="form-horizontal" role="form" method="post" >
	  		<form role="form" method="post" >
				<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="valor1">Clave/Serie:</span>
							    <input id="sericonsolidado" type="number" class="form-control"  
							    placeholder="Clave/Serie"  onClick="this.select()" >
						</div>
						<div class="col-sm-3">
							    <span class="input-group-addon" id="valor2">Número:</span>
							    <input id="numconsolidado" type="number" class="form-control"  
							    placeholder="Consolidado pedido"  onClick="this.select()" >
						</div>
						<div class="col-sm-2">
							    <span class="input-group-addon" id="valor3">Nro.Pedido:</span>
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
			<div class="row">
				<div class="col-sm-2"></div>
			  	
				  	<div class="col-sm-3">	
			       		<button type="submit" class="buttonbuscar" id="buscar" 
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
									$ventanpedido='NuevaDevolucion3.php';//primera version
								}else{
									$ventanpedido='NuevaDevolucion3.php';//segunda version con maestroclientes
								}
							?>

					<div class="col-sm-4">
				       	<form method="POST" action=<?php echo $ventanpedido; ?> >
						    	<button type="submit" class="buttonbuscar" id="envio" value="0" onchange="" style="display:none">
								<span class="glyphicon glyphicon-share-alt"></span> Registrar Devolución</button>
								<div style="display:none;" >
							    	<input class="form-control" type="text" id="xRUC" placeholder="RUC" name="xRUC" >
							    </div>
							    <div style="display:none;">
							    	<input class="form-control" type="text" id="xcliente" placeholder="razsocial" name="xcliente" >
							    </div>
							    <div style="display:none;" >
							    	<input class="form-control" type="text" id="xpedido" placeholder="nropedido" name="xpedido" >
							    </div>
							    <div style="display:none;" >
							    	<input class="form-control" type="text" id="xdireccion" placeholder="direccion" name="xdireccion" >
							    </div>
						    	<div style="display:none;">
							    	<input class="form-control" type="text" id="IDCONSOLIDADO" placeholder="IDCONSOLIDADO" 
							    	name="IDCONSOLIDADO" value=<?php echo $v2; ?>>
							    </div>
							    <div style="display:none;" >
							    	<input class="form-control" type="text" id="IDVENDEDOR" placeholder="IDVENDEDOR"  name="IDVENDEDOR" >
							    </div>
						</form>
					</div>
		    </div>
	  	</form>
	  	</div>
	</div>			
</div>
<div class="container">
	<span id="guarda" onclick="ObtenerFila();"></span>
	<span id="resultado" onclick="ObtenerFila();"></span>
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
<script>
	function prueba(pedido){
		//alert(pedido); 
	        var parametros = {
	                "pedido" : pedido,
	                "CONSOLIDADODEV" : <?php echo $v2; ?> 
	        };
	        $.ajax({
	                data:  parametros,
	                url:   'devolvertodo.php',
	                type:  'post',
	                beforeSend: function () {
	                        $("#guarda").html("Procesando, espere por favor...");
	                },
	                success:  function (response) {
	                        $("#guarda").html(response);
	                    	$(document).ready(function(){
								buscarPedido($('#ruc').val(), $('#cliente').val(), $('#sericonsolidado').val(), $('#numconsolidado').val(), $('#Pedido').val());return false;
							});
	                }
	        });
	}
</script>
<script> 
function validar(pedido) {
    var txt;
    var r = confirm("¿Esta seguro de registrar devolucion.?");
    if (r == true) {
        $(document).ready(function(){
			prueba(pedido);
		});
    }
} 
</script>
</body>
</html>