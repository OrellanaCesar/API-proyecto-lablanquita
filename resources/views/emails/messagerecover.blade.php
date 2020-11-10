<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<title>Recuperacion de Contraseña</title>
</head>
<style>
    .button {
        -webkit-appearance: button;
        -moz-appearance: button;
        appearance: button;

        text-decoration: none;
        color: initial;
    }
</style>
<body>
<p>Su nueva contraseña es: <strong>{{ $mensaje['password'] }}</strong></p>
<p>Ingrese nuevamente en el siguiente link con su nueva contraseña</p>
<a href="www.limpiezablanquita.com" class="button">www.limpiezablamquita.com</a>
<p>Gracias por confiar en nosotros!!.</p>
<p><strong>Limpieza Blanquita.</strong></p>
	{{-- <p><strong>Recibiste un Mensaje de:</strong> {{$mensaje['nombre']}} {{$mensaje['apellido']}}</p>
	<p><strong>Correo:</strong> {{$mensaje['email']}}</p>
	<p><strong>Provincia:</strong> {{$mensaje['provincia']}}</p>
	<p><strong>Comentario:</strong></p>
	{{$mensaje['comentario']}} --}}

</body>
</html>