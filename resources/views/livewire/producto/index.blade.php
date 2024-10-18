<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Mis productos') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[
        ['title' => 'Inicio', 'url' => route('dashboard')],
        ['title' => 'Mis productos', 'url' => null] 
    ]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" x-data="{ search: '' }">
        <div class="flex justify-end mb-4">
            <input type="text" placeholder="Buscar producto..." x-model="search"
                   class="border border-gray-300 rounded-lg p-2 mr-2" />
            <x-primary-button x-on:click.prevent="$dispatch('open-modal', 'create-producto')">
                {{ __('Añadir Producto') }}
            </x-primary-button>
        </div>
    
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            @if($productos->isEmpty())
                <p class="text-center text-gray-600">{{ __('No hay productos disponibles.') }}</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($productos as $producto)
                        <div x-show="search === '' || '{{ strtolower($producto->nombre) }}'.includes(search.toLowerCase())" 
                             class="p-4 bg-gray-50 border border-gray-200 rounded-lg shadow hover:shadow-lg">
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" 
                                 class="w-full h-40 object-cover rounded-md mb-4">
                            
                            <h3 class="text-lg font-bold text-gray-700">{{ $producto->nombre }}</h3>
                            <p class="text-gray-500 text-sm mt-2 ">{{ ucfirst(strtolower($producto->descripcion)) }}</p>
                            <p class="text-gray-500 text-sm mt-2">Precio: {{ $producto->precio }}</p>
    
                            <p class="mt-4">
                                <span class="px-1.5 py-0.5 rounded-full text-xs text-white {{ $producto->disponibilidad ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $producto->disponibilidad ? __('Disponible') : __('No disponible') }}
                                </span>
                            </p>
    
                            <div class="flex justify-end items-center mt-4 space-x-3">
                                <a wire:click="toggleDisponibilidad('{{ $producto->id }}')" class="cursor-pointer text-gray-500 hover:text-green-600">
                                    <i class="fa-solid fa-toggle-{{ $producto->disponibilidad ? 'on' : 'off' }} text-xl"></i>
                                </a>
                                <a x-on:click.prevent="$dispatch('open-modal', 'update-producto')" wire:click.prevent="viewProducto('{{ $producto->id }}')" 
                                   class="cursor-pointer text-gray-500 hover:text-blue-600">
                                    <i class="fa-solid fa-pen-to-square text-xl"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    

    <x-modal name="create-producto" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 text-center mb-4 ">
                {{ __('Add new') . ' ' . strtolower(__('producto')) }}
            </h2>
            <form wire:submit.prevent="store" enctype="multipart/form-data">
                @include('livewire.producto.form')
                <div class="mt-6 flex justify-end">
                    <x-danger-button x-on:click="$dispatch('close')"    type="button">
                        {{ __('Cancel') }}
                    </x-danger-button>

                    <x-primary-button class="ms-3" type="submit">
                        {{ __('Publicar') . ' ' . __('producto') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal name="update-producto" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 text-center mb-4 ">
                {{ __('Editar') . ' ' . strtolower(__('producto')) }}
            </h2>
            <form wire:submit.prevent="update" enctype="multipart/form-data">
                @include('livewire.producto.form')
                <div class="mt-6 flex justify-end">
                    <x-danger-button x-on:click="$dispatch('close')"    type="button">
                        {{ __('Cancel') }}
                    </x-danger-button>

                    <x-primary-button class="ms-3" type="submit">
                        {{ __('Actualizar') . ' ' . __('producto') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    @include('components.alert-component')



</div>
