<?php

namespace Tests\Feature;

use App\Models\Actor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_actors()
    {
        Actor::factory()->create(['name' => 'Leonardo DiCaprio']);
        Actor::factory()->create(['name' => 'Brad Pitt']);

        $response = $this->getJson('/api/actors');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Leonardo DiCaprio'])
            ->assertJsonFragment(['name' => 'Brad Pitt']);
    }

    public function test_store_creates_new_actor()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/actors', [
            'name'        => 'Tom Hanks',
            'description' => 'Legendás színész',
            'birth_date'  => '1960-01-01',
            'gender'      => 'férfi',
            'image'       => 'tom.jpg',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Tom Hanks']);

        $this->assertDatabaseHas('actors', [
            'name' => 'Tom Hanks',
        ]);
    }

    public function test_update_modifies_existing_actor()
    {
        $actor = Actor::factory()->create(['name' => 'Old Name']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson("/api/actors/{$actor->id}", [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'New Name']);

        $this->assertDatabaseHas('actors', [
            'id'   => $actor->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_returns_404_for_missing_actor()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson('/api/actors/999999', [
            'name' => 'Does not matter',
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    }

    public function test_delete_removes_actor()
    {
        $actor = Actor::factory()->create(['name' => 'Delete Me']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/actors/{$actor->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('actors', [
            'id' => $actor->id,
        ]);
    }
}
