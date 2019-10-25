<html> 
<head> 
<title>Probando bootstrap</title> 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen"> 

</head> 
<body class="container"> <h1>HOLA MediaVida</h1>
<script src="http://code.jquery.com/jquery.js&qu..."</script> 
<script src="js/bootstrap.min.js"></script> 

<div class="navbar navbar-inverse"> 
        <a class="navbar-brand" href="#">@MediaVida</a> 
        <ul class="nav navbar-nav"> 
                <li class="active"><a href="#">Inicio</a></li> 
                <li><a href="#">xD</a></li> 
                <li><a href="#">Fotos</a></li> 
        </ul> 
</div>

<form class="form-horizontal">
        <div class="form-group">
                <label for="inputEmail" class="control-label">User</label>
                <input type="text" class="form-control" id="inputEmail" placeholder="Email">
        </div>
        <div class="form-group">
                <label for="inputPassword" class="control-label">Password</label>
                <input type="password" class="form-control" id="inputPassword" placeholder="Password">
        </div>
        <div class="form-group">
                <div class="checkbox">
                        <label><input type="checkbox"> Recordarme</label>
                </div>
        </div>
        <div class="form-group">
                <button type="submit" class="btn btn-default">Log In</button>
                <a data-toggle="modal" href="#myModal" class="btn btn-primary btn-lg">Registrarse</a>
        </div>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> <!– Importante llamar antes a jQuery para que funcione bootstrap.min.js   –> 
<script src="js/bootstrap.min.js"></script> <!– Llamamos al JavaScript de Bootstrap –> 

</body> 
</html>