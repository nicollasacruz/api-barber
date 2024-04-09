<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FinanceTransaction;
use App\Models\ProductCommission;
use App\Models\User;

class ProductCommissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductCommission::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'items_price' => $this->faker->randomFloat(2, 0, 99999999.99),
            'percentage' => $this->faker->numberBetween(-10000, 10000),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'finance_transaction_id' => FinanceTransaction::factory(),
            'payed_at' => $this->faker->dateTime(),
        ];
    }
}
