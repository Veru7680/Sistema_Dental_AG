<x-admin-layout
title=" Doctores | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Doctores',
        'href'=>route('admin.doctors.index');
    ],
    [
        'name'=>'Editar'
    ]
    
    ]"  >