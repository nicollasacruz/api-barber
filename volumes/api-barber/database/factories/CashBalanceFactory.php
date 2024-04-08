<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CashBalance;
use App\Models\Manager;
use App\Models\Receptionist;

class CashBalanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashBalance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'receptionist_id' => Receptionist::factory(),
            'manager_id' => Manager::factory(),
            'start_balance' => $this->faker->randomFloat(2, 0, 99999999.99),
            'balance' => $this->faker->randomFloat(2, 0, 99999999.99),
            'final_balance' => $this->faker->randomFloat(2, 0, 99999999.99),
            'cash' => $this->faker->randomFloat(2, 0, 99999999.99),
            'card' => $this->faker->randomFloat(2, 0, 99999999.99),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
        ];
    }
}
