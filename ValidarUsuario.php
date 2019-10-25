<?php
require_once("conexion.php");
session_start();
$_SESSION['basedatos'] = @$_POST['nempresas'];
$_SESSION['BUSQUEDA']=0;
$_SESSION['usuario']= @$_POST['nnombre'];
$_SESSION['pass']=@$_POST['npassword'];

$basedatos =@$_SESSION['basedatos'];
$conexion=Conexion($basedatos);
$usuario = @$_SESSION['usuario'];
$pass = @$_SESSION['pass'];

if(!@$conexion){
	die( print_r( sqlsrv_errors(), true));
	echo "No hay conexion";
}
else{
	//echo 'Conectado';
	//echo $usuario;
	//echo $pass;
	$sql ="SELECT ESTADO FROM TBUSUARIOS WHERE cuenta=? AND Clave=?";
	$params = array($usuario, $pass);
	$rec = sqlsrv_query($conexion,$sql,$params);
		$count = 0;
		while ($row = sqlsrv_fetch_object($rec)) {
			$count++;
			$result = $row;
		}
		if ($count == 1) {
			HEADER ("Location:Menu.php");
		}else{
			echo '<div class="error">Su usuario es incorrecto, intente nuevamente.</div>';
			echo '<form>
                	<input type="button" value="volver atrás" name="volver atrás2" onclick="history.back()" />  
                  </form>';
		}
}	
?>