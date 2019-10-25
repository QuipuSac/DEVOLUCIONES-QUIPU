<?php
//leonardo sifuentes 25/10/2019 04:59 p.m. 25/10/2020
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
					//echo "DATOS RECIBIDOS: "."</BR>";
					//echo "IDCLIEPROV: ".$_POST['valorCaja1']."</BR>";
					//echo "DOCUMENTO CREADO..."."</BR>";
					//OBTENIENDO RUC Y RAZSOCIAL
					$sqlvendedor ="SELECT *	FROM tbclieprov	WHERE idClieProv = ? ";
					$paramsvendedor = array($_POST['valorCaja1']);				
					$recvendedor = sqlsrv_query($conexion,$sqlvendedor,$paramsvendedor);
					$filavendedor = sqlsrv_fetch_array( $recvendedor, SQLSRV_FETCH_ASSOC);
					$ruc=$filavendedor['RUC'];
					$descripcion=$filavendedor['Descripcion'];
					$DIRECCION=$filavendedor['Direccion'];

					//echo "RUC: ".$ruc."</BR>";
					//echo "Descripcion: ".$descripcion."</BR>";
					//echo "Direccion: ".$DIRECCION."</BR>";
					//echo "///////"."</br>";
				//[SPI_DOC_CLIE_PROV_GRIFO]
					date_default_timezone_set("America/Phoenix"); 
					$iddocumento=0;
					$correlativo=0;
					$GLOSA="CONSOLIDADO DEVOLUCIONES";
					$idDIARIO=89;
					$idtipodocu=238;//CONCILIADO PEDIDOS
					$seridocu=date("Y").$_POST['valorCaja1'];
					$numedocu="";
					$TIPOPAGO=0;
					$moneda="S";
					$idtica=1;
					$total = 0;
					$basei = 0;
					$igv = 0;
					$inafecto = 0;
					$idtitdiario = 0;
					$iddocumentoanterior = 0;
					$TC = 1;
					$totalME = 0;
					$idoperacion ="OVG";
					$detraccion ="N";
					$idtipodocreferencia = 0;
					$seridocref ="";
					$nuedocref ="";
					$DIRECCIONCLIEPROV ="-";
					$idvendedor = $_POST['valorCaja1'];
					//$fechadocref ="10/10/2010";
					$otros =0;
					$TDET =0;
					$IDET =0;
					$FechaEmision=date("m") . "/" . date("d") . "/" . date("Y");
					$Periodo=date("Y");
					$Mes=date("m");	

		$sqlCONSOLIDADO ="SELECT TOP 1 * FROM TBDOCUMENTOS WHERE IDTIPODOCU=238 AND IDVENDEDOR=? ORDER BY 1 DESC";
		$paramsCONSOLIDADO = array($_POST['valorCaja1']);
		$recCONSOLIDADO = sqlsrv_query($conexion,$sqlCONSOLIDADO,$paramsCONSOLIDADO);
		$rowCONSOLIDADO = sqlsrv_fetch_array( $recCONSOLIDADO, SQLSRV_FETCH_ASSOC) ;
		//echo  $rowpropiedades['valor'];
		if ($rowCONSOLIDADO['Estado']=='R') {
			$sqlCERRARCONSOLIDADO ="UPDATE TBDOCUMENTOS SET estado='C' WHERE iddocumento=?";
			$paramsCERRARCONSOLIDADO = array($rowCONSOLIDADO['idDocumento']);
			$stmtCERRARCONSOLIDADO = sqlsrv_query($conexion,$sqlCERRARCONSOLIDADO,$paramsCERRARCONSOLIDADO);
			sqlsrv_next_result($stmtCERRARCONSOLIDADO); 
			echo "CONSOLIDADO FINALIZADO";
		}else{
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
					/*
								echo "iddocumento".$iddocumento."</BR>";
								echo "correlativo".$correlativo."</BR>";
								echo "glosa".$GLOSA."</BR>";
								echo "idDIARIO".$idDIARIO."</BR>";
								echo "idtipodocu".$idtipodocu."</BR>";
								echo "serie".$seridocu."</BR>";
								echo "mnuemro".$numedocu."</BR>";
								echo "TIPOPAGO".$TIPOPAGO."</BR>";
								echo "moneda".$moneda."</BR>";
								echo "idtica".$idtica."</BR>";
								echo "total".$total."</BR>";
								echo "base".$basei."</BR>";
								echo "igv".$igv."</BR>";
								echo "ina".$inafecto."</BR>";
								echo "idtitdiario".$idtitdiario."</BR>";
								echo "iddocumentoanterior".$iddocumentoanterior."</BR>";
								echo "tc".$TC ."</BR>";
								echo "totalme".$totalME."</BR>";
								echo "idoperacion".$idoperacion ."</BR>";
								echo "detraccion".$detraccion."</BR>";
								echo "idtipodocreferencia".$idtipodocreferencia."</BR>";
								echo "serie2".$seridocref."</BR>";
								echo "nuemro2".$nuedocref."</BR>";
								echo "direccion".$DIRECCIONCLIEPROV ."</BR>";
								echo "idvendedor".$idvendedor ."</BR>";
								//$fechadocref ="10/10/2010";
								echo $otros."</BR>";
								echo $TDET."</BR>";
								echo $IDET."</BR>";
								echo $FechaEmision."</BR>";
								echo $Periodo."</BR>";
								echo $Mes."</BR>";
					*/
					$sqlspi_doc_clie_prov = "SPI_DOC_CLIE_PROV_GRIFO ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
					$paramsspi_doc_clie_prov = array(   
					        array($iddocumento, SQLSRV_PARAM_INOUT),array($idDIARIO, SQLSRV_PARAM_IN), 
					        array($idtipodocu, SQLSRV_PARAM_IN), array($seridocu, SQLSRV_PARAM_IN), 
						    array($numedocu, SQLSRV_PARAM_IN), array($correlativo, SQLSRV_PARAM_INOUT),
						    array($ruc, SQLSRV_PARAM_IN), array($descripcion, SQLSRV_PARAM_IN), 
						    array($GLOSA, SQLSRV_PARAM_IN), array($TIPOPAGO, SQLSRV_PARAM_IN), 
						    array($moneda, SQLSRV_PARAM_IN), array($idtica, SQLSRV_PARAM_IN), 
						    array($basei, SQLSRV_PARAM_IN), array($inafecto, SQLSRV_PARAM_IN), 
						    array($igv, SQLSRV_PARAM_IN),  array($total, SQLSRV_PARAM_IN), 
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
					$sqlMENSAJE ="SELECT *	FROM tbdocumentos	WHERE iddocumento = ? ";
					$paramsMENSAJE = array($iddocumento);
					$recMENSAJE = sqlsrv_query($conexion,$sqlMENSAJE,$paramsMENSAJE);
					$rowMENSAJE = sqlsrv_fetch_array( $recMENSAJE);

					$numerodoccc=$rowMENSAJE['NumeDocu'];
			echo $numerodoccc;
		}		
				//SPI_CAJACOMERCIALES
	}
?>