<div class="flex items-center space-x-2">
    <x-wire-button href="{{route('admin.appointments.edit', $appointment)}}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    <x-wire-button href="{{route('admin.appointments.consultation', $appointment)}}" green xs>
        <i class="fa-solid fa-file-lines"></i>
    </x-wire-button>


</div>
