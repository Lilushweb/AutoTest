<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Positions extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\PositionsFactory> */
    use HasFactory;

    protected $table = 'positions';
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function comfortCategories()
    {
        return $this->belongsToMany(
            ComfortCategory::class,
            'position_comfort_category'
        );
    }
}
