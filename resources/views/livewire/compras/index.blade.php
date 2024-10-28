<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Mis compras') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Mis compras', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 shadow sm:rounded-lg">

            <div class="flex justify-between items-center mt-4 mb-4">
                <div class="text-lg font-bold text-gray-800 mt-4">
                    Total comprado: ${{ number_format($total_comprado, 0) }}
                </div>

                <div class="flex items-center space-x-4">
                    <select wire:model.defer="filtro" class="border-gray-300 rounded-lg">
                        <option value="" selected>Todas las Ventas</option>
                        <option value="confirmadas">Confirmadas (Producto entregado y recibido)</option>
                        <option value="pendientes">Pendientes de Aprobación del Vendedor</option>
                        <option value="espera">Esperando Confirmación del Cliente</option>
                    </select>

                    <x-primary-button wire:click="aplicarFiltro">
                        <i class="fa-solid fa-filter mr-2"></i>
                        <span>Filtrar</span>
                    </x-primary-button>
                </div>
            </div>

            @if ($compras->isEmpty())
                <p class="text-gray-500">No hay compras disponibles.</p>
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
                                <p class="text-gray-500">Método de pago: {{ ucfirst($compra->metodo) }}</p>
                                <p class="text-gray-500">
                                    {{ $compra->entrega_domicilio ? 'Entrega a domicilio' : 'Recogida en tienda' }}:
                                    {{ $compra->lugar_entrega }}
                                </p>
                                <p class="text-gray-400 text-sm">Fecha de compra:
                                    {{ $compra->created_at->format('d/m/Y') }}</p>
                                <p class="text-gray-500 text-sm mt-2">Vendido por:
                                    {{ $compra->producto->vendedor->nombre_tienda }}</p>
                            </div>
                            <div class="text-right space-y-2">
                                <p class="text-lg font-bold text-gray-800">Total:
                                    ${{ number_format($compra->valor, 0) }}</p>

                                @if ($compra->metodo === 'nequi' && $compra->comprobante)
                                    <a href="{{ asset('storage/' . $compra->comprobante) }}" target="_blank"
                                        class="text-sm font-semibold text-blue-600 hover:underline">Ver comprobante</a>
                                @endif

                                @if (!$compra->confirmacion_vendedor && !$compra->confirmacion_cliente)
                                    <p
                                        class="text-sm font-semibold text-blue-800 bg-blue-100 px-4 py-2 rounded-lg flex items-center space-x-2">
                                        <span>Pendiente de confirmación por el vendedor</span>
                                    </p>
                                    <div
                                        class="mt-3 text-sm font-semibold text-red-800 bg-red-100 px-4 py-2 rounded-lg flex items-center justify-between space-x-2">
                                        <span>¿Deseas cancelar la compra?</span>
                                        <a wire:click.prevent="cancelarCompra('{{ $compra->id }}')"
                                            class="bg-red-600 text-white px-2 py-1 rounded-md hover:bg-red-700 transition-colors cursor-pointer">
                                            Cancelar
                                        </a>
                                    </div>
                                @endif

                                @if ($compra->confirmacion_vendedor && !$compra->confirmacion_cliente)
                                    <div
                                        class="text-sm font-semibold text-orange-800 bg-orange-100 px-3 py-2 rounded-lg flex items-center justify-between space-x-2">
                                        <span>Venta confirmada. Por favor, confirma la recepción.</span>
                                        <a wire:click.prevent="confirmarRecepción('{{ $compra->id }}')"
                                            class="bg-orange-600 text-white px-2 py-1 rounded-md hover:bg-orange-700 transition-colors cursor-pointer">
                                            Confirmar
                                        </a>
                                    </div>
                                @endif

                                @if ($compra->confirmacion_vendedor && $compra->confirmacion_cliente)
                                    <div
                                        class="text-sm font-semibold text-green-800 bg-green-100 px-3 py-2 rounded-lg flex items-center space-x-2">
                                        <span>Compra completada</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach


                </div>
            @endif
        </div>
    </div>
    @include('components.alert-component')
</div>
