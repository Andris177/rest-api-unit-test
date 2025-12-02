<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\Category;
use App\Models\Director;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition()
    {
        return [
            'title'        => $this->faker->sentence(3),
            'description'  => $this->faker->paragraph(),
            'cover_image'  => $this->faker->imageUrl(),
            'category_id'  => Category::factory(),
            'director_id'  => Director::factory(),
        ];
    }
}
