<?php
require_once("conexion.php");

session_start();
$basedatos = @$_SESSION['basedatos'];
$conexion = Conexion($basedatos);
$usuario = @$_SESSION['usuario'];
$pass = @$_SESSION['pass'];

if (!@$conexion) {

    echo "No hay conexion";
} else {

    if ($_POST['accion'] == 'Guardar Devolucion') {

        $consolidado = $_POST['datos_post'][3];
        /* variables de uso */

        header('Content-type: application/json; charset=utf-8');

        $sql = "SELECT * FROM tbusuarios	WHERE cuenta = ? ";
        $params = array($usuario);
        $rec = sqlsrv_query($conexion, $sql, $params);
        $fila = sqlsrv_fetch_array($rec, SQLSRV_FETCH_ASSOC);
        $idUsuario = $fila['idUsuario'];
        $vendedor = $fila['Nombres'] . " " . $fila['Apellidos'];

        $sqlvendedor = "SELECT *	FROM tbclieprov	WHERE Nombres=? AND  Apellidos=?  ";
        $paramsvendedor = array($fila['Nombres'], $fila['Apellidos']);
        $recvendedor = sqlsrv_query($conexion, $sqlvendedor, $paramsvendedor);
        $filavendedor = sqlsrv_fetch_array($recvendedor, SQLSRV_FETCH_ASSOC);
        $idUsuario2 = $filavendedor['idClieProv'];


        $sumador_total = $_POST['datos_post'][6];

        $idtitdiario = 0;
        $iddocumentoanterior = $_POST['datos_post'][1];
        $iddocumento = 0;
        $correlativo = 0;
        $GLOSA = "DEVOLUCION";
        $idDIARIO = 89;
        $idtipodocu = 236;
        $seridocu = "001";
        $numedocu = "";
        $TIPOPAGO = 0;
        $moneda = "S";
        $idtica = 1;
        $total = number_format($sumador_total, 2, '.', '');
        $basei = number_format(($total * 100) / 118, 2, '.', '');
        $igv = number_format($total - $basei, 2, '.', '');
        $inafecto = number_format(0, 2, '.', '');

        $idtitdiario = 0;
        $TC = 1;
        $totalME = 0;
        $idoperacion = "OVG";
        $detraccion = "N";
        $idtipodocreferencia = 215;
        $seridocref = "001";
        $ConsolidadoRefe = $_POST['datos_post'][3];
        $DIRECCIONCLIEPROV = $_POST['datos_post'][2];
        $idvendedor = $idUsuario2;
        $otros = 0;
        $TDET = 0;
        $IDET = 0;

        date_default_timezone_set("America/Phoenix");
        $FechaEmision = date("d") . "/" . date("m") . "/" . date("Y");
        $Periodo = date("Y");
        $Mes = date("m");

        $idOperacion = "I";
        $tabla = "TBDOCUMENTOS";

        $sqlCORRELATIVO = "SELECT top 1 (correlativo) as correlativo	FROM tbtitdiario 
        WHERE PERIODO = YEAR(GETDATE()) AND MES =MONTH(GETDATE()) AND IDDIARIO =89 order by 1 desc";
        //
        $queryCORRELATIVO = sqlsrv_query($conexion, $sqlCORRELATIVO);
        //
        /* varriable del corelaribo tbtitdiario = $correlativo */
        //
        if ($corr = sqlsrv_fetch_array($queryCORRELATIVO, SQLSRV_FETCH_ASSOC)) {
            $correlativo = $corr['correlativo'] + 1;
        } else {
            $correlativo = 1;
        }
        $sqlvouchercontable = "SPI_VOUCHERCONTABLE_PHP ?,?,?,?,?,?";
        $paramsvouchercontable = array(
            array($idtitdiario, SQLSRV_PARAM_INOUT),
            array($GLOSA, SQLSRV_PARAM_IN),
            array($idDIARIO, SQLSRV_PARAM_IN),
            array($correlativo, SQLSRV_PARAM_INOUT),
            array($Periodo, SQLSRV_PARAM_IN),
            array($Mes, SQLSRV_PARAM_IN)
        );

        $stmtvouchercontable = sqlsrv_query($conexion, $sqlvouchercontable, $paramsvouchercontable);
        sqlsrv_next_result($stmtvouchercontable);

        $sqlserienumero = "SPO_SERIE_NUMERO ?,?,? ";
        $params = array(&$idtipodocu, &$seridocu, &$numedocu);
        $stmtserienumero = sqlsrv_query($conexion, $sqlserienumero, $params);
        while ($raw = sqlsrv_fetch_array($stmtserienumero)) {
            $numedocu = $raw[0];
        }
        $sqlspi_doc_clie_prov = "SPI_DOC_CLIE_PROV_GRIFO_PHP ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
        $paramsspi_doc_clie_prov = array(
            array($iddocumento, SQLSRV_PARAM_INOUT), array($idDIARIO, SQLSRV_PARAM_IN),
            array($idtipodocu, SQLSRV_PARAM_IN), array($seridocu, SQLSRV_PARAM_IN),
            array($numedocu, SQLSRV_PARAM_IN), array($correlativo, SQLSRV_PARAM_INOUT),
            array($_POST['datos_post'][0], SQLSRV_PARAM_IN), array($_POST['datos_post'][5], SQLSRV_PARAM_IN),
            array($GLOSA, SQLSRV_PARAM_IN), array($TIPOPAGO, SQLSRV_PARAM_IN),
            array($moneda, SQLSRV_PARAM_IN), array($idtica, SQLSRV_PARAM_IN),
            array($basei, SQLSRV_PARAM_IN), array($inafecto, SQLSRV_PARAM_IN),
            array($igv, SQLSRV_PARAM_IN),  array($total, SQLSRV_PARAM_IN),
            array($GLOSA, SQLSRV_PARAM_IN), array($iddocumentoanterior, SQLSRV_PARAM_IN),
            array($idtitdiario, SQLSRV_PARAM_IN),  array($TC, SQLSRV_PARAM_IN),
            array($totalME, SQLSRV_PARAM_IN),  array($idoperacion, SQLSRV_PARAM_IN),
            array($detraccion, SQLSRV_PARAM_IN), array($idtipodocreferencia, SQLSRV_PARAM_IN),
            array($seridocref, SQLSRV_PARAM_IN),array($ConsolidadoRefe, SQLSRV_PARAM_IN),  array($otros, SQLSRV_PARAM_IN),
            array($TDET, SQLSRV_PARAM_IN), array($IDET, SQLSRV_PARAM_IN),
            array($DIRECCIONCLIEPROV, SQLSRV_PARAM_IN), array($idvendedor, SQLSRV_PARAM_IN)
        );
        $stmtspi_doc_clie_prov = sqlsrv_query($conexion, $sqlspi_doc_clie_prov, $paramsspi_doc_clie_prov);
        sqlsrv_next_result($stmtspi_doc_clie_prov);

        $sqlHISTORIALUSUARIOS = "SPI_HISTORIALUSUARIOS ?,?,?,?";
        $paramsHISTORIALUSUARIOS = array(
            array($idOperacion, SQLSRV_PARAM_IN),
            array($idUsuario, SQLSRV_PARAM_IN),
            array($tabla, SQLSRV_PARAM_IN),
            array($iddocumento, SQLSRV_PARAM_IN)
        );
        $stmtHISTORIALUSUARIOS = sqlsrv_query($conexion, $sqlHISTORIALUSUARIOS, $paramsHISTORIALUSUARIOS);
        sqlsrv_next_result($stmtHISTORIALUSUARIOS);


        ////    ////
        $contador = 0;
        for ($i = 0; $i < count($_POST['datos']); $i++) {
            $elemento  = $_POST['datos'][$i];
            $id_tmp = 0;
            $idDetComercial = 0;
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
                array($idDetComercial, SQLSRV_PARAM_INOUT),     array($iddocumento, SQLSRV_PARAM_IN),
                array($codigo_producto, SQLSRV_PARAM_IN),       array($nombre_producto, SQLSRV_PARAM_IN),
                array($idUnidad, SQLSRV_PARAM_IN),              array($BaseIME, SQLSRV_PARAM_IN),
                array($BaseI, SQLSRV_PARAM_IN),                 array($PrecioME, SQLSRV_PARAM_IN),
                array($precio_venta_f, SQLSRV_PARAM_IN),        array($cantidad, SQLSRV_PARAM_IN),
                array($PorcentDscto, SQLSRV_PARAM_IN),          array($Descuento, SQLSRV_PARAM_IN),
                array($DescuentoME, SQLSRV_PARAM_IN),           array($IGV, SQLSRV_PARAM_IN),
                array($IGVME, SQLSRV_PARAM_IN),                 array($TotalME, SQLSRV_PARAM_IN),
                array($precio_total_f, SQLSRV_PARAM_IN),        array($Afecto, SQLSRV_PARAM_IN),
                array($idProyecto, SQLSRV_PARAM_IN),            array($idAlmacen, SQLSRV_PARAM_IN),
                array($req, SQLSRV_PARAM_IN),                   array($DESLOTE, SQLSRV_PARAM_IN)
            );
            $stmtDETCOMERCIAL_PEDIDOS = sqlsrv_query($conexion, $sqlDETCOMERCIAL_PEDIDOS, $paramsDETCOMERCIAL_PEDIDOS);
            $next_result = sqlsrv_next_result($stmtDETCOMERCIAL_PEDIDOS);
            
            if($stmtDETCOMERCIAL_PEDIDOS){

                $contador++;
            }
        }


        $sqlMENSAJE = "SELECT *	FROM tbdocumentos	WHERE iddocumento = ? ";
        $paramsMENSAJE = array($iddocumento);
        $recMENSAJE = sqlsrv_query($conexion, $sqlMENSAJE, $paramsMENSAJE);
        $rowMENSAJE = sqlsrv_fetch_array($recMENSAJE);

        $numerodoccc = $rowMENSAJE['NumeDocu'];



        $IDCAJA = 0;
        if ($consolidado > 0) {
            //SPI_CAJACOMERCIALES
            //IDCONSOLIDADO,IDDOCUMENTO(PEDIDO,BONI),'I',FECHA,IDVENDEDOR,IDCAJA,IDPROYECTO,IDCC
            $sqlSPI_CAJACOMERCIALES = "SPI_CAJACOMERCIALES ?,?,?,?,?,?";
            $paramsSPI_CAJACOMERCIALES = array(
                array($IDCAJA, SQLSRV_PARAM_IN), array($consolidado, SQLSRV_PARAM_IN),
                array($iddocumento, SQLSRV_PARAM_IN), array($idOperacion, SQLSRV_PARAM_IN),
                array($FechaEmision, SQLSRV_PARAM_IN), array($idvendedor, SQLSRV_PARAM_IN)
            );
            $stmtSPI_CAJACOMERCIALES = sqlsrv_query($conexion, $sqlSPI_CAJACOMERCIALES, $paramsSPI_CAJACOMERCIALES);
            sqlsrv_next_result($stmtSPI_CAJACOMERCIALES);
        }
        if ($contador>=0) {
            $repuesta = array('estado' => true, 'respuesta' => $contador);
        }else{
            $repuesta = array('estado' => false, 'respuesta' => $contador);
        }
        echo json_encode($repuesta);
        exit;
    }else if($_POST['accion'] == 'eliminar devolucion'){
        header('Content-type: application/json; charset=utf-8');
        $dato = $_POST['data'];
        $sql = "SPD_DEVOLUCIONES  ?";
        $parametro = array($dato);
        $resul = sqlsrv_query($conexion, $sql,$parametro);
        if ($resul == true) 
        {
            $repuesta = array('estado' => true);
        }
        else 
        {
            $repuesta = array('estado' => false);
        }
        echo json_encode($repuesta);
        exit;
    }
}