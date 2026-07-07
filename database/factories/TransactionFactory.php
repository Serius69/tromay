<?php

namespace Database\Factories;

use App\Models\Cash;
use App\Models\Client;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            'client_id' => Client::factory(),
            'cash1_id'  => Cash::factory(),
            'cash2_id'  => Cash::factory(),
            'amount1'   => $this->faker->randomFloat(2, 10, 1000),
            'amount2'   => $this->faker->randomFloat(2, 10, 10000),
            'date'      => $this->faker->dateTimeThisMonth(),
            'type'      => $this->faker->randomElement([1, 2]),
            'status'    => 1,
        ];
    }
}
