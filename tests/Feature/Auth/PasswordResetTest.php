<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase; // 1. Import dòng này
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase; // 2. Thêm dòng này vào ngay dưới khai báo class

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $response->assertStatus(302);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->post('/forgot-password', ['email' => $user->email]);

        $token = $this->app->make('auth.password')->broker()->createToken($user);

        $response = $this->get('/reset-password/'.$token.'?email='.$user->email);

        $response->assertStatus(200);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        $token = $this->app->make('auth.password')->broker()->createToken($user);

        $response = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertRedirect('/login'); // <-- Sửa từ '/dashboard' thành '/login' ở đây
    }
}