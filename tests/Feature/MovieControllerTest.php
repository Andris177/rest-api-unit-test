<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_movies()
    {
        Movie::factory()->create(['title' => 'Inception']);
        Movie::factory()->create(['title' => 'The Matrix']);

        $response = $this->getJson('/api/movies');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Inception'])
            ->assertJsonFragment(['title' => 'The Matrix']);
    }

    public function test_store_creates_new_movie()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        // kell hozzá egy category és egy director, mert az adatbázisban NOT NULL
        $category = \App\Models\Category::factory()->create();
        $director = \App\Models\Director::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/movies', [
            'title'       => 'Interstellar',
            'description' => 'Space movie',
            'cover_image' => 'cover.jpg',
            'category_id' => $category->id,
            'director_id' => $director->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Interstellar']);

        $this->assertDatabaseHas('movies', [
            'title'       => 'Interstellar',
            'category_id' => $category->id,
            'director_id' => $director->id,
        ]);
    }


    public function test_update_modifies_existing_movie()
    {
        $movie = Movie::factory()->create(['title' => 'Old title']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson("/api/movies/{$movie->id}", [
            'title' => 'New title',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'New title']);

        $this->assertDatabaseHas('movies', [
            'id'    => $movie->id,
            'title' => 'New title',
        ]);
    }

    public function test_update_returns_404_for_missing_movie()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson('/api/movies/999999', [
            'title' => 'Does not matter',
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    }

    public function test_delete_removes_movie()
    {
        $movie = Movie::factory()->create(['title' => 'Delete me']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/movies/{$movie->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('movies', [
            'id' => $movie->id,
        ]);
    }
}
