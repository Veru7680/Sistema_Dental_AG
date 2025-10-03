<x-admin-layout
title=" Usuarios | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Usuarios',
    ]
    
    ]"  >

    <x-slot name="action">
      <x-wire-button blue href="{{route('admin.users.create')}}" >
    <i class="fa-solid fa-plus"></i>
    Nuevo
      </x-wire-button>
    </x-slot>


</x-admin-layout>