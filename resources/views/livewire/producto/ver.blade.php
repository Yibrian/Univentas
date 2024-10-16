<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Producto') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Producto', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
        <div class="p-4 sm:p-8 bg-white shadow rounded-lg">
            <div class="flex flex-col md:flex-row md:space-x-8">
                <div class="flex-shrink-0">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                        class="max-w-xs h-auto rounded-lg shadow-lg" />
                </div>
                <div class="mt-4 md:mt-0 md:flex-1">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $producto->nombre }}</h3>
                    <p class="mt-2 text-gray-600">{{ $producto->descripcion }}</p>
                    <p class="mt-4 text-2xl font-semibold text-gray-900">${{ number_format($producto->precio) }}</p>
                    <p class="mt-1 text-gray-700">Cantidad disponible: <span
                            class="font-semibold">{{ $producto->cantidad }}</span></p>
                    
                    <div class="mt-4 flex items-center">
                        <span class="text-yellow-500 flex">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i <3)
                                    <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z"/>
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                                    </svg>
                                @endif
                            @endfor
                        </span>
                    </div>

                    <div class="mt-6">
                        <x-primary-button>
                            Comprar Ahora!!
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-gray-50 shadow rounded-lg mt-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 bg-white shadow rounded-lg">
                <div class="flex-1">
                    <h4 class="text-2xl font-bold text-gray-800">Tienda:</h4>
                    <p class="text-xl font-semibold text-gray-600 mt-1">
                        {{ $producto->vendedor->nombre_tienda }}
                    </p>
                    <p class="mt-2 text-gray-500">{{ $producto->vendedor->descripcion }}</p>
                    <p class="mt-2 text-gray-500">Cantidad de productos en esta tienda: {{ $producto->vendedor->productos->count()}}</p>
            
                    <div class="mt-4 flex items-center">
                        <!-- Estrellas de calificación -->
                        <span class="text-yellow-500 flex">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i <5)
                                    <svg class="h-6 w-6 fill-current text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z"/>
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                                    </svg>
                                @endif
                            @endfor
                        </span>
                    </div>
                </div>
            
                <!-- Imagen de la tienda -->
                <div class="flex-shrink-0 mt-4 md:mt-0 md:ml-6">
                    <img src="{{ asset('storage/' . $producto->vendedor->foto_tienda) }}" 
                        alt="{{ $producto->vendedor->nombre_tienda }}" 
                        class="w-32 h-32 object-cover rounded-lg shadow-md" />
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <button
                    class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Ver tienda
                </button>
            </div>
        </div>
    </div>
</div>
