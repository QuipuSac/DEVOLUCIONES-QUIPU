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
        
        $idtitdiario = 0;
        $iddocumentoanterior = 0;
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
        $iddocumentoanterior = 0;
        $TC = 1;
        $totalME = 0;
        $idoperacion = "OVG";
        $detraccion = "N";
        $idtipodocreferencia = 215;
        $seridocref = "001";
        $nuedocref = $_POST['datos_post'][1];
        $DIRECCIONCLIEPROV = $_POST['datos_post'][2];
        $idvendedor = $idUsuario2;
        $otros = 0;
        $TDET = 0;
        $IDET = 0;
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
        $sqlspi_doc_clie_prov = "SPI_DOC_CLIE_PROV_GRIFO_PHP ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?";
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
			array($seridocref, SQLSRV_PARAM_IN),  array($otros, SQLSRV_PARAM_IN),
			array($TDET, SQLSRV_PARAM_IN), array($IDET, SQLSRV_PARAM_IN),
			array($DIRECCIONCLIEPROV, SQLSRV_PARAM_IN), array($idvendedor, SQLSRV_PARAM_IN)
		);
		$stmtspi_doc_clie_prov = sqlsrv_query($conexion, $sqlspi_doc_clie_prov, $paramsspi_doc_clie_prov);
		sqlsrv_next_result($stmtspi_doc_clie_prov);

        $repuesta = array('estado' => true, 'respuesta' => $_POST['datos_post'], 'respuesta1' => $_POST['datos']);

        echo json_encode($repuesta);
    }
}
