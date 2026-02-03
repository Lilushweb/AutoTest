<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\CarsFactory> */
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'model',
        'user_id',
        'comfort_category_id',
    ];

    public function comfortCategory(): BelongsTo
    {
        return $this->belongsTo(ComfortCategory::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carBookings(): HasMany
    {
        return $this->hasMany(CarBooking::class, 'car_id');
    }
}
