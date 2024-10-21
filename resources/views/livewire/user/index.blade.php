<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Users') }}
    </h2>
    <x-breadcrumbs :breadcrumbs="[
        ['title' => 'Inicio', 'url' => route('dashboard')],
        ['title' => 'Usuarios', 'url' => null] 
    ]" />
</x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-user')"  class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Add new') }}
                </button>

            <div class="max-w-full overflow-x-auto mt-6" style="overflow-x: auto; overflow-y: hidden">
                <x-datatable />
                <table class="w-full bg-white border border-gray-200 mt-6" id="DataTable">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm leading-normal">
                            <th class="py-2 px-6 text-left">{{ __('Name') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Cédula') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Email') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Rol') }}</th>
                            <th class="py-2 px-6 text-left">{{ __('Status') }}</th>
                            <th class="py-2 px-6 text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-2 px-6 text-left text-gray-900 flex items-center">
                                    <img src="{{ asset('storage/' . $user->photo) }}" alt="User Image"
                                        class="rounded-full"
                                        style="width: 32px; height: 32px; margin-right: 8px; object-fit: cover">
                                    {{ $user->name }}
                                </td>
                                <td class="py-2 px-6 text-left">{{ $user->cedula }}</td>
                                <td class="py-2 px-6 text-left">{{ $user->email }}</td>
                                <td class="py-2 px-6 text-left">
                                    {{ strtoupper(
                                        $user->getRoleNames()->first() === 'admin' ? 'Admin' : 
                                        ($user->hasRole('vendedor') ? 'vendedor' : $user->getRoleNames()->first())
                                    ) }}
                                </td>
                                                                <td class="py-2 px-6 text-left">
                                    @if ($user->is_active)
                                        <span
                                            style="display: inline-block; width: 10px; height: 10px; background-color: green; border-radius: 50%; margin-right: 5px;"></span>
                                        {{ __('Active') }}
                                    @else
                                        <span
                                            style="display: inline-block; width: 10px; height: 10px; background-color: gray; border-radius: 50%; margin-right: 5px;"></span>
                                        {{ __('Inactive') }}
                                    @endif
                                </td>
                                <td class="py-2 px-6 flex space-x-4 text-center justify-center">

                                    <a wire:click.prevent="viewUser('{{ $user->id }}')"
                                        x-on:click="$dispatch('open-modal', 'update-user')" class="cursor-pointer"
                                        title="{{ __('Update') . ' ' . strtolower(__('User')) }}">
                                        <i class="fa-solid fa-user-pen text-xl " style="color: #B91C1C"></i>
                                    </a>


                                    <a class="cursor-pointer" wire:click.prevent="changeStatus({{ $user }})"
                                        title="{{ __('Switch') . ' ' . strtolower(__('Status')) }}">
                                        <i class="fa-solid fa-rotate text-xl " style="color: #B91C1C"></i>
                                    </a>

                                    <a class="cursor-pointer" wire:click.prevent="deleteUser({{ $user }})"
                                        title="{{ __('Delete') . ' ' . strtolower(__('User')) }}">
                                        <i class="fa-solid fa-trash text-xl" style="color: #B91C1C"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <x-modal name="create-user" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 text-center mb-4 ">
                        {{ __('Add new') . ' ' . strtolower(__('User')) }}
                    </h2>
                    <form wire:submit.prevent="storeUser">
                        @include('livewire.user.form')
                        <div class="mt-6 flex justify-end">
                            <x-danger-button x-on:click="$dispatch('close')"  wire:click="reload"  type="button">
                                {{ __('Cancel') }}
                            </x-danger-button>

                            <x-primary-button class="ms-3" type="submit">
                                {{ __('Create') . ' ' . __('User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </x-modal>



            <x-modal name="update-user" :show="$errors->isNotEmpty()">

                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-6 text-center">
                        {{ __('Update') . ' ' . strtolower(__('User')) }}
                    </h2>
                    @if ($selectUser)
                        <form wire:submit.prevent="updateUser">

                            @include('livewire.user.form')

                            <div class="mt-6 flex justify-end">
                                <x-danger-button x-on:click="$dispatch('close')" wire:click="reload" type="button">
                                    {{ __('Cancel') }}
                                </x-danger-button>

                                <x-primary-button class="ms-3" type="submit">
                                    {{ __('Update') . ' ' . __('User') }}
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
