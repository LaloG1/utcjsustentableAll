<!DOCTYPE html>
<html>
<head>
    <title>Credenciales de acceso</title>
</head>
<body>
    <h1>¡Bienvenido a Utcj Sustentable!</h1>
    <p>Hola {{ $nombre }},</p>
    <p>Tu usuario y contraseña son:</p>
    <ul>
        <li><strong>Matrícula:</strong> {{ $matricula }}</li>
        <li><strong>Contraseña:</strong> {{ $password }}</li>
    </ul>
    <p>Por favor, ingresa en la App con estas credenciales para iniciar con las tareas asignadas.</p>
</body>
</html>