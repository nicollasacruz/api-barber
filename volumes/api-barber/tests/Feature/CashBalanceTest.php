<?php

use Tests\TestCase;
use App\Models\Barbershop;
use App\Models\User;
use App\Services\CashBalanceService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CashBalanceTest extends TestCase
{
    public function testOpenCashBalance()
    {
        $barbershop = Barbershop::factory()->create();

        $receptionist = User::factory()->create([
            'role' => ['user','receptionist'],
        ]);
        $barbershop->receptionist()->associate($receptionist);
        // $barbershop->receptionist()->associate($receptionist);
        $receptionist = User::find($receptionist->id);
        $this->actingAs($receptionist);

        $data = [
            'start_balance' => 1000,
        ];

        $cashBalanceService = new CashBalanceService();
        $result = $cashBalanceService->openCashBalance($data, $barbershop);

        $this->assertTrue($result['status']);
        $this->assertEquals('Cash balance opened successfully', $result['message']);
        $this->assertNotNull($result['data']);
    }

    public function testCloseCashBalance()
    {
        $manager = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $barbershop = Barbershop::factory()->create([
            'manager_id' => $manager->id,
        ]);
        // $manager->barbershop()->associate($barbershop);
        $data = [
            'manager_email' => $manager->email,
            'manager_password' => 'password',
            'cash' => 500,
            'card' => 300,
        ];

        $cashBalanceService = new CashBalanceService();
        $result = $cashBalanceService->closeCashBalance($data, $barbershop);

        $this->assertTrue($result['status']);
        $this->assertEquals('Cash balance closed successfully', $result['message']);
        $this->assertNotNull($result['data']);
    }

    public function testGetCashBalanceOpen()
    {
        $barbershop = Barbershop::factory()->create();

        $receptionist = User::factory()->create([
            'role' => ['user','receptionist'],
        ]);
        $barbershop->receptionist()->associate($receptionist);
        // $barbershop->receptionist()->associate($receptionist);
        $receptionist = User::find($receptionist->id);
        $this->actingAs($receptionist);

        $data = [
            'start_balance' => 1000,
        ];

        $cashBalanceService = new CashBalanceService();
        $cashBalanceService->openCashBalance($data, $barbershop);

        $result = $cashBalanceService->getCashBalanceOpen();

        $this->assertTrue($result['status']);
        $this->assertEquals('Cash balance open today', $result['message']);
        $this->assertNotNull($result['data']);
    }
}