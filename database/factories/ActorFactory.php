<?php

namespace Database\Factories;

use App\Models\Actor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Actor>
 */
class ActorFactory extends Factory
{
    protected $model = Actor::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'birth_date'  => $this->faker->date(),
            'gender'      => $this->faker->randomElement(['férfi', 'nő']),
            'image'       => $this->faker->imageUrl(),
        ];
    }
}
