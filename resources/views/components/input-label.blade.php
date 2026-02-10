@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-semibold uppercase tracking-[0.08em] text-slate-600']) }}>
    {{ $value ?? $slot }}
</label>
