<x-admin-layout
title=" Roles | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Roles',
    ]
    
    ]"  >
    <x-slot name="action">
       prosicion de boton
    </x-slot>


</x-admin-layout>