<?php
include("conexion.php");
	switch($ERROR){
	 	case 1:
      	echo '<div class="error">Su usuario es incorrecto, intente nuevamente.</div>';
	    Echo ‘<a href=”’.$_SERVER['HTTP_REFERER'].’”>Ejemplo de Botón Regresar en PHP</a>’;
	    break;

	    case 2:
	    echo '<div class="error">Su usuario es incorrecto, intente nuevamente.</div>';
	    Echo ‘<a href=”’.$_SERVER['HTTP_REFERER'].’”>Ejemplo de Botón Regresar en PHP</a>’;
	    break;

	}
?>
