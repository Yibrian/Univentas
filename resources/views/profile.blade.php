<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>

        <x-breadcrumbs :breadcrumbs="[
            ['title' => 'Inicio', 'url' => route('dashboard')],
            ['title' => 'Perfil', 'url' => null] 
        ]" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <livewire:cliente.cliente-info />
                </div>
            </div>

            {{-- @role('admin')
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            @endrole --}}
            
        </div>
    </div>
   
    @push('css')
    <style>
        @media (max-width: 900px) {
            .hide-on-mobile {
                display: none !important;
            }
        }
    </style>
    @endpush
</x-app-layout>
