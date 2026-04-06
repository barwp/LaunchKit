<x-guest-layout>
    @php($errors = $errors ?? new \Illuminate\Support\ViewErrorBag)

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-6">
            <h1 class="text-3xl font-black text-white">Buat akun baru</h1>
            <p class="mt-2 text-sm leading-7 text-slate-400">Daftar untuk mulai membuat adaptive landing page sesuai niche bisnis Anda.</p>
            <div class="mt-4 rounded-[22px] border border-emerald-400/20 bg-emerald-400/10 p-4 text-sm leading-7 text-emerald-100">
                <p class="font-bold text-white">Paket permanen aktif: LaunchKit Starter</p>
                <p class="mt-1">Harga <span class="font-semibold text-white">Rp 99.000</span>. Setelah register, Anda akan diarahkan ke WhatsApp admin untuk pembayaran dan akun akan aktif setelah admin melakukan approval.</p>
            </div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="referral_code" :value="__('Referral Code (Opsional)')" />
            <x-text-input id="referral_code" class="block mt-1 w-full" type="text" name="referral_code" :value="old('referral_code', $referralCode ?? request('ref'))" autocomplete="off" />
            <p class="mt-2 text-xs leading-6 text-emerald-300">Masukkan referral code untuk mendapatkan diskon Rp5.000 pada pembelian pertama.</p>
            <x-input-error :messages="$errors->get('referral_code')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6 flex items-center justify-end gap-4">
            <a class="text-sm text-slate-400 transition hover:text-white" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
