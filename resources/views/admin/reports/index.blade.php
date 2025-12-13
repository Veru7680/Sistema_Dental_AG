<x-admin-layout
    title=" Reporte | Dental AG"
    :breadcrumbs="[
        ['name'=>'Dashboard', 'href'=> route('admin.dashboard')],
        ['name'=>'Reporte'],
    ]">

    <div class="container-fluid p-4">
        <h1 class="text-xl font-semibold mb-4 text-gray-800">Reportes de Consultas</h1>

        <!-- FORMULARIO PRINCIPAL DE FILTROS -->
        <form method="GET" action="{{ route('admin.reports.index') }}" id="filterForm">
            <div class="bg-white p-4 rounded-lg shadow-md border border-gray-200 mb-6">
                <!-- Título y descripción -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">🔍 Filtros de Reporte</h2>
                    <p class="text-sm text-gray-600 mt-1">Filtra las consultas por usuario, fecha o periodo específico</p>
                </div>

                <!-- Indicador de rol -->
                <div class="mb-4">
                    @php
                        $role = auth()->user()->roles->first()->name ?? 'Usuario';
                    @endphp
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full 
                                {{ $role == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                ($role == 'doctor' ? 'bg-blue-100 text-blue-800' : 
                                ($role == 'patient' ? 'bg-green-100 text-green-800' : 
                                ($role == 'receptionist' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))) }}">
                        Rol: {{ ucfirst($role) }}
                    </span>
                </div>

                <!-- Fila 1: Filtro por usuario -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        👤 Usuario que solicita el reporte
                    </label>
                    <div class="flex flex-col md:flex-row gap-3">
                        <!-- Usuario logeado actualmente -->
                        <div class="flex-1">
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-sm font-medium text-blue-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-blue-600 mt-1">ID: {{ auth()->user()->id }}</p>
                            </div>
                            <input type="hidden" name="requested_by" value="{{ auth()->user()->id }}">
                        </div>
                        
                        <!-- Selector de otro usuario (dinámico según rol) -->
                        <div class="flex-1">
                            <select name="filter_user" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Buscar por otro usuario --</option>
                                <option value="{{ auth()->user()->id }}" {{ request('filter_user') == auth()->id() ? 'selected' : '' }}>
                                    Yo mismo ({{ auth()->user()->name }})
                                </option>
                                
                                @php
                                    $currentUser = auth()->user();
                                    $isPatient = $currentUser->hasRole('Paciente');
                                    $isDoctor = $currentUser->hasRole('Doctor');
                                    $isAdminOrReceptionist = $currentUser->hasRole(['admin', 'Recepcionista']);
                                @endphp
                                
                                @if($isPatient && isset($usersForFilter))
                                    <!-- Paciente: solo ve doctores que le han atendido -->
                                    <optgroup label="👨‍⚕️ Mis Doctores">
                                        @foreach($usersForFilter as $user)
                                            @if($user->hasRole('Doctor'))
                                                <option value="{{ $user->id }}" {{ request('filter_user') == $user->id ? 'selected' : '' }}>
                                                    Dr. {{ $user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    
                                @elseif($isDoctor && isset($usersForFilter))
                                    <!-- Doctor: solo ve sus pacientes -->
                                    <optgroup label="👤 Mis Pacientes">
                                        @foreach($usersForFilter as $user)
                                            @if($user->hasRole('Paciente'))
                                                <option value="{{ $user->id }}" {{ request('filter_user') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    
                                @elseif($isAdminOrReceptionist && isset($usersForFilter))
                                    <!-- Admin/Recepcionista: ve TODOS los usuarios -->
                                    <optgroup label="👨‍⚕️ Doctores">
                                        @foreach($usersForFilter->filter(function($u) { return $u->hasRole('Doctor'); }) as $doctorUser)
                                            <option value="{{ $doctorUser->id }}" {{ request('filter_user') == $doctorUser->id ? 'selected' : '' }}>
                                                Dr. {{ $doctorUser->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    
                                    <optgroup label="👤 Pacientes">
                                        @foreach($usersForFilter->filter(function($u) { return $u->hasRole('Paciente'); }) as $patientUser)
                                            <option value="{{ $patientUser->id }}" {{ request('filter_user') == $patientUser->id ? 'selected' : '' }}>
                                                {{ $patientUser->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    
                                    <optgroup label="👥 Otros Roles">
                                        @foreach($usersForFilter->filter(function($u) { return !$u->hasRole('Doctor') && !$u->hasRole('Paciente'); }) as $otherUser)
                                            @php
                                                $roleName = $otherUser->roles->first()->name ?? 'Usuario';
                                            @endphp
                                            <option value="{{ $otherUser->id }}" {{ request('filter_user') == $otherUser->id ? 'selected' : '' }}>
                                                {{ $otherUser->name }} ({{ $roleName }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Fila 2: Selección de periodo -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        📅 Periodo del reporte
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <!-- Tipo de periodo -->
                        <div>
                            <select name="period_type" id="periodType" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="month" {{ request('period_type', 'month') == 'month' ? 'selected' : '' }}>Por Mes</option>
                                <option value="week" {{ request('period_type') == 'week' ? 'selected' : '' }}>Por Semana</option>
                                <option value="day" {{ request('period_type') == 'day' ? 'selected' : '' }}>Por Día</option>
                                <option value="range" {{ request('period_type') == 'range' ? 'selected' : '' }}>Rango Personalizado</option>
                            </select>
                        </div>
                        
                        <!-- Selectores dinámicos -->
                        <div id="monthSelector" class="period-selector">
                            <select name="filter_month" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg">
                                <option value="">Seleccionar mes</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('filter_month') == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div id="yearSelector" class="period-selector">
                            <select name="filter_year" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg">
                                <option value="">Seleccionar año</option>
                                @for($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request('filter_year', date('Y')) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <!-- Selector de semana -->
                        <div id="weekSelector" class="period-selector hidden">
                            <input type="week" name="filter_week" 
                                   value="{{ request('filter_week', date('Y-\WW')) }}"
                                   class="w-full p-2.5 text-sm border border-gray-300 rounded-lg">
                        </div>
                        
                        <!-- Selector de día -->
                        <div id="daySelector" class="period-selector hidden">
                            <input type="date" name="filter_day" 
                                   value="{{ request('filter_day', date('Y-m-d')) }}"
                                   class="w-full p-2.5 text-sm border border-gray-300 rounded-lg">
                        </div>
                        
                        <!-- Rango de fechas -->
                        <div id="rangeSelector" class="period-selector hidden md:col-span-2">
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" name="start_date" 
                                       value="{{ request('start_date') }}"
                                       placeholder="Fecha inicio"
                                       class="w-full p-2.5 text-sm border border-gray-300 rounded-lg">
                                <input type="date" name="end_date" 
                                       value="{{ request('end_date') }}"
                                       placeholder="Fecha fin"
                                       class="w-full p-2.5 text-sm border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fila 3: Botones de acción -->
                <div class="flex flex-col md:flex-row justify-between items-center pt-4 border-t border-gray-200">
                    <!-- Información del filtro actual -->
                    <div class="text-sm text-gray-600 mb-3 md:mb-0">
                        @php
                            $hasDateFilter = request()->anyFilled(['filter_month', 'filter_year', 'filter_week', 'filter_day', 'start_date']);
                            $periodType = request('period_type', 'month');
                        @endphp
                        
                        @if($hasDateFilter)
                            <span class="font-medium">Filtros aplicados:</span>
                            @if($periodType === 'month' && request('filter_month') && request('filter_year'))
                                {{ \Carbon\Carbon::create(request('filter_year'), request('filter_month'), 1)->isoFormat('MMMM YYYY') }}
                            @elseif($periodType === 'week' && request('filter_week'))
                                @php
                                    $weekData = explode('-W', request('filter_week'));
                                    if (count($weekData) === 2) {
                                        $year = $weekData[0];
                                        $week = $weekData[1];
                                        $date = \Carbon\Carbon::now()->setISODate($year, $week);
                                        echo 'Semana ' . $week . ' (' . $date->startOfWeek()->isoFormat('DD/MM') . ' - ' . $date->endOfWeek()->isoFormat('DD/MM') . ')';
                                    }
                                @endphp
                            @elseif($periodType === 'day' && request('filter_day'))
                                {{ \Carbon\Carbon::parse(request('filter_day'))->isoFormat('DD/MM/YYYY') }}
                            @elseif($periodType === 'range' && request('start_date') && request('end_date'))
                                {{ \Carbon\Carbon::parse(request('start_date'))->isoFormat('DD/MM/YYYY') }} al {{ \Carbon\Carbon::parse(request('end_date'))->isoFormat('DD/MM/YYYY') }}
                            @endif
                            
                            @if(request('filter_user') && request('filter_user') != auth()->id())
                                @php
                                    $selectedUser = \App\Models\User::find(request('filter_user'));
                                @endphp
                                @if($selectedUser)
                                    | Usuario: {{ $selectedUser->name }}
                                @endif
                            @endif
                        @else
                            @if(auth()->user()->hasRole(['patient', 'doctor']))
                                Mostrando últimos 30 días
                            @else
                                Mostrando todos los registros
                            @endif
                        @endif
                    </div>
                    
                    <!-- Botones -->
                    <div class="flex space-x-2">
                        <button type="submit" 
                                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-300 focus:outline-none flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Aplicar Filtros
                        </button>
                        
                        <a href="{{ route('admin.reports.index') }}" 
                           class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-lg focus:outline-none flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Limpiar
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- TABLA DE RESULTADOS -->
        <div class="relative overflow-x-auto bg-white shadow-sm rounded-lg border border-gray-200">
            <!-- Header con búsqueda -->
            <div class="flex flex-col md:flex-row items-center justify-between p-4 border-b border-gray-200 gap-3">
                <div class="text-sm text-gray-700">
                    <span class="font-medium">{{ $consultations->total() }}</span> consultas encontradas
                    @if(request()->anyFilled(['filter_month', 'filter_year', 'filter_week', 'filter_day', 'start_date', 'filter_user']))
                        <span class="text-gray-500">(con filtros aplicados)</span>
                    @endif
                </div>
                
                <div class="relative w-full md:w-64">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Buscar en resultados..." 
                           class="block w-full ps-10 pe-3 py-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500"
                           value="{{ request('search') }}"
                           onkeydown="if(event.key === 'Enter') document.getElementById('filterForm').submit()">
                </div>
            </div>

            @if($consultations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-medium">ID</th>
                            <th scope="col" class="px-4 py-3 font-medium">Paciente</th>
                            <th scope="col" class="px-4 py-3 font-medium">Doctor</th>
                            <th scope="col" class="px-4 py-3 font-medium">Fecha Cita</th>
                            <th scope="col" class="px-4 py-3 font-medium">Horario</th>
                            <th scope="col" class="px-4 py-3 font-medium">Diagnóstico</th>
                            <th scope="col" class="px-4 py-3 font-medium">Tratamiento</th>
                            <th scope="col" class="px-4 py-3 font-medium">Fecha Consulta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($consultations as $consulta)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-gray-600">
                                #{{ str_pad($consulta->id, 3, '0', STR_PAD_LEFT) }}
                            </td>
                            
                            <!-- Paciente -->
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $consulta->appointment->patient->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    Cita #{{ $consulta->appointment_id }}
                                </div>
                            </td>
                            
                            <!-- Doctor -->
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $consulta->appointment->doctor->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $consulta->appointment->doctor->specialty ?? 'Especialista' }}
                                </div>
                            </td>
                            
                            <!-- Fecha de la cita -->
                            <td class="px-4 py-3">
                                @if($consulta->appointment && $consulta->appointment->date)
                                    <div class="font-medium">{{ \Carbon\Carbon::parse($consulta->appointment->date)->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ \Carbon\Carbon::parse($consulta->appointment->date)->isoFormat('dddd') }}
                                    </div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Horario -->
                            <td class="px-4 py-3">
                                @if($consulta->appointment && $consulta->appointment->start_time)
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            <span class="text-sm">
                                                {{ \Carbon\Carbon::parse($consulta->appointment->start_time)->format('H:i') }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                            <span class="text-sm">
                                                {{ $consulta->appointment->end_time ? \Carbon\Carbon::parse($consulta->appointment->end_time)->format('H:i') : '--:--' }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Diagnóstico -->
                            <td class="px-4 py-3 max-w-xs">
                                @if($consulta->diagnosis)
                                    <div class="truncate" title="{{ $consulta->diagnosis }}">
                                        {{ \Illuminate\Support\Str::limit($consulta->diagnosis, 40) }}
                                    </div>
                                    @if(strlen($consulta->diagnosis) > 40)
                                    <button type="button" class="text-xs text-blue-600 hover:text-blue-800 mt-1" onclick="alert('{{ addslashes($consulta->diagnosis) }}')">
                                        Ver completo
                                    </button>
                                    @endif
                                @else
                                    <span class="text-gray-400">Sin diagnóstico</span>
                                @endif
                            </td>
                            
                            <!-- Tratamiento -->
                            <td class="px-4 py-3 max-w-xs">
                                @if($consulta->treatment)
                                    <div class="truncate" title="{{ $consulta->treatment }}">
                                        {{ \Illuminate\Support\Str::limit($consulta->treatment, 40) }}
                                    </div>
                                    @if(strlen($consulta->treatment) > 40)
                                    <button type="button" class="text-xs text-blue-600 hover:text-blue-800 mt-1" onclick="alert('{{ addslashes($consulta->treatment) }}')">
                                        Ver completo
                                    </button>
                                    @endif
                                @else
                                    <span class="text-gray-400">Sin tratamiento</span>
                                @endif
                            </td>
                            
                            <!-- Fecha Consulta -->
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium">{{ $consulta->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $consulta->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Mostrar si no hay datos -->
            @else
            <div class="text-center py-8 text-gray-500 bg-white">
                <div class="mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.801 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.801 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-600 mb-2">No hay consultas que coincidan con tus filtros</p>
                <p class="text-sm text-gray-500">Intenta cambiar los criterios de búsqueda o <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:underline">limpiar los filtros</a></p>
            </div>
            @endif
            
            <!-- Paginación -->
            @if($consultations->hasPages())
            <div class="flex flex-col md:flex-row items-center justify-between p-4 border-t border-gray-200 gap-3">
                <div class="text-sm text-gray-700">
                    Mostrando {{ $consultations->firstItem() }} a {{ $consultations->lastItem() }} de {{ $consultations->total() }} resultados
                </div>
                <div class="flex space-x-2">
                    @if($consultations->onFirstPage())
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Anterior</span>
                    @else
                    <a href="{{ $consultations->previousPageUrl() }}" class="px-3 py-1 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg">Anterior</a>
                    @endif
                    
                    @if($consultations->hasMorePages())
                    <a href="{{ $consultations->nextPageUrl() }}" class="px-3 py-1 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg">Siguiente</a>
                    @else
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">Siguiente</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

</x-admin-layout>

<!-- JavaScript mejorado para filtros -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodType = document.getElementById('periodType');
    const selectors = {
        month: document.getElementById('monthSelector'),
        year: document.getElementById('yearSelector'),
        week: document.getElementById('weekSelector'),
        day: document.getElementById('daySelector'),
        range: document.getElementById('rangeSelector')
    };
    
    // Función para mostrar el selector correcto
    function updatePeriodSelectors() {
        const selectedType = periodType.value;
        
        // Ocultar todos primero
        Object.values(selectors).forEach(selector => {
            if (selector) {
                selector.classList.add('hidden');
            }
        });
        
        // Mostrar los selectores según el tipo
        switch(selectedType) {
            case 'month':
                selectors.month?.classList.remove('hidden');
                selectors.year?.classList.remove('hidden');
                break;
            case 'week':
                selectors.week?.classList.remove('hidden');
                break;
            case 'day':
                selectors.day?.classList.remove('hidden');
                break;
            case 'range':
                selectors.range?.classList.remove('hidden');
                break;
        }
    }
    
    // Inicializar
    if (periodType) {
        updatePeriodSelectors();
        periodType.addEventListener('change', updatePeriodSelectors);
    }
    
    // Establecer fecha actual por defecto en selector de día
    const dayInput = document.querySelector('input[name="filter_day"]');
    if (dayInput && !dayInput.value && periodType?.value === 'day') {
        const today = new Date().toISOString().split('T')[0];
        dayInput.value = today;
    }
    
    // Buscar automáticamente después de 500ms de inactividad
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('filterForm').submit();
            }, 500);
        });
    }
});
</script>

<style>
.period-selector {
    transition: all 0.3s ease;
}
</style>