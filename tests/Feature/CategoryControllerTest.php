<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_categories()
    {
        Category::factory()->create(['name' => 'Action']);
        Category::factory()->create(['name' => 'Drama']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Action'])
            ->assertJsonFragment(['name' => 'Drama']);
    }

    public function test_index_filters_by_needle()
    {
        Category::factory()->create(['name' => 'Comedy']);
        Category::factory()->create(['name' => 'Romantic Comedy']);

        $response = $this->getJson('/api/categories?needle=roman');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Romantic Comedy'])
            ->assertJsonMissing(['name' => 'Comedy']);
    }

    public function test_store_creates_new_category()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/categories', [
            'name' => 'Sci-fi',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Sci-fi']);

        $this->assertDatabaseHas('categories', [
            'name' => 'Sci-fi',
        ]);
    }

    public function test_update_modifies_existing_category()
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson("/api/categories/{$category->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_returns_404_for_missing_category()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson('/api/categories/999999', [
            'name' => 'Does not matter',
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    }

    public function test_delete_removes_category()
    {
        $category = Category::factory()->create(['name' => 'To be deleted']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
