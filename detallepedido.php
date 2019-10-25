<?php
	require_once("conexion.php");
	session_start();
	$basedatos =@$_SESSION['basedatos'];
	$conexion=Conexion($basedatos);
	if(!@$conexion){
	echo "No hay conexion";
	}	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){
		$q = (isset($_REQUEST['q'])&& $_REQUEST['q'] !=NULL)?$_REQUEST['q']:'';
		include 'pagination.php'; 
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; 
		$adjacents  = 4; 
		$offset = ($page - 1) * $per_page;
		$params = array($q); 
		$count_query=sqlsrv_query($conexion,"SELECT count(*) AS numrows FROM tbdetcomercial WHERE iddocumento=? ",$params); 
		if (!$count_query) {
				echo 'No se pudo ejecutar la consulta: ' ;
				if( ($errors = sqlsrv_errors() ) != null) {
				foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
				}
			}
		}
				$row= sqlsrv_fetch_array($count_query);
				$numrows = $row['numrows']; 
		 		
				$total_pages = ceil($numrows/$per_page);
				$reload = 'BusquedaPedido.php'; 
				
				$sqldetalle = "SELECT TBITEMS.Codigo as CODIGO,TBDETCOMERCIAL.idDetComercial as idDetComercial,TBDETCOMERCIAL.Descripcion as Descripcion,TBDETCOMERCIAL.Cantidad as Cantidad, TBDETCOMERCIAL.Precio as PRECIO,TBITEMS.idItem as IDITEM,
					TBUNIDADES.DESCRIPCION as UNIDAD,TBITEMS.CodigoDistribuidor as CODIGODIS
						FROM TBDETCOMERCIAL	
						INNER JOIN TBITEMS ON TBITEMS.idItem=TBDETCOMERCIAL.idItem
						INNER JOIN TBUNIDADES ON TBUNIDADES.idUnidad=TBDETCOMERCIAL.idUnidad
						WHERE  idDocumento= ?" ;
				$paramsdetalle = array($q);
				$querydetalle = sqlsrv_query($conexion,$sqldetalle,$paramsdetalle); 
 
				if ($numrows>0){
					?>
					<div class="table-responsive">
					  <table class="table">
					  	<tr  class="warning">
							<th>Productos Devueltos:</th> 
							<th></th>
							<th></th>
							<th><span>.</span></th>
							<th><span class="pull-right"></span></th>
							<th><span class="pull-right"></span></th>
							<th style="width: 36px;"></th> 
						</tr> 
						<tr  class="warning">
							<th>Código</th>
							<th>Producto</th>
							<th>Unidad</th>
							<th><span  >Cant.</span></th>
							<th><span class="pull-right">Precio</span></th>
							<th><span class="pull-right">Total</span></th>
							<th style="width: 36px;"></th>
						</tr>
						<?php
						$sumador_total=0;
						while ($row=sqlsrv_fetch_array($querydetalle)){
							$id_producto=$row['IDITEM'];
							$codigo_producto=$row['CODIGO'];
							$codigo_DIS=$row['CODIGODIS'];
							$nombre_producto=$row['Descripcion']; 
							$nombre_unidad=$row['UNIDAD']; 
							$Precio=$row['PRECIO'];
							$cantidad_venta=$row['Cantidad'];
							
							$precio_venta=$Precio;
							$precio_venta_f=number_format($precio_venta,2);//Formateo variables
							$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
							$precio_total=$precio_venta_r*$cantidad_venta;
							$precio_total_f=number_format($precio_total,2);//Precio total formateado
							$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
							$sumador_total+=$precio_total_r;//Sumado
							?> 
							<tr>
								<td ><?php echo $codigo_producto." || ".$codigo_DIS; ?></td>
								<td ><?php echo $nombre_producto; ?></td>
								<td ><?php echo $nombre_unidad; ?></td>
								<td ><?php echo $cantidad_venta; ?></td>
								<td ><span class="pull-right"><?php echo $precio_venta_r; ?></span></td>
								<td ><span class="pull-right"><?php echo $precio_total_f; ?></span></td> 
							</tr>
							<?php
						} /*
								$sql = "SELECT * FROM tbdocumentos WHERE  idDocumento= ?" ;
								$params = array($q);
								$query = sqlsrv_query($conexion,$sql,$params); 
								$rowpedido=sqlsrv_fetch_array($query); 
								$pedido=$rowpedido['NumeDocu'];

								$sqlidboni = "SELECT * FROM tbdocumentos WHERE  numedocureferencia= ?" ;
								$paramsidboni = array($pedido);
								$queryidboni = sqlsrv_query($conexion,$sqlidboni,$paramsidboni); 
								$rowidboni=sqlsrv_fetch_array($queryidboni); 
								$idbonificacion=$rowidboni['idDocumento'];

						if ($idbonificacion>0) {
								//echo"<script type=\"text/javascript\">alert(".$idbonificacion.");</script>";
								   
								$sqldetalleboni = "SELECT TBITEMS.Codigo as CODIGOB,TBDETCOMERCIAL.idDetComercial as idDetComercialB,TBDETCOMERCIAL.Descripcion as DescripcionB,TBDETCOMERCIAL.Cantidad as CantidadB, TBDETCOMERCIAL.Precio as PRECIOB,TBITEMS.idItem as IDITEMB,
									TBUNIDADES.DESCRIPCION as UNIDADB,TBITEMS.CodigoDistribuidor as CODIGODISB
										FROM TBDETCOMERCIAL	
										INNER JOIN TBITEMS ON TBITEMS.idItem=TBDETCOMERCIAL.idItem
										INNER JOIN TBUNIDADES ON TBUNIDADES.idUnidad=TBDETCOMERCIAL.idUnidad
										WHERE  idDocumento= ?" ;
								$paramsdetalleboni = array($idbonificacion);
								$querydetalleboni = sqlsrv_query($conexion,$sqldetalleboni,$paramsdetalleboni); 
								?>
								<tr  class="warning">
									<th>Bonificación:</th> 
									<th></th>
									<th></th>
									<th><span>.</span></th>
									<th><span class="pull-right"></span></th>
									<th><span class="pull-right"></span></th>
									<th style="width: 36px;"></th> 
								</tr> 
								<?php
								$sumador_total2=0;
								while ($row=sqlsrv_fetch_array($querydetalleboni)){
									$id_producto=$row['IDITEMB'];
									$codigo_producto=$row['CODIGOB'];
									$codigo_DIS=$row['CODIGODISB'];
									$nombre_producto=$row['DescripcionB']; 
									$nombre_unidad=$row['UNIDADB']; 
									$Precio=$row['PRECIOB'];
									$cantidad_venta=$row['CantidadB'];
									
									$precio_venta=$Precio;
									$precio_venta_f=number_format($precio_venta,2);//Formateo variables
									$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
									$precio_total=$precio_venta_r*$cantidad_venta;
									$precio_total_f=number_format($precio_total,2);//Precio total formateado
									$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
									$sumador_total2+=$precio_total_r;//Sumado
								?> 
								<tr>
									<td ><?php echo $codigo_producto." || ".$codigo_DIS; ?></td>
									<td ><?php echo $nombre_producto; ?></td>
									<td ><?php echo $nombre_unidad; ?></td>
									<td ><?php echo $cantidad_venta; ?></td>
									<td ><span class="pull-right"><?php echo $precio_venta_r; ?></span></td>
									<td ><span class="pull-right"><?php echo $precio_total_f; ?></span></td> 
								</tr>
								<?php
								}
							} */
						?>

						<tr>
							<th colspan=5><span class="pull-right">TOTAL</span></th>
							<td><span class="pull-right"><?php echo number_format($sumador_total,2);?></span></td>
							<td></td>
						</tr>
					  </table>					  
					</div>
					<?php 
				}
				
			}
?>