<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 rounded-xl border border-blue-500 bg-white px-4 py-2.5 text-sm font-semibold text-blue-500 shadow-sm transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
