<div>
        <x-wire-card class="lg:col-span-2">
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-800">
                    Bienvenido {{ auth()->user()->name }} 
                </p>

                <p class="mt-1 text-gray-600">
                    Aqui esta el resumen de tu panel de control.
                </p>

                <div class="flex justify-center mt-4">
                    <x-wire-button href="{{ route('admin.appointments.create')}}">
                        Reservar nueva Cita
                    </x-wire-button>
                </div>
            </div>
            
        </x-wire-card>

    <div class="grid lg:grid-cols-3 gap-8 mt-8">
        <div> 
            <x-wire-card>
                <p class="text-lg font-semibold text-gray-800">
                Ultima Cita
                </p>
                    @if($data['next_appointment'])
                        <p class="mt-4 font-semibold text-gray-800">
                        Dr.(a): {{ $data ['next_appointment']->doctor->user->name}}
                        </p>

                        <p class="text-gray-600 mb-4">
                            {{ $data ['next_appointment']->date->format('d/m/Y')}} a las
                            {{ $data ['next_appointment']->start_time->format('h:i A')}}
                        </p>

                        <x-wire-button href="route('admin.appointments.show', $data['next_appointment'])" class="w-full mb-4">
                            Detalle Cita
                        </x-wire-button>


                    @else
                        <p class="mt-4 text-gray-500">
                            No tiene cita programada para hoy
                        </p>
                    @endif
            </x-wire-card>
        </div>

        <div class="lg:col-span-2">
            <x-wire-card>
                <p class="text-lg font-semibold text-gray-900">
                    Citas Pasadas
                </p>

                <ul class="mt-4 divide-y divide-gray-200">
                    @forelse( $data['past_appointments'] as $appointment )
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">
                             Dr.(a)   {{ $appointment->doctor->user->name}}
                            </p>

                            <p class="text-xs text-gray-500 ">
                                {{ $appointment->date->format('d/m/Y')}} a las
                            </p>{{ $appointment->start_time->format('h:i A')}}
                        </div>

                        <a href="route('admin.appointment.show', $appointment')" class="text-sm text-indigo-600 hover:text-indigo-800">
                            Ver Consulta
                        </a>
                    </li>

                        @empty

                          <li clas="py-3">
                            <p class="mt-4 text-gray-500">
                                    No tiene cita pasadas registradas
                            </p>
                          </li>

                    @endforelse
                </ul>

            </x-wire-card>
        </div>
    </div>
</div>