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
	}else
		{
		$row = sqlsrv_fetch_array( $rec, SQLSRV_FETCH_ASSOC);
				
		?>
		<div class='panel panel-primary'><div class='table-responsive'><table class='table table-hover' style='width:100%' >
		<tr>
			<th>CODIGO</th>
			<th>CANT.</th>
			<th>DESCRIPCION</th>
			<th>UNIDAD</th>
			<th><span class="pull-right">PRECIO UNIT.</span></th>
			<th><span class="pull-right">PRECIO TOTAL</span></th>
			<th></th><th></th>
		</tr>
		<?php
			$sumador_total=0;
			$archivo = 'C:\bkp\pedido'.substr($basedatos, 0).substr($row['idUsuario'], 0).'.xml';
			if(file_exists($archivo)){
				
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
	}
?>
<tr>
	<td colspan=5><span class="pull-right">TOTAL</span></td>
	<td><span class="pull-right"><?php echo number_format($sumador_total,2);?></span></td>
	<td></td>
</tr>
</table></div></div>

