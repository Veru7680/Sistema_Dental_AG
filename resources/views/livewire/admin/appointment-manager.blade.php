<div>

 <x-wire-card>

    <p class="text-xl font-semibold mb-1 text-slate-800">
        Buscar disponibilidad
    </p>

    <p>
        Encuentra el horario perfecto para tu cita
    </p>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <x-wire-input
        label="fecha"
        type="date"
        wire:model="search.date"
        placeholder="Selecciona una fecha"
        />

            <x-wire-select
                label="hora"
                wire:model="search.hour"
                placeholder="Selecciona una hora">

                    @foreach($this->hourBlocks as $hourBlock)
                        <x-wire-select.option
                        :label="$hourBlock->format('H:i:s') . ' - ' . $hourBlock->copy()->addHour()->format('H:i:s')"
                        :value="$hourBlock->format('H:i:s')"
                        />
                    @endforeach
                    
            </x-wire-select>

             <x-wire-select
                label="Especialidad (opcional)"
                wire:model="search.speciality_id"
                placeholder="Selecciona una especialidad">

                    @foreach($specialities as $speciality)
                        <x-wire-select.option
                        :label="$speciality->name"
                        :value="$speciality->id"
                        />
                    @endforeach
                    
            </x-wire-select>

             <div class="lg:pt-6">
                <x-wire-button
                wire:click="searchAvailability"
                class="w-full"
                color="primary"                
                >
                    Buscar Disponibilidad
                </x-wire-button>
             </div>

    </div>
 </x-wire-card> 

</div>
