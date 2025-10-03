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

    @livewire('admin.datatables.role-table')
    <x-slot name="action">
      <x-wire-button blue href="{{route('admin.roles.create')}}" >
    <i class="fa-solid fa-plus"></i>
    Nuevo
      </x-wire-button>
    </x-slot>


</x-admin-layout>