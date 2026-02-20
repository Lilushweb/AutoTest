<?php

namespace Tests\Feature;

use App\Models\Position;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token(): void
    {
        $position = Position::create(['name' => 'Developer']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'position_id' => $position->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token', 'token_type']);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_register_requires_valid_email(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'invalid',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_returns_token_for_valid_credentials(): void
    {
        Position::create(['name' => 'Dev']);
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'secret',
            'position_id' => Position::first()->id,
            'role' => 'employee',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@example.com',
            'password' => 'secret',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['user', 'token', 'token_type']);
    }

    public function test_login_fails_for_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(422);
    }

    public function test_logout_requires_auth(): void
    {
        $response = $this->postJson('/api/logout');
        $response->assertStatus(401);
    }

    public function test_logout_succeeds_with_token(): void
    {
        Position::create(['name' => 'P']);
        $user = User::factory()->create(['position_id' => Position::first()->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJson(['message' => 'Logged out successfully']);
    }

    public function test_user_returns_authenticated_user(): void
    {
        Position::create(['name' => 'P']);
        $user = User::factory()->create(['position_id' => Position::first()->id]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ]);

        $response->assertOk()->assertJsonFragment(['email' => $user->email]);
    }
}
