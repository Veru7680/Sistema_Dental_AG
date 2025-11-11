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
        'name'=> 'Editar'
    ]
    
    ]"  >

    <x-wire-card class=" mt-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-lg font-medium">
                    Editando la cita para: 
                    <span class="font-bold text-indigo-700">
                        {{ $appointment->patient->user->name }}
                    </span>
                </p>

                <p class="text-sm text-slate-700">
                    Fecha de la cita: 
                    <span class="font-semibold text-slate-700">
                        {{ $appointment->date->format('d/m/Y') }} a las 
                        {{ $appointment->start_time->format('H:i:s') }} hrs.
                    </span>
                </p>
            </div>

            <div>
                <x-wire-badge 
                flat 
                :color="$appointment->status->color()"
                :label="$appointment->status->label()" />
                 
            </div>

        </div>
    </x-wire-card>       
    @livewire('admin.appointment-manager', [
       'appointmentEdit' => $appointment,
        ])

</x-admin-layout>