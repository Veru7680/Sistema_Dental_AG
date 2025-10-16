<x-admin-layout
title=" Horarios | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Horarios',
    ]
    
    ]"  >

    @livewire('admin.schedule-manager', [
    'doctor' => $doctor,
    ])



</x-admin-layout>