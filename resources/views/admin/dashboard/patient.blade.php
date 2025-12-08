<div>
    <!-- BIENVENIDA CENTRADA -->
    <x-wire-card class="lg:col-span-2 border border-gray-100 shadow-sm">
        <div class="text-center px-4 py-4 relative overflow-hidden rounded-lg">
            <!-- Fondo con imagen -->
            <div class="absolute inset-0 z-0">
                <img 
                    src="{{ asset('img/fondo.jpg') }}" 
                    alt="Fondo"
                    class="w-full h-full object-cover"
                >
                <!-- Overlay claro para texto negro -->
                <div class="absolute inset-0 bg-gradient-to-r from-white/70 to-white/60"></div>
            </div>
            
            <!-- Contenido (encima del fondo) -->
            <div class="relative z-10">
                <!-- Foto de perfil o inicial -->
                <div class="flex justify-center mb-3">
                    @if(auth()->user()->profile_photo_path)
                        <!-- Si tiene foto de perfil -->
                        <img 
                            src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" 
                            alt="{{ auth()->user()->name }}"
                            class="w-12 h-12 rounded-full border-2 border-white shadow-sm object-cover"
                        >
                    @else
                        <!-- Si no tiene foto, mostrar inicial -->
                        <div class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-100 to-blue-100 
                                    border-2 border-white shadow-sm flex items-center justify-center">
                            <span class="text-lg font-bold text-purple-700">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Saludo con nombre en NEGRO -->
                <p class="text-xl font-bold text-gray-900">
                    👋 Hola, {{ auth()->user()->name }}
                </p>
                
                <p class="mt-1 text-gray-700 text-sm">
                    Aquí está el resumen de tus citas médicas
                </p>

                <!-- Botón MORADO -->
                <div class="flex justify-center mt-4">
                    <x-wire-button 
                        href="{{ route('admin.appointments.create') }}" 
                        class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 
                            hover:from-purple-700 hover:to-indigo-700 text-white 
                            shadow-md hover:shadow-lg transition-all duration-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="text-sm font-medium">Agendar Nueva Cita</span>
                        </div>
            </x-wire-button>
        </div>
    </div>
</div>
   
    </x-wire-card>

        <!-- SECCIÓN COMPACTA - Todo en una pantalla -->
        <div class="grid lg:grid-cols-2 gap-4 mt-4">
            <!-- CITAS PARA HOY - Compacto -->
            <x-wire-card class="border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-gray-800 text-sm flex items-center">
                        <span class="mr-2">📋</span> Citas para Hoy
                    </p>
                    @php
                        $appointmentsToday = \App\Models\Appointment::with(['doctor.user'])
                            ->where('patient_id', auth()->user()->patient->id)
                            ->whereDate('date', today())
                            ->orderBy('start_time')
                            ->get();
                    @endphp
                    @if($appointmentsToday->count() > 0)
                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $appointmentsToday->count() }}
                        </span>
                    @endif
                </div>

                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    @forelse($appointmentsToday as $appointment)
                        <div class="bg-gray-50 rounded-lg p-2 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-xs font-bold text-blue-600">
                                            {{ strtoupper(substr($appointment->doctor->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-800">
                                            {{ $appointment->doctor->user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $appointment->start_time->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.appointments.show', $appointment) }}" 
                                class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                    Ver
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs text-center py-2">
                            No tienes citas para hoy
                        </p>
                    @endforelse
                </div>
            </x-wire-card>

            <!-- PRÓXIMAS CITAS - Compacto -->
            <x-wire-card class="border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <p class="font-semibold text-gray-800 text-sm flex items-center">
                        <span class="mr-2">📅</span> Próximas Citas
                    </p>
                    @php
                        $upcomingAppointments = \App\Models\Appointment::with(['doctor.user'])
                            ->where('patient_id', auth()->user()->patient->id)
                            ->whereDate('date', '>', today())
                            ->orderBy('date')
                            ->orderBy('start_time')
                            ->take(3)
                            ->get();
                    @endphp
                    @if($upcomingAppointments->count() > 0)
                        <a href="#" class="text-xs text-blue-600 hover:text-blue-800">
                            Ver todas
                        </a>
                    @endif
                </div>

                <div class="space-y-2">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="bg-gray-50 rounded-lg p-2 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-medium text-gray-800">
                                        {{ $appointment->doctor->user->name }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500">
                                            {{ $appointment->date->format('d/m') }}
                                        </span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">
                                            {{ $appointment->start_time->format('h:i A') }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">
                                    Próxima
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs text-center py-2">
                            No tienes citas futuras
                        </p>
                    @endforelse
                </div>
            </x-wire-card>
        </div>

    <!-- HISTORIAL DE CITAS - Compacto -->
    <x-wire-card class="border border-gray-200 mt-4">
        <div class="flex items-center justify-between mb-3">
            <p class="font-semibold text-gray-800 text-sm flex items-center">
                <span class="mr-2">📊</span> Historial Reciente
            </p>
            @php
                $appointmentHistory = \App\Models\Appointment::with(['doctor.user'])
                    ->where('patient_id', auth()->user()->patient->id)
                    ->whereIn('status', [2, 3])
                    ->orderBy('date', 'desc')
                    ->take(5)
                    ->get();
            @endphp
            @if($appointmentHistory->count() > 0)
                <a href="#" class="text-xs text-gray-600 hover:text-gray-800">
                    Ver todo
                </a>
            @endif
        </div>

        @if($appointmentHistory->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-2 font-medium text-gray-600">Fecha</th>
                            <th class="text-left py-2 font-medium text-gray-600">Doctor</th>
                            <th class="text-left py-2 font-medium text-gray-600">Estado</th>
                            <th class="text-left py-2 font-medium text-gray-600"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointmentHistory as $appointment)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-2">
                                    {{ $appointment->date->format('d/m/y') }}
                                </td>
                                <td class="py-2">
                                    <div class="flex items-center gap-1">
                                        <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-xs text-gray-600">
                                                {{ strtoupper(substr($appointment->doctor->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="truncate max-w-[100px]">
                                            {{ $appointment->doctor->user->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-2">
                                    @if($appointment->status == 2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">
                                            ✅
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">
                                            ❌
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('admin.appointments.show', $appointment) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-xs">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-400 text-xs text-center py-4">
                Aún no tienes historial de consultas
            </p>
        @endif
    </x-wire-card>
</div>