<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
     public $palabra_busqueda;

     public function mount(){
        
        if(request()->route('tipo') === 'query'){
            $this->palabra_busqueda = request()->route('clave');
        }

     }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

{{-- <nav x-data="{ open: false }" class="bg-red-700 border-b border-red-100"> --}}
<nav x-data="{ open: false }" class="bg-white border-b">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center justify-center h-15 p-2 " style="margin-top: 5px">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="h-16 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex">
                    @include('livewire.layout.links')
                </div>
            </div>

            <!-- Settings Dropdown and Search Bar -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div x-data="{ palabraClave:  @entangle('palabra_busqueda') }" class="mr-4">
                    <input type="text"
                        x-model="palabraClave"
                        @keydown.enter="if(palabraClave) { 
                            window.location.href = '{{ url('/busqueda/query') }}/' + palabraClave; 
                        }"
                        class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-72 sm:max-w-xs"
                        placeholder="Buscar..." wire:model="palabra_busqueda"
                    />
                </div>
                

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black bg-white hover:text-red-700 hover:bg-gray-100 focus:outline-none transition ease-in-out duration-150">
                            <!-- Imagen circular -->
                            <div class="mr-2">
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="User Image"
                                    class="rounded-full" style="width: 32px; height: 32px; object-fit: cover">
                            </div>
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @include('livewire.layout.options')
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

<!-- Responsive Navigation Menu -->
<div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1">
        @include('livewire.layout.links')
    </div>

    <!-- Responsive Settings Options -->
    <div class="pt-4 pb-1 border-t border-gray-200">
        <!-- Search Bar -->
        <div x-data="{ palabraClave:  @entangle('palabra_busqueda') }" class="px-4 mb-4">
            <input type="text"
                x-model="palabraClave"
                @keydown.enter="if(palabraClave) { 
                    window.location.href = '{{ url('/busqueda/query') }}/' + palabraClave; 
                }"
                class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-full"
                placeholder="Buscar..."
            />
        </div>
        
        
        
        <!-- Profile Section -->
        <div class="flex items-center px-4">
            <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="User Image" class="rounded-full"
                style="width: 32px; height: 32px; margin-right: 10px; object-fit: cover">
            <div>
                <div class="font-medium text-base text-gray-800" 
                     x-data="{{ json_encode(['name' => auth()->user()->name]) }}" 
                     x-text="name" 
                     x-on:profile-updated.window="name = $event.detail.name">
                </div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>
        </div>

        <!-- User Options -->
        <div class="mt-3 space-y-1">
            @include('livewire.layout.options')
        </div>
    </div>
</div>

</nav>
