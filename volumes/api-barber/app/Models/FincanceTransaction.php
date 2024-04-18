<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FinanceTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'user_id',
        'cash_balance_id',
        'amount',
        'sale_id',
        'withdrawal_id',
        'cash_ajustment_id',
        'commission_payment_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'cash_balance_id' => 'integer',
        'amount' => 'decimal:2',
        'sale_id' => 'integer',
        'withdrawal_id' => 'integer',
        'cash_ajustment_id' => 'integer',
        'commission_payment_id' => 'integer',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function cashBalance(): BelongsTo
    {
        return $this->belongsTo(CashBalance::class, 'cash_balance_id', 'id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function withdrawal(): BelongsTo
    {
        return $this->belongsTo(Withdrawal::class);
    }

    public function cashAjustment(): BelongsTo
    {
        return $this->belongsTo(CashAjustment::class);
    }

    public function commissionPayment(): BelongsTo
    {
        return $this->belongsTo(CommissionPayment::class);
    }
}
