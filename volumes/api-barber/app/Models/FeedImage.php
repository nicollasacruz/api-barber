<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class FeedImage extends Model implements HasMedia
{
    use HasFactory, Timestamp, InteractsWithMedia;

    protected $fillable = [
        'subtitle',
        'image',
        'isShow',
        'likes_count',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship with users who liked the image.
     */
    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes_users', 'feed_image_id', 'user_id')->withTimestamps();
    }

    /**
     * Increment likes count.
     */
    public function incrementLikesCount(): void
    {
        $this->increment('likes_count');
    }

    /**
     * Decrement likes count.
     */
    public function decrementLikesCount(): void
    {
        $this->decrement('likes_count');
    }
}
