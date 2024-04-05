<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ActivateUserTest extends TestCase
{
    private function createUser()
    {
        return User::factory()->create();
    }

    /**
     * Test that a user can create a account when URL is signed
     */
    public function test_user_can_create_account_when_url_is_signed(): void
    {
        $user = $this->createUser();

        $url = URL::temporarySignedRoute('activate-account.index', now()->addDay(), ['email' => $user->email]);

        $response = $this->get($url);

        $response->assertOk();
        $response->assertViewIs('auth.activate-account');
        $response->assertViewHas('email', $user->email);
    }

    /**
     * Test that a user cannot create a account when URL is not signed
     */
    public function test_user_cannot_create_account_when_url_is_not_signed(): void
    {
        $user = $this->createUser();

        $url = URL::temporarySignedRoute('activate-account.index', now()->addDay(), ['email' => $user->email]);

        $url = str_replace('signature', 'invalid-signature', $url);

        $response = $this->get($url);

        $response->assertStatus(403);
    }

    /**
     * Test that a user cannot create a account when URL is expired
     */
    public function test_user_cannot_create_account_when_url_is_expired(): void
    {
        $user = $this->createUser();

        $url = URL::temporarySignedRoute('activate-account.index', now()->subDay(), ['email' => $user->email]);

        $response = $this->get($url);

        $response->assertStatus(403);
    }

    /**
     * Test that an account can be stored when url is signed
     */
    public function test_account_can_be_stored_when_url_is_signed(): void
    {
        $email = fake()->safeEmail();

        $url = URL::temporarySignedRoute('activate-account.store', now()->addDay(), ['email' => $email]);

        $response = $this->post($url, [
            'name' => 'John Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => $email,
        ]);
    }

    /**
     * Test that an account cannot be stored when url is not signed
     */
    public function test_account_cannot_be_stored_when_url_is_not_signed(): void
    {
        $email = fake()->safeEmail();

        $url = URL::temporarySignedRoute('activate-account.store', now()->addDay(), ['email' => $email]);

        $url = str_replace('signature', 'invalid-signature', $url);

        $response = $this->post($url, [
            'name' => 'John Doe',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe',
            'email' => $email,
        ]);
    }
}
