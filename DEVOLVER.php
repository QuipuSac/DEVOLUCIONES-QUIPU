<?php
	$session_id= session_id();
	require_once("conexion.php");
	session_start();
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	$usuario = @$_SESSION['usuario'];
	$pass = @$_SESSION['pass'];
	$VARIABLE=$_SESSION['BONIWEB'];
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
				
				if (!empty($id)  and !empty($precio_venta) and !empty($unidad))
				{	
						$doc=new SimpleXMLElement('C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
						foreach($doc->session as $seg)
						{
						    if($seg['iditem'] == $id) {
						        $dom=dom_import_simplexml($seg);
						        $dom->parentNode->removeChild($dom);
						    }
						}
						$doc->asXML('C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');


						$archivodev = 'C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
							    		$idDEV=$id;
								    	$cantidadDEV= $cantidad;
								    	$precio_ventaDEV=$precio_venta;
								    	$unidadDEV=$unidad; 

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
				if ($VARIABLE=='S') {
					//VARIABLES 
					$querybonificacion ="SELECT TOP 1 B.iditemboni AS idItem,I.Codigo AS Codigo,B.DESCRIPCION AS Descripcion,
									B.CANTBONI AS CANTIDAD,B.PRECIO AS precioesp,B.iditem as itembonificador,B.CANTIDAD AS FACTOR
									,B.IDUNIDADBONI AS UNIDAD
									FROM tbbonificaciones B INNER JOIN TBITEMS I ON I.idItem=B.iditemboni 
									WHERE B.idItem = ? AND B.cantidad <=? AND 0 < B.cantidad and B.IDUNIDADB=?
									ORDER BY  FACTOR DESC";
					$paramsbonificacion = array(array($idDEV),array($cantidadDEV),array($unidadDEV));
					$sqlbonificacion=sqlsrv_query($conexion,$querybonificacion,$paramsbonificacion);
					while ($rowbonificacion=sqlsrv_fetch_array($sqlbonificacion)){
							$idB=$rowbonificacion['idItem'];
							$cantidadB=$rowbonificacion['CANTIDAD'];
							$precio_ventaB=$rowbonificacion['precioesp'];
							$unidadB=$rowbonificacion['UNIDAD'];
						if(file_exists($archivodev)){
							$libraryEXTRA = new SimpleXMLElement('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml', null, true);
							$session = $libraryEXTRA->addChild('session', $idDEV);
							$session->addAttribute('iditem', $idB);
							$session->addChild('iditem',$idB);
							$session->addChild('cantidad',$cantidadB);
							$session->addChild('precio_venta',$precio_ventaB);
							$session->addChild('unidad',$unidadB);
									    
							$libraryEXTRA->asXML('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
							$xml = simplexml_load_file('C:\bkp\devolucion'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
						}
					}
						
				}
				?>
				<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' >
				<tr>
					<th>CODIGO </th>
					<th>CANT.</th>
					<th>DESCRIPCION</th>
					<th>UNIDAD</th>
					<th><span class="pull-right">PRECIO UNIT.</span></th>
					<th><span class="pull-right">PRECIO TOTAL</span></th>
					<th></th>
					<th></th>
				</tr>
				<?php
					$sumador_total=0;
					
						$xml = simplexml_load_file('C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml');
						foreach($xml->session as $item){
						$params = array();
						$sql=sqlsrv_query($conexion,"SELECT * FROM tbitems WHERE idItem='". $item->iditem ."'" );
							while ($row=sqlsrv_fetch_array($sql))
							{
								$id_tmp=$row['idItem'];
								$codigo_producto=$row['Codigo'];
								$cantidad=(double)$item->cantidad;
								$nombre_producto=$row['Descripcion'];
								$id_UNIDAD=$item->unidad;
								if (!empty($id_UNIDAD))
								{
								$sql_unidad=sqlsrv_query($conexion, "select Descripcion from tbunidades where idUnidad='$id_UNIDAD'");
								$rw_unidad=sqlsrv_fetch_array($sql_unidad);
								$Uni=$rw_unidad['Descripcion'];
								$unidad=" ".strtoupper($Uni);
								}
								else {$unidad='';}
								$precio_venta=(double)$item->precio_venta;
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
											<button type="button" class="buttondetallepedido" onclick="eliminar( '<?php echo $id_tmp?>'),eliminarbonificacion('<?php echo $id_tmp?>'),editar('<?php echo $id_tmp?>','<?php echo $codigo_producto?>','<?php echo $id_UNIDAD?>','<?php echo $cantidad?>','<?php echo $precio_venta_f?>')">
												<span class="glyphicon glyphicon-arrow-up"></span>
											</button>
										</td>
										<td >
											<button type="button" class="buttondetallepedido" onclick="eliminar2('<?php echo $id_tmp?>','<?php echo $precio_venta_f?>','<?php echo $cantidad?>','<?php echo $id_UNIDAD?>'),eliminarbonificacion('<?php echo $id_tmp?>')">
												<span class="glyphicon glyphicon-trash"></span>
											</button>
										</td>
									</tr>		
<?php
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