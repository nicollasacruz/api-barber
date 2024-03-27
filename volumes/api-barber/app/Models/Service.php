<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, Timestamp, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'barbershop_id',
    ];

    protected $cast = [
        'name' => 'string',
        'description' => 'text',
        'price' => 'float',
        'duration' => 'integer',
    ];

    public function barbershop(): BelongsTo
    {
        return $this->belongsTo(Barbershop::class, 'barbershop_id', 'id');
    }
    
    public function barbers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function services(): belongsToMany
    {
        return $this->belongsToMany(Schedule::class);
    }
}
