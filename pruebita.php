<?php
header('Content-type: application/json; charset=utf-8');

$repuestatm = array('estado' => true, 'respuesta' => $_POST['datos_post'], 'respuesta1' => $_POST['datos']);


$repuesta = array();

$sumador_total = 0;
for ($i = 0; $i < count($_POST['datos']); $i++) {
    $elemento  = $_POST['datos'][$i];
    $id_tmp = 0;

    $codigo_producto =  $elemento[0];
    $cantidad = (float) $elemento[1];
    $nombre_producto = $elemento[2];
    $idUnidad = (int) $elemento[3];
    $precio_venta = (float) $elemento[4];
    $precio_venta_f = number_format($precio_venta, 2, '.', ''); //Formateo variables
    $precio_venta_r = str_replace(",", "", $precio_venta_f); //Reemplazo las comas
    $precio_total = $precio_venta_r * $cantidad;
    $precio_total_f = number_format($precio_total, 2, '.', ''); //Precio total formateado
    $precio_total_r = str_replace(",", "", $precio_total_f); //Reemplazo las comas
    $sumador_total += $precio_total_r; //Sumador
    /////////////
    $BaseI = number_format(($precio_venta_f * 100) / 118, 2, '.', '');;
    $IGV = number_format($precio_total_f - ($BaseI * $cantidad), 2, '.', '');
    $BaseIME = 0;
    $PrecioME = 0;
    $Cantidad = 0;
    $PorcentDscto = 0;
    $Descuento = 0;
    $DescuentoME = 0;
    $IGVME = 0;
    $TotalME = 0;

    $Afecto = 'S';
    $idProyecto = 0;
    $idAlmacen = 1;
    $req = 'N';
    $DESLOTE = '-';
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
    $stmtDETCOMERCIAL_PEDIDOS = sqlsrv_query($conexion, $sqlDETCOMERCIAL_PEDIDOS, $paramsDETCOMERCIAL_PEDIDOS);
    sqlsrv_next_result($stmtDETCOMERCIAL_PEDIDOS);
}

echo json_encode($repuesta);
