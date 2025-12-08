<div class="flex items-center space-x-2">
    @can('update_user')
        <x-wire-button href="{{route('admin.users.edit', $user)}}" blue xs>
            <i class="fa-solid fa-pen-to-square"></i>
        </x-wire-button>
    @endcan

    
</div>