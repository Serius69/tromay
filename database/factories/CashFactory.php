<?php

namespace Database\Factories;

use App\Models\Cash;
use Illuminate\Database\Eloquent\Factories\Factory;

class CashFactory extends Factory
{
    protected $model = Cash::class;

    public function definition(): array
    {
        return [
            'name'    => $this->faker->randomElement(['usd', 'eur', 'clp', 'pen', 'brl', 'ars']),
            'buy'     => $this->faker->randomFloat(4, 4, 12),
            'sell'    => $this->faker->randomFloat(4, 4, 12),
            'oficial' => $this->faker->randomFloat(4, 4, 12),
            'path'    => 'default.jpg',
            'status'  => 1,
        ];
    }
}
