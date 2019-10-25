<?php
	$codigo=@$_POST['codigo'];

		$Name = 'prueba.xml';
		$FileName = "./$Name";
		$Header = '';
		$Header .= "\r\n";

	    require_once("conexion.php");

		session_start();
		$basedatos =@$_SESSION['basedatos'];
		$conexion=Conexion($basedatos); 
		if(!@$conexion){
			//die( print_r( sqlsrv_errors(), true));
			echo "No hay conexion";
			echo $usuario;
			echo $basedatos;
			echo $pass;
		}else{
			$sql ="SELECT Descripcion FROM TBITEMS WHERE CODIGO=?";
			$params = array($codigo);
			$resultado = sqlsrv_query($conexion,$sql,$params);

			while ($dato=sqlsrv_fetch_array($resultado)) {
				echo ("<codigo>$dato[0]</linea>");
			}
		}
	?>