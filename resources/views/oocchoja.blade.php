<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta autor="Ing.HaroldCoilaV.">
    <title>Tabla Interactiva</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/anexo.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>   
    <script>
    console.log("=====[ HOJA ]=====");
    console.log("idocx: {{ session('idocx') }} ");
    console.log("oficinax: {{ session('oficinax') }}");
    console.log("idesx: {{ session('idesx') }}");
    console.log("codesx: {{ session('codesx') }}");
    console.log("esx: {{ session('esx') }}");
    console.log("nameactiodx: {{ session('nameactiodx') }}");
    console.log("prioactiodx: {{ session('prioactiodx') }}");
    console.log("headerx: {{ session('headerx') }}");
    console.log("cerradox: {{ session('cerradox') }}");
    console.log("======================");
</script>
<div class="top">
    <div class="izquierda">
        @if (session('cerradox') == 0)
            [Hoja abierta]
        @else
            [Hoja cerrada]
        @endif
        <form method="POST" action="{{ route('ooccmain') }}">
            @csrf
            <button id="volver" type="submit">Menú</button>
        </form>
    </div>
    <div class="titulo">Sistema de programación y formulación presupuestal PIA Y POI 2026</div>
    <div class="derecha">

        <form method="POST" action="{{ route('exportaoocchoja') }}" id="exportarForm">
            @csrf  <!-- Esto es necesario para proteger el formulario contra CSRF -->
            <button type="submit" style="background-color:#1b7ec5 !important;">Exportar</button>
        </form>

        @if (session('cerradox') == 0)
        <form method="POST" action="{{ route('grabaoocchoja') }}" id="grabarForm">
            @csrf
            <input type="hidden" name="totalFilasGrabar" id="totalFilasGrabar">
            <input type="hidden" name="DataGrabar" id="DataGrabar">
            <button type="button" onclick="grabar()">Grabar</button>
        </form>
        @endif
    </div>
</div>

<div class="cabezaOODD">
    <div class="form-group">
        <label for="organo-centralizado" style="background:#fff9d9;">Órgano Centralizado:</label>
        <input type="text" id="inp1" value="{{ session('ocx') }}" readonly>
    </div>
    <div class="form-group">
        <label for="establecimiento-salud" style="background:#ebf4ff;">Fondo:</label>
        <input type="text" id="inp2" value="{{ session('fondotx') }}"  readonly>
    </div>
        <div class="form-group">
        <label for="cod-centro-gestor-2" style="background:#cde3ff;">Actividad:</label>
        <input type="text" id="inp3" value="{{ session('nameactiodx') }}" readonly>
    </div>
    <!-- <div class="form-group">
        <label for="establecimiento-salud" style="background:#ebf4ff;">Prioridad:</label>
        <input type="text" id="inp2" value="{{ session('prioactiodx') }}"  readonly>
    </div> -->
    <p>// Un anexo por cada actividad y uno consolidado</p>
</div>
<div class="detalle">    
<table id="detallesTable">
        <thead>
            <tr>
                <th>Fondo Financiero</th>
                <th>Cod. PoFi</th>
                <th>Posición Presupuestaria</th>
                <th>Tipo Gasto</th>
                <th>Estimación del<br>Presupuesto 2025</th>
                <th>Enero</th>
                <th>Febrero</th>
                <th>Marzo</th>
                <th>Abril</th>
                <th>Mayo</th>
                <th>Junio</th>
                <th>Julio</th>
                <th>Agosto</th>
                <th>Septiembre</th>
                <th>Octubre</th>
                <th>Noviembre</th>
                <th>Diciembre</th>
                <th>Total<br>2026</th>
                <th>Proyección<br>Año 2027</th>
                <th>Proyección<br>Año 2028</th>
                <th>Proyección<br>Año 2029</th>
            </tr>
        </thead>
        <tbody>
            @php
                $index = 0;  // Iniciar el contador
            @endphp
            @foreach($detalles as $row)
                @php
                    $index++;  // Incrementar el contador en cada iteración
                @endphp
            @switch($row->color)
                @case(3) <tr id="f{{ $index + 1 }}" class="fcateupup"> @break
                @case(2) <tr id="f{{ $index + 1 }}" class="fcateup"> @break
                @case(1) <tr id="f{{ $index + 1 }}" class="fcate"> @break
                @default <tr id="f{{ $index + 1 }}">
            @endswitch
                    <td><input id="f{{ $index + 1 }}-1" value="{{ $row->financia_codigo }}" type="text" class="input-td" placeholder=" " {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td id="f{{ $index + 1 }}-2">{{ $row->pofi_codigo }}</td>
                    <td id="f{{ $index + 1 }}-3">{{ $row->pofi }}
                        
                        @if(session('cerradox') == 0)
                            @if ($row->color == 0)
                                <button class="add-row-btn" onclick="addRow(this)">+</button>
                                <button class="del-row-btn" onclick="delRow(this)">x</button>
                            @endif
                        @endif
                    </td>
                    <td>
                        <select id="f{{ $index + 1 }}-4" {{ session('cerradox') == 0 ? '' : 'disabled' }}>
                            @foreach([0 => ' ', 1 => 'Ineludible', 2 => 'Otro gasto operativo'] as $value => $label)
                                <option value="{{ $value }}" {{ $row->tipo == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input id="f{{ $index + 1 }}-5" value="{{ $row->estimacion }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-6" value="{{ $row->enero }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-7" value="{{ $row->febrero }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-8" value="{{ $row->marzo }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-9" value="{{ $row->abril }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-10" value="{{ $row->mayo }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-11" value="{{ $row->junio }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-12" value="{{ $row->julio }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-13" value="{{ $row->agosto }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-14" value="{{ $row->septiembre }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-15" value="{{ $row->octubre }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-16" value="{{ $row->noviembre }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-17" value="{{ $row->diciembre }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-18" value="{{ $row->total2026 }}" type="number" placeholder="0" class="numegris" disabled></td>
                    <td><input id="f{{ $index + 1 }}-19" value="{{ $row->proy2027 }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-20" value="{{ $row->proy2028 }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                    <td><input id="f{{ $index + 1 }}-21" value="{{ $row->proy2029 }}" type="number" placeholder="0" class="numegris" {{ session('cerradox') == 0 ? '' : 'disabled' }}></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="21" style="height: 20px; background-color: white; border-top: 2px solid #d0d0d0; border-bottom: 2px solid #d0d0d0;"></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td>GASTO DE PERSONAL</td>
            <td></td>
            <td><input id="t1" type="number" value="0" disabled></td>
            <td><input id="t2" type="number" value="0" disabled></td>
            <td><input id="t3" type="number" value="0" disabled></td>
            <td><input id="t4" type="number" value="0" disabled></td>
            <td><input id="t5" type="number" value="0" disabled></td>
            <td><input id="t6" type="number" value="0" disabled></td>
            <td><input id="t7" type="number" value="0" disabled></td>
            <td><input id="t8" type="number" value="0" disabled></td>
            <td><input id="t9" type="number" value="0" disabled></td>
            <td><input id="t10" type="number" value="0" disabled></td>
            <td><input id="t11" type="number" value="0" disabled></td>
            <td><input id="t12" type="number" value="0" disabled></td>
            <td><input id="t13" type="number" value="0" disabled></td>
            <td><input id="t14" type="number" value="0" disabled></td>
            <td><input id="t15" type="number" value="0" disabled></td>
            <td><input id="t16" type="number" value="0" disabled></td>
            <td><input id="t17" type="number" value="0" disabled></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>BIENES</td>
            <td></td>
            <td><input id="u1" type="number" value="0" disabled></td>
            <td><input id="u2" type="number" value="0" disabled></td>
            <td><input id="u3" type="number" value="0" disabled></td>
            <td><input id="u4" type="number" value="0" disabled></td>
            <td><input id="u5" type="number" value="0" disabled></td>
            <td><input id="u6" type="number" value="0" disabled></td>
            <td><input id="u7" type="number" value="0" disabled></td>
            <td><input id="u8" type="number" value="0" disabled></td>
            <td><input id="u9" type="number" value="0" disabled></td>
            <td><input id="u10" type="number" value="0" disabled></td>
            <td><input id="u11" type="number" value="0" disabled></td>
            <td><input id="u12" type="number" value="0" disabled></td>
            <td><input id="u13" type="number" value="0" disabled></td>
            <td><input id="u14" type="number" value="0" disabled></td>
            <td><input id="u15" type="number" value="0" disabled></td>
            <td><input id="u16" type="number" value="0" disabled></td>
            <td><input id="u17" type="number" value="0" disabled></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td>SERVICIOS</td>
            <td></td>
            <td><input id="v1" type="number" value="0" disabled></td>
            <td><input id="v2" type="number" value="0" disabled></td>
            <td><input id="v3" type="number" value="0" disabled></td>
            <td><input id="v4" type="number" value="0" disabled></td>
            <td><input id="v5" type="number" value="0" disabled></td>
            <td><input id="v6" type="number" value="0" disabled></td>
            <td><input id="v7" type="number" value="0" disabled></td>
            <td><input id="v8" type="number" value="0" disabled></td>
            <td><input id="v9" type="number" value="0" disabled></td>
            <td><input id="v10" type="number" value="0" disabled></td>
            <td><input id="v11" type="number" value="0" disabled></td>
            <td><input id="v12" type="number" value="0" disabled></td>
            <td><input id="v13" type="number" value="0" disabled></td>
            <td><input id="v14" type="number" value="0" disabled></td>
            <td><input id="v15" type="number" value="0" disabled></td>
            <td><input id="v16" type="number" value="0" disabled></td>
            <td><input id="v17" type="number" value="0" disabled></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td></td>
            <td><input id="w1" type="number" value="0" disabled></td>
            <td><input id="w2" type="number" value="0" disabled></td>
            <td><input id="w3" type="number" value="0" disabled></td>
            <td><input id="w4" type="number" value="0" disabled></td>
            <td><input id="w5" type="number" value="0" disabled></td>
            <td><input id="w6" type="number" value="0" disabled></td>
            <td><input id="w7" type="number" value="0" disabled></td>
            <td><input id="w8" type="number" value="0" disabled></td>
            <td><input id="w9" type="number" value="0" disabled></td>
            <td><input id="w10" type="number" value="0" disabled></td>
            <td><input id="w11" type="number" value="0" disabled></td>
            <td><input id="w12" type="number" value="0" disabled></td>
            <td><input id="w13" type="number" value="0" disabled></td>
            <td><input id="w14" type="number" value="0" disabled></td>
            <td><input id="w15" type="number" value="0" disabled></td>
            <td><input id="w16" type="number" value="0" disabled></td>
            <td><input id="w17" type="number" value="0" disabled></td>
        </tr>

        <tr>
            <td colspan="21" style="height: 10px; background-color: white; border-top: 1px solid #d0d0d0; border-bottom: 1px solid #d0d0d0;"></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>TOTAL GASTO GIP</td>
            <td></td>
            <td><input id="x1" type="number" value="0" disabled></td>
            <td><input id="x2" type="number" value="0" disabled></td>
            <td><input id="x3" type="number" value="0" disabled></td>
            <td><input id="x4" type="number" value="0" disabled></td>
            <td><input id="x5" type="number" value="0" disabled></td>
            <td><input id="x6" type="number" value="0" disabled></td>
            <td><input id="x7" type="number" value="0" disabled></td>
            <td><input id="x8" type="number" value="0" disabled></td>
            <td><input id="x9" type="number" value="0" disabled></td>
            <td><input id="x10" type="number" value="0" disabled></td>
            <td><input id="x11" type="number" value="0" disabled></td>
            <td><input id="x12" type="number" value="0" disabled></td>
            <td><input id="x13" type="number" value="0" disabled></td>
            <td><input id="x14" type="number" value="0" disabled></td>
            <td><input id="x15" type="number" value="0" disabled></td>
            <td><input id="x16" type="number" value="0" disabled></td>
            <td><input id="x17" type="number" value="0" disabled></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>TOTAL GASTO NO GIP</td>
            <td></td>
            <td><input id="y1" type="number" value="0" disabled></td>
            <td><input id="y2" type="number" value="0" disabled></td>
            <td><input id="y3" type="number" value="0" disabled></td>
            <td><input id="y4" type="number" value="0" disabled></td>
            <td><input id="y5" type="number" value="0" disabled></td>
            <td><input id="y6" type="number" value="0" disabled></td>
            <td><input id="y7" type="number" value="0" disabled></td>
            <td><input id="y8" type="number" value="0" disabled></td>
            <td><input id="y9" type="number" value="0" disabled></td>
            <td><input id="y10" type="number" value="0" disabled></td>
            <td><input id="y11" type="number" value="0" disabled></td>
            <td><input id="y12" type="number" value="0" disabled></td>
            <td><input id="y13" type="number" value="0" disabled></td>
            <td><input id="y14" type="number" value="0" disabled></td>
            <td><input id="y15" type="number" value="0" disabled></td>
            <td><input id="y16" type="number" value="0" disabled></td>
            <td><input id="y17" type="number" value="0" disabled></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b>TOTAL</b></td>
            <td></td>
            <td><input id="z1" type="number" value="0"  disabled></td>
            <td><input id="z2" type="number" value="0" disabled></td>
            <td><input id="z3" type="number" value="0" disabled></td>
            <td><input id="z4" type="number" value="0" disabled></td>
            <td><input id="z5" type="number" value="0" disabled></td>
            <td><input id="z6" type="number" value="0" disabled></td>
            <td><input id="z7" type="number" value="0" disabled></td>
            <td><input id="z8" type="number" value="0" disabled></td>
            <td><input id="z9" type="number" value="0" disabled></td>
            <td><input id="z10" type="number" value="0" disabled></td>
            <td><input id="z11" type="number" value="0" disabled></td>
            <td><input id="z12" type="number" value="0" disabled></td>
            <td><input id="z13" type="number" value="0" disabled></td>
            <td><input id="z14" type="number" value="0" disabled></td>
            <td><input id="z15" type="number" value="0" disabled></td>
            <td><input id="z16" type="number" value="0" disabled></td>
            <td><input id="z17" type="number" value="0" disabled></td>
        </tr>
    </tfoot>
    </table>
</div>
    

    <!-- Contenedor de sugerencias que se va a mostrar debajo del input -->
    <div id="suggestions-container" class="suggestions" style="display: none;"></div>


    <div class="nota">
        Nota.-
        <BR>1. Precisar el nivel de prioridad, sea 1, 2, 3, …. Donde 1 es la actividad más prioritaria.
        <BR>2. Tipo de gasto: <I> ineludible, <O> Otro gasto operativo																


    </div>
    
    <div id="todoengris">
        <div id="spin"></div>
        <div id="spintxt">Guarando?</div>
    </div>
</div>

<div class="footer">
    sistema de programación y formulación presupuestal PIA Y POI 2026 <em>Creado por: HaroldCoilaVillena</em>
</div>


<script>
        // Lista de opciones de texto con código y descripción
        const options = [
"001402 GESTION EN CALIDAD",
"001510 PROVISIONES",
"001712 PLAN CONTING. ATEN. PAC. PATOLOG. CARDI.",
"001713 Prevenir EsSalud",
"002005 RS.930 BIENES ESTRATEG. FENOMENO NIÑO",
"002206 PROGRAMA NACIONAL HELADAS Y FRIAJE",
"002503 CONTROL DEL SISTEMA PATRIMONIAL",
"002504 MANTENIMIENTO PREVENTIVO INFRAESTRUCTURA",
"003011 CAM REBAGLIATI- G.D.LIMA",
"003012 CERP. LA VICTORIA - G.D.LIMA",
"003013 CERP. CALLAO - G.D. LIMA",
"003014 CAM ALMENARA - G.D. LIMA",
"003015 CAM SABOGAL - LIMA",
"003036 EPIDEMIA DENGUE",
"003079 EMERGENCIA COVID-19",
"003191 EMERGENCIA CAS COVI - 2025 LBS.RP.",
"003706 MANTENIMIENTO Y REPARACION DE INFRAEST.",
"003707 MANTENIMIENTO Y CONSERVACION DE INFRAEST",
"003708 MANTENIMIENTO Y REPARACION DE EQUIPOS",
"005604 INTERCAMBIO PRESTACIONAL DL.1302",
"006002 LAUDOS ARBITRALES - REDES",
"006003 RECONOCIMIENTO DE DEUDA - REDES",
"070768 COMPRA CENTRALIZADA",
"080868 ACCIONES COMUNES",
"090968 COMPRA CENTRALIZADA DELEGADA BIENES",
"180100 ENFER.CRÓNI.NO TRASMIS.HIPERT.AR-DIAB-OB",
"240100 ENFERMEDADES ONCOLÓGICAS",
"770100 CARDIOVASCULAR",
"790100 OTRAS INTERVENCIONES DE SALUD",
"800400 ACCIONES COMUNES",
"002238 PLAN DE CONTINGENCIA ANTE LLUVIAS"
];


// Función para mostrar sugerencias
function initializeSuggestions() {
    // Obtener todos los inputs con la clase 'input-td'
    const inputs = document.querySelectorAll('.input-td');

    inputs.forEach(input => {
        const suggestionsContainer = document.createElement('div'); // Crear un contenedor dinámico para cada input

        input.addEventListener("input", function() {
            const query = input.value.toLowerCase();
            suggestionsContainer.innerHTML = ""; // Limpiar las sugerencias anteriores

            if (query) {
                // Filtrar opciones que contengan el texto ingresado en el código o en la descripción
                const filteredOptions = options.filter(option => {
                    const [code, ...descriptionParts] = option.split(" ");
                    const description = descriptionParts.join(" ").toLowerCase();
                    return code.includes(query) || description.includes(query);
                });

                // Si hay opciones filtradas, se muestran
                if (filteredOptions.length > 0) {
                    suggestionsContainer.style.display = "block";
                    suggestionsContainer.classList.add('suggestions'); // Agregar clase de estilo

                    filteredOptions.forEach(option => {
                        const suggestionItem = document.createElement("div");
                        suggestionItem.classList.add("suggestion-item");
                        suggestionItem.textContent = option;

                        // Al hacer clic en una sugerencia, se selecciona el código
                        suggestionItem.addEventListener("click", function() {
                            // Extraer solo el código (antes del primer espacio)
                            const code = option.split(" ")[0];
                            input.value = code; // Solo mostrar el código en el input
                            suggestionsContainer.style.display = "none"; // Ocultar sugerencias después de seleccionar
                        });

                        suggestionsContainer.appendChild(suggestionItem);
                    });

                    // Calcular la posición del input y mostrar las sugerencias justo debajo
                    const inputRect = input.getBoundingClientRect();

                    // Ajustar la posición de las sugerencias
                    suggestionsContainer.style.left = `${inputRect.left + window.scrollX}px`; // Considera desplazamiento horizontal
                    suggestionsContainer.style.top = `${inputRect.bottom + window.scrollY}px`; // Considera desplazamiento vertical

                } else {
                    suggestionsContainer.style.display = "none"; // Ocultar si no hay coincidencias
                }
            } else {
                suggestionsContainer.style.display = "none"; // Ocultar si no se está escribiendo nada
            }

            // Colocar el contenedor de sugerencias en el DOM, justo debajo del input correspondiente
            document.body.appendChild(suggestionsContainer); // Asegúrate de que se agregue al body, no a un contenedor relativo
        });

        // Cerrar las sugerencias al hacer clic fuera del input o de las sugerencias
        document.addEventListener("click", function(event) {
            // Si el clic ocurre fuera del input y las sugerencias, las ocultamos
            if (!event.target.closest("input") && !event.target.closest(".suggestions")) {
                suggestionsContainer.style.display = "none"; // Ocultar el contenedor de sugerencias
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initializeSuggestions();
});
    </script>


<script>
  // Escuchar el doble clic en cualquier input de las filas
  $('#detallesTable tbody tr').each(function() {
    $(this).find('td').each(function(index) {
      if (index >= 5 && index <= 16) { // Para las columnas 6 a 17 (índices 5 a 16)
        $(this).children('input').on('dblclick', function() {
          let rowId = $(this).closest('tr').index() + 2; // Obtener el índice de la fila (1-indexed)
          let currentValue = $(this).val(); // Valor actual del input
          let newValue = prompt("Insertar valor repetitivo de enero a diciembre:", currentValue);
          
          // Si el usuario ingresa un valor, se actualizan todos los inputs de esa fila
          if (newValue !== null) {
            // Actualizamos todos los inputs de la fila
            $(`#f${rowId}-6, #f${rowId}-7, #f${rowId}-8, #f${rowId}-9, #f${rowId}-10, #f${rowId}-11, #f${rowId}-12, #f${rowId}-13, #f${rowId}-14, #f${rowId}-15, #f${rowId}-16, #f${rowId}-17`)
              .val(newValue);

              //actualiza
              actualizarSumas();
          }
        });
      }
    });
  });

</script>


<script>
// Función para agregar una nueva fila
function addRow(button) {
    var row = button.closest('tr');  // Obtener la fila donde se hizo clic
    var newRow = row.cloneNode(true); // Clonar la fila

    // Obtener el id más alto en la tabla y aumentar en 1
    var highestId = 0;
    $('#detallesTable tbody tr').each(function() {
        var currentId = $(this).attr('id');
        if (currentId && currentId.startsWith('g')) {
            var idNumber = parseInt(currentId.substring(1));  // Extraer el número después de "g"
            if (idNumber > highestId) {
                highestId = idNumber;  // Guardar el id más alto encontrado
            }
        }
    });

    // Nuevo id será el siguiente en la secuencia
    var newId = 'g' + (highestId + 1);

    // Modificar el id de la nueva fila con el nuevo id
    newRow.id = newId;

    // Cambiar el id de cada input y td en la nueva fila
    newRow.querySelectorAll('input').forEach((input, index) => {
        input.id = newId + '-' + (index + 1); // Asignar ids únicos a los inputs, por ejemplo: g6-1, g6-2, ...
    });

    newRow.querySelectorAll('td').forEach((td, index) => {
        td.id = newId + '-' + (index + 1); // Asignar ids únicos a los td, por ejemplo: g6-1, g6-2, ...
    });

    // Limpiar los valores de los inputs de la nueva fila
    var inputs = newRow.querySelectorAll('input');
    inputs.forEach(function(input) {
        input.value = ''; // Limpiar el valor de cada input
    });

    // Insertar la nueva fila justo después de la fila original
    row.parentNode.insertBefore(newRow, row.nextSibling);  // Insertar la nueva fila después de la original

    // Asegurarse de que el select tenga la opción seleccionada correctamente
    var select = newRow.querySelector('select');
    var itemTipo = select.value;  // Aquí puedes ajustar según el valor 'item.tipo'

    // Condicional para seleccionar la opción en el select
    if (itemTipo == 1) {
        select.querySelector('option[value="1"]').selected = true;
    } else if (itemTipo == 2) {
        select.querySelector('option[value="2"]').selected = true;
    }

    // Re-inicializar las sugerencias en la nueva fila
    initializeSuggestions();

    // Forzar el recorrido del DOM de toda la tabla para asegurarnos de que los elementos estén actualizados
    $('#detallesTable tbody tr').each(function() {
        var id = $(this).attr('id');
        console.log("Recorrido de fila con ID:", id);  // Verificar que estamos recorriendo todas las filas
    });

    // Ahora vamos a capturar y mostrar los valores de los inputs de la fila recién creada
    console.log("ID de la nueva fila (tr):", newRow.id); // ID del tr

    // Mostrar el ID de todos los td en la nueva fila (para verificar)
    newRow.querySelectorAll('td').forEach((td, index) => {
        console.log("ID del td " + (index + 1) + " en la nueva fila:", td.id);
    });

    // Mostrar los valores de los inputs de la nueva fila en la consola (para depuración)
    newRow.querySelectorAll('input').forEach((input, index) => {
        console.log("Valor de " + input.id + ":", input.value); // Mostrar los valores
    });
}

// Función para eliminar una fila
function delRow(button) {
    var row = button.closest('tr');  // Obtener la fila donde se hizo clic
    row.parentNode.removeChild(row);  // Eliminar la fila

    // Aquí, puedes actualizar el contador de IDs si es necesario
}
</script>


    <script>
// Función para calcular las sumas acumulativas y actualizar las celdas
function actualizarSumas() {
    // Definir las reglas de las subsumas
    const reglasSuma = [
        // +2LOCACIONES
        { pofi_codigos: ['2510323101','2510323102','2510323103','2510323104','2510323105','2510323106','2510323107','2510323108','2510323109','2510323110','2510323201','2510323202','2510323203','2510323204','2510323206','2510323207','2510323208','2510323209','2510323300','2510323401','2510323402','2510323403','2510323404','2510323501','2510323502','2510323503','2510323504','2510323601','2510323602','2510323603','2510323604','2510323605','2510323606','2510323607','2510323608','2510323609','2510323610','2510323611','2510323612','2510323613'],pofi_destino: '2520250000',},
        { pofi_codigos: ['2510322101','2510322102','2510322103','2510322104','2510322105','2510322106','2510322107','2510322108','2510322109','2510322110','2510322111','2510322112','2510322201','2510322202','2510322203','2510322204','2510322205','2510322206','2510322207','2510322301','2510322302','2510322303','2510322304','2510322305','2510322306','2510322307'],pofi_destino: '2510322000',},
        // +2 pasajes nacionales
        { pofi_codigos: ['2520261101','2520261102','2520261103' ], pofi_destino: '2520261100', },
        { pofi_codigos: ['2520261201','2520261202','2520261203' ], pofi_destino: '2520261200', },
        //nivel 1
        { pofi_codigos: ['2510122023', '2510122024', '2510122183', '2510122184', '2510122193', '2510122194', '2510122203', '2510122204', '2510122213', '2510122214', '2510122223', '2510122224', '2510122253', '2510122254'], pofi_destino: '2510122000', },
        { pofi_codigos: ['2510132013', '2510132014', '2510132015'], pofi_destino: '2510132000', },
        { pofi_codigos: ['2510230100', '2510240100', '2510250100'], pofi_destino: '2510200000', },
        { pofi_codigos: ['2510325000', '2510304000', '2510306000', '2510307000', '2510308000', '2510309000', '2510310000', '2510311000', '2510313000', '2510314000', '2510318000', '2520250000', '2520257000','2510322000','2510315000','2510323000'], pofi_destino: '2510300000', },
        { pofi_codigos: ['2510323101','2510323102','2510323103','2510323104','2510323105','2510323106','2510323107','2510323108','2510323109','2510323110','2510323201','2510323202','2510323203','2510323204','2510323206','2510323207','2510323208','2510323209','2510323300','2510323401','2510323402','2510323403','2510323404','2510323501','2510323502','2510323503','2510323504','2510323601','2510323602','2510323603','2510323604','2510323605','2510323606','2510323607','2510323608','2510323609','2510323610','2510323611','2510323612','2510323613'], pofi_destino: '2510323000', },
        { pofi_codigos: ['2520101000', '2520102000', '2520103000', '2520104000', '2520105000', '2520106000', '2520107000', '2520108000', '2520109000', '2520110000', '2520111000', '2520112000', '2520113000', '2520114000', '2520197000', '2520198000', '2520199000'], pofi_destino: '2520100000', },
        { pofi_codigos: ['2520224023'],pofi_destino: '2520224020',},
        { pofi_codigos: ['2520224011'],pofi_destino: '2520224010',},
        { pofi_codigos: ['2520224010','2520224020','2520224030'],pofi_destino: '2520224000',},
        { pofi_codigos: ['2520238001', '2520238002', '2520238003', '2520238004', '2520238005', '2520238007', '2520238008', '2520238009', '2520238011', '2520238013', '2520238014', '2520238015', '2520238016', '2520238017', '2520238019', '2520238021', '2520238022', '2520238025', '2520238026', '2520238099'], pofi_destino: '2520238000', },
        { pofi_codigos: ['2520240001', '2520240002', '2520240003', '2520240004'], pofi_destino: '2520240000', },
        { pofi_codigos: ['2520244001', '2520244002', '2520244003', '2520244004', '2520244005', '2520244006', '2520244009', '2520244010'], pofi_destino: '2520244000', },
        { pofi_codigos: ['2520238031','2520238032','2520238033','2520238034','2520238035','2520238036'],pofi_destino: '2520238030',},
        { pofi_codigos: ['2520225010', '2520225020', '2520225030', '2520225040'], pofi_destino: '2520225000', },
        { pofi_codigos: ['2520404000'],pofi_destino: '2520400000',},
        { pofi_codigos: ['2520305000', '2520306000'],pofi_destino: '2520300000',},
        { pofi_codigos: ['2520252030','2520252010', '2520252020','2520252040'],pofi_destino: '2520252000',},
        { pofi_codigos: ['2520252011'],pofi_destino: '2520252010',},
        // NIVEL 2
        { pofi_codigos: ['2510101003', '2510101004', '2510103000', '2510103003', '2510103004', '2510104003', '2510104004', '2510105003', '2510110000', '2510110003', '2510110004', '2510111000', '2510111004', '2510112000', '2510112003', '2510112004', '2510113000', '2510113003', '2510113004', '2510114003', '2510114004', '2510115003', '2510115004', '2510116003', '2510118003', '2510120003', '2510126003', '2510126004', '2510127005', '2510131000', '2510131004', '2510136003', '2510137004', '2510140004', '2510122000','2510132000','2510118004','2510138004','2510199000'], pofi_destino: '2510100000', },
        { pofi_codigos: ['2520224000', '2520201000', '2520202000', '2520203000', '2520204000', '2520205000', '2520206000', '2520207000', '2520208000', '2520210000', '2520211000', '2520213000', '2520214000', '2520215000', '2520216000', '2520217000', '2520219000', '2520221000', '2520222000', '2520223000', '2520227000', '2520228000', '2520230000', '2520231000', '2520232000', '2520233000', '2520235000', '2520237000', '2520298000', '2520299000', '2520253000', '2520254000', '2520255000', '2520256000', '2520258000', '2520259000', '2520238000', '2520240000', '2520244000', '2520238030', '2520225000', '2520400000', '2520300000', '2520252000','2510320000','2510321000','2520245000','2520246000','2520247000','2520261000'], pofi_destino: '2520200000', },
        // +pasajes nacionales para servicios
        { pofi_codigos: ['2520261100', '2520261200','2520261300'], pofi_destino: '2520261000', },
        // NIVEL 3
        { pofi_codigos: ['2510100000', '2510200000', '2510300000'], pofi_destino: '2510000000', },
        { pofi_codigos: ['2520100000', '2520200000'], pofi_destino: '2520000000', },
        //NIVEL4
        { pofi_codigos: ['2510000000', '2520000000'], pofi_destino: '2500000000', },

        //SUMAS PARA TOTALES BLOQUE1
        { tipo: 'input',pofi_codigos: ['2510100000'],pofi_destino: 't'},
        { tipo: 'input',pofi_codigos: ['2510200000', '2520100000'],pofi_destino: 'u'},
        { tipo: 'input',pofi_codigos: ['2510300000', '2520200000'],pofi_destino: 'v'},
        { tipo: 'fila' ,pofi_codigos: ['t','u','v'],pofi_destino: 'w'},

        //SUMAS PARA TOTALES BLOQUE2
        { tipo: 'input',pofi_codigos: ['2510000000'],pofi_destino: 'x'},
        { tipo: 'input',pofi_codigos: ['2520000000'],pofi_destino: 'y'},
        { tipo: 'fila' ,pofi_codigos: ['x','y'],pofi_destino: 'z'},
    ];

    // Iterar sobre las reglas de suma
    reglasSuma.forEach(regla => {
        let suma = Array(17).fill(0); // Crear un array para almacenar las sumas de cada columna de 5 a 17

        // Suma por 'pofi_codigos' 1de2
        $('#detallesTable tbody tr').each(function() {
            const pofi_codigo = $(this).find('td:eq(1)').text(); // 'id_pofi' está en la segunda columna

            // Verificar si la fila tiene un 'id_pofi' que coincide con los valores definidos en las reglas
            if (regla.pofi_codigos.includes(pofi_codigo)) {
                // Si coincide, sumar las columnas 5 a 17
                for (let i = 5; i <= 21 ; i++) {
                    //if(i==18)i++;
                    const valor = parseInt($(this).find('td:eq(' + (i - 1) + ')').find('input').val()) || 0;
                    suma[i - 5] += valor; // Sumar el valor al índice correspondiente
                }
            }
        });

        // Suma por pofi_codigo 2de2
        $('#detallesTable tbody tr').each(function() {
            const pofi_codigo = $(this).find('td:eq(1)').text(); // Obtener el 'id_pofi' de la fila

            if (pofi_codigo === regla.pofi_destino) {
                // Si la fila tiene el 'id_pofi' destino, actualizar las celdas con las sumas
                for (let i = 0; i < suma.length; i++) {
                    $(this).find('td:eq(' + (5 + i - 1) + ')').find('input').val(suma[i]); // Actualizar los valores en las celdas
                }
            }
        });
        //SUMA HORIZONTAL 1de1
        $('#detallesTable tbody tr').each(function() {
            let sumaFila = 0;
            // Sumar valores de columnas 6 a 17 (índices 5 a 16)
            for (let i = 5; i <= 16; i++) {
                const valor = parseInt($(this).find('td:eq(' + i + ')').find('input').val()) || 0;
                sumaFila += valor;
            }

            // Insertar resultado en la columna 18 (índice 17)
            $(this).find('td:eq(17)').find('input').val(sumaFila);
        });

        //
        // Procesamiento adicional por tipo
        if (regla.tipo === 'fila') {
            $('#detallesTable tbody tr').each(function () {
                const pofi_codigo = $(this).find('td:eq(1)').text().trim();
                if (pofi_codigo === regla.pofi_destino) {
                    for (let i = 0, j = 0; i <= 16; i++) {
                        $(this).find('td:eq(' + (5 + i - 1) + ')').find('input').val(suma[j]);
                        j++;
                    }
                }
            });

            // Si la fila destino es 'w', también sumamos verticalmente entre inputs
            if (regla.pofi_destino === 'w') {
                for (let i = 1; i <= 17; i++) {
                    let total = 0;
                    ['t', 'u', 'v'].forEach(pref => {
                        total += parseInt($('#' + pref + i).val()) || 0;
                    });
                    $('#w' + i).val(total);
                }
            }
            // Si la fila destino es 'z', también sumamos verticalmente entre inputs
            if (regla.pofi_destino === 'z') {
                for (let i = 1; i <= 17; i++) {
                    let total = 0;
                    ['x', 'y'].forEach(pref => {
                        total += parseInt($('#' + pref + i).val()) || 0;
                    });
                    $('#z' + i).val(total);
                }
            }

        } else if (regla.tipo === 'input') {
            for (let i = 0, j = 0; i <= 16; i++) {
                $('#' + regla.pofi_destino + (i + 1)).val(suma[j]);
                j++;
            }
        }
    //
    });
}

// Llamar a la función actualizarSumas cada vez que se cambie un valor en los inputs
$('#detallesTable').on('input', 'input[type="number"]', function() {
    actualizarSumas(); // Actualizar las sumas al cambiar los valores
});

// Llamar a la función al cargar la página para aplicar las sumas iniciales
$(document).ready(function() {
    actualizarSumas();
});

        </script>

<script>
// Función para agregar una nueva fila
function addRow(button) {
    var row = button.closest('tr');  // Obtener la fila donde se hizo clic
    
    // Verificar si la fila tiene la clase 'fcate'
    if (row.classList.contains('fcate')) {
        // Si tiene la clase 'fcate', no clonamos la fila y ocultamos los botones
        var buttons = row.querySelectorAll('button');
        buttons.forEach(function(button) {
            button.style.opacity = 0;  // Ocultar los botones (opacidad 0)
        });
        return;  // Salir de la función sin clonar
    }

    var newRow = row.cloneNode(true); // Clonar la fila

    // Obtener el id más alto en la tabla y aumentar en 1
    var highestId = 0;
    $('#detallesTable tbody tr').each(function() {
        var currentId = $(this).attr('id');
        if (currentId && currentId.startsWith('g')) {
            var idNumber = parseInt(currentId.substring(1));  // Extraer el número después de "g"
            if (idNumber > highestId) {
                highestId = idNumber;  // Guardar el id más alto encontrado
            }
        }
    });

    // Nuevo id será el siguiente en la secuencia
    var newId = 'g' + (highestId + 1);

    // Modificar el id de la nueva fila con el nuevo id
    newRow.id = newId;

    // Cambiar el id de cada input en la nueva fila
    newRow.querySelectorAll('input').forEach((input, index) => {
        input.id = newId + '-' + (index + 1); // Asignar ids únicos a los inputs, por ejemplo: g6-1, g6-2, ...
    });

    // Actualizar el id de cada td en la nueva fila
    newRow.querySelectorAll('td').forEach((td, index) => {
        td.id = newId + '-' + (index + 1); // Asignar ids únicos a los td, por ejemplo: g6-1, g6-2, ...
    });

    // Limpiar los valores de los inputs de la nueva fila
    var inputs = newRow.querySelectorAll('input');
    inputs.forEach(function(input) {
        input.value = ''; // Limpiar el valor de cada input
    });

    // Insertar la nueva fila justo después de la fila original
    row.parentNode.insertBefore(newRow, row.nextSibling);  // Insertar la nueva fila después de la original

    // Asegurarse de que el select tenga la opción seleccionada correctamente
    var select = newRow.querySelector('select');
    var itemTipo = select.value;  // Aquí puedes ajustar según el valor 'item.tipo'

    // Condicional para seleccionar la opción en el select
    if (itemTipo == 1) {
        select.querySelector('option[value="1"]').selected = true;
    } else if (itemTipo == 2) {
        select.querySelector('option[value="2"]').selected = true;
    }

    // Re-inicializar las sugerencias en la nueva fila
    initializeSuggestions();

    // **Mostrar en consola el id del tr y de los td de la nueva fila**
    console.log("ID de la nueva fila (tr):", newRow.id); // ID del tr

    // Mostrar el ID de todos los td en la nueva fila (para verificar)
    newRow.querySelectorAll('td').forEach((td, index) => {
        console.log("ID del td " + (index + 1) + " en la nueva fila:", td.id);
    });
}

// Función para eliminar una fila
function delRow(button) {
    var row = button.closest('tr');  // Obtener la fila donde se hizo clic
    row.parentNode.removeChild(row);  // Eliminar la fila
}

</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const filaC = document.getElementById('fcate');
    if (filaC) {
        const inputC = filaC.querySelectorAll('input, select');
        inputC.forEach(function(input) {
            input.disabled = true; // Deshabilitar el input
        });
    } 
});
</script>


<script>
// Obtener todos los inputs con la clase 'numegris'
const inputG = document.querySelectorAll('.numegris');

// Función para cambiar el color de fondo según el valor del input
function changeColor() {
    inputG.forEach(input => {
        // Comprobar si el valor del input es 0
        if (input.value == "0" || input.value == "") {
            // Si el valor es 0 o está vacío, el fondo es gris claro
            input.value = "0"; // Asegurarse de que el valor sea 0 si está vacío
            input.style.color = "#999"; // Gris claro
            input.classList.remove('not-zero'); // Eliminar la clase 'not-zero'
        } else {
            // Si el valor no es 0, el fondo es negro
            input.style.color = "#000"; // Negro
            input.classList.add('not-zero'); // Añadir la clase 'not-zero' para estilo negro
        }
    });
}

// Escuchar el evento 'input' en cada uno de los inputs
inputG.forEach(input => {
    input.addEventListener('focus', function() {
        // Si el valor es 0 al hacer foco, lo borra para permitir ingreso
        if (input.value === "0" || input.value === "00" || input.value === "-") {
            input.value = "";
            input.style.color = "#000"; // Negro, ya que puede empezar a escribir
        }
    });

    input.addEventListener('blur', function() {
        // Cuando el input pierde el foco, si está vacío, "00" o "-", lo reinicia a 0
        if (input.value === "" || input.value === "00" || input.value === "-") {
            input.value = "0";
            input.style.color = "#999"; // Gris claro
        }
    });

    input.addEventListener('input', changeColor);
});

// Llamar a la función una vez para aplicar el estilo inicial
changeColor();

</script>


<script>
        // Función para resaltar la fila y columna activa al hacer cambios
        const inputC = document.querySelectorAll('input[type="number"], select');

        inputC.forEach(input => {
            input.addEventListener('focus', () => {
                const row = input.closest('tr');
                
                // Verificar si la fila tiene la clase 'fcate'
                if (row.classList.contains('fcate')) {
                    return; // Salir si la fila tiene la clase 'fcate'
                }

                // Agregar la clase 'active' para iluminar la fila y la columna
                row.classList.add('active');

                // Resaltar la columna activa
                const cells = input.closest('table').rows;
                for (let i = 0; i < cells.length; i++) {
                    // Verificar que la celda no esté dentro de una fila con la clase 'fcate'
                    if (!cells[i].classList.contains('fcate') && cells[i].cells[input.closest('td').cellIndex]) {
                        cells[i].cells[input.closest('td').cellIndex].classList.add('active');
                    }
                }
            });

            input.addEventListener('blur', () => {
                const row = input.closest('tr');

                // Verificar si la fila tiene la clase 'fcate'
                if (row.classList.contains('fcate')) {
                    return; // Salir si la fila tiene la clase 'fcate'
                }

                // Eliminar la clase 'active' cuando se pierde el foco
                row.classList.remove('active');

                // Eliminar resalto de la columna activa
                const cells = input.closest('table').rows;
                for (let i = 0; i < cells.length; i++) {
                    // Verificar que la celda no esté dentro de una fila con la clase 'fcate'
                    if (!cells[i].classList.contains('fcate') && cells[i].cells[input.closest('td').cellIndex]) {
                        cells[i].cells[input.closest('td').cellIndex].classList.remove('active');
                    }
                }
            });
        });

    </script>



<script>
function AjustaAltura() {
    const alturaRestante = window.innerHeight - 235; // Restar 360px de la altura total
    document.querySelector('.detalle').style.height = `${alturaRestante}px`;
}

window.addEventListener('load',AjustaAltura);
window.addEventListener('resize',AjustaAltura);
</script>




<script>
    // Función para bloquear el cambio de valor con las teclas de dirección
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('keydown', (event) => {
            // Prevenir el comportamiento predeterminado de las teclas de dirección
            if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
                event.preventDefault(); // Bloquea el cambio de valor
            }
        });
    });
</script>


<script>
function grabar() {
    var data = [];
    var totalFilas = 0; 

    // SERIE F
    $('#detallesTable tbody tr').each(function() {
        var rowId = $(this).attr('id');
        
        if (rowId && rowId.startsWith('f')) {
            var filaF = {};

            // Asignando los valores de los inputs y textos a las variables correspondientes
            filaF.x = document.getElementById(rowId + '-1').value || null;
            filaF.y = document.getElementById(rowId + '-2').textContent || null;
            filaF.z = (document.getElementById(rowId + '-4').value === "" ? 0 : Number(document.getElementById(rowId + '-4').value)) || 0;
            filaF.a = (document.getElementById(rowId + '-5').value === "" ? 0 : Number(document.getElementById(rowId + '-5').value)) || 0;
            filaF.b = (document.getElementById(rowId + '-6').value === "" ? 0 : Number(document.getElementById(rowId + '-6').value)) || 0;
            filaF.c = (document.getElementById(rowId + '-7').value === "" ? 0 : Number(document.getElementById(rowId + '-7').value)) || 0;
            filaF.d = (document.getElementById(rowId + '-8').value === "" ? 0 : Number(document.getElementById(rowId + '-8').value)) || 0;
            filaF.e = (document.getElementById(rowId + '-9').value === "" ? 0 : Number(document.getElementById(rowId + '-9').value)) || 0;
            filaF.f = (document.getElementById(rowId + '-10').value === "" ? 0 : Number(document.getElementById(rowId + '-10').value)) || 0;
            filaF.g = (document.getElementById(rowId + '-11').value === "" ? 0 : Number(document.getElementById(rowId + '-11').value)) || 0;
            filaF.h = (document.getElementById(rowId + '-12').value === "" ? 0 : Number(document.getElementById(rowId + '-12').value)) || 0;
            filaF.i = (document.getElementById(rowId + '-13').value === "" ? 0 : Number(document.getElementById(rowId + '-13').value)) || 0;
            filaF.j = (document.getElementById(rowId + '-14').value === "" ? 0 : Number(document.getElementById(rowId + '-14').value)) || 0;
            filaF.k = (document.getElementById(rowId + '-15').value === "" ? 0 : Number(document.getElementById(rowId + '-15').value)) || 0;
            filaF.l = (document.getElementById(rowId + '-16').value === "" ? 0 : Number(document.getElementById(rowId + '-16').value)) || 0;
            filaF.m = (document.getElementById(rowId + '-17').value === "" ? 0 : Number(document.getElementById(rowId + '-17').value)) || 0;
            filaF.n = (document.getElementById(rowId + '-18').value === "" ? 0 : Number(document.getElementById(rowId + '-18').value)) || 0;
            filaF.o = (document.getElementById(rowId + '-19').value === "" ? 0 : Number(document.getElementById(rowId + '-19').value)) || 0;
            filaF.p = (document.getElementById(rowId + '-20').value === "" ? 0 : Number(document.getElementById(rowId + '-20').value)) || 0;
            filaF.q = (document.getElementById(rowId + '-21').value === "" ? 0 : Number(document.getElementById(rowId + '-21').value)) || 0;

            //if (Object.values(filaF).some(value => value !== null && value !== 0)) {
                data.push(filaF);
                totalFilas++;
            //}
        }
    });

    // SERIE G si es que la hay
    if ($('#detallesTable tbody tr[id^="g"]').length > 0) {    
        $('#detallesTable tbody tr').each(function() {
            var rowId = $(this).attr('id'); 
            
            if (rowId && rowId.startsWith('g')) { 
                var filaG = {};
                filaG.x = $(this).find('input').eq(0).val() || null;
                filaG.y = $(this).find('td').eq(1).text() || null;
                filaG.z = $(this).find('select').val() !== "" ? $(this).find('select').val() : 0;
                filaG.a = $(this).find('input').eq(1).val() === "" ? 0 : Number($(this).find('input').eq(1).val());
                filaG.b = $(this).find('input').eq(2).val() === "" ? 0 : Number($(this).find('input').eq(2).val());
                filaG.c = $(this).find('input').eq(3).val() === "" ? 0 : Number($(this).find('input').eq(3).val());
                filaG.d = $(this).find('input').eq(4).val() === "" ? 0 : Number($(this).find('input').eq(4).val());
                filaG.e = $(this).find('input').eq(5).val() === "" ? 0 : Number($(this).find('input').eq(5).val());
                filaG.f = $(this).find('input').eq(6).val() === "" ? 0 : Number($(this).find('input').eq(6).val());
                filaG.g = $(this).find('input').eq(7).val() === "" ? 0 : Number($(this).find('input').eq(7).val());
                filaG.h = $(this).find('input').eq(8).val() === "" ? 0 : Number($(this).find('input').eq(8).val());
                filaG.i = $(this).find('input').eq(9).val() === "" ? 0 : Number($(this).find('input').eq(9).val());
                filaG.j = $(this).find('input').eq(10).val() === "" ? 0 : Number($(this).find('input').eq(10).val());
                filaG.k = $(this).find('input').eq(11).val() === "" ? 0 : Number($(this).find('input').eq(11).val());
                filaG.l = $(this).find('input').eq(12).val() === "" ? 0 : Number($(this).find('input').eq(12).val());
                filaG.m = $(this).find('input').eq(13).val() === "" ? 0 : Number($(this).find('input').eq(13).val());
                filaG.n = $(this).find('input').eq(14).val() === "" ? 0 : Number($(this).find('input').eq(14).val());
                filaG.o = $(this).find('input').eq(15).val() === "" ? 0 : Number($(this).find('input').eq(15).val());
                filaG.p = $(this).find('input').eq(16).val() === "" ? 0 : Number($(this).find('input').eq(16).val());
                filaG.q = $(this).find('input').eq(17).val() === "" ? 0 : Number($(this).find('input').eq(17).val());
                
                if (Object.values(filaG).some(value => value !== null && value !== 0)) {
                    data.push(filaG);
                    totalFilas++;
                }
            }
        });
    }

    document.getElementById('totalFilasGrabar').value = totalFilas;
    document.getElementById('DataGrabar').value = JSON.stringify(data);

    // Verificación en la consola para asegurarnos de que los datos se recojan correctamente
    console.log(totalFilas+"Filas detalle grabar:", JSON.stringify(data));

    if (totalFilas > 100) {
        document.getElementById('grabarForm').submit();
    } else {
        alert('Por favor, asegúrese de que las tablas tengan datos antes de grabar.');
    }
}

</script>







</body>
</html>
