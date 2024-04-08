<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FincanceTransaction;
use App\Models\User;

class FincanceTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FincanceTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(["commission","product_commission","sale","commission_paid","withdrawal","cash_ajustment"]),
            'user_id' => User::factory(),
            'amount' => $this->faker->randomFloat(2, 0, 99999999.99),
        ];
    }
}
