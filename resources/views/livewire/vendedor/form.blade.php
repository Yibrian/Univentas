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

        }
    }

    public function updatedFotoTiendaTemp()
    {
        $this->validate([
            'foto_tienda_temp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    }

    public function save()
    {
        $validatedData = $this->validate([
            'nombre_tienda' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'foto_tienda_temp' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'numero_nequi' => 'nullable|string|max:255',
            'lugar_tienda' =>'required|string|max:255',
        ]);

        if ($this->foto_tienda_temp) {
            if ($this->vendedor && $this->vendedor->foto_tienda && $this->vendedor->foto_tienda != 'tiendas/tienda_default.png') {
                Storage::disk('public')->delete($this->vendedor->foto_tienda);
            }
            $path = $this->foto_tienda_temp->store('tiendas', 'public');
        } else {
            $path = $this->vendedor->foto_tienda ?? 'tiendas/tienda_default.png';
        }

        if ($this->vendedor) {
            $this->vendedor->update([
                'nombre_tienda' => $this->nombre_tienda,
                'descripcion' => $this->descripcion,
                'foto_tienda' => $path,
                'numero_nequi' => $this->numero_nequi,
                'lugar_tienda' => $this->lugar_tienda
            ]);
            session()->flash('message', 'Vendedor actualizado exitosamente.');
            $this->dispatch('toast', ['title' => __('Vendedor actualizado exitosamente.'), 'type' => 'success', 'message' => '']);

        } else {
            Vendedor::create([
                'user_id' => auth()->id(),
                'nombre_tienda' => $this->nombre_tienda,
                'descripcion' => $this->descripcion,
                'foto_tienda' => $path,
                'numero_nequi' => $this->numero_nequi,
                'lugar_tienda' => $this->lugar_tienda

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
        <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Actualización de tienda', 'url' => null]]" />
    </x-slot>
@else
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar tienda') }}
        </h2>
        <x-breadcrumbs :breadcrumbs="[['title' => 'Inicio', 'url' => route('dashboard')], ['title' => 'Registro de tienda', 'url' => null]]" />
    </x-slot>
@endif


<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">


            <form wire:submit.prevent="save" class="mt-6 space-y-6"  enctype="multipart/form-data">
                <div class="mt-4">
                    <x-input-label for="nombre_tienda" :value="__('Nombre de la Tienda')" />
                    <x-text-input wire:model="nombre_tienda" id="nombre_tienda" class="block mt-1 w-full" type="text"
                        name="nombre_tienda" required autocomplete="nombre_tienda" />
                    <x-input-error :messages="$errors->get('nombre_tienda')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="lugar_tienda" :value="__('Ubicación breve de la Tienda')" />
                    <x-text-input wire:model="lugar_tienda" id="lugar_tienda" class="block mt-1 w-full" type="text"
                        name="lugar_tienda" required autocomplete="lugar_tienda" />
                    <x-input-error :messages="$errors->get('lugar_tienda')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="descripcion" :value="__('Descripción')" />
                    <textarea wire:model="descripcion" id="descripcion"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        name="descripcion" rows="4"></textarea>
                    <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                </div>
                <div class="mt-4">
                    <x-input-label for="numero_nequi" :value="__('Escribe sin espacios el número asociado a la cuenta de Nequi (si solo vas a recibir efectivo dejar el campo vacío)')" />
                    <x-text-input wire:model="numero_nequi" id="numero_nequi" class="block mt-1 w-full" type="text"
                        name="numero_nequi" autocomplete="numero_nequi" />
                    <x-input-error :messages="$errors->get('numero_nequi')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="foto_tienda_temp" :value="__('Foto de la Tienda')" />
                    <input wire:model="foto_tienda_temp" id="foto_tienda_temp"
                        class="block mt-1 w-full text-sm text-gray-500 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        type="file" accept="image/*" />
                    <x-input-error :messages="$errors->get('foto_tienda_temp')" class="mt-2" />

                    @if ($foto_tienda_temp)
                        <img src="{{ $foto_tienda_temp->temporaryUrl() }}" class="mt-2 w-32 h-32 object-cover"
                            alt="Previsualización de la imagen">
                    @elseif($vendedor && $vendedor->foto_tienda)
                        <img src="{{ asset('storage/' . $vendedor->foto_tienda) }}" class="mt-2 w-32 h-32 object-cover"
                            alt="Foto de la tienda">
                    @else
                        <img src="{{ asset('storage/tiendas/tienda_default.png') }}" class="mt-2 w-32 h-32 object-cover"
                            alt="Foto por defecto">
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Guardar') }}
                    </button>
                    <x-action-message class="me-3" on="saved">
                        {{ __('Guardado.') }}
                    </x-action-message>
                </div>
            </form>


        </div>
    </div>

    @include('components.alert-component')

</div>
