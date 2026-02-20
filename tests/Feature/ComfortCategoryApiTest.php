<?php

namespace Tests\Feature;

use App\Models\ComfortCategory;
use App\Models\Position;

class ComfortCategoryApiTest extends ApiTestCase
{
    public function test_comfort_category_index_requires_auth(): void
    {
        $response = $this->getJson('/api/comfortCategory');
        $response->assertStatus(401);
    }

    public function test_comfort_category_index_returns_list(): void
    {
        $user = $this->createUser();
        ComfortCategory::create(['name' => 'first']);

        $response = $this->getJson('/api/comfortCategory', $this->authJson($user));
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_comfort_category_store_requires_manager_or_admin(): void
    {
        $user = $this->createUser(['role' => 'employee']);
        $position = Position::create(['name' => 'P']);

        $response = $this->postJson('/api/comfortCategory', [
            'name' => 'New Category',
            'position_ids' => [$position->id],
        ], $this->authJson($user));

        $response->assertStatus(403);
    }

    public function test_comfort_category_store_creates_as_manager(): void
    {
        $user = $this->createManager();
        $position = Position::create(['name' => 'P']);

        $response = $this->postJson('/api/comfortCategory', [
            'name' => 'New Category',
            'position_ids' => [$position->id],
        ], $this->authJson($user));

        $response->assertStatus(201)->assertJsonFragment(['name' => 'New Category']);
        $this->assertDatabaseHas('comfort_categories', ['name' => 'new category']);
    }

    public function test_comfort_category_update_as_admin(): void
    {
        $user = $this->createAdmin();
        $category = ComfortCategory::create(['name' => 'Old']);
        $position = Position::create(['name' => 'P']);

        $response = $this->putJson('/api/comfortCategory/' . $category->id, [
            'name' => 'Updated Category',
            'position_ids' => [$position->id],
        ], $this->authJson($user));

        $response->assertOk();
        $this->assertDatabaseHas('comfort_categories', ['id' => $category->id, 'name' => 'updated category']);
    }

    public function test_comfort_category_destroy_as_admin(): void
    {
        $user = $this->createAdmin();
        $category = ComfortCategory::create(['name' => 'To Delete']);

        $response = $this->deleteJson('/api/comfortCategory/' . $category->id, [], $this->authJson($user));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('comfort_categories', ['id' => $category->id]);
    }
}
