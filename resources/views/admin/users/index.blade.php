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
 @can('create_user')
    <x-slot name="action">
      <x-wire-button blue href="{{route('admin.users.create')}}" >
    <i class="fa-solid fa-plus"></i>
    Nuevo
      </x-wire-button>
    </x-slot>
  @endcan

    @livewire('admin.datatables.user-table')

</x-admin-layout>