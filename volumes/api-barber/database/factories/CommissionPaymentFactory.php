<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CommissionPayment;
use App\Models\FinanceTransaction;
use App\Models\User;

class CommissionPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommissionPayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'finance_transaction_id' => FinanceTransaction::factory(),
            'closed_at' => $this->faker->dateTime(),
            'payed_at' => $this->faker->dateTime(),
        ];
    }
}
