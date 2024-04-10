<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CashBalance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'receptionist_id',
        'manager_id',
        'start_balance',
        'balance',
        'final_balance',
        'cash',
        'card',
        'status',
        'start_date',
        'end_date',
        'barbershop_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'receptionist_id' => 'integer',
        'manager_id' => 'integer',
        'start_balance' => 'decimal:2',
        'balance' => 'decimal:2',
        'final_balance' => 'decimal:2',
        'cash' => 'decimal:2',
        'card' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function receptionist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receptionist_id', 'id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function financeTransactions(): HasMany
    {
        return $this->hasMany(FinanceTransaction::class);
    }
}
