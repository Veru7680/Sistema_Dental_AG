<div class="flex items-center space-x-2">
    @can('update_role')
        <x-wire-button href="{{route('admin.roles.edit', $role)}}" blue xs>
            <i class="fa-solid fa-pen-to-square"></i>
        </x-wire-button>
    @endcan

    @can('delete_role')
        <form action="{{ route('admin.roles.destroy', $role)}}" 
            method="POST"
            class="delete-form">
            @csrf
            @method('DELETE')
            <x-wire-button type="submit" red xs>
                <i class="fa-solid fa-trash"></i>
            </x-wire-button>
        </form>
    @endcan

</div>