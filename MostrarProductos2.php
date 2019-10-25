
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
		$params = array($q,$q);
		$count_query=sqlsrv_query($conexion,"SELECT count(*) AS numrows FROM tbitems WHERE Codigo LIKE '%' + ? + '%' or Descripcion LIKE '%'+?+'%'",$params);
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
				$reload = 'NuevoPedido2.php';
/*
				$sql ="SELECT top 20 I.idItem AS IDITEM,I.Codigo AS CODIGO,I.Descripcion AS DESCRIPCION
					,D.Precio AS PRECIO,D.FACTOR AS FACTOR,D.idunidad as UNIDAD 
						FROM TBITEMS I INNER JOIN TBDETPRECIO D ON D.iditem=I.iditem
						WHERE I.Descripcion LIKE '%' + ? + '%'";	
*/
				$sql ="SELECT top 10 *	FROM TBITEMS WHERE Codigo LIKE '%' + ? + '%' or Descripcion LIKE '%' + ? + '%'  " ;
				$params = array($q,$q);
				$query = sqlsrv_query($conexion,$sql,$params);
				if ($numrows>0){
					?>
					<div class="table-responsive">
					  <table class="table">
						<tr  class="warning">
							<th>Código</th>
							<th>Producto</th>
							<th>Unidad</th>
							<th><span class="pull-right">Cant.</span></th>
							<th style="width: 36px;"></th>
						</tr>
						<?php
						while ($row=sqlsrv_fetch_array($query)){
							$id_producto=$row['idItem'];
							$codigo_producto=$row['Codigo'];
							$nombre_producto=$row['Descripcion'];
							$id_marca_producto=$row['idUnidad'];
							//$FACTOR=$row['FACTOR'];
							$sql_marca=sqlsrv_query($conexion, "select * from tbunidades where idUnidad='$id_marca_producto'");
							$rw_marca=sqlsrv_fetch_array($sql_marca);
							$nombre_marca=$rw_marca['Descripcion'];
							$precio_venta=$row["PrecioVenta"];
							$precio_venta=number_format($precio_venta, 2, '.', '');
							?>
							
							<div id="precioo" class='col-md-12'></div>
							<div id="CUCU" class='col-md-12'></div>
							<tr>
								<td class='col-xs-1'><?php echo $codigo_producto; ?></td>
								<td ><?php echo $nombre_producto; ?></td>
								<td class='col-xs-3'>
								<select class="form-control" data-width="auto" id="unidad_<?php echo $id_producto; ?>" onchange="valueselect()">
							  			
									<?php 
										$sqlunidades ="SELECT * FROM tbunidades";
										//$paramsunidades = array('');
										$recunidades = sqlsrv_query($conexion,$sqlunidades);
										while ($rowunidades=sqlsrv_fetch_array($recunidades)){
											$idUnidad=$rowunidades['idUnidad'];
											$unidad=$rowunidades['Descripcion'];
									?>
										<option value="<?php echo $idUnidad;?>" ><?php echo $unidad;?></option>
									<?php /*<td ><?php echo $nombre_marca; ?></td>*/
										}
									?>
								</select>
								</td>
								<td class='col-xs-2'>
								<div class="pull-right">
								<input type="number" class="form-control" min="0" required="required" onClick="this.select()" style="text-align:right" id="cantidad_<?php echo $id_producto; ?>" value="1" >
								</div></td>
								<td><button type='submit' class='buttontabla' id='NUMERAL' onclick="agregar('<?php echo $id_producto ?>')" ><span class="glyphicon glyphicon-plus"></span></button></td>
								
							</tr>
							<?php
						}
						?>
					  </table>					  
					</div>
					<?php
				}
				
			}
?>