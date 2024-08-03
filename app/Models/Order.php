<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'delivery_time' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function delayReports(): HasMany
    {
        return $this->hasMany(DelayReport::class);
    }

    public function trip(): HasOne
    {
        return $this->hasOne(Trip::class);
    }

    public function isLate(): Bool
    {
        return $this->delivery_time->lt(Carbon::now());
    }
    
    public function hasOpenDelayReport(): Bool
    {
        return $this->delayReports()->open()->exists();
    }

}
