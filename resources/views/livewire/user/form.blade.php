<div>
    <div class="flex">
        <div class="w-1/2">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full uppercase" type="text" name="name"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
        </div>
        <div class="w-1/2 ml-4">
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email"
                    autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

        </div>
    </div>
    {{-- 
    <div class="flex">
        <div class="w-1/2">

        </div>
        <div class="w-1/2 ml-4">

        </div>
    </div> 
    --}}

    <div class="flex">
        <div class="w-1/2">
            {{-- Cedula --}}
            <div class="mt-4">
                <x-input-label for="cedula" :value="__('Cédula')" />
                <x-text-input wire:model="cedula" id="cedula" class="block mt-1 w-full" type="text" name="cedula"
                    required autocomplete="cedula" required min="1" step="1" />
                <x-input-error :messages="$errors->get('cedula')" class="mt-2" />
            </div>
        </div>
        
    </div>

    <div class="flex">
        <div class="w-1/2">
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-password-input wire:model="password" id="password" class="block mt-1 w-full" type="password"
                    name="password" autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </div>
        <div class="w-1/2 ml-4">
            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-password-input wire:model="password_confirmation" id="password_confirmation"
                    class="block mt-1 w-full" type="password" name="password_confirmation"
                    autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
    </div>





    <div class="flex">
        <div class="w-1/2">
            <div class="mt-4">
                <x-select-rol :roles="$roles" />
            </div>
        </div>
        <div class="w-1/2 ml-4">
            <div class="mt-4">
                <x-input-label for="is_active" :value="__('Select') . ' ' . strtolower(__('Status')) . ':'" />
                <x-select-option wire:model="is_active" id="is_active" :disabled="false" :options="['1' => __('Active'), '0' => __('Inactive')]"
                    class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
            </div>
        </div>
    </div>


</div>
