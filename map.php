<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title> HTML5 GEOLOCATION</title>
	<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDGFeghvf0M9_HhA2B6NPcSYEIDlVDpSUc'></script>
</head>
<body>
	<button onclick="findMe()">Mostra ubicacion</button>
	<div class="container">	
		<h1>Mapa maincra</h1>
		<div id="mapa" style="width:700px; height: 500px">
			--aca va el mapa pe
		</div>  
		<script type="text/javascript">
			function findMe(){
				var output = document.getElementById('mapa');
				//verifica si soporta geolocalizacion
				if (navigator.geolocation) {
					output.innerHTML = "<P>tu navegador soporta pe chistris</p>";
				}else{
					output.innerHTML = "<P>tu navegador no aguanta pe</p>";
				}
				//obtener lat y long
				function localizacion(posicion){
					var latitude = posicion.coords.latitude;
					var longitude = posicion.coords.longitude;

					var imgURL = "https://maps.googleapis.com/maps/api/staticmap?center="+latitude+","+longitude+"&size=600x300&markers=color:red%7C"+latitude+","+longitude+"&key=AIzaSyDGFeghvf0M9_HhA2B6NPcSYEIDlVDpSUc";
					output.innerHTML = "<P>LATITUD: "+latitude+"<BR>LONGITUD: "+longitude+"</p>";
					//output.innerHTML ="<img src='"+imgURL+"'>";
				}
				function error(){
					output.innerHTML  = "<P>no se pudo obtener ubicación</p>";
				}
				navigator.geolocation.getCurrentPosition(localizacion,error);
			}
		</script>
	</div>
</body>

</html>
