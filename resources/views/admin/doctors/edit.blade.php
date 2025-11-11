<x-admin-layout
title=" Doctores | Dental AG" {{-- Aquí cambia el título de la página --}}

:breadcrumbs="[
    [
        'name'=>'Dashboard',
        'href'=> route('admin.dashboard'),
    ],

    [
        'name'=>'Doctores',
        'href'=>route('admin.doctors.index'),
    ],
    [
        'name'=>'Editar'
    ]
    
    ]"  >

 <form action="{{route('admin.doctors.update', $doctor)}}" method="POST">
        @csrf    
        @method('PUT')

        <x-wire-card class="mb-6">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center space-x-7">
                        <img src="{{$doctor->user->profile_photo_url}}"
                            class="h-20 w-20 rounded-full object-cover object-center"
                            alt="{{$doctor->user->name}}">
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{$doctor->user->name}}
                                </p>

                                <p class="text-sm font-semibold text-gray-500">
                                   Ci: {{$doctor->user->ci ?? 'N/A'}}
                                </p>
                            </div>
                </div>

                <div class="flex space-x-7 mt-6 lg:mt-0 ">
                    <x-wire-button outline black href="{{route('admin.doctors.schedules', $doctor)}}">
                        <i class="fa-solid fa-clock"> </i>
                        Horarios
                    </x-wire-button>


                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check"> </i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card> 

        <x-wire-card class="mb-6">
            <div class="space-y-4">

            <x-wire-native-select
                label="Especialidad"
                name="speciality_id"  
                >

                <option value="">
                    Selecciona una Especialidad
                </option>


                    @foreach($specialities as $speciality)
                        <option value="{{ $speciality->id}}" @selected($speciality->id == old('speciality_id', $doctor->speciality_id))>
                            {{ $speciality->name}}
                        </option>

                    @endforeach
            </x-wire-native-select>


            <x-wire-native-select 
                    label="Estado"
                    name="active">
                    <option value="">
                    Selecciona una Especialidad
                </option>
                         <option value="1" @selected(old('active', $doctor->active) == 1)>
                                                Activo
                        </option>
                        <option value="0" @selected(old('active', $doctor->active) == 0)>
                                                Inactivo
                        </option>
            </x-wire-native-select>  


            </div>
        </x-wire-card> 

  
    </form>

</x-admin-layout>