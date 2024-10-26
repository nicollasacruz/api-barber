<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Barbershop;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $barbershop = Barbershop::factory()->create();
        $admin = User::factory()->create([
            'role' => ['user', 'admin'],
        ]);
        $barber = User::factory()->create([
            'role' => ['user', 'barber'],
            'barbershop_id' =>  $barbershop->id,
        ]);
        $receptionist = User::factory()->create([
            'role' => ['user', 'receptionist'],
            'barbershop_id' =>  $barbershop->id,
            'password' => Hash::make('123'),
        ]);
        $manager = User::factory()->create([
            'role' => ['user', 'manager'],
            'barbershop_id' =>  $barbershop->id,
            'password' => Hash::make('123'),
        ]);

        $barbershop->receptionist()->attach($receptionist);
        $barbershop->barber()->attach($barber);
        $barbershop->manager()->attach($manager);
        
        Service::factory(3)->create([
            'barbershop_id' =>  $barbershop->id,
        ]);

        $barber->services()->attach(Service::all());
        User::factory(5)->create();


    }
}
