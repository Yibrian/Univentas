<?php

use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.guest')] class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $cedula = '';
    public string $password_confirmation = '';
    public $photo;

    //cliente
    public $telefono;
    public $direccion;
    public $genero;
    public $fecha_nac;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Validate the form data
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'lowercase', 'max:255', 'regex:/^[a-zA-Z0-9._%+-]+@correounivalle\.edu.co$/', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'cedula' => 'required|integer|min:1|unique:users',
            'photo' => 'nullable|image|max:2048|mimes:jpg,jpeg,png',
            'telefono' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'genero' => ['required', 'in:masculino,femenino,otro'],
            'fecha_nac' => ['required', 'date', 'before:today'],
        ]);

        $validated['name'] = strtoupper($validated['name']);

        $validated['password'] = Hash::make($validated['password']);

        if (isset($validated['photo'])) {
            $path = $this->photo->store('users', 'public');
            $validated['photo'] = $path;
        } else {
            $validated['photo'] = 'users/user-default.png';
        }

        $user = User::create($validated);

        $role = Role::findByName('cliente');
        $user->assignRole($role);

        $user->save();

        $validated['user_id'] = $user->id;

        $cliente = Cliente::create($validated);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>
<div>
    <h1 class="text-2xl font-bold mb-2 text-center">{{ __('Sign Up') }}</h1>
    <div class="flex justify-center items-center">
        <div class="w-full">
            <form wire:submit.prevent="register">
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input wire:model="name" id="name" class="block mt-1 w-full uppercase" type="text"
                        name="name" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email"
                        name="email" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Cedula -->
                <div class="mt-4">
                    <x-input-label for="cedula" :value="__('Cédula')" />
                    <x-text-input wire:model="cedula" id="cedula" class="block mt-1 w-full" type="text"
                        name="cedula" required autocomplete="cedula" />
                    <x-input-error :messages="$errors->get('cedula')" class="mt-2" />
                </div>

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

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password"
                        name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input wire:model="password_confirmation" id="password_confirmation"
                        class="block mt-1 w-full" type="password" name="password_confirmation" required
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    <div class="mt-2">
                        <label class="flex justify-end">
                            <input type="checkbox" id="show-password"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                onclick="togglePassword()" />
                            <span for="show-password" id="show-password-label"
                                class="ms-2 text-xs text-gray-600">{{ __('Show') . ' ' . strtolower(__('Password')) }}</span>
                        </label>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="mt-4 mb-2">
                    <x-input-label for="photo" :value="__('Foto de perfil')" class="mb-4" />
                    <div class="relative">
                        <input type="file" wire:model="photo" id="photo"
                            class="block w-full text-sm text-gray-500 
                            file:mr-4 file:py-2 file:px-4 file:rounded-full file:border file:border-gray-300
                            file:text-sm file:font-semibold file:bg-gray-100 hover:file:bg-gray-200
                            file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-300
                            dark:file:border-gray-600 dark:file:hover:bg-gray-600" />
                        <p id="photo-help" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Formato aceptado: JPG, PNG, máximo 2MB.') }}
                        </p>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('login') }}" wire:navigate>
                        {{ __('¿Ya tienes una cuenta?') }}
                    </a>

                    <button type="submit"
                        class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const passwordInput_confirm = document.getElementById('password_confirmation');
                const passwordLabel = document.getElementById('show-password-label');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordInput_confirm.type = 'text';
                    passwordLabel.textContent = '{{ __('Hide') . ' ' . strtolower(__('Password')) }}';
                } else {
                    passwordInput.type = 'password';
                    passwordInput_confirm.type = 'password';
                    passwordLabel.textContent = '{{ __('Show') . ' ' . strtolower(__('Password')) }}';
                }
            }
        </script>
    @endpush


</div>
