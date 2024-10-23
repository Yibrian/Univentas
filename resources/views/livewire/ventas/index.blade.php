<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Mis ventas') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Mis ventas', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 shadow sm:rounded-lg">
            @if ($ventas->isEmpty())
                <p class="text-gray-500">No has realizado ninguna ventas todavía.</p>
            @else
                <div class="space-y-4">
                    @foreach ($ventas as $venta)
                        <div
                            class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 ease-in-out group">
                            <img src="{{ asset('storage/' . $venta->producto->imagen) }}"
                                alt="{{ $venta->producto->nombre }}" class="w-24 h-24 object-cover rounded-md mr-4">
                            <div class="flex-1">
                                <h4
                                    class="text-lg font-semibold text-gray-600 group-hover:text-red-700 transition duration-200">
                                    {{ $venta->producto->nombre }}
                                </h4>
                                <p class="text-gray-800 font-semibold mt-2">Precio:
                                    ${{ number_format($venta->producto->precio, 0) }}</p>
                                <p class="text-gray-500">Cantidad comprada: {{ $venta->cantidad }}</p>
                                <p class="text-gray-400 text-sm">Fecha de ventas:
                                    {{ $venta->created_at->format('d/m/Y') }}</p>
                                <p class="text-gray-500 text-sm mt-2">Comprado por:
                                    {{ $venta->cliente->user->name }}</p>
                            </div>
                            <div class="text-right space-y-2">
                                <p class="text-lg font-bold text-gray-800">Total:
                                    ${{ number_format($venta->producto->precio * $venta->cantidad, 0) }}</p>

                                @if (!$venta->confirmacion_vendedor && !$venta->confirmacion_cliente)
                                    <div
                                        class="text-sm font-semibold text-yellow-800 bg-yellow-100 px-3 py-2 rounded-lg flex items-center justify-between space-x-2">
                                        <span>Recuerda confirmar la venta al cliente.</span>
                                        <a wire:click.prevent="confirmarVenta('{{ $venta->id }}')"
                                            class="bg-yellow-600 text-white px-2 py-1 rounded-md hover:bg-yellow-700 transition-colors cursor-pointer">
                                            Confirmar
                                        </a>
                                    </div>
                                    <div
                                        class="mt-2 text-sm font-semibold text-red-800 bg-red-100 px-3 py-2 rounded-lg flex items-center justify-between space-x-2">
                                        <span>¿Deseas cancelar esta venta?</span>
                                        <a wire:click.prevent="cancelarVenta('{{ $venta->id }}')"
                                            class="bg-red-600 text-white px-2 py-1 rounded-md hover:bg-red-700 transition-colors cursor-pointer">
                                            Cancelar
                                        </a>
                                    </div>
                                @endif

                                @if ($venta->confirmacion_vendedor && !$venta->confirmacion_cliente)
                                    <p
                                        class="text-sm font-semibold text-blue-800 bg-blue-100 px-4 py-2 rounded-lg flex items-center space-x-2">
                                        <span>Pendiente de recepción por el cliente</span>
                                    </p>
                                @endif

                                @if ($venta->confirmacion_vendedor && $venta->confirmacion_cliente)
                                    <div
                                        class="text-sm font-semibold text-green-800 bg-green-100 px-3 py-2 rounded-lg flex items-center space-x-2">
                                        <span>Venta completada</span>
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
