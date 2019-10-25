<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nuevo Pedido</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 
	<link href="css/estilos.css" rel="stylesheet" type="text/css" media="screen"> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
	function realizaProceso(valorCaja1, valorCaja2){
	        var parametros = {
	            "valorCaja1" : valorCaja1,
	        	"valorCaja2" : valorCaja2
	        };
	        $.ajax({
	            data:  parametros,
	            url:   'MostrarProductos.php',
	            type:  'post',
	            beforeSend: function () {
	                    $("#resultado").html("Procesando, espere por favor...");
	            },
	            success:  function (response) {
	                    $("#resultado").html(response);
	            }
	        });
	}
	</script>
	<script >
	function ObtenerFila(){
		$(document).ready(function() {
			$("a.l1s").click(function(){
			cod = $(this).parents("tr").find("td").eq(0).text();
			nombre = $(this).parents("tr").find("td").eq(1).text();
			precio = $(this).parents("tr").find("td").eq(2).text();
			unidad = $(this).parents("tr").find("td").eq(3).text();
			document.getElementById("valor1").value=cod;					
			document.getElementById("valor2").value=nombre;				
			document.getElementById("valor3").value=unidad;
			document.getElementById("valor5").value=precio;
			document.getElementById("valor4").focus();
			});
		});
	}
	</script>
	<script>
		function Calculo(){
		var precio = document.getElementById("valor5").value;
		var cantidad= document.getElementById("valor4").value;
		var total=(Math.round((precio*cantidad) * 100) / 100)
		document.getElementById("valor6").value=total;
		}
	</script>
	<script>
		function Agregar(valorCaja1, valorCaja2){
		var descripcion= document.getElementById("valor2").value;
		var unidad= document.getElementById("valor3").value;
		var cantidad= document.getElementById("valor4").value;
		var precio = document.getElementById("valor5").value;
		var total= document.getElementById("valor6").value;
		document.getElementById("valor1").value="";
		}
	</script>
</head>
<body class="container" background="img/login.JPG">	
<div class="container">
<center>
	<BR>
		<form method="POST" action="Menu.php">
		<button type="submit" class="btn btn-default">Menú</button>
		</form>
	<BR>
</center>	
	<div class="panel bordepanel">
		<div class="panel-heading" STYLE="background-color:#6E6E6E">
	    	<font color = "FFFFFF"><h3 class="panel-title">Pedido</h3></font>
	  </div>
		  	<div class="panel-body">
		  	<form class="form-horizontal" role="form">
		  		<form role="form"  >
					<div class="form-group">
						<font color = "585858"><label class="control-label col-sm-2" id="valora1">Código:</label></font>
					    <div class="col-sm-2">
					    	<input class="form-control" type="text" id="valor1" placeholder="Código" name="ncodigo">
					    </div>
					    <font color = "585858"><label class="control-label col-sm-1" id="valora2">Descripcion:</label></font>
					  	<div class="col-sm-3">
					  		<input class="form-control" type="text" id="valor2" placeholder="Descripción" name="nDescripcion">
					  	</div>
					  	<font color = "585858"><label class="control-label col-sm-1" id="valora3">Unidad:</label></font>
					    <div class="col-sm-2">
					    	<input class="form-control" type="text" id="valor3" placeholder="Unidad" name="nunidad">
					    </div>
					</div>
				    <div class="form-group">
				       	<font color = "585858"><label class="control-label col-sm-2" id="valora4">Cantidad:</label></font>
					  	<div class="col-sm-2">
					  		<input class="form-control" type="number" step="any" id="valor4" placeholder="Cantidad" name="nCantidad" onblur="Calculo();">
					  	</div>
				       	<font color = "585858"><label class="control-label col-sm-1" id="valora5">Precio:</label></font>
					  	<div class="col-sm-2">
					  		<input class="form-control" type="number" step="any" id="valor5" placeholder="Precio" name="nPrecio" onblur="Calculo();">
					  	</div>
					  	<font color = "585858"><label class="control-label col-sm-2" id="valora6">Total:</label></font>
					  	<div class="col-sm-2">
					  		<input class="form-control" type="number" step="any" id="valor6" placeholder="Total" name="nTotal">
					  	</div>
					</div> 
				  	<div class="col-sm-10">
					  	<div>	
				       		<button type="submit" class="buttonpepdido" value="busqueda" href="javascript:;" onclick="realizaProceso($('#valor1').val(), $('#valor2').val());return false;" value="Calcula"><img src="img/Glass.png"/></button>
				       	</div>
				       	<div >	
				       		<button type="submit" class="buttonpepdido" onclick="Agregar($('#valor1').val(), $('#valor2').val());return false;"  ><img src="img/Agregar.png"/></button>			       		
				       	</div>
			    	</div> 
			    </form>	
		  	</form>
		  	</div>
	</div>
</div>
<div id="mostrar" class="CONT"></div>
<form id="cod" ></form>
<form id="resultado" onclick="ObtenerFila();" ></form>
<form id="answer" ></form>

<script src="js/responsive.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>