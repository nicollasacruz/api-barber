<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends User
{
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

}
