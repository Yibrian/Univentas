<x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
    <i class="fa-solid fa-house mr-2 text-sm"></i> {{ __('Dashboard') }}
</x-nav-link>

@if (Auth::user()->vendedor)
    <x-nav-link :href="route('vender')" :active="request()->routeIs('vender')">
        <i class="fa-solid fa-store mr-2 text-sm"></i> {{ __('Vender') }}
    </x-nav-link>
@else
    <x-nav-link :href="route('mi.tienda')" :active="request()->routeIs('mi.tienda')">
        <i class="fa-solid fa-store mr-2 text-sm"></i> {{ __('¿Quieres vender?') }}
    </x-nav-link>
@endif

{{-- <div class="inline-flex items-center px-0 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
    <x-dropdown align="left" width="48">
        <x-slot name="trigger">
            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                <i class="fa-solid fa-users mr-2"></i>
                    <span>Desplegar opciones</span>
                    <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
            </button>
        </x-slot>
        <x-slot name="content">
            <x-dropdown-link :href="route('dashboard')" wire:navigate>
                {{ __('Op1') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('dashboard')" wire:navigate>
                {{ __('Op2') }}
            </x-dropdown-link>
            <x-dropdown-link :href="route('dashboard')" wire:navigate>
                {{ __('Op3') }}
            </x-dropdown-link>
        </x-slot>
    </x-dropdown>
</div> --}}
