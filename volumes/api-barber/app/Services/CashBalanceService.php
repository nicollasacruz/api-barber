<?php

namespace App\Services;

use App\Models\Barbershop;
use App\Models\CashBalance;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class CashBalanceService.
 */
class CashBalanceService
{
    public function openCashBalance($data, Barbershop $barbershop)
    {
        $cashBalance = CashBalance::create([
            'start_date' => now(),
            'status' => 'open',
            'receptionist_id' => auth()->id(),
            'start_balance' => $data['start_balance'],
            'barbershop_id' => $barbershop->id,
        ]);
        return [
            'status' => true,
            'message' => 'Cash balance opened successfully',
            'data' => $cashBalance,
        ];
    }

    public function closeCashBalance($data, Barbershop $barbershop, CashBalance $cashBalance, User $manager)
    {
        $manager = $barbershop->manager;
        if (!$manager || $manager->email != $data['manager_email']) {
            return [
                'status' => false,
                'message' => 'Manager credentials are not correct',
                'data' => false,
            ];
        }
        if (!Hash::check($data['manager_password'], $manager->password)) {
            return [
                'status' => false,
                'message' => 'Manager credentials are not correct',
                'data' => false,
            ];
        }

        $cashBalance->update([
            'final_balance' => $data['cash'] + $data['card'],
            'cash' => $data['cash'],
            'card' => $data['card'],
            'end_date' => now(),
            'status' => 'closed',
            'manager_id' => $manager->id,
        ]);

        return [
            'status' => true,
            'message' => 'Cash balance closed successfully',
            'data' => $cashBalance,
        ];
    }

    public function getCashBalanceOpen()
    {
        $cashBalance = CashBalance::where('status', 'open');

        if ($cashBalance->count() > 1) {
            return [
                'status' => false,
                'message' => 'There are more than one cash balance open',
                'data' => false,
            ];
        }

        if (!$cashBalance->first()->start_date->isToday()) {
            return [
                'status' => false,
                'message' => 'Cash balance is not open today',
                'data' => false,
            ];
        }

        return [
            'status' => true,
            'message' => 'Cash balance open today',
            'data' => $cashBalance->first(),
        ];
    }

}
