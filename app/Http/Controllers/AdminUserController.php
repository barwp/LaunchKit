<?php

namespace App\Http\Controllers;

use App\Models\AffiliateTransaction;
use App\Models\Order;
use App\Models\User;
use App\Services\AffiliateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function __construct(
        protected AffiliateService $affiliateService,
    ) {
    }

    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $users = User::query()
            ->withCount('projects')
            ->withCount('referrals')
            ->withSum('affiliateTransactions', 'amount')
            ->latest()
            ->get();

        $pendingUsers = User::query()
            ->with(['orders', 'referredBy'])
            ->where('account_status', 'pending_payment')
            ->latest()
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'pendingUsers' => $pendingUsers,
        ]);
    }

    public function create(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_admin' => ['nullable', 'boolean'],
        ]);

        User::query()->create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
            'is_admin' => (bool) ($payload['is_admin'] ?? false),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User berhasil dibuat.');
    }

    public function edit(User $user, Request $request): View
    {
        $this->ensureAdmin($request);

        $user->loadCount('projects');
        $user->loadCount('referrals');
        $user->loadSum('affiliateTransactions', 'amount');
        $user->load(['orders', 'wallet']);

        return view('admin.users.edit', [
            'managedUser' => $user,
        ]);
    }

    public function update(User $user, Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'is_admin' => ['nullable', 'boolean'],
            'account_status' => ['required', 'string', Rule::in(['pending_payment', 'approved', 'rejected'])],
        ]);

        $user->update([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'is_admin' => (bool) ($payload['is_admin'] ?? false),
            'account_status' => $payload['account_status'],
        ]);

        return back()->with('status', 'Data user berhasil diperbarui.');
    }

    public function updatePassword(User $user, Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $payload = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($payload['password']),
        ]);

        return back()->with('status', 'Password user berhasil diganti.');
    }

    public function destroy(User $user, Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        if ($request->user()->id === $user->id) {
            return back()->withErrors(['user' => 'Admin tidak bisa menghapus akunnya sendiri.']);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'User berhasil dihapus.');
    }

    protected function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }

    public function approve(User $user, Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $user->update(['account_status' => 'approved']);
        $user->orders()->where('status', 'pending')->update(['status' => 'approved']);

        $approvedOrders = $user->orders()->where('status', 'approved')->get();
        foreach ($approvedOrders as $order) {
            $exists = AffiliateTransaction::query()
                ->where('user_id', $user->referred_by)
                ->where('referred_user_id', $user->id)
                ->where('description', 'like', '%'.$order->package_name.'%')
                ->exists();

            if (! $exists) {
                $this->affiliateService->recordOrderCommission($order);
            }
        }

        return back()->with('status', 'User berhasil di-approve dan akun sekarang aktif.');
    }

    public function reject(User $user, Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $user->update(['account_status' => 'rejected']);
        $user->orders()->where('status', 'pending')->update(['status' => 'rejected']);

        return back()->with('status', 'User berhasil ditolak.');
    }
}
