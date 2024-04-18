<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Barber extends User
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function barbershop(): BelongsTo
    {
        return $this->belongsTo(Barbershop::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'barber_service', 'barber_id', 'service_id');
    }

    public function likedImages(): BelongsToMany
    {
        return $this->belongsToMany(FeedImage::class, 'likes_users', 'user_id', 'feed_image_id')->withTimestamps();
    }

    // You can add more barber-specific relationships or methods here
}
