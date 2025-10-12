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


                    <x-wire-button type="sumit">
                        <i class="fa-solid fa-check"> </i>
                        Guardar cambios
                    </x-wire-button>
                </div>


            </div>

        </x-wire-card> 
        
           {{-- TABS --}}
        <x-wire-card>
            <div x-data="{tab:'datos-personales',}">

                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                            <li class="me-2">
                                <a href="#" x-on:click="tab='datos-personales'"
                                :class="{
                                'inline-flex items-center justify-center p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group': tab === 'datos-personales',
                                'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' :tab !== 'datos-personales'
                                }">

                                <i class="fa-solid fa-user me-2"></i>   
                                    Datos Personales
                                </a>
                            </li>
                            <li class="me-2">
                                <a href="#" x-on:click="tab='antecedentes'"
                                :class="{
                                'inline-flex items-center justify-center p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group': tab === 'antecedentes',
                                'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' :tab !== 'antecedentes'
                                }">
                                <i class="fa-solid fa-file-lines me-2"></i>   
            
                                    Antecedentes
                                </a>
                            </li>
                           
                            <li class="me-2">
                                <a href="#" x-on:click="tab='contacto-emergencia'"
                                :class="{
                                'inline-flex items-center justify-center p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500 group': tab === 'contacto-emergencia',
                                'inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 group' :tab !== 'contacto-emergencia'
                                }"
                                >
                                <i class="fa-solid fa-heart me-2 "></i>      
                                Contactos de emergencia
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="px-4 mt-4">
                            <div x-show="tab === 'datos-personales'">
                              
                            <x-wire-alert info title="Edicion de Usuario" class="mb-4">
                              
                                    <p>Para editar esta informacion, dirigite al 
                                        <a href="{{route('admin.users.edit', $patient->user)}}" 
                                        class="text-indigo-600 hover:underline" target="_blank">
                                            perfil del usuario
                                        </a> asociado a este paciente 
                                    </p>
                                
                                
                            </x-wire-alert>


                            {{-- Datos personales --}}
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
                            </div>

                        {{-- Antecedentes --}}
                            <div x-show="tab === 'antecedentes'">
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
                            </div>


                           

                        {{-- Contacto de Emergencia --}}
                            <div x-show="tab === 'contacto-emergencia'">
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
                            </div>
                        </div>
            </div>
         </x-wire-card>
    </form>
</x-admin-layout>