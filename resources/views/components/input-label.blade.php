@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-slate-700 dark:text-slate-500']) }}>
    {{ $value ?? $slot }}
</label>
