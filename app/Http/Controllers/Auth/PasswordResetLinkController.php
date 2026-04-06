<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     */
    public function store(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::query()->where('email', $payload['email'])->first();

        $message = implode("\n", [
            'Halo Admin, saya lupa password akun LaunchKit Adaptive.',
            '',
            'Email: '.$payload['email'],
            'Nama: '.($user?->name ?? '-'),
            '',
            'Mohon bantu reset password akun saya.',
        ]);

        return redirect()->away(
            'https://wa.me/'.$this->paymentService->adminWhatsApp().'?text='.rawurlencode($message)
        );
    }
}
