<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function checkout(string $package, Request $request): RedirectResponse
    {
        return redirect()
            ->route('dashboard')
            ->with('status', 'Pembelian paket dilakukan saat registrasi akun dan diarahkan ke WhatsApp admin.');
    }

    public function purchase(string $package, Request $request): RedirectResponse
    {
        return redirect()
            ->route('dashboard')
            ->with('status', 'Transaksi paket hanya dilakukan saat registrasi akun.');
    }
}
