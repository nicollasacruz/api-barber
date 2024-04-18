<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Receptionist extends User
{
    public function barbershops(): BelongsTo
    {
        return $this->belongsTo(Barbershop::class);
    }

    public function cashBalances(): HasMany
    {
        return $this->hasMany(CashBalance::class);
    }

}
