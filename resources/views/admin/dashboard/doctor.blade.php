<div>
    <!-- ENCABEZADO -->
    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        <x-wire-card class="lg:col-span-2">
            <p class="text-2xl font-bold text-gray-800">
                ! Buen dia, Dr(a). {{ auth()->user()->name }} !
            </p>
            <p class="mt-1 text-gray-600">
                Aquí está el resumen de su jornada.
            </p>
        </x-wire-card>

        <x-wire-card class="bg-gradient-to-r from-blue-50 to-indigo-50">
            <p class="text-sm font-semibold text-gray-600">
                📊 Resumen Rápido
            </p>
            <div class="mt-2 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Citas Hoy</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $data['appointments_today_count'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Citas Semana</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $data['appointments_week_count'] ?? 0 }}</p>
                </div>
            </div>
        </x-wire-card>
    </div>

    <!-- PRIMERA FILA: PRÓXIMA CITA + AGENDA HOY + CITAS COMPLETADAS -->
    <div class="grid lg:grid-cols-4 gap-4 mb-8">
        <!-- PRÓXIMA CITA - MÁS PEQUEÑA -->
       

        <!-- AGENDA PARA HOY - MÁS PEQUEÑA -->
        <x-wire-card class="bg-gradient-to-r from-orange-50 to-amber-50 lg:col-span-2">
            <p class="text-md font-semibold text-gray-800 flex items-center mb-2">
                <span class="mr-2">📋</span> Agenda para Hoy
                @php
                    $todayAppointments = $data['appointments_today']
                        ->filter(function($appointment) {
                            return $appointment->date->isToday();
                        })
                        ->sortBy('start_time');
                @endphp
                @if($todayAppointments->count() > 0)
                    <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $todayAppointments->count() }}
                    </span>
                @endif
            </p>

            <div class="space-y-2 max-h-48 overflow-y-auto">
                @forelse($todayAppointments as $appointment)
                    <div class="bg-white border border-orange-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $appointment->patient->user->name }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1">
                                    🕐 {{ $appointment->start_time->format('h:i A') }}
                                </p>
                                <p class="text-xs mt-1">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                        ⏰ Programado
                                    </span>
                                </p>
                            </div>
                            <a href="{{ route('admin.appointments.consultation', $appointment) }}" 
                               class="text-xs bg-orange-500 text-white px-2 py-1 rounded hover:bg-orange-600 transition-colors">
                                Gestionar
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-2 text-sm">
                        No tiene citas para hoy
                    </p>
                @endforelse
            </div>
        </x-wire-card>

        <!-- CITAS COMPLETADAS HOY - MÁS PEQUEÑA -->
        <x-wire-card class="bg-gradient-to-r from-green-50 to-emerald-50">
            <p class="text-sm font-semibold text-gray-800 flex items-center mb-1">
                <span class="mr-1">✅</span> Citas Completadas Hoy
                @php
                    $completedToday = \App\Models\Appointment::with(['patient.user', 'doctor.user'])
                        ->whereHas('doctor', function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->where('status', 2)
                        ->whereDate('date', now()->format('Y-m-d'))
                        ->orderBy('start_time')
                        ->get();
                @endphp
                @if($completedToday->count() > 0)
                    <span class="ml-auto bg-green-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                        {{ $completedToday->count() }}
                    </span>
                @endif
            </p>

            <div class="space-y-1 max-h-40 overflow-y-auto">
                @forelse($completedToday as $appointment)
                    <div class="bg-white border border-green-200 rounded p-1.5 hover:shadow-sm transition-shadow">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-800">
                                    {{ $appointment->patient->user->name }}
                                </p>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    🕐 {{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}
                                </p>
                                @if($appointment->doctor)
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        {{ $appointment->doctor->user->name }}
                                    </p>
                                @endif
                            </div>
                            <span class="bg-green-100 text-green-800 px-1.5 py-0.5 rounded-full text-xs font-medium">
                                ✅
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-1 text-xs">
                        No hay citas completadas hoy
                    </p>
                @endforelse
            </div>
        </x-wire-card>

        <!-- CITAS PENDIENTES PASADAS - MÁS PEQUEÑA -->
        <x-wire-card class="bg-gradient-to-r from-red-50 to-rose-50">
            <p class="text-sm font-semibold text-gray-800 flex items-center mb-1">
                <span class="mr-1">⏰</span> Citas Pendientes Pasadas
                @php
                    $pendingPastAppointments = \App\Models\Appointment::with(['patient.user', 'doctor.user'])
                        ->whereHas('doctor', function($query) {
                            $query->where('user_id', auth()->id());
                        })
                        ->where('status', 1) // Status 1 = Programado/Pendiente
                        ->whereDate('date', now()->format('Y-m-d'))
                        ->whereTime('start_time', '<', now()->format('H:i:s'))
                        ->orderBy('start_time')
                        ->get();
                @endphp
                @if($pendingPastAppointments->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                        {{ $pendingPastAppointments->count() }}
                    </span>
                @endif
            </p>

            <div class="space-y-1 max-h-40 overflow-y-auto">
                @forelse($pendingPastAppointments as $appointment)
                    <div class="bg-white border border-red-200 rounded p-1.5 hover:shadow-sm transition-shadow">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-800">
                                    {{ $appointment->patient->user->name }}
                                </p>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    🕐 {{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}
                                </p>
                                <p class="text-xs text-red-600 mt-0.5 font-medium">
                                    ⚠️ Pasó la hora
                                </p>
                            </div>
                            <a href="{{ route('admin.appointments.consultation', $appointment) }}" 
                            class="text-xs bg-red-500 text-white px-1.5 py-0.5 rounded hover:bg-red-600 transition-colors">
                                Gestionar
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-1 text-xs">
                        No hay citas pendientes pasadas
                    </p>
                @endforelse
            </div>
        </x-wire-card>
    </div>

    <!-- SEGUNDA FILA: AGENDA SEMANAL (sin cambios) -->
    <div class="mb-8">
        <x-wire-card class="bg-gradient-to-r from-indigo-50 to-purple-50">
            <p class="text-lg font-semibold text-gray-800 flex items-center mb-4">
                <span class="mr-2">📅</span> Agenda Semanal
            </p>

            @php
                $today = now()->format('Y-m-d');
                $nextWeek = now()->addDays(7)->format('Y-m-d');
                
                $weeklyAppointments = $data['appointments_today']
                    ->filter(function($appointment) use ($today, $nextWeek) {
                        $appointmentDate = $appointment->date->format('Y-m-d');
                        return $appointmentDate >= $today && $appointmentDate <= $nextWeek;
                    })
                    ->sortBy(['date', 'start_time'])
                    ->groupBy(function($appointment) {
                        return $appointment->date->format('Y-m-d');
                    });
            @endphp

            <div class="space-y-4">
                @forelse($weeklyAppointments as $date => $appointments)
                    <div class="bg-white border border-indigo-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <span class="mr-2">🗓️</span>
                            {{ \Carbon\Carbon::parse($date)->locale('es')->translatedFormat('d/m/Y - l') }}
                            <span class="ml-auto bg-indigo-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ $appointments->count() }}
                            </span>
                        </h4>
                        
                        <div class="space-y-2">
                            @foreach($appointments as $appointment)
                                <div class="flex justify-between items-center bg-gray-50 rounded-lg p-3 hover:bg-white transition-colors">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-800">
                                            👤 {{ $appointment->patient->user->name }}
                                        </p>
                                        <p class="text-xs text-gray-600 mt-1">
                                            🕐 {{ $appointment->start_time->format('h:i A') }} 
                                            @if($appointment->end_time)
                                                - {{ $appointment->end_time->format('h:i A') }}
                                            @endif
                                        </p>
                                        <p class="text-xs mt-1">
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-medium">
                                                ⏰ Programado
                                            </span>
                                        </p>
                                    </div>
                                    
                                    <a href="{{ route('admin.appointments.consultation', $appointment) }}" 
                                       class="text-xs bg-indigo-600 text-white px-3 py-2 rounded hover:bg-indigo-700 transition-colors">
                                        Gestionar
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">
                        No hay citas programadas para esta semana
                    </p>
                @endforelse
            </div>
        </x-wire-card>
    </div>
</div>