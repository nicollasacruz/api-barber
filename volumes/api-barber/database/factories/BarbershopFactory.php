<?php

namespace Database\Factories;

use App\Models\Barbershop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Barbershop>
 */
class BarbershopFactory extends Factory
{
    protected $model = Barbershop::class;

    /**
     * @param string $name
     * @param string $email
     * @param string $address
     * @param int $manager_id
     * @param int $receptionist_id
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'address' => $this->faker->address,
            'manager_id' => User::factory()->create()->id,
            'receptionist_id' => User::factory()->create()->id,
        ];
    }
}
