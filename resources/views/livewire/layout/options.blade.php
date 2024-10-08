<x-dropdown-link :href="route('profile')" wire:navigate>
    {{ __('Profile') }}
</x-dropdown-link>

@role('admin')
<x-dropdown-link :href="route('users')">
    {{__('Users')}} 
</x-dropdown-link>
@endrole


@if (Auth::user()->vendedor)  
    <x-dropdown-link :href="route('mi.tienda')">
        {{__('Mi tienda')}} 
    </x-dropdown-link>
@endif

<!-- Authentication -->
<button wire:click="logout" class="w-full text-start">
    <x-dropdown-link>
        {{ __('Log Out') }}
    </x-dropdown-link>
</button>