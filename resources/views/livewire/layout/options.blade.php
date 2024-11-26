<x-dropdown-link :href="route('profile')" wire:navigate>
    {{ __('Profile') }}
</x-dropdown-link>

@role('admin')
    <x-dropdown-link :href="route('users')">
        {{ __('Users') }}
    </x-dropdown-link>
    <x-dropdown-link :href="route('categorias')">
        {{ __('Gestión de Categorias') }}
    </x-dropdown-link>
@endrole
@role('cliente')
    <x-dropdown-link :href="route('compras')">
        {{ __('Mis compras') }}
    </x-dropdown-link>
@endrole

@role('vendedor')
    <x-dropdown-link :href="route('ventas')">
        {{ __('Mis ventas') }}
    </x-dropdown-link>
    <x-dropdown-link :href="route('mi.tienda')">
        {{ __('Mi tienda') }}
    </x-dropdown-link>
    <x-dropdown-link :href="route('cupones')">
        {{ __('Cupones') }}
    </x-dropdown-link>
@endrole



<!-- Authentication -->
<button wire:click="logout" class="w-full text-start">
    <x-dropdown-link>
        {{ __('Log Out') }}
    </x-dropdown-link>
</button>
