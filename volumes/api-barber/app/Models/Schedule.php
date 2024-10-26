<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, Timestamp, SoftDeletes;

    protected $fillable = [
        'amount',
        'status',
        'start_date',
        'end_date',
        'barbershop_id',
        'barber_id',
        'client_id',
        'service_id'
    ];

    protected $casts = [
        'amount' => 'float',
        'status' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function barbershop(): BelongsTo
    {
        return $this->belongsTo(Barbershop::class, 'barbershop_id', 'id');
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'barber_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
