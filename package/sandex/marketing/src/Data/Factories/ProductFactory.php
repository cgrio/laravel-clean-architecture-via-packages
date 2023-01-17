<?php

namespace Sandex\Marketing\Data\Factories;

use Sandex\Marketing\Data\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

     protected $model = Product::class;
    public function definition()
    {
        return [

            'name' =>  $this->faker->name(),
            'description' => $this->faker->text(30),
            'sku' =>  $this->faker->text(10),
            'ncm' =>  $this->faker->text(20),

        ];
    }
}
