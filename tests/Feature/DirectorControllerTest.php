<?php

namespace Tests\Feature;

use App\Models\Director;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DirectorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_directors()
    {
        Director::factory()->create(['name' => 'Christopher Nolan']);
        Director::factory()->create(['name' => 'Quentin Tarantino']);

        $response = $this->getJson('/api/directors');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Christopher Nolan'])
            ->assertJsonFragment(['name' => 'Quentin Tarantino']);
    }

    public function test_store_creates_new_director()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/directors', [
            'name' => 'Steven Spielberg',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Steven Spielberg']);

        $this->assertDatabaseHas('directors', [
            'name' => 'Steven Spielberg',
        ]);
    }

    public function test_update_modifies_existing_director()
    {
        $director = Director::factory()->create(['name' => 'Old Name']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson("/api/directors/{$director->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('directors', [
            'id'   => $director->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_returns_404_for_missing_director()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson('/api/directors/999999', [
            'name' => 'Does not matter',
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    }

    public function test_delete_removes_director()
    {
        $director = Director::factory()->create(['name' => 'Delete Me']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/directors/{$director->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('directors', [
            'id' => $director->id,
        ]);
    }
}
