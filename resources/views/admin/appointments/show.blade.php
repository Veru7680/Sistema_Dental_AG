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
    ]

    [
        'name'=> 'Detalle'
    ]
    
    ]"  >


</x-admin-layout>