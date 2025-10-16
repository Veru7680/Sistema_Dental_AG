<x-admin-layout
title=" Doctores | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Doctores',
    ]
    
    ]"  >

  @livewire('admin.datatables.doctor-table')

</x-admin-layout>