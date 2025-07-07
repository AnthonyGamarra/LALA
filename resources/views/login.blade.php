<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
     <meta name="author" content="Harold Coila">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEMA PIA Y POI</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="todo">
    <div class="container corto">
        <div id="tapa">sistema de programación y formulación del PIA Y POI 2026</div>
        
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <label for="usuario">Ingrese usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="clave">Ingrese clave:</label>
            <input type="password" id="clave" name="clave" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</div>
</body>
</html>
