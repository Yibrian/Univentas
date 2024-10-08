@props(['roles' => [], 'disabled' => false])

<x-input-label for="rol" :value="__('Select') . ' ' . strtolower(__('Role')) . ':'" />
<select wire:model="rol" id="rol"
    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
    <option value="" disabled></option>
    @foreach ($roles as $id => $name)
        <option value="{{ $id }}">{{ ucfirst(__($name)) }}</option>
    @endforeach
</select>
<x-input-error :messages="$errors->get('rol')" class="mt-2" />
