<div class="flex items-center space-x-2">
    @can('update_doctor')
        <x-wire-button href="{{route('admin.doctors.edit', $doctor)}}" blue xs>
            <i class="fa-solid fa-pen-to-square"></i>
        </x-wire-button>

        <x-wire-button href="{{route('admin.doctors.schedules', $doctor)}}" green xs>
            <i class="fa-solid fa-clock"></i>
        </x-wire-button>
        @endcan
</div>
