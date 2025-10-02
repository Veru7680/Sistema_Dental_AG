<x-admin-layout
title="Clinica AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'prueba',
    ]
    
    ]">
    <x-slot name="action">
       prosicion de boton
    </x-slot>

hola desed admin
</x-admin-layout>