@props(['disabled' => false])

<div class="flex mt-1 mb-2">
    <div x-data="{ show: false }" class="relative flex-1 col-span-4">
        <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm']) !!}
            :type="show ? 'text' : 'password'"
            id="password"
            name="password"
             autocomplete="new-password">
        
        <button type="button" class="flex absolute inset-y-0 right-0 items-center pr-3 top-1/2 transform -translate-y-1/2" @click="show = !show" :class="{'hidden': !show, 'block': show }">
            <i class="fa-regular fa-eye"></i>
        </button>
        <button type="button" class="flex absolute inset-y-0 right-0 items-center pr-3 top-1/2 transform -translate-y-1/2" @click="show = !show" :class="{'block': !show, 'hidden': show }">
            <!-- Heroicon name: eye-slash -->
            <i class="fa-regular fa-eye-slash"></i>
        </button>
    </div>
</div>
