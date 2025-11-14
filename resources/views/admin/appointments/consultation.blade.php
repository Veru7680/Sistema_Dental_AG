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
        'name'=> 'Consulta'
    ]
    
    ]"  >

    @livewire('admin.consultation-manager', [
        'appointment' => $appointment,
    ])
   
</x-admin-layout>