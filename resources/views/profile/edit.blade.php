@extends('layouts.app', ['title' => 'Profile - LaunchKit Adaptive'])

@section('content')
    <section class="hero-panel p-8 lg:p-10">
        <div class="glow-orb left-[-60px] top-[-30px] h-44 w-44 bg-emerald-400/20"></div>
        <div class="glow-orb right-[-40px] bottom-[-20px] h-40 w-40 bg-sky-400/10"></div>
        <div class="relative z-10">
            <span class="badge-soft">Account Center</span>
            <h1 class="mt-5 text-4xl font-black text-white lg:text-5xl">Kelola profil dan keamanan akun Anda.</h1>
            <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-300">UI profile sekarang mengikuti shell utama LaunchKit Adaptive agar konsisten untuk user maupun admin.</p>
        </div>
    </section>

    <section class="mt-8 space-y-6">
        <div class="panel p-6 md:p-8">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="panel p-6 md:p-8">
            @include('profile.partials.update-password-form')
        </div>

        <div class="panel p-6 md:p-8">
            @include('profile.partials.delete-user-form')
        </div>
    </section>
@endsection
