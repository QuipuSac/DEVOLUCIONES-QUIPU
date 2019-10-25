<?php
require_once("conexion.php");

	session_start();
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	$usuario = @$_SESSION['usuario'];
	$pass = @$_SESSION['pass'];
	$pedido=$_POST['pedido'];
	$consolidado=$_POST['CONSOLIDADODEV'];
	if(!@$conexion){
	
	echo "No hay conexion";
	}
	else{
		$sql ="SELECT *	FROM tbusuarios	WHERE cuenta = ? ";
		$params = array($usuario);				
		$rec = sqlsrv_query($conexion,$sql,$params);
		$fila = sqlsrv_fetch_array( $rec, SQLSRV_FETCH_ASSOC);
		$idUsuario = $fila['idUsuario'];
		$vendedor = $fila['Nombres']." ".$fila['Apellidos'];
		
		$sqlvendedor ="SELECT *	FROM tbclieprov	WHERE Nombres=? AND  Apellidos=?  ";
		$paramsvendedor = array($fila['Nombres'],$fila['Apellidos']);				
		$recvendedor = sqlsrv_query($conexion,$sqlvendedor,$paramsvendedor);
		$filavendedor = sqlsrv_fetch_array( $recvendedor, SQLSRV_FETCH_ASSOC);
		$idUsuario2=$filavendedor['idClieProv'];
 
		$sqlpedido="SELECT * FROM TBDOCUMENTOS WHERE IDTIPODOCU=215 AND NUMEDOCU=?";
		$paramspedido = array($pedido);				
		$recpedido = sqlsrv_query($conexion,$sqlpedido,$paramspedido);
		$rowpedido = sqlsrv_fetch_array( $recpedido, SQLSRV_FETCH_ASSOC);

		if($rowpedido['Total']>0){
				$iddocumentopedido=$rowpedido['idDocumento'];
				$idClieProv=$rowpedido['idClieProv'];
				$RUC=$rowpedido['RUC'];
				$NombreClieProv=$rowpedido['NombreClieProv'];
				$DIRECCIONCLIEPROV=$rowpedido['DireccionClieProv'];
				$BaseI=$rowpedido['BaseI'];
				$inafecto=0;
				$Igv=$rowpedido['Igv'];
				$Total=$rowpedido['Total'];
				date_default_timezone_set("America/Phoenix"); 
				$FechaEmision=date("d") . "/" . date("m") . "/" . date("Y");
				$Periodo=date("Y");
				$Mes=date("m");
				$iddocumento=0;
				$correlativo=0;
				$GLOSA="DEVOLUCION";
				$idDIARIO=89;
				$idtipodocu=236;
				$seridocu="001";
				$numedocu="";
				$TIPOPAGO= 0;
				$moneda="S";
				$idtica=1;
				$idtitdiario=0;
				$iddocumentoanterior = 0;
				$TC = 1;
				$totalME = 0;
				$idoperacion ="OVG";
				$detraccion ="N";
				$idtipodocreferencia = 215;
				$seridocref ="001";
				$nuedocref =$pedido;
				$idvendedor = $idUsuario2;
				//$fechadocref ="10/10/2010";
				$otros =0;
				$TDET =0;
				$IDET =0;
				$sqlCORRELATIVO="SELECT top 1 (correlativo) as correlativo	FROM tbtitdiario 
								WHERE PERIODO = YEAR(GETDATE()) AND MES =MONTH(GETDATE()) AND IDDIARIO =89 order by 1 desc";
				$queryCORRELATIVO = sqlsrv_query($conexion,$sqlCORRELATIVO);
				if ($corr=sqlsrv_fetch_array($queryCORRELATIVO,SQLSRV_FETCH_ASSOC)){
						$correlativo=$corr['correlativo']+1;	
				}else{
					$correlativo=1;
				}
				$sqlvouchercontable = "SPI_VOUCHERCONTABLE ?,?,?,?,?,?,?";
				$paramsvouchercontable = array(   
				              array($idtitdiario, SQLSRV_PARAM_INOUT),
				              array($GLOSA, SQLSRV_PARAM_IN), 
				              array($FechaEmision, SQLSRV_PARAM_IN), 
				              array($idDIARIO, SQLSRV_PARAM_IN), 
				              array($correlativo, SQLSRV_PARAM_INOUT), 
				              array($Periodo, SQLSRV_PARAM_IN),   
				              array($Mes, SQLSRV_PARAM_IN)  
				            );  
				$stmtvouchercontable = sqlsrv_query( $conexion, $sqlvouchercontable,$paramsvouchercontable);
				sqlsrv_next_result($stmtvouchercontable); 
					//echo "idtitdiario de salida es: " . $idtitdiario . "<br/>";
					//echo "correlativo de salida es: " . $correlativo . "<br/>";

				$sqlserienumero = "SPO_SERIE_NUMERO ?,?,? ";
				$params = array(&$idtipodocu,&$seridocu,&$numedocu);
				$stmtserienumero = sqlsrv_query( $conexion, $sqlserienumero,$params);
					while ($raw = sqlsrv_fetch_array($stmtserienumero)) {
					$numedocu = $raw[0];
				}
				
				$sqlspi_doc_clie_prov = "SPI_DOC_CLIE_PROV_GRIFO ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
				$paramsspi_doc_clie_prov = array(   
				    array($iddocumento, SQLSRV_PARAM_INOUT),array($idDIARIO, SQLSRV_PARAM_IN), 
				    array($idtipodocu, SQLSRV_PARAM_IN), array($seridocu, SQLSRV_PARAM_IN), 
				    array($numedocu, SQLSRV_PARAM_IN), array($correlativo, SQLSRV_PARAM_INOUT),
				    array($RUC, SQLSRV_PARAM_IN), array($NombreClieProv, SQLSRV_PARAM_IN), 
				    array($GLOSA, SQLSRV_PARAM_IN), array($TIPOPAGO, SQLSRV_PARAM_IN), 
				    array($moneda, SQLSRV_PARAM_IN), array($idtica, SQLSRV_PARAM_IN), 
				    array($BaseI, SQLSRV_PARAM_IN), array($inafecto, SQLSRV_PARAM_IN), 
				    array($Igv, SQLSRV_PARAM_IN),  array($Total, SQLSRV_PARAM_IN), 
				    array($GLOSA, SQLSRV_PARAM_IN),  array($FechaEmision, SQLSRV_PARAM_IN), 
				    array($FechaEmision, SQLSRV_PARAM_IN), array($iddocumentoanterior, SQLSRV_PARAM_IN), 
				    array($idtitdiario, SQLSRV_PARAM_IN),  array($TC, SQLSRV_PARAM_IN), 
				    array($totalME, SQLSRV_PARAM_IN),  array($idoperacion, SQLSRV_PARAM_IN), 
				    array($detraccion, SQLSRV_PARAM_IN), array($idtipodocreferencia, SQLSRV_PARAM_IN), 
				    array($seridocref, SQLSRV_PARAM_IN),  array($nuedocref, SQLSRV_PARAM_IN), 
				    array($FechaEmision, SQLSRV_PARAM_IN),  array($otros, SQLSRV_PARAM_IN), 
				    array($TDET, SQLSRV_PARAM_IN), array($IDET, SQLSRV_PARAM_IN),
				    array($DIRECCIONCLIEPROV, SQLSRV_PARAM_IN), array($idvendedor, SQLSRV_PARAM_IN)
				    );  
				$stmtspi_doc_clie_prov = sqlsrv_query($conexion,$sqlspi_doc_clie_prov,$paramsspi_doc_clie_prov);
				sqlsrv_next_result($stmtspi_doc_clie_prov); 
							
				//echo "iddocumento de salida es: " . $iddocumento . "<br/>";
				//echo "correlativo de salida es: " . $correlativo . "<br/>";

				$idOperacion ="I";
				$tabla ="TBDOCUMENTOS";
				 
				$sqlHISTORIALUSUARIOS = "SPI_HISTORIALUSUARIOS ?,?,?,?";
				$paramsHISTORIALUSUARIOS = array(   
											 array($idOperacion, SQLSRV_PARAM_IN),
											 array($idUsuario, SQLSRV_PARAM_IN),
											 array($tabla, SQLSRV_PARAM_IN),
											 array($iddocumento, SQLSRV_PARAM_IN)
											);  
				$stmtHISTORIALUSUARIOS = sqlsrv_query( $conexion, $sqlHISTORIALUSUARIOS,$paramsHISTORIALUSUARIOS);
				sqlsrv_next_result($stmtHISTORIALUSUARIOS); 

				$sqlUPDATEdetallepedido="UPDATE tbdetcomercial SET idDocumento = ?,IDDOCREFERENCIA = ? WHERE idDocumento = ?";
				$paramsUPDATEdetallepedido = array($iddocumento,$iddocumento,$iddocumentopedido);				
				$stmtUPDATEdetallepedido = sqlsrv_query($conexion,$sqlUPDATEdetallepedido,$paramsUPDATEdetallepedido);
				sqlsrv_next_result($stmtUPDATEdetallepedido);  

				//echo"<script type=\"text/javascript\">alert(".$iddocumentopedido.");</script>";

				$sqlUPDATEdetallepedido2="UPDATE TBDOCUMENTOS SET BaseI =0,Igv = 0,Total =0, SINICIALS=0,OTROS=0 WHERE idDocumento = ?";
				$paramsUPDATEdetallepedido2 = array($iddocumentopedido);				
				$stmtUPDATEdetallepedido2 = sqlsrv_query($conexion,$sqlUPDATEdetallepedido2,$paramsUPDATEdetallepedido2);
				sqlsrv_next_result($stmtUPDATEdetallepedido2);

			$sqlbonificacion="SELECT * FROM TBDOCUMENTOS WHERE idtipodocureferencia=215 and seridocureferencia='001'and idtipodocu=234 AND NUMEDOCUreferencia=?";
				$paramsbonificacion = array($pedido);				
				$recbonificacion = sqlsrv_query($conexion,$sqlbonificacion,$paramsbonificacion);
				$rowbonificacion = sqlsrv_fetch_array( $recbonificacion, SQLSRV_FETCH_ASSOC);
				$iddocumentobonificacion=$rowbonificacion['idDocumento']; 
				$BaseIbonificacion=$rowbonificacion['BaseI'];
				$Igvbonificacion=$rowbonificacion['Igv'];
				$Totalbonificacion=$rowbonificacion['Total']; 
				$SINICIALSbonificacion=$rowbonificacion['SINICIALS']; 
				$OTROSSbonificacion=$rowbonificacion['OTROS'];
				
				if($rowbonificacion['idDocumento']){ 

			$sqlUPDATEdevolucion="UPDATE tbdocumentos SET BaseI=BaseI+?,Igv=Igv+?,Total=Total+?, SINICIALS=SINICIALS+?,OTROS=OTROS+? WHERE idDocumento = ?";
			$paramsUPDATEdevolucion = array($BaseIbonificacion,$Igvbonificacion,$Totalbonificacion,$SINICIALSbonificacion,$OTROSSbonificacion,$iddocumento);				
					$stmtUPDATEdevolucion = sqlsrv_query($conexion,$sqlUPDATEdevolucion,$paramsUPDATEdevolucion);
					sqlsrv_next_result($stmtUPDATEdevolucion);



					$sqlUPDATEdetallepedido3="UPDATE tbdetcomercial SET idDocumento = ?,IDDOCREFERENCIA = ? WHERE idDocumento = ?";
					$paramsUPDATEdetallepedido3 = array($iddocumento,$iddocumento,$iddocumentobonificacion);				
					$stmtUPDATEdetallepedido3 = sqlsrv_query($conexion,$sqlUPDATEdetallepedido3,$paramsUPDATEdetallepedido3);
					sqlsrv_next_result($stmtUPDATEdetallepedido3);
					//echo"<script type=\"text/javascript\">alert('con bonificacion');</script>";
					 
				} 

				$IDCAJA=0; 
					if($consolidado>0){
				        //SPI_CAJACOMERCIALES
				        //IDCONSOLIDADO,IDDOCUMENTO(PEDIDO,BONI),'I',FECHA,IDVENDEDOR,IDCAJA,IDPROYECTO,IDCC
				       	$sqlSPI_CAJACOMERCIALES = "SPI_CAJACOMERCIALES ?,?,?,?,?,?";
						$paramsSPI_CAJACOMERCIALES = array(   
				            array($IDCAJA, SQLSRV_PARAM_IN), array($consolidado, SQLSRV_PARAM_IN),
				            array($iddocumento, SQLSRV_PARAM_IN), array($idOperacion, SQLSRV_PARAM_IN),
				            array($FechaEmision, SQLSRV_PARAM_IN), array($idvendedor, SQLSRV_PARAM_IN)
				        );  
						$stmtSPI_CAJACOMERCIALES = sqlsrv_query( $conexion, $sqlSPI_CAJACOMERCIALES,$paramsSPI_CAJACOMERCIALES);
						sqlsrv_next_result($stmtSPI_CAJACOMERCIALES); 
				    }
		}else{
			echo"<script type=\"text/javascript\">alert('No se puede devolver');</script>";
		}

	}
?>