<?php

namespace Tests\Feature;

use App\Models\ComfortCategory;
use App\Models\Position;

class PositionApiTest extends ApiTestCase
{
    public function test_positions_index_requires_auth(): void
    {
        $response = $this->getJson('/api/positions');
        $response->assertStatus(401);
    }

    public function test_positions_index_returns_list(): void
    {
        $user = $this->createUser();
        Position::create(['name' => 'Developer']);

        $response = $this->getJson('/api/positions', $this->authJson($user));
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_positions_store_requires_manager_or_admin(): void
    {
        $user = $this->createUser(['role' => 'employee']);
        $cat = ComfortCategory::create(['name' => 'first']);

        $response = $this->postJson('/api/positions', [
            'name' => 'New Position',
            'comfort_category' => [$cat->id],
        ], $this->authJson($user));

        $response->assertStatus(403);
    }

    public function test_positions_store_creates_as_manager(): void
    {
        $user = $this->createManager();
        $cat = ComfortCategory::create(['name' => 'first']);

        $response = $this->postJson('/api/positions', [
            'name' => 'New Position',
            'comfort_category' => [$cat->id],
        ], $this->authJson($user));

        $response->assertStatus(201)->assertJsonFragment(['name' => 'New Position']);
        $this->assertDatabaseHas('positions', ['name' => 'new position']);
    }

    public function test_positions_update_as_manager(): void
    {
        $user = $this->createManager();
        $position = Position::create(['name' => 'Old Name']);
        $cat = ComfortCategory::create(['name' => 'cat']);

        $response = $this->putJson('/api/positions/' . $position->id, [
            'name' => 'Updated Name',
            'comfort_category' => [$cat->id],
        ], $this->authJson($user));

        $response->assertOk();
        $this->assertDatabaseHas('positions', ['id' => $position->id, 'name' => 'updated name']);
    }

    public function test_positions_destroy_as_admin(): void
    {
        $user = $this->createAdmin();
        $position = Position::create(['name' => 'To Delete']);

        $response = $this->deleteJson('/api/positions/' . $position->id, [], $this->authJson($user));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('positions', ['id' => $position->id]);
    }
}
