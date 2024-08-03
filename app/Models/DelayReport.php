<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DelayReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'delay_minute',
        'old_delivery_time'
    ];

    public function agent(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeOpen(Builder $query): void
    {
        $query->where('state', '!=', 'reviewed');
    }
}
