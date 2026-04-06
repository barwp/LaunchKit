<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name', 'LaunchKit Adaptive') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,700,800|poppins:400,500,600,700|playfair-display:600,700|cormorant-garamond:500,600,700|lora:400,600,700|space-grotesk:400,500,700|rajdhani:500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="shell">
            @auth
                @include('layouts.navigation')
            @endauth

            <main class="mx-auto w-full max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
                @if (session('status'))
                    <div class="mx-auto mb-6 max-w-4xl rounded-2xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm font-medium text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                @if (($errors ?? null)?->any())
                    <div class="mx-auto mb-6 max-w-4xl rounded-2xl border border-rose-400/20 bg-rose-400/10 px-4 py-3 text-sm text-rose-200">
                        <p class="font-semibold">Ada data yang perlu diperbaiki.</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach (($errors ?? collect())->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </body>
</html>
