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
		$dir= (isset($_REQUEST['dir'])&& $_REQUEST['dir'] !=NULL)?$_REQUEST['dir']:'';
		include 'pagination.php'; 
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; 
		$adjacents  = 4; 
		$offset = ($page - 1) * $per_page;
		
		$params = array($q,$dir);
		$count_query=sqlsrv_query($conexion,"SELECT count(*) AS numrows FROM tbclieprov 
											 WHERE tipoClieProv='C'and (Descripcion LIKE '%'+?+'%' and Direccion like '%'+?+'%' )",$params);
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
				$sql ="SELECT top 10 * FROM tbclieprov WHERE tipoClieProv='C' and (Descripcion LIKE '%'+?+'%' and Direccion like '%'+?+'%' )" ;
				$params = array($q,$dir);
				$query = sqlsrv_query($conexion,$sql,$params);
				if ($numrows>0){
					?>
					<div class="table-responsive">
					  <table class="table">
						<tr  class="warning">
							<th>RUC</th>
							<th>Raz.Social</th>
							<th>Dirección</th>
							<th style="width: 36px;"></th>
						</tr>
						<?php
						while ($row=sqlsrv_fetch_array($query)){
							$RUC=$row['RUC'];
							$Descripcion=$row['Descripcion'];
							$Direccion=$row['Direccion'];
							?>
							<tr>
								<td ><?php echo $RUC; ?></td>
								<td ><?php echo $Descripcion; ?></td>
								<td ><?php echo $Direccion; ?></td>
								<td class="pull-right" data-dismiss="modal">
									<button type="button" class="buttondetallepedido" onclick="agregarcliente('<?php echo $RUC ?>','<?php echo $Descripcion ?>','<?php echo $Direccion ?>')">
										<span class="glyphicon glyphicon-ok"></span>
									</button>
								</td>
								<td style='display:none'><span class="pull-right" data-dismiss="modal"><a href="#" 
									onclick="agregarcliente('<?php echo $RUC ?>','<?php echo $Descripcion ?>','<?php echo $Direccion ?>')">
								<i class="glyphicon glyphicon-ok"></i></a></span></td>
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