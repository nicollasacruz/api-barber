<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Plan;
use App\Models\User;

class PlanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Plan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 0, 99999999.99),
            'user_id' => User::factory(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'quantity_used' => $this->faker->numberBetween(-10000, 10000),
            'percentage_discount' => $this->faker->numberBetween(-10000, 10000),
            'valid_at' => $this->faker->dateTime(),
        ];
    }
}
