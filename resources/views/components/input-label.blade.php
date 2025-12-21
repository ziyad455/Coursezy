@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-light-text-secondary dark:text-dark-text-secondary']) }}>
    {{ $value ?? $slot }}
</label>
