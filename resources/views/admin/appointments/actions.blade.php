<div class="flex items-center space-x-2">
    @can('read_appointment')    
    <x-wire-button href="{{route('admin.appointments.show', $appointment)}}" purple xs>
        <i class="fa-solid fa-eye"></i>
    </x-wire-button>
     @endcan

    @can('update_appointment')
    <x-wire-button href="{{route('admin.appointments.edit', $appointment)}}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>
    @endcan
 @if(auth()->user()->hasRole('Doctor'))
        <x-wire-button href="{{route('admin.appointments.consultation', $appointment)}}" green xs>
            <i class="fa-solid fa-file-lines"></i>
        </x-wire-button>
    @endif

</div>
