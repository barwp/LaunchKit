<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-2xl border border-rose-400/20 bg-rose-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-300']) }}>
    {{ $slot }}
</button>
