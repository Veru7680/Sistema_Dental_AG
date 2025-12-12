<!-- ODONTOGRAMA -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Odontograma (Seleccionar áreas afectadas)</label>
    
    <!-- BOTONES DE COLORES PARA CONDICIONES -->
    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
        <p class="text-sm font-medium text-gray-700 mb-2">Seleccione una condición:</p>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-12 gap-3" id="botones-colores">
            <!-- Los botones se generan con JavaScript -->
        </div>
                
        <div class="mt-3">
            <button type="button" id="limpiar-color" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                <i class="fa-solid fa-eraser mr-1"></i> Limpiar color
            </button>
            
            <button type="button" id="borrar-todo" class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 ml-2">
                <i class="fa-solid fa-trash mr-1"></i> Borrar todo
            </button>
            
            <button type="button" id="limpiar-diagnostico" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 ml-2">
                <i class="fa-solid fa-broom mr-1"></i> Limpiar diagnóstico
            </button>
            
            <div id="color-actual" class="inline-block ml-3 text-sm">
                <span class="font-medium">Color: </span>
                <span id="color-actual-text" class="px-2 py-1 rounded">Ninguno</span>
            </div>
        </div>
    </div>
    
    <!-- EL ODONTOGRAMA -->
    <div id="odontograma-completo" style="display: flex; flex-direction: column; gap: 15px; padding: 20px; background: #f5f5f5; border-radius: 8px;">
        <!-- Fila 1: Maxilar Superior -->
        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">Maxilar Superior</div>
        <div id="maxilar-superior" style="display: flex; justify-content: center; gap: 5px; flex-wrap: wrap;">
            <!-- Dientes 1-16 se generan con JavaScript -->
        </div>
        
        <!-- Fila 2: Maxilar Inferior -->
        <div style="text-align: center; font-weight: bold; margin: 15px 0 5px;">Maxilar Inferior</div>
        <div id="maxilar-inferior" style="display: flex; justify-content: center; gap: 5px; flex-wrap: wrap;">
            <!-- Dientes 17-32 se generan con JavaScript -->
        </div>
    </div>
    
    <input type="hidden" wire:model="form.odontograma_data" id="odontograma-input">
    <p class="text-sm text-gray-500 mt-2">1. Seleccione un color 2. Haga clic en el área dental correspondiente</p>
</div>

<style>
    .diente {
        width: 40px;
        height: 50px;
        background: white;
        border: 1px solid #999;
        border-radius: 4px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        margin: 2px;
    }
    
    .num-diente {
        position: absolute;
        top: 1px;
        left: 2px;
        font-size: 9px;
        font-weight: bold;
        color: #333;
        z-index: 2;
    }
    
    .cuadro {
        position: absolute;
        border: 1px solid #ddd;
        background: white;
        transition: background 0.2s, transform 0.1s;
        cursor: pointer;
    }
    
    .cuadro:hover {
        transform: scale(1.05);
        z-index: 10;
    }
    
    .encia {
        position: absolute;
        background: #f8d7da;
        border-top: 1px solid #dc3545;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .encia:hover {
        background: #f5c2c7;
    }
    
    /* POSICIONAMIENTO MOVIDO 3px A LA IZQUIERDA */
    .cuadro[data-pos="1"] { top: 15px; left: 1px; width: 13px; height: 11px; }
    .cuadro[data-pos="2"] { top: 15px; left: 14px; width: 13px; height: 11px; }
    .cuadro[data-pos="3"] { top: 15px; left: 27px; width: 13px; height: 11px; }
    .cuadro[data-pos="4"] { top: 26px; left: 1px; width: 13px; height: 11px; }
    .cuadro[data-pos="5"] { top: 26px; left: 14px; width: 13px; height: 11px; }
    .cuadro[data-pos="6"] { top: 26px; left: 27px; width: 13px; height: 11px; }
    .cuadro[data-pos="7"] { top: 37px; left: 1px; width: 13px; height: 11px; }
    .cuadro[data-pos="8"] { top: 37px; left: 14px; width: 13px; height: 11px; }
    .cuadro[data-pos="9"] { top: 37px; left: 27px; width: 13px; height: 11px; }
    .encia[data-pos="10"] { top: 0; left: 0; width: 100%; height: 12px; }
    
    .odontograma-color-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 3px 2px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 10px;
    }
    
    .odontograma-color-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .odontograma-color-btn.active {
        border: 2px solid #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
</style>

<script>
    // Datos de configuración
    const coloresOdontograma = {
        'CAR': { nombre: 'Caries', color: '#000000', texto: 'blanco', abrev: 'Caries' },
        'ROT': { nombre: 'Fractura', color: '#8B4513', texto: 'blanco', abrev: 'Fractura' },
        'DES': { nombre: 'Desgaste', color: '#FFA500', texto: 'negro', abrev: 'Desgaste' },
        'ENC': { nombre: 'Encía', color: '#FF0000', texto: 'blanco', abrev: 'Encía' },
        'SAR': { nombre: 'Sarro', color: '#FFFF00', texto: 'negro', abrev: 'Sarro' },
        'DOL': { nombre: 'Dolor', color: '#9370DB', texto: 'blanco', abrev: 'Dolor' },
        'EMP': { nombre: 'Empaste', color: '#0000FF', texto: 'blanco', abrev: 'Empaste' },
        'COR': { nombre: 'Corona', color: '#00FF00', texto: 'negro', abrev: 'Corona' },
        'END': { nombre: 'Endodoncia', color: '#FF69B4', texto: 'blanco', abrev: 'Endodoncia' },
        'TOR': { nombre: 'Torcido', color: '#808080', texto: 'blanco', abrev: 'Torcido' },
        'EXT': { nombre: 'Extraído', color: 'striped', texto: 'negro', abrev: 'Extraído' }
    };

    // POSICIONES EN EL ORDEN QUE PEDISTE
    const posicionesOdontograma = {
        '5': 'central',
        '6': 'media derecha', 
        '4': 'media izquierda',
        '1': 'superior izquierda',
        '2': 'superior central',
        '3': 'superior derecha',
        '7': 'inferior izquierda',
        '8': 'inferior central',
        '9': 'inferior derecha',
        '10': 'encía'
    };

    // Variables globales
    let odontogramaSelecciones = @json($form['odontograma_data'] ?? []);
    let colorActual = null;
    let codigoColorActual = null;

    // Función para crear botones de colores
    function crearBotonesColores() {
        const contenedor = document.getElementById('botones-colores');
        const botones = [
            { codigo: 'CAR', color: '#000000', nombre: 'Caries', textoColor: 'white', desc: '(Negro)' },
            { codigo: 'ROT', color: '#8B4513', nombre: 'Fractura', textoColor: 'white', desc: '(Marrón)' },
            { codigo: 'DES', color: '#FFA500', nombre: 'Desgaste', textoColor: 'black', desc: '(Naranja)' },
            { codigo: 'ENC', color: '#FF0000', nombre: 'Encía', textoColor: 'white', desc: '(Rojo)' },
            { codigo: 'SAR', color: '#FFFF00', nombre: 'Sarro', textoColor: 'black', desc: '(Amarillo)' },
            { codigo: 'DOL', color: '#9370DB', nombre: 'Dolor', textoColor: 'white', desc: '(Morado)' },
            { codigo: 'END', color: '#FF69B4', nombre: 'Endodoncia', textoColor: 'white', desc: '(Rosa)' },
            { codigo: 'TOR', color: '#808080', nombre: 'Torcido', textoColor: 'white', desc: '(Gris)' },
            { codigo: 'EXT', codigoEspecial: 'striped', nombre: 'Extraído', textoColor: 'black', desc: '(Rayas negras)' }
        ];

        botones.forEach(boton => {
            const div = document.createElement('div');
            div.className = 'flex flex-col items-center';
            
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'odontograma-color-btn w-full h-12';
            button.setAttribute('data-color', boton.codigo);
            button.setAttribute('data-color-code', boton.codigoEspecial || boton.color);
            
            if (boton.codigo === 'EXT') {
                button.style.background = 'repeating-linear-gradient(45deg, #000000, #000000 2px, transparent 2px, transparent 4px)';
            } else {
                button.style.backgroundColor = boton.color;
            }
            button.style.color = boton.textoColor;
            
            const circle = document.createElement('div');
            circle.className = 'w-6 h-6 rounded-full mx-auto border';
            circle.style.borderColor = boton.textoColor === 'white' ? 'white' : '#d1d5db';
            
            button.appendChild(circle);
            div.appendChild(button);
            
            const nombre = document.createElement('span');
            nombre.className = 'text-xs mt-1 text-center font-medium';
            nombre.textContent = boton.nombre;
            div.appendChild(nombre);
            
            const desc = document.createElement('span');
            desc.className = 'text-xs text-gray-600 text-center';
            desc.textContent = boton.desc;
            div.appendChild(desc);
            
            contenedor.appendChild(div);
        });
    }

    // Función para crear un diente
    function crearDiente(numero) {
        const diente = document.createElement('div');
        diente.className = 'diente';
        diente.setAttribute('data-diente', numero);
        
        const num = document.createElement('div');
        num.className = 'num-diente';
        num.textContent = numero;
        diente.appendChild(num);
        
        for (let i = 1; i <= 9; i++) {
            const cuadro = document.createElement('div');
            cuadro.className = 'cuadro';
            cuadro.setAttribute('data-pos', i);
            diente.appendChild(cuadro);
        }
        
        const encia = document.createElement('div');
        encia.className = 'encia';
        encia.setAttribute('data-pos', '10');
        diente.appendChild(encia);
        
        return diente;
    }

    // Función para generar el odontograma
    function generarOdontograma() {
        const maxilarSuperior = document.getElementById('maxilar-superior');
        const maxilarInferior = document.getElementById('maxilar-inferior');
        
        for (let i = 1; i <= 16; i++) {
            maxilarSuperior.appendChild(crearDiente(i));
        }
        
        for (let i = 17; i <= 32; i++) {
            maxilarInferior.appendChild(crearDiente(i));
        }
    }

    // FUNCIÓN NUEVA - Formato agrupado por diente
function generarDiagnosticoTexto(selecciones) {
    if (!selecciones || selecciones.length === 0) {
        return '';
    }
    
    // Agrupar por diente y luego por patología
    const diagnosticoPorDiente = {};
    
    selecciones.forEach(sel => {
        const diente = sel.diente;
        const patologia = coloresOdontograma[sel.colorNombre]?.abrev || coloresOdontograma[sel.colorNombre]?.nombre || 'Condición';
        const posicion = posicionesOdontograma[sel.posicion] || `pos ${sel.posicion}`;
        
        if (!diagnosticoPorDiente[diente]) {
            diagnosticoPorDiente[diente] = {};
        }
        
        if (!diagnosticoPorDiente[diente][patologia]) {
            diagnosticoPorDiente[diente][patologia] = [];
        }
        
        diagnosticoPorDiente[diente][patologia].push(posicion);
    });
    
    const lineasDiagnostico = [''];
    
    // Ordenar dientes numéricamente
    const dientesOrdenados = Object.keys(diagnosticoPorDiente).sort((a, b) => parseInt(a) - parseInt(b));
    
    dientesOrdenados.forEach(dienteNum => {
        const patologias = diagnosticoPorDiente[dienteNum];
        const patologiasTexto = [];
        
        // Para cada patología en este diente
        for (const [patologia, posiciones] of Object.entries(patologias)) {
            // Ordenar posiciones en el orden específico
            posiciones.sort((a, b) => {
                const ordenPosiciones = ['central', 'media derecha', 'media izquierda', 'superior izquierda', 
                                       'superior central', 'superior derecha', 'inferior izquierda', 
                                       'inferior central', 'inferior derecha', 'encía'];
                return ordenPosiciones.indexOf(a) - ordenPosiciones.indexOf(b);
            });
            
            const posicionesTexto = posiciones.join(', ');
            patologiasTexto.push(`${patologia} (${posicionesTexto})`);
        }
        
        lineasDiagnostico.push(`D. ${dienteNum}: ${patologiasTexto.join(', ')}`);
    });
    
    return lineasDiagnostico.join('\n');
}

    // Función para actualizar el campo de diagnóstico
    function actualizarCampoDiagnostico() {
        const diagnosticoTexto = generarDiagnosticoTexto(odontogramaSelecciones);
        
        const inputDiagnostico = document.querySelector('input[wire\\:model="form.diagnosis"], textarea[wire\\:model="form.diagnosis"]');
        
        if (inputDiagnostico) {
            inputDiagnostico.value = diagnosticoTexto;
            inputDiagnostico.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    // Función para aplicar color a un elemento
    function aplicarColorElemento(elemento, colorCodigo, colorNombre) {
        if (colorCodigo === 'striped') {
            elemento.style.background = 'repeating-linear-gradient(45deg, #000000, #000000 2px, transparent 2px, transparent 4px)';
            elemento.style.border = '1px solid #000000';
        } else {
            elemento.style.background = colorCodigo;
            elemento.style.border = `1px solid ${colorCodigo}`;
        }
    }

    // Función para restaurar selecciones previas
    function restaurarSeleccionesPrevias() {
        if (odontogramaSelecciones && Array.isArray(odontogramaSelecciones)) {
            odontogramaSelecciones.forEach(sel => {
                const selector = `.diente[data-diente="${sel.diente}"] .${sel.tipo}[data-pos="${sel.posicion}"]`;
                const elemento = document.querySelector(selector);
                if (elemento) {
                    aplicarColorElemento(elemento, sel.colorCodigo, sel.colorNombre);
                }
            });
        }
    }

    // Función para actualizar campo Livewire
    function actualizarCampoOdontograma() {
        const input = document.getElementById('odontograma-input');
        if (input) {
            const datosJSON = JSON.stringify(odontogramaSelecciones);
            input.value = datosJSON;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    // Función para configurar eventos de los cuadros
    function configurarEventosCuadros() {
        document.addEventListener('click', function(e) {
            const cuadro = e.target.closest('.cuadro, .encia');
            if (!cuadro) return;
            
            // Doble clic para borrar
            if (e.detail === 2) {
                const diente = cuadro.closest('.diente');
                const numeroDiente = diente.getAttribute('data-diente');
                const posicion = cuadro.getAttribute('data-pos');
                const tipo = cuadro.classList.contains('cuadro') ? 'cuadro' : 'encia';
                const id = `d${numeroDiente}_${tipo}${posicion}`;
                
                // Limpiar color visualmente
                cuadro.style.background = tipo === 'cuadro' ? 'white' : '#f8d7da';
                cuadro.style.backgroundImage = 'none';
                cuadro.style.border = tipo === 'cuadro' ? '1px solid #ddd' : '1px solid #dc3545';
                
                // Eliminar de las selecciones
                odontogramaSelecciones = odontogramaSelecciones.filter(s => s.id !== id);
                
                // Actualizar campos
                actualizarCampoOdontograma();
                actualizarCampoDiagnostico();
                
                return;
            }
            
            // Clic simple para aplicar color
            if (!colorActual) {
                alert('Por favor, seleccione un color primero');
                return;
            }
            
            const diente = cuadro.closest('.diente');
            const numeroDiente = diente.getAttribute('data-diente');
            const posicion = cuadro.getAttribute('data-pos');
            const tipo = cuadro.classList.contains('cuadro') ? 'cuadro' : 'encia';
            const id = `d${numeroDiente}_${tipo}${posicion}`;
            
            // Buscar si ya existe
            const indiceExistente = odontogramaSelecciones.findIndex(s => s.id === id);
            
            if (indiceExistente !== -1) {
                // Actualizar existente
                odontogramaSelecciones[indiceExistente].colorCodigo = codigoColorActual;
                odontogramaSelecciones[indiceExistente].colorNombre = colorActual;
            } else {
                // Crear nuevo
                odontogramaSelecciones.push({
                    id: id,
                    diente: numeroDiente,
                    posicion: posicion,
                    tipo: tipo,
                    colorCodigo: codigoColorActual,
                    colorNombre: colorActual,
                    descripcion: coloresOdontograma[colorActual]?.nombre || 'Desconocido',
                    timestamp: new Date().toISOString()
                });
            }
            
            // Aplicar color
            aplicarColorElemento(cuadro, codigoColorActual, colorActual);
            
            // Actualizar campos
            actualizarCampoOdontograma();
            actualizarCampoDiagnostico();
        });
    }

    // Función para actualizar display del color seleccionado
    function actualizarColorSeleccionado() {
        const colorActualText = document.getElementById('color-actual-text');
        
        if (colorActual && coloresOdontograma[colorActual]) {
            const infoColor = coloresOdontograma[colorActual];
            colorActualText.textContent = infoColor.nombre;
            
            if (codigoColorActual === 'striped') {
                colorActualText.style.background = 'repeating-linear-gradient(45deg, #000000, #000000 2px, transparent 2px, transparent 4px)';
                colorActualText.style.color = 'black';
            } else {
                colorActualText.style.background = infoColor.color;
                colorActualText.style.color = infoColor.texto;
            }
        } else {
            colorActualText.textContent = 'Ninguno';
            colorActualText.style.background = '#f3f4f6';
            colorActualText.style.color = '#6b7280';
        }
    }

    // Función para borrar todo
    function borrarTodo() {
        if (!confirm('¿Está seguro de borrar todas las selecciones del odontograma?')) return;
        
        // Limpiar visualmente todos los cuadros
        document.querySelectorAll('.cuadro, .encia').forEach(elemento => {
            const tipo = elemento.classList.contains('cuadro') ? 'cuadro' : 'encia';
            elemento.style.background = tipo === 'cuadro' ? 'white' : '#f8d7da';
            elemento.style.backgroundImage = 'none';
            elemento.style.border = tipo === 'cuadro' ? '1px solid #ddd' : '1px solid #dc3545';
        });
        
        // Limpiar selecciones
        odontogramaSelecciones = [];
        
        // Limpiar color seleccionado
        colorActual = null;
        codigoColorActual = null;
        actualizarColorSeleccionado();
        
        // Quitar clase active de botones
        document.querySelectorAll('.odontograma-color-btn').forEach(b => {
            b.classList.remove('active');
        });
        
        // Actualizar campos
        actualizarCampoOdontograma();
        actualizarCampoDiagnostico();
    }

    // NUEVA FUNCIÓN: Limpiar solo el diagnóstico
    function limpiarDiagnostico() {
        if (!confirm('¿Está seguro de limpiar solo el campo de diagnóstico?\n\nLas selecciones del odontograma se mantendrán.')) return;
        
        const inputDiagnostico = document.querySelector('input[wire\\:model="form.diagnosis"], textarea[wire\\:model="form.diagnosis"]');
        if (inputDiagnostico) {
            inputDiagnostico.value = '';
            inputDiagnostico.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Crear botones de colores
        crearBotonesColores();
        
        // 2. Generar odontograma
        generarOdontograma();
        
        // 3. Configurar eventos de botones de colores
        document.addEventListener('click', function(e) {
            const boton = e.target.closest('.odontograma-color-btn');
            if (!boton) return;
            
            // Quitar active de todos
            document.querySelectorAll('.odontograma-color-btn').forEach(b => {
                b.classList.remove('active');
            });
            
            // Agregar active al seleccionado
            boton.classList.add('active');
            
            // Establecer color actual
            colorActual = boton.getAttribute('data-color');
            codigoColorActual = boton.getAttribute('data-color-code');
            
            // Actualizar display
            actualizarColorSeleccionado();
        });
        
        // 4. Configurar botón limpiar color
        document.getElementById('limpiar-color')?.addEventListener('click', function() {
            colorActual = null;
            codigoColorActual = null;
            actualizarColorSeleccionado();
            document.querySelectorAll('.odontograma-color-btn').forEach(b => {
                b.classList.remove('active');
            });
        });
        
        // 5. Configurar botón borrar todo
        document.getElementById('borrar-todo')?.addEventListener('click', borrarTodo);
        
        // 6. Configurar botón limpiar diagnóstico
        document.getElementById('limpiar-diagnostico')?.addEventListener('click', limpiarDiagnostico);
        
        // 7. Configurar eventos de cuadros
        configurarEventosCuadros();
        
        // 8. Restaurar selecciones previas
        restaurarSeleccionesPrevias();
        
        // 9. Actualizar diagnóstico inicial
        if (odontogramaSelecciones && odontogramaSelecciones.length > 0) {
            setTimeout(actualizarCampoDiagnostico, 100);
        }
    });
</script>