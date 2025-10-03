<x-admin-layout
title=" Usuarios | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Usuarios',
        'href'=> route('admin.users.index'),
    ],
     [
        'name'=>'Editar',
        'href'=> route('admin.users.index'),
    ]
    
    ]"  >


</x-admin-layout>