<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $titulo }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Buscar', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h1 class="mb-2">Resultados de la Búsqueda</h1>

            @if ($vendedor)
                <div class="flex items-center bg-gray-50 p-4 rounded-lg mb-4 shadow-md">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $vendedor->foto_tienda) }}" 
                            alt="{{ $vendedor->nombre_tienda }}" 
                            class="w-16 h-16 object-cover rounded-lg" />
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-bold text-gray-800">{{ $vendedor->nombre_tienda }}</h4>
                        <p class="text-gray-600 mt-1">{{ $vendedor->descripcion }}</p>
                    </div>
                </div>
            @endif

            @if ($categoria)
            <div class="flex items-center bg-gray-50 p-4 rounded-lg mb-4 shadow-md">
                <div class="flex-shrink-0">
                    <img src="{{ asset('storage/' . $categoria->photo) }}" 
                        alt="{{ $categoria->photo }}" 
                        class="w-16 h-16 object-cover rounded-lg" />
                </div>
                <div class="ml-4">
                    <h4 class="text-lg font-bold text-gray-800">{{ $categoria->nombre }}</h4>
                </div>
            </div>
            @endif

            @if ($productos->isEmpty())
                <p>No se encontraron productos.</p>
            @else
                <div class="space-y-4">
                    @foreach ($productos as $producto)
                        <a href="{{ route('producto', ['id' => $producto->id]) }}" class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 ease-in-out group cursor-pointer">
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                                class="w-24 h-24 object-cover rounded-md mr-4">
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-600 group-hover:text-red-700 transition duration-200">
                                    {{ $producto->nombre }}
                                </h4>
                                <p class="text-gray-500">{{ $producto->descripcion }}</p>
                                <p class="text-gray-800 font-semibold mt-2">Precio: ${{ number_format($producto->precio, 0) }}</p>
                                <p class="text-gray-500">Cantidad disponible: {{ $producto->cantidad }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
