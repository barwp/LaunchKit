<?php

namespace App\Http\Controllers;

use App\Services\AffiliateService;
use App\Services\PaymentService;
use App\Services\ReferralService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AffiliateController extends Controller
{
    public function __construct(
        protected AffiliateService $affiliateService,
        protected ReferralService $referralService,
        protected PaymentService $paymentService,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $this->referralService->ensureReferralCode($request->user());
        $stats = $this->affiliateService->statsFor($user);

        return view('affiliate.index', [
            'user' => $user,
            'stats' => $stats,
            'referralLink' => $this->referralService->buildReferralLink($user),
            'withdrawalMinimum' => (int) config('packages.withdrawal_minimum', 100000),
        ]);
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'phone_number' => ['required', 'string', 'max:30'],
        ]);

        $user = $request->user();
        $wallet = $this->affiliateService->ensureWallet($user);
        $withdrawal = $this->affiliateService->createWithdrawal($user, (int) $payload['amount'], $payload['phone_number']);

        return redirect()->away(
            $this->paymentService->generateWithdrawalWhatsAppLink(
                $user,
                (int) $wallet->fresh()->balance,
                (int) $withdrawal->amount,
                $withdrawal->phone_number
            )
        );
    }
}
