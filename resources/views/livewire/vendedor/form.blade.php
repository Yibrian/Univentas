<?php
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Vendedor;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Role;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public $vendedor;
    public $nombre_tienda;
    public $descripcion;
    public $foto_tienda;
    public $foto_tienda_temp;
    public $numero_nequi;
    public $qr_nequi;
    public $lugar_tienda;

    public function mount()
    {
        $this->vendedor = Auth::user()->vendedor;
        if ($this->vendedor) {
            $this->nombre_tienda = $this->vendedor->nombre_tienda;
            $this->descripcion = $this->vendedor->descripcion;
            $this->foto_tienda = $this->vendedor->foto_tienda;
            $this->numero_nequi = $this->vendedor->numero_nequi;
            $this->lugar_tienda = $this->vendedor->lugar_tienda;
            $this->qr_nequi = $this->vendedor->qr_nequi;
        }
    }

    public function updatedFotoTiendaTemp()
    {
        $this->validate([
            'foto_tienda_temp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    }

    public function eliminarQR()
    {
        if ($this->qr_nequi) {
            Storage::disk('public')->delete($this->vendedor->qr_nequi);
            $this->qr_nequi = null;
            $this->vendedor->qr_nequi = null;
            $this->vendedor-> save();

            $this->dispatch('toast', ['title' => __('Se ha eliminado el QR.'), 'type' => 'success', 'message' => '']);
        }
    }

    public function save()
    {
        $validatedData = $this->validate([
            'nombre_tienda' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto_tienda_temp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'numero_nequi' => 'nullable|string|max:255',
            'lugar_tienda' => 'required|string|max:255',
            'qr_nequi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Manejo de 'foto_tienda'
        if ($this->foto_tienda_temp) {
            if ($this->vendedor && $this->vendedor->foto_tienda && $this->vendedor->foto_tienda != 'tiendas/tienda_default.png') {
                Storage::disk('public')->delete($this->vendedor->foto_tienda);
            }
            $pathFotoTienda = $this->foto_tienda_temp->store('tiendas', 'public');
        } else {
            $pathFotoTienda = $this->vendedor->foto_tienda ?? 'tiendas/tienda_default.png';
        }

        // Manejo de 'qr_nequi'
        if ($this->qr_nequi) {
            // Si ya existe un QR, eliminarlo
            if ($this->vendedor && $this->vendedor->qr_nequi) {
                Storage::disk('public')->delete($this->vendedor->qr_nequi);
            }
            $pathQrNequi = $this->qr_nequi->store('qr_tiendas', 'public');
        } else {
            $pathQrNequi = null;
        }

        // Guardar datos
        if ($this->vendedor) {
            $this->vendedor->update([
                'nombre_tienda' => $this->nombre_tienda,
                'descripcion' => $this->descripcion,
                'foto_tienda' => $pathFotoTienda,
                'numero_nequi' => $this->numero_nequi,
                'lugar_tienda' => $this->lugar_tienda,
                'qr_nequi' => $pathQrNequi,
            ]);

            session()->flash('message', 'Vendedor actualizado exitosamente.');
            $this->dispatch('toast', ['title' => __('Vendedor actualizado exitosamente.'), 'type' => 'success', 'message' => '']);
        } else {
            Vendedor::create([
                'user_id' => auth()->id(),
                'nombre_tienda' => $this->nombre_tienda,
                'descripcion' => $this->descripcion,
                'foto_tienda' => $pathFotoTienda,
                'numero_nequi' => $this->numero_nequi,
                'lugar_tienda' => $this->lugar_tienda,
                'qr_nequi' => $pathQrNequi,
            ]);

            Auth::user()->assignRole('vendedor');

            $this->dispatch('toast', ['title' => __('Vendedor creado exitosamente.'), 'type' => 'success', 'message' => '']);
            $this->dispatch('reload');
        }
    }
};
?>


@if (Auth::user()->vendedor)
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Actualizar tienda') }}
        </h2>
        <x-breadcrumbs :breadcrumbs="[
            ['title' => 'Inicio', 'url' => route('dashboard')],
            ['title' => 'Actualización de tienda', 'url' => null],
        ]" />
    </x-slot>
@else
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar tienda') }}
        </h2>
        <x-breadcrumbs :breadcrumbs="[
            ['title' => 'Inicio', 'url' => route('dashboard')],
            ['title' => 'Registro de tienda', 'url' => null],
        ]" />
    </x-slot>
@endif


<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form wire:submit.prevent="save" class="space-y-8" enctype="multipart/form-data">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Nombre de la Tienda -->
                    <div>
                        <x-input-label for="nombre_tienda" :value="__('Nombre de la Tienda')" />
                        <x-text-input wire:model="nombre_tienda" id="nombre_tienda" class="mt-1 w-full" type="text" required autocomplete="nombre_tienda" />
                        <x-input-error :messages="$errors->get('nombre_tienda')" class="mt-2" />
                    </div>

                    <!-- Ubicación breve de la Tienda -->
                    <div>
                        <x-input-label for="lugar_tienda" :value="__('Ubicación breve de la Tienda')" />
                        <x-text-input wire:model="lugar_tienda" id="lugar_tienda" class="mt-1 w-full" type="text" required autocomplete="lugar_tienda" />
                        <x-input-error :messages="$errors->get('lugar_tienda')" class="mt-2" />
                    </div>

                    <!-- Descripción -->
                    <div class="col-span-1 sm:col-span-2">
                        <x-input-label for="descripcion" :value="__('Descripción')" />
                        <textarea wire:model="descripcion" id="descripcion" class="mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4"></textarea>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <!-- Número de Nequi -->
                    <div>
                        <x-input-label for="numero_nequi" :value="__('Número de cuenta Nequi (opcional)')" />
                        <x-text-input wire:model="numero_nequi" id="numero_nequi" class="mt-1 w-full" type="text" autocomplete="numero_nequi" />
                        <x-input-error :messages="$errors->get('numero_nequi')" class="mt-2" />
                    </div>

                    <!-- QR Opcional -->
                    <div class="col-span-1 sm:col-span-2">
                        <x-input-label for="qr_nequi" :value="__('QR (opcional)')" />
                        <input type="file" id="qr_nequi" wire:model="qr_nequi" class="mt-1 w-full" accept="image/*">
                        @if ($vendedor->qr_nequi)
                            <div class="flex items-center gap-2 mt-2">
                                <button wire:click="eliminarQR" class="bg-red-500 text-white px-3 py-1 rounded-lg">Eliminar QR</button>
                                <a href="{{ asset('storage/' . $vendedor->qr_nequi) }}" target="_blank" class="text-blue-600 hover:underline">Ver QR</a>
                            </div>
                        @endif
                    </div>

                    <!-- Foto de la Tienda -->
                    <div class="col-span-1 sm:col-span-2">
                        <x-input-label for="foto_tienda_temp" :value="__('Foto de la Tienda')" />
                        <input wire:model="foto_tienda_temp" id="foto_tienda_temp" class="mt-1 w-full text-sm text-gray-500 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="file" accept="image/*" />
                        <x-input-error :messages="$errors->get('foto_tienda_temp')" class="mt-2" />
                        <div class="mt-2">
                            @if ($foto_tienda_temp)
                                <img src="{{ $foto_tienda_temp->temporaryUrl() }}" class="w-32 h-32 object-cover" alt="Previsualización de la imagen">
                            @elseif($vendedor && $vendedor->foto_tienda)
                                <img src="{{ asset('storage/' . $vendedor->foto_tienda) }}" class="w-32 h-32 object-cover" alt="Foto de la tienda">
                            @else
                                <img src="{{ asset('storage/tiendas/tienda_default.png') }}" class="w-32 h-32 object-cover" alt="Foto por defecto">
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Botones de Acción -->
                <div class="flex justify-between items-center mt-4">
                    <button type="submit" class="px-4 py-2 bg-red-700 text-white rounded-md hover:bg-red-600 focus:bg-red-600 active:bg-red-800">Guardar</button>
                    <x-action-message class="me-3" on="saved">Guardado.</x-action-message>
                </div>
            </form>
        </div>
    </div>
    @include('components.alert-component')
</div>
