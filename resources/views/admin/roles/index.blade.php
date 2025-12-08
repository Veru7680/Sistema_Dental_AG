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

   @can('create_role')

      <x-slot name="action">
        
      </x-slot>

    @endcan
 @livewire('admin.datatables.role-table')

</x-admin-layout>