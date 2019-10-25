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
		$sql ="SELECT *	FROM tbusuarios	WHERE cuenta = ? ";
		$params = array($usuario);				
		$rec = sqlsrv_query($conexion,$sql,$params);
		$fila = sqlsrv_fetch_array( $rec, SQLSRV_FETCH_ASSOC);
		$idUsuario = $fila['idUsuario'];

		$sumador_totalbonificacion=0;
		$xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($fila['idUsuario'], 0).'.xml');
			foreach($xml->session as $item){
				
				$id_tmp=''. $item->iditem .'';
				$codigo_producto=''. $item->codigo .'';
				$cantidad=(double)''. $item->cantidad .'';
				$nombre_producto=''. $item->descripcion .'';
				$precio_venta=(double)''. $item->precio_venta .'';
				$precio_venta_f=number_format($precio_venta,2);//Formateo variables
				$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
				$precio_total=$precio_venta_r*$cantidad;
				$precio_total_f=number_format($precio_total,2);//Precio total formateado
				$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
				$sumador_totalbonificacion+=$precio_total_r;
			}
			//PARA ACTUALIZAR

				$sqlNUMERO ="SELECT * FROM TBDOCUMENTOS WHERE IDTIPODOCU=234 AND NUMEDOCUREFERENCIA=?";
				$paramsNUMERO = array($_POST['valorCaja5']);				
				$recNUMERO = sqlsrv_query($conexion,$sqlNUMERO,$paramsNUMERO);
				$filaNUMERO = sqlsrv_fetch_array( $recNUMERO, SQLSRV_FETCH_ASSOC);
				$numboni = $filaNUMERO['NumeDocu'];

				$nroPEDIDO = $_POST['valorCaja5'];
				
				$GLOSA="BONIFICACION";
				$idDIARIO=89;
				$numedocu=$numboni;
				$idtipodocu=234;
				$iddocumento=$_POST['valorCaja4'];
				$RUC = $_POST['valorCaja1'];
				$CLIENTE = $_POST['valorCaja2'];
				$TIPOPAGO=0;
				$moneda="S";
				$idtica=1;
				$total2 = number_format($sumador_totalbonificacion, 2, '.', '');
				$basei = number_format(($total*100)/118, 2, '.', '');
				$igv = number_format($total-$basei, 2, '.', '');
				$inafecto = number_format(0, 2, '.', '');
				date_default_timezone_set("America/Phoenix"); 
				$FechaEmision=date("d") . "/" . date("m") . "/" . date("Y");
				$iddocumentoanterior = 0;
				$idtitdiario = 0;
				$TC = 1;
				$totalME = 0;
				$idoperacion ="OVG";
				$detraccion ="N";
				$idtipodocreferencia = 215;
				$seridocref ="";
				$nuedocref =$_POST['valorCaja5'];
				$otros =0;
				$TDET =0;
				$IDET =0;	

				//echo "<br>".$RUC;
				//echo "<br>".$CLIENTE;
				///echo "<br>".$nroPEDIDO;

				$sqlSPU_DOCPED_CLIE_PROV = "SPU_DOCPED_CLIE_PROV ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
					$paramsSPU_DOCPED_CLIE_PROV = array(   
		             array($iddocumento, SQLSRV_PARAM_INOUT), array($RUC, SQLSRV_PARAM_IN), 
		             array($CLIENTE, SQLSRV_PARAM_IN), array($GLOSA, SQLSRV_PARAM_IN), 
		             array($TIPOPAGO, SQLSRV_PARAM_IN), array($moneda, SQLSRV_PARAM_IN),   
		             array($idtica, SQLSRV_PARAM_IN), array($basei, SQLSRV_PARAM_IN), 
		             array($inafecto, SQLSRV_PARAM_IN), array($igv, SQLSRV_PARAM_IN), 
		             array($total2, SQLSRV_PARAM_IN), array($GLOSA, SQLSRV_PARAM_IN), 
		             array($FechaEmision, SQLSRV_PARAM_IN), array($FechaEmision, SQLSRV_PARAM_IN), 
		             array($iddocumentoanterior, SQLSRV_PARAM_IN), array($idtitdiario, SQLSRV_PARAM_INOUT), 
		             array($TC, SQLSRV_PARAM_IN), array($totalME, SQLSRV_PARAM_IN), 
		             array($idoperacion, SQLSRV_PARAM_IN), array($detraccion, SQLSRV_PARAM_IN), 
		             array($idtipodocreferencia, SQLSRV_PARAM_IN), array($seridocref, SQLSRV_PARAM_IN), 
		             array($nuedocref, SQLSRV_PARAM_IN), array($TDET, SQLSRV_PARAM_IN), 
		             array($IDET, SQLSRV_PARAM_IN), array($FechaEmision, SQLSRV_PARAM_IN), 
		             array($otros, SQLSRV_PARAM_IN), array($idtipodocu, SQLSRV_PARAM_IN)
		               );  
					$stmtSPU_DOCPED_CLIE_PROV = sqlsrv_query( $conexion, $sqlSPU_DOCPED_CLIE_PROV,$paramsSPU_DOCPED_CLIE_PROV);
					sqlsrv_next_result($stmtSPU_DOCPED_CLIE_PROV); 
					///////////////////////////////////////////////////eliminar detalle
				$sqlSPD_DETCOMERCIAL_PEDIDOS = "SPD_DETCOMERCIAL_PEDIDOS ?";
					$paramsSPD_DETCOMERCIAL_PEDIDOS = array(   
		             array($iddocumento, SQLSRV_PARAM_IN)
		               );  
					$stmtSPD_DETCOMERCIAL_PEDIDOS = sqlsrv_query( $conexion, $sqlSPD_DETCOMERCIAL_PEDIDOS,$paramsSPD_DETCOMERCIAL_PEDIDOS);
					sqlsrv_next_result($stmtSPD_DETCOMERCIAL_PEDIDOS); 		
					/////////////////////////////////////////////////añadir detalle nuevo
				$xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($fila['idUsuario'], 0).'.xml');
				
				foreach($xml->session as $item){

					$params = array();
					$sql=sqlsrv_query($conexion,"SELECT * FROM tbitems WHERE idItem='". $item->iditem ."'" );
					while ($row=sqlsrv_fetch_array($sql))
					{
							$idDetComercial=0;

							$id_tmp=''. $item->iditem .'';
							$codigo_producto=''. $item->codigo .'';
							$cantidad=(double)''. $item->cantidad .'';
							$nombre_producto=''. $item->descripcion .'';
							$idUnidad=$row['idUnidad'];
							$precio_venta=(double)$item->precio_venta;
							$precio_venta_f=number_format($precio_venta, 2, '.', '');//Formateo variables
							$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
							$precio_total=$precio_venta_r*$cantidad;
							$precio_total_f=number_format($precio_total, 2, '.', '');//Precio total formateado
							$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
							$sumador_totalbonificacion+=$precio_total_r;//Sumador
							/////////////
							$BaseI=number_format(($precio_venta_f*100)/118, 2, '.', '');;
							$IGV=number_format($precio_total_f-($BaseI*$cantidad), 2, '.', '');
							$BaseIME =0;
							$PrecioME=0;
							$Cantidad=0;
							$PorcentDscto=0;
							$Descuento=0;
							$DescuentoME=0;
							$IGVME=0;
							$TotalME=0;
							
							$Afecto ='S';
							$idProyecto=0;
							$idAlmacen=1;
							$req='N';
							$DESLOTE='-';
							
							$sqlDETCOMERCIAL_PEDIDOS = "SPI_DETCOMERCIAL_PEDIDOS ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
							$paramsDETCOMERCIAL_PEDIDOS = array(   
				                 array($idDetComercial, SQLSRV_PARAM_INOUT), array($iddocumento, SQLSRV_PARAM_IN),
				                 array($codigo_producto, SQLSRV_PARAM_IN), array($nombre_producto, SQLSRV_PARAM_IN),
				                 array($idUnidad, SQLSRV_PARAM_IN), array($BaseIME, SQLSRV_PARAM_IN),
				                 array($BaseI, SQLSRV_PARAM_IN),  array($PrecioME, SQLSRV_PARAM_IN),
				                 array($precio_venta_f, SQLSRV_PARAM_IN),  array($cantidad, SQLSRV_PARAM_IN),
				                 array($PorcentDscto, SQLSRV_PARAM_IN),  array($Descuento, SQLSRV_PARAM_IN),
				                 array($DescuentoME, SQLSRV_PARAM_IN),  array($IGV, SQLSRV_PARAM_IN),
				                 array($IGVME, SQLSRV_PARAM_IN),  array($TotalME, SQLSRV_PARAM_IN),
				                 array($precio_total_f, SQLSRV_PARAM_IN),  array($Afecto, SQLSRV_PARAM_IN), 
				                 array($idProyecto, SQLSRV_PARAM_IN),  array($idAlmacen, SQLSRV_PARAM_IN),
				                 array($req, SQLSRV_PARAM_IN), array($DESLOTE, SQLSRV_PARAM_IN)
				               );  
							$stmtDETCOMERCIAL_PEDIDOS = sqlsrv_query( $conexion, $sqlDETCOMERCIAL_PEDIDOS,$paramsDETCOMERCIAL_PEDIDOS);
							sqlsrv_next_result($stmtDETCOMERCIAL_PEDIDOS); 
							//echo "idDetComercial de salida es: " . $idDetComercial . "<br/>";
					}
				}

}
	echo $numboni = $filaNUMERO['NumeDocu'];
	echo		$nroPEDIDO = $_POST['valorCaja5'];
	echo $iddocumento=$_POST['valorCaja4'];
	echo $RUC = $_POST['valorCaja1'];
	echo				$CLIENTE = $_POST['valorCaja2'];
	echo $nuedocref =$_POST['valorCaja5'];
	echo "Total de bonificacion: " . $total2 ;

?>