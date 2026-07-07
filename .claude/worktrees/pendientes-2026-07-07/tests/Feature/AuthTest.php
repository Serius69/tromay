<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $password = 'secret-password'): User
    {
        return User::factory()->create(['password' => Hash::make($password)]);
    }

    /** @test */
    public function the_login_page_renders(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Iniciar sesión');
    }

    /** @test */
    public function a_user_can_login_with_valid_credentials(): void
    {
        $user = $this->makeUser();

        $this->post(route('login.attempt'), [
            'email'    => $user->email,
            'password' => 'secret-password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function login_fails_with_wrong_password(): void
    {
        $user = $this->makeUser();

        $this->from(route('login'))->post(route('login.attempt'), [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ])->assertRedirect(route('login'))
          ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /** @test */
    public function login_is_rate_limited_after_five_failed_attempts(): void
    {
        $user = $this->makeUser();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.attempt'), [
                'email'    => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $this->from(route('login'))->post(route('login.attempt'), [
            'email'    => $user->email,
            'password' => 'secret-password', // aun con la contraseña correcta
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    /** @test */
    public function an_authenticated_user_can_logout(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    /** @test */
    public function an_authenticated_user_is_redirected_away_from_login(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect('/admin');
    }

    /** @test */
    public function guests_are_redirected_to_login_from_admin_routes(): void
    {
        foreach (['/admin', '/admin/analytics', '/admin/buy', '/admin/reports/daily-close', '/admin/quotation'] as $route) {
            $this->get($route)->assertRedirect(route('login'));
        }
    }
}
