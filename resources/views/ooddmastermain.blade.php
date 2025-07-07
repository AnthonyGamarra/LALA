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

        <form method="POST" action="{{ route('ooddmasterselect') }}">
            @csrf
            <button id="volver" type="submit">Volver</button>
        </form>
    </div>
    <div class="titulo" style="float:left;text-align:left;width:95%;">Control Maestro OODD 	<b style="font-size:24px;">&rArr;</b> Establecimiento XY</div>
</div>

<div class="master">
    <table class="m2">
        <thead>
            <tr>
                <th>#</th>
                <th>Código CG.</th>
                <th>Establecimiento de Salud</th>
                <th>Actividades</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @php
                $previousCodigo = null;
                $previousEess = null;
                $rowIndex = 1;
                $activities = [];  // Para almacenar las actividades
                $buttons = [];     // Para almacenar los botones
            @endphp

            @foreach($results as $result)
                @if ($previousCodigo !== $result->codigo || $previousEess !== $result->eess)
                    @if ($previousCodigo !== null && $previousEess !== null)
                    <tr>
                        <td>{{ $rowIndex }}</td>  
                        <td>{{ $previousCodigo }}</td> 
                        <td>{{ $previousEess }}</td>   
                        <td>
                            <ul>
                                @foreach ($activities as $activity)
                                    <li>{{ $activity }}</li> 
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @foreach ($buttons as $button)
                                <form action="{{ route('ooddmasterrestaura') }}" method="POST">
                                @csrf
                                    <input type="hidden" name="idexr" value="{{ $button['id'] }}">
                                    <button id="r-{{ $button['id'] }}" class="rest" type="submit">Restablecer</button>
                                </form>
                                
                                <form action="{{ route('ooddmastercambia') }}" method="POST">
                                @csrf
                                    <input type="hidden" name="idexc" value="{{ $button['id'] }}">
                                    <button id="{{ $button['id'] }}" class="{{ $button['class'] }}" type="submit">{{ $button['text'] }}</button>
                                </form>
                            @endforeach
                            
                        </td>
                    </tr>
                    @endif


                    @php $buttons = []; @endphp
                    @php
                        // Guardamos las actividades y botones para la siguiente fila
                        $activities = [$result->actividades];
                        $buttons[] = [
                            'id' => $result->cabeza_id,
                            'class' => $result->cerrado == 1 ? 'elim' : 'edit',
                            'text' => $result->cerrado == 1 ? 'Cerrado' : 'Abierto'
                        ];
                        $previousCodigo = $result->codigo;  // Guardamos el código mostrado
                        $previousEess = $result->eess;      // Guardamos el establecimiento de salud mostrado
                        $rowIndex++;  // Incrementamos el índice para la siguiente fila
                    @endphp
                @else

                    @php
                        // Agregamos las actividades a las anteriores sin perder el orden
                        $activities[] = $result->actividades;
                        // Agregamos los botones de la fila actual
                        $buttons[] = [
                            'id' => $result->cabeza_id,
                            'class' => $result->cerrado == 1 ? 'elim' : 'edit',
                            'text' => $result->cerrado == 1 ? 'Cerrado' : 'Abierto'
                        ];
                    @endphp
                    
                @endif
            @endforeach

            <!-- Al final del bucle, mostramos la última fila combinada -->
            <tr>
                <td>{{ $rowIndex }}</td>
                <td>{{ $previousCodigo }}</td>
                <td>{{ $previousEess }}</td>
                <td>
                    <ul>
                        @foreach ($activities as $activity)
                            <li>{{ $activity }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    @foreach ($buttons as $button)
                        <button id="r-{{ $button['id'] }}" class="rest">Restablecer</button>

                        <button id="{{ $button['id'] }}" class="{{ $button['class'] }}">
                            {{ $button['text'] }}
                        </button>
                    @endforeach
                </td>
            </tr>

        </tbody>



      
        
    </table>
</div>

<div class="footer">
    sistema de programación y formulación presupuestal PIA Y POI 2026 <em>Creado por: HaroldCoilaVillena</em>
</div>

<script>
    // Seleccionamos todos los botones
    const buttons = document.querySelectorAll('.m2 button.edit, .m2 button.elim');
    
    // Guardamos los textos originales y los colores de fondo
    buttons.forEach(button => {
        const originalText = button.textContent;
        const originalBackgroundColor = button.style.backgroundColor;

        // Evento hover (mouseover y mouseout) para cada botón
        button.addEventListener('mouseover', function() {
            this.textContent = "Intercambiar"; // Cambia el texto a "Intercambiar"
            this.style.backgroundColor = "#a96501"; // Cambia el color de fondo al hacer hover
        });

        button.addEventListener('mouseout', function() {
            this.textContent = originalText; // Restaura el texto original cuando el mouse sale
            this.style.backgroundColor = originalBackgroundColor; // Restaura el color original de fondo
        });
    });
</script>

</body>
</html>