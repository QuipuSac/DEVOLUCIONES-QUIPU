<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<title>Inicio</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen" > 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 
</head>
<body class="container"  background="img/sistemaVerde.JPG">
<div class="container-fluid">
	<form class="form-horizontal" method="POST" action="ValidarUsuario.php">
	<br/>

		<div class="container">	
			<center>
			<img src="img/icono.png" alt="Módulo de pedidos" class="img-responsive" >
			</center>
		</div>
		<br/>
			<div class="container">	
					<select class="form-control" name="nempresas" data-width="auto" onchange="inputEmail.select()">
						<option value="" selected data-icon="glyphicon-heart">Seleccione Empresa</option>
						<?php
						$info = array('Database'=>'MODELO', 'UID'=>'XOSCAR', 'PWD'=>'QUIPU2846+*'); 
						$link = sqlsrv_connect('quipuoscategui.hopto.org', $info);
						if(!$link){
							die( print_r( sqlsrv_errors(), true));
						}
						else{
							$query="SELECT * FROM TBBASES WHERE ESTADO=?";
						$resultado = sqlsrv_query($link,$query,array('1'));
						if ($resultado){
						while($renglon = sqlsrv_fetch_array($resultado))
							{
							$valor=$renglon['DESCRIPCION'];
							echo "<option value=".$valor.">".$valor."</option>\n";
							}
						}
						sqlsrv_close($link);
						}
						?>
					</select>
			</div>
		<br/>
			<div class="container">
				 <font color = "585858">
					<label for="inputEmail" class="control-label">Usuario: </label>
				</font>
					<input type="text" class="form-control" id="inputEmail" name="nnombre" placeholder="Usuario" />
			</div>
		<br/>
			<div class="container">
				<font color = "585858">
					<label for="inputPassword" class="control-label">Contraseña: </label>
				</font>
					<input type="password" class="form-control" id="inputPassword" name="npassword" placeholder="Contraseña" />
			</div>
		<br/>
		<center>
			<div class="container">
					<button type="submit" class="btn btn-block"  STYLE="background-color:#6E6E6E">
					<span class="glyphicon glyphicon-play"></span>
					<font color = "FFFFFF">Inicar Sesion</font>
		 			</button>
			</div>
		</center>
	</form>
</div>
<br/><br/><br/><br/><br/><br/><br/>
<script src="js/responsive.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>




