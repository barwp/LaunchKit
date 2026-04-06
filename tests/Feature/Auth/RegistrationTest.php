<?php

namespace Tests\Feature\Auth;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_register_as_pending_and_are_redirected_to_whatsapp(): void
    {
        $response = $this->post('/register', [
            'name' => 'Pending User',
            'email' => 'pending-user@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::query()->where('email', 'pending-user@example.com')->first();

        $this->assertGuest();
        $this->assertNotNull($user);
        $this->assertSame('pending_payment', $user->account_status);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'package_name' => 'LaunchKit Starter',
            'price' => 99000,
            'status' => 'pending',
        ]);
        $response->assertRedirectContains('https://wa.me/628119921200');
    }

    public function test_pending_registrations_are_visible_to_admin_and_can_be_approved(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'account_status' => 'approved',
        ]);

        $response = $this->post('/register', [
            'name' => 'Pending Approval',
            'email' => 'approval@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $pendingUser = User::query()->where('email', 'approval@example.com')->firstOrFail();
        $order = Order::query()->where('user_id', $pendingUser->id)->firstOrFail();

        $response->assertRedirectContains('https://wa.me/628119921200');

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('approval@example.com');

        $this->actingAs($admin)
            ->post(route('admin.users.approve', $pendingUser))
            ->assertRedirect();

        $pendingUser->refresh();
        $order->refresh();

        $this->assertSame('approved', $pendingUser->account_status);
        $this->assertSame('approved', $order->status);
    }
}
