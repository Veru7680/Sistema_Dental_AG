    <div class="grid lg:grid-cols-3 gap-6 mb-8">
            <!-- Card de Pacientes -->
        <x-wire-card class="relative overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-center p-4">
                <div class="relative mr-4">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl transform rotate-6 opacity-70"></div>
                    <div class="relative p-3 rounded-xl bg-gradient-to-br from-blue-200 to-indigo-200 shadow-inner">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 1a6 6 0 01-6-6m0 0a6 6 0 00-6-6m6 6V9a6 6 0 00-6-6"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-1">
                        Pacientes
                    </p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $data['total_patients'] ?? 0 }}
                    </p>
                    <div class="flex items-center mt-2 text-xs text-gray-500">
                        <div class="flex items-center bg-blue-50 px-2 py-1 rounded-full">
                            <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Registrados</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-wire-card>

        <!-- Card de Doctores -->
        <x-wire-card class="relative overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-center p-4">
                <div class="relative mr-4">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-100 to-cyan-100 rounded-xl transform rotate-6 opacity-70"></div>
                    <div class="relative p-3 rounded-xl bg-gradient-to-br from-teal-200 to-cyan-200 shadow-inner">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-teal-500 uppercase tracking-wider mb-1">
                        Doctores
                    </p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $data['total_doctors'] ?? 0 }}
                    </p>
                    <div class="flex items-center mt-2 text-xs text-gray-500">
                        <div class="flex items-center bg-teal-50 px-2 py-1 rounded-full">
                            <svg class="w-3 h-3 mr-1 text-teal-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium">Activos</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-wire-card>

        <!-- Card de Citas -->
        <x-wire-card class="relative overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-center p-4">
                <div class="relative mr-4">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl transform rotate-6 opacity-70"></div>
                    <div class="relative p-3 rounded-xl bg-gradient-to-br from-purple-200 to-pink-200 shadow-inner">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" 
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-semibold text-purple-500 uppercase tracking-wider mb-1">
                        Citas Hoy
                    </p>
                    <p class="text-3xl font-bold text-gray-800">
                        {{ $data['appointments_today'] ?? 0 }}
                    </p>
                    <div class="flex items-center mt-2">
                        @if(($data['appointments_today'] ?? 0) > 0)
                        <div class="flex items-center bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-1.5 rounded-full">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-xs font-medium text-green-600">Programadas</span>
                        </div>
                        @else
                        <div class="flex items-center bg-gradient-to-r from-gray-50 to-slate-50 px-3 py-1.5 rounded-full">
                            <div class="w-2 h-2 bg-gray-400 rounded-full mr-2"></div>
                            <span class="text-xs font-medium text-gray-500">Sin citas</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </x-wire-card>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
       <div class="lg:col-span-2">
    <x-wire-card class="p-3" style="background-color: #ccdbfd;">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-bold text-gray-800">
                Últimos 4 usuarios
            </p>
            <span class="text-xs text-gray-500 bg-white px-2 py-0.5 rounded-full">
                {{ $data['recent_users']->count() }} registros
            </span>
        </div>

        <div class="space-y-1.5">
            @foreach($data['recent_users']->take(4) as $user)
                <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-gray-100">
                    <div class="flex items-center min-w-0 flex-1">
                        <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="text-xs font-bold text-blue-600">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="min-w-0 flex-1 pr-2">
                            <p class="text-xs font-semibold text-gray-800 truncate">
                                {{ $user->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-xs font-medium text-gray-700 whitespace-nowrap">
                            {{ $user->created_at->format('d/m/Y') }}
                        </span>
                        <span class="text-xs text-gray-500 block">
                            {{ $user->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </x-wire-card>
</div>

            <div>
                <x-wire-card style="background-color: #ccdbfd;" class="shadow-sm hover:shadow transition-all duration-200">
                    <p class="text-lg font-semibold text-gray-900">
                        Acciones rapidas
                    </p>

                    <div class="mt-space-y-2">
                        <x-wire-button
                            class="w-full mb-3"
                            indigo
                            href="{{ route('admin.doctors.index')}}">
                            <div class="flex items-center justify-center">
                                <span class="mr-2">➕</span>
                                Nuevo Doctor
                            </div>
                        </x-wire-button>

                        <x-wire-button
                            class="w-full mb-3"
                            green
                            href="{{ route('admin.appointments.index') }}">
                            <div class="flex items-center justify-center">
                                <span class="mr-2">📅</span>
                                Nueva Cita
                            </div>
                        </x-wire-button>

                        <x-wire-button
                            class="w-full mb-3"
                            blue
                            href="{{ route('admin.patients.index') }}">
                            <div class="flex items-center justify-center">
                                <span class="mr-2">👤</span>
                                Nuevo Paciente
                            </div>
                        </x-wire-button>
                    </div>

                

                </x-wire-card>
            </div>
    </div>
