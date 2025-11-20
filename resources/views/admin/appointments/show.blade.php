<x-admin-layout
title=" Citas | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Citas',
        'href'=> route('admin.appointments.index'),
    ],

    [
        'name'=> 'Detalle'
    ]
    
    ]"  >

    <x-wire-card>
        <x-slot name="title">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Detalle de la Cita
                </h1>
                <p class="text-sm text-gray-500">
                    Fecha: {{ $appointment->date->format('d-m-Y')}} <br>
                </p>
            </div>
        </x-slot>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h2 class="font-semibold text-gray-500 uppercase text-xs mb-2">
                        Paciente
                    </h2>
                    <p class="text-lg font-semibold text-gray-500">
                        {{ $appointment->patient->user->name}}
                    </p>

                    <p class="text-lg text-gray-600">
                        {{ $appointment->patient->user->email}}
                    </p>
                </div>

                <div>
                        <h2 class="font-semibold text-gray-500 uppercase text-xs mb-2">
                        Medico
                    </h2>
                    <p class="text-lg font-semibold text-gray-500">
                        {{ $appointment->doctor->user->name}}
                    </p>

                    <p class="text-lg text-gray-600">
                        {{ $appointment->doctor->specialty->email ?? 'Sin especialidad'}}
                    </p>
                </div>
            </div>

            <hr class="my-4">

                <div class="font-semibold text-gray-800 mb-2">
                    <h3>
                        Diagnostico
                    </h3>

                    <p>
                    {{ $appointment->consultation->diagnosis ?? 'No disponible'}} 
                    </p>
                </div>

            <hr class="my-4">
            <div class="font-semibold text-gray-800 mb-2">
                <h3>
                    Plan de Tratamiento
                </h3>

                <p>
                   {{ $appointment->consultation->treatment ?? 'No disponible'}} 
                </p>
            </div>

            @isset($appointment->consultation->prescriptions)

            <hr class="my-4">
                <div class="font-semibold text-gray-800 mb-2">
                    <h3>
                        Receta medica:
                    </h3>

                    <ul class="space-y-3">
                        @foreach($appointment->consultation->prescriptions as $prescriptions)
                            <li>
                                <p>
                                    <strong>Medicamentos:</strong> {{ $prescriptions['medicine'] }} <br>
                                    <strong>Dosis:</strong> {{ $prescriptions['dosage'] }} <br>
                                    <strong>Intrucciones</strong> {{ $prescriptions['frequency'] }} 
                                </p>
                            </li>
                        @endforeach
                    </ul>
            </div>
            @endisset

            @role('Doctor') 
            <hr class="my-4">
                <div class="font-semibold text-gray-800 mb-2">
                    <h3>
                        Notas del medico :
                    </h3>

                    <p>
                    {{ $appointment->consultation->notes ?? 'No disponible'}} 
                    </p>
                </div>

            @endrole
        
    </x-wire-card>
</x-admin-layout>