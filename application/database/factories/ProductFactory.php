<?php

namespace Database\Factories;

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
    public function definition()
    {
        return [
            'name' => 'Iphone ' . rand(5, 14),
            'weight' => 1,
            'type' => 0,
            'shop_id' => 1,
            'image' => 'abc',
            'price' => 2000,
            'sale_off_rate' => 0,
            'quantity' => 200
        ];
    }
}
