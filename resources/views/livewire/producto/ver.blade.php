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
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fa-solid fa-star {{ $i <= $calificacion ? 'text-yellow-400' : 'text-gray-300' }}"></i>
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
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4">
                <div class="flex-1">
                    <h4 class="text-2xl font-bold text-gray-800">Tienda:</h4>
                    <p class="text-xl font-semibold text-gray-600 mt-1">
                        {{ $producto->vendedor->lugar_entrega_tienda }}
                    </p>
                    <p class="mt-2 text-gray-500">{{ $producto->vendedor->descripcion }}</p>
                    <p class="mt-2 text-gray-500">Cantidad de productos en esta tienda:
                        {{ $producto->vendedor->productos->count() }}</p>


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

        <div class="p-4 sm:p-8 bg-gray-50 shadow rounded-lg mt-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 bg-white">
                <h2 class="text-xl font-bold text-gray-700">Reseñas</h2>
            </div>

            <div class="mt-4">
                @if ($reviews && $reviews->isNotEmpty())
                    @foreach ($reviews as $review)
                        <div class="flex items-start mb-4 p-4 border-b border-gray-200">
                            <img src="{{ asset('storage/' . $review->cliente->user->photo) }}"
                                alt="Foto de {{ $review->cliente->user->name }}"
                                class="w-10 h-10 rounded-full border border-gray-300 mr-4">

                            <div class="flex-1">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fa-solid fa-star {{ $i <= $review->estrellas ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <!-- Comentario -->
                                @if ($review->comentario)
                                    <p class="text-gray-700 mt-1">{{ $review->comentario }}</p>
                                @else
                                    <p class="text-gray-500 mt-1 italic">{{ __('Sin comentario') }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500">{{ __('No hay reseñas disponibles.') }}</p>
                @endif
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
                            <span class="ml-3 text-sm font-medium text-gray-700">¿Quieres envío a domicilio? (Local,
                                Buga Valle)</span>
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
                        const valorFinal = valor < 0 ? 0 : valor;
                        return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'COP' }).format(valorFinal);
                    },
                
                    actualizarTotal() {
                        this.total = (this.entregaDomicilio ? {{ $producto->precio }} + this.valorEnvio : {{ $producto->precio }}) * this.Cantidad;
                
                    }
                }" x-init="actualizarTotal();
                $watch('entregaDomicilio', value => actualizarTotal()), $watch('cantidad', value => actualizarTotal()), $watch('codigo_cupon', value => actualizarTotal())" class="space-y-4">
                    <div class="space-y-4">
                        <x-input-label for="codigo_cupon" :value="__('Código de cupón')" />
                        <div class="flex space-x-4">
                            <x-text-input wire:model="codigo_cupon" id="codigo_cupon" class="block mt-1 w-full"
                                type="text" name="codigo_cupon" placeholder="Ingresa tu cupón aquí" />
                            <x-primary-button type="button" wire:click="aplicarCupon">
                                Aplicar
                            </x-primary-button>
                        </div>
                        <x-input-error :messages="$errors->get('codigo_cupon')" class="mt-2" />
                        @if ($cupon)
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4"
                                role="alert">
                                <span class="block sm:inline">
                                    @if ($cupon->tipo === 'porcentaje')
                                        Se aplicará un descuento de {{ $cupon->descuento }}% al total.
                                    @elseif ($cupon->tipo === 'monto')
                                        Se aplicará un descuento de
                                        ${{ number_format($cupon->descuento, 2, ',', '.') }} al
                                        total.
                                    @endif
                                </span>
                            </div>
                        @endif

                    </div>
                    <label class="block text-sm font-medium text-gray-700">Método de pago</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="metodo_pago" value="efectivo" x-model="metodoPago"
                                wire:model="metodo_pago"
                                class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm font-medium text-gray-700">Efectivo</span>

                        </label>
                        @if ($producto->vendedor->numero_nequi)
                            <label class="inline-flex items-center">
                                <input type="radio" name="metodo_pago" value="nequi" x-model="metodoPago"
                                    wire:model="metodo_pago"
                                    class="form-radio h-5 w-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Nequi</span>
                            </label>
                        @endif



                    </div>
                    <x-input-error :messages="$errors->get('metodo_pago')" class="mt-2" />


                    <!-- Pago en efectivo -->
                    <div x-show="metodoPago === 'efectivo'" class="mt-4">
                        @switch($tipo)
                            @case('monto')
                                <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                        class="font-semibold text-gray-700"
                                        x-text="formatearNumero(total - {{ $descuento ?? 0 }})"></span></p>
                            @break

                            @case('porcentaje')
                                <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                        class="font-semibold text-gray-700"
                                        x-text="formatearNumero(total - (total * ({{ $descuento ?? 0 }} / 100)))"></span></p>
                            @break

                            <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                    class="font-semibold text-gray-700" x-text="formatearNumero(total)"></span></p>

                            @default
                        @endswitch
                        @if (!$tipo)
                            <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                    class="font-semibold text-gray-700" x-text="formatearNumero(total)"></span></p>
                        @endif


                    </div>

                    @if ($producto->vendedor->numero_nequi)
                        <!-- Pago con Nequi -->
                        <div x-show="metodoPago === 'nequi'" class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700">Número de NEQUI de la tienda:
                                {{ $producto->vendedor->numero_nequi }}</label>
                            @if ($producto->vendedor->qr_nequi)
                                <a href="{{ asset('storage/' . $producto->vendedor->qr_nequi) }}" target="_blank"
                                    class="text-blue-600 hover:underline">Ver QR</a>
                            @endif
                            @switch($tipo)
                                @case('monto')
                                    <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                            class="font-semibold text-gray-700"
                                            x-text="formatearNumero(total - {{ $descuento ?? 0 }})"></span></p>
                                @break

                                @case('porcentaje')
                                    <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                            class="font-semibold text-gray-700"
                                            x-text="formatearNumero(total - (total * ({{ $descuento ?? 0 }} / 100)))"></span>
                                    </p>
                                @break

                                <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                        class="font-semibold text-gray-700" x-text="formatearNumero(total)"></span></p>

                                @default
                            @endswitch
                            @if (!$tipo)
                                <p class="text-sm text-gray-500">Total a pagar en efectivo: <span
                                        class="font-semibold text-gray-700" x-text="formatearNumero(total)"></span>
                                </p>
                            @endif


                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sube tu comprobante de
                                    Nequi</label>
                                <input type="file" name="comprobante" wire:model="comprobante" accept="image/*"
                                    class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <x-input-error :messages="$errors->get('comprobante')" class="mt-2" />

                        </div>
                    @endif


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
