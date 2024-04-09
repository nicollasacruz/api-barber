<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FinanceTransaction;
use App\Models\Receptionist;
use App\Models\Sale;
use App\Models\User;

class SaleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sale::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'receptionist_id' => Receptionist::factory(),
            'finance_transaction_id' => FinanceTransaction::factory(),
            'price' => $this->faker->randomFloat(2, 0, 99999999.99),
        ];
    }
}
