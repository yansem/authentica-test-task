<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = $this->faker->dateTimeBetween('-1 year');

        return [
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->numberBetween(100, 100000),
            'category_id' => Category::factory(),

            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}
