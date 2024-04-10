<?php

namespace Tests\Feature;

use App\Models\Barbershop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

// beforeAll(function () {
//     $this->admin = User::factory()->create(['role' => ['user', 'admin']]);
//     $this->manager = User::factory()->create(['role' => ['user', 'manager']]);
//     $this->receptionist = User::factory()->create(['role' => ['user', 'receptionist']]);
//     $this->user = User::factory()->create();
// });

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => ['user', 'admin']]);
    $this->manager = User::factory()->create(['role' => ['user', 'manager']]);
    $this->receptionist = User::factory()->create(['role' => ['user', 'receptionist']]);
    $this->user = User::factory()->create();
});

it('allows admin to store a barbershop', function () {
    $barbershopData = [
        "name" => "Kilback, Huel and Reilly",
        "email" => "josie.christiansen@example.net",
        "address" => "81523 Veronica Port Suite 552 Gulgowskimouth, OK 51954",
        "manager_id" => $this->manager->id,
        "receptionist_id" => $this->receptionist->id,
    ];
    $response = $this->actingAs($this->admin)
        ->postJson('/api/v1/barbershops', $barbershopData);
    $response->assertStatus(200);
});

it('allows manager to update a barbershop they manage', function () {
    $barbershop = Barbershop::factory()->create(
        [
            'manager_id' => $this->manager->id, 'receptionist_id' => $this->receptionist->id
        ]
    );
    $updatedData = [
        'name' => 'Updated Name',
        'address' => $barbershop->address,
        'manager_id' => $this->manager->id,
        'receptionist_id' => $barbershop->receptionist->id,
        'email' => $barbershop->email,
    ];
    $response = $this->actingAs($this->manager)
        ->patchJson("/api/v1/barbershops/{$barbershop->id}", $updatedData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('barbershops', ['id' => $barbershop->id, 'name' => 'Updated Name']);
});

it('blocks manager from updating a barbershop they do not manage', function () {
    $manager = User::factory()->create(['role' => ['user', 'manager']]);
    $barbershop = Barbershop::factory()->create(
        [
            'manager_id' => $this->manager->id, 'receptionist_id' => $this->receptionist->id
            ]
        );
        $updatedData = [
            'name' => 'Updated Names',
            'address' => $barbershop->address,
            'manager_id' => $barbershop->manager->id,
            'receptionist_id' => $barbershop->receptionist->id,
            'email' => $barbershop->email,
        ];
    dump([$manager->name, $manager->id]);
    $response = $this->actingAs($manager)
        ->patchJson("/api/v1/barbershops/{$barbershop->id}", $updatedData);
    $response->assertStatus(403);
});

it('allows admin to delete a barbershop', function () {
    $barbershopData = [
        "name" => "Kilback, Huel and Reilly",
        "email" => "josie.christiansen@example.net",
        "address" => "81523 Veronica Port Suite 552 Gulgowskimouth, OK 51954",
        "manager_id" => $this->manager->id,
        "receptionist_id" => $this->receptionist->id,
    ];
    $barbershop = Barbershop::factory()->create($barbershopData);

    $response = $this->actingAs($this->admin)
        ->deleteJson("/api/v1/barbershops/{$barbershop->id}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('barbershops', $barbershop->toArray());
});

it('blocks manager from deleting a barbershop', function () {
    $manager = User::factory()->create(['id' => 10, 'role' => ['user', 'manager']]);
    $barbershop = Barbershop::factory()->create(['manager_id' => $this->manager->id]);
    $response = $this->actingAs($manager)
        ->deleteJson("/api/v1/barbershops/{$barbershop->id}");
    $response->assertStatus(403);
});

it('blocks receptionist from deleting a barbershop', function () {
    $receptionist = User::factory()->create(['id' => 10, 'role' => ['user', 'receptionist']]);
    $barbershop = Barbershop::factory()->create(['manager_id' => $this->manager->id]);
    $response = $this->actingAs($receptionist)
        ->deleteJson("/api/v1/barbershops/{$barbershop->id}");
    $response->assertStatus(403);
});
