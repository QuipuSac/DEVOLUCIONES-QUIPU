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

			$sumador_total=0;
			$xml = simplexml_load_file('C:\bkp\pedido'.substr($basedatos, 0).substr($fila['idUsuario'], 0).'.xml');
			foreach($xml->session as $item){
			$params = array();
			$sql=sqlsrv_query($conexion,"SELECT * FROM tbitems WHERE idItem='". $item->iditem ."'" );
				while ($row=sqlsrv_fetch_array($sql))
				{
				$id_tmp=$row['idItem'];
				$codigo_producto=$row['Codigo'];
				$cantidad=(double)$item->cantidad;
				$nombre_producto=$row['Descripcion'];
				$idUnidad=(int)$item->unidad;
				$precio_venta=(double)$item->precio_venta;
				$precio_venta_f=number_format($precio_venta, 2, '.', '');//Formateo variables
				$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
				$precio_total=$precio_venta_r*$cantidad;
				$precio_total_f=number_format($precio_total, 2, '.', '');//Precio total formateado
				$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
				$sumador_total+=$precio_total_r;//Sumador
				/////////////
				//echo "<br>".$sumador_total;
				}
			}
			//PARA ACTUALIZAR
				$nroPEDIDO = $_POST['valorCaja5'];
				
				$GLOSA="PROFORMAS";
				$idDIARIO=89;
				$numedocu=$_POST['valorCaja5'];
				$idtipodocu=215;
				$iddocumento=$_POST['valorCaja4'];
				$RUC = $_POST['valorCaja1'];
				$CLIENTE = $_POST['valorCaja2'];
				$TIPOPAGO=$_POST['valorCaja8'];
				$moneda="S";
				$idtica=1;
				$total = number_format($sumador_total, 2, '.', '');
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
				$idtipodocreferencia = $_POST['valorCaja3'];
				$seridocref ="";
				$nuedocref ="";
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
		             array($total, SQLSRV_PARAM_IN), array($GLOSA, SQLSRV_PARAM_IN), 
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
				$xml = simplexml_load_file('C:\bkp\pedido'.substr($basedatos, 0).substr($fila['idUsuario'], 0).'.xml');
				
				foreach($xml->session as $item){
				$params = array();
				$sql=sqlsrv_query($conexion,"SELECT * FROM tbitems WHERE idItem='". $item->iditem ."'" );
					while ($row=sqlsrv_fetch_array($sql))
					{
							$idDetComercial=0;

							$id_tmp=$row['idItem'];
							$codigo_producto=$row['Codigo'];
							$cantidad=(double)$item->cantidad;
							$nombre_producto=$row['Descripcion'];
							$idUnidad=(int)$item->unidad;
							$precio_venta=(double)$item->precio_venta;
							$precio_venta_f=number_format($precio_venta, 2, '.', '');//Formateo variables
							$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
							$precio_total=$precio_venta_r*$cantidad;
							$precio_total_f=number_format($precio_total, 2, '.', '');//Precio total formateado
							$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
							$sumador_total+=$precio_total_r;//Sumador
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
//MODIFICAR BONIFICACION
			$archivo = 'C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';		
			$sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
			$paramspropiedades = array('BONIWEB');
			$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
			$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
					//echo  $rowpropiedades['valor'];

			if ($rowpropiedades['valor']=='S') {

				$sqlNUMERO ="SELECT * FROM TBDOCUMENTOS WHERE IDTIPODOCU=234 AND NUMEDOCUREFERENCIA=?";
				$paramsNUMERO = array($_POST['valorCaja5']);				
				$recNUMERO = sqlsrv_query($conexion,$sqlNUMERO,$paramsNUMERO);
				$filaNUMERO = sqlsrv_fetch_array( $recNUMERO, SQLSRV_FETCH_ASSOC);
				$numboni = $filaNUMERO['NumeDocu'];
				//PREGUNTA SI HAY DOCUMENTO DE BONIFICACION CREADO
				if ($_POST['valorCaja7']==0) {
				//$total2=147;
						$archivo = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($fila['idUsuario'], 0).'.xml';			
						if(file_exists($archivo))
						{	
							$xml = simplexml_load_file($archivo);
							$xml->session ;
								
							if (!@$xml->session){
								echo"Sin bonificación...";
							}else{

								$sumador_total=0;
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
								$sumador_total+=$precio_total_r;
								}
								$iddocumento=0;
								$correlativo=0;
								$GLOSA="BONIFICACION";
								$idDIARIO=89;
								$idtipodocu=234;
								$seridocu="001";
								$numedocu="";
								$TIPOPAGO=$_POST['valorCaja8'];
								$moneda="S";
								$idtica=1;
								$total2 = number_format($sumador_total, 2, '.', '');
								$basei = number_format(($total2*100)/118, 2, '.', '');
								$igv = number_format($total2-$basei, 2, '.', '');
								$inafecto = number_format(0, 2, '.', '');
								$idtitdiario = 0;
								$iddocumentoanterior = 0;
								$TC = 1;
								$totalME = 0;
								$idoperacion ="OVG";
								$detraccion ="N";
								$idtipodocreferencia = 215;
								$seridocref ="001";
								$nuedocref =$_POST['valorCaja5'];
								$DIRECCIONCLIEPROV =$_POST['valorCaja6'];
								$idvendedor = $idUsuario;
								$otros =0;
								$TDET =0;
								$IDET =0;
								date_default_timezone_set("America/Phoenix"); 
								$FechaEmision=date("d") . "/" . date("m") . "/" . date("Y");
								$Periodo=date("Y");
								$Mes=date("m");
								$sqlCORRELATIVO="SELECT top 1 (correlativo) as correlativo	FROM tbtitdiario 
												WHERE PERIODO = YEAR(GETDATE()) AND MES =MONTH(GETDATE()) AND IDDIARIO =89  order by 1 desc";
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
						                 array($_POST['valorCaja1'], SQLSRV_PARAM_IN), array($_POST['valorCaja2'], SQLSRV_PARAM_IN), 
						                 array($GLOSA, SQLSRV_PARAM_IN), array($TIPOPAGO, SQLSRV_PARAM_IN), 
						                 array($moneda, SQLSRV_PARAM_IN), array($idtica, SQLSRV_PARAM_IN), 
						                 array($basei, SQLSRV_PARAM_IN), array($inafecto, SQLSRV_PARAM_IN), 
						                 array($igv, SQLSRV_PARAM_IN),  array($total2, SQLSRV_PARAM_IN), 
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
													$idUnidad=(int)$item->unidad;
													$precio_venta=(double)$item->precio_venta;
													$precio_venta_f=number_format($precio_venta, 2, '.', '');//Formateo variables
													$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
													$precio_total=$precio_venta_r*$cantidad;
													$precio_total_f=number_format($precio_total, 2, '.', '');//Precio total formateado
													$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
													$sumador_total+=$precio_total_r;//Sumador
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
											}
										}
										$sqlMENSAJE ="SELECT *	FROM tbdocumentos	WHERE iddocumento = ? ";
										$paramsMENSAJE = array($iddocumento);
										$recMENSAJE = sqlsrv_query($conexion,$sqlMENSAJE,$paramsMENSAJE);
										$rowMENSAJE = sqlsrv_fetch_array( $recMENSAJE);
										$numerodoccc=$rowMENSAJE['NumeDocu'];
										//echo"'el numero de BONIFICACION es:  $numerodoccc ';";  
							}
						}
						//else{
						//	echo"<script type=\"text/javascript\">alert('Sin bonificación');</script>";
						//}
				}else{
						
						$archivo = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($fila['idUsuario'], 0).'.xml';			
						if(file_exists($archivo))
						{
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
							$iddocumento=$_POST['valorCaja7'];
							$RUC = $_POST['valorCaja1'];
							$CLIENTE = $_POST['valorCaja2'];
							$TIPOPAGO=$_POST['valorCaja8'];
							$moneda="S";
							$idtica=1;
							$total2 = number_format($sumador_totalbonificacion, 2, '.', '');
							$basei = number_format(($total2*100)/118, 2, '.', '');
							$igv = number_format($total2-$basei, 2, '.', '');
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
							$paramsSPD_DETCOMERCIAL_PEDIDOS = array(array($iddocumento, SQLSRV_PARAM_IN));  
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
												$idUnidad=(int)$item->unidad;
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
						}//if exist
				}
		}
	}
	echo "Modificacion satisfactoria..." ; 
?>


