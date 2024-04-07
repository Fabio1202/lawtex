<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test that users are shown paginated when accessing user is admin
     */
    public function test_users_are_shown_paginated_when_accessing_user_is_admin(): void
    {
        $response = $this->actingAs($this->createAdminUser())->get(route('users.index'));

        $response->assertOk();
        $response->assertViewIs('users.index');
        $response->assertViewHas('users');
    }

    /**
     * Test that users are not shown when accessing user is not admin
     */
    public function test_users_are_not_shown_when_accessing_user_is_not_admin(): void
    {
        $response = $this->actingAs($this->createUser())->get(route('users.index'));

        $response->assertForbidden();
    }

    /**
     * Test that a user can be shown when accessing user is admin
     */
    public function test_user_can_be_shown_when_accessing_user_is_admin(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createAdminUser())->get(route('users.show', $user));

        $response->assertOk();
        $response->assertViewIs('users.show');
        $response->assertViewHas('user');
    }

    /**
     * Test that a user cannot be shown when accessing user is not admin
     */
    public function test_user_cannot_be_shown_when_accessing_user_is_not_admin(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createUser())->get(route('users.show', $user));

        $response->assertForbidden();
    }

    /**
     * Test that a user can be updated when accessing user is admin
     */
    public function test_user_can_be_updated_when_accessing_user_is_admin(): void
    {
        // Migrate fresh
        $this->artisan('migrate:fresh');

        $user = $this->createUser();

        $response = $this->actingAs($this->createAdminUser())->put(route('users.update', $user), [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'admin' => 'on',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@test.com', $user->email);
        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test updating a admin user while keeping the admin role
     */
    public function test_updating_a_admin_user_while_keeping_the_admin_role(): void
    {
        // Migrate fresh
        $this->artisan('migrate:fresh');

        $user = $this->createAdminUser();

        $response = $this->actingAs($this->createAdminUser())->put(route('users.update', $user), [
            'name' => 'Test User',
            'email' => 'testy@test.com',
            'admin' => 'on',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('testy@test.com', $user->email);
        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test updating a user without admin role
     */
    public function test_updating_a_user_without_admin_role(): void
    {
        // Migrate fresh
        $this->artisan('migrate:fresh');

        $user = $this->createUser();

        $response = $this->actingAs($this->createAdminUser())->put(route('users.update', $user), [
            'name' => 'Test User',
            'email' => 'test@test.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@test.com', $user->email);
        $this->assertFalse($user->isAdmin());
    }

    /**
     * Test that a user cannot be updated when accessing user is not admin
     */
    public function test_user_cannot_be_updated_when_accessing_user_is_not_admin(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createUser())->put(route('users.update', $user), [
            'name' => 'Test User',
            'email' => 'test@test.com',
        ]);

        $response->assertForbidden();

        $user->refresh();

        $this->assertNotSame('Test User', $user->name);
        $this->assertNotSame('test@test.com', $user->email);
    }

    /**
     * Test that a user can be deleted when accessing user is admin
     */
    public function test_user_can_be_deleted_when_accessing_user_is_admin(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createAdminUser())->delete(route('users.destroy', $user));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertNull(User::find($user->id));
    }

    /**
     * Test that a user cannot be deleted when accessing user is not admin
     */
    public function test_user_cannot_be_deleted_when_accessing_user_is_not_admin(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createUser())->delete(route('users.destroy', $user));

        $response->assertForbidden();

        $this->assertNotNull(User::find($user->id));
    }

    /**
     * Cannot delete the only admin user
     */
    public function test_cannot_delete_the_only_admin_user(): void
    {
        //Migrate fresh
        $this->artisan('migrate:fresh');

        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->delete(route('users.destroy', $user));

        $response->assertSessionHasErrors('admin');
        $response->assertRedirect();

        $this->assertNotNull(User::find($user->id));
    }

    /**
     * Cannot remove the admin role from the only admin user
     */
    public function test_cannot_remove_the_admin_role_from_the_only_admin_user(): void
    {

        //Migrate fresh
        $this->artisan('migrate:fresh');

        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->put(route('users.update', $user), [
            'name' => 'Test User',
            'email' => 'testy@test.com',
        ]);

        $response->assertSessionHasErrors('admin');
        $response->assertRedirect();

        $user->refresh();

        $this->assertNotSame('Test User', $user->name);
        $this->assertNotSame('testy@test.com', $user->email);
        $this->assertTrue($user->isAdmin());
    }

    /**
     * Test that a user password can be reset when accessing user is admin
     */
    public function test_user_password_can_be_reset_when_accessing_user_is_admin(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $response = $this->actingAs($this->createAdminUser())->post(route('users.reset-password', $user));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        Notification::assertSentTo([$user], ResetPassword::class);
    }

    /**
     * Test that a user password cannot be reset when accessing user is not admin
     */
    public function test_user_password_cannot_be_reset_when_accessing_user_is_not_admin(): void
    {
        Notification::fake();

        $user = $this->createUser();

        $response = $this->actingAs($this->createUser())->post(route('users.reset-password', $user));

        $response->assertForbidden();

        Notification::assertNothingSent();
    }

    /**
     * Test that an activation link can be created when accessing user is admin
     */
    public function test_activation_link_can_be_created_when_accessing_user_is_admin(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createAdminUser())->post(route('users.create-activation-link'), [
            'email' => $user->email,
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['link']);

        // Test that signed URL is valid
        $response->assertJson(fn (AssertableJson $json) => $json->where('link', URL::signedRoute('activate-account.index', ['email' => $user->email]))
        );
    }

    /**
     * Test that an activation link cannot be created when accessing user is not admin
     */
    public function test_activation_link_cannot_be_created_when_accessing_user_is_not_admin(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($this->createUser())->post(route('users.create-activation-link'), [
            'email' => $user->email,
        ]);

        $response->assertForbidden();
    }

    private function createAdminUser()
    {
        $user = $this->createUser();
        $user->assignRole('admin');

        return $user;
    }

    private function createUser()
    {
        return User::factory()->create();
    }
}
