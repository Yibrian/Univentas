@props(['disabled' => false, 'options' => [], 'selected' => null])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    <option value="" >{{ __('Seleccione una opción') }}</option>
    @foreach ($options as $value => $label)
        <option value="{{ $value }}" {{ $value == $selected ? 'selected' : '' }}>
            {{ ucfirst(__($label)) }}
        </option>
    @endforeach
</select>
