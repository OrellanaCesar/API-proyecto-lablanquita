<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title>Mensaje Recibido</title>
</head>
<body>
	{{-- <img src="logo.jpeg" width="250" height="250" style="align-content: middle"> --}}
	<p><strong>Recibiste un Mensaje de:</strong> {{$mensaje['nombre']}} {{$mensaje['apellido']}}</p>
	<p><strong>Correo:</strong> {{$mensaje['email']}}</p>
	<p><strong>Provincia:</strong> {{$mensaje['provincia']}}</p>
	<p><strong>Comentario:</strong></p>
	{{$mensaje['comentario']}}

</body>
</html>
