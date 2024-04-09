<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FinanceTransaction;
use App\Models\Manager;
use App\Models\Purchase;
use App\Models\Receptionist;

class PurchaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'receptionist_id' => Receptionist::factory(),
            'manager_id' => Manager::factory(),
            'finance_transaction_id' => FinanceTransaction::factory(),
            'price' => $this->faker->randomFloat(2, 0, 99999999.99),
            'status' => $this->faker->boolean(),
        ];
    }
}
