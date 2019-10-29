<!-- leonardo estubo aqui 9:55 PM 10/25/2019	-->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Pedido</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

    <?php
    require_once("conexion.php");
    session_start();
    $basedatos = @$_SESSION['basedatos'];
    $conexion = Conexion($basedatos);
    $usuario = @$_SESSION['usuario'];
    $pass = @$_SESSION['pass'];

    var_dump($_SESSION);
    echo '<BR><BR>';
    var_dump($_POST);
    ?>

</head>


<body class="container" background="img/login.JPG">
    <div>
        <center>

            <BR>
            <form method="POST" action="Menu.php">
                <button type="submit" class="btn btn-default">Menu</button>
            </form>
            <BR>
        </center>
        <div class="panel bordepanel">
            <div class="panel-heading" STYLE="background-color:#6E6E6E">
                <font color="FFFFFF">
                    <h3 class="panel-title">Registro de Cantidades Recibidas-Pedido:
                        <?php echo $_POST['xpedido']; ?> </h3>
                </font>
            </div>
            <div class="panel-body mt-4">
                <div class="confirmarRespuesta row container-fluid mb-5">
                    <button type="button" class="btn btn-primary col-md-12" id="busquedaDetalle" data-toggle="modal" data-target="#myModal">confirmar productos</button>
                    <button type="button" class="btn btn-primary col-md-12 hide" id="calcular">Calcular</button>

                </div>

                <div id="resultados" class='mt-4 col-md-12'>
                    <div class="table-responsive">
                        <table class='table'>

                        </table>
                    </div>
                </div>
            </div>
            <!-- Carga los datos ajax -->
        </div>
        <script>
            function ocultarbonificacion() {
                <?php
                require_once("conexion.php");
                session_start();
                $basedatos = @$_SESSION['basedatos'];
                $conexion = Conexion($basedatos);
                $usuario = @$_SESSION['usuario'];
                $pass = @$_SESSION['pass'];

                if (!@$conexion) {
                    //die( print_r( sqlsrv_errors(), true));
                    echo "No hay conexion";
                } else {
                    $sqlpropiedades = "SELECT valor	FROM tbpropiedades	WHERE clave = ? ";
                    $paramspropiedades = array('BONIWEB');
                    $recpropiedades = sqlsrv_query($conexion, $sqlpropiedades, $paramspropiedades);
                    $rowpropiedades = sqlsrv_fetch_array($recpropiedades, SQLSRV_FETCH_ASSOC);
                    //echo  $rowpropiedades['valor'];
                    if ($rowpropiedades['valor'] == 'S') {
                        $varvisible = 'block';
                    } else {
                        $varvisible = 'none';
                    }
                } ?>
            }
        </script>

        <div id='bonificacion' class="panel bordepanel" style="display:<?PHP echo $varvisible ?>;">
            <div class="panel-heading" STYLE="background-color:#6E6E6E">
                <font color="FFFFFF">
                    <h3 class="panel-title">Bonificación</h3>
                </font>
            </div>
            <div class="panel-body">
                <div id="resultadosbonificacion" class='col-md-12'></div>
            </div>
            <!-- Carga los datos ajax -->
        </div>
        <div class="panel bordepanel">
            <div class="panel-heading" STYLE="background-color:#6E6E6E">
                <font color="FFFFFF">
                    <h3 class="panel-title">Cliente</h3>
                </font>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" role="form" id="datos_pedido">
                    <input class="form-control" type="text" id="DOC" onchange="Selectcombo(<?php echo $idTipoDocuReferencia; ?>)" value="<?php echo $idTipoDocuReferencia; ?>" style="visibility:hidden">

                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-3">
                            <span class="input-group-addon" id="valor1">Ruc:</span>
                            <input id="Ruc" type="text" class="form-control" placeholder="Ruc" value="<?php echo $_POST['xRUC']; ?>" onClick="this.select()" onblur="JalarRazSocial($('#Ruc').val()),Jalardireccion($('#Ruc').val())">
                        </div>
                        <div class="col-sm-5">
                            <span class="input-group-addon" id="valor1">Raz.Social:</span>
                            <input class="form-control" type="text" id="cliente" value="<?php echo $_POST['xcliente']; ?>" placeholder="Raz.Social" onClick="this.select()">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <span class="input-group-addon" id="valor1">Dirección:</span>
                            <input class="form-control" type="text" id="direccion" value="<?php echo $_POST['xdireccion']; ?>" placeholder="Dirección" onClick="this.select()">
                        </div>
                    </div>
                    <div class="row hide">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="pull-right">
                                <button type="button" class="buttonpedido" data-toggle="modal" data-target="#myCliente">
                                    <span class="glyphicon glyphicon-search"></span> Buscar Cliente
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row hide">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <div class="pull-right">
                                <font color="585858"><label id="valor1">Documento:</label></font>
                                <input name="documento2" id="documento1" type="radio" value="1" onClick="SeleccionaDocumento(1)" />
                                <font color="585858"><label id="valorticket">Factura</label></font>
                                <input name="documento2" id="documento3" type="radio" value="3" onClick="SeleccionaDocumento(3)" />
                                <font color="585858"><label id="valorticket">Boleta</label></font>
                            </div>

                        </div>
                    </div>
                    <div class="row hide">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-5">
                            <div class="pull-right">
                                <select class="form-control" data-width="auto" id="fpago">

                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="pull-right" style="display:none">
                        <button type="submit" class="buttonpedido" value="busqueda">
                            <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Pedido
                        </button>
                    </div>
                    <script>
                        var $_POST_AJAX = new Array();
                        $_POST_AJAX[0] = '<?php echo $_POST['xRUC']; ?>';
                        $_POST_AJAX[1] = '<?php echo $_POST['xpedido']; ?>';
                        $_POST_AJAX[2] = '<?php echo $_POST['xdireccion']; ?>';
                        $_POST_AJAX[3] = '<?php echo $_POST['IDCONSOLIDADO']; ?>';
                        $_POST_AJAX[4] = '<?php echo $_POST['IDVENDEDOR']; ?>';
                        $_POST_AJAX[5] = '<?php echo $_POST['xcliente']; ?>';
                        console.log($_POST_AJAX)
                    </script>
                    <div class="pull-right">
                        <a class="buttonpedido" id="btn-guardar-pedido">
                            <span class="glyphicon glyphicon-floppy-disk"></span> Guardar Devolucion
                        </a>
                    </div>
                    <div id="RAZON" class='col-md-12'></div>
                    <div style="display:none;">
                        <?php echo $_POST['xRUC']; ?>
                    </div>
                    <div style="display:none;">
                        <?php echo $_POST['xcliente']; ?>
                    </div>
                    <div style="display:none;">
                        <?php echo $_POST['xdireccion']; ?>
                    </div>
                    <div style="display:none;">
                        <?php echo $_POST['xpedido']; ?>
                    </div>
                    <div style="display:none;">
                        <?php echo $_POST['IDCONSOLIDADO']; ?>
                    </div>
                    <div style="display:none;">
                        <?php echo $_POST['IDVENDEDOR']; ?>
                    </div>
                </form>
            </div>



        </div>
        <div id="guarda1" class='col-md-12'></div>
        <div id="guarda2" class='col-md-12'></div>
    </div>
    <!-- items -->
    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">detalle de productos</h4>
                </div>
                <div class="modal-body">
                    <div class="herramientas">
                        <input type="checkbox" name="" id="btn-send-all">
                        <label for="btn-send-all">cancelar todo</label>
                    </div>

                    <div class="table-responsive">
                        <table class="table">

                            <?php

                            $sql = " SELECT 
                    tbItems.Codigo				,		tbDetComercial.Cantidad ,
                    tbDetComercial.Descripcion 	,		tbunidades.descripcion as UNIDADES, 
                    tbDetComercial.precio		,		tbdetcomercial.totalmn as TOTAL
                    FROM TBDETCOMERCIAL 
                    INNER JOIN TBDOCUMENTOS ON TBDOCUMENTOS.IDDOCUMENTO = TBDETCOMERCIAL.IDDOCUMENTO
                    INNER JOIN tbItems on tbItems.idItem = TBDETCOMERCIAL.idItem
                    INNER JOIN tbunidades on tbunidades.idunidad = TBDETCOMERCIAL.idunidad
                    WHERE TBDOCUMENTOS.NUMEDOCU = ?  and TBDOCUMENTOS.nombreClieProv = ? ";
                            $parametros = array($_POST['xpedido'], $_POST['xcliente']);
                            $stmt = sqlsrv_query($conexion, $sql, $parametros);
                            if ($stmt === false) die(print_r(sqlsrv_errors(), true));
                            $contenido = array();
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) $contenido[] = $row;
                            echo '<thead><tr><td></td>';
                            foreach ($contenido[0] as $key => $value) echo "<th>{$key}</th>";

                            echo '</tr></thead>';
                            echo '<tbody>';
                            foreach ($contenido as $key => $value) {
                                echo '<tr>';
                                echo "<td><button id='btn-enviar'>click</button></td>";

                                foreach ($value  as $key => $value) {
                                    if ($key == 'Cantidad') {
                                        $val = (int) $value;

                                        echo "<td><input type='number' style='width:35px' name='' class='precio' value='{$val}'></td>";
                                    } else if ($key == 'TOTAL' || $key == 'precio') {
                                        $val = round($value * 100) / 100;;
                                        echo "<td>{$val}</td>";
                                    } else
                                        echo "<td>{$value}</td>";
                                }
                                echo '</tr>';
                            }
                            echo "</tbody>";
                            sqlsrv_free_stmt($stmt);
                            ?>

                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- items -->
    <!-- clientes -->
    <!-- Modal -->
    <div class="modal fade bs-example-modal-lg" id="myCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"> Buscar clientes </h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="q1" placeholder=" Documento de identidad ">
                            </div>
                            <div class="col-sm-5">
                                <font color="585858"><label id="valor1">Tipo Documento:</label></font>
                                <input name="valordocumento" id="documento1" type="radio" value="6" onClick="Seleccionadocumento('6')" />
                                <font color="585858"><label id="valordocumento">RUC</label></font>
                                <input name="valordocumento" id="documento3" type="radio" value="1" onClick="Seleccionadocumento('1')" />
                                <font color="585858"><label id="valordocumento">DNI</label></font>
                                <input name="valordocumento" id="documento3" type="radio" value="0" onClick="Seleccionadocumento('0')" checked="checked" />
                                <font color="585858"><label id="valordocumento">Otros</label></font>
                                <script>
                                    function Seleccionadocumento(DOC3) {
                                        var DOCUMENTO = DOC3;
                                        document.getElementById("DOC3").value = DOCUMENTO;
                                    }
                                </script>
                            </div>
                            <div class="col-sm-10"></div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="q2" placeholder=" Cliente ">
                            </div>
                            <div class="col-sm-10"></div>
                            <div class="col-sm-10"></div>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="q3" placeholder=" Dirección ">
                            </div>
                            <div class="col-sm-10"></div>
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default" onload="loadcliente(1)" onclick="loadcliente(1)">
                                    <span class='glyphicon glyphicon-search'></span> Buscar</button>
                                <button type="button" class="btn btn-default" onclick="insertcliente($('#q1').val(), $('#q2').val(), $('#q3').val(),$('#DOC2').val(),$('#DOC3').val());loadcliente(1)">
                                    <span class='glyphicon glyphicon-user'></span> Registrar</button>
                            </div>
                            <div class="col-sm-4">
                                <font color="585858"><label id="valor1">Persona:</label></font>
                                <input name="valorpersona" id="documento1" type="radio" value="J" onClick="SeleccionaPersona('J')" />
                                <font color="585858"><label id="valorpersona">Jurídica</label></font>
                                <input name="valorpersona" id="documento3" type="radio" value="N" onClick="SeleccionaPersona('N')" checked="checked" />
                                <font color="585858"><label id="valorpersona">Natural</label></font>
                                <script>
                                    function SeleccionaPersona(DOC2) {
                                        var DOCUMENTO = DOC2;
                                        document.getElementById("DOC2").value = DOCUMENTO;
                                    }
                                </script>
                            </div>
                        </div>
                    </form>

                    <div id="loader2" style="position: absolute;	text-align: center;	top: 55px;	width: 100%;display:none;"></div>
                    <!-- Carga gif animado -->
                    <div class="outer_div2"></div>
                    <!-- Datos ajax Final -->

                </div>

                <div class="modal-footer">
                    <div id="guarda2" style="	text-align: center;	top: 55px;	width: 100%;display:none;"></div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <div class="col-sm-10">

                        <input class="form-control" type="text" id="DOC2" value="N" style="visibility:hidden">
                        <input class="form-control" type="text" id="DOC3" value="0" style="visibility:hidden">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- clientes -->
    <script src="js/responsive.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script src="js/ProcesosDevulucion.js"></script>
    <!-- script nuevo-->

</body>

</html>