<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
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
        'finance_transaction_id',
        'price',
        'status',
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
        'finance_transaction_id' => 'integer',
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receptionist(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function financeTransaction(): BelongsTo
    {
        return $this->belongsTo(FinanceTransaction::class);
    }

    public function financeTransaction(): HasOne
    {
        return $this->hasOne(FinanceTransaction::class);
    }

    public function productTransactions(): HasMany
    {
        return $this->hasMany(ProductTransaction::class);
    }
}
