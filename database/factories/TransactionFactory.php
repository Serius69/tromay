<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'client_id' => 1,
            'cash1_id' => 1,
            'cash2_id' => 2,
            'amount1' => $this->faker->numberBetween(10,1000),
            'amount2' => $this->faker->numberBetween(10,1000),
            'date' => $this->faker->date(),
            // 0 sell 1 buy
            'type' => 1,
            'status' => 1

        ];
    }
}
