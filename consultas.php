<?php
require_once('conexion.php');

class consulta
{
	private $objeto = '';
	public function __construct()
	{
		$this->objeto = new conexion();
		$this->objeto->conectar();
	}

	public function ValidarUsuario (){
		$sql ="SELECT ESTADO FROM TBUSUARIOS WHERE cuenta=? AND Clave=?";
		$result = $this->objeto->ejecutar($sql);
		return $result;	
	}

}



?>