<?php

use Tests\TestCase;
use App\Models\Barbershop;
use App\Models\CashBalance;
use App\Models\User;
use App\Services\CashBalanceService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CashBalanceTest extends TestCase
{

    protected $barbershop;
    protected $admin;
    protected $manager;
    protected $receptionist;
    protected $user;
    protected $cashBalance;

    protected function setUp(): void
    {
        parent::setUp();
        $this->barbershop = Barbershop::factory()->create();
        $this->admin = User::factory()->create(['role' => ['user', 'admin']]);
        $this->manager = User::factory()->create(['role' => ['user', 'manager']]);
        $this->receptionist = User::factory()->create(['role' => ['user', 'receptionist']]);
        $this->user = User::factory()->create();
        
        $this->barbershop->receptionist()->associate($this->receptionist);
        $this->barbershop->manager()->associate($this->manager);
    }

    public function testOpenCashBalance()
    {
        $this->barbershop->receptionist()->associate($this->receptionist);

        $this->receptionist = User::find($this->receptionist->id);

        $this->actingAs($this->receptionist);

        $data = [
            'start_balance' => 1000,
        ];

        $result = $this->post('/api/v1/barbershops/' . $this->barbershop->id . '/cash-balance/open', $data, [
            'Accept' => 'application/json',
        ]);

        $this->cashBalance = CashBalance::find($result->json()['data']['id']);

        $this->assertTrue($result['status']);
        $this->assertEquals('Cash balance opened successfully', $result['message']);
        $this->assertNotNull($result['data']);
    }

    public function testCloseCashBalance()
    {
        // $this->manager->barbershop()->associate($this->barbershop);

        $this->receptionist = User::find($this->receptionist->id);

        $this->actingAs($this->receptionist);

        $data = [
            'start_balance' => 1000,
        ];

        $result = $this->post('/api/v1/barbershops/' . $this->barbershop->id . '/cash-balance/open', $data, [
            'Accept' => 'application/json',
        ]);

        $this->cashBalance = CashBalance::find($result->json()['data']['id']);

        $data = [
            'manager_email' => $this->manager->email,
            'manager_password' => 'password',
            'cash' => 5000,
            'card' => 300,
        ];

        $result = $this->post('/api/v1/barbershops/' . $this->barbershop->id . '/cash-balance/' . $this->cashBalance->id .'/close', $data, [
            'Accept' => 'application/json',
        ]);

        $this->assertTrue($result['status']);
        $this->assertEquals('Cash balance closed successfully', $result['message']);
        $this->assertNotNull($result['data']);
    }

    public function testGetCashBalanceOpen()
    {
        $this->barbershop->receptionist()->associate($this->receptionist);
        $this->receptionist = User::find($this->receptionist->id);
        $this->actingAs($this->receptionist);

        $data = [
            'start_balance' => 1000,
        ];

        $cashBalanceService = new CashBalanceService();
        $cashBalanceService->openCashBalance($data, $this->barbershop);

        $result = $this->get('/api/v1/barbershops/' . $this->barbershop->id . '/cash-balance', [
            'Accept' => 'application/json',
        ]);

        $this->assertTrue($result['status']);
        $this->assertEquals('Cash balance open today', $result['message']);
        $this->assertNotNull($result['data']);
    }
}
