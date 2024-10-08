<?php
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div>
    <div class="flex justify-center items-center">
        <a href="#" wire:navigate>
            <x-application-logo class="w-44 h-44 fill-current text-gray-500 " />
        </a>

    </div>

    <div class="flex justify-center items-center">
        <form wire:submit="login" class="w-3/4">
            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email"
                    name="email" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                    name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />

            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mt-6">
                <label for="remember" class="inline-flex items-center">
                    <input wire:model="form.remember" id="remember" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-xs text-gray-600">{{ __('Remember me') }}</span>
                </label>



                <label for="show-password" class="inline-flex items-center">
                    <input type="checkbox" id="show-password"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        onclick="togglePassword()" />
                    <span
                        class="ms-2 text-xs text-gray-600 cursor-pointer">{{ __('Show') . ' ' . strtolower(__('Password')) }}</span>
                </label>
            </div>

            <div class="flex flex-col items-center justify-center mt-4 mb-8">
                <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full">
                    {{ __('Log in') }}
                </button>

            </div>


            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                @if (Route::has('register'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('register') }}" wire:navigate>
                        {{ __('¿Nuevo por aquí? Registrate!') }}
                    </a>
                @endif

            </div>

            <div class="flex items-center justify-center mt-4">
                <x-auth-session-status class="mb-4" :status="session('status')" />
            </div>
        </form>
    </div>

    @push('js')
        <script>
            function togglePassword() {
                const passwordInput = document.getElementById('password');
                const passwordLabel = document.getElementById('show-password-label');
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordLabel.textContent = '{{ __('Hide') . ' ' . strtolower(__('Password')) }}';
                } else {
                    passwordInput.type = 'password';
                    passwordLabel.textContent = '{{ __('Show') . ' ' . strtolower(__('Password')) }}';
                }
            }
        </script>
    @endpush
</div>
