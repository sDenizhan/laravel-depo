<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        return [
            'category_id' => $this->faker->randomElement(ProductCategory::pluck('id')->toArray()),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'barcode' => $this->faker->randomNumber(8, true),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'currency' => $this->faker->randomElement(\App\Enums\Currency::toArray()),
            'image' => null,
            'brand' => $this->faker->company
        ];
    }
}
