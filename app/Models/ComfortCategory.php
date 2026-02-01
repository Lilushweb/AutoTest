<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComfortCategory extends Model
{
    /** @use HasFactory<\Database\Factories\App\Models\ComfortCategoryFactory> */
    use HasFactory;
    protected $table = 'comfort_categories';
    protected $fillable = [
        'name',
    ];
    public function position(): BelongsToMany
    {
        return $this->belongsToMany(
            Position::class,
            'position_comfort_category',
            'comfort_category_id',
            'position_id'
        );
    }
}
