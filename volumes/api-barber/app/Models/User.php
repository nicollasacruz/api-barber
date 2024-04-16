<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail, HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'barbershop_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'array',
    ];

    public function hasRole($value): bool
    {
        return in_array($value, $this->role);
    }

    public function barbershop(): BelongsTo
    {
        return $this->belongsTo(Barbershop::class, 'barbershop_id', 'id');
    }

    public function barbershopReceptionist(): HasOne
    {
        return $this->hasOne(Barbershop::class, 'receptionist_id', 'id');
    }

    public function barbershopManaged(): HasOne
    {
        return $this->hasOne(Barbershop::class, 'manager_id', 'id');
    }

    public function clientSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'client_id', 'id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'barber_id', 'id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'barber_service', 'barber_id', 'service_id');
    }

    public function likedImages(): BelongsToMany
    {
        return $this->belongsToMany(FeedImage::class, 'likes_users', 'user_id', 'feed_image_id')->withTimestamps();
    }

    public function feedImages(): HasMany
    {
        return $this->hasMany(FeedImage::class, 'user_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function cashBalances(): HasMany
    {
        return $this->hasMany(CashBalance::class, 'receptionist_id', 'id');
    }
}
