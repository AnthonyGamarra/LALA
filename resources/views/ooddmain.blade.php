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
    console.log("=====[ MAIN ]=====");
    console.log("idredx: {{ session('idredx') }} ");
    console.log("redx: {{ session('redx') }}");
    console.log("idesx: {{ session('idesx') }}");
    console.log("codesx: {{ session('codesx') }}");
    console.log("esx: {{ session('esx') }}");
    console.log("headerx: {{ session('headerx') }}");
    console.log("======================");
</script>
<div class="todo">
    <div class="container largo">
            <div id="tapa">
                <div id="retorna">
                    <form action="{{ route('ooddselect') }}" method="POST">
                    @csrf
                        <button class="verbutton" type="submit">Cambiar EESS</button>
                    </form>
                </div>
                <div id="titulo" style="margin-top: -10px;">{{ session('redx') }} <BR> {{ session('codesx') }}::{{ session('esx') }}</div>
                <div id="ver">
                    <form action="{{ route('consolidaeess') }}" method="POST">
                    @csrf
                    <button class="verbutton" type="submit">Ver consolidado EESS</button>
                    </form>
                </div>
            </div>
            <div class="largo">
                <label class="medio1">Seleccione su actividad:</label>
                <label class="medio2">Prioridad:</label>
                <label class="medio3">Acción:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Estado:&nbsp;&nbsp;&nbsp;</label>
            </div>

            <div class="largo1">
                @foreach($results as $result)
                <form method="POST" id="cerrarForm" action="{{ route('cerrarooddhoja') }}">
                @csrf
                <div class="cuarto">
                    <input class="sel1" name="actividadd" id="actividadd" value="{{ $result->actividad }}" readonly>
                    <input class="sel2" name="prioridadd" id="prioridadd" value="{{ $result->prioridad }}" readonly>
                    <input type="hidden" name="cabezaidex" value="{{ $result->cabeza_id }}">
                    <input type="hidden" name="cerradd" value="{{ $result->cerrado }}">
                    
                    @if ($result->cerrado == 0)
                        <button class="but1" type="submit" formaction="{{ route('ooddhoja') }}" name="action" value="editar">Editar</button>
                        <button class="but2 confirmarCerrar" type="button" name="action" value="cerrar">Cerrar</button>
                    @else
                        <button class="but1" style="background-color: #1b7ec5 !important;" type="submit" formaction="{{ route('ooddhoja') }}" name="action" value="editar">Ver</button>
                        <button class="but2" style="background-color: #919191 !important;" disabled>Cerrado</button>
                    @endif
                </div>
                </form>
                @endforeach
            </div>
    </div>

<script>
    // Obtener todos los botones con la clase 'confirmarCerrar'
    document.querySelectorAll('.confirmarCerrar').forEach(function(button) {
        button.addEventListener('click', function(event) {
            // Mostrar alerta de confirmación
            var confirmacion = confirm("¿Está seguro que desea cerrar la actividad considerando que los cambios no irreversibles?");
            
            if (confirmacion) {
                // Si el usuario confirma, enviamos el formulario
                var form = button.closest('form');  // Buscar el formulario más cercano al botón
                form.submit();  // Enviar el formulario
            } else {
                // Si el usuario cancela, no hacemos nada
                return false;
            }
        });
    });
</script>



</div>

</body>
</html>
