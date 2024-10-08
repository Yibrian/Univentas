<?php
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use App\Models\Role;

new class extends Component {
    public $telefono;
    public $direccion;
    public $genero;
    public $fecha_nac;

    public function mount()
    {
        if (Auth::user()->cliente) {
            $this->telefono = Auth::user()->cliente->telefono;
            $this->direccion = Auth::user()->cliente->direccion;
            $this->genero = Auth::user()->cliente->genero;
            $this->fecha_nac = Auth::user()->cliente->fecha_nac;
        }
    }

    public function updatecliente()
    {
        $cliente = Auth::user()->cliente;

        $validated = $this->validate([
            'telefono' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'genero' => ['required', 'in:masculino,femenino,otro'],
            'fecha_nac' => ['required', 'date', 'before:today'],
        ]);

        if($cliente){
            $cliente->fill($validated);
            $cliente->save();
        }else{
            $validated['user_id'] = Auth::user()->id;
            $cliente = Cliente::create($validated);
            Auth::user()->assignRole('cliente');

        }


        $this->dispatch('profile-updated', name: $cliente->user->name);
    }
};
?>


<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información cliente') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Actualice la información de sus datos como cliente!') }}
        </p>
    </header>

    <div style="display: flex; justify-content: space-between">
        <div style="width: 36rem">
            <form wire:submit.prevent="updatecliente" class="mt-6 space-y-6">
                <!-- Telefono -->
                <div class="mt-4">
                    <x-input-label for="telefono" :value="__('Teléfono')" />
                    <x-text-input wire:model="telefono" id="telefono" class="block mt-1 w-full" type="text"
                        name="telefono" required autocomplete="telefono" />
                    <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                </div>

                <!-- Direccion -->
                <div class="mt-4">
                    <x-input-label for="direccion" :value="__('Dirección')" />
                    <x-text-input wire:model="direccion" id="direccion" class="block mt-1 w-full" type="text"
                        name="direccion" required autocomplete="direccion" />
                    <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                </div>
                <!-- Genero -->
                <div class="mt-4">
                    <x-input-label for="genero" :value="__('Género')" />
                    <select wire:model="genero" id="genero" required
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">{{ __('Seleccione su género') }}</option>
                        <option value="masculino">{{ __('Masculino') }}</option>
                        <option value="femenino">{{ __('Femenino') }}</option>
                        <option value="otro">{{ __('Otro') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('genero')" class="mt-2" />
                </div>

                <!-- Fecha Nacimiento -->
                <div class="mt-4">
                    <x-input-label for="fecha_nac" :value="__('Fecha de Nacimiento')" />
                    <x-text-input wire:model="fecha_nac" id="fecha_nac" class="block mt-1 w-full" type="date"
                        name="fecha_nac" required autocomplete="fecha_nac" />
                    <x-input-error :messages="$errors->get('fecha_nac')" class="mt-2" />
                </div>



                <div class="flex items-center gap-4">
                    <button type="submit"
                        class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Save') }}
                    </button>
                    <x-action-message class="me-3" on="profile-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>
        </div>
    </div>

</section>
