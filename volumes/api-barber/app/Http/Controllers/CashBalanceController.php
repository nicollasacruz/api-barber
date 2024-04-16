<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Models\CashBalance;
use App\Services\CashBalanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CashBalanceController extends Controller
{
    protected $cashBalanceService;

    public function __construct(CashBalanceService $cashBalanceService)
    {
        $this->cashBalanceService = $cashBalanceService;
    }
    /**
     * Open cash balance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function openCashBalance(Barbershop $barbershop, Request $request): JsonResponse
    {
        $data = $request->validate([
            'start_balance' => 'required|numeric',
        ]);

        $cashBalance = $this->cashBalanceService->openCashBalance($data, $barbershop);

        return response()->json($cashBalance);
    }

    /**
     * Close cash balance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function closeCashBalance(Barbershop $barbershop, CashBalance $cashBalance, Request $request): JsonResponse
    {
        $validateRequest = Validator::make(
            $request->all(),
            [
                'manager_email' => 'required|email',
                'manager_password' => 'required|string',
                'cash' => 'required|numeric',
                'card' => 'required|numeric',
            ]
        );

        if ($validateRequest->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateRequest->errors()
            ], 401);
        }
        $manager = $barbershop->manager;
        $cashBalance = $this->cashBalanceService->closeCashBalance($request->all(), $barbershop, $cashBalance, $manager);

        return response()->json($cashBalance, 200);
    }

    /**
     * Get cash balance.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCashBalance(Barbershop $barbershop): JsonResponse
    {
        $cashBalance = $this->cashBalanceService->getCashBalanceOpen();

        return response()->json($cashBalance);
    }
}
