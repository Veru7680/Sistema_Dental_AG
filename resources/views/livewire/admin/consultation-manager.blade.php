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

                <div class="lg:flex lg:space-x-3 space-y-5 lg:space-y-0 mt-4 lg:mt-0 ">
                    <x-wire-button class="w-full lg:w-auto" outline gray sm x-on:click="$openModal('historyModal')">
                        <i class="fa-solid fa-notes-medical"></i>
                        Ver Historia
                    </x-wire-button>


                    <x-wire-button class="w-full lg:w-auto" outline gray sm x-on:click="$openModal('previusConsultationsModal')">
                        <i class="fa-solid fa-clock-rotate-left"> </i>
                        Consultas anteriores
                    </x-wire-button>
                </div>
            </div>
    </x-wire-card> 
    
    <x-wire-card> 
        <x-tabs active="consulta" >
            <x-slot name="header">
                <x-tab-link tab="consulta">
                    <i class="fa-solid fa-notes-medical me-2"></i>
                        Consulta
                </x-tab-link>

                <x-tab-link tab="receta">
                    <i class="fa-solid fa-prescription-bottle-medical me-2"></i>
                        Receta
                </x-tab-link>

                <x-tab-link tab="historial">
                    <i class="fa-solid fa-prescription-bottle-medical me-2"></i>
                        Tabla Historial
                </x-tab-link>

            </x-slot>

            <x-tab-content tab="consulta">
    <div class="space-y-4">
        <!-- DIAGNÓSTICO - SELECT -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Diagnóstico (Seleccionar)</label>
            <select wire:model="form.diagnosis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Seleccione un diagnóstico...</option>
                
                <optgroup label="Caries Dental">
                    <option value="Caries clase I">Caries clase I (surcos y fisuras)</option>
                    <option value="Caries clase II">Caries clase II (entre dientes posteriores)</option>
                    <option value="Caries clase III">Caries clase III (entre dientes anteriores)</option>
                    <option value="Caries clase V">Caries clase V (cuello del diente)</option>
                    <option value="Caries múltiples">Caries múltiples</option>
                </optgroup>
                
                <optgroup label="Enfermedades de Encías">
                    <option value="Gingivitis">Gingivitis (encías inflamadas)</option>
                    <option value="Sarro dental">Sarro dental (cálculo)</option>
                    <option value="Recesión gingival">Recesión gingival (encías retraídas)</option>
                </optgroup>
                
                <optgroup label="Problemas del Nervio">
                    <option value="Pulpitis reversible">Pulpitis reversible (dolor que cede)</option>
                    <option value="Pulpitis irreversible">Pulpitis irreversible (dolor persistente)</option>
                    <option value="Absceso dental">Absceso dental (infección con pus)</option>
                </optgroup>
                
                <optgroup label="Problemas Comunes">
                    <option value="Hipersensibilidad dentinaria">Hipersensibilidad dentinaria (sensibilidad)</option>
                    <option value="Bruxismo">Bruxismo (rechinar dientes)</option>
                    <option value="Desgaste dental">Desgaste dental</option>
                    <option value="Halitosis">Halitosis (mal aliento)</option>
                    <option value="Xerostomía">Xerostomía (boca seca)</option>
                    <option value="Pericoronaritis">Pericoronaritis (muela del juicio)</option>
                </optgroup>

                <optgroup label="Traumatismos">
                    <option value="Fractura dental">Fractura dental</option>
                    <option value="Trauma dental">Trauma dental (golpe)</option>
                </optgroup>
            </select>
        </div>

        <!-- DIAGNÓSTICO - CUADRO DE TEXTO MANUAL -->
        <div>
            <input 
                type="text"
                wire:model="form.diagnosis_manual"
                placeholder="O escriba el diagnóstico manualmente aquí..."
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            />
        </div>

     

        <!-- TRATAMIENTO - SELECT -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Tratamiento (Seleccionar)</label>
            <select wire:model="form.treatment" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Seleccione un tratamiento...</option>
                
                <optgroup label="Tratamientos Más Comunes">
                    <option value="Obturación composite">Obturación composite (empaste)</option>
                    <option value="Profilaxis">Profilaxis (limpieza dental)</option>
                    <option value="Endodoncia">Endodoncia (tratamiento de conducto)</option>
                    <option value="Exodoncia simple">Exodoncia simple (extracción)</option>
                    <option value="Aplicación de flúor">Aplicación de flúor</option>
                    <option value="Pulido dental">Pulido dental</option>
                </optgroup>
                
                <optgroup label="Prótesis y Rehabilitación">
                    <option value="Corona dental">Corona dental (funda)</option>
                    <option value="Puente dental">Puente dental</option>
                    <option value="Prótesis removible">Prótesis removible</option>
                    <option value="Implante dental">Implante dental</option>
                </optgroup>

                <optgroup label="Otros Tratamientos">
                    <option value="Placa de descarga">Placa de descarga (bruxismo)</option>
                    <option value="Blanqueamiento dental">Blanqueamiento dental</option>
                    <option value="Curetaje dental">Curetaje dental (limpieza profunda)</option>
                    <option value="Instrucción de higiene oral">Instrucción de higiene oral</option>
                    <option value="Control de evolución">Control de evolución</option>
                    <option value="Prescripción de medicamentos">Prescripción de medicamentos</option>
                </optgroup>
            </select>
        </div>

        <!-- TRATAMIENTO - CUADRO DE TEXTO MANUAL -->
        <div>
            <input 
                type="text"
                wire:model="form.treatment_manual"
                placeholder="O escriba el tratamiento manualmente aquí..."
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            />
        </div>

       

        <!-- NOTAS -->
        <x-wire-textarea
            label="Notas Adicionales"
            placeholder="Observaciones, recomendaciones, evolución, próxima cita..."
            wire:model="form.notes"
        />
    </div>
</x-tab-content>

            <x-tab-content tab="receta">
                <div class="space-y-4">
                    @forelse($form['prescriptions'] as $index => $prescription)

                    <div class="bg-gray-50 p-4 rounded-lg border lg:flex gap-4 lg:space-y-4 lg:space-y-0 " 
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
                        label="Frecuencia /  Duracion"
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

        <div class="flex justify-end mt-6">
            <x-wire-button 
            wire:click="save"
            spinner="save"
            :disabled="!$appointment->status->isEditable()">
            <i class="fa-solid fa-save mr-2"></i>
            Guardar Consulta
            </x-wire-button>
        </div>
    </x-wire-card> 

    <x-wire-modal-card 
        title="Historia Medica del paciente" 
        name="historyModal"
        width="5xl">


        <div class="grid lg:grid-cols-4 gap-6">
            <div>
                <p class="font-medium text-gray-500 mb-1">
                    Alergias:
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $patient->allergias ?? 'No registrado' }}
                </p>
            </div>

            <div>
                <p class="font-medium text-gray-500 mb-1">
                    Enfermedades Cronicas:
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $patient->chronic_conditions ?? 'No registrado' }}
                </p>
            </div>

            <div>
                <p class="font-medium text-gray-500 mb-1">
                    Observaciones:
                </p>

                <p class="font-semibold text-gray-800">
                    {{ $patient->observations ?? 'No registrado' }}
                </p>
            </div>
            
        </div>

        <x-slot name="footer">
            <div class="flex justify-end">
                <a href="{{route('admin.patients.edit', $patient->id)}}"
                class="font-semibold text-blue-600 hover:text-blue-800"
                target="_blank">
                Ver / Editar Historia Medica
                </a>
            </div>

        </x-slot>
       
    </x-wire-modal-card>
    
    <x-wire-modal-card 
        title="Consultas anteriores" 
        name="previusConsultationsModal"
        width="4xl">

        @forelse($previusConsultations as $consultation)
            <a href="{{ route('admin.appointments.show', $consultation->appointment_id) }}" 
                class="block p-5 rounded-lg shadow-md border border-gray-200 hover:border-indigo-400 hover:shadow-indigo-100 transition-all duration-200"
                target="_blank">

                <div class="lg:flex justify-between items-center space-y-2 lg:space-y-0">
                    <div>
                        <p class="font-semibold text-gray-800 flex items-center">
                          <i class="fa solid fa-calendar-days text-gray-500 mr-2"></i>
                            {{ $consultation->appointment->date->format('d/m/Y H:i')}}
                        </p>

                        <p>
                            Atendido por:
                            Dr(a).{{ $consultation->appointment->doctor->user->name }}
                        </p>
                    </div>

                    <div>
                        <x-wire-button class="w-full lg:w-auto">
                            ver detalle
                        </x-wire-button>
                    </div>
                </div>
            </a>
            @empty
            <div class="text-center py-10 rounded-xl border border-dashed">
                <i class="fa-solid fa-inbox text-5xl text-gray-300"></i>

                <p class="mt-4 text-sm font-medium text-gray-500">
                Nose encontraron consultas anteriores para este paciente
                </p>
            </div>
        @endforelse

        <x-slot name="footer">
            <div class="flex justify-end">
                <x-wire-button outline gray sm x-on:click="$closeModal('previusConsultationsModal')">
                    Cerrar
                </x-wire-button>
                
            </div>
        </x-slot>

        
    </x-wire-modal-card >
</div>
