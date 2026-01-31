<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBooking extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\CarBookingFactory> */
    use HasFactory;

    protected $table = 'car_bookings';
    protected $fillable = [
        'car_id',
        'user_id',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
