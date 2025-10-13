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
        'name'=>'Editar',
    ],
    
    ]"  >


    <form action="{{route('admin.patients.update', $patient)}}" method="POST">
        @csrf    
        @method('PUT')

        <x-wire-card class="mb-6">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center space-x-7">
                        <img src="{{$patient->user->profile_photo_url}}"
                            class="h-20 w-20 rounded-full object-cover object-center"
                            alt="{{$patient->user->name}}">
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{$patient->user->name}}
                                </p>
                            </div>
                </div>

                <div class="flex space-x-7 mt-6 lg:mt-0 ">
                    <x-wire-button outline black href="{{route('admin.patients.index')}}">
                        Volver
                    </x-wire-button>


                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check"> </i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card> 

        
           {{-- TABS --}}
        <x-wire-card>
            <x-tabs active="datos-personales" >
                    <x-slot name="header">  
                        <x-tab-link tab="datos-personales">
                                    <i class="fa-solid fa-user me-2"></i>   
                                        Datos Personales
                        </x-tab-link>

                        <x-tab-link tab="antecedentes">
                                    <i class="fa-solid fa-file-lines me-2"></i>    
                                        Antecedentes                     
                        </x-tab-link>
                            
                        <x-tab-link tab="contacto-emergencia">                               
                                    <i class="fa-solid fa-heart me-2 "></i>      
                                    Contactos de emergencia
                                
                        </x-tab-link>

                    </x-slot>
                    {{-- Datos Personales --}}
                    <x-tab-content tab="datos-personales">
                        <x-wire-alert info title="Edicion de Usuario" class="mb-4">                          
                                            <p>Para editar esta informacion, dirigite al 
                                                <a href="{{route('admin.users.edit', $patient->user)}}" 
                                                class="text-indigo-600 hover:underline" target="_blank">
                                                    perfil del usuario
                                                </a> asociado a este paciente 
                                            </p>                                         
                            </x-wire-alert>

                        <div class="grid lg:grid-cols-2 gap-4">
                                        
                                    <div>
                                        <span class="text-gray-500 font-semibold text-sm">
                                            Telefono:
                                        </span>
                                        <span class="text-gray-900 text-sm ml-1">
                                            {{$patient->user->phone}}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <span class="text-gray-500 font-semibold text-sm">
                                            Email:
                                        </span>
                                        <span class="text-gray-900 text-sm ml-1">
                                            {{$patient->user->email}}
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <span class="text-gray-500 font-semibold text-sm">
                                            Direccion:
                                        </span>
                                        <span class="text-gray-900 text-sm ml-1">
                                            {{$patient->user->address}}
                                        </span>                             
                                        </div>
                            </div>
                    </x-tab-content>

                     {{-- Antecedentes --}}
                    <x-tab-content tab="antecedentes">                            
                            <div class="grid lg:grid-cols-2 gap-4">

                                        <div>
                                            <x-wire-textarea
                                            label="Alergias conocidas"
                                            name="allergias">
                                            {{old('allergias', $patient->allergias)}}
                                            </x-wire-textarea>
                                        </div>

                                        <div>
                                            <x-wire-textarea
                                            label="Enfermedades cronicas"
                                            name="chronic_conditions">
                                            {{old('chronic_conditions', $patient->chronic_conditions)}}
                                            </x-wire-textarea>
                                        </div>

                                        <div>
                                            <x-wire-textarea
                                            label="Observaciones"
                                            name="observations">
                                            {{old('observations', $patient->observations)}}
                                            </x-wire-textarea>
                                        </div>

                                        

                            </div>
                    </x-tab-content>

                    {{-- Contacto de Emergencia --}}
                    <x-tab-content tab="contacto-emergencia">                            
                            <div class="space-y-4">
                                        <x-wire-input
                                        label="Nombre del Contacto"
                                        name="emergency_contact_name"
                                        value="{{old('emergency_contact_name', $patient->emergency_contact_name)}}"/>

                                        <x-wire-input
                                        label="Telefono del Contacto"
                                        name="emergency_contact_phone"
                                        value="{{old('emergency_contact_phone', $patient->emergency_contact_phone)}}"/>

                                        <x-wire-input
                                        label="Relacion del Contacto"
                                        name="emergency_contact_relationship"
                                        value="{{old('emergency_contact_relationship', $patient->emergency_contact_relationship)}}"/>

                            </div>
                    </x-tab-content> 

                 </div>     
            </x-tabs>
        </x-wire-card>
    </form>
</x-admin-layout>