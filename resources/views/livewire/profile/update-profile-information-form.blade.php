<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $cedula= '';
    public $photo;
    public $fileName = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->cedula = Auth::user()->cedula;
    }
    public function updatedPhoto()
    {
        $this->fileName = $this->photo->getClientOriginalName();
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'photo' => 'nullable|image|max:2048|mimes:jpg,jpeg,png',
        ]);

        if (isset($validated['photo'])) {
            if ($user->photo && $user->photo !== 'users/user-default.png') {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $this->photo->store('users', 'public');
            $validated['photo'] = $path;

        } else {
            $validated['photo'] = $user->photo ?: 'users/user-default.png';
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        $this->photo = null;
        $this->fileName = '';

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>
    <div style="display: flex; justify-content: space-between">
        <div style="width: 36rem">
            <form wire:submit.prevent="updateProfileInformation" class="mt-6 space-y-6" enctype="multipart/form-data">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full"
                        required  autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="email" name="email" type="email"
                        class="mt-1 block w-full" required autocomplete="username" readonly/>
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}
                                <button wire:click.prevent="sendVerification"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <x-input-label for="cedula" :value="__('Cédula')" />
                    <x-text-input wire:model="cedula" id="cedula" name="cedula" type="number"
                        class="mt-1 block w-full" required autocomplete="cedula" readonly/>
                    <x-input-error class="mt-2" :messages="$errors->get('cedula')" />
                </div>

                <div>
                    <label for="image"
                        class="block text-sm font-medium text-gray-700">{{ __('Select an image') }}:</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            
                            <div class="flex flex-col items-center text-sm text-gray-600">
                                <label style="cursor: pointer" for="file-upload"
                                    class="relative bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>{{ __('Upload an image') }}</span>
                                    <input id="file-upload" name="file-upload" type="file" class="sr-only"
                                        wire:model="photo">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">{{ __('PNG, JPG, JPEG up to 10MB') }}</p>
                            <div class="mt-4 flex justify-center text-sm text-gray-600">
                                @if ($fileName)
                                    Selected file: {{ $fileName }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Save') }}
                    </button>
                    <x-action-message class="me-3" on="profile-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>
        </div>

        <div class="flex items-center justify-center flex-col hide-on-mobile" style="width: 32rem; height: 25rem; max-height: 25rem;">
            <div class="flex items-center justify-center flex-col shadow sm:rounded-lg p-4" style="width: 22rem; height: 18rem;">
                <img class="rounded-full w-3/5 h-3/4 object-cover" src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Image">
                <h2 class="text-lg font-medium text-gray-900 text-center mt-4">
                    {{ strtoupper(auth()->user()->name) }}
                </h2>
                <h2 class="text-sm text-gray-600 text-center mt-1">
                    {{ strtoupper(__(auth()->user()->getRoleNames()->first())) }}
                </h2>
            </div>
        </div>
        
    </div>
</section>
