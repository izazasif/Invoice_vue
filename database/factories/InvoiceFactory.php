<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween(10,1000),
            'cutomer_id' => $this->faker->numberBetween(1,1000),
            'date' => $this->faker->date,
            'due_date' => $this->faker->date,
            'reference' => 'ref-'.rand(10,5000),
            'terms_and_conditions' => $this->faker->paragraph(2),
            'sub_total' => $this->faker->numberBetween(100,1000),
            'discount' => $this->faker->numberBetween(10,1000),
            'total' => $this->faker->numberBetween(20,1000)

        ];
    }
}
