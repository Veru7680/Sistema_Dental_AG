<x-admin-layout
title=" Pacientes | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Pacientes',
        'href'=> route('admin.patients.index'),

    ],

    [
        'name'=>'Nuevo',
    ],
    
    ]"  >

 

</x-admin-layout>