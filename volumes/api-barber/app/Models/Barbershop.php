<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Barbershop extends Model implements HasMedia
{
    use HasFactory, HasTimestamps, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
        'cover_image',
        'email',
        'address',
        'businessHours',
        'receptionist_id',
        'manager_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'address' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'businessHours' => 'array', 
    ];

    public function barbers(): HasMany
    {
        return $this->hasMany(Barber::class);
    }

    public function receptionist(): HasMany
    {
        return $this->hasMany(Receptionist::class);
    }

    public function manager(): HasMany
    {
        return $this->hasMany(Manager::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function cashBalances(): HasMany
    {
        return $this->hasMany(CashBalance::class);
    }
}
    