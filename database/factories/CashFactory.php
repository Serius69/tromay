<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cash>
 */
class CashFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cash::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'buy' => $this->faker->numberBetween(6,10),
            'sell' => $this->faker->numberBetween(6,10),
            'oficial' => $this->faker->numberBetween(6,10),
            'photo_id' => 1,
            'status' => 1

        ];
    }
}
