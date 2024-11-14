<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Gestión de categorias') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Categorias', 'url' => null]]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-categoria')"
                class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Añadir Categoria') }}
            </button>

            <div class="max-w-full overflow-x-auto mt-6" style="overflow-x: auto; overflow-y: hidden">
                <x-datatable />
                <table class="w-full bg-white border border-gray-200 mt-6" id="DataTable">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm leading-normal">
                            <th class="py-2 px-6 text-left">{{ __('Foto') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Nombre') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($categorias as $categoria)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-2 px-6 text-left">
                                    <img src="{{ asset('storage/' . $categoria->photo) }}"
                                        alt="Foto de {{ $categoria->nombre }}" class="w-full h-20 object-cover">
                                </td>
                                <td class="py-2 px-6 text-left flex items-center justify-between">
                                    {{ $categoria->nombre }}
                                    <a wire:click.prevent="viewCategoria('{{ $categoria->id }}')"
                                       x-on:click="$dispatch('open-modal', 'update-categoria')" 
                                       class="cursor-pointer"
                                       title="{{ __('Actualizar') . ' ' . strtolower(__('Categoria')) }}">
                                        <i class="fa-solid fa-pen-to-square text-xl" style="color: #B91C1C"></i>
                                    </a>
                                </td>
                                

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            <x-modal name="create-categoria" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 text-center mb-4 ">
                        {{ __('Add new') . ' ' . strtolower(__('categoria')) }}
                    </h2>
                    <form wire:submit.prevent="storeCategoria" enctype="multipart/form-data">
                        @include('livewire.categoria.form')
                        <div class="mt-6 flex justify-end">
                            <x-danger-button x-on:click="$dispatch('close')" wire:click="reload" type="button">
                                {{ __('Cancel') }}
                            </x-danger-button>

                            <x-primary-button class="ms-3" type="submit">
                                {{ __('Create') . ' ' . __('categoria') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </x-modal>



            <x-modal name="update-categoria" :show="$errors->isNotEmpty()">

                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6 text-center">
                        {{ __('Update') . ' ' . strtolower(__('categoria')) }}
                    </h2>
                    @if ($selectCategoria)
                        <form wire:submit.prevent="updateCategoria">

                            @include('livewire.categoria.form')

                            <div class="mt-6 flex justify-end">
                                <x-danger-button x-on:click="$dispatch('close')" wire:click="reload" type="button">
                                    {{ __('Cancel') }}
                                </x-danger-button>

                                <x-primary-button class="ms-3" type="submit">
                                    {{ __('Update') . ' ' . __('Categoria') }}
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
