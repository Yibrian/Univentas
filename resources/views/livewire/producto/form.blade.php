<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="mt-2">
        <x-input-label for="nombre" :value="__('Nombre del Producto')" />
        <x-text-input wire:model="nombre" id="nombre" class="block mt-1 w-full" type="text" name="nombre" required />
        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
    </div>

    <div class="mt-2">
        <x-input-label for="descripcion" :value="__('Descripción')" />
        <textarea wire:model="descripcion" id="descripcion" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" name="descripcion"></textarea>
        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
    </div>

    <div class="mt-2">
        <x-input-label for="precio" :value="__('Precio')" />
        <x-text-input wire:model="precio" id="precio" class="block mt-1 w-full" type="number" step="0.01" name="precio" required />
        <x-input-error :messages="$errors->get('precio')" class="mt-2" />
    </div>

    <div class="mt-2">
        <x-input-label for="cantidad" :value="__('Cantidad disponible')" />
        <x-text-input wire:model="cantidad" id="cantidad" class="block mt-1 w-full" type="number" name="cantidad" required />
        <x-input-error :messages="$errors->get('cantidad')" class="mt-2" />
    </div>

    <div class="mt-2">
        <x-input-label for="disponibilidad" :value="__('Disponibilidad')" />
        <select wire:model="disponibilidad" id="disponibilidad" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Seleccionar') }}</option>
            <option value="1">{{ __('Disponible') }}</option>
            <option value="0">{{ __('No disponible') }}</option>
        </select>
        <x-input-error :messages="$errors->get('disponibilidad')" class="mt-2" />
    </div>

    <div class="mt-2">
        <x-input-label for="categoria_id" :value="__('Categoría')" />
        <select wire:model="categoria_id" id="categoria_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Seleccione una categoría') }}</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('categoria_id')" class="mt-2" />
    </div>
    <div class="mt-2">
        <x-input-label for="envio_domicilio" :value="__('¿Envío a domicilio?')" />
        <select wire:model="envio_domicilio" id="envio_domicilio" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
            <option value="">{{ __('Seleccionar') }}</option>
            <option value="1">{{ __('Sí') }}</option>
            <option value="0">{{ __('No') }}</option>
        </select>
        <x-input-error :messages="$errors->get('envio_domicilio')" class="mt-2" />
    </div>
    <div class="mt-2">
        <x-input-label for="precio_domicilio" :value="__('Precio del envío')" />
        <x-text-input wire:model="precio_domicilio" id="precio_domicilio" class="block mt-1 w-full" type="number" step="0.01" name="precio_domicilio" required />
        <x-input-error :messages="$errors->get('precprecio_domicilioio')" class="mt-2" />
    </div>

    <div class="mt-2">
        <x-input-label for="imagen" :value="__('Imagen del Producto')" />
        <input wire:model="imagen" id="imagen" class="block mt-1 w-full" type="file" name="imagen" />
        <x-input-error :messages="$errors->get('imagen')" class="mt-2" />
    </div>

   
</div>