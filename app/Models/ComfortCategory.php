<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComfortCategory extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\ComfortCategoryFactory> */
    use HasFactory;
    protected $table = 'comfort_categories';
    protected $fillable = [
        'name',
    ];
    public function position()
    {
        return $this->hasMany(Positions::class,'position_comfort_category');
    }
}
