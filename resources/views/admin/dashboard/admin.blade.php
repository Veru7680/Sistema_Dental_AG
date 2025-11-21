 <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <x-wire-card>
            <p class="text-sm font-semibold text-gray-500">
                Total Pacientes
            </p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $data['total_patients'] }} 
            </p>
        </x-wire-card>

        <x-wire-card>
            <p class="text-sm font-semibold text-gray-500">
                Total Doctores
            </p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $data['total_doctors'] }} 
            </p>
        </x-wire-card>

        <x-wire-card>
            <p class="text-sm font-semibold text-gray-500">
                Citas para hoy
            </p>
            <p class="mt-2 text-3xl font-bold text-gray-900">
            {{ $data['appointments_today'] }} 
            </p>
        </x-wire-card>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-wire-card>
                <p class="text-lg font-semibold text-gray-500">
                    Usuarios registrados recientemente
                </p>

            <ul class="divide-y divide-gray-200">
                @foreach($data['recent_users'] as $user)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $user->name }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ $user->email }}
                            </p>
                        </div>

                        <span class="text-xs text-gray-500">
                            {{ $user->created_at->diffForHumans() }}

                        </span>

                    </li>

                @endforeach
            </ul>

            </x-wire-card>

        </div>


        <div>
            <x-wire-card>
                <p class="text-lg font-semibold text-gray-900">
                    Acciones rapidas
                </p>

                <div class="mt-space-y-2">
                    <x-wire-button
                    class="w-full mb-3"
                    indigo
                    href="{{ route('admin.doctors.index')}}">
                     Gestionar Doctores
                    </x-wire-button>

                    <x-wire-button
                    class="w-full mb-3"
                    blue
                    href="{{ route('admin.patients.index')}}">
                    Gestionar Usurios
                    </x-wire-button>

                    <x-wire-button
                    class="w-full mb-3"
                    gray
                    href="{{ route('admin.appointments.index')}}">
                    Gestionar Citas
                    </x-wire-button>


                </div>

            </x-wire-card>
        </div>
    </div>
