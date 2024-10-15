<div class="mt-4">
    <x-input-label for="nombre" :value="__('Nombre de la categoria')" />
    <x-text-input wire:model="nombre" id="nombre" class="block mt-1 w-full" type="text"
        name="nombre" required autocomplete="nombre" />
    <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="photo" :value="__('Foto refernete a categoria')" />
    <input wire:model="photo" id="photo" class="block mt-1 w-full" type="file" name="photo" accept="image/*"/>
    <x-input-error :messages="$errors->get('photo')" class="mt-2" />
</div>

@if ($photo)
    <div class="mt-4">
        <p>{{ __('Vista previa de la foto:') }}</p>
        <img src="{{ $photo->temporaryUrl() }}" alt="Preview de la foto de la tienda" class="mt-2 w-32 h-32 object-cover">
    </div>
@endif