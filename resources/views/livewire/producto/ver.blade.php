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
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->lugar_entrega }}"
                        class="max-w-xs h-auto rounded-lg shadow-lg" />
                </div>
                <div class="mt-4 md:mt-0 md:flex-1">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $producto->lugar_entrega }}</h3>
                    <p class="mt-2 text-gray-600">{{ $producto->descripcion }}</p>
                    <p class="mt-4 text-2xl font-semibold text-gray-900">${{ number_format($producto->precio) }}</p>
                    <p class="mt-1 text-gray-700">Cantidad disponible: <span
                            class="font-semibold">{{ $producto->cantidad }}</span></p>

                    <div class="mt-4 flex items-center">
                        <span class="text-yellow-500 flex">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < 3)
                                    <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z"
                                            fill="none" stroke="currentColor" stroke-width="1.5" />
                                    </svg>
                                @endif
                            @endfor
                        </span>
                    </div>

                    <div class="mt-6">
                        <x-primary-button x-on:click.prevent="$dispatch('open-modal', 'comprar-producto')">
                            ¡Comprar Ahora!
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-gray-50 shadow rounded-lg mt-6">
            <div
                class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 bg-white shadow rounded-lg">
                <div class="flex-1">
                    <h4 class="text-2xl font-bold text-gray-800">Tienda:</h4>
                    <p class="text-xl font-semibold text-gray-600 mt-1">
                        {{ $producto->vendedor->lugar_entrega_tienda }}
                    </p>
                    <p class="mt-2 text-gray-500">{{ $producto->vendedor->descripcion }}</p>
                    <p class="mt-2 text-gray-500">Cantidad de productos en esta tienda:
                        {{ $producto->vendedor->productos->count() }}</p>

                    <div class="mt-4 flex items-center">
                        <!-- Estrellas de calificación -->
                        <span class="text-yellow-500 flex">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < 5)
                                    <svg class="h-6 w-6 fill-current text-yellow-500" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 fill-current text-gray-300" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.122-6.563L1 6.545l6.568-.955L10 0l2.432 5.59L19 6.545l-4.244 4.982 1.122 6.563L10 15z"
                                            fill="none" stroke="currentColor" stroke-width="1.5" />
                                    </svg>
                                @endif
                            @endfor
                        </span>
                    </div>
                </div>

                <!-- Imagen de la tienda -->
                <div class="flex-shrink-0 mt-4 md:mt-0 md:ml-6">
                    <img src="{{ asset('storage/' . $producto->vendedor->foto_tienda) }}"
                        alt="{{ $producto->vendedor->foto_tienda }}"
                        class="w-32 h-32 object-cover rounded-lg shadow-md" />
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('busqueda', ['tipo' => 'tienda', 'clave' => $producto->vendedor->id]) }}"
                    class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Ver tienda
                </a>
            </div>

        </div>
    </div>

    <x-modal name="comprar-producto" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 text-center mb-4 ">
                {{ 'Confirmar Compra ' }}
            </h2>

            <form wire:submit.prevent="registrarVenta" class="p-4 space-y-6 bg-white shadow-md rounded-lg mb-4">
                <!-- Cantidad a comprar -->
                <div class="mb-4">
                    <x-input-label for="cantidad" :value="__('Cantidad a comprar')" />
                    <x-text-input wire:model="cantidad" id="cantidad" class="block mt-1 w-full" type="number"
                        step="0.01" name="cantidad" required />
                    <x-input-error :messages="$errors->get('cantidad')" class="mt-2" />
                </div>

                <!-- Envío a domicilio -->
                <div x-data="{ entrega_domicilio: false, valorEnvio: {{ $producto->precio_domicilio }} }" class="space-y-4">
                    @if ($producto->envio_domicilio == 1)
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="entrega_domicilio" name="entrega_domicilio"
                                x-model="entrega_domicilio" wire:model="entrega_domicilio"
                                class="form-checkbox h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">¿Quieres envío a domicilio?</span>
                            <x-input-error :messages="$errors->get('entrega_domicilio')" class="mt-2" />

                        </label>
                    @endif


                    <!-- Lugar de entrega -->
                    <div x-show="entrega_domicilio" class="space-y-4">
                        <div>
                            <x-input-label for="lugar_entrega" :value="__('Lugar de entrega')" />
                            <x-text-input wire:model="lugar_entrega" id="lugar_entrega"
                                class="block mt-1 w-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-md"
                                type="text" name="lugar_entrega" wire:model="lugar_entrega" />
                            <x-input-error :messages="$errors->get('lugar_entrega')" class="mt-2" />
                        </div>
                        <p class="text-sm text-gray-500">Costo de envío: <span
                                class="font-semibold text-gray-700">$<span x-text="valorEnvio"></span></span></p>
                    </div>

                    <!-- Lugar de la tienda -->
                    <div x-show="!entrega_domicilio" class="space-y-2">
                        <label for="lugar_tienda" class="block text-sm font-medium text-gray-700">Lugar de la
                            tienda</label>
                        <p class="text-sm text-gray-500">{{ $producto->vendedor->lugar_tienda }}</p>
                    </div>
                </div>

                <!-- Método de pago -->
                <div x-data="{
                    metodoPago: '',
                    total: {{ $producto->precio }},
                    valorEnvio: {{ $producto->precio_domicilio }},
                    entregaDomicilio: @entangle('entrega_domicilio'),
                    Cantidad: @entangle('cantidad'),
                    formatearNumero(valor) {
                        return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'COP' }).format(valor);
                    },
                    actualizarTotal() {
                        this.total = (this.entregaDomicilio ? {{ $producto->precio }} + this.valorEnvio : {{ $producto->precio }}) * this.Cantidad;
                    }
                }" x-init="actualizarTotal();
                $watch('entregaDomicilio', value => actualizarTotal()), $watch('cantidad', value => actualizarTotal())" class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Método de pago</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="metodo_pago" value="efectivo" x-model="metodoPago" wire:model="metodo_pago"
                                class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Efectivo</span>

                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="metodo_pago" value="nequi" x-model="metodoPago" wire:model="metodo_pago"
                                class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Nequi</span>
                        </label>


                    </div>
                    <x-input-error :messages="$errors->get('metodo_pago')" class="mt-2" />


                    <!-- Pago en efectivo -->
                    <div x-show="metodoPago === 'efectivo'" class="mt-4">
                        <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                class="font-semibold text-gray-700" x-text="formatearNumero(total)"></span></p>
                    </div>

                    <!-- Pago con Nequi -->
                    <div x-show="metodoPago === 'nequi'" class="space-y-4">
                        <p class="text-sm text-gray-500">Total a transferir: <span class="font-semibold text-gray-700"
                                x-text="formatearNumero(total)"></span></p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sube tu comprobante de Nequi</label>
                            <input type="file" name="comprobante" wire:model="comprobante"
                                class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <x-input-error :messages="$errors->get('comprobante')" class="mt-2" />

                    </div>
                </div>


                <div class="flex justify-end space-x-4 mt-4">
                    <x-danger-button x-on:click="$dispatch('close')" wire:click="resetInputs" type="button">
                        {{ __('Cancel') }}
                    </x-danger-button>

                    <x-primary-button type="submit">
                        Confirmar compra
                    </x-primary-button>
                </div>
            </form>



        </div>
    </x-modal>
    @include('components.alert-component')
</div>
