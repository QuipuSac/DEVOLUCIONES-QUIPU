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
	/* 
		variables
		iditem, codigo,descripcion,precio,idunidad
  	*/
		//TBITEMS-TBALMACENES
		if (!empty($_POST['codigo']) and !empty($_POST['descripcion']) and !empty($_POST['idunidad']) and !empty($_POST['precio'])){
			$idItem =0;
			$Codigo  = $_POST['codigo'];
			$CogigoDistribuidor  = '';
			$CodigoMarca= '-';
			$Descripcion = $_POST['descripcion'];
			$idTipoItem  = 1;
			$idLinea = 1;
			$idMarca = 1;
			$idUnidad = $_POST['idunidad'];
			$Modelo = '-';
			$Serie = '';
			$depreciacion = 'N';
			$porcentajeDepreciacion = 0.0;
			date_default_timezone_set("America/Phoenix"); 
			$FechaAdquisicion = date("d") . "/" . date("m") . "/" . date("Y");
			$FechaInicio  = date("d") . "/" . date("m") . "/" . date("Y");
			$ControlStock = 'S';
			$StockMinimo = 0;
			$StockMaximo = 0;
			$StockSeguridad  = 0;
			$PrecioVenta  = $_POST['precio'];
			$PrecioVentaMinimo  = 0;
			$Moneda='S';
			$Afecto ='S';
			$idcuentaVenta =35530;
			$idcuentaCompra =35151;
			$idcuentaVariacion =35195;
			$idcuentaCosto =35472;
			$idAlmacen =34257;
			$ESGENERICO ='N';
			$ESCOMPRAVENTA ='S';
			$ADICIONAL ='-';
					
			$sqlitems ="SELECT * FROM tbitems WHERE iditem = ?  ";
			$paramsitems = array($_POST['iditem']);				
			$recitems = sqlsrv_query($conexion,$sqlitems,$paramsitems);
			$filaitems = sqlsrv_fetch_array( $recitems, SQLSRV_FETCH_ASSOC);
			$iditem2=$filaitems['idItem'];

			
			if(!@$iditem2)
			{	
				$sqlitems2 ="SELECT * FROM tbitems WHERE codigo = ?  ";
				$paramsitems2 = array($_POST['codigo']);				
				$recitems2 = sqlsrv_query($conexion,$sqlitems2,$paramsitems2);
				$filaitems2 = sqlsrv_fetch_array( $recitems2, SQLSRV_FETCH_ASSOC);
				$codigo2=$filaitems2['Codigo'];
				if(!@$codigo2)
				{
					$sqlSPI_ITEMS_WEB = "[SPI_ITEMS_WEB] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
					$paramsSPI_ITEMS_WEB = array(   
				            array($idItem, SQLSRV_PARAM_INOUT),array($Codigo, SQLSRV_PARAM_IN),
				            array($CogigoDistribuidor, SQLSRV_PARAM_IN),array($CodigoMarca, SQLSRV_PARAM_IN),
				            array($Descripcion, SQLSRV_PARAM_IN),array($idTipoItem, SQLSRV_PARAM_IN),
				            array($idLinea, SQLSRV_PARAM_IN),array($idMarca, SQLSRV_PARAM_IN),
				            array($idUnidad, SQLSRV_PARAM_IN),array($Modelo, SQLSRV_PARAM_IN),
				            array($Serie, SQLSRV_PARAM_IN),array($depreciacion, SQLSRV_PARAM_IN),
				            array($porcentajeDepreciacion, SQLSRV_PARAM_IN),array($FechaAdquisicion, SQLSRV_PARAM_IN),
				            array($FechaInicio, SQLSRV_PARAM_IN),array($ControlStock, SQLSRV_PARAM_IN),
				            array($StockMinimo, SQLSRV_PARAM_IN),array($StockMaximo, SQLSRV_PARAM_IN),
				            array($StockSeguridad, SQLSRV_PARAM_IN),array($PrecioVenta, SQLSRV_PARAM_IN),
				            array($PrecioVentaMinimo, SQLSRV_PARAM_IN),array($Moneda, SQLSRV_PARAM_IN),
				            array($Afecto, SQLSRV_PARAM_IN),array($idcuentaVenta, SQLSRV_PARAM_IN),
				            array($idcuentaCompra, SQLSRV_PARAM_IN),array($idcuentaVariacion, SQLSRV_PARAM_IN),
				            array($idcuentaCosto, SQLSRV_PARAM_IN),array($idAlmacen, SQLSRV_PARAM_IN),
				            array($ESGENERICO, SQLSRV_PARAM_IN),array($ESCOMPRAVENTA, SQLSRV_PARAM_IN),
				            array($ADICIONAL, SQLSRV_PARAM_IN) 
				              );  
					$stmtSPI_ITEMS_WEB = sqlsrv_query($conexion,$sqlSPI_ITEMS_WEB,$paramsSPI_ITEMS_WEB);
					sqlsrv_next_result($stmtSPI_ITEMS_WEB); 

					$sqldetprecio ="SELECT top 1 * FROM tbdetprecio order by 1 desc";
					$recdetprecio = sqlsrv_query($conexion,$sqldetprecio);
					$filaitems2 = sqlsrv_fetch_array( $recdetprecio, SQLSRV_FETCH_ASSOC);
					$idDetPrecio=$filaitems2['idDetPrecio'];
					$idDetPreciofinal=$idDetPrecio+1;

					$sqldetprecio2="INSERT INTO TBDETPRECIO (idDetPrecio,idItem,IdUnidad,Precio,Factor,idUnidadAnalisis,FInicio,FFin,Indicador) 
									VALUES (?,?,?,?,?,?,?,?,?)";
					$paramsdetprecio2= array(
							array($idDetPreciofinal, SQLSRV_PARAM_IN),array($idItem, SQLSRV_PARAM_IN),
							array($idUnidad, SQLSRV_PARAM_IN),array($PrecioVenta, SQLSRV_PARAM_IN),
							array(1, SQLSRV_PARAM_IN),array($idUnidad, SQLSRV_PARAM_IN),
							array($FechaInicio, SQLSRV_PARAM_IN),array($FechaInicio, SQLSRV_PARAM_IN),
							array('-', SQLSRV_PARAM_IN)
							);
					$stmtdetprecio2=sqlsrv_query($conexion,$sqldetprecio2,$paramsdetprecio2);
					sqlsrv_next_result($stmtdetprecio2); 
						//echo "idClieProv de salida es: " . $idClieProv . "<br/>";					
							
					echo"<script type=\"text/javascript\">alert('Producto registrado.');</script>";
				}
				else{
					echo"<script type=\"text/javascript\">alert('Código duplicado: ".$codigo2."');</script>";
				}
			}
			else{
				//para modificar item :D
				$sqlitems ="SELECT * FROM tbitems WHERE iditem = ?  ";
				$paramsitems = array($_POST['iditem']);				
				$recitems = sqlsrv_query($conexion,$sqlitems,$paramsitems);
				$filaitems = sqlsrv_fetch_array( $recitems, SQLSRV_FETCH_ASSOC);
				$iditem3=$filaitems['idItem'];
				//
				$sqlSPU_ITEMS_WEB = "[SPU_ITEMS_WEB] ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
				$paramsSPU_ITEMS_WEB = array(   
				        array($_POST['iditem'], SQLSRV_PARAM_IN),array($Codigo, SQLSRV_PARAM_IN),
				        array($CogigoDistribuidor, SQLSRV_PARAM_IN),array($CodigoMarca, SQLSRV_PARAM_IN),
				        array($Descripcion, SQLSRV_PARAM_IN),array($idTipoItem, SQLSRV_PARAM_IN),
				    	array($idLinea, SQLSRV_PARAM_IN),array($idMarca, SQLSRV_PARAM_IN),
				        array($idUnidad, SQLSRV_PARAM_IN),array($Modelo, SQLSRV_PARAM_IN),
				        array($Serie, SQLSRV_PARAM_IN),array($depreciacion, SQLSRV_PARAM_IN),
				        array($porcentajeDepreciacion, SQLSRV_PARAM_IN),array($FechaAdquisicion, SQLSRV_PARAM_IN),
				        array($FechaInicio, SQLSRV_PARAM_IN),array($ControlStock, SQLSRV_PARAM_IN),
				        array($StockMinimo, SQLSRV_PARAM_IN),array($StockMaximo, SQLSRV_PARAM_IN),
				        array($StockSeguridad, SQLSRV_PARAM_IN),array($PrecioVenta, SQLSRV_PARAM_IN),
				        array($PrecioVentaMinimo, SQLSRV_PARAM_IN),array($Moneda, SQLSRV_PARAM_IN),
				        array($Afecto, SQLSRV_PARAM_IN),array($idcuentaVenta, SQLSRV_PARAM_IN),
				        array($idcuentaCompra, SQLSRV_PARAM_IN),array($idcuentaVariacion, SQLSRV_PARAM_IN),
				        array($idcuentaCosto, SQLSRV_PARAM_IN),array($idAlmacen, SQLSRV_PARAM_IN),
				        array($ESGENERICO, SQLSRV_PARAM_IN),array($ESCOMPRAVENTA, SQLSRV_PARAM_IN),
				        array($ADICIONAL, SQLSRV_PARAM_IN) 
				        );  
				$stmtSPU_ITEMS_WEB = sqlsrv_query($conexion,$sqlSPU_ITEMS_WEB,$paramsSPU_ITEMS_WEB);
				sqlsrv_next_result($stmtSPU_ITEMS_WEB); 
				//
				$sqlupdetprecio2="UPDATE TBDETPRECIO SET Precio  = ? , idUnidad = ? , idUnidadAnalisis=? where idItem=? and Factor=1";
				$paramsupdetprecio2= array(
						array($PrecioVenta, SQLSRV_PARAM_IN),array($idUnidad, SQLSRV_PARAM_IN),
						array($idUnidad, SQLSRV_PARAM_IN),array($iditem3, SQLSRV_PARAM_IN)
						);
				$stmtupdetprecio2=sqlsrv_query($conexion,$sqlupdetprecio2,$paramsupdetprecio2);
				sqlsrv_next_result($stmtupdetprecio2); 
				//
				echo"<script type=\"text/javascript\">alert('Producto modificado: ".$filaitems['Descripcion']."');</script>";
			}
		}else{
			echo"<script type=\"text/javascript\">alert('Campos incompletos.');</script>";
		}
	}
?>