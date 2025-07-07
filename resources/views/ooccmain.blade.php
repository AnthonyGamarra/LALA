<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORGANO CENTRALIZADO</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<div class="todo">
    <div class="container largo">
        <div id="tapa">
            <div id="retorna">
                <form action="{{ route('ooccselect') }}" method="POST">
                @csrf
                    <button class="verbutton" type="submit">Cambiar</button>
                </form>
            </div>
            <div id="titulo" style="margin-top: -10px;">
                {{ session('ocx') }} :: {{ session('fondotx') }}
            </div>
            <div id="ver">
                <form action="{{ route('consolida_oocc') }}" method="POST">
                    @csrf
                    <button class="verbutton" type="submit">Ver consolidado FONDO</button>
                </form>
            </div>
        </div>

        @if(session('success')) <div style="color: green">{{ session('success') }}</div> @endif
        @if(session('error')) <div style="color: red">{{ session('error') }}</div> @endif

        <div class="largo">
            <label class="medio1">Seleccione su actividad:</label>
            <!-- <label class="medio2">Prioridad:</label> -->
            <label class="medio3">Acciones</label>
        </div>

        <div class="largo1">
            @foreach($results as $result)
            <div class="cuarto" style="display: flex; align-items: center; gap: 10px; padding-top: 20px; border-bottom: 1px solid #ccc;">
                <form method="POST" action="{{ route('oocchoja') }}" style="display: flex; align-items: center; gap: 10px; width: 100%; padding-bottom: 20px">
                    @csrf
                    <input 
                        class="sel1" 
                        name="actividadd" 
                        value="{{ $result->actividad }}" 
                        readonly 
                        style="flex: 1; height: 38px; margin: 5px 10px; box-sizing: border-box;">

                    <input type="hidden" name="cabezaidex" value="{{ $result->cabeza_id }}">
                    <input type="hidden" name="cerradd" value="{{ $result->cerrado }}">

                    @if ($result->cerrado == 0)
                        <button class="but1" type="submit" style="height: 38px;">Editar</button>

                        <button type="button" 
                                class="but2 abrirModalRenombrar" 
                                data-id="{{ $result->actioocc_id }}" 
                                data-nombre="{{ $result->actividad }}" 
                                style="background-color: orange; height: 38px;">
                            Renombrar
                        </button>

                        <button type="submit" formaction="{{ route('oocc.cerraroocchoja') }}" 
                                class="but2" 
                                style="margin-left: 5px; height: 38px;">
                            Cerrar
                        </button>
                    @else
                        <button class="but1" type="submit" style="background-color: #1b7ec5 !important; height: 38px;">Ver</button>
                        <button class="but2" disabled style="background-color: #919191 !important; height: 38px;">Cerrado</button>
                    @endif
                </form>
            </div>
            @endforeach
        </div>

        <!-- Formulario para agregar nueva actividad -->
        <div class="largo1" style="padding-top: 20px;">
            <label class="medio4">Nueva actividad:</label>
            <label class="medio5">Acción</label>
        </div>

        <div class="largo2">
            <form method="POST" action="{{ route('oocc.agregarActividad') }}">
                @csrf
                <div class="cuarto" style="display: flex; align-items: center; gap: 10px; padding-top: 10px;">
                    <input type="hidden" name="oocc_id" value="{{ session('oocc_id') }}">
                    <input type="hidden" name="fondo" value="{{ session('fondox') }}">

                    <input class="sel1" type="text" name="actividad" placeholder="Ingrese actividad" required
                        style="flex: 1; height: 38px; margin: 5px 10px; box-sizing: border-box;">

                    <button class="but1" type="submit" style="height: 38px;">Grabar</button>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- Modal de renombrar -->
<div id="modalRenombrar" style="display: none; position: fixed; top: 0; left: 0; 
    width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); 
    justify-content: center; align-items: center; z-index: 999;">
    
    <div style="background: white; padding: 20px; border-radius: 10px; width: 300px;">
        <form method="POST" action="{{ route('oocc.renombrarActividad') }}">
            @csrf
            <input type="hidden" name="actioocc_id" id="modalActiooccId">
            <label for="nuevo_nombre">Nuevo nombre:</label>
            <input type="text" id="modalNuevoNombre" name="nuevo_nombre" required style="width: 100%; margin-top: 10px; margin-bottom: 15px;">
            <div style="text-align: right;">
                <button type="button" onclick="cerrarModal()" style="margin-right: 10px;">Cancelar</button>
                <button type="submit" style="background-color: orange;">Renombrar</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.confirmarCerrar').forEach(button => {
    button.addEventListener('click', function () {
        if (confirm("¿Está seguro que desea cerrar la actividad?")) {
            this.closest('form').submit();
        }
    });
});

document.querySelectorAll('.abrirModalRenombrar').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');

        document.getElementById('modalActiooccId').value = id;
        document.getElementById('modalNuevoNombre').value = nombre;

        document.getElementById('modalRenombrar').style.display = 'flex';
    });
});

function cerrarModal() {
    document.getElementById('modalRenombrar').style.display = 'none';
}
</script>
</body>
</html>
