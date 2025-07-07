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
<script>
    console.log("=====[ SELECT ]=====");
    console.log("idredx: {{ session('idredx') }} ");
    console.log("redx: {{ session('redx') }}");
    console.log("======================");
</script>
<div class="todo">
    <div class="container medio">
        <div id="tapa">
            <div id="retorna">
                <form action="{{ route('logout') }}" method="POST">
                @csrf
                    <button class="verbutton" type="submit">Salir</button>
                </form>
            </div>
            <div id="titulo">{{ session('redx') }}</div>
            <div id="ver">
                <form action="{{ route('consolidared') }}" method="POST">
                @csrf
                <button class="verbutton" type="submit">Ver consolidado RED</button>
                </form>
            </div>
        </div>
        <div class="largo">
            <form action="{{ route('ooddmain') }}" method="POST">
            @csrf
            <label for="establecimiento">Seleccione su centro gestor</label>
                <select name="ide_eess" id="ide_eess" required>
                    @foreach($eess as $item)
                        <option value="{{ $item->id }}">{{ $item->codigo }} :: {{ $item->eess }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="name_idex" id="name_idex">
            </div>
            <button type="submit">Continuar</button>
        </form>
    </div>
</div>

</body>
</html>
