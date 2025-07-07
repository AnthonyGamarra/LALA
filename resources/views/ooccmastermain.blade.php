<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="IngHarold">
    <title>ORGANO DESCONCENTRADO</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/anexo.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="top">
    <div class="izquierda" style="width:5%;">

        <form method="POST" action="{{ route('ooccmasterselect') }}">
            @csrf
            <button id="volver" type="submit">Volver</button>
        </form>
    </div>
    <div class="titulo" style="float:left;text-align:left;width:95%;">Control Maestro OOCC 	<b style="font-size:24px;">&rArr;</b> Gerencia Central de la Persona Adulta Mayor y Persona con Discapacidad</div>
</div>

<div class="master">
    <table class="m1">
        <thead>
            <tr>
                <th>#</th>
                <th>Código CG.</th>
                <th>Centro Gestor</th>
                <th>Código de actividad</th>
                <th>Denominación de la actividad</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>11099A0000</td>
            <td>AFESSALUD</td>
            <td>02249900JE000001</td>
            <td>Elaborar publicaciones relevantes para la toma de decisiones en los niveles estratégicos y operativos, en materia del sistema nacional de estadística</td>
            <td>1</td>
            <td>Inactivo</td>
            <td>
                <button class="edit">Editar</button>
                <button class="elim">Eliminar</button>
            </td>
        </tr>
        
    </table>
</div>

<div class="footer">
    sistema de programación y formulación presupuestal PIA Y POI 2026
</div>


</body>
</html>