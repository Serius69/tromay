<?php

namespace Database\Factories;

use App\Models\Cash;
use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition(): array
    {
        $type   = $this->faker->randomElement(['buy', 'sell']);
        $amount = $this->faker->randomFloat(2, 10, 5000);
        $rate   = $this->faker->randomFloat(4, 4, 12);

        return [
            'client_id'   => null,
            'cash_id'     => Cash::factory(),
            'type'        => $type,
            'amount'      => $amount,
            'rate'        => $rate,
            'total'       => round($amount * $rate, 4),
            'valid_until' => today()->addDay(),
            'notes'       => null,
            'status'      => 1,
        ];
    }
}
