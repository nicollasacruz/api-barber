<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CashAjustment;
use App\Models\CashBalance;
use App\Models\User;

class CashAjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashAjustment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
            'reason' => $this->faker->text(),
            'cash_balance_id' => CashBalance::factory(),
        ];
    }
}
