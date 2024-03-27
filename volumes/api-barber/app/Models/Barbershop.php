<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'mail',
        'address',
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
        // 'icon' => 'string',
        // 'cover_image' => 'string',
        'mail' => 'string',
        'address' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function barbers(): HasMany
    {
        return $this->hasMany(User::class, 'barbershop_id', 'id');
    }

    public function receptionist(): HasOne
    {
        return $this->hasOne(User::class, 'receptionist_id', 'id');
    }

    public function manager(): HasOne
    {
        return $this->hasOne(User::class, 'manager_id', 'id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'barbershop_id', 'id');
    }
}
