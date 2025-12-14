<div>
    <x-wire-card class="mb-6">
        <div class="lg:flex lg:justify-between lg:items-center">
            <div class="flex items-center space-x-7">
                <img src="{{$appointment->patient->user->profile_photo_url}}"
                    class="h-20 w-20 rounded-full object-cover object-center"
                    alt="{{$appointment->patient->user->name}}">
                <div>
                    <p class="text-2xl font-bold text-gray-900">
                        {{$appointment->patient->user->name}}
                    </p>
                    <p class="text-sm font-semibold text-gray-500">
                       Ci: {{$appointment->patient->user->ci ?? 'N/A'}}
                    </p>
                </div>
            </div>
        </div>
    </x-wire-card> 
    
    <x-wire-card> 
        <!-- FORMULARIO PRINCIPAL -->
        <form wire:submit.prevent="save">
            <x-tabs active="consulta">
                <x-slot name="header">
                    <x-tab-link tab="usuario">
                        <i class="fa-solid fa-prescription-bottle-medical me-2"></i>Usuario
                    </x-tab-link>

                    <x-tab-link tab="consulta">
                        <i class="fa-solid fa-notes-medical me-2"></i> Consulta
                    </x-tab-link>
                    <x-tab-link tab="receta">
                        <i class="fa-solid fa-prescription-bottle-medical me-2"></i> Receta
                    </x-tab-link>
                    <x-tab-link tab="historial">
                        <i class="fa-solid fa-prescription-bottle-medical me-2"></i> Tabla Historial
                    </x-tab-link>
                </x-slot>
                
                <!-- PESTAÑA USUARIO -->
                <x-tab-content tab="usuario">
                    <div class="space-y-6">
                        
                        <!-- PESTAÑA USUARIO -->
                        <x-tab-content tab="usuario">
                            <div class="space-y-6">
                                
                                <!-- INFORMACIÓN PERSONAL -->
                                <div class="bg-white rounded-lg border p-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fa-solid fa-id-card mr-2 text-blue-600"></i>
                                        Información Personal
                                    </h3>
                                    
                                    @if($appointment->patient && $appointment->patient->user)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Nombre -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                                <div class="p-3 bg-gray-50 rounded border">
                                                    {{ $appointment->patient->user->name }}
                                                </div>
                                            </div>
                                            
                                            <!-- Correo Electrónico -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                                                <div class="p-3 bg-gray-50 rounded border">
                                                    {{ $appointment->patient->user->email }}
                                                </div>
                                            </div>
                                            
                                            <!-- Carnet de Identidad -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Carnet de Identidad</label>
                                                <div class="p-3 bg-gray-50 rounded border">
                                                    {{ $appointment->patient->user->ci ?? 'No registrado' }}
                                                </div>
                                            </div>
                                            
                                            <!-- Teléfono -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                                <div class="p-3 bg-gray-50 rounded border">
                                                    {{ $appointment->patient->user->phone ?? 'No registrado' }}
                                                </div>
                                            </div>
                                            
                                            <!-- Dirección -->
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                                                <div class="p-3 bg-gray-50 rounded border">
                                                    {{ $appointment->patient->user->address ?? 'No registrada' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-500">
                                            <i class="fa-solid fa-user-slash text-3xl mb-3"></i>
                                            <p>No hay información disponible del paciente</p>
                                        </div>
                                    @endif
                                </div>
                                
                            </div>
                        </x-tab-content>
                        
                        <!-- HISTORIAL MÉDICO -->
                        <div class="bg-white rounded-lg border p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fa-solid fa-file-medical mr-2 text-red-600"></i>
                                Historial Médico
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Alergias Conocidas -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alergias Conocidas</label>
                                    <div class="p-3 bg-gray-50 rounded border min-h-[60px]">
                                        {{ $patient->allergias ?? 'Ninguna' }}
                                    </div>
                                </div>
                                
                                <!-- Enfermedades Crónicas -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Enfermedades Crónicas</label>
                                    <div class="p-3 bg-gray-50 rounded border min-h-[60px]">
                                        {{ $patient->chronic_conditions ?? 'Ninguna' }}
                                    </div>
                                </div>
                                
                                <!-- Observaciones -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                                    <div class="p-3 bg-gray-50 rounded border min-h-[80px]">
                                        {{ $patient->observations ?? 'Sin observaciones' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- CONTACTO DE EMERGENCIA -->
                        <div class="bg-white rounded-lg border p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fa-solid fa-phone-volume mr-2 text-purple-600"></i>
                                Contacto de Emergencia
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Nombre del Contacto -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Contacto</label>
                                    <div class="p-3 bg-gray-50 rounded border">
                                        {{ $patient->emergency_contact_name ?? 'Ninguna' }}
                                    </div>
                                </div>
                                
                                <!-- Teléfono del Contacto -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono del Contacto</label>
                                    <div class="p-3 bg-gray-50 rounded border">
                                        {{ $patient->emergency_contact_phone ?? 'Ninguna' }}
                                    </div>
                                </div>
                                
                                <!-- Relación del Contacto -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Relación del Contacto</label>
                                    <div class="p-3 bg-gray-50 rounded border">
                                        {{ $patient->emergency_contact_relationship ?? 'No especificada' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-tab-content>

                <!-- PESTAÑA CONSULTA -->
                <x-tab-content tab="consulta">
                    <div class="space-y-4">
                        <div>
                            @include('odontograma')
                        </div>
                        
                            <!-- DIAGNÓSTICO -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                                <input 
                                    type="text"
                                    wire:model="form.diagnosis"
                                    placeholder="Escriba el diagnóstico..."
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                />
                                @error('form.diagnosis') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                        <!-- TRATAMIENTO -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tratamiento</label>
                            <textarea 
                                wire:model="form.treatment"
                                placeholder="Describa el tratamiento realizado..."
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                rows="3"
                            ></textarea>
                            @error('form.treatment') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>

                        <!-- NOTAS -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                            <textarea 
                                wire:model="form.notes"
                                placeholder="Observaciones, recomendaciones, evolución, próxima cita..."
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                rows="3"
                            ></textarea>
                            @error('form.notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                          <!-- COSTO DE SERVICIO -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Costo de Servicio ($)</label>
                            <input 
                                type="number"
                                wire:model="form.service_cost"
                                placeholder="0.00"
                                min="0"
                                step="0.01"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            />
                            @error('form.service_cost') 
                                <span class="text-red-500 text-sm">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </x-tab-content>

                <!-- PESTAÑA RECETA -->
                <x-tab-content tab="receta">
                    <div class="space-y-4">
                        @forelse($form['prescriptions'] as $index => $prescription)
                        <div class="bg-gray-50 p-4 rounded-lg border lg:flex gap-4 lg:space-y-4 lg:space-y-0" 
                             wire:key="prescription-{{$index}}">
                            <div class="flex-1">
                                <x-wire-input 
                                    label="Medicamento"
                                    placeholder="Ej: Amoxicilina 500mg"
                                    wire:model="form.prescriptions.{{$index}}.medicine"
                                />
                            </div>
                            <div class="lg:w-32">
                                <x-wire-input 
                                    label="Dosis"
                                    placeholder="Ej: 1 capsula"
                                    wire:model="form.prescriptions.{{$index}}.dosage"
                                />
                            </div>
                            <div class="flex-1">
                                <x-wire-input 
                                    label="Frecuencia / Duración"
                                    placeholder="Ej: cada 8 horas por 7 dias"
                                    wire:model="form.prescriptions.{{$index}}.frequency"
                                />
                            </div>
                            <div class="flex-shrink-0 lg:pt-7">
                                <x-wire-mini-button sm red
                                    icon="trash"
                                    wire:click="removePrescriptions({{$index}})" 
                                    spinner="removePrescriptions({{$index}})" />
                            </div>
                        </div> 
                        @empty
                        <div class="text-center text-gray-500 py-6">
                            No hay medicamentos añadidos a la receta.
                        </div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <x-wire-button outline secondary
                            wire:click="addPrescription"
                            spinner="addPrescription">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Añadir Medicamento
                        </x-wire-button>
                    </div>
                </x-tab-content>

                <!-- PESTAÑA HISTORIAL -->
                <x-tab-content tab="historial">
                 <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Médico
                                </th>
                                <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Diagnóstico
                                </th>
                                <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tratamiento
                                </th>
                                <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Receta
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($previusConsultations as $consultation)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <!-- Fecha -->
                                <td class="px-4 py-3 whitespace-nowrap text-gray-900">
                                    {{ $consultation->appointment->date->format('d-m-Y') }}
                                </td>
                                
                                <!-- Médico -->
                                <td class="px-4 py-3 text-gray-900">
                                    <div class="font-medium">Dr. {{ $consultation->appointment->doctor->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $consultation->appointment->doctor->specialty ?? 'Sin especialidad' }}</div>
                                </td>
                                
                                <!-- Diagnóstico -->
                                <td class="px-4 py-3 text-gray-900">
                                    {{ $consultation->diagnosis ?? 'Sin diagnóstico' }}
                                </td>
                                
                                <!-- Plan de Tratamiento -->
                                <td class="px-4 py-3 text-gray-900">
                                    {{ $consultation->treatment ?? 'Sin tratamiento' }}
                                </td>
                                
                                <!-- Prescripción -->
                                <td class="px-4 py-3 text-gray-900">
                                    @if($consultation->prescriptions && count($consultation->prescriptions) > 0)
                                        <div class="space-y-1">
                                            @foreach($consultation->prescriptions as $prescription)
                                                <div class="border-l-2 border-blue-400 pl-2">
                                                    <div class="font-medium text-blue-800 text-xs">
                                                        {{ is_array($prescription) ? $prescription['medicine'] : $prescription->medicine }}
                                                    </div>
                                                    <div class="text-xs text-gray-600">
                                                        Dosis: {{ is_array($prescription) ? $prescription['dosage'] : $prescription->dosage }} - 
                                                        {{ is_array($prescription) ? $prescription['frequency'] : $prescription->frequency }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin receta</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    No hay consultas anteriores para este paciente
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </x-tab-content>
            </x-tabs>

            <!-- BOTÓN GUARDAR - DENTRO DEL FORM -->
            <div class="flex justify-end mt-6">
                <x-wire-button 
                    type="submit"
                    spinner="save"
                    :disabled="!$appointment->status->isEditable()">
                    <i class="fa-solid fa-save mr-2"></i>
                    Guardar Consulta
                </x-wire-button>
            </div>
        </form>
    </x-wire-card> 

    <!-- MODALES (igual que tu código original) ... -->

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
            transition: background 0.2s;
            cursor: pointer;
        }
        
        .cuadro:hover {
            background: #f0f0f0;
        }
        
        .cuadro.seleccionado {
            background: #9370DB !important; /* Morado */
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
        
        .encia.seleccionado {
            background: #9370DB !important; /* Morado */
            border-top: 1px solid #9370DB;
        }
        
        /* POSICIONAMIENTO MOVIDO 3px A LA IZQUIERDA */
        /* Fila 1 */
        .cuadro[data-pos="1"] { top: 15px; left: 1px; width: 13px; height: 11px; }
        .cuadro[data-pos="2"] { top: 15px; left: 14px; width: 13px; height: 11px; }
        .cuadro[data-pos="3"] { top: 15px; left: 27px; width: 13px; height: 11px; }
        /* Fila 2 */
        .cuadro[data-pos="4"] { top: 26px; left: 1px; width: 13px; height: 11px; }
        .cuadro[data-pos="5"] { top: 26px; left: 14px; width: 13px; height: 11px; }
        .cuadro[data-pos="6"] { top: 26px; left: 27px; width: 13px; height: 11px; }
        /* Fila 3 */
        .cuadro[data-pos="7"] { top: 37px; left: 1px; width: 13px; height: 11px; }
        .cuadro[data-pos="8"] { top: 37px; left: 14px; width: 13px; height: 11px; }
        .cuadro[data-pos="9"] { top: 37px; left: 27px; width: 13px; height: 11px; }

        /* Encía (cuadro 10) - en la parte superior */
        .encia[data-pos="10"] { top: 0; left: 0; width: 100%; height: 12px; }
    </style>
    
    <script>
        // Variable para almacenar selecciones del odontograma
        let odontogramaSelecciones = [];
        
        // Inicializar odontograma cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar eventos de clic para los cuadros
            document.querySelectorAll('.cuadro, .encia').forEach(elemento => {
                elemento.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Alternar selección
                    this.classList.toggle('seleccionado');
                    
                    // Obtener datos del elemento
                    const diente = this.closest('.diente');
                    const numeroDiente = diente ? diente.getAttribute('data-diente') : null;
                    const posicion = this.getAttribute('data-pos');
                    const tipo = this.classList.contains('cuadro') ? 'cuadro' : 'encia';
                    const id = `d${numeroDiente}_${tipo}${posicion}`;
                    
                    // Manejar selección/deselección
                    if (this.classList.contains('seleccionado')) {
                        // Agregar a selecciones
                        odontogramaSelecciones.push({
                            id: id,
                            diente: numeroDiente,
                            posicion: posicion,
                            tipo: tipo
                        });
                    } else {
                        // Eliminar de selecciones
                        odontogramaSelecciones = odontogramaSelecciones.filter(s => s.id !== id);
                    }
                    
                    // Actualizar campo Livewire
                    actualizarCampoOdontograma();
                    
                    // Log para debugging
                    console.log('Odontograma seleccionado:', odontogramaSelecciones);
                });
            });
        });
        
        // Función para actualizar el campo Livewire con las selecciones
        function actualizarCampoOdontograma() {
            const input = document.getElementById('odontograma-input');
            if (input) {
                // Convertir a JSON
                const datosJSON = JSON.stringify(odontogramaSelecciones);
                input.value = datosJSON;
                
                // Disparar evento para que Livewire actualice el modelo
                input.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
        
        // También actualizar al enviar el formulario (por seguridad)
        document.addEventListener('livewire:initialized', function() {
            Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
                // Antes de cualquier acción de Livewire, asegurarse de que el odontograma esté actualizado
                actualizarCampoOdontograma();
            });
        });
    </script>
</div>
