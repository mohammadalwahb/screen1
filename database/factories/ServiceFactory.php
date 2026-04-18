<?php

namespace Database\Factories;

use App\Models\Building;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'building_id' => Building::factory(),
            'floor' => (string) fake()->numberBetween(1, 5),
            'room' => (string) fake()->numberBetween(100, 450),
            'picture' => null,
            'keywords' => [fake()->word(), fake()->word()],
            'is_active' => true,
        ];
    }
}
