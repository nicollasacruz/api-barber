<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CashAjustment;
use App\Models\CashBalance;
use App\Models\CommissionPayment;
use App\Models\FinanceTransaction;
use App\Models\Sale;
use App\Models\User;
use App\Models\Withdrawal;

class FinanceTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FinanceTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(["sale","commission_payment","withdrawal","cash_ajustment","purchase"]),
            'user_id' => User::factory(),
            'cash_balance_id' => CashBalance::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'sale_id' => Sale::factory(),
            'withdrawal_id' => Withdrawal::factory(),
            'cash_ajustment_id' => CashAjustment::factory(),
            'commission_payment_id' => CommissionPayment::factory(),
        ];
    }
}
