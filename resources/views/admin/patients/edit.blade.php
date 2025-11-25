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

                                <p class="text-sm font-semibold text-gray-500">
                                   Ci: {{$patient->user->ci ?? 'N/A'}}
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

                        <x-tab-link tab="historial">                               
                                    <i class="fa-solid fa-heart me-2 "></i>      
                                    Tabla Historial
                                
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

                                    <div class="flex flex-col">
                                        <span class="text-gray-500 font-semibold text-sm">
                                            Estado:
                                        </span>
                                        <x-wire-native-select 
                                   
                                        name="active"
                                        >
                                        <option value="">
                                            Selecciona una Especialidad
                                        </option>
                                            <option value="1" @selected(old('active', $patient->active) == 1)>
                                                Activo
                                            </option>
                                            <option value="0" @selected(old('active', $patient->active) == 0)>
                                                Inactivo
                                            </option>
                                        </x-wire-native-select>
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

                    <x-tab-content tab="historial">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fecha
                                        </th>
                                        <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Médico
                                        </th>
                                        <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Diagnóstico
                                        </th>
                                        <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tratamiento
                                        </th>
                                        <th class="px-4 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Receta
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($previusConsultations as $consultation)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-900">
                                            {{ $consultation->appointment->date->format('d-m-Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">
                                            <div class="font-medium">Dr. {{ $consultation->appointment->doctor->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $consultation->appointment->doctor->specialty ?? 'Sin especialidad' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $consultation->diagnosis ?? 'Sin diagnóstico' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $consultation->treatment ?? 'Sin tratamiento' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-900">
                                            @if($consultation->prescriptions && count($consultation->prescriptions) > 0)
                                                <div class="space-y-2">
                                                    @foreach($consultation->prescriptions as $prescription)
                                                        <div class="bg-blue-50 border border-blue-200 rounded p-2">
                                                            <div class="font-medium text-blue-800 text-xs">
                                                                💊 {{ is_array($prescription) ? $prescription['medicine'] : $prescription->medicine }}
                                                            </div>
                                                            <div class="text-xs text-blue-600 mt-1">
                                                                <strong>Dosis:</strong> {{ is_array($prescription) ? $prescription['dosage'] : $prescription->dosage }}
                                                            </div>
                                                            <div class="text-xs text-blue-600">
                                                                <strong>Instrucciones:</strong> {{ is_array($prescription) ? $prescription['frequency'] : $prescription->frequency }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs">Sin receta médica</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                            No hay consultas anteriores para este paciente
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </x-tab-content>

                 </div>
                 
            </x-tabs>

        </x-wire-card>
    </form>
</x-admin-layout>