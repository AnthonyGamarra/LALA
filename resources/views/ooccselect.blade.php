<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="IngHarold">
    <title>ORGANO CENTRALIZADO</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="todo">
    <div class="container medio">
        <div id="tapa">
            <div id="retorna">
                <form action="{{ route('logout') }}" method="POST">
                @csrf
                    <button class="verbutton" type="submit">Salir</button>
                </form>
            </div>
            <div id="titulo" style="margin-top: -10px;">{{ $oficina }}</div>
            <div id="ver">
                <form action="{{ route('consolidadoGeneralOOCC') }}" method="POST">
                @csrf
                    <button class="verbutton" type="submit">Ver consolidado OOCC</button>
                </form>
            </div>
        </div>
        <div class="largo">
            <form action="{{ route('ooccmain') }}" method="POST">
            @csrf
            <label for="establecimiento">Seleccione su Órgano Centralizado</label>
                <select name="fondox" id="fondox" required>
                    <option value="1">11099A0000 :: AFESSALUD</option>
                    <option value="2">12099A0000 :: SALUD</option>
                </select>
            </div>
            <button type="submit">Continuar</button>
        </form>
    </div>
</div>

</body>
</html>
