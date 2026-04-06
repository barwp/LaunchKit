<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\ReferralService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        protected ReferralService $referralService,
        protected PaymentService $paymentService,
    ) {
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'referralCode' => request('ref'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string', 'max:20'],
        ]);

        $referrer = $this->referralService->resolveReferrer($request->string('referral_code')->value());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'referral_code' => $this->referralService->generateCode(),
            'referred_by' => $referrer && $referrer->email !== $request->email ? $referrer->id : null,
            'account_status' => 'pending_payment',
        ]);

        $package = config('packages.items')[0];
        $discount = $this->paymentService->referralDiscountFor($user);
        Order::query()->create([
            'user_id' => $user->id,
            'package_name' => $package['name'],
            'price' => (int) $package['price'],
            'discount' => $discount,
            'final_price' => max(((int) $package['price']) - $discount, 0),
            'referral_code_used' => $user->referredBy?->referral_code,
            'status' => 'pending',
        ]);

        event(new Registered($user));

        return redirect()->away($this->paymentService->generateWhatsAppLink($user, $package, $discount));
    }
}
