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

    <x-slot name="action">
        <form action="{{ route('admin.appointments.destroy', $appointment)}}" method="POST">
            @csrf
            @method('DELETE')
            <x-wire-button red type="submit">
                Cancelar Cita
            </x-wire-button>
        </form>
    </x-slot>

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
   
   @if($appointment->status->isEditable())
    @livewire('admin.appointment-manager', [
       'appointmentEdit' => $appointment,
        ])

        @else
            <x-wire-card class=" mt-4">
                <p class="">
                    Esta cita no se puede editar por que ya ha sido Completada o Cancelada 
                </p>
            </x-wire-card>  

    @endif

</x-admin-layout>