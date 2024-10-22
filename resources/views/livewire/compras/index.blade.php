<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Mis compras') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Mis compras', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 shadow sm:rounded-lg">
            @if ($compras->isEmpty())
                <p class="text-gray-500">No has realizado ninguna compra todavía.</p>
            @else
                <div class="space-y-4">
                    @foreach ($compras as $compra)
                        <div
                            class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 ease-in-out group">
                            <img src="{{ asset('storage/' . $compra->producto->imagen) }}"
                                alt="{{ $compra->producto->nombre }}" class="w-24 h-24 object-cover rounded-md mr-4">
                            <div class="flex-1">
                                <h4
                                    class="text-lg font-semibold text-gray-600 group-hover:text-red-700 transition duration-200">
                                    {{ $compra->producto->nombre }}
                                </h4>
                                <p class="text-gray-800 font-semibold mt-2">Precio:
                                    ${{ number_format($compra->producto->precio, 0) }}</p>
                                <p class="text-gray-500">Cantidad comprada: {{ $compra->cantidad }}</p>
                                <p class="text-gray-400 text-sm">Fecha de compra:
                                    {{ $compra->created_at->format('d/m/Y') }}</p>
                                <p class="text-gray-500 text-sm mt-2">Vendido por:
                                    {{ $compra->producto->vendedor->nombre_tienda }}</p>
                            </div>
                            <div class="text-right space-y-2">
                                <p class="text-lg font-bold text-gray-800">Total:
                                    ${{ number_format($compra->producto->precio * $compra->cantidad, 0) }}</p>
                                @if (!$compra->confirmacion_vendedor)
                                    <p
                                        class="text-sm font-semibold text-blue-800 bg-blue-100 px-4 py-2 rounded-lg flex items-center space-x-2">
                                        <span>Pendiente de confirmación por el vendedor</span>
                                    </p>
                                @endif
                                @if ($compra->confirmacion_vendedor && !$compra->confirmacion_cliente)
                                    <div
                                        class="text-sm font-semibold text-orange-800 bg-orange-100 px-3 py-2 rounded-lg flex items-center justify-between space-x-2">
                                        <span>Venta confirmada. Por favor, confirma la recepción.</span>
                                        <button
                                            class="bg-orange-600 text-white px-2 py-1 rounded-md hover:bg-orange-700 transition-colors">
                                            Confirmar
                                        </button>
                                    </div>
                                @endif


                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
