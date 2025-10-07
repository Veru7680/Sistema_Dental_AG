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

    <x-wire-card>
        <form action="{{ route('admin.users.update', $user) }}" method="POST" >
            @csrf 
            @method('PUT')

            <div class="space-y-4">

            <div class="grid lg:grid-cols-2 gap-4">

             <x-wire-input
                name="name"
                label="Nombre"
                required
                :value="old('name', $user->name)"
                placeholder="Ingrese el nombre del usuario"
            />

            <x-wire-input
                name="email"
                label="Correo Electronico"
                type="email"
                required
                :value="old('email', $user->email)"
                placeholder="Ingrese el correo electronico de usuario"
            />

            <x-wire-input
                name="password"
                label="Contraseña"
                type="password"
                placeholder="Ingrese la contraseña del usuario"
            />
            
            <x-wire-input
                name="password_confirmation"
                label="Confirmar Contraseña"
                type="password"
                placeholder="Confirmar la contraseña del usuario"
            />

            <x-wire-input
                name="ci"
                label="Carnet Identidad"
                required
                :value="old('ci', $user->ci)"
                placeholder="Ingrese el CI del usuario"
            />

            <x-wire-input
                name="phone"
                label="Telefono"
                required
                :value="old('phone',  $user->phone)"
                placeholder="Ingrese el telefono del usuario"
            />
            </div>

            <x-wire-input
                name="address"
                label="Direccion"
                required
                :value="old('address',  $user->address)"
                placeholder="Ingrese la direccion del Domicilio"
            />


            <x-wire-native-select 
            label="Rol"
            name="role_id">

                <option value="">
                Seleccione un rol
                </option>

                @foreach($roles as $role)
                <option value="{{ $role->id }}" 
                @selected(old('role_id') == $role->id)>
                    {{ $role->name }}
                </option>
                 @endforeach
            </x-wire-native-select>
            <div class="flex justify-end">
                <x-wire-button type="submit" blue>
                    Actualizar
                </x-wire-button>
            </div>

            </div>

            

        </form>
    </x-wire-card>   

</x-admin-layout>