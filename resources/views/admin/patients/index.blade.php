<x-admin-layout
title=" Pacientes | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Pacientes',
    ]
    
    ]"  >

  @livewire('admin.datatables.patient-table')

</x-admin-layout>