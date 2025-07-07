<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="IngHarold">
    <title>ORGANO DESCONCENTRADO</title>
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
            <div id="titulo">Administrador de OODD</div>
        </div>
        <div class="largo">
            <form action="{{ route('ooddmastermain') }}" method="POST">
            @csrf
            <label for="establecimiento">Seleccione su centro gestor</label>
                <select name="ide_organo" id="ide_organo" required>
                    @foreach($organo as $item)
                        <option value="{{ $item->id }}">{{ $item->oodd }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit">Continuar</button>
        </form>
    </div>
</div>

</body>
</html>
