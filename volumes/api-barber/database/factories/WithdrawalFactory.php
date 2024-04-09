<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FinanceTransaction;
use App\Models\User;
use App\Models\Withdrawal;

class WithdrawalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Withdrawal::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'finance_transaction_id' => FinanceTransaction::factory(),
            'reason' => $this->faker->text(),
        ];
    }
}
