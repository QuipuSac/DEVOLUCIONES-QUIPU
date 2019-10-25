<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<title>Menú</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 	 
<script>
function ocultarconsolidado(){
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
			$paramspropiedades = array('CONSP');
			$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
			$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
			//echo  $rowpropiedades['valor'];
			if ($rowpropiedades['valor']=='S') {
				$varvisible='block';
			}
			else{
				$varvisible='none';
			}
		}
	?>
}
</script>
</head>
<body class="container" background="img/login.JPG" onload="Buscarconsolidado(),ocultarconsolidado()" >
<div class="container">
	<BR>
	<div>
	<center>
		<form method="POST" action="LOGout.php">
		<input type=image src="img/icono.png" class="img-responsive">
		
		</form>
	</center>
	</div>
	<BR>
	<div class="panel bordepanel ">
		<div class="panel-heading " STYLE="background-color:#6E6E6E">
	    	<font color = "FFFFFF"><h3 class="panel-title">Menú</h3></font>
	  	</div>
	  	<?php
			require_once("conexion.php");
					//session_start();
					$basedatos =@$_SESSION['basedatos'];
					$conexion=Conexion($basedatos);
					$usuario = @$_SESSION['usuario'];
					$pass = @$_SESSION['pass'];

					if(!@$conexion){
						//die( print_r( sqlsrv_errors(), true));
						echo "No hay conexion";
					}
					else{
						$sql ="SELECT tbusuarios.Nombres AS Nombres,tbusuarios.Apellidos AS Apellidos, TBPERFILES.Descripcion AS PERFIL
						FROM tbusuarios INNER JOIN TBPERFILES ON TBPERFILES.IDPERFIL=TBUSUARIOS.IDPERFIL
						WHERE cuenta = ? ";
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
							}
							else{
								while( $row = sqlsrv_fetch_array( $rec, SQLSRV_FETCH_ASSOC) ) {
									
									$vendedor = $row['Nombres']." ".$row['Apellidos'];
									$PERFIL= $row['PERFIL'];
									$sqlvendedor ="SELECT *	FROM tbclieprov	WHERE Nombres=? AND  Apellidos=? ";
									$paramsvendedor = array($row['Nombres'],$row['Apellidos']);				
									$recvendedor = sqlsrv_query($conexion,$sqlvendedor,$paramsvendedor);
									$filavendedor = sqlsrv_fetch_array( $recvendedor, SQLSRV_FETCH_ASSOC);

									if(!$filavendedor['idClieProv']){
										echo"<script type=\"text/javascript\">alert('El usuario no tiene perfil de vendedor');window.location='login.php'; </script>"; 
									}
									else{
										$idUsuario2=$filavendedor['idClieProv'];
									}

									?>

								<div class="panel-body">
								<div class="pull-left">
									<font color="585858"><h4>Bienvenido(a):<?php echo $vendedor."<br />";?></h4></font>
								</div>
								<div id="consolidado" class='col-md-12' style="display:<?PHP echo $varvisible?>;"></div>
								
								<?php
								}
								$ruc=$filavendedor['RUC'];
								$descripcion=$filavendedor['Descripcion'];
							}
					}

			$sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
			$paramspropiedades = array('vpedido');
			$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
			$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
			//echo  $rowpropiedades['valor'];

			if ($rowpropiedades['valor']==1) {
				$ventanpedido='NuevoPedido1.php';//primera version
			}else{
				$ventanpedido='NuevoPedido2.php';//segunda version con maestroclientes
			}
								?>
<script >
	function Consolidar(valorCaja1){
		//INCLUYE BONIFICACION
		var parametros = {
	                "valorCaja1" : valorCaja1
	        };
	        $.ajax({
	                data:  parametros,
	                url:   'Consolidar.php',
	                type:  'post',
	                beforeSend: function () {
	                       // $("#consolidado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) {
	                        $("#consolidado").html('Numero de Consolidado: '+response);
	                        document.getElementById("MENSAJE").value=response;
	                        //alert('Numero de Consolidado: '+response);
	                        //window.location = "Menu.php";style="display:none"
	                }
	        });		
	}
</script>
<script >
	function Buscarconsolidado(){
		//INCLUYE BONIFICACION
		var parametros = {
	                "valorCaja1" : <?php echo $idUsuario2;?>
	        };
	        $.ajax({
	                data:  parametros,
	                url:   'Buscarconsolidado.php',
	                type:  'post',
	                beforeSend: function () {
	                       // $("#consolidado").html("Procesando, espere por favor...");
	                },
	                success:  function (response) {
	                        $("#consolidado").html('Estado de consolidado: '+response);
	                        document.getElementById("MENSAJE").value=response;
	                        //alert('Numero de Consolidado: '+response);
	                        //window.location = "Menu.php"; style="display:none"
	                }
	        });		
	}
</script>
<script>
		function validar(){
	        var descripcion= document.getElementById("MENSAJE").value;

			if (isNaN(descripcion))
			{
			alert('Esto no es un numero');
			
			}
	}
</script>

			<div class="pull-right" style="display:<?PHP echo $varvisible?>;">
				<button type="button" class="buttonpepdido" onclick="Consolidar(<?php echo $idUsuario2;?>);return false;">
					<span class="glyphicon glyphicon-file"></span>Consolidar Devoluciones
				</button>
			</div>
		</div>
	  	<center>

	  		<form method="POST" action="BusquedaPedido.php">
	  		<input id="MENSAJE" type="text" class="form-control" name="numeroconsolidado" placeholder="MENSAJE" 
				style="display:none" onkeypress='return event.charCode >= 48 && event.charCode <= 57' >
			<input  type="text" class="form-control" name="idvendedor" placeholder="MENSAJE" value="<?php echo $idUsuario2;?>" style="display:none" >
			<button type="submit" class="buttonmenu">
				<font color = "FFFFFF">Busqueda de Pedido</font>
			</button>
			</form><BR>

			<form method="POST" action="BusquedaProducto.php" style="display:none">
			<button type="submit" class="buttonmenu" >
				<font color = "FFFFFF">Busqueda de Producto</font>
			</button>
			</form><BR>

			<form method="POST" action="BusquedaDevoluciones.php">
			<input  type="text" class="form-control" name="idvendedorpedido" placeholder="MENSAJE" value="<?php echo $idUsuario2;?>" style="display:none" >
			<input  type="text" class="form-control" name="PERFILEDITAR" placeholder="PERFILEDITAR" value="<?php echo $PERFIL;?>" style="display:none" >  
			<button type="submit" class="buttonmenu">
				<font color = "FFFFFF">Busqueda de Devoluciones</font>
			</button>
			</form><BR>

			<BR>
		</center>
	</div>
</div>
<script src="js/responsive.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>


