<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Gestionar cupones') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Cupones', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white p-6 shadow sm:rounded-lg">

            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-cupon')"
                class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add new') }}
            </button>

            <div class="max-w-full overflow-x-auto mt-6" style="overflow-x: auto; overflow-y: hidden">
                <x-datatable />
                <table class="w-full bg-white border border-gray-200 mt-6" id="DataTable">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm leading-normal">
                            <th class="py-2 px-6 text-left">{{ __('Codigo') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Tipo') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Descuento') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Usos') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Fecha de expiración') }}</th>
                            <th class="py-2 px-6 text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($cupones as $cupon)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-2 px-6 text-left">{{ $cupon->codigo }}</td>
                                <td class="py-2 px-6 text-left">{{ $cupon->tipo }}</td>
                                <td class="py-2 px-6 text-left">
                                    @if ($cupon->tipo === 'porcentaje')
                                        {{ $cupon->descuento }}%
                                    @elseif ($cupon->tipo === 'monto')
                                        ${{ number_format($cupon->descuento, 2) }}
                                    @endif
                                </td>
                                <td class="py-2 px-6 text-left">{{ $cupon->usos }}</td>
                                <td class="py-2 px-6 text-left">{{ $cupon->fecha_expiracion }}</td>
                                <td class="py-2 px-6 flex space-x-4 text-center justify-center">
                                    <a wire:click.prevent="viewCupon('{{ $cupon->id }}')"
                                        x-on:click="$dispatch('open-modal', 'update-cupon')" class="cursor-pointer"
                                        title="{{ 'Actualizar cupón' }}">
                                        <i class="fa-solid fa-pen-to-square text-xl " style="color: #B91C1C"></i>
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
            <x-modal name="create-cupon" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 text-center mb-4 ">
                        Agregar nuevo cupón
                    </h2>
                    <form wire:submit.prevent="store">

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Código -->
                            <div>
                                <x-input-label for="codigo" :value="__('Código')" />
                                <x-text-input wire:model="codigo" id="codigo" class="mt-1 w-full uppercase"
                                    type="text" required autocomplete="codigo" />
                                <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
                            </div>

                            <!-- Tipo -->
                            <div>
                                <x-input-label for="tipo" :value="__('Tipo')" />
                                <select wire:model="tipo" id="tipo"
                                    class="mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">{{ __('Seleccione un tipo') }}</option>
                                    <option value="porcentaje">{{ __('Porcentaje') }}</option>
                                    <option value="monto">{{ __('Monto') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
                            </div>
                            <!-- Descuento -->
                            <div>
                                <x-input-label for="descuento" :value="__('Descuento')" />
                                <x-text-input wire:model="descuento" id="descuento" class="mt-1 w-full" type="number"
                                    step="0.01" required autocomplete="descuento" />
                                <x-input-error :messages="$errors->get('descuento')" class="mt-2" />
                            </div>



                            <!-- Usos -->
                            <div>
                                <x-input-label for="usos" :value="__('Usos Máximos')" />
                                <x-text-input wire:model="usos" id="usos" class="mt-1 w-full" type="number"
                                    min="1" required autocomplete="usos" />
                                <x-input-error :messages="$errors->get('usos')" class="mt-2" />
                            </div>

                            <!-- Fecha de Expiración -->
                            <div class="col-span-2">
                                <x-input-label for="fecha_expiracion" :value="__('Fecha de Expiración')" />
                                <x-text-input wire:model="fecha_expiracion" id="fecha_expiracion" class="mt-1 w-full"
                                    type="date" required autocomplete="fecha_expiracion" />
                                <x-input-error :messages="$errors->get('fecha_expiracion')" class="mt-2" />
                            </div>
                        </div>


                        <div class="mt-6 flex justify-end">
                            <x-danger-button x-on:click="$dispatch('close')" wire:click="reload" type="button">
                                {{ __('Cancel') }}
                            </x-danger-button>

                            <x-primary-button class="ms-3" type="submit">
                                {{ 'Crear cupón' }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </x-modal>



            <x-modal name="update-cupon" :show="$errors->isNotEmpty()">

                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6 text-center">
                        {{ 'Actualizar cupón' }}
                    </h2>
                    @if ($SelectCupon)
                        <form wire:submit.prevent="updateCupon">
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Código -->
                                <div>
                                    <x-input-label for="codigo" :value="__('Código')" />
                                    <x-text-input wire:model="codigo" id="codigo" class="mt-1 w-full uppercase"
                                        type="text" required autocomplete="codigo" />
                                    <x-input-error :messages="$errors->get('codigo')" class="mt-2" />
                                </div>
    
                                <!-- Tipo -->
                                <div>
                                    <x-input-label for="tipo" :value="__('Tipo')" />
                                    <select wire:model="tipo" id="tipo"
                                        class="mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">{{ __('Seleccione un tipo') }}</option>
                                        <option value="porcentaje">{{ __('Porcentaje') }}</option>
                                        <option value="monto">{{ __('Monto') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
                                </div>
                                <!-- Descuento -->
                                <div>
                                    <x-input-label for="descuento" :value="__('Descuento')" />
                                    <x-text-input wire:model="descuento" id="descuento" class="mt-1 w-full" type="number"
                                        step="0.01" required autocomplete="descuento" />
                                    <x-input-error :messages="$errors->get('descuento')" class="mt-2" />
                                </div>
    
    
    
                                <!-- Usos -->
                                <div>
                                    <x-input-label for="usos" :value="__('Usos Máximos')" />
                                    <x-text-input wire:model="usos" id="usos" class="mt-1 w-full" type="number"
                                        min="1" required autocomplete="usos" />
                                    <x-input-error :messages="$errors->get('usos')" class="mt-2" />
                                </div>
    
                                <!-- Fecha de Expiración -->
                                <div class="col-span-2">
                                    <x-input-label for="fecha_expiracion" :value="__('Fecha de Expiración')" />
                                    <x-text-input wire:model="fecha_expiracion" id="fecha_expiracion" class="mt-1 w-full"
                                        type="date" required autocomplete="fecha_expiracion" />
                                    <x-input-error :messages="$errors->get('fecha_expiracion')" class="mt-2" />
                                </div>
                            </div>
    
                            <div class="mt-6 flex justify-end">
                                <x-danger-button x-on:click="$dispatch('close')" wire:click="reload" type="button">
                                    {{ __('Cancel') }}
                                </x-danger-button>
        
                                <x-primary-button class="ms-3" type="submit">
                                    {{ __('Update') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif

                 


                </div>
            </x-modal>
        </div>
    </div>

    @include('components.alert-component')




</div>
</div>
</div>
