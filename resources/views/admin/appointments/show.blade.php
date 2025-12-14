<x-admin-layout
    title="Consulta | Dental AG"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas', 'href' => route('admin.appointments.index')],
        ['name' => 'Detalle Consulta']
    ]">

    <x-wire-card>
        <!-- ENCABEZADO TIPO DOCUMENTO -->
        <div class="mb-8 border-b pb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- INFORMACIÓN PACIENTE -->
                <div>
                    <h2 class="font-bold text-gray-700 uppercase text-sm mb-3 border-l-4 border-blue-500 pl-2">
                        Datos del Paciente
                    </h2>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-600 font-medium w-1/3">Nombre:</td>
                            <td class="py-1 font-medium">{{ $appointment->patient->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">Email:</td>
                            <td class="py-1">{{ $appointment->patient->user->email }}</td>
                        </tr>
                        @if($appointment->patient->user->phone)
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">Teléfono:</td>
                            <td class="py-1">{{ $appointment->patient->user->phone }}</td>
                        </tr>
                        @endif
                        @if($appointment->patient->user->ci)
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">CI:</td>
                            <td class="py-1">{{ $appointment->patient->user->ci }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- INFORMACIÓN MÉDICO Y FECHA -->
                <div>
                    <h2 class="font-bold text-gray-700 uppercase text-sm mb-3 border-l-4 border-green-500 pl-2">
                        Datos de la Consulta
                    </h2>
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-600 font-medium w-1/3">Médico:</td>
                            <td class="py-1 font-medium">Dr. {{ $appointment->doctor->user->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">Especialidad:</td>
                            <td class="py-1">{{ $appointment->doctor->specialty->name ?? 'Odontología General' }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">Fecha:</td>
                            <td class="py-1">{{ $appointment->date->format('d/m/Y') }}</td>
                        </tr>
                        @if($appointment->time)
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">Hora:</td>
                            <td class="py-1">{{ $appointment->time }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="py-1 text-gray-600 font-medium">N° Consulta:</td>
                            <td class="py-1">#{{ $appointment->id }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- CUERPO DEL DOCUMENTO -->
        <div class="space-y-6">
            <!-- DIAGNÓSTICO -->
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-3 border-b pb-2">
                    <span class="bg-gray-800 text-white px-2 py-1 text-sm rounded mr-2">1</span>
                    Diagnóstico
                </h3>
                <div class="ml-6">
                    <div class="border-l-2 border-gray-300 pl-4 py-2">
                        <p class="text-gray-700">
                            {{ $appointment->consultation->diagnosis ?? 'No se registró diagnóstico.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- TRATAMIENTO -->
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-3 border-b pb-2">
                    <span class="bg-gray-800 text-white px-2 py-1 text-sm rounded mr-2">2</span>
                    Plan de Tratamiento
                </h3>
                <div class="ml-6">
                    <div class="border-l-2 border-gray-300 pl-4 py-2">
                        <p class="text-gray-700">
                            {{ $appointment->consultation->treatment ?? 'No se registró tratamiento.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- COSTO -->
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-3 border-b pb-2">
                    <span class="bg-gray-800 text-white px-2 py-1 text-sm rounded mr-2">3</span>
                    Costo del Servicio
                </h3>
                <div class="ml-6">
                    <div class="border-l-2 border-gray-300 pl-4 py-2">
                        <div class="flex items-center">
                            <span class="font-medium text-gray-600 mr-2">Monto:</span>
                            <span class="{{ $appointment->consultation->service_cost ? 'text-xl font-bold text-gray-800' : 'text-gray-500' }}">
                                {{ $appointment->consultation->service_cost ? 'Bs ' . number_format($appointment->consultation->service_cost, 2) : 'Sin especificar' }}
                            </span>
                        </div>
                        @if($appointment->consultation->service_cost)
                        <p class="text-sm text-gray-500 mt-1">
                            ({{ number_format($appointment->consultation->service_cost, 2) }} Bolivianos)
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RECETA MÉDICA -->
            @isset($appointment->consultation->prescriptions)
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-3 border-b pb-2">
                    <span class="bg-gray-800 text-white px-2 py-1 text-sm rounded mr-2">4</span>
                    Receta Médica
                </h3>
                <div class="ml-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">#</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">Medicamento</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">Dosis</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left font-medium text-gray-700">Instrucciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointment->consultation->prescriptions as $index => $prescription)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 px-4 py-2 font-medium">{{ $prescription['medicine'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $prescription['dosage'] }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $prescription['frequency'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endisset

            <!-- NOTAS MÉDICAS -->
            @role('Doctor')
            @if($appointment->consultation->notes)
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-3 border-b pb-2">
                    <span class="bg-gray-800 text-white px-2 py-1 text-sm rounded mr-2">5</span>
                    Observaciones Médicas
                </h3>
                <div class="ml-6">
                    <div class="border border-gray-300 rounded p-4 bg-gray-50">
                        <div class="flex">
                            <div class="mr-3 text-gray-400">
                                <i class="fa-solid fa-quote-left"></i>
                            </div>
                            <p class="text-gray-700 italic">
                                {{ $appointment->consultation->notes }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endrole
        </div>

        <!-- PIE DE PÁGINA -->
        <div class="mt-8 pt-6 border-t text-center text-gray-500 text-xs">
            <p>Documento generado el {{ now()->format('d/m/Y H:i') }} | Dental AG - Sistema de Gestión Médica</p>
            <p class="mt-1">Este documento tiene validez clínica y legal</p>
        </div>
    </x-wire-card>
</x-admin-layout>