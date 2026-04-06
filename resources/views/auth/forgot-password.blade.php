<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-black text-white">Lupa password?</h1>
        <p class="mt-2 text-sm leading-7 text-slate-400">
            Saat ini LaunchKit Adaptive belum mengirim email otomatis. Masukkan email akun Anda dan kami akan arahkan ke chat admin untuk bantuan reset password.
        </p>
        <div class="mt-4 rounded-[22px] border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm leading-7 text-emerald-100">
            Admin support: <span class="font-semibold text-white">08119921200</span>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-between gap-4">
            <a class="text-sm text-slate-400 transition hover:text-white" href="{{ route('login') }}">
                Kembali ke login
            </a>
            <x-primary-button>
                {{ __('Chat Admin Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
