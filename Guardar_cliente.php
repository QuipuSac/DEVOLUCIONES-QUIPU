<?php
require_once("conexion.php");

	session_start();
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	$usuario = @$_SESSION['usuario'];
	$pass = @$_SESSION['pass'];

	if(!@$conexion){
	
	echo "No hay conexion";
	}
	else{
/*
		echo "RUC  es: ".$_POST['valor1']."<BR/>";
		echo"RAZSOCIAL  es: ".$_POST['valor2']."<BR/>";
		echo "PERSONA  es: ".$_POST['valor4']."<BR/>";
		echo"DIRECCION  es: ".$_POST['valor3']."<BR/>";
		echo"telefono1  es: ".$telefono1 = '-';
		echo"telefono2  es: ".$telefono2  = '-';
		echo"fax  es: ".$fax = '-';
		echo"email  es: ".$email = '-';
		echo"Contacto  es: ".$Contacto = '-'; 
		echo"tipoClieProv  es: ".$tipoClieProv = 'C'; 
		echo"Estado  es: ".$Estado = 'A'; 
		echo"Nacionalidad  es: ".$Nacionalidad = 'PER';
		echo"CodDep  es: ".$CodDep =1; 
		echo"CodProv  es: ".$CodProv =1; 
		echo"CodDis  es: ".$CodDis =1; 
		echo"idClieProv  es: ".$idClieProv =0 ; 
		echo"Nombres  es: ".$Nombres  = '-'; 
		echo"Apellidos  es: ".$Apellidos = '-'; 
		echo"FechaNacimiento  es: ".$FechaNacimiento ='15/05/2017';
		echo "TDI  es: ".$_POST['valor5']."<BR/>";
  */
		//TBITEMS-TBALMACENES
		$telefono1 = '-';
		$telefono2  = '-';
		$fax = '-';
		$email = '-';
		$Contacto = '-'; 
		$tipoClieProv = 'C'; 
		$Estado = 'A'; 
		$Nacionalidad = 'PER';
		$CodDep =1; 
		$CodProv =1; 
		$CodDis =1; 
		$idClieProv =0 ; 
		$Nombres  = '-'; 
		$Apellidos = '-'; 
		$FechaNacimiento ='15/05/2017';
		

		$sqlCLIEPROV ="SELECT *	FROM tbclieprov	WHERE RUC = ? ";
		$paramsCLIEPROV = array($_POST['valor1']);				
		$recCLIEPROV = sqlsrv_query($conexion,$sqlCLIEPROV,$paramsCLIEPROV);
		$filaCLIEPROV = sqlsrv_fetch_array( $recCLIEPROV, SQLSRV_FETCH_ASSOC);
		$idUsuario2=$filaCLIEPROV['idClieProv'];

		
		if(!@$idUsuario2)
		{
			$sqlSPI_CLIEPROV_WEB = "SPI_CLIEPROV_WEB ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
			$paramsSPI_CLIEPROV_WEB = array(   
		            array($_POST['valor1'], SQLSRV_PARAM_IN),
		            array($_POST['valor2'], SQLSRV_PARAM_IN), 
		            array($_POST['valor4'], SQLSRV_PARAM_IN), 
	                array($_POST['valor3'], SQLSRV_PARAM_IN), 
	                array($telefono1, SQLSRV_PARAM_IN),
		            array($telefono2, SQLSRV_PARAM_IN),  
		            array($fax, SQLSRV_PARAM_IN),  
		            array($email, SQLSRV_PARAM_IN),  
		            array($Contacto, SQLSRV_PARAM_IN),  
		            array($tipoClieProv, SQLSRV_PARAM_IN),  
		            array($Estado, SQLSRV_PARAM_IN),  
		            array($Nacionalidad, SQLSRV_PARAM_IN), 
		            array($CodDep, SQLSRV_PARAM_IN),  
		            array($CodProv, SQLSRV_PARAM_IN),  
		            array($CodDis, SQLSRV_PARAM_IN),  
		            array($idClieProv, SQLSRV_PARAM_INOUT),  
		            array($Nombres, SQLSRV_PARAM_IN),  
		            array($Apellidos, SQLSRV_PARAM_IN),  
		            array($FechaNacimiento, SQLSRV_PARAM_IN),  
		            array($_POST['valor5'], SQLSRV_PARAM_IN)  
		              );  
			$stmtSPI_CLIEPROV_WEB = sqlsrv_query($conexion,$sqlSPI_CLIEPROV_WEB,$paramsSPI_CLIEPROV_WEB);
			sqlsrv_next_result($stmtSPI_CLIEPROV_WEB); 

				//echo "idClieProv de salida es: " . $idClieProv . "<br/>";					
					
			echo"<script type=\"text/javascript\">alert('Cliente registrado.');</script>";
		}
		else{
			echo"<script type=\"text/javascript\">alert('Cliente ya registrado como: ".$filaCLIEPROV['Descripcion']."');</script>";
		}
	}
?>