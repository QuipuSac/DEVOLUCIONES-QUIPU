<?php
	$session_id= session_id();
	require_once("conexion.php");
	session_start();
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	$usuario = @$_SESSION['usuario'];
	$pass = @$_SESSION['pass'];

	if(!@$conexion){
	//die( print_r( sqlsrv_errors(), true));
	echo "No hay conexion";
	}
	else{
		$sql ="SELECT *	FROM tbusuarios	WHERE cuenta = ? ";
		$params = array($usuario);
							
		$rec = sqlsrv_query($conexion,$sql,$params);
		if (!$rec) {
			echo 'No se pudo ejecutar la consulta: ' ;
			if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
					echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
					echo "code: ".$error[ 'code']."<br />";
					echo "message: ".$error[ 'message']."<br />";
				}
			}
		}else{
			$row = sqlsrv_fetch_array( $rec, SQLSRV_FETCH_ASSOC);
			
			if (isset($_POST['id'])){$id=$_POST['id'];}
			if (isset($_POST['cantidad'])){$cantidad=$_POST['cantidad'];}
			if (isset($_POST['precio_venta'])){$precio_venta=$_POST['precio_venta'];}
			if (isset($_POST['unidad'])){$unidad=$_POST['unidad'];}
				
				if (!empty($id) and !empty($cantidad) and !empty($precio_venta))
				{	
					$archivo = 'C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
						
					$sqlpropiedades ="SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
					$paramspropiedades = array('BONIWEB');
					$recpropiedades = sqlsrv_query($conexion,$sqlpropiedades,$paramspropiedades);
					$rowpropiedades = sqlsrv_fetch_array( $recpropiedades, SQLSRV_FETCH_ASSOC) ;
					//echo  $rowpropiedades['valor'];

					if ($rowpropiedades['valor']=='S') {
								$archivobonificacion = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
								///////verificando bonificacion
										$querybonificacion ="SELECT TOP 1 B.iditemboni AS idItem,I.Codigo AS Codigo,B.DESCRIPCION AS Descripcion,B.CANTBONI AS CANTIDAD,B.PRECIO AS precioesp,B.iditem as itembonificador,B.CANTIDAD AS FACTOR,B.IDUNIDADBONI AS UNIDAD
											FROM tbbonificaciones B INNER JOIN TBITEMS I ON I.idItem=B.iditemboni 
											WHERE B.idItem = ? AND B.cantidad <=? AND 0 < B.cantidad and B.IDUNIDADB=?
											ORDER BY  FACTOR DESC";
										//print_r($id);
										//print_r($cantidad);
										$paramsbonificacion = array(array($id),array($cantidad),array($unidad));
										$sqlbonificacion=sqlsrv_query($conexion,$querybonificacion,$paramsbonificacion);
										//$rowbonificacion=sqlsrv_fetch_array($sqlbonificacion);
								while ($rowbonificacion=sqlsrv_fetch_array($sqlbonificacion))
								{	
									if(file_exists($archivobonificacion)){

										$library = new SimpleXMLElement('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
										$session = $library->addChild('session', $rowbonificacion['itembonificador']);
										$session->addAttribute('itembonificador', $rowbonificacion['itembonificador']);
										$session->addChild('iditem',$rowbonificacion['idItem']);
										$session->addChild('codigo',$rowbonificacion['Codigo']);
										$session->addChild('descripcion',$rowbonificacion['Descripcion']);
										$session->addChild('cantidad',$rowbonificacion['CANTIDAD']);
										$session->addChild('precio_venta',$rowbonificacion['precioesp']);
										$session->addChild('itembonificador',$rowbonificacion['itembonificador']);
										$session->addChild('unidad',$rowbonificacion['UNIDAD']);
									    
										$sqlUNIDAD ="SELECT FactorConversion FROM TBUNIDADES WHERE IDUNIDAD = ? ";
										$paramsUNIDAD  = array($rowbonificacion['UNIDAD']);
										$recUNIDAD  = sqlsrv_query($conexion,$sqlUNIDAD,$paramsUNIDAD);
										$rowUNIDAD  = sqlsrv_fetch_array( $recUNIDAD, SQLSRV_FETCH_ASSOC) ;

										$library->asXML('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
										$xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');			

										$doc= simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
										$docEXTRA= simplexml_load_file('C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
										
										foreach($docEXTRA->session as $segEXTRA)
										{	
											$sqlUNIDADX ="SELECT FactorConversion FROM TBUNIDADES WHERE IDUNIDAD = ? ";
											$paramsUNIDADX  = array((float)$segEXTRA->unidad);
											$recUNIDADX  = sqlsrv_query($conexion,$sqlUNIDADX,$paramsUNIDADX);
											$rowUNIDADX = sqlsrv_fetch_array( $recUNIDADX, SQLSRV_FETCH_ASSOC) ;

											//and (int)$segEXTRA->iditem == $rowbonificacion['idItem']
										    	if((int)$segEXTRA->itembonificador == $rowbonificacion['itembonificador'] ) {
										    		$archivodev = 'C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
										    		if($segEXTRA->iditem==$rowbonificacion['idItem']){
											    		$idDEV=(int)$segEXTRA->iditem ;
					$cantidadDEV=((float)$segEXTRA->cantidad*$rowUNIDADX['FactorConversion'])-($rowbonificacion['CANTIDAD']*$rowUNIDAD['FactorConversion']);
												    	//$cantidadDEV=(float)$segEXTRA->cantidad-$rowbonificacion['CANTIDAD'];
												    	$precio_ventaDEV=0;//$rowbonificacion['precioesp'];
												    	$unidadDEV=1;//(int)$segEXTRA->unidad ; 

											    		if(file_exists($archivodev)){
															$libraryEXTRA = new SimpleXMLElement('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
														    $session = $libraryEXTRA->addChild('session', $idDEV);
														    $session->addAttribute('iditem', $idDEV);
															$session->addChild('iditem',$idDEV);
															$session->addChild('cantidad',$cantidadDEV);
															$session->addChild('precio_venta',$precio_ventaDEV);
															$session->addChild('unidad',$unidadDEV);
														    
														    $libraryEXTRA->asXML('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
														    $xml = simplexml_load_file('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
														}else {   //echo "El fichero $archivo no existe<br/>";
																$xml = new DomDocument('1.0', 'UTF-8'); 
															    $root = $xml->createElement('detalle'); 
															    $root = $xml->appendChild($root); 

																$session=$xml->createElement('session',$idDEV);
															    $session =$root->appendChild($session); 
																$session->setAttribute('iditem',$idDEV);

																$iditem=$xml->createElement('iditem',$idDEV); 
															    $iditem =$session->appendChild($iditem); 

															    $cant=$xml->createElement('cantidad',$cantidadDEV); 
															    $cant =$session->appendChild($cant); 
															     
															    $precio=$xml->createElement('precio_venta',$precio_ventaDEV); 
															    $precio=$session->appendChild($precio); 
															     
															    $und=$xml->createElement('unidad',$unidadDEV); 
															    $und=$session->appendChild($und); 
															     
															    $xml->formatOutput = true; 

															    $strings_xml = $xml->saveXML(); 

															    $xml->save('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml'); 

															    $xml = simplexml_load_file('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
															    $salida ="";				 
														}
										    		}else{
										    			$idDEV=(int)$segEXTRA->iditem ;
												    	$cantidadDEV=(float)$segEXTRA->cantidad;
												    	$precio_ventaDEV=(float)$segEXTRA->precio_venta;
												    	$unidadDEV=(int)$segEXTRA->unidad ; 

											    		if(file_exists($archivodev)){
															$libraryEXTRA = new SimpleXMLElement('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
														    $session = $libraryEXTRA->addChild('session', $idDEV);
														    $session->addAttribute('iditem', $idDEV);
															$session->addChild('iditem',$idDEV);
															$session->addChild('cantidad',$cantidadDEV);
															$session->addChild('precio_venta',$precio_ventaDEV);
															$session->addChild('unidad',$unidadDEV);
														    
														    $libraryEXTRA->asXML('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
														    $xml = simplexml_load_file('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
														}else {   //echo "El fichero $archivo no existe<br/>";
																$xml = new DomDocument('1.0', 'UTF-8'); 
															    $root = $xml->createElement('detalle'); 
															    $root = $xml->appendChild($root); 

																$session=$xml->createElement('session',$idDEV);
															    $session =$root->appendChild($session); 
																$session->setAttribute('iditem',$idDEV);

																$iditem=$xml->createElement('iditem',$idDEV); 
															    $iditem =$session->appendChild($iditem); 

															    $cant=$xml->createElement('cantidad',$cantidadDEV); 
															    $cant =$session->appendChild($cant); 
															     
															    $precio=$xml->createElement('precio_venta',$precio_ventaDEV); 
															    $precio=$session->appendChild($precio); 
															     
															    $und=$xml->createElement('unidad',$unidadDEV); 
															    $und=$session->appendChild($und); 
															     
															    $xml->formatOutput = true; 

															    $strings_xml = $xml->saveXML(); 

															    $xml->save('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml'); 

															    $xml = simplexml_load_file('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
															    $salida ="";				 
														}
										    		}
										    		
											        
											    }
										}    
									
									} else {
										$xml = new DomDocument('1.0', 'UTF-8'); 
									    $root = $xml->createElement('detalle'); 
									    $root = $xml->appendChild($root); 

										$session=$xml->createElement('session',$rowbonificacion['itembonificador']);
									    $session =$root->appendChild($session); 
										$session->setAttribute('itembonificador',$rowbonificacion['itembonificador']);

										$iditem=$xml->createElement('iditem',$rowbonificacion['idItem']); 
									    $iditem =$session->appendChild($iditem); 

									    $codigo=$xml->createElement('codigo',$rowbonificacion['Codigo']); 
									    $codigo =$session->appendChild($codigo); 

									    $descripcion=$xml->createElement('descripcion',$rowbonificacion['Descripcion']); 
									    $descripcion =$session->appendChild($descripcion); 

									    $cant=$xml->createElement('cantidad',$rowbonificacion['CANTIDAD']); 
									    $cant =$session->appendChild($cant); 

									    $precio=$xml->createElement('precio_venta',$rowbonificacion['precioesp']); 
							    		$precio=$session->appendChild($precio); 

							    		$itembonificador=$xml->createElement('itembonificador',$rowbonificacion['itembonificador']); 
							    		$itembonificador=$session->appendChild($itembonificador); 

							    		$IDUND=$xml->createElement('unidad',$rowbonificacion['UNIDAD']); 
							    		$IDUND=$session->appendChild($IDUND); 

									    $xml->formatOutput = true; 

									    $strings_xml = $xml->saveXML(); 

									    $xml->save('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml'); 
										    
									    $xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
									    $salida ="";
									}
									//////verificando bonificacion fin
								}
								//AQUI
									
								if(!$rowbonificacion=sqlsrv_fetch_array($sqlbonificacion)){
									$docEXTRA= simplexml_load_file('C:\bkp\bonificacionEXTRA'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
									foreach($docEXTRA->session as $segEXTRA)
										{ 	
											$sqlUNIDADX ="SELECT FactorConversion FROM TBUNIDADES WHERE IDUNIDAD = ? ";
											$paramsUNIDADX  = array((float)$segEXTRA->unidad);
											$recUNIDADX  = sqlsrv_query($conexion,$sqlUNIDADX,$paramsUNIDADX);
											$rowUNIDADX = sqlsrv_fetch_array( $recUNIDADX, SQLSRV_FETCH_ASSOC) ;

											$idDEV=(int)$segEXTRA->iditem ;
											$cantidadDEV=((float)$segEXTRA->cantidad*$rowUNIDADX['FactorConversion']) ;
											//$cantidadDEV=(float)$segEXTRA->cantidad-$rowbonificacion['CANTIDAD'];
											$precio_ventaDEV=0;//$rowbonificacion['precioesp'];
											$unidadDEV=1;

											$archivodev = 'C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
														if(file_exists($archivodev)){
															$libraryEXTRA = new SimpleXMLElement('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
														    $session = $libraryEXTRA->addChild('session', $idDEV);
														    $session->addAttribute('iditem', $idDEV);
															$session->addChild('iditem',$idDEV);
															$session->addChild('cantidad',$cantidadDEV);
															$session->addChild('precio_venta',$precio_ventaDEV);
															$session->addChild('unidad',$unidadDEV);
														    
														    $libraryEXTRA->asXML('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
														    $xml = simplexml_load_file('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
														}else {   //echo "El fichero $archivo no existe<br/>";
																$xml = new DomDocument('1.0', 'UTF-8'); 
															    $root = $xml->createElement('detalle'); 
															    $root = $xml->appendChild($root); 

																$session=$xml->createElement('session',$idDEV);
															    $session =$root->appendChild($session); 
																$session->setAttribute('iditem',$idDEV);

																$iditem=$xml->createElement('iditem',$idDEV); 
															    $iditem =$session->appendChild($iditem); 

															    $cant=$xml->createElement('cantidad',$cantidadDEV); 
															    $cant =$session->appendChild($cant); 
															     
															    $precio=$xml->createElement('precio_venta',$precio_ventaDEV); 
															    $precio=$session->appendChild($precio); 
															     
															    $und=$xml->createElement('unidad',$unidadDEV); 
															    $und=$session->appendChild($und); 
															     
															    $xml->formatOutput = true; 

															    $strings_xml = $xml->saveXML(); 

															    $xml->save('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml'); 

															    $xml = simplexml_load_file('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
															    $salida ="";				 
														}
										}
								}

					}
											
				} 
				if (isset($_GET['id']))
				{
					/*	$doc=new SimpleXMLElement('C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
						foreach($doc->session as $seg)
						{
						    if($seg['iditem'] == $_GET['id']) {
						        $dom=dom_import_simplexml($seg);
						        $dom->parentNode->removeChild($dom);
						    }
						}
						$doc->asXML('C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
						//eliminar bonificacion*/
					$archivobonificacionlectura = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
					if(file_exists($archivobonificacionlectura)){
						$docbonificacion=new SimpleXMLElement('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
						foreach($docbonificacion->session as $seg)
						{
						    if($seg['itembonificador'] == $_GET['id']) {
						        $dom=dom_import_simplexml($seg);
						        $dom->parentNode->removeChild($dom);
						    }
						}
						$docbonificacion->asXML('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');	
					}
				}
				?>
				<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' >
				<tr>
					<th>CODIGO</th>
					<th>CANT.</th>
					<th>DESCRIPCION</th>
					<th>UNIDAD</th>
					<th><span class="pull-right">PRECIO ESP.</span></th>
					<th><span class="pull-right">PRECIO TOTAL</span></th>
					<th></th>
				</tr>
				<?php
					$sumador_total=0;
					$archivobonificacionlectura = 'C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
					if(file_exists($archivobonificacionlectura)){
						$xml = simplexml_load_file('C:\bkp\bonificacion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
						foreach($xml->session as $item){ 
								$id_tmp=''. $item->iditem .'';
								$idboni=''. $item->itembonificador .'';
								$codigo_producto=''. $item->codigo .'';
								$cantidad=''. $item->cantidad .'';
								$nombre_producto=''. $item->descripcion .'';
								$id_UNIDAD=$item->unidad;
								if (!empty($id_UNIDAD))
								{
								$sql_unidad=sqlsrv_query($conexion, "select Descripcion from tbunidades where idUnidad='$id_UNIDAD'");
								$rw_unidad=sqlsrv_fetch_array($sql_unidad);
								$Uni=$rw_unidad['Descripcion'];
								$unidad=" ".strtoupper($Uni);
								}
								else {$unidad='';}
								$precio_venta=(double)''. $item->precio_venta .'';
								$precio_venta_f=number_format($precio_venta,2);//Formateo variables
								$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
								$precio_total=$precio_venta_r*$cantidad;
								$precio_total_f=number_format($precio_total,2);//Precio total formateado
								$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
								$sumador_total+=$precio_total_r;//Sumado
				?>
									<tr>
										<td><?php echo $codigo_producto;?></td>
										<td><?php echo $cantidad;?></td>
										<td><?php echo $nombre_producto;?></td>
										<td><?php echo $unidad;?></td>
										<td><span class="pull-right"><?php echo $precio_venta_f;?></span></td>
										<td><span class="pull-right"><?php echo $precio_total_f;?></span></td>
										<td >
											<button type="button" class="buttondetallepedido" onclick="eliminarbonificacionx( '<?php echo $idboni?>') ">
												<span class="glyphicon glyphicon-trash"></span>
											</button>
										</td>
										<td style='display:none'><span class="pull-right"><a href="#" onclick="eliminarbonificacionx( '<?php echo $idboni?>') "><i class="glyphicon glyphicon-trash"></i></a></span></td>
									</tr>		
<?php
							//}
						}
					}
		}
	}
?>		
		<tr>
			<td colspan=5><span class="pull-right">TOTAL</span></td>
			<td><span class="pull-right"><?php echo number_format($sumador_total,2);?></span></td>
			<td></td>
		</tr>
		</table></div></div>
		
		
